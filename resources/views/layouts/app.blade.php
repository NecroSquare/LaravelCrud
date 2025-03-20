<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #343a40;
            padding-top: 20px;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .sidebar .menu-title {
            padding: 15px 20px;
            font-size: 16px;
            font-weight: bold;
        }

        .sidebar.collapsed {
        width: 0;
        visibility: hidden;
        pointer-events: none;
    }

        /* Main Content */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        /* Navbar */
        .navbar {
            margin-left: 250px;
            background: white;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar .nav-item .nav-link {
            color: #333;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center">
            <h4>Laravel Crud</h4>
        </div>
        <a href="{{ route('loans.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <a href="{{ route('loans.borrowed') }}"><i class="fas fa-chart-line"></i> Loan Analytics</a>
        <a href="{{ route('crud.index') }}"><i class="fas fa-book"></i> Add Book's</a>
        <hr>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" onclick="document.getElementById('logout-form').submit(); return false;" class="nav-link fa-cog">
            Logout
        </a>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light px-4">
        <button class="btn btn-dark" onclick="toggleSidebar()">☰</button>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user"></i> Alexander Pierce
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item fa-user" href=""> Profile</a></li>
                    <li><form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="document.getElementById('logout-form').submit(); return false;" class="nav-link fa-cog">
                        Logout
                    </a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');
        const navbar = document.querySelector('.navbar');

        // Toggle class to handle visibility and interaction
        if (sidebar.classList.contains('collapsed')) {
            sidebar.classList.remove('collapsed');
            content.style.marginLeft = "250px";
            navbar.style.marginLeft = "250px";
        } else {
            sidebar.classList.add('collapsed');
            content.style.marginLeft = "0";
            navbar.style.marginLeft = "0";
        }
    }
    </script>
</body>
</html>
