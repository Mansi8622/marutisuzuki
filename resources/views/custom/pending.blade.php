@extends('layouts.frontend')

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
                                        <th> ID</th>
                                        <th>Order No</th>
                                        <th>Product</th>
                                        <th>Ordered Qty</th>
                                        <th>Confirmed Qty</th>
                                        <th>Pending Qty</th>
                                        <th>Advanced Amount</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order['order_id'] }}</td>
                                            <td>{{ $order['order_number'] }}</td>
                                            <td>{{ $order['product_name'] }}</td>
                                            <td>{{ $order['ordered_quantity'] }}</td>
                                            <td>{{ $order['confirmed_quantity'] }}</td>
                                            <td>{{ $order['pending_quantity'] }}</td>
                                            <td>{{ $order['pending_price'] }}</td>
                                            <td>{{ $order['total_amount'] }}</td>
                                           
                                            
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
