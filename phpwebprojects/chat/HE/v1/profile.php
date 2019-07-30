<?php 

 require_once 'Engine/connectdb.php';
 
 $res2=mysqli_query($conn,"SELECT * FROM users WHERE userName='".$_GET['uname']. "'");
 $userRow2=mysqli_fetch_array($res2);
 
 $TmpUser2= htmlspecialchars($userRow2['userName']);
 
 if($_GET['uname'] == $TmpUser2){ echo "משתמש רשום"; } else { echo ""; }
 
 ?>