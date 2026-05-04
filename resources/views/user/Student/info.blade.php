<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thông tin sinh viên</title>

<style>
    body {
        font-family: Arial;
        background: #f5f7fa;
        padding: 20px;
    }

    .card {
        background: #fff;
        border-radius: 10px;
        border: 2px solid #3fa9f5;
        padding: 15px;
    }

    .header {
        font-weight: bold;
        color: #2c7be5;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
    }

    .content {
        display: flex;
        margin-top: 15px;
    }

    .avatar img {
        width: 140px;
        border-radius: 8px;
    }

    .columns {
        display: flex;
        flex: 1;
    }

    .col {
        flex: 1;
        padding: 0 20px;
    }

    .col:not(:last-child) {
        border-right: 1px solid #ccc;
    }

    .row {
        margin-bottom: 10px;
    }

    .label {
        font-weight: bold;
        width: 150px;
        display: inline-block;
    }
</style>
</head>

<body>

<div class="card">
    <div class="header">👤 Thông tin sinh viên</div>

    <div class="content">
        <div class="avatar">
            <img id="avatar">
        </div>

        <div class="columns">

            <div class="col" id="col1"></div>
            <div class="col" id="col2"></div>
            <div class="col" id="col3"></div>

        </div>
    </div>
</div>

<script>
    // 🔥 CHỈ CẦN SỬA Ở ĐÂY
    const student = {
        avatar: "https://via.placeholder.com/140",
        col1: {
            "Mã SV": "2221050567",
            "Tên sinh viên": "Nguyễn Quang Huy",
            "Ngày sinh": "11/09/2004",
            "Giới tính": "Nam",
            "Trạng thái": "Đang học"
        },
        col2: {
            "Số điện thoại": "",
            "Số CMND/CCCD": "001204030734",
            "Dân tộc": "",
            "Tôn giáo": "",
            "Nơi sinh": ""
        },
        col3: {
            "Quốc tịch": "",
            "Email 1": "2221050567@student.humg.edu.vn",
            "Email 2": "",
            "Địa chỉ": ""
        }
    };

    // Render avatar
    document.getElementById("avatar").src = student.avatar;

    function render(colId, data) {
        let html = "";
        for (let key in data) {
            html += `<div class="row">
                        <span class="label">${key}:</span>
                        <span>${data[key]}</span>
                     </div>`;
        }
        document.getElementById(colId).innerHTML = html;
    }

    render("col1", student.col1);
    render("col2", student.col2);
    render("col3", student.col3);

</script>

</body>
</html>