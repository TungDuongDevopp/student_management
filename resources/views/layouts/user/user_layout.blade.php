<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cổng thông tin Sinh viên & Giảng viên">
    <title>@yield('title', 'Cổng Người Dùng') - Portal</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Centralized CSS -->
    <link rel="stylesheet" href="{{ asset('css/user_shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">

    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --bg-body: #f8fafc;
            --bg-sidebar: #ffffff;
            --text-main: #0f172a;
            --text-muted: #475569;
            --border-color: #e2e8f0;
            --sidebar-hover: #f1f5f9;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 64px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--bg-body);
            color: var(--text-main);
            overflow-x: hidden;
            transition: all 0.3s ease;
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

        /* Mobile hamburger */
        .mobile-hamburger {
            display: none;
            position: fixed;
            top: 0.75rem;
            left: 0.75rem;
            width: 40px;
            height: 40px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            align-items: center;
            justify-content: center;
            z-index: 60;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            font-size: 1.1rem;
            color: var(--text-main);
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 45;
            opacity: 0;
            transition: opacity 0.3s;
        }

        @media (max-width: 768px) {
            .app-wrapper { flex-direction: column; }
            .mobile-hamburger { display: flex; }
        }
    </style>
    
    @yield('head')
</head>
<body>
    <div class="app-wrapper">
        {{-- Sidebar — được nhúng bởi trang con, ví dụ student hay teacher --}}
        @yield('sidebar')

        <div class="main-area">
            <!-- Top Bar -->
            @include('layouts.user.topbar')
            
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
            localStorage.setItem('userSidebarState', document.body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
        }

        function toggleMobile() {
            document.body.classList.toggle('mobile-open');
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
        if (localStorage.getItem('userSidebarState') === 'collapsed' && window.innerWidth > 768) {
            document.body.classList.add('sidebar-collapsed');
        }
        
        // Init theme
        document.addEventListener('DOMContentLoaded', () => {
            updateTopbarThemeIcon(document.documentElement.getAttribute('data-theme') || 'dark');
        });
    </script>
    
    @yield('scripts')
</body>
</html>
