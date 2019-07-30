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

  
   //לקבל את נתוני ההודעה מהמשתמש
   $TxtChat = htmlspecialchars($_GET['chat']);
   $TxtMsg = htmlspecialchars($_POST['message']);
   $TxtName = htmlspecialchars($_POST['name']);
   $TxtType = htmlspecialchars($_POST['LoginType']);
   $TxtColor = htmlspecialchars($_POST['TxtColor']);
   $TxtImage = htmlspecialchars($_POST['TxtImage']);
 

   //אם הוקלד טקסט בכתובת תמונה מצורפת
   if(!empty($TxtImage)){
   $TmpMsg = "<font color=" .$TxtColor. ">" .$TxtMsg. "</font><br><img src=" .$TxtImage. " style=max-width:70%>";
   } else if(empty($TxtImage)){
   $TmpMsg = "<font color=" .$TxtColor. ">" .$TxtMsg. "</font>";
   }
 
   $resBlocked=mysqli_query($conn,"SELECT * FROM blocked WHERE ip='$ip' AND SessionID='$SessionID' AND chat='$TxtChat'");
   $BlockedUsers=mysqli_fetch_array($resBlocked);
   //אם המשתמש נחסם מהצאט
   if(($BlockedUsers['ip'] == $ip || $BlockedUsers['SessionID'] == $SessionID)){
   } else {
   if(isset($_POST['message'])){ 
   //להכניס את המידע שקיבלנו למערכת
   $sql = "INSERT INTO messages(nickname,message,type,chat,time) VALUES('$TxtName','$TmpMsg','$TxtType','$TxtChat','$date')";
   $res = mysqli_query($conn,$sql);

   }
   } 

   header('Content-type: text/xml');
 
    $last = (isset($_GET['last']) && $_GET['last'] != '') ? $_GET['last'] : 0;
	$sql = "SELECT id,nickname,message,type,chat,time FROM messages WHERE DATE_ADD(timestamp, INTERVAL 1 WEEK) 
    >= NOW() AND id > " . $last. " AND chat = " .$TxtChat. " ORDER BY id DESC  LIMIT 30";
	$res = mysqli_query($conn,$sql);
    $xml = new XMLWriter();
    $xml->openURI("php://output");
    $xml->startDocument();
    $xml->setIndent(true);
    $xml->startElement('root');     
    
    while ($row = mysqli_fetch_assoc($res)) {
    $xml->startElement("message");
    $xml->writeAttribute('id', $row['id']);
    $xml->writeElement("user", $row['nickname']);
    $xml->writeElement("text", $row['message']);
    $xml->writeElement("LoginType", $row['type']);
    $xml->writeElement("time", $row['time']);
    $xml->endElement();

    }
   
    $xml->endElement();
    
    $xml->flush();   