@extends('layouts.admin.sidebar')
@section('title', 'Cài đặt hệ thống')

@section('content')
<style>
    /* VIBE HIỆN ĐẠI TƯƠNG TỰ MAPLEARN EDU (MINIMALIST, CLEAN) */
    .admin-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #334155;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Breadcrumb Header */
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

    /* GRID SETTINGS */
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        align-items: stretch;
    }



    /* CONTENT PANELS */
    .settings-panel {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        padding: 2rem;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .panel-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #0f172a;
        margin: 0 0 2rem 0;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .form-group .hint {
        display: block;
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.4rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #334155;
        outline: none;
        transition: 0.2s;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .upload-box {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: 0.2s;
        background: #f8fafc;
    }

    .upload-box:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }

    .preview-image {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: contain;
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 0.5rem;
    }
</style>

<div class="admin-container">

    <!-- KHUNG HEADER VÀ BREADCRUMB -->
    <div class="page-header-block">
        <div class="breadcrumb-nav">
            <i class="fa-solid fa-house" style="font-size: 0.75rem;"></i>
            <a href="#">Dashboard</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Cài đặt hệ thống</span>
        </div>

        <div class="header-title-row">
            <h1>Cài đặt Website Education</h1>
            <button class="btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
            </button>
        </div>
    </div>

    <!-- KHUNG NỘI DUNG CHÍNH -->
    <div class="settings-grid">
            
            <!-- PANEL 1: THÔNG TIN CHUNG -->
            <div class="settings-panel" id="general">
                <h2 class="panel-title">Thông tin chung</h2>
                
                <div class="form-group">
                    <label>Tên Trường / Tên hệ thống <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control" value="Hệ thống Quản lý Đào tạo - Đại học Mỏ - Địa chất">
                </div>

                <div class="form-group">
                    <label>Khẩu hiệu (Slogan)</label>
                    <input type="text" class="form-control" value="Đại học Mỏ - Địa chất (HUMG) - Nơi kiến tạo tương lai">
                </div>



                <div class="form-group">
                    <label>Đồng bộ Tin tức & Thông báo</label>
                    <div style="display:flex; gap: 1.5rem; margin-top:0.5rem;">
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                            <input type="radio" name="sync_news" checked style="accent-color:#2563eb;"> Có (Cho phép User xem)
                        </label>
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                            <input type="radio" name="sync_news" style="accent-color:#2563eb;"> Không
                        </label>
                    </div>
                    <span class="hint">Tính năng này sẽ tự động đẩy các bài viết ở mục Thông báo sang trang chủ của Sinh viên & Giảng viên.</span>
                </div>
            </div>

            <!-- PANEL 2: THƯƠNG HIỆU -->
            <div class="settings-panel" id="branding">
                <h2 class="panel-title">Nhận diện thương hiệu</h2>
                
                <div class="form-group">
                    <label>Logo Website (Sử dụng cho Sidebar Admin & User)</label>
                    <div class="upload-box">
                        <img src="https://lic.humg.edu.vn/App_Themes/humg/images/humg-logo.png" class="preview-image">
                        <div>
                            <p style="margin:0 0 0.5rem 0; font-weight:600; color:#0f172a; font-size:0.9rem;">Tải Logo lên</p>
                            <input type="file" accept="image/*" style="font-size:0.85rem; color:#64748b;">
                            <span class="hint" style="margin-top:0.5rem;">Kích thước khuyến nghị: 200x50px. Định dạng: PNG trong suốt.</span>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label>Favicon (Biểu tượng Tab)</label>
                    <div class="upload-box">
                        <img src="https://lic.humg.edu.vn/App_Themes/humg/images/humg-logo.png" class="preview-image" style="width:48px; height:48px; padding:0;">
                        <div>
                            <input type="file" accept=".ico,.png" style="font-size:0.85rem; color:#64748b;">
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label>Banner Đăng nhập (Login Background)</label>
                    <div class="upload-box" style="flex-direction: column; align-items: stretch; padding: 1rem;">
                        <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800&h=200&fit=crop" style="width: 100%; height: 120px; border-radius: 6px; object-fit: cover;" alt="Banner">
                        <input type="file" accept="image/*" style="font-size:0.85rem; color:#64748b; margin-top:0.5rem;">
                        <span class="hint" style="margin-top:0;">Độ phân giải 16:9, dùng làm nền trang đăng nhập.</span>
                    </div>
                </div>
            </div>

            <!-- PANEL 3: LIÊN HỆ -->
            <div class="settings-panel" id="contact">
                <h2 class="panel-title">Thông tin liên hệ & Mạng xã hội</h2>
                
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label>Email Hỗ trợ (Support)</label>
                        <input type="email" class="form-control" value="support@maplearn.edu.vn">
                    </div>
                    <div class="form-group">
                        <label>Hotline CSKH / Tuyển sinh</label>
                        <input type="text" class="form-control" value="1900 1234">
                    </div>
                </div>

                <div class="form-group">
                    <label>Địa chỉ Trụ sở</label>
                    <input type="text" class="form-control" value="Số 18 Phố Viên, Phường Đức Thắng, Quận Bắc Từ Liêm, Hà Nội">
                </div>

                <div class="form-group">
                    <label>Đường dẫn Fanpage Facebook</label>
                    <input type="url" class="form-control" value="https://facebook.com/humg.edu">
                </div>
            </div>

            <!-- PANEL 4: SEO -->
            <div class="settings-panel" id="seo">
                <h2 class="panel-title">Tối ưu Tìm kiếm (SEO)</h2>
                
                <div class="form-group">
                    <label>Mô tả Tìm kiếm (Meta Description)</label>
                    <textarea class="form-control" rows="4">Hệ thống quản lý đào tạo Đại học Mỏ - Địa chất (HUMG) - Nền tảng học tập trực tuyến thông minh dành cho sinh viên và giảng viên.</textarea>
                    <span class="hint">Đoạn mô tả ngắn gọn sẽ hiển thị trên Google khi có người tìm kiếm tên trường. Tối đa 160 ký tự.</span>
                </div>

                <div class="form-group">
                    <label>Từ khóa (Meta Keywords)</label>
                    <input type="text" class="form-control" value="đại học mỏ địa chất, humg, quản lý đào tạo">
                    <span class="hint">Các từ khóa cách nhau bởi dấu phẩy (,).</span>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection