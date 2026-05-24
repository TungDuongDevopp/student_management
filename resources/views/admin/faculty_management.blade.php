@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Khoa')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">

        <div class="page-header">
            <h1>Quản lý Khoa</h1>
            <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Khoa</button>
        </div>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo mã khoa, tên khoa..." oninput="filterTable()">
        </div>
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã khoa</th>
                            <th>Tên khoa</th>
                            <th>Nhóm Khoa (Khối)</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="6">
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
                    <h2 id="modalTitle">Thêm Khoa</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="entityForm">
                        <input type="hidden" id="entityId">
                        <div class="form-group">
                            <label>Mã khoa *</label>
                            <input type="text" id="facultyCode" placeholder="VD: CNTT, KT, NN" required>
                        </div>
                        <div class="form-group">
                            <label>Tên khoa *</label>
                            <input type="text" id="facultyName" placeholder="VD: Công nghệ thông tin" required>
                        </div>
                        <div class="form-group">
                            <label>Nhóm Khoa (Khối)</label>
                            <select id="facultyGeneralId">
                                <option value="">-- Chọn Nhóm Khoa --</option>
                            </select>
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
    </div>

    <script>
        const API = '/api/faculties';
        const GENERAL_API = '/api/faculty-generals';
        const PER_PAGE = 10;
        let allData = [],
            filteredData = [],
            generalsData = [],
            currentPage = 1;

        async function fetchData() {
            try {
                const [r, g] = await Promise.all([
                    fetch(API),
                    fetch(GENERAL_API)
                ]);
                allData = await r.json();
                generalsData = await g.json();
                populateGeneralsDropdown();
                filterTable();
            } catch (e) {
                showToast('Lỗi tải dữ liệu', 'error');
            }
        }

        function populateGeneralsDropdown() {
            const sel = document.getElementById('facultyGeneralId');
            sel.innerHTML = '<option value="">-- Không thuộc nhóm nào --</option>' +
                generalsData.map(g => `<option value="${g.id}">${g.name}</option>`).join('');
        }

        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            filteredData = allData.filter(f => (f.code || '').toLowerCase().includes(q) || (f.name || '').toLowerCase()
                .includes(q));
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
                tb.innerHTML = '<tr><td colspan="6"><div class="empty-state">Chưa có khoa nào</div></td></tr>';
            } else {
                tb.innerHTML = pageData.map(f => `<tr>
            <td>${f.id}</td>
            <td><span class="badge">${f.code||'-'}</span></td>
            <td><strong>${f.name||'-'}</strong></td>
            <td><span class="badge" style="background:#e0f2fe;color:#0369a1">${f.faculty_general ? f.faculty_general.name : 'chưa gán nhóm'}</span></td>
            <td>${f.created_at ? new Date(f.created_at).toLocaleDateString('vi-VN') : '-'}</td>
            <td><div class="actions">
                <button class="btn btn-sm btn-edit" onclick='editEntity(${JSON.stringify(f)})'>Sửa</button>
                <button class="btn btn-sm btn-delete" onclick="deleteEntity(${f.id})">Xóa</button>
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

            html += `<span class="page-info">${filteredData.length} bản ghi</span>`;
            html += `<button onclick="goPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}>›</button>`;
            html += `<button onclick="goPage(${totalPages})" ${currentPage===totalPages?'disabled':''}>»</button>`;
            pg.innerHTML = html;
        }

        function goPage(p) {
            currentPage = p;
            renderPage();
        }

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Thêm Khoa';
            document.getElementById('entityForm').reset();
            document.getElementById('entityId').value = '';
            document.getElementById('formModal').classList.add('active');
        }

        function editEntity(f) {
            document.getElementById('modalTitle').textContent = 'Cập nhật Khoa';
            document.getElementById('entityId').value = f.id;
            document.getElementById('facultyCode').value = f.code || '';
            document.getElementById('facultyName').value = f.name || '';
            document.getElementById('facultyGeneralId').value = f.faculty_general_id || '';
            document.getElementById('formModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('formModal').classList.remove('active');
        }

        async function saveEntity() {
            const id = document.getElementById('entityId').value;
            const body = {
                faculty_general_id: document.getElementById('facultyGeneralId').value || null,
                code: document.getElementById('facultyCode').value,
                name: document.getElementById('facultyName').value,
            };
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
            if (!confirm('Bạn có chắc muốn xóa khoa này?')) return;
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
@endsection
