@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Thông báo')

@section('content')
<style>
    /* VIBE HIỆN ĐẠI TƯƠNG TỰ MAPLEARN EDU (MINIMALIST, CLEAN) */
    .admin-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #334155;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Breadcrumb Header - Khung riêng, rõ ràng */
    .page-header-block {
        margin-bottom: 2rem;
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .breadcrumb-nav a {
        color: #3b82f6;
        text-decoration: none;
    }

    .breadcrumb-nav a:hover {
        text-decoration: underline;
    }

    .breadcrumb-nav i {
        font-size: 0.6rem;
        color: #cbd5e1;
    }

    .header-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .header-title-row h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .btn-primary {
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 0.6rem 1.25rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.2s;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

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
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.85rem; }
    .form-control:focus { outline: none; border-color: #3b82f6; }
</style>

<div class="admin-container">

    <!-- KHUNG HEADER VÀ BREADCRUMB -->
    <div class="page-header-block">
        <div class="breadcrumb-nav">
            <i class="fa-solid fa-house" style="font-size: 0.75rem;"></i>
            <a href="#">Dashboard</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Quản lý nội dung</span>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Thông báo</span>
        </div>

        <div class="header-title-row">
            <h1>Tin tức & Thông báo</h1>
            <button class="btn-primary" onclick="openModal('newsModal')">
                <i class="fa-solid fa-plus"></i> Soạn bài viết mới
            </button>
        </div>
    </div>

    <!-- KHUNG NỘI DUNG CHÍNH -->
    <div class="content-block">
        
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
            
            <!-- BÀI VIẾT 1 -->
            <div class="article-item">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c476?w=600&h=400&fit=crop" alt="Thumbnail" class="article-cover">
                
                <div class="article-info">
                    <div class="article-tags">
                        <span class="badge badge-blue">Đào tạo & Học vụ</span>
                        <span class="badge badge-green">Đã xuất bản</span>
                    </div>
                    
                    <a href="#" class="article-title">Thông báo lịch thi kết thúc học phần – Kỳ 2 năm học 2025–2026</a>
                    
                    <p class="article-desc">Căn cứ vào kế hoạch năm học 2025-2026, Phòng Đào tạo chính thức thông báo lịch thi kết thúc học phần cho Kỳ 2. Các bạn sinh viên vui lòng truy cập vào tài khoản cá nhân để xem lịch trình chi tiết và chuẩn bị đầy đủ giấy tờ cần thiết trước khi vào phòng thi.</p>
                    
                    <div class="article-meta">
                        <div class="meta-group">
                            <img src="https://ui-avatars.com/api/?name=Đào+Tạo&background=f1f5f9&color=0f172a" class="meta-avatar">
                            <span style="font-weight: 500; color: #0f172a;">Phòng Đào Tạo</span>
                        </div>
                        <div class="meta-group"><i class="fa-regular fa-clock"></i> 07/05/2026 - 14:30</div>
                        <div class="meta-group"><i class="fa-regular fa-eye"></i> 1,245 lượt xem</div>
                    </div>
                </div>

                <div class="article-actions">
                    <button class="btn-action" title="Sửa bài"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-action delete" title="Xóa bài"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>

            <!-- BÀI VIẾT 2 -->
            <div class="article-item">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=600&h=400&fit=crop" alt="Thumbnail" class="article-cover">
                
                <div class="article-info">
                    <div class="article-tags">
                        <span class="badge" style="background:#fff7ed; color:#c2410c;">Hoạt động Đoàn</span>
                        <span class="badge badge-green">Đã xuất bản</span>
                    </div>
                    
                    <a href="#" class="article-title">Đăng ký tham gia giải bóng đá Sinh viên toàn trường 2026</a>
                    
                    <p class="article-desc">Đoàn Thanh niên trường phát động giải bóng đá truyền thống khối sinh viên. Các liên chi đoàn nhanh chóng thành lập đội tuyển và nộp danh sách thi đấu về văn phòng Đoàn trường trước ngày 15/05/2026.</p>
                    
                    <div class="article-meta">
                        <div class="meta-group">
                            <img src="https://ui-avatars.com/api/?name=Đoàn+TN&background=f1f5f9&color=0f172a" class="meta-avatar">
                            <span style="font-weight: 500; color: #0f172a;">Đoàn Thanh Niên</span>
                        </div>
                        <div class="meta-group"><i class="fa-regular fa-clock"></i> 05/05/2026 - 09:00</div>
                        <div class="meta-group"><i class="fa-regular fa-eye"></i> 856 lượt xem</div>
                    </div>
                </div>

                <div class="article-actions">
                    <button class="btn-action" title="Sửa bài"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-action delete" title="Xóa bài"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Modal Thêm/Sửa Bài Viết (Tích hợp TinyMCE) -->
<div class="modal-overlay" id="newsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Soạn Thảo Bài Viết / Thông Báo</h2>
            <button onclick="closeModal('newsModal')" style="background:transparent; border:none; font-size:1.5rem; color:#94a3b8; cursor:pointer;">&times;</button>
        </div>
        <div class="modal-body">
            
            <div class="form-group">
                <label>Tiêu đề bài viết <span style="color:#dc2626">*</span></label>
                <input type="text" class="form-control" placeholder="Nhập tiêu đề rõ ràng, súc tích...">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>Chuyên mục</label>
                    <select class="form-control">
                        <option>Đào tạo & Học vụ</option>
                        <option>Hoạt động Đoàn Hội</option>
                        <option>Học phí & Tài chính</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Người đăng (Tác giả)</label>
                    <input type="text" class="form-control" value="Phòng Đào Tạo" readonly style="background:#f8fafc;">
                </div>
            </div>

            <div class="form-group">
                <label>Ảnh đại diện (Cover Image) <span style="color:#dc2626">*</span></label>
                <input type="file" class="form-control" accept="image/*">
                <small style="color:#64748b; margin-top:0.5rem; display:block;">Tỉ lệ ảnh chuẩn: 16:9 (Ví dụ: 800x450px). Dùng làm thumbnail hiển thị.</small>
            </div>

            <div class="form-group">
                <label>Mô tả ngắn (Hiển thị ngoài danh sách)</label>
                <textarea class="form-control" rows="3" placeholder="Nhập đoạn tóm tắt nội dung bài viết khoảng 2-3 câu..."></textarea>
            </div>

            <div class="form-group">
                <label>Nội dung chi tiết <span style="color:#dc2626">*</span></label>
                <!-- Trình soạn thảo giống phần Feedback -->
                <textarea id="admin-news-editor"></textarea>
            </div>

        </div>
        <div class="modal-header" style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom:none; justify-content:flex-end; gap:1rem;">
            <button onclick="closeModal('newsModal')" style="padding:0.6rem 1.25rem; border:1px solid #cbd5e1; border-radius:6px; background:#fff; color:#475569; font-weight:600; cursor:pointer;">Hủy bỏ</button>
            <button class="btn-primary">Lưu & Xuất bản</button>
        </div>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    // Init TinyMCE
    tinymce.init({
        selector: '#admin-news-editor',
        height: 350,
        plugins: 'advlist autolink lists link image preview table wordcount',
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table',
        menubar: false,
        branding: false
    });
</script>
@endsection
