@extends('layouts.user.teacher_sidebar')
@section('title', 'Danh sách Sinh viên')
@section('content')
    <style>
        .sl-wrapper {
            max-width: 1200px;
        }

        .page-hero {
            background: linear-gradient(135deg, #7f1d1d 0%, #dc2626 100%);
            color: #fff;
            border-radius: 10px;
            padding: 1.5rem 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
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
            margin-bottom: .4rem;
        }

        .hero-title {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .sl-filters {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .sl-filters select,
        .sl-filters input {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.85rem;
            background: #fff;
            color: #1e293b;
        }

        .sl-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .sl-card-header {
            padding: 1rem 1.25rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sl-card-header h2 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .sl-stats {
            display: flex;
            gap: 0.75rem;
        }

        .sl-stat {
            font-size: 0.78rem;
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            font-weight: 600;
            background: #eff6ff;
            color: #2563eb;
        }

        .sl-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sl-table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.78rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            background: #f8fafc;
        }

        .sl-table td {
            padding: 0.65rem 1rem;
            font-size: 0.88rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .sl-table tbody tr:hover {
            background: #f8fafc;
        }

        .sl-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .sl-name-cell {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .sl-name {
            font-weight: 600;
            color: #1e293b;
        }

        .sl-email {
            font-size: 0.8rem;
            color: #64748b;
        }

        .sl-badge-active {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            background: #dcfce7;
            color: #166534;
            display: inline-block;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: #fff;
            color: #64748b;
        }

        .empty-state i {
            font-size: 2.5rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
            display: block;
        }
    </style>

    <div class="sl-wrapper">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li><a href="{{ route('teacher.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
                <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                <li class="active">Danh sách Sinh viên</li>
            </ol>
        </nav>
        <div class="page-hero">
            <div class="hero-content">
                <div class="hero-eyebrow">Teacher Academic Portal</div>
                <h1 class="hero-title"><i class="fa-solid fa-users-viewfinder" style="margin-right:0.5rem;"></i>Danh sách
                    Sinh viên</h1>
            </div>
            <div class="sl-filters">
                <select id="classSelector" onchange="window.location.href=this.value">
                    <option value="">-- Chọn danh sách lớp --</option>
                    @if ($classRooms->count() > 0)
                        <optgroup label="Lớp hành chính">
                            @foreach ($classRooms as $c)
                                <option value="{{ route('teacher.students', ['class_id' => $c->id]) }}"
                                    {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endif

                    @if ($schedules->count() > 0)
                        <optgroup label="Lớp học phần">
                            @foreach ($schedules as $s)
                                <option value="{{ route('teacher.students', ['schedule_id' => $s->id]) }}"
                                    {{ request('schedule_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->subject->name ?? 'Môn học' }} (Nhóm {{ $s->group_code ?? $s->id }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                <input type="text" id="searchInput" placeholder="Tìm sinh viên..." style="width:200px;"
                    onkeyup="filterTable()">
            </div>
        </div>

        @if ($currentClass || $currentSchedule)
            <div class="sl-card">
                <div class="sl-card-header">
                    <h2>{{ $title }}</h2>
                    <div class="sl-stats">
                        <span class="sl-stat"><i class="fa-solid fa-users"></i> Tổng số: {{ $students->count() }} SV</span>
                        <span class="sl-stat" style="background:#dcfce7; color:#166534;"><i
                                class="fa-solid fa-check-circle"></i> Đang học: {{ $students->count() }}</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="sl-table" id="studentsTable">
                        <thead>
                            <tr>
                                <th style="width:60px">STT</th>
                                <th>Họ và tên</th>
                                <th>Mã SV</th>
                                <th>Email</th>
                                <th>Lớp hành chính</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $idx => $sv)
                                @php
                                    // Get initials from name (e.g. "Nguyễn Văn An" -> "NA")
                                    $words = explode(' ', trim($sv->name));
                                    $initials = '';
                                    if (count($words) > 0) {
                                        $firstWord = $words[0];
                                        $lastWord = end($words);
                                        $initials =
                                            mb_substr($firstWord, 0, 1) .
                                            (count($words) > 1 ? mb_substr($lastWord, 0, 1) : '');
                                    }
                                @endphp
                                <tr>
                                    <td style="font-weight:600; color:#94a3b8; text-align: center;">{{ $idx + 1 }}
                                    </td>
                                    <td>
                                        <div class="sl-name-cell">
                                            <div class="sl-avatar">{{ $initials ?: 'SV' }}</div>
                                            <span class="sl-name">{{ $sv->name }}</span>
                                        </div>
                                    </td>
                                    <td style="font-weight:600;">{{ $sv->code }}</td>
                                    <td><span class="sl-email">{{ $sv->email }}</span></td>
                                    <td>{{ $sv->class_name }}</td>
                                    <td><span class="sl-badge-active">{{ $sv->status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="fa-solid fa-user-xmark"></i>
                                            <p>Không có sinh viên nào trong danh sách này.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="sl-card">
                <div class="empty-state">
                    <i class="fa-solid fa-layer-group"></i>
                    <p>Vui lòng chọn một lớp hành chính hoặc lớp học phần từ danh sách phía trên.</p>
                </div>
            </div>
        @endif
    </div>

    <script>
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toUpperCase();
            const table = document.getElementById("studentsTable");
            if (!table) return;

            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) { // Skip header row
                let tdName = tr[i].getElementsByTagName("td")[1];
                let tdCode = tr[i].getElementsByTagName("td")[2];

                if (tdName || tdCode) {
                    let nameValue = tdName.textContent || tdName.innerText;
                    let codeValue = tdCode.textContent || tdCode.innerText;

                    if (nameValue.toUpperCase().indexOf(filter) > -1 || codeValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection
