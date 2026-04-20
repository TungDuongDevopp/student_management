##### 3.2.4.1 Phân rã usecase của admin
<pre>
- Các usecase của admin chủ yếu liên quan đến thao tác với dữ liệu (CRUD ) và cấu hình hệ thống
- Các chức năng này đều có luồng xử lý tương tự nhau
    + Thêm mới dữ liệu
    + Cập nhập dữ liệu
    + Xoa dữ liệu
    + Xem dữ liệu
    + Tìm kiếm dữ liệu
- Vậy nên để tài liệu tránh quá tải biểu đồ usecase, ta chỉ đặc tả 1 usecase tổng quát cho các chức năng này

@startuml
left to right direction

actor Admin

rectangle "Hệ thống quản lý" {

  (Quản lý dữ liệu) as QL

  (Thêm dữ liệu) as CREATE
  (Cập nhật dữ liệu) as UPDATE
  (Xóa dữ liệu) as DELETE
  (Xem dữ liệu) as READ
  (Tìm kiếm dữ liệu) as SEARCH
  (Kiểm tra dữ liệu) as VALIDATE  
  Admin --> QL

  QL .> CREATE : <<include>>
  QL .> UPDATE : <<include>>
  QL .> DELETE : <<include>>
  QL .> READ   : <<include>>
  QL .> SEARCH : <<include>>
  CREATE .> VALIDATE : <<include>>
  UPDATE .> VALIDATE : <<include>>
}

@enduml
</pre>

##### 3.2.4.2 Đặc tả usecase của admin
<pre>
Do mang tính chất thao tác dữ liệu đơn giản, không có nghiệp vụ phức tạp nên không đặc tả chi tiết. Ta chỉ chọn một số usecase đặc biệt để đặc tả
a. Quản lý người dùng
- Actor: Admin
- Mô tả: Quản lý người dùng hệ thống
- Tiền điều kiện : Admin đăng nhập vào hệ thống
- Hậu điều kiện: Hệ thống thông báo sửa dữ liệu thành công
- Luồng sự kiện chính:
    1. Admin chọn chức năng quản lý người dùng
    2. Hệ thống hiển thị danh sách người dùng
    3. Admin chọn thao tác với tài khoản ( thêm, sửa xóa)
    4. Hệ thống hiển thị thay đổi thành công
- Luồng sự kiện thay thế
    A1. Trùng tài khoản
    1. Hệ thống thông báo trùng user (username, email, sdt)
    2. Admin sửa lại thông tin
    3. Hệ thống hiển thị thay đổi thành công
    A1. Dữ liệu không hợp lệ
    1. Hệ thống thông báo dữ liệu không hợp lệ
    2. Hệ thống yêu cầu nhập lại
 b. Phân quyền người dùng
 - Tên usecase: Phân quyền người dùng
 - Actor: Admin
 - Mô tả: Admin gán vai trò và quyền cho người sử dụng hệ thống
 -  Tiền điều kiện: Admin đăng nhập vào hệ thống, tài khoản người dùng đã tồn tại
 - Hậu điều kiện: Hệ thống báo gán quyền thành công
 - Luồng sự kiện chính:
    1. Admin chọn chức năng “Quản lý người dùng”
    2. Hệ thống hiển thị danh sách người dùng
    3. Admin chọn người dùng cần phân quyền
    4. Hệ thống hiển thị danh sách vai trò/quyền
    5. Admin gán vai trò hoặc quyền
    6. Hệ thống lưu thay đổi
    7. Hệ thống thông báo thành công
 - Luồng sự kiện thay thế
    A1. Không có quyền thao tác
    1. Hệ thống không cho phép thao tác
    -> Hệ thống từ chối
    A2. Dữ liệu không hợp lệ
    1. Hệ thống thông báo dữ liệu không hợp lệ
    2. Hệ thống yêu cầu nhập lại
 c. Cấu hình hệ thống
 - Tên usecase: cấu hình hệ thống
 - Actor: Admin
 - Mô tả: Admin cấu hình các thông số của hệ thống
 - Tiền điều kiện: Admin đăng nhập vào hệ thống
 - Hậu điều kiện: Hệ thống thông báo cấu hình thành công
 - Luồng sự kiện chính:
    1. Admin chọn chức năng “Cấu hình hệ thống”
    2. Hệ thống hiển thị các thông số cấu hình (ví dụ: thời gian đăng ký môn, hạn thanh toán, số tín chỉ tối đa…)
    3. Admin chỉnh sửa thông tin
    4. Admin lưu cấu hình
    5. Hệ thống cập nhật dữ liệu
    6. Hệ thống thông báo thành công
 - Luồng sự kiện thay thế
    A1. Dữ liệu cấu hình không hợp lý
    1. Hệ thống thông báo lỗi
    2. Hệ thống yêu cầu nhập lại
</pre>