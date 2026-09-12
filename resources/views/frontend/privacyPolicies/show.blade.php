@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.privacyPolicy.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.privacy-policies.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.privacyPolicy.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $privacyPolicy->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.privacyPolicy.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $privacyPolicy->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.privacyPolicy.fields.content') }}
                                    </th>
                                    <td>
                                        {!! $privacyPolicy->content !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.privacyPolicy.fields.effective_date') }}
                                    </th>
                                    <td>
                                        {{ $privacyPolicy->effective_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.privacyPolicy.fields.version_number') }}
                                    </th>
                                    <td>
                                        {{ $privacyPolicy->version_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.privacyPolicy.fields.banner_title') }}
                                    </th>
                                    <td>
                                        {{ $privacyPolicy->banner_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.privacyPolicy.fields.banner_subtitle') }}
                                    </th>
                                    <td>
                                        {{ $privacyPolicy->banner_subtitle }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.privacyPolicy.fields.banner_image') }}
                                    </th>
                                    <td>
                                        @foreach($privacyPolicy->banner_image as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $media->getUrl('thumb') }}">
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.privacyPolicy.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\PrivacyPolicy::STATUS_SELECT[$privacyPolicy->status] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.privacy-policies.index') }}">
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