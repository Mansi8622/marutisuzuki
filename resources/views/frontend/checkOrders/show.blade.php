@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.checkOrder.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.check-orders.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $checkOrder->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.select_user') }}
                                    </th>
                                    <td>
                                        {{ $checkOrder->select_user->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.select_product') }}
                                    </th>
                                    <td>
                                        @foreach($checkOrder->select_products as $key => $select_product)
                                            <span class="label label-info">{{ $select_product->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.order_number') }}
                                    </th>
                                    <td>
                                        {{ $checkOrder->order_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.total_amount') }}
                                    </th>
                                    <td>
                                        {{ $checkOrder->total_amount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.payment_method') }}
                                    </th>
                                    <td>
                                        {{ $checkOrder->payment_method }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.payment_status') }}
                                    </th>
                                    <td>
                                        {{ $checkOrder->payment_status }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.shipping_address') }}
                                    </th>
                                    <td>
                                        {!! $checkOrder->shipping_address !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.billing_address') }}
                                    </th>
                                    <td>
                                        {{ $checkOrder->billing_address }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.placed_at') }}
                                    </th>
                                    <td>
                                        {{ $checkOrder->placed_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.order_status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\CheckOrder::ORDER_STATUS_SELECT[$checkOrder->order_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.notes') }}
                                    </th>
                                    <td>
                                        {!! $checkOrder->notes !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.attachment') }}
                                    </th>
                                    <td>
                                        @foreach($checkOrder->attachment as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.check-orders.index') }}">
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