<?php 
/* Start of CMS Bridge
This section incorporates CMS Bridge middleware. See
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

    <?php include("util.php");?>
    <?php echo "<TITLE>Password Look Up - " . YOURPHPYELLOWNAME . " - " . COMPANY . " - A Dreamriver.com Software Product - Easily create classified ads or Yellow Page directories! phpYellow Pages from Dreamriver.com - php software mysql database software download apps</TITLE>"; ?>

	<META NAME="keywords" CONTENT="phpYellow, php, Yellow, Forgot, password, Fill, email, address, password, you.">
	<META NAME="description" CONTENT="Forgot your password? Fill in your email address ( the one you used in phpYellow Pages ) and we'll email your password to you.">
	<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com Email: richardc@dreamriver.com">
	<META NAME="GENERATOR" CONTENT="Blood, Sweat & Tears">
	</HEAD>

<BODY onload="document.passwordForm.loginemail.focus()">

<?php include("header.php");?>

<h1>Look Up Password</h1>

<p>Forgot your password? Fill in your email address ( the one you used in 
phpYellow Pages ) and we'll email your password to you.
</p>

<!-- Start Password Form -->
<br>
<form method="post" name="passwordForm" action="yellowgoal.php" onSubmit="return checkForm(this)">
<table class="form">
<tr>
<th colspan=2>Look Up Password</th>
</tr>


<tr>
<td>Your Email Address</td>
<td><input type="text" name="loginemail" size="25" maxlength="35" class="input"></td>
</tr>


<tr>
<td colspan=2>
<input type="submit" name="submit" value="Email My Password To Me" class="input">
<input type="hidden" name="goal" value="Password Lookup">
</td>
</tr>


</table>
</form>
<!-- End LogIn Form-->

<?php include("footer.php");?>
