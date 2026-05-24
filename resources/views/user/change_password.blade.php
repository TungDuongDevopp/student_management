@extends(Auth::check() && Auth::user()->role_id == 2 ? 'layouts.user.teacher_sidebar' : 'layouts.user.student_sidebar')
@section('title', 'Bảo Mật Tài Khoản')

@php
    $isTeacher = Auth::check() && Auth::user()->role_id == 2;
    $themeColor = $isTeacher ? '#dc2626' : '#2563eb';
    $themeHover = $isTeacher ? '#b91c1c' : '#1d4ed8';
    $themeLight = $isTeacher ? 'rgba(220, 38, 38, 0.1)' : 'rgba(37, 99, 235, 0.1)';
@endphp

@section('content')
<div style="background-color: #f9fafb; min-height: calc(100vh - 60px); padding: 4rem 1rem; font-family: 'Inter', -apple-system, sans-serif; display: flex; flex-direction: column; align-items: center;">
    
    <div style="width: 100%; max-width: 440px;">
        <!-- Back button -->
        <a href="{{ Auth::check() && Auth::user()->student ? route('student.info') : route('teacher.info') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #6b7280; text-decoration: none; font-size: 0.9rem; font-weight: 500; margin-bottom: 2rem; transition: color 0.2s;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#6b7280'">
            <i class="fa-solid fa-arrow-left"></i> Quay lại Hồ sơ
        </a>

        <div style="background: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #f3f4f6; overflow: hidden;">
            <div style="padding: 2.5rem 2.5rem 1.5rem;">
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                    <img src="https://lic.humg.edu.vn/App_Themes/humg/images/humg-logo.png" alt="Logo HUMG" style="height: 60px; object-fit: contain;">
                </div>
                <h1 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #111827; text-align: center;">Đổi mật khẩu</h1>
            </div>

            <div style="padding: 0 2.5rem 2.5rem;">
                @if(session('success'))
                    <div style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.9rem;">
                        <i class="fa-solid fa-circle-check" style="margin-top: 2px;"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('user.change_password.post') }}" method="POST">
                    @csrf
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem; font-size: 0.9rem;">Mật khẩu hiện tại</label>
                        <input type="password" name="old_password" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #111827; font-size: 0.95rem; outline: none; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='{{ $themeColor }}'; this.style.boxShadow='0 0 0 3px {{ $themeLight }}';" onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';" required>
                        
                        @if($errors->has('old_password') && $errors->first('old_password') == 'wrong')
                            <div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                                <i class="fa-solid fa-circle-exclamation"></i> Mật khẩu cũ không chính xác.
                            </div>
                        @elseif($errors->has('old_password'))
                            <div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;">{{ $errors->first('old_password') }}</div>
                        @endif
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem; font-size: 0.9rem;">Mật khẩu mới</label>
                        <input type="password" name="new_password" id="new_password" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #111827; font-size: 0.95rem; outline: none; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='{{ $themeColor }}'; this.style.boxShadow='0 0 0 3px {{ $themeLight }}';" onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';" required minlength="8">
                        @error('new_password')
                            <div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror

                        <!-- Bảng đo độ mạnh mật khẩu -->
                        <div id="password-strength-container" style="display: none; margin-top: 0.75rem; background: #f9fafb; border: 1px solid #f3f4f6; padding: 0.75rem; border-radius: 8px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span id="strength-text" style="font-size: 0.8rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Mức độ: Trống</span>
                            </div>
                            <div style="display: flex; gap: 4px; margin-bottom: 0.75rem; height: 4px;">
                                <div id="bar-1" style="flex: 1; border-radius: 4px; background: #e5e7eb; transition: all 0.3s;"></div>
                                <div id="bar-2" style="flex: 1; border-radius: 4px; background: #e5e7eb; transition: all 0.3s;"></div>
                                <div id="bar-3" style="flex: 1; border-radius: 4px; background: #e5e7eb; transition: all 0.3s;"></div>
                                <div id="bar-4" style="flex: 1; border-radius: 4px; background: #e5e7eb; transition: all 0.3s;"></div>
                            </div>
                            
                            <div style="font-size: 0.8rem; color: #6b7280; display: flex; flex-direction: column; gap: 0.4rem;">
                                <div id="req-length" style="display: flex; align-items: center; gap: 0.4rem;"><i class="fa-solid fa-circle-xmark" style="color: #ef4444; width: 12px;"></i> Ít nhất 8 ký tự</div>
                                <div id="req-number" style="display: flex; align-items: center; gap: 0.4rem;"><i class="fa-solid fa-circle-xmark" style="color: #ef4444; width: 12px;"></i> Chứa ít nhất 1 chữ số</div>
                                <div id="req-special" style="display: flex; align-items: center; gap: 0.4rem;"><i class="fa-solid fa-circle-xmark" style="color: #ef4444; width: 12px;"></i> Chứa ký tự đặc biệt (@, #, $,...)</div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem; font-size: 0.9rem;">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #111827; font-size: 0.95rem; outline: none; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='{{ $themeColor }}'; this.style.boxShadow='0 0 0 3px {{ $themeLight }}';" onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';" required minlength="8">
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem; margin-top: -1rem;">
                        <a href="{{ route('password.request') }}" style="font-size: 0.85rem; color: {{ $themeColor }}; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='{{ $themeHover }}'" onmouseout="this.style.color='{{ $themeColor }}'">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" style="width: 100%; background: {{ $themeColor }}; color: #fff; padding: 0.85rem 1.5rem; font-size: 0.95rem; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: background 0.2s; text-transform: uppercase; letter-spacing: 0.5px;" onmouseover="this.style.background='{{ $themeHover }}'" onmouseout="this.style.background='{{ $themeColor }}'">
                        Lưu mật khẩu
                    </button>
                </form>
            </div>
        </div>
        
        <p style="text-align: center; margin-top: 2rem; color: #9ca3af; font-size: 0.85rem;">
            <i class="fa-solid fa-lock" style="margin-right: 0.25rem;"></i> Thông tin của bạn được mã hóa an toàn.
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passInput = document.getElementById('new_password');
        const container = document.getElementById('password-strength-container');
        const bar1 = document.getElementById('bar-1');
        const bar2 = document.getElementById('bar-2');
        const bar3 = document.getElementById('bar-3');
        const bar4 = document.getElementById('bar-4');
        const strengthText = document.getElementById('strength-text');

        const reqLength = document.getElementById('req-length');
        const reqNumber = document.getElementById('req-number');
        const reqSpecial = document.getElementById('req-special');

        passInput.addEventListener('input', function(e) {
            const val = e.target.value;
            if (val.length > 0) {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }

            let strength = 0;
            
            const hasLength = val.length >= 8;
            const hasNumber = /\d/.test(val);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>\-_]/.test(val);
            const hasUpper = /[A-Z]/.test(val);
            const hasLower = /[a-z]/.test(val);

            if (hasLength) strength += 1;
            if (hasNumber && (hasUpper || hasLower)) strength += 1;
            if (hasSpecial) strength += 1;
            if (val.length >= 12 && hasNumber && hasSpecial && hasUpper && hasLower) strength += 1;

            updateReq(reqLength, hasLength, 'Ít nhất 8 ký tự');
            updateReq(reqNumber, hasNumber, 'Chứa ít nhất 1 chữ số');
            updateReq(reqSpecial, hasSpecial, 'Chứa ký tự đặc biệt (@, #, $,...)');

            const bars = [bar1, bar2, bar3, bar4];
            bars.forEach(b => b.style.background = '#e5e7eb');

            if (val.length === 0) {
                strengthText.textContent = "Mức độ: Trống";
                strengthText.style.color = "#6b7280";
            } else if (strength === 0 || strength === 1) {
                bar1.style.background = '#ef4444';
                strengthText.textContent = "Mức độ: Yếu";
                strengthText.style.color = "#ef4444";
            } else if (strength === 2) {
                bar1.style.background = '#eab308';
                bar2.style.background = '#eab308';
                strengthText.textContent = "Mức độ: Trung Bình";
                strengthText.style.color = "#eab308";
            } else if (strength === 3) {
                bar1.style.background = '#22c55e';
                bar2.style.background = '#22c55e';
                bar3.style.background = '#22c55e';
                strengthText.textContent = "Mức độ: Mạnh";
                strengthText.style.color = "#22c55e";
            } else if (strength >= 4) {
                bars.forEach(b => b.style.background = '#16a34a');
                strengthText.textContent = "Mức độ: Rất Mạnh";
                strengthText.style.color = "#16a34a";
            }
        });

        function updateReq(el, isMet, text) {
            if (isMet) {
                el.innerHTML = '<i class="fa-solid fa-circle-check" style="color: #22c55e; width: 12px;"></i> <span style="color: #374151; text-decoration: line-through; opacity: 0.7;">' + text + '</span>';
            } else {
                el.innerHTML = '<i class="fa-solid fa-circle-xmark" style="color: #ef4444; width: 12px;"></i> <span style="color: #6b7280;">' + text + '</span>';
            }
        }
    });
</script>
@endsection
