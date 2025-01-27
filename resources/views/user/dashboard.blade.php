@extends('user.sidebar')
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">

@section('content')
<style>
    body{
        font-family: 'Open Sans', sans-serif;
    }
</style>
    <h1>Welcome {{ session('userLogin')->name }}'s Portal</h1>
    <p>Here is the overview of the User panel.</p>
@endsection
