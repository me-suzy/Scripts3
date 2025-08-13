<?php
/*********************************************************
 * Author: John Astill (c) 
 * Date  : 18th December 2001
 * File  : CreateNewUser.php
 * Desc  : Form for creating a new user.
 ********************************************************/
require "SystemVars.php";
require "dbaseFunctions.php";
require "LogFunctions.php";
require "security.php";

// Try to read the test cookie
if ($HTTP_COOKIE_VARS["CookieTest"] != "Prediction") {
  LogMsg("Not able to read test cookie when creating new user");

  // Forward to the help page
  header("Location: HelpIndex.php#COOKIES"); 
}

// Access the session data.
session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

$newicon = $defaulticon;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>
  Create New User
</title>
<link rel="stylesheet" href="common.css" type="text/css">

<SCRIPT LANGUAGE="JavaScript">
<!--

// Check the form is complete. 
// Check that all the entries are complete.
// Check that the two passwords are equal.
// Check the email address 
function checkForm(form) {

	// Ensure a USER ID is entered
	if (form.USERID.value == "") {
		alert("User ID Required");
		return (false);
	}

  if (form.USERID.value.length >= 32) {
		alert("User ID too long ("+form.USERID.value.length+"characters). Must be less than 32 characters.");
		return (false);
  }

	// Ensure that the passwords is not empty
	if (form.PWD1.value == "") {
		alert("Passwords is required");
		return (false);
	}

	// Ensure that the passwords are equal
	if (form.PWD1.value != form.PWD2.value) {
		alert("Passwords do not match");
		return (false);
	}

  if (form.PWD1.value.length >= 32) {
		alert("Password too long. Must be less than 32 characters.");
		return (false);
  }

	// Ensure that an email address is entered
	if (form.EMAIL.value == "") {
		alert("Email address is required");
		return (false);
	}

  if (form.EMAIL.value.length >= 60) {
		alert("Email too long. Must be less than 60 characters.");
		return (false);
  }

	// Ensure that an email address is valid
	if (form.EMAIL.value.indexOf('@') < 0) {
		alert("Email address is not valid");
		return (false);
	}

	return true;
}

// -->
</SCRIPT>


</head>

<body class="MAIN">

<table width="800">
<tr>
<td colspan="3" align="center">
<!-- Header Row -->
<?php echo $HeaderRow ?>
</td>
</tr>

<?php if ($ErrorCode != "") { ?>
<tr>
<td colspan="3" bgcolor="red" align="center">
<!-- Error Message Row -->
<font class="TBLHEAD">
<?php echo $ErrorCode ?>
</font>
</td>
</tr>
<?php 
  // Empty the error code
  $ErrorCode = "";
}
?>

<tr>
<td valign="top">
<?php include "menus.php"?>
</td>
<td valign="top">
<!-- Show the Users info -->
<form method="POST" action="CreateUser.php">
<table width="500">

<tr>
<td colspan="3" align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
User Info
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
User ID
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<input type="TEXT" size="10" name="USERID" value="">
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
Select a user ID.
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
Password
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<input type="password" size="10" name="PWD1">
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
Password must be at least 5 characters long.
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
Password
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<input type="password" size="10" name="PWD2">
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
Repeat password.
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
Email Address
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<input type="TEXT" size="20" name="EMAIL" value="<?php echo $emailaddress?>">
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
Enter your valid email address.
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
Icon
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<?php echo $newicon;?>
<input type="HIDDEN" name="ICON" value="<?php echo $newicon;?>">
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
Select the icon to represent your user.
</font>
</td>
</tr>
<tr>
<td colspan="3" align="CENTER">
<font class="TBLROW">
<input type="SUBMIT" onClick="return checkForm(this.form);" name="CREATE" value="Create">
</td>
</tr>
</table>
</form>
</td>
</tr>
</table>

</body>
</html>


