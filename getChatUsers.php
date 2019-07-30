<?php
 
   session_start();
   require_once 'Engine/connectdb.php';
 
   //לבדוק אם המשתמש נמצא בצאט
   $host = "http://" .$_SERVER['HTTP_HOST']. "/chat.php?act=2";
   $SessionID = htmlspecialchars(trim($_COOKIE['PHPSESSID']));
   if(!isset($SessionID)){
   die('עליך להתחבר לצאט');    
   exit;
   } else if($_SERVER['HTTP_REFERER'] !== $host){
   die('עליך להתחבר לצאט');    
   exit;
   }
 
   $chat = htmlspecialchars($_GET['chat']);
 
   $resBlocked=mysqli_query($conn,"SELECT * FROM blocked WHERE ip='$ip' AND SessionID='$SessionID' AND chat='$chat'");
   $BlockedUsers=mysqli_fetch_array($resBlocked);
   //אם המשתמש נחסם מהצאט
   if(($BlockedUsers['ip'] == $ip || $BlockedUsers['SessionID'] == $SessionID)){
   } else {

   //עדכון פעילות המשתמש
   $query = "UPDATE online SET timestamp = NOW() WHERE DATE_ADD(timestamp, INTERVAL 1 MINUTE) 
   >= NOW() AND  SessionID='$SessionID' AND chat='$chat'";
   $res = mysqli_query($conn,$query);

   }
 
   header('Content-type: text/xml');
 
   $sql = "SELECT * FROM online
   WHERE DATE_ADD(timestamp, INTERVAL 1 MINUTE) 
   >= NOW() AND chat = " .$chat. "
   ORDER by LoginType DESC";
   $res2 = mysqli_query($conn,$sql);    
   $xml = new XMLWriter();
   $xml->openURI("php://output");
   $xml->startDocument();
   $xml->setIndent(true);
   $xml->startElement('root');
    
    
  while ($row = mysqli_fetch_assoc($res2)) {
   $xml->startElement("message");
   $xml->writeAttribute('id', $row['id']);
   $xml->writeElement("user", $row['username']);
   $xml->writeElement("LoginType", $row['LoginType']);
   $xml->writeElement("LastUserUpdate", $row['id']);
   $xml->endElement();
    }
    
  $xml->endElement();
  
  $xml->flush();