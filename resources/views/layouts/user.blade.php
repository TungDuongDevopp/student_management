<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Sinh viên</title>
    
    <!-- Link nhúng trực tiếp file CSS chung dành cho User -->
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    
    <!-- Dành chỗ để thêm CSS riêng biệt rải rác ở từng trang con -->
    @stack('styles')
</head>
<body>
    <!-- Thanh điều hướng chung (Header) -->
    <header class="user-header">
        <div class="logo" style="display: flex; align-items: center; gap: 10px;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Sinh Viên" style="height: 40px; border-radius: 8px;">
            <h2 style="margin: 0;">Hệ thống Sinh viên</h2>
        </div>
        <nav class="user-nav">
            <a href="#">Trang chủ</a>
            <a href="#">Hồ sơ cá nhân</a>
            <a href="#">Đăng xuất</a>
        </nav>
    </header>

    <!-- Khu vực hiển thị nội dung chính -->
    <main class="user-container">
        @yield('content')
    </main>

    <!-- Nhúng Javascript chung cho trang user -->
    <script src="{{ asset('js/user.js') }}"></script>
    
    <!-- Dành chỗ (stack) để cho các trang con thêm Javascript riêng của chúng -->
    @stack('scripts')
</body>
</html>
