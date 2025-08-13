<?php
if(!session_is_registered("sessionAdmin"))
{
	session_register("sessionAdmin");
}
include("../includes/config.php");
include("../includes/db_inc.php");
?>
<html>
<head>
<title><?php print $sitename; ?> Admin</title>
<link href="../style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center">
<table border="350" cellpadding="0" width="0" style="border: 1 solid #ACDC00" height="100" bgcolor="#ACDC00">
<tr class="text"><td width="350" valign="top" align="left" height="100"><?php include("../includes/admin_menu.php"); ?>
</tr></td></table>