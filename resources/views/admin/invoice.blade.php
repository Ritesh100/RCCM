@extends('admin.sidebar')
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

@section('content')
<style>
    body {
        font-family: 'Josefin Sans', sans-serif;
    }

    .btn-custom {
        background-color: white !important;
        color: #5271ff !important;
        border: 2px solid #5271ff !important;
    }

    .btn-custom:hover {
        background-color: #5271ff !important;
        color: white !important;
    }

    .action-link {
        color: #5271ff !important;
        font-size: 16px;
        text-decoration: none;
    }

    .action-link:hover {
        text-decoration: underline;
    }
    .container-fluid {
        padding-left: 20px; /* Space between sidebar and content */
    }
    h1 {
        margin-bottom: 30px; /* Added space below the heading */
        color: #575b5b; /* Less dark color for heading */
    }

    .search-container {
        margin-bottom: 30px; /* Space below the search bar */
    }

    .form-control {
        height: 40px; /* Consistent height for input field */
    }

    .table-responsive {
        margin-top: 20px; /* Added spacing above the table */
    }
    .table-head {
        color: #575b5b;
    }
</style>

<div class="container-fluid">
    <h1 class="mb-4 text-left" style="color: #575b5b;">Invoice and Credits</h1>

    <!-- Global Search Form -->
    <div class="d-flex justify-content-start mt-4 mb-2">
        <form action="{{ route('admin.invoice') }}" method="GET" class="input-group" style="max-width: 600px;">
            <input type="text" name="search" class="form-control rounded-pill" 
                placeholder="Search by Company name" value="{{ $searchQuery }}">
            <button type="submit" class="btn btn-custom rounded-pill ms-2">Search</button>
            <button type="button" class="btn btn-custom rounded-pill ms-2"  
                    onClick="window.location.href='{{ route('admin.invoice') }}'">Reset</button>
        </form>
    </div>

    <!-- Create Invoice Button -->
    <div class="mb-4">
        <a href="{{ route('admin.createInvoice') }}" class="btn btn-custom">
            <i class="fas fa-plus"></i> Create Invoice
        </a>
    </div>

    <!-- Timesheet Table -->
    <div class="table-responsive shadow-lg">
        <table class="table table-hover table-striped table-borderless align-middle w-100">
            <thead class="table-head" style="color: #575b5b;">
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
                    <td>
                        <span class="badge {{ $invoice->status === 'Paid' ? 'bg-success' : 'bg-warning' }}">
                            {{ $invoice->status }}
                        </span>
                        
                    </td>
                    
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('admin.editInvoice', ['id' => $invoice->id]) }}"
                            class="action-link me-3"  title="Edit Invoice">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>

                        <!-- Delete Button -->
                        <a href="{{ route('admin.deleteInvoice', ['id' => $invoice->id]) }}" 
                            class="action-link me-3" style="font-size:16px; text-decoration:none;" title="Delete Invoice"
                            onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this invoice?')) { document.getElementById('delete-form-{{ $invoice->id }}').submit(); }">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                        <form id="delete-form-{{ $invoice->id }}" action="{{ route('admin.deleteInvoice', ['id' => $invoice->id]) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        <!-- View Button -->
                        <a href="{{ route('admin.invoicePdf', ['id' => $invoice->id]) }}"
                            class="action-link me-3" style="font-size:16px; text-decoration:none;" title="View Invoice">
                            <i class="fas fa-file-alt me-1"></i> View 
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
