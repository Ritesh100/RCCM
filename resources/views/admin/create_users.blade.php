@extends('admin.sidebar')

@section('content')

<h1>Create RC</h1>

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">RCC  Full Name:</label>
        <input type="text" id="name" name="name">
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
    </div>
    <div>
        <label for="companyName">RC Name:</label>
        <select id="companyName" name="companyName" required>
            <option value="">Select Company</option>
            @foreach($companies as $company)
                <option value="{{ $company->name }}" data-email="{{ $company->email }}">{{ $company->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="reportingTo">Reporting To (Email):</label>
        <select id="reportingTo" name="reportingTo" required disabled>
            <option value="">Select Email</option>
            @foreach($companies as $company)
                <option value="{{ $company->email }}" data-name="{{ $company->name }}">{{ $company->email }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address">
    </div>
    <div>
        <label for="contact">Contact:</label>
        <input type="text" id="contact" name="contact">
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="hrlyRate">Hourly Rate:</label>
        <input type="text" id="hrlyRate" name="hrlyRate" required>
    </div>

    <button type="submit">Create Users</button>
</form>

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
