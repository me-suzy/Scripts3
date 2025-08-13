<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<?php require("inc.auth.php"); ?>
<?php 
if ($userlev == "guest") {
	echo "<font color=red><b>You are not allowed to view this page</b></font>";
	die;
}
?>

<?php
$available = file ('http://www.phpfootball.sourceforge.net/version.txt');

if (!$available){ echo "<font color=red>Coud not get new version number</font><br>";
}else{
if ($versionsrc==rtrim($available[0])) {
	echo "Installed version of PHPFootball is the latest one.<br>You don't need to update it.<br>";
	echo "Current version: $available[0]<br>";
	echo "Installed version: $versionsrc<br><br>";
}else{
	echo "<b>New version of PHPFootball is available!<br>It is advisable that you update to the new one.</b><br>";
	echo "New version: $available[0]<br>";
	echo "Installed version: $versionsrc<br><br>";
	echo "Before upgrading it is advisable you use the control pannel backup option and restore your data on the new version<br>";
	echo "<form target=_blank name=form1 action=http://www.phpfootball.sourceforge.net>";
	echo "<input type=submit class=button name=act value=\"Get new version : $available[0]\"></form>";
}
}
?>


<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
