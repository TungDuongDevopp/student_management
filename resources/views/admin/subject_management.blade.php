@extends('layouts.admin.sidebar')

@section('title', 'Quản lý Môn học')

@section('content')
    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #1e293b;
            --bg-input: #0f172a;
            --border: #334155;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --danger: #ef4444;
            --success: #22c55e;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
        }

        /* Giới hạn CSS dark theme chỉ áp dụng trong thẻ wrap này nếu cần, tạm thời để nguyên */
        .content-wrapper {
            background: var(--bg-primary);
            color: var(--text);
            padding: 2rem;
            border-radius: 12px;
            min-height: calc(100vh - 4rem);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent
        }

        .breadcrumb {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 0.5rem
        }

        .breadcrumb a {
            color: var(--accent);
            text-decoration: none
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem
        }

        .btn-primary {
            background: var(--accent);
            color: #fff
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px)
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 6px
        }

        .btn-edit {
            background: rgba(59, 130, 246, 0.15);
            color: var(--accent);
            border: 1px solid rgba(59, 130, 246, 0.3)
        }

        .btn-edit:hover {
            background: rgba(59, 130, 246, 0.25)
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3)
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.25)
        }

        .btn-secondary {
            background: var(--bg-input);
            color: var(--text-muted);
            border: 1px solid var(--border)
        }

        .btn-secondary:hover {
            border-color: var(--accent);
            color: var(--text)
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden
        }

        .table-wrapper {
            overflow-x: auto
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        thead {
            background: rgba(59, 130, 246, 0.08)
        }

        th {
            padding: 1rem;
            text-align: left;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border)
        }

        td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid rgba(51, 65, 85, 0.5);
            font-size: 0.9rem;
            vertical-align: middle
        }

        tr:hover {
            background: rgba(59, 130, 246, 0.04)
        }

        .actions {
            display: flex;
            gap: 0.5rem
        }

        .badge {
            padding: 0.25rem 0.7rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600
        }

        .badge-faculty {
            background: rgba(139, 92, 246, 0.15);
            color: #a78bfa
        }

        .badge-credits {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa
        }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center
        }

        .modal-overlay.active {
            display: flex
        }

        .modal {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            width: 480px;
            max-width: 95vw;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5)
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .modal-header h2 {
            font-size: 1.2rem;
            font-weight: 600
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            cursor: pointer
        }

        .modal-close:hover {
            color: var(--text)
        }

        .modal-body {
            padding: 1.5rem
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem
        }

        .form-group {
            margin-bottom: 1.25rem
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted)
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.7rem 1rem;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.9rem;
            transition: border-color 0.2s
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15)
        }

        .toast {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            color: #fff;
            font-weight: 500;
            z-index: 2000;
            transform: translateX(120%);
            transition: transform 0.3s ease;
            font-size: 0.9rem
        }

        .toast.show {
            transform: translateX(0)
        }

        .toast-success {
            background: var(--success)
        }

        .toast-error {
            background: var(--danger)
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted)
        }

        .search-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem
        }

        .search-bar input {
            flex: 1;
            padding: 0.7rem 1rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.9rem
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--accent)
        }

        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap
        }

        .filter-tab {
            padding: 0.55rem 1.2rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg-card);
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.4rem
        }

        .filter-tab:hover {
            border-color: var(--accent);
            color: var(--text)
        }

        .filter-tab.active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent)
        }

        .filter-tab .count {
            background: rgba(255, 255, 255, 0.15);
            padding: 0.1rem 0.5rem;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 700
        }

        .filter-tab.active .count {
            background: rgba(255, 255, 255, 0.25)
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            border-top: 1px solid var(--border)
        }

        .pagination button {
            padding: 0.5rem 0.9rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--bg-input);
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s
        }

        .pagination button:hover:not(:disabled) {
            border-color: var(--accent);
            color: var(--text)
        }

        .pagination button.active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent)
        }

        .pagination button:disabled {
            opacity: 0.4;
            cursor: not-allowed
        }

        .pagination .page-info {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin: 0 0.5rem
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95)
            }

            to {
                opacity: 1;
                transform: scale(1)
            }
        }

        .modal-overlay.active .modal {
            animation: fadeIn 0.2s ease
        }
    </style>

    <div class="content-wrapper">

        <div class="page-header">
            <h1>Quản lý Môn học</h1>
            <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Môn học</button>
        </div>

        <div class="filter-tabs" id="filterTabs"></div>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên môn học, khoa..." oninput="filterTable()">
        </div>
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên môn học</th>
                            <th>Khoa</th>
                            <th>Số tín chỉ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">Đang tải dữ liệu...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="pagination" id="pagination"></div>
        </div>

        <!-- Add/Edit Modal -->
        <div class="modal-overlay" id="formModal">
            <div class="modal">
                <div class="modal-header">
                    <h2 id="modalTitle">Thêm Môn học</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="entityForm">
                        <input type="hidden" id="entityId">
                        <div class="form-group">
                            <label>Khoa *</label>
                            <select id="facultyId" required>
                                <option value="">-- Chọn khoa --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tên môn học *</label>
                            <input type="text" id="subjectName" placeholder="VD: Lập trình Web" required>
                        </div>
                        <div class="form-group">
                            <label>Số tín chỉ</label>
                            <input type="number" id="subjectCredits" placeholder="VD: 3" min="1" max="10">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                    <button class="btn btn-primary" onclick="saveEntity()">Lưu</button>
                </div>
            </div>
        </div>
        <div class="toast" id="toast"></div>

        <script>
            const API = '/api/subjects';
            const PER_PAGE = 10;
            let allData = [],
                allFaculties = [],
                filteredData = [],
                currentPage = 1,
                activeFilter = 'all';

            async function fetchData() {
                try {
                    const [subRes, facRes] = await Promise.all([
                        fetch(API).then(r => r.json()),
                        fetch('/api/faculties').then(r => r.json())
                    ]);
                    allData = subRes;
                    allFaculties = facRes;
                    renderFilterTabs();
                    filterTable();
                } catch (e) {
                    showToast('Lỗi tải dữ liệu', 'error');
                }
            }

            function renderFilterTabs() {
                const tabs = document.getElementById('filterTabs');
                const counts = {
                    all: allData.length
                };
                allFaculties.forEach(f => {
                    counts[f.id] = allData.filter(s => s.faculty_id === f.id).length;
                });

                let html =
                    `<div class="filter-tab ${activeFilter==='all'?'active':''}" onclick="setFilter('all')">Tất cả <span class="count">${counts.all}</span></div>`;
                allFaculties.forEach(f => {
                    if (counts[f.id] > 0) html +=
                        `<div class="filter-tab ${activeFilter==f.id?'active':''}" onclick="setFilter(${f.id})">${f.name} <span class="count">${counts[f.id]}</span></div>`;
                });
                tabs.innerHTML = html;
            }

            function setFilter(val) {
                activeFilter = val;
                renderFilterTabs();
                filterTable();
            }

            function filterTable() {
                const q = document.getElementById('searchInput').value.toLowerCase();
                filteredData = allData.filter(s => {
                    const matchSearch = (s.name || '').toLowerCase().includes(q) || (s.faculty?.name || '')
                        .toLowerCase().includes(q);
                    const matchFilter = activeFilter === 'all' || s.faculty_id === activeFilter;
                    return matchSearch && matchFilter;
                });
                currentPage = 1;
                renderPage();
            }

            function renderPage() {
                const totalPages = Math.max(1, Math.ceil(filteredData.length / PER_PAGE));
                if (currentPage > totalPages) currentPage = totalPages;
                const start = (currentPage - 1) * PER_PAGE;
                const pageData = filteredData.slice(start, start + PER_PAGE);

                const tb = document.getElementById('tableBody');
                if (!filteredData.length) {
                    tb.innerHTML = '<tr><td colspan="5"><div class="empty-state">Chưa có môn học nào</div></td></tr>';
                } else {
                    tb.innerHTML = pageData.map(s => `<tr>
            <td>${s.id}</td>
            <td><strong>${s.name||'-'}</strong></td>
            <td>${s.faculty ? `<span class="badge badge-faculty">${s.faculty.name}</span>` : '-'}</td>
            <td>${s.credits ? `<span class="badge badge-credits">${s.credits} TC</span>` : '-'}</td>
            <td><div class="actions">
                <button class="btn btn-sm btn-edit" onclick='editEntity(${JSON.stringify(s)})'>Sửa</button>
                <button class="btn btn-sm btn-delete" onclick="deleteEntity(${s.id})">Xóa</button>
            </div></td>
        </tr>`).join('');
                }
                renderPagination(totalPages);
            }

            function renderPagination(totalPages) {
                const pg = document.getElementById('pagination');
                if (totalPages <= 1) {
                    pg.innerHTML = '';
                    return;
                }
                let html = `<button onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}>‹</button>`;
                for (let i = 1; i <= totalPages; i++) html +=
                    `<button class="${i===currentPage?'active':''}" onclick="goPage(${i})">${i}</button>`;
                html += `<span class="page-info">${filteredData.length} bản ghi</span>`;
                html += `<button onclick="goPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}>›</button>`;
                pg.innerHTML = html;
            }

            function goPage(p) {
                currentPage = p;
                renderPage();
            }

            function populateFacultySelect(selectedId) {
                document.getElementById('facultyId').innerHTML = '<option value="">-- Chọn khoa --</option>' + allFaculties.map(
                    f => `<option value="${f.id}" ${f.id==selectedId?'selected':''}>${f.name}</option>`).join('');
            }

            function openAddModal() {
                document.getElementById('modalTitle').textContent = 'Thêm Môn học';
                document.getElementById('entityForm').reset();
                document.getElementById('entityId').value = '';
                populateFacultySelect('');
                document.getElementById('formModal').classList.add('active');
            }

            function editEntity(s) {
                document.getElementById('modalTitle').textContent = 'Cập nhật Môn học';
                document.getElementById('entityId').value = s.id;
                populateFacultySelect(s.faculty_id);
                document.getElementById('subjectName').value = s.name || '';
                document.getElementById('subjectCredits').value = s.credits || '';
                document.getElementById('formModal').classList.add('active');
            }

            function closeModal() {
                document.getElementById('formModal').classList.remove('active');
            }

            async function saveEntity() {
                const id = document.getElementById('entityId').value;
                const body = {
                    faculty_id: parseInt(document.getElementById('facultyId').value) || null,
                    name: document.getElementById('subjectName').value
                };
                const credits = document.getElementById('subjectCredits').value;
                if (credits) body.credits = parseInt(credits);
                else body.credits = null;

                try {
                    const url = id ? `${API}/${id}` : API;
                    const method = id ? 'PUT' : 'POST';
                    const res = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(body)
                    });
                    if (!res.ok) {
                        const err = await res.json();
                        throw new Error(err.message || 'Lỗi');
                    }
                    showToast(id ? 'Cập nhật thành công!' : 'Thêm thành công!', 'success');
                    closeModal();
                    fetchData();
                } catch (e) {
                    showToast('Lỗi: ' + e.message, 'error');
                }
            }

            async function deleteEntity(id) {
                if (!confirm('Bạn có chắc muốn xóa môn học này?')) return;
                try {
                    const r = await fetch(`${API}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        }
                    });
                    if (!r.ok) throw new Error('Lỗi xóa');
                    showToast('Xóa thành công!', 'success');
                    fetchData();
                } catch (e) {
                    showToast('Lỗi: ' + e.message, 'error');
                }
            }

            function showToast(msg, type = 'success') {
                const t = document.getElementById('toast');
                t.textContent = msg;
                t.className = `toast toast-${type} show`;
                setTimeout(() => t.classList.remove('show'), 3000);
            }
            fetchData();
        </script>
    </div>
@endsection
