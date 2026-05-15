@extends('layouts.user.teacher_sidebar')

@section('title', 'Góp ý & Phản hồi')

@section('content')
<style>
    .feedback-container { display: flex; flex-direction: column; gap: 1.5rem; width: 100%; }

    .page-header {
        background: #fff; border-radius: 8px; padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .page-header h1 { font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem; }
    .page-header p  { font-size: 0.85rem; color: #64748b; line-height: 1.5; }

    .feedback-form {
        background: #fff; border-radius: 8px; padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .form-group { margin-bottom: 1.25rem; }

    .form-label {
        display: block; font-size: 0.82rem; font-weight: 600; color: #334155;
        margin-bottom: 0.4rem;
    }
    .form-label .required { color: #ef4444; margin-left: 2px; }

    .form-input, .form-textarea {
        width: 100%; padding: 0.6rem 0.85rem; border: 1px solid #e2e8f0;
        border-radius: 6px; font-size: 0.88rem; color: #0f172a;
        font-family: 'Inter', sans-serif; transition: border-color 0.15s;
        background: #fff; box-sizing: border-box;
    }
    .form-input:focus, .form-textarea:focus {
        outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    }
    .form-input::placeholder, .form-textarea::placeholder { color: #94a3b8; }
    .form-textarea { resize: vertical; min-height: 160px; line-height: 1.6; }

    .form-hint { font-size: 0.73rem; color: #94a3b8; margin-top: 0.3rem; }
    .form-actions { display: flex; gap: 0.75rem; margin-top: 1.25rem; }

    .btn-submit {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.65rem 1.5rem; background: #2563eb; color: #fff;
        border: none; border-radius: 6px; font-size: 0.88rem; font-weight: 600;
        cursor: pointer; transition: background 0.15s; font-family: 'Inter', sans-serif;
    }
    .btn-submit:hover    { background: #1d4ed8; }
    .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

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
    .history-date    { font-size: 0.73rem; color: #94a3b8; margin-top: 0.2rem; }
    .history-preview {
        font-size: 0.78rem; color: #64748b; margin-top: 0.15rem; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .history-reply {
        margin-top: 0.5rem; padding: 0.5rem 0.75rem;
        background: #f0fdf4; border-left: 3px solid #22c55e;
        border-radius: 0 6px 6px 0; font-size: 0.78rem; color: #166534;
    }

    .status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.7rem; font-weight: 600; padding: 3px 10px;
        border-radius: 20px; white-space: nowrap; flex-shrink: 0;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-replied { background: #dcfce7; color: #166534; }

    .empty-state { text-align: center; padding: 2rem 1rem; color: #94a3b8; }
    .empty-state i { font-size: 2rem; margin-bottom: 0.75rem; display: block; }
    .empty-state p { font-size: 0.85rem; }

    .alert-msg {
        padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.85rem;
        margin-bottom: 1rem; display: none;
    }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    @media (max-width: 640px) {
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
        <div id="alertMsg" class="alert-msg"></div>
        <form id="feedbackForm">
            <div class="form-group">
                <label class="form-label">Họ & Tên Giảng viên</label>
                <input type="text" class="form-input"
                    value="{{ Auth::user()->teacher->name ?? Auth::user()->username ?? '' }}"
                    readonly style="background:#f8fafc; color:#64748b;">
            </div>

            <div class="form-group">
                <label class="form-label" for="feedback-content">
                    Nội dung phản hồi <span class="required">*</span>
                </label>
                <textarea id="feedback-content" class="form-textarea"
                    placeholder="Mô tả chi tiết nội dung bạn muốn phản hồi..." required></textarea>
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
            <h2><i class="fa-solid fa-clock-rotate-left" style="margin-right:6px; color:#64748b;"></i>Phản hồi đã gửi</h2>
        </div>
        <div id="historyList">
            <div class="empty-state"><i class="fa-solid fa-spinner fa-spin"></i><p>Đang tải...</p></div>
        </div>
    </section>

</div>

<script>
    const ACCOUNT_ID = {{ Auth::id() }};
    const API_URL    = '/api/feedbacks';

    // ── GỬI PHẢN HỒI ──────────────────────────────────────────────────────────
    document.getElementById('feedbackForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const content = document.getElementById('feedback-content').value.trim();
        if (!content) return;

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang gửi...';

        try {
            const res = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ account_id: ACCOUNT_ID, content })
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
            el.innerHTML = '<div class="empty-state"><i class="fa-regular fa-comment-dots"></i><p>Chưa có phản hồi nào được gửi.</p></div>';
            return;
        }
        el.innerHTML = items.map(f => {
            const date = new Date(f.created_at).toLocaleString('vi-VN');
            const badge = f.status === 1
                ? '<span class="status-badge status-replied">✓ Đã phản hồi</span>'
                : '<span class="status-badge status-pending">⏳ Đang xử lý</span>';
            const reply = f.reply
                ? `<div class="history-reply"><strong>Phản hồi từ nhà trường:</strong> ${escHtml(f.reply)}</div>`
                : '';
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
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
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
