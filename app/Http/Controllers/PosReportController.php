<?php

namespace App\Http\Controllers;

use App\Models\PosCustomer;
use App\Models\PosExpense;
use App\Models\PosPurchase;
use App\Models\PosPurchaseReturn;
use App\Models\PosReturn;
use App\Models\PosSale;
use App\Models\PosSaleItem;
use App\Models\PosSupplier;
use App\Services\PosStockService;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosReportController extends Controller
{
    protected function range(Request $request)
    {
        $from = $request->from ?: date('Y-m-d');
        $to = $request->to ?: date('Y-m-d');
        return [$from, $to];
    }

    public function profitLoss(Request $request)
    {
        list($from, $to) = $this->range($request);

        $sale_ids = PosSale::where('status', 'Final')->whereBetween('sale_date', [$from, $to])->pluck('id');
        $sales_total = (float) PosSale::whereIn('id', $sale_ids)->sum('grand_total');
        $sales_count = count($sale_ids);
        $discounts = (float) PosSale::whereIn('id', $sale_ids)->sum('discount_amount');
        $cogs = (float) PosSaleItem::whereIn('sale_id', $sale_ids)->sum(DB::raw('purchase_price * qty'));

        $returns_total = (float) PosReturn::whereBetween('return_date', [$from, $to])->sum('grand_total');
        $returns_cogs = (float) DB::table('pos_return_items')
            ->join('pos_returns', 'pos_returns.id', '=', 'pos_return_items.return_id')
            ->whereBetween('pos_returns.return_date', [$from, $to])
            ->sum(DB::raw('pos_return_items.purchase_price * pos_return_items.qty'));

        $expenses = (float) PosExpense::whereBetween('expense_date', [$from, $to])->sum('amount');

        $purchases_total = (float) PosPurchase::whereIn('status', ['Pending', 'Received'])->whereBetween('purchase_date', [$from, $to])->sum('grand_total');
        $purchase_returns_total = (float) PosPurchaseReturn::whereBetween('return_date', [$from, $to])->sum('grand_total');

        $gross_profit = ($sales_total - $returns_total) - ($cogs - $returns_cogs);
        $net_profit = $gross_profit - $expenses;

        $stock_value = (float) DB::table('product_stocks')->sum(DB::raw('qty * price'));
        $stock_cost_value = (float) DB::table('product_stocks')
            ->join('products', 'products.id', '=', 'product_stocks.product_id')
            ->where('products.digital', 0)
            ->sum(DB::raw('product_stocks.qty * COALESCE(products.pos_cost_price, 0)'));
        $items_qty = (float) DB::table('product_stocks')->sum('qty');
        $items_count = DB::table('product_stocks')->where('qty', '>', 0)->count();

        return view('backend.pos.reports.profit_loss', compact(
            'from', 'to', 'sales_total', 'sales_count', 'discounts', 'cogs',
            'returns_total', 'returns_cogs', 'expenses', 'gross_profit', 'net_profit',
            'purchases_total', 'purchase_returns_total', 'stock_value', 'stock_cost_value',
            'items_qty', 'items_count'
        ));
    }

    public function dues()
    {
        $customers = PosCustomer::orderBy('name')->get();
        foreach ($customers as $customer) {
            $customer->due = (float) $customer->opening_balance
                + (float) PosSale::where('customer_id', $customer->id)->where('status', 'Final')->sum(DB::raw('grand_total - paid_amount'))
                - (float) PosReturn::where('customer_id', $customer->id)->sum(DB::raw('grand_total - paid_amount'));
            $customer->total_sales = (float) PosSale::where('customer_id', $customer->id)->where('status', 'Final')->sum('grand_total');
        }

        $suppliers = PosSupplier::orderBy('name')->get();
        foreach ($suppliers as $supplier) {
            $supplier->due = (float) $supplier->opening_balance
                + (float) PosPurchase::where('supplier_id', $supplier->id)->whereIn('status', ['Pending', 'Received'])->sum(DB::raw('grand_total - paid_amount'))
                - (float) PosPurchaseReturn::where('supplier_id', $supplier->id)->sum(DB::raw('grand_total - paid_amount'));
            $supplier->total_purchases = (float) PosPurchase::where('supplier_id', $supplier->id)->whereIn('status', ['Pending', 'Received'])->sum('grand_total');
        }

        $total_customer_due = 0;
        $total_supplier_due = 0;
        foreach ($customers as $customer) {
            $total_customer_due += $customer->due > 0 ? $customer->due : 0;
        }
        foreach ($suppliers as $supplier) {
            $total_supplier_due += $supplier->due > 0 ? $supplier->due : 0;
        }

        return view('backend.pos.reports.dues', compact(
            'customers', 'suppliers', 'total_customer_due', 'total_supplier_due'
        ));
    }

    public function index()
    {
        $today = date('Y-m-d');
        $sale_ids = PosSale::where('status', 'Final')->whereDate('sale_date', $today)->pluck('id');
        $today_sales = (float) PosSale::whereIn('id', $sale_ids)->sum('grand_total');
        $today_sales_count = count($sale_ids);
        $today_profit = (float) PosSale::whereIn('id', $sale_ids)->sum('grand_total')
            - (float) PosSaleItem::whereIn('sale_id', $sale_ids)->sum(DB::raw('purchase_price * qty'));
        $today_expenses = (float) PosExpense::whereDate('expense_date', $today)->sum('amount');
        $today_profit -= $today_expenses;

        $low_stock = Product::with('stocks')->where('published', 1)->where('digital', 0)->get()
            ->filter(function ($product) {
                if ($product->alert_qty == null || $product->alert_qty <= 0) {
                    return false;
                }
                return PosStockService::stock($product->id) <= $product->alert_qty;
            })
            ->take(10)->values();

        return view('backend.pos.reports.dashboard', compact(
            'today_sales', 'today_sales_count', 'today_profit', 'today_expenses', 'low_stock'
        ));
    }
}