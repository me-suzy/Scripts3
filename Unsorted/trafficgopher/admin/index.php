<?php
session_start();
ob_start();
include("../includes/admin_header.php");
?>
<?php
if($Submit == "Submit")
{
	$password = str_replace("'","''",$password);
	$password = str_replace("\\","",$password);
	$st = "select * from StatAdmin where password = '$password'";
	$rs = mysql_query($st) or die(mysql_error());
	if(mysql_num_rows($rs) > 0)
	{
		$row = mysql_fetch_array($rs);
		$sessionAdmin = "admin";
		header("Location:changeInfo.php");
	}
	else
	{
		$msg = $M_Usernamenotfound;
	}
}

?>
<script Language="Javascript">
function validate()
{
	with(document.frm)
	{
		if(password.value == "")
		{
			alert("Please enter password");
			password.focus();
			return false;
		}
	}
}
</script>
<form name=frm method="post" action="index.php" onSubmit="return validate();">
<table width="300" border="0" cellspacing="1" cellpadding="2" align="center">
<tr>
	<td colspan="2">
	  <div align="center">
		<b><font face="Arial, Helvetica, sans-serif" size="2">Admin Login</font></b>
	  </div>
	</td>
</tr>
<tr>
	<td colspan="2">
	  <div align="center">
		<?php
			if($msg != "")
			{
				print "<center><span class=error>$msg</span></center>";
			}
		?>
	  </div>
	</td>
</tr>
<tr>
	<td width="100">
	  <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Password:</font></div>
	</td>
	<td width="200">
	  <input type="password" size=12 name="password">
	</td>
</tr>
<tr>
	<td colspan="2">
	  <div align="center">
		<input type="submit" name="Submit" value="Submit" class="button">
		<input type="reset" name="Reset" value="Reset" class="button">
	  </div>
	</td>
</tr>
</form>
<?php
include("../includes/admin_footer.php");
?>