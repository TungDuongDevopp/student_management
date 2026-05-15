@extends('layouts.user.teacher_sidebar')
@section('title', 'Danh sách Sinh viên')
@section('content')
<style>
    .sl-wrapper { max-width:1200px; }
    .sl-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
    .sl-header h1 { font-size:1.5rem; font-weight:800; color:#0f172a; margin:0; }
    .sl-filters { display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; }
    .sl-filters select, .sl-filters input { padding:0.5rem 0.75rem; border:1px solid #e2e8f0; border-radius:8px; font-size:0.85rem; background:#fff; color:#1e293b; }
    .sl-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; }
    .sl-card-header { padding:1rem 1.25rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
    .sl-card-header h2 { font-size:1rem; font-weight:700; color:#1e293b; margin:0; }
    .sl-stats { display:flex; gap:0.75rem; }
    .sl-stat { font-size:0.78rem; padding:0.3rem 0.65rem; border-radius:6px; font-weight:600; background:#eff6ff; color:#2563eb; }
    .sl-table { width:100%; border-collapse:collapse; }
    .sl-table th { padding:0.75rem 1rem; text-align:left; font-size:0.78rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; border-bottom:2px solid #e2e8f0; background:#f8fafc; }
    .sl-table td { padding:0.65rem 1rem; font-size:0.88rem; color:#334155; border-bottom:1px solid #f1f5f9; }
    .sl-table tbody tr:hover { background:#f8fafc; }
    .sl-avatar { width:32px; height:32px; border-radius:50%; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; }
    .sl-name-cell { display:flex; align-items:center; gap:0.75rem; }
    .sl-name { font-weight:600; color:#1e293b; }
    .sl-email { font-size:0.78rem; color:#94a3b8; }
    .sl-badge-active { font-size:0.72rem; padding:0.2rem 0.5rem; border-radius:4px; font-weight:600; background:#dcfce7; color:#166534; }
</style>

<div class="sl-wrapper">
    <div class="sl-header">
        <h1><i class="fa-solid fa-users-viewfinder" style="color:#2563eb; margin-right:0.5rem;"></i>Danh sách Sinh viên</h1>
        <div class="sl-filters">
            <select>
                <option>DCCTTPM70A — Lập trình Web nâng cao</option>
                <option>DCCTTPM70B — Phân tích & thiết kế hệ thống</option>
                <option>DCCNTT69C — Cơ sở dữ liệu</option>
                <option>DCCTTPM71A — Lập trình PHP Laravel</option>
            </select>
            <input type="text" placeholder="Tìm sinh viên..." style="width:200px;">
        </div>
    </div>

    <div class="sl-card">
        <div class="sl-card-header">
            <h2>DCCTTPM70A — Lập trình Web nâng cao</h2>
            <div class="sl-stats">
                <span class="sl-stat"><i class="fa-solid fa-users"></i> Tổng: 65</span>
                <span class="sl-stat" style="background:#dcfce7; color:#166534;">Đang học: 65</span>
            </div>
        </div>
        <table class="sl-table">
            <thead>
                <tr>
                    <th style="width:50px">STT</th>
                    <th>Họ và tên</th>
                    <th>Mã SV</th>
                    <th>Email</th>
                    <th>Lớp hành chính</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @php
                $students = [
                    ['name'=>'Nguyễn Văn An','code'=>'SV001','email'=>'sv001@student.humg.edu.vn','class'=>'DCCNTT70A','i'=>'NA'],
                    ['name'=>'Trần Thị Bình','code'=>'SV002','email'=>'sv002@student.humg.edu.vn','class'=>'DCCNTT70A','i'=>'TB'],
                    ['name'=>'Lê Hoàng Cường','code'=>'SV003','email'=>'sv003@student.humg.edu.vn','class'=>'DCCTPM70A','i'=>'LC'],
                    ['name'=>'Phạm Minh Đức','code'=>'SV004','email'=>'sv004@student.humg.edu.vn','class'=>'DCCTPM70A','i'=>'MĐ'],
                    ['name'=>'Hoàng Thị Em','code'=>'SV005','email'=>'sv005@student.humg.edu.vn','class'=>'DCCNTT70B','i'=>'HE'],
                    ['name'=>'Vũ Quốc Phong','code'=>'SV006','email'=>'sv006@student.humg.edu.vn','class'=>'DCCNTT70A','i'=>'QP'],
                    ['name'=>'Đặng Thị Giang','code'=>'SV007','email'=>'sv007@student.humg.edu.vn','class'=>'DCCTPM70A','i'=>'TG'],
                    ['name'=>'Bùi Văn Hải','code'=>'SV008','email'=>'sv008@student.humg.edu.vn','class'=>'DCCNTT70B','i'=>'VH'],
                    ['name'=>'Ngô Thị Lan','code'=>'SV009','email'=>'sv009@student.humg.edu.vn','class'=>'DCCTPM70A','i'=>'TL'],
                    ['name'=>'Dương Tuấn Kiệt','code'=>'SV010','email'=>'sv010@student.humg.edu.vn','class'=>'DCCNTT70A','i'=>'TK'],
                ];
                @endphp
                @foreach($students as $idx => $sv)
                <tr>
                    <td style="font-weight:600; color:#94a3b8;">{{ $idx + 1 }}</td>
                    <td>
                        <div class="sl-name-cell">
                            <div class="sl-avatar">{{ $sv['i'] }}</div>
                            <span class="sl-name">{{ $sv['name'] }}</span>
                        </div>
                    </td>
                    <td style="font-weight:600;">{{ $sv['code'] }}</td>
                    <td><span class="sl-email">{{ $sv['email'] }}</span></td>
                    <td>{{ $sv['class'] }}</td>
                    <td><span class="sl-badge-active">Đang học</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
