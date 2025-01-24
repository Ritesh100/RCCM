<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            display: flex;
            margin: 0; /* Ensure no default body margin */
            font-family: 'Josefin Sans', sans-serif;
        }

        .sidebar {
            width: 250px;
            background-color: #ffffff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto; /* Enable vertical scroll */
            overflow-x: hidden; /* Disable horizontal scroll */
        }

        .sidebar .menu {
            flex-grow: 1;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: #5271FF; /* Updated text color */
            display: block;
            margin: 5px 0;
        }

        .sidebar a:hover {
            background-color: #ffffff; 
            color: #5271FF; 
        }

        .sidebar .logout {
            margin-bottom: 20px;
        }

        .logout-btn {
    width: 100%;
    background-color: white !important;  /* Ensure background is white */
    color: #5271FF !important;           /* Ensure text color is #5271FF */
    border: 1px solid #5271FF !important; /* Ensure border is #5271FF */
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
}

.logout-btn:hover {
    background-color: #5271FF !important;  /* Ensure background on hover is #5271FF */
    color: white !important;               /* Ensure text color on hover is white */
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
            color: #5271FF; /* Updated link color */
        }

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: #5271FF; /* Updated active and hover color */
        }

        .user-avatar {
            width: 48px;
            height: 48px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.toggled {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                width: 100%;
            }

            .toggle-btn {
                display: block;
            }
        }

        /* Additional specific text color changes */
        .user-name {
            color: #5271FF; /* Updated color for the user's name */
        }

        .user-role {
            color: #bbb; /* Adjusted color for the 'Administrator' label */
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white">
        <!-- Company Info Section -->
        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
            <div class="user-avatar bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                <span class="fs-4 text-white">
                    {{ substr(Auth::user()->userName, 0, 1) }}
                </span>
            </div>
            <div>
                <h6 class="mb-0 user-name">
                    @if(session('admin'))
                    {{ Auth::user()->userName }}
                    @else
                        Welcome!
                    @endif
                    <small class="user-role" style="color: #5271ff;">Administrator</small>
                </h6>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.profile') }}" 
                   class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-user me-3"></i>
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.company') }}" 
                   class="nav-link {{ request()->routeIs('admin.company') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-building me-3"></i>
                    RCC Partner
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users') }}" 
                   class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-users me-3"></i>
                    RC
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.timesheets') }}" 
                   class="nav-link {{ request()->routeIs('admin.timesheets') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-clock me-3"></i>
                    Timesheet
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.payslips') }}" 
                   class="nav-link {{ request()->routeIs('admin.payslips') ? 'active' : '' }} d-flex align-items-center">
                   <i class="fas fa-money-bill me-3"></i>
                    Payslips
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.leave') }}" 
                   class="nav-link {{ request()->routeIs('admin.leave') ? 'active' : '' }} d-flex align-items-center">
                   <i class="fas fa-money-bill me-3"></i>
                    Leaves
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.document') }}" 
                   class="nav-link {{ request()->routeIs('admin.document') ? 'active' : '' }} d-flex align-items-center">
                   <i class="fas fa-file-alt me-3"></i>
                   Document
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.invoice') }}" 
                   class="nav-link {{ request()->routeIs('admin.invoice') ? 'active' : '' }} d-flex align-items-center">
                   <i class="fas fa-upload me-3"></i>
                   Invoice and Credits
                </a>
            </li>
        </ul>

        <!-- Logout Button -->
        <div class="mt-auto border-top pt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn logout-btn d-flex align-items-center justify-content-center gap-2">
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


    
   
        
        <!-- Include Bootstrap 5 and Bootstrap Icons for the + icon -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

