<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Josefin Sans', sans-serif;
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
            <div class="form-container">
                <h2 class="text-center mb-4">Create RC</h2>
                <hr>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">RCC Full Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="companyName" class="form-label">RC Name:</label>
                        <select id="companyName" name="companyName" class="form-select" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->name }}" data-email="{{ $company->email }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="reportingTo" class="form-label">Reporting To (Email):</label>
                        <select id="reportingTo" name="reportingTo" class="form-select" required disabled>
                            <option value="">Select Email</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->email }}" data-name="{{ $company->name }}">{{ $company->email }}</option>
                            @endforeach
                        </select>
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
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="currency" class="form-label">Currency:</label>
                        <select id="currency" name="currency" class="form-control" required>
                            <option value="NPR">Nepal - NPR</option>
                            <option value="INR">India - INR</option>
                            <option value="USD">USA - USD</option>
                            <option value="AUD">Australia - AUD</option>
                            <option value="JPY">Japan - JPY</option>
                            <option value="CAD">Canada - CAD</option>
                            <option value="EUR">Europe - EUR</option>
                        </select>
                        @error('currency')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="hrlyRate" class="form-label">Hourly Rate:</label>
                        <input type="text" id="hrlyRate" name="hrlyRate" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Create Users</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
// jQuery or Vanilla JavaScript can be used
document.addEventListener('DOMContentLoaded', function() {
    const companyNameSelect = document.getElementById('companyName');
    const emailSelect = document.getElementById('reportingTo');

    companyNameSelect.addEventListener('change', function() {
        const selectedOption = companyNameSelect.options[companyNameSelect.selectedIndex];
        const selectedEmail = selectedOption.getAttribute('data-email');
        
        // Automatically select corresponding email
        emailSelect.value = selectedEmail;
        emailSelect.disabled = false;

        // Disable other email options to ensure the user can't select other emails
        for (let i = 0; i < emailSelect.options.length; i++) {
            if (emailSelect.options[i].value !== selectedEmail) {
                emailSelect.options[i].disabled = true;
            } else {
                emailSelect.options[i].disabled = false;
            }
        }
    });

    emailSelect.addEventListener('change', function() {
        const selectedOption = emailSelect.options[emailSelect.selectedIndex];
        const selectedCompanyName = selectedOption.getAttribute('data-name');
        
        // Automatically select corresponding company name
        companyNameSelect.value = selectedCompanyName;

        // Disable other company name options to ensure the user can't select other names
        for (let i = 0; i < companyNameSelect.options.length; i++) {
            if (companyNameSelect.options[i].value !== selectedCompanyName) {
                companyNameSelect.options[i].disabled = true;
            } else {
                companyNameSelect.options[i].disabled = false;
            }
        }
    });
});
</script>

@endsection
