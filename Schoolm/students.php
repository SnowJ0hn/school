<?php 
include 'config.php'; 

// เพิ่มนักเรียน
if (isset($_POST['add'])) {
    $teacher_id = !empty($_POST['teacher_id']) ? $_POST['teacher_id'] : "NULL";

    $sql = "INSERT INTO Student (first_name, last_name, birth, email, phone, address, teacher_id)
            VALUES ('{$_POST['first_name']}',
                    '{$_POST['last_name']}',
                    '{$_POST['birth']}',
                    '{$_POST['email']}',
                    '{$_POST['phone']}',
                    '{$_POST['address']}',
                    $teacher_id)";
    if (!$conn->query($sql)) {
        die("❌ Insert Error: " . $conn->error);
    }
    header("Location: students.php"); 
    exit();
}

// ลบนักเรียน
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    if (!$conn->query("DELETE FROM Student WHERE student_id = $del_id")) {
        die("❌ Delete Error: " . $conn->error);
    }
    header("Location: students.php");
    exit();
}

// ดึงข้อมูลนักเรียน + อาจารย์ที่ปรึกษา
$result = $conn->query("SELECT s.*, t.first_name AS t_first, t.last_name AS t_last 
                        FROM Student s 
                        LEFT JOIN Teacher t ON s.teacher_id = t.teacher_id");

// ดึงรายชื่ออาจารย์สำหรับ dropdown
$teachers = $conn->query("SELECT * FROM Teacher");
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการนักเรียน</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">

<div class="card shadow-lg">
  <div class="card-header bg-dark text-white">📋 รายชื่อนักเรียน</div>
  <div class="card-body">
    <table class="table table-hover align-middle">
      <thead class="table-primary">
        <tr>
          <th>รหัส</th>
          <th>ชื่อ-นามสกุล</th>
          <th>วันเกิด</th>
          <th>อีเมล</th>
          <th>เบอร์โทร</th>
          <th>อาจารย์ที่ปรึกษา</th>
          <th class="text-center">การจัดการ</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['student_id'] ?></td>
            <td><?= $row['first_name'] . " " . $row['last_name'] ?></td>
            <td><?= $row['birth'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['t_first'] ? $row['t_first']." ".$row['t_last'] : "-" ?></td>
            <td class="text-center">
              <a href="?delete=<?= $row['student_id'] ?>" 
                 class="btn btn-sm btn-danger"
                 onclick="return confirm('ลบนักเรียนคนนี้?')">ลบ</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" class="text-center text-muted">ไม่มีข้อมูล</td></tr>
      <?php endif; ?>
      </tbody>
    </table>

    <h4 class="mt-4 text-primary">➕ เพิ่มนักเรียน</h4>
    <form method="post">
      <input type="text" name="first_name" placeholder="ชื่อ" class="form-control mb-2" required>
      <input type="text" name="last_name" placeholder="นามสกุล" class="form-control mb-2" required>
      <input type="date" name="birth" class="form-control mb-2">
      <input type="email" name="email" placeholder="Email" class="form-control mb-2">
      <input type="text" name="phone" placeholder="เบอร์โทร" class="form-control mb-2">
      <input type="text" name="address" placeholder="ที่อยู่" class="form-control mb-2">
      
      <select name="teacher_id" class="form-control mb-2">
        <option value="">-- เลือกอาจารย์ที่ปรึกษา --</option>
        <?php while($t = $teachers->fetch_assoc()): ?>
          <option value="<?= $t['teacher_id'] ?>">
            <?= $t['first_name']." ".$t['last_name'] ?>
          </option>
        <?php endwhile; ?>
      </select>

      <button name="add" class="btn btn-success">บันทึก</button>
    </form>
  </div>
</div>

<br><a href="index.php" class="btn btn-secondary">⬅ กลับเมนู</a>
</body>
</html>
