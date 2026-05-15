<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Trang Quản trị Hệ thống Quản lý Sinh viên">
    <title>@yield('title', 'Admin Dashboard') - Portal</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Centralized CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">

    <!-- Theme init (chạy trước khi render để không bị nháy) -->
    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        if (savedTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>

    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--bg-primary);
            color: var(--text);
            overflow-x: hidden;
            transition: all 0.3s ease;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        .app-wrapper {
            display: flex;
            width: 100%;
        }

        .main-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .main-area main {
            flex: 1;
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .app-wrapper { flex-direction: column; }
        }
    </style>
    
    @yield('head')
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar Navigation -->
        @include('layouts.admin.nav')

        <div class="main-area">
            <!-- Top Bar -->
            @include('layouts.admin.topbar')
            
            <main role="main">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Sidebar toggle
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarState', document.body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
        }

        // Theme toggle
        function toggleTheme() {
            const html = document.documentElement;
            const isLight = html.getAttribute('data-theme') === 'light';
            const newTheme = isLight ? 'dark' : 'light';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateTopbarThemeIcon(newTheme);
        }

        function updateTopbarThemeIcon(theme) {
            const icon = document.getElementById('topbar-theme-icon');
            if (icon) {
                icon.className = theme === 'light' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            }
        }

        // Init sidebar state
        if (localStorage.getItem('sidebarState') === 'collapsed') {
            document.body.classList.add('sidebar-collapsed');
        }
        
        // Init theme icon
        document.addEventListener('DOMContentLoaded', () => {
            updateTopbarThemeIcon(document.documentElement.getAttribute('data-theme') || 'dark');
        });
    </script>
    
    @yield('scripts')
</body>
</html>
