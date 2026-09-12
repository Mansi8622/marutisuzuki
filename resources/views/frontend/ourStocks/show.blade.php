@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.ourStock.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.our-stocks.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ourStock.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $ourStock->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ourStock.fields.select_product') }}
                                    </th>
                                    <td>
                                        {{ $ourStock->select_product->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ourStock.fields.quantity_available') }}
                                    </th>
                                    <td>
                                        {{ $ourStock->quantity_available }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ourStock.fields.sku') }}
                                    </th>
                                    <td>
                                        {{ $ourStock->sku }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.our-stocks.index') }}">
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