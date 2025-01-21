@extends('company.sidebar')
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body{
        font-family: 'Josefin Sans', sans-serif;
    }
</style>
@section('content')
    <h1>Welcome {{ session('company')->name }}'s Portal</h1>
    <p>Here is the overview of the Company panel.</p>
@endsection
