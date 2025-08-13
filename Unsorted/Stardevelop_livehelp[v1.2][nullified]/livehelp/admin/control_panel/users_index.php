<?php
/*
stardevelop.com Live Help
International Copyright stardevelop.com

You may not distribute this program in any manner,
modified or otherwise, without the express, written
consent from stardevelop.com

You may make modifications, but only for your own 
use and within the confines of the License Agreement.
All rights reserved.

Selling the code for this program without prior 
written consent is expressly forbidden. Obtain 
permission before redistributing this program over 
the Internet or in any other medium.  In all cases 
copyright and header must remain intact.  
*/
include('../../include/config_database.php');
include('../../include/class.mysql.php');
include('../../include/config.php');
include('../../include/auth.php');

$language_file = '../../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('../../locale/lang_en.php');
}

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center"></div>
<table width="400" border="0" align="center">
  <tr> 
    <td width="22"><img src="../../icons/users_small.gif" alt="<?php echo($language['manage_accounts']); ?>" width="22" height="22"></td>
    <td colspan="6"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['manage_accounts']); ?></em></font> <div align="right"></div></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['username']); ?></font></strong></td>
    <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['name']); ?></font></strong></td>
    <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['department']); ?></font></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><strong></strong></td>
  </tr>
  <?php
$query_select_users = "SELECT user_id,username,first_name,last_name,department FROM " . $table_prefix . "users WHERE status = '0'";
$rows_msgs = $SQLDISPLAY->selectall($query_select_users);

if (is_array($rows_msgs)) {
	foreach ($rows_msgs as $row) {
		if (is_array($row)) {
?>
  <tr> 
    <td>&nbsp;</td>
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row['username']); ?></font></td>
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row['first_name'] . ' ' . $row['last_name']); ?></font></td>
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row['department']); ?></font></td>
    <td width="22"><a href="./users_edit.php?<?php echo(SID); ?>&UID=<?php echo($row['user_id']); ?>&USER=<?php echo($row['username']); ?>"><img src="../../icons/edit_small.gif" alt="<?php echo($language['edit_user']); ?>" width="22" height="22" border="0"></a></td>
    <td width="22"><a href="./users_delete.php?<?php echo(SID); ?>&UID=<?php echo($row['user_id']); ?>&USER=<?php echo($row['username']); ?>"><img src="../../icons/editdelete_small.gif" alt="<?php echo($language['delete_user']); ?>" width="22" height="22" border="0"></a></td>
    <td width="22"><a href="./users_password.php?<?php echo(SID); ?>UID=<?php echo($row['user_id']); ?>&USER=<?php echo($row['username']); ?>"><img src="../../icons/encrypted_small.gif" alt="<?php echo($language['change_user_password']); ?>" width="22" height="22" border="0"></a></td>
  </tr>
  <?php
		}
	}
}
?>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="./users_add.php?<?php echo(SID); ?>" class="normlink"><?php echo($language['add_user']); ?></a></font></div></td>
    <td width="22"><a href="./users_add.php?<?php echo(SID); ?>"><img src="../../icons/user_add.gif" alt="<?php echo($language['add_user']); ?>" width="22" height="22" border="0"></a></td>
  </tr>
</table>
</body>
</html>