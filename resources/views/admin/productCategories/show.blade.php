@extends('layouts.admin')
@php
    $routePrefix = $routePrefix ?? 'admin.product-categories';
@endphp
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.productCategory.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route($routePrefix . '.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.productCategory.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $productCategory->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.productCategory.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $productCategory->name }}
                                    </td>
                                </tr>
                                @if(! ($isSubCategory ?? false))
                                <tr>
                                    <th>Sub Categories</th>
                                    <td>
                                        @forelse($productCategory->subcategories as $subCategory)
                                            <span class="label label-info">{{ $subCategory->name }}</span><p>{{ $subCategory->vehicles->pluck('name')->implode(', ') }}</p>
                                        @empty
                                            <span class="text-muted">No sub categories</span>
                                        @endforelse
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>
                                        {{ trans('cruds.productCategory.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $productCategory->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.productCategory.fields.photo') }}
                                    </th>
                                    <td>
                                        @if($productCategory->photo)
                                            <a href="{{ $productCategory->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $productCategory->photo->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route($routePrefix . '.index') }}">
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
