##### 3.2.3.1 Phân rã usecase của giảng viên 
**a. Đăng nhập** 

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

' Sau khi nhap thay/co co the chot
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

**f. Xem thông báo**

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

**h. Gửi ý kiến phản hồi**

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
#### 3.2.3.2 Đặc tả usecase của giảng viên 

**a. Đăng nhập**
Mã Use Case: UC_GV_01

Tác nhân: Giảng viên

Mô tả: Giảng viên thực hiện xác thực tài khoản để truy cập vào các chức năng của hệ thống. Hỗ trợ chức năng khôi phục mật khẩu khi cần thiết.

Tiền điều kiện: Giảng viên đã được cấp tài khoản hợp lệ từ Quản trị viên (Admin).

Luồng sự kiện chính (Basic Flow):

Giảng viên truy cập vào trang Đăng nhập của hệ thống.

Giảng viên điền thông tin vào trường Tên đăng nhập (Username) và Mật khẩu (Password).

Bấm nút Đăng nhập. Hệ thống mã hóa mật khẩu và truy vấn Database để đối chiếu.

Nếu hợp lệ, hệ thống khởi tạo phiên làm việc và điều hướng vào trang chủ.

(Thực thi nhánh Extend): Giảng viên bấm Quên mật khẩu, nhập Email. Hệ thống tự động gửi link đặt lại mật khẩu.

Luồng ngoại lệ (Alternative Flow):

(A1) Sai thông tin: Nếu tài khoản hoặc mật khẩu không khớp, hệ thống chặn truy cập, bôi đỏ ô nhập liệu và báo lỗi "Thông tin đăng nhập không chính xác".

Hậu điều kiện: Xác thực thành công, giao diện mở khóa các chức năng tương ứng với quyền Giảng viên.

**b. Xem danh sách lớp học**

Mã Use Case: UC_GV_02

Tác nhân: Giảng viên

Mô tả: Giảng viên tra cứu danh sách các lớp học phần được phân công giảng dạy. Hỗ trợ các tính năng mở rộng như lọc theo học kỳ và tìm kiếm.

Tiền điều kiện: Giảng viên đã đăng nhập thành công vào hệ thống.

Luồng sự kiện chính (Basic Flow):

Giảng viên truy cập module Quản lý Lớp học.

Hệ thống mặc định hiển thị danh sách lớp của học kỳ hiện tại dưới dạng lưới dữ liệu (Grid/Table view).

(Thực thi nhánh Extend): Giảng viên thao tác Lọc lớp theo Học kỳ (chọn từ Dropdown).

(Thực thi nhánh Extend): Giảng viên nhập từ khóa vào ô Tìm kiếm tên lớp.

Hệ thống load lại dữ liệu và trả về kết quả tương ứng với bộ lọc.

Luồng ngoại lệ (Alternative Flow):

(A1) Không có dữ liệu: Nếu bộ lọc không khớp hoặc giảng viên không có lớp, lưới dữ liệu hiển thị trạng thái rỗng kèm thông báo "Không tìm thấy lớp học phần phù hợp".

Hậu điều kiện: Không làm thay đổi dữ liệu hệ thống.

**c. Xem danh sách sinh viên**

Mã Use Case: UC_GV_03

Tác nhân: Giảng viên

Mô tả: Giảng viên xem chi tiết hồ sơ thành viên của một lớp học cụ thể, hỗ trợ truy xuất nhanh và kết xuất dữ liệu.

Tiền điều kiện: Giảng viên đã đăng nhập và đang ở màn hình danh sách lớp học.

Luồng sự kiện chính (Basic Flow):

Từ danh sách lớp, Giảng viên chọn hành động Xem chi tiết tại một lớp cụ thể.

Hệ thống render danh sách sinh viên thuộc lớp đó (Mã SV, Họ tên, Ngày sinh, Tình trạng).

(Thực thi nhánh Extend): Giảng viên nhập text vào ô Tìm kiếm mã sinh viên để thu hẹp kết quả hiển thị.

(Thực thi nhánh Extend): Giảng viên bấm nút Xuất danh sách ra Excel. Hệ thống gọi API xuất file .xlsx tải xuống thiết bị.

Luồng ngoại lệ (Alternative Flow):

(A1) Lỗi kết xuất file: Nếu hệ thống quá tải, giao diện hiển thị thông báo "Xuất file thất bại, vui lòng thử lại".

Hậu điều kiện: Không.

**d. Nhập và cập nhật điểm**

Mã Use Case: UC_GV_04

Tác nhân: Giảng viên

Mô tả: Ghi nhận điểm số của sinh viên thông qua nhiều phương thức (nhập tay/import) và thực hiện nghiệp vụ chốt bảng điểm.

Tiền điều kiện: Thời gian hiện tại nằm trong khung thời gian cho phép nhập điểm của học kỳ.

Luồng sự kiện chính (Basic Flow):

Giảng viên điều hướng tới module Quản lý Điểm, chọn môn học và lớp tương ứng.

Hệ thống tải lên bảng điểm rỗng hoặc chứa dữ liệu cũ (trạng thái Unlocked).

(Thực thi nhánh Extend): Giảng viên chọn Nhập điểm thủ công (nhập trực tiếp trên các ô lưới dữ liệu).

(Thực thi nhánh Extend): Giảng viên chọn Import điểm từ Excel (tải file lên, hệ thống tự điền dữ liệu).

Bấm Lưu bảng điểm. Hệ thống kiểm tra và ghi nhận vào Database.

(Thực thi nhánh Extend): Giảng viên bấm Chốt bảng điểm sau khi đã rà soát kỹ.

Luồng ngoại lệ (Alternative Flow):

(A1) Dữ liệu không hợp lệ: Nhập ký tự sai định dạng (chữ cái vào ô số), hệ thống chặn thao tác lưu và bôi đỏ ô lỗi.

Hậu điều kiện: Nếu thực thi Chốt bảng điểm, hệ thống khóa quyền chỉnh sửa của Giảng viên đối với lớp này.

**e. Xem thời khóa biểu**

Mã Use Case: UC_GV_05

Tác nhân: Giảng viên

Mô tả: Cung cấp giao diện trực quan để Giảng viên theo dõi lịch giảng dạy cá nhân.

Tiền điều kiện: Giảng viên đã đăng nhập.

Luồng sự kiện chính (Basic Flow):

Giảng viên truy cập module Thời khóa biểu.

Hệ thống gọi API, đổ dữ liệu lịch học lên giao diện lịch (Calendar View).

(Thực thi nhánh Extend): Giảng viên sử dụng Toggle Switch để chuyển sang chế độ Xem theo Tuần.

(Thực thi nhánh Extend): Giảng viên sử dụng Toggle Switch để chuyển sang chế độ Xem theo Tháng.

Hệ thống sắp xếp lại các block lịch học tương ứng với View đã chọn.

Luồng ngoại lệ (Alternative Flow):

(A1) Trùng lịch hiển thị: Nếu có 2 ca học bị xếp trùng giờ, hệ thống hiển thị cảnh báo màu đỏ trên chính block thời gian đó.

Hậu điều kiện: Không.

**f. Xem thông báo**

Mã Use Case: UC_GV_06

Tác nhân: Giảng viên

Mô tả: Giảng viên tiếp nhận và tra cứu các thông báo từ Phòng Đào tạo hoặc hệ thống.

Tiền điều kiện: Giảng viên đã đăng nhập thành công.

Luồng sự kiện chính (Basic Flow):

Giảng viên truy cập module Thông báo hoặc bấm vào biểu tượng chuông trên thanh điều hướng.

Hệ thống load dữ liệu và hiển thị danh sách thông báo theo thứ tự mới nhất xếp trên.

Giảng viên click vào một thông báo cụ thể để xem chi tiết.

(Thực thi nhánh Extend): Giảng viên bấm nút Đánh dấu đã đọc cho thông báo.

Luồng ngoại lệ (Alternative Flow):

(A1) Không có dữ liệu: Màn hình hiển thị trạng thái rỗng kèm dòng text "Bạn không có thông báo nào mới".

Hậu điều kiện: Hệ thống cập nhật trạng thái thông báo từ Chưa đọc sang Đã đọc.

**g.Điểm danh sinh viên**

Mã Use Case: UC_GV_07

Tác nhân: Giảng viên

Mô tả: Nghiệp vụ quản lý chuyên cần của sinh viên thông qua các phương thức truyền thống hoặc tự động hóa.

Tiền điều kiện: Thời gian thao tác phải nằm trong giới hạn vật lý của ca học đó.

Luồng sự kiện chính (Basic Flow):

Giảng viên vào Quản lý Lớp học, chọn Điểm danh cho ca học hiện tại.

(Thực thi nhánh Extend): Giảng viên sử dụng Điểm danh gọi tên (tick trạng thái Có mặt/Vắng trên danh sách).

(Thực thi nhánh Extend): Giảng viên bấm Tạo QR Code điểm danh. Hệ thống sinh mã chiếu lên màn hình để sinh viên tự quét.

Bấm Lưu phiên điểm danh.

Luồng ngoại lệ (Alternative Flow):

(A1) Thao tác ngoài giờ: Nếu thao tác sau khi ca học kết thúc quá giờ, nút chức năng bị vô hiệu hóa kèm thông báo "Hết hạn cập nhật chuyên cần".

Hậu điều kiện: Dữ liệu chuyên cần được lưu trữ vào cơ sở dữ liệu hệ thống.

**h.Gửi ý kiến phản hồi**

Mã Use Case: UC_GV_08

Tác nhân: Giảng viên

Mô tả: Giảng viên tạo và gửi các phiếu yêu cầu hỗ trợ (Ticket) liên quan đến lỗi kỹ thuật hoặc nghiệp vụ lên Ban Quản trị.

Tiền điều kiện: Giảng viên đã đăng nhập thành công.

Luồng sự kiện chính (Basic Flow):

Giảng viên truy cập module Hỗ trợ & Phản hồi -> Chọn Tạo yêu cầu mới.

Hệ thống render biểu mẫu tạo phiếu hỗ trợ.

(Thực thi nhánh Extend): Giảng viên chọn Chủ đề từ danh sách (Lỗi hệ thống, Nghiệp vụ...).

Giảng viên nhập Tiêu đề và Nội dung chi tiết.

(Thực thi nhánh Extend): Giảng viên tải lên tệp Đính kèm minh chứng.

Chọn Gửi yêu cầu. Hệ thống validate dữ liệu.

Luồng ngoại lệ (Alternative Flow):

(A1) Thiếu thông tin bắt buộc: Nếu không chọn Chủ đề hoặc để trống Nội dung, hệ thống chặn gửi và báo đỏ.

Hậu điều kiện: Ticket được lưu vào hệ thống, sinh ra mã theo dõi và đặt trạng thái Chờ xử lý.

### 3.2.5 Phân tích usecase chung của sinh viên & giảng viên
#### 3.2.5.1 Phân rã usecase chung của sinh viên & giảng viên

**a. Đăng nhập**

<pre>
@startuml
left to right direction

actor "Người dùng\n(Giảng viên, Sinh viên)" as User

usecase "Đăng nhập" as login
usecase "Quên mật khẩu" as forgot_pwd

User --> login

login <.. forgot_pwd : <<extend>>
@enduml
</pre>

**b. Xem thời khóa biểu**

<pre>
@startuml
left to right direction

actor "Người dùng\n(Giảng viên, Sinh viên)" as User

usecase "Đăng nhập" as login
usecase "Xem thời khóa biểu" as xem_tkb
usecase "Xem theo Tuần" as tkb_tuan
usecase "Xem theo Tháng" as tkb_thang

User --> xem_tkb

xem_tkb ..> login : <<include>>

tkb_tuan .> xem_tkb : <<extend>>
tkb_thang .> xem_tkb : <<extend>>
@enduml
</pre>

**c. Xem thông báo**

<pre>
@startuml
left to right direction

actor "Người dùng\n(Giảng viên, Sinh viên)" as User

usecase "Đăng nhập" as login
usecase "Xem thông báo" as xem_tb
usecase "Đánh dấu đã đọc" as danh_dau

User --> xem_tb

xem_tb ..> login : <<include>>

danh_dau .> xem_tb : <<extend>>
@enduml
</pre>

**d. Gửi ý kiến phản hồi**

<pre>
@startuml
left to right direction

actor "Người dùng\n(Giảng viên, Sinh viên)" as User

usecase "Đăng nhập" as login
usecase "Gửi phiếu hỗ trợ (Ticket)" as gui_ticket
usecase "Chọn chủ đề (Lỗi/Nghiệp vụ)" as phan_loai
usecase "Đính kèm minh chứng" as dinh_kem

User --> gui_ticket

gui_ticket ..> login : <<include>>

phan_loai .> gui_ticket : <<extend>>
dinh_kem .> gui_ticket : <<extend>>

@enduml
</pre>

#### 3.2.5.2 Đặc tả usecase chung của sinh viên & giảng viên

**a. Đăng nhập**

Mã Use Case: UC_CHUNG_01

Tác nhân: Người dùng (Giảng viên, Sinh viên)

Mô tả: Người dùng thực hiện xác thực tài khoản để truy cập vào các chức năng của hệ thống. Hỗ trợ chức năng khôi phục mật khẩu khi cần thiết.

Tiền điều kiện: Người dùng đã được cấp tài khoản hợp lệ từ hệ thống nhà trường.

Luồng sự kiện chính (Basic Flow):

Người dùng truy cập vào trang Đăng nhập của hệ thống.

Điền thông tin vào trường Tên đăng nhập (Username) và Mật khẩu (Password).

Bấm nút Đăng nhập. Hệ thống mã hóa mật khẩu và truy vấn Database để đối chiếu.

Nếu hợp lệ, hệ thống khởi tạo phiên làm việc và điều hướng vào trang chủ tương ứng với quyền (Role) của người dùng.

(Thực thi nhánh Extend): Người dùng bấm Quên mật khẩu, nhập Email. Hệ thống tự động gửi link đặt lại mật khẩu.

Luồng ngoại lệ (Alternative Flow):

(A1) Sai thông tin: Nếu tài khoản hoặc mật khẩu không khớp, hệ thống chặn truy cập, bôi đỏ ô nhập liệu và báo lỗi "Thông tin đăng nhập không chính xác".

Hậu điều kiện: Xác thực thành công, giao diện mở khóa các chức năng phân quyền.

**b. Xem thời khóa biểu**

Mã Use Case: UC_CHUNG_02

Tác nhân: Người dùng (Giảng viên, Sinh viên)

Mô tả: Cung cấp giao diện trực quan để Sinh viên theo dõi lịch học và Giảng viên theo dõi lịch giảng dạy cá nhân.

Tiền điều kiện: Người dùng đã đăng nhập thành công.

Luồng sự kiện chính (Basic Flow):

Người dùng truy cập module Thời khóa biểu.

Hệ thống gọi API, ánh xạ dữ liệu lịch học/giảng dạy lên giao diện lịch (Calendar View).

(Thực thi nhánh Extend): Người dùng sử dụng Toggle Switch để chuyển sang chế độ Xem theo Tuần.

(Thực thi nhánh Extend): Người dùng sử dụng Toggle Switch để chuyển sang chế độ Xem theo Tháng.

Hệ thống sắp xếp lại các block lịch tương ứng với View đã chọn.

Luồng ngoại lệ (Alternative Flow):

(A1) Trùng lịch hiển thị: Nếu có 2 ca bị xếp trùng giờ, hệ thống hiển thị cảnh báo màu đỏ trên chính block thời gian đó.

Hậu điều kiện: Không.

**c. Xem thông báo**

Mã Use Case: UC_CHUNG_03

Tác nhân: Người dùng (Giảng viên, Sinh viên)

Mô tả: Người dùng tiếp nhận và tra cứu các thông báo từ Phòng Đào tạo hoặc hệ thống.

Tiền điều kiện: Người dùng đã đăng nhập thành công.

Luồng sự kiện chính (Basic Flow):

Người dùng truy cập module Thông báo hoặc bấm vào biểu tượng chuông trên thanh điều hướng.

Hệ thống load dữ liệu và hiển thị danh sách thông báo theo thứ tự mới nhất xếp trên.

Người dùng click vào một thông báo cụ thể để xem chi tiết.

(Thực thi nhánh Extend): Người dùng bấm nút Đánh dấu đã đọc cho thông báo.

Luồng ngoại lệ (Alternative Flow):

(A1) Không có dữ liệu: Màn hình hiển thị trạng thái rỗng kèm dòng text "Bạn không có thông báo nào mới".

Hậu điều kiện: Hệ thống cập nhật trạng thái thông báo từ Chưa đọc sang Đã đọc.

**d. Gửi ý kiến phản hồi**

Mã Use Case: UC_CHUNG_04

Tác nhân: Người dùng (Giảng viên, Sinh viên)

Mô tả: Người dùng tạo và gửi các phiếu yêu cầu hỗ trợ (Ticket) liên quan đến lỗi kỹ thuật hoặc nghiệp vụ lên Ban Quản trị.

Tiền điều kiện: Người dùng đã đăng nhập thành công.

Luồng sự kiện chính (Basic Flow):

Người dùng truy cập module Hỗ trợ & Phản hồi -> Chọn Tạo yêu cầu mới.

Hệ thống render biểu mẫu tạo phiếu hỗ trợ.

(Thực thi nhánh Extend): Người dùng chọn Chủ đề từ danh sách (Lỗi hệ thống, Nghiệp vụ, Xin cấp quyền...).

Người dùng nhập Tiêu đề và Nội dung chi tiết.

(Thực thi nhánh Extend): Người dùng tải lên tệp Đính kèm minh chứng.

Chọn Gửi yêu cầu. Hệ thống validate dữ liệu.

Luồng ngoại lệ (Alternative Flow):

(A1) Thiếu thông tin bắt buộc: Nếu không chọn Chủ đề hoặc để trống Nội dung, hệ thống chặn gửi và báo đỏ.

Hậu điều kiện: Ticket được lưu vào hệ thống, sinh ra mã theo dõi và đặt trạng thái Chờ xử lý.