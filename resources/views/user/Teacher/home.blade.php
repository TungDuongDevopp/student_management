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

        .welcome-banner {

            background: linear-gradient(135deg, #7f1d1d 0%, #dc2626 100%);
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
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 6px;
            padding: 1.25rem 1rem;
            border-left: 4px solid var(--primary, #dc2626);
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
            border-left-color: #0284c7;
        }

        .stat-card:nth-child(3) {
            border-left-color: #f59e0b;
        }

        .stat-card:nth-child(4) {
            border-left-color: #10b981;
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

        <section class="welcome-banner" aria-label="Lời chào">
            <h1>Xin chào Giảng viên, {{ Auth::user()->teacher?->name ?? (Auth::user()->username ?? 'Ngô Ngọc Anh') }}!</h1>
            <p>Hôm nay bạn có {{ $classes_today_count ?? 2 }} ca dạy. Chúc bạn một ngày làm việc hiệu quả tại khoa CNTT.</p>
        </section>

        <section class="stats-grid" aria-label="Chỉ số tổng quan">
            <article class="stat-card">
                <h2 class="stat-label">Lớp đang phụ trách</h2>
                <p class="stat-value">{{ $assigned_classes ?? '4' }}</p>
                <p class="stat-sub">Học kỳ hiện tại</p>
            </article>
            <article class="stat-card">
                <h2 class="stat-label">Tổng sinh viên</h2>
                <p class="stat-value">{{ $total_students ?? '245' }}</p>
                <p class="stat-sub">Đang theo học</p>
            </article>

        </section>

        <section class="news-section" aria-labelledby="news-heading">
            <div class="section-header">
                <h2 id="news-heading"><i class="fa-regular fa-newspaper" style="margin-right:8px; color:#dc2626;"></i>Tin
                    tức & Thông báo nội bộ</h2>
                <a href="#">Xem tất cả →</a>
            </div>
            <div class="news-list">
                <article class="news-item">
                    <img src="{{ asset('storage/images/news/Anhhopkhoa.jpg') }}" alt="Họp khoa" class="news-thumb"
                        loading="lazy">
                    <div class="news-body">
                        <div class="news-meta">
                            <span class="news-tag">Công tác</span>
                            <time datetime="2026-05-10"><i class="fa-regular fa-clock" style="margin-right:4px;"></i>
                                10/05/2026</time>
                        </div>
                        <h3><a href="#">Giấy mời họp giao ban khoa Công nghệ Thông tin tháng 5</a></h3>
                        <p class="news-desc">Kính mời toàn thể cán bộ, giảng viên khoa CNTT tham dự buổi họp giao ban định
                            kỳ để tổng kết công tác giảng dạy giữa kỳ.</p>
                    </div>
                </article>
                <article class="news-item">
                    <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=600&h=400&fit=crop"
                        alt="Nhập điểm" class="news-thumb" loading="lazy">
                    <div class="news-body">
                        <div class="news-meta">
                            <span class="news-tag">Giáo vụ</span>
                            <time datetime="2026-05-08"><i class="fa-regular fa-clock" style="margin-right:4px;"></i>
                                08/05/2026</time>
                        </div>
                        <h3><a href="#">Hạn chót nhập điểm giữa kỳ lên hệ thống Quản lý đào tạo</a></h3>
                        <p class="news-desc">Phòng Giáo vụ nhắc nhở các thầy cô hoàn thành việc chấm thi và nhập điểm thành
                            phần cho sinh viên trước 24h00 ngày 15/05.</p>
                    </div>
                </article>
                <article class="news-item">
                    <img src="{{ asset('storage/images/news/Anhnghiencuusv.jpg') }}" alt="Nghiên cứu khoa học"
                        class="news-thumb" loading="lazy">
                    <div class="news-body">
                        <div class="news-meta">
                            <span class="news-tag">NCKH</span>
                            <time datetime="2026-05-01"><i class="fa-regular fa-clock" style="margin-right:4px;"></i>
                                01/05/2026</time>
                        </div>
                        <h3><a href="#">Thông báo đăng ký đề tài Nghiên cứu khoa học cấp trường năm 2026</a></h3>
                        <p class="news-desc">Nhà trường mở cổng đăng ký đề tài NCKH cho cán bộ giảng viên. Các nhóm nghiên
                            cứu nộp thuyết minh đề cương theo biểu mẫu đính kèm.</p>
                    </div>
                </article>
            </div>
        </section>

        <section class="schedule-today" aria-labelledby="schedule-heading">
            <div class="section-header">
                <h2 id="schedule-heading"><i class="fa-solid fa-chalkboard-user"
                        style="margin-right:8px; color:#10b981;"></i>Lịch giảng dạy hôm nay</h2>
                <a href="{{ route('teacher.schedule') }}">Xem lịch giảng dạy →</a>
            </div>
            <div class="schedule-item">
                <span class="schedule-time">07:30</span>
                <div class="schedule-info">
                    <h4>Phát triển phần mềm hướng dịch vụ (SOA) - Lớp DCCTPM70A</h4>
                    <p>Phòng C1-305 · Sĩ số: 65 · Loại: Lý thuyết</p>
                </div>
            </div>
            <div class="schedule-item">
                <span class="schedule-time">13:30</span>
                <div class="schedule-info">
                    <h4>Đồ án Cơ sở Ngành - Nhóm 04</h4>
                    <p>Phòng LAB A2-202 · Sĩ số: 25 · Loại: Thực hành</p>
                </div>
            </div>
        </section>

    </main>
@endsection
