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

?>

<?php

$sql = "
SELECT
    students.id,
    students.student_code,
    students.name,
    students.email,
    classrooms.code AS classroom_name,
    faculties.name AS faculty_name

FROM students

LEFT JOIN classrooms
ON students.classroom_id = classrooms.id

LEFT JOIN faculties
ON classrooms.faculty_id = faculties.id

ORDER BY students.id  ASC
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản Lý Sinh Viên</title>

<style>
{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.table-header{
    margin-bottom:20px;
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
}

.edit-btn{
    background:orange;
}

.delete-btn{
    background:red;
}

.edit-btn:hover,
.delete-btn:hover{
    opacity:0.8;
}

@media(max-width:768px){

    body{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
        height:auto;
        position:relative;
    }

    .main{
        margin-left:0;
    }

    table{
        font-size:14px;
    }

}
</style>
</head>

<body>

    <div class="main">

        <!-- HEADER -->

        <div class="header">

            <div>
                <h1>Hệ Thống Quản Lý Sinh Viên</h1>
            </div>

        </div>

        <!-- CARD -->

        <div class="cards">

            <div class="card">
                <h3>Tổng sinh viên</h3>
                <p>
                    <?php echo mysqli_num_rows($result); ?>
                </p>
            </div>
        </div>
 <!-- TABLE -->

            <table>

                <tr>
                    <th>ID</th>
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Lớp</th>
                    <th>Khoa</th>
                    <th>Hành động</th>
                </tr>

                <?php while($row = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td>
                        <?= $row['id'] ?>
                    </td>

                    <td>
                        <?= $row['student_code'] ?>
                    </td>

                    <td>
                        <?= $row['name'] ?>
                    </td>

                    <td>
                        <?= $row['email'] ?>
                    </td>

                    <td>
                        <?= $row['classroom_name'] ?>
                    </td>

                    <td>
                        <?= $row['faculty_name'] ?>
                    </td>

                    <td>
                         <button class="edit-btn">
                            Sửa
                        </button>

                        <button class="delete-btn">
                            Xóa
                        </button>
                    </td>

                </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</body>
</html>