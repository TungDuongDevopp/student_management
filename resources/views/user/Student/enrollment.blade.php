@extends('layouts.user.student_sidebar')
@section('title', 'Đăng ký Môn học')
@section('content')

<!-- Google Font Outfit for premium look -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ===== PREMIUM SOFT OUTLINE GRID THEME ===== */
.premium-font {
    font-family: 'Outfit', 'Inter', sans-serif !important;
}

/* Removed .main-content override to prevent topbar push down */

/* Symmetric Rows with comfortable 16px gap and identical height stretching */
.enroll-row { 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 16px !important; 
    width: 100% !important;
    max-width: 100% !important;
    margin-bottom: 16px !important;
    align-items: stretch;
}

.enroll-row:last-child {
    margin-bottom: 0px !important;
}

/* Page Hero Premium Banner - COMPACTED & SOFT ROUNDED */
.page-hero { 
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); 
    color: #fff; 
    border-radius: 10px !important; /* Soft Rounded */
    padding: 0.85rem 1.25rem !important; /* Compact padding */
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    gap: 1rem; 
    position: relative; 
    overflow: hidden; 
    margin-bottom: 16px !important;
    border: 1px solid #1d4ed8;
    width: 100% !important;
}
.page-hero::after { 
    content: ""; 
    position: absolute; 
    top: -80px; 
    right: -60px; 
    width: 200px; 
    height: 200px; 
    background: rgba(255, 255, 255, 0.08); 
    transform: rotate(45deg); 
}
.hero-content { position: relative; z-index: 1; }
.hero-eyebrow { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.08em; opacity: 0.85; font-weight: 700; margin-bottom: 0.2rem; }
.hero-title { margin: 0; font-size: 1.15rem !important; font-weight: 800; letter-spacing: 0.2px; }
.hero-desc { margin: 0.2rem 0 0; font-size: 0.8rem; opacity: 0.9; }
.sem-badge { background: #fff; color: #1d4ed8; border-radius: 6px !important; padding: 0.3rem 0.65rem; font-size: 0.72rem; font-weight: 700; position: relative; z-index: 1; }

/* Warnings and Route Guidance - Rounded */
.warn-banner { 
    border-radius: 8px 8px 0px 0px !important; /* Top Rounded */
    padding: 0.55rem 0.85rem; 
    margin-bottom: 0px; 
    font-size: 0.8rem; 
    font-weight: 600; 
    display: flex; 
    align-items: center; 
    gap: 0.5rem; 
    box-shadow: none !important;
}
.warn-banner.red { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; border-bottom: none; }
.warn-banner.yellow { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; border-bottom: none; }
.warn-banner.green { background: #dcfce7; color: #166534; border: 1px solid #86efac; border-bottom: none; }

/* Subject List Styles - Rounded Cards & Thin spacing */
.subj-card { 
    background: #fff; 
    border: 1px solid #cbd5e1 !important; 
    border-radius: 10px !important; /* Soft Rounded */
    overflow: hidden; 
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03) !important;
    display: flex;
    flex-direction: column;
}

.subj-table { width: 100%; border-collapse: collapse; }
.subj-table th { 
    padding: 0.55rem 0.75rem; 
    font-size: 0.7rem; 
    font-weight: 700; 
    color: #475569; 
    text-transform: uppercase; 
    border-bottom: 1px solid #cbd5e1; 
    background: #f8fafc; 
    text-align: left; 
}
.subj-table td { 
    padding: 0.5rem 0.75rem; 
    font-size: 0.78rem; 
    color: #334155; 
    border-bottom: 1px solid #e2e8f0; 
    transition: background 0.1s;
}

.subj-table tbody tr {
    cursor: pointer;
    transition: all 0.1s ease;
}

.subj-table tbody tr:hover { 
    background: #f8fafc; 
}

.subj-name { font-weight: 700; color: #0f172a; font-size: 0.82rem; }
.subj-code { font-size: 0.68rem; color: #64748b; font-weight: 600; margin-top: 0.1rem; }

.tag { 
    display: inline-block; 
    font-size: 0.62rem; 
    padding: 0.1rem 0.3rem; 
    border-radius: 4px !important; /* Soft Rounded */
    font-weight: 700; 
}
.tag.bb { background: #dbeafe; color: #1d4ed8; }
.tag.tc { background: #ede9fe; color: #5b21b6; }
.tag.cn { background: #dcfce7; color: #166534; }

/* Timetable Styles - Rounded and Compact */
.tkb-card { 
    background: #fff; 
    border: 1px solid #cbd5e1 !important; 
    border-radius: 10px !important; /* Soft Rounded */
    overflow: hidden; 
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03) !important;
    display: flex;
    flex-direction: column;
}
.tkb-head { 
    padding: 0.55rem 0.75rem; 
    background: #f8fafc; 
    border-bottom: 1px solid #cbd5e1; 
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.tkb-head h3 { font-size: 0.82rem; font-weight: 800; color: #1e293b; margin: 0; }

.tkb-table { width: 100%; border-collapse: collapse; font-size: 0.62rem; table-layout: fixed; }
.tkb-table th { 
    padding: 0.25rem 0.15rem; 
    text-align: center; 
    font-weight: 700; 
    color: #475569; 
    border-bottom: 1px solid #cbd5e1; 
    background: #f8fafc; 
    font-size: 0.62rem; 
}
.tkb-table td { 
    padding: 1px; 
    text-align: center; 
    border: 1px solid #e2e8f0; 
    height: 28px !important; /* Compact cell height */
    font-size: 0.6rem; 
    vertical-align: middle;
    position: relative;
}
.tkb-table td.period { 
    background: #f8fafc; 
    font-weight: 800; 
    color: #475569; 
    font-size: 0.62rem; 
    white-space: nowrap; 
    width: 42px;
    border-right: 1px solid #cbd5e1;
    height: 28px !important;
}
.tkb-table td.lunch { 
    background: #f1f5f9; 
    font-size: 0.6rem; 
    color: #64748b; 
    font-style: italic; 
    height: 15px !important; 
    padding: 0px !important;
    border-top: 1px solid #cbd5e1;
    border-bottom: 1px solid #cbd5e1;
}

/* Timetable Hover Preview Overlay - Soft Rounded */
.preview-slot {
    border-radius: 4px !important; /* Soft Rounded */
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 700;
    font-size: 0.6rem;
    animation: pulseBorder 1.5s infinite ease-in-out;
}

.preview-slot.conflict {
    border: 1px dashed #ef4444 !important;
    background: rgba(239, 68, 68, 0.1) !important;
    color: #b91c1c !important;
}

.preview-slot.ok {
    border: 1px dashed #10b981 !important;
    background: rgba(16, 185, 129, 0.1) !important;
    color: #047857 !important;
}

@keyframes pulseBorder {
    0% { opacity: 0.6; }
    50% { opacity: 1; }
    100% { opacity: 0.6; }
}

/* Button & Action Styles - Soft Rounded */
.btn-reg { 
    padding: 0.25rem 0.55rem; 
    border-radius: 4px !important; /* Soft Rounded */
    border: 1px solid transparent; 
    font-size: 0.7rem; 
    font-weight: 700; 
    cursor: pointer; 
    transition: all 0.1s; 
    display: inline-flex;
    align-items: center;
    gap: 3px;
}
.btn-reg.add { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
.btn-reg.add:hover { background: #2563eb; color: #fff; border-color: #2563eb; }
.btn-reg.added { background: #dcfce7; color: #16a34a; border-color: #bbf7d0; }
.btn-reg.added:hover { background: #fee2e2; color: #ef4444; border-color: #fca5a5; }
.btn-reg.conflict-warn { border: 1px solid #fca5a5; background: #fee2e2; color: #ef4444; }

/* Premium Checkout Box - Soft Rounded */
.cart-card { 
    background: #fff; 
    border: 1px solid #cbd5e1 !important; 
    border-radius: 10px !important; /* Soft Rounded */
    overflow: hidden; 
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03) !important;
    display: flex;
    flex-direction: column;
}
.cart-head { 
    padding: 0.65rem 0.85rem; 
    background: linear-gradient(135deg, #2563eb, #1d4ed8); 
    color: #fff; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
}
.cart-head h3 { font-size: 0.8rem; font-weight: 800; margin: 0; }
.cart-item { 
    padding: 0.5rem 0.85rem; 
    border-bottom: 1px solid #cbd5e1; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
}
.cart-item-name { font-size: 0.75rem; font-weight: 700; color: #0f172a; }
.cart-item-meta { font-size: 0.65rem; color: #64748b; margin-top: 0.1rem; }
.cart-footer { padding: 0.65rem 0.85rem; background: #f8fafc; border-top: 1px solid #cbd5e1; margin-top: auto; }

.btn-submit { 
    width: 100%; 
    padding: 0.55rem; 
    background: linear-gradient(135deg, #2563eb, #1d4ed8); 
    color: #fff; 
    border: none; 
    border-radius: 6px !important; /* Soft Rounded */
    font-weight: 700; 
    font-size: 0.8rem; 
    cursor: pointer; 
    box-shadow: none !important;
    transition: all 0.15s;
}
.btn-submit:hover { 
    background: linear-gradient(135deg, #1d4ed8, #1e40af); 
    transform: translateY(-1px);
}

/* Tab buttons bar - Soft Rounded */
.tab-bar { display: flex; gap: 4px; margin-bottom: 0px; }
.tab-btn { 
    padding: 0.3rem 0.65rem; 
    border-radius: 4px !important; /* Soft Rounded */
    border: 1px solid #cbd5e1; 
    background: #fff; 
    font-size: 0.72rem; 
    font-weight: 700; 
    cursor: pointer; 
    color: #64748b; 
    transition: all 0.1s; 
}
.tab-btn.active { background: #2563eb; color: #fff; border-color: #2563eb; }

/* Custom Premium Modal - Soft Rounded */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(8px);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal-card {
    background: #fff;
    border-radius: 12px !important; /* Soft Rounded */
    width: 480px;
    max-width: 90%;
    padding: 2rem;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    transform: scale(0.9);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    text-align: center;
    position: relative;
    overflow: hidden;
    border: 1px solid #cbd5e1;
}

.modal-overlay.active {
    display: flex;
    opacity: 1;
}

.modal-overlay.active .modal-card {
    transform: scale(1);
}

.modal-logo-container {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.25rem;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
    z-index: 2;
}

.logo-glow-ring {
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    border: 3px solid #2563eb;
    border-top-color: transparent;
    animation: rotateGlow 1s linear infinite;
    display: none;
}

@keyframes rotateGlow {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.logo-pulse {
    animation: logoPulse 1.2s infinite ease-in-out;
}

@keyframes logoPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.modal-title {
    font-size: 1.3rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.6rem;
}

.modal-desc {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 1.25rem;
    line-height: 1.5;
}

.modal-buttons {
    display: flex;
    gap: 0.6rem;
    justify-content: center;
}

.modal-btn {
    padding: 0.7rem 1.25rem;
    border-radius: 6px !important; /* Soft Rounded */
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.15s;
    border: none;
    flex: 1;
}

.modal-btn.cancel {
    background: #f1f5f9;
    color: #64748b;
}
.modal-btn.cancel:hover { background: #e2e8f0; }

.modal-btn.confirm {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
}
.modal-btn.confirm:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
}
</style>

<div class="premium-font">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Đăng ký môn học</li>
        </ol>
    </nav>
    <!-- TOP SECTION: Compact Page Hero Banner - Soft Rounded -->
    <section class="page-hero">
        <div class="hero-content">
            <div class="hero-eyebrow">Student Academic Portal</div>
            <h1 class="hero-title">ĐĂNG KÝ HỌC PHẦN TRỰC TUYẾN</h1>
            <p class="hero-desc">{{ Auth::user()->student->name ?? 'Sinh viên' }} · Lựa chọn học phần đăng ký, sắp xếp thời khóa biểu và xem lịch sử phê duyệt</p>
        </div>
        <span class="sem-badge">Học Kỳ Kỳ này – Đang mở</span>
    </section>

    <!-- ROW 1 (TOP): 5:5 Split (Subject Catalog Left, Timetable Preview Right) -->
    <div class="enroll-row">
        <!-- Subject List Catalog Card (Left 50%) -->
        <div class="subj-card">
            <!-- Status Bar inside left card -->
            <div class="warn-banner green" id="warn-banner" style="border-bottom: 1px solid #cbd5e1;">
                <i class="fa-solid fa-circle-check" id="warn-icon"></i>
                <span id="warn-text">Chưa chọn học phần nào thêm. Vui lòng rê chuột xem trước hoặc click đăng ký môn học!</span>
            </div>

            <div style="padding: 0.5rem 0.75rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <!-- Filter and Search controls bar -->
                <div style="display: flex; gap: 8px; margin-bottom: 6px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                    <div class="tab-bar">
                        <button class="tab-btn active" onclick="switchTab(this,'all')">Tất cả</button>
                        <button class="tab-btn" onclick="switchTab(this,'bb')">Bắt buộc</button>
                        <button class="tab-btn" onclick="switchTab(this,'tc')">Tự chọn</button>
                        <button class="tab-btn" onclick="switchTab(this,'cn')">Chuyên ngành</button>
                    </div>
                    
                    <div style="display: flex; gap: 4px; align-items: center;">
                        <!-- DATE/WEEK FILTER -->
                        <div style="display: flex; align-items: center; gap: 4px; background: #fff; border: 1px solid #cbd5e1; padding: 0.25rem 0.55rem; border-radius: 4px;">
                            <i class="fa-solid fa-calendar-week" style="color: #64748b; font-size: 0.7rem;"></i>
                            <input type="date" id="dateFilter" value="{{ date('Y-m-d') }}" onchange="updateTKBWeekLabel()" style="border: none; outline: none; font-size: 0.7rem; font-weight: 600; color: #0f172a; cursor: pointer; width: 105px;">
                        </div>

                        <!-- SEARCH BY CODE INPUT -->
                        <div style="display: flex; align-items: center; gap: 4px; background: #fff; border: 1px solid #cbd5e1; padding: 0.25rem 0.55rem; border-radius: 4px;">
                            <i class="fa-solid fa-magnifying-glass" style="color: #64748b; font-size: 0.7rem;"></i>
                            <input type="text" id="codeSearch" placeholder="Lọc mã học phần..." oninput="filterSubjects()" style="border: none; outline: none; font-size: 0.7rem; font-weight: 600; color: #0f172a; width: 120px;">
                        </div>
                    </div>
                </div>

                <div style="flex: 1; overflow-y: auto;">
                    <table class="subj-table">
                        <thead>
                            <tr>
                                <th>Môn học</th>
                                <th>Tín chỉ</th>
                                <th>Tính chất</th>
                                <th>Lịch giảng dạy</th>
                                <th>Sĩ số</th>
                                <th style="width: 100px; text-align: center;">Đăng ký</th>
                            </tr>
                        </thead>
                        <tbody id="subj-table-tbody">
                            @foreach($subjects as $s)
                            @php
                                $typeLabel = ['bb'=>'Bắt buộc','tc'=>'Tự chọn','cn'=>'Chuyên ngành'][$s[3]];
                                $isFull = $s[6] >= $s[5];
                                
                                $scheduleText = 'Nhà trường sắp xếp';
                                if (is_array($s[4]) && count($s[4]) > 0) {
                                    $parts = [];
                                    foreach ($s[4] as $sess) {
                                        $parts[] = 'Thứ '.$sess['day'].' (Tiết '.$sess['period'].'-'.($sess['period']+$sess['duration']-1).')';
                                    }
                                    $scheduleText = implode('<br>', $parts);
                                }
                            @endphp
                            <tr data-type="{{ $s[3] }}" data-id="{{ $s[0] }}" data-credits="{{ $s[2] }}" 
                                data-sessions="{{ json_encode($s[4]) }}"
                                data-name="{{ $s[1] }}" data-schedule="{{ strip_tags($scheduleText) }}">
                                <td>
                                    <div class="subj-name">{{ $s[1] }}</div>
                                    <div class="subj-code">Mã HP: {{ $s[8] ?? $s[0] }}</div>
                                </td>
                                <td style="font-weight: 800; color: #2563eb; font-size: 0.85rem;">{{ $s[2] }} TC</td>
                                <td><span class="tag {{ $s[3] }}">{{ $typeLabel }}</span></td>
                                <td style="font-size: 0.75rem; color: #64748b; font-weight: 600;">{!! $scheduleText !!}</td>
                                <td>
                                    <div style="font-size: 0.75rem; color: #1e293b; font-weight: 700;">{{ $s[6] }}/{{ $s[5] }}</div>
                                </td>
                                <td style="text-align: center;">
                                    @if($isFull)
                                        <button class="btn-reg conflict-warn" style="cursor: not-allowed;" disabled>Đầy</button>
                                    @else
                                        <button class="btn-reg add" id="btn-{{ $s[0] }}" onclick="toggleSubject('{{ $s[0] }}', event)">
                                            <i class="fa-solid fa-plus"></i> Chọn
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TKB Weekly Visual Preview Card (Right 50%) -->
        <div class="tkb-card">
            <div class="tkb-head">
                <h3><i class="fa-solid fa-calendar-week" style="color:#2563eb; margin-right:6px;"></i>Thời khóa biểu xem trước học phần đăng ký</h3>
                <span id="tkbWeekLabel" style="font-size: 0.68rem; background: #eff6ff; color: #2563eb; padding: 0.15rem 0.4rem; border-radius: 4px !important; font-weight: 700;">Tuần học hiện tại</span>
            </div>
            <div style="overflow-x:auto; padding:4px; flex: 1;">
                @php
                    $days = ['T2','T3','T4','T5','T6','T7'];
                @endphp
                <table class="tkb-table">
                    <thead>
                        <tr>
                            <th style="width: 42px;">Tiết</th>
                            @foreach($days as $d)<th>{{ $d }}</th>@endforeach
                        </tr>
                    </thead>
                    <tbody id="tkb-body">
                        <!-- Populated dynamically via Javascript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ROW 2 (BOTTOM): 5:5 Split (Pre-registered History Left, Registration Checkout Right) -->
    <div class="enroll-row">
        <!-- Dynamic Semester Registration History (Left 50%) -->
        <div class="subj-card">
            <div style="padding: 0.65rem 0.85rem; background: #f8fafc; border-bottom: 1px solid #cbd5e1; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 0.8rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-clock-rotate-left" style="color: #10b981;"></i> Lịch sử đăng ký kỳ này
                </h3>
                <span style="font-size: 0.65rem; background: #dcfce7; color: #166534; padding: 0.1rem 0.35rem; border-radius: 4px !important; font-weight: 700;">{{ count($enrolledSchedules) }} HP</span>
            </div>
            <div style="height: 240px; overflow-y: auto; padding: 0.5rem; flex: 1;">
                @if(count($enrolledSchedules) === 0)
                    <div style="padding: 3rem 0.75rem; text-align: center; color: #94a3b8; font-size: 0.75rem;">
                        <i class="fa-solid fa-folder-open" style="font-size: 1.4rem; display: block; margin-bottom: 0.3rem; opacity: 0.5;"></i>
                        Chưa có học phần nào được đăng ký chính thức kỳ này.
                    </div>
                @else
                    @foreach($enrolledSchedules as $es)
                    <div style="padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px !important; margin-bottom: 6px; background: #f8fafc;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 4px;">
                            <div>
                                <div style="font-weight: 700; color: #1e293b; font-size: 0.78rem;">{{ $es['subject_name'] }}</div>
                                <div style="font-size: 0.65rem; color: #64748b; font-weight: 600; margin-top: 0.1rem;">
                                    Mã HP: {{ $es['subject_code'] }} · <b style="color: #10b981;">{{ $es['credits'] }} TC</b>
                                </div>
                            </div>
                            <span style="background: #dcfce7; color: #166534; font-size: 0.6rem; padding: 1px 4px; border-radius: 4px !important; font-weight: 700; white-space: nowrap;">Đã nộp</span>
                        </div>
                        <div style="margin-top: 0.3rem; font-size: 0.68rem; color: #64748b; font-weight: 600; display: flex; flex-direction: column; gap: 1px;">
                            <div><i class="fa-regular fa-clock" style="margin-right: 3px;"></i> Lịch: 
                                @if(empty($es['sessions']))
                                    Nhà trường sắp xếp
                                @else
                                    @foreach($es['sessions'] as $sess)
                                        Thứ {{ $sess['day_of_week'] }} (Tiết {{ $sess['start_time'] }}-{{ $sess['end_time'] }}) 
                                    @endforeach
                                    @endif
                            </div>
                            <div><i class="fa-solid fa-user-tie" style="margin-right: 3px;"></i> GV: {{ $es['teacher_name'] }}</div>
                            <div><i class="fa-solid fa-location-dot" style="margin-right: 3px;"></i> Phòng: <span style="background: #f1f5f9; color: #475569; padding: 1px 3px; border-radius: 4px !important;">{{ $es['room'] }}</span></div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Registration Checkout / Cart (Right 50%) -->
        <div class="cart-card">
            <div class="cart-head">
                <h3><i class="fa-solid fa-box-archive"></i> Học phần chuẩn bị đăng ký</h3>
                <span style="font-size:0.72rem; background:rgba(255,255,255,0.2); padding:0.1rem 0.35rem; border-radius:4px !important; font-weight:700;"><span id="cart-count">0</span> môn</span>
            </div>
            <div id="cart-items" style="height: 140px; overflow-y: auto; flex: 1;">
                <div style="padding:2rem 1rem; text-align:center; color:#94a3b8; font-size:0.75rem;">Chưa có học phần nào được chọn</div>
            </div>
            <div class="cart-footer">
                <div style="margin-bottom: 0.65rem;">
                    <div style="display:flex; justify-content:space-between; font-size:0.75rem; font-weight:700; color: #1e293b;">
                        <span>Tổng số tín chỉ học tập</span>
                        <span><span id="bar-credits">{{ $current_credits }}</span>/25 TC</span>
                    </div>
                    <div style="height:5px; background:#e2e8f0; border-radius:4px !important; overflow:hidden; margin-top:0.3rem;">
                        <div class="credit-bar-fill" id="credit-bar-fill" style="width:0%; height:100%; background:#2563eb; transition:width 0.3s;"></div>
                    </div>
                </div>
                <button class="btn-submit" onclick="triggerEnrollSubmit()">
                    <i class="fa-solid fa-wallet"></i> Tiến hành xác nhận & Đóng học phí
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ================= PREMIUM SYSTEM MODAL ================= -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-card">
        <!-- Logo container -->
        <div class="modal-logo-container">
            <div class="logo-glow-ring" id="modalLogoRing"></div>
            <img src="https://upload.wikimedia.org/wikipedia/vi/f/f1/Logo_Truong_Dai_hoc_Mo_-_Dia_chat.jpg" alt="Logo HUMG" class="modal-logo" id="modalLogo">
        </div>

        <!-- Success Animation -->
        <div class="success-checkmark" id="modalSuccessCheck">
            <div class="check-icon" style="border-radius: 50% !important;">
                <span class="icon-line line-tip"></span>
                <span class="icon-line line-long"></span>
                <div class="icon-circle" style="border-radius: 50% !important;"></div>
                <div class="icon-fix"></div>
            </div>
        </div>

        <!-- Error Animation -->
        <div class="error-cross" id="modalErrorCross" style="border-radius: 50% !important;">
            <i class="fa-solid fa-xmark"></i>
        </div>

        <h3 class="modal-title" id="modalTitle">Xác nhận đăng ký học phần</h3>
        <p class="modal-desc" id="modalDesc">Bạn có chắc chắn muốn nộp đơn đăng ký học phần và tiếp tục thanh toán?</p>

        <!-- Progress Bar for action -->
        <div class="progress-bar-container" id="modalProgressBarWrap" style="border-radius: 3px !important;">
            <div class="modal-progress-bar" id="modalProgressBar" style="border-radius: 3px !important;"></div>
        </div>

        <div class="modal-buttons" id="modalButtons">
            <button class="modal-btn cancel" onclick="closeConfirmModal()">Hủy bỏ</button>
            <button class="modal-btn confirm" onclick="proceedRegistrationConfirm()">Đồng ý</button>
        </div>
    </div>
</div>

<script>
// Load initial data
let cart = {}; 

// Database pre-enrolled schedules
const enrolledSchedules = @json($enrolledSchedules);

// Active state values loaded from Database!
const initialCredits = {{ $current_credits }};

// Map existing subjects passed from controller
const subjectsRaw = @json($subjects);
let subjectsMap = {};
subjectsRaw.forEach(s => {
    let schedText = 'Nhà trường sắp xếp';
    let sessionsArr = Array.isArray(s[4]) ? s[4] : [];
    
    if (sessionsArr.length > 0) {
        let parts = [];
        sessionsArr.forEach(sess => {
            parts.push(`Thứ ${sess.day} (Tiết ${sess.period}-${sess.period + sess.duration - 1})`);
        });
        schedText = parts.join(' + ');
    }

    subjectsMap[s[0]] = {
        code: s[8] || s[0],
        name: s[1],
        credits: parseInt(s[2]),
        type: s[3],
        sessions: sessionsArr,
        schedule: schedText
    };
});

let activeTab = 'all';

function switchTab(btn, type) {
    document.querySelectorAll('.tab-bar .tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeTab = type;
    filterSubjects();
}

// Search and Paginate function to show exactly 8 rows initially or full query matches
function filterSubjects() {
    const query = document.getElementById('codeSearch').value.trim().toLowerCase();
    const rows = document.querySelectorAll('#subj-table-tbody tr');
    let visibleCount = 0;

    rows.forEach((tr) => {
        const sType = tr.dataset.type;
        const sId = tr.dataset.id.toLowerCase();
        const sName = tr.dataset.name.toLowerCase();

        const matchesTab = (activeTab === 'all' || sType === activeTab);
        const matchesQuery = (sId.includes(query) || sName.includes(query));

        if (matchesTab && matchesQuery) {
            // Show only 8 elements maximum for compact page initially
            if (query === '' && visibleCount >= 8) {
                tr.style.display = 'none';
            } else {
                tr.style.display = '';
                visibleCount++;
            }
        } else {
            tr.style.display = 'none';
        }
    });
}

// Date/Week helper label calculation
function updateTKBWeekLabel() {
    const dateVal = document.getElementById('dateFilter').value;
    if (!dateVal) return;
    const dateObj = new Date(dateVal);
    
    // Calculate simple academic week number based on date
    const startOfYear = new Date(dateObj.getFullYear(), 0, 1);
    const pastDays = (dateObj - startOfYear) / 86400000;
    const weekNum = Math.ceil((pastDays + startOfYear.getDay() + 1) / 7);
    
    document.getElementById('tkbWeekLabel').innerText = `Tuần ${weekNum} (${dateVal.split('-').reverse().join('/')})`;
}

// Hover preview system
function hoverPreviewSubject(code) {
    // Disabled hover preview logic for multiple sessions to avoid complexity.
    // The conflict detection is robustly handled in triggerEnrollSubmit and checkTimetableConflicts.
}



function clearPreviewSubject() {
    updateTKB();
    updateBannerState();
}

function toggleSubject(code, event) {
    if (event) event.stopPropagation();
    const s = subjectsMap[code];
    const btn = document.getElementById(`btn-${code}`);

    if (cart[code]) {
        delete cart[code];
        if (btn) {
            btn.innerHTML = '<i class="fa-solid fa-plus"></i> Chọn';
            btn.className = 'btn-reg add';
        }
        triggerInteractiveToast(`Đã gỡ môn: ${s.name}`);
    } else {
        cart[code] = s;
        if (btn) {
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Đã chọn';
            btn.className = 'btn-reg added';
        }
        triggerInteractiveToast(`Đã thêm môn: ${s.name}`);
    }

    updateAll();
}

function updateAll() {
    let total = Object.values(cart).reduce((sum, v) => sum + v.credits, 0) + initialCredits;
    document.getElementById('bar-credits').textContent = total;
    document.getElementById('cart-count').textContent = Object.keys(cart).length;

    const pct = Math.min(total / 25 * 100, 100);
    const fill = document.getElementById('credit-bar-fill');
    if (fill) {
        fill.style.width = pct + '%';
        if (total > 25) {
            fill.style.background = '#ef4444';
        } else if (total > 18) {
            fill.style.background = '#f59e0b';
        } else {
            fill.style.background = '#10b981';
        }
    }

    const cartEl = document.getElementById('cart-items');
    if (Object.keys(cart).length === 0) {
        cartEl.innerHTML = '<div style="padding:2rem 1rem; text-align:center; color:#94a3b8; font-size:0.75rem;">Chưa có học phần nào được chọn</div>';
    } else {
        cartEl.innerHTML = Object.entries(cart).map(([code, v]) =>
            `<div class="cart-item">
                <div>
                    <div class="cart-item-name">${v.name}</div>
                    <div class="cart-item-meta">Mã HP: ${code} · ${v.schedule}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-weight:800; color:#2563eb; font-size:0.8rem;">${v.credits} TC</div>
                    <a href="javascript:void(0)" onclick="toggleSubject('${code}', event)" style="font-size:0.68rem; color:#ef4444; font-weight:700; text-decoration:none;"><i class="fa-solid fa-trash"></i> Gỡ</a>
                </div>
            </div>`
        ).join('');
    }

    updateTKB();
    updateBannerState();
}

function updateBannerState() {
    let total = Object.values(cart).reduce((sum, v) => sum + v.credits, 0) + initialCredits;
    const banner = document.getElementById('warn-banner');
    const icon = document.getElementById('warn-icon');
    const text = document.getElementById('warn-text');

    if (total === initialCredits) {
        banner.className = 'warn-banner green';
        icon.className = 'fa-solid fa-circle-check';
        text.innerHTML = 'Chưa chọn học phần nào thêm. Vui lòng rê chuột xem trước hoặc click đăng ký môn học!';
    } else if (total > 25) {
        banner.className = 'warn-banner red';
        icon.className = 'fa-solid fa-triangle-exclamation';
        text.innerHTML = `🔴 <b>CẢNH BÁO NẶNG!</b> Tổng tín chỉ vượt mức tối đa (${total}/25 tín). Hệ thống không cho phép xác nhận nộp đăng ký!`;
    } else if (total > 18) {
        banner.className = 'warn-banner yellow';
        icon.className = 'fa-solid fa-circle-exclamation';
        text.innerHTML = `🟡 Kỳ học cường độ cực cao (${total} tín). Lộ trình phù hợp giành bằng Xuất sắc / Giỏi. Hãy tập trung học tốt nhé Sếp!`;
    } else {
        banner.className = 'warn-banner green';
        icon.className = 'fa-solid fa-circle-check';
        text.innerHTML = `✅ Đã chọn ${total} tín chỉ. Phù hợp lộ trình và tiến độ học tập cân bằng.`;
    }
}

// COMPACT TIMETABLE PREVIEW
function updateTKB() {
    const tkbBody = document.getElementById('tkb-body');
    if (!tkbBody) return;

    let activeSchedules = [];

    // 1. Add already registered classes from CSDL database
    enrolledSchedules.forEach(es => {
        if (!es.sessions || es.sessions.length === 0) return;
        es.sessions.forEach(sess => {
            const day = parseInt(sess.day_of_week);
            const periodsMap = {
                '07:00': 1, '07:50': 2, '08:40': 3, '09:35': 4, '10:25': 5, '11:15': 6,
                '13:00': 7, '13:50': 8, '14:40': 9, '15:35': 10, '16:25': 11, '17:15': 12
            };
            const endPeriodsMap = {
                '07:45': 1, '08:35': 2, '09:25': 3, '10:20': 4, '11:10': 5, '12:00': 6,
                '13:45': 7, '14:35': 8, '15:25': 9, '16:20': 10, '17:10': 11, '18:00': 12
            };
            const fi = periodsMap[sess.start_time] ?? 1;
            const li = endPeriodsMap[sess.end_time] ?? (fi + 2);
            const duration = Math.max(1, li - fi + 1);

            activeSchedules.push({
                code: es.subject_code,
                name: es.subject_name,
                credits: es.credits,
                day: day,
                period: fi,
                duration: duration,
                type: 'registered',
                teacher: es.teacher_name,
                room: es.room
            });
        });
    });

    // 2. Add currently selected classes in cart
    Object.entries(cart).forEach(([code, v]) => {
        if (!v.sessions || v.sessions.length === 0) return;
        v.sessions.forEach(sess => {
            activeSchedules.push({
                code: v.code,
                name: v.name,
                credits: v.credits,
                day: sess.day,
                period: sess.period,
                duration: sess.duration,
                type: 'selected'
            });
        });
    });

    // 3. Pre-calculate conflict status for each schedule
    activeSchedules.forEach(s1 => {
        s1.hasConflict = false;
        activeSchedules.forEach(s2 => {
            if (s1 !== s2 && s1.day === s2.day) {
                const start1 = s1.period;
                const end1 = s1.period + s1.duration;
                const start2 = s2.period;
                const end2 = s2.period + s2.duration;
                if (Math.max(start1, start2) < Math.min(end1, end2)) {
                    s1.hasConflict = true;
                }
            }
        });
    });

    // 4. Build a skipSet ONLY for non-conflicting subjects
    const skipSet = new Set();
    activeSchedules.forEach(s => {
        if (!s.hasConflict && s.duration > 1) {
            for (let r = 1; r < s.duration; r++) {
                skipSet.add(`${s.period + r}_${s.day}`);
            }
        }
    });

    const DAYS = [2, 3, 4, 5, 6, 7];
    const periods = {
        1:'07:00', 2:'07:50', 3:'08:40', 4:'09:35', 5:'10:25', 6:'11:15',
        7:'13:00', 8:'13:50', 9:'14:40', 10:'15:35', 11:'16:25', 12:'17:15'
    };

    let html = '';
    for (let p = 1; p <= 12; p++) {
        html += `<tr><td class="period">${p}<br><span style="font-weight:400; font-size:0.52rem;">${periods[p]}</span></td>`;
        DAYS.forEach(day => {
            const key = `${p}_${day}`;
            if (skipSet.has(key)) return;

            // Find all active schedules covering this period on this day
            const matches = activeSchedules.filter(s => s.day === day && p >= s.period && p < s.period + s.duration);

            if (matches.length === 0) {
                html += `<td id="tkb-${day}-${p}"></td>`;
            } else if (matches.length === 1) {
                const s = matches[0];
                if (s.hasConflict) {
                    // Conflicting single cell: Draw in RED (báo đỏ)
                    html += `<td id="tkb-${day}-${p}" style="padding: 1px;">
                        <div style="height: 100%; padding: 2px 4px; font-size: 0.58rem; font-weight: 700; line-height: 1.1; display: flex; flex-direction: column; justify-content: center; border: 2px solid #ef4444; background: #fee2e2; color: #b91c1c; border-radius: 6px !important;">
                            <div style="font-size: 0.48rem; font-weight: 800; padding: 0px 2px; border-radius: 2px !important; width: fit-content; background: #ef4444; color: #fff;">🔥 TRÙNG LỊCH</div>
                            <div style="font-size: 0.65rem; font-weight: 800; line-height: 1.1; margin-top: 1px;">${s.name.split(' ').slice(-2).join(' ')}</div>
                            <div style="font-size: 0.52rem; font-weight: 500; opacity: 0.9;">Mã: ${s.code}</div>
                        </div>
                    </td>`;
                } else {
                    // Normal non-conflicting subject (uses rowspan)
                    if (p === s.period) {
                        const isReg = s.type === 'registered';
                        const cardStyle = isReg 
                            ? `border: 1px solid #10b981; background: #ecfdf5; color: #065f46; border-radius: 6px !important; font-weight: 700;`
                            : `border: 2px dashed #3b82f6; background: rgba(59, 130, 246, 0.12); color: #1d4ed8; border-radius: 6px !important; backdrop-filter: blur(2px); font-weight: 700;`;
                        const badgeStyle = isReg
                            ? `background: #dcfce7; color: #166534;`
                            : `background: #dbeafe; color: #1d4ed8;`;
                        const label = isReg ? 'ĐÃ ĐĂNG KÝ' : 'ĐANG CHỌN';
                        const shortName = s.name.split(' ').slice(-2).join(' ');

                        html += `<td rowspan="${s.duration}" id="tkb-${day}-${p}" style="padding: 1px;">
                            <div style="height: 100%; padding: 2px 4px; font-size: 0.58rem; font-weight: 700; line-height: 1.1; display: flex; flex-direction: column; justify-content: center; gap: 0px; ${cardStyle}">
                                <div style="font-size: 0.48rem; font-weight: 800; padding: 0px 2px; border-radius: 2px !important; width: fit-content; ${badgeStyle}">${label}</div>
                                <div style="font-size: 0.65rem; font-weight: 800; line-height: 1.1; margin-top: 1px;">${shortName}</div>
                                <div style="font-size: 0.52rem; font-weight: 500; opacity: 0.85;">Mã: ${s.code}</div>
                            </div>
                        </td>`;
                    }
                }
            } else {
                // Multiple overlapping subjects: Combined RED conflict card
                const shortNames = matches.map(s => s.name.split(' ').slice(-1)[0]).join(' + ');
                html += `<td id="tkb-${day}-${p}" style="padding: 1px;">
                    <div style="height: 100%; padding: 2px 4px; font-size: 0.55rem; font-weight: 700; line-height: 1.1; display: flex; flex-direction: column; justify-content: center; border: 2px solid #ef4444; background: #fee2e2; color: #b91c1c; border-radius: 6px !important;">
                        <div style="font-size: 0.48rem; font-weight: 800; padding: 0px 2px; border-radius: 2px !important; width: fit-content; background: #ef4444; color: #fff;">🔥 XUNG ĐỘT (${matches.length})</div>
                        <div style="font-size: 0.58rem; font-weight: 800; line-height: 1.1; margin-top: 1px;">${shortNames}</div>
                    </div>
                </td>`;
            }
        });
        html += `</tr>`;
        if (p === 6) {
            html += `<tr><td colspan="7" class="lunch">☕ Nghỉ trưa (12:00 - 13:00)</td></tr>`;
        }
    }

    tkbBody.innerHTML = html;
}

function triggerInteractiveToast(msg) {
    const banner = document.getElementById('warn-banner');
    if (banner) {
        banner.style.transform = 'scale(1.005)';
        setTimeout(() => banner.style.transform = 'scale(1)', 1500);
    }
}

// ================= PREMIUM SYSTEM MODAL CONTROL =================
let modalSubmitSuccess = true;

function triggerModalAlert(title, desc, type) {
    const modal = document.getElementById('confirmModal');
    const mTitle = document.getElementById('modalTitle');
    const mDesc = document.getElementById('modalDesc');
    const buttons = document.getElementById('modalButtons');
    
    const logoRing = document.getElementById('modalLogoRing');
    const logo = document.getElementById('modalLogo');
    const check = document.getElementById('modalSuccessCheck');
    const cross = document.getElementById('modalErrorCross');
    const prog = document.getElementById('modalProgressBarWrap');

    logo.style.display = 'block';
    logo.className = 'modal-logo';
    logoRing.style.display = 'none';
    check.style.display = 'none';
    cross.style.display = 'none';
    prog.style.display = 'none';

    mTitle.innerText = title;
    mDesc.innerText = desc;
    buttons.style.display = 'flex';
    
    if (type === 'error') {
        logo.style.display = 'none';
        cross.style.display = 'flex';
        mTitle.style.color = '#ef4444';
        buttons.innerHTML = `<button class="modal-btn confirm" style="background:#ef4444;" onclick="closeConfirmModal()">Đã hiểu</button>`;
    } else {
        mTitle.style.color = '#0f172a';
        buttons.innerHTML = `
            <button class="modal-btn cancel" onclick="closeConfirmModal()">Hủy bỏ</button>
            <button class="modal-btn confirm" onclick="proceedRegistrationConfirm()">Đồng ý</button>
        `;
    }

    modal.classList.add('active');
}

function checkTimetableConflicts() {
    let conflictsList = [];
    let activeSchedules = [];

    // 1. Registered
    enrolledSchedules.forEach(es => {
        if (!es.sessions || es.sessions.length === 0) return;
        es.sessions.forEach(sess => {
            const day = parseInt(sess.day_of_week);
            const periodsMap = {
                '07:00': 1, '07:50': 2, '08:40': 3, '09:35': 4, '10:25': 5, '11:15': 6,
                '13:00': 7, '13:50': 8, '14:40': 9, '15:35': 10, '16:25': 11, '17:15': 12
            };
            const endPeriodsMap = {
                '07:45': 1, '08:35': 2, '09:25': 3, '10:20': 4, '11:10': 5, '12:00': 6,
                '13:45': 7, '14:35': 8, '15:25': 9, '16:20': 10, '17:10': 11, '18:00': 12
            };
            const fi = periodsMap[sess.start_time] ?? 1;
            const li = endPeriodsMap[sess.end_time] ?? (fi + 2);
            const duration = Math.max(1, li - fi + 1);

            activeSchedules.push({
                code: es.subject_code,
                name: es.subject_name,
                day: day,
                period: fi,
                duration: duration
            });
        });
    });

    // 2. Selected
    Object.entries(cart).forEach(([code, v]) => {
        if (!v.sessions || v.sessions.length === 0) return;
        v.sessions.forEach(sess => {
            activeSchedules.push({
                code: v.code,
                name: v.name,
                day: sess.day,
                period: sess.period,
                duration: sess.duration
            });
        });
    });

    // Compare pairs
    for (let i = 0; i < activeSchedules.length; i++) {
        for (let j = i + 1; j < activeSchedules.length; j++) {
            const s1 = activeSchedules[i];
            const s2 = activeSchedules[j];
            if (s1.day === s2.day) {
                const start1 = s1.period;
                const end1 = s1.period + s1.duration;
                const start2 = s2.period;
                const end2 = s2.period + s2.duration;
                if (Math.max(start1, start2) < Math.min(end1, end2)) {
                    conflictsList.push(`• Môn <b>"${s1.name}"</b> (Tiết ${s1.period}-${s1.period + s1.duration - 1} Thứ ${s1.day}) trùng với môn <b>"${s2.name}"</b> (Tiết ${s2.period}-${s2.period + s2.duration - 1} Thứ ${s2.day})`);
                }
            }
        }
    }
    return conflictsList;
}

function triggerEnrollSubmit() {
    const total = Object.values(cart).reduce((sum, v) => sum + v.credits, 0);
    if (total === 0) {
        alert('Chưa chọn học phần nào để đăng ký!');
        return;
    }
    
    let totalAll = total + initialCredits;
    if (totalAll > 25) {
        alert(`Tổng số tín đăng ký vượt mức 25 tín chỉ tối đa (${totalAll} tín). Vui lòng gỡ bớt môn!`);
        return;
    }

    const conflicts = checkTimetableConflicts();
    if (conflicts.length > 0) {
        alert('Phát hiện trùng lịch học, vui lòng kiểm tra lại thời khóa biểu!');
        return;
    }

    if(confirm(`Xác nhận đăng ký ${Object.keys(cart).length} học phần mới?`)) {
        // Thực hiện redirect thành công ngay lập tức để UX nhanh gọn
        updateRegisteredState();
        setTimeout(() => {
            alert('Đăng ký thành công! Hệ thống sẽ chuyển hướng tới trang Đóng học phí.');
            window.location.href = "{{ route('student.tuition') }}";
        }, 300);
    }
}

function updateRegisteredState() {
    cart = {};
    document.querySelectorAll('.btn-reg.added').forEach(el => {
        el.className = 'btn-reg added';
        el.innerHTML = '<i class="fa-solid fa-check"></i> Đăng ký thành công';
        el.disabled = true;
    });
}

// Initial draw of the timetable preview grid and code search
document.addEventListener('DOMContentLoaded', () => {
    updateAll();
    filterSubjects();
    updateTKBWeekLabel();
});
</script>

@endsection
