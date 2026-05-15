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
                            Trạng thái đăng ký môn học (Toàn trường)
                        </label>
                        <select id="is_registration_open" class="form-input" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-body); color: var(--text-main);">
                            <option value="0">Đang đóng (Sinh viên không thể đăng ký)</option>
                            <option value="1">Đang mở (Sinh viên được phép đăng ký)</option>
                        </select>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">
                            Bật công tắc này khi tới đợt đăng ký môn học của sinh viên.
                        </p>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">
                            Năm học hiện tại
                        </label>
                        <input type="text" id="current_academic_year" class="form-input" 
                               style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-body); color: var(--text-main);"
                               placeholder="VD: 2026-2027">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">
                            Học kỳ hiện tại
                        </label>
                        <select id="current_semester" class="form-input" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-body); color: var(--text-main);">
                            <option value="1">Học kỳ 1</option>
                            <option value="2">Học kỳ 2</option>
                            <option value="3">Học kỳ Hè (Kỳ 3)</option>
                        </select>
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
                if (configs.current_academic_year !== undefined) {
                    document.getElementById('current_academic_year').value = configs.current_academic_year;
                }
                if (configs.current_semester !== undefined) {
                    document.getElementById('current_semester').value = configs.current_semester;
                }
            } catch (e) {
                showToast('Lỗi tải cấu hình hệ thống', 'error');
            }
        }

        async function saveConfigs() {
            const body = {
                configs: {
                    is_registration_open: document.getElementById('is_registration_open').value,
                    current_academic_year: document.getElementById('current_academic_year').value,
                    current_semester: document.getElementById('current_semester').value,
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
