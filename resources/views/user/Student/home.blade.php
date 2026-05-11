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
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
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
            gap: 1rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 6px;
            padding: 1.25rem 1rem;
            border-left: 4px solid var(--primary, #2563eb);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .stat-card .stat-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.03em;
            margin-bottom: 0.4rem;
            margin-top: 0;
        }

        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .stat-card .stat-sub {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 400;
            margin-top: 0.15rem;
            margin-bottom: 0;
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

        <section class="welcome-banner" aria-label="Lời chào">
            <h1>Xin chào, {{ Auth::user()->student->name ?? (Auth::user()->username ?? 'Sinh viên') }}!</h1>
            <p>Chào mừng bạn quay trở lại hệ thống. Hãy kiểm tra tiến độ học tập và các thông báo mới nhất từ nhà trường.
            </p>
        </section>

        <section class="stats-grid" aria-label="Chỉ số tổng quan">
            <article class="stat-card">
                <h2 class="stat-label">Điểm trung bình (GPA)</h2>
                <p class="stat-value">{{ $gpa ?? '3.45' }}</p>
                <p class="stat-sub">Xếp loại: {{ $ranking ?? 'Giỏi' }}</p>
            </article>
            <article class="stat-card">
                <h2 class="stat-label">Tín chỉ tích lũy</h2>
                <p class="stat-value">{{ $earned_credits ?? '95' }} <span
                        style="font-size:0.85rem; font-weight:400; color:#94a3b8">/ 130</span></p>
                <p class="stat-sub">Còn thiếu {{ 130 - ($earned_credits ?? 95) }} tín chỉ</p>
            </article>
            <article class="stat-card">
                <h2 class="stat-label">Môn đang học</h2>
                <p class="stat-value">{{ $current_subjects_count ?? '6' }}</p>
                <p class="stat-sub">Kỳ học hiện tại</p>
            </article>
            <article class="stat-card">
                <h2 class="stat-label">Số tiết vắng</h2>
                <p class="stat-value">{{ $absences_count ?? '2' }}</p>
                <p class="stat-sub">Tối đa cho phép: 15</p>
            </article>
        </section>


        <section class="news-section" aria-labelledby="news-heading">
            <div class="section-header">
                <h2 id="news-heading"><i class="fa-regular fa-newspaper" style="margin-right:8px; color:#2563eb;"></i>Tin
                    tức & Thông báo</h2>
                <a href="#">Xem tất cả →</a>
            </div>

            <div class="news-list">
                <article class="news-item">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c476?w=600&h=400&fit=crop"
                        alt="Thông báo lịch thi" class="news-thumb" loading="lazy">
                    <div class="news-body">
                        <div class="news-meta">
                            <span class="news-tag">Đào tạo</span>
                            <time datetime="2026-05-07"><i class="fa-regular fa-clock" style="margin-right:4px;"></i>
                                07/05/2026</time>
                        </div>
                        <h3><a href="#">Thông báo lịch thi kết thúc học phần – Kỳ 2 năm học 2025–2026</a></h3>
                        <p class="news-desc">Phòng Đào tạo thông báo lịch thi dự kiến cho học kỳ 2. Sinh viên vui lòng kiểm
                            tra và phản hồi nếu có trùng lịch trước ngày 15/05.</p>
                    </div>
                </article>

                <article class="news-item">
                    <img src="https://images.unsplash.com/photo-1562774053-701939374585?w=600&h=400&fit=crop"
                        alt="Danh sách học bổng" class="news-thumb" loading="lazy">
                    <div class="news-body">
                        <div class="news-meta">
                            <span class="news-tag">Học bổng</span>
                            <time datetime="2026-05-05"><i class="fa-regular fa-clock" style="margin-right:4px;"></i>
                                05/05/2026</time>
                        </div>
                        <h3><a href="#">Công bố danh sách sinh viên nhận học bổng khuyến khích học tập HK1 năm học
                                2025-2026</a></h3>
                        <p class="news-desc">Chúc mừng 120 sinh viên khoa CNTT đã đạt thành tích xuất sắc. Chi tiết mức
                            hưởng và thời gian nhận tiền vui lòng xem trong file đính kèm.</p>
                    </div>
                </article>

                <article class="news-item">
                    <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=600&h=400&fit=crop"
                        alt="Hướng dẫn đăng ký" class="news-thumb" loading="lazy">
                    <div class="news-body">
                        <div class="news-meta">
                            <span class="news-tag">Đào tạo</span>
                            <time datetime="2026-05-01"><i class="fa-regular fa-clock" style="margin-right:4px;"></i>
                                01/05/2026</time>
                        </div>
                        <h3><a href="#">Hướng dẫn chi tiết quy trình đăng ký môn học qua hệ thống trực tuyến – Kỳ Hè
                                2026</a></h3>
                        <p class="news-desc">Cổng đăng ký tín chỉ kỳ Hè sẽ chính thức mở vào lúc 08:00 sáng ngày 10/05. Các
                            bạn sinh viên chuẩn bị sẵn danh sách mã môn.</p>
                    </div>
                </article>
                {{-- @endforeach --}}
            </div>
        </section>

        <section class="schedule-today" aria-labelledby="schedule-heading">
            <div class="section-header">
                <h2 id="schedule-heading"><i class="fa-regular fa-clock" style="margin-right:8px; color:#10b981;"></i>Lịch
                    học hôm nay</h2>
                <a href="{{ route('student.schedule') }}">Xem chi tiết →</a>
            </div>
            <div class="schedule-item">
                <span class="schedule-time">07:30</span>
                <div class="schedule-info">
                    <h4>Lập trình Web</h4>
                    <p>Phòng A3-302 · Thầy Nguyễn Anh Tuấn</p>
                </div>
            </div>
            <div class="schedule-item">
                <span class="schedule-time">09:30</span>
                <div class="schedule-info">
                    <h4>Cơ sở dữ liệu</h4>
                    <p>Phòng B2-201 · Cô Trần Thị Thu Hà</p>
                </div>
            </div>
        </section>

    </main>
@endsection
