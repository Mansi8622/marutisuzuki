<!doctype html>
<html>
<head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,Arial,sans-serif;color:#172b49;font-size:12px}.head{background:#172b49;color:#fff;padding:18px;border-radius:8px}.head h1{margin:0;font-size:24px}.badge{display:inline-block;background:#fff5db;color:#8a5b00;padding:6px 10px;border-radius:20px;font-weight:bold}.grid{width:100%;margin:18px 0;border-collapse:collapse}.grid td,.grid th{border:1px solid #dfe7f0;padding:9px;text-align:left}.grid th{background:#f4f7fb;text-transform:uppercase;font-size:10px}.note{background:#fff8e6;border:1px solid #f0d58a;padding:10px;border-radius:6px;margin-top:18px}</style></head>
<body>
  <div class="head"><h1>Replacement Invoice</h1><p>This invoice is for replacement only. Amount is not applicable.</p></div>
  <p><span class="badge">Replacement Copy</span></p>
  <table class="grid"><tr><th>Replacement No</th><td>{{ $replacement->id }}</td><th>Order No</th><td>{{ $replacement->order_number }}</td></tr><tr><th>Date</th><td>{{ optional($replacement->created_at)->format('d M Y') }}</td><th>Status</th><td>{{ ucfirst($replacement->status ?? 'Pending') }}</td></tr><tr><th>Company</th><td>{{ $replacement->company->company_name ?? 'N/A' }}</td><th>Customer</th><td>{{ $replacement->customer_name }}</td></tr></table>
  <table class="grid"><thead><tr><th>Product</th><th>Quantity</th><th>Issue</th></tr></thead><tbody><tr><td>{{ $replacement->product->name ?? 'N/A' }}</td><td>{{ $replacement->quantity }}</td><td>{{ $replacement->issues ?? 'N/A' }}</td></tr></tbody></table>
  <table class="grid"><tr><th>Contact Phone</th><td>{{ $replacement->customer_phone }}</td><th>Contact Email</th><td>{{ $replacement->customer_email ?? 'N/A' }}</td></tr><tr><th>Notes</th><td colspan="3">{{ $replacement->customer_notes ?? $replacement->notes ?? 'N/A' }}</td></tr></table>
  <div class="note"><strong>No amount shown:</strong> this document confirms replacement processing against the original invoice/order only.</div>
</body>
</html>
