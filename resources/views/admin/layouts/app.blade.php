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
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-icon"><i class="fas fa-box-open"></i></div>
                <h2 class="logo-text">SimPinjam</h2>
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
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari sesuatu...">
                </div>
                <div class="header-actions">
                    <div class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="badge">0</span>
                    </div>
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff" alt="Admin Profile">
                        <span class="user-name">Admin</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ asset('admin/js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>