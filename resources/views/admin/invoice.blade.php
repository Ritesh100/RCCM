@extends('admin.sidebar')

@section('content')

<div class="container-fluid">
    <h1>Invoice and Credits</h1></div>
    <div class="mt-4">
        <a href="{{ route('admin.createInvoice') }}" class="btn btn-primary">
            Create Invoice
        </a>
    </div>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

