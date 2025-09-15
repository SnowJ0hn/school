<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "registration_db";

// Connect MySQL (no DB yet)
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Create DB if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

// Select DB
$conn->select_db($db);

// Table Teacher
$conn->query("CREATE TABLE IF NOT EXISTS Teacher (
    teacher_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100),
    phone VARCHAR(20),
    department VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Table Student
$conn->query("CREATE TABLE IF NOT EXISTS Student (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    birth DATE,
    email VARCHAR(100),
    phone VARCHAR(20),
    address VARCHAR(255),
    teacher_id INT,
    FOREIGN KEY (teacher_id) REFERENCES Teacher(teacher_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Table Subject
$conn->query("CREATE TABLE IF NOT EXISTS Subject (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100),
    credits INT,
    teacher_id INT,
    FOREIGN KEY (teacher_id) REFERENCES Teacher(teacher_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Table Registration
$conn->query("CREATE TABLE IF NOT EXISTS Registration (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    semester INT,
    year INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Student(student_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES Subject(subject_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
?>