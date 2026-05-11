@extends('layouts.user.student_sidebar')

@section('title', 'Thời Khóa Biểu Sinh Viên')

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
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
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
        margin-bottom: 1.5rem;
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
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 0.68rem 1rem;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
    }

    .sheet-container {
        font-family: 'Inter', sans-serif;
        background: #fff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }
    .sheet-header {
        background: #f8fafc;
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .sheet-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    .sheet-table th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        padding: 12px;
        border: 1px solid #e2e8f0;
        text-align: center;
    }
    .sheet-table td {
        border: 1px solid #e2e8f0;
        height: 120px;
        vertical-align: top;
        padding: 8px;
        position: relative;
    }
    .time-col {
        background: #f8fafc;
        width: 100px;
        text-align: center !important;
        vertical-align: middle !important;
        font-weight: 700;
        color: #64748b;
    }
    .time-col small {
        font-weight: 400;
        display: block;
        margin-top: 4px;
        font-size: 0.75rem;
    }
    /* Card môn học */
    .course-card {
        background: #eff6ff;
        border-left: 4px solid #2563eb;
        padding: 10px;
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: 4px;
        transition: all 0.2s;
        border-radius: 4px;
    }
    .course-card:hover {
        background: #dbeafe;
        transform: scale(1.02);
        z-index: 10;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .course-name { font-size: 0.85rem; font-weight: 700; color: #1e40af; line-height: 1.2; }
    .course-code { font-size: 0.7rem; color: #3b82f6; font-weight: 600; }
    .course-info { font-size: 0.75rem; color: #64748b; display: flex; align-items: center; gap: 4px; }
    .course-info i { width: 14px; }
    .room-tag {
        margin-top: auto;
        display: inline-block;
        background: #2563eb;
        color: white;
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 2px;
        font-weight: 600;
        width: fit-content;
    }
    .lunch-break {
        background: #f1f5f9;
        text-align: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 2px;
        height: 30px !important;
    }

    @media (max-width: 1024px) {
        .toolbar {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .toolbar {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="content-wrapper">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Lịch học tập</li>
        </ol>
    </nav>

    <div class="schedule-page">
        <section class="page-hero" aria-label="Tổng quan lịch học tập">
            <div class="hero-content">
                <div class="hero-eyebrow">Student Academic Portal</div>
                <h1 id="page-title" class="hero-title">Lịch Học Tập Tuần</h1>
                <p class="hero-desc">
                    Theo dõi lịch học, môn học, thời gian và địa điểm phòng học nhanh chóng.
                </p>
            </div>
        </section>

        <section class="toolbar" aria-label="Bộ lọc lịch học">
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
                    <option>Chỉ lịch hôm nay</option>
                </select>
            </div>

            <button type="button" class="btn-filter">
                <i class="fa-solid fa-filter"></i>
                Lọc lịch
            </button>
        </section>

        <div class="sheet-container">
            <header class="sheet-header">
                <div>
                    <h2 style="margin:0; font-size:1.25rem; font-weight:800; color:#0f172a">THỜI KHÓA BIỂU HỌC TẬP</h2>
                    <p style="margin:0; font-size:0.85rem; color:#64748b">Tuần 36 · HK2 2025-2026</p>
                </div>
                <div style="display:flex; gap:10px">
                    <button style="background:#fff; border:1px solid #e2e8f0; padding:8px 16px; border-radius:4px; font-weight:600; cursor:pointer; color:#475569;">
                        <i class="fa-solid fa-file-excel"></i> Xuất Excel
                    </button>
                    <button style="background:#2563eb; border:none; color:#fff; padding:8px 16px; border-radius:4px; font-weight:600; cursor:pointer;">
                        <i class="fa-solid fa-print"></i> In lịch học
                    </button>
                </div>
            </header>

            <table class="sheet-table">
                <thead>
                    <tr>
                        <th style="width:110px">Thời gian</th>
                        <th>Thứ 2</th>
                        <th>Thứ 3</th>
                        <th>Thứ 4</th>
                        <th>Thứ 5</th>
                        <th>Thứ 6</th>
                        <th>Thứ 7</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="time-col">Tiết 1-3<br><small>07:00 - 09:25</small></td>
                        <td>
                            <div class="course-card">
                                <span class="course-code">#MH1024</span>
                                <div class="course-name">Phát triển ứng dụng Web</div>
                                <div class="course-info"><i class="fa-solid fa-user-tie"></i> Thầy Ngô Ngọc Anh</div>
                                <div class="room-tag">P.C301 - Nhà C</div>
                            </div>
                        </td>
                        <td></td>
                        <td>
                            <div class="course-card" style="border-left-color: #10b981; background: #ecfdf5;">
                                <span class="course-code" style="color:#059669">#MH2055</span>
                                <div class="course-name" style="color:#065f46">Cơ sở dữ liệu nâng cao</div>
                                <div class="course-info"><i class="fa-solid fa-user-tie"></i> Cô Trần Thị Thu Hà</div>
                                <div class="room-tag" style="background:#10b981">P.B202 - Nhà B</div>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="time-col">Tiết 4-6<br><small>09:35 - 12:00</small></td>
                        <td></td>
                        <td>
                            <div class="course-card" style="border-left-color: #f59e0b; background: #fffbeb;">
                                <span class="course-code" style="color:#d97706">#MH3011</span>
                                <div class="course-name" style="color:#92400e">Kiến trúc máy tính</div>
                                <div class="course-info"><i class="fa-solid fa-user-tie"></i> Thầy Lê Minh Đức</div>
                                <div class="room-tag" style="background:#f59e0b">P.A105 - Nhà A</div>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr><td colspan="7" class="lunch-break">NGHỈ TRƯA</td></tr>
                    <tr>
                        <td class="time-col">Tiết 7-9<br><small>12:30 - 14:55</small></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="time-col">Tiết 10-12<br><small>15:05 - 17:30</small></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
