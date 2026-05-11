<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cổng thông tin Giảng viên">
    <title>@yield('title', 'Cổng Giảng viên')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        .user-sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 50;
            transition: all 0.3s ease;
        }

        .toggle-btn {
            position: absolute;
            top: 1.2rem;
            right: -13px;
            width: 26px;
            height: 26px;
            background: #fff;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 55;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            transition: all 0.2s;
            font-size: 0.7rem;
        }

        .toggle-btn:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary);
        }

        .user-profile {
            padding: 1.25rem 1rem 1rem;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
            white-space: nowrap;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            background-color: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 0.6rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
            object-fit: cover;
        }

        .user-profile h3 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.15rem;
            color: var(--text-main);
        }

        .user-profile p {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0.75rem 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-item {
            list-style: none;
            margin-bottom: 2px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.7rem 1.25rem;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 400;
            font-size: 0.92rem;
            border-left: 3px solid transparent;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .nav-link i {
            font-size: 1.05rem;
            width: 22px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-link:hover {
            color: var(--text-main);
            background-color: var(--sidebar-hover);
        }

        .nav-link.active {
            color: var(--primary);
            background-color: var(--primary-light);
            font-weight: 500;
            border-left: 3px solid var(--primary);
        }

        .sidebar-footer {
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--border-color);
            overflow: hidden;
            white-space: nowrap;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.6rem;
            background-color: #fef2f2;
            color: #dc2626;
            text-decoration: none;
            font-weight: 500;
            border-radius: 4px;
            transition: all 0.15s;
            border: 1px solid #fecaca;
            font-size: 0.88rem;
        }

        .logout-btn:hover {
            background-color: #fee2e2;
        }

        .logout-btn i {
            width: 22px;
            text-align: center;
            flex-shrink: 0;
        }

        .main-content {
            flex: 1;
            padding: 1.5rem;
            width: calc(100% - var(--sidebar-width));
            transition: width 0.3s ease;
            min-height: 100vh;
        }

        body.sidebar-collapsed .user-sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .main-content {
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        body.sidebar-collapsed .user-profile h3,
        body.sidebar-collapsed .user-profile p,
        body.sidebar-collapsed .nav-link span,
        body.sidebar-collapsed .logout-btn span {
            display: none;
        }

        body.sidebar-collapsed .user-avatar {
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        body.sidebar-collapsed .user-profile {
            padding: 1rem 0;
            justify-content: center;
        }

        body.sidebar-collapsed .nav-link {
            padding: 0.55rem 0;
            justify-content: center;
        }

        body.sidebar-collapsed .logout-btn {
            padding: 0.5rem 0;
            justify-content: center;
        }

        body.sidebar-collapsed .toggle-btn i {
            transform: rotate(180deg);
        }

        @media (max-width: 768px) {
            .mobile-hamburger {
                display: flex;
            }

            .toggle-btn {
                display: none;
            }

            .user-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 280px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                border-right: none;
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
            }

            body.mobile-open .user-sidebar {
                transform: translateX(0);
            }

            body.mobile-open .sidebar-backdrop {
                display: block;
                opacity: 1;
            }

            body.sidebar-collapsed .user-sidebar {
                width: 280px;
                transform: translateX(-100%);
            }

            body.sidebar-collapsed .user-profile h3,
            body.sidebar-collapsed .user-profile p,
            body.sidebar-collapsed .nav-link span,
            body.sidebar-collapsed .logout-btn span {
                display: inline;
            }

            body.sidebar-collapsed .user-avatar {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
                margin-bottom: 0.6rem;
            }

            body.sidebar-collapsed .user-profile {
                padding: 1.25rem 1rem 1rem;
            }

            body.sidebar-collapsed .nav-link {
                padding: 0.7rem 1.25rem;
                justify-content: flex-start;
            }

            body.sidebar-collapsed .logout-btn {
                padding: 0.6rem;
                justify-content: center;
            }

            .main-content {
                width: 100%;
                padding: 1rem 0.75rem;
                padding-top: 3.5rem;
            }

            body.sidebar-collapsed .main-content {
                width: 100%;
            }

            .user-profile {
                padding: 1.5rem 1.25rem 1.25rem;
            }

            .nav-link {
                padding: 0.85rem 1.5rem;
                font-size: 0.95rem;
            }

            .nav-link i {
                font-size: 1.1rem;
                width: 24px;
            }

            .logout-btn {
                padding: 0.75rem;
                font-size: 0.92rem;
            }
        }
    </style>
</head>

<body>

    <div class="mobile-hamburger" onclick="toggleMobile()">
        <i class="fa-solid fa-bars"></i>
    </div>
    <div class="sidebar-backdrop" onclick="toggleMobile()"></div>

    <aside class="user-sidebar" id="sidebar">
        <div class="toggle-btn" onclick="toggleSidebar()" title="Thu gọn/Phóng to Sidebar">
            <i class="fa-solid fa-chevron-left"></i>
        </div>

        <div class="user-profile">
            @if (Auth::check() && Auth::user()->teacher && Auth::user()->teacher->images)
                <img src="{{ Auth::user()->teacher->images }}" alt="Avatar" class="user-avatar">
            @else
                <div class="user-avatar"><i class="fa-solid fa-chalkboard-user"></i></div>
            @endif
            <h3>{{ Auth::check() ? Auth::user()->teacher->name ?? Auth::user()->username : 'Giảng viên' }}</h3>
            <p>{{ Auth::check() ? Auth::user()->teacher->teacher_code ?? 'Chưa cập nhật' : '' }}</p>
        </div>

        <nav class="sidebar-nav" aria-label="Menu Giảng Viên">
            <li class="nav-item">
                <a href="{{ route('teacher.home') }}"
                    class="nav-link {{ request()->routeIs('teacher.home') ? 'active' : '' }}" title="Trang chủ">
                    <i class="fa-solid fa-house"></i>
                    <span>Trang chủ</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teacher.info') }}"
                    class="nav-link {{ request()->routeIs('teacher.info') ? 'active' : '' }}" title="Thông tin cá nhân">
                    <i class="fa-regular fa-address-card"></i>
                    <span>Thông tin cá nhân</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teacher.schedule') }}"
                    class="nav-link {{ request()->routeIs('teacher.schedule') ? 'active' : '' }}"
                    title="Lịch giảng dạy">
                    <i class="fa-regular fa-calendar-days"></i>
                    <span>Lịch giảng dạy</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teacher.classes') }}"
                    class="nav-link {{ request()->routeIs('teacher.classes') ? 'active' : '' }}"
                    title="Danh sách Lớp học">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Danh sách Lớp học</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teacher.students') }}"
                    class="nav-link {{ request()->routeIs('teacher.students') ? 'active' : '' }}"
                    title="Danh sách Sinh viên">
                    <i class="fa-solid fa-users-viewfinder"></i>
                    <span>Danh sách Sinh viên</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teacher.grades') }}"
                    class="nav-link {{ request()->routeIs('teacher.grades') ? 'active' : '' }}"
                    title="Nhập & Cập nhật điểm">
                    <i class="fa-solid fa-marker"></i>
                    <span>Nhập & Cập nhật điểm</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teacher.attendances') }}"
                    class="nav-link {{ request()->routeIs('teacher.attendances') ? 'active' : '' }}" title="Điểm danh">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Điểm danh</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('teacher.feedback') }}" class="nav-link" title="Gửi phản hồi">
                    <i class="fa-regular fa-comment-dots"></i>
                    <span>Gửi phản hồi</span>
                </a>
            </li>
        </nav>

        <div class="sidebar-footer">
            <a href="#" class="logout-btn" onclick="toggleTheme(); return false;" title="Chế độ Giao diện"
                style="margin-bottom: 8px; background-color: transparent; border-color: transparent; color: var(--text-muted);">
                <i class="fa-solid fa-moon" id="theme-icon"></i>
                <span id="theme-text">Chế độ Tối</span>
            </a>
            <a href="{{ route('logout') }}" class="logout-btn" title="Đăng xuất">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </aside>

    <main class="main-content" role="main">
        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('userSidebarState', document.body.classList.contains('sidebar-collapsed') ? 'collapsed' :
                'expanded');
        }

        function toggleMobile() {
            document.body.classList.toggle('mobile-open');
        }
        if (localStorage.getItem('userSidebarState') === 'collapsed' && window.innerWidth > 768) {
            document.body.classList.add('sidebar-collapsed');
        }

        function toggleTheme() {
            const html = document.documentElement;
            const isLight = html.getAttribute('data-theme') === 'light';
            const newTheme = isLight ? 'dark' : 'light';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('theme-icon');
            const text = document.getElementById('theme-text');
            if (icon && text) {
                if (theme === 'light') {
                    icon.className = 'fa-solid fa-sun';
                    text.textContent = 'Chế độ Sáng';
                } else {
                    icon.className = 'fa-solid fa-moon';
                    text.textContent = 'Chế độ Tối';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcon(document.documentElement.getAttribute('data-theme') || 'dark');
        });
    </script>
</body>

</html>
