@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Đơn đăng ký học phần')
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
            min-width: 140px;
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

        .stat-credits .stat-value {
            color: #4ade80;
        }

        .stat-avg .stat-value {
            color: #fbbf24;
        }

        .student-name {
            font-weight: 600;
        }

        .muted {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        /* Detail Modal Table styles */
        .detail-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            background: rgba(255, 255, 255, 0.03);
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .detail-info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .detail-info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.04em;
        }

        .detail-info-value {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .detail-table-wrapper {
            max-height: 280px;
            overflow-y: auto;
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-top: 0.5rem;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .detail-table th, .detail-table td {
            padding: 0.6rem 0.8rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .detail-table th {
            background: var(--bg-body);
            color: var(--text-muted);
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .detail-table tr:last-child td {
            border-bottom: none;
        }

        .btn-cancel-subject {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel-subject:hover {
            background: rgba(239, 68, 68, 0.25);
            color: #f87171;
        }
    </style>

    <div class="content-wrapper">
        <div class="admin-banner">
            <div class="ab-content">
                <div class="ab-subtitle">ADMINISTRATION PORTAL</div>
                <div class="ab-title">Quản lý Đơn đăng ký học phần</div>
                <div class="ab-desc">Quản lý, theo dõi và cấu hình các thông tin liên quan đến đơn đăng ký học phần.</div>
            </div>
            <div class="ab-action">
                
            </div>
            <div class="ab-decor"></div>
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-card stat-total">
                <span class="stat-value" id="statTotal">—</span>
                <span class="stat-label">Tổng số đơn</span>
            </div>
            <div class="stat-card stat-credits">
                <span class="stat-value" id="statTotalCredits">—</span>
                <span class="stat-label">Tổng số tín chỉ</span>
            </div>
            <div class="stat-card stat-avg">
                <span class="stat-value" id="statAvgCredits">—</span>
                <span class="stat-label">Tín chỉ TB / Đơn</span>
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
                <span class="filter-label">Tìm kiếm</span>
                <input type="text" class="filter-select" id="searchInput" placeholder="Tên SV, mã SV, lớp..."
                    oninput="applyFilters()" style="min-width:240px">
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID Đơn</th>
                            <th>Mã SV</th>
                            <th>Sinh viên</th>
                            <th>Lớp sinh hoạt</th>
                            <th>Học kỳ</th>
                            <th style="text-align:center">Tổng số tín chỉ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">Đang tải dữ liệu...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="pagination" id="pagination"></div>
        </div>

        {{-- Detail Modal --}}
        <div class="modal-overlay" id="detailModal">
            <div class="modal" style="width:680px">
                <div class="modal-header">
                    <h2>🔍 Chi tiết đơn đăng ký</h2>
                    <button class="modal-close" onclick="closeDetailModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="detail-info-grid">
                        <div class="detail-info-item">
                            <span class="detail-info-label">Sinh viên</span>
                            <span class="detail-info-value" id="detailStudentName">—</span>
                        </div>
                        <div class="detail-info-item">
                            <span class="detail-info-label">Mã SV / Lớp</span>
                            <span class="detail-info-value" id="detailStudentMeta">—</span>
                        </div>
                        <div class="detail-info-item">
                            <span class="detail-info-label">Học kỳ</span>
                            <span class="detail-info-value" id="detailSemester">—</span>
                        </div>
                        <div class="detail-info-item">
                            <span class="detail-info-label">Tổng tín chỉ</span>
                            <span class="detail-info-value" id="detailTotalCredits" style="color:var(--accent)">0</span>
                        </div>
                    </div>

                    <div>
                        <div class="filter-label" style="margin-bottom:0.25rem">Danh sách môn học đã đăng ký</div>
                        <div class="detail-table-wrapper">
                            <table class="detail-table">
                                <thead>
                                    <tr>
                                        <th style="width:50px">STT</th>
                                        <th>Mã lớp HP</th>
                                        <th>Tên môn học</th>
                                        <th style="text-align:center;width:80px">Tín chỉ</th>
                                        <th>Giảng viên</th>
                                        <th style="text-align:center;width:80px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="detailTableBody">
                                    <tr>
                                        <td colspan="6" class="muted" style="text-align:center;padding:1rem">
                                            Chưa đăng ký môn học nào.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeDetailModal()">Đóng</button>
                </div>
            </div>
        </div>

        {{-- Delete Confirm Modal --}}
        <div class="modal-overlay" id="deleteModal">
            <div class="modal" style="width:400px">
                <div class="modal-header">
                    <h2>Xác nhận xóa đơn đăng ký</h2>
                    <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <p style="color:var(--text-muted)">
                        Bạn có chắc chắn muốn xóa đơn đăng ký học phần này?
                        Tất cả các môn học đã đăng ký trong đơn của học kỳ này sẽ bị hủy bỏ.
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeDeleteModal()">Hủy</button>
                    <button class="btn btn-delete" onclick="confirmDelete()">🗑 Xóa đơn</button>
                </div>
            </div>
        </div>

        <div class="toast" id="toast"></div>
    </div>

    <script>
        const API = '/api/tuitions';
        const ENROLL_API = '/api/enrollments';
        const SEM_API = '/api/semesters';
        const PER_PAGE = 15;

        let allData = [];
        let filteredData = [];
        let allSemesters = [];
        let currentPage = 1;
        let activeDetailId = null;
        let deleteId = null;

        // ── BOOT ─────────────────────────────────────────────────────────────
        async function fetchData() {
            try {
                const [tuitionRes, semRes] = await Promise.all([
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
                allData = tuitionRes;
                allSemesters = semRes;
                buildSemesterFilter();
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

        // ── STATS ─────────────────────────────────────────────────────────────
        function updateStats(data) {
            const totalRecords = data.length;
            const totalCredits = data.reduce((acc, t) => acc + (t.total_credits || 0), 0);
            const avgCredits = totalRecords > 0 ? (totalCredits / totalRecords).toFixed(1) : '0.0';

            document.getElementById('statTotal').textContent = totalRecords;
            document.getElementById('statTotalCredits').textContent = totalCredits;
            document.getElementById('statAvgCredits').textContent = avgCredits;
        }

        // ── FILTERS ───────────────────────────────────────────────────────────
        function applyFilters() {
            const semId = document.getElementById('semesterFilter').value;
            const q = document.getElementById('searchInput').value.toLowerCase();

            filteredData = allData.filter(t => {
                const matchSem = semId === 'all' || String(t.semester_id) === semId;
                const studentName = (t.student?.name || '').toLowerCase();
                const studentCode = (t.student?.student_code || '').toLowerCase();
                const classCode = (t.student?.classroom?.code || '').toLowerCase();
                const matchSearch = !q || studentName.includes(q) || studentCode.includes(q) || classCode.includes(q);
                return matchSem && matchSearch;
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
                tb.innerHTML = '<tr><td colspan="7"><div class="empty-state">Không có đơn đăng ký nào phù hợp</div></td></tr>';
                renderPagination(1);
                return;
            }

            tb.innerHTML = pageData.map(t => {
                const semName = t.semester ?
                    `${t.semester.name}${t.semester.academic_year ? ' – ' + t.semester.academic_year : ''}` :
                    '—';

                return `<tr>
                    <td><strong>#${t.id}</strong></td>
                    <td class="muted" style="font-weight:600;">${escHtml(t.student?.student_code || '—')}</td>
                    <td>
                        <div class="student-name">${escHtml(t.student?.name || '—')}</div>
                    </td>
                    <td><span class="badge badge-faculty">${escHtml(t.student?.classroom?.code || '—')}</span></td>
                    <td><span class="badge badge-semester">${escHtml(semName)}</span></td>
                    <td style="text-align:center;font-weight:600;color:var(--accent)">${t.total_credits || 0}</td>
                    <td><div class="actions">
                        <button class="btn btn-sm btn-edit" onclick='openDetailModal(${JSON.stringify(t)})'>🔍 Chi tiết</button>
                        <button class="btn btn-sm btn-delete" onclick="openDelete(${t.id})">🗑 Xóa</button>
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
                
                const delta = 1;
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
                
                html += `<button onclick="goPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}>›</button>`;
                pg.innerHTML = html;
            }

        function goPage(p) {
            currentPage = p;
            renderPage();
        }

        // ── DETAIL MODAL ───────────────────────────────────────────────────────
        function openDetailModal(t) {
            activeDetailId = t.id;
            const semName = t.semester ?
                `${t.semester.name}${t.semester.academic_year ? ' – ' + t.semester.academic_year : ''}` :
                '—';

            document.getElementById('detailStudentName').textContent = t.student?.name || '—';
            document.getElementById('detailStudentMeta').textContent = `${t.student?.student_code || '—'} | Lớp: ${t.student?.classroom?.code || '—'}`;
            document.getElementById('detailSemester').textContent = semName;
            document.getElementById('detailTotalCredits').textContent = t.total_credits || 0;

            renderDetailEnrollments(t.enrollments || []);
            document.getElementById('detailModal').classList.add('active');
        }

        function renderDetailEnrollments(enrollments) {
            const tb = document.getElementById('detailTableBody');
            if (!enrollments || enrollments.length === 0) {
                tb.innerHTML = `<tr><td colspan="6" class="muted" style="text-align:center;padding:1.5rem">Chưa đăng ký môn học nào.</td></tr>`;
                return;
            }

            tb.innerHTML = enrollments.map((e, idx) => {
                const schedule = e.schedule || {};
                const subject = schedule.subject || {};
                const teacherName = schedule.teacher?.name || '—';
                const classCode = schedule.class_section_group_code || '—';

                return `<tr>
                    <td>${idx + 1}</td>
                    <td style="font-weight:600;color:var(--text-muted)">${escHtml(classCode)}</td>
                    <td style="font-weight:600;">${escHtml(subject.name || '—')}</td>
                    <td style="text-align:center;font-weight:600;color:var(--accent)">${subject.credits || 0}</td>
                    <td>${escHtml(teacherName)}</td>
                    <td style="text-align:center">
                        <button class="btn-cancel-subject" onclick="cancelSubject(${e.id})">Hủy môn</button>
                    </td>
                </tr>`;
            }).join('');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('active');
            activeDetailId = null;
        }

        // ── CANCEL INDIVIDUAL SUBJECT ──────────────────────────────────────────
        async function cancelSubject(enrollmentId) {
            if (!confirm('Bạn có chắc chắn muốn hủy đăng ký môn học này?')) return;
            try {
                const res = await fetch(`${ENROLL_API}/${enrollmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) throw new Error('Hủy môn học thất bại');
                showToast('Hủy môn học thành công!', 'success');

                // Reload the whole tuition list and update modal content
                const freshTuitionRes = await fetch(API, {
                    headers: { 'Accept': 'application/json' }
                }).then(r => r.json());

                allData = freshTuitionRes;
                applyFilters();

                // Find the updated tuition and update modal display
                const updatedTuition = allData.find(t => t.id === activeDetailId);
                if (updatedTuition) {
                    openDetailModal(updatedTuition);
                } else {
                    closeDetailModal();
                }
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        // ── DELETE ENTIRE TUITION APPLICATION ──────────────────────────────────
        function openDelete(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
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
                if (!r.ok) throw new Error('Lỗi khi xóa đơn đăng ký');
                showToast('Đã xóa đơn đăng ký học phần thành công!', 'success');
                closeDeleteModal();
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

        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) closeDetailModal();
        });
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        fetchData();
    </script>
@endsection
