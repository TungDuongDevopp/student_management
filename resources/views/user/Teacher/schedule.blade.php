@extends('layouts.user.teacher_sidebar')

@section('title', 'Lịch Giảng Dạy')

@section('content')

<style>
    .schedule-page {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        color: #334155;
    }

    .breadcrumb-nav {
        margin-bottom: 1rem;
        background: #fff;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        display: inline-block;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        list-style: none;
        margin: 0;
        padding: 0;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .breadcrumb a {
        color: #3b82f6;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .breadcrumb a:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }
    
    .breadcrumb .separator {
        color: #94a3b8;
        font-size: 0.7rem;
    }
    
    .breadcrumb .active {
        color: #64748b;
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
        background: rgba(255,255,255,0.08);
        transform: rotate(45deg);
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-eyebrow {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        opacity: 0.85;
        font-weight: 700;
        margin-bottom: 0.4rem;
    }

    .hero-title {
        margin: 0;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .hero-desc {
        margin: 0.45rem 0 0;
        font-size: 0.9rem;
        opacity: 0.9;
        line-height: 1.5;
    }

    .hero-action {
        position: relative;
        z-index: 1;
        background: #fff;
        color: #b91c1c;
        border: none;
        border-radius: 6px;
        padding: 0.7rem 1rem;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        box-shadow: 0 8px 18px rgba(0,0,0,0.12);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .summary-card {
        background: #fff;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .summary-label {
        margin: 0 0 0.4rem;
        font-size: 0.72rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.04em;
    }

    .summary-value {
        margin: 0;
        font-size: 1.45rem;
        font-weight: 800;
        color: #0f172a;
    }

    .summary-sub {
        margin: 0.25rem 0 0;
        font-size: 0.78rem;
        color: #94a3b8;
    }

    .toolbar {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.35rem;
    }

    .form-control {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.65rem 0.75rem;
        font-size: 0.85rem;
        color: #334155;
        background: #fff;
    }

    .btn-filter {
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 0.68rem 1rem;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
    }

    .schedule-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.05);
    }

    .schedule-header {
        padding: 1.15rem 1.25rem;
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .schedule-header h2 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
    }

    .schedule-header p {
        margin: 0.25rem 0 0;
        font-size: 0.82rem;
        color: #64748b;
    }

    .week-badge {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        border-radius: 999px;
        padding: 0.4rem 0.75rem;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .schedule-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .schedule-table th {
        background: #fef2f2;
        color: #991b1b;
        font-weight: 800;
        font-size: 0.78rem;
        text-transform: uppercase;
        padding: 0.85rem;
        border: 1px solid #fecaca;
        text-align: center;
    }

    .schedule-table td {
        border: 1px solid #f1f5f9;
        height: 172px;
        vertical-align: top;
        padding: 0.75rem;
        background: #fff;
    }

    .time-col {
        background: #fff7ed !important;
        width: 105px;
        text-align: center;
        vertical-align: middle !important;
        font-weight: 800;
        color: #9a3412;
        border: 1px solid #fed7aa !important;
    }

    .time-col small {
        display: block;
        margin-top: 0.2rem;
        font-size: 0.7rem;
        color: #c2410c;
        font-weight: 600;
    }

    .teacher-card {
        height: 100%;
        background: #ffffff;
        border: 1px solid #fecaca;
        border-left: 4px solid #dc2626;
        border-radius: 8px;
        padding: 0.85rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.08);
        transition: 0.2s ease;
    }

    .teacher-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(220, 38, 38, 0.12);
    }

    .class-code {
        font-size: 0.68rem;
        color: #dc2626;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .subject-title {
        font-size: 0.92rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.35;
    }

    .meta-info {
        font-size: 0.76rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        line-height: 1.4;
    }

    .status-row {
        margin-top: 0.25rem;
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.22rem 0.55rem;
        font-size: 0.68rem;
        font-weight: 700;
    }

    .status-normal {
        background: #dcfce7;
        color: #15803d;
    }

    .status-warning {
        background: #fef3c7;
        color: #b45309;
    }

    .action-btns {
        margin-top: auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.45rem;
    }

    .btn-action {
        font-size: 0.72rem;
        padding: 0.45rem;
        border-radius: 5px;
        border: none;
        font-weight: 800;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
    }

    .btn-view {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-score {
        background: #dc2626;
        color: #fff;
    }

    .empty-slot {
        height: 100%;
        border: 1px dashed #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        color: #94a3b8;
        font-size: 0.78rem;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .note-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        color: #475569;
        font-size: 0.85rem;
        line-height: 1.6;
    }

    .note-box strong {
        color: #0f172a;
    }

    @media (max-width: 1024px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .toolbar {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .page-hero {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.4rem;
        }

        .summary-grid,
        .toolbar {
            grid-template-columns: 1fr;
        }

        .hero-title {
            font-size: 1.25rem;
        }
    }
</style>

{{-- 
    NOTE LOGIC:
    Hiện tại giao diện đang dùng dữ liệu tĩnh để dựng UI trước.
    Sau này khi kết nối database, nên truyền các biến:
    $teacher
    $currentSemester
    $currentWeek
    $timeSlots
    $weekDays
    $schedules

    Cấu trúc render động đề xuất:
    @foreach($timeSlots as $slot)
        @foreach($weekDays as $day)
            @foreach($schedules[$day][$slot['key']] ?? [] as $schedule)
                Render teacher-card
            @endforeach
        @endforeach
    @endforeach
--}}

<div class="content-wrapper">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ route('teacher.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Lịch giảng dạy</li>
        </ol>
    </nav>

<main class="schedule-page" aria-labelledby="page-title">

    <section class="page-hero" aria-label="Tổng quan lịch giảng dạy">
        <div class="hero-content">
            <div class="hero-eyebrow">Teacher Academic Portal</div>
            <h1 id="page-title" class="hero-title">Lịch Giảng Dạy Tuần</h1>
            <p class="hero-desc">
                Theo dõi kế hoạch giảng dạy, lớp học phần, phòng học và thao tác nhanh với danh sách sinh viên, điểm số.
            </p>
        </div>

        <button type="button" class="hero-action">
            <i class="fa-solid fa-calendar-plus"></i>
            Đăng ký dạy bù
        </button>
    </section>

    <section class="summary-grid" aria-label="Thống kê nhanh">
        <article class="summary-card">
            <p class="summary-label">Giảng viên</p>
            <p class="summary-value">Ngô Ngọc Anh</p>
            <p class="summary-sub">Khoa Công nghệ thông tin</p>
        </article>

        <article class="summary-card">
            <p class="summary-label">Số lớp tuần này</p>
            <p class="summary-value">04</p>
            <p class="summary-sub">Bao gồm lớp chính khóa và thực hành</p>
        </article>

        <article class="summary-card">
            <p class="summary-label">Số sinh viên</p>
            <p class="summary-value">246</p>
            <p class="summary-sub">Tổng sĩ số các lớp phụ trách</p>
        </article>

        <article class="summary-card">
            <p class="summary-label">Bảng điểm</p>
            <p class="summary-value">02</p>
            <p class="summary-sub">Bảng điểm chưa chốt</p>
        </article>
    </section>

    <section class="toolbar" aria-label="Bộ lọc lịch giảng dạy">
        <div class="form-group">
            <label for="semester">Học kỳ</label>
            <select id="semester" class="form-control">
                <option>Học kỳ 2 - Năm học 2025-2026</option>
                <option>Học kỳ 1 - Năm học 2025-2026</option>
                <option>Học kỳ hè - Năm học 2025-2026</option>
            </select>
        </div>

        <div class="form-group">
            <label for="week">Tuần học</label>
            <select id="week" class="form-control">
                <option>Tuần 36 - 06/05/2026 đến 11/05/2026</option>
                <option>Tuần 37 - 13/05/2026 đến 18/05/2026</option>
                <option>Tuần 38 - 20/05/2026 đến 25/05/2026</option>
            </select>
        </div>

        <div class="form-group">
            <label for="view-mode">Chế độ xem</label>
            <select id="view-mode" class="form-control">
                <option>Xem theo tuần</option>
                <option>Xem theo tháng</option>
                <option>Chỉ lớp hôm nay</option>
            </select>
        </div>

        <button type="button" class="btn-filter">
            <i class="fa-solid fa-filter"></i>
            Lọc lịch
        </button>
    </section>

    <section class="schedule-card" aria-labelledby="schedule-title">
        <header class="schedule-header">
            <div>
                <h2 id="schedule-title">Kế hoạch giảng dạy chi tiết</h2>
                <p>Lịch hiển thị theo ca học, thứ trong tuần và lớp học phần được phân công.</p>
            </div>

            <span class="week-badge">
                Tuần hiện tại · HK2 2025-2026
            </span>
        </header>

        <div class="table-responsive">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th scope="col">Ca / Tiết</th>
                        <th scope="col">Thứ 2</th>
                        <th scope="col">Thứ 3</th>
                        <th scope="col">Thứ 4</th>
                        <th scope="col">Thứ 5</th>
                        <th scope="col">Thứ 6</th>
                        <th scope="col">Thứ 7</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="time-col">
                            Tiết 1-3
                            <small>07:00 - 09:15</small>
                        </td>

                        <td>
                            <article class="teacher-card" aria-label="Lớp Lập trình Web nâng cao">
                                <span class="class-code">DCCTPM70A</span>
                                <div class="subject-title">Lập trình Web nâng cao</div>

                                <div class="meta-info">
                                    <i class="fa-solid fa-users"></i>
                                    Sĩ số: 65 sinh viên
                                </div>

                                <div class="meta-info">
                                    <i class="fa-solid fa-location-dot"></i>
                                    Phòng: C301
                                </div>

                                <div class="meta-info">
                                    <i class="fa-regular fa-clock"></i>
                                    07:00 - 09:15
                                </div>

                                <div class="status-row">
                                    <span class="status-badge status-normal">Đúng lịch</span>
                                    <span class="status-badge status-warning">Chưa chốt điểm</span>
                                </div>

                                <div class="action-btns">
                                    <a href="#" class="btn-action btn-view">Danh sách</a>
                                    <a href="#" class="btn-action btn-score">Vào điểm</a>
                                </div>
                            </article>
                        </td>

                        <td>
                            <div class="empty-slot">Không có lịch</div>
                        </td>

                        <td>
                            <article class="teacher-card" aria-label="Lớp Phân tích hệ thống">
                                <span class="class-code">DCCTPM70B</span>
                                <div class="subject-title">Phân tích & thiết kế hệ thống</div>

                                <div class="meta-info">
                                    <i class="fa-solid fa-users"></i>
                                    Sĩ số: 58 sinh viên
                                </div>

                                <div class="meta-info">
                                    <i class="fa-solid fa-location-dot"></i>
                                    Phòng: B202
                                </div>

                                <div class="meta-info">
                                    <i class="fa-regular fa-clock"></i>
                                    07:00 - 09:15
                                </div>

                                <div class="status-row">
                                    <span class="status-badge status-normal">Đúng lịch</span>
                                </div>

                                <div class="action-btns">
                                    <a href="#" class="btn-action btn-view">Danh sách</a>
                                    <a href="#" class="btn-action btn-score">Vào điểm</a>
                                </div>
                            </article>
                        </td>

                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                    </tr>

                    <tr>
                        <td class="time-col">
                            Tiết 4-6
                            <small>09:30 - 11:45</small>
                        </td>

                        <td><div class="empty-slot">Không có lịch</div></td>

                        <td>
                            <article class="teacher-card" aria-label="Lớp Cơ sở dữ liệu">
                                <span class="class-code">DCCNTT69C</span>
                                <div class="subject-title">Cơ sở dữ liệu</div>

                                <div class="meta-info">
                                    <i class="fa-solid fa-users"></i>
                                    Sĩ số: 62 sinh viên
                                </div>

                                <div class="meta-info">
                                    <i class="fa-solid fa-location-dot"></i>
                                    Phòng: A402
                                </div>

                                <div class="meta-info">
                                    <i class="fa-regular fa-clock"></i>
                                    09:30 - 11:45
                                </div>

                                <div class="status-row">
                                    <span class="status-badge status-normal">Đúng lịch</span>
                                </div>

                                <div class="action-btns">
                                    <a href="#" class="btn-action btn-view">Danh sách</a>
                                    <a href="#" class="btn-action btn-score">Vào điểm</a>
                                </div>
                            </article>
                        </td>

                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                    </tr>

                    <tr>
                        <td class="time-col">
                            Tiết 7-9
                            <small>13:00 - 15:15</small>
                        </td>

                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>

                        <td>
                            <article class="teacher-card" aria-label="Lớp Lập trình PHP Laravel">
                                <span class="class-code">DCCTPM71A</span>
                                <div class="subject-title">Lập trình PHP Laravel</div>

                                <div class="meta-info">
                                    <i class="fa-solid fa-users"></i>
                                    Sĩ số: 61 sinh viên
                                </div>

                                <div class="meta-info">
                                    <i class="fa-solid fa-location-dot"></i>
                                    Phòng: LAB-03
                                </div>

                                <div class="meta-info">
                                    <i class="fa-regular fa-clock"></i>
                                    13:00 - 15:15
                                </div>

                                <div class="status-row">
                                    <span class="status-badge status-normal">Thực hành</span>
                                    <span class="status-badge status-warning">Cần điểm danh</span>
                                </div>

                                <div class="action-btns">
                                    <a href="#" class="btn-action btn-view">Danh sách</a>
                                    <a href="#" class="btn-action btn-score">Vào điểm</a>
                                </div>
                            </article>
                        </td>

                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                    </tr>

                    <tr>
                        <td class="time-col">
                            Tiết 10-12
                            <small>15:30 - 17:45</small>
                        </td>

                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                        <td><div class="empty-slot">Không có lịch</div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <aside class="note-box" aria-label="Ghi chú logic phát triển">
        <strong>Ghi chú logic:</strong>
        Giao diện hiện đang dùng dữ liệu mẫu để kiểm tra UI. Khi kết nối database, phần lịch nên được render bằng vòng lặp theo
        <strong>ca học</strong> và <strong>thứ trong tuần</strong>. Mỗi ô lịch kiểm tra xem có lớp học phần hay không; nếu có thì render card,
        nếu không thì hiển thị trạng thái “Không có lịch”. Các nút “Danh sách” và “Vào điểm” sau này cần đổi từ
        <code>href="#"</code> sang route thật.
    </aside>

</main>
</div>

@endsection