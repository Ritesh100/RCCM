<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        /* Basic styling for sidebar */
        body {
            display: flex;
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
                Welcome, {{ Auth::user()->userName }} <!-- Display the logged-in user's name -->
            </div>
            
            <!-- Menu options -->
            <a href="{{ route('admin.profile') }}">Profile</a>
            <a href="{{ route('admin.company') }}">RCC Partner</a>
            <a href="{{ route('admin.users') }}">RC</a>
        </div>

        <!-- Logout Button -->
        <div class="logout">
            <form action="{{ route('logout') }}" method="POST">
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
