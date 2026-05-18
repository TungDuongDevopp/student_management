@extends('layouts.user.teacher_sidebar')
@section('title', 'Lịch Giảng Dạy')
@section('content')

<style>
    .schedule-page { font-family:'Inter',-apple-system,sans-serif;display:flex;flex-direction:column;gap:1.5rem;color:#334155; }
    .breadcrumb-nav { margin-bottom:1rem;background:#fff;padding:.75rem 1.25rem;border-radius:8px;border:1px solid #e2e8f0;display:inline-block; }
    .breadcrumb { display:flex;align-items:center;gap:.5rem;list-style:none;margin:0;padding:0;font-size:.85rem;font-weight:600; }
    .breadcrumb a { color:#3b82f6;text-decoration:none;display:flex;align-items:center;gap:.3rem; }
    .breadcrumb .separator { color:#94a3b8;font-size:.7rem; }
    .breadcrumb .active { color:#64748b; }

    .page-hero { background:linear-gradient(135deg,#7f1d1d 0%,#dc2626 100%);color:#fff;border-radius:10px;padding:1.75rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;position:relative;overflow:hidden; }
    .page-hero::after { content:"";position:absolute;top:-80px;right:-60px;width:260px;height:260px;background:rgba(255,255,255,.08);transform:rotate(45deg); }
    .hero-content { position:relative;z-index:1; }
    .hero-eyebrow { font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;opacity:.85;font-weight:700;margin-bottom:.4rem; }
    .hero-title { margin:0;font-size:1.45rem;font-weight:800; }
    .hero-desc { margin:.45rem 0 0;font-size:.9rem;opacity:.9;line-height:1.5; }

    .summary-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:1rem; }
    .summary-card { background:#fff;border-radius:8px;padding:1rem;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,.04); }
    .summary-label { margin:0 0 .4rem;font-size:.72rem;text-transform:uppercase;font-weight:700;color:#64748b;letter-spacing:.04em; }
    .summary-value { margin:0;font-size:1.45rem;font-weight:800;color:#0f172a; }
    .summary-sub { margin:.25rem 0 0;font-size:.78rem;color:#94a3b8; }

    .toolbar { background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:1rem;display:grid;grid-template-columns:1fr 1fr auto;gap:1rem;align-items:end;box-shadow:0 1px 3px rgba(0,0,0,.04); }
    .form-group label { display:block;font-size:.75rem;font-weight:700;color:#475569;margin-bottom:.35rem; }
    .form-control { width:100%;border:1px solid #cbd5e1;border-radius:6px;padding:.65rem .75rem;font-size:.85rem;color:#334155;background:#fff; }
    .btn-filter { background:#dc2626;color:#fff;border:none;border-radius:6px;padding:.68rem 1.2rem;font-size:.85rem;font-weight:700;cursor:pointer; }

    .schedule-card { background:#fff;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;box-shadow:0 4px 10px rgba(15,23,42,.05); }
    .schedule-header { padding:1.15rem 1.25rem;background:#fff;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap; }
    .schedule-header h2 { margin:0;font-size:1.05rem;font-weight:800;color:#0f172a; }
    .schedule-header p { margin:.25rem 0 0;font-size:.82rem;color:#64748b; }
    .week-badge { background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;border-radius:999px;padding:.4rem .75rem;font-size:.78rem;font-weight:700; }

    .table-responsive { width:100%;overflow-x:auto; }
    .schedule-table { width:100%;min-width:980px;border-collapse:collapse;table-layout:fixed; }
    .schedule-table th { background:#fef2f2;color:#991b1b;font-weight:800;font-size:.78rem;text-transform:uppercase;padding:.35rem;border:1px solid #fecaca;text-align:center; }
    .schedule-table td { border:1px solid #f1f5f9;height:34px !important;vertical-align:top;padding:2px !important;background:#fff; }
    .time-col { background:#fff7ed!important;width:90px;text-align:center;vertical-align:middle!important;font-weight:800;color:#9a3412;border:1px solid #fed7aa!important;font-size:.7rem; }
    .time-col small { display:block;margin-top:0px;font-size:.58rem;color:#c2410c;font-weight:600; }

    .teacher-card { height:100%;background:#fff;border:1px solid #fecaca;border-left:3px solid #dc2626;border-radius:4px;padding:2px 4px;display:flex;flex-direction:column;gap:0px;box-shadow:0 2px 6px rgba(220,38,38,.08);transition:.2s ease; overflow:hidden; box-sizing:border-box; }
    .teacher-card:hover { transform:translateY(-1px);box-shadow:0 4px 10px rgba(220,38,38,.12); }
    .class-code { font-size:.55rem;color:#dc2626;font-weight:800;text-transform:uppercase;letter-spacing:.04em;line-height:1; }
    .subject-title { font-size:.7rem;font-weight:700;color:#0f172a;line-height:1.1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
    .meta-info { font-size:.6rem;color:#64748b;display:flex;align-items:center;gap:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1; }
    .status-row { margin-top:2px;display:flex;gap:.25rem;flex-wrap:wrap;line-height:1; }
    .status-badge { display:inline-flex;align-items:center;border-radius:999px;padding:0px 4px;font-size:.55rem;font-weight:700;line-height:1; }
    .status-normal { background:#dcfce7;color:#15803d; }
    .status-warning { background:#fef3c7;color:#b45309; }
    .action-btns { margin-top:auto;display:grid;grid-template-columns:1fr 1fr;gap:2px; }
    .btn-action { font-size:.55rem;padding:2px;border-radius:2px;border:none;font-weight:800;cursor:pointer;text-align:center;text-decoration:none;display:block;line-height:1; }
    .btn-view { background:#f1f5f9;color:#475569; }
    .btn-score { background:#dc2626;color:#fff; }
    .empty-slot { height:100%;display:flex;align-items:center;justify-content:center;color:#f1f5f9;font-size:.65rem; }
    .break-row td { text-align:center;padding:.15rem!important;background:rgba(59,130,246,.04);color:#94a3b8;font-size:.68rem;font-style:italic;height:20px!important; }

    @media(max-width:1024px){ .summary-grid{grid-template-columns:repeat(2,1fr);} .toolbar{grid-template-columns:1fr 1fr;} }
    @media(max-width:640px){ .summary-grid,.toolbar{grid-template-columns:1fr;} }
</style>

<div class="content-wrapper">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ route('teacher.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Lịch giảng dạy</li>
        </ol>
    </nav>

<main class="schedule-page">

    <section class="page-hero">
        <div class="hero-content">
            <div class="hero-eyebrow">Teacher Academic Portal</div>
            <h1 class="hero-title">Lịch Giảng Dạy</h1>
            <p class="hero-desc">{{ $teacher->name ?? 'Giảng viên' }} · {{ $teacher->faculty->name ?? '' }}</p>
        </div>
        <span class="week-badge" id="semBadge">Tất cả học kỳ</span>
    </section>

    <section class="summary-grid">
        <article class="summary-card">
            <p class="summary-label">Giảng viên</p>
            <p class="summary-value" style="font-size:1.05rem;">{{ $teacher->name ?? '—' }}</p>
            <p class="summary-sub">{{ $teacher->faculty->name ?? '—' }}</p>
        </article>
        <article class="summary-card">
            <p class="summary-label">Tổng lớp phụ trách</p>
            <p class="summary-value" id="statClasses">{{ $stats['assigned_classes'] }}</p>
            <p class="summary-sub">Lớp hành chính + học phần</p>
        </article>
        <article class="summary-card">
            <p class="summary-label">Tổng sinh viên</p>
            <p class="summary-value" id="statStudents">{{ $stats['total_students'] }}</p>
            <p class="summary-sub">Tổng sĩ số các lớp</p>
        </article>
        <article class="summary-card">
            <p class="summary-label">Bảng điểm chưa chốt</p>
            <p class="summary-value" id="statUngraded">{{ $stats['ungraded_schedules'] }}</p>
            <p class="summary-sub">Cần rà soát trước khi khoá điểm</p>
        </article>
    </section>

    <section class="toolbar">
        <div class="form-group">
            <label for="selSemester">Học kỳ</label>
            <select id="selSemester" class="form-control">
                <option value="">— Tất cả học kỳ —</option>
                @foreach($semesters as $sem)
                    <option value="{{ $sem->id }}" {{ $sem->status == 1 ? 'selected' : '' }}>
                        {{ $sem->name }}{{ $sem->academic_year ? ' – '.$sem->academic_year : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Ngày hiện tại</label>
            <div class="form-control" style="background:#f8fafc;color:#64748b;" id="todayLabel"></div>
        </div>
        <button type="button" class="btn-filter" onclick="renderTimetable()">
            <i class="fa-solid fa-rotate"></i> Cập nhật
        </button>
    </section>

    <section class="schedule-card">
        <header class="schedule-header">
            <div>
                <h2>Thời khoá biểu chi tiết</h2>
                <p>Hiển thị lịch theo buổi học trong tuần</p>
            </div>
            <span class="week-badge" id="filterLabel">Đang tải...</span>
        </header>
        <div class="table-responsive">
            <table class="schedule-table" id="timetableEl">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Thứ 2</th>
                        <th>Thứ 3</th>
                        <th>Thứ 4</th>
                        <th>Thứ 5</th>
                        <th>Thứ 6</th>
                        <th>Thứ 7</th>
                    </tr>
                </thead>
                <tbody id="timetableBody">
                    <tr><td colspan="7" style="text-align:center;padding:2rem;color:#94a3b8;">Đang tải lịch...</td></tr>
                </tbody>
            </table>
        </div>
    </section>

</main>
</div>

<script>
// ── DỮ LIỆU TỪ CONTROLLER ─────────────────────────────────────────────────
const ALL_SCHEDULES = @json($schedules);

// ── ĐỊNH NGHĨA CÁC TIẾT ─────────────────────────────────────────────────
const SLOTS = [
    { id:1,  label:'Tiết 1',  start:'07:00', end:'07:45' },
    { id:2,  label:'Tiết 2',  start:'07:50', end:'08:35' },
    { id:3,  label:'Tiết 3',  start:'08:40', end:'09:25' },
    { id:4,  label:'Tiết 4',  start:'09:35', end:'10:20' },
    { id:5,  label:'Tiết 5',  start:'10:25', end:'11:10' },
    { id:6,  label:'Tiết 6',  start:'11:15', end:'12:00' },
    { id:7,  label:'Tiết 7',  start:'13:00', end:'13:45' },
    { id:8,  label:'Tiết 8',  start:'13:50', end:'14:35' },
    { id:9,  label:'Tiết 9',  start:'14:40', end:'15:25' },
    { id:10, label:'Tiết 10', start:'15:35', end:'16:20' },
    { id:11, label:'Tiết 11', start:'16:25', end:'17:10' },
    { id:12, label:'Tiết 12', start:'17:15', end:'18:00' },
];

// Ngày trong tuần: index 2=Thứ 2 ... 7=Thứ 7
const DAYS = [2,3,4,5,6,7];

// ── HÀM TIỆN ÍCH ─────────────────────────────────────────────────────────
function toMinutes(hhmm) {
    if (!hhmm) return 0;
    const [h, m] = hhmm.split(':').map(Number);
    return h * 60 + m;
}

function findFirstSlot(startTime) {
    const t = toMinutes(startTime);
    for (let i = 0; i < SLOTS.length; i++) {
        const slotStart = toMinutes(SLOTS[i].start);
        const slotEnd   = toMinutes(SLOTS[i].end);
        // Nếu giờ bắt đầu nằm trong hoặc trước khi slot kết thúc
        if (t <= slotEnd + 5) return i; // +5 phút dung sai
    }
    return 0;
}

function findLastSlot(endTime) {
    const t = toMinutes(endTime);
    for (let i = SLOTS.length - 1; i >= 0; i--) {
        if (toMinutes(SLOTS[i].end) <= t + 5) return i;
    }
    return 0;
}

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// ── RENDER TIMETABLE ───────────────────────────────────────────────────────
function renderTimetable() {
    const semId = document.getElementById('selSemester').value;
    const semText = document.getElementById('selSemester').selectedOptions[0]?.text ?? '';

    // Lọc theo học kỳ
    const filtered = semId
        ? ALL_SCHEDULES.filter(s => String(s.semester_id) === String(semId))
        : ALL_SCHEDULES;

    document.getElementById('filterLabel').textContent =
        semId ? semText : `Tất cả — ${filtered.length} lớp`;

    // Build lookup: cell[slotIndex][day] = { schedule, session, rowspan }
    // rowspan: số tiết môn chiếm
    // Đánh dấu các ô bị span bởi ô trên -> skip khi render

    const cellMap = {}; // key: `${slotIndex}_${day}` -> data
    const skipSet = new Set(); // key: `${slotIndex}_${day}` bị span

    filtered.forEach(sch => {
        sch.sessions.forEach(ss => {
            const day = ss.day_of_week;
            if (!DAYS.includes(day)) return;

            const firstSlotIdx = findFirstSlot(ss.start_time);
            const lastSlotIdx  = findLastSlot(ss.end_time);
            const rowspan = Math.max(1, lastSlotIdx - firstSlotIdx + 1);
            const key = `${firstSlotIdx}_${day}`;

            cellMap[key] = { sch, ss, rowspan };

            // Đánh dấu các ô phía dưới bị chiếm bởi rowspan
            for (let r = 1; r < rowspan; r++) {
                skipSet.add(`${firstSlotIdx + r}_${day}`);
            }

            .toolbar {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(max-width:640px) {

            .summary-grid,
            .toolbar {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="content-wrapper">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li><a href="{{ route('teacher.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
                <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                <li class="active">Lịch giảng dạy</li>
            </ol>
        </nav>

        <main class="schedule-page">

            <section class="page-hero">
                <div class="hero-content">
                    <div class="hero-eyebrow">Teacher Academic Portal</div>
                    <h1 class="hero-title">Lịch Giảng Dạy</h1>
                    <p class="hero-desc">{{ $teacher->name ?? 'Giảng viên' }} · {{ $teacher->faculty->name ?? '' }}</p>
                </div>
                <span class="week-badge" id="semBadge">Tất cả học kỳ</span>
            </section>

            <section class="summary-grid">
                <article class="summary-card">
                    <p class="summary-label">Giảng viên</p>
                    <p class="summary-value" style="font-size:1.05rem;">{{ $teacher->name ?? '—' }}</p>
                    <p class="summary-sub">{{ $teacher->faculty->name ?? '—' }}</p>
                </article>
                <article class="summary-card">
                    <p class="summary-label">Tổng lớp phụ trách</p>
                    <p class="summary-value" id="statClasses">{{ $stats['assigned_classes'] }}</p>
                    <p class="summary-sub">Lớp hành chính + học phần</p>
                </article>
                <article class="summary-card">
                    <p class="summary-label">Tổng sinh viên</p>
                    <p class="summary-value" id="statStudents">{{ $stats['total_students'] }}</p>
                    <p class="summary-sub">Tổng sĩ số các lớp</p>
                </article>
                <article class="summary-card">
                    <p class="summary-label">Bảng điểm chưa chốt</p>
                    <p class="summary-value" id="statUngraded">{{ $stats['ungraded_schedules'] }}</p>
                    <p class="summary-sub">Cần rà soát trước khi khoá điểm</p>
                </article>
            </section>

            <section class="toolbar">
                <div class="form-group">
                    <label for="selSemester">Học kỳ</label>
                    <select id="selSemester" class="form-control">
                        <option value="">— Tất cả học kỳ —</option>
                        @foreach ($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $sem->status == 1 ? 'selected' : '' }}>
                                {{ $sem->name }}{{ $sem->academic_year ? ' – ' . $sem->academic_year : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Ngày hiện tại</label>
                    <div class="form-control" style="background:#f8fafc;color:#64748b;" id="todayLabel"></div>
                </div>
                <button type="button" class="btn-filter" onclick="renderTimetable()">
                    <i class="fa-solid fa-rotate"></i> Cập nhật
                </button>
            </section>

            <section class="schedule-card">
                <header class="schedule-header">
                    <div>
                        <h2>Thời khoá biểu chi tiết</h2>
                        <p>Hiển thị lịch theo buổi học trong tuần</p>
                    </div>
                    <span class="week-badge" id="filterLabel">Đang tải...</span>
                </header>
                <div class="table-responsive">
                    <table class="schedule-table" id="timetableEl">
                        <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th>Thứ 2</th>
                                <th>Thứ 3</th>
                                <th>Thứ 4</th>
                                <th>Thứ 5</th>
                                <th>Thứ 6</th>
                                <th>Thứ 7</th>
                            </tr>
                        </thead>
                        <tbody id="timetableBody">
                            <tr>
                                <td colspan="7" style="text-align:center;padding:2rem;color:#94a3b8;">Đang tải lịch...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>

    <script>
        // ── DỮ LIỆU TỪ CONTROLLER ─────────────────────────────────────────────────
        const ALL_SCHEDULES = @json($schedules);

        // ── ĐỊNH NGHĨA CÁC TIẾT ─────────────────────────────────────────────────
        const SLOTS = [{
                id: 1,
                label: 'Tiết 1',
                start: '06:45',
                end: '07:35'
            },
            {
                id: 2,
                label: 'Tiết 2',
                start: '07:45',
                end: '08:35'
            },
            {
                id: 3,
                label: 'Tiết 3',
                start: '08:45',
                end: '09:35'
            },
            {
                id: 4,
                label: 'Tiết 4',
                start: '09:45',
                end: '10:35'
            },
            {
                id: 5,
                label: 'Tiết 5',
                start: '10:45',
                end: '11:35'
            },
            {
                id: 6,
                label: 'Tiết 6',
                start: '11:45',
                end: '12:35'
            },
            {
                id: 7,
                label: 'Tiết 7',
                start: '13:30',
                end: '14:20'
            },
            {
                id: 8,
                label: 'Tiết 8',
                start: '14:30',
                end: '15:20'
            },
            {
                id: 9,
                label: 'Tiết 9',
                start: '15:30',
                end: '16:20'
            },
            {
                id: 10,
                label: 'Tiết 10',
                start: '16:30',
                end: '17:20'
            },
            {
                id: 11,
                label: 'Tiết 11',
                start: '17:30',
                end: '18:20'
            },
            {
                id: 12,
                label: 'Tiết 12',
                start: '18:30',
                end: '19:20'
            },
            {
                id: 13,
                label: 'Tiết 13',
                start: '19:30',
                end: '20:20'
            }

        ];

        // Ngày trong tuần: index 2=Thứ 2 ... 7=Thứ 7
        const DAYS = [2, 3, 4, 5, 6, 7];

        // ── HÀM TIỆN ÍCH ─────────────────────────────────────────────────────────
        function toMinutes(hhmm) {
            if (!hhmm) return 0;
            const [h, m] = hhmm.split(':').map(Number);
            return h * 60 + m;
        }

        function findFirstSlot(startTime) {
            const t = toMinutes(startTime);
            for (let i = 0; i < SLOTS.length; i++) {
                const slotStart = toMinutes(SLOTS[i].start);
                const slotEnd = toMinutes(SLOTS[i].end);
                // Nếu giờ bắt đầu nằm trong hoặc trước khi slot kết thúc
                if (t <= slotEnd + 5) return i; // +5 phút dung sai
            }
            return 0;
        }

        function findLastSlot(endTime) {
            const t = toMinutes(endTime);
            for (let i = SLOTS.length - 1; i >= 0; i--) {
                if (toMinutes(SLOTS[i].end) <= t + 5) return i;
            }
            return 0;
        }

        function escHtml(s) {
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        // ── RENDER TIMETABLE ───────────────────────────────────────────────────────
        function renderTimetable() {
            const semId = document.getElementById('selSemester').value;
            const semText = document.getElementById('selSemester').selectedOptions[0]?.text ?? '';

            // Lọc theo học kỳ
            const filtered = semId ?
                ALL_SCHEDULES.filter(s => String(s.semester_id) === String(semId)) :
                ALL_SCHEDULES;

            document.getElementById('filterLabel').textContent =
                semId ? semText : `Tất cả — ${filtered.length} lớp`;

            // Build lookup: cell[slotIndex][day] = { schedule, session, rowspan }
            // rowspan: số tiết môn chiếm
            // Đánh dấu các ô bị span bởi ô trên -> skip khi render

            const cellMap = {}; // key: `${slotIndex}_${day}` -> data
            const skipSet = new Set(); // key: `${slotIndex}_${day}` bị span

            filtered.forEach(sch => {
                sch.sessions.forEach(ss => {
                    const day = ss.day_of_week;
                    if (!DAYS.includes(day)) return;

                    const firstSlotIdx = findFirstSlot(ss.start_time);
                    const lastSlotIdx = findLastSlot(ss.end_time);
                    const rowspan = Math.max(1, lastSlotIdx - firstSlotIdx + 1);
                    const key = `${firstSlotIdx}_${day}`;

                    cellMap[key] = {
                        sch,
                        ss,
                        rowspan
                    };

                    // Đánh dấu các ô phía dưới bị chiếm bởi rowspan
                    for (let r = 1; r < rowspan; r++) {
                        skipSet.add(`${firstSlotIdx + r}_${day}`);
                    }
                });
            });

            // Build rows
            let html = '';
            let slotIdx = 0;
            while (slotIdx < SLOTS.length) {
                const slot = SLOTS[slotIdx];
                html += `<tr>
            <td class="time-col">${slot.label}<small>${slot.start}–${slot.end}</small></td>`;

                DAYS.forEach(day => {
                    const key = `${slotIdx}_${day}`;
                    if (skipSet.has(key)) {
                        // bị rowspan từ ô trên -> không render td
                        return;
                    }
                    const cell = cellMap[key];
                    if (cell) {
                        const {
                            sch,
                            ss,
                            rowspan
                        } = cell;
                        const ungradedBadge = sch.has_ungraded ?
                            `<span class="status-badge status-warning">Chưa chốt điểm</span>` : '';
                        html += `<td rowspan="${rowspan}">
                    <article class="teacher-card">
                        <span class="class-code">${escHtml(sch.group_code || `#${sch.id}`)}</span>
                        <div class="subject-title">${escHtml(sch.subject_name)}</div>
                        <div class="meta-info"><i class="fa-solid fa-users"></i> ${sch.current_capacity}/${sch.max_capacity} sv</div>
                        <div class="meta-info"><i class="fa-solid fa-location-dot"></i> ${escHtml(sch.room)}</div>
                        <div class="meta-info"><i class="fa-regular fa-clock"></i> ${ss.start_time}–${ss.end_time}</div>
                        <div class="status-row">
                            <span class="status-badge status-normal">Đúng lịch</span>
                            ${ungradedBadge}
                        </div>
                        <div class="action-btns">
                            <a href="#" class="btn-action btn-view">Danh sách</a>
                            <a href="#" class="btn-action btn-score">Vào điểm</a>
                        </div>
                    </article>
                </td>`;
                    } else {
                        html += `<td><div class="empty-slot">—</div></td>`;
                    }
                });

                html += `</tr>`;

                // Chèn nghỉ trưa sau tiết 6
                if (slotIdx === 5) {
                    html +=
                        `<tr class="break-row"><td colspan="7"><i class="fa-solid fa-mug-hot"></i> Nghỉ trưa (12:00 – 13:00)</td></tr>`;
                }

                slotIdx++;
            }

            document.getElementById('timetableBody').innerHTML = html;
        }

        // ── KHỞI TẠO ──────────────────────────────────────────────────────────────
        const DAYS_VI = ['', '', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
        const now = new Date();
        const jsDay = now.getDay(); // 0=CN,1=Thứ2,...
        const dayLabel = jsDay === 0 ? 'Chủ nhật' : `Thứ ${jsDay + 1}`;
        document.getElementById('todayLabel').textContent =
            `${dayLabel}, ${now.toLocaleDateString('vi-VN')}`;

        // Auto chọn học kỳ đang hoạt động (status=1)
        document.addEventListener('DOMContentLoaded', () => {
            renderTimetable();
        });
        renderTimetable();
    </script>

@endsection
