@extends('custom.master')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<style>
.dashboard{background:#f5f7fb}.work-card{background:#fff;border:1px solid #e4ebf3;border-radius:14px;box-shadow:0 12px 28px rgba(18,34,56,.07);padding:18px}.page-head{display:flex;justify-content:space-between;gap:14px;align-items:center;margin-bottom:16px}.page-head h1{font:800 30px 'Barlow Condensed',sans-serif;color:#13243d;margin:0}.page-head p{color:#718096;margin:0}.table thead{background:#172b49;color:#fff}.table th{border:0!important;font-size:11px;text-transform:uppercase;letter-spacing:.05em;padding:14px!important}.table td{padding:13px 12px!important;vertical-align:middle;border-color:#edf1f5!important}.badge-soft{display:inline-flex;gap:6px;align-items:center;border-radius:999px;background:#fff5db;color:#8a5b00;padding:6px 10px;font-weight:800;font-size:.72rem}.action-row{display:flex;gap:7px;flex-wrap:wrap}.icon-btn{width:34px;height:34px;border-radius:8px;display:inline-grid;place-items:center;border:1px solid #dbe4ef;background:#fff;color:#172b49;text-decoration:none}.icon-btn:hover{background:#eef4ff;color:#2454b9}.icon-btn.pdf{color:#c92a2a}.icon-btn.edit{color:#9a6700}.icon-btn.disabled{opacity:.42;pointer-events:none}.modal-dialog{max-width:860px}.modal-content{max-height:90vh;overflow:hidden}.modal-body{max-height:75vh;overflow:auto}.dataTables_wrapper .dataTables_filter input,.dataTables_wrapper .dataTables_length select{border:1px solid #dbe4ef;border-radius:8px;padding:6px 10px}
</style>

<section class="dashboard py-5">
  <div class="container">
    <div class="row">
      @include('custom.sidebar')
      <div class="col-lg-9 mb-3">
        @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if(session('success'))<div class="alert alert-success">{!! session('success') !!}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{!! session('error') !!}</div>@endif
        <div class="work-card">
          <div class="page-head">
            <div><h1>Replacement</h1><p>Track replacement invoices, status and contact updates.</p></div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#replacementModal"><i class="fa-solid fa-plus me-1"></i> File Replacement</button>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle datatable-replacements w-100">
              <thead><tr><th>Company</th><th>Order</th><th>Date</th><th>Product</th><th>Qty</th><th>Issue</th><th>Status</th><th>Info</th><th>Actions</th></tr></thead>
              <tbody>
                @foreach($replacements as $replacement)
                  @php $isPending = strtolower($replacement->status ?? 'pending') === 'pending'; @endphp
                  <tr>
                    <td>{{ $replacement->company->company_name ?? 'N/A' }}</td>
                    <td><strong>{{ $replacement->order_number }}</strong></td>
                    <td>{{ optional($replacement->created_at)->format('d M Y') }}</td>
                    <td>{{ $replacement->product->name ?? 'N/A' }}</td>
                    <td>{{ $replacement->quantity }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($replacement->issues ?? 'N/A', 42) }}</td>
                    <td><span class="badge-soft"><i class="fa-solid fa-rotate-left"></i>{{ ucfirst($replacement->status ?? 'Pending') }}</span></td>
                    <td>{{ \Illuminate\Support\Str::limit($replacement->info ?? 'No additional info', 42) }}</td>
                    <td><div class="action-row"><a href="{{ route('frontend.customer-replacements.show', $replacement) }}" class="icon-btn" title="View"><i class="fa-regular fa-eye"></i></a><a href="{{ $isPending ? route('frontend.customer-replacements.edit', $replacement) : '#' }}" class="icon-btn edit {{ $isPending ? '' : 'disabled' }}" title="Edit pending contact details"><i class="fa-regular fa-pen-to-square"></i></a><a href="{{ route('replacement.invoice', $replacement->id) }}" class="icon-btn pdf" title="Replacement PDF"><i class="fa-regular fa-file-pdf"></i></a></div></td>
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

<div class="modal fade" id="replacementModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">File Replacement</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">
    <form action="{{ route('frontend.replacement.store') }}" method="post" enctype="multipart/form-data">@csrf
      <div class="mb-3"><label for="orderSelect" class="form-label">Select Order</label><select id="orderSelect" class="form-control" name="order_id"><option value="">-- Select Order --</option>@foreach ($orders as $order)<option value="{{ $order->id }}">{{ $order->order_number }}</option>@endforeach</select></div>
      <div id="orderDetailsSection" style="display:none">
        <input type="hidden" name="check_order_id" id="checkOrderId"><input type="hidden" name="order_number" id="orderNumberHidden">
        <div class="table-responsive"><table class="table table-bordered"><tr><th>Order Number</th><td id="orderNumberDisplay"></td></tr><tr><th>Total Amount</th><td id="totalAmount"></td></tr><tr><th>Payment</th><td id="paymentMethod"></td></tr><tr><th>Status</th><td id="orderStatus"></td></tr><tr><th>Shipping</th><td id="shippingAddress"></td></tr></table></div>
        <h5 class="mt-3">Products</h5><div id="productList" class="list-group"></div>
        <div class="row mt-3"><div class="col-md-4 mb-3"><input type="text" name="customer_name" class="form-control" placeholder="Contact name" required></div><div class="col-md-4 mb-3"><input type="email" name="customer_email" class="form-control" placeholder="Email"></div><div class="col-md-4 mb-3"><input type="text" name="customer_phone" class="form-control" placeholder="Phone" required></div><div class="col-12 mb-3"><textarea name="customer_notes" rows="2" class="form-control" placeholder="Contact notes"></textarea></div><div class="col-12 mb-3"><textarea name="issues" class="form-control" placeholder="Issue"></textarea></div><div class="col-12 mb-3"><textarea name="notes" class="form-control" placeholder="Internal note"></textarea></div><div class="col-12 mb-3"><input type="file" name="attachment" class="form-control"></div></div>
        <div class="text-end"><button type="submit" class="btn btn-success"><i class="fa-solid fa-paper-plane me-1"></i> Submit</button></div>
      </div>
    </form>
  </div></div></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function(){ $('.datatable-replacements').DataTable({ pageLength:5, lengthMenu:[[5,10,25,50],[5,10,25,50]], order:[[2,'desc']] }); });
document.getElementById('orderSelect').addEventListener('change', function(){
  const orderId = this.value; if(!orderId){ document.getElementById('orderDetailsSection').style.display = 'none'; return; }
  fetch(`/order-details/${orderId}`).then(r=>r.json()).then(data=>{
    if(data.error){ alert('Order not found'); return; }
    document.getElementById('orderNumberDisplay').textContent = data.order.order_number ?? 'N/A';
    document.getElementById('totalAmount').textContent = data.order.total_amount ?? 'N/A';
    document.getElementById('paymentMethod').textContent = data.order.payment_method ?? 'N/A';
    document.getElementById('orderStatus').textContent = data.order.order_status ?? 'N/A';
    document.getElementById('shippingAddress').textContent = data.order.shipping_address ?? 'N/A';
    document.getElementById('checkOrderId').value = data.order.id ?? '';
    document.getElementById('orderNumberHidden').value = data.order.order_number ?? '';
    let html = '';
    (data.products || []).forEach((item, index) => {
      html += `<div class="list-group-item"><label class="form-check"><input class="form-check-input product-checkbox" type="checkbox" name="selected_products[${index}][product_id]" value="${item.id}"> <strong>${item.name}</strong> - Qty ordered: ${item.quantity}<input type="hidden" name="selected_products[${index}][company_id]" value="${item.company_id ?? ''}"></label><input type="number" name="selected_products[${index}][quantity]" class="form-control quantity-input mt-2" min="1" max="${item.quantity}" disabled placeholder="Replacement quantity"></div>`;
    });
    document.getElementById('productList').innerHTML = html || '<div class="list-group-item">No products found</div>';
    document.querySelectorAll('.product-checkbox').forEach(cb => cb.addEventListener('change', function(){ const input = this.closest('.list-group-item').querySelector('.quantity-input'); input.disabled = !this.checked; if(!this.checked) input.value=''; }));
    document.getElementById('orderDetailsSection').style.display = 'block';
  });
});
</script>
@endsection
