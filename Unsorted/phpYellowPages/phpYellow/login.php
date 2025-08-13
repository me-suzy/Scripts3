<?php // initialize or capture variables if phpversion 4.1.0 or higher is present
$phpVersion = phpversion();
if ($phpVersion >= "4.1.2") { // real mccoy
$yemail = !isset($_POST['yemail'])?NULL:$_POST['yemail'];
$password = !isset($_POST['password'])?NULL:$_POST['password'];
//$cms_bridge_cc = !isset($_REQUEST['cms_bridge_cc'])?NULL:$_REQUEST['cms_bridge_cc'];
}?>

<?php 
/* Start of CMS Bridge
This section incorporate CMS Bridge middleware. See
http://portal.dbserve.net/codeshare/cms_bridge.htm
for instructions and information.
*/
if (isset($cms_bridge_cc)) {
	include "cms_bridge/sync_user.php";
} // End of CMS Bridge ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>

	<script language="Javascript" src="yellow.js"></script>
	<script language="Javascript">loadCSSFile();</script>

    <?php require("util.php");?>
    <?php echo "<TITLE>phpYellow - " . YOURPHPYELLOWNAME . " - " . COMPANY . "</TITLE>"; ?>

	<META NAME="keywords" CONTENT="phpYellow, Pages, php, Yellow, add, edit, delete, listing, login, below">
	<META NAME="description" CONTENT="Login page. To add, edit or delete your listing(s) please login below:">
	<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com Email: richardc@dreamriver.com">
	<META NAME="GENERATOR" CONTENT="Blood, Sweat & Tears">

	</HEAD>

<BODY>
<?php 
include("header.php"); 
echo "<p><br></p>";
?>

<h1>Add, Edit or Delete<br>
Your Free Listing</h1>

To add, edit or delete your listing(s) please login below.

<P><BR></P>
<?php include("loginForm.php");?>


<P><BR></P>

<?php include("footer.php");?>
