<?php 
 session_start();
 require_once 'Engine/connectdb.php';

 $res=mysqli_query($conn,"SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res);
 
  $TmpUser= htmlspecialchars($userRow['userName']);
 
 if(!isset($_SESSION['user'])){
 header("Location: login.php");
 }
 
  $error = false;

 if ( isset($_POST['btn-update']) ) {
  
  
  $email = htmlspecialchars($_POST['email']);
  $pass = htmlspecialchars($_POST['pass']);
  $newpass = htmlspecialchars($_POST['newpass']);

  
  
  if(empty($email)){
   $email = $userRow['userEmail'];
  } else {
   $query = "SELECT userEmail FROM users WHERE userEmail='$email'";
   $result = mysqli_query($conn,$query);
   $count = mysqli_num_rows($result);
   if($count!=0){
    $error = true;
    $emailError = "אמייל כבר בשימוש.";
   }
  } 
  
  if (empty($pass)){
   $error = true;
   $passError = "הכנס סיסמה.";
  } else if(strlen($pass) < 6) {
   $error = true;
   $passError = "סיסמה חייבת להכיל מעל 6 תווים.";
  } else if($userRow['userPass'] == $pass){
  } else {
   $error = true;
   $passError = "סיסמה שגוייה";
  }
 
  
    if(empty($newpass)){
    $newpass = $userRow['userPass'];
  }  else if(strlen($newpass) < 6) {
   $error = true;
   $pass2Error = "סיסמה חייבת להכיל מעל 6 תווים.";
  }
  
  
   if( !$error ) {
   $query = "UPDATE users SET userEmail = '$email' , userPass = '$newpass' WHERE userName = '" .$userRow['userName']. "'";
   $res = mysqli_query($conn,$query);
    
   if ($res) {
    $errTyp = "success";
    $errMSG = "פרטי המשתמש עודכנו";
    unset($email);
    unset($pass);
    unset($newpass);
   } else {
    $errTyp = "danger";
    $errMSG = "משהו השתבש."; 
   } 
    
  }
  
  
 }
 

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>עדכון פרטים</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="resources/StyleChat.css">
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
  
  <div class="container">
   <div id="login-form">
    <form action="?action=update" method="POST">
     <div class="col-md-12"> 
      <div class="form-group">
       <h2 class="text-center">עדכון פרטי משתמש</h2>
        </div>
        
          <div class="form-group">
             <hr>
             </div>
            
            <?php
   if ( isset($errMSG) ) {
    
    ?>
    <div class="form-group">
             <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
             </div>
                <?php
   }
   ?>
                      
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                 <input type="email" name="email" class="form-control" placeholder="אמייל (השאר ריק אם אתה לא רוצה לשנות)" maxlength="120" value="<?php echo $email ?>">
                </div>
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                <input type="password" name="pass" class="form-control" placeholder="סיסמה נוכחית" maxlength="20" required>
                </div>
                <span class="text-danger"><?php echo $passError; ?></span>
            </div>
            
                     <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                <input type="password" name="newpass" class="form-control" placeholder="סיסמה חדשה (השאר ריק אם אתה לא רוצה לשנות)" maxlength="20">
                </div>
                <span class="text-danger"><?php echo $pass2Error; ?></span>
            </div>
            
            <div class="form-group">
             <hr>
            </div>
            
           <div class="form-group">
             <button type="submit" class="btn btn-block btn-primary" name="btn-update">עדכון פרטים</button>
            </div>
            
              <div class="form-group">
                <hr>
           </div>
        </div>
    </form>
   </div> 
     </div> 
           <footer>
           <table height="250" align="center">          
         <td><a href="contact.php" target="_blank">צור איתנו קשר</a></td>
           </table>
          </footer>    
  </body>
</html>