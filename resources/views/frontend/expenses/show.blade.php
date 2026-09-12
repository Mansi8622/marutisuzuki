@extends('layouts.frontend')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.expense.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.expenses.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.expense.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $expense->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.expense.fields.expense_category') }}
                                    </th>
                                    <td>
                                        {{ $expense->expense_category->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.expense.fields.entry_date') }}
                                    </th>
                                    <td>
                                        {{ $expense->entry_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.expense.fields.amount') }}
                                    </th>
                                    <td>
                                        {{ $expense->amount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.expense.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $expense->description }}
                                    </td>
                                </tr>
                                <!-- Added row for displaying the image -->
                                <tr>
                                    <th>
                                        {{ trans('cruds.expense.fields.upload_image') }}
                                    </th>
                                    <td>
    @if($expense->upload_image) <!-- Check if there is an uploaded image -->
        <img src="{{ asset($expense->upload_image) }}" alt="Expense Image" width="50"> <!-- Display the image -->
    @else
        {{ trans('global.no_image') }} <!-- In case there's no image -->
    @endif
</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.expenses.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
