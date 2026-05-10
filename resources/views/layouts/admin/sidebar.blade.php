<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-bg: #ffffff;
            --sidebar-text: #475569;
            --sidebar-active-bg: #eff6ff;
            --sidebar-active-text: #2563eb;
            --sidebar-hover-bg: #f8fafc;
            --sidebar-border: #e2e8f0;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --header-height: 60px;
        }

        [data-theme="dark"] {
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active-bg: rgba(59, 130, 246, 0.1);
            --sidebar-active-text: #3b82f6;
            --sidebar-hover-bg: #1e293b;
            --sidebar-border: #334155;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-main, #f1f5f9);
            color: var(--text-main, #475569);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s, color 0.3s;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
        }

        .admin-sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-toggle-btn {
            position: absolute;
            right: -14px;
            top: 24px;
            width: 28px;
            height: 28px;
            background: var(--primary, #2563eb);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1001;
            transition: transform 0.3s;
        }
        .admin-sidebar.collapsed .sidebar-toggle-btn {
            transform: rotate(180deg);
        }

        /* Nút Hamburger cho Mobile */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1002;
            background: var(--primary, #2563eb);
            color: #fff;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid var(--sidebar-border);
            height: 80px;
        }

        .brand-text {
            white-space: nowrap;
            transition: opacity 0.2s;
        }
        .admin-sidebar.collapsed .brand-text {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
            list-style: none;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--sidebar-border); border-radius: 4px; }

        .nav-section {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
        .admin-sidebar.collapsed .nav-section {
            text-align: center;
            padding: 1rem 0 0.5rem;
        }
        .admin-sidebar.collapsed .nav-section::after {
            content: '...';
        }
        .admin-sidebar.collapsed .nav-section span {
            display: none;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            white-space: nowrap;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .nav-link i {
            width: 24px;
            font-size: 1.1rem;
            text-align: center;
            margin-right: 1rem;
            transition: margin 0.3s;
        }

        .admin-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }
        .admin-sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        .admin-sidebar.collapsed .nav-link span {
            display: none;
        }
        .admin-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        .nav-link:hover {
            background-color: var(--sidebar-hover-bg);
            color: var(--sidebar-active-text);
        }

        .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
            font-weight: 600;
        }

        /* Theme Toggle & User Panel */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .theme-switch {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            color: var(--sidebar-text);
            cursor: pointer;
            border-radius: 8px;
            transition: 0.2s;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .theme-switch:hover {
            background-color: var(--sidebar-hover-bg);
        }
        .admin-sidebar.collapsed .theme-switch span { display: none; }
        .admin-sidebar.collapsed .theme-switch { justify-content: center; padding: 0.75rem; }
        
        [data-theme="dark"] .dark-icon { display: none; }
        [data-theme="light"] .light-icon { display: none; }
        [data-theme="dark"] .light-icon { display: inline-block; color: #fbbf24; }
        [data-theme="light"] .dark-icon { display: inline-block; }

        .logout-btn {
            color: #ef4444 !important;
            margin-top: 0.5rem;
        }
        .logout-btn:hover {
            background-color: #fef2f2 !important;
        }
        [data-theme="dark"] .logout-btn:hover { background-color: rgba(239, 68, 68, 0.1) !important; }

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2rem 2.5rem;
            transition: margin-left 0.3s ease;
            width: calc(100% - var(--sidebar-width));
        }

        .admin-sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .admin-sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }
            .admin-sidebar.mobile-open {
                transform: translateX(0);
            }
            .sidebar-toggle-btn {
                display: none;
            }
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 4rem 1rem 1rem 1rem;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; width: 100vw; height: 100vh;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            .sidebar-overlay.active { display: block; }
        }
    </style>
</head>
<body>

    <!-- Mobile Header/Toggle -->
    <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i class="fa-solid fa-bars"></i>
    </button>
    <div class="sidebar-overlay" onclick="toggleMobileSidebar()"></div>

    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Thu gọn/Phóng to Sidebar">
            <i class="fa-solid fa-chevron-left"></i>
        </div>

        <div class="sidebar-brand">
            <img src="https://lic.humg.edu.vn/App_Themes/humg/images/humg-logo.png" alt="Logo" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; flex-shrink: 0;">
            <div class="brand-text">
                <h2 style="font-size: 1rem; font-weight: 700; color: var(--text-title); margin: 0;">ADMIN PANEL</h2>
                <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">
                    {{ Auth::user()->username ?? 'Quản trị viên' }}
                </span>
            </div>
        </div>

        <ul class="sidebar-nav">
            <li class="nav-section"><span>Hệ thống</span></li>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    <span>Tổng quan</span>
                </a>
            </li>
            
            <li class="nav-section"><span>Nhân sự</span></li>
            <li class="nav-item">
                <a href="{{ route('admin.accounts') }}" class="nav-link {{ request()->routeIs('admin.accounts') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Tài khoản</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.students') }}" class="nav-link {{ request()->routeIs('admin.students') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Sinh viên</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.teachers') }}" class="nav-link {{ request()->routeIs('admin.teachers') ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Giảng viên</span>
                </a>
            </li>

            <li class="nav-section"><span>Đào tạo</span></li>
            <li class="nav-item">
                <a href="{{ route('admin.classes') }}" class="nav-link {{ request()->routeIs('admin.classes') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Lớp học</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.subjects') }}" class="nav-link {{ request()->routeIs('admin.subjects') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Môn học</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.schedules') }}" class="nav-link {{ request()->routeIs('admin.schedules') ? 'active' : '' }}">
                    <i class="fa-regular fa-calendar-days"></i>
                    <span>Thời khóa biểu</span>
                </a>
            </li>

            <li class="nav-section"><span>Tài chính & Đánh giá</span></li>
            <li class="nav-item">
                <a href="{{ route('admin.grades') }}" class="nav-link {{ request()->routeIs('admin.grades') ? 'active' : '' }}">
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>Bảng điểm</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.fees') }}" class="nav-link {{ request()->routeIs('admin.fees') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Học phí</span>
                </a>
            </li>

            <li class="nav-section"><span>Khác</span></li>
            <li class="nav-item">
                <a href="{{ route('admin.announcements') }}" class="nav-link {{ request()->routeIs('admin.announcements') ? 'active' : '' }}">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>Thông báo</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.feedbacks') }}" class="nav-link {{ request()->routeIs('admin.feedbacks') ? 'active' : '' }}">
                    <i class="fa-regular fa-comments"></i>
                    <span>Góp ý</span>
                </a>
            </li>
            
            <li class="nav-section"><span>Cài đặt</span></li>
            <li class="nav-item">
                <a href="{{ route('admin.config') }}" class="nav-link {{ request()->routeIs('admin.config') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i>
                    <span>Cài đặt chung</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="theme-switch" onclick="toggleTheme()" title="Sáng/Tối">
                <i class="fa-solid fa-moon dark-icon"></i>
                <i class="fa-solid fa-sun light-icon" style="display:none;"></i>
                <span>Giao diện</span>
            </div>
            <a href="#" class="nav-link logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </aside>

    <main class="main-content">
        @yield('content')
    </main>

    <script>
        // Init Theme
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.querySelector('.dark-icon').style.display = 'none';
            document.querySelector('.light-icon').style.display = 'inline-block';
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
        }

        // Toggle Theme
        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            
            if (next === 'dark') {
                document.querySelector('.dark-icon').style.display = 'none';
                document.querySelector('.light-icon').style.display = 'inline-block';
            } else {
                document.querySelector('.dark-icon').style.display = 'inline-block';
                document.querySelector('.light-icon').style.display = 'none';
            }
        }

        // Toggle Sidebar Desktop
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        // Toggle Sidebar Mobile
        function toggleMobileSidebar() {
            document.getElementById('sidebar').classList.toggle('mobile-open');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }
    </script>
</body>
</html>
