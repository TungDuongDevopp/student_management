@extends('layouts.user.teacher_sidebar')
@section('title', 'Điểm danh')
@section('content')
<style>
    .att-wrapper { max-width: 1200px; }
    .page-hero { background:linear-gradient(135deg,#7f1d1d 0%,#dc2626 100%);color:#fff;border-radius:10px;padding:1.5rem 1.75rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;position:relative;overflow:hidden;margin-bottom:1.5rem; }
    .page-hero::after { content:"";position:absolute;top:-80px;right:-60px;width:260px;height:260px;background:rgba(255,255,255,.08);transform:rotate(45deg); }
    .hero-content { position:relative;z-index:1; }
    .hero-eyebrow { font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;opacity:.85;font-weight:700;margin-bottom:.4rem; }
    .hero-title { margin:0;font-size:1.45rem;font-weight:800; }
    .att-filters { display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; position:relative; z-index:1; }
    .att-filters select, .att-filters input { padding:0.5rem 0.75rem; border:1px solid #e2e8f0; border-radius:8px; font-size:0.85rem; background:#fff; color:#1e293b; }
    .att-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; }
    .att-card-header { padding:1rem 1.25rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
    .att-card-header h2 { font-size:1rem; font-weight:700; color:#1e293b; margin:0; }
    .att-summary { display:flex; gap:0.75rem; }
    .att-summary span { font-size:0.78rem; padding:0.3rem 0.65rem; border-radius:6px; font-weight:600; }
    .att-present { background:#dcfce7; color:#166534; }
    .att-absent { background:#fee2e2; color:#991b1b; }
    .att-late { background:#fef3c7; color:#92400e; }
    .att-table { width:100%; border-collapse:collapse; }
    .att-table th { padding:0.75rem 1rem; text-align:left; font-size:0.78rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; border-bottom:2px solid #e2e8f0; background:#f8fafc; }
    .att-table td { padding:0.7rem 1rem; font-size:0.88rem; color:#334155; border-bottom:1px solid #f1f5f9; }
    .att-table tbody tr:hover { background:#f8fafc; }
    .att-student-info { display:flex; align-items:center; gap:0.75rem; }
    .att-student-avatar { width:32px; height:32px; border-radius:50%; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; }
    .att-student-name { font-weight:600; color:#1e293b; }
    .att-student-code { font-size:0.78rem; color:#94a3b8; }
    .att-radio-group { display:flex; gap:1rem; }
    .att-radio-group label { display:flex; align-items:center; gap:0.3rem; font-size:0.82rem; cursor:pointer; font-weight:500; }
    .att-radio-group input[type="radio"] { accent-color:#2563eb; }
    .att-note { width:140px; padding:0.35rem 0.6rem; border:1px solid #e2e8f0; border-radius:6px; font-size:0.8rem; }
    .att-btn-save { padding:0.65rem 1.5rem; background:#2563eb; color:#fff; border:none; border-radius:8px; font-weight:700; font-size:0.88rem; cursor:pointer; transition:all 0.2s; }
    .att-btn-save:hover { background:#1d4ed8; }
    .att-footer { padding:1rem 1.25rem; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:0.75rem; }
</style>

<div class="att-wrapper">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ route('teacher.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Điểm danh</li>
        </ol>
    </nav>
    <div class="page-hero">
        <div class="hero-content">
            <div class="hero-eyebrow">Teacher Academic Portal</div>
            <h1 class="hero-title"><i class="fa-solid fa-user-check" style="margin-right:0.5rem;"></i>Điểm danh Sinh viên</h1>
        </div>
        <div class="att-filters">
            <select>
                <option>DCCTTPM70A — Lập trình Web nâng cao</option>
                <option>DCCTTPM70B — Phân tích & thiết kế hệ thống</option>
                <option>DCCNTT69C — Cơ sở dữ liệu</option>
                <option>DCCTTPM71A — Lập trình PHP Laravel</option>
            </select>
            <input type="date" value="2026-05-15">
            <select>
                <option>Tiết 1-3 (07:00 - 09:25)</option>
                <option>Tiết 4-6 (09:35 - 12:00)</option>
                <option>Tiết 7-9 (13:00 - 15:25)</option>
                <option>Tiết 10-12 (15:35 - 18:00)</option>
            </select>
        </div>
    </div>

    <div class="att-card">
        <div class="att-card-header">
            <h2>DCCTTPM70A — Lập trình Web nâng cao · Tiết 1-3 · Thứ 2</h2>
            <div class="att-summary">
                <span class="att-present"><i class="fa-solid fa-check"></i> Có mặt: 60</span>
                <span class="att-absent"><i class="fa-solid fa-xmark"></i> Vắng: 3</span>
                <span class="att-late"><i class="fa-solid fa-clock"></i> Muộn: 2</span>
            </div>
        </div>
        <table class="att-table">
            <thead>
                <tr>
                    <th style="width:50px">STT</th>
                    <th>Sinh viên</th>
                    <th>Mã SV</th>
                    <th style="width:280px">Trạng thái</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                @php
                $students = [
                    ['name'=>'Nguyễn Văn An','code'=>'SV001','initials'=>'NA'],
                    ['name'=>'Trần Thị Bình','code'=>'SV002','initials'=>'TB'],
                    ['name'=>'Lê Hoàng Cường','code'=>'SV003','initials'=>'LC'],
                    ['name'=>'Phạm Minh Đức','code'=>'SV004','initials'=>'MĐ'],
                    ['name'=>'Hoàng Thị Em','code'=>'SV005','initials'=>'HE'],
                    ['name'=>'Vũ Quốc Phong','code'=>'SV006','initials'=>'QP'],
                    ['name'=>'Đặng Thị Giang','code'=>'SV007','initials'=>'TG'],
                    ['name'=>'Bùi Văn Hải','code'=>'SV008','initials'=>'VH'],
                    ['name'=>'Ngô Thị Lan','code'=>'SV009','initials'=>'TL'],
                    ['name'=>'Dương Tuấn Kiệt','code'=>'SV010','initials'=>'TK'],
                ];
                @endphp
                @foreach($students as $i => $sv)
                <tr>
                    <td style="font-weight:600; color:#94a3b8;">{{ $i + 1 }}</td>
                    <td>
                        <div class="att-student-info">
                            <div class="att-student-avatar">{{ $sv['initials'] }}</div>
                            <span class="att-student-name">{{ $sv['name'] }}</span>
                        </div>
                    </td>
                    <td><span class="att-student-code">{{ $sv['code'] }}</span></td>
                    <td>
                        <div class="att-radio-group">
                            <label style="color:#16a34a"><input type="radio" name="att_{{ $sv['code'] }}" value="present" checked> Có mặt</label>
                            <label style="color:#dc2626"><input type="radio" name="att_{{ $sv['code'] }}" value="absent"> Vắng</label>
                            <label style="color:#d97706"><input type="radio" name="att_{{ $sv['code'] }}" value="late"> Muộn</label>
                        </div>
                    </td>
                    <td><input type="text" class="att-note" placeholder="Ghi chú..."></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="att-footer">
            <button class="att-btn-save" style="background:#64748b;">Lưu nháp</button>
            <button class="att-btn-save"><i class="fa-solid fa-check"></i> Xác nhận điểm danh</button>
        </div>
    </div>
</div>
@endsection
