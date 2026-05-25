-- Database and tables initialization for BCST application

-- Create bcst_db
CREATE DATABASE IF NOT EXISTS `bcst_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bcst_db`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `enrollment_date` DATE DEFAULT NULL,
  `student_id` VARCHAR(100) DEFAULT NULL,
  `full_name` VARCHAR(255) DEFAULT NULL,
  `gender` VARCHAR(50) DEFAULT NULL,
  `date_of_birth` DATE DEFAULT NULL,
  `place_of_birth` VARCHAR(255) DEFAULT NULL,
  `fathers_name` VARCHAR(255) DEFAULT NULL,
  `contact_no` VARCHAR(100) DEFAULT NULL,
  `mothers_name` VARCHAR(255) DEFAULT NULL,
  `religion` VARCHAR(100) DEFAULT NULL,
  `school_level` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `report_card_file` VARCHAR(255) DEFAULT NULL,
  `birth_cert_file` VARCHAR(255) DEFAULT NULL,
  `good_moral_file` VARCHAR(255) DEFAULT NULL,
  `nso_psa_file` VARCHAR(255) DEFAULT NULL,
  `formal_picture_file` VARCHAR(255) DEFAULT NULL,
  `diploma_file` VARCHAR(255) DEFAULT NULL,
  `form_137_file` VARCHAR(255) DEFAULT NULL,
  `approved` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(150) DEFAULT NULL,
  `log_date` DATE DEFAULT NULL,
  `time_in` TIME DEFAULT NULL,
  `time_out` TIME DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert admin user into bcst_db (password: bcstadmin)
INSERT INTO `users` (`username`, `email`, `password`) VALUES
('admin', 'admin@bcst.local', '$2y$10$RE/CB26pnzEIizC7sEBYee9chVUObQ4Mg96btwCuiWvNgR/Qw/ZJS')
ON DUPLICATE KEY UPDATE `username`=VALUES(`username`), `password`=VALUES(`password`);

-- Create enrollment_db (for legacy files that reference this name)
CREATE DATABASE IF NOT EXISTS `enrollment_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `enrollment_db`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `enrollment_date` DATE DEFAULT NULL,
  `student_id` VARCHAR(100) DEFAULT NULL,
  `full_name` VARCHAR(255) DEFAULT NULL,
  `gender` VARCHAR(50) DEFAULT NULL,
  `date_of_birth` DATE DEFAULT NULL,
  `place_of_birth` VARCHAR(255) DEFAULT NULL,
  `fathers_name` VARCHAR(255) DEFAULT NULL,
  `contact_no` VARCHAR(100) DEFAULT NULL,
  `mothers_name` VARCHAR(255) DEFAULT NULL,
  `religion` VARCHAR(100) DEFAULT NULL,
  `school_level` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `report_card_file` VARCHAR(255) DEFAULT NULL,
  `birth_cert_file` VARCHAR(255) DEFAULT NULL,
  `good_moral_file` VARCHAR(255) DEFAULT NULL,
  `nso_psa_file` VARCHAR(255) DEFAULT NULL,
  `formal_picture_file` VARCHAR(255) DEFAULT NULL,
  `diploma_file` VARCHAR(255) DEFAULT NULL,
  `form_137_file` VARCHAR(255) DEFAULT NULL,
  `approved` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(150) DEFAULT NULL,
  `log_date` DATE DEFAULT NULL,
  `time_in` TIME DEFAULT NULL,
  `time_out` TIME DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert admin user into enrollment_db as well
INSERT INTO `users` (`username`, `email`, `password`) VALUES
('admin', 'admin@bcst.local', '$2y$10$RE/CB26pnzEIizC7sEBYee9chVUObQ4Mg96btwCuiWvNgR/Qw/ZJS')
ON DUPLICATE KEY UPDATE `username`=VALUES(`username`), `password`=VALUES(`password`);
