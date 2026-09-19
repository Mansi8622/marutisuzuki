@extends('custom.master')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<style>
.dashboard{background:#f5f7fb}.work-card{background:#fff;border:1px solid #e4ebf3;border-radius:14px;box-shadow:0 12px 28px rgba(18,34,56,.07);padding:18px}.page-head{display:flex;justify-content:space-between;gap:14px;align-items:center;margin-bottom:16px}.page-head h1{font:800 30px 'Barlow Condensed',sans-serif;color:#13243d;margin:0}.page-head p{color:#718096;margin:0;font-size:.86rem}.table-wrap{overflow:auto}.table thead{background:#172b49;color:#fff}.table th{border:0!important;font-size:11px;text-transform:uppercase;letter-spacing:.05em;padding:14px!important;white-space:nowrap}.table td{padding:13px 12px!important;vertical-align:middle;border-color:#edf1f5!important}.table tbody tr:hover{background:#f7f9ff}.order-no{font-weight:800;color:#13243d}.pill{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:6px 10px;font-size:.72rem;font-weight:800}.pill.pending{background:#fff5db;color:#8a5b00}.pill.done{background:#e8faf1;color:#087f5b}.pill.info{background:#eef4ff;color:#2454b9}.action-row{display:flex;gap:7px;flex-wrap:wrap}.icon-btn{width:34px;height:34px;border-radius:8px;display:inline-grid;place-items:center;border:1px solid #dbe4ef;background:#fff;color:#172b49;text-decoration:none}.icon-btn:hover{background:#eef4ff;color:#2454b9}.icon-btn.pdf{color:#c92a2a}.icon-btn.edit{color:#9a6700}.icon-btn.disabled{opacity:.42;pointer-events:none}.dataTables_wrapper .dataTables_length select{min-width:70px}.dataTables_wrapper .dataTables_filter input{border:1px solid #dbe4ef;border-radius:8px;padding:6px 10px}
</style>

<section class="dashboard py-5">
  <div class="container">
    <div class="row">
      @include('custom.sidebar')
      <div class="col-lg-9 mb-3">
        <div class="work-card">
          <div class="page-head">
            <div>
              <h1>My Orders</h1>
              <p>View, edit pending delivery details, and download invoices.</p>
            </div>
            <a class="btn btn-primary" href="/product"><i class="fa-solid fa-plus me-1"></i> New order</a>
          </div>

          <div class="table-wrap">
            <table class="table table-hover align-middle datatable-orders w-100">
              <thead>
                <tr><th>#</th><th>Date</th><th>Order</th><th>Amount</th><th>Status</th><th>Payment</th><th>Tracking</th><th>Note</th><th>Actions</th></tr>
              </thead>
              <tbody>
                @foreach($orders as $index => $order)
                  @php $isPending = strtolower($order->order_status ?? '') === 'pending'; @endphp
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ optional($order->created_at)->format('d M Y') }}</td>
                    <td><span class="order-no">{{ $order->order_number }}</span>@include('custom.partials.order-selections', ['selectionOrder' => $order])</td>
                    <td>Rs {{ number_format($order->total_amount, 2) }}</td>
                    <td><span class="pill {{ $isPending ? 'pending' : 'done' }}"><i class="fa-solid {{ $isPending ? 'fa-clock' : 'fa-circle-check' }}"></i>{{ ucfirst($order->order_status ?? 'Pending') }}</span></td>
                    <td><span class="pill info"><i class="fa-solid fa-credit-card"></i>{{ $order->payment_method === 'Credit Line' ? 'Credit Line' : 'Paid' }}</span></td>
                    <td>@if($order->carrier)<a href="{{ $order->carrier->tracking_url }}" target="_blank" class="icon-btn" title="Track order"><i class="fa-solid fa-truck-fast"></i></a>@else<span class="text-muted">N/A</span>@endif</td>
                    <td title="Click to copy" onclick="navigator.clipboard.writeText(this.innerText)">{!! $order->notes ?? '<span class="text-muted">N/A</span>' !!}</td>
                    <td><div class="action-row"><a href="{{ route('frontend.orders.show', $order) }}" class="icon-btn" title="View"><i class="fa-regular fa-eye"></i></a><a href="{{ $isPending ? route('frontend.orders.edit', $order) : '#' }}" class="icon-btn edit {{ $isPending ? '' : 'disabled' }}" title="Edit pending delivery details"><i class="fa-regular fa-pen-to-square"></i></a><a href="{{ route('invoice.download', $order->order_number) }}" class="icon-btn pdf" title="Download PDF"><i class="fa-regular fa-file-pdf"></i></a></div></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function(){ $('.datatable-orders').DataTable({ pageLength:5, lengthMenu:[[5,10,25,50],[5,10,25,50]], order:[[1,'desc']] }); });
</script>
@endsection
