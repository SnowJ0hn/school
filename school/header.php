<?php require_once "config.php"; ?>
<!doctype html>
<html lang="th">
<head>
  <link rel="stylesheet" href="style.css">
  <meta charset="utf-8">
  <title>ระบบลงทะเบียนเรียน</title>
  <link href="css/style.css" rel="stylesheet">
  <style>
    :root{--accent:#2563eb;--muted:#6b7280;}
    body{font-family:sans-serif;background:#f7f9fc;margin:0}
    .nav{background:var(--accent);padding:12px}
    .nav a{color:#fff;margin-right:12px;text-decoration:none}
    .wrap{max-width:1000px;margin:20px auto;padding:0 16px}
    .card{background:#fff;padding:16px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.1);margin-bottom:20px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:8px;border-bottom:1px solid #eee;text-align:left}
    th{background:#f3f4f6}
    .btn{padding:6px 10px;border-radius:6px;border:1px solid #ccc;background:#fff;cursor:pointer}
    .btn.primary{background:var(--accent);color:#fff;border:none}
    .btn.danger{color:#dc2626;border-color:#dc2626}
    .grid2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    input,select,textarea{width:100%;padding:8px;border:1px solid #ccc;border-radius:6px}
    .muted{color:var(--muted);font-size:13px}
  </style>
</head>
<body>
<div class="nav">
  <a href="index.php"><b>🏫 ระบบลงทะเบียน</b></a>
  <a href="students.php">นักเรียน</a>
  <a href="teachers.php">อาจารย์</a>
  <a href="subjects.php">รายวิชา</a>
  <a href="registrations.php">ลงทะเบียน</a>
</div>
<div class="wrap">
