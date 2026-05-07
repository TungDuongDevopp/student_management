<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "qlsv"
);

if (!$conn) {
    die("Kết nối thất bại");
}

$sql = "
SELECT
    teachers.id,
    teachers.teacher_code,
    teachers.name,
    teachers.email,
    faculties.name AS faculty_name

FROM teachers

LEFT JOIN faculties
ON teachers.faculty_id = faculties.id

ORDER BY teachers.id ASC
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản Lý Giáo Viên</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f1f5f9;
    padding:30px;
}

.header{
    background:white;
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.header h1{
    color:#2563eb;
}

.card{
    background:white;
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.card h3{
    margin-bottom:10px;
}

.card p{
    font-size:30px;
    color:#2563eb;
    font-weight:bold;
}

.table-box{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#2563eb;
    color:white;
    padding:15px;
    text-align:left;
}

td{
    padding:15px;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f8fafc;
}

.edit-btn,
.delete-btn{
    padding:8px 14px;
    border:none;
    border-radius:8px;
    color:white;
    cursor:pointer;
    text-decoration:none;
}

.edit-btn{
    background:orange;
}

.delete-btn{
    background:red;
}

</style>

</head>

<body>

    <!-- HEADER -->

    <div class="header">

        <h1>Hệ Thống Quản Lý Giáo Viên</h1>

    </div>

    <!-- CARD -->

    <div class="card">

        <h3>Tổng giáo viên</h3>

        <p>

            <?php echo mysqli_num_rows($result); ?>

        </p>

    </div>

    <!-- TABLE -->

    <div class="table-box">

        <table>

            <tr>

                <th>ID</th>

                <th>Mã GV</th>

                <th>Họ tên</th>

                <th>Email</th>

                <th>Khoa</th>

                <th>Hành động</th>

            </tr>

            <?php while($row = mysqli_fetch_assoc($result)) { ?>

            <tr>

                <td>
                    <?= $row['id'] ?>
                </td>

                <td>
                    <?= $row['teacher_code'] ?>
                </td>

                <td>
                    <?= $row['name'] ?>
                </td>

                <td>
                    <?= $row['email'] ?>
                </td>

                <td>
                    <?= $row['faculty_name'] ?>
                </td>

                <td>

                    <a
                        class="edit-btn"
                        href="edit_teacher.php?id=<?= $row['id'] ?>"
                    >
                        Sửa
                    </a>

                    <a
                        class="delete-btn"
                        href="delete_teacher.php?id=<?= $row['id'] ?>"
                        onclick="return confirm('Bạn có chắc muốn xóa?')"
                    >
                        Xóa
                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</body>

</html>