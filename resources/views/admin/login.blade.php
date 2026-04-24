<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hệ thống Quản lý Sinh viên</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #10b981; /* Emerald green for admin */
            --primary-hover: #059669;
            --bg-color: #0f172a; /* Dark blue background */
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--bg-color);
            background-image: radial-gradient(circle at 50% 0%, #1e293b 0%, transparent 70%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            padding: 3rem;
            width: 480px;
            max-width: 90%;
            position: relative;
            overflow: hidden;
        }

        /* Decorative glowing orb */
        .login-container::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 150px;
            height: 150px;
            background: var(--primary);
            filter: blur(100px);
            opacity: 0.3;
            z-index: 0;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 50px;
            height: 50px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            margin: 0 auto 2rem;
        }

        .brand-icon svg {
            width: 24px;
            height: 24px;
        }

        .header-text {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .header-text h1 {
            color: var(--text-main);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
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
            width: 20px;
            height: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            font-size: 1rem;
            color: var(--text-main);
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(15, 23, 42, 0.9);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="content-wrapper">
            <div class="brand-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            
            <div class="header-text">
                <h1>Quản trị Hệ thống</h1>
                <p>Đăng nhập bằng tài khoản Administrator</p>
            </div>

            <form action="#" method="POST">
                @csrf
                <div class="input-group">
                    <label for="username">Tên đăng nhập</label>
                    <div class="input-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <input type="text" id="username" name="username" placeholder="Nhập username" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-login">Truy cập Dashboard</button>
            </form>
        </div>
    </div>

</body>
</html>
