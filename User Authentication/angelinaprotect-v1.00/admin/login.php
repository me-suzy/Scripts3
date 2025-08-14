<?
session_start(); 

$errorMessage = ''; 
if (isset($_POST['User']) && isset($_POST['Password'])) { 

	require("../library/config.php");
     
    $userId   = $_POST['User']; 
    $password = $_POST['Password']; 
     
  require("../library/opendb.php");
	dbconnect();
$mysql_link = mysql_connect($dbhost, $dbuname, $dbpass);
    $sql = "SELECT user_id 
            FROM member_admin 
            WHERE user_id = '$userId' AND user_password = PASSWORD('$password')"; 
     
    $result = mysql_query($sql) or die('Query failed. ' . mysql_error()); 
     
    if (mysql_num_rows($result) == 1) { 
        $_SESSION['admin_is_logged_in'] = true; 
        header('Location: main.php'); 
        exit; 
    } else { 
        $errorMessage = 'Sorry, wrong user and-or password'; 
    } } 
?> 
<html> 
<head> 
<title>Admin Login</title> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
</head> 

<body> 
<font size=+3><b><i><center>Admin Login</center></i></b></font>
<?
if ($errorMessage != '') { 
?> 
<p align="center"><strong><font color="#990000"><?php echo $errorMessage; ?></font></strong></p> 
<?php 
} 
?> 
<form action="" method="post" name="frmLogin" id="frmLogin"> 
<table width="400" bgcolor=red align="center" cellpadding="2" cellspacing="2"> 
  <tr> 
   <th width="150" bgcolor=white>User</th> 
   <th bgcolor=white><input name="User" type="text" id="User"></th> 
  </tr> 
  <tr> 
   <th bgcolor=white width="150">Password</th> 
   <th bgcolor=white><input name="Password" type="password" id="Password"></th> 
  </tr> 
  <tr> 
   <th bgcolor=white width="150">&nbsp;</th> 
   <th bgcolor=white><input name="btnLogin" type="submit" id="btnLogin" value="Login"></th> 
  </tr> 
</table> 
</form> 
<? include('footer.php'); ?>
</body> 
</html> 