<!-- resources/views/admin/profile.blade.php -->
@extends('user.sidebar')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
 body {
        background-color: #f0f2f5; /* Light gray background */
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh; /* Full viewport height */
        padding: 20px; /* Add padding for better spacing */
    }
    .container{
            width:1000px;
        }

    .form-container {
        background-color: #ffffff; /* White background for form */
        padding: 30px;
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); /* Subtle shadow */
    }

h1, h2 {
    text-align: center;
    margin-bottom: 20px;
}
.table-responsive {
    margin-left: 0;
    margin-right: 0;
    width: 70vw; /* Make sure it takes the full viewport width */
}

.table {
    width: 100% !important; /* Force full width */
}

</style>


@section('content')
   
    <div class="container-fluid">
        @if ($errors->any())
               
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                
            @endif

        <div class="row justify-content-center mb-3">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center mb-4">Upload Document</h2>
                    <hr>

                    <form action="{{ route('user.storeDocument')}}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Document Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter the name of the document" required>
                            <div class="invalid-feedback">
                                Please provide a document name.
                            </div>
                        </div>
        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control bg-light" id="email" name="email" value="{{ $user->email }}" readonly>
                        </div>
        
                        <div class="mb-4">
                            <label for="doc_file" class="form-label fw-semibold">Upload Document</label>
                            <input type="file" class="form-control" id="doc_file" name="doc_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" required>
                            <div class="invalid-feedback">
                                Please select a valid file.
                            </div>
                            <small class="text-muted mt-1 d-block">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG, XLS, XLSX</small>
                        </div>
        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-upload me-2"></i>Upload
                            </button>
                        </div>
                    </form>
                    
                </div>
            </section>
        
           
        
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle w-100 mb-0">
                    <thead class="bg-light">
                    <tr >
                    <th>S.N.</th>
                    <th>Name</th>
                    {{-- <th>Email</th> --}}
                    <th>File</th>
                </tr>
                </thead>
                @foreach ($document as $key=>$doc)
                <tr>
                   
                    <td>{{++$key}}</td>
                    <td>{{$doc->name}}</td>
                    {{-- <td>{{$doc->email}}</td> --}}
                    <td>
                        <a href="{{ Storage::url($doc->path) }}" target="_blank">
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
