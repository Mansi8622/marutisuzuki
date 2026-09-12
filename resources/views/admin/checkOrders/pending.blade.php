@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Pending Orders</strong>
                </div>

                <div class="panel-body">
                    @if($orders->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User</th>
                                        <th>Order No</th>
                                        <th>Product</th>
                                        <th>Ordered Qty</th>
                                        <th>Confirmed Qty</th>
                                        <th>Pending Qty</th>
                                        <th>Stock Available</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order['order_id'] }}</td>
                                            <td>{{ $order['user'] }}</td>
                                            <td>{{ $order['order_number'] }}</td>
                                            <td>{{ $order['product_name'] }}</td>
                                            <td>{{ $order['ordered_quantity'] }}</td>
                                            <td>{{ $order['confirmed_quantity'] }}</td>
                                            <td>{{ $order['pending_quantity'] }}</td>
                                            <td>
                                                @if($order['stock_quantity'] > 0)
                                                    <span class="text-success">{{ $order['stock_quantity'] }} In Stock</span>
                                                @else
                                                    <span class="text-danger">Out of Stock</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.check-orders.show', $order['order_id']) }}" class="btn btn-xs btn-primary">View</a>
                                                <a href="{{ route('admin.check-orders.edit', $order['order_id']) }}" class="btn btn-xs btn-warning">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No pending orders found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        $('.datatable').DataTable({
            order: [[0, 'desc']],
            pageLength: 50
        });
    });
</script>
@endsection
