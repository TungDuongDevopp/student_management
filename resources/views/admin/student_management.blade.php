@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Sinh viên')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">
    <div class="content-wrapper">

        <div class="admin-banner">
            <div class="ab-content">
                <div class="ab-subtitle">ADMINISTRATION PORTAL</div>
                <div class="ab-title">Quản lý Sinh viên</div>
                <div class="ab-desc">Quản lý, theo dõi và cấu hình các thông tin liên quan đến sinh viên.</div>
            </div>
            <div class="ab-action">
                <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Sinh viên</button>
            </div>
            <div class="ab-decor"></div>
        </div>
        
        <div class="stats-row" style="margin-bottom: 1.5rem; display: flex; gap: 1rem;">
            <div class="stat-card" style="flex:1; background:var(--bg-card); border:1px solid var(--border); border-radius:8px; padding:1.25rem; display:flex; flex-direction:column; gap:0.2rem; text-align:center;">
                <span style="font-size:1.8rem; font-weight:700; color:#3b82f6;" id="statTotalStudents">—</span>
                <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;">Tổng Sinh Viên</span>
            </div>
            <div class="stat-card" style="flex:1; background:var(--bg-card); border:1px solid var(--border); border-radius:8px; padding:1.25rem; display:flex; flex-direction:column; gap:0.2rem; text-align:center;">
                <span style="font-size:1.8rem; font-weight:700; color:#22c55e;" id="statActiveStudents">—</span>
                <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;">Đang Học</span>
            </div>
            <div class="stat-card" style="flex:1; background:var(--bg-card); border:1px solid var(--border); border-radius:8px; padding:1.25rem; display:flex; flex-direction:column; gap:0.2rem; text-align:center;">
                <span style="font-size:1.8rem; font-weight:700; color:#ef4444;" id="statInactiveStudents">—</span>
                <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;">Nghỉ / Dừng</span>
            </div>
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
            <div class="modal" style="width:600px">
                <div class="modal-header">
                    <h2 id="modalTitle">Thêm Sinh viên</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="studentForm" enctype="multipart/form-data">
                        <input type="hidden" id="studentId">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                            <div class="form-group" style="grid-column:span 2">
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
                                <label>Ngày sinh</label>
                                <input type="date" id="studentDob">
                            </div>
                            <div class="form-group">
                                <label>Giới tính</label>
                                <select id="studentGender">
                                    <option value="">-- Chọn --</option>
                                    <option value="0">Nam</option>
                                    <option value="1">Nữ</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" id="studentPhone" placeholder="VD: 0912345678">
                            </div>
                            <div class="form-group">
                                <label>Chuyên ngành</label>
                                <input type="text" id="studentSpec" placeholder="VD: Công nghệ phần mềm">
                            </div>
                            <div class="form-group" style="grid-column:span 2">
                                <label>Địa chỉ</label>
                                <input type="text" id="studentAddress" placeholder="Địa chỉ thường trú">
                            </div>
                            <div class="form-group" style="grid-column:span 2">
                                <label>Ảnh đại diện</label>
                                <div class="image-upload" onclick="document.getElementById('studentImage').click()">
                                    <img id="imagePreview" style="display:none">
                                    <p id="imageText">Nhấn để chọn ảnh (JPG, PNG, GIF - tối đa 2MB)</p>
                                </div>
                                <input type="file" id="studentImage" accept="image/*" style="display:none"
                                    onchange="previewImage(this)">
                            </div>
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
                
                const active = allStudents.filter(s => !s.account || !s.account.is_locked).length;
                const inactive = allStudents.length - active;
                document.getElementById('statTotalStudents').textContent = allStudents.length;
                document.getElementById('statActiveStudents').textContent = active;
                document.getElementById('statInactiveStudents').textContent = inactive;

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
                html += `<button onclick="goPage(${totalPages})" ${currentPage===totalPages?'disabled':''}>»</button>`;
                pg.innerHTML = html;
            }

            function goPage(p) {
                currentPage = p;
                renderPage();
            }

            async function loadDropdowns(currentAccountId = null) {
                const [accounts, classrooms] = await Promise.all([fetch('/api/accounts').then(r => r.json()), fetch(
                    '/api/classrooms').then(r => r.json())]);
                // Chỉ hiển thị tài khoản có role Student và chưa được gán cho sinh viên nào khác
                const studentAccounts = accounts.filter(a => a.role && a.role.name === 'Student' && !a.student);
                
                // Khi sửa, nếu tài khoản hiện tại đang thuộc về sinh viên này thì vẫn cho hiển thị
                const currentAccountId = document.getElementById('studentId').value ? document.getElementById('accountId').dataset.current : null;
                if (currentAccountId) {
                    const currentAcc = accounts.find(a => a.id == currentAccountId);
                    if (currentAcc && !studentAccounts.find(a => a.id == currentAcc.id)) {
                        studentAccounts.push(currentAcc);
                    }
                }

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
                document.getElementById('accountId').dataset.current = s.account_id || '';
                loadDropdowns().then(() => {
                    document.getElementById('accountId').value = s.account_id || '';
                    document.getElementById('accountId').disabled = true;
                    document.getElementById('classroomId').value = s.classroom_id || '';
                });
                document.getElementById('studentCode').value    = s.student_code || '';
                document.getElementById('studentName').value    = s.name || '';
                document.getElementById('studentEmail').value   = s.email || '';
                document.getElementById('studentDob').value     = s.date_of_birth || '';
                document.getElementById('studentGender').value  = s.gender !== null && s.gender !== undefined ? s.gender : '';
                document.getElementById('studentPhone').value   = s.phone_number || '';
                document.getElementById('studentSpec').value    = s.specialization || '';
                document.getElementById('studentAddress').value = s.address || '';
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
                const g = v => document.getElementById(v).value;
                if (g('studentCode'))    formData.append('student_code',   g('studentCode'));
                if (g('studentName'))    formData.append('name',            g('studentName'));
                if (g('studentEmail'))   formData.append('email',           g('studentEmail'));
                if (g('studentDob'))     formData.append('date_of_birth',   g('studentDob'));
                if (g('studentGender') !== '') formData.append('gender',    g('studentGender'));
                if (g('studentPhone'))   formData.append('phone_number',    g('studentPhone'));
                if (g('studentSpec'))    formData.append('specialization',  g('studentSpec'));
                if (g('studentAddress')) formData.append('address',         g('studentAddress'));
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
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
