@extends('layouts.frontend')

@section('content')
<div class="container">
    <div class="row">
        <!-- Wallet Balance Card -->
        <div class="col-md-6">
            <div class="card shadow-lg border-success">
                <div class="card-body">
                    <h5 class="card-title text-success">Wallet Balance</h5>
                    <h3 class="text-success">₹{{ number_format($wallet->welcome_amount, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Due Amount Card -->
        <div class="col-md-6">
            <div class="card shadow-lg border-danger">
                <div class="card-body">
                    <h5 class="card-title text-danger">Due Amount</h5>
                    <h3 class="text-danger">₹{{ number_format($wallet->due, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History Table -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title">Transaction History</h5>

                    <div id="transaction-history">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $key => $transaction)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>₹{{ number_format($transaction->request_amount, 2) }}</td>
                                        <td>{{ ucfirst($transaction->transaction_type) }}</td>
                                        <td>{{ ucfirst($transaction->status) }}</td>
                                        <td>{{ $transaction->created_at->format('d M, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Transactions Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PDF Download Button -->
                    <button class="btn btn-outline-danger" onclick="downloadPDF()">Download PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for PDF Download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadPDF() {
        var element = document.getElementById('transaction-history');
        html2pdf()
            .from(element)
            .save('Transaction_History.pdf');
    }
</script>
@endsection
