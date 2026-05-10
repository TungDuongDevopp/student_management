@extends('layouts.admin.sidebar')
@section('title', 'Admin Panel')
@section('content')
<style>
        :root {
            --bg-primary:#0f172a;--bg-secondary:#1e293b;--bg-card:#1e293b;--bg-input:#0f172a;
            --border:#334155;--accent:#3b82f6;--accent-hover:#2563eb;--danger:#ef4444;
            --success:#22c55e;--text:#f1f5f9;--text-muted:#94a3b8;--shadow:0 4px 24px rgba(0,0,0,0.3);
        }
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif}
        body{background:var(--bg-primary);color:var(--text);min-height:100vh;padding:2rem}
        .page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem}
        .page-header h1{font-size:1.8rem;font-weight:700;background:linear-gradient(135deg,#3b82f6,#8b5cf6);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .breadcrumb{color:var(--text-muted);font-size:0.85rem;margin-bottom:0.5rem}
        .breadcrumb a{color:var(--accent);text-decoration:none}
        .btn{padding:0.6rem 1.2rem;border:none;border-radius:8px;font-size:0.9rem;font-weight:600;cursor:pointer;transition:all 0.2s;display:inline-flex;align-items:center;gap:0.5rem}
        .btn-primary{background:var(--accent);color:#fff}
        .btn-primary:hover{background:var(--accent-hover);transform:translateY(-1px)}
        .btn-sm{padding:0.4rem 0.8rem;font-size:0.8rem;border-radius:6px}
        .btn-edit{background:rgba(59,130,246,0.15);color:var(--accent);border:1px solid rgba(59,130,246,0.3)}
        .btn-edit:hover{background:rgba(59,130,246,0.25)}
        .btn-delete{background:rgba(239,68,68,0.15);color:var(--danger);border:1px solid rgba(239,68,68,0.3)}
        .btn-delete:hover{background:rgba(239,68,68,0.25)}
        .btn-secondary{background:var(--bg-input);color:var(--text-muted);border:1px solid var(--border)}
        .btn-secondary:hover{border-color:var(--accent);color:var(--text)}
        .card{background:var(--bg-card);border:1px solid var(--border);border-radius:12px;box-shadow:var(--shadow);overflow:hidden}
        .table-wrapper{overflow-x:auto}
        table{width:100%;border-collapse:collapse}
        thead{background:rgba(59,130,246,0.08)}
        th{padding:1rem;text-align:left;font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid var(--border)}
        td{padding:0.85rem 1rem;border-bottom:1px solid rgba(51,65,85,0.5);font-size:0.9rem;vertical-align:middle}
        tr:hover{background:rgba(59,130,246,0.04)}
        .actions{display:flex;gap:0.5rem}
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);z-index:1000;align-items:center;justify-content:center}
        .modal-overlay.active{display:flex}
        .modal{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;width:480px;max-width:95vw;max-height:90vh;overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,0.5)}
        .modal-header{padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center}
        .modal-header h2{font-size:1.2rem;font-weight:600}
        .modal-close{background:none;border:none;color:var(--text-muted);font-size:1.5rem;cursor:pointer}
        .modal-close:hover{color:var(--text)}
        .modal-body{padding:1.5rem}
        .modal-footer{padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem}
        .form-group{margin-bottom:1.25rem}
        .form-group label{display:block;margin-bottom:0.4rem;font-size:0.85rem;font-weight:500;color:var(--text-muted)}
        .form-group input{width:100%;padding:0.7rem 1rem;background:var(--bg-input);border:1px solid var(--border);border-radius:8px;color:var(--text);font-size:0.9rem;transition:border-color 0.2s}
        .form-group input:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,0.15)}
        .toast{position:fixed;top:1.5rem;right:1.5rem;padding:1rem 1.5rem;border-radius:10px;color:#fff;font-weight:500;z-index:2000;transform:translateX(120%);transition:transform 0.3s ease;font-size:0.9rem}
        .toast.show{transform:translateX(0)}
        .toast-success{background:var(--success)}
        .toast-error{background:var(--danger)}
        .empty-state{text-align:center;padding:3rem;color:var(--text-muted)}
        .search-bar{display:flex;gap:1rem;margin-bottom:1.5rem}
        .search-bar input{flex:1;padding:0.7rem 1rem;background:var(--bg-card);border:1px solid var(--border);border-radius:8px;color:var(--text);font-size:0.9rem}
        .search-bar input:focus{outline:none;border-color:var(--accent)}
        .pagination{display:flex;justify-content:center;align-items:center;gap:0.5rem;padding:1rem;border-top:1px solid var(--border)}
        .pagination button{padding:0.5rem 0.9rem;border:1px solid var(--border);border-radius:6px;background:var(--bg-input);color:var(--text-muted);cursor:pointer;font-size:0.85rem;transition:all 0.2s}
        .pagination button:hover:not(:disabled){border-color:var(--accent);color:var(--text)}
        .pagination button.active{background:var(--accent);color:#fff;border-color:var(--accent)}
        .pagination button:disabled{opacity:0.4;cursor:not-allowed}
        .pagination .page-info{color:var(--text-muted);font-size:0.85rem;margin:0 0.5rem}
        .badge{padding:0.25rem 0.7rem;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(59,130,246,0.15);color:#60a5fa}
        @keyframes fadeIn{from{opacity:0;transform:scale(0.95)}to{opacity:1;transform:scale(1)}}
        .modal-overlay.active .modal{animation:fadeIn 0.2s ease}
    </style>
<div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Quản lý Khoa</div>
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
                <thead><tr><th>ID</th><th>Mã khoa</th><th>Tên khoa</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
                <tbody id="tableBody"><tr><td colspan="5"><div class="empty-state">Đang tải dữ liệu...</div></td></tr></tbody>
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
const API = '/api/faculties';
const PER_PAGE = 10;
let allData = [], filteredData = [], currentPage = 1;

async function fetchData() {
    try {
        const r = await fetch(API);
        allData = await r.json();
        filterTable();
    } catch(e) { showToast('Lỗi tải dữ liệu','error'); }
}

function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    filteredData = allData.filter(f => (f.code||'').toLowerCase().includes(q) || (f.name||'').toLowerCase().includes(q));
    currentPage = 1;
    renderPage();
}

function renderPage() {
    const totalPages = Math.max(1, Math.ceil(filteredData.length / PER_PAGE));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage - 1) * PER_PAGE;
    const pageData = filteredData.slice(start, start + PER_PAGE);

    const tb = document.getElementById('tableBody');
    if (!filteredData.length) { tb.innerHTML = '<tr><td colspan="5"><div class="empty-state">Chưa có khoa nào</div></td></tr>'; }
    else {
        tb.innerHTML = pageData.map(f => `<tr>
            <td>${f.id}</td>
            <td><span class="badge">${f.code||'-'}</span></td>
            <td><strong>${f.name||'-'}</strong></td>
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
    if (totalPages <= 1) { pg.innerHTML = ''; return; }
    let html = `<button onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}>‹</button>`;
    for (let i = 1; i <= totalPages; i++) html += `<button class="${i===currentPage?'active':''}" onclick="goPage(${i})">${i}</button>`;
    html += `<span class="page-info">${filteredData.length} bản ghi</span>`;
    html += `<button onclick="goPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}>›</button>`;
    pg.innerHTML = html;
}

function goPage(p) { currentPage = p; renderPage(); }

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
    document.getElementById('formModal').classList.add('active');
}

function closeModal() { document.getElementById('formModal').classList.remove('active'); }

async function saveEntity() {
    const id = document.getElementById('entityId').value;
    const body = {
        code: document.getElementById('facultyCode').value,
        name: document.getElementById('facultyName').value
    };
    try {
        const url = id ? `${API}/${id}` : API;
        const method = id ? 'PUT' : 'POST';
        const res = await fetch(url, { method, headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||'','Accept':'application/json'}, body: JSON.stringify(body) });
        if (!res.ok) { const err = await res.json(); throw new Error(err.message || 'Lỗi'); }
        showToast(id ? 'Cập nhật thành công!' : 'Thêm thành công!', 'success');
        closeModal(); fetchData();
    } catch(e) { showToast('Lỗi: ' + e.message, 'error'); }
}

async function deleteEntity(id) {
    if (!confirm('Bạn có chắc muốn xóa khoa này?')) return;
    try {
        const r = await fetch(`${API}/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||'','Accept':'application/json'} });
        if (!r.ok) throw new Error('Lỗi xóa');
        showToast('Xóa thành công!', 'success'); fetchData();
    } catch(e) { showToast('Lỗi: ' + e.message, 'error'); }
}

function showToast(msg, type='success') { const t=document.getElementById('toast');t.textContent=msg;t.className=`toast toast-${type} show`;setTimeout(()=>t.classList.remove('show'),3000); }
fetchData();
</script>
@endsection