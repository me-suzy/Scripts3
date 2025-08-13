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

$language_file = '../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('../locale/lang_en.php');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body marginwidth="0" leftmargin="0" topmargin="0" bottommargin="0" rightmagin="0">
<div align="right">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CBE7F7">
    <tr> 
      <td width="20" height="20"><img src="../images/header_left.gif" width="20" height="20"></td>
      <td><div align="right"><font color="#666666" size="1" face="Verdana, Arial, Helvetica, sans-serif"><em> 
          <?php echo($language['stardevelop_slogan']); ?>
          </em></font></div></td>
      <td width="20"><img src="../images/header_right.gif" width="20" height="20"></td>
    </tr>
  </table>
</div>
</body>
</html>