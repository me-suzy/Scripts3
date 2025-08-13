<! doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
   <link rel=stylesheet type="text/css" href="phpcheckout.css">

	<TITLE>admin-Login-Only - Login Page - Software Powers the Net - DreamRiver</TITLE>

	<META NAME="keywords" CONTENT="admin-Login-Only, protect, secure, authenticate, verify, make sure, restrict, admin, only">
	<META NAME="Author" CONTENT="Richard Creech, DreamRiver.com sales@dreamriver.com http://www.dreamriver.com This software is Copyright 2002 DreamRiver.com. All Rights Reserved.">
	<META NAME="description" CONTENT="This file is adminLoginForm.php. Login to a sessions controlled page with this file called adminLoginForm.php. You will be asked for a username and password.">
	<META NAME="GENERATOR" CONTENT="Hard work and years of programming">


<script language="JavaScript" type="text/javascript">
<!--
// June 12, 2000 Last Revised
// CHECK EMAIL AND PASSWORD
// Credit: This function by dannyg@dannyg.com
// all fields are required
function checkForm(form) {
for (var i=0; i < form.elements.length; i++) {
	if (form.elements[i].value == "") {
		alert("Fill out all fields please.")
		form.elements[i].focus()
		return false
	}
}
return true
}
// -->
</script>
</head>
<body onload="window.document.adminLoginForm.formuser.focus()">
<!--START OF adminLoginForm.php -->
<blockquote>
	<p><br></p>
	<!--  -->
	<form method="post" name="adminLoginForm" onSubmit="return checkForm(this)" action="adminLogin.php">
		<input type="hidden" name="loginAttempts" value="<?php echo $loginAttempts;?>">
		<table border="0" cellpadding="5">
			<tr>
				<th colspan=2>Login to Admin Center</th>
			</tr>

			<tr>
				<td align="right">Admin User Name</td>
				<td>
					<input type="text" name="formuser" value="<?php echo $formuser;?>">
				</td>
			</tr>

			<tr>
				<td>Admin Password</td>
				<td>
					<input type="password" name="formpassword" value="<?php echo $formpassword;?>">
				</td>
			</tr>

			<tr>
				<td colspan=2>
					<input class="submit" type="submit" name="submit" value="Login to Admin Center">
				</td>
			</tr>
		</table>
	</form>
</blockquote>		
<!--END of adminLoginForm.php -->
</body>
</html>