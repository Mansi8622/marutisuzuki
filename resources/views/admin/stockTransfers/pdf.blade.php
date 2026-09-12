<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Transfer Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            background: #f9f9f9;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
        }
        .invoice-header p {
            margin: 0;
        }
        .invoice-details {
            margin-top: 20px;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-details th, .invoice-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-footer {
            margin-top: 20px;
            text-align: center;
        }
        .button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Stock Transfer Invoice</h1>
            <p><strong>Transfer ID:</strong> {{ $stockTransfer->id }}</p>
            <p><strong>Date:</strong> {{ $stockTransfer->created_at->format('d-m-Y') }}</p>
        </div>
        
        <div class="invoice-details">
            <h2>User Details:</h2>
            <p><strong>Name:</strong> {{ $stockTransfer->select_user->name ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $stockTransfer->select_user->phone ?? 'N/A' }}</p>

            <h2>Products Transferred:</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product SKU</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockTransfer->select_products as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->pivot->quantity ?? 'N/A' }}</td> <!-- This will show the quantity for each product -->
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="invoice-footer">
            <p>&copy; {{ date('Y') }} Your Company</p>
        </div>
    </div>

    <button class="button" id="download-pdf">Download PDF</button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script>
        document.getElementById('download-pdf').addEventListener('click', function () {
            var element = document.querySelector('.invoice-container');
            html2pdf()
                .from(element)
                .save('stock_transfer_invoice.pdf');
        });
    </script>
</body>
</html>
