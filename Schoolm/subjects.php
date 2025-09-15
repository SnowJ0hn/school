<?php 
include 'config.php'; 

if (isset($_POST['add'])) {
    $sql = "INSERT INTO Subject (subject_name, credits, teacher_id)
            VALUES ('{$_POST['subject_name']}', '{$_POST['credits']}', '{$_POST['teacher_id']}')";
    $conn->query($sql);
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM Subject WHERE subject_id = {$_GET['delete']}");
}

$result = $conn->query("SELECT sub.*, t.first_name AS t_first, t.last_name AS t_last 
                        FROM Subject sub
                        LEFT JOIN Teacher t ON sub.teacher_id = t.teacher_id");
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการวิชา</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">

<div class="card shadow-lg">
  <div class="card-header bg-dark text-white">📘 รายวิชา</div>
  <div class="card-body">
    <table class="table table-hover">
      <thead class="table-primary">
        <tr>
          <th>ID</th><th>ชื่อวิชา</th><th>หน่วยกิต</th><th>อาจารย์ผู้สอน</th><th class="text-center">การจัดการ</th>
        </tr>
      </thead>
      <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['subject_id'] ?></td>
          <td><?= $row['subject_name'] ?></td>
          <td><?= $row['credits'] ?></td>
          <td><?= $row['t_first'] ? $row['t_first']." ".$row['t_last'] : "-" ?></td>
          <td class="text-center">
            <a href="?delete=<?= $row['subject_id'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('ลบวิชานี้?')">ลบ</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>

    <h3>➕ เพิ่มวิชา</h3>
    <form method="post">
      <input type="text" name="subject_name" placeholder="ชื่อวิชา" class="form-control mb-2" required>
      <input type="number" name="credits" placeholder="หน่วยกิต" class="form-control mb-2">
      <input type="number" name="teacher_id" placeholder="รหัสอาจารย์" class="form-control mb-2">
      <button name="add" class="btn btn-success">บันทึก</button>
    </form>
  </div>
</div>

<br><a href="index.php" class="btn btn-secondary">⬅ กลับเมนู</a>
</body>
</html>
