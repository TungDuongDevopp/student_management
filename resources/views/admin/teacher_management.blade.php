@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Giảng viên')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">
    <div class="content-wrapper">

        <div class="admin-banner">
            <div class="ab-content">
                <div class="ab-subtitle">ADMINISTRATION PORTAL</div>
                <div class="ab-title">Quản lý Giảng viên</div>
                <div class="ab-desc">Quản lý, theo dõi và cấu hình các thông tin liên quan đến giảng viên.</div>
            </div>
            <div class="ab-action">
                <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Giảng viên</button>
            </div>
            <div class="ab-decor"></div>
        </div>
        
        <div class="stats-row" style="margin-bottom: 1.5rem; display: flex; gap: 1rem;">
            <div class="stat-card" style="flex:1; background:var(--bg-card); border:1px solid var(--border); border-radius:8px; padding:1.25rem; display:flex; flex-direction:column; gap:0.2rem; text-align:center;">
                <span style="font-size:1.8rem; font-weight:700; color:#3b82f6;" id="statTotalTeachers">—</span>
                <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;">Tổng Giảng Viên</span>
            </div>
            <div class="stat-card" style="flex:1; background:var(--bg-card); border:1px solid var(--border); border-radius:8px; padding:1.25rem; display:flex; flex-direction:column; gap:0.2rem; text-align:center;">
                <span style="font-size:1.8rem; font-weight:700; color:#22c55e;" id="statActiveTeachers">—</span>
                <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;">Đang Giảng Dạy</span>
            </div>
            <div class="stat-card" style="flex:1; background:var(--bg-card); border:1px solid var(--border); border-radius:8px; padding:1.25rem; display:flex; flex-direction:column; gap:0.2rem; text-align:center;">
                <span style="font-size:1.8rem; font-weight:700; color:#ef4444;" id="statInactiveTeachers">—</span>
                <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;">Nghỉ / Dừng</span>
            </div>
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
            <div class="modal" style="width:620px">
                <div class="modal-header">
                    <h2 id="modalTitle">Thêm Giảng viên</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="entityForm" enctype="multipart/form-data">
                        <input type="hidden" id="entityId">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                            <div class="form-group" style="grid-column:span 2">
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
                                <label>Ngày sinh</label>
                                <input type="date" id="teacherDob">
                            </div>
                            <div class="form-group">
                                <label>Giới tính</label>
                                <select id="teacherGender">
                                    <option value="">-- Chọn --</option>
                                    <option value="0">Nam</option>
                                    <option value="1">Nữ</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" id="teacherPhone" placeholder="VD: 0912345678">
                            </div>
                            <div class="form-group">
                                <label>Bộ môn</label>
                                <input type="text" id="teacherDept" placeholder="VD: Kỹ thuật phần mềm">
                            </div>
                            <div class="form-group">
                                <label>Học vị</label>
                                <select id="teacherDegree">
                                    <option value="">-- Chọn --</option>
                                    <option value="Cử nhân">Cử nhân (CN)</option>
                                    <option value="Thạc sĩ">Thạc sĩ (Thạc sĩ)</option>
                                    <option value="Tiến sĩ">Tiến sĩ (TS)</option>
                                    <option value="Phó giáo sư">Phó Giáo sư (PGS)</option>
                                    <option value="Giáo sư">Giáo sư (GS)</option>
                                </select>
                            </div>
                            <div class="form-group" style="grid-column:span 2">
                                <label>Địa chỉ</label>
                                <input type="text" id="teacherAddress" placeholder="Địa chỉ thường trú">
                            </div>
                            <div class="form-group" style="grid-column:span 2">
                                <label>Ảnh đại diện</label>
                                <div class="image-upload" onclick="document.getElementById('teacherImage').click()">
                                    <img id="imagePreview" style="display:none">
                                    <p id="imageText">Nhấn để chọn ảnh (JPG, PNG, GIF - tối đa 2MB)</p>
                                </div>
                                <input type="file" id="teacherImage" accept="image/*" style="display:none"
                                    onchange="previewImg(this)">
                            </div>
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
                
                const active = allData.filter(t => !t.account || !t.account.is_locked).length;
                const inactive = allData.length - active;
                document.getElementById('statTotalTeachers').textContent = allData.length;
                document.getElementById('statActiveTeachers').textContent = active;
                document.getElementById('statInactiveTeachers').textContent = inactive;

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
                const [accounts, faculties] = await Promise.all([fetch('/api/accounts').then(r => r.json()), fetch(
                    '/api/faculties').then(r => r.json())]);
                // Chỉ hiển thị tài khoản có role Teacher và chưa gán cho GV nào
                const teacherAccounts = accounts.filter(a => a.role && a.role.name === 'Teacher' && !a.teacher);
                
                const currentAccountId = document.getElementById('entityId').value ? document.getElementById('accountId').dataset.current : null;
                if (currentAccountId) {
                    const currentAcc = accounts.find(a => a.id == currentAccountId);
                    if (currentAcc && !teacherAccounts.find(a => a.id == currentAcc.id)) {
                        teacherAccounts.push(currentAcc);
                    }
                }

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
                document.getElementById('accountId').dataset.current = t.account_id || '';
                loadDropdowns().then(() => {
                    document.getElementById('accountId').value = t.account_id || '';
                    document.getElementById('accountId').disabled = true;
                    document.getElementById('facultyId').value = t.faculty_id || '';
                });
                document.getElementById('teacherCode').value = t.teacher_code || '';
                document.getElementById('teacherName').value = t.name || '';
                document.getElementById('teacherEmail').value = t.email || '';
                document.getElementById('teacherDob').value = t.date_of_birth || '';
                document.getElementById('teacherGender').value = t.gender !== null && t.gender !== undefined ? t.gender : '';
                document.getElementById('teacherPhone').value = t.phone || '';
                document.getElementById('teacherDept').value = t.department || '';
                document.getElementById('teacherDegree').value = t.degree || '';
                document.getElementById('teacherAddress').value = t.address || '';
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
                const g = v => document.getElementById(v).value;
                if (g('teacherCode')) fd.append('teacher_code', g('teacherCode'));
                if (g('teacherName')) fd.append('name', g('teacherName'));
                if (g('teacherEmail')) fd.append('email', g('teacherEmail'));
                if (g('teacherDob')) fd.append('date_of_birth', g('teacherDob'));
                if (g('teacherGender') !== '') fd.append('gender', g('teacherGender'));
                if (g('teacherPhone')) fd.append('phone', g('teacherPhone'));
                if (g('teacherDept')) fd.append('department', g('teacherDept'));
                if (g('teacherDegree')) fd.append('degree', g('teacherDegree'));
                if (g('teacherAddress')) fd.append('address', g('teacherAddress'));
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
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
