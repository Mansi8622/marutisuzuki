@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.tax.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.taxes.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.tax.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $tax->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.tax.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $tax->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.tax.fields.tax_rate') }}
                                    </th>
                                    <td>
                                        {{ $tax->tax_rate }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.tax.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Tax::STATUS_SELECT[$tax->status] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.taxes.index') }}">
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