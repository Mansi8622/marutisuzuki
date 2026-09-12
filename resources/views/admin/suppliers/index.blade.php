@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Supplier List
                    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-success btn-sm float-right">Add Supplier</a>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->state }}</td>
                                <td>{{ $supplier->city }}</td>
                                <td>
                                    <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $suppliers->links() }} <!-- Pagination -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
