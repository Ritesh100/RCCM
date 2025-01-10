@extends('admin.sidebar')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4 text-center">Invoice and Credits</h1>
      
        <!-- Global Search form -->
  <div class="d-flex justify-content-center mt-4 mb-2">
    <form action="{{ route('admin.invoice') }}" method="GET" class="input-group" style="max-width: 600px;">
        <input type="text" name="search" class="form-control rounded-pill" 
            placeholder="Search by Company name" value="{{ $searchQuery }}">
        <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
        <button type="button" class="btn btn-secondary rounded-pill ms-2"  
                onClick="window.location.href='{{ route('admin.invoice') }}'">Reset</button>
    </form>
</div>

   
    <div class="">
        <a href="{{ route('admin.createInvoice') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Create Invoice
        </a>
    </div>
    <br><br>
    <div class="table-responsive shadow-lg">
        <table class="table table-hover table-striped table-borderless align-middle w-100"> <!-- Add w-100 for full width -->
            <thead class=" text-black">
        <tr>
            <th>S.N.</th>
            <th>Company Name </th>
            <th>Week Range</th>
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
                    <a class="btn btn-warning btn-sm" 
                       href="{{ route('admin.editInvoice', ['id' => $invoice->id]) }}" 
                       data-bs-toggle="tooltip" title="Edit Invoice">
                       <i class="fas fa-edit me-1"></i> Edit
                    </a>

                <a class="btn btn-danger btn-sm" 
                                   href="{{ route('admin.deleteInvoice', ['id' => $invoice->id]) }}" 
                                   data-bs-toggle="tooltip" title="Delete Invoice"
                                   onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this invoice?')) { document.getElementById('delete-form-{{ $invoice->id }}').submit(); }">
                                   <i class="fas fa-trash-alt"></i> Delete
                                </a>

                                <form id="delete-form-{{ $invoice->id }}" action="{{ route('admin.deleteInvoice', ['id' => $invoice->id]) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                
                        <a class="btn btn-primary btn-sm "
                        href="{{ route('admin.invoicePdf', ['id' => $invoice->id]) }}">
                        <i class="fas fa-file-alt"></i> View </a></td>

               
            </tr>
            </tbody>
        @endforeach

    </table>
    </div>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
