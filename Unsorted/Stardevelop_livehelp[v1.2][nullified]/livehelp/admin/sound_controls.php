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
<link href="../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body text="#000000" link="#333333" vlink="#000000" alink="#000000" marginwidth="0" leftmargin="25" topmargin="0"> 
<font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="200" height="55" id="soundsControl" align="middle"> 
  <param name="allowScriptAccess" value="sameDomain" /> 
  <param name="movie" value="../include/soundsControl.swf?TIME=<?php echo(microtime()); ?>" /> 
  <param name="quality" value="high" /> 
  <param name="bgcolor" value="#ffffff" /> 
  <param name="PLAY" value="false" /> 
  <param name="LOOP" value="false" /> 
  <embed src="../include/soundsControl.swf?TIME=<?php echo(microtime()); ?>" width="100" height="25" loop="false" align="middle" quality="high" bgcolor="#ffffff" name="soundsControl" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" play="false" /> 
</object> 
</em></font> 
</body>
</body>
</html>
