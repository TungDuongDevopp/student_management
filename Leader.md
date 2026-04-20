#### 3.3.1 Một số sơ đồ activity usecase cốt lõi của sinh viên
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
#### 3.4.1 Một số sơ đồ sequence usecase cốt lõi của sinh viên
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
H -> DB: Lấy dữ liệu điểm danh
DB --> H: Dữ liệu

alt Có dữ liệu
  H --> S: Hiển thị kết quả
else Không có
  H --> S: Thông báo
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