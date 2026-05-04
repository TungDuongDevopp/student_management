<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tài khoản - Hệ thống Quản lý</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .badge{padding:0.25rem 0.7rem;border-radius:20px;font-size:0.75rem;font-weight:600}
        .badge-admin{background:rgba(239,68,68,0.15);color:#f87171}
        .badge-student{background:rgba(34,197,94,0.15);color:#4ade80}
        .badge-teacher{background:rgba(59,130,246,0.15);color:#60a5fa}
        .badge-default{background:rgba(148,163,184,0.15);color:var(--text-muted)}
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
        .form-group input,.form-group select{width:100%;padding:0.7rem 1rem;background:var(--bg-input);border:1px solid var(--border);border-radius:8px;color:var(--text);font-size:0.9rem;transition:border-color 0.2s}
        .form-group input:focus,.form-group select:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,0.15)}
        .form-group input:disabled{opacity:0.5;cursor:not-allowed}
        .info-text{font-size:0.8rem;color:var(--text-muted);margin-top:0.3rem;font-style:italic}
        .toast{position:fixed;top:1.5rem;right:1.5rem;padding:1rem 1.5rem;border-radius:10px;color:#fff;font-weight:500;z-index:2000;transform:translateX(120%);transition:transform 0.3s ease;font-size:0.9rem}
        .toast.show{transform:translateX(0)}
        .toast-success{background:var(--success)}
        .toast-error{background:var(--danger)}
        .empty-state{text-align:center;padding:3rem;color:var(--text-muted)}
        .search-bar{display:flex;gap:1rem;margin-bottom:1.5rem}
        .search-bar input{flex:1;padding:0.7rem 1rem;background:var(--bg-card);border:1px solid var(--border);border-radius:8px;color:var(--text);font-size:0.9rem}
        .search-bar input:focus{outline:none;border-color:var(--accent)}
        @keyframes fadeIn{from{opacity:0;transform:scale(0.95)}to{opacity:1;transform:scale(1)}}
        .modal-overlay.active .modal{animation:fadeIn 0.2s ease}
    </style>
</head>
<body>
    <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Quản lý Tài khoản</div>
    <div class="page-header">
        <h1>Quản lý Tài khoản</h1>
        <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Tài khoản</button>
    </div>
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Tìm kiếm theo username, quyền..." oninput="filterTable()">
    </div>
    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>ID</th><th>Username</th><th>Quyền</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
                <tbody id="tableBody"><tr><td colspan="5"><div class="empty-state">Đang tải dữ liệu...</div></td></tr></tbody>
            </table>
        </div>
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
                    <select id="addRoleId" required><option value="">-- Chọn quyền --</option></select>
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
                    <select id="editRoleId" required><option value="">-- Chọn quyền --</option></select>
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
let allData = [], allRoles = [];

async function fetchRoles() {
    try { allRoles = await fetch('/api/roles').then(r=>r.json()); } catch(e) {}
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

async function fetchData() {
    try {
        await fetchRoles();
        const r = await fetch(API); allData = await r.json(); renderTable(allData);
    } catch(e) { showToast('Lỗi tải dữ liệu','error'); }
}

function renderTable(data) {
    const tb = document.getElementById('tableBody');
    if (!data.length) { tb.innerHTML = '<tr><td colspan="5"><div class="empty-state">Chưa có tài khoản nào</div></td></tr>'; return; }
    tb.innerHTML = data.map(a => `<tr>
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

function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    renderTable(allData.filter(a => a.username.toLowerCase().includes(q) || (a.role?.name||'').toLowerCase().includes(q)));
}

function populateRoleSelect(selId, selectedVal) {
    const sel = document.getElementById(selId);
    sel.innerHTML = '<option value="">-- Chọn quyền --</option>' + allRoles.map(r => `<option value="${r.id}" ${r.id==selectedVal?'selected':''}>${r.name}</option>`).join('');
}

function openAddModal() {
    document.getElementById('addUsername').value = '';
    document.getElementById('addPassword').value = '';
    populateRoleSelect('addRoleId', '');
    document.getElementById('addModal').classList.add('active');
}
function closeAddModal() { document.getElementById('addModal').classList.remove('active'); }

function editAccount(a) {
    document.getElementById('editId').value = a.id;
    document.getElementById('editUsername').value = a.username;
    populateRoleSelect('editRoleId', a.role_id);
    document.getElementById('editModal').classList.add('active');
}
function closeEditModal() { document.getElementById('editModal').classList.remove('active'); }

async function createAccount() {
    const body = { role_id: parseInt(document.getElementById('addRoleId').value), username: document.getElementById('addUsername').value, password: document.getElementById('addPassword').value };
    try {
        const r = await fetch(API, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||'','Accept':'application/json'}, body:JSON.stringify(body) });
        if(!r.ok){const e=await r.json();throw new Error(e.message||'Lỗi');}
        showToast('Tạo tài khoản thành công!','success'); closeAddModal(); fetchData();
    } catch(e) { showToast('Lỗi: '+e.message,'error'); }
}

async function updateAccount() {
    const id = document.getElementById('editId').value;
    const body = { role_id: parseInt(document.getElementById('editRoleId').value) };
    try {
        const r = await fetch(`${API}/${id}`, { method:'PUT', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||'','Accept':'application/json'}, body:JSON.stringify(body) });
        if(!r.ok){const e=await r.json();throw new Error(e.message||'Lỗi');}
        showToast('Cập nhật quyền thành công!','success'); closeEditModal(); fetchData();
    } catch(e) { showToast('Lỗi: '+e.message,'error'); }
}

async function deleteAccount(id) {
    if(!confirm('Bạn có chắc muốn xóa tài khoản này?')) return;
    try {
        const r = await fetch(`${API}/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||'','Accept':'application/json'}});
        if(!r.ok) throw new Error('Lỗi xóa');
        showToast('Xóa thành công!','success'); fetchData();
    } catch(e) { showToast('Lỗi: '+e.message,'error'); }
}

function showToast(msg,type='success') { const t=document.getElementById('toast');t.textContent=msg;t.className=`toast toast-${type} show`;setTimeout(()=>t.classList.remove('show'),3000); }
fetchData();
</script>
</body>
</html>
