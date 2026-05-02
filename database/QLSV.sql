-- ROLES & ACCOUNTS
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- FACULTY
CREATE TABLE faculties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE,
    name VARCHAR(100) NOT NULL
);

-- CLASSROOM
CREATE TABLE classrooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    teacher_id INT,
    semester_id INT,
    code VARCHAR(50) UNIQUE,
    quantity INT,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id)
);

-- STUDENT
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    classroom_id INT,
    student_code VARCHAR(50) UNIQUE,
    name VARCHAR(100),
    email VARCHAR(255),
    images VARCHAR(255),
    FOREIGN KEY (account_id) REFERENCES accounts(id),
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id)
);

-- TEACHER
CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    faculty_id INT NOT NULL,
    teacher_code VARCHAR(50) UNIQUE,
    name VARCHAR(100),
    email VARCHAR(255),
    images VARCHAR(255),
    FOREIGN KEY (account_id) REFERENCES accounts(id),
    FOREIGN KEY (faculty_id) REFERENCES faculties(id)
);

-- SUBJECT
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT,
    name VARCHAR(100),
    credits INT,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id)
);

-- ROOM
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50)
);

-- SEMESTER
CREATE TABLE semesters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    academic_year VARCHAR(20)
);

-- SCHEDULE
CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT,
    teacher_id INT,
    room_id INT,
    semester_id INT,
    classroom_id INT,
    day_of_week INT,
    shift INT,
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (semester_id) REFERENCES semesters(id),
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id)
);

-- ENROLLMENT (có điểm cuối kỳ)
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    schedule_id INT NOT NULL,
    final_score FLOAT,
    status TINYINT,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (schedule_id) REFERENCES schedules(id)
);

-- ATTENDANCE
CREATE TABLE attendances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT,
    attendance_date DATE,
    status TINYINT,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id)
);

-- TUITION (theo kỳ)
CREATE TABLE tuitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    semester_id INT,
    total_amount DECIMAL(10,2),
    paid_amount DECIMAL(10,2),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (semester_id) REFERENCES semesters(id)
);

-- PAYMENT
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tuition_id INT,
    amount DECIMAL(10,2),
    payment_date DATETIME,
    FOREIGN KEY (tuition_id) REFERENCES tuitions(id)
);

-- FEEDBACK
CREATE TABLE feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT,
    content TEXT,
    created_at DATETIME,
    FOREIGN KEY (account_id) REFERENCES accounts(id)
);