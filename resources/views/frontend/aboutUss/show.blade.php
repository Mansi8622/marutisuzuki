@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.aboutUs.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.about-uss.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $aboutUs->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $aboutUs->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.contant') }}
                                    </th>
                                    <td>
                                        {!! $aboutUs->contant !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.mission_statement') }}
                                    </th>
                                    <td>
                                        {!! $aboutUs->mission_statement !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.vision_statement') }}
                                    </th>
                                    <td>
                                        {!! $aboutUs->vision_statement !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.team_section_content') }}
                                    </th>
                                    <td>
                                        {!! $aboutUs->team_section_content !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.achievements_content') }}
                                    </th>
                                    <td>
                                        {!! $aboutUs->achievements_content !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.banner_title') }}
                                    </th>
                                    <td>
                                        {{ $aboutUs->banner_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.banner_subtitle') }}
                                    </th>
                                    <td>
                                        {{ $aboutUs->banner_subtitle }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.banner_image') }}
                                    </th>
                                    <td>
                                        @if($aboutUs->banner_image)
                                            <a href="{{ $aboutUs->banner_image->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $aboutUs->banner_image->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.aboutUs.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\AboutUs::STATUS_SELECT[$aboutUs->status] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.about-uss.index') }}">
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