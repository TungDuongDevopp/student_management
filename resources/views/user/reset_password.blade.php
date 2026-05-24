<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Hệ thống Quản lý</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --input-border: #cbd5e1;
            --input-bg: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.8)),
                url('https://media.sohuutritue.net.vn/files/quyentrung/2025/07/08/1-0927.jpg') center center/cover no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: var(--card-bg);
            border-radius: 0;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            padding: 3.5rem 4rem;
            width: 520px;
            max-width: 95%;
            position: relative;
        }

        .school-logo {
            width: 80px;
            height: auto;
            display: block;
            margin: 0 auto 1.5rem auto;
        }

        .header-text {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .header-text h1 {
            color: var(--text-main);
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .header-text p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-main);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper svg {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            width: 18px;
            height: 18px;
        }

        .input-group input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 0;
            font-size: 1rem;
            color: var(--text-main);
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: var(--primary-hover);
        }

        .forgot-pass {
            text-align: right;
            margin-top: 0.6rem;
        }

        .forgot-pass a {
            font-size: 0.9rem;
            color: var(--primary);
            text-decoration: none;
        }

        .forgot-pass a:hover {
            text-decoration: underline;
        }

        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #ef4444;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            opacity: 0;
            transform: translateY(-20px);
            animation: slideInToast 0.4s forwards, fadeOutToast 0.4s forwards 4s;
        }

        @keyframes slideInToast {
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOutToast {
            to { opacity: 0; visibility: hidden; }
        }
    </style>
</head>

<body>
    @if($errors->any())
    <div class="toast-notification">
        {{ $errors->first() }}
    </div>
    @endif

    <div class="login-container">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Logo_Truong_Dai_hoc_Mo_-_Dia_chat.jpg/960px-Logo_Truong_Dai_hoc_Mo_-_Dia_chat.jpg"
            class="school-logo">

        <div class="header-text">
            <h1>Đặt Lại Mật Khẩu</h1>
            <p>Vui lòng nhập mật khẩu mới cho tài khoản của bạn.</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="input-group">
                <label for="email">Địa chỉ Email</label>
                <div class="input-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly style="background: #e2e8f0; cursor: not-allowed;">
                </div>
            </div>

            <div class="input-group">
                <label for="password">Mật khẩu mới</label>
                <div class="input-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <input type="password" id="password" name="password" placeholder="Tối thiểu 6 ký tự" required>
                </div>
                @error('password')
                    <div style="color: red; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                <div class="input-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
                </div>
            </div>

            <button type="submit" class="btn-login">Cập Nhật Mật Khẩu</button>
        </form>
    </div>

</body>

</html>
