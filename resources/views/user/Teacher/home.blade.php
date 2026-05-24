@extends('layouts.user.teacher_sidebar')

@section('title', 'Trang chủ Giảng viên')

@section('content')
    <style>
        .home-container {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            color: #334155;
        }

        .page-hero {
            background: linear-gradient(135deg, #7f1d1d 0%, #dc2626 100%);
            color: #fff;
            border-radius: 10px;
            padding: 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .page-hero::after {
            content: "";
            position: absolute;
            top: -80px;
            right: -60px;
            width: 260px;
            height: 260px;
            background: rgba(255, 255, 255, .08);
            transform: rotate(45deg);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-eyebrow {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            opacity: .85;
            font-weight: 700;
            margin-bottom: .4rem;
        }

        .hero-title {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .hero-desc {
            margin: .45rem 0 0;
            font-size: .9rem;
            opacity: .9;
            line-height: 1.5;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #dc2626;
            border-radius: 12px 0 0 12px;
        }

        .stat-card:nth-child(2)::before {
            background: #0284c7;
        }

        .stat-card:nth-child(3)::before {
            background: #f59e0b;
        }



        .stat-card .stat-icon {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 1.25rem;
            opacity: 0.15;
        }

        .stat-card:nth-child(1) .stat-icon {
            color: #dc2626;
        }

        .stat-card:nth-child(2) .stat-icon {
            color: #0284c7;
        }

        .stat-card:nth-child(3) .stat-icon {
            color: #f59e0b;
        }

        .stat-card:nth-child(4) .stat-icon {
            color: #10b981;
        }

        .stat-card .stat-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.5px;
            margin: 0 0 0.5rem;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.25rem;
            line-height: 1.2;
        }

        .stat-card .stat-desc {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
            margin: 0;
        }

        .stat-card .stat-desc b {
            color: #334155;
            font-weight: 600;
        }

        /* Tin tức & Lịch dạy (Tái sử dụng CSS Sinh viên) */
        .news-section,
        .schedule-today {
            background: #fff;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.6rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-header h2 {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
        }

        .section-header a {
            font-size: 0.8rem;
            color: #dc2626;
            text-decoration: none;
            font-weight: 500;
        }

        .section-header a:hover {
            text-decoration: underline;
        }

        .news-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .news-item {
            display: flex;
            flex-direction: column;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .news-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        }

        .news-thumb {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .news-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .news-body h3 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            margin-top: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-body h3 a {
            color: #0f172a;
            text-decoration: none;
        }

        .news-body h3 a:hover {
            color: #dc2626;
        }

        .news-meta {
            font-size: 0.72rem;
            color: #64748b;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .news-tag {
            background: #fef2f2;
            color: #dc2626;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.65rem;
        }

        .news-desc {
            font-size: 0.82rem;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 0;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        .schedule-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .schedule-time {
            min-width: 55px;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: #dc2626;
            background: #fef2f2;
            padding: 0.35rem 0.5rem;
            border-radius: 4px;
            border: 1px solid #fecaca;
        }

        .schedule-info h4 {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.15rem;
            margin-top: 0;
        }

        .schedule-info p {
            font-size: 0.75rem;
            color: #64748b;
            margin: 0;
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .news-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .news-list {
                grid-template-columns: 1fr;
            }

            .welcome-banner {
                padding: 1.5rem;
            }
        }
    </style>

    <main class="home-container">

        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li><a href="{{ route('teacher.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
                <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                <li class="active">Tổng quan</li>
            </ol>
        </nav>

        <section class="welcome-banner page-hero" aria-label="Lời chào">
            <div class="hero-content">
                <div class="hero-eyebrow">Teacher Academic Portal</div>
                <h1 class="hero-title" style="margin-bottom: 0.35rem;">Xin chào Giảng viên,
                    {{ Auth::user()->teacher?->name ?? (Auth::user()->username ?? 'Giảng viên') }}!</h1>
                <p class="hero-desc">
                    @if (count($todaySchedules) > 0)
                        Hôm nay bạn có <strong>{{ count($todaySchedules) }} lớp</strong> cần giảng dạy. Chúc bạn một buổi
                        lên
                        lớp hiệu quả!
                    @else
                        Hôm nay bạn không có lớp dạy nào. Chúc bạn nghỉ ngơi vui vẻ!
                    @endif
                </p>
            </div>
        </section>

        <section class="stats-grid" aria-label="Chỉ số giảng dạy">
            <article class="stat-card">
                <i class="fa-solid fa-chalkboard-user stat-icon"></i>
                <div class="stat-title">Lớp giảng dạy</div>
                <div class="stat-value">{{ $stats['assigned_classes'] ?? 0 }}</div>
                <div class="stat-desc">Học kỳ hiện tại</div>
            </article>

            <article class="stat-card">
                <i class="fa-solid fa-users stat-icon"></i>
                <div class="stat-title">Sinh viên</div>
                <div class="stat-value">{{ $stats['total_students'] ?? 0 }}</div>
                <div class="stat-desc">Đang quản lý</div>
            </article>

            <article class="stat-card">
                <i class="fa-solid fa-clock stat-icon"></i>
                <div class="stat-title">Lịch học hôm nay</div>
                <div class="stat-value">{{ count($todaySchedules) }}</div>
                <div class="stat-desc">
                    @if (count($todaySchedules) > 0)
                        Ca đầu: <b>{{ $todaySchedules[0]['start_time'] ?? '--:--' }}</b>
                    @else
                        Hôm nay trống tiết
                    @endif
                </div>
            </article>


        </section>

        <section class="news-section" aria-labelledby="news-heading">
            <div class="section-header">
                <h2 id="news-heading"><i class="fa-regular fa-newspaper" style="margin-right:8px; color:#dc2626;"></i>Tin
                    tức & Thông báo nội bộ</h2>
                <a href="#">Xem tất cả →</a>
            </div>
            <div class="news-list">
                @forelse($news ?? [] as $article)
                    <article class="news-item">
                        <img src="{{ $article->thumbnail ? asset($article->thumbnail) : 'https://upload.wikimedia.org/wikipedia/commons/2/25/Truong_Dai_hoc_Mo_Dia_chat.jpg' }}"
                            alt="{{ $article->title }}" class="news-thumb" loading="lazy">
                        <div class="news-body">
                            <div class="news-meta">
                                <span class="news-tag">{{ $article->category ?? 'Chung' }}</span>
                                <time datetime="{{ $article->created_at->format('Y-m-d') }}"><i class="fa-regular fa-clock"
                                        style="margin-right:4px;"></i>
                                    {{ $article->created_at->format('d/m/Y') }}</time>
                            </div>
                            <h3><a href="{{ route('user.news.show', $article->id) }}">{{ $article->title }}</a></h3>
                            <p class="news-desc">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: span 3; text-align: center; color: #64748b; padding: 2rem;">
                        Chưa có thông báo mới.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="schedule-today" aria-labelledby="schedule-heading">
            <div class="section-header">
                <h2 id="schedule-heading"><i class="fa-solid fa-chalkboard-user"
                        style="margin-right:8px; color:#10b981;"></i>Lịch giảng dạy hôm nay</h2>
                <a href="{{ route('teacher.schedule') }}">Xem toàn bộ lịch →</a>
            </div>
            @forelse($todaySchedules as $item)
                <div class="schedule-item">
                    <span class="schedule-time">{{ $item['start_time'] ?: '--:--' }}</span>
                    <div class="schedule-info">
                        <h4>{{ $item['subject_name'] }}{{ $item['group_code'] ? ' - Nhóm ' . $item['group_code'] : '' }}
                        </h4>
                        <p>Phòng {{ $item['room'] }} &middot; Sĩ số: {{ $item['current_capacity'] }} SV &middot;
                            {{ $item['start_time'] }}–{{ $item['end_time'] }}</p>
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:1.5rem;color:#94a3b8;font-size:.9rem;">
                    <i class="fa-solid fa-calendar-xmark" style="font-size:1.5rem;margin-bottom:.5rem;display:block;"></i>
                    Không có lớp dạy hôm nay
                </div>
            @endforelse
        </section>

    </main>
@endsection
