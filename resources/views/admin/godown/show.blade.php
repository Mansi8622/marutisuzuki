@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} Godown
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.godowns.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>

                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $godown->name }}</td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>{{ $godown->location }}</td>
                                </tr>
                                <tr>
                                    <th>Capacity</th>
                                    <td>{{ $godown->capacity }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ ucfirst($godown->status) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.godowns.index') }}">
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
