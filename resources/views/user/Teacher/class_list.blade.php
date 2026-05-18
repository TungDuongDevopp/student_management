@extends('layouts.user.teacher_sidebar')
@section('title', 'Danh sách Lớp học')
@section('content')
<style>
    .cls-wrapper { max-width: 1200px; font-family: 'Inter', sans-serif; color: #334155; }
    .cls-header { margin-bottom:1.5rem; }
    .cls-header h1 { font-size:1.5rem; font-weight:800; color:#0f172a; margin:0 0 0.25rem; }
    .cls-header p { color:#64748b; font-size:0.88rem; margin:0; }
    
    .section-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 2rem 0 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0; }

    .cls-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:1rem; }
    .cls-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:1.25rem; transition:all 0.2s; position:relative; overflow:hidden; display: flex; flex-direction: column; }
    .cls-card:hover { box-shadow:0 8px 24px rgba(0,0,0,0.08); transform:translateY(-2px); }
    .cls-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; border-radius:12px 12px 0 0; }
    
    .cls-card.blue::before { background:#2563eb; }
    .cls-card.green::before { background:#10b981; }
    .cls-card.amber::before { background:#f59e0b; }
    .cls-card.purple::before { background:#8b5cf6; }
    
    .cls-code { font-size:0.75rem; font-weight:700; padding:0.2rem 0.5rem; border-radius:4px; display:inline-block; margin-bottom:0.5rem; width: fit-content; }
    .cls-code.admin { color:#2563eb; background:#eff6ff; }
    .cls-code.subject { color:#059669; background:#ecfdf5; }
    
    .cls-name { font-size:1.05rem; font-weight:700; color:#1e293b; margin-bottom:0.75rem; line-height: 1.3; }
    
    .cls-meta { display:flex; flex-direction:column; gap:0.4rem; margin-bottom:1rem; flex-grow: 1; }
    .cls-meta-item { display:flex; align-items:flex-start; gap:0.5rem; font-size:0.82rem; color:#64748b; }
    .cls-meta-item i { width:16px; text-align:center; color:#94a3b8; margin-top: 3px; }
    
    .cls-badges { display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1rem; }
    .cls-badge { font-size:0.72rem; padding:0.2rem 0.5rem; border-radius:4px; font-weight:600; }
    .cls-badge.ok { background:#dcfce7; color:#166534; }
    .cls-badge.warn { background:#fef3c7; color:#92400e; }
    .cls-badge.info { background:#e0f2fe; color:#0369a1; }
    
    .cls-actions { display:flex; gap:0.5rem; padding-top:0.75rem; border-top:1px solid #f1f5f9; margin-top: auto; }
    .cls-actions a { flex:1; text-align:center; padding:0.45rem; border-radius:6px; font-size:0.8rem; font-weight:600; text-decoration:none; transition:all 0.15s; }
    .cls-actions .btn-list { background:#eff6ff; color:#2563eb; }
    .cls-actions .btn-list:hover { background:#dbeafe; }
    .cls-actions .btn-score { background:#f0fdf4; color:#16a34a; }
    .cls-actions .btn-score:hover { background:#dcfce7; }
    .cls-actions .btn-att { background:#fef3c7; color:#d97706; }
    .cls-actions .btn-att:hover { background:#fde68a; }

    .empty-state { text-align: center; padding: 3rem; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; color: #64748b; }
    .empty-state i { font-size: 2rem; color: #94a3b8; margin-bottom: 1rem; display: block; }
</style>

@php
    $daysOfWeek = [2 => 'Thứ 2', 3 => 'Thứ 3', 4 => 'Thứ 4', 5 => 'Thứ 5', 6 => 'Thứ 6', 7 => 'Thứ 7', 8 => 'Chủ nhật'];
@endphp

<div class="cls-wrapper">
    <div class="cls-header">
        <h1><i class="fa-solid fa-layer-group" style="color:#2563eb; margin-right:0.5rem;"></i>Danh sách Lớp học</h1>
        <p>Giảng viên: {{ $teacher->name ?? '—' }}</p>
    </div>

    {{-- LỚP HÀNH CHÍNH (Chủ nhiệm) --}}
    <h2 class="section-title"><i class="fa-solid fa-id-card-clip" style="color:#64748b; margin-right:8px;"></i>Lớp hành chính (Chủ nhiệm)</h2>
    @if($classRooms->count() > 0)
        <div class="cls-grid">
            @foreach($classRooms as $class)
                <div class="cls-card blue">
                    <span class="cls-code admin">Lớp hành chính</span>
                    <div class="cls-name">{{ $class->name }}</div>
                    <div class="cls-meta">
                        <div class="cls-meta-item"><i class="fa-solid fa-users"></i> Sĩ số: {{ $class->quantity ?? 0 }} sinh viên</div>
                        <div class="cls-meta-item"><i class="fa-solid fa-building-columns"></i> Khoa: {{ $class->faculty->name ?? '—' }}</div>
                    </div>
                    <div class="cls-actions">
                        <a href="{{ route('teacher.students') }}?class_id={{ $class->id }}" class="btn-list"><i class="fa-solid fa-list"></i> DS sinh viên</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fa-solid fa-chalkboard-user"></i>
            <p>Không có lớp hành chính nào do bạn chủ nhiệm.</p>
        </div>
    @endif

    {{-- LỚP HỌC PHẦN (Giảng dạy) --}}
    <h2 class="section-title"><i class="fa-solid fa-book-open" style="color:#64748b; margin-right:8px;"></i>Lớp học phần (Giảng dạy)</h2>
    @if($schedules->count() > 0)
        <div class="cls-grid">
            @foreach($schedules as $index => $schedule)
                @php
                    // Tính toán status (ví dụ: có sinh viên chưa chốt điểm)
                    $ungradedCount = $schedule->enrollments->where('status', 0)->count();
                    $hasUngraded = $ungradedCount > 0;
                    
                    // Chọn màu viền xoay vòng
                    $colors = ['green', 'amber', 'purple', 'blue'];
                    $colorClass = $colors[$index % count($colors)];
                @endphp
                <div class="cls-card {{ $colorClass }}">
                    <span class="cls-code subject">Nhóm {{ $schedule->group_code ?? $schedule->id }}</span>
                    <div class="cls-name">{{ $schedule->subject->name ?? 'Môn học không xác định' }}</div>
                    <div class="cls-meta">
                        <div class="cls-meta-item"><i class="fa-solid fa-users"></i> Sĩ số: {{ $schedule->current_capacity ?? 0 }}/{{ $schedule->max_capacity ?? 0 }} sinh viên</div>
                        
                        {{-- Hiển thị thời gian học --}}
                        @if($schedule->sessions->count() > 0)
                            <div class="cls-meta-item">
                                <i class="fa-regular fa-calendar"></i>
                                <div>
                                    @foreach($schedule->sessions as $session)
                                        <div>
                                            {{ $daysOfWeek[$session->day_of_week] ?? 'Thứ ?' }} 
                                            ({{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }})
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="cls-meta-item"><i class="fa-regular fa-calendar"></i> Chưa xếp lịch</div>
                        @endif

                        <div class="cls-meta-item"><i class="fa-solid fa-location-dot"></i> Phòng: {{ $schedule->room ? (($schedule->room->block ? $schedule->room->block.'.' : '').$schedule->room->name) : 'Chưa xếp phòng' }}</div>
                        <div class="cls-meta-item"><i class="fa-solid fa-layer-group"></i> Học kỳ: {{ $schedule->semester->name ?? '—' }} {{ $schedule->semester->academic_year ? '('.$schedule->semester->academic_year.')' : '' }}</div>
                    </div>
                    <div class="cls-badges">
                        <span class="cls-badge ok">Đang giảng dạy</span>
                        @if($hasUngraded)
                            <span class="cls-badge warn">{{ $ungradedCount }} điểm chưa chốt</span>
                        @endif
                    </div>
                    <div class="cls-actions">
                        <a href="{{ route('teacher.students') }}?schedule_id={{ $schedule->id }}" class="btn-list"><i class="fa-solid fa-list"></i> DS sinh viên</a>
                        <a href="{{ route('teacher.grades') }}?schedule_id={{ $schedule->id }}" class="btn-score"><i class="fa-solid fa-pen"></i> Vào điểm</a>
                        <a href="{{ route('teacher.attendances') }}?schedule_id={{ $schedule->id }}" class="btn-att"><i class="fa-solid fa-user-check"></i> Điểm danh</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fa-solid fa-book-open-reader"></i>
            <p>Chưa có lớp học phần nào được phân công.</p>
        </div>
    @endif
</div>
@endsection
