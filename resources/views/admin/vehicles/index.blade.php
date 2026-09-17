@extends('layouts.admin')
@section('content')
<div class="panel panel-default"><div class="panel-heading">Vehicle Name</div><div class="panel-body">
@can('sub_category_create')<a class="btn btn-success" href="{{ route('admin.vehicles.create') }}">Add vehicle</a>@endcan
<table class="table table-bordered"><thead><tr><th>Sub-category / Company</th><th>Vehicle model</th><th>Actions</th></tr></thead><tbody>
@foreach($vehicles as $vehicle)<tr><td>{{ $vehicle->subcategory->name ?? 'Deleted company' }}</td><td>{{ $vehicle->name }}</td><td>
@can('sub_category_edit')<a class="btn btn-info btn-xs" href="{{ route('admin.vehicles.edit', $vehicle) }}">Edit</a>@endcan
@can('sub_category_delete')<form method="POST" action="{{ route('admin.vehicles.destroy', $vehicle) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-danger btn-xs">Delete</button></form>@endcan
</td></tr>@endforeach
</tbody></table></div></div>
@endsection
