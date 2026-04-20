# PHẦN MỀM QUẢN LÝ SINH VIÊN
## TÀI LIỆU MÔ TẢ THIẾT KẾ PHẦN MỀM
### Đề tài: Quản lý sinh viên

<br>

**Giảng viên hướng dẫn:** Ngô Ngọc Anh  
**Sinh viên thực hiện:** Đặng Văn Tùng Dương, Phạm Thành Vinh  

<br>

_Hà Nội, 12/04/2026_

---

# 1. Mục lục

---

# 2. Danh mục hình ảnh

---

# 3. Danh mục bảng biểu

---

# 4. Bảng phân công nhiệm vụ

| STT | Họ và tên              | Nhiệm vụ           |
|-----|-----------------------|--------------------|
| 1   | Đặng Văn Tùng Dương   | Lập trình          |
| 2   | Phạm Thành Vinh       | Thiết kế giao diện |

## I. Giới thiệu
### 1.1 Mục đích tài liệu
 Tài liệu này mô tả và phân tích chi tiết thiết kế của hệ thống Quản lý sinh viên, bao gồm kiến trúc, các thành phần chức năng và phương án triển khai. Đây là cơ sở nòng cốt để hỗ trợ nhóm phát triển trong quá trình xây dựng và hoàn thiện phần mềm một cách hiệu quả nhất.
### 1.2 Phạm vi tài liệu
Tài liệu được sử dụng làm cơ sở trao đổi giữa các thành viên trong nhóm phát triển, phục vụ trực tiếp các công đoạn: lập trình, kiểm thử, triển khai, vận hành và bảo trì hệ thống sau này.
### 1.3 Thuật ngữ và các từ viết tắt
Mục này trình bày các thuật ngữ chuyên ngành, từ viết tắt và các khái niệm được sử dụng trong tài liệu. Việc định nghĩa rõ ràng giúp đảm bảo tất cả người đọc ( như lập trình viên, tester, quản lý dự án,...) hiểu thống nhất nội dung, tránh nhầm lẫn trong quá trình phát triển và triển khai hệ thống.

| Thuật ngữ | Định nghĩa | Giải thích |
| :--- | :--- | :--- |
| **CNTT** | Công nghệ thông tin | |
| **CNPM** | Công nghệ phần mềm | |
| **PTTKH** | Phân tích thiết kế hệ thống | |
| **CSDL** | Cơ sở dữ liệu | Nơi lưu trữ, quản lý và truy xuất thông tin của hệ thống. |
| **PK** | Primary Key | Khóa chính,|
| **UK** | Unique Key | Khóa duy nhất |
| **ERD** | Entity-Relationship Diagram | Sơ đồ thực thể kết hợp |
| **CRUD** | Create, Read, Update, Delete | Bốn thao tác nền tảng trong việc tương tác và xử lý dữ liệu. |
| **API** | Application Programming Interface | Cổng giao tiếp dữ liệu giữa các thành phần hệ thống. |
| **UML** | Unified Modeling Language | Ngôn ngữ mô hình hóa thống nhất. |
| **SDD** | Software Design Document | Tài liệu đặc tả thiết kế phần mềm dùng cho programmers, testers, maintainers, systems integrators, vv.. |
## II. Khảo sát hiện trạng phần mềm
### 2.1 Khảo sát hiện trạng
Hiện nay, phần lớn quy trình đăng ký môn học và quản lý đào tạo tại nhà trường đang được vận hành trên nền tảng kiến trúc cũ. Khi đến đợt đăng ký môn học hoặc đóng học phí, sinh viên phải truy cập vào cổng thông tin nội bộ. Mọi thao tác từ tra cứu lịch học, chọn lớp đến theo dõi công nợ đều thực hiện trên các giao diện dạng bảng biểu tĩnh, thiếu sự liên kết dữ liệu giữa các phân hệ.

Phương pháp quản lý này cơ bản đáp ứng được luồng nghiệp vụ truyền thống nhưng bộc lộ nhiều điểm nghẽn nghiêm trọng trong thời đại phát triển công nghệ hiện nay :
* **Hạn chế trong quản lý dữ liệu học tập:** Hiện nay, quy trình quản lý điểm số và điểm danh vẫn chưa được số hóa hoàn toàn. Phần lớn Giảng viên vẫn thực hiện điểm danh và nhập điểm thủ công qua các tệp tin rời rạc như **Excel, PDF**. Việc lưu trữ dữ liệu phân mảnh này không chỉ gây khó khăn cho việc tra cứu của Sinh viên mà còn tiềm ẩn rủi ro sai sót, thiếu đồng bộ khi tổng hợp dữ liệu cuối kỳ.
* **Hạn chế giao diện và thời khóa biểu:** Đăng ký môn học mang tính liệt kê thụ động. Hệ thống không trực quan hóa thời khóa biểu thành dạng lưới (Grid/Calendar View), khiến sinh viên gặp khó khăn trong việc rà soát xung đột giữa các môn học và không có được sự quản lý thời gian của cá nhân.
* **Điểm nghẽn thanh toán:** Luồng đóng học phí bị khóa cứng, bắt buộc định tuyến qua một cổng ngân hàng duy nhất (BIDV). Quá trình đối soát mất thời gian và gây bất tiện cho sinh viên sử dụng các ngân hàng khác khi muốn chuyển khoản qua.
* **Khuyết thiếu kênh thông báo:** Không có hệ thống cảnh báo tự động (Push Notification/Email). Sinh viên thường xuyên bỏ lỡ các mốc thời gian quan trọng (mở cổng đăng ký, hạn chót nộp học phí, lịch thi cá nhân,...) do phải tự truy cập web để kiểm tra thủ công.
* **Hiệu suất kém:** Thường xuyên xảy ra tình trạng quá tải, sập máy chủ khi lưu lượng sinh viên truy cập đồng thời tăng đột biến ở các trường đại học lớn.

Quy trình quản lý học tập hiện tại diễn ra qua các bước tương tác sau:

* **Bước 1 Cấu hình:** Quản trị viên (Phòng Đào tạo) thiết lập danh mục môn học, định mức học phí và thông báo lịch mở cổng đăng ký trên website.
* **Bước 2 Thực thi đăng ký:** Sinh viên đăng nhập, tra cứu mã môn và thực hiện đăng ký. Do thiếu công cụ trực quan, sinh viên phải tự nháp/ghi chú thời khóa biểu bên ngoài để kiểm soát xung đột lịch học. Quá trình này thường xuyên xảy ra tình trạng nghẽn mạng (bottleneck).
* **Bước 3 Xác nhận & Chờ đợi:** Sau khi kết thúc đợt đăng ký, hệ thống chốt danh sách và cập nhật định mức học phí vào tài khoản cá nhân. Sinh viên hoàn toàn thụ động ở bước này do không có hệ thống thông báo tự động (Email/Push) khi có thông báo phải đóng tiền.
* **Bước 4 Thanh toán & Đối soát:** Sinh viên nộp tiền vào tài khoản ngân hàng liên kết (độc quyền qua BIDV) để hệ thống thực hiện quét nợ tự động. Tuy nhiên, quy trình này tồn tại hai hạn chế lớn: Một là sự thiếu linh hoạt trong phương thức thanh toán khi bị ràng buộc vào một ngân hàng duy nhất; hai là thiếu cơ chế phản hồi tức thời. Hệ thống không tự động gửi email xác nhận hay biên lai điện tử, khiến sinh viên không có bằng chứng giao dịch ngay lập tức để đối soát hoặc thực hiện các thủ tục hành chính liên quan.

Do đó, việc tái thiết kế hệ thống Quản lý sinh viên trên một nền tảng kiến trúc hiện đại là yêu cầu cấp thiết. Giải pháp mới cần tập trung vào việc tự động hóa luồng nghiệp vụ, trực quan hóa dữ liệu lịch học và tối ưu hóa năng lực chịu tải nhằm đảm bảo tính ổn định của hạ tầng

### 2.2 Mô tả yêu cầu hệ thống mới
Sau khi tiến hành khảo sát thực tế và phân tích những bất cập của hệ thống cũ, nhóm chúng em đã nghiên cứu và rút ra được các yêu cầu cần thiết cho hệ thống mới. Để đảm bảo vận hành trơn tru, hệ thống sẽ được chia thành nhiều phân hệ chức năng chuyên biệt, hỗ trợ tối đa cho cả ba đối tượng: Quản trị viên (Admin), Giảng viên và Sinh viên.

* **Quản lý Đăng ký môn học & Thời khóa biểu:** Cho phép sinh viên xem danh sách môn, thực hiện thêm hoặc hủy đăng ký. Tính năng đột phá là hệ thống tự động ánh xạ môn học vừa chọn lên **Thời khóa biểu dạng lưới (Calendar View)** theo thời gian thực. Hệ thống sẽ cảnh báo ngay lập tức nếu xảy ra tình trạng trùng lịch, giúp sinh viên chủ động hoàn toàn trong việc quản lý thời gian học tập.
* **Quản lý Điểm số & Điểm danh trực tuyến:** Số hóa hoàn toàn nghiệp vụ của Giảng viên. Giao diện cấp quyền cho Giảng viên nhập điểm, điểm danh trực tiếp qua lưới dữ liệu trên web (hoặc import hàng loạt chuẩn hóa từ file Excel). Sinh viên lập tức được cập nhật tra cứu bảng điểm và nhận cảnh báo (Warning) nếu số buổi nghỉ học vượt quá quy định.
* **Quản lý Thanh toán (Payment):** Cung cấp các chức năng xem công nợ, lựa chọn phương thức thanh toán và xuất biên lai số. Hệ thống tích hợp đa dạng các cổng thanh toán qua **API (VNPay, MoMo, Open Banking)** thay vì độc quyền một ngân hàng. Cơ chế tự động gạch nợ giúp cập nhật trạng thái tài chính của sinh viên ngay khi giao dịch thành công.
* **Quản lý Thông báo (Notification):** Cung cấp công cụ cho Quản trị viên khởi tạo và quản lý các chiến dịch truyền thông. Hệ thống hỗ trợ tự động gửi **Email (Email Automation)** và **Thông báo đẩy (Push Notification)** trực tiếp đến tài khoản sinh viên trước các mốc thời gian quan trọng như hạn chót học phí hoặc lịch thi.
* **Quản lý Sinh viên & Người dùng:** Cho phép Quản trị viên thực hiện các thao tác quản lý hồ sơ (CRUD). Sinh viên có quyền chủ động cập nhật thông tin cá nhân trên hệ thống. Mọi dữ liệu được đồng bộ tức thời giữa các phân hệ, đảm bảo tính nhất quán và chính xác của thông tin người dùng.
* **Quản lý Danh mục đào tạo:** Quản trị viên thao tác trực tiếp với cơ sở dữ liệu về môn học, khoa, viện và phòng học. Hệ thống cung cấp giao diện quản trị **(Admin Dashboard)** hiển thị các báo cáo thống kê trực quan về số lượng sinh viên đăng ký và doanh thu học phí theo thời gian thực.

### 2.3 Ràng buộc thiết kế
Để đảm bảo hệ thống vận hành ổn định và có khả năng mở rộng tốt, nhóm chúng em tuân thủ các ràng buộc kỹ thuật sau:

* **Ngôn ngữ lập trình & Framework:** Phát triển theo mô hình Web App (Monolithic). Backend sử dụng ngôn ngữ **PHP** (Framework **Laravel**) đáp ứng khả năng phân luồng và bảo mật cao. Giao diện Frontend được xây dựng chuẩn hóa bằng **HTML5, CSS3, JavaScript** kết hợp các thư viện UI hiện đại.
* **Cơ sở dữ liệu (Database):** Hệ thống vận hành trên nền tảng **SQL Server**, đảm bảo khả năng lưu trữ dữ liệu lớn, tính toàn vẹn và bảo mật cao cho toàn bộ thông tin sinh viên, điểm số và học phí.
* **Tích hợp dịch vụ bên ngoài:** Triển khai kết nối đa nền tảng qua **RESTful API**, tích hợp giải pháp **SePay/VietQR** để tự động gạch nợ tức thời qua **Webhooks** và sử dụng **SMTP Server** để thực hiện chiến dịch **Email Automation** gửi biên lai số tự động.
* **An toàn và Bảo mật:** Triển khai chứng chỉ **SSL (HTTPS)** cho toàn bộ hệ thống. Áp dụng mô hình phân quyền **RBAC (Role-Based Access Control)** để kiểm soát quyền truy cập chặt chẽ giữa Admin, Giảng viên và Sinh viên.
* **Hiệu suất & Khả năng sử dụng:** Tối ưu hóa truy vấn dữ liệu để hệ thống hoạt động mượt mà ngay cả khi có lượng truy cập lớn. Giao diện thiết kế theo chuẩn **Responsive**, tương thích hoàn toàn trên cả máy tính và thiết bị di động.

## III. Phân tích thiết kế hệ thống
### 3.1 Phân tích actor và use case có trong hệ thống
  
##### 3.1.1 Admin
- Quản lý sinh viên  
- Quản lý giảng viên  
- Quản lý lớp học  
- Quản lý môn học  
- Quản lý điểm  
- Quản lý học phí  
- Quản lý thời khóa biểu
- Quản lý thông báo  
- Quản lý người dùng 
- Quản lý ý kiến phản hồi
- Cấu hình hệ thống

---

##### 3.1.2 Giảng viên
- Xem danh sách lớp học  
- Xem danh sách sinh viên  
- Nhập và cập nhật điểm  
- Xem thời khóa biểu  
- Xem thông báo  
- Điểm danh sinh viên
- Gửi ý kiến phản hồi

---

##### 3.1.3 Sinh viên
- Xem thời khóa biểu  
- Xem bảng điểm  
- Xem thông báo  
- Cập nhật thông tin cá nhân  
- Xem và thanh toán học phí  
- Đăng ký môn học  
- Hủy đăng ký môn học  
- Xem danh sách môn học 
- Xem kết quả điểm danh 
- Đóng góp ý kiến

 ### 3.2 Phân tích use case chi tiết

 #### 3.2.1 Usecase tổng quan
@startuml
left to right direction
skinparam packageStyle rectangle

actor "Sinh viên (User)" as SV
actor "Giảng viên" as GV
actor "Quản trị" as Admin

SV --> (Đăng nhập)
GV --> (Đăng nhập)
Admin --> (Đăng nhập)

rectangle "Phần mềm Của Sinh Viên" {
  usecase "Cập nhật thông tin cá nhân" as SV0
  usecase "Xem thời khóa biểu" as SV1
  usecase "Xem bảng điểm" as SV2
  usecase "Xem thông báo" as SV3
  usecase "Thanh toán học phí" as SV4
  usecase "Đăng ký môn học" as SV5
  usecase "Xem danh sách môn học" as SV6
  usecase "Xem kết quả điểm danh" as SV7
}

rectangle "Phần mềm Của Giảng Viên" {
  usecase "Xem danh sách lớp học" as GV1
  usecase "Xem danh sách sinh viên" as GV2
  usecase "Nhập và cập nhật điểm" as GV3
  usecase "Xem thời khóa biểu" as GV4
  usecase "Xem thông báo" as GV5
  usecase "Điểm danh sinh viên" as GV6
}

rectangle "Phần Mềm Quản Trị Hệ Thống" {
  usecase "Quản lý sinh viên" as AD1
  usecase "Quản lý giảng viên" as AD2
  usecase "Quản lý môn học" as AD3
  usecase "Quản lý điểm" as AD4
  usecase "Quản lý thanh toán học phí" as AD5
  usecase "Quản lý thời khóa biểu" as AD6
  usecase "Quản lý thông báo" as AD7
  usecase "Quản lý tài khoản" as AD8
}

SV --> SV0
SV --> SV1
SV --> SV2
SV --> SV3
SV --> SV4
SV --> SV5
SV --> SV6
SV --> SV7

GV --> GV1
GV --> GV2
GV --> GV3
GV --> GV4
GV --> GV5
GV --> GV6

Admin --> AD1
Admin --> AD2
Admin --> AD3
Admin --> AD4
Admin --> AD5
Admin --> AD6
Admin --> AD7
Admin --> AD8

(SV0) ..> (Đăng nhập) : <<include>>
(SV1) ..> (Đăng nhập) : <<include>>
(SV2) ..> (Đăng nhập) : <<include>>
(SV3) ..> (Đăng nhập) : <<include>>
(SV4) ..> (Đăng nhập) : <<include>>
(SV5) ..> (Đăng nhập) : <<include>>
(SV6) ..> (Đăng nhập) : <<include>>
(SV7) ..> (Đăng nhập) : <<include>>

(GV1) ..> (Đăng nhập) : <<include>>
(GV2) ..> (Đăng nhập) : <<include>>
(GV3) ..> (Đăng nhập) : <<include>>
(GV4) ..> (Đăng nhập) : <<include>>
(GV5) ..> (Đăng nhập) : <<include>>
(GV6) ..> (Đăng nhập) : <<include>>

(AD1) ..> (Đăng nhập) : <<include>>
(AD2) ..> (Đăng nhập) : <<include>>
(AD3) ..> (Đăng nhập) : <<include>>
(AD4) ..> (Đăng nhập) : <<include>>
(AD5) ..> (Đăng nhập) : <<include>>
(AD6) ..> (Đăng nhập) : <<include>>
(AD7) ..> (Đăng nhập) : <<include>>
(AD8) ..> (Đăng nhập) : <<include>>

@enduml
 #### 3.2.2 Usercase sinh viên
<pre>
@startuml
left to right direction
skinparam packageStyle rectangle

actor "Sinh Viên" as SV

rectangle "Hệ thống Quản lý Sinh viên" {
  usecase "Gửi ý kiến phản hồi" as UC1
  usecase "Xem kết quả điểm danh" as UC2
  usecase "Xem danh sách môn học" as UC3
  usecase "Cập nhật thông tin cá nhân" as UC4
  usecase "Hủy môn học" as UC5
  usecase "Đăng ký môn học" as UC6
  usecase "Xem học phí\n--\nextension points\nĐóng học phí" as UC7
  usecase "Đóng học phí" as UC8
  usecase "Xem thời khóa biểu" as UC9
  usecase "Xem bảng điểm" as UC10

  SV -- UC1
  SV -- UC2
  SV -- UC3
  SV -- UC4
  SV -- UC5
  SV -- UC7
  SV -- UC9
  SV -- UC10

  UC5 ..> UC6 : <<Include>>
  UC8 ..> UC7 : <<Extend>>
}
@enduml
</pre>
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
#### 3.2.3 Usercase giảng viên
@startuml
left to right direction
skinparam packageStyle rectangle

actor "Giảng viên" as gv

rectangle "Hệ thống Quản lý Đào tạo" {
    
    ' Nhóm thông tin chung
    package "Thông tin & Tra cứu" {
        usecase "Xem thời khóa biểu" as UC_Schedule
        usecase "Xem thông báo" as UC_Notif
    }

    ' Nhóm quản lý lớp học
    package "Quản lý Lớp & Sinh viên" {
        usecase "Xem danh sách lớp" as UC_ClassList
        usecase "Xem danh sách sinh viên" as UC_StudentList
    }

    ' Nhóm nghiệp vụ giảng dạy
    package "Nghiệp vụ Giảng dạy" {
        usecase "Điểm danh sinh viên" as UC_Attendance
        usecase "Nhập và cập nhật điểm" as UC_Grades
    }

    ' Kết nối Giảng viên với các Use Case chính
    gv -- UC_Schedule
    gv -- UC_Notif
    gv -- UC_ClassList
    gv -- UC_StudentList
    gv -- UC_Attendance
    gv -- UC_Grades
}

@enduml
#### 3.2.4 Usercase admin
<pre>
@startuml
left to right direction
skinparam packageStyle rectangle

actor "Admin" as admin

rectangle "Hệ thống Quản lý Đào tạo" {
    
    ' Nhóm quản lý đối tượng (Master Data)
    package "Quản lý Người dùng & Đối tượng" {
        usecase "Quản lý Người dùng" as UC_Users
        usecase "Quản lý Sinh viên" as UC_Students
        usecase "Quản lý Giảng viên" as UC_Faculty
    }

    ' Nhóm quản lý nghiệp vụ đào tạo
    package "Quản lý Đào tạo" {
        usecase "Quản lý Môn học & Lớp học" as UC_Course
        usecase "Quản lý Thời khóa biểu" as UC_Schedule
        usecase "Quản lý Điểm số" as UC_Grades
    }

    ' Nhóm quản lý vận hành
    package "Vận hành & Hệ thống" {
        usecase "Quản lý Học phí" as UC_Fees
        usecase "Quản lý Thông báo & Phản hồi" as UC_Comm
        usecase "Cấu hình hệ thống" as UC_Config
    }

    ' Kết nối Admin với các Use Case chính
    admin -- UC_Users
    admin -- UC_Students
    admin -- UC_Faculty
    admin -- UC_Course
    admin -- UC_Schedule
    admin -- UC_Grades
    admin -- UC_Fees
    admin -- UC_Comm
    admin -- UC_Config
}

@enduml
</pre>
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
### 3.3 Sequence

### 3.4 Activity
## IV. Thiết kế CSDL

## V. Thiết kế giao diện

## VI. Cài đặt và thử nghiệ