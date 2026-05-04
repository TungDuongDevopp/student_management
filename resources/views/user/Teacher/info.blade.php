<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Cố vấn học tập</title>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
        padding: 20px;
    }

    .card {
        background: #fff;
        border: 2px solid #3fa9f5;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 15px 20px;
        max-width: 900px;
    }

    .card-header {
        font-weight: bold;
        color: #2c7be5;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }

    .content {
        display: flex;
        margin-top: 15px;
    }

    .col {
        flex: 1;
        padding: 0 20px;
    }

    .col:first-child {
        padding-left: 0;
    }

    .col:last-child {
        padding-right: 0;
    }

    .col:not(:last-child) {
        border-right: 1px solid #ccc;
    }

    .row {
        margin-bottom: 12px;
    }

    .label {
        font-weight: 500;
        color: #555;
        display: inline-block;
        width: 120px;
    }

    .value {
        font-weight: 600;
        color: #333;
    }
</style>
</head>

<body>

<div class="card">
    <div class="card-header">
        👤 Cố vấn học tập
    </div>

    <div class="content">

        <!-- Cột trái -->
        <div class="col">
            <div class="row">
                <span class="label">Tài khoản:</span>
                <span class="value">0801-16</span>
            </div>
            <div class="row">
                <span class="label">Họ và tên:</span>
                <span class="value">Ngô Ngọc Anh</span>
            </div>
        </div>

        <!-- Cột phải -->
        <div class="col">
            <div class="row">
                <span class="label">Email:</span>
                <span class="value">ngongocanh@humg.edu.vn</span>
            </div>
            <div class="row">
                <span class="label">Điện thoại:</span>
                <span class="value">0971117492</span>
            </div>
        </div>

    </div>
</div>

</body>
</html>