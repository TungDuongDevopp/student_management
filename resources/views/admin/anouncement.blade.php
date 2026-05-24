@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Thông báo')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">
<style>
    /* Custom styles for content */



    /* KHUNG DANH SÁCH BÀI VIẾT (TIN TỨC) */
    .content-block {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        padding: 2rem;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .search-input {
        flex: 1;
        max-width: 400px;
        padding: 0.65rem 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #334155;
        outline: none;
        transition: 0.2s;
    }

    .search-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .filter-select {
        padding: 0.65rem 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #334155;
        outline: none;
        background: #fff;
        min-width: 180px;
    }

    /* ITEM BÀI BÁO CHUẨN SEO */
    .article-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .article-item {
        display: flex;
        gap: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .article-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .article-cover {
        width: 220px;
        height: 140px;
        border-radius: 8px;
        object-fit: cover;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .article-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .article-tags {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-blue { background: #eff6ff; color: #2563eb; }
    .badge-green { background: #f0fdf4; color: #16a34a; }

    .article-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #0f172a;
        margin: 0 0 0.5rem 0;
        line-height: 1.4;
        text-decoration: none;
        transition: color 0.2s;
    }

    .article-title:hover {
        color: #2563eb;
    }

    .article-desc {
        font-size: 0.85rem;
        color: #475569;
        line-height: 1.6;
        margin: 0 0 1rem 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        font-size: 0.8rem;
        color: #64748b;
        margin-top: auto;
    }

    .meta-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .meta-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e2e8f0;
    }

    .article-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        justify-content: flex-start;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-action:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .btn-action.delete:hover {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    /* Modal Style */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(15, 23, 42, 0.4);
        display: none; align-items: center; justify-content: center; z-index: 9999;
    }
    .modal-overlay.active { display: flex; }
    
    .modal-content {
        background: #fff; width: 850px; max-width: 95%;
        border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }
    
    .modal-header {
        padding: 1.5rem 2rem; border-bottom: 1px solid #e2e8f0;
        display: flex; justify-content: space-between; align-items: center;
    }
    .modal-header h2 { font-size: 1.15rem; font-weight: 700; color: #0f172a; margin: 0; }
    
    .modal-body {
        padding: 2rem; max-height: 70vh; overflow-y: auto;
    }
    
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.5rem; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.85rem; background-color: #fff; color: #0f172a; }
    .form-control:focus { outline: none; border-color: #3b82f6; }
</style>

<div class="content-wrapper">

    <div class="admin-banner">
        <div class="ab-content">
            <div class="ab-subtitle">ADMINISTRATION PORTAL</div>
            <div class="ab-title">Quản lý Thông báo</div>
            <div class="ab-desc">Soạn thảo, quản lý và xuất bản tin tức, thông báo cho sinh viên và giảng viên.</div>
        </div>
        <div class="ab-action">
            <button class="btn btn-primary" onclick="openModal('newsModal')">
                <i class="fa-solid fa-plus"></i> Soạn bài viết mới
            </button>
        </div>
        <div class="ab-decor"></div>
    </div>

    <!-- KHUNG NỘI DUNG CHÍNH -->
    <div class="content-block">
        
        @if(session('success'))
            <div style="padding: 1rem; background: #dcfce7; color: #166534; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; border: 1px solid #bbf7d0;">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="filter-row">
            <input type="text" class="search-input" placeholder="Tìm kiếm tiêu đề, người đăng...">
            <select class="filter-select">
                <option value="">Tất cả danh mục</option>
                <option value="1">Đào tạo & Học vụ</option>
                <option value="2">Hoạt động Ngoại khóa</option>
            </select>
            <select class="filter-select">
                <option value="">Mới nhất</option>
                <option value="old">Cũ nhất</option>
                <option value="views">Nhiều lượt xem</option>
            </select>
        </div>

        <div class="article-list">
            @forelse($news as $article)
            <div class="article-item">
                <img src="{{ asset($article->thumbnail) }}" alt="Thumbnail" class="article-cover">
                
                <div class="article-info">
                    <div class="article-tags">
                        <span class="badge badge-blue">{{ $article->category ?? 'Tin chung' }}</span>
                        @if($article->is_published)
                            <span class="badge badge-green">Đã xuất bản</span>
                        @endif
                        @if($article->target_audience == 'student')
                            <span class="badge" style="background:#fef3c7; color:#b45309;">Chỉ SV</span>
                        @elseif($article->target_audience == 'teacher')
                            <span class="badge" style="background:#fee2e2; color:#b91c1c;">Chỉ GV</span>
                        @endif
                    </div>
                    
                    <a href="#" class="article-title">{{ $article->title }}</a>
                    
                    <div class="article-desc">{!! strip_tags(Str::limit($article->content, 200)) !!}</div>
                    
                    <div class="article-meta">
                        <div class="meta-group">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=f1f5f9&color=0f172a" class="meta-avatar">
                            <span style="font-weight: 500; color: #0f172a;">Admin</span>
                        </div>
                        <div class="meta-group"><i class="fa-regular fa-clock"></i> {{ $article->created_at->format('d/m/Y - H:i') }}</div>
                    </div>
                </div>

                <div class="article-actions">
                    <form action="{{ route('admin.news.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này không?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action delete" title="Xóa bài"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 3rem 1rem; color: #94a3b8;">
                <i class="fa-regular fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                <p>Chưa có bài viết hay thông báo nào trong cơ sở dữ liệu.</p>
            </div>
            @endforelse
        </div>

    </div>

</div>

<!-- Modal Thêm/Sửa Bài Viết (Tích hợp TinyMCE) -->
<div class="modal-overlay" id="newsModal">
    <div class="modal-content">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h2>Soạn Thảo Bài Viết / Thông Báo</h2>
                <button type="button" onclick="closeModal('newsModal')" style="background:transparent; border:none; font-size:1.5rem; color:#94a3b8; cursor:pointer;">&times;</button>
            </div>
            <div class="modal-body">
                
                <div class="form-group">
                    <label>Tiêu đề bài viết <span style="color:#dc2626">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Nhập tiêu đề rõ ràng, súc tích..." required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label>Chuyên mục</label>
                        <select name="category" class="form-control">
                            <option value="Đào tạo & Học vụ">Đào tạo & Học vụ</option>
                            <option value="Hoạt động Đoàn Hội">Hoạt động Đoàn Hội</option>
                            <option value="Học phí & Tài chính">Học phí & Tài chính</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Đối tượng <span style="color:#dc2626">*</span></label>
                        <select name="target_audience" class="form-control" required>
                            <option value="all">Tất cả (SV & GV)</option>
                            <option value="student">Chỉ Sinh viên</option>
                            <option value="teacher">Chỉ Giảng viên</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Ảnh đại diện (Cover Image)</label>
                    <input type="file" name="thumbnail" id="thumbnail-input" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <small style="color:#64748b; margin-top:0.5rem; display:block;">Tỉ lệ ảnh chuẩn: 16:9. Dùng làm thumbnail hiển thị (Có thể bỏ trống).</small>
                    <div id="image-preview-container" style="display: none; margin-top: 1rem; position: relative;">
                        <img id="image-preview" src="#" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Nội dung chi tiết <span style="color:#dc2626">*</span></label>
                    <!-- Trình soạn thảo giống phần Feedback -->
                    <textarea id="admin-news-editor" name="content"></textarea>
                </div>

            </div>
            <div class="modal-header" style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom:none; justify-content:flex-end; gap:1rem;">
                <button type="button" onclick="closeModal('newsModal')" style="padding:0.6rem 1.25rem; border:1px solid #cbd5e1; border-radius:6px; background:#fff; color:#475569; font-weight:600; cursor:pointer;">Hủy bỏ</button>
                <button type="submit" class="btn-primary">Lưu & Xuất bản</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('image-preview');
            var container = document.getElementById('image-preview-container');
            output.src = reader.result;
            container.style.display = 'block';
        }
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    // Tắt cảnh báo phiên bản CKEditor
    CKEDITOR.config.versionCheck = false;

    // Init CKEditor 4
    CKEDITOR.replace('content', {
        height: 350,
        language: 'vi',
        removeButtons: 'About',
        toolbarGroups: [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            '/',
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] }
        ]
    });
</script>
@endsection
