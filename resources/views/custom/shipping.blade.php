@extends('custom.master')

@section('content')
<div class="container my-5">
    <div class="card shadow-sm p-4">
        <h1 class="text-center mb-4">Shipping and Delivery Policy</h1>
        
        <section class="mb-4">
            <h2 class="h5 fw-bold">Overview</h2>
            <p>Our Shipping and Delivery Policy outlines the shipping process, delivery timelines, and related information. By placing an order, you agree to this policy.</p>
        </section>
        
        <section class="mb-4">
            <h2 class="h5 fw-bold">Shipping Process</h2>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Orders are processed within <strong>1-3 business days</strong> after payment confirmation.</li>
                <li class="list-group-item">Shipping is done through our trusted courier partners, and tracking details are shared via email or SMS once the order is dispatched.</li>
            </ul>
        </section>
        
        <section class="mb-4">
            <h2 class="h5 fw-bold">Delivery Timeline</h2>
            <p>Estimated delivery times depend on the shipping address and product availability:</p>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Standard Delivery:</strong> 0-7 business days.</li>
                <li class="list-group-item"><strong>Express Delivery:</strong> 1-3 business days (if applicable).</li>
            </ul>
            <p class="mt-2 text-muted">Delivery times may be extended during holidays or unforeseen circumstances.</p>
        </section>
        
        <section class="mb-4">
            <h2 class="h5 fw-bold">Shipping Charges</h2>
            <p>Shipping charges vary based on location, order value, and chosen delivery method. Details will be provided at checkout.</p>
        </section>
        
        <section class="mb-4">
            <h2 class="h5 fw-bold">Order Tracking</h2>
            <p>After dispatch, you will receive a tracking ID via email/SMS to monitor your shipment's progress. Contact customer support for assistance if needed.</p>
        </section>
        
        <section>
            <h2 class="h5 fw-bold">Failed Delivery</h2>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">If delivery fails due to incorrect address or recipient unavailability, our team will attempt to contact you to resolve the issue.</li>
                <li class="list-group-item">Additional shipping charges may apply for re-delivery.</li>
            </ul>
        </section>
    </div>
</div>
@endsection
