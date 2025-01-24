<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <style>
        /* Basic styling for sidebar */
        body {
            font-family: 'Josefin Sans', sans-serif;
            display: flex;
            margin: 0; /* Ensure no default body margin */

        }

        .sidebar {
            width: 250px;
            background-color: #white;
            color:#5271ff;
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
            color: #5271ff;
            display: block;
            margin: 5px 0;
        }

        .sidebar a:hover {
            
            background-color: white !important; /* White background */
    color: #5271FF !important; /* Blue text color */
    border: 1px solid #5271FF !important;
        }

        


        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px); /* Ensure content takes up remaining width */

        }

        .content h1 {
            color: #333;
        }
        .custom-logout-btn {
    background-color: #ffffff; 
    color: #5271ff;           
    border: 2px solid #5271ff; 
    border-radius: 5px;        
    padding: 10px 15px;        
    transition: all 0.3s ease; 
}

.custom-logout-btn:hover {
    background-color: #f0f0f0;
    color: #4056b2;          
    border-color: #4056b2;    
}


        .welcome {
            color: #5271ff;
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
           
            color: #ffffff;
                background: #5271ff;
            
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
        <!-- Company Info Section -->
        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
            <div class="user-avatar bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                <span class="fs-4 text-white">
                    @if(session('company'))
                        {{ substr(session('company')->name, 0, 1) }}
                    @else
                        C
                    @endif
                </span>
            </div>
            <div>
                <h6 class="mb-0" style="color: #5271ff;">
                    @if(session('company'))
                        {{ session('company')->name }}
                    @else
                        Welcome!
                    @endif
                </h6>
                <small style="color: #5271ff;">Dashboard</small>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="{{ route('company.profile.edit') }}" class="nav-link {{ request()->routeIs('company.profile.edit') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-user-circle me-3 "></i>
                    Profile
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('company.profile.users') }}" 
                   class="nav-link {{ request()->routeIs('company.profile.users') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-users me-3 "></i>
                    RC
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('company.timeSheet') }}" 
                   class="nav-link {{ request()->routeIs('company.timeSheet') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-clock me-3"></i>
                    Timesheet
                </a>
            </li>
           
            <li class="nav-item mb-2">
                <a href="{{ route('company.leave') }}" 
                   class="nav-link {{ request()->routeIs('company.leave') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-calendar-alt me-3"></i>
                    Leaves
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('company.document') }}" 
                   class="nav-link {{ request()->routeIs('company.document') ? 'active' : '' }} d-flex align-items-center">
                    <i class="fas fa-file-alt me-3"></i>
                    Document
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('company.invoice') }}" 
                   class="nav-link {{ request()->routeIs('company.invoice') ? 'active' : '' }} d-flex align-items-center">
                   <i class="fas fa-upload me-3"></i> <!-- Another icon change -->
                   Invoice and Credits
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('company.payslips') }}" 
                   class="nav-link {{ request()->routeIs('company.payslips') ? 'active' : '' }} d-flex align-items-center">
                   <i class="fas fa-money-bill me-3"></i>
                   PaySlip
                </a>
            </li>
        </ul>
        <div class="mt-auto border-top pt-3">
            <a href="{{ url('/privacy') }}" 
               class="nav-link {{ request()->routeIs('/privacy') ? 'active' : '' }} d-flex align-items-center" style="font-size:12px; font-style:italic;">
                <i class="fas fa-shield-alt me-3"></i>
                Privacy and Policy
            </a>                          

        </div>

        <!-- Logout Button -->
        <div class="mt-auto border-top pt-3">
            <form action="{{ route('companyLogout') }}" method="POST">
                @csrf
                <button type="submit" class="btn custom-logout-btn w-100 d-flex align-items-center justify-content-center gap-2">
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
