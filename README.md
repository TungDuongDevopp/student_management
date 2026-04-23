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
 <pre>
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
</pre>
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

c. Usecase xem kết quả điểm danh
<pre>
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
    1.Hệ thống thông báo lỗi trùng lịch
    2. Sinh viên chọn đăng ký môn mới
    3. Hệ thống thông báo thành công
    A2. Ngoài thời gian đăng ký môn học
    1. Hệ thống thông báo lỗi ngoài thời gian đăng ký môn học

b. Đặc tả usecase thanh toán học phí
-Tên usecase: Thanh toán học phí
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
<pre>
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
</pre>
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

' Sau khi nhap thay/co co the chot lai
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

' Khởi tạo tác nhân Giảng viên
actor "Giảng viên" as GV

' Khai báo khối chức năng Đăng nhập
usecase "Đăng nhập" as login

' Khai báo cụm chức năng Ticket
usecase "Gửi phiếu hỗ trợ (Ticket)" as gui_ticket
usecase "Chọn chủ đề (Lỗi/Nghiệp vụ)" as phan_loai
usecase "Đính kèm minh chứng" as dinh_kem

' Liên kết Giảng viên với hành động gửi
GV --> gui_ticket

' Ràng buộc bắt buộc phải đăng nhập
gui_ticket ..> login : <<include>>

' Khai báo các tính năng mở rộng khi điền Form
phan_loai .> gui_ticket : <<extend>>
dinh_kem .> gui_ticket : <<extend>>

@enduml
</pre>
#### 3.2.3.2 Đặc tả usecase của giảng viên 
<pre>
a. Đăng nhập
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

b. Xem danh sách lớp học

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

c. Xem danh sách sinh viên

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

d. Nhập và cập nhật điểm

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

e. Xem thời khóa biểu

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

f. Xem thông báo

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

g.Điểm danh sinh viên

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
</pre>
#### 3.2.4 Usercase admin
<pre>
@startuml
left to right direction
skinparam packageStyle rectangle

actor "Admin" as admin

rectangle "Hệ thống Quản lý Đào tạo" {
    
    ' Nhóm quản lý đối tượng 
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
    + Cập nhật dữ liệu
    + Xóa dữ liệu
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
    A2. Dữ liệu không hợp lệ
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
### 3.2.5 Phân tích usecase chung của sinh viên & giảng viên
#### 3.2.5.1 Phân rã usecase chung của sinh viên & giảng viên

a. Đăng nhập

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

b. Xem thời khóa biểu

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

c. Xem thông báo

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

d. Gửi ý kiến phản hồi

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
<pre>
a. Đăng nhập

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

b. Xem thời khóa biểu

Mã Use Case: UC_CHUNG_02

Tác nhân: Người dùng (Giảng viên, Sinh viên)

Mô tả: Cung cấp giao diện trực quan để Sinh viên theo dõi lịch học và Giảng viên theo dõi lịch giảng dạy cá nhân.

Tiền điều kiện: Người dùng đã đăng nhập thành công.

Luồng sự kiện chính (Basic Flow):

Người dùng truy cập module Thời khóa biểu.

Hệ thống gọi API, đổ dữ liệu lịch học/giảng dạy lên giao diện lịch (Calendar View).

(Thực thi nhánh Extend): Người dùng sử dụng Toggle Switch để chuyển sang chế độ Xem theo Tuần.

(Thực thi nhánh Extend): Người dùng sử dụng Toggle Switch để chuyển sang chế độ Xem theo Tháng.

Hệ thống sắp xếp lại các block lịch tương ứng với View đã chọn.

Luồng ngoại lệ (Alternative Flow):

(A1) Trùng lịch hiển thị: Nếu có 2 ca bị xếp trùng giờ, hệ thống hiển thị cảnh báo màu đỏ trên chính block thời gian đó.

Hậu điều kiện: Không.

c. Xem thông báo

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

d. Gửi ý kiến phản hồi

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
</pre> 

### 3.3 Sequence

#### 3.3.1 Một số sơ đồ sequence usecase cốt lõi của sinh viên
<pre>
a. Sequence đăng ký môn học
@startuml
actor "Sinh viên" as S
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

S -> H: Chọn "Đăng ký môn học"
H -> DB: Lấy danh sách môn học
DB --> H: Danh sách môn
H --> S: Hiển thị danh sách

S -> H: Chọn môn học

H -> DB: Kiểm tra điều kiện + chỗ + lịch + tín chỉ
DB --> H: Kết quả kiểm tra

alt Hợp lệ
  H -> DB: Lưu đăng ký
  DB --> H: OK
  H --> S: Thông báo thành công
else Không hợp lệ
  H --> S: Thông báo lỗi
end

@enduml
b. Sequence thanh toán học phí

@startuml
actor "Sinh viên" as S
participant "Hệ thống" as H
participant "Cổng thanh toán" as C
participant "Ngân hàng" as N
participant "Cơ sở dữ liệu" as DB

S -> H: Chọn "Thanh toán học phí"
H -> DB: Lấy thông tin học phí
DB --> H: Dữ liệu học phí

alt Có khoản nợ
  S -> H: Chọn thanh toán
  H -> C: Tạo yêu cầu thanh toán
  C -> N: Xử lý giao dịch
  N --> C: Kết quả

  alt Thành công
    C --> H: Thành công
    H -> DB: Cập nhật trạng thái
    H --> S: Thông báo thành công
  else Thất bại
    C --> H: Thất bại
    H --> S: Thông báo lỗi
  end

else Không có nợ
  H --> S: Thông báo
end

@enduml
c. Sequence xem kết quả điểm danh
@startuml
actor "Sinh viên" as S
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

S -> H: Xem kết quả điểm danh
H --> S: Hiển thị danh sách môn học đã đăng ký
S -> H: Chọn môn học
H -> DB: Lấy dữ liệu điểm danh môn học đó
DB --> H: Dữ liệu

alt Có dữ liệu
  H --> S: Hiển thị chi tiết kết quả
else Không có
  H --> S: Thông báo chưa có dữ liệu điểm danh
end

@enduml

d. Sequence xem bảng điểm
@startuml
actor "Sinh viên" as S
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

S -> H: Xem bảng điểm
H -> DB: Lấy dữ liệu bảng điểm
DB --> H: Dữ liệu

alt Có dữ liệu
  H --> S: Hiển thị bảng điểm + GPA
else Không có
  H --> S: Thông báo
end

@enduml
</pre>
#### 3.3.2 Một số sơ đồ sequence usecase cốt lõi của giảng viên 
<pre>
a. Sequence điểm danh sinh viên
@startuml
actor "Giảng viên" as GV
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

GV -> H: Chọn "Điểm danh sinh viên"
H -> DB: Lấy danh sách sinh viên theo ca học
DB --> H: Danh sách sinh viên
H --> GV: Hiển thị danh sách điểm danh

GV -> H: Nhập thông tin điểm danh (Có mặt/Vắng)
H -> DB: Kiểm tra thời gian điểm danh

alt Hợp lệ (Trong ca học)
  H -> DB: Lưu kết quả chuyên cần
  DB --> H: OK
  H --> GV: Thông báo lưu thành công
else Không hợp lệ (Hết hạn)
  H --> GV: Thông báo lỗi
end

@enduml
b. Sequence nhập và cập nhật điểm

@startuml
actor "Giảng viên" as GV
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

GV -> H: Chọn "Nhập điểm"
H -> DB: Lấy danh sách sinh viên & bảng điểm
DB --> H: Dữ liệu bảng điểm
H --> GV: Hiển thị bảng điểm

GV -> H: Điền điểm số
H -> DB: Kiểm tra định dạng điểm & trạng thái chốt
DB --> H: Kết quả kiểm tra

alt Hợp lệ (Điểm 0-10, bảng điểm chưa chốt)
  H -> DB: Lưu bảng điểm
  DB --> H: OK
  H --> GV: Thông báo thành công
else Không hợp lệ
  H --> GV: Thông báo lỗi (Sai định dạng/Đã khóa)
end

@enduml
c. Sequence xem danh sách lớp và sinh viên


@startuml
actor "Giảng viên" as GV
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

GV -> H: Xem danh sách lớp học
H -> DB: Lấy dữ liệu lớp được phân công
DB --> H: Dữ liệu lớp

alt Có lớp giảng dạy
  H --> GV: Hiển thị danh sách lớp
  GV -> H: Chọn xem chi tiết một lớp
  H -> DB: Lấy danh sách sinh viên của lớp
  DB --> H: Dữ liệu sinh viên
  H --> GV: Hiển thị danh sách sinh viên
else Không có lớp
  H --> GV: Thông báo không có dữ liệu
end

@enduml
d. Sequence xem thời khóa biểu giảng dạy

@startuml
actor "Giảng viên" as GV
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

GV -> H: Xem thời khóa biểu
H -> DB: Lấy dữ liệu lịch giảng dạy
DB --> H: Dữ liệu lịch

alt Có lịch dạy
  H --> GV: Hiển thị thời khóa biểu (theo tuần/tháng)
else Không có lịch dạy
  H --> GV: Thông báo lịch trống
end

@enduml
</pre>
#### 3.3.3 Một số sơ đồ sequence usecase cốt lõi của admin
<pre>
a. Sequence quản lý tài khoản (Thêm mới Giảng viên/Sinh viên)

@startuml
actor "Admin" as AD
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

AD -> H: Chọn "Thêm tài khoản mới"
H --> AD: Hiển thị form nhập thông tin
AD -> H: Điền thông tin (Mã số, Họ tên, Role, Email...)
H -> DB: Kiểm tra Mã số & Email đã tồn tại chưa
DB --> H: Kết quả kiểm tra

alt Hợp lệ (Chưa tồn tại)
  H -> DB: Khởi tạo tài khoản & phân quyền
  DB --> H: Lưu thành công
  H --> AD: Thông báo "Tạo tài khoản thành công"
else Không hợp lệ (Bị trùng)
  H --> AD: Thông báo lỗi "Mã số hoặc Email đã tồn tại"
end

@enduml
b. Sequence quản lý lớp học phần (Tạo lớp mới)


@startuml
actor "Admin" as AD
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

AD -> H: Chọn "Quản lý lớp học phần" -> "Thêm mới"
H --> AD: Hiển thị form tạo lớp
AD -> H: Nhập dữ liệu (Mã lớp, Môn học, Sĩ số, Học kỳ...)
H -> DB: Kiểm tra tính hợp lệ của Mã lớp
DB --> H: Kết quả kiểm tra

alt Hợp lệ
  H -> DB: Lưu thông tin lớp học phần
  DB --> H: OK
  H --> AD: Thông báo "Thêm lớp học phần thành công"
else Trùng Mã lớp
  H --> AD: Thông báo lỗi "Mã lớp đã tồn tại trong hệ thống"
end

@enduml
c. Sequence phân công giảng dạy


@startuml
actor "Admin" as AD
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

AD -> H: Chọn "Phân công giảng dạy"
H -> DB: Lấy danh sách Lớp học phần & Giảng viên
DB --> H: Dữ liệu
H --> AD: Hiển thị giao diện phân công

AD -> H: Chọn Giảng viên gắn vào Lớp học phần cụ thể
H -> DB: Kiểm tra lịch giảng dạy (Tránh trùng giờ)
DB --> H: Kết quả kiểm tra lịch

alt Không bị trùng lịch
  H -> DB: Cập nhật phân công giảng dạy
  DB --> H: OK
  H --> AD: Thông báo "Phân công thành công"
else Bị trùng lịch
  H --> AD: Thông báo lỗi "Giảng viên bị trùng lịch dạy"
end

@enduml

d. Sequence quản lý học phí sinh viên
@startuml
actor "Admin" as AD
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

AD -> H: Chọn "Quản lý học phí"
H -> DB: Truy xuất danh sách công nợ và trạng thái đóng tiền
DB --> H: Danh sách (Sinh viên, Số tiền nợ, Trạng thái)
H --> AD: Hiển thị danh sách học phí toàn trường

alt Tra cứu sinh viên cụ thể
  AD -> H: Nhập Mã sinh viên cần kiểm tra
  H -> DB: Truy vấn chi tiết hóa đơn
  DB --> H: Thông tin chi tiết
  H --> AD: Hiển thị chi tiết (Các khoản đã đóng/chưa đóng)
end

alt Xác nhận thanh toán thủ công (Chuyển khoản/Tiền mặt)
  AD -> H: Chọn hóa đơn & Bấm "Xác nhận đã đóng"
  H -> DB: Cập nhật trạng thái -> "Đã thanh toán"
  DB --> H: OK
  H --> AD: Thông báo cập nhật thành công
else Xuất báo cáo tài chính
  AD -> H: Chọn "Xuất báo cáo thu học phí"
  H -> DB: Tổng hợp dữ liệu thu chi theo học kỳ
  DB --> H: Dữ liệu tổng hợp
  H --> AD: Trả về file báo cáo (Excel/PDF)
end

@enduml
e. Sequence xử lý ý kiến phản hồi (Ticket)


@startuml
actor "Admin" as AD
participant "Hệ thống" as H
participant "Cơ sở dữ liệu" as DB

AD -> H: Truy cập "Quản lý phiếu hỗ trợ (Ticket)"
H -> DB: Lấy danh sách các Ticket chờ xử lý
DB --> H: Dữ liệu Ticket
H --> AD: Hiển thị danh sách phản hồi

AD -> H: Chọn xem chi tiết 1 Ticket
H --> AD: Hiển thị nội dung chi tiết

AD -> H: Nhập nội dung phản hồi & Đổi trạng thái thành "Đã xử lý"
H -> DB: Lưu cập nhật Ticket
DB --> H: OK
H --> AD: Thông báo "Đã phản hồi thành công"

@enduml
</pre>
### 3.4 Activity
#### 3.4.1 Một số sơ đồ activity usecase cốt lõi của sinh viên
<pre>
a. Activity đăng ký môn học
@startuml
start

:Đăng nhập hệ thống;
:Chọn chức năng "Đăng ký môn học";
:Hệ thống hiển thị danh sách môn học;

if (Có môn học cần đăng ký?) then (Có)

  :Chọn môn học;

  :Kiểm tra điều kiện môn học;
  if (Đủ điều kiện?) then (Không)
    :Thông báo không đủ điều kiện;
    stop
  endif

  :Kiểm tra số chỗ;
  if (Còn chỗ?) then (Không)
    :Thông báo hết chỗ;
    stop
  endif

  :Kiểm tra trùng lịch;
  if (Trùng lịch?) then (Có)
    :Thông báo xung đột;
    stop
  endif

  :Kiểm tra số tín chỉ tối đa;
  if (Vượt quá giới hạn?) then (Có)
    :Thông báo vượt số tín chỉ cho phép;
    stop
  endif

  :Xác nhận đăng ký;
  :Lưu dữ liệu;
  :Thông báo thành công;

else (Không)
  :Thông báo không có môn học;
endif

stop
@enduml

* Ghi chú: Giới hạn số tín chỉ tối đa phụ thuộc vào trạng thái học tập của sinh viên (bình thường, cảnh báo học tập, năm cuối,...), không giới hạn số tín chỉ tối thiểu 
b. Activity thanh toán học phí

@startuml
start

:Đăng nhập hệ thống;
:Chọn chức năng "Thanh toán học phí";
:Hệ thống hiển thị thông tin học phí;

if (Có khoản nợ học phí?) then (Có)
  :Chọn phương thức thanh toán trực tuyến;
  :Chọn ngân hàng / ví điện tử;
  :Chuyển hướng đến cổng thanh toán;
  :Nhập thông tin thanh toán;
  :Xác nhận thanh toán;

  if (Thanh toán thành công?) then (Có)
    :Hệ thống cập nhật trạng thái đã thanh toán;
    :Hiển thị thông báo thành công;
  else (Không)
    :Hiển thị thông báo thất bại;
    :Cho phép thực hiện lại;
  endif

else (Không)
  :Hiển thị thông báo không có khoản nợ;
endif

stop
@enduml
c. Activity xem kết quả điểm danh
@startuml
start

:Đăng nhập hệ thống;
:Chọn chức năng "Xem kết quả điểm danh";
:Hệ thống hiển thị danh sách môn học đã đăng ký;

if (Có môn học đã điểm danh?) then (Có)

  :Chọn môn học;
  :Hệ thống hiển thị chi tiết điểm danh;

  if (Có dữ liệu điểm danh?) then (Có)
    :Hiển thị thống kê điểm danh (Có mặt, Vắng, Có phép, Không phép);
    :Hiển thị lịch sử điểm danh chi tiết;
    :Hiển thị cảnh báo nếu có vi phạm;
  else (Không)
    :Thông báo chưa có dữ liệu điểm danh;
  endif

else (Không)
  :Thông báo không có môn học;
endif

stop
@enduml
d. Activity xem bảng điểm
@startuml
start

:Đăng nhập hệ thống;
:Chọn chức năng "Xem bảng điểm";
:Hệ thống hiển thị danh sách học kỳ;

if (Có dữ liệu học kỳ?) then (Có)

  :Chọn học kỳ;
  :Hệ thống hiển thị bảng điểm học kỳ;

  if (Có dữ liệu bảng điểm?) then (Có)
    :Hiển thị danh sách môn học và điểm số;
    :Hiển thị điểm trung bình học kỳ (TBC học kỳ);
    :Hiển thị điểm trung bình tích lũy (TBC tích lũy);
    :Hiển thị xếp loại học lực;
  else (Không)
    :Thông báo chưa có dữ liệu bảng điểm;
  endif

else (Không)
  :Thông báo không có dữ liệu học kỳ;
endif

stop
@enduml
</pre>

3.4.2 Một số sơ đồ activity usecase cốt lõi của giảng viên
<pre>
a. Activity điểm danh sinh viên

@startuml
start

:Đăng nhập hệ thống;
:Chọn chức năng "Điểm danh sinh viên";
:Hệ thống hiển thị danh sách lớp học phần;

if (Có ca học hiện tại?) then (Có)
  :Chọn lớp để điểm danh;
  :Hệ thống hiển thị danh sách sinh viên;
  
  :Nhập trạng thái điểm danh (Có mặt/Vắng);
  :Bấm lưu kết quả;

  if (Thời gian điểm danh hợp lệ?) then (Có)
    :Hệ thống lưu dữ liệu chuyên cần;
    :Thông báo lưu thành công;
  else (Không)
    :Thông báo đã hết hạn điểm danh;
  endif

else (Không)
  :Thông báo không có ca học nào đang diễn ra;
endif

stop
@enduml
b. Activity nhập và cập nhật điểm

@startuml
start

:Đăng nhập hệ thống;
:Chọn chức năng "Nhập điểm";
:Hệ thống hiển thị danh sách lớp phụ trách;

if (Có lớp phụ trách?) then (Có)
  :Chọn lớp học phần;
  
  if (Bảng điểm đã chốt?) then (Có)
    :Thông báo bảng điểm đã khóa;
    stop
  else (Không)
    :Hệ thống hiển thị giao diện nhập điểm;
    :Giảng viên nhập điểm (hoặc Import file Excel);
    :Bấm lưu bảng điểm;
    
    if (Định dạng điểm hợp lệ 0-10?) then (Có)
      :Hệ thống lưu dữ liệu điểm;
      :Thông báo cập nhật thành công;
    else (Không)
      :Thông báo dữ liệu lỗi;
      :Bôi đỏ các ô điểm sai định dạng;
    endif
  endif

else (Không)
  :Thông báo không có dữ liệu lớp học;
endif

stop
@enduml
c. Activity xem danh sách lớp và sinh viên

@startuml
start

:Đăng nhập hệ thống;
:Chọn chức năng "Xem danh sách lớp học";

if (Được phân công dạy lớp nào không?) then (Có)
  :Hệ thống hiển thị danh sách lớp;
  :Giảng viên chọn xem chi tiết 1 lớp;
  :Hệ thống hiển thị danh sách sinh viên của lớp đó;
else (Không)
  :Thông báo danh sách lớp trống;
endif

stop
@enduml
d. Activity xem thời khóa biểu giảng dạy 

@startuml
start

:Đăng nhập hệ thống;
:Chọn chức năng "Xem thời khóa biểu";
:Hệ thống truy xuất dữ liệu lịch dạy;

if (Có lịch giảng dạy?) then (Có)
  :Hệ thống hiển thị thời khóa biểu;
  :Giảng viên chọn chế độ xem (Tuần/Tháng);
  :Hệ thống sắp xếp và hiển thị lại giao diện lịch;
else (Không)
  :Thông báo lịch dạy trống;
endif

stop
@enduml
</pre>
#### 3.4.3 Một số sơ đồ activity usecase cốt lõi của admin
<pre>
a. Activity quản lý tài khoản 

@startuml
start

:Đăng nhập bằng quyền Admin;
:Chọn chức năng "Quản lý tài khoản" -> "Thêm mới";
:Hệ thống hiển thị biểu mẫu nhập liệu;

:Nhập thông tin (Mã số, Họ tên, Email, Phân quyền...);
:Bấm lưu thông tin;

:Hệ thống kiểm tra trùng lặp Mã số / Email;
if (Thông tin đã tồn tại?) then (Có)
  :Thông báo lỗi trùng lặp dữ liệu;
else (Không)
  :Lưu tài khoản vào cơ sở dữ liệu;
  :Thông báo tạo tài khoản thành công;
endif

stop
@enduml
b. Activity quản lý lớp học phần 

@startuml
start

:Đăng nhập bằng quyền Admin;
:Chọn chức năng "Quản lý lớp học phần" -> "Thêm mới";
:Hiển thị Form tạo lớp;

:Nhập thông số lớp (Mã lớp, Môn học, Sĩ số, Học kỳ);
:Bấm xác nhận tạo lớp;

:Hệ thống kiểm tra tính hợp lệ (Mã lớp);
if (Mã lớp bị trùng?) then (Có)
  :Cảnh báo mã lớp đã tồn tại;
else (Không)
  :Khởi tạo lớp học phần;
  :Thông báo thêm lớp thành công;
endif

stop
@enduml
c. Activity phân công giảng dạy

@startuml
start

:Đăng nhập bằng quyền Admin;
:Chọn chức năng "Phân công giảng dạy";
:Hệ thống hiển thị danh sách lớp và giảng viên;

:Chọn Lớp học phần cần phân công;
:Chọn Giảng viên phụ trách;
:Bấm xác nhận phân công;

:Hệ thống kiểm tra lịch giảng dạy;
if (Giảng viên bị trùng lịch?) then (Có)
  :Cảnh báo trùng thời khóa biểu;
  :Yêu cầu chọn giảng viên hoặc khung giờ khác;
else (Không)
  :Lưu dữ liệu phân công;
  :Thông báo phân công hoàn tất;
endif

stop
@enduml

d. Activity quản lý học phí
@startuml
start

:Đăng nhập bằng quyền Admin;
:Chọn chức năng "Quản lý học phí";
:Hệ thống hiển thị danh sách công nợ;

:Nhập mã Sinh viên cần kiểm tra;
if (Tìm thấy dữ liệu?) then (Có)
  :Hiển thị chi tiết hóa đơn của Sinh viên;
  
  :Chọn hóa đơn cần xử lý;
  :Bấm "Xác nhận đã thanh toán";
  :Cập nhật trạng thái hóa đơn trong hệ thống;
  :Thông báo xác nhận thành công;
else (Không)
  :Thông báo không tìm thấy sinh viên;
endif

stop
@enduml

e. Activity quản lý phiếu hỗ trợ 
@startuml
start

:Đăng nhập bằng quyền Admin;
:Chọn chức năng "Quản lý phiếu hỗ trợ (Ticket)";
:Hệ thống hiển thị danh sách Ticket chờ xử lý;

if (Có Ticket nào chưa xử lý?) then (Có)
  :Chọn xem chi tiết 1 Ticket;
  :Hệ thống hiển thị nội dung phản hồi của người dùng;
  
  :Admin nhập nội dung giải đáp/hỗ trợ;
  :Đổi trạng thái Ticket thành "Đã xử lý";
  :Bấm lưu cập nhật;
  
  :Hệ thống lưu dữ liệu Ticket;
  :Thông báo gửi phản hồi thành công;
else (Không)
  :Thông báo không có yêu cầu hỗ trợ mới;
endif

stop
@enduml
</pre>

### 3.5 Class
<pre>
@startuml
skinparam linetype ortho

class Role
class Account
class Feedback
class Student
class Grade
class Attendance
class Classroom
class Enrollment
class Subject
class Faculty
class Teacher
class Tuition
class Payment
class Schedule
class "System config" as SystemConfig
class Room
class Semester

' Relationships
Role "1" -- "*" Account : Has
Account "1" -- "*" Feedback : Send
Teacher "1" -- "*" Feedback : Has

Student "1" -- "*" Enrollment : Create
Enrollment "1" *-- "1..*" Grade : Records
Enrollment "1" *-- "1..*" Attendance : Records

Enrollment "*" -- "1" Subject : Belong to
Subject "*" -- "1" Faculty : Belongs to
Faculty "1" o-- "*" Classroom : Has
Faculty "1" o-- "*" Teacher : Belongs to

Teacher "1..*" -- "1..*" Subject : Has
Student "1" -- "*" Classroom : Enrols
Classroom "1" -- "1..*" Teacher : Has

Subject "1..*" -- "1" Schedule : Has
Schedule "1" -- "1" Semester : Has
Schedule "1..*" -- "1" Room : uses

Student "1" -- "*" Tuition : Has
Tuition "1" *-- "1..*" Payment : Settles

@enduml
</pre>
### 3.6 Component
<pre>
@startuml
skinparam componentStyle rectangle

package "Client (Trình duyệt Web)" {
  [Giao diện Sinh viên/Giảng viên] as UI
}

package "Web Server (Laravel Framework)" {
  [Routing (Điều hướng)] as Route
  [Controllers (Xử lý Logic)] as Ctrl
  [Views (Blade Template)] as View
  [Models (Eloquent ORM)] as Model
}

package "Database Server" {
  [SQL Server] as DB
}

package "External Services" {
  [Cổng thanh toán (VNPay/MoMo)] as Payment
  [Email Server (SMTP)] as Email
}

' Luồng tương tác
UI --> Route : Gửi HTTP/HTTPS Request
Route --> Ctrl : Phân luồng Request
Ctrl <--> Model : Gọi & Xử lý dữ liệu
Ctrl --> View : Trả về dữ liệu
View --> UI : Render HTML/CSS/JS

Model <--> DB : Truy vấn SQL (CRUD)

Ctrl --> Payment : API Gọi thanh toán
Ctrl --> Email : Gửi thông báo tự động
@enduml
</pre>

### 3.7 Deployment
<pre>
@startuml
left to right direction

node "Client Device" <<Thiết bị người dùng>> {
  node "Web Browser" {
    artifact "Frontend (HTML/CSS/JS)"
  }
}

node "Application Server" <<Máy chủ Web>> {
  node "Web Engine (Nginx / Apache)" {
    artifact "Laravel Application" {
      component "Controllers"
      component "Models"
      component "Views"
    }
  }
}

node "Database Server" <<Máy chủ Cơ sở dữ liệu>> {
  database "SQL Server 2022" {
    artifact "StudentManagement_DB"
  }
}

cloud "Dịch vụ bên thứ 3" <<External APIs>> {
  node "VNPay/Momo Gateway"
  node "SMTP Mail Server"
}

' Kết nối mạng
"Client Device" -- "Application Server" : Giao thức HTTPS (Port 443)
"Application Server" -- "Database Server" : TCP/IP (Port 1433)
"Application Server" -- "Dịch vụ bên thứ 3" : RESTful API / JSON
@enduml
</pre>

## IV. Thiết kế CSDL


## V. Thiết kế giao diện

## VI. Cài đặt và thử nghiệm
