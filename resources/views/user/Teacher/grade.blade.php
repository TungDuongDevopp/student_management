@extends('layouts.user.teacher_sidebar')
@section('title', 'Cập nhật Điểm Sinh viên')
@section('content')
    <style>
        .sl-wrapper {
            max-width: 1200px;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        .sl-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .sl-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .sl-filters {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .sl-filters select,
        .sl-filters input {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.85rem;
            background: #fff;
            color: #1e293b;
            outline: none;
            transition: border-color 0.2s;
        }

        .sl-filters select:focus,
        .sl-filters input:focus {
            border-color: #3b82f6;
        }

        .sl-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .sl-card-header {
            padding: 1.25rem 1.5rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .sl-card-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            line-height: 1.4;
        }

        .sl-stats {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .sl-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .sl-table th {
            padding: 0.85rem 1.25rem;
            text-align: left;
            font-size: 0.78rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            background: #f1f5f9;
            white-space: nowrap;
        }

        .sl-table td {
            padding: 0.75rem 1.25rem;
            font-size: 0.88rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
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

        .btn-save {
            background: #16a34a;
            color: #fff;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-save:hover {
            background: #15803d;
        }

        /* Input grade styling */
        .grade-input {
            width: 80px;
            padding: 0.4rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            text-align: center;
            font-weight: 600;
            outline: none;
        }

        .grade-input:focus {
            border-color: #16a34a;
        }
    </style>

    <div class="sl-wrapper">
        <div class="sl-header">
            <h1><i class="fa-solid fa-pen-to-square" style="color:#16a34a; margin-right:0.5rem;"></i>Cập nhật Điểm Sinh viên
            </h1>
            <div class="sl-filters">
                <select id="scheduleSelector" onchange="window.location.href=this.value">
                    <option value="">-- Chọn lớp học phần --</option>
                    @if ($schedules->count() > 0)
                        @foreach ($schedules as $s)
                            <option value="{{ route('teacher.grades', ['schedule_id' => $s->id]) }}"
                                {{ request('schedule_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->subject->name ?? 'Môn học' }} (Nhóm {{ $s->group_code ?? $s->id }})
                            </option>
                        @endforeach
                    @endif
                </select>
                <input type="text" id="searchInput" placeholder="Tìm sinh viên..." style="width:200px;"
                    onkeyup="filterTable()">
            </div>
        </div>

        @if ($currentSchedule)
            <div class="sl-card">
                <div class="sl-card-header">
                    <h2>Lớp học phần: {{ $currentSchedule->subject->name ?? '' }} (Nhóm {{ $currentSchedule->group_code }})
                    </h2>
                    <div class="sl-stats">
                        <span style="font-size:0.85rem; font-weight: 600; color:#475569;"><i class="fa-solid fa-users"></i>
                            Tổng số: {{ $students->count() }} SV</span>
                        <button class="btn-save" onclick="saveGrades()"><i class="fa-solid fa-save"></i> Chốt
                            & Lưu điểm</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="sl-table" id="studentsTable">
                        <thead>
                            <tr>
                                <th style="width:60px">STT</th>
                                <th>Họ và tên</th>
                                <th>Mã SV</th>
                                <th>Lớp hành chính</th>
                                <th style="text-align:center; width:120px;">Điểm chuyên cần</th>
                                <th style="text-align:center; width:120px;">Điểm giữa kỳ</th>
                                <th style="text-align:center; width:120px;">Điểm cuối kỳ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $idx => $sv)
                                @php
                                    $words = explode(' ', trim($sv->name));
                                    $initials =
                                        count($words) > 0
                                            ? mb_substr($words[0], 0, 1) .
                                                (count($words) > 1 ? mb_substr(end($words), 0, 1) : '')
                                            : '';
                                @endphp
                                <tr>
                                    <td style="font-weight:600; color:#94a3b8; text-align: center;">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="sl-name-cell">
                                            <div class="sl-avatar" style="background:#f0fdf4; color:#16a34a;">
                                                {{ $initials ?: 'SV' }}</div>
                                            <span class="sl-name">{{ $sv->name }}</span>
                                        </div>
                                    </td>
                                    <td style="font-weight:600;">{{ $sv->code }}</td>
                                    <td>{{ $sv->class_name }}</td>
                                    <td style="text-align:center;">
                                        <input type="number" class="grade-input" name="score_c[{{ $sv->enrollment_id }}]"
                                            min="0" max="10" step="0.1" value="{{ $sv->score_c }}"
                                            placeholder="--">
                                    </td>
                                    <td style="text-align:center;">
                                        <input type="number" class="grade-input" name="score_b[{{ $sv->enrollment_id }}]"
                                            min="0" max="10" step="0.1" value="{{ $sv->score_b }}"
                                            placeholder="--">
                                    </td>
                                    <td style="text-align:center;">
                                        <input type="number" class="grade-input" name="score_a[{{ $sv->enrollment_id }}]"
                                            min="0" max="10" step="0.1" value="{{ $sv->score_a }}"
                                            placeholder="--">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fa-solid fa-user-xmark"></i>
                                            <p>Không có sinh viên nào trong danh sách lớp học phần này.</p>
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
                    <i class="fa-solid fa-book-open"></i>
                    <p>Vui lòng chọn một lớp học phần từ danh sách phía trên.</p>
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

            for (let i = 1; i < tr.length; i++) {
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

        async function saveGrades() {
            const inputs = document.querySelectorAll('.grade-input');
            const grades = {};
            inputs.forEach(input => {
                const nameAttr = input.getAttribute('name');
                const match = nameAttr.match(/(score_[abc])\[(\d+)\]/);
                if (match) {
                    const type = match[1];
                    const enrollmentId = match[2];
                    if (!grades[enrollmentId]) {
                        grades[enrollmentId] = {};
                    }
                    grades[enrollmentId][type] = input.value === '' ? null : parseFloat(input.value);
                }
            });

            const btn = document.querySelector('.btn-save');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...';
            btn.disabled = true;

            try {
                const response = await fetch('{{ route("teacher.grades.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ grades })
                });
                const resData = await response.json();
                if (response.ok && resData.success) {
                    alert('Lưu điểm thành công!');
                    location.reload();
                } else {
                    alert('Lỗi: ' + (resData.message || 'Không thể lưu điểm.'));
                }
            } catch (e) {
                alert('Lỗi kết nối hoặc hệ thống.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    </script>
@endsection
