@extends('layouts.user.student_sidebar')

@section('title', 'Góp ý & Phản hồi')

@section('content')
<style>
    .feedback-container { display: flex; flex-direction: column; gap: 1.5rem; max-width: 860px; }

    .page-header {
        background: #fff; border-radius: 8px; padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .page-header h1 { font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem; }
    .page-header p { font-size: 0.85rem; color: #64748b; line-height: 1.5; }

    .feedback-form {
        background: #fff; border-radius: 8px; padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .form-group { margin-bottom: 1.25rem; }
    .form-group:last-of-type { margin-bottom: 0; }

    .form-label {
        display: block; font-size: 0.82rem; font-weight: 600; color: #334155;
        margin-bottom: 0.4rem;
    }
    .form-label .required { color: #ef4444; margin-left: 2px; }

    .form-input, .form-select, .form-textarea {
        width: 100%; padding: 0.6rem 0.85rem; border: 1px solid #e2e8f0;
        border-radius: 6px; font-size: 0.88rem; color: #0f172a;
        font-family: 'Inter', sans-serif; transition: border-color 0.15s;
        background: #fff;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    }
    .form-input::placeholder, .form-textarea::placeholder { color: #94a3b8; }

    .form-textarea { resize: vertical; min-height: 140px; line-height: 1.6; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    .form-hint { font-size: 0.73rem; color: #94a3b8; margin-top: 0.3rem; }

    .file-upload {
        border: 2px dashed #e2e8f0; border-radius: 8px; padding: 1.5rem;
        text-align: center; cursor: pointer; transition: all 0.2s;
        background: #fafbfc;
    }
    .file-upload:hover { border-color: #2563eb; background: #f8faff; }
    .file-upload i { font-size: 1.5rem; color: #94a3b8; margin-bottom: 0.5rem; }
    .file-upload p { font-size: 0.82rem; color: #64748b; }
    .file-upload span { font-size: 0.72rem; color: #94a3b8; }
    .file-upload input[type="file"] { display: none; }

    .form-actions { display: flex; gap: 0.75rem; margin-top: 1.25rem; }

    .btn-submit {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.65rem 1.5rem; background: #2563eb; color: #fff;
        border: none; border-radius: 6px; font-size: 0.88rem; font-weight: 600;
        cursor: pointer; transition: background 0.15s; font-family: 'Inter', sans-serif;
    }
    .btn-submit:hover { background: #1d4ed8; }

    .btn-reset {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.65rem 1.25rem; background: #fff; color: #64748b;
        border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.88rem; font-weight: 500;
        cursor: pointer; transition: all 0.15s; font-family: 'Inter', sans-serif;
    }
    .btn-reset:hover { background: #f8fafc; color: #334155; border-color: #cbd5e1; }

    .history-section {
        background: #fff; border-radius: 8px; padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .section-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1rem; padding-bottom: 0.6rem; border-bottom: 1px solid #f1f5f9;
    }
    .section-header h2 { font-size: 0.95rem; font-weight: 600; color: #0f172a; }

    .history-item {
        display: flex; justify-content: space-between; align-items: flex-start;
        padding: 0.85rem 0; border-bottom: 1px solid #f8fafc; gap: 1rem;
    }
    .history-item:last-child { border-bottom: none; }
    .history-title { font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.2rem; }
    .history-date { font-size: 0.73rem; color: #94a3b8; }
    .history-preview { font-size: 0.78rem; color: #64748b; margin-top: 0.15rem; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }

    .status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.7rem; font-weight: 600; padding: 3px 10px;
        border-radius: 20px; white-space: nowrap; flex-shrink: 0;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-replied { background: #dcfce7; color: #166534; }
    .status-closed { background: #f1f5f9; color: #475569; }

    .empty-state {
        text-align: center; padding: 2rem 1rem; color: #94a3b8;
    }
    .empty-state i { font-size: 2rem; margin-bottom: 0.75rem; display: block; }
    .empty-state p { font-size: 0.85rem; }

    @media (max-width: 640px) {
        .form-row { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .btn-submit, .btn-reset { width: 100%; justify-content: center; }
    }
</style>

<div class="feedback-container">

    <header class="page-header">
        <h1><i class="fa-regular fa-comment-dots" style="margin-right:6px; color:#2563eb;"></i>Góp ý & Phản hồi</h1>
        <p>Gửi ý kiến, góp ý hoặc phản hồi đến Nhà trường. Mọi phản hồi sẽ được tiếp nhận và xử lý trong vòng 3–5 ngày làm việc.</p>
    </header>

    <section class="feedback-form" aria-label="Form gửi phản hồi">
        <form action="#" method="POST" enctype="multipart/form-data" id="feedbackForm">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="feedback-name">Họ và tên</label>
                    <input type="text" id="feedback-name" class="form-input"
                        value="{{ Auth::user()->student->name ?? Auth::user()->username ?? '' }}" readonly
                        style="background:#f8fafc; color:#64748b;">
                </div>
                <div class="form-group">
                    <label class="form-label" for="feedback-email">Email liên hệ</label>
                    <input type="email" id="feedback-email" class="form-input"
                        value="{{ Auth::user()->student->email ?? '' }}" readonly
                        style="background:#f8fafc; color:#64748b;">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="feedback-category">Danh mục <span class="required">*</span></label>
                    <select id="feedback-category" name="category" class="form-select" required>
                        <option value="" disabled selected>— Chọn danh mục —</option>
                        <option value="academic">Học vụ & Đào tạo</option>
                        <option value="facility">Cơ sở vật chất</option>
                        <option value="service">Dịch vụ sinh viên</option>
                        <option value="system">Hệ thống CNTT</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="feedback-priority">Mức độ ưu tiên</label>
                    <select id="feedback-priority" name="priority" class="form-select">
                        <option value="normal" selected>Bình thường</option>
                        <option value="high">Quan trọng</option>
                        <option value="urgent">Khẩn cấp</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="feedback-subject">Tiêu đề <span class="required">*</span></label>
                <input type="text" id="feedback-subject" name="subject" class="form-input"
                    placeholder="Nhập tiêu đề ngắn gọn cho phản hồi" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="feedback-content">Nội dung chi tiết <span class="required">*</span></label>
                <textarea id="feedback-content" name="content" class="form-textarea"
                    placeholder="Mô tả chi tiết nội dung bạn muốn phản hồi..." required></textarea>
                <p class="form-hint">Vui lòng cung cấp đủ thông tin để chúng tôi xử lý nhanh hơn.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Đính kèm tài liệu (nếu có)</label>
                <label class="file-upload" for="feedback-file">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p>Kéo thả hoặc <strong style="color:#2563eb;">bấm để chọn file</strong></p>
                    <span>Hỗ trợ: JPG, PNG, PDF — Tối đa 5MB</span>
                    <input type="file" id="feedback-file" name="attachment" accept=".jpg,.jpeg,.png,.pdf">
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fa-regular fa-paper-plane"></i> Gửi phản hồi
                </button>
                <button type="reset" class="btn-reset">
                    <i class="fa-solid fa-rotate-left"></i> Nhập lại
                </button>
            </div>
        </form>
    </section>

    {{-- ===== LỊCH SỬ PHẢN HỒI ===== --}}
    <section class="history-section" aria-label="Lịch sử phản hồi">
        <div class="section-header">
            <h2><i class="fa-solid fa-clock-rotate-left" style="margin-right:6px; color:#64748b;"></i>Phản hồi đã gửi</h2>
        </div>

        {{-- Dữ liệu mẫu — Khi có Backend sẽ thay bằng @foreach --}}
        <div class="history-item">
            <div>
                <p class="history-title">Phòng học A3-302 hỏng máy lạnh</p>
                <p class="history-preview">Máy lạnh phòng A3-302 không hoạt động từ ngày 28/04, ảnh hưởng đến việc học tập của sinh viên...</p>
                <p class="history-date">Gửi lúc: 29/04/2026</p>
            </div>
            <span class="status-badge status-replied">✓ Đã phản hồi</span>
        </div>
        <div class="history-item">
            <div>
                <p class="history-title">Đề xuất mở thêm lớp học phần Trí tuệ nhân tạo</p>
                <p class="history-preview">Em muốn đề xuất Khoa CNTT mở thêm lớp học phần AI vào kỳ Hè 2026 vì nhu cầu đăng ký rất cao...</p>
                <p class="history-date">Gửi lúc: 15/04/2026</p>
            </div>
            <span class="status-badge status-pending">⏳ Đang xử lý</span>
        </div>
        <div class="history-item">
            <div>
                <p class="history-title">Hệ thống đăng ký môn học bị lỗi</p>
                <p class="history-preview">Khi đăng ký môn Mạng máy tính, hệ thống báo lỗi 500 và không lưu được...</p>
                <p class="history-date">Gửi lúc: 02/04/2026</p>
            </div>
            <span class="status-badge status-closed">Đã đóng</span>
        </div>
    </section>

</div>
@endsection
