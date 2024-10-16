<!-- resources/views/admin/profile.blade.php -->
@extends('admin.sidebar')

@section('content')
    <h1>Profile</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST">
        @csrf
        @method('PUT') <!-- Use PUT for updates -->
        
        <div>
            <label for="userName">Username:</label>
            <input type="text" id="userName" name="userName" value="{{ old('userName', $user->userName) }}" required>
            @error('userName')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="userEmail">Email:</label>
            <input type="email" id="userEmail" name="userEmail" value="{{ old('userEmail', $user->userEmail) }}" required>
            @error('userEmail')
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

        <div>
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit">Update Profile</button>
    </form>
@endsection

