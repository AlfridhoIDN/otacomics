<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bs-purple: #8e24aa;
            --bs-purple-dark: #5e1670;
            --bs-purple-light: #c158dc;
            --bs-dark: #121212;
            --bs-dark-secondary: #1e1e1e;
            --bs-dark-tertiary: #2d2d2d;
        }

        body {
            background-color: var(--bs-dark);
            color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-dark {
            background-color: var(--bs-dark-secondary) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--bs-purple-light) !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.75) !important;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--bs-purple-light) !important;
        }

        .nav-link.active {
            position: relative;
        }

        .nav-link.active:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0.5rem;
            right: 0.5rem;
            height: 2px;
            background-color: var(--bs-purple);
        }

        .sidebar {
            background-color: var(--bs-dark-secondary);
            border-right: 1px solid rgba(142, 36, 170, 0.2);
            height: calc(100vh - 56px);
            position: sticky;
            top: 56px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background-color: rgba(142, 36, 170, 0.1);
            color: var(--bs-purple-light);
        }

        .sidebar-link.active {
            background-color: rgba(142, 36, 170, 0.2);
            color: var(--bs-purple-light);
            border-left: 3px solid var(--bs-purple);
        }

        .sidebar-link i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        .main-content {
            flex: 1;
            padding: 1.5rem 0;
        }

        footer {
            background-color: var(--bs-dark-secondary);
            border-top: 1px solid rgba(142, 36, 170, 0.2);
            padding: 1rem 0;
            margin-top: auto;
        }

        .dropdown-menu {
            background-color: var(--bs-dark-secondary);
            border: 1px solid rgba(142, 36, 170, 0.2);
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.75);
        }

        .dropdown-item:hover {
            background-color: rgba(142, 36, 170, 0.1);
            color: var(--bs-purple-light);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--bs-purple);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.5rem;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bs-dark-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--bs-purple);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--bs-purple-light);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                height: auto;
                position: static;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i>Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}
                            </div>
                            <span>{{ auth()->user() ? auth()->user()->name : 'Admin' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('account.logout') }}">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 px-0 d-none d-lg-block">
                <div class="sidebar">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                    <a href="{{route('admin.dashboard')}}" class="sidebar-link">
                        <i class="bi bi-people"></i> Users
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-secondary py-3">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Admin Dashboard. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
