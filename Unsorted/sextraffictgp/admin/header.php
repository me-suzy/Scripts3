<?php
include ("../stconfig.php");
include("global.php");

if(!$logged_in || $logged_in==""){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr> 
    <td valign="top"> 
      <form method="post" action="<?=$PHP_SELF?>">
        <table border="0" cellspacing="0" cellpadding="10" width="500" align="center" bgcolor="#003366">
          <tr> 
            <td> 
              <div align="center"><b><font size="3" face="Arial" color="#FFFFFF">Admin Login</font></b></div>
            </td>
          </tr>
        </table>
        <table border="0" cellspacing="0" cellpadding="5" width="500" align="center">
          <tr> 
            <td width="50%"><div align="right"><b><font size="2" face="Arial, Helvetica, sans-serif">Username</font></b></div></td>
            <td width="50%"><input type="text" name="admin_username" size="15" maxlength="15"></td>
          </tr>
          <tr> 
            <td width="50%"><div align="right"><b><font size="2" face="Arial, Helvetica, sans-serif">Password</font></b></div></td>
            <td width="50%"><input type="password" name="admin_password" size="15" maxlength="15"></td>
          </tr>
          <tr> 
            <td width="50%"><div align="center">&nbsp;</div></td>
            <td width="50%"><input type="submit" name="Submit" value="Login"><input type="hidden" value="true" name="member_login"></td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr>
    <td valign="top"><div align="center"><font size="1" face="Verdana">Powered by <b><a href="http://www.sextraffic.net">SexTraffic.Net</a></b></font></div></td>
  </tr>
</table>
<?php
exit;
}


if($logged_in || !$logged_in==""){

$admincolor1 = "#F4F5FD";
$admincolor2 = "#EBEBEB";
$admincolor3 = "#333366";
$admincolor4 = "#FFFFFF";

?>
<html>
<head>
<title>SexTraffic.net TGP Admin Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" bottomMargin="0" rightMargin="0" topmargin="0" marginwidth="0" marginheight="0">
<style>
<!--
A:link{text-decoration: none; color: #221E48;}
A:visited{text-decoration: none; color: #221E48;}
A:active{text-decoration: none; color: #339966;}
A:hover{text-decoration: none; color: #339966;}
-->
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#333366">
  <tr> 
    <td height="30" width="64%"> 
      <div align="left"><font color="#FFFFFF" size="3" face="arial"><b>SexTraffic.net TGP Admin Control Panel</b></font></div>
    </td>
	    
    <td height="30" width="36%">
      <div align="right">

<?php $open = @fopen("http://www.sextraffic.net/scripts/version1.php", "r"); if($open){ include("http://www.sextraffic.net/scripts/version1.php");} else { echo "<font size='2' face='arial'><a href='http://www.sextraffic.net'><font color='#FFFFFF'><b>Version Check</b></font></a></font>&nbsp;&nbsp;";}?>
</div>
    </td>
	  </tr>
</table>
<table width="100%" border="0" cellspacing="" cellpadding="0" height="93%">
  <tr>
    <td width="200" bgcolor="#f7f7f7" valign="top"><?php include("menu.php");?></td>
	<td background="images/checker.gif" width="1"></td>
    <td valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td valign="top">
<?php
}
?>