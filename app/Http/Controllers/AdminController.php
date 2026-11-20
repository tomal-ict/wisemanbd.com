<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;
use Artisan;
use Cache;
use CoreComponentRepository;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard(Request $request)
    {   
        CoreComponentRepository::initializeCache();
        $root_categories = Category::where('level', 0)->get();

        $cached_graph_data = Cache::remember('cached_graph_data', 86400, function() use ($root_categories){
            $num_of_sale_data = null;
            $qty_data = null;
            foreach ($root_categories as $key => $category){
                $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                $category_ids[] = $category->id;

                $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();
                $qty = 0;
                $sale = 0;
                foreach ($products as $key => $product) {
                    $sale += $product->num_of_sale;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                }
                $qty_data .= $qty.',';
                $num_of_sale_data .= $sale.',';
            }
            $item['num_of_sale_data'] = $num_of_sale_data;
            $item['qty_data'] = $qty_data;

            return $item;
        });

        $today = date('Y-m-d');
        $month_start = date('Y-m-01');

        $today_sales = (float) DB::table('orders')->where('created_at', '>=', $today)->sum('grand_total');
        $today_orders = DB::table('orders')->where('created_at', '>=', $today)->count();
        $month_sales = (float) DB::table('orders')->where('created_at', '>=', $month_start)->sum('grand_total');
        $month_orders = DB::table('orders')->where('created_at', '>=', $month_start)->count();

        $month_profit = (float) DB::table('order_details as od')
            ->join('orders as o', 'o.id', '=', 'od.order_id')
            ->leftJoin('products as p', 'p.id', '=', 'od.product_id')
            ->where('o.delivery_status', 'delivered')
            ->where('o.created_at', '>=', $month_start)
            ->sum(DB::raw('(od.price - COALESCE(p.purchase_price, 0)) * od.quantity'));

        $total_transactions = DB::table('orders')->count();
        $total_delivered = DB::table('orders')->where('delivery_status', 'delivered')->count();
        $total_cancelled = DB::table('orders')->where('delivery_status', 'cancelled')->count();

        $status_counts = DB::table('orders')->selectRaw('delivery_status, COUNT(*) as total')->groupBy('delivery_status')->pluck('total', 'delivery_status');
        $pending_orders = (int) ($status_counts->get('pending') ?? 0);
        $confirmed_orders = (int) ($status_counts->get('confirmed') ?? 0);
        $picked_up_orders = (int) ($status_counts->get('picked_up') ?? 0);
        $on_the_way_orders = (int) ($status_counts->get('on_the_way') ?? 0);

        $sales_series = DB::table('orders')
            ->selectRaw('DATE(created_at) as d, SUM(grand_total) as total, COUNT(*) as cnt')
            ->where('created_at', '>=', date('Y-m-d', strtotime('-29 days')))
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->keyBy('d');

        $last_30_days = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $row = $sales_series->get($d);
            $last_30_days[] = [
                'date'   => $d,
                'sales'  => (float) ($row->total ?? 0),
                'orders' => (int) ($row->cnt ?? 0)
            ];
        }

        $monthly_sales = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = date('Y-m-01', strtotime("-$i months"));
            $end = date('Y-m-01', strtotime('+' . (1 - $i) . ' months'));
            $monthly_sales[] = [
                'month' => date('M y', strtotime($start)),
                'sales' => (float) DB::table('orders')->where('created_at', '>=', $start)->where('created_at', '<', $end)->sum('grand_total')
            ];
        }

        return view('backend.dashboard', compact(
            'root_categories',
            'cached_graph_data',
            'today_sales',
            'today_orders',
            'month_sales',
            'month_orders',
            'month_profit',
            'total_transactions',
            'total_delivered',
            'total_cancelled',
            'pending_orders',
            'confirmed_orders',
            'picked_up_orders',
            'on_the_way_orders',
            'last_30_days',
            'monthly_sales'
        ));
    }

    function clearCache(Request $request)
    {
        Artisan::call('cache:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }
}
