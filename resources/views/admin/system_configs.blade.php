@extends('layouts.admin.sidebar')
@section('title', 'Cấu hình chung toàn Hệ thống')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">
        <div class="page-header">
            <h1>Cấu hình Hệ thống (Global)</h1>
            <button class="btn btn-primary" onclick="saveConfigs()">💾 Lưu cấu hình</button>
        </div>

        <div class="card" style="max-width: 800px;">
            <form id="configForm" onsubmit="event.preventDefault(); saveConfigs();">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">
                            🗓️ Trạng thái đăng ký môn học (Đầu kỳ)
                        </label>
                        <select id="is_registration_open" class="form-input"
                            style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-body); color: var(--text-main);">
                            <option value="0">Đang đóng (Sinh viên không thể đăng ký)</option>
                            <option value="1">Đang mở (Sinh viên được phép đăng ký)</option>
                        </select>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">
                            Mở vào đầu học kỳ để sinh viên đăng ký môn học.
                        </p>
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">
                            📝 Trạng thái chấm điểm (Cuối kỳ)
                        </label>
                        <select id="is_grading_open" class="form-input"
                            style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-body); color: var(--text-main);">
                            <option value="0">Đang đóng (Giảng viên không thể nhập điểm)</option>
                            <option value="1">Đang mở (Giảng viên được phép nhập điểm)</option>
                        </select>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">
                            Mở vào cuối học kỳ để giảng viên nhập điểm thi.
                        </p>
                    </div>



                </div>
            </form>
        </div>

        <div class="toast" id="toast"></div>
    </div>

    <script>
        const API = '/api/system-configs';

        async function fetchData() {
            try {
                const res = await fetch(API);
                const configs = await res.json();

                // configs là 1 object dạng { key: "value", key2: "value2" }
                if (configs.is_registration_open !== undefined) {
                    document.getElementById('is_registration_open').value = configs.is_registration_open;
                }
                if (configs.is_grading_open !== undefined) {
                    document.getElementById('is_grading_open').value = configs.is_grading_open;
                }

            } catch (e) {
                showToast('Lỗi tải cấu hình hệ thống', 'error');
            }
        }

        async function saveConfigs() {
            const body = {
                configs: {
                    is_registration_open: document.getElementById('is_registration_open').value,
                    is_grading_open: document.getElementById('is_grading_open').value,
                }
            };

            try {
                const res = await fetch(API, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });

                if (!res.ok) throw new Error('Lỗi lưu cấu hình');
                showToast('Đã lưu cấu hình hệ thống thành công!', 'success');
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
