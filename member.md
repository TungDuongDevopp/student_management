##### 3.2.3.1 Phân rã usecase của giảng viên 
**a. Đăng nhập** 'tách thành mục 3.2.5

<pre>
@startuml
left to right direction

actor "Giảng viên" as GV

usecase "Đăng nhập" as login
usecase "Quên mật khẩu" as forgot_pwd

GV --> login

login <.. forgot_pwd : <<extend>>
@enduml
</pre>

**b. Xem danh sách lớp học**

<pre>
@startuml
left to right direction

actor "Giảng viên" as GV

usecase "Đăng nhập" as login
usecase "Xem danh sách lớp học" as xem_lop
usecase "Lọc lớp theo Học kỳ" as loc_hk
usecase "Tìm kiếm tên lớp" as tim_lop

GV --> xem_lop

xem_lop ..> login : <<include>>

loc_hk .> xem_lop : <<extend>>
tim_lop .> xem_lop : <<extend>>
@enduml
</pre>

**c. Xem danh sách sinh viên**

<pre>
@startuml
left to right direction

actor "Giảng viên" as GV

usecase "Đăng nhập" as login
usecase "Xem danh sách sinh viên" as xem_sv
usecase "Xuất danh sách ra Excel" as xuat_excel
usecase "Tìm kiếm mã sinh viên" as tim_sv

GV --> xem_sv

xem_sv ..> login : <<include>>

xuat_excel .> xem_sv : <<extend>>
tim_sv .> xem_sv : <<extend>>
@enduml
</pre>

**d. Nhập và cập nhật điểm**

<pre>
@startuml
left to right direction

actor "Giảng viên" as GV

usecase "Đăng nhập" as login
usecase "Nhập và cập nhật điểm" as nhap_diem
usecase "Nhập điểm thủ công" as nhap_tay
usecase "Import điểm từ Excel" as import_excel
usecase "Chốt bảng điểm" as chot_diem

GV --> nhap_diem

nhap_diem ..> login : <<include>>

' Cac phuong thuc nhap diem khac nhau
nhap_tay .> nhap_diem : <<extend>>
import_excel .> nhap_diem : <<extend>>

' Sau khi nhap co co the chot
chot_diem .> nhap_diem : <<extend>>
@enduml
</pre>

**e. Xem thời khóa biểu**

<pre>
@startuml
left to right direction

actor "Giảng viên" as GV

usecase "Đăng nhập" as login
usecase "Xem thời khóa biểu" as xem_tkb
usecase "Xem theo Tuần" as tkb_tuan
usecase "Xem theo Tháng" as tkb_thang

GV --> xem_tkb

xem_tkb ..> login : <<include>>

tkb_tuan .> xem_tkb : <<extend>>
tkb_thang .> xem_tkb : <<extend>>
@enduml
</pre>

**f. Xem thông báo**'tách thành mục 3.2.5

<pre>
@startuml
left to right direction

actor "Giảng viên" as GV

usecase "Đăng nhập" as login
usecase "Xem thông báo" as xem_tb
usecase "Đánh dấu đã đọc" as danh_dau

GV --> xem_tb

xem_tb ..> login : <<include>>

danh_dau .> xem_tb : <<extend>>
@enduml
</pre>

**g. Điểm danh sinh viên**

<pre>
@startuml
left to right direction

actor "Giảng viên" as GV

usecase "Đăng nhập" as login
usecase "Điểm danh sinh viên" as diem_danh
usecase "Điểm danh gọi tên" as dd_thu_cong
usecase "Tạo QR Code điểm danh" as dd_qr

GV --> diem_danh

diem_danh ..> login : <<include>>

dd_thu_cong .> diem_danh : <<extend>>
dd_qr .> diem_danh : <<extend>>
@enduml
</pre>

**h. Gửi ý kiến phản hồi**'tách thành mục 3.2.5

<pre>
@startuml
left to right direction

' ĐÂY LÀ NOTE ẨN: Khởi tạo tác nhân Giảng viên
actor "Giảng viên" as GV

' Khai báo khối chức năng Đăng nhập
usecase "Đăng nhập" as login

' Khai báo cụm chức năng Ticket
usecase "Gửi phiếu hỗ trợ (Ticket)" as gui_ticket
usecase "Chọn chủ đề (Lỗi/Nghiệp vụ)" as phan_loai
usecase "Đính kèm minh chứng" as dinh_kem

' Liên kết Giảng viên với hành động gửi
GV --> gui_ticket

' NOTE ẨN: Ràng buộc bắt buộc phải đăng nhập
gui_ticket ..> login : <<include>>

' NOTE ẨN: Khai báo các tính năng mở rộng khi điền Form
phan_loai .> gui_ticket : <<extend>>
dinh_kem .> gui_ticket : <<extend>>

@enduml
</pre>
