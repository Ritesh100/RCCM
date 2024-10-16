@extends('admin.sidebar')

@section('content')

    <h1>Create RCC partner</h1>

    <form action="{{ route('admin.company.store') }}" method="POST">
        @csrf
        
        <div>
            <label for="name">RCC Partner Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
      
        <div>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address">
        </div>
        <div>
            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact">
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Create Company</button>
    </form>
@endsection
