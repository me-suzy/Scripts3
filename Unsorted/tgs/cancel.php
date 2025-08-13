<?php
session_start();
ob_start();
include("includes/header.php");
include("include/messages.php");
$msg = $M_NotRegister;
if(!$msg == "")
{
	print "<br><br><center>$msg</center><br><br>";
}
include("includes/footer.php");
?>