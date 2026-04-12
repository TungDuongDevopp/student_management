<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sinh viên - Admin</title>
    
    <!-- Link nhúng trực tiếp file CSS chung -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- Dành chỗ (stack) để cho các trang con có thể thêm CSS riêng của chúng vào đây -->
    @stack('styles')
</head>
<body>
    <div class="admin-container">
        <!-- Khu vực thanh truy cập bên trái -->
        <aside class="sidebar">
            <h2>Hệ thống</h2>
            <ul>
                <li><a href="#" style="color:white; text-decoration:none;">Dashboard</a></li>
                <li><a href="#" style="color:white; text-decoration:none;">Sinh viên</a></li>
            </ul>
        </aside>

        <!-- Khu vực hiển thị nội dung chính -->
        <main class="main-content">
            <!-- Dành chỗ (yield) để cho các trang con chèn giao diện thực tế vào đây -->
            @yield('content')
        </main>
    </div>

    <!-- Nhúng trực tiếp Javascript chung cho toàn hệ thống -->
    <script src="{{ asset('js/admin.js') }}"></script>
    
    <!-- Dành chỗ (stack) để cho các trang con thêm Javascript riêng của chúng -->
    @stack('scripts')
</body>
</html>
