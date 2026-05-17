@extends('layouts.user.student_sidebar')
@section('title', 'Kết quả Điểm danh')
@section('content')
<style>
.att-wrap { width: 100%; max-width: 100%; padding: 0 1rem; box-sizing: border-box; }
.page-hero { background:linear-gradient(135deg,#1e40af 0%,#3b82f6 100%);color:#fff;border-radius:10px;padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;position:relative;overflow:hidden;margin-bottom:1.5rem;border:1px solid #1d4ed8; }
.page-hero::after { content:"";position:absolute;top:-80px;right:-60px;width:260px;height:260px;background:rgba(255,255,255,.08);transform:rotate(45deg); }
.hero-content { position:relative;z-index:1; }
.hero-eyebrow { font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;opacity:.85;font-weight:700;margin-bottom:.3rem; }
.hero-title { margin:0;font-size:1.35rem;font-weight:800; }
.hero-desc { margin:.3rem 0 0;font-size:.85rem;opacity:.9; }
.sem-badge { background:#fff;color:#1d4ed8;border-radius:6px;padding:.35rem .75rem;font-size:.75rem;font-weight:700;position:relative;z-index:1; }
.att-summary { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
.att-sbox { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1rem 1.25rem; }
.att-sbox .val { font-size:1.8rem; font-weight:800; }
.att-sbox .lbl { font-size:0.75rem; color:#64748b; margin-top:0.3rem; }
.att-sbox.present .val { color:#16a34a; }
.att-sbox.absent .val { color:#ef4444; }
.att-sbox.rate .val { color:#2563eb; }
.att-sbox.warn .val { color:#f59e0b; }

.att-subject-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:1rem; }
.att-subject-head { padding:0.85rem 1.25rem; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
.att-subject-head h3 { font-size:0.95rem; font-weight:700; color:#1e293b; margin:0; }
.att-bar-wrap { padding:0.85rem 1.25rem; }
.att-progress { height:12px; background:#e2e8f0; border-radius:6px; overflow:hidden; margin:0.5rem 0; }
.att-progress-fill { height:100%; border-radius:6px; transition:width 0.3s; }
.att-row { display:flex; justify-content:space-between; font-size:0.78rem; color:#64748b; }
.att-detail-table { width:100%; border-collapse:collapse; font-size:0.82rem; }
.att-detail-table th { padding:0.5rem 1rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; font-size:0.72rem; font-weight:700; color:#64748b; text-align:left; }
.att-detail-table td { padding:0.5rem 1rem; border-bottom:1px solid #f1f5f9; color:#334155; }
.status-badge { display:inline-block; padding:0.15rem 0.5rem; border-radius:4px; font-size:0.72rem; font-weight:700; }
.s-present { background:#dcfce7; color:#166534; }
.s-absent { background:#fee2e2; color:#991b1b; }
.s-late { background:#fef3c7; color:#92400e; }
.warn-alert { background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:0.65rem 1rem; margin:0 1.25rem 0.85rem; font-size:0.82rem; color:#991b1b; font-weight:600; }
.ok-alert { background:#dcfce7; border:1px solid #86efac; border-radius:8px; padding:0.65rem 1rem; margin:0 1.25rem 0.85rem; font-size:0.82rem; color:#166534; font-weight:600; }
</style>

@php
$subjects_att = [
    [
        'code' => 'IT3010', 'name' => 'Lập trình Web nâng cao',
        'teacher' => 'Ngô Ngọc Anh', 'room' => 'C301',
        'schedule' => 'Thứ 2 - Tiết 1-3',
        'total' => 15, 'present' => 14, 'absent' => 1, 'late' => 0,
        'sessions' => [
            ['05/05/2026','Tiết 1-3','Có mặt'],
            ['12/05/2026','Tiết 1-3','Có mặt'],
            ['19/05/2026','Tiết 1-3','Vắng'],
        ]
    ],
    [
        'code' => 'IT3020', 'name' => 'Cơ sở dữ liệu nâng cao',
        'teacher' => 'Trần Thị Thu Hà', 'room' => 'B202',
        'schedule' => 'Thứ 4 - Tiết 1-3',
        'total' => 15, 'present' => 11, 'absent' => 3, 'late' => 1,
        'sessions' => [
            ['07/05/2026','Tiết 1-3','Có mặt'],
            ['14/05/2026','Tiết 1-3','Vắng'],
            ['21/05/2026','Tiết 1-3','Muộn'],
        ]
    ],
    [
        'code' => 'IT3030', 'name' => 'Kiến trúc máy tính',
        'teacher' => 'Lê Minh Đức', 'room' => 'A105',
        'schedule' => 'Thứ 3 - Tiết 4-6',
        'total' => 10, 'present' => 6, 'absent' => 4, 'late' => 0,
        'sessions' => [
            ['06/05/2026','Tiết 4-6','Vắng'],
            ['13/05/2026','Tiết 4-6','Có mặt'],
        ]
    ],
];
@endphp

<div class="att-wrap">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Kết quả điểm danh</li>
        </ol>
    </nav>
    <section class="page-hero">
        <div class="hero-content">
            <div class="hero-eyebrow">Student Academic Portal</div>
            <h1 class="hero-title"><i class="fa-solid fa-clipboard-check" style="margin-right:8px;"></i>KẾT QUẢ ĐIỂM DANH</h1>
            <p class="hero-desc">Theo dõi chi tiết số buổi đi học, số buổi vắng và tỷ lệ chuyên cần kỳ học này</p>
        </div>
        <span class="sem-badge">Học Kỳ Kỳ này – Đang mở</span>
    </section>

    <!-- Tổng quan -->
    <div class="att-summary">
        <div class="att-sbox present"><div class="val">31</div><div class="lbl">Buổi có mặt</div></div>
        <div class="att-sbox absent"><div class="val">8</div><div class="lbl">Buổi vắng mặt</div></div>
        <div class="att-sbox rate"><div class="val">79%</div><div class="lbl">Tỷ lệ có mặt TB</div></div>
        <div class="att-sbox warn"><div class="val">2</div><div class="lbl">Môn cảnh báo nghỉ</div></div>
    </div>

    @foreach($subjects_att as $s)
    @php
        $attendRate = $s['total'] > 0 ? round($s['present']/$s['total']*100) : 100;
        $allowedAbsent = floor($s['total'] * 0.2);
        $isWarn = $s['absent'] >= $allowedAbsent;
        $isDanger = $s['absent'] > $allowedAbsent;
        $color = $isDanger ? '#ef4444' : ($isWarn ? '#f59e0b' : '#16a34a');
    @endphp
    <div class="att-subject-card">
        <div class="att-subject-head">
            <div>
                <h3>{{ $s['name'] }}</h3>
                <div style="font-size:0.78rem; color:#64748b; margin-top:2px;">
                    {{ $s['code'] }} · GV: {{ $s['teacher'] }} · {{ $s['schedule'] }} · P.{{ $s['room'] }}
                </div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:1.4rem; font-weight:800; color:{{ $color }};">{{ $attendRate }}%</div>
                <div style="font-size:0.72rem; color:#94a3b8;">tỷ lệ có mặt</div>
            </div>
        </div>

        @if($isDanger)
        <div class="warn-alert">
            <i class="fa-solid fa-ban"></i> NGUY HIỂM! Bạn đã vắng {{ $s['absent'] }}/{{ $allowedAbsent }} buổi tối đa. Có thể bị cấm thi! Liên hệ giảng viên ngay.
        </div>
        @elseif($isWarn)
        <div class="warn-alert" style="background:#fef3c7; border-color:#fcd34d; color:#92400e;">
            <i class="fa-solid fa-triangle-exclamation"></i> Cảnh báo: Bạn đã vắng {{ $s['absent'] }} buổi. Số buổi tối đa được phép vắng là {{ $allowedAbsent }} buổi (20%).
        </div>
        @else
        <div class="ok-alert">
            <i class="fa-solid fa-circle-check"></i> Tốt! Bạn đã vắng {{ $s['absent'] }}/{{ $allowedAbsent }} buổi được phép. Còn {{ $allowedAbsent - $s['absent'] }} buổi dự phòng.
        </div>
        @endif

        <div class="att-bar-wrap">
            <div class="att-row">
                <span>Có mặt: <b style="color:#16a34a;">{{ $s['present'] }}</b></span>
                <span>Vắng: <b style="color:#ef4444;">{{ $s['absent'] }}</b></span>
                <span>Muộn: <b style="color:#f59e0b;">{{ $s['late'] }}</b></span>
                <span>Tổng: <b>{{ $s['total'] }}</b> buổi</span>
                <span>Phép vắng tối đa: <b>{{ $allowedAbsent }}</b> buổi</span>
            </div>
            <div class="att-progress">
                <div class="att-progress-fill" style="width:{{ $attendRate }}%; background:{{ $color }};"></div>
            </div>
        </div>

        <details style="margin:0 1.25rem 0.85rem; cursor:pointer;">
            <summary style="font-size:0.82rem; font-weight:600; color:#2563eb; list-style:none; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i> Xem chi tiết buổi học
            </summary>
            <table class="att-detail-table" style="margin-top:0.5rem;">
                <thead>
                    <tr><th>Ngày</th><th>Buổi học</th><th>Trạng thái</th></tr>
                </thead>
                <tbody>
                    @foreach($s['sessions'] as $sess)
                    <tr>
                        <td>{{ $sess[0] }}</td>
                        <td>{{ $sess[1] }}</td>
                        <td>
                            @if($sess[2]=='Có mặt')
                                <span class="status-badge s-present">✓ Có mặt</span>
                            @elseif($sess[2]=='Vắng')
                                <span class="status-badge s-absent">✗ Vắng</span>
                            @else
                                <span class="status-badge s-late">⚠ Muộn</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </details>
    </div>
    @endforeach
</div>
@endsection
