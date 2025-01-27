<!-- resources/views/admin/profile.blade.php -->
@extends('user.sidebar')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">


<style>
    
    body{
        font-family: 'Open Sans', sans-serif;
    }

    h1,
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .table-responsive {
        margin-left: 0;
        margin-right: 0;
        width: 70vw;
        /* Make sure it takes the full viewport width */
    }

    .table {
        width: 100% !important;
        /* Force full width */
    }
    
    .custom-btn {
        background-color: white !important; 
        color: #5271ff !important; 
        border: 2px solid #5271ff !important; 
        
    }

    .custom-btn:hover {
        background-color: #5271ff !important; 
        color: white !important; 
        border-color: #5271ff !important; 
    }

</style>


@section('content')

    <div class="container-fluid">

        <h1 class="mb-4 text-start" style="color: #575b5b;">Documents</h1>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        @endif
        <!-- Global Search form -->
        <div class="d-flex justify-content-start mt-4 mb-2">
            <form action="{{ route('user.document') }}" method="GET" class="input-group" style="max-width: 600px;">
                <input type="text" name="search" class="form-control rounded-pill" placeholder="Search by Document name"
                    value="{{ $searchQuery }}">
                <button type="submit" class="btn custom-btn rounded-pill ms-2">Search</button>
                <button type="button" class="btn custom-btn  rounded-pill ms-2"
                    onClick="window.location.href='{{ route('user.document') }}'">Reset</button>
            </form>
        </div>

        <form action="{{ route('user.storeDocument') }}" method="POST" enctype="multipart/form-data"
    class="needs-validation ms-3" novalidate> 
    @csrf
    <div class="date-range-section p-4 shadow rounded bg-light mb-5" style="max-width: 800px;"> <!-- Adjusted max-width -->
        <!-- Added shadow and background -->
        <div class="row g-3 align-items-start">
            <div class="col-md-6">
                <label for="name" class="form-label fw-semibold">Document Name</label>
                <input type="text" class="form-control" id="name" name="name"
                    placeholder="Enter the name of the document" required>
                <div class="invalid-feedback">
                    Please provide a document name.
                </div>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input type="email" class="form-control bg-light" id="email" name="email"
                    value="{{ $user->email }}" readonly>
            </div>

            <div class="col-md-6">
                <label for="doc_file" class="form-label fw-semibold">Upload Document</label>
                <input type="file" class="form-control" id="doc_file" name="doc_file"
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" required>
                <div class="invalid-feedback">
                    Please select a valid file.
                </div>
                <small class="text-muted mt-1 d-block">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG, XLS,
                    XLSX</small>
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn custom-btn">
                    <i class="bi bi-upload me-2"></i>Upload
                </button>
            </div>
        </div>
    </div>
</form>

        


        </section>




        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle w-100 mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="color: #575b5b;">S.N.</th>
                        <th style="color: #575b5b;">Name</th>
                        {{-- <th>Email</th> --}}
                        <th style="color: #575b5b;">File</th>
                    </tr>
                </thead>
                @foreach ($document as $key => $doc)
                    <tr>

                        <td>{{ ++$key }}</td>
                        <td>{{ $doc->name }}</td>
                        {{-- <td>{{$doc->email}}</td> --}}
                        <td>
                            <a href="{{ Storage::url($doc->path) }}" target="_blank"
                                class="btn btn-outline-primary btn-sm me-1">
                                <i class="fas fa-file-alt"></i>

                                View
                            </a>


                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
<!-- Bootstrap JS (optional, for components like modals and dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
