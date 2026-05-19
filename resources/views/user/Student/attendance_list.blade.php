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

.card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-top: 1rem;
}
.table-wrapper {
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.88rem;
    color: #334155;
    text-align: left;
}
th {
    padding: 0.85rem 1.25rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #475569;
}
td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
tr:last-child td {
    border-bottom: none;
}
.badge-faculty {
    background: #eff6ff;
    color: #1e40af;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}
.badge-success {
    background: #dcfce7;
    color: #15803d;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.78rem;
    font-weight: 700;
    display: inline-block;
    min-width: 24px;
    text-align: center;
}
.badge-danger {
    background: #fee2e2;
    color: #b91c1c;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.78rem;
    font-weight: 700;
    display: inline-block;
    min-width: 24px;
    text-align: center;
}
.btn-feedback {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.85rem;
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
}
.btn-feedback:hover {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}
.empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
    color: #94a3b8;
}
.empty-state i {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: #cbd5e1;
    display: block;
}
</style>

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
            <p class="hero-desc">Xem chi tiết số buổi có mặt, vắng mặt của các học phần trong học kỳ</p>
        </div>
        <span class="sem-badge">Học Kỳ Hiện Tại</span>
    </section>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">STT</th>
                        <th style="width: 140px;">Mã học phần</th>
                        <th>Tên học phần</th>
                        <th>Giảng viên</th>
                        <th style="width: 140px;">Số buổi có mặt</th>
                        <th style="width: 140px;">Số buổi vắng</th>
                        <th style="width: 140px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if($enrollments->count() > 0)
                        @foreach($enrollments as $e)
                            @php
                                $present = $e->attendances->where('status', 1)->count();
                                $absent = $e->attendances->where('status', 0)->count();
                                $subjectName = $e->schedule->subject->name ?? '—';
                                $subjectCode = $e->schedule->subject->code ?? $e->schedule->subject->id ?? '—';
                                $teacherName = $e->schedule->teacher->name ?? '—';
                                $groupCode = $e->schedule->group_code ?? '—';
                                
                                // Tạo nội dung phản hồi pre-filled
                                $feedbackContent = "Em muốn phản hồi về kết quả điểm danh môn " . $subjectName . " (Mã môn: " . $subjectCode . ", Nhóm: " . $groupCode . ").\nSố buổi có mặt: " . $present . ", Số buổi vắng: " . $absent . ".\nNội dung phản hồi chi tiết: ";
                                
                                $feedbackUrl = route('student.feedback', [
                                    'category' => 'academic',
                                    'subject' => 'Phản hồi điểm danh môn ' . $subjectName,
                                    'content' => $feedbackContent
                                ]);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="badge-faculty">{{ $subjectCode }}</span></td>
                                <td><strong>{{ $subjectName }}</strong></td>
                                <td>{{ $teacherName }}</td>
                                <td><span class="badge-success">{{ $present }}</span></td>
                                <td><span class="badge-danger">{{ $absent }}</span></td>
                                <td style="text-align: center;">
                                    <a href="{{ $feedbackUrl }}" class="btn-feedback">
                                        <i class="fa-regular fa-comment-dots"></i> Phản hồi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fa-regular fa-calendar-times"></i>
                                    <p>Chưa đăng ký học phần nào trong học kỳ này.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
