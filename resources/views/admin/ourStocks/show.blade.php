@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.ourStock.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.our-stocks.index') }}">
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
                            <a class="btn btn-default" href="{{ route('admin.our-stocks.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.relatedData') }}
                </div>
                <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                    <li role="presentation">
                        <a href="#select_product_stock_transfers" aria-controls="select_product_stock_transfers" role="tab" data-toggle="tab">
                            {{ trans('cruds.stockTransfer.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="select_product_stock_transfers">
                        @includeIf('admin.ourStocks.relationships.selectProductStockTransfers', ['stockTransfers' => $ourStock->selectProductStockTransfers])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection