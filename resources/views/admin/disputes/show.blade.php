@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.dispute.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.disputes.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dispute.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $dispute->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dispute.fields.customer') }}
                                    </th>
                                    <td>
                                        {{ $dispute->customer->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dispute.fields.type') }}
                                    </th>
                                    <td>
                                        {{ $dispute->type }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dispute.fields.refund_request') }}
                                    </th>
                                    <td>
                                        {{ $dispute->refund_request }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dispute.fields.response') }}
                                    </th>
                                    <td>
                                        {{ $dispute->response }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dispute.fields.last_update') }}
                                    </th>
                                    <td>
                                        {{ $dispute->last_update }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.disputes.index') }}">
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