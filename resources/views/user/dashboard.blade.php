@extends('user.sidebar')
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

@section('content')
    <h1>Welcome {{ session('userLogin')->name }}'s Portal</h1>
    <p>Here is the overview of the User panel.</p>
@endsection
