@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add Supplier</div>
                <div class="panel-body">
                    <form action="{{ route('admin.suppliers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            @include('admin.suppliers.form') <!-- Form Include -->
                        </div>
                        <button type="submit" class="btn btn-success">Save Supplier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
