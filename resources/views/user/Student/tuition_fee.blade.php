@extends('layouts.user.student_sidebar')
@section('title', 'Học phí học kỳ')
@section('content')
    <style>
        .tf-wrap {
            width: 100%;
            box-sizing: border-box;
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
            margin-bottom: 1.5rem;
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

        .tf-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .tf-card-head {
            padding: 1rem 1.25rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tf-card-head h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .tf-table {
            width: 100%;
            border-collapse: collapse;
        }

        .tf-table th {
            padding: 0.65rem 1.25rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
            background: #f8fafc;
        }

        .tf-table th:last-child {
            text-align: right;
        }

        .tf-table td {
            padding: 0.8rem 1.25rem;
            font-size: 0.875rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            vertical-align: middle;
        }

        .tf-table td:last-child {
            text-align: right;
            font-weight: 700;
            color: #1e293b;
        }

        .tf-table tbody tr:last-child td {
            border-bottom: none;
        }

        .tf-table tbody tr:hover {
            background: #fafbfc;
        }

        .tf-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #eff6ff);
            border-top: 2px solid #e2e8f0;
            gap: 1rem;
        }

        .tf-total-label {
            font-size: 0.9rem;
            color: #475569;
        }

        .tf-total-amount {
            font-size: 2rem;
            font-weight: 800;
            color: #ef4444;
            line-height: 1;
        }

        .tf-total-sub {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 2px;
        }

        .btn-pay {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 2rem;
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }

        .btn-pay:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.4);
            color: #fff;
            text-decoration: none;
        }

        .btn-pay:disabled,
        .btn-pay.disabled {
            background: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .credit-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 0.78rem;
            font-weight: 700;
        }
    </style>

    @php
        $student_code = $student->student_code ?? 'SV001';
        $semester = $activeSemester->name ?? 'Học kỳ';
        if ($activeSemester->academic_year ?? null) {
            $semester .= ' – ' . $activeSemester->academic_year;
        }
        $price_per_credit = $student->classroom->faculty->facultyGeneral->tuition_fee_per_credit ?? 480000;
        $total_fee = $tuition ? $tuition->total_amount ?? 0 : 0;
        $paid = $tuition ? $tuition->paid_amount ?? 0 : 0;
        $remaining = $total_fee - $paid;
        $hasEnrollments = $enrollments->count() > 0;
    @endphp

    <div class="tf-wrap">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li><a href="{{ route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
                <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                <li><a href="{{ route('student.enrollment') }}">Đăng ký môn học</a></li>
                <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                <li class="active">Học phí</li>
            </ol>
        </nav>

        <section class="page-hero">
            <div class="hero-content">
                <div class="hero-eyebrow">Student Academic Portal</div>
                <h1 class="hero-title"><i class="fa-solid fa-receipt" style="margin-right:8px;"></i>HỌC PHÍ HỌC KỲ</h1>
                <p class="hero-desc">Chi tiết học phí theo từng môn học đã đăng ký</p>
            </div>
            <span class="sem-badge">{{ $semester }}</span>
        </section>

        @if (!$hasEnrollments)
            <div
                style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:0.75rem 1.25rem; margin-bottom:1.25rem; font-size:0.85rem; color:#1e40af; font-weight:600; display:flex; align-items:center; gap:0.6rem;">
                <i class="fa-solid fa-circle-info"></i>
                Bạn chưa đăng ký môn học nào trong học kỳ này. Học phí sẽ được tạo sau khi xác nhận đăng ký.
            </div>
        @elseif(!$tuition)
            <div
                style="background:#fffbeb; border:1px solid #fcd34d; border-radius:10px; padding:0.75rem 1.25rem; margin-bottom:1.25rem; font-size:0.85rem; color:#92400e; font-weight:600; display:flex; align-items:center; gap:0.6rem;">
                <i class="fa-solid fa-clock"></i>
                Phiếu học phí đang được xử lý. Vui lòng quay lại sau hoặc liên hệ phòng đào tạo.
            </div>
        @elseif($remaining > 0)
            <div
                style="background:#fffbeb; border:1px solid #fcd34d; border-radius:10px; padding:0.75rem 1.25rem; margin-bottom:1.25rem; font-size:0.85rem; color:#92400e; font-weight:600; display:flex; align-items:center; gap:0.6rem;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                Còn <b style="margin:0 4px;">{{ number_format($remaining, 0, ',', '.') }}đ</b> học phí chưa được thanh toán.
            </div>
        @else
            <div
                style="background:#f0fdf4; border:1px solid #86efac; border-radius:10px; padding:0.75rem 1.25rem; margin-bottom:1.25rem; font-size:0.85rem; color:#166534; font-weight:600; display:flex; align-items:center; gap:0.6rem;">
                <i class="fa-solid fa-circle-check"></i>
                Bạn đã hoàn thành nghĩa vụ đóng học phí cho học kỳ này.
            </div>
        @endif

        <div class="tf-card">
            <div class="tf-card-head">
                <h3><i class="fa-solid fa-list-check" style="color:#2563eb; margin-right:6px;"></i>Danh sách môn học đã đăng
                    ký</h3>
                <span style="font-size:0.78rem; color:#64748b;">{{ number_format($price_per_credit, 0, ',', '.') }}đ / tín
                    chỉ</span>
            </div>
            <table class="tf-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">STT</th>
                        <th>Mã môn học</th>
                        <th>Tên môn học</th>
                        <th style="text-align:center;">Số tín chỉ</th>
                        <th>Học phí</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $i => $e)
                        @php
                            $subject = $e->schedule->subject ?? null;
                            if (!$subject) {
                                continue;
                            }
                            $subjectFee = $subject->credits * $price_per_credit;
                        @endphp
                        <tr>
                            <td style="text-align:center; color:#94a3b8;">{{ $i + 1 }}</td>
                            <td><span class="credit-badge">{{ $subject->code ?? $subject->id }}</span></td>
                            <td style="font-weight:600; color:#1e293b;">{{ $subject->name }}</td>
                            <td style="text-align:center;">{{ $subject->credits }} TC</td>
                            <td>{{ number_format($subjectFee, 0, ',', '.') }}đ</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#94a3b8; padding:2.5rem;">
                                <i class="fa-regular fa-calendar-xmark"
                                    style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                                Chưa đăng ký môn học nào trong học kỳ này.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="tf-footer">
                <div>
                    <div class="tf-total-label">Tổng học phí phải nộp</div>
                    <div class="tf-total-amount">{{ number_format($total_fee, 0, ',', '.') }}đ</div>
                    @if ($paid > 0)
                        <div class="tf-total-sub">Đã thanh toán: {{ number_format($paid, 0, ',', '.') }}đ</div>
                    @endif
                </div>

                @if ($tuition && $remaining > 0)
                    <a href="{{ route('student.payment') }}" class="btn-pay">
                        <i class="fa-solid fa-credit-card"></i> Thanh toán ngay
                    </a>
                @elseif($tuition)
                    <span class="btn-pay disabled">
                        <i class="fa-solid fa-circle-check"></i> Đã thanh toán
                    </span>
                @else
                    <span class="btn-pay disabled" style="background:#94a3b8;">
                        <i class="fa-solid fa-hourglass-half"></i> Chưa có phiếu học phí
                    </span>
                @endif
            </div>
        </div>

        <div class="tf-card" style="margin-top:1.5rem;">
            <div class="tf-card-head">
                <h3><i class="fa-solid fa-clock-rotate-left" style="color:#2563eb; margin-right:6px;"></i>Danh sách khoản
                    học phí qua các học kỳ</h3>
            </div>
            <table class="tf-table">
                <thead>
                    <tr>
                        <th>Tên học kỳ</th>
                        <th style="text-align:right;">Tổng học phí</th>
                        <th style="text-align:right;">Học phí đã đóng</th>
                        <th style="text-align:right;">Còn nợ / Dư</th>
                        <th style="text-align:center;">Thời gian đóng gần nhất</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allTuitions as $t)
                        @php
                            $tRemaining = $t->total_amount - $t->paid_amount;
                            $lastPayment = $t->payments->max('created_at');
                            $lastPaymentStr = $lastPayment ? $lastPayment->format('d/m/Y H:i') : '—';
                        @endphp
                        <tr>
                            <td style="font-weight:600; color:#1e293b;">
                                {{ $t->semester->name ?? 'Học kỳ' }}
                                @if ($t->semester->academic_year ?? null)
                                    – {{ $t->semester->academic_year }}
                                @endif
                            </td>
                            <td style="text-align:right;">{{ number_format($t->total_amount, 0, ',', '.') }}đ</td>
                            <td style="text-align:right; color:#16a34a;">{{ number_format($t->paid_amount, 0, ',', '.') }}đ
                            </td>
                            <td style="text-align:right;">
                                @if ($tRemaining > 0)
                                    <span
                                        style="color:#ef4444; font-weight:700;">{{ number_format($tRemaining, 0, ',', '.') }}đ
                                        (Nợ)</span>
                                @elseif($tRemaining < 0)
                                    <span
                                        style="color:#16a34a; font-weight:700;">{{ number_format(abs($tRemaining), 0, ',', '.') }}đ
                                        (Trường nợ)</span>
                                @else
                                    <span style="color:#64748b;">0đ</span>
                                @endif
                            </td>
                            <td style="text-align:center; color:#64748b; font-size:0.82rem;">{{ $lastPaymentStr }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#94a3b8; padding:1.5rem;">
                                Chưa có thông tin học phí nào được lưu trữ.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
