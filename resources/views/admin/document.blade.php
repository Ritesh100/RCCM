@extends('admin.sidebar')

@section('content')

<div class="container-fluid">
    <h1 class="mb-4 text-center">Documents</h1>
</div>
<div class="table-responsive shadow-lg mt-4"> <!-- Added shadow-lg for a shadow effect -->
    <table class="table table-hover table-striped table-borderless align-middle w-100"> <!-- Full width with w-100 -->
        <thead class="text-black">
          
    <tr>
        <th>S.N.</th>
        <th>Document Name</th>
        <th>Uploaded By</th>
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
            <a href="{{ Storage::url($doc->path) }}" target="_blank" class="btn btn-outline-primary btn-sm me-1 ">
                <i class="fas fa-file-alt"></i> View
            </a>
            
            <!-- Download button -->
            <a href="{{ Storage::url($doc->path) }}" download="{{ $doc->name }}" class="btn btn-outline-success btn-sm me-1">
                <i class="fas fa-download"></i> Download
            </a>
        </td>
        <td>
            <!-- Delete button/form -->
<form action="{{ route('document.delete', $doc->id) }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger btn-sm me-1 " onclick="return confirm('Are you sure you want to delete this document?')">
        <i class="fas fa-trash-alt"></i> Delete
    </button>
</form>

        </td>
    </tr>
    @endforeach
</table>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

