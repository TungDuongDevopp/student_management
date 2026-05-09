-- ============================================================
-- QLSV Database Schema
-- Thứ tự: bảng không FK trước, bảng có FK sau
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ── roles ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `roles` (
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `name`        varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at`  timestamp    NULL DEFAULT NULL,
  `updated_at`  timestamp    NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── faculties ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `faculties` (
  `id`         int(11)     NOT NULL AUTO_INCREMENT,
  `code`       varchar(50) DEFAULT NULL,
  `name`       varchar(100) NOT NULL,
  `created_at` timestamp   NULL DEFAULT NULL,
  `updated_at` timestamp   NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── rooms ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `rooms` (
  `id`          int(11)     NOT NULL AUTO_INCREMENT,
  `block`       varchar(10) DEFAULT NULL,
  `name`        varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at`  timestamp   NULL DEFAULT NULL,
  `updated_at`  timestamp   NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── semesters ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `semesters` (
  `id`            int(11)     NOT NULL AUTO_INCREMENT,
  `name`          varchar(50) DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  `status`        tinyint(4)  NOT NULL DEFAULT 0,
  `created_at`    timestamp   NULL DEFAULT NULL,
  `updated_at`    timestamp   NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── accounts ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `accounts` (
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `role_id`    int(11)      NOT NULL,
  `username`   varchar(50)  NOT NULL,
  `password`   varchar(255) NOT NULL,
  `created_at` timestamp    NULL DEFAULT NULL,
  `updated_at` timestamp    NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── classrooms ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `classrooms` (
  `id`         int(11)     NOT NULL AUTO_INCREMENT,
  `faculty_id` int(11)     NOT NULL,
  `teacher_id` int(11)     DEFAULT NULL,
  `code`       varchar(50) DEFAULT NULL,
  `quantity`   int(11)     DEFAULT NULL,
  `created_at` timestamp   NULL DEFAULT NULL,
  `updated_at` timestamp   NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `classrooms_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── subjects ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `subjects` (
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `faculty_id` int(11)      DEFAULT NULL,
  `name`       varchar(100) DEFAULT NULL,
  `credits`    int(11)      DEFAULT NULL,
  `created_at` timestamp    NULL DEFAULT NULL,
  `updated_at` timestamp    NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── teachers ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `teachers` (
  `id`           int(11)      NOT NULL AUTO_INCREMENT,
  `account_id`   int(11)      NOT NULL,
  `faculty_id`   int(11)      NOT NULL,
  `teacher_code` varchar(50)  DEFAULT NULL,
  `name`         varchar(100) DEFAULT NULL,
  `email`        varchar(255) DEFAULT NULL,
  `images`       varchar(255) DEFAULT NULL,
  `created_at`   timestamp    NULL DEFAULT NULL,
  `updated_at`   timestamp    NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_code` (`teacher_code`),
  KEY `account_id` (`account_id`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  CONSTRAINT `teachers_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── students ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `students` (
  `id`           int(11)      NOT NULL AUTO_INCREMENT,
  `account_id`   int(11)      NOT NULL,
  `classroom_id` int(11)      DEFAULT NULL,
  `student_code` varchar(50)  DEFAULT NULL,
  `name`         varchar(100) DEFAULT NULL,
  `email`        varchar(255) DEFAULT NULL,
  `images`       varchar(255) DEFAULT NULL,
  `created_at`   timestamp    NULL DEFAULT NULL,
  `updated_at`   timestamp    NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_code` (`student_code`),
  KEY `account_id` (`account_id`),
  KEY `classroom_id` (`classroom_id`),
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`account_id`)   REFERENCES `accounts`   (`id`),
  CONSTRAINT `students_ibfk_2` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── schedules (lớp học phần) ─────────────────────────────────
CREATE TABLE IF NOT EXISTS `schedules` (
  `id`           int(11) NOT NULL AUTO_INCREMENT,
  `subject_id`   int(11) DEFAULT NULL,
  `teacher_id`   int(11) DEFAULT NULL,
  `room_id`      int(11) DEFAULT NULL,
  `semester_id`  int(11) DEFAULT NULL,
  `classroom_id` int(11) DEFAULT NULL,
  `day_of_week`  int(11) DEFAULT NULL,
  `shift`        int(11) DEFAULT NULL,
  `created_at`   timestamp NULL DEFAULT NULL,
  `updated_at`   timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id`   (`subject_id`),
  KEY `teacher_id`   (`teacher_id`),
  KEY `room_id`      (`room_id`),
  KEY `semester_id`  (`semester_id`),
  KEY `classroom_id` (`classroom_id`),
  CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`subject_id`)   REFERENCES `subjects`    (`id`),
  CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`teacher_id`)   REFERENCES `teachers`    (`id`),
  CONSTRAINT `schedules_ibfk_3` FOREIGN KEY (`room_id`)      REFERENCES `rooms`       (`id`),
  CONSTRAINT `schedules_ibfk_4` FOREIGN KEY (`semester_id`)  REFERENCES `semesters`   (`id`),
  CONSTRAINT `schedules_ibfk_5` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms`  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── enrollments ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id`          int(11)   NOT NULL AUTO_INCREMENT,
  `student_id`  int(11)   NOT NULL,
  `schedule_id` int(11)   NOT NULL,
  `final_score` float     DEFAULT NULL,
  `status`      tinyint(4) DEFAULT NULL,
  `created_at`  timestamp  NULL DEFAULT NULL,
  `updated_at`  timestamp  NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id`  (`student_id`),
  KEY `schedule_id` (`schedule_id`),
  CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`)  REFERENCES `students`  (`id`),
  CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── attendances ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `attendances` (
  `id`              int(11)    NOT NULL AUTO_INCREMENT,
  `enrollment_id`   int(11)    DEFAULT NULL,
  `attendance_date` date       DEFAULT NULL,
  `status`          tinyint(4) DEFAULT NULL,
  `created_at`      timestamp  NULL DEFAULT NULL,
  `updated_at`      timestamp  NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enrollment_id` (`enrollment_id`),
  CONSTRAINT `attendances_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── tuitions ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tuitions` (
  `id`           int(11)        NOT NULL AUTO_INCREMENT,
  `student_id`   int(11)        DEFAULT NULL,
  `semester_id`  int(11)        DEFAULT NULL,
  `total_amount` decimal(10,2)  DEFAULT NULL,
  `paid_amount`  decimal(10,2)  DEFAULT NULL,
  `created_at`   timestamp      NULL DEFAULT NULL,
  `updated_at`   timestamp      NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id`  (`student_id`),
  KEY `semester_id` (`semester_id`),
  CONSTRAINT `tuitions_ibfk_1` FOREIGN KEY (`student_id`)  REFERENCES `students`  (`id`),
  CONSTRAINT `tuitions_ibfk_2` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── payments ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `payments` (
  `id`           int(11)       NOT NULL AUTO_INCREMENT,
  `tuition_id`   int(11)       DEFAULT NULL,
  `amount`       decimal(10,2) DEFAULT NULL,
  `payment_date` datetime      DEFAULT NULL,
  `created_at`   timestamp     NULL DEFAULT NULL,
  `updated_at`   timestamp     NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tuition_id` (`tuition_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`tuition_id`) REFERENCES `tuitions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── feedbacks ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id`         int(11)    NOT NULL AUTO_INCREMENT,
  `account_id` int(11)    DEFAULT NULL,
  `content`    text       DEFAULT NULL,
  `reply`      text       DEFAULT NULL COMMENT 'Phản hồi từ admin',
  `status`     tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: chưa xem, 1: đã xem',
  `created_at` timestamp  NULL DEFAULT NULL,
  `updated_at` timestamp  NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── users (Laravel auth) ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`                bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name`              varchar(255) NOT NULL,
  `email`             varchar(255) NOT NULL,
  `email_verified_at` timestamp    NULL DEFAULT NULL,
  `password`          varchar(255) NOT NULL,
  `remember_token`    varchar(100) DEFAULT NULL,
  `created_at`        timestamp    NULL DEFAULT NULL,
  `updated_at`        timestamp    NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── password_reset_tokens ────────────────────────────────────
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email`      varchar(255) NOT NULL,
  `token`      varchar(255) NOT NULL,
  `created_at` timestamp    NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── sessions ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `sessions` (
  `id`            varchar(255)        NOT NULL,
  `user_id`       bigint(20) unsigned DEFAULT NULL,
  `ip_address`    varchar(45)         DEFAULT NULL,
  `user_agent`    text                DEFAULT NULL,
  `payload`       longtext            NOT NULL,
  `last_activity` int(11)             NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index`       (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── migrations (Laravel internal) ───────────────────────────
CREATE TABLE IF NOT EXISTS `migrations` (
  `id`        int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255)     NOT NULL,
  `batch`     int(11)          NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
