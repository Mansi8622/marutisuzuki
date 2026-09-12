<!-- Transaction History Modal -->
<div class="modal fade" id="transactionHistoryModal" tabindex="-1" aria-labelledby="transactionHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionHistoryModalLabel">Transaction History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="transactionTable" class="table table-bordered">
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
                        @php
                            $transactions = \App\Models\Transaction::where('vendor_id', Auth::id())->orderBy('created_at', 'desc')->get();
                        @endphp
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
            <div class="modal-footer">
                <!-- PDF Download Button -->
                <a href="{{ route('frontend.transaction.pdf') }}" class="btn btn-outline-danger">Download PDF</a>
            </div>
        </div>
    </div>
</div>

<!-- DataTables & PDF Script -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $('#transactionTable').DataTable(); // DataTables initialize
    });

    function downloadPDF() {
        var element = document.getElementById('transactionTable');
        html2pdf()
            .from(element)
            .save('Transaction_History.pdf');
    }
</script>
