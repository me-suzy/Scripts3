<?
session_start();
require_once("php_inc.php"); 
require_once("webmaster/settings_inc.php"); 
include("header_inc.php");
db_connect();
?>
<h2>Lost password</h2><br>

<?

	
if ($submit)
{
 srand((double) microtime() * 1000000);
	$pwd="";
	while (strlen($pwd)<8) {
	$r=rand(1, 3);
	if ($r==1) {
	$rcode=rand(48, 57);
	}
	if ($r==2) {
	$rcode=rand(65, 90);
	}
	if ($r==3) {
	$rcode=rand(97, 122);
	}
	$pwd.=chr($rcode);
	}

	
 $res = mysql_query ("update user set passwd = password('$pwd') where email = '$email'");
 if ($res)
 {
         print "<b>Password is reset</b><br>Your new password is in your mailbox soon.<br>";
         $sendto = $email;
         $from = $from_adress;
         $subject = "Reset password";
         $message = "Hi $email ! Your new password is $pwd";

         $headers = "From: $from\r\n";
         // send e-mail
         mail($sendto, $subject, $message, $headers);
 }
 else 
 {
 	print "<b>Error</b><br>Your password could not be reset. <br>"; 	
 }
}
?>
<form method="post" action="lostpassword.php">

Please type in your emailaddress below, and you will soon recieve your new password.
<br>
<input type="text" name="email">
<input type="submit" name="submit" value="Reset password">
</form>

<?
include("footer_inc.php");
?>
