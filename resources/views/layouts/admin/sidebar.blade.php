<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Trang Quản trị Hệ thống Quản lý Sinh viên">
    <title>@yield('title', 'Admin Dashboard - Hệ Thống Quản Lý')</title>
    
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
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        body { display: flex; min-height: 100vh; background-color: var(--bg-body); color: var(--text-main); overflow-x: hidden; transition: all 0.3s ease; }

        .admin-sidebar {
            width: var(--sidebar-width); background-color: var(--bg-sidebar); border-right: 1px solid var(--border-color);
            display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; z-index: 40;
            transition: width 0.3s ease;
        }

        /* Nút Toggle thu gọn */
        .toggle-btn {
            position: absolute; top: 1.5rem; right: -14px; width: 28px; height: 28px;
            background: #ffffff; color: var(--text-muted); border: 1px solid var(--border-color);
            border-radius: 4px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; z-index: 50; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: all 0.2s;
        }
        .toggle-btn:hover { background: var(--primary-light); color: var(--primary); border-color: var(--primary); }

        .sidebar-brand {
            padding: 1.5rem; display: flex; align-items: center; gap: 14px; border-bottom: 1px solid var(--border-color);
            height: 80px; overflow: hidden; white-space: nowrap;
        }

        .sidebar-nav { flex: 1; padding: 1.5rem 0; overflow-y: auto; overflow-x: hidden; }

        .nav-section {
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);
            font-weight: 700; margin-bottom: 0.5rem; margin-top: 1.5rem; padding-left: 1.5rem; white-space: nowrap; transition: opacity 0.2s;
        }
        .nav-section:first-child { margin-top: 0; }

        .nav-item { list-style: none; margin-bottom: 0.25rem; }

        .nav-link {
            display: flex; align-items: center; gap: 12px; padding: 0.75rem 1.5rem;
            color: var(--text-muted); text-decoration: none; font-weight: 500; font-size: 0.95rem;
            border-left: 4px solid transparent; transition: all 0.2s ease; white-space: nowrap;
        }
        .nav-link i { font-size: 1.1rem; width: 24px; text-align: center; flex-shrink: 0; }
        
        
        .nav-link:hover { color: var(--text-main); background-color: var(--sidebar-hover); }
        .nav-link.active { color: var(--primary); background-color: var(--primary-light); font-weight: 600; border-left: 4px solid var(--primary); }

        .sidebar-footer {
            padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 0.5rem; overflow: hidden; white-space: nowrap;
        }

        .footer-btn {
            display: flex; align-items: center; gap: 12px; padding: 0.75rem; text-decoration: none;
            font-weight: 500; border-radius: 4px; transition: background 0.2s; font-size: 0.95rem; border: 1px solid transparent;
        }
        .footer-btn i { width: 24px; text-align: center; flex-shrink: 0; }

        .btn-home { color: var(--text-main); border-color: var(--border-color); }
        .btn-home:hover { background-color: var(--sidebar-hover); }
        .btn-logout { color: #ef4444; background: #fef2f2; border-color: #fecaca; }
        .btn-logout:hover { background-color: #fee2e2; }

        .main-content { flex: 1; padding: 2rem; width: calc(100% - var(--sidebar-width)); transition: width 0.3s ease; }

        body.sidebar-collapsed .admin-sidebar { width: var(--sidebar-collapsed-width); }
        body.sidebar-collapsed .main-content { width: calc(100% - var(--sidebar-collapsed-width)); }
        body.sidebar-collapsed .sidebar-brand img { margin-left: -5px; } 
        body.sidebar-collapsed .brand-text,
        body.sidebar-collapsed .nav-section,
        body.sidebar-collapsed .nav-link span,
        body.sidebar-collapsed .footer-btn span { display: none; }
        body.sidebar-collapsed .nav-link { padding: 0.75rem 0; justify-content: center; }
        body.sidebar-collapsed .footer-btn { padding: 0.75rem 0; justify-content: center; }
        body.sidebar-collapsed .toggle-btn i { transform: rotate(180deg); }
    </style>
</head>
<body>

    <aside class="admin-sidebar" id="sidebar">
        <!-- Nút Toggle -->
        <div class="toggle-btn" onclick="toggleSidebar()" title="Thu gọn/Phóng to Sidebar">
            <i class="fa-solid fa-chevron-left"></i>
        </div>

        <div class="sidebar-brand">
            <img src="https://lic.humg.edu.vn/App_Themes/humg/images/humg-logo.png" alt="Logo Trường" style="width: 42px; height: 42px; border-radius: 4px; object-fit: cover; flex-shrink: 0; transition: margin 0.3s;">
            <div class="brand-text" style="display: flex; flex-direction: column; justify-content: center;">
                <h2 style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin: 0; line-height: 1.2;">ADMIN PANEL</h2>
                <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">
                    {{ Auth::user()->username ?? 'Quản trị viên' }}
                </span>
            </div>
        </div>

        <nav class="sidebar-nav" aria-label="Menu Chính">
            <div class="nav-section">Hệ thống</div>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link active" title="Tổng quan hệ thống">
                    <i class="fa-solid fa-house"></i>
                    <span>Tổng quan</span>
                </a>
            </li>
            
            <div class="nav-section">Nhân sự</div>
            <li class="nav-item">
                <a href="{{ route('admin.accounts') }}" class="nav-link" title="Quản lý Tài khoản">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Quản lý tài khoản</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.students') }}" class="nav-link" title="Hồ sơ Sinh viên">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Hồ sơ Sinh viên</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.teachers') }}" class="nav-link" title="Hồ sơ Giảng viên">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Hồ sơ Giảng viên</span>
                </a>
            </li>

            <div class="nav-section">Đào tạo</div>
            <li class="nav-item">
                <a href="{{ route('admin.classes') }}" class="nav-link" title="Danh sách Lớp">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Quản lý Lớp học</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.subjects') }}" class="nav-link" title="Danh sách Môn">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Quản lý Môn học</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.schedules') }}" class="nav-link" title="Lịch giảng dạy">
                    <i class="fa-regular fa-calendar-days"></i>
                    <span>Thời khóa biểu</span>
                </a>
            </li>

            <div class="nav-section">Tài chính & Đánh giá</div>
            <li class="nav-item">
                <a href="{{ route('admin.grades') }}" class="nav-link" title="Bảng điểm">
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>Bảng điểm</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.fees') }}" class="nav-link" title="Công nợ học phí">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Học phí & Công nợ</span>
                </a>
            </li>

            <div class="nav-section">Khác</div>
            <li class="nav-item">
                <a href="{{ route('admin.announcements') }}" class="nav-link" title="Bảng tin">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>Thông báo</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.feedbacks') }}" class="nav-link" title="Góp ý">
                    <i class="fa-regular fa-comments"></i>
                    <span>Xem phản hồi</span>
                </a>
            </li>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ url('/') }}" class="footer-btn btn-home" title="Về trang chủ hệ thống">
                <i class="fa-solid fa-building-columns"></i>
                <span>Trang chủ Website</span>
            </a>
            
            <a href="{{ route('logout') }}" class="footer-btn btn-logout" title="Đăng xuất khỏi hệ thống">
                <i class="fa-solid fa-power-off"></i>
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
           
            localStorage.setItem('sidebarState', document.body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
        }
        if (localStorage.getItem('sidebarState') === 'collapsed') {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>
</body>
</html>
