<?php

namespace App\Http\Controllers;

use App\Models\PosPurchase;
use App\Models\PosPurchaseReturn;
use App\Models\PosSupplier;
use App\Models\PosSupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosSupplierPaymentController extends Controller
{
    const PAYMENT_METHODS = ['Cash', 'bKash', 'Nagad', 'Rocket', 'Card', 'Bank'];

    public function index(Request $request)
    {
        $payments = PosSupplierPayment::with('supplier')
            ->when($request->supplier_id, function ($builder, $supplierId) {
                $builder->where('supplier_id', $supplierId);
            })
            ->when($request->date, function ($builder, $date) {
                $builder->whereDate('payment_date', $date);
            })
            ->orderBy('payment_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        $suppliers = PosSupplier::where('status', 1)->orderBy('name')->get();
        $total = PosSupplierPayment::query()
            ->when($request->supplier_id, function ($builder, $supplierId) {
                $builder->where('supplier_id', $supplierId);
            })
            ->when($request->date, function ($builder, $date) {
                $builder->whereDate('payment_date', $date);
            })
            ->sum('amount');

        $supplierDues = [];
        foreach ($suppliers as $supplier) {
            $dues = (float) $supplier->opening_balance
                + (float) PosPurchase::where('supplier_id', $supplier->id)->whereIn('status', ['Pending', 'Received'])->sum(DB::raw('grand_total - paid_amount'))
                - (float) PosPurchaseReturn::where('supplier_id', $supplier->id)->sum(DB::raw('grand_total - paid_amount'))
                - (float) PosSupplierPayment::where('supplier_id', $supplier->id)->sum('amount');
            $supplierDues[$supplier->id] = max(0, $dues);
        }

        return view('backend.pos.supplier-payments.index', compact('payments', 'suppliers', 'total', 'supplierDues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|integer',
            'amount'      => 'required|numeric|min:1',
            'payment_date'=> 'nullable|date',
        ]);

        $count = DB::table('pos_supplier_payments')->whereDate('created_at', date('Y-m-d'))->count();
        PosSupplierPayment::create([
            'payment_no'    => 'SP-' . date('ymd') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT),
            'payment_date'  => $request->payment_date ?: date('Y-m-d'),
            'supplier_id'   => (int) $request->supplier_id,
            'payment_method'=> in_array($request->payment_method, self::PAYMENT_METHODS) ? $request->payment_method : 'Cash',
            'amount'        => (float) $request->amount,
            'note'          => $request->note,
            'created_by'    => auth()->user() != null ? auth()->user()->id : null,
        ]);

        flash(translate('Payment added successfully'))->success();
        return back();
    }

    public function destroy($id)
    {
        PosSupplierPayment::findOrFail($id)->delete();
        flash(translate('Payment deleted successfully'))->success();
        return back();
    }
}
