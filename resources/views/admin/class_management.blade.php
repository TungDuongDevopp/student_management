@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Lớp học')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">
    <div class="content-wrapper">

        <div class="page-header">
            <h1>Quản lý Lớp học</h1>
            <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Lớp</button>
        </div>

        <div class="filter-tabs" id="filterTabs"></div>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo mã lớp, khoa, giảng viên..."
                oninput="filterTable()">
        </div>
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã lớp</th>
                            <th>Tên lớp</th>
                            <th>Khoa</th>
                            <th>GVCN</th>
                            <th>Sĩ số</th>
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

        <div class="modal-overlay" id="formModal">
            <div class="modal">
                <div class="modal-header">
                    <h2 id="modalTitle">Thêm Lớp</h2>
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
                            <label>Giảng viên chủ nhiệm</label>
                            <select id="teacherId">
                                <option value="">-- Chọn GV (tùy chọn) --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Mã lớp *</label>
                            <input type="text" id="classCode" placeholder="VD: CNTT01-K18" required>
                        </div>
                        <div class="form-group">
                            <label>Tên lớp *</label>
                            <input type="text" id="className" placeholder="VD: Công nghệ thông tin 1 K18" required>
                        </div>
                        <div class="form-group">
                            <label>Sĩ số</label>
                            <input type="number" id="classQuantity" placeholder="VD: 40" min="0">
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
            const API = '/api/classrooms';
            const PER_PAGE = 10;
            let allData = [], allFaculties = [], allTeachers = [];
            let filteredData = [], currentPage = 1, activeFilter = 'all';

            async function fetchData() {
                try {
                    const [classRes, facRes, teachRes] = await Promise.all([
                        fetch(API).then(r => r.json()),
                        fetch('/api/faculties').then(r => r.json()),
                        fetch('/api/teachers').then(r => r.json())
                    ]);
                    allData = classRes;
                    allFaculties = facRes;
                    allTeachers = teachRes;
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
                    counts[f.id] = allData.filter(c => c.faculty_id === f.id).length;
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
                filteredData = allData.filter(c => {
                    const matchSearch = (c.code || '').toLowerCase().includes(q) || (c.faculty?.name || '')
                        .toLowerCase().includes(q) || (c.teacher?.name || '').toLowerCase().includes(q);
                    const matchFilter = activeFilter === 'all' || c.faculty_id === activeFilter;
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
                    tb.innerHTML = '<tr><td colspan="7"><div class="empty-state">Chưa có lớp nào</div></td></tr>';
                } else {
                    tb.innerHTML = pageData.map(c => `<tr>
            <td>${c.id}</td>
            <td><strong>${c.code||'-'}</strong></td>
            <td>${c.name||'-'}</td>
            <td>${c.faculty ? `<span class="badge badge-faculty">${c.faculty.name}</span>` : '-'}</td>
            <td>${c.teacher?.name||'-'}</td>
            <td>${c.quantity||'-'}</td>
            <td><div class="actions">
                <button class="btn btn-sm btn-edit" onclick='editEntity(${JSON.stringify(c)})'>Sửa</button>
                <button class="btn btn-sm btn-delete" onclick="deleteEntity(${c.id})">Xóa</button>
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

            function populateDropdowns(selectedFac, selectedTeach) {
                document.getElementById('facultyId').innerHTML = '<option value="">-- Chọn khoa --</option>' + allFaculties.map(
                    f => `<option value="${f.id}" ${f.id==selectedFac?'selected':''}>${f.name}</option>`).join('');
                
                const usedTeacherIds = allData.map(c => c.teacher_id).filter(id => id && id !== selectedTeach);
                const availableTeachers = allTeachers.filter(t => !usedTeacherIds.includes(t.id));
                
                document.getElementById('teacherId').innerHTML = '<option value="">-- Chọn GV (tùy chọn) --</option>' +
                    availableTeachers.map(t =>
                        `<option value="${t.id}" ${t.id==selectedTeach?'selected':''}>${t.name} (${t.teacher_code||'N/A'})</option>`
                    ).join('');
            }

            function openAddModal() {
                document.getElementById('modalTitle').textContent = 'Thêm Lớp';
                document.getElementById('entityForm').reset();
                document.getElementById('entityId').value = '';
                populateDropdowns('', '');
                document.getElementById('formModal').classList.add('active');
            }

            function editEntity(c) {
                document.getElementById('modalTitle').textContent = 'Cập nhật Lớp';
                document.getElementById('entityId').value = c.id;
                populateDropdowns(c.faculty_id, c.teacher_id);
                document.getElementById('classCode').value = c.code || '';
                document.getElementById('className').value = c.name || '';
                document.getElementById('classQuantity').value = c.quantity || '';
                document.getElementById('formModal').classList.add('active');
            }

            function closeModal() {
                document.getElementById('formModal').classList.remove('active');
            }

            async function saveEntity() {
                const id = document.getElementById('entityId').value;
                const body = {
                    faculty_id: parseInt(document.getElementById('facultyId').value),
                    code: document.getElementById('classCode').value,
                    name: document.getElementById('className').value
                };
                const teachId = document.getElementById('teacherId').value;
                if (teachId) body.teacher_id = parseInt(teachId);
                else body.teacher_id = null;
                const qty = document.getElementById('classQuantity').value;
                if (qty) body.quantity = parseInt(qty);
                else body.quantity = null;

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
                if (!confirm('Bạn có chắc muốn xóa lớp này?')) return;
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
