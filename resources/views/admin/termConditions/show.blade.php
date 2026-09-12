@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.termCondition.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.term-conditions.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $termCondition->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $termCondition->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.content') }}
                                    </th>
                                    <td>
                                        {!! $termCondition->content !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.effective_date') }}
                                    </th>
                                    <td>
                                        {{ $termCondition->effective_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.version_number') }}
                                    </th>
                                    <td>
                                        {{ $termCondition->version_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.banner_title') }}
                                    </th>
                                    <td>
                                        {{ $termCondition->banner_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.banner_subtitle') }}
                                    </th>
                                    <td>
                                        {{ $termCondition->banner_subtitle }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.banner_image') }}
                                    </th>
                                    <td>
                                        @if($termCondition->banner_image)
                                            <a href="{{ $termCondition->banner_image->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $termCondition->banner_image->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\TermCondition::STATUS_SELECT[$termCondition->status] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.term-conditions.index') }}">
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