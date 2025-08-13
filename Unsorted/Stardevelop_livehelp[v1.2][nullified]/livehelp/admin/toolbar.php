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

$language_file = '../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('../locale/lang_en.php');
}
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body marginwidth="0" leftmargin="0" topmargin="0" bottommargin="0" rightmagin="0">
<table width="100" height="25" border="0" align="center">
  <tr> 
    <td><div align="center"><a href="javascript:history.go(-1)"><img src="../icons/back.gif" alt="<?php echo($language['back']); ?>" width="22" height="22" border="0"></a></div></td>
    <td><div align="center"><a href="<?php echo($admin_homepage); ?>" target="displayFrame"><img src="../icons/home.gif" alt="<?php echo($language['home']); ?>" width="22" height="22" border="0"></a></div></td>
    <td><div align="center"><a href="javascript:history.go(1)"><img src="../icons/forward.gif" alt="<?php echo($language['forward']); ?>" width="22" height="22" border="0"></a></div></td> 
  </tr>
</table>
</body>
</html>