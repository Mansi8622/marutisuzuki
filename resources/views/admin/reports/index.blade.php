@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12" style="margin-top:20px !important;">
        <div class="panel panel-default">
        </div>
    </div>
</div>

<!-- Form for selecting vendor and date range -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="card-header">
                        <h4>Transaction Report</h4>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.report.show') }}">
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="vendor_id">Select Vendor</label>
                            <select name="vendor_id" id="vendor_id" class="form-control" required>
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->vendor_id }}" 
                                        {{ request('vendor_id') == $vendor->vendor_id ? 'selected' : '' }}>
                                        Vendor ID: {{ $vendor->vendor_id }} (Status: {{ $vendor->status }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
            
                        <div class="form-group col-lg-4">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}" required>
                        </div>
            
                        <div class="form-group col-lg-4">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}" required>
                        </div>
            
                        <div class="col-lg-12 mt-2">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </div>
                </form>

                <!-- Show report only if transactions exist -->
                @if(isset($transactions) && count($transactions) > 0)
                    <p><strong>Welcome Amount:</strong> ₹{{ number_format($wallet->welcome_amount, 2) }}</p>
                    <p><strong>Due Amount:</strong> ₹{{ number_format($wallet->due, 2) }}</p>

                    <h5>Transaction Summary (from {{ $startDate }} to {{ $endDate }})</h5>
                    <p><strong>Total Transactions:</strong> {{ $transactionCount }}</p>
                    <p><strong>Total Transaction Amount:</strong> ₹{{ number_format($totalTransactionAmount, 2) }}</p>

                    <h5>Transaction Details:</h5>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover datatable datatable-WalletRequest">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $transaction->id }}</td>
                                            <td>₹{{ number_format($transaction->request_amount, 2) }}</td>
                                            <td>{{ $transaction->status }}</td>
                                            <td class="{{ $transaction->transaction_type == 'request-amount' ? 'text-danger' : 'text-success' }}">
                                                {{ $transaction->transaction_type == 'request-amount' ? 'Debit' : 'Credit' }}
                                            </td>
                                            <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <p class="text-center">No transactions found for the selected period.</p>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 100,
        });

        $('.datatable-WalletRequest').DataTable({ buttons: dtButtons });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection