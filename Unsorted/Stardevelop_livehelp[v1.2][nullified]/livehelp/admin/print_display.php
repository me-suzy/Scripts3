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
include('../include/config_database.php');
include('../include/class.mysql.php');
include('../include/config.php');
include('../include/auth.php');
include('../include/functions.php');

$language_file = '../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('../locale/lang_en.php');
}

session_start();
$login_id = $_SESSION['LOGIN_ID'];
session_write_close();

$support_login_id = $_GET['LOGIN_ID'];
$support_username = $_GET['USER'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body onFocus="parent.document.title = 'Admin <?php echo($livehelp_name); ?>"; onLoad="parent.displayFrame.focus();window.print();">
<table width="450" border="0" align="center">
    <tr> 
      <td width="22"><img src="../icons/fileprint.gif" alt="<?php echo($language['print_chat_transcript']); ?>" width="22" height="22"></td>
      
    <td><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['print_chat_transcript']); ?> :: <?php echo($support_username); ?></em></font> 
      <div align="right"></div></td>
    </tr>
</table>
<?php
include('displayer_include.php');
?>
</body>
</html>