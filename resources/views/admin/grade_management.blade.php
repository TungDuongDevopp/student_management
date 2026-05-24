@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Đăng ký học phần')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <style>
        .filter-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .filter-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .filter-select {
            padding: 0.6rem 1rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.875rem;
            min-width: 200px;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--accent);
        }

        .stats-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .stat-card {
            flex: 1;
            min-width: 130px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1rem 1.2rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
        }

        .stat-card .stat-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-total .stat-value {
            color: #60a5fa;
        }

        .stat-passed .stat-value {
            color: #4ade80;
        }

        .stat-failed .stat-value {
            color: #f87171;
        }

        .badge-score-pass {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
        }

        .badge-score-fail {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        .badge-score-none {
            background: rgba(148, 163, 184, 0.15);
            color: var(--text-muted);
        }

        .score-input {
            width: 70px;
            padding: 0.3rem 0.5rem;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text);
            font-size: 0.85rem;
            text-align: center;
        }

        .score-input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .btn-save-score {
            background: rgba(59, 130, 246, 0.15);
            color: var(--accent);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .btn-save-score:hover {
            background: rgba(59, 130, 246, 0.25);
        }

        .subject-name {
            font-weight: 600;
            max-width: 180px;
        }

        .student-name {
            font-weight: 500;
        }

        .muted {
            color: var(--text-muted);
            font-size: 0.8rem;
        }
    </style>

    <div class="content-wrapper">
        <div class="page-header">
            <h1>Quản lý Điểm</h1>
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-card stat-total">
                <span class="stat-value" id="statTotal">—</span>
                <span class="stat-label">Tổng đăng ký</span>
            </div>
            <div class="stat-card stat-passed">
                <span class="stat-value" id="statPassed">—</span>
                <span class="stat-label">Đã có điểm</span>
            </div>
            <div class="stat-card stat-failed">
                <span class="stat-value" id="statNone">—</span>
                <span class="stat-label">Chưa có điểm</span>
            </div>
        </div>

        {{-- Filters --}}
        <div class="filter-row">
            <div class="filter-group">
                <span class="filter-label">Học kỳ</span>
                <select class="filter-select" id="semesterFilter" onchange="applyFilters()">
                    <option value="all">Tất cả học kỳ</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Lớp học phần</span>
                <select class="filter-select" id="classFilter" onchange="applyFilters()" style="min-width:230px">
                    <option value="all">Tất cả lớp học phần</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Tìm kiếm</span>
                <input type="text" class="filter-select" id="searchInput" placeholder="Tên SV, môn học, mã SV..."
                    oninput="applyFilters()" style="min-width:220px">
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sinh viên</th>
                            <th>Môn học / Học kỳ</th>
                            <th style="text-align:center">Chuyên cần (10%)</th>
                            <th style="text-align:center">Giữa kỳ (30%)</th>
                            <th style="text-align:center">Cuối kỳ (60%)</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">Đang tải dữ liệu...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="pagination" id="pagination"></div>
        </div>

        {{-- Score Edit Modal --}}
        <div class="modal-overlay" id="scoreModal">
            <div class="modal" style="width:440px">
                <div class="modal-header">
                    <h2>✏️ Cập nhật điểm</h2>
                    <button class="modal-close" onclick="closeScoreModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="meta-info" id="scoreMeta"
                        style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;font-size:0.85rem;color:var(--text-muted)">
                    </div>
                    <div id="bannedAlert" style="display:none;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:6px;padding:0.5rem 0.75rem;margin-bottom:0.75rem;font-size:0.82rem;color:#f87171;">
                        🚫 <strong>Cấm thi:</strong> Điểm chuyên cần = 0, điểm cuối kỳ sẽ tự động là 0.
                    </div>
                    <div class="form-group" style="margin-bottom:0.5rem">
                        <label>Điểm Chuyên cần (10%)</label>
                        <input type="number" id="scoreCInput" min="0" max="10" step="0.1"
                            placeholder="Nhập điểm..." style="width:100%" oninput="onScoreCChange()">
                    </div>
                    <div class="form-group" style="margin-bottom:0.5rem">
                        <label>Điểm Giữa kỳ (30%)</label>
                        <input type="number" id="scoreBInput" min="0" max="10" step="0.1"
                            placeholder="Nhập điểm..." style="width:100%">
                    </div>
                    <div class="form-group">
                        <label>Điểm Cuối kỳ (60%)</label>
                        <input type="number" id="scoreAInput" min="0" max="10" step="0.1"
                            placeholder="Nhập điểm..." style="width:100%">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeScoreModal()">Hủy</button>
                    <button class="btn btn-primary" onclick="saveScore()">💾 Lưu điểm</button>
                </div>
            </div>
        </div>



        <div class="toast" id="toast"></div>
    </div>

    <script>
        const API = '/api/enrollments';
        const SEM_API = '/api/semesters';
        const PER_PAGE = 15;

        let allData = [];
        let filteredData = [];
        let allSemesters = [];
        let currentPage = 1;
        let editId = null;

        // ── BOOT ─────────────────────────────────────────────────────────────
        async function fetchData() {
            try {
                const [enrollRes, semRes] = await Promise.all([
                    fetch(API, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()),
                    fetch(SEM_API, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()),
                ]);
                allData = enrollRes;
                allSemesters = semRes;
                buildSemesterFilter();
                buildClassFilter();
                updateStats(allData);
                applyFilters();
            } catch (e) {
                showToast('Lỗi tải dữ liệu', 'error');
            }
        }

        // ── SEMESTER FILTER DROPDOWN ──────────────────────────────────────────
        function buildSemesterFilter() {
            const sel = document.getElementById('semesterFilter');
            sel.innerHTML = '<option value="all">Tất cả học kỳ</option>' +
                allSemesters.map(s =>
                    `<option value="${s.id}">${s.name}${s.academic_year ? ' – ' + s.academic_year : ''}</option>`
                ).join('');
        }

        // ── CLASS SECTION FILTER DROPDOWN ────────────────────────────────────
        function buildClassFilter() {
            // Collect unique schedules from loaded enrollments
            const seen = new Map();
            allData.forEach(e => {
                const sch = e.schedule;
                if (sch && !seen.has(sch.id)) {
                    const label = (sch.subject?.name || 'Môn học') +
                        (sch.group_code ? ` (Nhóm ${sch.group_code})` : ` (#${sch.id})`) +
                        (sch.teacher?.name ? ` – ${sch.teacher.name}` : '');
                    seen.set(sch.id, label);
                }
            });
            const sel = document.getElementById('classFilter');
            const sorted = [...seen.entries()].sort((a, b) => a[1].localeCompare(b[1], 'vi'));
            sel.innerHTML = '<option value="all">Tất cả lớp học phần</option>' +
                sorted.map(([id, label]) => `<option value="${id}">${escHtml(label)}</option>`).join('');
        }

        // ── STATS ─────────────────────────────────────────────────────────────
        function updateStats(data) {
            document.getElementById('statTotal').textContent = data.length;
            document.getElementById('statPassed').textContent = data.filter(e => e.grade?.final_score !== null && e.grade
                ?.final_score !== undefined).length;
            document.getElementById('statNone').textContent = data.filter(e => e.grade?.final_score === null || e.grade
                ?.final_score === undefined).length;
        }

        // ── FILTERS ───────────────────────────────────────────────────────────
        function applyFilters() {
            const semId     = document.getElementById('semesterFilter').value;
            const classId   = document.getElementById('classFilter').value;
            const q         = document.getElementById('searchInput').value.toLowerCase();

            filteredData = allData.filter(e => {
                const matchSem   = semId === 'all'   || String(e.schedule?.semester?.id) === semId;
                const matchClass = classId === 'all' || String(e.schedule?.id) === classId;
                const studentName  = (e.student?.name || '').toLowerCase();
                const studentCode  = (e.student?.student_code || '').toLowerCase();
                const subjectName  = (e.schedule?.subject?.name || '').toLowerCase();
                const matchSearch  = !q || studentName.includes(q) || studentCode.includes(q) || subjectName.includes(q);
                return matchSem && matchClass && matchSearch;
            });

            updateStats(filteredData);
            currentPage = 1;
            renderPage();
        }

        // ── RENDER ────────────────────────────────────────────────────────────
        function renderPage() {
            const totalPages = Math.max(1, Math.ceil(filteredData.length / PER_PAGE));
            if (currentPage > totalPages) currentPage = totalPages;
            const start = (currentPage - 1) * PER_PAGE;
            const pageData = filteredData.slice(start, start + PER_PAGE);
            const tb = document.getElementById('tableBody');

            if (!filteredData.length) {
                tb.innerHTML = '<tr><td colspan="8"><div class="empty-state">Không có đăng ký nào phù hợp</div></td></tr>';
                renderPagination(1);
                return;
            }

            tb.innerHTML = pageData.map(e => {
                const scoreC = e.grade?.score_c !== null && e.grade?.score_c !== undefined ? parseFloat(e.grade
                    .score_c).toFixed(1) : '—';
                const scoreB = e.grade?.score_b !== null && e.grade?.score_b !== undefined ? parseFloat(e.grade
                    .score_b).toFixed(1) : '—';
                const scoreA = e.grade?.score_a !== null && e.grade?.score_a !== undefined ? parseFloat(e.grade
                    .score_a).toFixed(1) : '—';

                // Cấm thi: chuyên cần = 0 hoặc chưa có
                const scoreCVal = e.grade?.score_c;
                const isBanned = scoreCVal !== null && scoreCVal !== undefined && parseFloat(scoreCVal) === 0;
                const bannedBadge = isBanned
                    ? `<span class="badge" style="background:rgba(239,68,68,0.15);color:#f87171;font-size:0.7rem;margin-left:4px;padding:1px 5px;border-radius:4px;">🚫 Cấm thi</span>`
                    : '';

                const score = e.grade?.final_score;
                const scoreBadge = (score === null || score === undefined) ?
                    `<span class="badge badge-score-none">Chưa có</span>` :
                    score >= 4 ? // Trong hệ đào tạo tín chỉ, điểm >= 4 là qua môn (D trở lên)
                    `<span class="badge badge-score-pass">${parseFloat(score).toFixed(1)}</span>` :
                    `<span class="badge badge-score-fail">${parseFloat(score).toFixed(1)}</span>`;

                const semName = e.schedule?.semester ?
                    `${e.schedule.semester.name}${e.schedule.semester.academic_year ? ' – ' + e.schedule.semester.academic_year : ''}` :
                    '—';

                const groupLabel = e.schedule?.group_code ? ` (Nhóm ${escHtml(e.schedule.group_code)})` : '';
                return `<tr>
                    <td><strong>#${e.id}</strong></td>
                    <td>
                        <div class="student-name">${escHtml(e.student?.name || '—')}</div>
                        <div class="muted">${escHtml(e.student?.student_code || '')} | Lớp: ${escHtml(e.student?.classroom?.code || '—')}</div>
                    </td>
                    <td>
                        <div class="subject-name">${escHtml(e.schedule?.subject?.name || '—')}${groupLabel}</div>
                        <div class="muted">GV: ${escHtml(e.schedule?.teacher?.name || '—')} | HK: ${escHtml(semName)}</div>
                    </td>
                    <td style="text-align:center;font-weight:500;">${scoreC}${bannedBadge}</td>
                    <td style="text-align:center;font-weight:500;">${scoreB}</td>
                    <td style="text-align:center;font-weight:500;">${isBanned ? '<span class="badge badge-score-fail">0.0 🚫</span>' : scoreA}</td>
                    <td><div class="actions">
                        <button class="btn btn-sm btn-edit" onclick='openScoreModal(${JSON.stringify(e)})'>Sửa điểm</button>
                    </div></td>
                </tr>`;
            }).join('');

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
                const pg = document.getElementById('pagination');
                if (totalPages <= 1) {
                    pg.innerHTML = '';
                    return;
                }
                let html = `<button onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}>‹</button>`;
                
                const delta = 2;
                const left = currentPage - delta;
                const right = currentPage + delta;
                const range = [];
                const rangeWithDots = [];
                let l;

                for (let i = 1; i <= totalPages; i++) {
                    if (i === 1 || i === totalPages || (i >= left && i <= right)) {
                        range.push(i);
                    }
                }

                for (let i of range) {
                    if (l) {
                        if (i - l === 2) {
                            rangeWithDots.push(l + 1);
                        } else if (i - l !== 1) {
                            rangeWithDots.push('...');
                        }
                    }
                    rangeWithDots.push(i);
                    l = i;
                }

                for (let i of rangeWithDots) {
                    if (i === '...') {
                        html += `<span class="page-dots">...</span>`;
                    } else {
                        html += `<button class="${i===currentPage?'active':''}" onclick="goPage(${i})">${i}</button>`;
                    }
                }
                
                html += `<span class="page-info">${filteredData.length} bản ghi</span>`;
                html += `<button onclick="goPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}>›</button>`;
                pg.innerHTML = html;
            }

        function goPage(p) {
            currentPage = p;
            renderPage();
        }

        // ── SCORE MODAL ───────────────────────────────────────────────────────
        function openScoreModal(e) {
            editId = e.id;
            const semName = e.schedule?.semester ?
                `${e.schedule.semester.name}${e.schedule.semester.academic_year ? ' – ' + e.schedule.semester.academic_year : ''}` :
                '—';
            document.getElementById('scoreMeta').innerHTML = `
                <span><strong>SV:</strong> ${escHtml(e.student?.name || '—')}</span>
                <span><strong>Môn:</strong> ${escHtml(e.schedule?.subject?.name || '—')}</span>
                <span><strong>HK:</strong> ${escHtml(semName)}</span>
            `;
            document.getElementById('scoreCInput').value = e.grade?.score_c !== null && e.grade?.score_c !== undefined ? e
                .grade.score_c : '';
            document.getElementById('scoreBInput').value = e.grade?.score_b !== null && e.grade?.score_b !== undefined ? e
                .grade.score_b : '';
            document.getElementById('scoreAInput').value = e.grade?.score_a !== null && e.grade?.score_a !== undefined ? e
                .grade.score_a : '';
            document.getElementById('scoreModal').classList.add('active');
        }

        function closeScoreModal() {
            document.getElementById('scoreModal').classList.remove('active');
            document.getElementById('bannedAlert').style.display = 'none';
            editId = null;
        }

        function onScoreCChange() {
            const val = document.getElementById('scoreCInput').value;
            const banned = val === '' || parseFloat(val) === 0;
            document.getElementById('bannedAlert').style.display = banned ? 'block' : 'none';
            if (banned) {
                document.getElementById('scoreAInput').value = 0;
                document.getElementById('scoreAInput').readOnly = true;
                document.getElementById('scoreAInput').style.background = 'rgba(239,68,68,0.08)';
            } else {
                document.getElementById('scoreAInput').readOnly = false;
                document.getElementById('scoreAInput').style.background = '';
            }
        }

        async function saveScore() {
            if (!editId) return;
            const valC = document.getElementById('scoreCInput').value;
            const valB = document.getElementById('scoreBInput').value;
            const valA = document.getElementById('scoreAInput').value;
            const body = {
                score_c: valC === '' ? null : parseFloat(valC),
                score_b: valB === '' ? null : parseFloat(valB),
                score_a: valA === '' ? null : parseFloat(valA),
            };
            try {
                const res = await fetch(`${API}/${editId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });
                if (!res.ok) throw new Error((await res.json()).message || 'Lỗi');
                showToast('Cập nhật điểm thành công!', 'success');
                closeScoreModal();
                fetchData();
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        // ── UTILS ─────────────────────────────────────────────────────────────
        function escHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = `toast toast-${type} show`;
            setTimeout(() => t.classList.remove('show'), 3000);
        }

        document.getElementById('scoreModal').addEventListener('click', function(e) {
            if (e.target === this) closeScoreModal();
        });

        fetchData();
    </script>
@endsection
