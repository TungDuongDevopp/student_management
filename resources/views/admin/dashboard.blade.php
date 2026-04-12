@extends('layouts.admin')

@push('styles')
    <style>
        /* CSS dùng riêng cho trang Dashboard (bạn viết thêm ở đây) */
        .dashboard-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-box">
        <h1>Admin Dashboard</h1>
        <p>Chào mừng bạn đến với trang quản trị hệ thống.</p>
    </div>
@endsection

@push('scripts')
    <script>
        // JS dành riêng cho trang Dashboard
        console.log('Bạn đang ở màn hình Dashboard!');
    </script>
@endpush
