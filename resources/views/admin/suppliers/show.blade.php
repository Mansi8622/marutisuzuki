@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Supplier Details</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tr><th>ID</th><td>{{ $supplier->id }}</td></tr>
                        <tr><th>Name</th><td>{{ $supplier->name }}</td></tr>
                        <tr><th>Email</th><td>{{ $supplier->email }}</td></tr>
                        <tr><th>Phone</th><td>{{ $supplier->phone }}</td></tr>
                        <tr><th>State</th><td>{{ $supplier->state }}</td></tr>
                        <tr><th>City</th><td>{{ $supplier->city }}</td></tr>
                        <tr><th>Pin Code</th><td>{{ $supplier->pin_code }}</td></tr>
                        <tr><th>Full Address</th><td>{{ $supplier->full_address }}</td></tr>
                        <tr><th>GST Number</th><td>{{ $supplier->gst_number }}</td></tr>
                        <tr><th>Bank Name</th><td>{{ $supplier->bank_name }}</td></tr>
                        <tr><th>Account Number</th><td>{{ $supplier->account_number }}</td></tr>
                        <tr><th>IFSC Code</th><td>{{ $supplier->ifsc_code }}</td></tr>
                        <tr>
                            <th>GST Document</th>
                            <td>
                                @if($supplier->gst_document)
                                    <a href="{{ asset('storage/' . $supplier->gst_document) }}" target="_blank">View Document</a>
                                @else
                                    No Document Uploaded
                                @endif
                            </td>
                        </tr>
                    </table>
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">Back</a>
                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-warning">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
