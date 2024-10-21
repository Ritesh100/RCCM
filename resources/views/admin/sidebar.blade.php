<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Basic styling for sidebar */
        body {
            display: flex;
            margin: 0; /* Ensure no default body margin */

        }

        
        .sidebar {
            width: 250px;
            background-color: #333;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar .menu {
            flex-grow: 1;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            margin: 5px 0;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .sidebar .logout {
            margin-bottom: 20px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px); /* Ensure content takes up remaining width */

        }

        .content h1 {
            color: #333;
        }

        .welcome {
            color: white;
            font-size: 16px;
            padding: 15px;
            text-align: center;
        }
        .sidebar {
            min-height: 100vh;
            border-right: 1px solid #dee2e6;
        }
        .nav-link {
            color: var(--bs-gray-700);
        }
        .nav-link:hover, .nav-link.active {
            color: var(--bs-primary);
            background: var(--bs-light);
        }
        .user-avatar {
            width: 48px;
            height: 48px;
        }
       

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white">
        <!-- User Info Section -->
        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
            <div class="user-avatar bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2">
                <span class="fs-4 text-white">{{ substr(Auth::user()->userName, 0, 1) }}</span>
            </div>
            <div>
                <h6 class="mb-0">{{ Auth::user()->userName }}</h6>
                <small class="text-white-50">Administrator</small>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="{{ route('admin.profile') }}" 
                   class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-user me-3"></i>
                    Profile
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.company') }}" 
                   class="nav-link {{ request()->routeIs('admin.company') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-building me-3"></i>
                    RCC Partner
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.users') }}" 
                   class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-users me-3"></i>
                    RC
                </a>
            </li>
        </ul>

        <!-- Logout Button -->
        <div class="mt-auto border-top pt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
    <!-- Main Content Area -->
    <div class="content">
        @yield('content')
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>

