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
include('include/config_database.php');
include('include/class.mysql.php');
include('include/config.php');

session_start();
session_write_close();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<frameset rows="*,2" frameborder="NO" border="0" framespacing="0">
  <frame src="logout_index.php?<?php echo(SID); ?>" name="logoutContentsFrame" scrolling="NO">
  <frame src="blank.php?<?php echo(SID); ?>" name="printFrame" scrolling="NO">
</frameset>
<noframes>
<body>
</body>
</noframes>
</html>