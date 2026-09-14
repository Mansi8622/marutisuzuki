@extends('layouts.frontend')

@section('frontend-content')
    <style>
        .credit-repayment-page { background: #f5f7fb; min-height: 60vh; padding: 36px 0; }
        .credit-repayment-card { border: 0; border-radius: 16px; box-shadow: 0 12px 28px rgba(18, 34, 56, .08); overflow: hidden; }
        .credit-repayment-card .card-header { background: #10243b; color: #fff; font-weight: 700; padding: 18px 22px; }
        .credit-repayment-card .table th { border-top: 0; color: #70839b; font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; }
    </style>

    <section class="credit-repayment-page">
        <div class="container">
            <div class="card credit-repayment-card">
                <div class="card-header">Select credit invoices to repay</div>

                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('wallet.payout.submit') }}">
                        @csrf

                        <div class="table-responsive">
                            <table class="table align-middle mb-4">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Invoice</th>
                                        <th>Remaining</th>
                                        <th style="min-width: 170px">Pay now</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        @if ($order->remaining > 0)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="invoice-check" name="order_ids[]" value="{{ $order->id }}" aria-label="Select {{ $order->order_number }}">
                                                </td>
                                                <td class="font-weight-bold">{{ $order->order_number }}</td>
                                                <td>₹{{ number_format($order->remaining, 2) }}</td>
                                                <td>
                                                    <input class="form-control repay-amount" data-id="{{ $order->id }}" name="amounts[{{ $order->id }}]" type="number" min="0" max="{{ $order->remaining }}" step="0.01" placeholder="Enter amount" disabled>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No unpaid credit invoices are available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center border-top pt-3">
                            <strong class="mb-2 mb-sm-0">Total selected: ₹<span id="repayTotal">0.00</span></strong>
                            <button type="submit" class="btn btn-primary">Continue to payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const totalElement = document.getElementById('repayTotal');

            function updateTotal() {
                let total = 0;
                document.querySelectorAll('.repay-amount:not(:disabled)').forEach(function (input) {
                    total += parseFloat(input.value) || 0;
                });
                totalElement.textContent = total.toFixed(2);
            }

            document.querySelectorAll('.invoice-check').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const input = document.querySelector('.repay-amount[data-id="' + this.value + '"]');
                    input.disabled = !this.checked;
                    if (!this.checked) input.value = '';
                    updateTotal();
                });
            });

            document.querySelectorAll('.repay-amount').forEach(function (input) {
                input.addEventListener('input', updateTotal);
            });
        });
    </script>
@endsection
