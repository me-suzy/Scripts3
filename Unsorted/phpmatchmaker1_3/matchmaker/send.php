<?
require_once("php_inc.php"); 
session_start();
include("header_inc.php");
db_connect();

if (session_is_registered("valid_user"))
{
 print "<form method=post action=send.php>";
 print "<input type=hidden name='profile' value='$profile'>";
 do_html_heading("Mailbox");
 member_menu();

 if ($submit)
 {
 	$mailstring= "insert into mail (mail_to, mail_from, title, message, status) values ('$profile', '$valid_user', '$title', '$message', 'New!')";
	$mailstring_result=mysql_query($mailstring);
	
	if ($mailstring_result)
	{
	 	 print "<p>Mail is sent to $profile !";
		 $result = mysql_query("select * from user where username = '$profile'");
		 $row = mysql_fetch_array($result);
		 $email = $row[email];

		 $to = "$email";
		 $subject = "$valid_user has sent you mail !";
		 $content = "Hi $profile,\n" .
		 					"In order to read the content of the mail, " .
							"please log into your account at:\n\n" .
							"http://localhost/phpmatchmaker/login.php\n\n" .
							"Regards\nPHP MatchMaker";
		 $fromaddress = "webmaster@";
		 
		 mail($to, $subject,$content,$fromaddress);
		 
		 
	}
	
 }	
?>
 <p>
 <table border="0" cellpadding="2">
  <tr>
    <td bgcolor="#F8F4F8" valign="top"><font face="Arial" size="2">&nbsp;</font><font face="Arial" size="2">Mail
      to: </font></td>
    <td bgcolor="#F8F4F8" valign="top"><font face="Arial" size="2">
<?
print "<a href='detail.php?profile=$profile'>$profile</a></font>";
?>
</td>
</tr>
  <tr>
    <td bgcolor="#F8F4F8" valign="top"><font face="Arial" size="2">&nbsp;Subject:</font></td>
    <td bgcolor="#F8F4F8" valign="top"><input type="text" name="title" size="48" value="<? echo $title ?>"></td>
  </tr>
  <tr>
    <td bgcolor="#F8F4F8" valign="top"><font face="Arial" size="2">&nbsp;Message:</font></td>
    <td bgcolor="#F8F4F8" valign="top"><textarea rows="17" name="message" cols="42"></textarea></td>
  </tr>
</table>
<p><input type="submit" value="Send mail" name="submit"></form></p>
 
<?
// ----- END OF CONTENT ----------- // 
}
else
{
	 		print "Session expired, please <a href='logon.php'>logon again</a>.";
			exit;
}
include("footer_inc.php");
?>