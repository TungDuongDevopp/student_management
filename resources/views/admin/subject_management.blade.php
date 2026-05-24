@extends('layouts.admin.sidebar')

@section('title', 'Quản lý Môn học')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

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
                            <th>Mã môn</th>
                            <th>Tên môn học</th>
                            <th>Khoa</th>
                            <th>Số tín chỉ</th>
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
                            <label>Mã môn học</label>
                            <input type="text" id="subjectCode" placeholder="VD: CS101" maxlength="20">
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
                    tb.innerHTML = '<tr><td colspan="6"><div class="empty-state">Chưa có môn học nào</div></td></tr>';
                } else {
                    tb.innerHTML = pageData.map(s => `<tr>
            <td>${s.id}</td>
            <td><span class="badge badge-code">${s.code || '-'}</span></td>
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
                document.getElementById('subjectCode').value = s.code || '';
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
                    code: document.getElementById('subjectCode').value.trim() || null,
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
