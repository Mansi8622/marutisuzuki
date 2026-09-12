@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Supplier</div>
                <div class="panel-body">
                    <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            @include('admin.suppliers.form', ['supplier' => $supplier]) <!-- Form Include -->
                        </div>
                        <button type="submit" class="btn btn-warning">Update Supplier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
