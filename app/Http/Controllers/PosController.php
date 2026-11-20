<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\PosCustomer;
use App\Models\PosHold;
use App\Models\PosHoldItem;
use App\Models\PosReturn;
use App\Models\PosReturnItem;
use App\Models\PosSale;
use App\Models\PosSaleItem;
use App\Models\PosSalePayment;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use App\Services\PosStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    const PAYMENT_METHODS = ['Cash', 'bKash', 'Nagad', 'Rocket', 'Card', 'Bank'];

    public function register()
    {
        $walkInCustomer = $this->walkInCustomer();
        $customers = PosCustomer::where('status', 1)->orderBy('name')->get();
        $stocks = ProductStock::with('product')
            ->whereHas('product', function ($query) {
                $query->where('published', 1)->where('digital', 0);
            })
            ->orderBy('id', 'desc')
            ->paginate(60);
        $holds = PosHold::with('customer', 'items')->orderBy('id', 'desc')->get();
        $categories = Category::where('digital', 0)->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('backend.pos.register', compact('walkInCustomer', 'customers', 'stocks', 'holds', 'categories', 'brands'));
    }

    public function productSearch(Request $request)
    {
        $search = trim((string) $request->get('q'));
        $stocks = ProductStock::with('product')
            ->whereHas('product', function ($query) use ($request) {
                $query->where('published', 1)->where('digital', 0);

                if ($request->category_id) {
                    $query->where('category_id', (int) $request->category_id);
                }

                if ($request->brand_id) {
                    $query->where('brand_id', (int) $request->brand_id);
                }
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($stockQuery) use ($search) {
                    $stockQuery->where('sku', 'like', '%' . $search . '%')
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery->where(function ($productQuery) use ($search) {
                                $productQuery->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('barcode', 'like', '%' . $search . '%');

                                 if (is_numeric($search)) {
                                     $productQuery->orWhere('id', (int) $search);
                                 }

                                 if (preg_match('/^P-(\d+)$/i', $search, $matches)) {
                                     $productQuery->orWhere('id', (int) $matches[1]);
                                 }
                             });
                         });
                 });
            })
            ->orderBy('id', 'desc')
            ->paginate(60);

        return response()->json([
            'products' => $stocks->getCollection()->map(function ($stock) {
                return $this->stockPayload($stock);
            })->values(),
            'next_page' => $stocks->hasMorePages() ? $stocks->currentPage() + 1 : null,
        ]);
    }

    protected function walkInCustomer()
    {
        return PosCustomer::firstOrCreate(
            ['customer_code' => 'WALK-IN'],
            ['name' => translate('Walk-in Customer'), 'status' => 1]
        );
    }

    protected function customerId($customerId)
    {
        $customer = PosCustomer::where('id', (int) $customerId)->where('status', 1)->first();
        return $customer ? $customer->id : $this->walkInCustomer()->id;
    }

    protected function requestItems(Request $request)
    {
        $items = $request->input('items', []);
        if (is_string($items)) {
            $items = json_decode($items, true);
        }

        return is_array($items) ? $items : [];
    }

    protected function stockPayload(ProductStock $stock)
    {
        $product = $stock->product;
        $price = $stock->price !== null ? (float) $stock->price : (float) $product->unit_price;

        return [
            'stock_id'    => (int) $stock->id,
            'product_id'  => (int) $product->id,
            'name'        => $product->getTranslation('name'),
            'variant'     => $stock->variant,
            'sku'         => $stock->sku ?: ($product->barcode ?: 'P-' . $product->id),
            'price'       => $price,
            'stock'       => (float) $stock->qty,
            'image'       => uploaded_asset($stock->image ?: $product->thumbnail_img),
            'category_id' => (int) $product->category_id,
            'brand_id'    => (int) $product->brand_id,
        ];
    }

    protected function nextNumber($prefix, $table)
    {
        $todayCount = DB::table($table)->whereDate('created_at', date('Y-m-d'))->count();
        return $prefix . '-' . date('ymd') . '-' . str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $items = $this->requestItems($request);
        $customer_id = $this->customerId($request->customer_id);
        $payment_method = in_array($request->payment_method, self::PAYMENT_METHODS) ? $request->payment_method : 'Cash';

        if (!is_array($items) || count($items) === 0) {
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => translate('Cart is empty')], 422);
            }
            flash(translate('Cart is empty'))->error();
            return back();
        }

        $stockIds = collect($items)->pluck('stock_id')->filter()->map(function ($id) {
            return (int) $id;
        })->unique()->values();
        $productIds = collect($items)->pluck('product_id')->filter()->map(function ($id) {
            return (int) $id;
        })->unique()->values();
        $stocks = ProductStock::with('product')->whereIn('id', $stockIds)->get()->keyBy('id');
        $products = Product::with('stocks')->whereIn('id', $productIds)->get()->keyBy('id');

        $sale_items = [];
        $subtotal = 0;
        foreach ($items as $item) {
            $stock = $stocks->get((int) ($item['stock_id'] ?? 0));
            $product = $stock ? $stock->product : $products->get((int) ($item['product_id'] ?? 0));
            if ($product == null || $product->digital == 1) {
                continue;
            }

            if ($stock == null) {
                $stock = $product->stocks->where('variant', '')->first() ?: $product->stocks->first();
            }

            $qty = max(0.01, (float) $item['qty']);
            $price = $stock && $stock->price !== null ? max(0, (float) $stock->price) : max(0, (float) $product->unit_price);
            $available = $stock ? (float) $stock->qty : PosStockService::stock($product->id);

            if ($available < $qty) {
                $message = translate('Insufficient stock for') . ' ' . $product->getTranslation('name') . ' (' . translate('available') . ': ' . $available . ')';
                if ($request->expectsJson()) {
                    return response()->json(['ok' => false, 'message' => $message], 422);
                }
                flash($message)->error();
                return back();
            }

            $line_discount = max(0, (float) (isset($item['discount']) ? $item['discount'] : 0));
            $line_total = $price * $qty;
            $line_discount = min($line_discount, $line_total);
            $subtotal += $line_total - $line_discount;

            $sale_items[] = [
                'product_id'     => $product->id,
                'stock_id'       => $stock ? $stock->id : null,
                'name'           => $product->getTranslation('name'),
                'variant'        => $stock ? $stock->variant : null,
                'qty'            => $qty,
                'price'          => $price,
                'discount_amount'=> $line_discount,
                'purchase_price' => PosStockService::cost($product->id),
                'total'          => $line_total - $line_discount,
            ];
        }

        if (count($sale_items) === 0) {
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => translate('Cart is empty')], 422);
            }
            flash(translate('Cart is empty'))->error();
            return back();
        }

        $discount_type = $request->discount_type == 'percent' ? 'percent' : 'flat';
        $discount_input = max(0, (float) $request->discount);
        $discount_amount = $discount_type == 'percent'
            ? min($subtotal, $subtotal * $discount_input / 100)
            : min($subtotal, $discount_input);

        $other_charges = max(0, (float) $request->other_charges);
        $before_round = $subtotal - $discount_amount + $other_charges;
        $grand_total = round($before_round);
        $round_off = $grand_total - $before_round;

        $paid_amount = min($grand_total, max(0, (float) $request->paid_amount));
        $change_return = max(0, (float) $request->paid_amount - $grand_total);

        try {
            DB::beginTransaction();
            $sale = PosSale::create([
                'slip_no'        => $this->nextNumber('PS', 'pos_sales'),
                'sale_date'      => date('Y-m-d'),
                'customer_id'    => $customer_id,
                'discount_type'  => $discount_type,
                'discount_amount'=> $discount_amount,
                'other_charges'  => $other_charges,
                'subtotal'       => $subtotal,
                'round_off'      => $round_off,
                'grand_total'    => $grand_total,
                'paid_amount'    => $paid_amount,
                'change_return'  => $change_return,
                'payment_method' => $payment_method,
                'note'           => $request->note,
                'status'         => 'Final',
                'created_by'     => auth()->user() != null ? auth()->user()->id : null,
            ]);

            foreach ($sale_items as $sale_item) {
                $sale_item['sale_id'] = $sale->id;
                PosSaleItem::create($sale_item);
                $decremented = $sale_item['stock_id']
                    ? PosStockService::decrementStock($sale_item['stock_id'], $sale_item['qty'])
                    : PosStockService::decrement($sale_item['product_id'], $sale_item['qty']);
                if (!$decremented) {
                    throw new \Exception('Stock decrement failed for product #' . $sale_item['product_id']);
                }
            }

            if ($paid_amount > 0) {
                PosSalePayment::create([
                    'sale_id'        => $sale->id,
                    'payment_date'   => date('Y-m-d'),
                    'payment_method' => $payment_method,
                    'amount'         => $paid_amount,
                    'note'           => translate('POS sale payment'),
                    'created_by'     => auth()->user() != null ? auth()->user()->id : null,
                ]);
            }
            if ((int) $request->hold_id > 0) {
                $hold = PosHold::find((int) $request->hold_id);
                if ($hold != null) {
                    $hold->items()->delete();
                    $hold->delete();
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
            }
            flash($e->getMessage())->error();
            return back();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'invoice_url' => route('pos.invoice', $sale->id),
            ]);
        }

        flash(translate('Sale completed successfully') . ' — ' . $sale->slip_no)->success();
        return redirect()->route('pos.invoice', $sale->id);
    }

    public function sales(Request $request)
    {
        $sales = PosSale::with('customer', 'items')
            ->when($request->date, function ($builder, $date) {
                $builder->whereDate('sale_date', $date);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);
        return view('backend.pos.sales', compact('sales'));
    }

    public function invoice($id)
    {
        $sale = PosSale::with('customer', 'items')->findOrFail($id);
        $cashier = $sale->created_by ? User::find($sale->created_by) : null;
        $previous_sales_total = PosSale::where('customer_id', $sale->customer_id)
            ->where('id', '<', $sale->id)
            ->where('status', 'Final')
            ->sum(DB::raw('grand_total - paid_amount'));
        $customer_due = $sale->customer != null
            ? (float) $sale->customer->opening_balance
                + (float) PosSale::where('customer_id', $sale->customer_id)->where('status', 'Final')->sum(DB::raw('grand_total - paid_amount'))
                - (float) \App\Models\PosReturn::where('customer_id', $sale->customer_id)->sum(DB::raw('grand_total - paid_amount'))
            : 0;

        return view('backend.pos.invoice', compact('sale', 'cashier', 'previous_sales_total', 'customer_due'));
    }

    public function destroy($id)
    {
        $sale = PosSale::with('items')->findOrFail($id);
        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $sale_item) {
                if ($sale_item->stock_id) {
                    PosStockService::incrementStock($sale_item->stock_id, $sale_item->qty);
                } else {
                    PosStockService::increment($sale_item->product_id, $sale_item->qty);
                }
            }
            $sale->payments()->delete();
            $sale->items()->delete();
            $sale->delete();
        });
        flash(translate('Sale deleted and stock restored'))->success();
        return back();
    }

    // ---------- Returns ----------

    public function returns()
    {
        $returns = PosReturn::with('customer', 'sale')->orderBy('id', 'desc')->paginate(20);
        return view('backend.pos.returns', compact('returns'));
    }

    public function saleItems(Request $request)
    {
        $sale = PosSale::with('items', 'customer')->findOrFail((int) $request->sale_id);
        $items = [];
        foreach ($sale->items as $item) {
            $returned = (float) PosReturnItem::where('sale_item_id', $item->id)->sum('qty');
            $items[] = [
                'id'       => $item->id,
                'name'     => $item->name,
                'stock_id' => $item->stock_id,
                'variant'  => $item->variant,
                'qty'      => (float) $item->qty,
                'returned' => $returned,
                'price'    => (float) $item->price,
            ];
        }
        return response()->json([
            'sale'  => ['id' => $sale->id, 'slip_no' => $sale->slip_no, 'customer' => $sale->customer ? $sale->customer->name : ''],
            'items' => $items,
        ]);
    }

    public function returnStore(Request $request)
    {
        $sale = PosSale::with('items')->findOrFail((int) $request->sale_id);
        $items = $request->input('items');
        if (!is_array($items) || count($items) === 0) {
            flash(translate('No items selected for return'))->error();
            return back();
        }

        $return_items = [];
        $subtotal = 0;
        foreach ($items as $saleItemId => $item) {
            $sale_item = $sale->items->firstWhere('id', (int) $saleItemId);
            if ($sale_item == null) {
                continue;
            }
            $qty = max(0.01, (float) $item['qty']);
            $returned_before = (float) PosReturnItem::where('sale_item_id', $sale_item->id)->sum('qty');
            if ($returned_before + $qty > $sale_item->qty) {
                flash(translate('Return quantity exceeds sold quantity for') . ' ' . $sale_item->name)->error();
                return back();
            }
            $total = $sale_item->price * $qty * (1 - ($sale_item->discount_amount > 0 && $sale_item->qty > 0 ? $sale_item->discount_amount / ($sale_item->price * $sale_item->qty) : 0));
            $subtotal += $total;
            $return_items[] = [
                'sale_item_id'   => $sale_item->id,
                'product_id'     => $sale_item->product_id,
                'stock_id'       => $sale_item->stock_id,
                'name'           => $sale_item->name,
                'variant'        => $sale_item->variant,
                'qty'            => $qty,
                'price'          => $sale_item->price,
                'purchase_price' => $sale_item->purchase_price,
                'total'          => $total,
            ];
        }

        if (count($return_items) === 0) {
            flash(translate('No valid items to return'))->error();
            return back();
        }

        $grand_total = round($subtotal);
        $paid_amount = min($grand_total, max(0, (float) $request->paid_amount));
        $payment_method = in_array($request->payment_method, self::PAYMENT_METHODS) ? $request->payment_method : 'Cash';
        $return_no = null;

        DB::transaction(function () use ($return_items, $sale, $grand_total, $subtotal, $paid_amount, $payment_method, $request, &$return_no) {
            $return = PosReturn::create([
                'return_no'      => $this->nextNumber('RT', 'pos_returns'),
                'sale_id'        => $sale->id,
                'return_date'    => date('Y-m-d'),
                'customer_id'    => $sale->customer_id,
                'discount_amount'=> 0,
                'subtotal'       => $subtotal,
                'grand_total'    => $grand_total,
                'paid_amount'    => $paid_amount,
                'payment_method' => $payment_method,
                'note'           => $request->note,
                'status'         => 'Accepted',
                'created_by'     => auth()->user() != null ? auth()->user()->id : null,
            ]);
            foreach ($return_items as $return_item) {
                $return_item['return_id'] = $return->id;
                PosReturnItem::create($return_item);
                $restored = $return_item['stock_id']
                    ? PosStockService::incrementStock($return_item['stock_id'], $return_item['qty'])
                    : PosStockService::increment($return_item['product_id'], $return_item['qty']);
                if (!$restored) {
                    throw new \RuntimeException('Stock restoration failed for product #' . $return_item['product_id']);
                }
            }
            $return_no = $return->return_no;
        });

        flash(translate('Return processed successfully') . ' — ' . $return_no)->success();
        return back();
    }

    public function returnDestroy($id)
    {
        $return = PosReturn::with('items')->findOrFail($id);
        DB::transaction(function () use ($return) {
            foreach ($return->items as $return_item) {
                if ($return_item->stock_id) {
                    PosStockService::decrementStock($return_item->stock_id, $return_item->qty);
                } else {
                    PosStockService::decrement($return_item->product_id, $return_item->qty);
                }
            }
            $return->items()->delete();
            $return->delete();
        });
        flash(translate('Return deleted and stock restored'))->success();
        return back();
    }

    // ---------- Holds ----------

    public function holds()
    {
        $holds = PosHold::with('customer', 'items')->orderBy('id', 'desc')->get();
        $stockIds = $holds->flatMap(function ($hold) {
            return $hold->items->pluck('stock_id');
        })->filter()->unique();
        $availableStocks = ProductStock::whereIn('id', $stockIds)->pluck('qty', 'id');

        foreach ($holds as $hold) {
            foreach ($hold->items as $item) {
                $item->setAttribute('available_stock', (float) $availableStocks->get($item->stock_id, 0));
            }
        }

        return response()->json(['holds' => $holds]);
    }

    public function holdStore(Request $request)
    {
        $items = $this->requestItems($request);
        if (!is_array($items) || count($items) === 0) {
            return response()->json(['ok' => false, 'message' => translate('Cart is empty')], 422);
        }

        $stockIds = collect($items)->pluck('stock_id')->filter()->map(function ($id) {
            return (int) $id;
        })->unique()->values();
        $stocks = ProductStock::with('product')->whereIn('id', $stockIds)->get()->keyBy('id');
        $holdItems = [];
        $subtotal = 0;
        foreach ($items as $item) {
            $stock = $stocks->get((int) ($item['stock_id'] ?? 0));
            if ($stock == null || $stock->product == null) {
                continue;
            }
            $product = $stock->product;
            $qty = max(0.01, (float) ($item['qty'] ?? 0));
            if ((float) $stock->qty < $qty) {
                return response()->json([
                    'ok' => false,
                    'message' => translate('Insufficient stock for') . ' ' . $product->getTranslation('name'),
                ], 422);
            }
            $price = $stock->price !== null ? max(0, (float) $stock->price) : max(0, (float) $product->unit_price);
            $subtotal += $price * $qty;
            $holdItems[] = [
                'product_id' => $product->id,
                'stock_id'   => $stock->id,
                'name'       => $product->getTranslation('name'),
                'variant'    => $stock->variant,
                'qty'        => $qty,
                'price'      => $price,
            ];
        }

        if (count($holdItems) === 0) {
            return response()->json(['ok' => false, 'message' => translate('Cart is empty')], 422);
        }

        $discount_amount = min($subtotal, max(0, (float) $request->discount));
        $grand_total = round($subtotal - $discount_amount);
        $customerId = $this->customerId($request->customer_id);
        $holdId = null;

        DB::transaction(function () use ($request, $holdItems, $customerId, $subtotal, $discount_amount, $grand_total, &$holdId) {
            $hold = PosHold::create([
                'hold_no'        => $this->nextNumber('HD', 'pos_holds'),
                'customer_id'    => $customerId,
                'discount_amount'=> $discount_amount,
                'subtotal'       => $subtotal,
                'grand_total'    => $grand_total,
                'note'           => $request->note,
                'created_by'     => auth()->user() != null ? auth()->user()->id : null,
            ]);
            foreach ($holdItems as $item) {
                PosHoldItem::create([
                    'hold_id'        => $hold->id,
                    'product_id'     => $item['product_id'],
                    'stock_id'       => $item['stock_id'],
                    'name'           => $item['name'],
                    'variant'        => $item['variant'],
                    'qty'            => $item['qty'],
                    'price'          => $item['price'],
                    'discount_amount'=> 0,
                    'total'          => $item['price'] * $item['qty'],
                ]);
            }
            $holdId = $hold->id;
        });

        return response()->json(['ok' => true, 'hold_id' => $holdId]);
    }

    public function holdDestroy($id)
    {
        $hold = PosHold::findOrFail($id);
        $hold->items()->delete();
        $hold->delete();
        flash(translate('Hold cleared'))->success();
        return back();
    }
}
