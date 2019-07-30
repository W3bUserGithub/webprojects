<?php
 session_start();
 require_once 'Engine/connectdb.php';

 $TmpBlockMsg = "<font color=purple>נחסם</font>";
 $TmpLoginType = "0";
 $TmpUsername =  htmlspecialchars($_GET['TmpUsername']);
 $TmpSessionID = htmlspecialchars($_GET['TmpSessionID']);
 $TmpIP = htmlspecialchars($_GET['TmpIP']);
 $TmpAdminAction = htmlspecialchars($_GET['AdminAction']);
 $TmpChat = htmlspecialchars($_GET['chat_id']);
 
 $res=mysqli_query($conn,"SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res);
 
 $TmpUser= htmlspecialchars($userRow['userName']);
 
 if(!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
 }
 
 
 $res2=mysqli_query($conn,"SELECT * FROM chatrooms WHERE id=".$_GET['chat_id']);
 $chatRow=mysqli_fetch_array($res2);
 
  if($chatRow['owner'] == $TmpUser){ 
  } else if($userRow['userLevel'] == '10'){ 
  } else {
  header("Location: ChatPortal.php");
  exit;
  }
  
  $resBlocked=mysqli_query($conn,"SELECT * FROM blocked WHERE ip='$ip' AND SessionID='$TmpSessionID' AND chat='$TmpChat'");
  $BlockedUsersCount=mysqli_num_rows($resBlocked);
  
  if($TmpAdminAction == 9){
   if($BlockedUsersCount!=0){
      header('Location: ChatAdminUsers.php?chat_id='.$TmpChat);
} else {
//לחסום
   $query = "INSERT INTO blocked(ip,SessionID,chat) VALUES('$TmpIP','$TmpSessionID','$TmpChat')";
   $res3 = mysqli_query($conn,$query);
   $query2 = "INSERT INTO messages(nickname,message,type,chat,time) VALUES('$TmpUsername','$TmpBlockMsg','$TmpLoginType','$TmpChat','$date')";
   $res4 = mysqli_query($conn,$query2);
   header('Location: ChatAdminUsers.php?chat_id='.$TmpChat);
}
  } else if($TmpAdminAction == 5){
//הסר חסימה
   $res5=mysqli_query($conn,"DELETE FROM blocked WHERE ip='$TmpIP' AND SessionID='$TmpSessionID' AND chat='$TmpChat'");
   header('Location: ChatAdminUsers.php?chat_id='.$TmpChat);
   
 } 
 
 
 ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="rtl">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>ניהול הצאט</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  </head>
 <body>
 <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="ChatAdmin.php">ניהול הצאט</a></li>
            <li><a href="ChatPortal.php">רשימת הצאטים</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right"><?php if(isset($_SESSION['user'])){ ?>
          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <span class="glyphicon glyphicon-user"></span>&nbsp;שלום <?php echo $TmpUser; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
               <li><a href="usersettings.php"><span class="glyphicon glyphicon-cog"></span>&nbsp;הגדרות משתמש</a></li> 
               <li><a href="login.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;התנתק</a></li>
             </ul>
            </li><?php } else { ?>
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
               <span class="glyphicon glyphicon-user"></span>&nbsp;שלום אורח&nbsp;<span class="caret"></span></a>
               <ul class="dropdown-menu">
               <li><a href="login.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;התחבר</a></li>
              </ul>
            </li><?php } ?>
          </ul>
        </div>
      </div>
    </nav> 
  <table>
  <td height="250"></td>
   </table>
   <div class="container">
     <table>
      <th>גולשים פעילים בצאט:</th>
       <tbody>
       	<?php 
        $sqlonline = "SELECT * FROM online
 WHERE DATE_ADD(timestamp, INTERVAL 1 MINUTE) 
>= NOW() AND chat = " .$TmpChat. "
 ORDER by LoginType DESC";
   $res6 = mysqli_query($conn,$sqlonline);  
   while($row = mysqli_fetch_array($res6)){ 
   	echo "<tr>
         <td>" .$row['username']. "</td><td width=20></td>
         <td>" .$row['ip']. "</td><td width=40></td>
         <td><a href=?chat_id=" .$TmpChat. "&AdminAction=9&TmpUsername=" .$row['username']. "&TmpSessionID=" .$row['SessionID']. "&TmpIP=" .$row['ip']. ">חסום משתמש</a></td></tr>"; } ?>
         </tbody>
         </table>
         <br>
          <table>
          <th>חסומים:</th>
       <tbody>
           <?php 
        $sqlUnblock = "SELECT * FROM blocked
 WHERE chat = " .$TmpChat. "
 ORDER by id DESC";
   $res7 = mysqli_query($conn,$sqlUnblock);  
   while($row2 = mysqli_fetch_array($res7)){ 
   	echo "<tr>
         <td>" .$row2['ip']. "</td><td width=40></td>
         <td><a href=?chat_id=" .$TmpChat. "&AdminAction=5&TmpSessionID=" .$row2['SessionID']. "&TmpIP=" .$row2['ip']. ">הסר חסימה</a></td></tr>";
         } ?>
         </tbody>
        </table>
       </div>

      <footer>
     <table height="250" align="center">          
     <td><a href="contact.php" target="_blank">צור איתנו קשר</a></td>
   </table>
  </footer>    
 </body>
</html>