<aside class="admin-sidebar" id="sidebar">
    <!-- Nút Toggle thu gọn -->
    <div class="toggle-btn" onclick="toggleSidebar()" title="Thu gọn/Phóng to Sidebar">
        <i class="fa-solid fa-chevron-left"></i>
    </div>

    <div class="sidebar-brand">
        <img src="https://lic.humg.edu.vn/App_Themes/humg/images/humg-logo.png" alt="Logo Trường"
            style="width: 42px; height: 42px; border-radius: 4px; object-fit: cover; flex-shrink: 0; transition: margin 0.3s;">
        <div class="brand-text">
            <h2 style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin: 0; line-height: 1.2;">
                ADMIN PANEL</h2>
            <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">
                {{ Auth::user()->username ?? 'Quản trị viên' }}
            </span>
        </div>
    </div>

    <nav class="sidebar-nav" aria-label="Menu Chính">
        <div class="nav-section">Hệ thống</div>
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Tổng quan</span>
            </a>
        </li>

        <div class="nav-section">Nhân sự</div>
        <li class="nav-item">
            <a href="{{ route('admin.accounts') }}"
                class="nav-link {{ request()->routeIs('admin.accounts') ? 'active' : '' }}"
                title="Quản lý Tài khoản">
                <i class="fa-solid fa-users-gear"></i>
                <span>Quản lý tài khoản</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.students') }}"
                class="nav-link {{ request()->routeIs('admin.students') ? 'active' : '' }}" title="Hồ sơ Sinh viên">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Quản lý Sinh viên</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.teachers') }}"
                class="nav-link {{ request()->routeIs('admin.teachers') ? 'active' : '' }}"
                title="Hồ sơ Giảng viên">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Quản lý Giảng viên</span>
            </a>
        </li>

        <div class="nav-section">Đào tạo</div>
        <li class="nav-item">
            <a href="{{ route('admin.classes') }}"
                class="nav-link {{ request()->routeIs('admin.classes') ? 'active' : '' }}" title="Danh sách Lớp">
                <i class="fa-solid fa-layer-group"></i>
                <span>Quản lý Lớp học</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.attendences') }}"
                class="nav-link {{ request()->routeIs('admin.attendences') ? 'active' : '' }}"
                title="Danh sách điểm danh">
                <i class="fa-solid fa-user-check"></i>
                <span>Quản lý điểm danh</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.enrollments') }}"
                class="nav-link {{ request()->routeIs('admin.enrollments') ? 'active' : '' }}" title="Đơn đăng ký">
                <i class="fa-solid fa-star-half-stroke"></i>
                <span>Quản lý đơn đăng ký</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.subjects') }}"
                class="nav-link {{ request()->routeIs('admin.subjects') ? 'active' : '' }}" title="Danh sách Môn">
                <i class="fa-solid fa-book-open"></i>
                <span>Quản lý Môn học</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.schedules') }}"
                class="nav-link {{ request()->routeIs('admin.schedules') ? 'active' : '' }}"
                title="Lịch giảng dạy">
                <i class="fa-regular fa-calendar-days"></i>
                <span>Quản lý Lịch học</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.semesters') }}"
                class="nav-link {{ request()->routeIs('admin.semesters') ? 'active' : '' }}"
                title="Quản lý học kỳ">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Quản lý học kỳ</span>
            </a>
        </li>

        <div class="nav-section">Tài chính & Đánh giá</div>

        <li class="nav-item">
            <a href="{{ route('admin.fees') }}"
                class="nav-link {{ request()->routeIs('admin.fees') ? 'active' : '' }}" title="Công nợ học phí">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Học phí & Công nợ</span>
            </a>
        </li>

        <div class="nav-section">Khác</div>

        <li class="nav-item">
            <a href="{{ route('admin.feedbacks') }}"
                class="nav-link {{ request()->routeIs('admin.feedbacks') ? 'active' : '' }}" title="Góp ý">
                <i class="fa-regular fa-comments"></i>
                <span>Xem phản hồi</span>
            </a>
        </li>
    </nav>

    <div class="sidebar-footer">
        <a href="#" class="footer-btn btn-theme" onclick="toggleTheme(); return false;"
            title="Chế độ Giao diện">
            <i class="fa-solid fa-moon" id="theme-icon"></i>
            <span id="theme-text">Chế độ Tối</span>
        </a>

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
