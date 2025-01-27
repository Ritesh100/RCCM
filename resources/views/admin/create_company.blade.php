<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">


<style>
    body {
        font-family: 'Open Sans', sans-serif;
        background-color: #f0f2f5; /* Light gray background */
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh; /* Full viewport height */
        padding: 20px; /* Add padding for better spacing */
    }
    .container{
            width:1000px;
        }

    .form-container {
        background-color: #ffffff; /* White background for form */
        padding: 30px;
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); /* Subtle shadow */
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
</style>

@extends('admin.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-container ">
                <h2 class="text-center mb-4">Create Company</h2>
                <hr>
                <form action="{{ route('admin.company.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">RCC Partner Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address:</label>
                        <input type="text" id="address" name="address" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact:</label>
                        <input type="text" id="contact" name="contact" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Create Company</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


<!-- Bootstrap JS (optional, for components like modals and dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
