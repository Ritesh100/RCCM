@extends('admin.sidebar')
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">


@section('content')
<style>
    body{
        font-family: 'Open Sans', sans-serif;
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
    <h1 class="mb-4" style="color: #575b5b;">Documents</h1>
</div>

<!-- Global Search Form -->
<div class="d-flex justify-content-start mt-4 mb-4 ms-3">
    <form action="{{ route('admin.document') }}" method="GET" class="input-group" style="max-width: 600px;">
        <input type="text" name="search" class="form-control rounded-pill" 
            placeholder="Search by Document name" value="{{ $searchQuery }}">
        <button type="submit" class="btn custom-btn rounded-pill ms-2">Search</button>
        <button type="button" class="btn custom-btn rounded-pill ms-2" 
                onClick="window.location.href='{{ route('admin.document') }}'">Reset</button>
    </form>
</div>

<!-- Upload Document Form -->
<form action="{{ route('user.storeDocument')}}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    <div class="p-4 shadow rounded bg-light ms-3" style="max-width: 800px;">
        <h4 class="mb-4" style="color: #575b5b;">Upload Document</h4>
        <div class="row g-4">
            <!-- Document Name -->
            <div class="col-md-12">
                <label for="name" class="form-label" style="color: #575b5b;">Document Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter the name of the document" required>
                <div class="invalid-feedback">
                    Please provide a document name.
                </div>
            </div>

            <!-- User Email -->
            <div class="col-md-12">
                <label for="email" class="form-label" style="color: #575b5b;">User Email</label>
                <select class="form-control bg-light" id="email" name="email">
                    <option value="">Select Email</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->email }}">{{ $user->email }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Upload Document -->
            <div class="col-md-12">
                <label for="doc_file" class="form-label" style="color: #575b5b;">Upload Document</label>
                <input type="file" class="form-control" id="doc_file" name="doc_file" 
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" required>
                <div class="invalid-feedback">
                    Please select a valid file.
                </div>
                <small class="text-muted mt-1 d-block">
                    Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG, XLS, XLSX
                </small>
            </div>

            <!-- Submit Button -->
            <div class="col-md-12">
                <button type="submit" class="btn custom-btn">
                    <i class="bi bi-upload me-2"></i>Upload
                </button>
            </div>
        </div>
    </div>
</form>




<div class="table-responsive shadow-lg mt-4">
    <table class="table table-hover table-striped table-borderless align-middle w-100">
        <thead class="text-black text-nowrap">
            <tr>
                <th style="color: #575b5b;">S.N.</th>
                <th style="color: #575b5b;">Document Name</th>
                <th style="color: #575b5b;">Email</th>
                <th style="color: #575b5b;">File</th>
                <th style="color: #575b5b;">Actions</th>
            </tr>
        </thead>

        @foreach ($documents as $key => $doc)
        <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $doc->name }}</td>
            <td>{{ $doc->email }}</td>
            <td>
                <a href="{{ Storage::url($doc->path) }}" target="_blank" class="action-link text-decoration-none me-3">
                    <i class="fas fa-file-alt"></i> View
                </a>
                <a href="{{ Storage::url($doc->path) }}" download="{{ $doc->name }}" class="action-link text-decoration-none">
                    <i class="fas fa-download"></i> Download
                </a>
            </td>
            <td>
                <form action="{{ route('document.delete', $doc->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-link border-0 bg-transparent text-decoration-none" 
                        onclick="return confirm('Are you sure you want to delete this document?')">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
