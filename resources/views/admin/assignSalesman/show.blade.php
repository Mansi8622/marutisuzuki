@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Salesman Details
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.assign-salesmen.index') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $assignSalesman->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $assignSalesman->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $assignSalesman->number }}</td>
                            </tr>
                            <tr>
                                <th>Retailer</th>
                                <td>{{ $assignSalesman->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Profile Image</th>
                                <td>
                                    @if($assignSalesman->getProfileImageUrl())
                                        <img src="{{ $assignSalesman->getProfileImageUrl() }}" alt="Salesman Image" width="150">
                                    @else
                                        <span>No image uploaded.</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.assign-salesmen.index') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
