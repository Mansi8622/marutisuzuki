@extends('layouts.admin')
@section('content')
<div class="panel panel-default"><div class="panel-heading">{{ $vehicle->exists ? 'Edit' : 'Add' }} vehicle</div><div class="panel-body">
<form method="POST" action="{{ $vehicle->exists ? route('admin.vehicles.update', $vehicle) : route('admin.vehicles.store') }}">@csrf
@if($vehicle->exists) @method('PUT') @endif
<div class="form-group"><label>Sub-category / Company</label><select name="subcategory_id" class="form-control" required><option value="">Select company</option>@foreach($companies as $company)<option value="{{ $company->id }}" {{ old('subcategory_id', $vehicle->subcategory_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>@endforeach</select></div>
<div class="form-group"><label>Vehicle model</label><input class="form-control" name="name" value="{{ old('name', $vehicle->name) }}" required></div>
@foreach($errors->all() as $error)<p class="text-danger">{{ $error }}</p>@endforeach
<button class="btn btn-success">Save</button></form></div></div>
@endsection
