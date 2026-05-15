@extends('layouts.user.teacher_sidebar')
@section('title', 'Nhập & Cập nhật điểm')
@section('content')
<style>
    .gr-wrapper { max-width:1200px; }
    .gr-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
    .gr-header h1 { font-size:1.5rem; font-weight:800; color:#0f172a; margin:0; }
    .gr-filters { display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; }
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
    <div class="gr-header">
        <h1><i class="fa-solid fa-marker" style="color:#2563eb; margin-right:0.5rem;"></i>Nhập & Cập nhật điểm</h1>
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
                    <th style="width:70px">CC (10%)</th>
                    <th style="width:70px">BT (10%)</th>
                    <th style="width:70px">GK (30%)</th>
                    <th style="width:70px">CK (50%)</th>
                    <th style="width:75px">TB (10)</th>
                </tr>
            </thead>
            <tbody>
                @php
                $gradeData = [
                    ['name'=>'Nguyễn Văn An','code'=>'SV001','cc'=>8,'bt'=>7,'gk'=>6.5,'ck'=>7.0],
                    ['name'=>'Trần Thị Bình','code'=>'SV002','cc'=>9,'bt'=>8.5,'gk'=>7.0,'ck'=>8.0],
                    ['name'=>'Lê Hoàng Cường','code'=>'SV003','cc'=>7,'bt'=>6,'gk'=>5.0,'ck'=>4.5],
                    ['name'=>'Phạm Minh Đức','code'=>'SV004','cc'=>10,'bt'=>9,'gk'=>8.5,'ck'=>9.0],
                    ['name'=>'Hoàng Thị Em','code'=>'SV005','cc'=>6,'bt'=>5,'gk'=>4.0,'ck'=>3.5],
                    ['name'=>'Vũ Quốc Phong','code'=>'SV006','cc'=>8,'bt'=>7.5,'gk'=>7.0,'ck'=>6.5],
                    ['name'=>'Đặng Thị Giang','code'=>'SV007','cc'=>9,'bt'=>8,'gk'=>8.0,'ck'=>7.5],
                    ['name'=>'Bùi Văn Hải','code'=>'SV008','cc'=>7,'bt'=>6.5,'gk'=>5.5,'ck'=>6.0],
                    ['name'=>'Ngô Thị Lan','code'=>'SV009','cc'=>10,'bt'=>9.5,'gk'=>9.0,'ck'=>8.5],
                    ['name'=>'Dương Tuấn Kiệt','code'=>'SV010','cc'=>5,'bt'=>4,'gk'=>3.5,'ck'=>3.0],
                ];
                @endphp
                @foreach($gradeData as $idx => $sv)
                @php
                    $avg = round($sv['cc']*0.1 + $sv['bt']*0.1 + $sv['gk']*0.3 + $sv['ck']*0.5, 1);
                    $pass = $avg >= 4.0;
                @endphp
                <tr>
                    <td style="font-weight:600; color:#94a3b8;">{{ $idx + 1 }}</td>
                    <td style="font-weight:600; color:#1e293b;">{{ $sv['name'] }}</td>
                    <td style="font-weight:600;">{{ $sv['code'] }}</td>
                    <td><input type="number" class="gr-input" value="{{ $sv['cc'] }}" min="0" max="10" step="0.5"></td>
                    <td><input type="number" class="gr-input" value="{{ $sv['bt'] }}" min="0" max="10" step="0.5"></td>
                    <td><input type="number" class="gr-input" value="{{ $sv['gk'] }}" min="0" max="10" step="0.5"></td>
                    <td><input type="number" class="gr-input" value="{{ $sv['ck'] }}" min="0" max="10" step="0.5"></td>
                    <td class="gr-avg {{ $pass ? 'pass' : 'fail' }}">{{ $avg }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="gr-footer">
            <span class="gr-note"><i class="fa-solid fa-info-circle"></i> CC = Chuyên cần · BT = Bài tập · GK = Giữa kỳ · CK = Cuối kỳ</span>
            <div class="gr-actions">
                <button class="gr-btn gr-btn-draft"><i class="fa-solid fa-floppy-disk"></i> Lưu nháp</button>
                <button class="gr-btn gr-btn-save"><i class="fa-solid fa-check"></i> Lưu điểm</button>
                <button class="gr-btn gr-btn-lock"><i class="fa-solid fa-lock"></i> Chốt điểm</button>
            </div>
        </div>
    </div>
</div>
@endsection
