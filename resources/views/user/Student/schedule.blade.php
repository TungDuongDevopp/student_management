@extends('layouts.user.student_sidebar')
@section('title', 'Thời Khóa Biểu')
@section('content')
    <style>
        .schedule-page {
            font-family: 'Inter', -apple-system, sans-serif;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            color: #334155;
        }

        .breadcrumb-nav {
            margin-bottom: 1rem;
            background: #fff;
            padding: .75rem 1.25rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            display: inline-block;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: .5rem;
            list-style: none;
            margin: 0;
            padding: 0;
            font-size: .85rem;
            font-weight: 600;
        }

        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
        }

        .breadcrumb .separator {
            color: #94a3b8;
            font-size: .7rem;
        }

        .breadcrumb .active {
            color: #64748b;
        }

        .page-hero {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #fff;
            border-radius: 10px;
            padding: 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .page-hero::after {
            content: "";
            position: absolute;
            top: -80px;
            right: -60px;
            width: 260px;
            height: 260px;
            background: rgba(255, 255, 255, .08);
            transform: rotate(45deg);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-eyebrow {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            opacity: .85;
            font-weight: 700;
            margin-bottom: .4rem;
        }

        .hero-title {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .hero-desc {
            margin: .45rem 0 0;
            font-size: .9rem;
            opacity: .9;
        }

        .sem-badge {
            background: #fff;
            color: #1d4ed8;
            border-radius: 999px;
            padding: .4rem .85rem;
            font-size: .78rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .toolbar {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
        }

        .form-group label {
            display: block;
            font-size: .75rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: .35rem;
        }

        .form-control {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: .65rem .75rem;
            font-size: .85rem;
            color: #334155;
            background: #fff;
        }

        .btn-filter {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: .68rem 1.2rem;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
        }

        .sheet-container {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(15, 23, 42, .05);
        }

        .sheet-header {
            background: #f8fafc;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sheet-header h2 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
        }

        .sheet-header p {
            margin: .25rem 0 0;
            font-size: .82rem;
            color: #64748b;
        }

        .sheet-table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .sheet-table th {
            background: #eff6ff;
            color: #1e40af;
            font-weight: 700;
            font-size: .78rem;
            text-transform: uppercase;
            padding: .85rem;
            border: 1px solid #bfdbfe;
            text-align: center;
        }

        .sheet-table td {
            border: 1px solid #f1f5f9;
            height: 120px;
            vertical-align: top;
            padding: .5rem;
            background: #fff;
        }

        .time-col {
            background: #f8fafc !important;
            width: 90px;
            text-align: center !important;
            vertical-align: middle !important;
            font-weight: 800;
            color: #475569;
            border: 1px solid #e2e8f0 !important;
            font-size: .8rem;
        }

        .time-col small {
            display: block;
            margin-top: .2rem;
            font-size: .65rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .course-card {
            height: 100%;
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            border-radius: 6px;
            padding: .65rem;
            display: flex;
            flex-direction: column;
            gap: .3rem;
            transition: .2s ease;
        }

        .course-card:hover {
            background: #dbeafe;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, .15);
        }

        .course-code {
            font-size: .65rem;
            color: #2563eb;
            font-weight: 800;
            text-transform: uppercase;
        }

        .course-name {
            font-size: .83rem;
            font-weight: 700;
            color: #1e40af;
            line-height: 1.3;
        }

        .course-info {
            font-size: .72rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        .room-tag {
            margin-top: auto;
            display: inline-block;
            background: #2563eb;
            color: #fff;
            font-size: .65rem;
            padding: .18rem .45rem;
            border-radius: 4px;
            font-weight: 700;
            width: fit-content;
        }

        .empty-slot {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e2e8f0;
            font-size: .75rem;
        }

        .lunch-break td {
            background: #f8fafc !important;
            text-align: center;
            padding: .4rem;
            font-size: .75rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: .1em;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        @media(max-width:1024px) {
            .toolbar {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(max-width:640px) {
            .toolbar {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="content-wrapper">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li><a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
                <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                <li class="active">Thời khóa biểu</li>
            </ol>
        </nav>

        <div class="schedule-page">
            <section class="page-hero">
                <div class="hero-content">
                    <div class="hero-eyebrow">Student Academic Portal</div>
                    <h1 class="hero-title">Thời Khóa Biểu</h1>
                    <p class="hero-desc">{{ $student->name ?? 'Sinh viên' }} · Xem lịch học theo học kỳ</p>
                </div>
                <span class="sem-badge" id="semBadge">Đang tải...</span>
            </section>

            <section class="toolbar">
                <div class="form-group">
                    <label for="selSemester">Học kỳ</label>
                    <select id="selSemester" class="form-control" onchange="renderTimetable()">
                        <option value="">— Tất cả học kỳ —</option>
                        @foreach ($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $sem->status == 1 ? 'selected' : '' }}>
                                {{ $sem->name }}{{ $sem->academic_year ? ' – ' . $sem->academic_year : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Ngày hôm nay</label>
                    <div class="form-control" style="background:#f8fafc;color:#64748b;" id="todayLabel"></div>
                </div>
                <button type="button" class="btn-filter" onclick="renderTimetable()">
                    <i class="fa-solid fa-rotate"></i> Cập nhật
                </button>
            </section>

            <div class="sheet-container">
                <header class="sheet-header">
                    <div>
                        <h2>THỜI KHÓA BIỂU HỌC TẬP</h2>
                        <p id="filterLabel">Đang tải dữ liệu...</p>
                    </div>
                    <div style="display:flex;gap:.5rem;">
                        <button onclick="window.print()"
                            style="background:#fff;border:1px solid #e2e8f0;padding:.5rem 1rem;border-radius:6px;font-weight:600;cursor:pointer;color:#475569;font-size:.82rem;">
                            <i class="fa-solid fa-print"></i> In lịch
                        </button>
                    </div>
                </header>
                <div class="table-responsive">
                    <table class="sheet-table">
                        <thead>
                            <tr>
                                <th style="width:90px">Thời gian</th>
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
                                <td colspan="7" style="text-align:center;padding:2rem;color:#94a3b8;">Đang tải...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ALL_SCHEDULES = @json($schedules);

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
            },

        ];
        const DAYS = [2, 3, 4, 5, 6, 7];

        function toMin(hhmm) {
            if (!hhmm) return 0;
            const [h, m] = hhmm.split(':').map(Number);
            return h * 60 + m;
        }

        function findFirstSlot(t) {
            const tm = toMin(t);
            for (let i = 0; i < SLOTS.length; i++)
                if (tm <= toMin(SLOTS[i].end) + 5) return i;
            return 0;
        }

        function findLastSlot(t) {
            const tm = toMin(t);
            for (let i = SLOTS.length - 1; i >= 0; i--)
                if (toMin(SLOTS[i].end) <= tm + 5) return i;
            return 0;
        }

        function esc(s) {
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        // Bảng màu xoay vòng cho các môn
        const COLORS = [{
                bg: '#eff6ff',
                border: '#2563eb',
                text: '#1e40af',
                tag: '#2563eb'
            },
            {
                bg: '#ecfdf5',
                border: '#10b981',
                text: '#065f46',
                tag: '#10b981'
            },
            {
                bg: '#fffbeb',
                border: '#f59e0b',
                text: '#92400e',
                tag: '#f59e0b'
            },
            {
                bg: '#fdf4ff',
                border: '#a855f7',
                text: '#581c87',
                tag: '#a855f7'
            },
            {
                bg: '#fff1f2',
                border: '#f43f5e',
                text: '#881337',
                tag: '#f43f5e'
            },
        ];

        function renderTimetable() {
            const semId = document.getElementById('selSemester').value;
            const semText = document.getElementById('selSemester').selectedOptions[0]?.text ?? '';

            const filtered = semId ?
                ALL_SCHEDULES.filter(s => String(s.semester_id) === String(semId)) :
                ALL_SCHEDULES;

            document.getElementById('semBadge').textContent = semId ? semText : 'Tất cả học kỳ';
            document.getElementById('filterLabel').textContent =
                `${semId ? semText : 'Tất cả'} · ${filtered.length} môn đã đăng ký`;

            // Gán màu theo index môn
            const colorMap = {};
            filtered.forEach((s, idx) => {
                colorMap[s.id] = COLORS[idx % COLORS.length];
            });

            const cellMap = {};
            const skipSet = new Set();

            filtered.forEach(sch => {
                sch.sessions.forEach(ss => {
                    const day = ss.day_of_week;
                    if (!DAYS.includes(day)) return;
                    const fi = findFirstSlot(ss.start_time);
                    const li = findLastSlot(ss.end_time);
                    const rowspan = Math.max(1, li - fi + 1);
                    const key = `${fi}_${day}`;
                    if (!cellMap[key]) {
                        cellMap[key] = {
                            sch,
                            ss,
                            rowspan
                        };
                        for (let r = 1; r < rowspan; r++) skipSet.add(`${fi+r}_${day}`);
                    }
                });
            });

            let html = '';
            SLOTS.forEach((slot, si) => {
                html += `<tr><td class="time-col">${slot.label}<small>${slot.start}–${slot.end}</small></td>`;
                DAYS.forEach(day => {
                    const key = `${si}_${day}`;
                    if (skipSet.has(key)) return;
                    const cell = cellMap[key];
                    if (cell) {
                        const {
                            sch,
                            ss,
                            rowspan
                        } = cell;
                        const c = colorMap[sch.id];
                        html += `<td rowspan="${rowspan}">
                    <div class="course-card" style="background:${c.bg};border-left-color:${c.border};">
                        <span class="course-code" style="color:${c.border};">Nhóm ${esc(sch.group_code||sch.id)}</span>
                        <div class="course-name" style="color:${c.text};">${esc(sch.subject_name)}</div>
                        <div class="course-info"><i class="fa-solid fa-user-tie"></i> ${esc(sch.teacher_name)}</div>
                        <div class="course-info"><i class="fa-regular fa-clock"></i> ${ss.start_time}–${ss.end_time}</div>
                        <div class="room-tag" style="background:${c.tag};">${esc(ss.room || '—')}</div>
                    </div>
                </td>`;
                    } else {
                        html += `<td><div class="empty-slot">—</div></td>`;
                    }
                });
                html += `</tr>`;
                if (si === 4) {
                    html +=
                        `<tr class="lunch-break"><td colspan="7"><i class="fa-solid fa-mug-hot"></i> NGHỈ TRƯA (11:35 – 12:30)</td></tr>`;
                }
            });

            document.getElementById('timetableBody').innerHTML = html;
        }

        // Khởi tạo
        const now = new Date();
        const jsDay = now.getDay();
        document.getElementById('todayLabel').textContent =
            `${jsDay===0?'Chủ nhật':'Thứ '+(jsDay+1)}, ${now.toLocaleDateString('vi-VN')}`;
        renderTimetable();
    </script>
@endsection
