@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Giảng viên')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">
<div class="content-wrapper">
    <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Quản lý Giảng viên</div>
    <div class="page-header">
        <h1>Quản lý Giảng viên</h1>
        <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Giảng viên</button>
    </div>
    <div class="filter-tabs" id="filterTabs"></div>
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên, mã GV, email..." oninput="filterTable()">
    </div>
    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Mã GV</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Khoa</th>
                        <th>Tài khoản</th>
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

    <div class="modal-overlay" id="formModal">
        <div class="modal">
            <div class="modal-header">
                <h2 id="modalTitle">Thêm Giảng viên</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="entityForm" enctype="multipart/form-data">
                    <input type="hidden" id="entityId">
                    <div class="form-group">
                        <label>Tài khoản (Account) *</label>
                        <select id="accountId" required>
                            <option value="">-- Chọn tài khoản --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Khoa *</label>
                        <select id="facultyId" required>
                            <option value="">-- Chọn khoa --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mã giảng viên</label>
                        <input type="text" id="teacherCode" placeholder="VD: GV001">
                    </div>
                    <div class="form-group">
                        <label>Họ và tên</label>
                        <input type="text" id="teacherName" placeholder="Nhập họ tên">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="teacherEmail" placeholder="email@example.com">
                    </div>
                    <div class="form-group">
                        <label>Ảnh đại diện</label>
                        <div class="image-upload" onclick="document.getElementById('teacherImage').click()">
                            <img id="imagePreview" style="display:none">
                            <p id="imageText">Nhấn để chọn ảnh (JPG, PNG, GIF - tối đa 2MB)</p>
                        </div>
                        <input type="file" id="teacherImage" accept="image/*" style="display:none"
                            onchange="previewImg(this)">
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
        const API = '/api/teachers';
        const PER_PAGE = 10;
        let allData = [],
            allFaculties = [],
            filteredData = [],
            currentPage = 1,
            activeFilter = 'all';

        async function fetchData() {
            try {
                const [teachersRes, facultiesRes] = await Promise.all([fetch(API), fetch('/api/faculties')]);
                allData = await teachersRes.json();
                allFaculties = await facultiesRes.json();
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
                counts[f.id] = allData.filter(t => t.faculty_id === f.id).length;
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
            filteredData = allData.filter(t => {
                const matchSearch = (t.name || '').toLowerCase().includes(q) || (t.teacher_code || '').toLowerCase()
                    .includes(q) || (t.email || '').toLowerCase().includes(q);
                const matchFilter = activeFilter === 'all' || t.faculty_id === activeFilter;
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
                tb.innerHTML = '<tr><td colspan="8"><div class="empty-state">Chưa có giảng viên nào</div></td></tr>';
            } else {
                tb.innerHTML = pageData.map(t => `<tr>
            <td>${t.id}</td>
            <td>${t.images ? `<img class="avatar" src="/storage/${t.images}">` : `<div class="avatar-placeholder">${(t.name||'?')[0].toUpperCase()}</div>`}</td>
            <td>${t.teacher_code||'-'}</td>
            <td><strong>${t.name||'-'}</strong></td>
            <td>${t.email||'-'}</td>
            <td>${t.faculty?.name||'-'}</td>
            <td>${t.account?.username||'-'}</td>
            <td><div class="actions">
                <button class="btn btn-sm btn-edit" onclick='editEntity(${JSON.stringify(t)})'>Sửa</button>
                <button class="btn btn-sm btn-delete" onclick="deleteEntity(${t.id})">Xóa</button>
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

        async function loadDropdowns() {
            const [accounts, faculties] = await Promise.all([fetch('/api/accounts').then(r => r.json()), fetch(
                '/api/faculties').then(r => r.json())]);
            // Chỉ hiển thị tài khoản có role Teacher
            const teacherAccounts = accounts.filter(a => a.role && a.role.name === 'Teacher');
            document.getElementById('accountId').innerHTML = '<option value="">-- Chọn tài khoản --</option>' +
                teacherAccounts.map(a => `<option value="${a.id}">${a.username}</option>`).join('');
            document.getElementById('facultyId').innerHTML = '<option value="">-- Chọn khoa --</option>' + faculties
                .map(f => `<option value="${f.id}">${f.name}</option>`).join('');
        }

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Thêm Giảng viên';
            document.getElementById('entityForm').reset();
            document.getElementById('entityId').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('imageText').style.display = 'block';
            document.getElementById('accountId').disabled = false;
            loadDropdowns();
            document.getElementById('formModal').classList.add('active');
        }

        function editEntity(t) {
            document.getElementById('modalTitle').textContent = 'Cập nhật Giảng viên';
            document.getElementById('entityId').value = t.id;
            loadDropdowns().then(() => {
                document.getElementById('accountId').value = t.account_id || '';
                document.getElementById('accountId').disabled = true;
                document.getElementById('facultyId').value = t.faculty_id || '';
            });
            document.getElementById('teacherCode').value = t.teacher_code || '';
            document.getElementById('teacherName').value = t.name || '';
            document.getElementById('teacherEmail').value = t.email || '';
            if (t.images) {
                document.getElementById('imagePreview').src = '/storage/' + t.images;
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('imageText').style.display = 'none';
            } else {
                document.getElementById('imagePreview').style.display = 'none';
                document.getElementById('imageText').style.display = 'block';
            }
            document.getElementById('formModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('formModal').classList.remove('active');
        }

        function previewImg(input) {
            if (input.files[0]) {
                const r = new FileReader();
                r.onload = e => {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('imageText').style.display = 'none'
                };
                r.readAsDataURL(input.files[0]);
            }
        }

        async function saveEntity() {
            const id = document.getElementById('entityId').value;
            const fd = new FormData();
            fd.append('account_id', document.getElementById('accountId').value);
            fd.append('faculty_id', document.getElementById('facultyId').value);
            const code = document.getElementById('teacherCode').value;
            if (code) fd.append('teacher_code', code);
            const name = document.getElementById('teacherName').value;
            if (name) fd.append('name', name);
            const email = document.getElementById('teacherEmail').value;
            if (email) fd.append('email', email);
            const img = document.getElementById('teacherImage').files[0];
            if (img) fd.append('images', img);
            try {
                let url = API;
                if (id) {
                    url = `${API}/${id}`;
                    fd.append('_method', 'PUT');
                }
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: fd
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
            if (!confirm('Bạn có chắc muốn xóa giảng viên này?')) return;
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
