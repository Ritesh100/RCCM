@extends('admin.sidebar')

@section('content')
    <div class="container-fluid">
        <h1>Invoice and Credits</h1>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.createInvoice') }}" class="btn btn-primary">
            Create Invoice
        </a>
    </div>
    <br><br>
    <table border="1">
        <tr>
            <th>S.N.</th>
            <th>Week Range</th>
            <th>Action</th>
        </tr>
        @foreach ($invoices as $key => $invoice)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $invoice->week_range }}</td>
                <td><a href="{{ route('admin.invoicePdf', ['id' => $invoice->id]) }}"><button>View Invoice</button></a></td>
            </tr>
        @endforeach

    </table>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
