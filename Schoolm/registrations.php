<?php 
include 'config.php'; 

// เพิ่มการลงทะเบียน
if (isset($_POST['add'])) {
    $sql = "INSERT INTO Registration (student_id, subject_id, semester, year)
            VALUES ('{$_POST['student_id']}',
                    '{$_POST['subject_id']}',
                    '{$_POST['semester']}',
                    '{$_POST['year']}')";
    if (!$conn->query($sql)) {
        die("❌ Insert Error: " . $conn->error);
    }
    header("Location: registrations.php"); 
    exit();
}

// ลบการลงทะเบียน
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    if (!$conn->query("DELETE FROM Registration WHERE registration_id = $del_id")) {
        die("❌ Delete Error: " . $conn->error);
    }
    header("Location: registrations.php");
    exit();
}

// ดึงข้อมูลการลงทะเบียน
$sql = "SELECT r.*, s.first_name, s.last_name,
               sub.subject_name, sub.credits,
               t.first_name AS t_first, t.last_name AS t_last
        FROM Registration r
        JOIN Student s ON r.student_id = s.student_id
        JOIN Subject sub ON r.subject_id = sub.subject_id
        LEFT JOIN Teacher t ON sub.teacher_id = t.teacher_id";
$result = $conn->query($sql);

// ดึงนักศึกษา
$students = $conn->query("SELECT * FROM Student");

// ดึงรายวิชา
$subjects = $conn->query("SELECT sub.*, t.first_name AS t_first, t.last_name AS t_last 
                          FROM Subject sub 
                          LEFT JOIN Teacher t ON sub.teacher_id = t.teacher_id");
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการลงทะเบียน</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">

<div class="card shadow-lg">
  <div class="card-header bg-dark text-white">📝 รายการลงทะเบียน (<?= $result->num_rows ?> รายการ)</div>
  <div class="card-body">
    <table class="table table-hover align-middle">
      <thead class="table-primary">
        <tr>
          <th>รหัส</th>
          <th>นักเรียน</th>
          <th>รายวิชา</th>
          <th>หน่วยกิต</th>
          <th>อาจารย์ผู้สอน</th>
          <th>เทอม/ปี</th>
          <th>วันที่ลงทะเบียน</th>
          <th class="text-center">การจัดการ</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr class="table-warning">
            <td><?= $row['registration_id'] ?></td>
            <td><?= $row['first_name']." ".$row['last_name'] ?></td>
            <td><?= $row['subject_name'] ?></td>
            <td><span class="badge bg-secondary"><?= $row['credits'] ?> หน่วย</span></td>
            <td><?= $row['t_first']." ".$row['t_last'] ?></td>
            <td><span class="badge bg-info">เทอม <?= $row['semester'] ?></span> <?= $row['year'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td class="text-center">
              <a href="?delete=<?= $row['registration_id'] ?>" class="btn btn-sm btn-danger"
                 onclick="return confirm('ยกเลิกการลงทะเบียนนี้?')">ยกเลิก</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="8" class="text-center text-muted">ไม่มีข้อมูล</td></tr>
      <?php endif; ?>
      </tbody>
    </table>

    <h4 class="mt-4 text-primary">➕ เพิ่มการลงทะเบียน</h4>
    <form method="post">

      <!-- เลือกนักศึกษา -->
      <select name="student_id" class="form-control mb-2" required>
        <option value="">-- เลือกนักศึกษา --</option>
        <?php while ($s = $students->fetch_assoc()): ?>
          <option value="<?= $s['student_id'] ?>">
            <?= $s['student_id']." - ".$s['first_name']." ".$s['last_name'] ?>
          </option>
        <?php endwhile; ?>
      </select>

      <!-- เลือกรายวิชา -->
      <select name="subject_id" class="form-control mb-2" required>
        <option value="">-- เลือกรายวิชา --</option>
        <?php while ($sub = $subjects->fetch_assoc()): ?>
          <option value="<?= $sub['subject_id'] ?>">
            <?= $sub['subject_id']." - ".$sub['subject_name']." (".$sub['credits']." หน่วย) - ".$sub['t_first']." ".$sub['t_last'] ?>
          </option>
        <?php endwhile; ?>
      </select>

      <input type="number" name="semester" placeholder="เทอม" class="form-control mb-2" required>
      <input type="number" name="year" placeholder="ปีการศึกษา" class="form-control mb-2" required>

      <button name="add" class="btn btn-success">บันทึก</button>
    </form>
  </div>
</div>

<br><a href="index.php" class="btn btn-secondary">⬅ กลับเมนู</a>
</body>
</html>
