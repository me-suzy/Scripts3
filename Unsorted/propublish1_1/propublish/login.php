<? if (!$skip) { include "header.php"; } ?>
<?
if ($logout == 1)
{
	session_destroy();	
}
?>
<?php
require("admin/db.php");
require("admin/set_inc.php");
require("admin/language/$lang");

if ($submit)
{
	$result = mysql_query("SELECT * FROM author_news WHERE email ='$email' AND password = '$password'");
   	$row = mysql_fetch_array($result); 
	$fname = $row["fname"];
	$lname = $row["lname"];
	$level = $row["level"];
	$userid = $row["userid"];
	

	if (($row["email"] == $email) AND ($row["password"] == $password))
  	{ 
  		$name = "$fname $lname";
  		
  		session_register("email","userid",level,"name");
		print "<b>$la_login_wel $fname $lname</b><br>";
		print "$la_login_ready:<p>";
		print "<a href='admin/list.php'>$la_newslist</a><br>";
  	}	
  	else
	{
		print "<b>$la_error</b><br>$la_error_2";	
	}
}

elseif (!$submit)
{	 
   if ($logout)
   {
   		print "<b>$la_login_logout1</b><br>$la_login_logout2<p>";
   }
   
	
	
   print "<form name='login' action='$PHP_SELF' method='post' class='articlebody'>";
   print "<input type=hidden name=side value=login>";
   print "<p><table border=0 bgcolor=#FFFFFF width=585 cellpadding=3>";
   print "<tr>";
   print "<td align=left valign=bottom><font 
class='articlebody'>$la_email:</font></td>";
   print "<td align=left valign=bottom><input name='email' type='text' 
size='20' maxlen=20 value='$email'></td>";
   print "</tr>";
   print "<tr>";
   print "<td align=left valign=bottom><font 
class='articleBody'>$la_passwd:</font></td>";
   print "<td align=left valign=bottom><input name='password' 
type='password' size='20' maxlen=20 value='$password'></td>";
   print "</tr> <tr><td width=100% colspan=2 align=left> <input 
type='submit' name='submit' value='$la_login'></td>";
   print "</tr></table>";
   
   print "<p><a href='login.php?logout=1'>$la_logout</a>";

}
?>


<? if (!$skip) { include "footer.php"; } ?>

