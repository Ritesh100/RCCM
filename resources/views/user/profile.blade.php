<!-- resources/views/company/profile.blade.php -->
@extends('user.sidebar')


<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">


<style>
    body {
        background-color: #f0f2f5;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        /* Full viewport height */
        padding: 20px;
        /* Add padding for better spacing */
    }


    .form-container {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 8px;
        /* Rounded corners */
        width: 500px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        /* Subtle shadow */
    }

    h1,
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
</style>

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center mb-4">Edit User Profile</h2>

                    <hr>
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                    
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name:</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}"  readonly>
                            @error('name')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
                            @error('email')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="reportingTo" class="form-label">Reporting To (Email):</label>
                            <input type="email" id="reportingTo" name="reportingTo" class="form-control" value="{{ $user->reportingTo }}" readonly>
                            @error('reportingTo')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="address" class="form-label">Address:</label>
                            <input type="text" id="address" name="address" class="form-control" value="{{ $user->address }}" readonly>
                            @error('address')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact:</label>
                            <input type="text" id="contact" name="contact" class="form-control" value="{{ $user->contact }}" readonly>
                            @error('contact')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="currency" class="form-label">Currency:</label>
                            <input type="text" id="currency" name="currency" class="form-control" value="{{ $user->currency }}" readonly>
                            @error('contact')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="hrlyRate" class="form-label">Hourly Rate:</label>
                            <input type="text" id="hrlyRate" name="hrlyRate" class="form-control" value="{{ $user->hrlyRate }}" readonly>
                            @error('hrlyRate')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password:</label>
                            <input type="password" id="password" name="password" class="form-control">
                            @error('password')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password:</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                        </div>
                    
                        
                    
                        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                    </form>
                    
                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Bootstrap JS (optional, for components like modals and dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
