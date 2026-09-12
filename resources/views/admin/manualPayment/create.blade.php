@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        
        <div class="card-body">
            <div class="row">
            <div class="col-lg-12">
                <h4 class="mb-4">Add Manual Payment (Cash / Cheque)</h4>

            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.manual.payment.store') }}" id="paymentForm">
                @csrf

                <!-- Vendor Selection -->
                <div class="mb-4 col-lg-12">
                    <label for="vendor_id" class="form-label fw-bold">Select Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-select form-control" required>
                        <option value="">-- Choose Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->email }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Due Orders -->
                <div class="mb-4 col-lg-12">
                    <h5 class="mb-3">Pending Orders</h5>
                    <div id="dueOrdersList" class="row g-3"></div>
                </div>

                <!-- Payment Summary -->
                <div class="mb-4 col-lg-6">
                    <label class="form-label fw-bold">Total Amount to Pay</label>
                    <input type="text" id="totalAmountToPay" name="total_amount" class="form-control bg-light border-0 fw-bold fs-5 text-success" readonly value="0.00" >
                </div>

                <!-- Payment Type -->
                <div class="mb-4 col-lg-12 mt-3">
                    <label for="transaction_type" class="form-label fw-bold">Payment Method</label>
                    <select name="transaction_type" id="transaction_type" class="form-select form-control" required>
                        <option value="">-- Select Type --</option>
                        <option value="cash">Cash</option>
                        <option value="cheque">Cheque</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="upi">UPI</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Notes -->
                <div class="mb-4 col-lg-12">
                    <label for="notes" class="form-label fw-bold">Notes (Optional)</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Cheque No, Transaction ID, etc."></textarea>
                </div>

                <div class=" col-lg-6 p-4">
                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<!-- Blinking Effect CSS -->
<style>
.blink {
    animation: blinker 1.5s linear infinite;
    color: #dc3545;
    font-weight: bold;
}
@keyframes blinker {
    50% { opacity: 0; }
}
.order-card {
    border: 2px dashed #ddd;
    padding: 1rem;
    border-radius: 8px;
    background-color: #f8f9fa;
}
.order-card.active {
    border-color: #198754;
    background-color: #eafaf1;
}
</style>

<!-- JavaScript -->
<script>
document.getElementById('vendor_id').addEventListener('change', function () {
    const vendorId = this.value;
    const dueOrdersList = document.getElementById('dueOrdersList');
    const totalAmountToPay = document.getElementById('totalAmountToPay');

    dueOrdersList.innerHTML = '';
    totalAmountToPay.value = '0.00';

    if (!vendorId) return;

    fetch(`/admin/vendor/${vendorId}/due-orders`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach((order, index) => {
                    if (order.remaining_amount > 0) {
                        const createdAt = new Date(order.created_at);
                        const today = new Date();
                        const diffDays = Math.ceil(Math.abs(today - createdAt) / (1000 * 60 * 60 * 24));

                        const checkboxId = `order_check_${index}`;
                        const inputId = `amount_input_${index}`;

                        const col = document.createElement('div');
                        col.classList.add('col-md-6');

                        col.innerHTML = `
                            <div class="order-card" id="card_${index}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <input type="checkbox" class="form-check-input me-2" id="${checkboxId}" name="order_id[]" value="${order.id}">
                                        <label for="${checkboxId}" class="fw-bold">#${order.order_number}</label>
                                    </div>
                                    <span class="blink small">Pending ${diffDays} day${diffDays > 1 ? 's' : ''}</span>
                                </div>
                                <p class="mb-1"><strong>Total:</strong> <b>₹</b>${order.total_amount}</p>
                                <p class="text-danger mb-2"><strong>Due:</strong> <b>₹</b>${order.remaining_amount}</p>
                                <div class="input-group" id="${inputId}" style="display: none;">
                                    <span class="input-group-text"><b>₹</b></span>
                                    <input type="number" name="paid_amount[]" id="amount_${index}" class="form-control" min="0" max="${order.remaining_amount}" data-remaining="${order.remaining_amount}" placeholder="Enter amount">
                                </div>
                            </div>
                        `;
                        dueOrdersList.appendChild(col);

                        document.getElementById(checkboxId).addEventListener('change', function () {
                            const card = document.getElementById(`card_${index}`);
                            const amountInputDiv = document.getElementById(inputId);
                            const amountInput = document.getElementById(`amount_${index}`);
                            if (this.checked) {
                                card.classList.add('active');
                                amountInputDiv.style.display = 'flex';
                            } else {
                                card.classList.remove('active');
                                amountInputDiv.style.display = 'none';
                                amountInput.value = '';
                                calculateTotal();
                            }
                        });

                        document.getElementById(`amount_${index}`).addEventListener('input', function () {
                            const entered = parseFloat(this.value || 0);
                            const max = parseFloat(this.dataset.remaining);
                            this.classList.toggle('is-invalid', entered > max);
                            calculateTotal();
                        });
                    }
                });

                function calculateTotal() {
                    let total = 0;
                    document.querySelectorAll('[id^="amount_"]').forEach(input => {
                        const amount = parseFloat(input.value || 0);
                        if (amount > 0) total += amount;
                    });
                    totalAmountToPay.value = total.toFixed(2);
                }
            } else {
                dueOrdersList.innerHTML = '<p class="text-muted">No due orders found for this vendor.</p>';
            }
        })
        .catch(err => {
            console.error(err);
            dueOrdersList.innerHTML = '<p class="text-danger">Unable to load due orders.</p>';
        });
});
</script>
@endsection
