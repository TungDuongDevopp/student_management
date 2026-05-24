@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Học phí')
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
            font-size: 1.5rem;
            font-weight: 700;
        }
        .stat-card .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .stat-total   .stat-value { color: #60a5fa; }
        .stat-paid    .stat-value { color: #4ade80; }
        .stat-partial .stat-value { color: #fbbf24; }
        .stat-unpaid  .stat-value { color: #f87171; }

        .badge-paid    { background: rgba(34,197,94,0.15);  color: #4ade80; }
        .badge-partial { background: rgba(251,191,36,0.15); color: #fbbf24; }
        .badge-unpaid  { background: rgba(239,68,68,0.15);  color: #f87171; }

        .amount-col { font-variant-numeric: tabular-nums; }
        .student-name { font-weight: 600; }
        .muted { color: var(--text-muted); font-size: 0.8rem; }

        .progress-bar-wrap {
            background: var(--border);
            border-radius: 999px;
            height: 6px;
            min-width: 80px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 999px;
            transition: width 0.4s ease;
        }

        /* Detail modal */
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem 1.5rem;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
        }
        .detail-grid .item-label {
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
        }
        .payments-list {
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 8px;
            max-height: 160px;
            overflow-y: auto;
            padding: 0.5rem;
            font-size: 0.85rem;
        }
        .payment-item {
            display: flex;
            justify-content: space-between;
            padding: 0.4rem 0.5rem;
            border-bottom: 1px solid var(--border);
        }
        .payment-item:last-child { border-bottom: none; }
    </style>

    <div class="content-wrapper">
        <div class="page-header">
            <h1>Quản lý Học phí</h1>
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-card stat-total">
                <span class="stat-value" id="statTotal">—</span>
                <span class="stat-label">Tổng bản ghi</span>
            </div>
            <div class="stat-card stat-paid">
                <span class="stat-value" id="statPaid">—</span>
                <span class="stat-label">Đã thanh toán đủ</span>
            </div>
            <div class="stat-card stat-partial">
                <span class="stat-value" id="statPartial">—</span>
                <span class="stat-label">Đóng một phần</span>
            </div>
            <div class="stat-card stat-unpaid">
                <span class="stat-value" id="statUnpaid">—</span>
                <span class="stat-label">Chưa đóng</span>
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
                <span class="filter-label">Tình trạng học phí</span>
                <select class="filter-select" id="debtFilter" onchange="applyFilters()">
                    <option value="all">Tất cả</option>
                    <option value="paid">Đã thanh toán đủ</option>
                    <option value="partial">Đóng một phần (nợ)</option>
                    <option value="unpaid">Chưa đóng tiền</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Tìm kiếm</span>
                <input type="text" class="filter-select" id="searchInput"
                    placeholder="Tên SV, mã SV, lớp..." oninput="applyFilters()"
                    style="min-width:220px">
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
                            <th>Lớp</th>
                            <th>Học kỳ</th>
                            <th>Tổng phải đóng</th>
                            <th>Đã đóng</th>
                            <th>Còn nợ</th>
                            <th>Tiến độ</th>
                            <th>Tình trạng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">Đang tải dữ liệu...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="pagination" id="pagination"></div>
        </div>

        {{-- Detail / Edit Modal --}}
        <div class="modal-overlay" id="detailModal">
            <div class="modal" style="width:520px">
                <div class="modal-header">
                    <h2 id="detailTitle">Chi tiết học phí</h2>
                    <button class="modal-close" onclick="closeDetail()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="detail-grid" id="detailGrid"></div>

                    <div class="form-group">
                        <label>Tổng phải đóng (VNĐ)</label>
                        <input type="number" id="inpTotal" min="0" step="1000"
                            placeholder="VD: 5000000" style="width:100%">
                    </div>
                    <div class="form-group">
                        <label>Đã đóng (VNĐ)</label>
                        <input type="number" id="inpPaid" min="0" step="1000"
                            placeholder="VD: 2500000" style="width:100%">
                    </div>

                    <div style="margin-top:1rem">
                        <div class="filter-label" style="margin-bottom:0.5rem">Lịch sử thanh toán</div>
                        <div class="payments-list" id="paymentsList">
                            <div class="muted" style="padding:0.5rem">Chưa có giao dịch nào.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeDetail()">Hủy</button>
                    <button class="btn btn-primary" onclick="saveDetail()">💾 Lưu thay đổi</button>
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
                        Bạn có chắc muốn xóa bản ghi học phí này? Lịch sử thanh toán liên quan cũng sẽ bị xóa.
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
        const API     = '/api/tuitions';
        const SEM_API = '/api/semesters';
        const PER_PAGE = 15;

        let allData      = [];
        let filteredData = [];
        let allSemesters = [];
        let currentPage  = 1;
        let editId       = null;
        let deleteId     = null;

        // ── BOOT ─────────────────────────────────────────────────────────────
        async function fetchData() {
            try {
                const [tuitionRes, semRes] = await Promise.all([
                    fetch(API,     { headers: { 'Accept': 'application/json' } }).then(r => r.json()),
                    fetch(SEM_API, { headers: { 'Accept': 'application/json' } }).then(r => r.json()),
                ]);
                allData      = tuitionRes;
                allSemesters = semRes;
                buildSemesterFilter();
                applyFilters();
            } catch (e) {
                showToast('Lỗi tải dữ liệu', 'error');
            }
        }

        // ── SEMESTER DROPDOWN ─────────────────────────────────────────────────
        function buildSemesterFilter() {
            const sel = document.getElementById('semesterFilter');
            sel.innerHTML = '<option value="all">Tất cả học kỳ</option>' +
                allSemesters.map(s =>
                    `<option value="${s.id}">${s.name}${s.academic_year ? ' – ' + s.academic_year : ''}</option>`
                ).join('');
        }

        // ── HELPERS ───────────────────────────────────────────────────────────
        function debtStatus(t) {
            const total = parseFloat(t.total_amount) || 0;
            const paid  = parseFloat(t.paid_amount)  || 0;
            if (total === 0) return 'paid';
            if (paid >= total) return 'paid';
            if (paid > 0)  return 'partial';
            return 'unpaid';
        }

        function fmtVnd(n) {
            const num = parseFloat(n) || 0;
            return num.toLocaleString('vi-VN') + ' ₫';
        }

        function fmtDate(d) {
            if (!d) return '—';
            return new Date(d).toLocaleDateString('vi-VN');
        }

        function progressColor(pct) {
            if (pct >= 100) return '#4ade80';
            if (pct >= 50)  return '#fbbf24';
            return '#f87171';
        }

        // ── STATS ─────────────────────────────────────────────────────────────
        function updateStats(data) {
            document.getElementById('statTotal').textContent   = data.length;
            document.getElementById('statPaid').textContent    = data.filter(t => debtStatus(t) === 'paid').length;
            document.getElementById('statPartial').textContent = data.filter(t => debtStatus(t) === 'partial').length;
            document.getElementById('statUnpaid').textContent  = data.filter(t => debtStatus(t) === 'unpaid').length;
        }

        // ── FILTERS ───────────────────────────────────────────────────────────
        function applyFilters() {
            const semId = document.getElementById('semesterFilter').value;
            const debt  = document.getElementById('debtFilter').value;
            const q     = document.getElementById('searchInput').value.toLowerCase();

            filteredData = allData.filter(t => {
                const matchSem    = semId === 'all' || String(t.semester_id) === semId;
                const matchDebt   = debt  === 'all' || debtStatus(t) === debt;
                const stuName     = (t.student?.name         || '').toLowerCase();
                const stuCode     = (t.student?.student_code || '').toLowerCase();
                const classCode   = (t.student?.classroom?.code || '').toLowerCase();
                const matchSearch = !q || stuName.includes(q) || stuCode.includes(q) || classCode.includes(q);
                return matchSem && matchDebt && matchSearch;
            });

            updateStats(filteredData);
            currentPage = 1;
            renderPage();
        }

        // ── RENDER ────────────────────────────────────────────────────────────
        function renderPage() {
            const totalPages = Math.max(1, Math.ceil(filteredData.length / PER_PAGE));
            if (currentPage > totalPages) currentPage = totalPages;
            const start    = (currentPage - 1) * PER_PAGE;
            const pageData = filteredData.slice(start, start + PER_PAGE);
            const tb       = document.getElementById('tableBody');

            if (!filteredData.length) {
                tb.innerHTML = '<tr><td colspan="10"><div class="empty-state">Không có bản ghi học phí nào phù hợp</div></td></tr>';
                renderPagination(1);
                return;
            }

            tb.innerHTML = pageData.map(t => {
                const total   = parseFloat(t.total_amount) || 0;
                const paid    = parseFloat(t.paid_amount)  || 0;
                const remain  = Math.max(0, total - paid);
                const pct     = total > 0 ? Math.min(100, Math.round(paid / total * 100)) : 100;
                const status  = debtStatus(t);
                const semName = t.semester
                    ? `${t.semester.name}${t.semester.academic_year ? ' – ' + t.semester.academic_year : ''}`
                    : '—';

                const badgeClass = status === 'paid' ? 'badge-paid' : status === 'partial' ? 'badge-partial' : 'badge-unpaid';
                const badgeLabel = status === 'paid' ? 'Đã đủ' : status === 'partial' ? 'Còn nợ' : 'Chưa đóng';

                return `<tr>
                    <td><strong>#${t.id}</strong></td>
                    <td>
                        <div class="student-name">${escHtml(t.student?.name || '—')}</div>
                        <div class="muted">${escHtml(t.student?.student_code || '')}</div>
                    </td>
                    <td><span class="badge badge-faculty">${escHtml(t.student?.classroom?.code || '—')}</span></td>
                    <td><span class="badge badge-semester">${escHtml(semName)}</span></td>
                    <td class="amount-col">${fmtVnd(total)}</td>
                    <td class="amount-col">${fmtVnd(paid)}</td>
                    <td class="amount-col" style="color:${remain > 0 ? '#f87171' : '#4ade80'}">${remain > 0 ? fmtVnd(remain) : '—'}</td>
                    <td>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar-fill" style="width:${pct}%;background:${progressColor(pct)}"></div>
                        </div>
                        <div class="muted" style="margin-top:2px">${pct}%</div>
                    </td>
                    <td><span class="badge ${badgeClass}">${badgeLabel}</span></td>
                    <td><div class="actions">
                        <button class="btn btn-sm btn-edit" onclick='openDetail(${JSON.stringify(t)})'>Chi tiết</button>
                        <button class="btn btn-sm btn-delete" onclick="openDelete(${t.id})">🗑</button>
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

        function goPage(p) { currentPage = p; renderPage(); }

        // ── DETAIL MODAL ──────────────────────────────────────────────────────
        function openDetail(t) {
            editId = t.id;
            const semName = t.semester
                ? `${t.semester.name}${t.semester.academic_year ? ' – ' + t.semester.academic_year : ''}`
                : '—';
            document.getElementById('detailTitle').textContent = `Học phí #${t.id}`;
            document.getElementById('detailGrid').innerHTML = `
                <div>
                    <div class="item-label">Sinh viên</div>
                    <strong>${escHtml(t.student?.name || '—')}</strong>
                    <div class="muted">${escHtml(t.student?.student_code || '')}</div>
                </div>
                <div>
                    <div class="item-label">Lớp</div>
                    <span class="badge badge-faculty">${escHtml(t.student?.classroom?.code || '—')}</span>
                </div>
                <div>
                    <div class="item-label">Học kỳ</div>
                    <span class="badge badge-semester">${escHtml(semName)}</span>
                </div>
                <div>
                    <div class="item-label">Tình trạng</div>
                    ${(() => {
                        const s = debtStatus(t);
                        return s === 'paid'
                            ? '<span class="badge badge-paid">Đã thanh toán đủ</span>'
                            : s === 'partial'
                            ? '<span class="badge badge-partial">Còn nợ một phần</span>'
                            : '<span class="badge badge-unpaid">Chưa đóng tiền</span>';
                    })()}
                </div>
            `;
            document.getElementById('inpTotal').value = t.total_amount ?? '';
            document.getElementById('inpPaid').value  = t.paid_amount  ?? '';

            // Payments list
            const pmts = t.payments || [];
            document.getElementById('paymentsList').innerHTML = pmts.length
                ? pmts.map(p => {
                    const status = p.status || 'completed';
                    let statusBadge = '';
                    let actionButtons = '';
                    
                    if (status === 'pending') {
                        statusBadge = `<span class="badge" style="background:rgba(251,191,36,0.15); color:#d97706; font-size:0.7rem; padding:2px 6px; margin-left:6px;">Chờ duyệt</span>`;
                        actionButtons = `
                            <div style="display:inline-flex; gap:0.25rem; margin-left:10px;">
                                <button class="btn btn-sm" style="background:#16a34a; color:#fff; padding:2px 8px; font-size:0.7rem; border:none; border-radius:4px; cursor:pointer;" onclick="approvePayment(${p.id}, ${t.id})">Duyệt</button>
                                <button class="btn btn-sm" style="background:#ef4444; color:#fff; padding:2px 8px; font-size:0.7rem; border:none; border-radius:4px; cursor:pointer;" onclick="rejectPayment(${p.id}, ${t.id})">Từ chối</button>
                            </div>
                        `;
                    } else if (status === 'failed') {
                        statusBadge = `<span class="badge" style="background:rgba(239,68,68,0.15); color:#ef4444; font-size:0.7rem; padding:2px 6px; margin-left:6px;">Bị từ chối</span>`;
                    } else {
                        statusBadge = `<span class="badge" style="background:rgba(34,197,94,0.15); color:#16a34a; font-size:0.7rem; padding:2px 6px; margin-left:6px;">Đã duyệt</span>`;
                    }
                    
                    return `
                    <div class="payment-item" style="display:flex; justify-content:space-between; align-items:center; padding:0.4rem 0.5rem; border-bottom:1px solid var(--border);">
                        <div>
                            <span>${fmtDate(p.payment_date)}</span>
                            ${statusBadge}
                        </div>
                        <div style="display:flex; align-items:center;">
                            <strong style="margin-right:4px;">${fmtVnd(p.amount)}</strong>
                            ${actionButtons}
                        </div>
                    </div>`;
                }).join('')
                : '<div class="muted" style="padding:0.5rem">Chưa có giao dịch nào.</div>';

            document.getElementById('detailModal').classList.add('active');
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.remove('active');
            editId = null;
        }

        async function saveDetail() {
            if (!editId) return;
            const body = {
                total_amount: parseFloat(document.getElementById('inpTotal').value) || null,
                paid_amount:  parseFloat(document.getElementById('inpPaid').value)  || null,
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
                showToast('Cập nhật học phí thành công!', 'success');
                closeDetail();
                fetchData();
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        async function approvePayment(paymentId, tuitionId) {
            if (!confirm('Bạn có chắc chắn muốn duyệt giao dịch thanh toán này? Số tiền sẽ tự động được cộng vào phần Đã đóng của sinh viên.')) return;
            try {
                const res = await fetch(`/api/payments/${paymentId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Lỗi duyệt');
                
                showToast('Duyệt giao dịch thành công!', 'success');
                await refreshModalAndData(tuitionId);
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        async function rejectPayment(paymentId, tuitionId) {
            if (!confirm('Bạn có chắc chắn muốn từ chối giao dịch thanh toán này?')) return;
            try {
                const res = await fetch(`/api/payments/${paymentId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Lỗi từ chối');
                
                showToast('Từ chối giao dịch thành công!', 'success');
                await refreshModalAndData(tuitionId);
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        async function refreshModalAndData(tuitionId) {
            await fetchData();
            const freshTuition = allData.find(t => t.id === tuitionId);
            if (freshTuition) {
                openDetail(freshTuition);
            } else {
                closeDetail();
            }
        }

        // ── DELETE ────────────────────────────────────────────────────────────
        function openDelete(id)  { deleteId = id; document.getElementById('deleteModal').classList.add('active'); }
        function closeDelete()   { document.getElementById('deleteModal').classList.remove('active'); deleteId = null; }

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
                showToast('Đã xóa bản ghi học phí!', 'success');
                closeDelete();
                fetchData();
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        // ── UTILS ─────────────────────────────────────────────────────────────
        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className   = `toast toast-${type} show`;
            setTimeout(() => t.classList.remove('show'), 3000);
        }

        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) closeDetail();
        });
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDelete();
        });

        fetchData();
    </script>
@endsection
