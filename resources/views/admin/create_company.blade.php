

@extends('admin.sidebar')

@section('content')
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
    .custom-btn {
                background-color: white !important;
                color: #5271ff !important;
                border: 2px solid #5271ff !important;
            }
            .custom-btn:hover {
                background-color: #f8f9fa !important;
                color: #5271ff !important;
            }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-container ">
                <h2 class="text-center mb-4">Create Company</h2>
                <hr>
                <form action="{{ route('admin.company.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="mb-3">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="master_agreement" class="form-label">Master Agreement (PDF):</label>
                        <input type="file" name="master_agreement" id="master_agreement" class="form-control" required>
                        @error('master_agreement')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
            
                    <div class="mb-3">
                        <label for="service_agreement">Service Agreement (PDF):</label>
                        <input type="file" name="service_agreement" id="service_agreement" class="form-control" required>
                        @error('service_agreement')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
            
                    <div class="mb-3">
                        <label for="service_schedule" class="form-label">Service Schedule by RCC</label>
                        <div id="service-schedule-container">
                            <div class="service-schedule-item mb-2">
                                <input type="file" name="service_schedule[]" id= "service_schedule" class="form-control">
                            </div>
                        </div>
                        <button type="button" id="add-service-schedule" class="btn custom-btn mt-2">Add Another Service Schedule</button>
                        @error('service_schedule')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                     </div>

            

                    <button type="submit" class="btn btn-primary w-100">Create Company</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('add-service-schedule').addEventListener('click', function() {
        const container = document.getElementById('service-schedule-container');
        const newItem = document.createElement('div');
        newItem.className = 'service-schedule-item mb-2';
        newItem.innerHTML = '<input type="file" name="service_schedule[]" class="form-control">';
        container.appendChild(newItem);
    });
</script>
@endsection




