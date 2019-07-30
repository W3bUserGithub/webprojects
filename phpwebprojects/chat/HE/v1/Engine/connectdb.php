<?php

 error_reporting(0);
 date_default_timezone_set('Asia/Jerusalem');
 $ip = $_SERVER['REMOTE_ADDR'];
 $date = date('Y/m/d G:i:s');
 
 define('DBHOST', 'localhost');
 define('DBUSER', 'root');
 define('DBPASS', '');
 define('DBNAME', 'chat');
 
 //התחברות למסד הנתונים
 $conn = mysqli_connect(DBHOST,DBUSER,DBPASS);
 $dbcon = mysqli_select_db($conn,DBNAME);
 
 if ( !$conn ) {
  die("Connection failed : " . mysqli_error());
 }
 
 if ( !$dbcon ) {
  die("Database Connection failed : " . mysqli_error());
 }
 
 mysqli_query($conn,"SET names 'utf8'");


//מחיקת נתונים ישנים
 mysqli_query($conn,"DELETE FROM online
 WHERE DATE_ADD(timestamp, INTERVAL 1 DAY) 
<= NOW()");

mysqli_query($conn,"DELETE FROM messages
 WHERE DATE_ADD(timestamp, INTERVAL 2 WEEK) 
<= NOW()");

 mysqli_query($conn,"DELETE FROM chatrooms
 WHERE DATE_ADD(LastActivityView, INTERVAL 3 WEEK) 
<= NOW()");