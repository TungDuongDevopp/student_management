@extends('layouts.user.student_sidebar')
@section('title', 'Tra cứu Điểm')
@section('content')
<style>
.grade-wrap { width: 100%; box-sizing: border-box; }
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
    $passCount = 0;
    $failCount = 0;
    
    foreach($enrollments as $e) {
        if ($e->grade && $e->grade->final_score !== null) {
            if ($e->grade->final_score >= 4.0) {
                $passCount++;
            } else {
                $failCount++;
            }
        }
    }

    if (!function_exists('getLetterGrade')) {
        function getLetterGrade($score) {
            if ($score === null) return '—';
            if ($score >= 8.5) return 'A';
            if ($score >= 7.0) return 'B';
            if ($score >= 5.5) return 'C';
            if ($score >= 4.0) return 'D';
            return 'F';
        }
    }
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
            <select id="semesterFilter" style="padding:0.45rem 0.75rem; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.15); color:#fff; border-radius:8px; font-size:0.82rem; font-weight:600; outline:none; cursor:pointer;" onchange="filterSemester(this.value)">
                <option value="all" style="color:#334155;">Tất cả học kỳ</option>
                @foreach($enrollmentsBySemester as $semName => $sems)
                    <option value="{{ md5($semName) }}" style="color:#334155;">{{ $semName }}</option>
                @endforeach
            </select>
            <span class="sem-badge">Học Kỳ Hiện Tại</span>
        </div>
    </section>

    <!-- GPA Summary -->
    <div class="gpa-banner">
        <div class="gpa-box gpa"><div class="gval">{{ $gpa ?? '0.00' }}</div><div class="glbl">GPA (Hệ 4)</div></div>
        <div class="gpa-box pass"><div class="gval">{{ $passCount }}</div><div class="glbl">Môn đạt</div></div>
        <div class="gpa-box fail"><div class="gval">{{ $failCount }}</div><div class="glbl">Môn cần thi lại</div></div>
        <div class="gpa-box rank"><div class="gval">{{ $ranking ?? 'Khác' }}</div><div class="glbl">Xếp loại học lực</div></div>
    </div>

    @if($failCount > 0)
    <div style="background:#fee2e2; border:1px solid #fca5a5; border-radius:10px; padding:0.75rem 1rem; margin-bottom:1rem; font-size:0.85rem; color:#991b1b; font-weight:600;">
        <i class="fa-solid fa-triangle-exclamation"></i> Bạn có {{ $failCount }} môn học chưa đạt cần thi lại. Liên hệ Phòng đào tạo để đăng ký thi cải thiện.
    </div>
    @endif

    @if($enrollmentsBySemester->count() > 0)
        @foreach($enrollmentsBySemester as $semName => $semEnrollments)
            @php
                $semCredits = $semEnrollments->sum(function($e) {
                    return $e->schedule->subject->credits ?? 0;
                });
                
                $semWeighted = 0;
                $semCreditsWithGrades = 0;
                foreach ($semEnrollments as $e) {
                    if ($e->grade && $e->grade->final_score !== null && ($e->schedule->subject->credits ?? 0) > 0) {
                        $semWeighted += $e->grade->final_score * $e->schedule->subject->credits;
                        $semCreditsWithGrades += $e->schedule->subject->credits;
                    }
                }
                $semGpa = $semCreditsWithGrades > 0 ? round($semWeighted / $semCreditsWithGrades, 2) : 0;
            @endphp
            <div class="grade-card semester-card" id="sem-{{ md5($semName) }}">
                <div class="grade-card-head">
                    <h3><i class="fa-regular fa-calendar" style="color:#2563eb; margin-right:6px;"></i>{{ $semName }}</h3>
                    <div style="display:flex; gap:1rem; align-items:center;">
                        <span style="font-size:0.8rem; color:#64748b;">GPA kỳ (Hệ 10): <b style="color:#2563eb;">{{ number_format($semGpa, 2) }}</b></span>
                        <span style="font-size:0.8rem; color:#64748b;">Tổng tín: <b>{{ $semCredits }}</b></span>
                    </div>
                </div>
                <table class="gtable">
                    <thead>
                        <tr>
                            <th>Môn học</th>
                            <th style="width: 80px;">Tín chỉ</th>
                            <th style="width: 140px;">Điểm C (10%)</th>
                            <th style="width: 140px;">Điểm B (30%)</th>
                            <th style="width: 140px;">Điểm A (60%)</th>
                            <th style="width: 180px;">Điểm TB</th>
                            <th style="width: 100px;">Xếp loại</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($semEnrollments as $e)
                            @php
                                $subName = $e->schedule->subject->name ?? '—';
                                $subCode = $e->schedule->subject->code ?? $e->schedule->subject->id ?? '—';
                                $credits = $e->schedule->subject->credits ?? 0;
                                $scoreC = $e->grade->score_c ?? null;
                                $scoreB = $e->grade->score_b ?? null;
                                $scoreA = $e->grade->score_a ?? null;
                                $final = $e->grade->final_score ?? null;
                                
                                $letter = getLetterGrade($final);
                                $pct = $final !== null ? min($final/10*100, 100) : 0;
                                $color = $final !== null ? ($final>=8.5?'#16a34a':($final>=7?'#2563eb':($final>=5.5?'#f59e0b':'#ef4444'))) : '#cbd5e1';
                            @endphp
                            <tr>
                                <td>
                                    <div>{{ $subName }}</div>
                                    <div style="font-size:0.72rem; color:#94a3b8; font-weight:400;">{{ $subCode }}</div>
                                </td>
                                <td>{{ $credits }}</td>
                                <td>{{ $scoreC !== null ? number_format($scoreC, 1) : '—' }}</td>
                                <td>{{ $scoreB !== null ? number_format($scoreB, 1) : '—' }}</td>
                                <td style="font-weight:700;">{{ $scoreA !== null ? number_format($scoreA, 1) : '—' }}</td>
                                <td>
                                    @if($final !== null)
                                        <div style="display:flex; align-items:center; gap:8px; justify-content:center;">
                                            <b style="color:{{ $color }};">{{ number_format($final, 2) }}</b>
                                            <div class="progress-mini"><div class="progress-fill" style="width:{{$pct}}%; background:{{$color}};"></div></div>
                                        </div>
                                    @else
                                        <span style="color: #94a3b8;">Chưa nhập điểm</span>
                                    @endif
                                </td>
                                <td><span class="grade-letter {{ $letter }}">{{ $letter }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <div class="grade-card" style="padding: 3rem 1.5rem; text-align: center; color: #94a3b8;">
            <i class="fa-regular fa-folder-open" style="font-size: 2.5rem; margin-bottom: 1rem; color: #cbd5e1; display: block;"></i>
            Chưa có thông tin điểm học tập nào được cập nhật.
        </div>
    @endif

    <!-- Logic tính điểm -->
    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:1rem 1.25rem; margin-top: 1rem;">
        <h4 style="font-size:0.85rem; font-weight:700; color:#1e293b; margin:0 0 0.75rem;"><i class="fa-solid fa-circle-info" style="color:#2563eb;"></i> Công thức tính điểm</h4>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; font-size:0.78rem; color:#475569;">
            <div><b>Điểm TB</b> = Điểm C×10% + Điểm B×30% + Điểm A×60%</div>
            <div><b>A</b> ≥ 8.5 · <b>B</b> ≥ 7.0 · <b>C</b> ≥ 5.5 · <b>D</b> ≥ 4.0 · <b>F</b> &lt; 4.0</div>
            <div><b>GPA</b> = Σ(Điểm TB × Tín chỉ) ÷ Tổng tín chỉ</div>
        </div>
    </div>
</div>

<script>
    function filterSemester(val) {
        const cards = document.querySelectorAll('.semester-card');
        cards.forEach(card => {
            if (val === 'all') {
                card.style.display = 'block';
            } else {
                if (card.id === 'sem-' + val) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    }
</script>
@endsection
