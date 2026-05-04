<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Giảng viên - Hệ thống Quản lý</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --bg-primary:#0f172a;--bg-secondary:#1e293b;--bg-card:#1e293b;--bg-input:#0f172a;
            --border:#334155;--accent:#3b82f6;--accent-hover:#2563eb;--danger:#ef4444;--danger-hover:#dc2626;
            --success:#22c55e;--warning:#f59e0b;--text:#f1f5f9;--text-muted:#94a3b8;--shadow:0 4px 24px rgba(0,0,0,0.3);
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
        .avatar{width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid var(--border)}
        .avatar-placeholder{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#8b5cf6,#ec4899);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.9rem}
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);z-index:1000;align-items:center;justify-content:center}
        .modal-overlay.active{display:flex}
        .modal{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;width:520px;max-width:95vw;max-height:90vh;overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,0.5)}
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
        .image-upload{border:2px dashed var(--border);border-radius:10px;padding:1.5rem;text-align:center;cursor:pointer;transition:all 0.2s}
        .image-upload:hover{border-color:var(--accent);background:rgba(59,130,246,0.05)}
        .image-upload img{max-width:120px;max-height:120px;border-radius:8px;margin-bottom:0.5rem}
        .image-upload p{color:var(--text-muted);font-size:0.85rem}
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
    <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Quản lý Giảng viên</div>
    <div class="page-header">
        <h1>Quản lý Giảng viên</h1>
        <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Giảng viên</button>
    </div>
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên, mã GV, email..." oninput="filterTable()">
    </div>
    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>ID</th><th>Ảnh</th><th>Mã GV</th><th>Họ tên</th><th>Email</th><th>Khoa</th><th>Tài khoản</th><th>Thao tác</th></tr></thead>
                <tbody id="tableBody"><tr><td colspan="8"><div class="empty-state">Đang tải dữ liệu...</div></td></tr></tbody>
            </table>
        </div>
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
                        <label>Tài khoản (Account ID) *</label>
                        <select id="accountId" required><option value="">-- Chọn tài khoản --</option></select>
                    </div>
                    <div class="form-group">
                        <label>Khoa *</label>
                        <select id="facultyId" required><option value="">-- Chọn khoa --</option></select>
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
                        <input type="file" id="teacherImage" accept="image/*" style="display:none" onchange="previewImg(this)">
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
let allData = [];

async function fetchData() {
    try { const r = await fetch(API); allData = await r.json(); renderTable(allData); } catch(e) { showToast('Lỗi tải dữ liệu','error'); }
}

function renderTable(data) {
    const tb = document.getElementById('tableBody');
    if (!data.length) { tb.innerHTML = '<tr><td colspan="8"><div class="empty-state">Chưa có giảng viên nào</div></td></tr>'; return; }
    tb.innerHTML = data.map(t => `<tr>
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

function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    renderTable(allData.filter(t => (t.name||'').toLowerCase().includes(q) || (t.teacher_code||'').toLowerCase().includes(q) || (t.email||'').toLowerCase().includes(q)));
}

async function loadDropdowns() {
    const [accounts, faculties] = await Promise.all([fetch('/api/accounts').then(r=>r.json()), fetch('/api/faculties').then(r=>r.json())]);
    document.getElementById('accountId').innerHTML = '<option value="">-- Chọn tài khoản --</option>' + accounts.map(a => `<option value="${a.id}">${a.username} (${a.role?.name||'N/A'})</option>`).join('');
    document.getElementById('facultyId').innerHTML = '<option value="">-- Chọn khoa --</option>' + faculties.map(f => `<option value="${f.id}">${f.name}</option>`).join('');
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
    loadDropdowns().then(() => { document.getElementById('accountId').value = t.account_id||''; document.getElementById('accountId').disabled = true; document.getElementById('facultyId').value = t.faculty_id||''; });
    document.getElementById('teacherCode').value = t.teacher_code||'';
    document.getElementById('teacherName').value = t.name||'';
    document.getElementById('teacherEmail').value = t.email||'';
    if (t.images) { document.getElementById('imagePreview').src='/storage/'+t.images; document.getElementById('imagePreview').style.display='block'; document.getElementById('imageText').style.display='none'; }
    else { document.getElementById('imagePreview').style.display='none'; document.getElementById('imageText').style.display='block'; }
    document.getElementById('formModal').classList.add('active');
}

function closeModal() { document.getElementById('formModal').classList.remove('active'); }

function previewImg(input) {
    if (input.files[0]) { const r=new FileReader(); r.onload=e=>{document.getElementById('imagePreview').src=e.target.result;document.getElementById('imagePreview').style.display='block';document.getElementById('imageText').style.display='none'}; r.readAsDataURL(input.files[0]); }
}

async function saveEntity() {
    const id = document.getElementById('entityId').value;
    const fd = new FormData();
    fd.append('account_id', document.getElementById('accountId').value);
    fd.append('faculty_id', document.getElementById('facultyId').value);
    const code = document.getElementById('teacherCode').value; if(code) fd.append('teacher_code', code);
    const name = document.getElementById('teacherName').value; if(name) fd.append('name', name);
    const email = document.getElementById('teacherEmail').value; if(email) fd.append('email', email);
    const img = document.getElementById('teacherImage').files[0]; if(img) fd.append('images', img);
    try {
        let url=API, method='POST';
        if(id){url=`${API}/${id}`;fd.append('_method','PUT');}
        const res = await fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''},body:fd});
        if(!res.ok){const err=await res.json();throw new Error(err.message||'Lỗi');}
        showToast(id?'Cập nhật thành công!':'Thêm thành công!','success'); closeModal(); fetchData();
    } catch(e) { showToast('Lỗi: '+e.message,'error'); }
}

async function deleteEntity(id) {
    if(!confirm('Bạn có chắc muốn xóa giảng viên này?')) return;
    try {
        const r=await fetch(`${API}/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||'','Accept':'application/json'}});
        if(!r.ok) throw new Error('Lỗi xóa');
        showToast('Xóa thành công!','success'); fetchData();
    } catch(e) { showToast('Lỗi: '+e.message,'error'); }
}

function showToast(msg,type='success') { const t=document.getElementById('toast');t.textContent=msg;t.className=`toast toast-${type} show`;setTimeout(()=>t.classList.remove('show'),3000); }
fetchData();
</script>
</body>
</html>
