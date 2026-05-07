<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Quản Lý Ký Túc Xá - Giáo Viên</title>

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
      width:260px;
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
      margin-left:260px;
      width:100%;
      padding:30px;
    }

    /* HEADER */

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
      margin-bottom:5px;
    }

    .header p{
      color:gray;
    }

    .avatar{
      width:60px;
      height:60px;
      border-radius:50%;
    }

    /* TABLE */

    .teacher-box{
      background:white;
      padding:25px;
      border-radius:15px;
      box-shadow:0 2px 10px rgba(0,0,0,0.05);
    }

    .teacher-box h2{
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

    .role{
      padding:6px 12px;
      border-radius:20px;
      color:white;
      font-size:14px;
    }

    .manager{
      background:#16a34a;
    }

    .assistant{
      background:#f59e0b;
    }

    .supervisor{
      background:#dc2626;
    }

    /* BUTTON */

    .btn{
      padding:8px 15px;
      border:none;
      border-radius:8px;
      cursor:pointer;
      color:white;
      margin-right:5px;
    }

    .edit{
      background:#2563eb;
    }

    .delete{
      background:#dc2626;
    }

    .btn:hover{
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

  <div class="sidebar">

    <h2>QUẢN LÝ KTX</h2>

    <ul class="menu">
      <li class="active">🏠 Trang quản lý</li>
      <li>👨‍🎓 Quản lý sinh viên</li>
      <li>🛏️ Quản lý phòng</li>
      <li>💳 Quản lý hóa đơn</li>
    </ul>

  </div>


  <div class="main">


    <div class="header">

      <div>
        <h1>Trang Quản Lý Giáo Viên</h1>
        <p>Quản lý sinh viên và nhân viên ký túc xá</p>
      </div>

      <img
        class="avatar"
        src=""
        alt=""
      >

    </div>



    <div class="teacher-box">

      <h2>Danh Sách Giáo Viên / Quản Lý</h2>

      <table>

        <tr>
          <th>Mã NV</th>
          <th>Họ Tên</th>
          <th>Chức Vụ</th>
          <th>SĐT</th>
          <th>Khu Quản Lý</th>
          <th>Hành Động</th>
        </tr>

        <tr>
          <td>GV001</td>
          <td>Nguyễn Văn Minh</td>
          <td>
            <span class="role manager">
              Quản lý trưởng
            </span>
          </td>
          <td>0988777666</td>
          <td>Khu A</td>
          <td>
            <button class="btn edit" onclick="editRow(this)">Sửa</button>
            <button class="btn delete" onclick="deleteRow(this)">Xóa</button>
          </td>
        </tr>

        <tr>
          <td>GV002</td>
          <td>Trần Thị Lan</td>
          <td>
            <span class="role assistant">
              Trợ lý quản lý
            </span>
          </td>
          <td>0911222444</td>
          <td>Khu B</td>
          <td>
            <button class="btn edit" onclick="editRow(this)">Sửa</button>
            <button class="btn delete" onclick="deleteRow(this)">Xóa</button>
          </td>
        </tr>

        <tr>
          <td>GV003</td>
          <td>Lê Quốc Huy</td>
          <td>
            <span class="role supervisor">
              Giám sát
            </span>
          </td>
          <td>0977555888</td>
          <td>Khu C</td>
          <td>
            <button class="btn edit" onclick="editRow(this)">Sửa</button>
            <button class="btn delete" onclick="deleteRow(this)">Xóa</button>
          </td>
        </tr>

        <tr>
          <td>GV004</td>
          <td>Phạm Thị Hoa</td>
          <td>
            <span class="role assistant">
              Trợ lý quản lý
            </span>
          </td>
          <td>0933444555</td>
          <td>Khu D</td>
          <td>
            <button class="btn edit" onclick="editRow(this)">Sửa</button>
            <button class="btn delete" onclick="deleteRow(this)">Xóa</button>
          </td>
        </tr>

      </table>

    </div>

  </div>

</body>
<script>

function editRow(button){

    let row = button.parentElement.parentElement;

    let name = row.cells[1];
    let phone = row.cells[3];

    let newName = prompt("Nhập tên mới:", name.innerHTML);
    let newPhone = prompt("Nhập SĐT mới:", phone.innerHTML);

    if(newName != null){
        name.innerHTML = newName;
    }

    if(newPhone != null){
        phone.innerHTML = newPhone;
    }

}

function deleteRow(button){

    if(confirm("Bạn có chắc muốn xóa không?")){

        let row = button.parentElement.parentElement;
        row.remove();

    }

}

</script>
</html>