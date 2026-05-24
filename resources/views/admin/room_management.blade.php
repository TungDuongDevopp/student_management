@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Phòng học')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">
        <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Quản lý Phòng học</div>
        <div class="page-header">
            <h1>Quản lý Phòng học</h1>
            <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Phòng</button>
        </div>

        <div class="filter-tabs" id="filterTabs"></div>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên phòng, nhà..." oninput="filterTable()">
        </div>
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nhà</th>
                            <th>Phòng</th>
                            <th>Mô tả</th>
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
                    <h2 id="modalTitle">Thêm Phòng</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="entityForm">
                        <input type="hidden" id="entityId">
                        <div class="form-group">
                            <label>Nhà (Block)</label>
                            <input type="text" id="roomBlock" placeholder="VD: A, B, C" maxlength="10">
                        </div>
                        <div class="form-group">
                            <label>Tên phòng *</label>
                            <input type="text" id="roomName" placeholder="VD: 101, 202" required>
                        </div>
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea id="roomDesc" placeholder="Mô tả phòng học (tùy chọn)"></textarea>
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
            const API = '/api/rooms';
            const PER_PAGE = 10;
            let allData = [],
                filteredData = [],
                currentPage = 1,
                activeFilter = 'all';

            async function fetchData() {
                try {
                    const r = await fetch(API);
                    allData = await r.json();
                    renderFilterTabs();
                    filterTable();
                } catch (e) {
                    showToast('Lỗi tải dữ liệu', 'error');
                }
            }

            function getBlocks() {
                const blocks = [...new Set(allData.map(r => r.block).filter(Boolean))].sort();
                return blocks;
            }

            function renderFilterTabs() {
                const tabs = document.getElementById('filterTabs');
                const blocks = getBlocks();
                const counts = {
                    all: allData.length
                };
                blocks.forEach(b => {
                    counts[b] = allData.filter(r => r.block === b).length;
                });
                const noBlock = allData.filter(r => !r.block).length;

                let html =
                    `<div class="filter-tab ${activeFilter==='all'?'active':''}" onclick="setFilter('all')">Tất cả <span class="count">${counts.all}</span></div>`;
                blocks.forEach(b => {
                    html +=
                        `<div class="filter-tab ${activeFilter===b?'active':''}" onclick="setFilter('${b}')">Nhà ${b} <span class="count">${counts[b]}</span></div>`;
                });
                if (noBlock > 0) html +=
                    `<div class="filter-tab ${activeFilter==='none'?'active':''}" onclick="setFilter('none')">Chưa xếp nhà <span class="count">${noBlock}</span></div>`;
                tabs.innerHTML = html;
            }

            function setFilter(val) {
                activeFilter = val;
                renderFilterTabs();
                filterTable();
            }

            function filterTable() {
                const q = document.getElementById('searchInput').value.toLowerCase();
                filteredData = allData.filter(r => {
                    const matchSearch = (r.name || '').toLowerCase().includes(q) || (r.block || '').toLowerCase()
                        .includes(q) || (r.description || '').toLowerCase().includes(q);
                    let matchFilter = true;
                    if (activeFilter === 'none') matchFilter = !r.block;
                    else if (activeFilter !== 'all') matchFilter = r.block === activeFilter;
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
                    tb.innerHTML = '<tr><td colspan="5"><div class="empty-state">Chưa có phòng nào</div></td></tr>';
                } else {
                    tb.innerHTML = pageData.map(r => `<tr>
                <td>${r.id}</td>
                <td>${r.block ? `<span class="badge">Nhà ${r.block}</span>` : '-'}</td>
                <td><strong>${r.name||'-'}</strong></td>
                <td>${r.description||'-'}</td>
                <td><div class="actions">
                    <button class="btn btn-sm btn-edit" onclick='editEntity(${JSON.stringify(r)})'>Sửa</button>
                    <button class="btn btn-sm btn-delete" onclick="deleteEntity(${r.id})">Xóa</button>
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
                document.getElementById('modalTitle').textContent = 'Thêm Phòng';
                document.getElementById('entityForm').reset();
                document.getElementById('entityId').value = '';
                document.getElementById('formModal').classList.add('active');
            }

            function editEntity(r) {
                document.getElementById('modalTitle').textContent = 'Cập nhật Phòng';
                document.getElementById('entityId').value = r.id;
                document.getElementById('roomBlock').value = r.block || '';
                document.getElementById('roomName').value = r.name || '';
                document.getElementById('roomDesc').value = r.description || '';
                document.getElementById('formModal').classList.add('active');
            }

            function closeModal() {
                document.getElementById('formModal').classList.remove('active');
            }

            async function saveEntity() {
                const id = document.getElementById('entityId').value;
                const body = {
                    block: document.getElementById('roomBlock').value || null,
                    name: document.getElementById('roomName').value,
                    description: document.getElementById('roomDesc').value || null
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
                if (!confirm('Bạn có chắc muốn xóa phòng này?')) return;
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
