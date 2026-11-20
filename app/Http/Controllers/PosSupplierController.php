<?php

namespace App\Http\Controllers;

use App\Models\PosPurchase;
use App\Models\PosPurchaseReturn;
use App\Models\PosSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosSupplierController extends Controller
{
    public function index()
    {
        $suppliers = PosSupplier::orderBy('name')->paginate(20);
        foreach ($suppliers as $supplier) {
            $supplier->due = (float) $supplier->opening_balance
                + (float) PosPurchase::where('supplier_id', $supplier->id)->whereIn('status', ['Pending', 'Received'])->sum(DB::raw('grand_total - paid_amount'))
                - (float) PosPurchaseReturn::where('supplier_id', $supplier->id)->sum(DB::raw('grand_total - paid_amount'));
        }
        return view('backend.pos.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        $count = PosSupplier::count();
        PosSupplier::create([
            'supplier_code'   => 'S-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT),
            'name'            => $request->name,
            'mobile'          => $request->mobile,
            'email'           => $request->email,
            'address'         => $request->address,
            'opening_balance' => max(0, (float) $request->opening_balance),
            'status'          => 1,
        ]);
        flash(translate('Supplier added successfully'))->success();
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        $supplier = PosSupplier::findOrFail($id);
        $supplier->update([
            'name'            => $request->name,
            'mobile'          => $request->mobile,
            'email'           => $request->email,
            'address'         => $request->address,
            'opening_balance' => max(0, (float) $request->opening_balance),
            'status'          => $request->status != null ? 1 : 0,
        ]);
        flash(translate('Supplier updated successfully'))->success();
        return back();
    }

    public function destroy($id)
    {
        PosSupplier::findOrFail($id)->delete();
        flash(translate('Supplier deleted successfully'))->success();
        return back();
    }
}