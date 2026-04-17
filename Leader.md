##### 3.2.2.1 Phân rã usecase tiêu biểu của sinh viên
a. Usecase đăng ký môn học
<pre>
@startuml
left to right direction

actor "Sinh viên" as SV

(Đăng ký môn học) as DK
(Xem danh sách môn học) as XEM
(Kiểm tra điều kiện) as DK_CHECK
(Kiểm tra trùng lịch) as CHECK_TKB
(Lưu đăng ký) as SAVE
(Hủy đăng ký môn học) as HUY

SV --> DK

DK .> XEM : <<include>>
DK .> DK_CHECK : <<include>>
DK .> CHECK_TKB : <<include>>
DK .> SAVE : <<include>>

HUY .> DK : <<extend>>

@enduml
</pre>
b. Usecase thanh toán học phí
<pre>
@startuml
left to right direction

actor "Sinh viên" as SV

(Thanh toán học phí) as PAY
(Xem thông tin học phí) as VIEW
(Xác nhận thanh toán) as CONFIRM
(Cập nhật trạng thái) as UPDATE
(Hủy thanh toán) as CANCEL

SV --> PAY

PAY .> VIEW : <<include>>
PAY .> CONFIRM : <<include>>
PAY .> UPDATE : <<include>>

CANCEL .> PAY : <<extend>>

@enduml
</pre>
c.Usecase xem kết quả điểm danh
</pre>
@startuml
left to right direction

actor "Sinh viên" as SV

(Xem kết quả điểm danh) as VIEW_ATT
(Xem danh sách buổi học) as LIST
(Xem trạng thái điểm danh) as STATUS

SV --> VIEW_ATT

VIEW_ATT .> LIST : <<include>>
VIEW_ATT .> STATUS : <<include>>

@enduml
</pre>
d.Usecase xem bảng điểm
<pre>
@startuml
left to right direction

actor "Sinh viên" as SV

(Xem bảng điểm) as VIEW_GRADE
(Xem danh sách môn học) as SUBJECT
(Xem điểm chi tiết) as DETAIL

SV --> VIEW_GRADE

VIEW_GRADE .> SUBJECT : <<include>>
VIEW_GRADE .> DETAIL : <<include>>

@enduml
</pre>

##### 3.2.2.2 Đặc tả usecase tiêu biểu của sinh viên
<pre>
a. Đặc tả usecase đăng ký môn học
- Tên usecase: Đăng ký môn học
- Actor: Sinh viên
- Mô tả: Sinh viên đăng ký môn học cho học kỳ mới
- Tiền điều kiện: Sinh viên đăng nhập trên hệ thống trong thời gian cho phép đăng ký môn học
- Hậu điều kiện: Môn học được thêm vào danh sách môn học của sinh viên, dữ liệu được lưu vào hệ thống
- Luồng sự kiện chính:
    1. Sinh viên chọn chức năng đăng ký môn học
    2. Hệ thống hiển thị danh sách môn học
    3. Sinh viên chọn môn học muốn đăng ký
    4. Hệ thống kiểm tra điều kiện và trùng lịch
    5. Hệ thống lưu đăng ký
    6. Hệ thống hiển thị thông báo thành công
- Luồng sự kiện thay thế
    A1. Môn học bị trùng thời khóa biểu
    1.Hệ thống thông báo lỗi trùng lịch`
    2. Sinh viên chọn đăng ký môn mới
    3. Hệ thống thông báo thành công
    A2. Ngoài thời gian đăng ký môn học
    1. Hệ thống thông báo lỗi ngoài thời gian đăng ký môn học

b. Đặc tả usecase thanh toán học phí
-Tên usecase: Thánh toán học phí
-Actor: Sinh viên
-Mô tả: Sinh viên thanh toán học phí
-Tiền điều kiện: Sinh viên đăng nhập trên hệ thống trong thời gian cho phép thanh toán học phí
-Hậu điều kiện: Học phí được thanh toán, dữ liệu được lưu vào hệ thống
-Luồng sự kiện chính:
    1.Sinh viên chọn chức năng “Thanh toán học phí”
    2.Hệ thống hiển thị thông tin học phí
    3.Sinh viên chọn phương thức thanh toán
    4.Sinh viên xác nhận thanh toán
    5.Hệ thống xử lý thanh toán
    6.Hệ thống cập nhật trạng thái
    7.Thông báo thành công
-Luồng sự kiện thay thế:
    A1. Hết thời hạn thanh toán
    1. Hệ thống thông báo lỗi hết thời hạn thanh toán
    2. Hệ thống từ chối thanh toán
    A2. Thanh toán thất bại
    1. Hệ thống thông báo lỗi
    2. Sinh viên thực hiện lại
c. Đặc tả usecase xem kết quả điểm danh
- Tên usecase: Xem kết quả điểm danh
- Tên actor: Sinh viên
- Mô tả: Sinh viên xem kết quả điểm danh
- Tiền điều kiện: Sinh viên đăng nhập trên hệ thống
- Hậu điều kiện: Kết quả điểm danh được hiển thị
- Luồng sự kiện chính:
    1. Sinh viên chọn chức năng xem kết quả điểm danh
    2. Hệ thống hiển thị danh sách môn học
    3. Sinh viên chọn môn học muốn xem kết quả điểm danh
    4. Hệ thống hiển thị kết quả điểm danh
- Luồng sự kiện thay thế:
    A1. Sinh viên không có kết quả điểm danh
    1. Hệ thống thông báo lỗi không có kết quả điểm danh

d. Đặc tả usecase xem bảng điểm 
- Tên usecase: Xem bảng điểm
- Tên actor: Sinh viên
- Mô tả: Sinh viên xem bảng điểm
- Tiền điều kiện: Sinh viên đăng nhập trên hệ thống
- Hậu điều kiện: Bảng điểm được hiển thị
- Luồng sự kiện chính:
    1. Sinh viên chọn chức năng xem bảng điểm
    2. Hệ thống hiển thị danh sách môn học
    3. Sinh viên chọn môn học muốn xem bảng điểm
    4. Hệ thống hiển thị bảng điểm
- Luồng sự kiện thay thế:
    A1. Sinh viên không có bảng điểm
    1. Hệ thống thông báo lỗi không có bảng điểm
</pre>