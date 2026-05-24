@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Học kỳ')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">
    <div class="content-wrapper">

        <div class="page-header">
            <h1>Quản lý Học kỳ</h1>
            <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Học kỳ</button>
        </div>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên học kỳ, năm học..." oninput="filterTable()">
        </div>
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên học kỳ</th>
                            <th>Năm học</th>
                            <th>Trạng thái</th>
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
                    <h2 id="modalTitle">Thêm Học kỳ</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="entityForm">
                        <input type="hidden" id="entityId">
                        <div class="form-group">
                            <label>Tên học kỳ *</label>
                            <input type="text" id="semName" placeholder="VD: Học kỳ 1" required>
                        </div>
                        <div class="form-group">
                            <label>Năm học *</label>
                            <input type="text" id="semYear" placeholder="VD: 2025-2026" required>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <div class="toggle-wrap">
                                <label class="toggle">
                                    <input type="checkbox" id="semStatus">
                                    <span class="slider"></span>
                                </label>
                                <span class="toggle-label" id="statusLabel">Không hoạt động</span>
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
            const API = '/api/semesters';
            const PER_PAGE = 10;
            let allData = [],
                filteredData = [],
                currentPage = 1;

            // Toggle label
            document.getElementById('semStatus').addEventListener('change', function() {
                document.getElementById('statusLabel').textContent = this.checked ? 'Đang hoạt động' :
                    'Không hoạt động';
            });

            async function fetchData() {
                try {
                    const r = await fetch(API);
                    allData = await r.json();
                    filterTable();
                } catch (e) {
                    showToast('Lỗi tải dữ liệu', 'error');
                }
            }

            function filterTable() {
                const q = document.getElementById('searchInput').value.toLowerCase();
                filteredData = allData.filter(s => (s.name || '').toLowerCase().includes(q) || (s.academic_year || '')
                    .toLowerCase().includes(q));
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
                    tb.innerHTML = '<tr><td colspan="5"><div class="empty-state">Chưa có học kỳ nào</div></td></tr>';
                } else {
                    tb.innerHTML = pageData.map(s => `<tr class="${s.status == 1 ? 'row-active' : ''}">
            <td>${s.id}</td>
            <td><strong>${s.name||'-'}</strong></td>
            <td>${s.academic_year||'-'}</td>
            <td>${s.status == 1 ? '<span class="badge badge-active">● Đang hoạt động</span>' : '<span class="badge badge-inactive">Không hoạt động</span>'}</td>
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
                document.getElementById('modalTitle').textContent = 'Thêm Học kỳ';
                document.getElementById('entityForm').reset();
                document.getElementById('entityId').value = '';
                document.getElementById('semStatus').checked = false;
                document.getElementById('statusLabel').textContent = 'Không hoạt động';
                document.getElementById('formModal').classList.add('active');
            }

            function editEntity(s) {
                document.getElementById('modalTitle').textContent = 'Cập nhật Học kỳ';
                document.getElementById('entityId').value = s.id;
                document.getElementById('semName').value = s.name || '';
                document.getElementById('semYear').value = s.academic_year || '';
                document.getElementById('semStatus').checked = s.status == 1;
                document.getElementById('statusLabel').textContent = s.status == 1 ? 'Đang hoạt động' : 'Không hoạt động';
                document.getElementById('formModal').classList.add('active');
            }

            function closeModal() {
                document.getElementById('formModal').classList.remove('active');
            }

            async function saveEntity() {
                const id = document.getElementById('entityId').value;
                const body = {
                    name: document.getElementById('semName').value,
                    academic_year: document.getElementById('semYear').value,
                    status: document.getElementById('semStatus').checked ? 1 : 0
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
                if (!confirm('Bạn có chắc muốn xóa học kỳ này?')) return;
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
