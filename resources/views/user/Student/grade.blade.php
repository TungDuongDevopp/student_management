@extends('layouts.user.student_sidebar')
@section('title', 'Tra cứu Điểm')
@section('content')
<style>
.grade-wrap { width: 100%; max-width: 100%; padding: 0 1rem; box-sizing: border-box; }
.page-hero { background:linear-gradient(135deg,#1e40af 0%,#3b82f6 100%);color:#fff;border-radius:10px;padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;position:relative;overflow:hidden;margin-bottom:1.5rem;border:1px solid #1d4ed8; }
.page-hero::after { content:"";position:absolute;top:-80px;right:-60px;width:260px;height:260px;background:rgba(255,255,255,.08);transform:rotate(45deg); }
.hero-content { position:relative;z-index:1; }
.hero-eyebrow { font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;opacity:.85;font-weight:700;margin-bottom:.3rem; }
.hero-title { margin:0;font-size:1.35rem;font-weight:800; }
.hero-desc { margin:.3rem 0 0;font-size:.85rem;opacity:.9; }
.sem-badge { background:#fff;color:#1d4ed8;border-radius:6px;padding:.35rem .75rem;font-size:.75rem;font-weight:700;position:relative;z-index:1; }
.gpa-banner { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
.gpa-box { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.1rem 1.25rem; }
.gpa-box .gval { font-size:2rem; font-weight:800; line-height:1; }
.gpa-box .glbl { font-size:0.78rem; color:#64748b; margin-top:0.3rem; }
.gpa-box.gpa .gval { color:#2563eb; }
.gpa-box.pass .gval { color:#16a34a; }
.gpa-box.fail .gval { color:#ef4444; }
.gpa-box.rank .gval { font-size:1.2rem; color:#7c3aed; }

.grade-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:1.25rem; }
.grade-card-head { padding:0.85rem 1.25rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
.grade-card-head h3 { font-size:0.95rem; font-weight:700; color:#1e293b; margin:0; }
.gtable { width:100%; border-collapse:collapse; }
.gtable th { padding:0.65rem 1rem; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; border-bottom:2px solid #e2e8f0; background:#f8fafc; text-align:center; }
.gtable th:first-child { text-align:left; }
.gtable td { padding:0.65rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9; text-align:center; color:#334155; }
.gtable td:first-child { text-align:left; font-weight:600; color:#1e293b; }
.gtable tbody tr:hover { background:#f8fafc; }
.grade-letter { font-size:0.9rem; font-weight:800; padding:0.2rem 0.5rem; border-radius:6px; }
.A { background:#dcfce7; color:#166534; }
.B { background:#dbeafe; color:#1d4ed8; }
.C { background:#fef3c7; color:#92400e; }
.D { background:#fee2e2; color:#991b1b; }
.F { background:#fecaca; color:#7f1d1d; }
.progress-mini { width:80px; height:6px; background:#e2e8f0; border-radius:3px; display:inline-block; overflow:hidden; }
.progress-fill { height:100%; border-radius:3px; }
</style>

@php
$semesters = [
    [
        'name' => 'Học kỳ 2 - 2025-2026',
        'subjects' => [
            ['IT3010','Lập trình Web nâng cao',3, 9.0, 8.5, 7.0, 8.0],
            ['IT3020','Cơ sở dữ liệu nâng cao',3, 8.5, 9.0, 7.5, 8.5],
            ['IT3030','Kiến trúc máy tính',2,  7.0, 7.5, 6.5, 7.0],
        ]
    ],
    [
        'name' => 'Học kỳ 1 - 2025-2026',
        'subjects' => [
            ['IT2010','Lập trình Hướng đối tượng',3, 8.0, 8.0, 7.5, 8.0],
            ['IT2020','Cấu trúc dữ liệu & Giải thuật',4, 9.5, 9.0, 8.5, 9.0],
            ['IT2030','Mạng máy tính',3, 7.0, 6.5, 5.5, 6.0],
            ['IT2040','Xác suất thống kê',2, 6.0, 7.0, 5.0, 5.5],
        ]
    ],
];

function getLetterGrade($gpa) {
    if ($gpa >= 8.5) return 'A';
    if ($gpa >= 7.0) return 'B';
    if ($gpa >= 5.5) return 'C';
    if ($gpa >= 4.0) return 'D';
    return 'F';
}

function calcAvg($s) {
    return round($s[3]*0.1 + $s[4]*0.1 + $s[5]*0.3 + $s[6]*0.5, 2);
}

$allGrades = [];
foreach($semesters as $sem) {
    foreach($sem['subjects'] as $s) {
        $avg = calcAvg($s);
        $allGrades[] = ['avg' => $avg, 'credits' => $s[2]];
    }
}
$totalCreditsMock = array_sum(array_column($allGrades, 'credits'));
$weightedSumMock = array_sum(array_map(fn($g) => $g['avg'] * $g['credits'], $allGrades));
$gpa10Mock = $totalCreditsMock > 0 ? round($weightedSumMock / $totalCreditsMock, 2) : 0;
$passCount = count(array_filter($allGrades, fn($g) => $g['avg'] >= 4.0));
$failCount = count($allGrades) - $passCount;
$rankMock = $gpa10Mock >= 8.5 ? 'Xuất sắc' : ($gpa10Mock >= 7.0 ? 'Giỏi' : ($gpa10Mock >= 5.5 ? 'Khá' : ($gpa10Mock >= 4.0 ? 'Trung bình' : 'Yếu')));
@endphp

<div class="grade-wrap">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Tra cứu điểm</li>
        </ol>
    </nav>
    <section class="page-hero">
        <div class="hero-content">
            <div class="hero-eyebrow">Student Academic Portal</div>
            <h1 class="hero-title"><i class="fa-solid fa-chart-bar" style="margin-right:8px;"></i>TRA CỨU ĐIỂM</h1>
            <p class="hero-desc">Xem bảng điểm chi tiết, điểm trung bình tích lũy học kỳ và toàn khóa học</p>
        </div>
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <select style="padding:0.45rem 0.75rem; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.15); color:#fff; border-radius:8px; font-size:0.82rem; font-weight:600; outline:none; cursor:pointer;">
                <option style="color:#334155;">Tất cả học kỳ</option>
                <option style="color:#334155;">HK2 - 2025-2026</option>
                <option style="color:#334155;">HK1 - 2025-2026</option>
            </select>
            <span class="sem-badge">Học Kỳ Kỳ này – Đang mở</span>
        </div>
    </section>

    <!-- GPA Summary -->
    <div class="gpa-banner">
        <div class="gpa-box gpa"><div class="gval">{{ $gpa ?? '0.00' }}</div><div class="glbl">GPA (Hệ 4)</div></div>
        <div class="gpa-box pass"><div class="gval">{{ $passCount }}</div><div class="glbl">Môn đạt</div></div>
        <div class="gpa-box fail"><div class="gval">{{ $failCount }}</div><div class="glbl">Môn cần thi lại</div></div>
        <div class="gpa-box rank"><div class="gval">{{ $ranking ?? $rankMock }}</div><div class="glbl">Xếp loại học lực</div></div>
    </div>

    @if($failCount > 0)
    <div style="background:#fee2e2; border:1px solid #fca5a5; border-radius:10px; padding:0.75rem 1rem; margin-bottom:1rem; font-size:0.85rem; color:#991b1b; font-weight:600;">
        <i class="fa-solid fa-triangle-exclamation"></i> Bạn có {{ $failCount }} môn dưới 4.0 điểm cần thi lại. Liên hệ Phòng đào tạo để đăng ký thi cải thiện.
    </div>
    @endif

    @foreach($semesters as $sem)
    @php
        $semCredits = array_sum(array_column($sem['subjects'], 2));
        $semWeighted = array_sum(array_map(fn($s) => calcAvg($s) * $s[2], $sem['subjects']));
        $semGpa = $semCredits > 0 ? round($semWeighted/$semCredits, 2) : 0;
    @endphp
    <div class="grade-card">
        <div class="grade-card-head">
            <h3><i class="fa-regular fa-calendar" style="color:#2563eb; margin-right:6px;"></i>{{ $sem['name'] }}</h3>
            <div style="display:flex; gap:1rem; align-items:center;">
                <span style="font-size:0.8rem; color:#64748b;">GPA kỳ: <b style="color:#2563eb;">{{ $semGpa }}</b></span>
                <span style="font-size:0.8rem; color:#64748b;">Tổng tín: <b>{{ $semCredits }}</b></span>
            </div>
        </div>
        <table class="gtable">
            <thead>
                <tr>
                    <th>Môn học</th>
                    <th>Tín</th>
                    <th>CC (10%)</th>
                    <th>BT (10%)</th>
                    <th>GK (30%)</th>
                    <th>CK (50%)</th>
                    <th>Điểm TB</th>
                    <th>Xếp loại</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sem['subjects'] as $s)
                @php
                    $avg = calcAvg($s);
                    $letter = getLetterGrade($avg);
                    $pct = min($avg/10*100, 100);
                    $color = $avg>=8.5?'#16a34a':($avg>=7?'#2563eb':($avg>=5.5?'#f59e0b':'#ef4444'));
                @endphp
                <tr>
                    <td>
                        <div>{{ $s[1] }}</div>
                        <div style="font-size:0.72rem; color:#94a3b8; font-weight:400;">{{ $s[0] }}</div>
                    </td>
                    <td>{{ $s[2] }}</td>
                    <td>{{ $s[3] }}</td>
                    <td>{{ $s[4] }}</td>
                    <td>{{ $s[5] }}</td>
                    <td style="font-weight:700;">{{ $s[6] }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px; justify-content:center;">
                            <b style="color:{{ $color }};">{{ $avg }}</b>
                            <div class="progress-mini"><div class="progress-fill" style="width:{{$pct}}%; background:{{$color}};"></div></div>
                        </div>
                    </td>
                    <td><span class="grade-letter {{ $letter }}">{{ $letter }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

    <!-- Logic tính điểm -->
    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:1rem 1.25rem;">
        <h4 style="font-size:0.85rem; font-weight:700; color:#1e293b; margin:0 0 0.75rem;"><i class="fa-solid fa-circle-info" style="color:#2563eb;"></i> Công thức tính điểm</h4>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; font-size:0.78rem; color:#475569;">
            <div><b>Điểm TB</b> = CC×10% + BT×10% + GK×30% + CK×50%</div>
            <div><b>A</b> ≥ 8.5 · <b>B</b> ≥ 7.0 · <b>C</b> ≥ 5.5 · <b>D</b> ≥ 4.0 · <b>F</b> &lt; 4.0</div>
            <div><b>GPA</b> = Σ(Điểm TB × Tín chỉ) ÷ Tổng tín chỉ</div>
        </div>
    </div>
</div>
@endsection
