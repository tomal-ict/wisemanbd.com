<?php

namespace App\Http\Controllers;

use App\Models\PosCustomer;
use App\Models\PosReturn;
use App\Models\PosSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosCustomerController extends Controller
{
    public function index()
    {
        $customers = PosCustomer::orderBy('name')->paginate(20);
        foreach ($customers as $customer) {
            $customer->due = $this->due($customer->id);
        }
        return view('backend.pos.customers.index', compact('customers'));
    }

    protected function due($customerId)
    {
        $customer = PosCustomer::find($customerId);
        if ($customer == null) {
            return 0;
        }
        return (float) $customer->opening_balance
            + (float) PosSale::where('customer_id', $customerId)->where('status', 'Final')->sum(DB::raw('grand_total - paid_amount'))
            - (float) PosReturn::where('customer_id', $customerId)->sum(DB::raw('grand_total - paid_amount'));
    }

    protected function paid($customerId)
    {
        return (float) PosSale::where('customer_id', $customerId)->where('status', 'Final')->sum('paid_amount')
            + (float) PosReturn::where('customer_id', $customerId)->sum('paid_amount');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        $count = PosCustomer::count();
        $customer = PosCustomer::create([
            'customer_code'   => 'C-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT),
            'name'            => $request->name,
            'mobile'          => $request->mobile,
            'email'           => $request->email,
            'address'         => $request->address,
            'opening_balance' => max(0, (float) $request->opening_balance),
            'status'          => 1,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'mobile' => $customer->mobile,
                ],
            ], 201);
        }

        flash(translate('Customer added successfully'))->success();
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        $customer = PosCustomer::findOrFail($id);
        $customer->update([
            'name'            => $request->name,
            'mobile'          => $request->mobile,
            'email'           => $request->email,
            'address'         => $request->address,
            'opening_balance' => max(0, (float) $request->opening_balance),
            'status'          => $request->status != null ? 1 : 0,
        ]);
        flash(translate('Customer updated successfully'))->success();
        return back();
    }

    public function destroy($id)
    {
        PosCustomer::findOrFail($id)->delete();
        flash(translate('Customer deleted successfully'))->success();
        return back();
    }

    public function ledger($id)
    {
        $customer = PosCustomer::with('sales', 'sales.items')->findOrFail($id);
        $sales = PosSale::with('items')->where('customer_id', $id)->orderBy('id', 'desc')->get();
        $returns = PosReturn::where('customer_id', $id)->orderBy('id', 'desc')->get();
        $due = $this->due($id);
        $total_paid = $this->paid($id);

        return view('backend.pos.customers.ledger', compact('customer', 'sales', 'returns', 'due', 'total_paid'));
    }
}
