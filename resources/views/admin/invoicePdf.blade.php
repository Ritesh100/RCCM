<style>
    p{
        font-size: 15px;
    }
</style>
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

                <p><strong>Charges</strong></p>
                @foreach ($chargeNames as $index => $chargeName)
                    <p>Charge {{ $index + 1 }}: &nbsp;
                        <span style="font-style: italic;">{{ $chargeName }}</span>
                        <span style="float: right;">Rs {{ number_format($chargeTotals[$index] ?? 0, 2) }}</span>
                    </p>
                @endforeach
                
            <p class="fw-bold">Total Ex GST:  <span style="float: right;">
                Rs {{ number_format($totalChargeSum, 2) }}</span></p>
        </div>

        <div class="my-4">
            <p><strong>Additional Information:</strong></p>
            <div class="container">
            <p>Previous Credit:<strong> Rs {{ number_format($previousCredit, 2) }}</strong></p>
            <p>Accumulated Credit:<strong> Rs {{ number_format($accumulatedCredit, 2) }}</strong></p>
            <p> Total Credit:<strong>Rs {{ number_format($credit, 2) }}</strong></p>
        </div>

        @if(count($images) > 0)
        <div class="invoice-attachments">
            <h3>Attachments</h3>
            <div class="d-flex">
                @foreach($images as $image)
                    <div class="invoice-image">
                        <img src="data:{{ $image['mime'] }};base64,{{ $image['base64'] }}" 
                             style="width: 500px; height: auto; object-fit: cover; margin: 5px;">
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    

        <footer class="border-top pt-3 mt-4">
            <p class="mb-0"><strong>Contact Information:</strong></p>
            <p>E: support@remotecolleagues.com | P: 0452548517</p>
            <p>BSB: 067873</p>
            <p>Account Number: 13645434</p>
            <p>Account Holder: Remote Colleagues</p>
        </footer>
    @endforeach
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

