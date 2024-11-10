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

            <p><strong>Invoice issued to:</strong><br>
                {{ $invoice->invoice_for }}<br>
                {{ $invoice->email }}</p>

           <p> <strong>Charges</strong> 
            @foreach ($chargeNames as $index => $chargeName)
                <p>Charge {{ $index + 1 }}: {{ $chargeName }} - Rs {{ number_format($chargeTotals[$index] ?? 0, 2) }}</p>
            @endforeach
        </p> 
            <p class="fw-bold">Total Ex GST: Rs {{ number_format($totalChargeSum, 2) }}</p>
        </div>

        <div class="my-4">
            <p><strong>Additional Information:</strong></p>
            <div class="container">
            <p>Total Credits:<strong>0</strong></p>
            <p>Previous Invoice Ongoing Fortnightly RC Service Fees Charged:<strong>0</strong></p>
            <p>Previous Invoice Ongoing Fortnightly RC Service Fees Paid:<strong>0</strong></p>
            <p>Accumulated Credits:<strong>0</strong></p>
            <p>Updated Total Credits:<strong>{{ number_format($credit, 2) }}</strong></p>
        </div>

        @if(count($images) > 0)
        <div class="invoice-attachments">
            <h3>Attachments</h3>
            <div class="d-flex">
                @foreach($images as $image)
                    <div class="invoice-image">
                        <img src="data:{{ $image['mime'] }};base64,{{ $image['base64'] }}" 
                             style="width: 100px; height: auto; object-fit: cover; margin: 5px;">
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    

        <footer class="border-top pt-3 mt-4">
            <p class="mb-0"><strong>Contact Information:</strong></p>
            <p>E: support@remotecolleagues.com | P: 0452548517</p>
            <p>BSB: 062-948</p>
            <p>Account Number: 29988838</p>
            <p>Account Holder: Binaya Raj Mahat [To be Updated Soon]</p>
        </footer>
    @endforeach
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <div class="footer">
        <p>Generated on: {{ now() }}</p>
    </div>
</body>
</html>
