@extends('layouts.admin.sidebar')
@section('title', 'Dashboard')
@section('content')

@php
    $cntStudents = \App\Models\Student::count();
    $cntTeachers = \App\Models\Teacher::count();
    $cntFaculties = \App\Models\Faculty::count();
    $cntSubjects = \App\Models\Subject::count();
    $cntFeedbacks = \App\Models\Feedback::where('status', 0)->count();
    $cntSchedules = \App\Models\Schedule::count();
    
    $tuitionsList = \App\Models\Tuition::all(['total_amount', 'paid_amount']);
    $debtCount = 0;
    $totalRevenue = 0;
    foreach($tuitionsList as $t) {
        $total = floatval($t->total_amount);
        $paid = floatval($t->paid_amount);
        if ($total > 0 && $paid < $total) $debtCount++;
        $totalRevenue += $paid;
    }
    
    $formattedRevenue = '0';
    if ($totalRevenue >= 1000000000) {
        $formattedRevenue = number_format($totalRevenue / 1000000000, 1) . ' Tỷ';
    } elseif ($totalRevenue >= 1000000) {
        $formattedRevenue = number_format($totalRevenue / 1000000, 0) . ' Tr';
    } elseif ($totalRevenue > 0) {
        $formattedRevenue = number_format($totalRevenue) . 'đ';
    }

    $activeSemester = \App\Models\Semester::where('status', 1)->first() ?? \App\Models\Semester::latest()->first();
    $semName = $activeSemester ? $activeSemester->semester_name : 'Chưa có dữ liệu';
    $semYear = $activeSemester ? 'Năm học: ' . $activeSemester->school_year : '';
    
    $semSchedules = $activeSemester ? \App\Models\Schedule::where('semester_id', $activeSemester->id)->count() : 0;
    
    $semEnrollments = 0;
    if ($activeSemester) {
        $scheduleIds = \App\Models\Schedule::where('semester_id', $activeSemester->id)->pluck('id');
        $semEnrollments = \App\Models\Enrollment::whereIn('schedule_id', $scheduleIds)->count();
    }

    $semDebt = 0;
    if ($activeSemester) {
        $semDebt = \App\Models\Tuition::where('semester_id', $activeSemester->id)
                    ->whereColumn('paid_amount', '<', 'total_amount')
                    ->where('total_amount', '>', 0)
                    ->count();
    }
@endphp

    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <style>
        /* ── Core Layout ── */
        .content-wrapper {
            padding: 1.5rem;
            background: #f4f6f9;
            min-height: 100vh;
            color: #333;
            font-family: 'Inter', sans-serif;
        }

        [data-theme="dark"] .content-wrapper {
            background: var(--bg-body, #0f172a);
            color: var(--text-main, #f1f5f9);
        }

        /* ── Admin Welcome Banner ── */
        .admin-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 6px;
            padding: 1.25rem 1.5rem;
            color: #fff;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        [data-theme="dark"] .admin-banner {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border: 1px solid #4a5568;
        }
        
        .ab-subtitle {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 0.2rem;
            text-transform: uppercase;
        }
        
        .ab-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            color: #f8fafc;
        }
        
        .ab-desc {
            font-size: 0.95rem;
            color: #cbd5e1;
            max-width: 600px;
        }
        
        .ab-decor {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 350px;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.15) 50%, rgba(59, 130, 246, 0.3) 100%);
            clip-path: polygon(20% 0, 100% 0, 100% 100%, 0% 100%);
        }

        /* ── Semester highlight ── */
        .sem-banner {
            background: #fff;
            border-left: 4px solid #17a2b8;
            border-radius: 4px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        [data-theme="dark"] .sem-banner {
            background: #1e293b;
            border-left-color: #0dcaf0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }

        .sem-info { display: flex; flex-direction: column; gap: 0.2rem; }

        .sem-label {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 500;
        }

        .sem-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #17a2b8;
        }

        .sem-stats-group {
            display: flex;
            gap: 3rem;
        }

        .sem-stat {
            text-align: center;
            display: flex;
            flex-direction: column;
        }

        .sem-stat-val {
            font-size: 1.4rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            color: #212529;
        }
        [data-theme="dark"] .sem-stat-val { color: #fff; }

        .sem-stat-lbl {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 500;
        }

        /* ── Stats cards (Perfect 4-column grid) ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } }

        .stat-card {
            border-radius: 4px;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff !important;
            position: relative;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .stat-card:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .stat-info {
            display: flex;
            flex-direction: column;
            z-index: 2;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            line-height: 1.2;
            margin-bottom: 0.2rem;
            color: #fff !important;
        }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: rgba(255,255,255,0.9) !important;
        }

        .stat-icon {
            font-size: 2.5rem;
            color: rgba(0,0,0,0.15);
            z-index: 1;
        }
        [data-theme="dark"] .stat-icon { color: rgba(255,255,255,0.15); }

        /* Solid Colors Override (Important to beat Dark Mode) */
        .sc-blue   { background: #007bff !important; }
        .sc-purple { background: #6f42c1 !important; }
        .sc-cyan   { background: #17a2b8 !important; }
        .sc-green  { background: #28a745 !important; }
        .sc-orange { background: #fd7e14 !important; }
        .sc-red    { background: #dc3545 !important; }
        .sc-gray   { background: #6c757d !important; }
        .sc-teal   { background: #20c997 !important; }

        /* ── Quick actions (Perfect 6-column grid) ── */
        .qa-section {
            background: #fff;
            border-top: 3px solid #007bff;
            border-radius: 4px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        [data-theme="dark"] .qa-section { background: #1e293b; border-top-color: #0d6efd; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }

        .qa-title {
            font-size: 1.05rem;
            font-weight: 500;
            color: #212529;
            margin-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 0.8rem;
        }
        [data-theme="dark"] .qa-title { color: #fff; border-bottom-color: #334155; }

        .qa-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1.25rem;
        }
        @media (max-width: 1200px) { .qa-grid { grid-template-columns: repeat(4, 1fr); } }
        @media (max-width: 768px) { .qa-grid { grid-template-columns: repeat(2, 1fr); } }

        .qa-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 1.2rem 1rem;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-decoration: none;
            color: #495057;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        [data-theme="dark"] .qa-link { background: #0f172a; border-color: #334155; color: #cbd5e1; }

        .qa-link:hover {
            background: #e9ecef;
            color: #212529;
            transform: translateY(-2px);
            border-color: #007bff;
        }
        [data-theme="dark"] .qa-link:hover { background: #334155; color: #fff; border-color: #0d6efd; }

        .qa-link span {
            font-size: 1.5rem;
            color: #6c757d;
        }
        [data-theme="dark"] .qa-link span { color: #94a3b8; }

        /* ── Professional Charts Grid ── */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 992px) { .charts-grid { grid-template-columns: 1fr; } }

        .chart-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        [data-theme="dark"] .chart-card { background: #1e293b; border-color: #334155; }
        
        .chart-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        [data-theme="dark"] .chart-header { background: #0f172a; border-bottom-color: #334155; }

        .chart-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .ci-purple { background: linear-gradient(135deg, #a855f7, #7e22ce); }
        .ci-green { background: linear-gradient(135deg, #22c55e, #15803d); }
        .ci-orange { background: linear-gradient(135deg, #f97316, #c2410c); }
        .ci-cyan { background: linear-gradient(135deg, #06b6d4, #0369a1); }

        .chart-title-wrap {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chart-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.15rem;
        }
        [data-theme="dark"] .chart-title { color: #f1f5f9; }

        .chart-subtitle {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 400;
        }

        .chart-wrap {
            position: relative;
            height: 280px;
            width: 100%;
            flex-grow: 1;
            padding: 1.5rem;
        }
        .chart-wrap.tall { height: 320px; }

        /* ── Recent feedbacks table ── */
        .recent-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            overflow: hidden;
        }
        [data-theme="dark"] .recent-card { background: #1e293b; border-color: #334155; }

        .recent-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        [data-theme="dark"] .recent-header { background: #0f172a; border-bottom-color: #334155; }

        .recent-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        [data-theme="dark"] .recent-title { color: #f1f5f9; }

        .recent-link {
            font-size: 0.85rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        .recent-link:hover { text-decoration: underline; }

        .fb-list {
            display: flex;
            flex-direction: column;
            padding: 0.5rem 1.5rem;
        }

        .fb-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        [data-theme="dark"] .fb-item { border-bottom-color: #334155; }
        .fb-item:last-child { border-bottom: none; }

        .fb-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 0.4rem;
            flex-shrink: 0;
        }
        .fb-dot.unread { background: #f59e0b; }
        .fb-dot.read { background: #22c55e; }

        .fb-body { flex: 1; min-width: 0; }

        .fb-sender {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
            margin-bottom: 0.1rem;
        }
        [data-theme="dark"] .fb-sender { color: #f1f5f9; }

        .fb-content {
            font-size: 0.85rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fb-time {
            font-size: 0.8rem;
            color: #94a3b8;
            font-weight: 400;
            white-space: nowrap;
        }
    </style>

    <div class="content-wrapper">
        
        {{-- Admin Banner --}}
        <div class="admin-banner">
            <div class="ab-content">
                <div class="ab-subtitle">ADMINISTRATION PORTAL</div>
                <div class="ab-title">Tổng quan Hệ thống</div>
                <div class="ab-desc" id="bannerDesc">Đang tải...</div>
            </div>
            <div class="ab-decor"></div>
        </div>

        {{-- Active semester banner --}}
        <div class="sem-banner" id="semBanner">
            <div class="sem-info">
                <div class="sem-label">Học kỳ đang hoạt động</div>
                <div class="sem-name" id="semName">{{ $semName }}</div>
                <div class="sem-label" id="semYear" style="color: #17a2b8;">{{ $semYear }}</div>
            </div>
            <div class="sem-stats-group">
                <div class="sem-stat">
                    <div class="sem-stat-val" id="semSchedules">{{ number_format($semSchedules) }}</div>
                    <div class="sem-stat-lbl">Lớp học phần</div>
                </div>
                <div class="sem-stat">
                    <div class="sem-stat-val" id="semEnrollments">{{ number_format($semEnrollments) }}</div>
                    <div class="sem-stat-lbl">Lượt đăng ký</div>
                </div>
                <div class="sem-stat">
                    <div class="sem-stat-val" id="semDebt">{{ number_format($semDebt) }}</div>
                    <div class="sem-stat-lbl">SV nợ học phí</div>
                </div>
            </div>
        </div>

        {{-- Stats cards (8 Cards for perfect 4x2 grid) --}}
        <div class="stats-grid">
            <a href="{{ route('admin.students') }}" class="stat-card sc-blue">
                <div class="stat-info">
                    <div class="stat-value" id="cntStudents">{{ $cntStudents }}</div>
                    <div class="stat-label">Tổng Sinh viên</div>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-user-graduate"></i></div>
            </a>
            <a href="{{ route('admin.teachers') }}" class="stat-card sc-purple">
                <div class="stat-info">
                    <div class="stat-value" id="cntTeachers">{{ $cntTeachers }}</div>
                    <div class="stat-label">Tổng Giảng viên</div>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
            </a>
            <a href="{{ route('admin.faculties') }}" class="stat-card sc-cyan">
                <div class="stat-info">
                    <div class="stat-value" id="cntFaculties">{{ $cntFaculties }}</div>
                    <div class="stat-label">Tổng Khoa</div>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-building-columns"></i></div>
            </a>
            <a href="{{ route('admin.subjects') }}" class="stat-card sc-teal">
                <div class="stat-info">
                    <div class="stat-value" id="cntSubjects">{{ $cntSubjects }}</div>
                    <div class="stat-label">Tổng Môn học</div>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-book"></i></div>
            </a>
            <a href="{{ route('admin.schedules') }}" class="stat-card sc-green">
                <div class="stat-info">
                    <div class="stat-value" id="cntSchedules">{{ $cntSchedules }}</div>
                    <div class="stat-label">Lớp học phần</div>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-calendar-alt"></i></div>
            </a>
            <a href="{{ route('admin.fees') }}" class="stat-card sc-orange">
                <div class="stat-info">
                    <div class="stat-value" id="cntRevenue">{{ $formattedRevenue }}</div>
                    <div class="stat-label">Doanh thu</div>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
            </a>
            <a href="{{ route('admin.feedbacks') }}" class="stat-card sc-gray">
                <div class="stat-info">
                    <div class="stat-value" id="cntFeedbacks">{{ $cntFeedbacks }}</div>
                    <div class="stat-label">Phản hồi mới</div>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-comments"></i></div>
            </a>
            <a href="{{ route('admin.fees') }}" class="stat-card sc-red">
                <div class="stat-info">
                    <div class="stat-value" id="cntDebt">{{ $debtCount }}</div>
                    <div class="stat-label">Nợ học phí</div>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            </a>
        </div>

        {{-- Quick actions (12 items for perfect 6x2 grid) --}}
        <div class="qa-section">
            <div class="qa-title"><i class="fa-solid fa-bolt" style="color: #007bff; margin-right: 5px;"></i> Truy cập nhanh</div>
            <div class="qa-grid">
                @php
                $quickLinks = [
                    ['url' => route('admin.students'),    'icon' => '🎓',  'label' => 'Sinh viên'],
                    ['url' => route('admin.teachers'),    'icon' => '👨‍🏫', 'label' => 'Giảng viên'],
                    ['url' => route('admin.classes'),     'icon' => '🏫',  'label' => 'Lớp học'],
                    ['url' => route('admin.schedules'),   'icon' => '📅',  'label' => 'Lịch học'],
                    ['url' => route('admin.enrollments'), 'icon' => '📋',  'label' => 'Đăng ký'],
                    ['url' => route('admin.fees'),        'icon' => '💰',  'label' => 'Học phí'],
                    ['url' => route('admin.subjects'),    'icon' => '📚',  'label' => 'Môn học'],
                    ['url' => route('admin.rooms'),       'icon' => '🚪',  'label' => 'Phòng học'],
                    ['url' => route('admin.accounts'),    'icon' => '👤',  'label' => 'Tài khoản'],
                    ['url' => route('admin.feedbacks'),   'icon' => '💬',  'label' => 'Phản hồi'],
                    ['url' => route('admin.semesters'),   'icon' => '🗓️', 'label' => 'Học kỳ'],
                    ['url' => route('admin.news'),        'icon' => '📰', 'label' => 'Bài viết'],
                ];
                @endphp
                @foreach($quickLinks as $ql)
                <a href="{{ $ql['url'] }}" class="qa-link">
                    <span>{{ $ql['icon'] }}</span>
                    {{ $ql['label'] }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Professional Charts row 1 --}}
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-icon ci-purple"><i class="fa-solid fa-chart-pie"></i></div>
                    <div class="chart-title-wrap">
                        <div class="chart-title">Học phí theo học kỳ</div>
                        <span class="chart-subtitle">Đã thu vs Còn nợ (triệu đồng)</span>
                    </div>
                </div>
                <div class="chart-wrap tall"><canvas id="chartTuition"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-icon ci-cyan"><i class="fa-solid fa-chart-bar"></i></div>
                    <div class="chart-title-wrap">
                        <div class="chart-title">Phân bổ Sinh viên</div>
                        <span class="chart-subtitle">Số lượng sinh viên theo từng Khoa</span>
                    </div>
                </div>
                <div class="chart-wrap tall"><canvas id="chartFaculty"></canvas></div>
            </div>
        </div>

        {{-- Professional Charts row 2 --}}
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-icon ci-orange"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="chart-title-wrap">
                        <div class="chart-title">Top Đăng ký Học phần</div>
                        <span class="chart-subtitle">Các lớp học phần có lượng đăng ký cao nhất</span>
                    </div>
                </div>
                <div class="chart-wrap"><canvas id="chartEnroll"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-icon ci-green"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="chart-title-wrap">
                        <div class="chart-title">Tình trạng Học phí</div>
                        <span class="chart-subtitle">Tỷ lệ hoàn thành học phí toàn hệ thống</span>
                    </div>
                </div>
                <div class="chart-wrap" style="display:flex;align-items:center;justify-content:center">
                    <canvas id="chartDebtDonut" style="max-height:240px;max-width:300px"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent feedbacks --}}
        <div class="recent-card">
            <div class="recent-header">
                <div class="recent-title"><i class="fa-solid fa-bell" style="color: #f59e0b; margin-right: 8px;"></i> Phản hồi mới nhất</div>
                <a href="{{ route('admin.feedbacks') }}" class="recent-link">Xem tất cả <i class="fa-solid fa-arrow-right" style="font-size: 0.8rem; margin-left: 0.2rem;"></i></a>
            </div>
            <div class="fb-list" id="fbList">
                <div style="color: #64748b; font-size: 0.9rem; padding: 1rem;">Đang tải dữ liệu...</div>
            </div>
        </div>
    </div>

<script>
        // ── Chart.js global defaults ──────────────────────────────────────────────
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = '#334155';
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.font.size = 12;
        Chart.defaults.plugins.legend.labels.boxWidth = 12;

        const ACCENT = '#3b82f6';
        const PALETTE = ['#3b82f6', '#8b5cf6', '#22c55e', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899', '#f97316', '#a3e635',
            '#e879f9'
        ];

        let charts = {};

        // ── BOOT ─────────────────────────────────────────────────────────────────
        async function boot() {
            document.getElementById('bannerDesc').textContent = 'Cập nhật lần cuối: ' + new Date().toLocaleTimeString('vi-VN');

            try {
                const [students, teachers, faculties, subjects, feedbacks,
                    tuitions, schedules, enrollments, semesters
                ] = await Promise.all([
                    fetch('/api/students').then(r => r.json()),
                    fetch('/api/teachers').then(r => r.json()),
                    fetch('/api/faculties').then(r => r.json()),
                    fetch('/api/subjects').then(r => r.json()),
                    fetch('/api/feedbacks').then(r => r.json()),
                    fetch('/api/tuitions').then(r => r.json()),
                    fetch('/api/schedules').then(r => r.json()),
                    fetch('/api/enrollments').then(r => r.json()),
                    fetch('/api/semesters').then(r => r.json()),
                ]);

                // Update new parameters for fillStatCards
                fillStatCards(students, teachers, faculties, subjects, feedbacks, tuitions, schedules);
                fillSemBanner(semesters, schedules, enrollments, tuitions);
                renderTuitionChart(tuitions, semesters);
                renderFacultyChart(students, faculties);
                renderEnrollChart(schedules);
                renderDebtDonut(tuitions);
                renderRecentFeedbacks(feedbacks);
            } catch (e) {
                console.error(e);
            }
        }

        // ── STAT CARDS ────────────────────────────────────────────────────────────
        function fillStatCards(students, teachers, faculties, subjects, feedbacks, tuitions, schedules) {
            document.getElementById('cntStudents').textContent = students.length;
            document.getElementById('cntTeachers').textContent = teachers.length;
            document.getElementById('cntFaculties').textContent = faculties.length;
            document.getElementById('cntSubjects').textContent = subjects.length;
            document.getElementById('cntSchedules').textContent = schedules.length;

            const unread = feedbacks.filter(f => f.status == 0).length;
            document.getElementById('cntFeedbacks').textContent = unread;

            let debtCount = 0;
            let totalRevenue = 0;
            
            tuitions.forEach(t => {
                const total = parseFloat(t.total_amount) || 0;
                const paid = parseFloat(t.paid_amount) || 0;
                if (total > 0 && paid < total) debtCount++;
                totalRevenue += paid;
            });
            
            document.getElementById('cntDebt').textContent = debtCount;
            
            // Format revenue (e.g. 1.2B or 500M)
            let formattedRevenue = '0';
            if (totalRevenue >= 1000000000) {
                formattedRevenue = (totalRevenue / 1000000000).toFixed(1) + ' Tỷ';
            } else if (totalRevenue >= 1000000) {
                formattedRevenue = (totalRevenue / 1000000).toFixed(0) + ' Tr';
            } else if (totalRevenue > 0) {
                formattedRevenue = totalRevenue.toLocaleString() + 'đ';
            }
            document.getElementById('cntRevenue').textContent = formattedRevenue;
        }

        // ── SEMESTER BANNER ───────────────────────────────────────────────────────
        function fillSemBanner(semesters, schedules, enrollments, tuitions) {
            const active = semesters.find(s => s.status == 1);
            if (!active) return;

            const banner = document.getElementById('semBanner');
            banner.style.display = 'flex';
            document.getElementById('semName').textContent = active.name;
            document.getElementById('semYear').textContent = active.academic_year || '';

            const semSched = schedules.filter(s => s.semester_id == active.id);
            const semEnrolls = enrollments.filter(e => {
                return semSched.some(s => s.id == e.schedule_id);
            });
            const semDebt = tuitions.filter(t => {
                if (t.semester_id != active.id) return false;
                return (parseFloat(t.paid_amount) || 0) < (parseFloat(t.total_amount) || 0);
            }).length;

            document.getElementById('semSchedules').textContent = semSched.length;
            document.getElementById('semEnrollments').textContent = semEnrolls.length;
            document.getElementById('semDebt').textContent = semDebt;
        }

        // ── CHART 1: Học phí theo học kỳ ─────────────────────────────────────────
        function renderTuitionChart(tuitions, semesters) {
            // Lấy tối đa 6 học kỳ gần nhất
            const semList = semesters.slice(-6);
            const labels = semList.map(s => s.name + (s.academic_year ? ' ' + s.academic_year : ''));

            const paidData = semList.map(s => {
                const rows = tuitions.filter(t => t.semester_id == s.id);
                return +(rows.reduce((sum, t) => sum + (parseFloat(t.paid_amount) || 0), 0) / 1e6).toFixed(2);
            });
            const debtData = semList.map(s => {
                const rows = tuitions.filter(t => t.semester_id == s.id);
                return +(rows.reduce((sum, t) => {
                    const rem = (parseFloat(t.total_amount) || 0) - (parseFloat(t.paid_amount) || 0);
                    return sum + Math.max(0, rem);
                }, 0) / 1e6).toFixed(2);
            });

            if (charts.tuition) charts.tuition.destroy();
            charts.tuition = new Chart(document.getElementById('chartTuition'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                            label: 'Đã đóng (triệu)',
                            data: paidData,
                            backgroundColor: 'rgba(34,197,94,.75)',
                            borderRadius: 6
                        },
                        {
                            label: 'Còn nợ (triệu)',
                            data: debtData,
                            backgroundColor: 'rgba(239,68,68,.65)',
                            borderRadius: 6
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: '#e2e8f0'
                            }
                        },
                        y: {
                            grid: {
                                color: '#e2e8f0'
                            },
                            beginAtZero: true,
                            ticks: {
                                callback: v => v + 'M'
                            }
                        }
                    }
                }
            });
        }

        // ── CHART 2: SV theo khoa ─────────────────────────────────────────────────
        function renderFacultyChart(students, faculties) {
            const labels = faculties.map(f => f.name);
            const data = faculties.map(f => students.filter(s => {
                // students may have classroom → classroom.faculty_id, or direct faculty_id
                return s.classroom?.faculty_id == f.id;
            }).length);

            if (charts.faculty) charts.faculty.destroy();
            charts.faculty = new Chart(document.getElementById('chartFaculty'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: PALETTE,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 14
                            }
                        }
                    },
                    cutout: '62%'
                }
            });
        }

        // ── CHART 3: Đăng ký học phần (top 8) ────────────────────────────────────
        function renderEnrollChart(schedules) {
            const sorted = [...schedules]
                .filter(s => (s.enrollments_count || 0) > 0)
                .sort((a, b) => (b.enrollments_count || 0) - (a.enrollments_count || 0))
                .slice(0, 8);

            const labels = sorted.map(s => (s.subject?.name || 'Môn #' + s.id).substring(0, 22));
            const data = sorted.map(s => s.enrollments_count || 0);

            if (charts.enroll) charts.enroll.destroy();
            charts.enroll = new Chart(document.getElementById('chartEnroll'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Số SV đăng ký',
                        data,
                        backgroundColor: PALETTE.map(c => c + 'cc'),
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: '#e2e8f0'
                            },
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // ── CHART 4: Donut học phí ────────────────────────────────────────────────
        function renderDebtDonut(tuitions) {
            let paid = 0,
                partial = 0,
                unpaid = 0;
            tuitions.forEach(t => {
                const total = parseFloat(t.total_amount) || 0;
                const p = parseFloat(t.paid_amount) || 0;
                if (total === 0 || p >= total) paid++;
                else if (p > 0) partial++;
                else unpaid++;
            });

            if (charts.debt) charts.debt.destroy();
            charts.debt = new Chart(document.getElementById('chartDebtDonut'), {
                type: 'doughnut',
                data: {
                    labels: ['Đã đóng đủ', 'Còn nợ một phần', 'Chưa đóng'],
                    datasets: [{
                        data: [paid, partial, unpaid],
                        backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 16
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total ? Math.round(ctx.raw / total * 100) : 0;
                                    return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                                }
                            }
                        }
                    },
                    cutout: '62%'
                }
            });
        }

        // ── RECENT FEEDBACKS ──────────────────────────────────────────────────────
        function renderRecentFeedbacks(feedbacks) {
            const list = document.getElementById('fbList');
            list.innerHTML = '';

            const recent = [...feedbacks]
                .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
                .slice(0, 5);

            if (recent.length === 0) {
                list.innerHTML = '<div style="color:#6c757d;font-size:0.9rem;">Không có phản hồi nào.</div>';
                return;
            }

            recent.forEach(f => {
                const isUnread = f.status == 0;
                const time = new Date(f.created_at).toLocaleDateString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const item = document.createElement('div');
                item.className = 'fb-item';
                item.innerHTML = `
                    <div class="fb-dot ${isUnread ? 'unread' : 'read'}"></div>
                    <div class="fb-body">
                        <div class="fb-sender">${f.student?.name || 'Sinh viên vô danh'}</div>
                        <div class="fb-content" title="${f.content || ''}">${f.content || '...'}</div>
                    </div>
                    <div class="fb-time">${time}</div>
                `;
                list.appendChild(item);
            });
        }

        document.addEventListener('DOMContentLoaded', boot);
    </script>
@endsection
