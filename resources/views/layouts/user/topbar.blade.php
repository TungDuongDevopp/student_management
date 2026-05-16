{{-- User Top Bar (Sinh viên / Giảng viên) --}}
<header class="topbar topbar-user-portal">
    <div class="topbar-left">
        <button class="topbar-toggle" onclick="toggleSidebar()" title="Thu gọn menu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="topbar-breadcrumb">
            @if (Auth::check() && Auth::user()->student)
                <a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i></a>
            @elseif(Auth::check() && Auth::user()->teacher)
                <a href="{{ route('teacher.home') }}"><i class="fa-solid fa-house"></i></a>
            @endif
            <i class="fa-solid fa-angle-right"></i>
            <span>@yield('title', 'Trang chủ')</span>
        </div>
    </div>

    <div class="topbar-right">
        {{-- Notifications --}}
        <div class="topbar-icon-btn" title="Thông báo">
            <i class="fa-regular fa-bell"></i>
            <span class="topbar-badge">0</span>
        </div>

        {{-- User Dropdown --}}
        <div class="topbar-user" id="topbar-user-dropdown">
            <div class="topbar-user-info">
                @if (Auth::check() && Auth::user()->student)
                    <span class="topbar-user-name">{{ Auth::user()->student?->name ?? Auth::user()->username }}</span>
                    <span class="topbar-user-role">Sinh viên</span>
                @elseif(Auth::check() && Auth::user()->teacher)
                    <span class="topbar-user-name">{{ Auth::user()->teacher?->name ?? Auth::user()->username }}</span>
                    <span class="topbar-user-role">Giảng viên</span>
                @else
                    <span class="topbar-user-name">{{ Auth::user()->username ?? 'Người dùng' }}</span>
                    <span class="topbar-user-role">User</span>
                @endif
            </div>
            <div class="topbar-avatar">
                @if (Auth::check() && (Auth::user()->student?->images || Auth::user()->teacher?->images))
                    <img src="{{ asset('storage/' . (Auth::user()->student?->images ?? Auth::user()->teacher?->images)) }}"
                        alt="Avatar">
                @else
                    <i class="fa-solid fa-user"></i>
                @endif
            </div>

            {{-- Dropdown Menu --}}
            <div class="topbar-dropdown">
                @if (Auth::check() && Auth::user()->student)
                    <a href="{{ route('student.info') }}">
                        <i class="fa-regular fa-circle-user"></i> Thông tin cá nhân
                    </a>
                @elseif(Auth::check() && Auth::user()->teacher)
                    <a href="{{ route('teacher.info') }}">
                        <i class="fa-regular fa-circle-user"></i> Thông tin cá nhân
                    </a>
                @else
                    <a href="#">
                        <i class="fa-regular fa-circle-user"></i> Thông tin cá nhân
                    </a>
                @endif

                <a href="#">
                    <i class="fa-solid fa-key"></i> Đổi mật khẩu
                </a>
                <div class="topbar-dropdown-divider"></div>
                <a href="#" class="topbar-logout-link"
                    onclick="event.preventDefault(); document.getElementById('topbar-logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
                <form id="topbar-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    // --- DROPDOWN LOGIC (Nằm ngay tại Topbar cho dễ quản lý) ---
    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.topbar-dropdown');
        const userBtn = document.getElementById('topbar-user-dropdown');

        if (!userBtn || !dropdown) return;

        // 1. Nếu click trúng Link (Thông tin cá nhân, Đăng xuất...) -> CHUYỂN HƯỚNG NGAY
        if (event.target.closest('.topbar-dropdown a')) {
            return; // Để trình duyệt tự chạy link href
        }

        // 2. Nếu click vào vùng Avatar/Tên -> TOGGLE đóng mở menu
        if (userBtn.contains(event.target)) {
            event.preventDefault();
            dropdown.classList.toggle('show');
            userBtn.classList.toggle('active');
        } else {
            // 3. Click ra ngoài -> ĐÓNG MENU
            dropdown.classList.remove('show');
            userBtn.classList.remove('active');
        }
    });
</script>
