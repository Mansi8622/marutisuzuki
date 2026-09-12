@extends('layouts.frontend')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.expense.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.expenses.store") }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Hidden user_id input -->
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                        <div class="form-group">
                            <label for="expense_category_id">{{ trans('cruds.expense.fields.expense_category') }}</label>
                            <select class="form-control select2" name="expense_category_id" id="expense_category_id">
                                @foreach($expense_categories as $id => $entry)
                                    <option value="{{ $id }}" {{ old('expense_category_id') == $id ? 'selected' : '' }}>
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('expense_category_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('expense_category_id') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.expense.fields.expense_category_helper') }}</span>
                        </div>

                       <input type="hidden" name="entry_date" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">


                        <div class="form-group">
                            <label class="required" for="amount">{{ trans('cruds.expense.fields.amount') }}</label>
                            <input class="form-control" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01" required>
                            @if($errors->has('amount'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('amount') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.expense.fields.amount_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="description">{{ trans('cruds.expense.fields.description') }}</label>
                            <input class="form-control" type="text" name="description" id="description" value="{{ old('description', '') }}">
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.expense.fields.description_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="upload_image">Upload Image</label>
                            <input type="file" name="upload_image" class="form-control-file" id="upload_image">
                            @if($errors->has('upload_image'))
                                <div class="invalid-feedback d-block">
                                    {{ $errors->first('upload_image') }}
                                </div>
                            @endif
                            <span class="help-block">Optional supporting document/image</span>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
