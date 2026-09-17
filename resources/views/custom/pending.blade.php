@extends('layouts.frontend')

@section('frontend-content')
<style>.pending-work{background:#f5f7fb;padding:32px 0;min-height:60vh}.pending-work .panel{border:0;border-radius:15px;box-shadow:0 10px 26px rgba(18,34,56,.07);overflow:hidden}.pending-work .panel-heading{background:#172b49;color:#fff;padding:19px 22px;font:800 21px 'Barlow Condensed',sans-serif}.pending-work .panel-body{padding:0}.pending-work th{font-size:11px;text-transform:uppercase;color:#718096;letter-spacing:.05em}.pending-work th,.pending-work td{padding:14px!important;vertical-align:middle!important;border-color:#edf1f5!important}</style>
<section class="pending-work"><div class="container">
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
</div></div></section>
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
