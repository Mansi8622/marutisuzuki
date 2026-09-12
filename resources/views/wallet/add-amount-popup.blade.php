<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head><div class="modal" id="addAmountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Add Amount</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addAmountForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="request_amount">Enter Amount :</label>
                        <input type="number" class="form-control" name="request_amount" id="request_amount" required>
                    </div>
                    <input type="hidden" name="transaction_type" value="request-amount">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function() {
        $('#addAmountForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize(); // Serialize form data for the request

            $.ajax({
                url: "{{ route('frontend.wallet.requestAmount') }}",
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.success);
                        location.reload(); // Reload the page on success
                    } else {
                        alert(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    });
</script>

