<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyExpenseRequest;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class ExpenseController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('expense_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expenses = Expense::with(['expense_category'])->get();

        return view('admin.expenses.index', compact('expenses'));
    }

   public function create()
{
    abort_if(Gate::denies('expense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $expense_categories = ExpenseCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

    // Only users with business_type = 'Sells'
    $users = \App\Models\User::where('business_type', 'Sells Man')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

    return view('admin.expenses.create', compact('expense_categories', 'users'));
}
public function store(Request $request)
{
    abort_if(Gate::denies('expense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $validated = $request->validate([
        'expense_category_id' => 'required|exists:expense_categories,id',
        'entry_date' => 'required|date',
        'amount' => 'required|numeric',
        'description' => 'nullable|string',
        'user_id' => 'required|exists:users,id',
        'upload_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf', // Optional: add validation for image
        'status' => 'nullable|in:pending,approve,reject', // Optional: allow manual status
    ]);

    // Set default status if not provided
    if (!isset($validated['status'])) {
        $validated['status'] = Expense::STATUS_PENDING;
    }

    // Handle image upload
    if ($request->hasFile('upload_image')) {
        $path = $request->file('upload_image')->store('expenses', 'public');
        $validated['upload_image'] = $path;
    }

    Expense::create($validated);

    return redirect()->route('admin.expenses.index')->with('success', 'Expense created successfully.');
}


    public function edit(Expense $expense)
{
    abort_if(Gate::denies('expense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $expense_categories = ExpenseCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

    // Show only users with 'Sells' business_type
    $users = \App\Models\User::where('business_type', 'Sells Man')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

    // Load related models
    $expense->load(['expense_category', 'user']);

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

        return view('admin.expenses.show', compact('expense'));
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


     public function addAmount()
    {
        abort_if(Gate::denies('addamount_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend('Please select', '');

        return view('admin.addamounts.create', compact('users'));
    }

    public function storeAddAmount(Request $request)
    {
        abort_if(Gate::denies('addamount_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'amount'      => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|in:approve,pending,reject',
        ]);

        AddAmount::create($request->all());

        return redirect()->route('admin.addamounts.index')->with('success', 'Amount added successfully');
    }
}
