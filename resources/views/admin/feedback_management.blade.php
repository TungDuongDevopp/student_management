@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Phản hồi')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <style>
        /* ── Feedback-specific styles ── */
        .filter-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            align-items: center;
        }

        .filter-row .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .filter-row .filter-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .filter-row .filter-tabs {
            display: flex;
            gap: 0.4rem;
            margin-bottom: 0;
        }

        .badge-unread {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }

        .badge-read {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
        }

        .badge-teacher {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
        }

        .badge-student {
            background: rgba(139, 92, 246, 0.15);
            color: #a78bfa;
        }

        .content-preview {
            max-width: 280px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .reply-preview {
            max-width: 220px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.85rem;
            color: #4ade80;
            font-style: italic;
        }

        .no-reply {
            color: var(--text-muted);
            font-size: 0.8rem;
            font-style: italic;
        }

        .btn-reply {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .btn-reply:hover {
            background: rgba(34, 197, 94, 0.25);
        }

        .btn-seen {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .btn-seen:hover {
            background: rgba(245, 158, 11, 0.25);
        }

        .detail-modal .modal {
            width: 580px;
        }

        .detail-block {
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .detail-block.reply-block {
            border-color: rgba(34, 197, 94, 0.3);
            background: rgba(34, 197, 94, 0.05);
            color: #4ade80;
        }

        .meta-info {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .meta-info span strong {
            color: var(--text);
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

        .stat-card.stat-total .stat-value {
            color: #60a5fa;
        }

        .stat-card.stat-unread .stat-value {
            color: #fbbf24;
        }

        .stat-card.stat-read .stat-value {
            color: #4ade80;
        }
    </style>

    <div class="content-wrapper">
        <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Quản lý Phản hồi</div>
        <div class="page-header">
            <h1>💬 Quản lý Phản hồi</h1>
        </div>

        {{-- Stats --}}
        <div class="stats-row" id="statsRow">
            <div class="stat-card stat-total">
                <span class="stat-value" id="statTotal">—</span>
                <span class="stat-label">Tổng phản hồi</span>
            </div>
            <div class="stat-card stat-unread">
                <span class="stat-value" id="statUnread">—</span>
                <span class="stat-label">Chưa xem</span>
            </div>
            <div class="stat-card stat-read">
                <span class="stat-value" id="statRead">—</span>
                <span class="stat-label">Đã xem / Đã phản hồi</span>
            </div>
        </div>

        {{-- Filters --}}
        <div class="filter-row">
            <div class="filter-group">
                <span class="filter-label">Trạng thái</span>
                <div class="filter-tabs" id="statusTabs">
                    <button class="filter-tab active" data-status="all" onclick="setStatusFilter('all', this)">
                        Tất cả <span class="count" id="cntAll">0</span>
                    </button>
                    <button class="filter-tab" data-status="0" onclick="setStatusFilter('0', this)">
                        Chưa xem <span class="count" id="cntUnread">0</span>
                    </button>
                    <button class="filter-tab" data-status="1" onclick="setStatusFilter('1', this)">
                        Đã xem <span class="count" id="cntRead">0</span>
                    </button>
                </div>
            </div>
            <div class="filter-group">
                <span class="filter-label">Vai trò</span>
                <div class="filter-tabs" id="roleTabs">
                    <button class="filter-tab active" data-role="all" onclick="setRoleFilter('all', this)">
                        Tất cả
                    </button>
                    <button class="filter-tab" data-role="2" onclick="setRoleFilter('2', this)">
                        Giảng viên
                    </button>
                    <button class="filter-tab" data-role="3" onclick="setRoleFilter('3', this)">
                        Sinh viên
                    </button>
                </div>
            </div>
        </div>

        {{-- Search --}}
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm theo tên, nội dung..." oninput="applyFilters()">
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người gửi</th>
                            <th>Vai trò</th>
                            <th>Nội dung</th>
                            <th>Phản hồi</th>
                            <th>Trạng thái</th>
                            <th>Ngày gửi</th>
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

        {{-- Detail / Reply Modal --}}
        <div class="modal-overlay detail-modal" id="detailModal">
            <div class="modal" style="width:600px">
                <div class="modal-header">
                    <h2 id="detailModalTitle">Chi tiết phản hồi</h2>
                    <button class="modal-close" onclick="closeDetailModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="meta-info" id="detailMeta"></div>
                    <div class="form-group">
                        <label>📩 Nội dung phản hồi từ người dùng</label>
                        <div class="detail-block" id="detailContent"></div>
                    </div>
                    <div class="form-group">
                        <label>💬 Phản hồi của Admin</label>
                        <textarea class="detail-block" id="replyInput" rows="4" placeholder="Nhập nội dung phản hồi..."
                            style="resize:vertical;width:100%;background:var(--bg-input);border:1px solid var(--border);border-radius:8px;color:var(--text);font-size:0.9rem;padding:0.85rem 1rem;font-family:'Inter',sans-serif;line-height:1.6;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeDetailModal()">Đóng</button>
                    <button class="btn btn-seen" id="btnMarkSeen" onclick="markSeen()">✔ Đánh dấu đã xem</button>
                    <button class="btn btn-reply" onclick="sendReply()">📤 Gửi phản hồi</button>
                </div>
            </div>
        </div>

        {{-- Delete Confirm Modal --}}
        <div class="modal-overlay" id="deleteModal">
            <div class="modal" style="width:420px">
                <div class="modal-header">
                    <h2>Xác nhận xóa</h2>
                    <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <p style="color:var(--text-muted)">Bạn có chắc muốn xóa phản hồi này? Hành động này không thể hoàn tác.
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeDeleteModal()">Hủy</button>
                    <button class="btn btn-delete" onclick="confirmDelete()">🗑 Xóa</button>
                </div>
            </div>
        </div>

        <div class="toast" id="toast"></div>
    </div>

    <script>
        const API = '/api/feedbacks';
        const API_REPLY = (id) => `/api/feedbacks/${id}/reply`;
        const API_SEEN = (id) => `/api/feedbacks/${id}/seen`;
        const PER_PAGE = 10;

        let allData = [];
        let filteredData = [];
        let currentPage = 1;
        let statusFilter = 'all';
        let roleFilter = 'all';
        let currentId = null;
        let deleteTargetId = null;

        const ROLE_MAP = {
            1: 'Admin',
            2: 'Giảng viên',
            3: 'Sinh viên'
        };
        const ROLE_BADGE = {
            1: 'badge-admin',
            2: 'badge-teacher',
            3: 'badge-student'
        };

        // ── FETCH ────────────────────────────────────────────────────────────
        async function fetchData() {
            try {
                const r = await fetch(API, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                allData = await r.json();
                updateStats();
                applyFilters();
            } catch (e) {
                showToast('Lỗi tải dữ liệu', 'error');
            }
        }

        // ── STATS ────────────────────────────────────────────────────────────
        function updateStats() {
            document.getElementById('statTotal').textContent = allData.length;
            document.getElementById('statUnread').textContent = allData.filter(f => f.status == 0).length;
            document.getElementById('statRead').textContent = allData.filter(f => f.status == 1).length;

            // Update tab counts
            document.getElementById('cntAll').textContent = allData.length;
            document.getElementById('cntUnread').textContent = allData.filter(f => f.status == 0).length;
            document.getElementById('cntRead').textContent = allData.filter(f => f.status == 1).length;
        }

        // ── FILTERS ──────────────────────────────────────────────────────────
        function setStatusFilter(val, btn) {
            statusFilter = val;
            document.querySelectorAll('#statusTabs .filter-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentPage = 1;
            applyFilters();
        }

        function setRoleFilter(val, btn) {
            roleFilter = val;
            document.querySelectorAll('#roleTabs .filter-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentPage = 1;
            applyFilters();
        }

        function applyFilters() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            filteredData = allData.filter(f => {
                const matchStatus = statusFilter === 'all' || String(f.status) === statusFilter;
                const roleId = f.account?.role_id ?? null;
                const matchRole = roleFilter === 'all' || String(roleId) === roleFilter;
                const matchSearch = !q ||
                    (f.account?.name || '').toLowerCase().includes(q) ||
                    (f.content || '').toLowerCase().includes(q) ||
                    (f.reply || '').toLowerCase().includes(q);
                return matchStatus && matchRole && matchSearch;
            });
            renderPage();
        }

        // ── RENDER ───────────────────────────────────────────────────────────
        function renderPage() {
            const totalPages = Math.max(1, Math.ceil(filteredData.length / PER_PAGE));
            if (currentPage > totalPages) currentPage = totalPages;
            const start = (currentPage - 1) * PER_PAGE;
            const pageData = filteredData.slice(start, start + PER_PAGE);
            const tb = document.getElementById('tableBody');

            if (!filteredData.length) {
                tb.innerHTML = '<tr><td colspan="8"><div class="empty-state">Không có phản hồi nào phù hợp</div></td></tr>';
            } else {
                tb.innerHTML = pageData.map(f => {
                    const roleId = f.account?.role_id ?? null;
                    const roleName = ROLE_MAP[roleId] || 'Không rõ';
                    const roleBadge = ROLE_BADGE[roleId] || 'badge-default';
                    const statusBadge = f.status == 0 ?
                        '<span class="badge badge-unread">⏳ Chưa xem</span>' :
                        '<span class="badge badge-read">✅ Đã xem</span>';
                    const replyCell = f.reply ?
                        `<span class="reply-preview" title="${escHtml(f.reply)}">${escHtml(f.reply)}</span>` :
                        `<span class="no-reply">Chưa phản hồi</span>`;
                    const seenBtn = f.status == 0 ?
                        `<button class="btn btn-sm btn-seen" onclick="quickMarkSeen(${f.id}, event)">✔ Đã xem</button>` :
                        '';
                    return `<tr id="row-${f.id}">
                        <td><strong>#${f.id}</strong></td>
                        <td><strong>${escHtml(f.account?.name || '—')}</strong></td>
                        <td><span class="badge ${roleBadge}">${roleName}</span></td>
                        <td><div class="content-preview" title="${escHtml(f.content||'')}">${escHtml(f.content||'—')}</div></td>
                        <td>${replyCell}</td>
                        <td>${statusBadge}</td>
                        <td>${f.created_at ? new Date(f.created_at).toLocaleDateString('vi-VN') : '—'}</td>
                        <td><div class="actions">
                            <button class="btn btn-sm btn-edit" onclick='openDetail(${JSON.stringify(f)})'>🔍 Chi tiết</button>
                            ${seenBtn}
                            <button class="btn btn-sm btn-delete" onclick="openDelete(${f.id})">🗑</button>
                        </div></td>
                    </tr>`;
                }).join('');
            }
            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const pg = document.getElementById('pagination');
            if (totalPages <= 1) {
                pg.innerHTML = '';
                return;
            }
            let html = `<button onclick="goPage(1)" ${currentPage===1?'disabled':''}>«</button>`;
            html += `<button onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}>‹</button>`;

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);
            if (endPage - startPage < 4) {
                if (startPage === 1) endPage = Math.min(totalPages, 5);
                else if (endPage === totalPages) startPage = Math.max(1, totalPages - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `<button class="${i===currentPage?'active':''}" onclick="goPage(${i})">${i}</button>`;
            }

            html += `<span class="page-info">${filteredData.length} phản hồi</span>`;
            html += `<button onclick="goPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}>›</button>`;
            html += `<button onclick="goPage(${totalPages})" ${currentPage===totalPages?'disabled':''}>»</button>`;
            pg.innerHTML = html;
        }

        function goPage(p) {
            currentPage = p;
            renderPage();
        }

        // ── DETAIL MODAL ─────────────────────────────────────────────────────
        function openDetail(f) {
            currentId = f.id;
            const roleId = f.account?.role_id ?? null;
            const roleName = ROLE_MAP[roleId] || 'Không rõ';
            document.getElementById('detailModalTitle').textContent = `Phản hồi #${f.id}`;
            document.getElementById('detailMeta').innerHTML = `
                <span><strong>Người gửi:</strong> ${escHtml(f.account?.name || '—')}</span>
                <span><strong>Vai trò:</strong> ${roleName}</span>
                <span><strong>Ngày gửi:</strong> ${f.created_at ? new Date(f.created_at).toLocaleDateString('vi-VN') : '—'}</span>
                <span><strong>Trạng thái:</strong> ${f.status == 0 ? '⏳ Chưa xem' : '✅ Đã xem'}</span>
            `;
            document.getElementById('detailContent').textContent = f.content || '(Không có nội dung)';
            document.getElementById('replyInput').value = f.reply || '';
            document.getElementById('btnMarkSeen').style.display = f.status == 0 ? '' : 'none';
            document.getElementById('detailModal').classList.add('active');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('active');
            currentId = null;
        }

        // ── REPLY ────────────────────────────────────────────────────────────
        async function sendReply() {
            if (!currentId) return;
            const reply = document.getElementById('replyInput').value.trim();
            if (!reply) {
                showToast('Vui lòng nhập nội dung phản hồi', 'error');
                return;
            }
            try {
                const res = await fetch(API_REPLY(currentId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        reply
                    })
                });
                if (!res.ok) throw new Error((await res.json()).message || 'Lỗi');
                showToast('Phản hồi đã được gửi!', 'success');
                closeDetailModal();
                fetchData();
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        // ── MARK SEEN ────────────────────────────────────────────────────────
        async function markSeen() {
            if (!currentId) return;
            await doMarkSeen(currentId);
            closeDetailModal();
        }

        async function quickMarkSeen(id, e) {
            e.stopPropagation();
            await doMarkSeen(id);
        }

        async function doMarkSeen(id) {
            try {
                const res = await fetch(API_SEEN(id), {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) throw new Error((await res.json()).message || 'Lỗi');
                showToast('Đã đánh dấu là đã xem!', 'success');
                fetchData();
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        // ── DELETE ───────────────────────────────────────────────────────────
        function openDelete(id) {
            deleteTargetId = id;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
            deleteTargetId = null;
        }

        async function confirmDelete() {
            if (!deleteTargetId) return;
            try {
                const r = await fetch(`${API}/${deleteTargetId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });
                if (!r.ok) throw new Error('Lỗi xóa');
                showToast('Đã xóa phản hồi!', 'success');
                closeDeleteModal();
                fetchData();
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        // ── UTILS ────────────────────────────────────────────────────────────
        function escHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = `toast toast-${type} show`;
            setTimeout(() => t.classList.remove('show'), 3000);
        }

        // ── CLOSE ON OVERLAY CLICK ───────────────────────────────────────────
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) closeDetailModal();
        });
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        fetchData();
    </script>
@endsection
