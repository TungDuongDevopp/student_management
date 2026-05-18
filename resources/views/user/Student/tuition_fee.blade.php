@extends('layouts.user.student_sidebar')
@section('title', 'Đóng học phí')
@section('content')
<style>
.tf-wrap { width: 100%; max-width: 100%; padding: 0 1rem; box-sizing: border-box; }
.page-hero { background:linear-gradient(135deg,#1e40af 0%,#3b82f6 100%);color:#fff;border-radius:10px;padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;position:relative;overflow:hidden;margin-bottom:1.5rem;border:1px solid #1d4ed8; }
.page-hero::after { content:"";position:absolute;top:-80px;right:-60px;width:260px;height:260px;background:rgba(255,255,255,.08);transform:rotate(45deg); }
.hero-content { position:relative;z-index:1; }
.hero-eyebrow { font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;opacity:.85;font-weight:700;margin-bottom:.3rem; }
.hero-title { margin:0;font-size:1.35rem;font-weight:800; }
.hero-desc { margin:.3rem 0 0;font-size:.85rem;opacity:.9; }
.sem-badge { background:#fff;color:#1d4ed8;border-radius:6px;padding:.35rem .75rem;font-size:.75rem;font-weight:700;position:relative;z-index:1; }
.tf-grid { display:grid; grid-template-columns:1fr 480px; gap:1.5rem; align-items:start; }
.tf-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
.tf-card-head { padding:1rem 1.25rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
.tf-card-head h3 { font-size:0.95rem; font-weight:700; color:#1e293b; margin:0; }
.tf-table { width:100%; border-collapse:collapse; }
.tf-table th { padding:0.65rem 1rem; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; border-bottom:2px solid #e2e8f0; background:#f8fafc; }
.tf-table td { padding:0.7rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9; color:#334155; }
.tf-table td:last-child { text-align:right; font-weight:700; }
.status-pill { display:inline-block; font-size:0.72rem; padding:0.2rem 0.6rem; border-radius:20px; font-weight:700; }
.s-paid { background:#dcfce7; color:#166534; }
.s-unpaid { background:#fee2e2; color:#991b1b; }
.s-partial { background:#fef3c7; color:#92400e; }
.tf-total { display:flex; justify-content:space-between; align-items:center; padding:1rem 1.25rem; background:#f8fafc; border-top:2px solid #e2e8f0; }
.tf-total .amount { font-size:1.5rem; font-weight:800; color:#ef4444; }

/* QR Card */
.qr-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; position:sticky; top:1rem; }
.qr-head { padding:1rem 1.25rem; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; }
.qr-head h3 { margin:0; font-size:0.95rem; font-weight:700; }
.qr-head p { margin:0.25rem 0 0; font-size:0.78rem; opacity:0.85; }
.qr-img-wrap { padding:1.5rem; display:flex; justify-content:center; }
.qr-img-wrap img { width:200px; height:200px; border-radius:8px; border:2px solid #e2e8f0; }
.bank-info { padding:0 1.25rem 1rem; }
.bank-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:0.6rem; }
.bank-row .bkey { font-size:0.78rem; color:#64748b; }
.bank-row .bval { font-size:0.85rem; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:0.5rem; }
.copy-btn { background:#eff6ff; border:none; color:#2563eb; font-size:0.7rem; padding:0.15rem 0.4rem; border-radius:4px; cursor:pointer; font-weight:700; }
.copy-btn:hover { background:#2563eb; color:#fff; }
.notice-box { margin:0 1.25rem 1rem; background:#fef3c7; border:1px solid #fcd34d; border-radius:8px; padding:0.75rem; }
.notice-box p { margin:0; font-size:0.78rem; color:#92400e; line-height:1.5; }
.notice-box strong { color:#78350f; }
.pay-btn { display:block; margin:0 1.25rem 1.25rem; padding:0.75rem; background:#16a34a; color:#fff; border:none; border-radius:8px; font-weight:700; font-size:0.88rem; cursor:pointer; text-align:center; width:calc(100% - 2.5rem); }
.pay-btn:hover { background:#15803d; }
.history-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-top:1.5rem; }
</style>

@php
$student_code = $student->student_code ?? 'SV001';
$student_name = $student->name ?? 'Sinh viên';
$semester = $activeSemester->name ?? 'Học kỳ';

$price_per_credit = $student->classroom->faculty->facultyGeneral->tuition_fee_per_credit ?? 480000;
$total_fee = $tuition->total_amount ?? 0;
$paid = $tuition->paid_amount ?? 0;
$remaining = $total_fee - $paid;

$bank_name = 'MB Bank';
$bank_account = '0388123456';
$bank_owner = 'TRUONG DAI HOC MO DIA CHAT';
$transfer_content = 'HOCPHI '.$student_code.' '.$activeSemester->id;

// VietQR URL
$amount_encoded = $remaining;
$qr_url = "https://img.vietqr.io/image/MB-{$bank_account}-compact2.jpg?amount={$amount_encoded}&addInfo=".urlencode($transfer_content)."&accountName=".urlencode($bank_owner);
@endphp

<div class="tf-wrap">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Đóng học phí</li>
        </ol>
    </nav>
    <section class="page-hero">
        <div class="hero-content">
            <div class="hero-eyebrow">Student Academic Portal</div>
            <h1 class="hero-title"><i class="fa-solid fa-credit-card" style="margin-right:8px;"></i>ĐÓNG HỌC PHÍ</h1>
            <p class="hero-desc">Xem chi tiết học phí, lịch sử giao dịch và thực hiện quét mã thanh toán trực tuyến</p>
        </div>
        <span class="sem-badge">{{ $semester }} – Đang mở</span>
    </section>

    @if($remaining > 0)
    <div style="background:#fee2e2; border:1px solid #fca5a5; border-radius:10px; padding:0.75rem 1rem; margin-bottom:1.25rem; font-size:0.85rem; color:#991b1b; font-weight:600;">
        <i class="fa-solid fa-clock"></i> Còn <b>{{ number_format($remaining,0,',','.') }}đ</b> chưa thanh toán. Quá hạn sẽ bị khóa tài khoản học tập.
    </div>
    @else
    <div style="background:#dcfce7; border:1px solid #86efac; border-radius:10px; padding:0.75rem 1rem; margin-bottom:1.25rem; font-size:0.85rem; color:#166534; font-weight:600;">
        <i class="fa-solid fa-circle-check"></i> Chúc mừng! Bạn đã hoàn thành nghĩa vụ đóng học phí cho học kỳ này.
    </div>
    @endif

    <div class="tf-grid">
        <!-- LEFT: Chi tiết học phí -->
        <div>
            <div class="tf-card" style="margin-bottom:1.25rem;">
                <div class="tf-card-head">
                    <h3>Chi tiết học phí theo môn</h3>
                    <span style="font-size:0.78rem; color:#64748b;">{{ number_format($price_per_credit,0,',','.') }}đ/tín chỉ</span>
                </div>
                <table class="tf-table">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Môn học</th>
                            <th style="text-align:center;">Tín chỉ</th>
                            <th style="text-align:right;">Học phí</th>
                            <th style="text-align:center;">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $e)
                        @php
                            $subject = $e->schedule->subject ?? null;
                            if (!$subject) continue;
                            $subjectFee = $subject->credits * $price_per_credit;
                            
                            // Trạng thái thanh toán của môn học
                            // Nhập 1 phát hết luôn toàn bộ môn:
                            if ($paid >= $total_fee) {
                                $status = 'paid';
                            } elseif ($paid == 0) {
                                $status = 'unpaid';
                            } else {
                                $status = 'partial';
                            }
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight:600; color:#1e293b;">{{ $subject->name }}</div>
                                <div style="font-size:0.72rem; color:#94a3b8;">{{ $subject->id }}</div>
                            </td>
                            <td style="text-align:center;">{{ $subject->credits }}</td>
                            <td style="text-align:right;">{{ number_format($subjectFee,0,',','.') }}đ</td>
                            <td style="text-align:center;">
                                @if($status=='paid') <span class="status-pill s-paid">✓ Đã đóng</span>
                                @elseif($status=='partial') <span class="status-pill s-partial">⚡ Đóng 1 phần</span>
                                @else <span class="status-pill s-unpaid">✗ Chưa đóng</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#94a3b8; padding: 2rem;">Chưa đăng ký môn học nào trong học kỳ này.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="tf-total">
                    <div>
                        <div style="font-size:0.82rem; color:#64748b;">Tổng học phí: <b>{{ number_format($total_fee,0,',','.') }}đ</b></div>
                        <div style="font-size:0.82rem; color:#16a34a;">Đã thanh toán: <b>{{ number_format($paid,0,',','.') }}đ</b></div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:0.78rem; color:#64748b;">Còn lại phải nộp</div>
                        <div class="amount">{{ number_format($remaining,0,',','.') }}đ</div>
                    </div>
                </div>
            </div>

            <!-- Lịch sử giao dịch -->
            <div class="history-card">
                <div class="tf-card-head">
                    <h3><i class="fa-solid fa-clock-rotate-left" style="color:#2563eb; margin-right:6px;"></i>Lịch sử giao dịch</h3>
                </div>
                <table class="tf-table">
                    <thead><tr><th style="text-align:left;">Ngày</th><th style="text-align:left;">Hình thức</th><th>Số tiền</th><th style="text-align:left;">Nội dung</th><th style="text-align:center;">Trạng thái</th></tr></thead>
                    <tbody>
                        @forelse($payments as $p)
                        <tr>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>Chuyển khoản Ngân hàng</td>
                            <td style="color:#16a34a; font-weight:700;">{{ number_format($p->amount,0,',','.') }}đ</td>
                            <td style="font-size:0.78rem; color:#64748b;">HOCPHI {{ $student_code }} {{ $activeSemester->id }}</td>
                            <td style="text-align:center;"><span class="status-pill s-paid">✓ Thành công</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#94a3b8; padding:1.5rem;">Chưa có giao dịch thanh toán nào được thực hiện.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIGHT: QR Code -->
        <div class="qr-card">
            <div class="qr-head">
                <h3><i class="fa-solid fa-qrcode"></i> Quét QR Chuyển khoản</h3>
                <p>Tự động điền thông tin — Không cần nhập tay</p>
            </div>

            <div class="qr-img-wrap" style="background:#fff; @if($remaining <= 0) opacity: 0.3; pointer-events: none; @endif">
                <img src="{{ $qr_url }}" alt="QR Code học phí" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode('MBBANK '.$bank_account.' '.$transfer_content.' '.$remaining) }}'">
            </div>

            <div class="bank-info">
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
                    <span class="bkey">Số tiền cần đóng</span>
                    <span class="bval" style="color:#ef4444; font-size:1rem;">
                        {{ number_format($remaining,0,',','.') }}đ
                        @if($remaining > 0)
                        <button class="copy-btn" onclick="copyText('{{ $remaining }}', this)">Copy</button>
                        @endif
                    </span>
                </div>
                <div class="bank-row" style="align-items:flex-start;">
                    <span class="bkey">Nội dung CK</span>
                    <span class="bval" style="flex-direction:column; align-items:flex-end; gap:4px;">
                        <span style="font-family:monospace; font-size:0.82rem; background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $transfer_content }}</span>
                        <button class="copy-btn" onclick="copyText('{{ $transfer_content }}', this)">Copy</button>
                    </span>
                </div>
            </div>

            <div class="notice-box">
                <p><strong>⚠️ LƯU Ý QUAN TRỌNG:</strong><br>
                Nhập <strong>ĐÚNG NỘI DUNG</strong> chuyển khoản: <strong>{{ $transfer_content }}</strong><br>
                Hệ thống tự động xác nhận trong <strong>5-10 phút</strong> sau khi nhận tiền.<br>
                Sai nội dung → Không được xác nhận tự động.</p>
            </div>

            <div style="margin:0 1.25rem 0.5rem; padding:0.75rem; background:#f0fdf4; border:1px solid #86efac; border-radius:8px; display:flex; align-items:center; gap:0.6rem;">
                <i class="fa-solid fa-circle" style="color:#16a34a; font-size:0.5rem;"></i>
                <span style="font-size:0.78rem; color:#166534; font-weight:600;">Hệ thống đang theo dõi thanh toán tự động (SePay)</span>
            </div>

            <button class="pay-btn" onclick="checkPaymentStatus()" @if($remaining <= 0) disabled style="background:#64748b; cursor:not-allowed;" @endif>
                @if($remaining <= 0)
                ✅ Đã hoàn thành đóng học phí
                @else
                <i class="fa-solid fa-rotate"></i> Kiểm tra trạng thái thanh toán
                @endif
            </button>
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

function checkPaymentStatus() {
    const btn = event.target;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang kiểm tra...';
    btn.disabled = true;
    
    // Gọi API kiểm tra
    fetch('/api/payment/check', {
        method: 'POST',
        headers: {
            'Content-Type':'application/json', 
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
        },
        body: JSON.stringify({student_code: '{{ $student_code }}'})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = '✅ Đã thanh toán thành công!';
            btn.style.background = '#16a34a';
            setTimeout(() => location.reload(), 1500);
        } else {
            btn.innerHTML = '⏳ Chưa nhận được thanh toán';
            btn.style.background = '#64748b';
            setTimeout(() => {
                btn.innerHTML = '<i class="fa-solid fa-rotate"></i> Kiểm tra lại';
                btn.style.background = '';
                btn.disabled = false;
            }, 3000);
        }
    })
    .catch(() => {
        btn.innerHTML = '<i class="fa-solid fa-rotate"></i> Kiểm tra trạng thái thanh toán';
        btn.disabled = false;
    });
}
</script>
@endsection
