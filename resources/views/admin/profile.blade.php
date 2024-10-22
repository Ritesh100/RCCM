<!-- resources/views/company/profile.blade.php -->
@extends('admin.sidebar')

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

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
                    <h2 class="text-center mb-4">Edit Admin Profile</h2>

                    <hr>


                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="userName" class="form-label">Username:</label>
                            <input type="text" id="userName" name="userName" class="form-control"
                                value="{{ old('userName', $user->userName) }}" required>
                            @error('userName')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="abn" class="form-label">ABN:</label>
                            <input type="text" id="abn" name="abn" class="form-control"
                                value="{{ old('abn', $user->abn) }}" required>
                            @error('abn')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email:</label>
                            <input type="email" id="userEmail" name="userEmail" class="form-control"
                                value="{{ old('userEmail', $user->userEmail) }}" required>
                            @error('userEmail')
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
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Bootstrap JS (optional, for components like modals and dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
