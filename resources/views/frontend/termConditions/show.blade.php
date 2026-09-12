@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.termCondition.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.term-conditions.index') }}">
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
                            <a class="btn btn-default" href="{{ route('frontend.term-conditions.index') }}">
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