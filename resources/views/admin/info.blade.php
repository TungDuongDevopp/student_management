@extends('layouts.admin.sidebar')
@section('title', 'Thông tin cá nhân Admin')
@section('content')
<style>
    .info-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; }
    .info-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 2rem; }
    .info-item { margin-bottom: 1.5rem; }
    .info-label { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; display: block; }
    .info-value { color: var(--text); font-weight: 600; font-size: 1rem; }
    .admin-badge { background: #ef4444; color: #white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
</style>

<div class="page-header">
    <h1><i class="fa-solid fa-user-shield" style="margin-right: 10px;"></i>Hồ sơ Quản trị viên</h1>
</div>

<div class="info-grid">
    <div class="info-card" style="text-align: center;">
        <div style="width: 120px; height: 120px; border-radius: 50%; background: #334155; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #94a3b8; border: 4px solid #3b82f6;">
            <i class="fa-solid fa-user-secret"></i>
        </div>
        <h2 style="margin-bottom: 0.5rem;">{{ Auth::user()->username }}</h2>
        <span class="admin-badge">Root Administrator</span>
        <div style="margin-top: 2rem; text-align: left;">
            <div class="info-item">
                <span class="info-label">Quyền hạn</span>
                <span class="info-value">Toàn quyền hệ thống (Super Admin)</span>
            </div>
            <div class="info-item">
                <span class="info-label">Ngày tham gia</span>
                <span class="info-value">01/01/2025</span>
            </div>
        </div>
    </div>

    <div class="info-card">
        <h3>Thông tin chi tiết</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 1.5rem;">
            <div class="info-item">
                <span class="info-label">Tên người dùng</span>
                <span class="info-value">{{ Auth::user()->username }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email quản trị</span>
                <span class="info-value">admin@humg.edu.vn</span>
            </div>
            <div class="info-item">
                <span class="info-label">Số điện thoại nội bộ</span>
                <span class="info-value">024 3838 3838</span>
            </div>
            <div class="info-item">
                <span class="info-label">Phòng ban</span>
                <span class="info-value">Trung tâm CNTT & Truyền thông</span>
            </div>
            <div class="info-item">
                <span class="info-label">Lần đăng nhập cuối</span>
                <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">IP hiện tại</span>
                <span class="info-value">127.0.0.1</span>
            </div>
        </div>
        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); display: flex; gap: 1rem;">
            <button class="btn btn-primary" style="padding: 0.75rem 1.5rem; border: none; border-radius: 8px; background: #3b82f6; color: white; cursor: pointer; font-weight: 700;">
                <i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa thông tin
            </button>
            <a href="{{ route('admin.change_password') }}" class="btn btn-secondary" style="padding: 0.75rem 1.5rem; border: 1px solid #cbd5e1; border-radius: 8px; background: white; color: #475569; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; transition: background 0.2s;">
                <i class="fa-solid fa-lock"></i> Đổi mật khẩu
            </a>
        </div>
    </div>
</div>
@endsection
