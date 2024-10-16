@extends('company.sidebar')
@section('content')
<table border="1">
    <tr>
        <td>S.N.</td>
        <td>RC name</td>
        <td>address</td>
        <td>contact</td>
        <td>email</td>
    </tr>
    @foreach ($users as $user)
   <tr>
        
            <td>-</td>
            <td>{{$user->name}}</td>
            <td>{{$user->address}}</td>
            <td>{{$user->contact}}</td>
            <td>{{$user->email}}</td>
        
   </tr>
   @endforeach
</table>
@endsection