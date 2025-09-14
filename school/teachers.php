<?php include "header.php";

/* CRUD */
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $id = intval($_POST['teacher_id'] ?? 0);
  $first=$_POST['first_name']; $last=$_POST['last_name'];
  $email=$_POST['email']; $phone=$_POST['phone']; $dept=$_POST['department'];

  if ($id>0) {
    $stmt=$mysqli->prepare("UPDATE teacher SET first_name=?,last_name=?,email=?,phone=?,department=? WHERE teacher_id=?");
    $stmt->bind_param("sssssi",$first,$last,$email,$phone,$dept,$id);
  } else {
    $stmt=$mysqli->prepare("INSERT INTO teacher(first_name,last_name,email,phone,department) VALUES(?,?,?,?,?)");
    $stmt->bind_param("sssss",$first,$last,$email,$phone,$dept);
  }
  $stmt->execute();
}
if (isset($_GET['del'])) {
  $id=intval($_GET['del']);
  $stmt=$mysqli->prepare("DELETE FROM teacher WHERE teacher_id=?");
  $stmt->bind_param("i",$id); $stmt->execute();
}

$edit=null;
if (isset($_GET['edit'])) {
  $id=intval($_GET['edit']);
  $res=$mysqli->prepare("SELECT * FROM teacher WHERE teacher_id=?");
  $res->bind_param("i",$id); $res->execute();
  $edit=$res->get_result()->fetch_assoc();
}
?>
<div class="card">
<h3><?php echo $edit?"แก้ไข":"เพิ่ม";?> อาจารย์</h3>
<form method="post" class="grid2">
  <input type="hidden" name="teacher_id" value="<?php echo h($edit['teacher_id']??0); ?>">
  <div><input name="first_name" placeholder="ชื่อ" value="<?php echo h($edit['first_name']??""); ?>"></div>
  <div><input name="last_name" placeholder="นามสกุล" value="<?php echo h($edit['last_name']??""); ?>"></div>
  <div><input name="email" placeholder="Email" value="<?php echo h($edit['email']??""); ?>"></div>
  <div><input name="phone" placeholder="โทร" value="<?php echo h($edit['phone']??""); ?>"></div>
  <div><input name="department" placeholder="ภาควิชา" value="<?php echo h($edit['department']??""); ?>"></div>
  <div><button class="btn primary">บันทึก</button></div>
</form>
</div>
<link rel="stylesheet" href="style.css">

<div class="card">
<h3>รายการอาจารย์</h3>
<table>
<tr><th>ID</th><th>ชื่อ</th><th>ภาควิชา</th><th>จัดการ</th></tr>
<?php $q=$mysqli->query("SELECT * FROM teacher ORDER BY teacher_id DESC");
while($r=$q->fetch_assoc()): ?>
<tr>
  <td><?php echo $r['teacher_id'];?></td>
  <td><?php echo h($r['first_name']." ".$r['last_name']);?></td>
  <td><?php echo h($r['department']);?></td>
  <td>
    <a class="btn" href="?edit=<?php echo $r['teacher_id'];?>">แก้ไข</a>
    <a class="btn danger" href="?del=<?php echo $r['teacher_id'];?>" onclick="return confirm('ลบ?')">ลบ</a>
  </td>
</tr>
<?php endwhile;?>
</table>
</div>
</div><!-- /.wrap -->
</body>
</html>
