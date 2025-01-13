<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RC Partner Login</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            padding: 0;
            margin: 0;
        }
        .container {
            width: 1000px; /* Fixed width */
        }

        .form-container {
            background-color: #ffffff; /* White background for form */
            padding: 30px;
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center mb-4">Login</h2>
                    <form action="{{ route('companyLogin') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>

                        @if (session('error'))
                            <div class="alert alert-danger mt-3">
                                {{ session('error') }}
                            </div>
                        @endif
                        <p>By Signing in to our Portal, you agree to our 
                            <a href="{{ url('/privacy') }}" target="_blank">Privacy Policy</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            {{-- <a href="/rcLogin" class="btn btn-link">Login with RC</a> --}}
        </div>
    </div>
   

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
