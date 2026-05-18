@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Tin tức & Thông báo')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">
        <div class="page-header">
            <h1>Tin tức & Thông báo nội bộ</h1>
            <button class="btn btn-primary" onclick="openAddModal()">+ Thêm Bài viết</button>
        </div>
        
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo tiêu đề..." oninput="filterTable()">
        </div>

        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px">ID</th>
                            <th style="width: 100px">Thumbnail</th>
                            <th>Tiêu đề</th>
                            <th>Đối tượng</th>
                            <th>Chuyên mục</th>
                            <th>Ngày đăng</th>
                            <th style="width: 150px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($news as $n)
                        <tr class="news-row">
                            <td>{{ $n->id }}</td>
                            <td>
                                <img src="{{ asset($n->thumbnail) }}" alt="thumb" style="width:60px; height:40px; object-fit:cover; border-radius:4px;">
                            </td>
                            <td><strong>{{ $n->title }}</strong></td>
                            <td>
                                @if($n->target_audience == 'student') Sinh viên @elseif($n->target_audience == 'teacher') Giảng viên @else Tất cả @endif
                            </td>
                            <td><span class="badge">{{ $n->category }}</span></td>
                            <td>{{ $n->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-sm btn-edit" onclick="editEntity({{ $n->id }})">Sửa</button>
                                    <button class="btn btn-sm btn-delete" onclick="deleteEntity({{ $n->id }})">Xóa</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7"><div class="empty-state">Chưa có tin tức nào</div></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination" id="pagination"></div>
        </div>

        <!-- Add/Edit Modal (Mockup) -->
        <div class="modal-overlay" id="formModal">
            <div class="modal" style="max-width: 600px;">
                <div class="modal-header">
                    <h2 id="modalTitle">Thêm Bài Viết</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="entityForm">
                        <div class="form-group">
                            <label>Tiêu đề *</label>
                            <input type="text" id="nTitle" placeholder="Nhập tiêu đề tin tức" required>
                        </div>
                        <div style="display:flex; gap:1rem;">
                            <div class="form-group" style="flex:1;">
                                <label>Đối tượng xem *</label>
                                <select id="nTarget">
                                    <option value="all">Tất cả (SV & GV)</option>
                                    <option value="student">Chỉ Sinh viên</option>
                                    <option value="teacher">Chỉ Giảng viên</option>
                                </select>
                            </div>
                            <div class="form-group" style="flex:1;">
                                <label>Chuyên mục</label>
                                <input type="text" id="nCat" placeholder="VD: Đào tạo, Công tác...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Thumbnail (Đường dẫn ảnh)</label>
                            <input type="text" id="nThumb" placeholder="storage/images/news/anh.jpg">
                        </div>
                        <div class="form-group">
                            <label>Nội dung *</label>
                            <textarea id="nContent" rows="5" placeholder="Nhập nội dung bài viết..." style="width:100%; padding:0.5rem; border:1px solid #e2e8f0; border-radius:6px;"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                    <button class="btn btn-primary" onclick="saveEntity()">Lưu & Đăng</button>
                </div>
            </div>
        </div>

        <div class="toast" id="toast"></div>
    </div>

    <script>
        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.news-row');
            rows.forEach(r => {
                const title = r.children[2].textContent.toLowerCase();
                if (title.includes(q)) r.style.display = '';
                else r.style.display = 'none';
            });
        }

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Thêm Bài Viết Mới';
            document.getElementById('entityForm').reset();
            document.getElementById('formModal').classList.add('active');
        }

        function editEntity(id) {
            document.getElementById('modalTitle').textContent = 'Sửa Bài Viết';
            document.getElementById('formModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('formModal').classList.remove('active');
        }

        function saveEntity() {
            showToast('Lưu bài viết thành công!', 'success');
            closeModal();
            setTimeout(() => location.reload(), 1000);
        }

        function deleteEntity(id) {
            if (confirm('Bạn có chắc muốn xóa bài viết này?')) {
                showToast('Đã xóa bài viết!', 'success');
                setTimeout(() => location.reload(), 1000);
            }
        }

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = `toast toast-${type} show`;
            setTimeout(() => t.classList.remove('show'), 3000);
        }
    </script>
@endsection
