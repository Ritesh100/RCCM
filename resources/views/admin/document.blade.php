@extends('admin.sidebar')
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
@section('content')
<style>
    body{
        font-family: 'Josefin Sans', sans-serif;
    }
</style>
<div class="container-fluid">
    <h1 class="mb-4 text-center">Documents</h1>
</div>
  <!-- Global Search form -->
  <div class="d-flex justify-content-center mt-4 mb-4">
    <form action="{{ route('admin.document') }}" method="GET" class="input-group" style="max-width: 600px;">
        <input type="text" name="search" class="form-control rounded-pill" 
            placeholder="Search by Document name" value="{{ $searchQuery }}">
        <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
        <button type="button" class="btn btn-secondary rounded-pill ms-2"  
                onClick="window.location.href='{{ route('admin.document') }}'">Reset</button>
    </form>
</div>
<form action="{{ route('user.storeDocument')}}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    <div class="date-range-section p-4 shadow rounded bg-light mx-auto"  style="max-width: 1000px;">
        <h4 class="text-center mb-4">Documents</h4>
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <label for="name" class="form-label fw-semibold">Document Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter the name of the document" required>
                <div class="invalid-feedback">
                    Please provide a document name.
                </div>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label fw-semibold">User Email </label>
                <select class="form-control bg-light" id="email" name="email">
                    <option value="">Select Email</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->email }}">{{ $user->email }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="doc_file" class="form-label fw-semibold">Upload Document</label>
                <input type="file" class="form-control" id="doc_file" name="doc_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" required>
                <div class="invalid-feedback">
                    Please select a valid file.
                </div>
                <small class="text-muted mt-1 d-block">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG, XLS, XLSX</small>
            </div>

            <div class="">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload me-2"></i>Upload
                </button>
            </div>
        </div>
    </div>
</form>


<div class="table-responsive shadow-lg mt-4"> <!-- Added shadow-lg for a shadow effect -->
    <table class="table table-hover table-striped table-borderless align-middle w-100"> <!-- Full width with w-100 -->
        <thead class="text-black text-nowrap">
          
    <tr>
        <th>S.N.</th>
        <th>Document Name</th>
        <th>Email</th>
        <th>File</th>
        <th>Actions</th> <!-- Add an Actions column -->
    </tr>
        </thead>

    @foreach ($documents as $key => $doc)
    <tr>
        <td>{{ ++$key }}</td>
        <td>{{ $doc->name }}</td>
        <td>{{ $doc->email }}</td>
        <td>
            <!-- View button -->
        <a href="{{ Storage::url($doc->path) }}" target="_blank" class="text-primary text-decoration-none me-3">
            <i class="fas fa-file-alt"></i> View
        </a>

        <!-- Download button -->
        <a href="{{ Storage::url($doc->path) }}" download="{{ $doc->name }}" class="text-success text-decoration-none">
            <i class="fas fa-download"></i> Download
        </a>
            
        </td>
        <td>
            <!-- Delete button/form -->
            <form action="{{ route('document.delete', $doc->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-danger border-0 bg-transparent text-decoration-none" onclick="return confirm('Are you sure you want to delete this document?')">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </form>

        </td>
    </tr>
    @endforeach
</table>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>