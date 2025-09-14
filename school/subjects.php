<?php include "header.php";

/* CREATE / UPDATE */
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $id = intval($_POST['subject_id'] ?? 0);
  $name = trim($_POST['subject_name'] ?? "");
  $credits = intval($_POST['credits'] ?? 0);
  $teacher = ($_POST['teacher_id'] === '' ? null : intval($_POST['teacher_id']));

  if ($id>0) {
    $stmt = $mysqli->prepare("UPDATE subject SET subject_name=?, credits=?, teacher_id=? WHERE subject_id=?");
    $stmt->bind_param("siii", $name, $credits, $teacher, $id);
  } else {
    $stmt = $mysqli->prepare("INSERT INTO subject (subject_name, credits, teacher_id) VALUES (?,?,?)");
    $stmt->bind_param("sii", $name, $credits, $teacher);
  }
  $stmt->execute();
}

/* DELETE */
if (isset($_GET['del'])) {
  $id = intval($_GET['del']);
  $stmt = $mysqli->prepare("DELETE FROM subject WHERE subject_id=?");
  $stmt->bind_param("i",$id);
  $stmt->execute();
}

/* EDIT LOAD */
$edit = null; // ✅ ป้องกัน error
if (isset($_GET['edit'])) {
  $id = intval($_GET['edit']);
  $res = $mysqli->prepare("SELECT * FROM subject WHERE subject_id=?");
  $res->bind_param("i",$id);
  $res->execute();
  $edit = $res->get_result()->fetch_assoc();
}

/* teacher list */
$teachers = $mysqli->query("SELECT teacher_id, first_name, last_name FROM teacher ORDER BY first_name, last_name");
?>
<div class="card">
<h3><?php echo $edit ? "แก้ไขรายวิชา" : "เพิ่มรายวิชา"; ?></h3>
<form method="post" class="grid2">
  <input type="hidden" name="subject_id" value="<?php echo h($edit['subject_id'] ?? 0); ?>">
  <div><input name="subject_name" placeholder="ชื่อวิชา" required value="<?php echo h($edit['subject_name'] ?? ""); ?>"></div>
  <div><input type="number" name="credits" placeholder="หน่วยกิต" required value="<?php echo h($edit['credits'] ?? 3); ?>"></div>
  <div style="grid-column:1/3">
    <select name="teacher_id">
      <option value="">— เลือกอาจารย์ผู้สอน —</option>
      <?php while($t=$teachers->fetch_assoc()):
        $sel = ($edit && $edit['teacher_id']==$t['teacher_id']) ? "selected" : "";
        echo "<option value='{$t['teacher_id']}' $sel>".h($t['first_name']." ".$t['last_name'])."</option>";
      endwhile;?>
    </select>
  </div>
  <div><button class="btn primary">บันทึก</button></div>
</form>
</div>

<div class="card">
<h3>รายวิชา</h3>
<table>
<tr><th>ID</th><th>ชื่อวิชา</th><th>หน่วยกิต</th><th>ผู้สอน</th><th>จัดการ</th></tr>
<?php
$sql = "SELECT s.*, CONCAT(t.first_name,' ',t.last_name) AS tname
        FROM subject s
        LEFT JOIN teacher t ON s.teacher_id=t.teacher_id
        ORDER BY s.subject_id DESC";
$q = $mysqli->query($sql);
while($r=$q->fetch_assoc()): ?>
<tr>
  <td><?php echo $r['subject_id']; ?></td>
  <td><?php echo h($r['subject_name']); ?></td>
  <td><?php echo h($r['credits']); ?></td>
  <td><?php echo h($r['tname'] ?? "-"); ?></td>
  <td>
    <a class="btn" href="?edit=<?php echo $r['subject_id']; ?>">แก้ไข</a>
    <a class="btn danger" href="?del=<?php echo $r['subject_id']; ?>" onclick="return confirm('ลบ?')">ลบ</a>
  </td>
</tr>
<?php endwhile; ?>
</table>
</div>
</div><!-- /.wrap -->
</body>
</html>
