@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Sinh viên')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">
    <div class="content-wrapper">

        <div class="page-header">
            <h1>Quản lý Sinh viên</h1>
            <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Sinh viên</button>
        </div>
        <div class="filter-tabs" id="filterTabs"></div>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên, mã SV, email..." oninput="filterTable()">
        </div>
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Mã SV</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Lớp</th>
                            <th>Tài khoản</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
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

        <!-- Add/Edit Modal -->
        <div class="modal-overlay" id="studentModal">
            <div class="modal">
                <div class="modal-header">
                    <h2 id="modalTitle">Thêm Sinh viên</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="studentForm" enctype="multipart/form-data">
                        <input type="hidden" id="studentId">
                        <div class="form-group">
                            <label>Tài khoản (Account) *</label>
                            <select id="accountId" required>
                                <option value="">-- Chọn tài khoản --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Lớp học</label>
                            <select id="classroomId">
                                <option value="">-- Chọn lớp --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Mã sinh viên</label>
                            <input type="text" id="studentCode" placeholder="VD: SV001">
                        </div>
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" id="studentName" placeholder="Nhập họ tên">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="studentEmail" placeholder="email@example.com">
                        </div>
                        <div class="form-group">
                            <label>Ảnh đại diện</label>
                            <div class="image-upload" onclick="document.getElementById('studentImage').click()">
                                <img id="imagePreview" style="display:none">
                                <p id="imageText">Nhấn để chọn ảnh (JPG, PNG, GIF - tối đa 2MB)</p>
                            </div>
                            <input type="file" id="studentImage" accept="image/*" style="display:none"
                                onchange="previewImage(this)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                    <button class="btn btn-primary" onclick="saveStudent()">Lưu</button>
                </div>
            </div>
        </div>
        <div class="toast" id="toast"></div>

        <script>
            const API = '/api/students';
            const PER_PAGE = 10;
            let allStudents = [],
                allClassrooms = [],
                filteredData = [],
                currentPage = 1,
                activeFilter = 'all';

            async function fetchStudents() {
                try {
                    const [studentsRes, classroomsRes] = await Promise.all([fetch(API), fetch('/api/classrooms')]);
                    allStudents = await studentsRes.json();
                    allClassrooms = await classroomsRes.json();
                    renderFilterTabs();
                    filterTable();
                } catch (e) {
                    showToast('Lỗi tải dữ liệu', 'error');
                }
            }

            function renderFilterTabs() {
                const tabs = document.getElementById('filterTabs');
                const counts = {
                    all: allStudents.length
                };
                allClassrooms.forEach(c => {
                    counts[c.id] = allStudents.filter(s => s.classroom_id === c.id).length;
                });
                const noClass = allStudents.filter(s => !s.classroom_id).length;
                let html =
                    `<div class="filter-tab ${activeFilter==='all'?'active':''}" onclick="setFilter('all')">Tất cả <span class="count">${counts.all}</span></div>`;
                allClassrooms.forEach(c => {
                    if (counts[c.id] > 0) html +=
                        `<div class="filter-tab ${activeFilter==c.id?'active':''}" onclick="setFilter(${c.id})">${c.code} <span class="count">${counts[c.id]}</span></div>`;
                });
                if (noClass > 0) html +=
                    `<div class="filter-tab ${activeFilter==='none'?'active':''}" onclick="setFilter('none')">Chưa có lớp <span class="count">${noClass}</span></div>`;
                tabs.innerHTML = html;
            }

            function setFilter(val) {
                activeFilter = val;
                renderFilterTabs();
                filterTable();
            }

            function filterTable() {
                const q = document.getElementById('searchInput').value.toLowerCase();
                filteredData = allStudents.filter(s => {
                    const matchSearch = (s.name || '').toLowerCase().includes(q) || (s.student_code || '').toLowerCase()
                        .includes(q) || (s.email || '').toLowerCase().includes(q);
                    let matchFilter = true;
                    if (activeFilter === 'none') matchFilter = !s.classroom_id;
                    else if (activeFilter !== 'all') matchFilter = s.classroom_id === activeFilter;
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

                const tbody = document.getElementById('studentTableBody');
                if (!filteredData.length) {
                    tbody.innerHTML = '<tr><td colspan="8"><div class="empty-state">Chưa có sinh viên nào</div></td></tr>';
                } else {
                    tbody.innerHTML = pageData.map(s => `<tr>
            <td>${s.id}</td>
            <td>${s.images ? `<img class="avatar" src="/storage/${s.images}">` : `<div class="avatar-placeholder">${(s.name||'?')[0].toUpperCase()}</div>`}</td>
            <td>${s.student_code||'-'}</td>
            <td><strong>${s.name||'-'}</strong></td>
            <td>${s.email||'-'}</td>
            <td>${s.classroom?.code||'-'}</td>
            <td>${s.account?.username||'-'}</td>
            <td><div class="actions">
                <button class="btn btn-sm btn-edit" onclick='editStudent(${JSON.stringify(s)})'>Sửa</button>
                <button class="btn btn-sm btn-delete" onclick="deleteStudent(${s.id})">Xóa</button>
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
                for (let i = 1; i <= totalPages; i++) {
                    html += `<button class="${i===currentPage?'active':''}" onclick="goPage(${i})">${i}</button>`;
                }
                html += `<span class="page-info">${filteredData.length} bản ghi</span>`;
                html += `<button onclick="goPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}>›</button>`;
                pg.innerHTML = html;
            }

            function goPage(p) {
                currentPage = p;
                renderPage();
            }

            async function loadDropdowns() {
                const [accounts, classrooms] = await Promise.all([fetch('/api/accounts').then(r => r.json()), fetch(
                    '/api/classrooms').then(r => r.json())]);
                // Chỉ hiển thị tài khoản có role Student
                const studentAccounts = accounts.filter(a => a.role && a.role.name === 'Student');
                document.getElementById('accountId').innerHTML = '<option value="">-- Chọn tài khoản --</option>' +
                    studentAccounts.map(a => `<option value="${a.id}">${a.username}</option>`).join('');
                document.getElementById('classroomId').innerHTML = '<option value="">-- Chọn lớp --</option>' + classrooms
                    .map(c => `<option value="${c.id}">${c.code}</option>`).join('');
            }

            function openAddModal() {
                document.getElementById('modalTitle').textContent = 'Thêm Sinh viên';
                document.getElementById('studentForm').reset();
                document.getElementById('studentId').value = '';
                document.getElementById('imagePreview').style.display = 'none';
                document.getElementById('imageText').style.display = 'block';
                document.getElementById('accountId').disabled = false;
                loadDropdowns();
                document.getElementById('studentModal').classList.add('active');
            }

            function editStudent(s) {
                document.getElementById('modalTitle').textContent = 'Cập nhật Sinh viên';
                document.getElementById('studentId').value = s.id;
                loadDropdowns().then(() => {
                    document.getElementById('accountId').value = s.account_id || '';
                    document.getElementById('accountId').disabled = true;
                    document.getElementById('classroomId').value = s.classroom_id || '';
                });
                document.getElementById('studentCode').value = s.student_code || '';
                document.getElementById('studentName').value = s.name || '';
                document.getElementById('studentEmail').value = s.email || '';
                if (s.images) {
                    document.getElementById('imagePreview').src = '/storage/' + s.images;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('imageText').style.display = 'none';
                } else {
                    document.getElementById('imagePreview').style.display = 'none';
                    document.getElementById('imageText').style.display = 'block';
                }
                document.getElementById('studentModal').classList.add('active');
            }

            function closeModal() {
                document.getElementById('studentModal').classList.remove('active');
            }

            function previewImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        document.getElementById('imagePreview').src = e.target.result;
                        document.getElementById('imagePreview').style.display = 'block';
                        document.getElementById('imageText').style.display = 'none';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            async function saveStudent() {
                const id = document.getElementById('studentId').value;
                const formData = new FormData();
                formData.append('account_id', document.getElementById('accountId').value);
                const clsId = document.getElementById('classroomId').value;
                if (clsId) formData.append('classroom_id', clsId);
                const code = document.getElementById('studentCode').value;
                if (code) formData.append('student_code', code);
                const name = document.getElementById('studentName').value;
                if (name) formData.append('name', name);
                const email = document.getElementById('studentEmail').value;
                if (email) formData.append('email', email);
                const imgFile = document.getElementById('studentImage').files[0];
                if (imgFile) formData.append('images', imgFile);
                try {
                    let url = API;
                    if (id) {
                        url = `${API}/${id}`;
                        formData.append('_method', 'PUT');
                    }
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: formData
                    });
                    if (!res.ok) {
                        const err = await res.json();
                        throw new Error(err.message || 'Lỗi');
                    }
                    showToast(id ? 'Cập nhật thành công!' : 'Thêm thành công!', 'success');
                    closeModal();
                    fetchStudents();
                } catch (e) {
                    showToast('Lỗi: ' + e.message, 'error');
                }
            }

            async function deleteStudent(id) {
                if (!confirm('Bạn có chắc muốn xóa sinh viên này?')) return;
                try {
                    const res = await fetch(`${API}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) throw new Error('Lỗi xóa');
                    showToast('Xóa thành công!', 'success');
                    fetchStudents();
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

            fetchStudents();
        </script>
    </div>
@endsection
