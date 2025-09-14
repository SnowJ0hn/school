<?php include "header.php";

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $id=intval($_POST['student_id']??0);
  $first=$_POST['first_name']; $last=$_POST['last_name'];
  $birth=$_POST['birth']??null; $email=$_POST['email'];
  $phone=$_POST['phone']; $addr=$_POST['address'];
  $advisor=$_POST['teacher_id']!==""?intval($_POST['teacher_id']):null;

  if ($id>0){
    $stmt=$mysqli->prepare("UPDATE student SET first_name=?,last_name=?,birth=?,email=?,phone=?,address=?,teacher_id=? WHERE student_id=?");
    $stmt->bind_param("ssssssii",$first,$last,$birth,$email,$phone,$addr,$advisor,$id);
  } else {
    $stmt=$mysqli->prepare("INSERT INTO student(first_name,last_name,birth,email,phone,address,teacher_id) VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssi",$first,$last,$birth,$email,$phone,$addr,$advisor);
  }
  $stmt->execute();
}

if(isset($_GET['del'])){
  $id=intval($_GET['del']);
  $stmt=$mysqli->prepare("DELETE FROM student WHERE student_id=?");
  $stmt->bind_param("i",$id); $stmt->execute();
}

$edit=null;
if(isset($_GET['edit'])){
  $id=intval($_GET['edit']);
  $res=$mysqli->prepare("SELECT * FROM student WHERE student_id=?");
  $res->bind_param("i",$id); $res->execute();
  $edit=$res->get_result()->fetch_assoc();
}

$teachers=$mysqli->query("SELECT teacher_id,first_name,last_name FROM teacher");
?>
<div class="card">
<h3><?php echo $edit?"แก้ไข":"เพิ่ม";?> นักเรียน</h3>
<form method="post" class="grid2">
  <input type="hidden" name="student_id" value="<?php echo h($edit['student_id']??0);?>">
  <div><input name="first_name" placeholder="ชื่อ" value="<?php echo h($edit['first_name']??"");?>"></div>
  <div><input name="last_name" placeholder="นามสกุล" value="<?php echo h($edit['last_name']??"");?>"></div>
  <div><input type="date" name="birth" value="<?php echo h($edit['birth']??"");?>"></div>
  <div><input name="email" placeholder="Email" value="<?php echo h($edit['email']??"");?>"></div>
  <div><input name="phone" placeholder="โทร" value="<?php echo h($edit['phone']??"");?>"></div>
  <div style="grid-column:1/3"><textarea name="address" placeholder="ที่อยู่"><?php echo h($edit['address']??"");?></textarea></div>
  <div style="grid-column:1/3">
    <select name="teacher_id">
      <option value="">— เลือกอาจารย์ที่ปรึกษา —</option>
      <?php while($t=$teachers->fetch_assoc()):
        $sel=($edit && $edit['teacher_id']==$t['teacher_id'])?"selected":"";
        echo "<option value='{$t['teacher_id']}' $sel>".h($t['first_name']." ".$t['last_name'])."</option>";
      endwhile;?>
    </select>
  </div>
  <div><button class="btn primary">บันทึก</button></div>
</form>
</div>
<link rel="stylesheet" href="style.css">

<div class="card">
<h3>รายชื่อนักเรียน</h3>
<table>
<tr><th>ID</th><th>ชื่อ</th><th>ที่ปรึกษา</th><th>จัดการ</th></tr>
<?php $q=$mysqli->query("SELECT s.*, CONCAT(t.first_name,' ',t.last_name) AS advisor FROM student s LEFT JOIN teacher t ON s.teacher_id=t.teacher_id ORDER BY student_id DESC");
while($r=$q->fetch_assoc()): ?>
<tr>
  <td><?php echo $r['student_id'];?></td>
  <td><?php echo h($r['first_name']." ".$r['last_name']);?></td>
  <td><?php echo h($r['advisor']??"-");?></td>
  <td>
    <a class="btn" href="?edit=<?php echo $r['student_id'];?>">แก้ไข</a>
    <a class="btn danger" href="?del=<?php echo $r['student_id'];?>" onclick="return confirm('ลบ?')">ลบ</a>
  </td>
</tr>
<?php endwhile;?>
</table>
</div>
</div><!-- /.wrap -->
</body>
</html>
