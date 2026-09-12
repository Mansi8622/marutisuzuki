<!-- Modal -->
<div class="modal fade" id="payout" tabindex="-1" aria-labelledby="payoutLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Request Payout</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="payoutform" method="POST" action="{{ route('frontend.wallet.requestAmount') }}">
    @csrf
    <div class="modal-body">
        <!-- Credit Line Orders Dropdown -->
        <div class="mb-3" id="creditLineOrdersContainer" style="display: none;">
            <label for="creditLineOrderSelect">Select Credit Line Order:</label>
            <select id="creditLineOrderSelect" name="order_id" class="form-control mb-3" required>
                <option value="">-- Select an Order --</option>
            </select>

            <label>Total Invoice Amount:</label>
            <input type="text" id="orderTotalAmount" class="form-control mb-2" readonly name="total_amount">

            <label>Created At:</label>
            <input type="text" id="orderCreatedAt" class="form-control mb-2" readonly>
        </div>

        <!-- Enter Payout Amount -->
        <div class="mb-3">
            <label for="payout_amount" class="form-label">Enter Amount To Pay:</label>
            <input type="number" class="form-control" name="request_amount" id="payout_amount" max="{{ $dueAmount }}" required>
        </div>

        <!-- Hidden Transaction Type -->
        <input type="hidden" name="transaction_type" value="payout">

        <!-- Hidden Razorpay Payment ID -->
        <input type="hidden" id="razorpay_payment_id" name="razorpay_payment_id">
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="payNowBtn">Submit</button>
    </div>
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('payNowBtn').addEventListener('click', function(e) {
    e.preventDefault();

    let dueAmount = {{ $dueAmount }};

    let amount = document.getElementById('payout_amount').value;
    let orderId = document.getElementById('creditLineOrderSelect').value;
    let totalAmount = document.getElementById('orderTotalAmount').value;

    let payoutAmount = parseFloat(document.getElementById('payout_amount').value);

// Check if payoutAmount exceeds the dueAmount
if (payoutAmount > dueAmount) {
    // Show an alert if the entered amount exceeds the dueAmount
    alert("The amount you are trying to pay exceeds the available due amount.");
    return;  // Prevent form submission
}

    if (!amount || !orderId) {
        alert("Please enter amount and select order.");
        return;
    }

    var options = {
        key: "{{ config('services.razorpay.key') }}", // 🔐 using key from .env via config
        amount: amount * 100, // in paisa
        currency: "INR",
        name: "Your App Name",
        description: "Payout for Order ID: " + orderId,
        handler: function (response) {
            // Set Razorpay payment ID in the hidden input
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;

            // Now, submit the form
            document.getElementById('payoutform').submit();
        },
        modal: {
            ondismiss: function () {
                alert("Payment was cancelled.");
            }
        },
        theme: {
            color: "#3399cc"
        }
    };

    var rzp1 = new Razorpay(options);
    rzp1.open();
});
</script>






      </div>
  </div>
</div>
<!-- Request Payout Modal -->
<div class="modal fade" id="payout" tabindex="-1" aria-labelledby="payoutLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="payoutform" method="POST" action="{{ route('frontend.wallet.requestAmount') }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="payoutLabel">Request Payout</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
  
          <div class="modal-body">
            <!-- Order Dropdown -->
            <div class="mb-3" id="creditLineOrdersContainer" style="display: none;">
              <label for="creditLineOrderSelect">Select Credit Line Order:</label>
              <select id="creditLineOrderSelect" name="order_number" class="form-control mb-3" required>
                <option value="">-- Select an Order --</option>
              </select>
  
              <label>Total Amount:</label>
              <input type="text" id="orderTotalAmount" class="form-control mb-2" readonly name="total_amount">
  
              <label>Paid Amount:</label>
              <input type="text" id="orderPaidAmount" class="form-control mb-2" readonly>
  
              <label>Remaining Amount:</label>
              <input type="text" id="orderRemainingAmount" class="form-control mb-2" readonly>
  
              <label>Created At:</label>
              <input type="text" id="orderCreatedAt" class="form-control mb-2" readonly>
  
              <input type="hidden" id="orderTransactionId" name="transaction_id" value="">
            </div>
  
            <!-- Enter Payout Amount -->
            <div class="mb-3">
              <label for="payout_amount" class="form-label">Enter Amount:</label>
              <input type="number" class="form-control" name="request_amount" id="payout_amount" required>
            </div>
  
            <!-- Hidden Transaction Type -->
            <input type="hidden" name="transaction_type" value="payout">
          </div>
  
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const payoutModal = document.getElementById('payout');
  
      payoutModal.addEventListener('show.bs.modal', function () {
        fetch("{{ route('wallet.credit.line.orders') }}")
          .then(response => response.json())
          .then(data => {
            const container = document.getElementById('creditLineOrdersContainer');
            const select = document.getElementById('creditLineOrderSelect');
  
            if (data.orders && data.orders.length > 0) {
              container.style.display = 'block';
              select.innerHTML = '<option value="">-- Select an Order --</option>';
  
              data.orders.forEach(order => {
                const option = document.createElement('option');
                option.value = order.order_number;
                option.dataset.amount = order.total_amount || 0;
                option.dataset.created = order.created_at || 'N/A';
                option.dataset.transaction = order.transaction_id || '';
                option.dataset.paid = order.paid_amount || 0;
                option.dataset.remaining = order.remaining_amount || 0;
                option.textContent = `${order.order_number} - ₹${order.remaining_amount} Remaining`;
  
                select.appendChild(option);
              });
            } else {
              container.style.display = 'none';
              select.innerHTML = '<option value="">No orders available</option>';
            }
          })
          .catch(error => {
            console.error("Error fetching orders:", error);
          });
      });
  
      document.getElementById('creditLineOrderSelect').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
  
        const total = parseFloat(selected.dataset.amount || 0);
        const paid = parseFloat(selected.dataset.paid || 0);
        const remaining = parseFloat(selected.dataset.remaining || 0);
  
        document.getElementById('orderTotalAmount').value = total.toFixed(2);
        document.getElementById('orderPaidAmount').value = paid.toFixed(2);
        document.getElementById('orderRemainingAmount').value = remaining.toFixed(2);
        document.getElementById('orderCreatedAt').value = selected.dataset.created || '';
        document.getElementById('orderTransactionId').value = selected.dataset.transaction || '';
  
        const payoutInput = document.getElementById('payout_amount');
        payoutInput.max = remaining > 0 ? remaining : total;
        payoutInput.value = '';
  
        if (remaining <= 0) {
          alert("This order has already been fully paid.");
          payoutInput.disabled = true;
        } else {
          payoutInput.disabled = false;
        }
      });
    });
  </script>
  