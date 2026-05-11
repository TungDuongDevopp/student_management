@extends('layouts.admin.sidebar')
@section('title', 'Dashboard')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <style>
        /* ── Stats cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.2rem 1.4rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
            cursor: default;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(0,0,0,.35);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 14px 14px 0 0;
        }
        .sc-blue::before   { background: linear-gradient(90deg,#3b82f6,#60a5fa); }
        .sc-green::before  { background: linear-gradient(90deg,#22c55e,#4ade80); }
        .sc-purple::before { background: linear-gradient(90deg,#8b5cf6,#a78bfa); }
        .sc-yellow::before { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
        .sc-red::before    { background: linear-gradient(90deg,#ef4444,#f87171); }
        .sc-cyan::before   { background: linear-gradient(90deg,#06b6d4,#67e8f9); }

        .stat-icon {
            font-size: 1.6rem;
            line-height: 1;
            margin-bottom: .1rem;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }
        .sc-blue   .stat-value { color: #60a5fa; }
        .sc-green  .stat-value { color: #4ade80; }
        .sc-purple .stat-value { color: #a78bfa; }
        .sc-yellow .stat-value { color: #fbbf24; }
        .sc-red    .stat-value { color: #f87171; }
        .sc-cyan   .stat-value { color: #67e8f9; }

        .stat-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 600;
        }
        .stat-sub {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: .1rem;
        }

        /* ── Charts grid ── */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }
        .charts-grid.full { grid-template-columns: 1fr; }

        @media (max-width: 900px) {
            .charts-grid { grid-template-columns: 1fr; }
        }

        .chart-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.4rem;
        }
        .chart-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1rem;
        }
        .chart-title span {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 400;
            margin-left: .5rem;
        }
        .chart-wrap {
            position: relative;
            height: 240px;
        }
        .chart-wrap.tall { height: 280px; }

        /* ── Recent feedbacks table ── */
        .recent-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.4rem;
            margin-bottom: 1.25rem;
        }
        .recent-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .recent-title {
            font-size: 1rem;
            font-weight: 700;
        }
        .recent-link {
            font-size: 0.8rem;
            color: var(--accent);
            text-decoration: none;
        }
        .recent-link:hover { text-decoration: underline; }

        .fb-list { display: flex; flex-direction: column; gap: .6rem; }
        .fb-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: .7rem 1rem;
            border-radius: 10px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            transition: background .15s;
        }
        .fb-item:hover { background: rgba(59,130,246,.06); }
        .fb-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            margin-top: .4rem;
            flex-shrink: 0;
        }
        .fb-dot.unread { background: #fbbf24; }
        .fb-dot.read   { background: #4ade80; }
        .fb-body { flex: 1; min-width: 0; }
        .fb-sender {
            font-weight: 600;
            font-size: .875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .fb-content {
            font-size: .8rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .fb-time {
            font-size: .75rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* ── Semester highlight ── */
        .sem-banner {
            background: linear-gradient(135deg, rgba(59,130,246,.15), rgba(139,92,246,.15));
            border: 1px solid rgba(59,130,246,.3);
            border-radius: 14px;
            padding: 1.2rem 1.6rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        .sem-label {
            font-size: .75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .06em;
        }
        .sem-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #a78bfa;
        }
        .sem-stat { text-align: center; }
        .sem-stat-val { font-size: 1.5rem; font-weight: 800; color: var(--text); }
        .sem-stat-lbl { font-size: .72rem; color: var(--text-muted); text-transform: uppercase; }

        .loading-shimmer {
            background: linear-gradient(90deg, var(--bg-card) 25%, var(--border) 50%, var(--bg-card) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
            border-radius: 6px;
            height: 2rem;
            width: 60%;
        }
        @keyframes shimmer { to { background-position: -200% 0; } }
    </style>

    <div class="content-wrapper">
        <div class="page-header">
            <h1>Dashboard</h1>
            <span style="font-size:.85rem;color:var(--text-muted)" id="lastUpdated"></span>
        </div>

        {{-- Active semester banner --}}
        <div class="sem-banner" id="semBanner" style="display:none">
            <div>
                <div class="sem-label">Học kỳ đang hoạt động</div>
                <div class="sem-name" id="semName">—</div>
                <div class="sem-label" id="semYear"></div>
            </div>
            <div class="sem-stat">
                <div class="sem-stat-val" id="semSchedules">—</div>
                <div class="sem-stat-lbl">Lớp học phần</div>
            </div>
            <div class="sem-stat">
                <div class="sem-stat-val" id="semEnrollments">—</div>
                <div class="sem-stat-lbl">Lượt đăng ký</div>
            </div>
            <div class="sem-stat">
                <div class="sem-stat-val" id="semDebt">—</div>
                <div class="sem-stat-lbl">SV nợ học phí</div>
            </div>
        </div>

        {{-- Stats cards --}}
        <div class="stats-grid">
            <div class="stat-card sc-blue">
                <div class="stat-icon">🎓</div>
                <div class="stat-value" id="cntStudents">—</div>
                <div class="stat-label">Sinh viên</div>
            </div>
            <div class="stat-card sc-purple">
                <div class="stat-icon">👨‍🏫</div>
                <div class="stat-value" id="cntTeachers">—</div>
                <div class="stat-label">Giảng viên</div>
            </div>
            <div class="stat-card sc-cyan">
                <div class="stat-icon">🏛️</div>
                <div class="stat-value" id="cntFaculties">—</div>
                <div class="stat-label">Khoa</div>
            </div>
            <div class="stat-card sc-green">
                <div class="stat-icon">📚</div>
                <div class="stat-value" id="cntSubjects">—</div>
                <div class="stat-label">Môn học</div>
            </div>
            <div class="stat-card sc-yellow">
                <div class="stat-icon">💬</div>
                <div class="stat-value" id="cntFeedbacks">—</div>
                <div class="stat-label">Phản hồi chưa xem</div>
            </div>
            <div class="stat-card sc-red">
                <div class="stat-icon">💸</div>
                <div class="stat-value" id="cntDebt">—</div>
                <div class="stat-label">Nợ học phí</div>
            </div>
        </div>

        {{-- Charts row 1 --}}
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-title">Học phí theo học kỳ <span>Đã đóng vs Còn nợ (triệu đồng)</span></div>
                <div class="chart-wrap tall"><canvas id="chartTuition"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-title">Sinh viên theo khoa <span>Phân bổ</span></div>
                <div class="chart-wrap tall"><canvas id="chartFaculty"></canvas></div>
            </div>
        </div>

        {{-- Charts row 2 --}}
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-title">Đăng ký học phần <span>Số SV theo lớp học phần (top 8)</span></div>
                <div class="chart-wrap"><canvas id="chartEnroll"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-title">Tình trạng học phí <span>Toàn hệ thống</span></div>
                <div class="chart-wrap" style="display:flex;align-items:center;justify-content:center">
                    <canvas id="chartDebtDonut" style="max-height:240px;max-width:300px"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent feedbacks --}}
        <div class="recent-card">
            <div class="recent-header">
                <div class="recent-title">💬 Phản hồi chưa xem gần nhất</div>
                <a href="{{ route('admin.feedbacks') }}" class="recent-link">Xem tất cả →</a>
            </div>
            <div class="fb-list" id="fbList">
                <div style="color:var(--text-muted);font-size:.875rem">Đang tải...</div>
            </div>
        </div>
    </div>

    <script>
    // ── Chart.js global defaults ──────────────────────────────────────────────
    Chart.defaults.color          = '#94a3b8';
    Chart.defaults.borderColor    = '#334155';
    Chart.defaults.font.family    = 'Inter, sans-serif';
    Chart.defaults.font.size      = 12;
    Chart.defaults.plugins.legend.labels.boxWidth = 12;

    const ACCENT  = '#3b82f6';
    const PALETTE = ['#3b82f6','#8b5cf6','#22c55e','#f59e0b','#ef4444','#06b6d4','#ec4899','#f97316','#a3e635','#e879f9'];

    let charts = {};

    // ── BOOT ─────────────────────────────────────────────────────────────────
    async function boot() {
        document.getElementById('lastUpdated').textContent =
            'Cập nhật: ' + new Date().toLocaleTimeString('vi-VN');

        try {
            const [students, teachers, faculties, subjects, feedbacks,
                   tuitions, schedules, enrollments, semesters] = await Promise.all([
                fetch('/api/students').then(r => r.json()),
                fetch('/api/teachers').then(r => r.json()),
                fetch('/api/faculties').then(r => r.json()),
                fetch('/api/subjects').then(r => r.json()),
                fetch('/api/feedbacks').then(r => r.json()),
                fetch('/api/tuitions').then(r => r.json()),
                fetch('/api/schedules').then(r => r.json()),
                fetch('/api/enrollments').then(r => r.json()),
                fetch('/api/semesters').then(r => r.json()),
            ]);

            fillStatCards(students, teachers, faculties, subjects, feedbacks, tuitions);
            fillSemBanner(semesters, schedules, enrollments, tuitions);
            renderTuitionChart(tuitions, semesters);
            renderFacultyChart(students, faculties);
            renderEnrollChart(schedules);
            renderDebtDonut(tuitions);
            renderRecentFeedbacks(feedbacks);
        } catch (e) {
            console.error(e);
        }
    }

    // ── STAT CARDS ────────────────────────────────────────────────────────────
    function fillStatCards(students, teachers, faculties, subjects, feedbacks, tuitions) {
        document.getElementById('cntStudents').textContent  = students.length;
        document.getElementById('cntTeachers').textContent  = teachers.length;
        document.getElementById('cntFaculties').textContent = faculties.length;
        document.getElementById('cntSubjects').textContent  = subjects.length;

        const unread = feedbacks.filter(f => f.status == 0).length;
        document.getElementById('cntFeedbacks').textContent = unread;

        const debt = tuitions.filter(t => {
            const total = parseFloat(t.total_amount) || 0;
            const paid  = parseFloat(t.paid_amount)  || 0;
            return total > 0 && paid < total;
        }).length;
        document.getElementById('cntDebt').textContent = debt;
    }

    // ── SEMESTER BANNER ───────────────────────────────────────────────────────
    function fillSemBanner(semesters, schedules, enrollments, tuitions) {
        const active = semesters.find(s => s.status == 1);
        if (!active) return;

        const banner = document.getElementById('semBanner');
        banner.style.display = 'flex';
        document.getElementById('semName').textContent = active.name;
        document.getElementById('semYear').textContent = active.academic_year || '';

        const semSched = schedules.filter(s => s.semester_id == active.id);
        const semEnrolls = enrollments.filter(e => {
            return semSched.some(s => s.id == e.schedule_id);
        });
        const semDebt = tuitions.filter(t => {
            if (t.semester_id != active.id) return false;
            return (parseFloat(t.paid_amount) || 0) < (parseFloat(t.total_amount) || 0);
        }).length;

        document.getElementById('semSchedules').textContent  = semSched.length;
        document.getElementById('semEnrollments').textContent = semEnrolls.length;
        document.getElementById('semDebt').textContent       = semDebt;
    }

    // ── CHART 1: Học phí theo học kỳ ─────────────────────────────────────────
    function renderTuitionChart(tuitions, semesters) {
        // Lấy tối đa 6 học kỳ gần nhất
        const semList = semesters.slice(-6);
        const labels  = semList.map(s => s.name + (s.academic_year ? ' ' + s.academic_year : ''));

        const paidData  = semList.map(s => {
            const rows = tuitions.filter(t => t.semester_id == s.id);
            return +(rows.reduce((sum, t) => sum + (parseFloat(t.paid_amount) || 0), 0) / 1e6).toFixed(2);
        });
        const debtData  = semList.map(s => {
            const rows = tuitions.filter(t => t.semester_id == s.id);
            return +(rows.reduce((sum, t) => {
                const rem = (parseFloat(t.total_amount) || 0) - (parseFloat(t.paid_amount) || 0);
                return sum + Math.max(0, rem);
            }, 0) / 1e6).toFixed(2);
        });

        if (charts.tuition) charts.tuition.destroy();
        charts.tuition = new Chart(document.getElementById('chartTuition'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Đã đóng (triệu)', data: paidData,  backgroundColor: 'rgba(34,197,94,.75)',  borderRadius: 6 },
                    { label: 'Còn nợ (triệu)',  data: debtData,  backgroundColor: 'rgba(239,68,68,.65)', borderRadius: 6 },
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: { grid: { color: '#1e293b' } },
                    y: { grid: { color: '#1e293b' }, beginAtZero: true,
                         ticks: { callback: v => v + 'M' } }
                }
            }
        });
    }

    // ── CHART 2: SV theo khoa ─────────────────────────────────────────────────
    function renderFacultyChart(students, faculties) {
        const labels = faculties.map(f => f.name);
        const data   = faculties.map(f => students.filter(s => {
            // students may have classroom → classroom.faculty_id, or direct faculty_id
            return s.classroom?.faculty_id == f.id;
        }).length);

        if (charts.faculty) charts.faculty.destroy();
        charts.faculty = new Chart(document.getElementById('chartFaculty'), {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{ data, backgroundColor: PALETTE, borderWidth: 2, borderColor: '#1e293b' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { padding: 14 } }
                },
                cutout: '62%'
            }
        });
    }

    // ── CHART 3: Đăng ký học phần (top 8) ────────────────────────────────────
    function renderEnrollChart(schedules) {
        const sorted = [...schedules]
            .filter(s => (s.enrollments_count || 0) > 0)
            .sort((a, b) => (b.enrollments_count || 0) - (a.enrollments_count || 0))
            .slice(0, 8);

        const labels = sorted.map(s => (s.subject?.name || 'Môn #' + s.id).substring(0, 22));
        const data   = sorted.map(s => s.enrollments_count || 0);

        if (charts.enroll) charts.enroll.destroy();
        charts.enroll = new Chart(document.getElementById('chartEnroll'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Số SV đăng ký',
                    data,
                    backgroundColor: PALETTE.map(c => c + 'cc'),
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: '#1e293b' }, beginAtZero: true, ticks: { stepSize: 1 } },
                    y: { grid: { display: false } }
                }
            }
        });
    }

    // ── CHART 4: Donut học phí ────────────────────────────────────────────────
    function renderDebtDonut(tuitions) {
        let paid = 0, partial = 0, unpaid = 0;
        tuitions.forEach(t => {
            const total = parseFloat(t.total_amount) || 0;
            const p     = parseFloat(t.paid_amount)  || 0;
            if (total === 0 || p >= total) paid++;
            else if (p > 0) partial++;
            else unpaid++;
        });

        if (charts.debt) charts.debt.destroy();
        charts.debt = new Chart(document.getElementById('chartDebtDonut'), {
            type: 'doughnut',
            data: {
                labels: ['Đã đóng đủ', 'Còn nợ một phần', 'Chưa đóng'],
                datasets: [{
                    data: [paid, partial, unpaid],
                    backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
                    borderWidth: 2, borderColor: '#1e293b',
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16 } },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct   = total ? Math.round(ctx.raw / total * 100) : 0;
                                return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    // ── RECENT FEEDBACKS ──────────────────────────────────────────────────────
    function renderRecentFeedbacks(feedbacks) {
        const unread = feedbacks
            .filter(f => f.status == 0)
            .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
            .slice(0, 5);

        const list = document.getElementById('fbList');
        if (!unread.length) {
            list.innerHTML = '<div style="color:var(--text-muted);font-size:.875rem">🎉 Không có phản hồi nào chưa xem.</div>';
            return;
        }
        list.innerHTML = unread.map(f => {
            const name = f.account?.name || f.account?.username || 'Ẩn danh';
            const time = f.created_at
                ? new Date(f.created_at).toLocaleDateString('vi-VN')
                : '';
            const text = (f.content || '').substring(0, 80);
            return `<div class="fb-item">
                <div class="fb-dot unread"></div>
                <div class="fb-body">
                    <div class="fb-sender">${escHtml(name)}</div>
                    <div class="fb-content">${escHtml(text)}${f.content?.length > 80 ? '…' : ''}</div>
                </div>
                <div class="fb-time">${time}</div>
            </div>`;
        }).join('');
    }

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    boot();
    </script>
@endsection
