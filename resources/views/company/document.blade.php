@extends('company.sidebar')

@section('content')
<style>
    a{
        text-decoration: none;
    }
</style>
<table border="1">
    <tr>
        <th>S.N.</th>
        <th>Document Name</th>
        <th>Uploaded By</th>
        <th>File</th>
    </tr>
    @foreach ($documents as $key=>$doc)
    <tr>
       
        <td>{{++$key}}</td>
        <td>{{$doc->name}}</td>
        <td>{{$doc->email}}</td>
        <td>
            <a href="{{ Storage::url($doc->path) }}" target="_blank">
                <button>View</button>
            </a>
            <a href="{{ Storage::url($doc->path) }}" download='{{$doc->name}}'>
                <button>download</button>
            </a>
        </td>
    </tr>
    @endforeach
</table>
@endsection