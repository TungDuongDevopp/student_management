<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Trang Chủ Sinh Viên - Ký Túc Xá</title>

  <style>

    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family:Arial, Helvetica, sans-serif;
    }

    body{
      background:#f1f5f9;
      display:flex;
    }

    /* SIDEBAR */

    .sidebar{
      width:250px;
      height:100vh;
      background:#0f172a;
      color:white;
      padding:20px;
      position:fixed;
    }

    .sidebar h2{
      text-align:center;
      margin-bottom:40px;
      color:#38bdf8;
    }

    .menu{
      list-style:none;
    }

    .menu li{
      padding:15px;
      margin-bottom:10px;
      border-radius:10px;
      cursor:pointer;
      transition:0.3s;
    }

    .menu li:hover{
      background:#1e293b;
    }

    .active{
      background:#2563eb;
    }

    /* MAIN */

    .main{
      margin-left:250px;
      width:100%;
      padding:30px;
    }

    .header{
      background:white;
      padding:20px;
      border-radius:15px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:30px;
      box-shadow:0 2px 10px rgba(0,0,0,0.05);
    }

    .header h1{
      color:#0f172a;
    }

    .header p{
      margin-top:5px;
      color:gray;
    }

    .avatar{
      width:60px;
      height:60px;
      border-radius:50%;
    }

    /* STUDENT TABLE */

    .student-box{
      background:white;
      padding:25px;
      border-radius:15px;
      box-shadow:0 2px 10px rgba(0,0,0,0.05);
    }

    .student-box h2{
      margin-bottom:20px;
      color:#0f172a;
    }

    table{
      width:100%;
      border-collapse:collapse;
    }

    table th{
      background:#2563eb;
      color:white;
      padding:15px;
      text-align:left;
    }

    table td{
      padding:15px;
      border-bottom:1px solid #ddd;
    }

    table tr:hover{
      background:#f8fafc;
    }

    .status{
      padding:5px 10px;
      border-radius:20px;
      font-size:14px;
      color:white;
    }

    .active-status{
      background:green;
    }

    .warning-status{
      background:orange;
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

  <!-- SIDEBAR -->

  <div class="sidebar">

    <h2>KÝ TÚC XÁ</h2>

    <ul class="menu">
      <li class="active">🏠 Trang chủ</li>
      <li>👤 Thông tin cá nhân</li>
      <li>🛏️ Thông tin phòng</li>
      <li>💳 Hóa đơn</li>
    </ul>

  </div>


  <div class="main">



    <div class="header">

      <div>
        <h1>Hệ Thống Quản Lý Ký Túc Xá</h1>
        <p>Danh sách sinh viên đang ở trong ký túc xá</p>
      </div>

      <img
        class="avatar"
        src=""
        alt=""
      >

    </div>


    <div class="student-box">

      <h2>Danh Sách Sinh Viên</h2>

      <table>

        <tr>
          <th>MSSV</th>
          <th>Họ Tên</th>
          <th>Phòng</th>
          <th>SĐT</th>
          <th>Trạng Thái</th>
        </tr>

        <tr>
          <td>22110001</td>
          <td>Nguyễn Văn A</td>
          <td>A-203</td>
          <td>0987654321</td>
          <td>
            <span class="status active-status">
              Đang ở
            </span>
          </td>
        </tr>

        <tr>
          <td>22110002</td>
          <td>Trần Thị B</td>
          <td>B-105</td>
          <td>0911222333</td>
          <td>
            <span class="status active-status">
              Đang ở
            </span>
          </td>
        </tr>

        <tr>
          <td>22110003</td>
          <td>Lê Văn C</td>
          <td>C-301</td>
          <td>0977555666</td>
          <td>
            <span class="status warning-status">
              Sắp hết hạn
            </span>
          </td>
        </tr>

        <tr>
          <td>22110004</td>
          <td>Phạm Thị D</td>
          <td>D-202</td>
          <td>0966888999</td>
          <td>
            <span class="status active-status">
              Đang ở
            </span>
          </td>
        </tr>

        <tr>
          <td>22110005</td>
          <td>Hoàng Văn E</td>
          <td>E-110</td>
          <td>0933444555</td>
          <td>
            <span class="status warning-status">
              Sắp hết hạn
            </span>
          </td>
        </tr>

      </table>

    </div>

  </div>

</body>
</html>