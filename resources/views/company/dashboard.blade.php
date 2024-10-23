@extends('company.sidebar')

@section('content')
    <h1>Welcome {{ session('company')->name }}'s Portal</h1>
    <p>Here is the overview of the Company panel.</p>
@endsection
