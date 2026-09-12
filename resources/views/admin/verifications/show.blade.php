@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.verification.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.verifications.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.verification.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $verification->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.verification.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $verification->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.verification.fields.email') }}
                                    </th>
                                    <td>
                                        {{ $verification->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.verification.fields.number') }}
                                    </th>
                                    <td>
                                        {{ $verification->number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.verification.fields.image') }}
                                    </th>
                                    <td>
                                        <img src="{{ Storage::url($verification->image) }}" width="150" height="150" alt="Profile Image">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.verification.fields.aadhar_image') }}
                                    </th>
                                    <td>
                                        <img src="{{ Storage::url($verification->aadhar_image) }}" width="150" height="150" alt="Aadhar Image">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.verification.fields.pan_image') }}
                                    </th>
                                    <td>
                                        <img src="{{ Storage::url($verification->pan_image) }}" width="150" height="150" alt="PAN Image">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.verification.fields.reseller_code') }}
                                    </th>
                                    <td>
                                        {{ $verification->reseller_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.verification.fields.verification_status') }}
                                    </th>
                                    <td>
                                        {{ $verification->verification_status }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.verifications.index') }}">
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
