<!-- resources/views/admin/profile.blade.php -->
@extends('company.sidebar')

@section('content')
    <h1>Edit Company Profile</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('company.profile.update') }}" method="POST">
        @csrf
        <div>
            <label for="name">Company Name</label>
            <input type="text" id="name" name="name" value="{{ $company->name }}" required>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $company->email }}" required>
        </div>

        <div>
            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="{{ $company->address }}">
        </div>

        <div>
            <label for="contact">Contact Number</label>
            <input type="text" id="contact" name="contact" value="{{ $company->contact }}">
        </div>

        <div>
            <label for="password">New Password (optional)</label>
            <input type="password" id="password" name="password">
        </div>

        <div>
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit">Update Profile</button>
    </form>
@endsection