@extends('admin.sidebar')

@section('content')
<style>
    a {
        text-decoration: none;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    button {
        margin-right: 5px;
    }
</style>
<div class="container-fluid">
    <h1 class="mb-4 text-center">Documents</h1>
</div>
<table border="1">
    <tr>
        <th>S.N.</th>
        <th>Document Name</th>
        <th>Uploaded By</th>
        <th>File</th>
        <th>Actions</th> <!-- Add an Actions column -->
    </tr>

    @foreach ($documents as $key => $doc)
    <tr>
        <td>{{ ++$key }}</td>
        <td>{{ $doc->name }}</td>
        <td>{{ $doc->email }}</td>
        <td>
            <!-- View button -->
            <a href="{{ Storage::url($doc->path) }}" target="_blank">
                <button>View</button>
            </a>
            <!-- Download button -->
            <a href="{{ Storage::url($doc->path) }}" download="{{ $doc->name }}">
                <button>Download</button>
            </a>
        </td>
        <td>
            <!-- Delete button/form -->
            <form action="{{ route('document.delete', $doc->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete this document?')">
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
