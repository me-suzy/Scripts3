<?
session_start();
session_register(admin);
include "../db_inc.php";
include "settings_inc.php";
db_connect();
?>
<html>
<head></head>
<style>
A:link { color: blue; text-decoration: underline}
A:visited { color: blue; text-decoration: underline}
A:hover { color: red; text-decoration: underline}
</style>
<body>
<p>
<table>
<tr>
<td colspan=3>
<!-- Header rows -->

<table border="0" cellpadding="3" width="100%" cellspacing="3" bgcolor="#FFFFFF">
  <tr>
    <td width="297" colspan="3"><a href="http://www.deltascripts.com/"><img height="23" src="logo.gif" width="140" align="left" border="0"></a>
    </td>
    <td width="431" colspan="5">
      <p align="right"><font face="Verdana" size="1">PHP MatchMaker <a href="">V 1.3</a></font></td>
  </tr>
  <tr><td></td>
  </tr>
</table>
<!-- END Header rows-->
</td>
</tr>

<tr>
<td valign=top width=130 bgcolor="#FFFFFF" height="100%">

  <!-- Table menu -->
        <table border="1" cellpadding="3" cellspacing="0" width="130">

        <tr>
                        <td bgcolor="lightgrey"><font color="black" face="Tahoma,Arial,Helvetica" size="2">&nbsp; Menu</font></td>
  </tr>

        <tr bgcolor="white">
        <td width="200">
                         <font face="Verdana" size="1">
                          &nbsp;<a href = "index.php">Main admin</a><br>
                          &nbsp;<a href = "list_users.php">Users admin</a><br>
                          &nbsp;<a href = "mail_users.php">Email users</a><br>
                          &nbsp;<a href = "settings.php">Settings</a><br>
                          
                          &nbsp;<a href = "../" target="_blank">View frontpage</a><br>
						 </font><p>
         </td>
   </tr>
   </table>

   <!-- END Table menu -->
</td>
<td align=left valign=top width=100%>
 <!-- Table menu -->
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
  
	<tr>
			<td bgcolor="lightgrey"><font color="black" face="Tahoma,Arial,Helvetica" size="2">&nbsp; Welcome</font></td>
  </tr>
   
	<tr bgcolor="white">
	<td width="100%">
			 <font face='Verdana' size='1'>
