
    <div class="container-fluid">
        
        <h2>Invoice</h2>
        <table border="1">
            <tr>
                <th>S.N.</th>
                <th>Name</th>
                <th>User Email</th>

                {{-- Generate headers for charge names and totals based on the first invoice --}}
                @php
                    $maxChargeNames = count($charge_names[0] ?? []);
                    $maxChargeTotals = count($charge_totals[0] ?? []);
                @endphp

                @for ($i = 1; $i <= $maxChargeNames; $i++)
                    <th>Charge Name {{ $i }}</th>
                @endfor

                @for ($i = 1; $i <= $maxChargeTotals; $i++)
                    <th>Charge Total {{ $i }}</th>
                @endfor
                <th>Total Invoice Amount</th>
                <th>Total Transferred</th>
                <th>Total Charged for RC</th>
                <th>Credit</th>
            </tr>

            @foreach ($invoices as $key => $invoice)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $invoice->invoice_for }}</td>
                    <td>{{ $invoice->email }}</td>

                    {{-- Display charge names for each invoice --}}
                    @php
                        $chargeNames = json_decode($invoice->charge_name, true) ?? [];
                        $chargeTotals = json_decode($invoice->charge_total, true) ?? [];
                        $totalChargeSum = array_sum($chargeTotals); // Calculate the total of all charge totals
                    @endphp

                    {{-- Display charge names for each invoice --}}
                    @for ($i = 0; $i < $maxChargeNames; $i++)
                        <td>{{ $chargeNames[$i] ?? '' }}</td>
                    @endfor

                    {{-- Display charge totals for each invoice --}}
                    @for ($i = 0; $i < $maxChargeTotals; $i++)
                        <td>{{ $chargeTotals[$i] ?? '' }}</td>
                    @endfor

                    {{-- Display the total sum of charge totals --}}
                    <td>{{ $totalChargeSum }}</td>

                    <td>{{ $invoice->total_transferred }}</td>
                    <td>{{ $invoice->total_charge }}</td>
                    <td>{{ $credit }}</td>
                </tr>
            @endforeach
        </table>

    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
