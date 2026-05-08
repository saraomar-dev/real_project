<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body { font-family: sans-serif; padding: 30px; }
        .invoice-box { border: 1px solid #eee; padding: 20px; max-width: 800px; margin: auto; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        table { width: 100%; text-align: left; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; }
        .total { font-weight: bold; font-size: 20px; color: #4CAF50; }
        .print-btn { background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div>
                <h2>COMMUNITY GARDEN</h2>
                <p>Official Rental Invoice</p>
            </div>
            <div style="text-align: right;">
                <button onclick="window.print()" class="print-btn">Print / Save as PDF</button>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <p><strong>To:</strong> {{ $invoice->user->name }}</p>
            <p><strong>Email:</strong> {{ $invoice->user->email }}</p>
            <p><strong>Date:</strong> {{ $invoice->created_at->format('Y-m-d') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Plot Number</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Land Rental Fee</td>
                    <td>#{{ $invoice->plot->plot_number }}</td>
                    <td>{{ number_format($invoice->amount, 2) }} LE</td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 20px;">
            <p class="total">Total: {{ number_format($invoice->amount, 2) }} LE</p>
            <p>Status: <span style="color: {{ $invoice->status == 'paid' ? 'green' : 'red' }}">{{ strtoupper($invoice->status) }}</span></p>
        </div>
    </div>
</body>
</html>