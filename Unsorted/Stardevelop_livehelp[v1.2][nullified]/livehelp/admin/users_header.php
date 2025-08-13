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

session_start();
$username = $_SESSION['USERNAME'];
$login_id = $_SESSION['LOGIN_ID'];
session_write_close();

if (!isset($_GET['STATUS'])){ $_GET['STATUS'] = "online"; }
$connection_status = $_GET['STATUS'];
if ($connection_status == 'online') {
$connection_status = $language['online'];
}
elseif ($connection_status == 'offline') {
$connection_status = $language['offline'];
}
elseif ($connection_status == 'brb') {
$connection_status = $language['brb'];
}

$refresh_status = $_GET['REFRESH_STATUS'];
if ($refresh_status == '') { $refresh_status = 'false'; }

?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../include/styles.css" rel="stylesheet" type="text/css">
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

function multiLoad(doc1,doc2) {
	parent.usersHeaderFrame.location.href=doc1;
	parent.usersFrame.location.href=doc2;
}

onlineRefresher();

//Refresh the users frame to show the logged in user online
window.setTimeout('parent.usersFrame.location.reload()', 10000);
	
//-->
</script>
</head>
<body text="#000000" link="#333333" vlink="#000000" alink="#000000" marginwidth="0" leftmargin="0" topmargin="0" bottommargin="0" rightmagin="0">
<img src="../icons/helplogo.gif" alt="stardevelop.com Live Help" width="250" height="83"> <br>
<table width="272" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td><div align="right">
        <table width="100%" border="0" align="right" cellpadding="1" cellspacing="1">
          <tr> 
            <td width="22"><div align="center"><a href="online_mode.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>&REFRESH_STATUS=<?php echo($refresh_status); ?>"><img src="../icons/connected.gif" alt="<?php echo($language['online_connected_mode']); ?>" width="22" height="22" border="0"></a></div></td>
            <td width="22"><div align="center"><a href="offline_mode.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>&REFRESH_STATUS=<?php echo($refresh_status); ?>"><img src="../icons/disconnected.gif" alt="<?php echo($language['offline_hidden_mode']); ?>" width="22" height="22" border="0"></a></div></td>
            <td width="22"><div align="center"><a href="brb_mode.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>&REFRESH_STATUS=<?php echo($refresh_status); ?>"><img src="../icons/brb.gif" alt="<?php echo($language['brb_hidden_mode']); ?>" width="22" height="22" border="0"></a></div></td>
            <td><div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="http://livehelp.stardevelop.com/documentation/" target="_blank" class="normlink"><?php echo($language['help']); ?></a> 
                :: <?php echo($language['refresh']); ?> ( <a href="javascript:multiLoad('./users_header.php?<?php echo(SID); ?>&REFRESH_STATUS=true', './users.php?<?php echo(SID); ?>&REFRESH=true');" class="normlink"><?php if ($refresh_status == 'true') { ?><strong><?php } ?><?php echo($language['on']); ?><?php if ($refresh_status == 'true') { ?></strong><?php } ?></a> / <a href="javascript:multiLoad('./users_header.php?<?php echo(SID); ?>&REFRESH_STATUS=false', './users.php?<?php echo(SID); ?>&REFRESH=false');" class="normlink"><?php if ($refresh_status == 'false') { ?><strong><?php } ?><?php echo($language['off']); ?><?php if ($refresh_status == 'false') { ?></strong><?php } ?></a> )</font></div></td>
          </tr>
        </table>
        
      </div></td>
  </tr>
</table>
<table width="272" border="0" cellspacing="2" cellpadding="2">
  <tr> 
    <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['currently_logged_in'] . ' ' . $username); ?><br>
        <?php echo($language['using_mode']); ?> '<strong><?php echo($connection_status); ?></strong>'</em></font></div></td>
    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="../icons/logged_in.gif" alt="<?php echo($language['currently_online']); ?>" width="48" height="48">
    </font></td>
  </tr>
</table>
</body>
</html>