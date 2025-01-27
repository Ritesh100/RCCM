@extends('company.sidebar')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">



@section('content')
<style>
    body{
        font-family: 'Open Sans', sans-serif;
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
    .action-link {
        color: #5271ff !important;
        font-size: 16px;
        text-decoration: none;
    }

    .action-link:hover {
        text-decoration: underline;
    }
</style>
<div class="containe-fluid">
    <h1 class="mb-4 text-left" style="color: #575b5b;">Users Document</h1>
    
    <div class="d-flex justify-content-start mt-4 mb-4 ms-3">
        <form action="{{ route('company.document') }}" method="GET" class="input-group w-50">
            <input type="text" name="search" class="form-control rounded-pill" placeholder="Search by Document name" value="{{ request('search') }}">
            <button type="submit" class="btn custom-btn rounded-pill ms-2">Search</button>
            <button type="button" class="btn custom-btn rounded-pill ms-2" 
                    onClick="window.location.href='{{ route('company.document') }}'">Reset</button>
        </form>
    </div>
    

<div class="table-responsive shadow-lg mt-4"> 
    <table class="table table-hover table-striped table-borderless align-middle w-100"> 
        <thead class="text-black">
        <tr>

        <th style="color: #575b5b;">S.N.</th>
        <th style="color: #575b5b;">Document Name</th>
        <th style="color: #575b5b;">Uploaded By</th>
        <th style="color: #575b5b;">File</th>
    </tr>
</thead>
    @foreach ($documents as $key=>$doc)
    <tr>
       
        <td>{{++$key}}</td>
        <td>{{$doc->name}}</td>
        <td>{{$doc->email}}</td>
        <td>
            <a href="{{ Storage::url($doc->path) }}" target="_blank" class="action-link me-3  me-1" title="View">
                <i class="fas fa-file-alt"></i>View
            </a>
            <a href="{{ Storage::url($doc->path) }}" download="{{$doc->name}}" class="action-link me-3" title="Download">
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