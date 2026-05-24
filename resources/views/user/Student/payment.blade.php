@extends('layouts.user.student_sidebar')
@section('title', 'Thanh toán học phí')
@section('content')
<style>
.pay-wrap { width: 100%; box-sizing: border-box; }
.page-hero { background:linear-gradient(135deg,#1e40af 0%,#3b82f6 100%);color:#fff;border-radius:10px;padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;position:relative;overflow:hidden;margin-bottom:1.5rem;border:1px solid #1d4ed8; }
.page-hero::after { content:"";position:absolute;top:-80px;right:-60px;width:260px;height:260px;background:rgba(255,255,255,.08);transform:rotate(45deg); }
.hero-content { position:relative;z-index:1; }
.hero-eyebrow { font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;opacity:.85;font-weight:700;margin-bottom:.3rem; }
.hero-title { margin:0;font-size:1.35rem;font-weight:800; }
.hero-desc { margin:.3rem 0 0;font-size:.85rem;opacity:.9; }
.sem-badge { background:#fff;color:#1d4ed8;border-radius:6px;padding:.35rem .75rem;font-size:.75rem;font-weight:700;position:relative;z-index:1; }

.pay-grid { display:grid; grid-template-columns: 1.2fr 1fr; gap:2rem; align-items:start; }
@media (max-width: 900px) { .pay-grid { grid-template-columns: 1fr; } }

.pay-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
.pay-card-head { padding:1rem 1.25rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
.pay-card-head h3 { font-size:0.95rem; font-weight:700; color:#1e293b; margin:0; }

/* Summary */
.summary-item { display:flex; justify-content:space-between; align-items:center; padding:0.75rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.875rem; }
.summary-item:last-child { border-bottom:none; }
.summary-item .label { color:#64748b; }
.summary-item .value { font-weight:700; color:#1e293b; }
.summary-total { display:flex; justify-content:space-between; align-items:center; padding:1rem 1.25rem; background:linear-gradient(135deg,#fef2f2,#fff5f5); border-top:2px solid #fca5a5; }
.summary-total .label { font-size:0.9rem; font-weight:600; color:#991b1b; }
.summary-total .amount { font-size:1.8rem; font-weight:800; color:#ef4444; }

/* QR */
.qr-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; position:sticky; top:1rem; }
.qr-head { padding:1rem 1.25rem; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; }
.qr-head h3 { margin:0; font-size:0.95rem; font-weight:700; }
.qr-head p { margin:0.25rem 0 0; font-size:0.78rem; opacity:0.85; }
.qr-body { padding:1.25rem; }
.qr-img-wrap { display:flex; justify-content:center; margin-bottom:1.5rem; padding: 1rem; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1; }
.qr-img-wrap img { width:260px; max-width:100%; height:auto; object-fit:contain; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
.bank-row { display:flex; justify-content:space-between; align-items:center; padding:0.75rem 0; border-bottom:1px dashed #e2e8f0; }
.bank-row:last-child { border-bottom:none; }
.bkey { font-size:0.85rem; color:#64748b; font-weight: 600; }
.bval { font-size:0.95rem; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:0.5rem; text-align: right; }
.copy-btn { background:#eff6ff; border:none; color:#2563eb; font-size:0.7rem; padding:0.15rem 0.5rem; border-radius:4px; cursor:pointer; font-weight:700; transition:all 0.15s; }
.copy-btn:hover { background:#2563eb; color:#fff; }
.notice-box { background:#fffbeb; border:1px solid #fcd34d; border-radius:8px; padding:0.75rem; margin-top:1rem; font-size:0.78rem; color:#92400e; line-height:1.6; }
.notice-box strong { color:#78350f; }
.btn-check { display:block; width:100%; margin-top:1rem; padding:0.8rem; background:#16a34a; color:#fff; border:none; border-radius:8px; font-weight:700; font-size:0.9rem; cursor:pointer; transition:background 0.15s; }
.btn-check:hover { background:#15803d; }
.btn-back { display:inline-flex; align-items:center; gap:0.4rem; color:#64748b; font-size:0.82rem; text-decoration:none; margin-bottom:1rem; }
.btn-back:hover { color:#2563eb; }
</style>

@php
    $student_code = $student->student_code ?? 'SV001';
    $semester_label = ($activeSemester->name ?? 'HK') . ($activeSemester->academic_year ?? '');

    $price_per_credit = $student->classroom->faculty->facultyGeneral->tuition_fee_per_credit ?? 480000;
    $total_fee = $tuition->total_amount ?? 0;
    $paid = $tuition->paid_amount ?? 0;
    $remaining = $total_fee - $paid;

    $configs = \App\Models\SystemConfig::all()->pluck('value', 'key');
    $bank_name = trim($configs['bank_name'] ?? 'MB');
    $bank_account = trim($configs['bank_account'] ?? '0388123456');
    $bank_owner = mb_strtoupper(trim($configs['bank_owner'] ?? 'TRUONG DAI HOC ABC'));
    
    // Tạo nội dung chuyển khoản tự động
    $transfer_content = 'HOCPHI ' . $student_code . ' ' . ($activeSemester->id ?? 1);

    // Xử lý tạo link VietQR
    // VietQR hỗ trợ tên ngắn (như MB, VCB, ACB) hoặc mã BIN
    $bank_id_for_qr = urlencode(str_replace(' ', '', $bank_name)); 
    $qr_url = "https://img.vietqr.io/image/{$bank_id_for_qr}-{$bank_account}-compact2.jpg?amount={$remaining}&addInfo=" .
        urlencode($transfer_content) . '&accountName=' . urlencode($bank_owner);

    $pending_payment = $payments->where('status', 'pending')->first();
@endphp

<div class="pay-wrap">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li><a href="{{ route('student.tuition') }}">Học phí</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Thanh toán</li>
        </ol>
    </nav>

    <section class="page-hero">
        <div class="hero-content">
            <div class="hero-eyebrow">Student Academic Portal</div>
            <h1 class="hero-title"><i class="fa-solid fa-qrcode" style="margin-right:8px;"></i>THANH TOÁN HỌC PHÍ</h1>
            <p class="hero-desc">Quét mã QR để thanh toán nhanh qua ứng dụng ngân hàng</p>
        </div>
        <span class="sem-badge">{{ $semester_label }}</span>
    </section>

    @if($remaining <= 0)
    <div style="background:#f0fdf4; border:1px solid #86efac; border-radius:10px; padding:0.75rem 1.25rem; margin-bottom:1.25rem; font-size:0.875rem; color:#166534; font-weight:600; display:flex; align-items:center; gap:0.6rem;">
        <i class="fa-solid fa-circle-check"></i>
        Bạn đã thanh toán đầy đủ học phí học kỳ này. Không cần thực hiện thêm giao dịch nào.
    </div>
    @endif

    @if($pending_payment)
    <div style="background:#fffbeb; border:1px solid #fcd34d; border-radius:10px; padding:0.75rem 1.25rem; margin-bottom:1.25rem; font-size:0.875rem; color:#b45309; font-weight:600; display:flex; align-items:center; gap:0.6rem;">
        <i class="fa-solid fa-clock-rotate-left"></i>
        Yêu cầu xác nhận thanh toán số tiền {{ number_format($pending_payment->amount, 0, ',', '.') }}đ đang chờ duyệt. Vui lòng không thực hiện chuyển khoản lại.
    </div>
    @endif

    <div class="pay-grid">
        <!-- LEFT: Thông tin thanh toán -->
        <div class="pay-card">
            <div class="pay-card-head">
                <h3><i class="fa-solid fa-file-invoice-dollar" style="color:#2563eb; margin-right:6px;"></i>Thông tin thanh toán</h3>
            </div>
            <div class="summary-item">
                <span class="label">Sinh viên</span>
                <span class="value">{{ $student->name }}</span>
            </div>
            <div class="summary-item">
                <span class="label">Mã sinh viên</span>
                <span class="value" style="font-family:monospace;">{{ $student_code }}</span>
            </div>
            <div class="summary-item">
                <span class="label">Học kỳ</span>
                <span class="value">{{ $semester_label }}</span>
            </div>
            <div class="summary-item">
                <span class="label">Tổng học phí</span>
                <span class="value">{{ number_format($total_fee, 0, ',', '.') }} VNĐ</span>
            </div>
            @if($paid > 0)
            <div class="summary-item">
                <span class="label">Đã thanh toán</span>
                <span class="value" style="color:#16a34a;">{{ number_format($paid, 0, ',', '.') }} VNĐ</span>
            </div>
            @endif
            <div class="summary-total">
                <div class="label"><i class="fa-solid fa-circle-exclamation" style="margin-right:8px;"></i>Số tiền cần nộp</div>
                <div class="amount">{{ number_format($remaining, 0, ',', '.') }} VNĐ</div>
            </div>

            @if($payments->count() > 0)
            <div style="padding:1rem 1.25rem; border-top:1px solid #e2e8f0;">
                <div style="font-size:0.8rem; font-weight:700; color:#475569; margin-bottom:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">Lịch sử giao dịch</div>
                @foreach($payments as $p)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.5rem 0; border-bottom:1px solid #f1f5f9; font-size:0.82rem;">
                    <div style="color:#64748b;">
                        {{ $p->created_at->format('d/m/Y H:i') }}
                        @if(($p->status ?? 'completed') === 'pending')
                            <span style="font-size:0.7rem; background:#fef3c7; color:#d97706; padding:2px 6px; border-radius:4px; margin-left:6px; font-weight:600;">Chờ duyệt</span>
                        @elseif(($p->status ?? 'completed') === 'failed')
                            <span style="font-size:0.7rem; background:#fee2e2; color:#dc2626; padding:2px 6px; border-radius:4px; margin-left:6px; font-weight:600;">Bị từ chối</span>
                        @else
                            <span style="font-size:0.7rem; background:#dcfce7; color:#16a34a; padding:2px 6px; border-radius:4px; margin-left:6px; font-weight:600;">Đã duyệt</span>
                        @endif

                        @if($p->proof_image)
                            <a href="{{ asset($p->proof_image) }}" target="_blank" style="margin-left:8px; font-size:0.72rem; color:#2563eb; text-decoration:underline;" title="Xem ảnh minh chứng"><i class="fa-regular fa-image"></i> Minh chứng</a>
                        @endif
                    </div>
                    <div style="font-weight:700; color: {{ ($p->status ?? 'completed') === 'pending' ? '#d97706' : (($p->status ?? 'completed') === 'failed' ? '#dc2626' : '#16a34a') }};">
                        +{{ number_format($p->amount, 0, ',', '.') }}đ
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- RIGHT: QR Code -->
        <div class="qr-card">
            <div class="qr-head">
                <h3><i class="fa-solid fa-qrcode" style="margin-right:6px;"></i>Quét QR Chuyển khoản</h3>
                <p>Tự động điền thông tin — Không cần nhập tay</p>
            </div>
            <div class="qr-body">
                <div class="qr-img-wrap" style="{{ $remaining <= 0 ? 'opacity:0.3; pointer-events:none;' : '' }}">
                    <img src="{{ $qr_url }}" alt="QR Code học phí"
                        onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=210x210&data={{ urlencode('MBBANK '.$bank_account.' '.$transfer_content.' '.$remaining) }}'">
                </div>

                <div>
                    <div class="bank-row">
                        <span class="bkey">Ngân hàng</span>
                        <span class="bval">🏦 {{ $bank_name }}</span>
                    </div>
                    <div class="bank-row">
                        <span class="bkey">Số tài khoản</span>
                        <span class="bval">
                            {{ $bank_account }}
                            <button class="copy-btn" onclick="copyText('{{ $bank_account }}', this)">Copy</button>
                        </span>
                    </div>
                    <div class="bank-row">
                        <span class="bkey">Chủ tài khoản</span>
                        <span class="bval" style="font-size:0.78rem;">{{ $bank_owner }}</span>
                    </div>
                    <div class="bank-row">
                        <span class="bkey">Số tiền</span>
                        <span class="bval" style="color:#ef4444; gap: 1rem;">
                            {{ number_format($remaining, 0, ',', '.') }} VNĐ
                            @if($remaining > 0)
                            <button class="copy-btn" onclick="copyText('{{ $remaining }}', this)">Copy Số Tiền</button>
                            @endif
                        </span>
                    </div>
                    <div class="bank-row">
                        <span class="bkey">Nội dung CK</span>
                        <span class="bval" style="flex-direction:column; align-items:flex-end; gap:6px;">
                            <span style="font-family:monospace; font-size:1rem; background:#f1f5f9; padding:6px 12px; border-radius:6px; color:#2563eb;">{{ $transfer_content }}</span>
                            <button class="copy-btn" onclick="copyText('{{ $transfer_content }}', this)" style="align-self: flex-end;">Copy Nội Dung</button>
                        </span>
                    </div>
                </div>

                <div class="notice-box">
                    <strong>⚠️ Lưu ý:</strong> Nhập <strong>ĐÚNG NỘI DUNG</strong> chuyển khoản:<br>
                    <code style="background:#fef3c7; padding:2px 6px; border-radius:3px; font-size:0.82rem;">{{ $transfer_content }}</code><br>
                    Sai nội dung → Không được xác nhận tự động.
                </div>

                @if($remaining > 0)
                    @if($pending_payment)
                    <button class="btn-check" disabled style="background:#fbbf24; color:#78350f; cursor:not-allowed;">
                        ⏳ Đang chờ hệ thống phê duyệt chuyển khoản...
                    </button>
                    @else
                    <button class="btn-check" onclick="togglePaymentForm()" style="background:#2563eb;">
                        <i class="fa-solid fa-circle-check"></i> Xác nhận tôi đã chuyển khoản
                    </button>
                    
                    <div id="confirmFormWrap" style="display:none; margin-top:1rem; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 1rem; background: #faf5ff; text-align: left;">
                        <h4 style="margin-top:0; font-size:0.875rem; color:#1e1b4b; border-bottom:1px solid #e2e8f0; padding-bottom:0.5rem; margin-bottom:0.75rem;"><i class="fa-solid fa-file-invoice-dollar"></i> Khai báo thông tin chuyển khoản</h4>
                        
                        <div style="margin-bottom:0.75rem;">
                            <label style="display:block; font-size:0.78rem; font-weight:600; color:#475569; margin-bottom:4px;">Số tiền đã chuyển (VNĐ):</label>
                            <input type="number" id="payAmountInput" value="{{ (int)$remaining }}" max="{{ (int)$remaining }}" min="1000" step="1000" style="width:100%; padding:0.5rem; border:1px solid #cbd5e1; border-radius:6px; font-weight:700; color:#1e293b; font-size:0.9rem;">
                        </div>
                        
                        <div style="margin-bottom:0.75rem;">
                            <label style="display:block; font-size:0.78rem; font-weight:600; color:#475569; margin-bottom:4px;">Ảnh minh chứng (Bill chuyển khoản):</label>
                            <input type="file" id="payProofInput" accept="image/*" style="width:100%; font-size:0.8rem;">
                            <small style="color:#64748b; font-size:0.7rem; display:block; margin-top:2px;">Hỗ trợ ảnh PNG, JPG, JPEG (Dưới 5MB)</small>
                        </div>
                        
                        <div style="display:flex; gap:0.5rem;">
                            <button onclick="submitPaymentConfirm(this)" style="flex:1; background:#16a34a; color:#fff; border:none; padding:0.5rem; border-radius:6px; font-weight:700; cursor:pointer; font-size:0.85rem;">Gửi yêu cầu</button>
                            <button onclick="togglePaymentForm()" style="background:#64748b; color:#fff; border:none; padding:0.5rem 0.75rem; border-radius:6px; cursor:pointer; font-size:0.85rem;">Hủy</button>
                        </div>
                    </div>
                    @endif
                @else
                <button class="btn-check" disabled style="background:#64748b; cursor:not-allowed;">
                    ✅ Đã hoàn thành đóng học phí
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.textContent;
        btn.textContent = '✓ Đã copy';
        btn.style.background = '#16a34a';
        btn.style.color = '#fff';
        setTimeout(() => {
            btn.textContent = orig;
            btn.style.background = '';
            btn.style.color = '';
        }, 2000);
    });
}

function togglePaymentForm() {
    const wrap = document.getElementById('confirmFormWrap');
    wrap.style.display = wrap.style.display === 'none' ? 'block' : 'none';
}

function submitPaymentConfirm(btn) {
    const amountInput = document.getElementById('payAmountInput');
    const fileInput = document.getElementById('payProofInput');
    const amount = parseFloat(amountInput.value);
    
    if (isNaN(amount) || amount <= 0) {
        alert('Vui lòng nhập số tiền thanh toán hợp lệ.');
        return;
    }
    
    if (amount > {{ $remaining }}) {
        alert('Số tiền nộp không được vượt quá số tiền còn nợ ({{ number_format($remaining, 0, ",", ".") }}đ).');
        return;
    }
    
    if (!fileInput.files || fileInput.files.length === 0) {
        alert('Vui lòng tải lên ảnh minh chứng chuyển khoản (Bill).');
        return;
    }
    
    const formData = new FormData();
    formData.append('student_code', '{{ $student_code }}');
    formData.append('amount', amount);
    formData.append('proof_image', fileInput.files[0]);
    
    const origText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang gửi...';
    btn.disabled = true;
    
    fetch('/api/payment/check', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
            btn.innerHTML = origText;
            btn.disabled = false;
        }
    })
    .catch(err => {
        alert('Có lỗi kết nối hệ thống.');
        btn.innerHTML = origText;
        btn.disabled = false;
    });
}
</script>
@endsection
