<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\PosCustomer;
use App\Models\PosPurchase;
use App\Models\PosPurchaseItem;
use App\Models\PosPurchasePayment;
use App\Models\PosPurchaseReturn;
use App\Models\PosPurchaseReturnItem;
use App\Models\PosSupplier;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SizeStock;
use App\Services\PosStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosPurchaseController extends Controller
{
    const PAYMENT_METHODS = ['Cash', 'bKash', 'Nagad', 'Rocket', 'Card', 'Bank'];

    protected function nextNumber($prefix)
    {
        $count = DB::table('pos_purchases')->whereDate('created_at', date('Y-m-d'))->count();
        return $prefix . '-' . date('ymd') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function index(Request $request)
    {
        $purchases = PosPurchase::with('supplier', 'items')
            ->when($request->status, function ($builder, $status) {
                $builder->where('status', $status);
            })
            ->when($request->date, function ($builder, $date) {
                $builder->whereDate('purchase_date', $date);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);
        $suppliers = PosSupplier::where('status', 1)->orderBy('name')->get();

        return view('backend.pos.purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = PosSupplier::where('status', 1)->orderBy('name')->get();

        return view('backend.pos.purchases.create', compact('suppliers'));
    }

    /**
     * Product suggestions for the purchase form.
     * Returns each matching product with its color/size options so the
     * front-end can let the user pick a specific variant to restock.
     */
    public function productSearch(Request $request)
    {
        $search = trim((string) $request->get('q'));

        $products = Product::with('sizeStocks')
            ->where('published', 1)
            ->where('digital', 0)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('barcode', 'like', '%' . $search . '%')
                        ->orWhere('id', (int) $search)
                        ->orWhereHas('stocks', function ($stockQuery) use ($search) {
                            $stockQuery->where('sku', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->limit(25)
            ->get();

        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'product_id' => (int) $product->id,
                    'name'       => $product->getTranslation('name'),
                    'barcode'    => $product->barcode ?: ('P-' . $product->id),
                    'image'      => uploaded_asset($product->thumbnail_img),
                    'unit_price' => (float) $product->unit_price,
                    'cost'       => (float) $product->pos_cost_price,
                    'has_variant'=> $this->productHasVariant($product),
                    'colors'     => $this->productColors($product),
                    'sizes'      => $this->productSizes($product),
                ];
            })->values(),
        ]);
    }

    protected function productHasVariant($product)
    {
        $colors = $this->productColors($product);
        $sizes = $this->productSizes($product);
        return count($colors) > 0 || count($sizes) > 0;
    }

    protected function productColors($product)
    {
        $out = [];
        $codes = ($product->colors != null && is_array(json_decode($product->colors, true)))
            ? json_decode($product->colors, true) : [];
        foreach ($codes as $code) {
            $color = Color::where('code', $code)->first();
            $out[] = [
                'code' => $code,
                'name' => $color != null ? $color->name : $code,
            ];
        }
        return $out;
    }

    protected function productSizes($product)
    {
        return ($product->sizes != null && is_array(json_decode($product->sizes, true)))
            ? json_decode($product->sizes, true) : [];
    }

    /**
     * Build the ProductStock variant string following the app convention:
     * color-name + '-' + size, or color only, or size only, or ''.
     */
    protected function variantString($color, $size)
    {
        $color = trim((string) $color);
        $size = trim((string) $size);
        if ($color !== '' && $size !== '') {
            return $color . '-' . $size;
        }
        return $color !== '' ? $color : $size;
    }

    /**
     * Increment the stock of a specific color/size variant on both the
     * ProductStock (source of truth) and SizeStock (web-facing) tables.
     */
    protected function incrementVariantStock($productId, $color, $size, $qty, $unitCost)
    {
        $variant = $this->variantString($color, $size);

        $productStock = ProductStock::where('product_id', $productId)->where('variant', $variant)->first();
        if ($productStock === null) {
            $product = Product::find($productId);
            $productStock = new ProductStock;
            $productStock->product_id = $productId;
            $productStock->variant = $variant;
            $productStock->price = $product != null ? $product->unit_price : 0;
            $productStock->sku = $variant !== '' ? $variant : ($product != null ? ($product->barcode ?: 'P-' . $product->id) : '');
            $productStock->qty = 0;
            $productStock->save();
        }
        $productStock->increment('qty', $qty);

        $sizeStock = SizeStock::where('product_id', $productId)
            ->where('color', trim((string) $color))
            ->where('size', trim((string) $size))
            ->first();
        if ($sizeStock === null) {
            $product = Product::find($productId);
            $sizeStock = new SizeStock;
            $sizeStock->product_id = $productId;
            $sizeStock->color = trim((string) $color);
            $sizeStock->size = trim((string) $size);
            $sizeStock->variant = $variant;
            $sizeStock->price = $product != null ? $product->unit_price : 0;
            $sizeStock->save();
        }
        $sizeStock->increment('qty', $qty);

        if ($unitCost > 0) {
            PosStockService::setCost($productId, $unitCost);
        }
        PosStockService::syncCurrentStock($productId);

        return true;
    }

    protected function decrementVariantStock($productId, $color, $size, $qty)
    {
        $variant = $this->variantString($color, $size);

        $productStock = ProductStock::where('product_id', $productId)->where('variant', $variant)->first();
        if ($productStock !== null) {
            $productStock->decrement('qty', $qty);
        }

        $sizeStock = SizeStock::where('product_id', $productId)
            ->where('color', trim((string) $color))
            ->where('size', trim((string) $size))
            ->first();
        if ($sizeStock !== null) {
            $sizeStock->decrement('qty', $qty);
        }

        PosStockService::syncCurrentStock($productId);
        return true;
    }

    public function store(Request $request)
    {
        $items = $request->input('items');
        if (!is_array($items) || count($items) === 0) {
            flash(translate('Add at least one product'))->error();
            return back();
        }

        $purchase_items = [];
        $subtotal = 0;
        foreach ($items as $item) {
            $product = Product::find((int) $item['product_id']);
            if ($product == null) {
                continue;
            }
            $qty = max(0.01, (float) $item['qty']);
            $unit_cost = max(0, (float) $item['unit_cost']);
            $total = $qty * $unit_cost;
            $subtotal += $total;
            $purchase_items[] = [
                'product_id' => $product->id,
                'name'       => $product->getTranslation('name'),
                'variant'    => isset($item['size']) ? $this->variantString($item['color'] ?? '', $item['size']) : ($item['variant'] ?? ''),
                'color'      => $item['color'] ?? '',
                'size'       => $item['size'] ?? '',
                'qty'        => $qty,
                'unit_cost'  => $unit_cost,
                'discount_amount' => 0,
                'total'      => $total,
            ];
        }
        if (count($purchase_items) === 0) {
            flash(translate('Add at least one product'))->error();
            return back();
        }

        $status = in_array($request->status, ['Pending', 'Received', 'Cancelled']) ? $request->status : 'Pending';
        $other_charges = max(0, (float) $request->other_charges);
        $discount_amount = min($subtotal, max(0, (float) $request->discount_amount));
        $before_round = $subtotal - $discount_amount + $other_charges;
        $grand_total = round($before_round);
        $round_off = $grand_total - $before_round;
        $paid_amount = min($grand_total, max(0, (float) $request->paid_amount));
        $payment_method = in_array($request->payment_method, self::PAYMENT_METHODS) ? $request->payment_method : 'Cash';

        DB::beginTransaction();
        try {
            $purchase = PosPurchase::create([
                'purchase_no'    => $this->nextNumber('PP'),
                'purchase_date'  => $request->purchase_date ?: date('Y-m-d'),
                'supplier_id'    => (int) $request->supplier_id ?: 1,
                'discount_type'  => 'flat',
                'discount_amount' => $discount_amount,
                'other_charges'  => $other_charges,
                'subtotal'       => $subtotal,
                'round_off'      => $round_off,
                'grand_total'    => $grand_total,
                'paid_amount'    => $paid_amount,
                'payment_method' => $payment_method,
                'note'           => $request->note,
                'status'         => $status,
                'created_by'     => auth()->user() != null ? auth()->user()->id : null,
            ]);

            foreach ($purchase_items as $purchase_item) {
                $purchase_item['purchase_id'] = $purchase->id;
                PosPurchaseItem::create($purchase_item);
                if ($status == 'Received') {
                    $hasVariant = ($purchase_item['color'] ?? '') !== '' || ($purchase_item['size'] ?? '') !== '';
                    if ($hasVariant) {
                        $this->incrementVariantStock(
                            $purchase_item['product_id'],
                            $purchase_item['color'] ?? '',
                            $purchase_item['size'] ?? '',
                            $purchase_item['qty'],
                            $purchase_item['unit_cost']
                        );
                    } else {
                        PosStockService::increment($purchase_item['product_id'], $purchase_item['qty']);
                        PosStockService::setCost($purchase_item['product_id'], $purchase_item['unit_cost']);
                    }
                }
            }

            if ($paid_amount > 0) {
                PosPurchasePayment::create([
                    'purchase_id'    => $purchase->id,
                    'payment_date'   => date('Y-m-d'),
                    'payment_method' => $payment_method,
                    'amount'         => $paid_amount,
                    'note'           => translate('Purchase payment'),
                    'created_by'     => auth()->user() != null ? auth()->user()->id : null,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            flash($e->getMessage())->error();
            return back();
        }

        flash(translate('Purchase saved successfully') . ' — ' . $purchase->purchase_no)->success();
        return redirect()->route('pos.purchases.index');
    }

    public function show($id)
    {
        $purchase = PosPurchase::with('supplier', 'items', 'payments')->findOrFail($id);
        $supplier_due = $this->supplierDue($purchase->supplier_id);
        return view('backend.pos.purchases.invoice', compact('purchase', 'supplier_due'));
    }

    public function addPayment(Request $request)
    {
        $purchase = PosPurchase::findOrFail((int) $request->purchase_id);
        $amount = min($purchase->grand_total - $purchase->paid_amount, max(0, (float) $request->amount));
        if ($amount <= 0) {
            flash(translate('Nothing to pay'))->error();
            return back();
        }
        $payment_method = in_array($request->payment_method, self::PAYMENT_METHODS) ? $request->payment_method : 'Cash';
        PosPurchasePayment::create([
            'purchase_id'    => $purchase->id,
            'payment_date'   => date('Y-m-d'),
            'payment_method' => $payment_method,
            'amount'         => $amount,
            'note'           => $request->note,
            'created_by'     => auth()->user() != null ? auth()->user()->id : null,
        ]);
        $purchase->paid_amount += $amount;
        $purchase->save();

        flash(translate('Payment recorded'))->success();
        return back();
    }

    public function destroy($id)
    {
        $purchase = PosPurchase::with('items', 'payments')->findOrFail($id);
        DB::transaction(function () use ($purchase) {
            if ($purchase->status == 'Received') {
                foreach ($purchase->items as $purchase_item) {
                    $hasVariant = ($purchase_item->color ?? '') !== '' || ($purchase_item->size ?? '') !== '';
                    if ($hasVariant) {
                        $this->decrementVariantStock($purchase_item->product_id, $purchase_item->color ?? '', $purchase_item->size ?? '', $purchase_item->qty);
                    } else {
                        PosStockService::decrement($purchase_item->product_id, $purchase_item->qty);
                    }
                }
            }
            $purchase->payments()->delete();
            $purchase->items()->delete();
            $purchase->delete();
        });
        flash(translate('Purchase deleted'))->success();
        return back();
    }

    // ---------- Purchase returns ----------

    public function returnStore(Request $request)
    {
        $purchase = PosPurchase::with('items')->findOrFail((int) $request->purchase_id);
        $items = $request->input('items');
        if (!is_array($items) || count($items) === 0) {
            flash(translate('No items selected for return'))->error();
            return back();
        }

        $return_items = [];
        $subtotal = 0;
        foreach ($items as $item) {
            $purchase_item = $purchase->items->firstWhere('id', (int) $item['purchase_item_id']);
            if ($purchase_item == null) {
                continue;
            }
            $qty = max(0.01, (float) $item['qty']);
            $returned_before = (float) PosPurchaseReturnItem::where('purchase_item_id', $purchase_item->id)->sum('qty');
            if ($returned_before + $qty > $purchase_item->qty) {
                flash(translate('Return quantity exceeds purchased quantity for') . ' ' . $purchase_item->name)->error();
                return back();
            }
            $total = $purchase_item->unit_cost * $qty;
            $subtotal += $total;
            $return_items[] = [
                'purchase_item_id'  => $purchase_item->id,
                'product_id'        => $purchase_item->product_id,
                'name'              => $purchase_item->name,
                'qty'               => $qty,
                'unit_cost'         => $purchase_item->unit_cost,
                'total'             => $total,
            ];
        }
        if (count($return_items) === 0) {
            flash(translate('No valid items to return'))->error();
            return back();
        }

        $grand_total = round($subtotal);
        $paid_amount = min($grand_total, max(0, (float) $request->paid_amount));
        $payment_method = in_array($request->payment_method, self::PAYMENT_METHODS) ? $request->payment_method : 'Cash';

        DB::transaction(function () use ($return_items, $purchase, $grand_total, $subtotal, $paid_amount, $payment_method, $request) {
            $return = PosPurchaseReturn::create([
                'return_no'      => 'PR-' . date('ymd') . '-' . str_pad(DB::table('pos_purchase_returns')->whereDate('created_at', date('Y-m-d'))->count() + 1, 4, '0', STR_PAD_LEFT),
                'purchase_id'    => $purchase->id,
                'return_date'    => date('Y-m-d'),
                'supplier_id'    => $purchase->supplier_id,
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
                $return_item['purchase_return_id'] = $return->id;
                PosPurchaseReturnItem::create($return_item);
                PosStockService::decrement($return_item['product_id'], $return_item['qty']);
            }
        });

        flash(translate('Purchase return processed successfully'))->success();
        return back();
    }

    protected function supplierDue($supplierId)
    {
        $supplier = PosSupplier::find($supplierId);
        if ($supplier == null) {
            return 0;
        }
        return (float) $supplier->opening_balance
            + (float) PosPurchase::where('supplier_id', $supplierId)->whereIn('status', ['Pending', 'Received'])->sum(DB::raw('grand_total - paid_amount'))
            - (float) PosPurchaseReturn::where('supplier_id', $supplierId)->sum(DB::raw('grand_total - paid_amount'));
    }
}
