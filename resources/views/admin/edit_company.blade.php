<!-- resources/views/admin/edit_company.blade.php -->
@extends('admin.sidebar')

@section('content')
    <h1>Edit Company</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.company.update', $company->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Use PUT for update operation -->

        <div>
            <label for="name">Company Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" required>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="{{ old('address', $company->address) }}">
            @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact" value="{{ old('contact', $company->contact) }}">
            @error('contact')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="email">Company Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $company->email) }}" required>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password">
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Update Company</button>
    </form>
@endsection
