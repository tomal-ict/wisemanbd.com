<?php

namespace App\Http\Controllers;

use App\Models\PosExpense;
use App\Models\PosExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = PosExpense::with('category')
            ->when($request->category_id, function ($builder, $categoryId) {
                $builder->where('category_id', $categoryId);
            })
            ->when($request->date, function ($builder, $date) {
                $builder->whereDate('expense_date', $date);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);
        $categories = PosExpenseCategory::where('status', 1)->orderBy('name')->get();
        $total = PosExpense::sum('amount');

        return view('backend.pos.expenses.index', compact('expenses', 'categories', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_for' => 'required',
            'amount'      => 'required|numeric|min:0',
        ]);

        $count = DB::table('pos_expenses')->whereDate('created_at', date('Y-m-d'))->count();
        PosExpense::create([
            'expense_code'  => 'EX-' . date('ymd') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT),
            'category_id'   => (int) $request->category_id,
            'expense_date'  => $request->expense_date ?: date('Y-m-d'),
            'expense_for'   => $request->expense_for,
            'reference_no'  => $request->reference_no,
            'amount'        => (float) $request->amount,
            'note'          => $request->note,
            'created_by'    => auth()->user() != null ? auth()->user()->id : null,
        ]);
        flash(translate('Expense added successfully'))->success();
        return back();
    }

    public function destroy($id)
    {
        PosExpense::findOrFail($id)->delete();
        flash(translate('Expense deleted successfully'))->success();
        return back();
    }

    public function categoryStore(Request $request)
    {
        $request->validate(['name' => 'required']);
        PosExpenseCategory::create([
            'code'        => 'EC-' . str_pad(PosExpenseCategory::count() + 1, 3, '0', STR_PAD_LEFT),
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => 1,
        ]);
        flash(translate('Category added successfully'))->success();
        return back();
    }

    public function categoryDestroy($id)
    {
        PosExpenseCategory::findOrFail($id)->delete();
        flash(translate('Category deleted successfully'))->success();
        return back();
    }
}