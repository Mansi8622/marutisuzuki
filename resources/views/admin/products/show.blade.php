@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.product.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $product->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $product->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.category') }}
                                    </th>
                                    <td>
                                        @foreach($product->categories as $key => $category)
                                            <span class="label label-info">{{ $category->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.tag') }}
                                    </th>
                                    <td>
                                        @foreach($product->tags as $key => $tag)
                                            <span class="label label-info">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.select_company') }}
                                    </th>
                                    <td>
                                        @foreach($product->select_companies as $key => $select_company)
                                            <span class="label label-info">{{ $select_company->company_name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.item_code') }}
                                    </th>
                                    <td>
                                        {{ $product->item_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.hsn_code') }}
                                    </th>
                                    <td>
                                        {{ $product->hsn_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.godown') }}
                                    </th>
                                    <td>
                                        {{ $product->godown }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $product->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.price') }}
                                    </th>
                                    <td>
                                        {{ $product->price }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.discount') }}
                                    </th>
                                    <td>
                                        {{ $product->discount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.price_1') }}
                                    </th>
                                    <td>
                                        {{ $product->price_1 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.rate_2') }}
                                    </th>
                                    <td>
                                        {{ $product->rate_2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.rate_3') }}
                                    </th>
                                    <td>
                                        {{ $product->rate_3 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.photo') }}
                                    </th>
                                    <td>
                                        @foreach($product->photo as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $media->getUrl('thumb') }}">
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.product_photo_2') }}
                                    </th>
                                    <td>
                                        @foreach($product->product_photo_2 as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $media->getUrl('thumb') }}">
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.product_photo_3') }}
                                    </th>
                                    <td>
                                        @if($product->product_photo_3)
                                            <a href="{{ $product->product_photo_3->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $product->product_photo_3->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.product.fields.sku') }}
                                    </th>
                                    <td>
                                        {{ $product->sku }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.products.index') }}">
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
                        <a href="#select_product_our_stocks" aria-controls="select_product_our_stocks" role="tab" data-toggle="tab">
                            {{ trans('cruds.ourStock.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#select_product_check_godowns" aria-controls="select_product_check_godowns" role="tab" data-toggle="tab">
                            {{ trans('cruds.checkGodown.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#product_cancellations" aria-controls="product_cancellations" role="tab" data-toggle="tab">
                            {{ trans('cruds.cancellation.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#product_refunds" aria-controls="product_refunds" role="tab" data-toggle="tab">
                            {{ trans('cruds.refund.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#select_product_check_orders" aria-controls="select_product_check_orders" role="tab" data-toggle="tab">
                            {{ trans('cruds.checkOrder.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="select_product_our_stocks">
                        @includeIf('admin.products.relationships.selectProductOurStocks', ['ourStocks' => $product->selectProductOurStocks])
                    </div>
                    <div class="tab-pane" role="tabpanel" id="select_product_check_godowns">
                        @includeIf('admin.products.relationships.selectProductCheckGodowns', ['checkGodowns' => $product->selectProductCheckGodowns])
                    </div>
                    <div class="tab-pane" role="tabpanel" id="product_cancellations">
                        @includeIf('admin.products.relationships.productCancellations', ['cancellations' => $product->productCancellations])
                    </div>
                    <div class="tab-pane" role="tabpanel" id="product_refunds">
                        @includeIf('admin.products.relationships.productRefunds', ['refunds' => $product->productRefunds])
                    </div>
                    <div class="tab-pane" role="tabpanel" id="select_product_check_orders">
                        @includeIf('admin.products.relationships.selectProductCheckOrders', ['checkOrders' => $product->selectProductCheckOrders])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection