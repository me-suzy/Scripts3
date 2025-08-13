<!-- doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN"-->
<html>
<head>
<TITLE>Administration Center Login</TITLE>
<META NAME="keywords" CONTENT="">
<META NAME="Author" CONTENT="Richard Creech, DreamRiver.com give@dreamriver.com http://www.dreamriver.com This software is Copyright 2002 DreamRiver.com. All Rights Reserved.">
<META NAME="description" CONTENT="">
<META NAME="GENERATOR" CONTENT="Hard work and years of programming">

<script language="Javascript" type="text/javascript" src="yellow.js"></script>
<script language="Javascript" type="text/javascript">loadCSSFile();</script>

<meta name="robots" content="noindex,nofollow">
</head>


<body onload="window.document.adminLoginForm.formuser.focus()">
<!--START OF adminLoginForm.php -->
<blockquote>
	<p><br></p>
	<!--  -->
	<form method="post" name="adminLoginForm" action="adminLogin.php">
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
					<input class="input" type="submit" name="submit" value="Login to Admin Center">
				</td>
			</tr>
		</table>
	</form>
</blockquote>		
<!--END of adminLoginForm.php -->
</body>
</html>