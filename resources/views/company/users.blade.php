@extends('company.sidebar')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .custom-header {
            background: linear-gradient(to right, #6c757d, #adb5bd);
            color: white;
        }
    
        .custom-header th {
            padding: 5px;
            text-align: center;
        }
    </style>
    <h4 class="mt-4">RC </h4> 
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