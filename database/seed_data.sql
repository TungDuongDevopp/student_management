-- =============================================
-- SEED DATA - Quản lý Sinh viên
-- Mật khẩu mặc định: 123456 (bcrypt hash)
-- =============================================

-- -----------------------------------------------
-- 1. ROLES (3 vai trò)
-- -----------------------------------------------
INSERT INTO roles (id, name, description) VALUES
(1, 'Admin', 'Quản trị viên hệ thống'),
(2, 'Teacher', 'Giảng viên'),
(3, 'Student', 'Sinh viên');

-- -----------------------------------------------
-- 2. ACCOUNTS (1 admin + 5 giảng viên + 10 sinh viên = 16)
-- Mật khẩu: 123456
-- -----------------------------------------------
INSERT INTO accounts (id, role_id, username, password) VALUES
(1,  1, 'admin',    '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(2,  2, 'gv.anhtuan',   '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(3,  2, 'gv.thuha',     '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(4,  2, 'gv.minhduc',   '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(5,  2, 'gv.thanhlam',  '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(6,  2, 'gv.hoangnam',  '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(7,  3, 'sv.vanA',      '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(8,  3, 'sv.thiB',      '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(9,  3, 'sv.vanC',      '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(10, 3, 'sv.thiD',      '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(11, 3, 'sv.vanE',      '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(12, 3, 'sv.thiF',      '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(13, 3, 'sv.vanG',      '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(14, 3, 'sv.thiH',      '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(15, 3, 'sv.vanI',      '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu'),
(16, 3, 'sv.thiK',      '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu');

-- -----------------------------------------------
-- 3. FACULTIES (5 khoa)
-- -----------------------------------------------
INSERT INTO faculties (id, code, name) VALUES
(1, 'CNTT',  'Công nghệ thông tin'),
(2, 'KT',    'Kinh tế'),
(3, 'NN',    'Ngoại ngữ'),
(4, 'DT',    'Điện tử viễn thông'),
(5, 'CK',    'Cơ khí');

-- -----------------------------------------------
-- 4. SEMESTERS (4 học kỳ)
-- -----------------------------------------------
INSERT INTO semesters (id, name, academic_year) VALUES
(1, 'Học kỳ 1', '2025-2026'),
(2, 'Học kỳ 2', '2025-2026'),
(3, 'Học kỳ 1', '2026-2027'),
(4, 'Học kỳ 2', '2026-2027');

-- -----------------------------------------------
-- 5. ROOMS (10 phòng học)
-- -----------------------------------------------
INSERT INTO rooms (id, name) VALUES
(1,  'A101'),
(2,  'A102'),
(3,  'A201'),
(4,  'A202'),
(5,  'B101'),
(6,  'B102'),
(7,  'B201'),
(8,  'B202'),
(9,  'C101'),
(10, 'C102');

-- -----------------------------------------------
-- 6. TEACHERS (5 giảng viên)
-- -----------------------------------------------
INSERT INTO teachers (id, account_id, faculty_id, teacher_code, name, email, images) VALUES
(1, 2, 1, 'GV001', 'Nguyễn Anh Tuấn','Tuan@teacher.edu.vn','images/teachers/anh_tuan.jpg'),
(2, 3, 1, 'GV002', 'Trần Thị Thu Hà','Ha@teacher.edu.vn', 'images/teachers/thu_ha.jpg'),
(3, 4, 2, 'GV003', 'Lê Minh Đức','Duc@teacher.edu.vn', 'images/teachers/minh_duc.jpg'),
(4, 5, 3, 'GV004', 'Phạm Thanh Lâm','Lam@teacher.edu.vn', 'images/teachers/thanh_lam.jpg'),
(5, 6, 4, 'GV005', 'Hoàng Văn Nam','Nam@teacher.edu.vn', 'images/teachers/hoang_nam.jpg');

-- -----------------------------------------------
-- 7. CLASSROOMS (10 lớp học)
-- -----------------------------------------------
INSERT INTO classrooms (id, faculty_id, teacher_id, semester_id, code, quantity) VALUES
(1,  1, 1, 1, 'CNTT01-K18', 35),
(2,  1, 2, 1, 'CNTT02-K18', 40),
(3,  1, 1, 2, 'CNTT01-K19', 38),
(4,  2, 3, 1, 'KT01-K18',   45),
(5,  2, 3, 2, 'KT02-K18',   42),
(6,  3, 4, 1, 'NN01-K18',   30),
(7,  3, 4, 2, 'NN02-K19',   32),
(8,  4, 5, 1, 'DT01-K18',   36),
(9,  4, 5, 2, 'DT02-K19',   34),
(10, 5, NULL, 1, 'CK01-K18', 40);

-- -----------------------------------------------
-- 8. STUDENTS (10 sinh viên)
-- -----------------------------------------------
INSERT INTO students (id, account_id, classroom_id, student_code, name, email, images) VALUES
(1,  7,  1, 'SV2025001', 'Nguyễn Văn A',  'vana@student.edu.vn', 'images/students/van_a.jpg'),
(2,  8,  1, 'SV2025002', 'Trần Thị B',    'thib@student.edu.vn', 'images/students/thi_b.jpg'),
(3,  9,  2, 'SV2025003', 'Lê Văn C',      'vanc@student.edu.vn', 'images/students/van_c.jpg'),
(4,  10, 2, 'SV2025004', 'Phạm Thị D',    'thid@student.edu.vn', 'images/students/thi_d.jpg'),
(5,  11, 3, 'SV2025005', 'Hoàng Văn E',   'vane@student.edu.vn', 'images/students/van_e.jpg'),
(6,  12, 4, 'SV2025006', 'Đỗ Thị F',      'thif@student.edu.vn', 'images/students/thi_f.jpg'),
(7,  13, 5, 'SV2025007', 'Bùi Văn G',     'vang@student.edu.vn', 'images/students/van_g.jpg'),
(8,  14, 6, 'SV2025008', 'Vũ Thị H',      'thih@student.edu.vn', 'images/students/thi_h.jpg'),
(9,  15, 8, 'SV2025009', 'Đinh Văn I',    'vani@student.edu.vn', 'images/students/van_i.jpg'),
(10, 16, 9, 'SV2025010', 'Ngô Thị K',     'thik@student.edu.vn', 'images/students/thi_k.jpg');

-- -----------------------------------------------
-- 9. SUBJECTS (10 môn học)
-- -----------------------------------------------
INSERT INTO subjects (id, faculty_id, name, credits) VALUES
(1,  1, 'Lập trình Web',           3),
(2,  1, 'Cơ sở dữ liệu',          3),
(3,  1, 'Cấu trúc dữ liệu',       3),
(4,  1, 'Mạng máy tính',           2),
(5,  2, 'Kinh tế vi mô',           3),
(6,  2, 'Quản trị kinh doanh',     3),
(7,  3, 'Tiếng Anh cơ bản',        2),
(8,  3, 'Tiếng Anh nâng cao',      2),
(9,  4, 'Kỹ thuật điện tử',        3),
(10, 5, 'Cơ học ứng dụng',         3);

-- -----------------------------------------------
-- 10. SCHEDULES (10 thời khóa biểu)
-- day_of_week: 2=T2, 3=T3, 4=T4, 5=T5, 6=T6, 7=T7
-- shift: 1=Sáng(7h-9h30), 2=Sáng(9h45-12h), 3=Chiều(13h-15h30), 4=Chiều(15h45-18h)
-- -----------------------------------------------
INSERT INTO schedules (id, subject_id, teacher_id, room_id, semester_id, classroom_id, day_of_week, shift) VALUES
(1,  1, 1, 1, 1, 1, 2, 1),
(2,  2, 2, 2, 1, 1, 3, 2),
(3,  3, 1, 3, 1, 2, 4, 1),
(4,  4, 2, 4, 1, 2, 5, 3),
(5,  5, 3, 5, 1, 4, 2, 2),
(6,  6, 3, 6, 1, 5, 3, 1),
(7,  7, 4, 7, 1, 6, 4, 4),
(8,  8, 4, 8, 2, 7, 5, 1),
(9,  9, 5, 9, 1, 8, 6, 2),
(10, 10, NULL, 10, 1, 10, 7, 3);

-- -----------------------------------------------
-- 11. ENROLLMENTS (10 đăng ký môn học)
-- status: 0=Đang học, 1=Đạt, 2=Không đạt
-- -----------------------------------------------
INSERT INTO enrollments (id, student_id, schedule_id, final_score, status) VALUES
(1,  1, 1, 8.5,  1),
(2,  1, 2, 7.0,  1),
(3,  2, 1, 9.0,  1),
(4,  2, 2, 4.5,  2),
(5,  3, 3, 6.5,  1),
(6,  4, 4, NULL,  0),
(7,  5, 3, 7.5,  1),
(8,  6, 5, 5.0,  1),
(9,  8, 7, NULL,  0),
(10, 9, 9, 8.0,  1);

-- -----------------------------------------------
-- 12. ATTENDANCES (10 điểm danh)
-- status: 0=Vắng, 1=Có mặt, 2=Có phép
-- -----------------------------------------------
INSERT INTO attendances (id, enrollment_id, attendance_date, status) VALUES
(1,  1, '2025-09-02', 1),
(2,  1, '2025-09-09', 1),
(3,  1, '2025-09-16', 0),
(4,  2, '2025-09-03', 1),
(5,  2, '2025-09-10', 2),
(6,  3, '2025-09-02', 1),
(7,  3, '2025-09-09', 1),
(8,  5, '2025-09-04', 1),
(9,  5, '2025-09-11', 0),
(10, 9, '2025-09-04', 1);

-- -----------------------------------------------
-- 13. TUITIONS (10 công nợ học phí)
-- -----------------------------------------------
INSERT INTO tuitions (id, student_id, semester_id, total_amount, paid_amount) VALUES
(1,  1, 1, 8500000.00,  8500000.00),
(2,  2, 1, 8500000.00,  8500000.00),
(3,  3, 1, 9000000.00,  5000000.00),
(4,  4, 1, 9000000.00,  0.00),
(5,  5, 2, 8500000.00,  8500000.00),
(6,  6, 1, 7500000.00,  7500000.00),
(7,  7, 1, 7500000.00,  3000000.00),
(8,  8, 1, 6000000.00,  6000000.00),
(9,  9, 1, 9000000.00,  9000000.00),
(10, 10, 1, 9000000.00, 4500000.00);

-- -----------------------------------------------
-- 14. PAYMENTS (10 lịch sử thanh toán)
-- -----------------------------------------------
INSERT INTO payments (id, tuition_id, amount, payment_date) VALUES
(1,  1, 8500000.00, '2025-08-20 09:30:00'),
(2,  2, 8500000.00, '2025-08-21 10:15:00'),
(3,  3, 3000000.00, '2025-08-22 14:00:00'),
(4,  3, 2000000.00, '2025-09-15 08:45:00'),
(5,  5, 8500000.00, '2026-01-10 11:20:00'),
(6,  6, 7500000.00, '2025-08-25 16:30:00'),
(7,  7, 3000000.00, '2025-08-28 09:00:00'),
(8,  8, 6000000.00, '2025-08-19 13:50:00'),
(9,  9, 9000000.00, '2025-08-20 10:00:00'),
(10, 10, 4500000.00, '2025-08-30 15:10:00');

-- -----------------------------------------------
-- 15. FEEDBACKS (10 ý kiến phản hồi)
-- -----------------------------------------------
INSERT INTO feedbacks (id, account_id, content, created_at) VALUES
(1,  7,  'Hệ thống đăng ký môn học rất tiện lợi, dễ sử dụng.',              '2025-09-10 08:30:00'),
(2,  8,  'Mong muốn có thêm chức năng xem lịch thi trên hệ thống.',          '2025-09-12 09:15:00'),
(3,  9,  'Giao diện thời khóa biểu rất trực quan, cảm ơn nhà trường.',       '2025-09-15 14:20:00'),
(4,  10, 'Cổng thanh toán học phí đôi khi bị chậm vào giờ cao điểm.',         '2025-09-18 16:00:00'),
(5,  11, 'Đề nghị bổ sung thông báo qua email khi có lịch thi mới.',          '2025-09-20 10:45:00'),
(6,  2,  'Chức năng nhập điểm hoạt động ổn định, giao diện rõ ràng.',         '2025-10-01 11:00:00'),
(7,  3,  'Mong muốn có thể import điểm từ file Excel trực tiếp.',             '2025-10-05 13:30:00'),
(8,  12, 'Hệ thống điểm danh QR code rất nhanh và chính xác.',                '2025-10-10 07:50:00'),
(9,  13, 'Nên thêm dark mode cho giao diện, nhìn dễ chịu hơn khi học tối.',   '2025-10-15 21:30:00'),
(10, 14, 'Trang xem bảng điểm cần hiển thị thêm GPA tổng.',                   '2025-10-20 15:00:00');
