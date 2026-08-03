<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Sistem Peminjaman Aset</title>
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .user-profile {
            position: relative;
            cursor: pointer;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 8px 0;
            min-width: 150px;
            z-index: 1000;
            border: 1px solid #e5e7eb;
            margin-top: 5px;
        }
        .user-profile:hover .dropdown-menu {
            display: block;
        }
        .dropdown-item {
            display: block;
            width: 100%;
            padding: 8px 16px;
            text-align: left;
            background: none;
            border: none;
            color: #374151;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }
        .dropdown-item:hover {
            background: #f3f4f6;
            color: #111827;
        }
        .dropdown-item i {
            margin-right: 8px;
            color: #6b7280;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-icon"><i class="fas fa-box-open"></i></div>
                <h2 class="logo-text">Peminjaman</h2>
                <div class="toggle-btn" id="toggle-btn"><i class="fas fa-bars"></i></div>
            </div>
            <ul class="sidebar-menu">
                <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/admin/dashboard') }}"><i class="fas fa-home"></i> <span>Dashboard</span></a>
                </li>
                <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/users') }}"><i class="fas fa-users"></i> <span>Manajemen User</span></a>
                </li>
                <li class="{{ request()->is('admin/assets*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/assets') }}"><i class="fas fa-boxes"></i> <span>Manajemen Aset</span></a>
                </li>
                <li class="{{ request()->is('admin/loans*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/loans') }}"><i class="fas fa-hand-holding"></i> <span>Peminjaman</span></a>
                </li>
                <li class="{{ request()->is('admin/returns*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/returns') }}"><i class="fas fa-undo"></i> <span>Pengembalian</span></a>
                </li>
                <li class="{{ request()->is('admin/reports*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/reports') }}"><i class="fas fa-chart-bar"></i> <span>Laporan</span></a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="top-header">
                <!-- Search bar hidden per request -->
                <div class="header-actions">
                    <!-- Notification bell hidden per request -->
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama_pengguna ?? 'Admin') }}&background=0D8ABC&color=fff" alt="Admin Profile">
                        <span class="user-name">{{ auth()->user()->nama_pengguna ?? 'Admin' }}</span>
                        <i class="fas fa-chevron-down"></i>
                        <div class="dropdown-menu">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="content-wrapper">
                @if(session('success'))
                    <div style="background-color: #d1fae5; color: #065f46; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #34d399;">
                        <i class="fas fa-check-circle" style="margin-right: 8px;"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div style="background-color: #fee2e2; color: #991b1b; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f87171;">
                        <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i> {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ asset('admin/js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>