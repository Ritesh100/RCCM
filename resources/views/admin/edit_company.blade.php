
@extends('admin.sidebar')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-XLZI48k5a5Y7sEq6Hp7MNJ+UDEEGPzPHTxSAIDzOeXf4mrn4QU7pkX9q3GJkBq8v" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Open Sans', sans-serif;
                background-color: #f0f2f5;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 20px;
            }
            .container {
                width: 1000px;
            }
            .form-container {
                background-color: #ffffff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            }
            h1, h2 {
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




    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="text-center mb-4">Update Company</h2>
                    <hr>

                    <form action="{{ route('admin.company.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password:</label>
                            <input type="password" id="password" name="password" class="form-control">
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_person" class="form-label">Contact Person:</label>
                            <input type="text" id="contact_person" name="contact_person" class="form-control"
                            value="{{ old('contact_person', $company->contact_person) }}" required>
                            @error('contact_person')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="master_agreement" class="form-label">Master Agreement by RCC</label>
                            <input type="file" id="master_agreement" name="master_agreement" class="form-control"
                            value="{{ old('master_agreement',$company->master_agreement) }}" required>
                            @error('master_agreement')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        

                        <div class="mb-3">
                            <label for="service_agreement" class="form-label">Service Agreement by RCC</label>
                            <input type="file" id="service_agreement" name="service_agreement" class="form-control"
                            value="{{ old('service_agreement', $company->service_agreement )}}" required>
                            @error('service_agreement')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="service_schedule" class="form-label">Service Schedule by RCC</label>
                            <div id="service-schedule-container">
                                <div class="service-schedule-item mb-2">
                                    <input type="file" name="service_schedule[]" id="service_schedule" class="form-control" value=" {{ old('service_schedule')}}">
                                </div>
                            </div>
                            <button type="button" id="add-service-schedule" class="btn custom-btn mt-2">Add Another Service Schedule</button>
                            @error('service_schedule')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="mb-3">
                            <p>Signed Agreements: {{ $company->agreement_date ?? '---Date of agreement' }}</p>
                            <label for="signed_master_agreement" class="form-label">Master Agreement</label>
                            <input type="file" id="signed_master_agreement" name="signed_master_agreement" class="form-control">
                            @error('signed_master_agreement')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div> --}}


                        <button type="submit" class="btn custom-btn w-100">Update Company</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js" integrity="sha384-Qh0JpDq/bbmfTHp+p7Iwhz5xEtOn23wPUZt4UPc0Iz5boKkktJ/E0i7K+Psm0T5F" crossorigin="anonymous"></script>



    

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


