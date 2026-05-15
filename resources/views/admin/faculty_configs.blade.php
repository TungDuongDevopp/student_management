@extends('layouts.admin.sidebar')
@section('title', 'Cấu hình Đào tạo & Tài chính')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">
        <div class="page-header">
            <h1>Cấu hình Đào tạo & Tài chính</h1>
            <button class="btn btn-primary" onclick="saveConfigs()">💾 Lưu tất cả</button>
        </div>

        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px">ID Khoa</th>
                            <th>Tên Khoa/Ngành</th>
                            <th style="width: 250px">Học phí 1 tín chỉ (VNĐ)</th>
                            <th style="width: 250px">Tín chỉ tích lũy tối đa</th>
                        </tr>
                    </thead>
                    <tbody id="configTableBody">
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">Đang tải dữ liệu...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="toast" id="toast"></div>
    </div>

    <script>
        const API = '/api/faculty-configs';
        let facultiesData = [];

        async function fetchData() {
            try {
                const res = await fetch(API);
                facultiesData = await res.json();
                renderTable();
            } catch (e) {
                showToast('Lỗi tải cấu hình', 'error');
            }
        }

        function renderTable() {
            const tb = document.getElementById('configTableBody');
            if (!facultiesData.length) {
                tb.innerHTML = '<tr><td colspan="4"><div class="empty-state">Chưa có khoa nào trong hệ thống</div></td></tr>';
                return;
            }

            tb.innerHTML = facultiesData.map(f => {
                const conf = f.config || {};
                const tuition = conf.tuition_fee_per_credit || 500000;
                const maxCredits = conf.max_credits || 150;

                return `
                    <tr data-faculty-id="${f.id}">
                        <td>${f.id}</td>
                        <td><strong>${f.name}</strong></td>
                        <td>
                            <input type="number" class="config-tuition" value="${tuition}" min="0" step="1000"
                                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; background: var(--bg-body); color: var(--text-main);">
                        </td>
                        <td>
                            <input type="number" class="config-max-credits" value="${maxCredits}" min="1"
                                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; background: var(--bg-body); color: var(--text-main);">
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function saveConfigs() {
            const rows = document.querySelectorAll('#configTableBody tr[data-faculty-id]');
            const configsToSave = [];

            rows.forEach(row => {
                const fid = row.getAttribute('data-faculty-id');
                const tuition = row.querySelector('.config-tuition').value;
                const maxCredits = row.querySelector('.config-max-credits').value;

                configsToSave.push({
                    faculty_id: parseInt(fid),
                    tuition_fee_per_credit: parseInt(tuition),
                    max_credits: parseInt(maxCredits)
                });
            });

            try {
                const res = await fetch(API, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ configs: configsToSave })
                });
                
                if (!res.ok) throw new Error('Lỗi lưu cấu hình');
                showToast('Đã lưu cấu hình các khoa thành công!', 'success');
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            }
        }

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = `toast toast-${type} show`;
            setTimeout(() => t.classList.remove('show'), 3000);
        }

        fetchData();
    </script>
@endsection
