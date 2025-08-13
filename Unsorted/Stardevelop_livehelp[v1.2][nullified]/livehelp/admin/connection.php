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

session_start();
$current_session = session_id();
$username_session = $_SESSION['USERNAME'];
session_write_close();

?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
  <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
<?php
print($language['started_session'] . ' ' . $current_session . "<br>");
flush();
print($language['connected_to_db'] . ' ' . $username_session . '@' . $_SERVER["SERVER_NAME"] . '...' . "<br>");
flush();
?>
  <br>
  </font>
  </p>
</body>
</html>