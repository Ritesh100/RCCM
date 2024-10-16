<!-- resources/views/admin/edit_company.blade.php -->
@extends('admin.sidebar')

@section('content')
    <h1>Edit Users</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.users.update', $users->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Use PUT for update operation -->

        <div>
            <label for="name"> Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $users->name) }}" required>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="address"> Address:</label>
            <input type="text" id="address" name="address" value="{{ old('address', $users->address) }}">
            @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="contact"> Contact:</label>
            <input type="text" id="contact" name="contact" value="{{ old('contact', $users->contact) }}">
            @error('contact')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="email">Users Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $users->email) }}" required>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="name"> Reporting To:</label>
            <input type="text" id="reportingTo" name="reportingTo" value="{{ old('reportingTo', $users->reportingTo) }}" required>
            @error('reportingTo')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="name"> Hourly Rate:</label>
            <input type="text" id="hrlyRate" name="hrlyRate" value="{{ old('hrlyRate', $users->hrlyRate) }}" required>
            @error('hrlyRate')
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

        <button type="submit">Update Users</button>
    </form>
@endsection
