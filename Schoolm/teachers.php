<?php 
include 'config.php'; 

if (isset($_POST['add'])) {
    $sql = "INSERT INTO Teacher (first_name, last_name, email, phone, department)
            VALUES ('{$_POST['first_name']}', '{$_POST['last_name']}', '{$_POST['email']}',
                    '{$_POST['phone']}', '{$_POST['department']}')";
    $conn->query($sql);
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM Teacher WHERE teacher_id = {$_GET['delete']}");
}

$result = $conn->query("SELECT * FROM Teacher");
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการอาจารย์</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">

<div class="card shadow-lg">
  <div class="card-header bg-dark text-white">👨‍🏫 รายชื่ออาจารย์</div>
  <div class="card-body">
    <table class="table table-hover">
      <thead class="table-primary">
        <tr>
          <th>ID</th><th>ชื่อ-นามสกุล</th><th>Email</th><th>Phone</th><th>แผนก</th><th class="text-center">การจัดการ</th>
        </tr>
      </thead>
      <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['teacher_id'] ?></td>
          <td><?= $row['first_name']." ".$row['last_name'] ?></td>
          <td><?= $row['email'] ?></td>
          <td><?= $row['phone'] ?></td>
          <td><?= $row['department'] ?></td>
          <td class="text-center">
            <a href="?delete=<?= $row['teacher_id'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('ลบอาจารย์คนนี้?')">ลบ</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>

    <h3>➕ เพิ่มอาจารย์</h3>
    <form method="post">
      <input type="text" name="first_name" placeholder="ชื่อ" class="form-control mb-2" required>
      <input type="text" name="last_name" placeholder="นามสกุล" class="form-control mb-2" required>
      <input type="email" name="email" placeholder="Email" class="form-control mb-2">
      <input type="text" name="phone" placeholder="เบอร์โทร" class="form-control mb-2">
      <input type="text" name="department" placeholder="แผนก" class="form-control mb-2">
      <button name="add" class="btn btn-success">บันทึก</button>
    </form>
  </div>
</div>

<br><a href="index.php" class="btn btn-secondary">⬅ กลับเมนู</a>
</body>
</html>
