<?php
 session_start();
 require_once 'Engine/connectdb.php';
 
 $res=mysqli_query($conn,"SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res);
 
 $ress=mysqli_query($conn,"SELECT * FROM chatrooms WHERE id=".$_POST['chat_id']);
 $chatRoww=mysqli_fetch_array($ress);
  
  
 $G_Chat_ID = htmlspecialchars($_GET['chat_id']);
 $chat_id = htmlspecialchars($_POST['chat_id']);
 $username = htmlspecialchars($_POST['LoginNickName']);
  $host = "http://" .$_SERVER['HTTP_HOST']. "/ChatPortal.php";
  if($_SERVER['HTTP_REFERER'] == $host){

 if($chatRoww['owner'] == $userRow['userName']){ 
  $LoginType =  "4";
 } else if(isset($_SESSION['user'])) { 
  $LoginType =  "2"; 
 } else if(!isset($_SESSION['user'])) { 
  $LoginType = "1";
 } 
 
 } else {
 $LoginType = htmlspecialchars($_POST['LoginUserType']);
 }
 
 $SessionID = htmlspecialchars(trim($_COOKIE['PHPSESSID']));
  $LoginTxt = "<font color=green>התחבר</font>";
  $LoginType2 = "0";
 $TmpUser= htmlspecialchars($userRow['userName']);
 
   $resBlocked=mysqli_query($conn,"SELECT * FROM blocked WHERE ip='$ip' AND SessionID='$SessionID' AND chat='$chat_id'");
  $BlockedUsers=mysqli_fetch_array($resBlocked);
 
 if(isset($_POST['BtnExit'])){
 if(($BlockedUsers['ip'] == $ip || $BlockedUsers['SessionID'] == $SessionID)){ 
 $ExitQuery= "DELETE FROM online WHERE chat = '$C' AND SessionID='$SessionID'";
 $ExitResult = mysqli_query($conn,$ExitQuery);
 header("Location: ChatPortal.php");
 } else {
 $C = htmlspecialchars($_POST['chat_id']);
 $U = htmlspecialchars($_POST['username']);
 $L = htmlspecialchars($_POST['LoginType']);
 $ExitMsg = "<font color=red>התנתק</font>";
 $LoginTypeExit = "0";
 
 $ExitQuery2 = "INSERT INTO messages(nickname,message,type,chat,time) VALUES('$U','$ExitMsg','$LoginTypeExit','$C','$date')";
 $ExitResult2 = mysqli_query($conn,$ExitQuery2); 
 $ExitQuery= "DELETE FROM online WHERE chat = '$C' AND SessionID='$SessionID'";
 $ExitResult = mysqli_query($conn,$ExitQuery);

header("Location: ChatPortal.php");
 
 }
 }

 if(isset($_POST['SubmitLogin'])){
     
 $res2=mysqli_query($conn,"SELECT * FROM chatrooms WHERE id=".$_POST['chat_id']);
 $chatRow=mysqli_fetch_array($res2);
 
 $query = "SELECT SessionID,chat,username FROM online WHERE DATE_ADD(timestamp, INTERVAL 1 MINUTE) 
>= NOW() AND SessionID='$SessionID' AND chat = '$chat_id'";
 $res3 = mysqli_query($conn,$query);
 $count = mysqli_num_rows($res3);
 
  if(($BlockedUsers['ip'] == $ip || $BlockedUsers['SessionID'] == $SessionID)){
  } else {
   if($count!=0){
    
   $query2 = "UPDATE online SET ip = '$ip', username = '$username' , LoginType = '$LoginType' timestamp = NOW() WHERE SessionID='$SessionID'";
   $res4 = mysqli_query($conn,$query2);
   } else {
   $query3 = "INSERT INTO online(username,LoginType,SessionID,chat,ip) VALUES('$username','$LoginType','$SessionID','$chat_id','$ip')";
   $res5 = mysqli_query($conn,$query3); 
   $queryLogin = "INSERT INTO messages(nickname,message,type,chat,time) VALUES('$username','$LoginTxt','$LoginType2','$chat_id','$date')";
   $resLogin = mysqli_query($conn,$queryLogin); 
   $rees1200 = mysqli_query($conn,"UPDATE chatrooms SET LastActivityView = NOW() WHERE id='$chat_id'");
   }
  }
  
   } else {   
  $res6=mysqli_query($conn,"SELECT * FROM chatrooms WHERE id=".$_GET['chat_id']);
  $chatRow=mysqli_fetch_array($res6);
 }
 
 //פרטי הצאט
 $info1 = $chatRow['title'];
 $info2 = $chatRow['description'];
 $info3 = $chatRow['url'];
 $info4 = $chatRow['image'];
 
 if($chatRow['timestamp'] == ''){
     die('אין תוכן');
     exit;
 } else if($chatRow['ChatStatus'] == '9'){
  die('הצאט נחסם זמנית <a href=contact.php> צור קשר </a>');
  exit;
 }
 
 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="rtl">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $info1; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script><?php if(isset($_POST['SubmitLogin'])) { ?>
  <script language="javascript" src="GeneralChatScript.js"></script>
  <script type="text/javascript" src="resources/jscolor.js"></script><?php } ?>
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
  <td height="50"></td>
   </table>
  <center>
  <h1 class="text-center"><?php echo $info1; ?></h1>
  <h2 class="text-center"><?php echo $info2; ?></h2>
  <h3 class="text-center"><?php echo $info3; ?></h3>
  </center>
   <?php if(!isset($_POST['SubmitLogin'])) { ?>
   <br>
             <div class="form-group">
             <div align="center">
              <form action="?act=2" method="POST">
              <table><?php if(isset($_SESSION['user'])) { 
               echo "<td><input type=hidden id=LoginNickName name=LoginNickName class=form-control value=" .$userRow['userName']. " required></td>"; } else {
               echo "<td><input type=text id=LoginNickName name=LoginNickName class=form-control required></td>";} ?>
               
              <td><input type="hidden" id="LoginUserType" name="LoginUserType" class="form-control"  value="<?php if($chatRow['owner'] == $userRow['userName']){ echo "4"; } else if(isset($_SESSION['user'])) { echo "2"; } else if(!isset($_SESSION['user'])) { echo "1"; } ?>" style="display:none" required></td>
              <td><input type="hidden" id="chat_id" name="chat_id" class="form-control"  value="<?php echo $G_Chat_ID; ?>" style="display:none" required></td>
              <td><input type="submit" id="SubmitLogin" name="SubmitLogin" class="btn btn-primary" value="התחבר"></td>
            </form>
           </div>
          </div>
         </table>
        <table height="250" align="center">          
         <td><a href="contact.php" target="_blank">צור איתנו קשר</a></td>
        </table>
        </body>
       </html><?php } else { ?>
     <style>
        BODY{background-image: url(<?php echo $info4; ?>);}
       .ChatTable_Div_Users{width: 180px;height:220px;}
       .DivBlock_ChatMsg{height: 300px;width: 500px;}
       .ChatTable_Div_Chat{word-break: break-word;height:350px;width: 740px;font-size:14px;font-color:#444444;color:#444444;font-family: 'Arial',Arial}
       .ChatPanelInputDiv{width: 500px;}
     </style>
    <div class="container">
    <div class="row " style="padding-top:10px;">
    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-body scrollcss" id="div_chat_parent" style="overflow: auto;max-height:400px;height:400px;">
           <div id="div_chat"></div>
            </div>
            <div class="panel-footer">
                <div class="input-group">
                    <input type="text" name="txt_message" id="txt_message" class="form-control" onkeypress="javascript:if(event.keyCode === 13){sendChatText();return false;} ">
                    <span class="input-group-btn">
                    <button class="btn btn" type="button" onclick="javascript:sendChatText();">שלח</button>
                  </span>
                 </div>
               </div>
            </div>
        </div>
    
    
     <div class="col-md-4">
          <div class="panel panel-primary">
            <div class="panel-heading">
               מחוברים בצאט          
            </div>
            <div class="panel-body">
            <div id="div_users"></div>
            </div>
            </div>
          </div>
        </div>
       </div>
       <table align="center" cellpadding="0" cellspacing="0" border="0">
        <td class="DivBlock_Buttons" colspan="3" valign="top">        
    		<table cellpadding="2" border="0" align="center" style="border:0;" id="Table2">
				<tbody><tr>
					<td align="center" width="" valign="top">				
						צבע כתב:<br>
						<input class="color form-control" id="TxtColor" name="color" size="7" maxlength="7" value="444444" > 
					</td>
					<td align="center" width="" valign="top">
						כתובת תמונה מצורפת:<br>
						<input type="text" class="form-control" id="TxtImage" name="TxtImage"  dir="ltr">
					</td>
				</tr>
			</tbody></table>
			<table>
			 <td style="display:none"><input type="hidden" class="form-control"  id="chat_id" name="chat_id" value="<?php echo $chat_id; ?>" style="display:none"></td>
			 <td style="display:none"><input type="hidden" class="form-control"  id="username" name="username" value="<?php echo $username; ?>" style="display:none"></td>
			 <td style="display:none"><input type="hidden" class="form-control"  id="nickname" name="nickname" value="<?php echo $username; ?>" style="display:none"></td>
			 <td style="display:none"><input type="hidden" class="form-control"  id="LoginType" name="LoginType" value="<?php echo $LoginType; ?>" style="display:none"></td>
			 <td style="display:none"><input type="hidden" class="form-control"  id="Islogin" name="Islogin" value="true" style="display:none"></td>
			 <td style="display:none"><input type="hidden" class="form-control"  id="SessionID" name="SessionID" value="" style="display:none"></td>
			</table>
		 </form>
        <br>
    	<div class="row">
    	<table>
    	<td>
    	<form action="?act=9" method="POST">
    	<input type="hidden" class="form-control"  id="chat_id" name="chat_id" value="<?php echo $chat_id; ?>" style="display:none">
    	<input type="hidden" class="form-control"  id="username" name="username" value="<?php echo $username; ?>" style="display:none">
    	<input type="hidden" class="form-control"  id="LoginType" name="LoginType" value="<?php echo $LoginType; ?>" style="display:none">
    	<input type="submit" name="BtnExit" id="BtnExit" class="btn btn-info" value="יציאה">
       </form></td>
       <td>
       <a href="contact.php" target="_blank" class="btn btn-success">דווח</a>
       </td>
      <td>
    	<?php
    	  if($chatRoww['owner'] == $userRow['userName']){ 
  $TmpINPT =  "<a href=ChatAdminUsers.php?chat_id=" .$chat_id. " class='btn btn-warning' target='_blank'>ניהול הצאט</a>";
 } else if(isset($_SESSION['user'])) { 
 $TmpINPT =  ""; 
 } else if(!isset($_SESSION['user'])) { 
 $TmpINPT = "";
 } 
 
 echo $TmpINPT;
        ?>
      	</td>
           <td>
        <?php
    	  if($chatRoww['owner'] == $userRow['userName']){ 
  $TmpINPT2 =  "<a href='ChatAdmin.php' class='btn btn-default' target='_blank'>הגדרות הצאט</a>";
 } else if(isset($_SESSION['user'])) { 
 $TmpINPT2 =  ""; 
 } else if(!isset($_SESSION['user'])) { 
 $TmpINPT2 = "";
 } 
 
 echo $TmpINPT2;
        ?>
      	</td>
        </table>
      </div>
       </table>
       
         <footer>
           <table height="150" align="center">          
            <td><a href="contact.php" target="_blank">צור איתנו קשר</a></td>
           </table>
         </footer>    
       <script language="javascript">
	     StartChat();
    </script>
  </body>
</html><?php } ?>