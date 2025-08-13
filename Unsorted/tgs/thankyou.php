<?php
session_start();
ob_start();
include("includes/header.php");
include("includes/messages.php");
if($session_first_name == "" || $session_username == "")
{
	header("Location:register.php");
}
else
{
	$st="insert into StatMember(sponsorid,parentid,firstname,lastname,email,username,password,companyname,createdate,package_type,account_status)";
	$st="$st values('0','0','$session_first_name','$session_last_name','$session_email','$session_username','$session_password','',NOW(),'$session_packageType','P')";
	$rs = mysql_query($st)or die(mysql_error());
	$msg = $M_RegisterSuccess;
}
	if(!msg == "")
	{
		print "<center>$msg</center><br>";
		print "<center><a href='index.php'>Login</a></center>";
	}
?>
<?php
include("includes/footer.php");
?>