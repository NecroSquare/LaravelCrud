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
        <a href="#" onclick="document.getElementById('logout-form').submit(); return false;" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>    
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light px-4">
        <button class="btn btn-dark" onclick="toggleSidebar()">☰</button>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user"></i> {{ Auth::user()->name ?? 'Guest' }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="fas fa-user"></i> Profile
                        </button>
                    </li>
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" onclick="document.getElementById('logout-form').submit(); return false;" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

        <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> {{ Auth::user()->name ?? 'Guest' }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email ?? 'No email available' }}</p>
                    <p><strong>Phone:</strong> {{ Auth::user()->phone ?? 'No phone number' }}</p>
                    <p><strong>Address:</strong> {{ Auth::user()->address ?? 'No address provided' }}</p>
                    <p><strong>Joined:</strong> {{ Auth::user()->created_at->format('d M Y') }}</p>
                </div>
                <div class="modal-footer">
                    <form id="logout-form-modal" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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
