{{-- User Top Bar (Sinh viên / Giảng viên) --}}
<header class="topbar topbar-user-portal">
    <div class="topbar-left">
        <button class="topbar-toggle" onclick="toggleSidebar()" title="Thu gọn menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        
        @php
            $target = (Auth::check() && Auth::user()->role_id == 2) ? 'teacher' : 'student';
            $topNews = \App\Models\News::where('is_published', true)
                ->whereIn('target_audience', [$target, 'all'])
                ->latest()
                ->first();
                
            $recentNewsList = \App\Models\News::where('is_published', true)
                ->whereIn('target_audience', [$target, 'all'])
                ->latest()
                ->take(5)
                ->get();
                
            $newNewsCount = $recentNewsList->where('created_at', '>=', now()->subDays(3))->count();
        @endphp
    </div>

    <div class="topbar-right">
        {{-- Notifications Dropdown --}}
        <div class="topbar-user" id="topbar-notifications-dropdown" style="position: relative;">
            <div class="topbar-icon-btn" title="Thông báo" style="cursor: pointer;">
                <i class="fa-regular fa-bell" style="{{ $newNewsCount > 0 ? 'animation: ring 2s infinite; color: #ef4444;' : '' }}"></i>
                <span class="topbar-badge" style="{{ $newNewsCount > 0 ? 'background-color: #ef4444;' : '' }}">{{ $newNewsCount }}</span>
            </div>
            
            <div class="topbar-dropdown notifications-menu" style="width: 320px; padding: 0; right: -10px;">
                <div style="padding: 10px 15px; font-weight: 700; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; color: var(--text-main);">
                    Thông báo mới
                    <a href="{{ route('user.news.index') }}" style="font-size: 0.75rem; color: var(--primary); text-decoration: none; padding: 0;">Xem tất cả</a>
                </div>
                <div style="max-height: 350px; overflow-y: auto;">
                    @forelse($recentNewsList as $n)
                    <a href="{{ route('user.news.show', $n->id) }}" style="display: flex; gap: 12px; padding: 12px 15px; border-bottom: 1px solid var(--border-color); text-decoration: none; align-items: flex-start; transition: background 0.2s;">
                        <img src="{{ $n->thumbnail ? asset($n->thumbnail) : 'https://upload.wikimedia.org/wikipedia/commons/2/25/Truong_Dai_hoc_Mo_Dia_chat.jpg' }}" alt="thumb" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; flex-shrink: 0; background: #fff;">
                        <div style="flex: 1; overflow: hidden; display: flex; flex-direction: column; gap: 4px;">
                            <div style="font-weight: 600; font-size: 0.85rem; color: var(--text-main); line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $n->title }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">{{ strip_tags($n->content) }}</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted); opacity: 0.8;"><i class="fa-regular fa-clock"></i> {{ $n->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                    @empty
                    <div style="padding: 20px 15px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">Không có thông báo nào.</div>
                    @endforelse
                </div>
            </div>
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
        // DROPDOWN USER
        const userBtn = document.getElementById('topbar-user-dropdown');
        const userDropdown = userBtn ? userBtn.querySelector('.topbar-dropdown') : null;
        
        // DROPDOWN NOTIFICATIONS
        const notifBtn = document.getElementById('topbar-notifications-dropdown');
        const notifDropdown = notifBtn ? notifBtn.querySelector('.topbar-dropdown') : null;

        // Xử lý đóng tất cả dropdowns
        function closeAllDropdowns() {
            if(userDropdown) {
                userDropdown.classList.remove('show');
                userBtn.classList.remove('active');
            }
            if(notifDropdown) {
                notifDropdown.classList.remove('show');
                notifBtn.classList.remove('active');
            }
        }

        // Nếu click trúng Link (Thông tin cá nhân, Đăng xuất, Xem tin tức...) -> CHUYỂN HƯỚNG NGAY
        if (event.target.closest('.topbar-dropdown a')) {
            return;
        }

        // Click vào chuông thông báo
        if (notifBtn && notifBtn.contains(event.target)) {
            event.preventDefault();
            const isShowing = notifDropdown.classList.contains('show');
            closeAllDropdowns();
            if (!isShowing) {
                notifDropdown.classList.add('show');
                notifBtn.classList.add('active');
            }
            return;
        }

        // Click vào Avatar/Tên người dùng
        if (userBtn && userBtn.contains(event.target)) {
            event.preventDefault();
            const isShowing = userDropdown.classList.contains('show');
            closeAllDropdowns();
            if (!isShowing) {
                userDropdown.classList.add('show');
                userBtn.classList.add('active');
            }
            return;
        }

        // Click ra ngoài -> ĐÓNG TẤT CẢ
        closeAllDropdowns();
    });
</script>
