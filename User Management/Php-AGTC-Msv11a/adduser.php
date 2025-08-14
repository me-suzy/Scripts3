<?php
// *************************************************************************************************
// Title: 		PHP AGTC-Membership system v1.1a
// Developed by: Andy Greenhalgh
// Email:		andy@agtc.co.uk
// Website:		agtc.co.uk
// Copyright:	2005(C)Andy Greenhalgh - (AGTC)
// Licence:		GPL, You may distribute this software under the terms of this General Public License
// *************************************************************************************************
//
include("config.php");
$username = "";
$userpass = "";
$userlevel = "";
$useremail ="";
 
if(isset($_POST['Submit']))
{
if ($_POST['username'] == "" or $_POST['userpass'] == "" or $_POST['useremail'] == "") {

$msg3 = true;
$pass = "no"; }
if (!$pass == "no") {
        $username = $_POST['username'];
        $userpass = md5($_POST[userpass]);
        $userlevel = $_POST['userlevel'];
        $useremail = $_POST['useremail'];
		$dt = date("d-m-Y");
		$gra = getenv("REMOTE_ADDR");
		
		// CHECK FOR DUPLICATE
		$result = mysql_query("Select * from login_table",$con);
		
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
$n++;
		
		
		if ($username == $row['user_name']) {$_GET['userid'] = "";
		
		header("Location: adduser.php?pass=no&msg2=true");}
		if ($useremail == $row['user_email']) {$_GET['userid'] = "";
		
		header("Location: adduser.php?pass=no&msg2b=true");}
		
		
		}
		
		
        if(!isset($_GET['userid']))
        { 
                $result = mysql_query("Insert into login_table(user_name,user_pass,user_email,user_level,date,user_ip) values('$username','$userpass','$useremail','$userlevel','$dt','$gra')");
                $msg2 = "";
				$msg3 = ""; 
				$msg = "You are now registered &nbsp; <a href='login.php'>LOGIN HERE</a>";
				include "send.php";
        }
        
}}
?>
<html>
<head>
<title>ADD USER</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#A7A3F5"><font face="arial">


<form name="form1" method="post" action="adduser.php">
 <h3 align="center">Add New User</h3>
  <table width="40%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000">
    <tr>
      <td width="20%"><div align="right"><strong>User Name:</strong></div></td>
      <td width="40%"><input name="username" type="text" id="username" value="" maxlength="15"></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Password:</strong></div></td>
      <td><input name="userpass" type="password" id="userpass" value="" maxlength="15"></td>
    </tr>
<tr>
      <td><div align="right"><strong>Email Address:</strong></div></td>
      <td><input name="useremail" type="text" id="useremail" value="" maxlength"25"></td>
    </tr>
      <input name="userlevel" type="hidden" id="userlevel" value="1">
	    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Add User">
        <input type="reset" name="Submit2" value="Reset"></td>
    </tr>
  </table>
  <p class = "smallErrorText" align="center">
  <?php
   if ($msg2) {$msg = "The user name you have choosen is already in use, sorry, please choose another";}
  if ($msg2b) {$msg = "The email address you have choosen is already in use, sorry, please choose another";} 
  if ($msg3) {$msg = "Error, you must fill in all fields";}
  
  
  echo $msg; ?>
  </p>
  <p>&nbsp;</p>
</form>
</body>
</html>