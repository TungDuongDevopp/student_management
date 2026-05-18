@extends('layouts.user.student_sidebar')

@section('title', 'Trang chủ Sinh viên')

@section('content')

    <style>
        .home-container {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            color: #334155;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border-radius: 8px;
            padding: 2rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.06);
            transform: rotate(45deg);
        }

        .welcome-banner h1 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .welcome-banner p {
            font-size: 0.88rem;
            opacity: 0.85;
            line-height: 1.5;
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
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
            background: #2563eb;
            border-radius: 12px 0 0 12px;
        }

        .stat-card:nth-child(2)::before {
            background: #10b981;
        }

        .stat-card:nth-child(3)::before {
            background: #f59e0b;
        }

        .stat-card:nth-child(4)::before {
            background: #ef4444;
        }

        .stat-card .stat-icon {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 1.25rem;
            opacity: 0.15;
        }

        .stat-card:nth-child(1) .stat-icon {
            color: #2563eb;
        }

        .stat-card:nth-child(2) .stat-icon {
            color: #10b981;
        }

        .stat-card:nth-child(3) .stat-icon {
            color: #f59e0b;
        }

        .stat-card:nth-child(4) .stat-icon {
            color: #ef4444;
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

        .news-section {
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
            color: #2563eb;
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
            color: #0f172a;
            margin-bottom: 0.5rem;
            margin-top: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-body h3 a {
            color: inherit;
            text-decoration: none;
        }

        .news-body h3 a:hover {
            color: #2563eb;
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
            background: #eff6ff;
            color: #2563eb;
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

        /* === LỊCH HỌC HÔM NAY === */
        .schedule-today {
            background: #fff;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
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
            color: #2563eb;
            background: #eff6ff;
            padding: 0.35rem 0.5rem;
            border-radius: 4px;
            border: 1px solid #bfdbfe;
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
                <li><a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
                <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                <li class="active">Tổng quan</li>
            </ol>
        </nav>

        <section class="welcome-banner" aria-label="Lời chào">
            <div
                style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.2px; font-weight: 700; opacity: 0.9; margin-bottom: 0.25rem;">
                Trang chủ Sinh viên</div>
            <h1>Xin chào Sinh viên, {{ Auth::user()->student?->name ?? (Auth::user()->username ?? 'N/A') }}!</h1>
            <p>
                @if (count($todaySchedules) > 0)
                    Hôm nay bạn có <strong>{{ count($todaySchedules) }} môn</strong> cần lên lớp. Chúc bạn học tập hiệu quả!
                @else
                    Hôm nay bạn không có tiết học nào. Chúc bạn nghỉ ngơi vui vẻ!
                @endif
            </p>
        </section>
    </main>
    <section class="stats-grid" aria-label="Chỉ số tổng quan" style="margin-top: 1rem; margin-bottom: 1rem;">
        <article class="stat-card">
            <i class="fa-solid fa-graduation-cap stat-icon"></i>
            <div class="stat-title">Điểm GPA</div>
            <div class="stat-value">{{ $gpa ?? '0.00' }}</div>
            <div class="stat-desc">Xếp loại: <b>{{ $ranking ?? 'Chưa xác định' }}</b></div>
        </article>

        <article class="stat-card">
            <i class="fa-solid fa-book stat-icon"></i>
            <div class="stat-title">Tín chỉ</div>
            <div class="stat-value">{{ $earned_credits ?? '0' }}</div>
            <div class="stat-desc">Đã tích lũy</div>
        </article>

        <article class="stat-card">
            <i class="fa-solid fa-calendar-week stat-icon"></i>
            <div class="stat-title">Môn học</div>
            <div class="stat-value">{{ $current_subjects_count ?? '0' }}</div>
            <div class="stat-desc">Học kỳ hiện tại</div>
        </article>

        <article class="stat-card">
            <i class="fa-solid fa-clock stat-icon"></i>
            <div class="stat-title">Lịch học</div>
            <div class="stat-value">{{ count($todaySchedules) }}</div>
            <div class="stat-desc">
                @if (count($todaySchedules) > 0)
                    Ca đầu: <b>{{ $todaySchedules[0]['start_time'] ?? '--:--' }}</b>
                @else
                    Hôm nay nghỉ
                @endif
            </div>
        </article>
    </section>

    <section class="news-section" aria-labelledby="news-heading">
        <div class="section-header">
            <h2 id="news-heading"><i class="fa-regular fa-newspaper" style="margin-right:8px; color:#2563eb;"></i>Tin
                tức & Thông báo</h2>
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
            <h2 id="schedule-heading"><i class="fa-regular fa-clock" style="margin-right:8px; color:#10b981;"></i>Lịch học
                hôm nay</h2>
            <a href="{{ route('student.schedule') }}">Xem toàn bộ lịch →</a>
        </div>
        @forelse($todaySchedules as $item)
            <div class="schedule-item">
                <span class="schedule-time">{{ $item['start_time'] ?: '--:--' }}</span>
                <div class="schedule-info">
                    <h4>{{ $item['subject_name'] }}</h4>
                    <p>Phòng {{ $item['room'] }} &middot; {{ $item['teacher_name'] }} &middot;
                        {{ $item['start_time'] }}–{{ $item['end_time'] }}</p>
                </div>
            </div>
        @empty
            <div class="no-schedule" style="text-align: center; color: #64748b; padding: 2rem;">
                Hôm nay bạn không có lịch học.
            </div>
        @endforelse
    </section>

@endsection
