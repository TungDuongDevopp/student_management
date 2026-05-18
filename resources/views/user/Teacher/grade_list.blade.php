@extends('layouts.user.teacher_sidebar')
@section('title', 'Nhập & Cập nhật điểm')
@section('content')
<style>
    .gr-wrapper { max-width:1200px; }
    .page-hero { background:linear-gradient(135deg,#7f1d1d 0%,#dc2626 100%);color:#fff;border-radius:10px;padding:1.5rem 1.75rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;position:relative;overflow:hidden;margin-bottom:1.5rem; }
    .page-hero::after { content:"";position:absolute;top:-80px;right:-60px;width:260px;height:260px;background:rgba(255,255,255,.08);transform:rotate(45deg); }
    .hero-content { position:relative;z-index:1; }
    .hero-eyebrow { font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;opacity:.85;font-weight:700;margin-bottom:.4rem; }
    .hero-title { margin:0;font-size:1.45rem;font-weight:800; }
    .gr-filters { display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; position:relative; z-index:1; }
    .gr-filters select { padding:0.5rem 0.75rem; border:1px solid #e2e8f0; border-radius:8px; font-size:0.85rem; background:#fff; color:#1e293b; }
    .gr-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; }
    .gr-card-header { padding:1rem 1.25rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem; }
    .gr-card-header h2 { font-size:1rem; font-weight:700; color:#1e293b; margin:0; }
    .gr-table { width:100%; border-collapse:collapse; }
    .gr-table th { padding:0.75rem 0.75rem; text-align:center; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; border-bottom:2px solid #e2e8f0; background:#f8fafc; }
    .gr-table th:nth-child(1), .gr-table th:nth-child(2) { text-align:left; }
    .gr-table td { padding:0.6rem 0.75rem; font-size:0.85rem; color:#334155; border-bottom:1px solid #f1f5f9; text-align:center; }
    .gr-table td:nth-child(1), .gr-table td:nth-child(2) { text-align:left; }
    .gr-table tbody tr:hover { background:#f8fafc; }
    .gr-input { width:60px; padding:0.35rem 0.4rem; border:1px solid #e2e8f0; border-radius:6px; font-size:0.82rem; text-align:center; }
    .gr-input:focus { border-color:#2563eb; outline:none; box-shadow:0 0 0 2px rgba(37,99,235,0.15); }
    .gr-avg { font-weight:700; }
    .gr-avg.pass { color:#16a34a; }
    .gr-avg.fail { color:#dc2626; }
    .gr-footer { padding:1rem 1.25rem; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
    .gr-note { font-size:0.8rem; color:#94a3b8; }
    .gr-actions { display:flex; gap:0.75rem; }
    .gr-btn { padding:0.6rem 1.25rem; border:none; border-radius:8px; font-weight:700; font-size:0.85rem; cursor:pointer; transition:all 0.2s; }
    .gr-btn-draft { background:#f1f5f9; color:#475569; }
    .gr-btn-draft:hover { background:#e2e8f0; }
    .gr-btn-save { background:#2563eb; color:#fff; }
    .gr-btn-save:hover { background:#1d4ed8; }
    .gr-btn-lock { background:#16a34a; color:#fff; }
    .gr-btn-lock:hover { background:#15803d; }
</style>

<div class="gr-wrapper">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ route('teacher.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Nhập & Cập nhật điểm</li>
        </ol>
    </nav>
    <div class="page-hero">
        <div class="hero-content">
            <div class="hero-eyebrow">Teacher Academic Portal</div>
            <h1 class="hero-title"><i class="fa-solid fa-marker" style="margin-right:0.5rem;"></i>Nhập & Cập nhật điểm</h1>
        </div>
        <div class="gr-filters">
            <select>
                <option>DCCTTPM70A — Lập trình Web nâng cao</option>
                <option>DCCTTPM70B — Phân tích & thiết kế hệ thống</option>
                <option>DCCNTT69C — Cơ sở dữ liệu</option>
                <option>DCCTTPM71A — Lập trình PHP Laravel</option>
            </select>
            <select>
                <option>Học kỳ 2 — Năm học 2025-2026</option>
                <option>Học kỳ 1 — Năm học 2025-2026</option>
            </select>
        </div>
    </div>

    <div class="gr-card">
        <div class="gr-card-header">
            <h2>DCCTTPM70A — Lập trình Web nâng cao · Sĩ số: 65</h2>
            <span style="font-size:0.8rem; color:#f59e0b; font-weight:600;"><i class="fa-solid fa-circle-exclamation"></i> Chưa chốt điểm</span>
        </div>
        <table class="gr-table">
            <thead>
                <tr>
                    <th style="width:50px">STT</th>
                    <th>Họ và tên</th>
                    <th style="width:80px">Mã SV</th>
                    <th style="width:90px">Điểm C (10%)</th>
                    <th style="width:90px">Điểm B (30%)</th>
                    <th style="width:90px">Điểm A (60%)</th>
                    <th style="width:75px">Tổng kết</th>
                </tr>
            </thead>
            <tbody>
                @php
                $gradeData = [
                    ['name'=>'Nguyễn Văn An','code'=>'SV001','c'=>8,'b'=>6.5,'a'=>7.0],
                    ['name'=>'Trần Thị Bình','code'=>'SV002','c'=>9,'b'=>7.0,'a'=>8.0],
                    ['name'=>'Lê Hoàng Cường','code'=>'SV003','c'=>7,'b'=>5.0,'a'=>4.5],
                    ['name'=>'Phạm Minh Đức','code'=>'SV004','c'=>10,'b'=>8.5,'a'=>9.0],
                    ['name'=>'Hoàng Thị Em','code'=>'SV005','c'=>6,'b'=>4.0,'a'=>3.5],
                    ['name'=>'Vũ Quốc Phong','code'=>'SV006','c'=>8,'b'=>7.0,'a'=>6.5],
                    ['name'=>'Đặng Thị Giang','code'=>'SV007','c'=>9,'b'=>8.0,'a'=>7.5],
                    ['name'=>'Bùi Văn Hải','code'=>'SV008','c'=>7,'b'=>5.5,'a'=>6.0],
                    ['name'=>'Ngô Thị Lan','code'=>'SV009','c'=>10,'b'=>9.0,'a'=>8.5],
                    ['name'=>'Dương Tuấn Kiệt','code'=>'SV010','c'=>5,'b'=>3.5,'a'=>3.0],
                ];
                @endphp
                @foreach($gradeData as $idx => $sv)
                @php
                    $avg = round($sv['c']*0.1 + $sv['b']*0.3 + $sv['a']*0.6, 1);
                    $pass = $avg >= 4.0;
                @endphp
                <tr>
                    <td style="font-weight:600; color:#94a3b8;">{{ $idx + 1 }}</td>
                    <td style="font-weight:600; color:#1e293b;">{{ $sv['name'] }}</td>
                    <td style="font-weight:600;">{{ $sv['code'] }}</td>
                    <td><input type="number" class="gr-input" value="{{ $sv['c'] }}" min="0" max="10" step="0.5"></td>
                    <td><input type="number" class="gr-input" value="{{ $sv['b'] }}" min="0" max="10" step="0.5"></td>
                    <td><input type="number" class="gr-input" value="{{ $sv['a'] }}" min="0" max="10" step="0.5"></td>
                    <td class="gr-avg {{ $pass ? 'pass' : 'fail' }}">{{ $avg }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="gr-footer">
            <span class="gr-note"><i class="fa-solid fa-info-circle"></i> Điểm C = Chuyên cần/Quá trình · Điểm B = Giữa kỳ · Điểm A = Cuối kỳ thi</span>
            <div class="gr-actions">
                <button class="gr-btn gr-btn-draft"><i class="fa-solid fa-floppy-disk"></i> Lưu nháp</button>
                <button class="gr-btn gr-btn-save"><i class="fa-solid fa-check"></i> Lưu điểm</button>
                <button class="gr-btn gr-btn-lock"><i class="fa-solid fa-lock"></i> Chốt điểm</button>
            </div>
        </div>
    </div>
</div>
@endsection
