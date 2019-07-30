<?php 
 session_start();
 require_once 'Engine/connectdb.php';

 $res=mysqli_query($conn,"SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res);
 
 $TmpUser= htmlspecialchars($userRow['userName']);
 $TmpEmail= htmlspecialchars($userRow['userEmail']);

 
 
 $error = false;
 
 if (isset($_POST['btn-contact'])) {
  
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $content = htmlspecialchars($_POST['content']);

  if (empty($name)) {
   $error = true;
   $nameError = "הכנס כינוי.";
  } 
  
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "כתובת דואר אלקטרוני לא תקינה";
  } 
  
  if (empty($content)) {
   $error = true;
   $contentError = "הכנס תווים.";
  } 
  
  
  if( !$error ) {
   
   $query = "INSERT INTO reports(nickname,text,email,time) VALUES('$name','$content','$email','$date')";
   $res = mysqli_query($conn,$query);
    
   if ($res) {
    $errTyp = "success";
    $errMSG = "<h5>ההודעה שלך נשלחה בהצלחה אנו נשוב אלייך הכי מהר שנוכל.</h5>";
    unset($name);
    unset($email);
    unset($pass);
   } else {
    $errTyp = "danger";
    $errMSG = "משהו השתבש , נסה שוב מאוחר יותר."; 
   } 
    
  }
  
  
 }
 

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>צור קשר</title>
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
  
     <h3 class="text-center">צור איתנו קשר</h3>
     <div class="container">
     <hr>
       <div class="col-md-12"> 
      <form action="?action=contact" method="POST">
   <?php
   if ( isset($errMSG) ) {
    
    ?>
    <div class="form-group">
             <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
        <?php echo $errMSG; ?>
                </div>
             </div>
                <?php
   }
   ?>           
            <div class="form-group">
             <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
              <input type="text" name="name" class="form-control" placeholder="כינוי" value="<?php echo $TmpUser; ?>" maxlength="20" required>
               </div>
              <span class="text-danger"></span>
            </div>
             <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
               <input type="email" name="email" class="form-control" placeholder="כתובת אמייל" value="<?php echo $TmpEmail; ?>" maxlength="120" required>
                </div>
                <span class="text-danger"></span>
            </div>
             <div class="form-group">
             <textarea class="form-control" name="content" placeholder="תוכן" cols="50" rows="5" required></textarea>
              <span class="text-danger"><?php echo $contentError; ?></span>
            </div>
            <div class="form-group">
             <hr>
            </div>
            <div class="form-group">
             <button type="submit" class="btn btn-block btn-primary" name="btn-contact">שלח</button>
            </div>
          <div class="form-group">
          <hr>
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