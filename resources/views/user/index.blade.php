@extends('layouts.user')

@push('styles')
    <style>
        /* CSS dùng riêng cho trang chủ Sinh viên */
        .welcome-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="welcome-card">
        <h1>Test UI - Được khởi tạo bởi Sếp Vinh (Frontend Module)</h1>
        <p>Cấu trúc thư mục Laravel đã sẵn sàng!</p>
    </div>
@endsection

@push('scripts')
    <script>
        // JS riêng cho trang chủ Sinh viên
        console.log("Welcome Sinh Viên!");
    </script>
@endpush
