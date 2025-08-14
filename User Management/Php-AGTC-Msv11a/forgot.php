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
session_start();
include("config.php");

$msg = "";

if (isset($_POST['Submit']))
{
	$useremail = $_POST['useremail'];
	$result = mysql_query("Select * From login_table where user_email='$useremail'",$con);
	
	if(mysql_num_rows($result)>0)
	{
		$row = mysql_fetch_array($result, MYSQL_BOTH);
		if($useremail == $row["user_email"]) // CHECK TO SEE IF USER EMAIL IS REGISTERED
		{										// IF NOT, MESSAGE WILL TELL USER NOT REISTERED
			
			$msg = "Your password has been sent to your email address";		
		
// RANDOM PASSWORD GENERATOR FOR NEW PASS
function NewPass() {
$rnd = "abchefghjkmnpqrstuvwxyz0123456789";
srand((double)microtime()*1000000); 
$i = 0;
while ($i <= 7) {
    	$num = rand() % 33;
    	$tmp = substr($rnd, $num, 1);
    	$pass = $pass . $tmp;
    	$i++;}
return $pass;}
$rand_pass = NewPass(); // UN ENCRYPTED PASSWORD
$newpass = md5($rand_pass); // PASSWORD ENCRYPTION
		
// HERE MYSQL PREPARES TO UPDATE THE USERS RECORD CHANGING TO THE NEW ENCRYPTED PASSWORD	
		$username = $row["user_name"];
        $userpass = $newpass;
        $userlevel = $row["user_level"];
        $useremail = $row["user_email"];
		$userid = $row["userid"];
$result = mysql_query("Update login_table set user_name='$username', user_pass='$userpass', user_email='$useremail', user_level='$userlevel' where userid=".$userid);

// THIS PART SENDS USER A NEW PASSWORD
$email = $useremail;
$todayis = date("l, F j, Y, g:i a") ;
$subject = "Password Recovery";
$message = " 
From: $sendersName ($sendersEmail)\n
You have requested your Login information as below: \n\n
Username: $username\n
Password: $rand_pass\n
";
$from = "From: $sendersEmail";
if ($email != "") 
mail($email, $subject, $message, $from);
		}
		else
		{
			$msg = "The email address provided is not in our records";
		}
	}
	else
	{
		$msg = "The email address provided is not in our records";
    }
}
mysql_close ($con);
?>

<!-- THIS IS THE REQUEST NEW PASSWORD FORM -->
<html>
<head>
<title>Password Recovery</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<p align="center"><strong>PHP AGTC-Membership system v1.1a</strong></p>
<form name="form1" method="post" action="">
  <p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Please 
    enter your registered email address </font></p>
  <p align="center"><?php echo "<font color='red'>$msg</font>" ?></p>
  <table class="table" width="60%" border="0" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000">
    <tr bgcolor="#000000"> 
      <td colspan="2"><div align="center"><font color="#FC9801" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>REQUEST NEW PASSWORD </strong></font></div></td>
    </tr>
    <tr> 
      <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please enter your email address:&nbsp; </font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input name="useremail" type="text" id="useremail" width="200">
        </font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="center">
        <input type="submit" name="Submit" value="Request Password">
      </div></td>
    </tr>
  </table>
<p align="center" class="smallErrorText"><a href="login.php">Login Here </a></p>
</form>
<p align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
Powered by PHP AGTC-Membership system v1.1a</a></strong><br></font> Developed by <strong>Andy Greenhalgh </strong> <a href="mailto:andy@agtc.co.uk">andy@agtc.co.uk</a></p>
<p>&nbsp; </p>
</body>
</html>