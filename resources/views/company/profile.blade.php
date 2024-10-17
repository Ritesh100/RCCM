<!-- resources/views/company/profile.blade.php -->
@extends('company.sidebar')

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
                    <h2 class="text-center mb-4">Edit Company Profile</h2>

                    <hr>


                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('company.profile.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Company Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ $company->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ $company->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" id="address" name="address" class="form-control"
                                value="{{ $company->address }}">
                        </div>

                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact Number</label>
                            <input type="text" id="contact" name="contact" class="form-control"
                                value="{{ $company->contact }}">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password (optional)</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
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
