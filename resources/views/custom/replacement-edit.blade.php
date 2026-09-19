@extends('custom.master')

@section('content')
<style>.dashboard{background:#f5f7fb}.panel{background:#fff;border:1px solid #e4ebf3;border-radius:14px;box-shadow:0 12px 28px rgba(18,34,56,.07);padding:20px}.panel h1{font:800 30px 'Barlow Condensed',sans-serif;color:#13243d}.locked{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;padding:12px;color:#64748b}.form-control{border-radius:8px;border-color:#dbe4ef}</style>
<section class="dashboard py-5"><div class="container"><div class="row">@include('custom.sidebar')<div class="col-lg-9"><div class="panel">
  <h1>Edit Replacement Contact</h1><p class="text-muted">Only contact details can be changed while replacement is pending. Product and quantity stay locked.</p>
  <div class="locked mb-3"><strong>{{ $replacement->product->name ?? 'Product' }}</strong> · Qty {{ $replacement->quantity }} · {{ $replacement->order_number }}</div>
  <form method="POST" action="{{ route('frontend.customer-replacements.update', $replacement) }}">@csrf @method('PUT')
    <div class="row"><div class="col-md-4 mb-3"><label class="form-label">Contact name</label><input name="customer_name" class="form-control" value="{{ old('customer_name', $replacement->customer_name) }}" required></div><div class="col-md-4 mb-3"><label class="form-label">Phone</label><input name="customer_phone" class="form-control" value="{{ old('customer_phone', $replacement->customer_phone) }}" required></div><div class="col-md-4 mb-3"><label class="form-label">Email</label><input name="customer_email" type="email" class="form-control" value="{{ old('customer_email', $replacement->customer_email) }}"></div><div class="col-12 mb-3"><label class="form-label">Contact notes</label><textarea name="customer_notes" class="form-control" rows="4">{{ old('customer_notes', $replacement->customer_notes) }}</textarea></div></div>
    <div class="d-flex gap-2"><button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Save</button><a href="{{ route('frontend.customer-replacements.show', $replacement) }}" class="btn btn-outline-secondary">Cancel</a></div>
  </form>
</div></div></div></div></section>
@endsection
