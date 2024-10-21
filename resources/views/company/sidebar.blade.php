<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
       

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="menu">
            <!-- Display the username -->
            <div class="welcome">
                @if(session('company'))
                    Welcome, {{ session('company')->name }}!
                @else
                    Welcome!
                @endif
            </div>
            
            <!-- Menu options -->
            <a href="{{ route('company.profile.edit') }}">Profile</a>
            {{-- <a href="{{ route('company.users') }}">Users</a> --}}
            <a href="{{ route('company.profile.users') }}">Users</a>
            <a href="{{ route('company.timeSheet') }}">Timesheet</a>
            <a href="{{ route('company.document') }}">Document</a>

            <a href="{{ route('company.document') }}">Leaves</a>

        </div>

        <!-- Logout Button -->
        <div class="logout">
            <form action="{{ route('companyLogout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger" style="width: 100%; padding: 10px; background-color: red; color: white; border: none; cursor: pointer;">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        @yield('content')
    </div>

</body>
</html>
