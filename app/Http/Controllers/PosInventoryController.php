<?php

namespace App\Http\Controllers;

use App\Models\PosStockEntry;
use App\Services\PosStockService;
use App\Models\Product;
use Illuminate\Http\Request;

class PosInventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('stocks')
            ->where('published', 1)
            ->where('digital', 0)
            ->when($request->q, function ($builder, $q) {
                $builder->where(function ($query) use ($q) {
                    $query->where('name', 'like', '%' . $q . '%')
                        ->orWhereHas('stocks', function ($stockQuery) use ($q) {
                            $stockQuery->where('sku', 'like', '%' . $q . '%');
                        });
                });
            })
            ->orderBy('name')
            ->paginate(20);

        foreach ($products as $product) {
            $product->total_stock = PosStockService::stock($product->id);
        }

        $entries = PosStockEntry::with('product')->orderBy('id', 'desc')->limit(15)->get();

        return view('backend.pos.inventory.index', compact('products', 'entries'));
    }

    public function adjustmentStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|numeric',
            'note'       => 'required',
        ]);

        $qty = (float) $request->qty;
        if ($qty < 0 && PosStockService::stock((int) $request->product_id) < abs($qty)) {
            flash(translate('Insufficient stock for this adjustment'))->error();
            return back();
        }

        PosStockService::registerEntry((int) $request->product_id, $qty, $request->note);
        flash(translate('Stock adjusted successfully'))->success();
        return back();
    }
}
