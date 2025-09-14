<?php include "header.php";

/* CREATE */
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $student  = intval($_POST['student_id'] ?? 0);
  $subject  = intval($_POST['subject_id'] ?? 0);
  $semester = trim($_POST['semester'] ?? "");
  $year     = intval($_POST['year'] ?? 0);

  if ($student>0 && $subject>0 && $semester!=="" && $year>0) {
    $stmt = $mysqli->prepare("INSERT INTO registration (student_id,subject_id,semester,year) VALUES (?,?,?,?)");
    $stmt->bind_param("iisi", $student,$subject,$semester,$year);
    try {
      $stmt->execute();
      echo '<div class="card">✅ ลงทะเบียนสำเร็จ</div>';
    } catch (mysqli_sql_exception $e) {
      echo '<div class="card">⚠️ ไม่สามารถลงทะเบียนซ้ำในวิชา/ภาคเรียน/ปีเดียวกันได้</div>';
    }
  }
}

/* DELETE */
if (isset($_GET['del'])) {
  $id = intval($_GET['del']);
  $stmt = $mysqli->prepare("DELETE FROM registration WHERE registration_id=?");
  $stmt->bind_param("i",$id);
  $stmt->execute();
  echo '<div class="card">🗑️ ยกเลิกการลงทะเบียนแล้ว</div>';
}

/* dropdown data */
$students = $mysqli->query("SELECT student_id, CONCAT(first_name,' ',last_name) AS sname FROM student ORDER BY sname");
$subjects = $mysqli->query("SELECT subject_id, subject_name FROM subject ORDER BY subject_name");
?>
<div class="card">
<h3>ลงทะเบียนเรียน</h3>
<form method="post" class="grid2">
  <div>
    <label>นักเรียน</label>
    <select name="student_id" required>
      <option value="">— เลือกนักเรียน —</option>
      <?php while($s=$students->fetch_assoc()): ?>
        <option value="<?php echo $s['student_id']; ?>"><?php echo h($s['sname']); ?></option>
      <?php endwhile; ?>
    </select>
  </div>
  <div>
    <label>รายวิชา</label>
    <select name="subject_id" required>
      <option value="">— เลือกวิชา —</option>
      <?php while($sub=$subjects->fetch_assoc()): ?>
        <option value="<?php echo $sub['subject_id']; ?>"><?php echo h($sub['subject_name']); ?></option>
      <?php endwhile; ?>
    </select>
  </div>
  <div><input name="semester" placeholder="ภาคเรียน (เช่น 1 หรือ 2/3)" required></div>
  <div><input type="number" name="year" placeholder="ปีการศึกษา" required value="<?php echo date('Y'); ?>"></div>
  <div style="grid-column:1/3">
    <button class="btn primary">ลงทะเบียน</button>
  </div>
</form>
</div>

<div class="card">
<h3>รายการลงทะเบียน</h3>
<table>
<tr><th>ID</th><th>นักเรียน</th><th>วิชา</th><th>ภาค/ปี</th><th>จัดการ</th></tr>
<?php
$sql = "SELECT r.registration_id, r.semester, r.year,
               CONCAT(s.first_name,' ',s.last_name) AS sname,
               sub.subject_name
        FROM registration r
        JOIN student s ON r.student_id=s.student_id
        JOIN subject sub ON r.subject_id=sub.subject_id
        ORDER BY r.registration_id DESC";
$q = $mysqli->query($sql);
while($r=$q->fetch_assoc()): ?>
<tr>
  <td><?php echo $r['registration_id']; ?></td>
  <td><?php echo h($r['sname']); ?></td>
  <td><?php echo h($r['subject_name']); ?></td>
  <td><?php echo h($r['semester']."/".$r['year']); ?></td>
  <td>
    <a class="btn danger" href="?del=<?php echo $r['registration_id']; ?>" onclick="return confirm('ยกเลิกการลงทะเบียนนี้?')">ลบ</a>
  </td>
</tr>
<?php endwhile; ?>
</table>
</div>
</div><!-- /.wrap -->
</body>
</html>
