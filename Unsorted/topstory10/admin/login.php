<?
session_start();
if(($usrName==$tst["admin"]["usrName"]) && ($usrPass==$tst["admin"]["usrPass"])) {
	$valid_user=$usrPass;
	session_register("valid_user");
}
if(!session_is_registered("valid_user")) {
	?>
	<form method=post action="<? $PHP_SELF ?>">
	<table align="center" border="0" cellspacing="3" cellpadding="4">
		<tr>
		<td colspan="2">Enter Your User Name and PassWord</td>
	</tr>
	<tr>
		<td align="right">User Name : </td>
		<td><input type="text" name="usrName"></td>
	</tr>
	<tr>
		<td align="right">Password : </td>
		<td><input type="password" name="usrPass"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" value="- Authenticate -"></td>
	</tr>
	</table>
	</form>
	<?
	exit;
}
	?>
