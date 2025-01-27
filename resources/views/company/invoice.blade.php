@extends('company.sidebar') 
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">


@section('content')
<style>
    body{
        font-family: 'Open Sans', sans-serif;
    }
    .action-link {
        color: #5271ff !important;
        font-size: 16px;
        text-decoration: none;
    }

    .action-link:hover {
        text-decoration: underline;
    }
</style>
<div class="container-fluid">
    <h1 class="mb-4 text-left" style="color: #575b5b;" >Invoice and Credits</h1>

    <!-- Global Search form -->
    <div class="d-flex justify-content-start mt-4 mb-2">
       
    </div>

    <!-- Create Invoice button -->
    <div class="mb-3">
       
    </div>

    <!-- Invoices Table -->
    <div class="table-responsive shadow-lg">
        <table class="table table-hover table-striped table-borderless align-middle w-100">
            <thead class="text-box" style="color: #575b5b;">
                <tr style="color: #575b5b;">
                    <th style="color: #575b5b;">S.N.</th>
                    <th style="color: #575b5b;">Company Name</th>
                    <th style="color: #575b5b;">Week Range</th>
                    <th style="color: #575b5b;">Status</th>
                    <th style="color: #575b5b;">Action</th>
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

                    

                        <a class="action-link me-3" 
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


