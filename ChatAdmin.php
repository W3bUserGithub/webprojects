<?php 
 session_start();
 require_once 'Engine/connectdb.php';


 $res=mysqli_query($conn,"SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res);
 
 if(!isset($_SESSION['user'])){
 header("Location: login.php");
 }
 
 $res2=mysqli_query($conn,"SELECT * FROM chatrooms WHERE owner='".$userRow['userName']."'");
 $chatRow=mysqli_fetch_array($res2);
 
 
 //ספירת הצאטים
 $chatRowCount=mysqli_num_rows($res2);
 
 $TmpUser= htmlspecialchars($userRow['userName']);
 $info1 = htmlspecialchars($chatRow['title']);
 $info2 = htmlspecialchars($chatRow['description']);
 $info3 = htmlspecialchars($chatRow['url']);
 $info4 = htmlspecialchars($chatRow['image']);
 
 
   $chatName = htmlspecialchars($_POST['chatName']);
   $chatDesc = htmlspecialchars($_POST['chatDesc']);
   $chatUrl = htmlspecialchars($_POST['chatUrl']);
   $chatImg = htmlspecialchars($_POST['chatImg']);
   
   
  if(isset($_POST['Btn-updateroom'])){
  
  if($chatRowCount!=0){
    //עדכן את הצאט
   $query = "UPDATE chatrooms SET title = '$chatName' , description = '$chatDesc' , url = '$chatUrl', image = '$chatImg' , lastupdate = '$date' WHERE id = '" .$chatRow['id']. "'";
   $result = mysqli_query($conn,$query);  
   header("Location: ChatAdmin.php?AdminAction=10");
   } else { 
    //צור צאט חדש
   $query = "INSERT INTO chatrooms(title,description,url,image,owner,lastupdate) VALUES('$chatName','$chatDesc','$chatUrl','$chatImg','$TmpUser','$date')";
   $result = mysqli_query($conn,$query);
   header("Location: ChatAdmin.php?AdminAction=10");
       
   }
   
 }
 

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>הגדרות הצאט</title>
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
  <div id="wrapper">
   <div class="container">    
    <div class="page-header">
     <h3 class="text-center">ניהול הצאט</h3>
      </div><?php if($_GET['AdminAction'] == '10'){ ?>
         <p>הצאט שלך נוצר</p>
         <b><a href="chat.php?chat_id=<?php echo $chatRow['id']; ?>"><?php echo $info1; ?></a></b><?php } else { ?>      
         <form action="?AdminAction=10" method="POST">
         <div class="form-group">
           <label for="chatName">כותרת הצאט:</label>
           <input type="text" class="form-control" id="chatName" name="chatName" value="<?php echo $info1; ?>">
         </div>
         <div class="form-group">
          <label for="chatDesc">תיאור הצאט:</label>
          <textarea class="form-control" name="chatDesc" id="chatDesc" cols="50" rows="5"><?php echo $info2; ?></textarea>
         </div>
         <div class="form-group">
          <label for="chatUrl">לינק:</label>
          <input type="text" class="form-control" id="chatUrl" name="chatUrl" value="<?php echo $info3; ?>">
         </div>
         <div class="form-group">
          <label for="chatImg">רקע הצאט:</label>
           <input type="text" class="form-control" id="chatImg" name="chatImg" value="<?php echo $info4; ?>">
         </div>
         <div class="form-group">
          <hr>
         </div>
           <input type="submit" class="btn btn-primary" name="Btn-updateroom" id="Btn-updateroom" value="צור צאט חדש"><?php } ?>
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
