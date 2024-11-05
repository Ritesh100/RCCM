@extends('company.sidebar')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">


@section('content')
<style>
    a{
        text-decoration: none;
    }
</style>
<div class="containe-fluid">
    <h1 class="mb-4 text-center">Users Document</h1>
    
    <div class="d-flex justify-content-center mt-4 mb-4">
        <form action="{{ route('company.document') }}" method="GET" class="input-group" style="max-width: 600px; margin: auto;">
            <input type="text" name="search" class="form-control rounded-pill" placeholder="Search by Document name" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
            <button type="button" class="btn btn-secondary rounded-pill ms-2" 
                    onClick="window.location.href='{{ route('company.document') }}'">Reset</button>
        </form>
    </div>

<div class="table-responsive shadow-lg mt-4"> <!-- Added shadow-lg for a shadow effect -->
    <table class="table table-hover table-striped table-borderless align-middle w-100"> <!-- Full width with w-100 -->
        <thead class="text-black">
        <tr>

        <th>S.N.</th>
        <th>Document Name</th>
        <th>Uploaded By</th>
        <th>File</th>
    </tr>
</thead>
    @foreach ($documents as $key=>$doc)
    <tr>
       
        <td>{{++$key}}</td>
        <td>{{$doc->name}}</td>
        <td>{{$doc->email}}</td>
        <td>
            <a href="{{ Storage::url($doc->path) }}" target="_blank" class="btn btn-outline-primary btn-sm me-1" title="View">
                <i class="fas fa-file-alt"></i>View
            </a>
            <a href="{{ Storage::url($doc->path) }}" download="{{$doc->name}}" class="btn btn-outline-success btn-sm" title="Download">
                <i class="fas fa-download"></i>Download
            </a>
        </td>
    </tr>
    @endforeach
</table>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

@endsection