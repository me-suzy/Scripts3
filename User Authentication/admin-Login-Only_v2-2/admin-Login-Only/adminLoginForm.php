<!-- doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN"-->
<html>
<head>
	<TITLE>Login to Admin Center</TITLE>

	<link rel="stylesheet" type="text/css" href="admin-login-only.css">

	<meta name="robots" content="noindex,nofollow">

</head>


<body onload="window.document.adminLoginForm.formuser.focus()">
<!--START OF adminLoginForm.php -->
<blockquote>
	<p><br></p>
	<!--  -->
	<form method="post" name="adminLoginForm" action="adminLogin.php">
	<?php $loginAttempts = !isset($_POST['loginAttempts'])?1:$_POST['loginAttempts'] + 1;?>
		<input type="hidden" name="loginAttempts" value="<?php echo $loginAttempts;?>">

		<!-- table for background -->
		<table width="280" height="175" background="images/backgrounds/background_280x175.gif">
		<tr>
			<td>
		<table border="0" cellpadding="5" cellspacing=3>
			<tr>
				<td align="right" colspan=2 style="color:honeydew;font-style:italic;font-size:9px;">admin-Login-Only Version 2.2</td>
			</tr>
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
			</td>
		</tr>
		</table>

	</form>
</blockquote>		
<!--END of adminLoginForm.php -->
</body>
</html>