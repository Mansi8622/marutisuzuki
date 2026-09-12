@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.walletRequest.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.wallet-requests.update", [$walletRequest->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label>{{ trans('cruds.walletRequest.fields.status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\WalletRequest::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $walletRequest->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.walletRequest.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="welcome_amount">{{ trans('cruds.walletRequest.fields.welcome_amount') }}</label>
                            <input class="form-control" type="number" name="welcome_amount" id="welcome_amount" value="{{ old('welcome_amount', $walletRequest->welcome_amount) }}" step="0.01">
                            @if($errors->has('welcome_amount'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('welcome_amount') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.walletRequest.fields.welcome_amount_helper') }}</span>
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