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

session_start();
$login_id = $_SESSION['LOGIN_ID'];
session_write_close();

$SQLCONNECT = new mySQL; 
$SQLCONNECT->connect();

$query_select = "SELECT server FROM " . $table_prefix . "sessions WHERE login_id = $login_id";
$row = $SQLCONNECT->selectquery($query_select);
if (is_array($row)) {
	$server = $row['server'];
}

$SQLCONNECT->disconnect();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--

// stardevelop.com Live Help International Copyright 2003
// JavaScript Check Status Functions

function currentTime() {
	var date = new Date();
	return date.getTime();
}

function onlineRefresher() {
	var tracker = new Image;
	var time = currentTime();
	
	tracker.src = './online_refresher.php?TIME=' + time + '&<?php echo(SID); ?>';
	var timer = window.setTimeout('onlineRefresher();', <?php echo($connection_timeout * 400); ?>);
}

onlineRefresher();

parent.statisticsFrame.location.href='statistics.php?WIDTH=' + screen.width + '&HEIGHT=' + screen.height + '&<?php echo(SID); ?>';

//-->
</script>
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>">
<div align="center"> 
  <p><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><img src="<?php echo($server . $livehelp_logo); ?>" alt="<?php echo($livehelp_name); ?>" border="0"><br>
    <?php
  echo($welcome_note);
  ?>
    </font></p>
</div>
</body>
</html>