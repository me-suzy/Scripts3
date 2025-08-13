<?php require("scripts/inc.auth.php"); ?>

<?php include("scripts/inc.layout.php"); ?>

<?php
if ($userlev == "guest" | $userlev == "user")  {
	$bottom = "league.php";
}
if ($userlev == "admin") {
	$indexsrc = "scripts/help.php";
	$bottom = "admin.php";
}
if ($userlev == "developer") {
	$indexsrc = "scripts/filter.php?dbtable=Accounts&dbfield=Userlevel";
	$bottom = "developer.php";
}
?>

<html>
<head>
<title>PHP Football</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<frameset cols="*" rows="*,106" frameborder="NO" border="0" framespacing="0"> 
  <frame src="<?php echo $indexsrc; ?>" name="top">
  <frame src="<?php echo $bottom; ?>" name="bottom">
</frameset>
<noframes><body bgcolor="#FFFFFF">
</body></noframes>
</html>