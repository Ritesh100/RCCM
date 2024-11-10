<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-header { text-align: center; font-size: 20px; margin-bottom: 20px; }
        .invoice-details, .charge-details { margin-bottom: 20px; }
        .invoice-details td, .charge-details td { padding: 5px; }
        .charges-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .charges-table th, .charges-table td { border: 1px solid #ddd; padding: 8px; }
        .charges-table th { text-align: left; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; }
        .invoice-images { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h2>Invoice #{{ $invoice->invoice_number }}</h2>
        <p>For the period: {{ $invoice->week_range }}</p>
    </div>

    <div class="invoice-details">
        <table>
            <tr>
                <td><strong>Invoice From:</strong> {{ $invoice->invoice_from }}</td>
                <td><strong>Email:</strong> {{ $invoice->email }}</td>
            </tr>
            <tr>
                <td><strong>Invoice Address:</strong> {{ $invoice->invoice_address_from }}</td>
                <td><strong>Invoice For:</strong> {{ $invoice->invoice_for }}</td>
            </tr>
            <tr>
                <td><strong>Contact Email:</strong> {{ $invoice->contact_email }}</td>
                <td><strong>Total Charges:</strong> {{ $invoice->total_charge }}</td>
            </tr>
            <tr>
                <td><strong>Total Transferred:</strong> {{ $invoice->total_transferred }}</td>
                <td><strong>Previous Credits:</strong> {{ $invoice->previous_credits }}</td>
            </tr>
        </table>
    </div>

    <div class="charge-details">
        <h3>Charge Details:</h3>
        <table class="charges-table">
            <thead>
                <tr>
                    <th>Charge Name</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chargeNames as $index => $chargeName)
                    <tr>
                        <td>{{ $chargeName }}</td>
                        <td>{{ $chargeTotals[$index] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="invoice-images">
        <h3>Invoice Images:</h3>
        @foreach($imagePaths as $imagePath)
    <img src="{{ asset('storage/' . $imagePath) }}" alt= imagePath>
@endforeach

    </div>
    

    <div class="footer">
        <p>Generated on: {{ now() }}</p>
    </div>
</body>
</html>
