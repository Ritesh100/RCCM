@extends('company.sidebar')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

 
        <style>
    body{
        font-family: 'Josefin Sans', sans-serif;
    }

        .custom-header {
            background: linear-gradient(to right, #6c757d, #adb5bd);
            color: white;
        }
    
        .custom-header th {
            padding: 5px;
            text-align: center;
        }
        .custom-btn-white {
        background-color: white !important; /* White background */
        color: #5271ff !important; /* Text color */
        border: 2px solid #5271ff !important; /* Border color matching text */
        font-weight: 600; /* Bold text */
        padding: 0.5rem 1rem; /* Adjust padding for better button size */
        border-radius: 30px; /* Rounded-pill shape */
        transition: all 0.3s ease-in-out; /* Smooth hover effect */
    }

    .custom-btn-white:hover {
        background-color: #5271ff !important; /* Blue background on hover */
        color: white !important; /* White text on hover */
    }
    </style>

    <div class="containe-fluid">
        <h1 class="mb-4 text-left" style="color: #575b5b;">RC Partners</h1>  

        <div class="d-flex justify-content-start mt-4 mb-4">

        <form action="{{ route('company.profile.users') }}" method="GET" class="input-group" style="max-width: 600px;">
            <input type="text" name="search" class="form-control rounded-pill" placeholder="Search by User name"
                   value="{{ request('search') }}">
            <button type="submit" class="btn custom-btn-white rounded-pill ms-2 mb-2">Search</button>
            <button type="button" class="btn custom-btn-white  rounded-pill ms-2 mb-2" 
                    onClick="window.location.href='{{ route('company.profile.users') }}'">Reset</button>
        </form>
    </div>

    
<div class="table-responsive shadow-lg mt-4"> <!-- Added shadow-lg for a shadow effect -->
    <table class="table table-hover table-striped table-borderless align-middle w-100"> <!-- Full width with w-100 -->
        <thead class="text-black">
            <tr>
                <th class="text-center">S.N.</th> <!-- Centered header -->
                <th>RC Name</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody> <!-- Centered text in tbody -->
            @foreach ($users as $index => $user)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td> <!-- Centered index -->
                <td class="font-weight-bold">{{ $user->name }}</td>
                <td class="font-weight-bold">{{ $user->address }}</td>
                <td class="font-weight-bold">{{ $user->contact }}</td>
                <td>{{ $user->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection