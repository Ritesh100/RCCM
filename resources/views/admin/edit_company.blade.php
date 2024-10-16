<!-- resources/views/admin/edit_company.blade.php -->
@extends('admin.sidebar')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
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
   
   h1, h2 {
       text-align: center;
       margin-bottom: 20px;
   }
   
   </style>
@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center mb-4">Update Company</h2>
                    <hr>

                    <form action="{{ route('admin.company.update', $company->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Use PUT for update -->

                        <div class="mb-3">
                            <label for="name" class="form-label">Company Name:</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name', $company->name) }}" required>
                            @error('name')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address:</label>
                            <input type="text" id="address" name="address" class="form-control"
                                value="{{ old('address', $company->address) }}">
                            @error('address')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact:</label>
                            <input type="text" id="contact" name="contact" class="form-control"
                                value="{{ old('contact', $company->contact) }}">
                            @error('contact')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ old('email', $company->email) }}" required>
                            @error('email')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Company</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

