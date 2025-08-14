<?
require "config.php";
?>
<?
//if calling pages into an index file place this section at the start of your index
if (isset($_POST['userid']) && isset($_POST['password']))
{
  // if the user has just tried to log in
  $query = "select * from i_users "
           ."where username='$_POST[userid]' "
           ." and passwd='$_POST[password]' ";
  $result = mysql_query($query, $db_conn);
  $row = mysql_fetch_assoc($result);
  if (mysql_num_rows($result) >0)
  {
    // if they are in the database register the user id
	//below are the session variables add/remove or modify these as you wish
    $valid_user = $_POST['userid'];
    $_SESSION['valid_user'] = $valid_user;
    $_SESSION['pass'] = $_POST['password'];
	$_SESSION['groupname'] = $row['groupname'];
	
	 }
}
//end of section
?>
<html>
<head>


<title>Authmain</title>
</head>
<BODY>
<table align="center" width="950" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>

<table align="left" width="160" cellpadding="0" cellspacing="0" border="0">
<tr>
<td height="220" width="160">
<?
if ($log=="") include "i-auth.php";
if ($log=="logout") include "logout.php";
if ($log=="change") include "changepass.php";
if ($log=="1") include "changepass.php";
if ($log=="forgot") include "forgot.php";
?>
</td>
</tr>
<tr>
<td height="30">
</td>
<tr>
<tr>
<td>
<a href="index.php?page=home" />Home</a>
</td>
</tr>
<tr>
<td>

</td>
</tr>
</table>



<table align="center" border="0" cellspacing="5" cellpadding="5">
<tr>
<td height="50" align="center">
Authmain
</td>
</tr>
<tr>
<td class="log" align="center">
<?
if ($page=='reg') include 'register.php';
if ($page=='home') include 'home.php';
?>
</td>
</tr>
<tr>
<td align="center">
</td>
</tr>
</table>
</td>
</tr>
</table>
</BODY>
</HTML>