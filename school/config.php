<?php
/* config.php: สร้าง Database และเชื่อมต่อ */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "school_db";

/* connect server */
$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) { die("เชื่อมต่อ MySQL ล้มเหลว: " . $mysqli->connect_error); }
$mysqli->set_charset("utf8mb4");

/* create db */
$mysqli->query("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->select_db($db);

/* create tables */
$ddl = <<<SQL
CREATE TABLE IF NOT EXISTS teacher (
  teacher_id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name  VARCHAR(100) NOT NULL,
  email      VARCHAR(100),
  phone      VARCHAR(20),
  department VARCHAR(100)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS student (
  student_id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name  VARCHAR(100) NOT NULL,
  birth DATE,
  email VARCHAR(100),
  phone VARCHAR(20),
  address TEXT,
  teacher_id INT NULL,
  CONSTRAINT fk_student_advisor FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS subject (
  subject_id INT AUTO_INCREMENT PRIMARY KEY,
  subject_name VARCHAR(150) NOT NULL,
  credits TINYINT NOT NULL,
  teacher_id INT NULL,
  CONSTRAINT fk_subject_teacher FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registration (
  registration_id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  subject_id INT NOT NULL,
  semester VARCHAR(10) NOT NULL,
  year INT NOT NULL,
  CONSTRAINT fk_reg_student FOREIGN KEY (student_id) REFERENCES student(student_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_reg_subject FOREIGN KEY (subject_id) REFERENCES subject(subject_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  UNIQUE KEY uq_reg_once (student_id, subject_id, semester, year)
) ENGINE=InnoDB;
SQL;

foreach (array_filter(array_map('trim', explode(';', $ddl))) as $stmt) {
  if ($stmt) $mysqli->query($stmt);
}

/* helper */
if (!function_exists('h')) {
  function h($s){ return htmlspecialchars($s ?? "", ENT_QUOTES, 'UTF-8'); }
}
?>
