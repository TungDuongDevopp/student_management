@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Lịch học')
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
            min-width: 180px;
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
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .stat-total .stat-value {
            color: #60a5fa;
        }

        .stat-enroll .stat-value {
            color: #4ade80;
        }

        .stat-empty .stat-value {
            color: #f87171;
        }

        .subject-cell {
            font-weight: 600;
        }

        .muted {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .enroll-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: rgba(96, 165, 250, 0.15);
            color: #60a5fa;
            border-radius: 999px;
            padding: 0.2rem 0.65rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .day-badge {
            background: rgba(167, 139, 250, 0.15);
            color: #a78bfa;
            border-radius: 6px;
            padding: 0.15rem 0.55rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .shift-badge {
            background: rgba(251, 191, 36, 0.12);
            color: #fbbf24;
            border-radius: 6px;
            padding: 0.15rem 0.55rem;
            font-size: 0.8rem;
            font-weight: 600;
        }
    </style>

    <div class="content-wrapper">
        <div class="page-header">
            <h1>Quản lý Lớp học phần</h1>
            <button class="btn btn-primary" onclick="openAdd()">+ Thêm lớp học phần</button>
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-card stat-total">
                <span class="stat-value" id="statTotal">—</span>
                <span class="stat-label">Tổng lớp học phần</span>
            </div>
            <div class="stat-card stat-enroll">
                <span class="stat-value" id="statEnrolled">—</span>
                <span class="stat-label">Có sinh viên đăng ký</span>
            </div>
            <div class="stat-card stat-empty">
                <span class="stat-value" id="statEmpty">—</span>
                <span class="stat-label">Chưa có SV đăng ký</span>
            </div>
        </div>

        {{-- Filters --}}
        <div class="filter-row">
            <div class="filter-group">
                <span class="filter-label">Học kỳ</span>
                <select class="filter-select" id="semFilter" onchange="applyFilters()">
                    <option value="all">Tất cả học kỳ</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Khoa</span>
                <select class="filter-select" id="facFilter" onchange="applyFilters()">
                    <option value="all">Tất cả khoa</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Tìm kiếm</span>
                <input type="text" class="filter-select" id="searchInput" placeholder="Môn học, GV, phòng, lớp..."
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
                            <th>Môn học</th>
                            <th>Giảng viên</th>
                            <th>Phòng</th>
                            <th>Học kỳ</th>
                            <th>Lịch học</th>
                            <th>Nhóm</th>
                            <th>Sĩ số</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">Đang tải dữ liệu...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="pagination" id="pagination"></div>
        </div>

        {{-- Add / Edit Modal --}}
        <div class="modal-overlay" id="formModal">
            <div class="modal" style="width:560px">
                <div class="modal-header">
                    <h2 id="modalTitle">Thêm lớp học phần</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="entityForm">
                        <input type="hidden" id="entityId">

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                            <div class="form-group" style="grid-column:span 2">
                                <label>Môn học *</label>
                                <select id="fSubject" required>
                                    <option value="">-- Chọn môn học --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Giảng viên</label>
                                <select id="fTeacher">
                                    <option value="">-- Chọn GV --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Học kỳ *</label>
                                <select id="fSemester" required>
                                    <option value="">-- Chọn học kỳ --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Mã nhóm</label>
                                <input type="text" id="fGroupCode" placeholder="VD: N01, Nhóm 1" maxlength="20">
                            </div>
                            <div class="form-group">
                                <label>Sĩ số tối đa</label>
                                <input type="number" id="fMaxCapacity" value="40" min="1" max="500">
                            </div>
                            <div class="form-group">
                                <label>Ngày bắt đầu</label>
                                <input type="date" id="fStartDate">
                            </div>
                            <div class="form-group">
                                <label>Ngày kết thúc</label>
                                <input type="date" id="fEndDate">
                            </div>
                        </div>

                        {{-- Danh sách buổi học --}}
                        <div style="margin-top:1rem">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem">
                                <label style="font-weight:600;font-size:0.85rem">Lịch các buổi trong tuần</label>
                                <button type="button" class="btn btn-sm" onclick="addSession()"
                                    style="background:var(--accent);color:#fff;border:none;padding:0.3rem 0.75rem;border-radius:6px;cursor:pointer;font-size:0.8rem">
                                    + Thêm buổi
                                </button>
                            </div>
                            <div id="sessionList"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                    <button class="btn btn-primary" onclick="saveEntity()">💾 Lưu</button>
                </div>
            </div>
        </div>

        {{-- Delete Confirm Modal --}}
        <div class="modal-overlay" id="deleteModal">
            <div class="modal" style="width:400px">
                <div class="modal-header">
                    <h2>Xác nhận xóa</h2>
                    <button class="modal-close" onclick="closeDelete()">&times;</button>
                </div>
                <div class="modal-body">
                    <p style="color:var(--text-muted)">
                        Xóa lớp học phần này sẽ ảnh hưởng tới tất cả SV đã đăng ký và điểm danh liên quan. Bạn có chắc
                        không?
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeDelete()">Hủy</button>
                    <button class="btn btn-delete" onclick="confirmDelete()">🗑 Xóa</button>
                </div>
            </div>
        </div>

        <div class="toast" id="toast"></div>
    </div>

    <script>
        const API = '/api/schedules';
        const PER_PAGE = 15;

        // lookup tables
        let allData = [];
        let allSemesters = [];
        let allSubjects = [];
        let allTeachers = [];
        let allRooms = [];
        let allFaculties = [];
        let ROOM_OPTIONS = '';

        let filteredData = [];
        let currentPage = 1;
        let deleteId = null;

        const DAY_MAP = {
            2: 'Thứ 2',
            3: 'Thứ 3',
            4: 'Thứ 4',
            5: 'Thứ 5',
            6: 'Thứ 6',
            7: 'Thứ 7',
            8: 'CN'
        };
        // Hàm format giờ từ "HH:MM:SS" hoặc "HH:MM" → "HH:MM"
        function fmtTime(t) {
            if (!t) return '';
            return t.substring(0, 5);
        }

        // ── BOOT ─────────────────────────────────────────────────────────────
        async function fetchData() {
            try {
                const [schRes, semRes, subRes, teaRes, romRes, facRes] = await Promise.all([
                    fetch(API).then(r => r.json()),
                    fetch('/api/semesters').then(r => r.json()),
                    fetch('/api/subjects').then(r => r.json()),
                    fetch('/api/teachers').then(r => r.json()),
                    fetch('/api/rooms').then(r => r.json()),
                    fetch('/api/faculties').then(r => r.json()),
                ]);
                allData = schRes;
                allSemesters = semRes;
                allSubjects = subRes;
                allTeachers = teaRes;
                allRooms = romRes;
                ROOM_OPTIONS = allRooms.map(x =>
                    `<option value="${x.id}">${x.block ? x.block + '.' : ''}${x.name}</option>`
                ).join('');
                allFaculties = facRes;
                buildFilters();
                applyFilters();
            } catch (e) {
                showToast('Lỗi tải dữ liệu', 'error');
            }
        }

        // ── FILTER DROPDOWNS ──────────────────────────────────────────────────
        function buildFilters() {
            document.getElementById('semFilter').innerHTML =
                '<option value="all">Tất cả học kỳ</option>' +
                allSemesters.map(s =>
                    `<option value="${s.id}">${s.name}${s.academic_year ? ' – ' + s.academic_year : ''}</option>`
                ).join('');

            document.getElementById('facFilter').innerHTML =
                '<option value="all">Tất cả khoa</option>' +
                allFaculties.map(f => `<option value="${f.id}">${f.name}</option>`).join('');
        }

        // ── STATS ─────────────────────────────────────────────────────────────
        function updateStats(data) {
            document.getElementById('statTotal').textContent = data.length;
            document.getElementById('statEnrolled').textContent = data.filter(s => (s.current_capacity || 0) > 0).length;
            document.getElementById('statEmpty').textContent = data.filter(s => (s.current_capacity || 0) === 0).length;
        }

        // ── APPLY FILTERS ─────────────────────────────────────────────────────
        function applyFilters() {
            const semId = document.getElementById('semFilter').value;
            const facId = document.getElementById('facFilter').value;
            const q = document.getElementById('searchInput').value.toLowerCase();

            filteredData = allData.filter(s => {
                const matchSem = semId === 'all' || String(s.semester_id) === semId;
                const subFacId = s.subject?.faculty_id;
                const matchFac = facId === 'all' || String(subFacId) === facId;
                const roomNames = (s.sessions && s.sessions.length) ?
                    s.sessions.filter(ss => ss.room).map(ss => `${ss.room.block ? ss.room.block + '.' : ''}${ss.room.name}`).join(' ').toLowerCase() :
                    '';
                const matchQ = !q ||
                    (s.subject?.name || '').toLowerCase().includes(q) ||
                    (s.teacher?.name || '').toLowerCase().includes(q) ||
                    roomNames.includes(q);
                return matchSem && matchFac && matchQ;
            });

            updateStats(filteredData);
            currentPage = 1;
            renderPage();
        }

        // ── RENDER TABLE ──────────────────────────────────────────────────────
        function renderPage() {
            const totalPages = Math.max(1, Math.ceil(filteredData.length / PER_PAGE));
            if (currentPage > totalPages) currentPage = totalPages;
            const start = (currentPage - 1) * PER_PAGE;
            const pageData = filteredData.slice(start, start + PER_PAGE);
            const tb = document.getElementById('tableBody');

            if (!filteredData.length) {
                tb.innerHTML =
                    '<tr><td colspan="9"><div class="empty-state">Không có lớp học phần nào phù hợp</div></td></tr>';
                renderPagination(1);
                return;
            }

            tb.innerHTML = pageData.map(s => {
                const semName = s.semester ?
                    `${s.semester.name}${s.semester.academic_year ? ' – ' + s.semester.academic_year : ''}` :
                    '—';

                let subjectInfo = `<div class="subject-cell">${escHtml(s.subject?.name || '—')}</div>`;
                subjectInfo +=
                    `<div class="muted">${escHtml(s.subject?.credits ? s.subject.credits + ' tín chỉ' : '')}</div>`;
                if (s.start_date && s.end_date) {
                    subjectInfo +=
                        `<div style="font-size:0.75rem;color:#64748b;margin-top:4px;">📅 ${new Date(s.start_date).toLocaleDateString('vi-VN')} - ${new Date(s.end_date).toLocaleDateString('vi-VN')}</div>`;
                }

                const room = (s.sessions && s.sessions.length) ?
                    [...new Set(s.sessions.filter(ss => ss.room).map(ss => `${ss.room.block ? ss.room.block + '.' : ''}${ss.room.name}`))].join(', ') :
                    '—';

                // Hiển thị tất cả buổi học
                const sessionsHtml = (s.sessions && s.sessions.length) ?
                    s.sessions.map(ss => {
                        const rName = ss.room ? ` (${ss.room.block ? ss.room.block + '.' : ''}${ss.room.name})` : '';
                        return `<div class="day-badge" style="display:block;margin-bottom:4px;padding:4px;border:1px solid #e2e8f0;">` +
                            `${DAY_MAP[ss.day_of_week] || ''} ` +
                            `<span class="shift-badge">${fmtTime(ss.start_time)}–${fmtTime(ss.end_time)}</span>` +
                            `<span style="font-size:0.75rem;font-weight:700;color:var(--accent);margin-left:4px;">${rName}</span>` +
                            `</div>`;
                    }).join('') :
                    '—';

                const group = s.group_code ?
                    `<span class="badge badge-credits">${escHtml(s.group_code)}</span>` :
                    '—';
                const cnt = s.current_capacity || 0;

                return `<tr>
                    <td><strong>#${s.id}</strong></td>
                    <td>
                        ${subjectInfo}
                    </td>
                    <td>${escHtml(s.teacher?.name || '—')}</td>
                    <td>${escHtml(room)}</td>
                    <td><span class="badge badge-semester">${escHtml(semName)}</span></td>
                    <td>${sessionsHtml}</td>
                    <td>${group}</td>
                    <td>
                        <span class="enroll-badge" style="${cnt >= (s.max_capacity || 40) ? 'background:#fee2e2;color:#991b1b' : ''}">
                            👥 ${cnt} / ${s.max_capacity || 40}
                        </span>
                        <div style="font-size:0.75rem; color:#64748b; margin-top:3px; font-weight:600;">Còn ${Math.max(0, (s.max_capacity || 40) - cnt)} chỗ</div>
                    </td>
                    <td><div class="actions">
                        <button class="btn btn-sm btn-edit" onclick='editEntity(${JSON.stringify(s)})'>Sửa</button>
                        <button class="btn btn-sm btn-delete" onclick="openDelete(${s.id})">🗑</button>
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

        // ── POPULATE MODAL DROPDOWNS ──────────────────────────────────────────
        function populateDropdowns(s = {}) {
            document.getElementById('fSubject').innerHTML =
                '<option value="">-- Chọn môn học --</option>' +
                allSubjects.map(x =>
                    `<option value="${x.id}" ${x.id == s.subject_id ? 'selected' : ''}>${x.name}${x.credits ? ' (' + x.credits + ' TC)' : ''}</option>`
                ).join('');

            document.getElementById('fTeacher').innerHTML =
                '<option value="">-- Chọn GV --</option>' +
                allTeachers.map(x =>
                    `<option value="${x.id}" ${x.id == s.teacher_id ? 'selected' : ''}>${x.name}${x.teacher_code ? ' (' + x.teacher_code + ')' : ''}</option>`
                ).join('');

            // Chỉ hiển thị HK đang hoạt động (status=1), nhưng giữ HK hiện tại nếu đang sửa
            const activeSemesters = allSemesters.filter(x => x.status == 1 || x.id == s.semester_id);
            document.getElementById('fSemester').innerHTML =
                '<option value="">-- Chọn học kỳ --</option>' +
                activeSemesters.map(x =>
                    `<option value="${x.id}" ${x.id == s.semester_id ? 'selected' : ''}>${x.name}${x.academic_year ? ' – ' + x.academic_year : ''}${x.status != 1 ? ' (đã kết thúc)' : ''}</option>`
                ).join('');

            document.getElementById('fGroupCode').value = s.group_code || '';
            document.getElementById('fMaxCapacity').value = s.max_capacity || 40;
            document.getElementById('fStartDate').value = s.start_date || '';
            document.getElementById('fEndDate').value = s.end_date || '';

            // Render danh sách buổi học
            renderSessionList(s.sessions || []);
        }

        // ── SESSION LIST ──────────────────────────────────────────────────────
        const DAY_OPTIONS = [2, 3, 4, 5, 6, 7, 8].map(d =>
            `<option value="${d}">${DAY_MAP[d]}</option>`
        ).join('');

        function renderSessionList(sessions) {
            const list = document.getElementById('sessionList');
            list.innerHTML = '';
            if (sessions.length === 0) {
                addSession();
            } else {
                sessions.forEach(ss => addSession(ss));
            }
        }

        function addSession(data = {}) {
            const list = document.getElementById('sessionList');
            const idx = list.children.length;
            const row = document.createElement('div');
            row.className = 'session-row';
            row.style.cssText =
                'display:grid;grid-template-columns:1.5fr 1.5fr 1fr 1fr auto;gap:0.5rem;align-items:center;margin-bottom:0.5rem;padding:0.5rem;background:var(--bg-card);border:1px solid var(--border);border-radius:8px';
            row.innerHTML = `
                <select class="sess-day" style="padding:0.4rem;border:1px solid var(--border);border-radius:6px;background:var(--bg-card);color:var(--text)" title="Thứ">
                    <option value="">-- Thứ --</option>
                    ${DAY_OPTIONS}
                </select>
                <select class="sess-room" style="padding:0.4rem;border:1px solid var(--border);border-radius:6px;background:var(--bg-card);color:var(--text)" title="Phòng học">
                    <option value="">-- Phòng --</option>
                    ${ROOM_OPTIONS}
                </select>
                <input type="time" class="sess-start" step="300" title="Giờ bắt đầu"
                    style="padding:0.4rem;border:1px solid var(--border);border-radius:6px;background:var(--bg-card);color:var(--text)">
                <input type="time" class="sess-end" step="300" title="Giờ kết thúc"
                    style="padding:0.4rem;border:1px solid var(--border);border-radius:6px;background:var(--bg-card);color:var(--text)">
                <button type="button" onclick="this.closest('.session-row').remove()"
                    style="padding:0.35rem 0.6rem;background:#ef4444;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:0.8rem"
                    title="Xóa buổi">✕</button>
            `;
            // Điền dữ liệu nếu có
            if (data.day_of_week) row.querySelector('.sess-day').value = data.day_of_week;
            if (data.room_id) row.querySelector('.sess-room').value = data.room_id;
            if (data.start_time) row.querySelector('.sess-start').value = fmtTime(data.start_time);
            if (data.end_time) row.querySelector('.sess-end').value = fmtTime(data.end_time);
            list.appendChild(row);
        }

        function collectSessions() {
            return [...document.querySelectorAll('#sessionList .session-row')].map(row => ({
                day_of_week: parseInt(row.querySelector('.sess-day').value) || null,
                room_id: parseInt(row.querySelector('.sess-room').value) || null,
                start_time: row.querySelector('.sess-start').value || null,
                end_time: row.querySelector('.sess-end').value || null,
            })).filter(ss => ss.day_of_week || ss.room_id || ss.start_time);
        }

        // ── ADD / EDIT ────────────────────────────────────────────────────────
        function openAdd() {
            document.getElementById('modalTitle').textContent = 'Thêm lớp học phần';
            document.getElementById('entityId').value = '';
            document.getElementById('entityForm').reset();
            populateDropdowns();
            renderSessionList([]);
            document.getElementById('formModal').classList.add('active');
        }

        function editEntity(s) {
            document.getElementById('modalTitle').textContent = 'Cập nhật lớp học phần';
            document.getElementById('entityId').value = s.id;
            populateDropdowns(s);
            document.getElementById('formModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('formModal').classList.remove('active');
        }

        async function saveEntity() {
            const id = document.getElementById('entityId').value;
            const get = id => document.getElementById(id).value;

            const body = {
                subject_id: parseInt(get('fSubject')) || null,
                teacher_id: parseInt(get('fTeacher')) || null,
                semester_id: parseInt(get('fSemester')) || null,
                group_code: get('fGroupCode').trim() || null,
                max_capacity: parseInt(get('fMaxCapacity')) || 40,
                start_date: get('fStartDate') || null,
                end_date: get('fEndDate') || null,
                sessions: collectSessions(),
            };

            if (!body.subject_id || !body.semester_id) {
                showToast('Vui lòng chọn Môn học và Học kỳ', 'error');
                return;
            }

            try {
                const res = await fetch(id ? `${API}/${id}` : API, {
                    method: id ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });
                if (!res.ok) throw new Error((await res.json()).message || 'Lỗi');
                showToast(id ? 'Cập nhật thành công!' : 'Thêm thành công!', 'success');
                closeModal();
                fetchData();
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        // ── DELETE ────────────────────────────────────────────────────────────
        function openDelete(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDelete() {
            document.getElementById('deleteModal').classList.remove('active');
            deleteId = null;
        }

        async function confirmDelete() {
            if (!deleteId) return;
            try {
                const r = await fetch(`${API}/${deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });
                if (!r.ok) throw new Error('Lỗi xóa');
                showToast('Đã xóa lớp học phần!', 'success');
                closeDelete();
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

        document.getElementById('formModal').addEventListener('click', e => {
            if (e.target === document.getElementById('formModal')) closeModal();
        });
        document.getElementById('deleteModal').addEventListener('click', e => {
            if (e.target === document.getElementById('deleteModal')) closeDelete();
        });

        fetchData();
    </script>
@endsection
