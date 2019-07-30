<?php 
 session_start();
 require_once 'Engine/connectdb.php';
 
 $G_Chat_ID = htmlspecialchars($_GET['chat']);
 
 $res=mysqli_query($conn,"SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res);
 
 $res2=mysqli_query($conn,"SELECT * FROM chatrooms WHERE id=" .$G_Chat_ID);
 $chatRow=mysqli_fetch_array($res2);
 
 if($chatRow['ChatStatus'] == '9'){
  die('הצאט נחסם זמנית <a href=contact.php> צור קשר </a>');
  exit;
 }
 
 
 $G_Chat_Title= htmlspecialchars($chatRow['title']);
 $G_Chat= htmlspecialchars($chatRow['id']);
 $TmpUser= htmlspecialchars($userRow['userName']);
 

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>היסטורייה  - <?php echo $G_Chat_Title; ?></title>
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
  <td height="50"></td>
  </table>
  <div id="wrapper">
   <div class="container">    
    <div class="page-header">
     <h3 class="text-center"><?php echo $chatRow['title']; ?></h3>
     <table>
     <td height="10"></td>
     </table>
     <center>
     <form action="chat.php?act=2" method="POST">
      <table>
        <tr>
            <td>בחר צאט:</td>
            <td><select id="chat_id" name="chat_id" class="form-control">
            <?php $query = mysqli_query($conn,"SELECT * FROM chatrooms ORDER BY title");
            while($chtrow=mysqli_fetch_array($query)){
               $G_chat_Title2= trim($chtrow['title']);
               $G_chat_Title2 = strip_tags($G_chat_Title2);
               $G_chat_Title2 = htmlspecialchars($G_chat_Title2);
               $G_chat_ID2= trim($chtrow['id']);
               $G_chat_ID2 = strip_tags($G_chat_ID2);
               $G_chat_ID2 = htmlspecialchars($G_chat_ID2);
           
         	if($G_chat_Title2 == ''){
          	} else {
          	if($G_chat_ID2 == $G_Chat){
              echo "<option value=" .$G_chat_ID2. " selected>" .$G_chat_Title2. "</option>
              ";
          	} else { 
              echo "<option value=" .$G_chat_ID2. ">" .$G_chat_Title2. "</option>
              ";
             }
           }
        }
        ?>
               </select>
             </td>
           </tr><?php if(!isset($_SESSION['user'])){ ?>
             <tr>           
				<td>כינוי:</td>
			    <td style="display: none;"><input type="hidden" id="LoginUserType" name="LoginUserType" class="form-control"  value="<?php if($chatRow['owner'] == $userRow['userName']){
                echo "4";
                } else if(isset($_SESSION['user'])) {
                echo "2";    
                } else if(!isset($_SESSION['user'])) {
                echo "1"; 
                } ?>" style="display:none" required></td>
				<td><input type="text" class="form-control" id="LoginNickName" name="LoginNickName" size="20" maxlength="20" required></td>          
            </tr>
           <tr>
            <td>&nbsp;</td>
            <td><input type="submit" class="btn btn-primary" value="התחבר" id="SubmitLogin" name="SubmitLogin"></td>
            <td>&nbsp;</td>
            </tr><?php } else { ?>
            <tr>           
			   <td style="display:none;">
               <input type="hidden" class="form-control" id="LoginNickName" name="LoginNickName" value="<?php echo $TmpUser; ?>" size="20" maxlength="20" required></td>          
               <td style="display: none;"><input type="hidden" id="LoginUserType" name="LoginUserType" class="form-control"  value="<?php if($chatRow['owner'] == $userRow['userName']){ echo "4"; } else if(isset($_SESSION['user'])) { echo "2"; } else if(!isset($_SESSION['user'])) { echo "1"; } ?>" style="display:none" required></td>
            </tr>
             <tr>
            <td>&nbsp;</td>
            <td><input type="submit" class="btn btn-primary" value="התחבר" id="SubmitLogin" name="SubmitLogin"></td>
            <td>&nbsp;</td>
           </tr><?php } ?>
         </table>
       </form>
     </center>
     </div>
     <table>
     <td height="10"></td>
     </table>
       <table>
         <tbody>
         	
         
          <tr>
          <?php
           $res3=mysqli_query($conn,"SELECT * FROM messages WHERE chat=" .$_GET['chat']. " AND timestamp >= NOW()- INTERVAL 1 DAY ORDER BY id DESC LIMIT 300");
           while($msgRow=mysqli_fetch_array($res3)){
            echo "<tr>
             <td>" .$msgRow['nickname']. ":&nbsp;</td><td width=20></td>
             <td>" .$msgRow['message']. "</td><td width=100></td>
             <td>" .$msgRow['time']. "</td>
             </tr>";
             } 
           ?>
           	
           
                  </tbody>
                </table>
              </div>
            </div>
            
           <footer>
           <table height="250" align="center">          
           <td><a href="contact.php" target="_blank">צור איתנו קשר</a></td>
           </table>
          </footer>    
        </div>
      </div>
    </body>
  </html>