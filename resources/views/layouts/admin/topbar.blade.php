{{-- Admin Top Bar --}}
<header class="topbar">
    <div class="topbar-left">
        <button class="topbar-toggle" onclick="toggleSidebar()" title="Thu gọn menu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="topbar-breadcrumb">
            <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-angle-right"></i>
            <span>@yield('title', 'Tổng quan')</span>
        </div>
    </div>

    <div class="topbar-right">
        {{-- Notifications --}}
        <div class="topbar-icon-btn" title="Thông báo">
            <i class="fa-regular fa-bell"></i>
            <span class="topbar-badge">2</span>
        </div>

        {{-- User Dropdown --}}
        <div class="topbar-user" id="topbar-user-dropdown">
            <div class="topbar-user-info">
                <span class="topbar-user-name">{{ Auth::user()->username ?? 'Admin' }}</span>
                <span class="topbar-user-role">Quản trị viên</span>
            </div>
            <div class="topbar-avatar">
                <i class="fa-solid fa-user-shield"></i>
            </div>

            {{-- Dropdown Menu --}}
            <div class="topbar-dropdown">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge"></i> Tổng quan
                </a>
                <a href="{{ route('admin.info') }}">
                    <i class="fa-regular fa-circle-user"></i> Thông tin cá nhân
                </a>
                <a href="#">
                    <i class="fa-solid fa-key"></i> Đổi mật khẩu
                </a>
                <div class="topbar-dropdown-divider"></div>
                <a href="#" class="topbar-logout-link" onclick="event.preventDefault(); document.getElementById('topbar-logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
                <form id="topbar-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.topbar-dropdown');
        const userBtn = document.getElementById('topbar-user-dropdown');
        
        if (!userBtn || !dropdown) return;

        if (event.target.closest('.topbar-dropdown a')) {
            return;
        }

        if (userBtn.contains(event.target)) {
            event.preventDefault();
            dropdown.classList.toggle('show');
            userBtn.classList.toggle('active');
        } else {
            dropdown.classList.remove('show');
            userBtn.classList.remove('active');
        }
    });
</script>
