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
    </style>
</head>

<body>

    <div class="login-container">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Logo_Truong_Dai_hoc_Mo_-_Dia_chat.jpg/960px-Logo_Truong_Dai_hoc_Mo_-_Dia_chat.jpg"
            class="school-logo">

        <div class="header-text">
            <h1>Đăng nhập hệ thống</h1>
            <p>Vui lòng nhập username và password của bạn</p>
        </div>

        <form action="#" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                        </path>
                    </svg>
                    <input type="text" id="username" name="username" placeholder="Nhập username" required>
                </div>
            </div>

            <div class="input-group">
                <label for="password">Mật khẩu</label>
                <div class="input-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <div class="forgot-pass">
                    <a href="#">Quên mật khẩu?</a>
                </div>
            </div>

            <button type="submit" class="btn-login">Đăng Nhập</button>
        </form>
    </div>

</body>

</html>
