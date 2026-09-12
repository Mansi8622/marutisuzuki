<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyExpenseRequest;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
   public function index()
{
    abort_if(Gate::denies('expense_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $user = auth()->user();

    // Initialize the expenses query with necessary relationships
    $query = Expense::with(['expense_category', 'user']);

    if ($user->is_admin) {
        // Admin sees all expenses
        $expenses = $query->get();
    } elseif ($user->business_type === 'Sells Man') {
        // Sells Man sees only their own expenses
        $expenses = $query->where('user_id', $user->id)->get();
    } else {
        // Other users get no expenses
        $expenses = collect();
    }

    return view('frontend.expenses.index', compact('expenses'));
}



    public function create()
    {
        abort_if(Gate::denies('expense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense_categories = ExpenseCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.expenses.create', compact('expense_categories'));
    }

 public function store(StoreExpenseRequest $request)
{
    $data = $request->all();
    $data['user_id'] = auth()->id();
    $data['status'] = Expense::STATUS_PENDING; // ✅ Default status

    if ($request->hasFile('upload_image')) {
        $file = $request->file('upload_image');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/expenses'), $filename);
        $data['upload_image'] = 'uploads/expenses/' . $filename;
    }

    Expense::create($data);

    return redirect()->route('frontend.expenses.index');
}



   public function edit(Expense $expense)
{
    abort_if(Gate::denies('expense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $expense_categories = ExpenseCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

    // Sirf "Sells" business_type wale users dikhao
    $users = \App\Models\User::where('business_type', 'Sells')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

    $expense->load('expense_category');

    return view('admin.expenses.edit', compact('expense', 'expense_categories', 'users'));
}



   public function update(UpdateExpenseRequest $request, Expense $expense)
{
    $expense->update($request->all());

    return redirect()->route('admin.expenses.index');
}



    public function show(Expense $expense)
    {
        abort_if(Gate::denies('expense_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense->load('expense_category');

        return view('frontend.expenses.show', compact('expense'));
    }

    public function destroy(Expense $expense)
    {
        abort_if(Gate::denies('expense_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense->delete();

        return back();
    }

    public function massDestroy(MassDestroyExpenseRequest $request)
    {
        $expenses = Expense::find(request('ids'));

        foreach ($expenses as $expense) {
            $expense->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
