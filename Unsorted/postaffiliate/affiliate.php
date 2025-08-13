<?php  

if($_GET['ref']) 
{		
  SetCookie ("ref",$_GET['ref']); 

  session_start();
  $_SESSION['ref'] = $_GET['ref'];

  include "affconfig.php";   

  mysql_connect($server, $db_user, $db_pass) 
    or die ("Database CONNECT Error (line 17)"); 
  mysql_db_query($database, "INSERT INTO clickthroughs VALUES ('".$_GET['ref']."', '$clientdate', '$clienttime', '$clientbrowser', '$clientip', '$clienturl', '')") 
    or die ("Database INSERT Error (line 18)"); 
} 
?>