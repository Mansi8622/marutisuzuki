@extends('custom.master')

@section('content')
@php $shipping = json_decode($order->shipping_address, true) ?: []; @endphp
<style>.dashboard{background:#f5f7fb}.panel{background:#fff;border:1px solid #e4ebf3;border-radius:14px;box-shadow:0 12px 28px rgba(18,34,56,.07);padding:20px}.panel h1{font:800 30px 'Barlow Condensed',sans-serif;color:#13243d}.locked{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;padding:12px;color:#64748b}.form-control{border-radius:8px;border-color:#dbe4ef}</style>
<section class="dashboard py-5"><div class="container"><div class="row">@include('custom.sidebar')<div class="col-lg-9"><div class="panel">
  <h1>Edit Delivery Details</h1><p class="text-muted">Only pending orders allow delivery address and contact updates. Items and amount stay locked.</p>
  <div class="locked mb-3"><strong>{{ $order->order_number }}</strong> · Rs {{ number_format($order->total_amount,2) }} · {{ $order->select_products->count() }} item(s)</div>
  <form method="POST" action="{{ route('frontend.orders.update', $order) }}">@csrf @method('PUT')
    <div class="row"><div class="col-md-4 mb-3"><label class="form-label">Contact name</label><input name="contact_name" class="form-control" value="{{ old('contact_name', $shipping['contact_name'] ?? auth()->user()->name ?? '') }}" required></div><div class="col-md-4 mb-3"><label class="form-label">Phone</label><input name="contact_phone" class="form-control" value="{{ old('contact_phone', $shipping['contact_phone'] ?? '') }}" required></div><div class="col-md-4 mb-3"><label class="form-label">Email</label><input name="contact_email" type="email" class="form-control" value="{{ old('contact_email', $shipping['contact_email'] ?? '') }}"></div><div class="col-md-4 mb-3"><label class="form-label">Country</label><input name="country" class="form-control" value="{{ old('country', $shipping['country'] ?? '') }}"></div><div class="col-md-4 mb-3"><label class="form-label">State</label><input name="state" class="form-control" value="{{ old('state', $shipping['state'] ?? '') }}"></div><div class="col-md-4 mb-3"><label class="form-label">District</label><input name="district" class="form-control" value="{{ old('district', $shipping['district'] ?? '') }}"></div><div class="col-12 mb-3"><label class="form-label">Full address</label><textarea name="full_address" class="form-control" rows="4" required>{{ old('full_address', $shipping['full_address'] ?? '') }}</textarea></div></div>
    <div class="d-flex gap-2"><button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Save</button><a href="{{ route('frontend.orders.show', $order) }}" class="btn btn-outline-secondary">Cancel</a></div>
  </form>
</div></div></div></div></section>
@endsection
