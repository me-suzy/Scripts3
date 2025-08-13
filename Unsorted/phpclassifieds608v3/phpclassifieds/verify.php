<? 
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");

$user_email = strip_tags($user_email);
$sql = "UPDATE $usr_tbl SET verify = '0' WHERE email = '$user_email' AND verify = '$verify'";
$result = mysql_query($sql);

$res = mysql_affected_rows();

if ($res>0)
{
	
	$valid_user = $user_email;
  	
	if ($approve_mem)
	{
		$sql = "select * from $usr_tbl where email = '$user_email'";
		$result_s = mysql_query($sql);
		
		$row = mysql_fetch_array($result_s);
		$approve = $row["approve"];
		
		if ($approve)
		{
				session_register("valid_user");
		}
	}
	else 
	{
		session_register("valid_user");	
	}
	
	
  	
	
}

include("navigation.php"); 
print '		    
				<table border="0" width="100%" cellspacing="0" cellpadding="10">
				<tr>
				<td width="100%">
				';	          

print "<h3>$la_verify</h3>";



if ($res >0)
{
	
 // Referer check, and then send
 	print "<b>$la_success</b><br />$la_success_2<p />";
	
 	if ($approve_mem and !$approve)
 	{
 		print $la_wait_app;		
 	}
 	else 
 	{
 		print "<a href='member.php'>$la_successreg3</a>. <p />";	
 	}
 
 	$email = $user_email;
 	
	
	
	require("admin/config/mail.inc.php");
	$welcome_newu_msg = ereg_replace ("\{NAME\}", $name, $welcome_newu_msg);
	//$welcome_newu_msg = ereg_replace ("{NAME}", "$name", $welcome_newu_msg);
	$welcome_newu_msg = ereg_replace ("\{URL\}", $url, $welcome_newu_msg);
	//$welcome_newu_msg = ereg_replace ("{URL}", "$url", $welcome_newu_msg);
	$welcome_newu_msg = ereg_replace ("\{EMAIL\}", "$email", $welcome_newu_msg);
	//$welcome_newu_msg = ereg_replace ("{EMAIL}", "$email", $welcome_newu_msg);
	$welcome_newu_msg = ereg_replace ("\{PASSWD\}", "$passwd", $welcome_newu_msg);
	//$welcome_newu_msg = ereg_replace ("{PASSWD}", "$passwd", $welcome_newu_msg);
	$welcome_newu_msg = ereg_replace ("\{SITENAME\}", "$name_of_site", $welcome_newu_msg);
	//$welcome_newu_msg = ereg_replace ("{SITENAME}", "$name_of_site", $welcome_newu_msg);
	$welcome_newu_msg = ereg_replace ("\{VERIFY\}", "$random", $welcome_newu_msg);
	//$welcome_newu_msg = ereg_replace ("{SITENAME}", "$name_of_site", $welcome_newu_msg);
	
	$subject = "$welcome_newu_ttl";
	$message = "$welcome_newu_msg";
	

	$headers .= "From: $name_of_site<$from_adress_mail>\n";
	$headers .= "Reply-To: <$from_adress_mail>\n";
	$headers .= "X-Sender: <$from_adress_mail>\n";
	$headers .= "X-Mailer: PHP4\n"; //mailer
	$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
	$headers .= "Return-Path: <$from_adress_mail>\n";
	
	
	
	mail($email, $subject, $message, $headers);
        	
		
	
}
else 
{
	print "<b>$la_error</b><br />$la_notf ";	
	print "$la_notf2";
}

print "</td></tr></table>";
include_once("admin/config/footer.inc.php"); ?>