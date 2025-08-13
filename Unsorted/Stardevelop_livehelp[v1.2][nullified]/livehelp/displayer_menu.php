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
include('./include/config_database.php');
include('./include/class.mysql.php');
include('./include/config.php');

$language_file = './locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('./locale/lang_en.php');
}

session_start();
$username = $_SESSION['USERNAME'];
session_write_close();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="include/styles.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>" marginwidth="0" leftmargin="5" topmargin="0" bottommargin="0" rightmagin="5">
<table width="100%" border="0" align="center">
  <tr> 
    <td width="22"><img src="icons/chat.gif" alt="<?php echo($language['chat_transcript']); ?>" width="22" height="22"></td>
    <td><font size="3" face="<?php echo($font_type); ?>"><em><?php echo($language['chat_transcript']); ?> 
      :: <?php echo($username); ?></em></font> <div align="right"></div></td>
    <td><table border="0" align="right" cellpadding="0" cellspacing="0">
        <tr> 
          <td width="30"><div align="center"><a href="logout.php" target="_top"><img src="icons/disconnected.gif" alt="<?php echo($language['logout']); ?>" width="22" height="22" border="0"></a></div></td>
          <td><font size="1" face="<?php echo($font_type); ?>"><a href="logout.php" target="_top" class="normlink"><?php echo($language['logout']); ?></a></font></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>