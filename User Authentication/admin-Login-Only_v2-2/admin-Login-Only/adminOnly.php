<?php
session_start();
if(   (!isset($_SESSION['adminUser'])) || (!isset($_SESSION['adminPassword'])) ) {
	include_once("adminLogin.php");
	exit;
}
// requires for multi applications inter-operability using same admin-Login-Only module
if(file_exists("util.php")) { // phpyellow admin user and password file
	require_once("util.php");
}
if(file_exists("secret.php")) { // admin-Login-Only admin user and password file
	require_once("secret.php");
}
/* adminOnly.php
   if the session variables are not set or are incorrect values 
   then present the login screen
*/
if( ($_SESSION['adminUser'] != ADMINUSER) || ($_SESSION['adminPassword'] != ADMINPASSWORD) ) {
	include_once("adminLogin.php");
	exit;
}else{?>
<table>
<tr>
	<td width=32><a href="adminLogOut.php"><img src="images/icons/icon_logout.gif" width="32" height="32" border=0 alt="Logout"></a></td>
	<td width=32><a href="<?php echo ADMINHOME;?>"><img src="images/icons/icon_admin.gif" width="32" height="32" border=0 alt="Admin"></a></td>
	<td width="100%" align="center"><div style="background-color:#A8D470;font-size:xx-small;font-style:italic;">Page secured by <a href="http://www.globalissa.com" target="_blank">admin-Login-Only</a> | <a href="http://www.globalissa.com/checkversion/index.php?productname=admin-Login-Only&installversion=2.2" target="_blank">Check this version 2.2</a></div></td>
	<td width=32><a href="<?php echo ADMINHOME;?>"><img src="images/icons/icon_admin.gif" width="32" height="32" border=0 alt="Admin"></a></td>
	<td width=32><a href="adminLogOut.php"><img src="images/icons/icon_logout.gif" width="32" height="32" border=0 alt="Logout"></a></td>
</tr>
<tr>
	<td style="font-size:x-small;"><a href="adminLogOut.php">Logout</a></td>
	<td style="font-size:x-small;"><a href="<?php echo ADMINHOME;?>">Admin</a></td>
	<td>&nbsp;</td>
	<td style="font-size:x-small;"><a href="<?php echo ADMINHOME;?>">Admin</a></td>
	<td style="font-size:x-small;"><a href="adminLogOut.php">Logout</a></td>
</tr>
</table>
<?php }?>