<br>
&nbsp;<font color="#000000"><b>Admin Management</b></font><br>
<?php
	if($sessionAdmin == "")
	{
?>
&nbsp;» <A href="index.php"><b>Login</b></A><BR><BR>
<?php
	}
	else
	{
?>
&nbsp;» <A href="changeInfo.php"><b>Change Admin Info</b></A><BR>
&nbsp;» <A href="paypal.php"><b>Member Charges</b></A><BR>
&nbsp;» <A href="logout.php"><b>Logout</b></A><BR><BR>

<?php
	}
?>
&nbsp;<font color="#000000"><b>Member Management</b></font><br>
&nbsp;» <A href="register.php"><b>Create Account</b></A><BR>
&nbsp;» <A href="broadcast.php"><b>Email Members</b></A><BR>
&nbsp;» <A href="members.php"><b>Member List</b></A><BR>
