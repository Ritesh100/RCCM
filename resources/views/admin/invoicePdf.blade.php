<div class="container ">
    <h2 class=" mb-1">Invoice</h2>

    @foreach ($invoices as $key => $invoice)
        @php
            $chargeNames = json_decode($invoice->charge_name, true) ?? [];
            $chargeTotals = json_decode($invoice->charge_total, true) ?? [];
            $totalChargeSum = array_sum($chargeTotals);
        @endphp

        <div class="mb-1">
            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Issued by:</strong><br>
                Admin<br>
                ABN: {{ $admin_abn }}<br></p>
            
            <p><b>Issued on </b>: {{ $issued_on->format('Y-m-d') }}</p>

            <p><strong>Invoice issued to:</strong><br>
                {{ $invoice->invoice_for }}<br>
                {{ $invoice->email }}</p>

           <p> <strong>Charges</strong> 
            @foreach ($chargeNames as $index => $chargeName)
                <p>Charge {{ $index + 1 }}: {{ $chargeName }} - ${{ number_format($chargeTotals[$index] ?? 0, 2) }}</p>
            @endforeach
        </p> 
            <p class="fw-bold">Total Ex GST: ${{ number_format($totalChargeSum, 2) }}</p>
        </div>

        <div class="my-4">
            <p><strong>Additional Information:</strong></p>
            <div class="container">
            <p>Total Credits:<strong> $0</strong></p>
            <p>Previous Invoice Ongoing Fortnightly RC Service Fees Charged:<strong> $0</strong></p>
            <p>Previous Invoice Ongoing Fortnightly RC Service Fees Paid:<strong> $0</strong></p>
            <p>Accumulated Credits:<strong> $0</strong></p>
            <p>Updated Total Credits:<strong> ${{ number_format($credit, 2) }}</strong></p>
        </div>

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

