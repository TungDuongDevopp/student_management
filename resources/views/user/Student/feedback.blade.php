@extends('layouts.user.student_sidebar')

@section('title', 'Góp ý & Phản hồi')

@section('content')
    <style>
        .feedback-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;
        }

        .page-hero {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #fff;
            border-radius: 10px;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 0.25rem;
            border: 1px solid #1d4ed8;
        }

        .page-hero::after {
            content: "";
            position: absolute;
            top: -80px;
            right: -60px;
            width: 260px;
            height: 260px;
            background: rgba(255, 255, 255, .08);
            transform: rotate(45deg);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-eyebrow {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            opacity: .85;
            font-weight: 700;
            margin-bottom: .3rem;
        }

        .hero-title {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 800;
        }

        .hero-desc {
            margin: .3rem 0 0;
            font-size: .85rem;
            opacity: .9;
        }

        .sem-badge {
            background: #fff;
            color: #1d4ed8;
            border-radius: 6px;
            padding: .35rem .75rem;
            font-size: .75rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .feedback-form {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group:last-of-type {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .form-label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.6rem 0.85rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.88rem;
            color: #0f172a;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.15s;
            background: #fff;
            box-sizing: border-box;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: #94a3b8;
        }

        .form-textarea {
            resize: vertical;
            min-height: 140px;
            line-height: 1.6;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-hint {
            font-size: 0.73rem;
            color: #94a3b8;
            margin-top: 0.3rem;
        }

        .file-upload {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            display: block;
            background: #f8fafc;
            transition: all 0.2s;
        }

        .file-upload:hover {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .file-upload i {
            font-size: 2rem;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            display: block;
        }

        .file-upload p {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
        }

        .file-upload span {
            font-size: 0.8rem;
            color: #64748b;
        }

        .file-upload input[type="file"] {
            display: none;
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.5rem;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
            font-family: 'Inter', sans-serif;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-reset {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            background: #fff;
            color: #64748b;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.88rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'Inter', sans-serif;
        }

        .btn-reset:hover {
            background: #f8fafc;
            color: #334155;
            border-color: #cbd5e1;
        }

        .history-section {
            background: #fff;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.6rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-header h2 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
        }

        .history-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.85rem 0;
            border-bottom: 1px solid #f8fafc;
            gap: 1rem;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.2rem;
        }

        .history-date {
            font-size: 0.73rem;
            color: #94a3b8;
        }

        .history-preview {
            font-size: 0.78rem;
            color: #64748b;
            margin-top: 0.15rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .history-reply {
            margin-top: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: #f0fdf4;
            border-left: 3px solid #22c55e;
            border-radius: 0 6px 6px 0;
            font-size: 0.78rem;
            color: #166534;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-replied {
            background: #dcfce7;
            color: #166534;
        }

        .status-closed {
            background: #f1f5f9;
            color: #475569;
        }

        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            display: block;
        }

        .empty-state p {
            font-size: 0.85rem;
        }

        .alert-msg {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            display: none;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-submit,
            .btn-reset {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="feedback-container">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li><a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
                <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                <li class="active">Gửi phản hồi</li>
            </ol>
        </nav>
        <section class="page-hero">
            <div class="hero-content">
                <div class="hero-eyebrow">Student Academic Portal</div>
                <h1 class="hero-title"><i class="fa-regular fa-comment-dots" style="margin-right:8px;"></i>GÓP Ý & PHẢN HỒI
                </h1>
                <p class="hero-desc">Gửi ý kiến đóng góp, thắc mắc hoặc phản ánh trực tiếp tới Ban Đào tạo Nhà trường</p>
            </div>
            <span class="sem-badge">Học Kỳ Kỳ này – Đang mở</span>
        </section>

        <section class="feedback-form" aria-label="Form gửi phản hồi">
            <div id="alertMsg" class="alert-msg"></div>
            <form id="feedbackForm">
                <div class="form-row" style="margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="feedback-category">Danh mục góp ý <span
                                class="required">*</span></label>
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
                    <label class="form-label" for="feedback-subject">Tiêu đề phản hồi <span
                            class="required">*</span></label>
                    <input type="text" id="feedback-subject" name="subject" class="form-input"
                        placeholder="Nhập tiêu đề ngắn gọn cho phản hồi" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="feedback-content">
                        Nội dung phản hồi <span class="required">*</span>
                    </label>
                    <textarea id="feedback-content" class="form-textarea" placeholder="Mô tả chi tiết nội dung bạn muốn phản hồi..."
                        required></textarea>
                    <p class="form-hint">Vui lòng cung cấp đủ thông tin để chúng tôi xử lý nhanh hơn.</p>
                </div>



                <div class="form-actions">
                    <button type="submit" class="btn-submit" id="submitBtn">
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
                <h2><i class="fa-solid fa-clock-rotate-left" style="margin-right:6px; color:#64748b;"></i>Phản hồi đã gửi
                </h2>
            </div>
            <div id="historyList">
                <div class="empty-state"><i class="fa-solid fa-spinner fa-spin"></i>
                    <p>Đang tải...</p>
                </div>
            </div>
        </section>

    </div>

    <script>
        const ACCOUNT_ID = {{ Auth::id() }};
        const API_URL = '/api/feedbacks';

        // ── GỬI PHẢN HỒI ──────────────────────────────────────────────────────────
        document.getElementById('feedbackForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const categorySelect = document.getElementById('feedback-category');
            const prioritySelect = document.getElementById('feedback-priority');
            const subjectInput = document.getElementById('feedback-subject');
            const contentTextarea = document.getElementById('feedback-content');

            const category = categorySelect.options[categorySelect.selectedIndex].text;
            const priority = prioritySelect.options[prioritySelect.selectedIndex].text;
            const subject = subjectInput.value.trim();
            const detail = contentTextarea.value.trim();

            if (!subject || !detail) return;

            // Đóng gói thông tin gửi lên server
            const content = `[${category}] [Mức độ: ${priority}] Tiêu đề: ${subject}\n\nChi tiết:\n${detail}`;

            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang gửi...';

            try {
                const res = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                            '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        account_id: ACCOUNT_ID,
                        content
                    })
                });
                if (!res.ok) throw new Error((await res.json()).message || 'Lỗi gửi phản hồi');
                showAlert('Phản hồi đã được gửi thành công!', 'success');
                this.reset();
                loadHistory();
            } catch (err) {
                showAlert('Lỗi: ' + err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-regular fa-paper-plane"></i> Gửi phản hồi';
            }
        });

        // ── TẢI LỊCH SỬ ───────────────────────────────────────────────────────────
        async function loadHistory() {
            try {
                const data = await fetch(API_URL).then(r => r.json());
                const mine = data
                    .filter(f => f.account_id === ACCOUNT_ID)
                    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                renderHistory(mine);
            } catch {
                document.getElementById('historyList').innerHTML =
                    '<p style="color:#ef4444;font-size:0.85rem;">Không thể tải lịch sử.</p>';
            }
        }

        function renderHistory(items) {
            const el = document.getElementById('historyList');
            if (!items.length) {
                el.innerHTML =
                    '<div class="empty-state"><i class="fa-regular fa-comment-dots"></i><p>Chưa có phản hồi nào được gửi.</p></div>';
                return;
            }
            el.innerHTML = items.map(f => {
                const date = new Date(f.created_at).toLocaleString('vi-VN');
                const badge = f.status === 1 ?
                    '<span class="status-badge status-replied">✓ Đã phản hồi</span>' :
                    '<span class="status-badge status-pending">⏳ Đang xử lý</span>';
                const reply = f.reply ?
                    `<div class="history-reply"><strong>Phản hồi từ nhà trường:</strong> ${escHtml(f.reply)}</div>` :
                    '';
                return `<div class="history-item">
                <div style="flex:1">
                    <p class="history-preview">${escHtml(f.content)}</p>
                    ${reply}
                    <p class="history-date">Gửi lúc: ${date}</p>
                </div>
                ${badge}
            </div>`;
            }).join('');
        }

        function escHtml(s) {
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function showAlert(msg, type) {
            const el = document.getElementById('alertMsg');
            el.textContent = msg;
            el.className = `alert-msg alert-${type}`;
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 4000);
        }

        loadHistory();
    </script>
@endsection
