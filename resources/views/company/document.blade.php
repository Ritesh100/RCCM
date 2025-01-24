@extends('company.sidebar')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">


@section('content')
<style>
    body{
        font-family: 'Josefin Sans', sans-serif;
    }
    a{
        text-decoration: none;
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
<div class="containe-fluid">
    <h1 class="mb-4 text-left" style="color: #575b5b;">Users Document</h1>
    
    <div class="d-flex justify-content-left mt-4 mb-4">
        <form action="{{ route('company.document') }}" method="GET" class="input-group" style="max-width: 600px; margin: auto;">
            <input type="text" name="search" class="form-control rounded-pill" placeholder="Search by Document name" value="{{ request('search') }}">
            <button type="submit" class="btn custom-btn rounded-pill ms-2">Search</button>
            <button type="button" class="btn custom-btn rounded-pill ms-2" 
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