@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Tài khoản')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">

        <div class="page-header">
            <h1>Quản lý Tài khoản</h1>
            <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Tài khoản</button>
        </div>

        <!-- Role Tabs -->
        <div class="role-tabs" id="roleTabs"></div>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo username..." oninput="filterTable()">
        </div>
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Quyền</th>
                            <th>Ngày tạo</th>
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

        <!-- Add Modal -->
        <div class="modal-overlay" id="addModal">
            <div class="modal">
                <div class="modal-header">
                    <h2>Thêm Tài khoản</h2>
                    <button class="modal-close" onclick="closeAddModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Quyền *</label>
                        <select id="addRoleId" required>
                            <option value="">-- Chọn quyền --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" id="addUsername" placeholder="Nhập username" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu *</label>
                        <input type="password" id="addPassword" placeholder="Nhập mật khẩu" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeAddModal()">Hủy</button>
                    <button class="btn btn-primary" onclick="createAccount()">Tạo tài khoản</button>
                </div>
            </div>
        </div>

        <!-- Edit Modal (chỉ update quyền) -->
        <div class="modal-overlay" id="editModal">
            <div class="modal">
                <div class="modal-header">
                    <h2>Cập nhật Quyền</h2>
                    <button class="modal-close" onclick="closeEditModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editId">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="editUsername" disabled>
                        <p class="info-text">Username không thể thay đổi</p>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="text" value="••••••••" disabled>
                        <p class="info-text">Mật khẩu không thể thay đổi</p>
                    </div>
                    <div class="form-group">
                        <label>Quyền *</label>
                        <select id="editRoleId" required>
                            <option value="">-- Chọn quyền --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeEditModal()">Hủy</button>
                    <button class="btn btn-primary" onclick="updateAccount()">Cập nhật</button>
                </div>
            </div>
        </div>

        <div class="toast" id="toast"></div>

        <script>
            const API = '/api/accounts';
            const PER_PAGE = 10;
            let allData = [],
                allRoles = [],
                filteredData = [],
                currentPage = 1,
                activeRoleFilter = 'all';

            async function fetchRoles() {
                try {
                    allRoles = await fetch('/api/roles').then(r => r.json());
                } catch (e) {}
            }

            function roleBadge(role) {
                if (!role) return '<span class="badge badge-default">N/A</span>';
                const n = role.name.toLowerCase();
                let cls = 'badge-default';
                if (n.includes('admin')) cls = 'badge-admin';
                else if (n.includes('sinh') || n.includes('student')) cls = 'badge-student';
                else if (n.includes('giảng') || n.includes('teacher')) cls = 'badge-teacher';
                return `<span class="badge ${cls}">${role.name}</span>`;
            }

            function renderRoleTabs() {
                const tabs = document.getElementById('roleTabs');
                const counts = {
                    all: allData.length
                };
                allRoles.forEach(r => {
                    counts[r.id] = allData.filter(a => a.role_id === r.id).length;
                });

                let html =
                    `<div class="role-tab ${activeRoleFilter==='all'?'active':''}" onclick="setRoleFilter('all')">Tất cả <span class="count">${counts.all}</span></div>`;
                allRoles.forEach(r => {
                    html +=
                        `<div class="role-tab ${activeRoleFilter==r.id?'active':''}" onclick="setRoleFilter(${r.id})">${r.name} <span class="count">${counts[r.id]||0}</span></div>`;
                });
                tabs.innerHTML = html;
            }

            function setRoleFilter(roleId) {
                activeRoleFilter = roleId;
                renderRoleTabs();
                filterTable();
            }

            async function fetchData() {
                try {
                    await fetchRoles();
                    const r = await fetch(API);
                    allData = await r.json();
                    renderRoleTabs();
                    filterTable();
                } catch (e) {
                    showToast('Lỗi tải dữ liệu', 'error');
                }
            }

            function filterTable() {
                const q = document.getElementById('searchInput').value.toLowerCase();
                filteredData = allData.filter(a => {
                    const matchSearch = a.username.toLowerCase().includes(q) || (a.role?.name || '').toLowerCase()
                        .includes(q);
                    const matchRole = activeRoleFilter === 'all' || a.role_id === activeRoleFilter;
                    return matchSearch && matchRole;
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
                    tb.innerHTML = '<tr><td colspan="5"><div class="empty-state">Chưa có tài khoản nào</div></td></tr>';
                } else {
                    tb.innerHTML = pageData.map(a => `<tr>
            <td>${a.id}</td>
            <td><strong>${a.username}</strong></td>
            <td>${roleBadge(a.role)}</td>
            <td>${a.created_at ? new Date(a.created_at).toLocaleDateString('vi-VN') : '-'}</td>
            <td><div class="actions">
                <button class="btn btn-sm btn-edit" onclick='editAccount(${JSON.stringify(a)})'>Sửa quyền</button>
                <button class="btn btn-sm btn-delete" onclick="deleteAccount(${a.id})">Xóa</button>
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

            function populateRoleSelect(selId, selectedVal) {
                const sel = document.getElementById(selId);
                sel.innerHTML = '<option value="">-- Chọn quyền --</option>' + allRoles.map(r =>
                    `<option value="${r.id}" ${r.id==selectedVal?'selected':''}>${r.name}</option>`).join('');
            }

            function openAddModal() {
                document.getElementById('addUsername').value = '';
                document.getElementById('addPassword').value = '';
                populateRoleSelect('addRoleId', '');
                document.getElementById('addModal').classList.add('active');
            }

            function closeAddModal() {
                document.getElementById('addModal').classList.remove('active');
            }

            function editAccount(a) {
                document.getElementById('editId').value = a.id;
                document.getElementById('editUsername').value = a.username;
                populateRoleSelect('editRoleId', a.role_id);
                document.getElementById('editModal').classList.add('active');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.remove('active');
            }

            async function createAccount() {
                const body = {
                    role_id: parseInt(document.getElementById('addRoleId').value),
                    username: document.getElementById('addUsername').value,
                    password: document.getElementById('addPassword').value
                };
                try {
                    const r = await fetch(API, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(body)
                    });
                    if (!r.ok) {
                        const e = await r.json();
                        throw new Error(e.message || 'Lỗi');
                    }
                    showToast('Tạo tài khoản thành công!', 'success');
                    closeAddModal();
                    fetchData();
                } catch (e) {
                    showToast('Lỗi: ' + e.message, 'error');
                }
            }

            async function updateAccount() {
                const id = document.getElementById('editId').value;
                const body = {
                    role_id: parseInt(document.getElementById('editRoleId').value)
                };
                try {
                    const r = await fetch(`${API}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(body)
                    });
                    if (!r.ok) {
                        const e = await r.json();
                        throw new Error(e.message || 'Lỗi');
                    }
                    showToast('Cập nhật quyền thành công!', 'success');
                    closeEditModal();
                    fetchData();
                } catch (e) {
                    showToast('Lỗi: ' + e.message, 'error');
                }
            }

            async function deleteAccount(id) {
                if (!confirm('Bạn có chắc muốn xóa tài khoản này?')) return;
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
