@extends('company.sidebar') 

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 text-center">Invoice and Credits</h1>

    <!-- Global Search form -->
    <div class="d-flex justify-content-center mt-4 mb-2">
       
    </div>

    <!-- Create Invoice button -->
    <div class="mb-3">
       
    </div>

    <!-- Invoices Table -->
    <div class="table-responsive shadow-lg">
        <table class="table table-hover table-striped table-borderless align-middle w-100">
            <thead class="text-black">
                <tr>
                    <th>S.N.</th>
                    <th>Company Name</th>
                    <th>Week Range</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $key => $invoice)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $invoice->invoice_for }}</td>
                    <td>{{ $invoice->week_range }}</td>
                    <td>{{ $invoice->status}}</td>
                    
                    
                    <td>

                    

                        <a class="btn btn-primary btn-sm" 
                           href="{{ route('company.invoicePdf', ['id' => $invoice->id]) }}">
                           <i class="fas fa-file-alt"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


