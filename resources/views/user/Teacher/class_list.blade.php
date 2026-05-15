@extends('layouts.user.teacher_sidebar')
@section('title', 'Danh sách Lớp học')
@section('content')
<style>
    .cls-wrapper { max-width: 1200px; }
    .cls-header { margin-bottom:1.5rem; }
    .cls-header h1 { font-size:1.5rem; font-weight:800; color:#0f172a; margin:0 0 0.25rem; }
    .cls-header p { color:#64748b; font-size:0.88rem; margin:0; }
    .cls-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:1rem; }
    .cls-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:1.25rem; transition:all 0.2s; position:relative; overflow:hidden; }
    .cls-card:hover { box-shadow:0 8px 24px rgba(0,0,0,0.08); transform:translateY(-2px); }
    .cls-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; border-radius:12px 12px 0 0; }
    .cls-card.blue::before { background:#2563eb; }
    .cls-card.green::before { background:#10b981; }
    .cls-card.amber::before { background:#f59e0b; }
    .cls-card.purple::before { background:#8b5cf6; }
    .cls-code { font-size:0.75rem; font-weight:700; color:#2563eb; background:#eff6ff; padding:0.2rem 0.5rem; border-radius:4px; display:inline-block; margin-bottom:0.5rem; }
    .cls-name { font-size:1.05rem; font-weight:700; color:#1e293b; margin-bottom:0.75rem; }
    .cls-meta { display:flex; flex-direction:column; gap:0.35rem; margin-bottom:0.75rem; }
    .cls-meta-item { display:flex; align-items:center; gap:0.5rem; font-size:0.82rem; color:#64748b; }
    .cls-meta-item i { width:16px; text-align:center; color:#94a3b8; }
    .cls-badges { display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.75rem; }
    .cls-badge { font-size:0.72rem; padding:0.2rem 0.5rem; border-radius:4px; font-weight:600; }
    .cls-badge.ok { background:#dcfce7; color:#166534; }
    .cls-badge.warn { background:#fef3c7; color:#92400e; }
    .cls-badge.lab { background:#ede9fe; color:#5b21b6; }
    .cls-actions { display:flex; gap:0.5rem; padding-top:0.75rem; border-top:1px solid #f1f5f9; }
    .cls-actions a { flex:1; text-align:center; padding:0.45rem; border-radius:6px; font-size:0.8rem; font-weight:600; text-decoration:none; transition:all 0.15s; }
    .cls-actions .btn-list { background:#eff6ff; color:#2563eb; }
    .cls-actions .btn-list:hover { background:#dbeafe; }
    .cls-actions .btn-score { background:#f0fdf4; color:#16a34a; }
    .cls-actions .btn-score:hover { background:#dcfce7; }
    .cls-actions .btn-att { background:#fef3c7; color:#d97706; }
    .cls-actions .btn-att:hover { background:#fde68a; }
</style>

<div class="cls-wrapper">
    <div class="cls-header">
        <h1><i class="fa-solid fa-layer-group" style="color:#2563eb; margin-right:0.5rem;"></i>Danh sách Lớp học phần</h1>
        <p>Học kỳ 2 — Năm học 2025-2026 · Giảng viên: Ngô Ngọc Anh</p>
    </div>

    <div class="cls-grid">
        <div class="cls-card blue">
            <span class="cls-code">DCCTTPM70A</span>
            <div class="cls-name">Lập trình Web nâng cao</div>
            <div class="cls-meta">
                <div class="cls-meta-item"><i class="fa-solid fa-users"></i> Sĩ số: 65 sinh viên</div>
                <div class="cls-meta-item"><i class="fa-regular fa-calendar"></i> Thứ 2 · Tiết 1-3 (07:00-09:25)</div>
                <div class="cls-meta-item"><i class="fa-solid fa-location-dot"></i> Phòng C301 — Nhà C</div>
            </div>
            <div class="cls-badges">
                <span class="cls-badge ok">Đúng lịch</span>
                <span class="cls-badge warn">Chưa chốt điểm</span>
            </div>
            <div class="cls-actions">
                <a href="{{ route('teacher.students') }}" class="btn-list"><i class="fa-solid fa-list"></i> DS sinh viên</a>
                <a href="#" class="btn-score"><i class="fa-solid fa-pen"></i> Vào điểm</a>
                <a href="{{ route('teacher.attendances') }}" class="btn-att"><i class="fa-solid fa-user-check"></i> Điểm danh</a>
            </div>
        </div>

        <div class="cls-card green">
            <span class="cls-code" style="color:#059669; background:#ecfdf5;">DCCTTPM70B</span>
            <div class="cls-name">Phân tích & thiết kế hệ thống</div>
            <div class="cls-meta">
                <div class="cls-meta-item"><i class="fa-solid fa-users"></i> Sĩ số: 58 sinh viên</div>
                <div class="cls-meta-item"><i class="fa-regular fa-calendar"></i> Thứ 4 · Tiết 1-3 (07:00-09:25)</div>
                <div class="cls-meta-item"><i class="fa-solid fa-location-dot"></i> Phòng B202 — Nhà B</div>
            </div>
            <div class="cls-badges">
                <span class="cls-badge ok">Đúng lịch</span>
            </div>
            <div class="cls-actions">
                <a href="{{ route('teacher.students') }}" class="btn-list"><i class="fa-solid fa-list"></i> DS sinh viên</a>
                <a href="#" class="btn-score"><i class="fa-solid fa-pen"></i> Vào điểm</a>
                <a href="{{ route('teacher.attendances') }}" class="btn-att"><i class="fa-solid fa-user-check"></i> Điểm danh</a>
            </div>
        </div>

        <div class="cls-card amber">
            <span class="cls-code" style="color:#d97706; background:#fffbeb;">DCCNTT69C</span>
            <div class="cls-name">Cơ sở dữ liệu</div>
            <div class="cls-meta">
                <div class="cls-meta-item"><i class="fa-solid fa-users"></i> Sĩ số: 62 sinh viên</div>
                <div class="cls-meta-item"><i class="fa-regular fa-calendar"></i> Thứ 3 · Tiết 4-6 (09:35-12:00)</div>
                <div class="cls-meta-item"><i class="fa-solid fa-location-dot"></i> Phòng A402 — Nhà A</div>
            </div>
            <div class="cls-badges">
                <span class="cls-badge ok">Đúng lịch</span>
            </div>
            <div class="cls-actions">
                <a href="{{ route('teacher.students') }}" class="btn-list"><i class="fa-solid fa-list"></i> DS sinh viên</a>
                <a href="#" class="btn-score"><i class="fa-solid fa-pen"></i> Vào điểm</a>
                <a href="{{ route('teacher.attendances') }}" class="btn-att"><i class="fa-solid fa-user-check"></i> Điểm danh</a>
            </div>
        </div>

        <div class="cls-card purple">
            <span class="cls-code" style="color:#7c3aed; background:#f5f3ff;">DCCTTPM71A</span>
            <div class="cls-name">Lập trình PHP Laravel</div>
            <div class="cls-meta">
                <div class="cls-meta-item"><i class="fa-solid fa-users"></i> Sĩ số: 61 sinh viên</div>
                <div class="cls-meta-item"><i class="fa-regular fa-calendar"></i> Thứ 5 · Tiết 7-9 (13:00-15:25)</div>
                <div class="cls-meta-item"><i class="fa-solid fa-location-dot"></i> Phòng LAB-03</div>
            </div>
            <div class="cls-badges">
                <span class="cls-badge lab">Thực hành</span>
                <span class="cls-badge warn">Cần điểm danh</span>
            </div>
            <div class="cls-actions">
                <a href="{{ route('teacher.students') }}" class="btn-list"><i class="fa-solid fa-list"></i> DS sinh viên</a>
                <a href="#" class="btn-score"><i class="fa-solid fa-pen"></i> Vào điểm</a>
                <a href="{{ route('teacher.attendances') }}" class="btn-att"><i class="fa-solid fa-user-check"></i> Điểm danh</a>
            </div>
        </div>
    </div>
</div>
@endsection
