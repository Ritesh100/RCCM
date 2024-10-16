@extends('user.sidebar')

@section('content')
    <h1>Welcome {{ session('userLogin')->name }}'s Portal</h1>
    <p>Here is the overview of the Comapny panel.</p>
@endsection
