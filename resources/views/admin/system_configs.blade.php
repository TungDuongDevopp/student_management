@extends('layouts.admin.sidebar')
@section('title', 'Cấu hình chung toàn Hệ thống')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">
        <div class="admin-banner">
            <div class="ab-content">
                <div class="ab-subtitle">ADMINISTRATION PORTAL</div>
                <div class="ab-title">Cấu hình Hệ thống </div>
            </div>
            <div class="ab-action">
                <button class="btn btn-primary" onclick="saveConfigs()"><i class="fa-solid fa-floppy-disk"></i> Lưu cấu hình</button>
            </div>
            <div class="ab-decor"></div>
        </div>

        <div class="card" style="margin-bottom: 2rem;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border);">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Thông tin chung</h2>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.2rem;">Cấu hình tên trường, mô tả và các thẻ/p>
            </div>
            <div style="padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Tên trường </label>
                    <input type="text" id="site_name" class="form-input" placeholder="VD: Trường Đại học ABC" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Hotline hỗ trợ</label>
                    <input type="text" id="site_hotline" class="form-input" placeholder="VD: 1900 1234" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Fanpage Facebook</label>
                    <input type="text" id="site_fanpage" class="form-input" placeholder="VD: https://facebook.com/..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Meta Author </label>
                    <input type="text" id="meta_author" class="form-input" placeholder="Tên tác giả hoặc tổ chức" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="grid-column: span 2; margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);"> Keywords </label>
                    <input type="text" id="meta_keywords" class="form-input" placeholder="Từ khóa cách nhau bằng dấu phẩy. VD:HUMG, TUYENSINH, SV..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="grid-column: span 2; margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Mô tả Website </label>
                    <textarea id="site_description" class="form-input" placeholder="Mô tả ngắn gọn về trường..." style="width: 100%; height: 80px; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);"></textarea>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 2rem;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border);">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Hình ảnh & Thương hiệu</h2>
            </div>
            <div style="padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Logo Website</label>
                    <input type="file" accept="image/*" onchange="previewBase64(event, 'preview_logo', 'site_logo_base64')" style="width: 100%; padding: 0.5rem; border: 1px dashed var(--border); border-radius: 6px; color: var(--text-main);">
                    <input type="hidden" id="site_logo_base64">
                    <div style="margin-top: 1rem; padding: 1rem; background: var(--bg-primary); border-radius: 8px; text-align: center; border: 1px solid var(--border);">
                        <img id="preview_logo" src="" style="max-height: 60px; max-width: 100%; display: none;" alt="Logo">
                        <span id="preview_logo_placeholder" style="color: var(--text-muted); font-size: 0.85rem;">Chưa có Logo</span>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Favicon</label>
                    <input type="file" accept="image/*" onchange="previewBase64(event, 'preview_favicon', 'site_favicon_base64')" style="width: 100%; padding: 0.5rem; border: 1px dashed var(--border); border-radius: 6px; color: var(--text-main);">
                    <input type="hidden" id="site_favicon_base64">
                    <div style="margin-top: 1rem; padding: 1rem; background: var(--bg-primary); border-radius: 8px; text-align: center; border: 1px solid var(--border);">
                        <img id="preview_favicon" src="" style="max-height: 60px; max-width: 100%; display: none;" alt="Favicon">
                        <span id="preview_favicon_placeholder" style="color: var(--text-muted); font-size: 0.85rem;">Chưa có Favicon</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 2rem;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border);">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Cấu hình Học vụ</h2>
            </div>
            <div style="padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Trạng thái Đăng ký môn (Đầu kỳ)</label>
                    <select id="is_registration_open" class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                        <option value="0">Đang đóng </option>
                        <option value="1">Đang mở</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Trạng thái Chấm điểm (Cuối kỳ)</label>
                    <select id="is_grading_open" class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                        <option value="0">Đang đóng </option>
                        <option value="1">Đang mở </option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 2rem;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Thanh toán & Tích hợp API Tự động</h2>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.2rem;">Cấu hình số tài khoản trường và tích hợp token gạch nợ tự động qua Web2M/SePay.</p>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="font-weight: 600; color: var(--text-main);">Bật Auto Gạch Nợ</label>
                    <label class="switch" style="position: relative; display: inline-block; width: 44px; height: 24px;">
                        <input type="checkbox" id="auto_payment_enabled" style="opacity: 0; width: 0; height: 0;">
                        <span class="slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; transition: .4s; border-radius: 24px;"></span>
                    </label>
                </div>
            </div>
            <div style="padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Ngân hàng thụ hưởng</label>
                    <input type="text" id="bank_name" class="form-input" placeholder="Tên viết tắt (VD: VCB, MB, ACB...)" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Số tài khoản</label>
                    <input type="text" id="bank_account" class="form-input" placeholder="VD: 1023456789" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="grid-column: span 2; margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Tên chủ tài khoản</label>
                    <input type="text" id="bank_owner" class="form-input" placeholder="VD: TRUONG DAI HOC ABC" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0; border-top: 1px dashed var(--border); padding-top: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Nhà Cung Cấp API</label>
                    <select id="api_provider" class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                        <option value="sepay">SePay (sepay.vn)</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0; border-top: 1px dashed var(--border); padding-top: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">API Token</label>
                    <input type="password" id="api_token" class="form-input" placeholder="Nhập Token bảo mật..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 2rem;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border);">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Cấu hình Gửi Email (SMTP)</h2>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.2rem;">Cấu hình email để hệ thống có thể tự động gửi thư khôi phục mật khẩu, thông báo...</p>
            </div>
            <div style="padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">SMTP Host</label>
                    <input type="text" id="mail_host" class="form-input" placeholder="VD: smtp.gmail.com" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">SMTP Port</label>
                    <input type="text" id="mail_port" class="form-input" placeholder="VD: 587 hoặc 465" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Mã hóa (Encryption)</label>
                    <input type="text" id="mail_encryption" class="form-input" placeholder="VD: tls hoặc ssl" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Tên người gửi (From Name)</label>
                    <input type="text" id="mail_from_name" class="form-input" placeholder="VD: Hệ Thống Đào Tạo ABC" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Tài khoản Email</label>
                    <input type="email" id="mail_username" class="form-input" placeholder="VD: admin@school.edu.vn" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Mật khẩu / App Password</label>
                    <input type="password" id="mail_password" class="form-input" placeholder="••••••••" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-input); color: var(--text-main);">
                </div>
            </div>
        </div>

        <div class="toast" id="toast"></div>
    </div>

    <style>
        /* Thêm style cho thẻ switch (nút gạt) */
        .switch input:checked + .slider { background-color: var(--success, #10b981); }
        .switch .slider { background-color: #94a3b8; }
        .switch .slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        .switch input:checked + .slider:before { transform: translateX(20px); }
    </style>

    <script>
        const API = '/api/system-configs';

        function previewBase64(event, imgId, hiddenId) {
            const file = event.target.files[0];
            if(!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const b64 = e.target.result;
                document.getElementById(hiddenId).value = b64;
                const img = document.getElementById(imgId);
                img.src = b64;
                img.style.display = 'inline-block';
                document.getElementById(imgId + '_placeholder').style.display = 'none';
            }
            reader.readAsDataURL(file);
        }

        async function fetchData() {
            try {
                const res = await fetch(API);
                const configs = await res.json();
                
                const fields = ['site_name', 'site_hotline', 'site_fanpage', 'meta_author', 'meta_keywords', 'site_description', 
                               'is_registration_open', 'is_grading_open', 
                               'bank_name', 'bank_account', 'bank_owner', 'api_provider', 'api_token',
                               'mail_host', 'mail_port', 'mail_encryption', 'mail_from_name', 'mail_username', 'mail_password'];
                
                fields.forEach(f => {
                    if (configs[f] !== undefined && document.getElementById(f)) {
                        document.getElementById(f).value = configs[f];
                    }
                });

                if (configs.auto_payment_enabled == '1') {
                    document.getElementById('auto_payment_enabled').checked = true;
                }

                if (configs.site_logo) {
                    document.getElementById('site_logo_base64').value = configs.site_logo;
                    document.getElementById('preview_logo').src = configs.site_logo;
                    document.getElementById('preview_logo').style.display = 'inline-block';
                    document.getElementById('preview_logo_placeholder').style.display = 'none';
                }

                if (configs.site_favicon) {
                    document.getElementById('site_favicon_base64').value = configs.site_favicon;
                    document.getElementById('preview_favicon').src = configs.site_favicon;
                    document.getElementById('preview_favicon').style.display = 'inline-block';
                    document.getElementById('preview_favicon_placeholder').style.display = 'none';
                }

            } catch (e) {
                showToast('Lỗi tải cấu hình hệ thống', 'error');
            }
        }

        async function saveConfigs() {
            const btn = document.querySelector('.ab-action .btn-primary');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...';
            btn.disabled = true;

            const body = {
                configs: {
                    site_name: document.getElementById('site_name').value,
                    site_hotline: document.getElementById('site_hotline').value,
                    site_fanpage: document.getElementById('site_fanpage').value,
                    meta_author: document.getElementById('meta_author').value,
                    meta_keywords: document.getElementById('meta_keywords').value,
                    site_description: document.getElementById('site_description').value,
                    site_logo: document.getElementById('site_logo_base64').value,
                    site_favicon: document.getElementById('site_favicon_base64').value,
                    is_registration_open: document.getElementById('is_registration_open').value,
                    is_grading_open: document.getElementById('is_grading_open').value,
                    bank_name: document.getElementById('bank_name').value,
                    bank_account: document.getElementById('bank_account').value,
                    bank_owner: document.getElementById('bank_owner').value,
                    auto_payment_enabled: document.getElementById('auto_payment_enabled').checked ? '1' : '0',
                    api_provider: document.getElementById('api_provider').value,
                    api_token: document.getElementById('api_token').value,
                    mail_host: document.getElementById('mail_host').value,
                    mail_port: document.getElementById('mail_port').value,
                    mail_encryption: document.getElementById('mail_encryption').value,
                    mail_from_name: document.getElementById('mail_from_name').value,
                    mail_username: document.getElementById('mail_username').value,
                    mail_password: document.getElementById('mail_password').value,
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
                showToast('Đã cập nhật hệ thống thành công!', 'success');
            } catch (e) {
                showToast('Lỗi: ' + e.message, 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
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
