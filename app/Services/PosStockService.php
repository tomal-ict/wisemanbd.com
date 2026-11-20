<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductStock;

/**
 * Shared inventory service.
 * The single source of stock truth is `product_stocks.qty`
 * (the same table the website decrements on web orders).
 * POS sales, purchases and stock adjustments go through here.
 */
class PosStockService
{
    public static function rows($productId)
    {
        return ProductStock::where('product_id', $productId)->orderByRaw("variant = '' DESC")->get();
    }

    public static function stock($productId)
    {
        return (float) ProductStock::where('product_id', $productId)->sum('qty');
    }

    public static function cost($productId)
    {
        $product = Product::find($productId);
        return $product != null ? (float) $product->pos_cost_price : 0;
    }

    public static function setCost($productId, $cost)
    {
        if ($cost <= 0) {
            return;
        }
        Product::where('id', $productId)->update(['pos_cost_price' => $cost]);
    }

    public static function increment($productId, $qty)
    {
        $qty = (float) $qty;
        if ($qty <= 0) {
            return true;
        }
        $rows = self::rows($productId);
        if (count($rows) > 0) {
            $rows->first()->increment('qty', $qty);
            return true;
        }
        $product = Product::find($productId);
        if ($product == null) {
            return false;
        }
        ProductStock::create([
            'product_id' => $productId,
            'variant'    => '',
            'price'      => $product->unit_price,
            'sku'        => $product->barcode ?: 'P-' . $product->id,
            'qty'        => $qty,
        ]);
        Product::where('id', $productId)->update(['current_stock' => self::stock($productId)]);
        return true;
    }

    /**
     * Decrement stock across variant rows. Returns false when
     * insufficient stock so the caller can abort the transaction.
     */
    public static function decrement($productId, $qty)
    {
        $qty = (float) $qty;
        if ($qty <= 0) {
            return true;
        }
        $rows = self::rows($productId);
        $available = array_sum($rows->pluck('qty')->map(function ($q) { return (float) $q; })->toArray());
        if ($available < $qty) {
            return false;
        }
        $remaining = $qty;
        foreach ($rows as $row) {
            if ($remaining <= 0) {
                break;
            }
            $take = min((float) $row->qty, $remaining);
            $row->decrement('qty', $take);
            $remaining -= $take;
        }
        Product::where('id', $productId)->update(['current_stock' => self::stock($productId)]);
        return true;
    }

    public static function decrementStock($stockId, $qty)
    {
        $qty = (float) $qty;
        if ($qty <= 0) {
            return true;
        }

        $stock = ProductStock::find($stockId);
        if ($stock == null || (float) $stock->qty < $qty) {
            return false;
        }

        $updated = ProductStock::where('id', $stockId)
            ->where('qty', '>=', $qty)
            ->decrement('qty', $qty);

        if (!$updated) {
            return false;
        }

        self::syncCurrentStock($stock->product_id);
        return true;
    }

    public static function incrementStock($stockId, $qty)
    {
        $qty = (float) $qty;
        if ($qty <= 0) {
            return true;
        }

        $stock = ProductStock::find($stockId);
        if ($stock == null) {
            return false;
        }

        $stock->increment('qty', $qty);
        self::syncCurrentStock($stock->product_id);
        return true;
    }

    public static function syncCurrentStock($productId)
    {
        Product::where('id', $productId)->update(['current_stock' => self::stock($productId)]);
    }

    public static function registerEntry($productId, $qty, $note)
    {
        self::increment($productId, $qty);
        return \App\Models\PosStockEntry::create([
            'product_id'  => $productId,
            'entry_date'  => date('Y-m-d'),
            'qty'         => $qty,
            'note'        => $note,
            'created_by'  => auth()->user() != null ? auth()->user()->id : null,
        ]);
    }
}
