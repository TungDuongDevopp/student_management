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

* **Ngôn ngữ lập trình & Framework:** Phát triển theo mô hình Web App. Backend sử dụng ngôn ngữ **PHP** (Framework **Laravel**) đáp ứng khả năng phân luồng và bảo mật cao. Giao diện Frontend được xây dựng chuẩn hóa bằng **HTML5, CSS3, JavaScript** kết hợp các thư viện UI hiện đại.
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
- Quản lý tài khoản  

---

##### 3.1.2 Giảng viên
- Xem danh sách lớp học  
- Xem danh sách sinh viên  
- Nhập và cập nhật điểm  
- Xem thời khóa biểu  
- Xem thông báo  
- Điểm danh sinh viên

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
- Phản hồi thông tin

 ### 3.2 Phân tích use case chi tiết

 #### 3.2.1 Usecase tổng quan

 #### 3.2.2 Usercase sinh viên


## IV. Thiết kế CSDL

## V. Thiết kế giao diện

## VI. Cài đặt và thử nghiệm

