@extends('layouts.user.teacher_sidebar')

@section('title', 'Thông tin Giảng viên')

@section('content')
    <style>
        .info-container {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            color: #334155;
            width: 100%;
        }

        .profile-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            border-radius: 8px;
            padding: 2rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .profile-header::after {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.06);
            transform: rotate(45deg);
        }

        .profile-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.35);
            background: #e2e8f0;
            z-index: 1;
        }

        .profile-main {
            z-index: 1;
        }

        .profile-main h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.35rem;
        }

        .profile-main p {
            margin: 0.15rem 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.28rem 0.65rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .badge-active {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-warning {
            background: #fef3c7;
            color: #b45309;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 6px;
            padding: 1.25rem 1rem;
            border-left: 4px solid #2563eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .stat-card:nth-child(2) {
            border-left-color: #10b981;
        }

        .stat-card:nth-child(3) {
            border-left-color: #f59e0b;
        }

        .stat-card:nth-child(4) {
            border-left-color: #ef4444;
        }

        .stat-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.03em;
            margin: 0 0 0.4rem;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .stat-sub {
            font-size: 0.75rem;
            color: #94a3b8;
            margin: 0.15rem 0 0;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 1.5rem;
        }

        .info-card {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
        }

        .info-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .info-item {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 1rem;
            background: #f8fafc;
        }

        .info-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin: 0 0 0.3rem;
        }

        .info-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
            line-height: 1.5;
        }

        .action-row {
            display: flex;
            gap: 0.75rem;
            flex-direction: column;
        }

        .btn {
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #334155;
            border-radius: 6px;
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: 0.2s;
        }

        .btn:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            color: #fff;
            border-color: #1d4ed8;
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
            }

            .stats-grid,
            .info-list {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <main class="info-container">
        <section class="profile-header">
            <img src="{{ $teacher?->images ? asset('storage/' . $teacher->images) : asset('images/default-avatar.png') }}"
                alt="Avatar" class="profile-avatar">
            <div class="profile-main">
                <h1>{{ $teacher?->name ?? (auth()->user()->username ?? 'N/A') }}</h1>
                <p>{{ $teacher?->degree ?? 'N/A' }}{{ !empty($teacher?->department) ? ' · ' . $teacher->department : '' }}
                </p>
                <p>Mã giảng viên: {{ $teacher?->teacher_code ?? 'N/A' }} · Khoa:
                    {{ $teacher?->faculty?->name ?? 'N/A' }}</p>
                <span class="badge badge-active">Đang công tác</span>
            </div>
        </section>

        <section class="stats-grid">
            <article class="stat-card">
                <p class="stat-label">Lớp đang giảng dạy</p>
                <p class="stat-value">4</p>
                <p class="stat-sub">Theo học kỳ hiện tại</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Sinh viên quản lý</p>
                <p class="stat-value">245</p>
                <p class="stat-sub">Tổng sinh viên trong lớp học phần</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Bảng điểm chưa chốt</p>
                <p class="stat-value">2</p>
                <p class="stat-sub">Cần rà soát trước khi khóa điểm</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Ticket đang xử lý</p>
                <p class="stat-value">12</p>
                <p class="stat-sub">Phiếu hỗ trợ chưa hoàn tất</p>
            </article>
        </section>

        <section class="content-grid">
            <div class="info-card">
                <div class="section-header">
                    <h2><i class="fa-regular fa-id-card" style="margin-right:8px; color:#2563eb;"></i>Thông tin cá nhân &
                        công tác</h2>
                </div>
                <div class="info-list">
                    <div class="info-item">
                        <p class="info-label">Họ và tên</p>
                        <p class="info-value">{{ $teacher?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Mã giảng viên</p>
                        <p class="info-value">{{ $teacher?->teacher_code ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Ngày sinh</p>
                        <p class="info-value">
                            {{ $teacher?->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Giới tính</p>
                        <p class="info-value">
                            {{ $teacher?->gender == 1 ? 'Nữ' : ($teacher?->gender == 0 ? 'Nam' : 'N/A') }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Email</p>
                        <p class="info-value">{{ $teacher?->email ?? (auth()->user()->username ?? 'N/A') }}
                        </p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Số điện thoại</p>
                        <p class="info-value">{{ $teacher?->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Địa chỉ</p>
                        <p class="info-value">{{ $teacher?->address ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Khoa</p>
                        <p class="info-value">{{ $teacher?->faculty?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Bộ môn</p>
                        <p class="info-value">{{ $teacher?->department ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Học vị</p>
                        <p class="info-value">{{ $teacher?->degree ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <aside class="info-card">
                <div class="section-header">
                    <h2><i class="fa-solid fa-bolt" style="margin-right:8px; color:#f59e0b;"></i>Truy cập nhanh</h2>
                </div>
                <div class="action-row">
                    <a href="{{ route('teacher.classes') }}" class="btn btn-primary"><i
                            class="fa-solid fa-chalkboard-user"></i> Lớp đang giảng dạy</a>
                    <a href="{{ route('teacher.schedule') }}" class="btn"><i class="fa-regular fa-calendar"></i> Lịch
                        giảng dạy</a>
                    <a href="{{ route('teacher.grades') }}" class="btn"><i class="fa-solid fa-clipboard-list"></i> Quản
                        lý điểm số</a>
                    <a href="{{ route('teacher.feedback') }}" class="btn"><i class="fa-regular fa-message"></i> Gửi hỗ
                        trợ (Ticket)</a>
                </div>
            </aside>
        </section>
    </main>
@endsection
