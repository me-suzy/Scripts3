<?
session_start();
$admin = 1;
session_register(admin);
?>

<!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
     <html><head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=x-user-defined" />
<link rel="stylesheet" href="../style.css" type="text/css" />

<?

if (!$override) 
{
include_once("config/general.inc.php");
include_once("config/options.inc.php");
include_once("config/globalad.inc.php");
include_once("config/globaluser.inc.php");
include_once("db.php");
}
?>
<style type="text/css">
A:link { color: blue; text-decoration: underline}
A:visited { color: blue; text-decoration: underline}
A:hover { color: red; text-decoration: underline}
</style>

</head>
<body>
<p />
<table>
<tr>
<td colspan="3">
<!-- Header rows -->

<table border="0" cellpadding="3" width="100%" cellspacing="3" bgcolor="#FFFFFF">
  <tr>
    <td width="297" colspan="3"><a href="http://www.deltascripts.com/"><img height="23" src="logo.gif" width="140" align="left" alt="" border="0" /></a>
    </td>
    <td width="431" colspan="5">
      <p align="right" /> PHP Classifieds <a href="">V6.08</a> </td>
  </tr>
  <tr><td></td>
  </tr>
</table>
<!-- END Header rows-->
</td>
</tr>

<tr>
<td valign="top" width="130" bgcolor="#FFFFFF" height="100%">

  <!-- Table menu -->
        <table border="1" cellpadding="3" cellspacing="0" width="130">

        <tr>
                        <td bgcolor="lightgrey"> &nbsp; Menu </td>
  </tr>

        <tr bgcolor="white">
        <td width="200">
                          
                          &nbsp;<a href = "index.php">Main admin</a><br />
                          &nbsp;<a href = "list_users.php">Users admin</a><br />
                          &nbsp;<a href = "ads.php">Ads admin</a><br />
                          &nbsp;<a href = "add_cat.php">Category admin</a><br />
                          &nbsp;<a href = "validate.php">Validate ads</a><br />
                          &nbsp;<a href = "validate_users.php">Validate users</a><br />
                          &nbsp;<a href = "email.php">Email members</a><br />
                          &nbsp;<a href = "set.php">Settings</a><br />
                          &nbsp;<a href = "check_update.php">Check update</a><br />
                          &nbsp;<a href = "extra.php">Extra fields</a><br />
                          <? if (!$auto)
                          {
                           print "&nbsp;<a href = '../update.php'>Update counter</a><br />";
                          }
                          ?>
                          &nbsp;<a href = "stats.php">View stats</a><br />
                          &nbsp;<a href = "../" target="_blank">View frontpage</a><br />
                          &nbsp;<a href = "backup.php">Backup</a><br />
                          &nbsp;<a href = "licence.php">Licence</a><br />
             <p />
         </td>
   </tr>
   </table>
<center>
   
    <p>
    <a href="http://validator.w3.org/check/referer"><img
        src="http://www.w3.org/Icons/valid-xhtml10"
        alt="Valid XHTML 1.0!" height="31" width="88" border="0" /></a>
</p>
<p>
 <a href="http://jigsaw.w3.org/css-validator/">
  <img style="border:0;width:88px;height:31px"
       src="http://jigsaw.w3.org/css-validator/images/vcss" 
       alt="Valid CSS!" />
 </a>
</p>

</center>
   <!-- END Table menu -->
</td>
<td align="left" valign="top" width="100%">
