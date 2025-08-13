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

ignore_user_abort(true);

if (!isset($_GET['REFRESH'])){ $_GET['REFRESH'] = "false"; }
$refresh = $_GET['REFRESH'];

session_start();
$current_login_id = $_SESSION['LOGIN_ID'];
session_write_close();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
<!--

function multiLoad(doc1,doc2) {
	parent.displayFrame.location.href=doc1;
	parent.messengerFrame.location.href=doc2;
}

<?php
if ($refresh == 'true') {
?>
window.setTimeout('parent.usersFrame.location.reload()', '<?php echo((int)$user_panel_refresh_rate*1000); ?>');
<?php
}
?>

//-->
</script>
<style type="text/css">
<!--
a.normlink:link { color: #000000; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif: none }
a.normlink:hover { text-decoration: underline ; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif}
.normlink {  text-decoration: none; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif}
-->
</style>
</head>
<body link="#000000" vlink="#000000" alink="#000000" onFocus="parent.document.title = 'Admin <?php echo($livehelp_name); ?>'">
<?php

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

$query_select = "SELECT s.datetime, u.department FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u  WHERE s.login_id = u.last_login_id AND s.username = u.username AND login_id = '$current_login_id'";
$rows = $SQLDISPLAY->selectall($query_select);
if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
		$login_datetime = $row['datetime'];
		$department = $row['department'];
		}
	}
}

?>
<font face="Verdana, Arial, Helvetica, sans-serif"> <br>
<table width="272" border="0" cellpadding="1" cellspacing="1">
  <tr> 
    <td colspan="6"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="../icons/users.gif" alt="<?php echo($language['online']); ?>" name="OnlineIcon" width="32" height="32"> 
      <font size="4"><?php echo($language['online']); ?></font></font></td>
  </tr>
  <?php
//ONLINE ADMIN USERS QUERY
$query_select = "SELECT s.username,s.login_id,(UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(s.datetime)) AS display_flag FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(s.last_refresh)) < '" . $connection_timeout . "' ORDER  BY s.username";
$rows = $SQLDISPLAY->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
			$display_flag = $row['display_flag'];
			$new_msgs = "";

			$query_select_new_msgs = "SELECT count(dest_flag) AS num_msgs FROM " . $table_prefix . "messages WHERE dest_flag = 0 AND from_login_id = " . $login_id;
			$row_new_msgs = $SQLDISPLAY->selectquery($query_select_new_msgs);
			if ($row_new_msgs['num_msgs'] > 0) {
			$new_msgs = '<img src="../icons/09.gif" border="0" width="22" height="22">';
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
parent.document.title = '*Admin <?php echo($livehelp_name); ?>';
//-->
</script>
  <?php
			}
			if ($display_flag < $user_panel_refresh_rate*2) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
parent.soundControlsFrame.document.soundsControl.Play();
parent.document.title = '*Admin <?php echo($livehelp_name); ?>';
//-->
</script>
  <?php
			}
?>
  <tr> 
    <td width="25"> <p align="center"><font size="2"> 
        <?php if ($current_login_id != $login_id) { ?>
        <a href="<?php echo("javascript:multiLoad('./displayer_frame.php?" . SID . "&USER=" . $username . "&LOGIN_ID=" . $login_id . "', './messenger.php?" . SID . "&USER=" . $username . "&LOGIN_ID=" . $login_id . "')"); ?>"> 
        <?php } ?>
        <img src="../icons/21.gif" alt="<?php echo($language['online_admin']); ?>" width="22" height="22" border="0"> 
        <?php if ($current_login_id != $login_id) { ?>
        </a> 
        <?php } ?>
        </font></p></td>
    <td width="100"><font size="2"> 
      <?php if ($current_login_id != $login_id) { ?>
      <a href="<?php echo("javascript:multiLoad('./displayer_frame.php?" . SID . "&USER=" . $username . "&LOGIN_ID=" . $login_id . "', './messenger.php?" . SID . "&USER=" . $username . "&LOGIN_ID=" . $login_id . "')"); ?>" class="normlink"> 
      <?php } ?>
      <?php echo($username); ?> 
      <?php if ($current_login_id != $login_id) { ?>
      </a> 
      <?php } ?>
      </font></td>
    <td width="22"><div align="center"><font size="2"><?php echo($new_msgs); ?></font></div></td>
    <td width="22"><font size="2">&nbsp;</font></td>
    <td width="22">&nbsp;</td>
    <td width="22"><font size="2">&nbsp;</font></td>
  </tr>
  <?php
		}
	}
}

//ONLINE GUEST USERS QUERY
$query_select = "SELECT s.username,s.login_id,(UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(datetime)) AS display_flag FROM " . $table_prefix . "sessions AS s WHERE active = '$current_login_id' AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_refresh)) < '" . $connection_timeout . "' ORDER BY username";
$rows = $SQLDISPLAY->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
			$display_flag = $row['display_flag'];
			$new_msgs = "";

			$query_select_new_msgs = "SELECT count(dest_flag) AS num_msgs FROM " . $table_prefix . "messages WHERE dest_flag = 0 AND from_login_id = " . $login_id;
			$row_new_msgs = $SQLDISPLAY->selectquery($query_select_new_msgs);
			if ($row_new_msgs['num_msgs'] > 0) {
			$new_msgs = '<img src="../icons/09.gif" border="0" width="22" height="22" alt="' . $language['new_msg'] . '">';
?>
  <SCRIPT LANGUAGE="JavaScript">
<!--
parent.document.title = '*Admin <?php echo($livehelp_name); ?>';
//-->
</script>
  <?php
			}
			if ($display_flag < $user_panel_refresh_rate*2) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
parent.soundControlsFrame.document.soundsControl.Play();
parent.document.title = '*Admin <?php echo($livehelp_name); ?>';
//-->
</script>
  <?php
			}
?>
  <tr> 
    <td width="25"> <p align="center"><font size="2"><a href="<?php echo("javascript:multiLoad('./displayer_frame.php?" . SID . "&USER=" . $username . "&LOGIN_ID=" . $login_id . "', './messenger.php?" . SID . "&USER=" . $username . "&LOGIN_ID=" . $login_id ."')"); ?>"><img src="../icons/20.gif" alt="<?php echo($language['online_guest']); ?>" width="22" height="22" border="0"></a><br>
        </font></p></td>
    <td width="100"><font size="2"><a href="<?php echo("javascript:multiLoad('./displayer_frame.php?" . SID . "&USER=" . $username . "&LOGIN_ID=" . $login_id . "', './messenger.php?" . SID . "&USER=" . $username . "&LOGIN_ID=" . $login_id ."')"); ?>" class="normlink"><?php echo($username); ?></a></font></td>
    <td width="22"><div align="center"><font size="2"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>" target="displayFrame"><img src="../icons/03.gif" alt="<?php echo($language['information']); ?>" width="22" height="22" border="0"></a></font></div></td>
    <td width="22"><div align="center"><font size="2"><img src="../icons/configure_small.gif" border="0" width="22" height="22" alt="<?php echo($language['edit_user']); ?>"></font></div></td>
    <td width="22"><div align="center"><a href="./././ignore_user.php?<?php echo(SID); ?>&TO_LOGIN_ID=<?php echo($login_id); ?>&FROM_LOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/ignore_user.gif" width="22" height="22" border="0"></a></div></td>
    <td width="22"><div align="center"><font size="2"><?php echo($new_msgs); ?></font></div></td>
  </tr>
  <?php 
		}
	}
}
?>
  <tr> 
    <td><font size="2">&nbsp;</font></td>
    <td><font size="2">&nbsp;</font></td>
    <td width="22"><font size="2">&nbsp;</font></td>
    <td width="22"><font size="2">&nbsp;</font></td>
    <td width="22">&nbsp;</td>
    <td width="22"><font size="2">&nbsp;</font></td>
  </tr>
  <tr> 
    <td colspan="6"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="../icons/pending.gif" alt="<?php echo($language['pending']); ?>" width="32" height="32"> 
      <font size="4"><?php echo($language['pending']); ?></font></font></td>
  </tr>
  <?php
//PENDING USERS QUERY displays pending users not loged in on users users table depending on department settings
if ($departments == 'true') {
	$query_select = "SELECT DISTINCT s.login_id,s.username,(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.datetime)) AS display_flag FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < '" . $connection_timeout . "' AND s.active = 0 AND s.department = '$department' ORDER BY s.username";
}
elseif ($departments == 'false') {
	$query_select = "SELECT DISTINCT s.login_id,s.username,(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.datetime)) AS display_flag FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < '" . $connection_timeout . "' AND s.active = 0 ORDER BY s.username";
}
$rows = $SQLDISPLAY->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
			$display_flag = $row['display_flag'];
			$new_msgs = "";
			
			$query_select_new_msgs = "SELECT count(dest_flag) AS num_msgs FROM " . $table_prefix . "messages WHERE dest_flag = 0 AND from_login_id = $login_id";
			$row_new_msgs = $SQLDISPLAY->selectquery($query_select_new_msgs);
			if ($row_new_msgs['num_msgs'] > 0) {
			$new_msgs = '<img src="../icons/09.gif" border="0" width="22" height="22" alt="' . $language['new_msg'] . '">';
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
parent.document.title = '*Admin <?php echo($livehelp_name); ?>';
//-->
</script>
  <?php
			}
			if ($display_flag < $user_panel_refresh_rate*2) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
parent.soundControlsFrame.document.soundsControl.Play();
parent.document.title = '*Admin <?php echo($livehelp_name); ?>';
//-->
</script>
  <?php
			}
?>
  <tr> 
    <td width="25"> <p align="center"><font size="2"><a href="./././make_active.php?<?php echo(SID); ?>&USER=<?php echo($username); ?>&LOGIN_ID=<?php echo($login_id); ?>&SLOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/19.gif" alt="<?php echo($language['pending_user']); ?>" width="22" height="22" border="0"></a><br>
        </font></p></td>
    <td width="100"><font size="2"><a href="./././make_active.php?<?php echo(SID); ?>&USER=<?php echo($username); ?>&LOGIN_ID=<?php echo($login_id); ?>&SLOGIN_ID=<?php echo($current_login_id); ?>" class="normlink"><?php echo($username); ?></a></font></td>
    <td width="22"><div align="center"><font size="2"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>" target="displayFrame"><img src="../icons/03.gif" alt="<?php echo($language['information']); ?>" width="22" height="22" border="0"></a></font></div></td>
    <td width="22"><div align="center"><font size="2"><a href="./././make_active.php?<?php echo(SID); ?>&USER=<?php echo($username); ?>&LOGIN_ID=<?php echo($login_id); ?>&SLOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/10.gif" alt="<?php echo($language['add_user']); ?>" width="22" height="22" border="0"></a></font></div></td>
    <td width="22"><div align="center"><a href="./././ignore_user.php?<?php echo(SID); ?>&TO_LOGIN_ID=<?php echo($login_id); ?>&FROM_LOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/ignore_user.gif" width="22" height="22" border="0"></a></div></td>
    <td width="22"><div align="center"><font size="2"><?php echo($new_msgs); ?></font></div></td>
  </tr>
  <?php
		}
	}
}
?>
  <tr> 
    <td><font size="2">&nbsp;</font></td>
    <td><font size="2">&nbsp;</font></td>
    <td width="22"><font size="2">&nbsp;</font></td>
    <td width="22"><font size="2">&nbsp;</font></td>
    <td width="22">&nbsp;</td>
    <td width="22"><font size="2">&nbsp;</font></td>
  </tr>
  <tr> 
    <td colspan="6"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="../icons/offline.gif" alt="<?php echo($language['offline']); ?>" width="32" height="32"> 
      <font size="4"><?php echo($language['offline']); ?></font></font></td>
  </tr>
  <?php
//OFFLINE USERS QUERY
$query_select = "SELECT DISTINCT s.login_id,s.username FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE s.datetime > '$login_datetime' AND (active = '$current_login_id' OR active = '0') AND u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) > '" . $connection_timeout . "' ORDER BY s.username";
$rows = $SQLDISPLAY->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
?>
  <tr> 
    <td width="25"> <p align="center"><font size="2"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>" target="displayFrame"><img src="../icons/16.gif" alt="<?php echo($language['offline_user']); ?>" width="22" height="22" border="0"></a><br>
        </font></p></td>
    <td width="100"><font size="2"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>" target="displayFrame" class="normlink"><?php echo($username); ?></a></font></td>
    <td width="22"><div align="center"><font size="2"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>" target="displayFrame"><img src="../icons/03.gif" alt="<?php echo($language['information']); ?>" width="22" height="22" border="0"></a></font></div></td>
    <td width="22"><font size="2"><a href="./././ignore_user.php?<?php echo(SID); ?>&TO_LOGIN_ID=<?php echo($login_id); ?>&FROM_LOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/ignore_user.gif" width="22" height="22" border="0"></a></font></td>
    <td width="22">&nbsp;</td>
    <td width="22"><font size="2">&nbsp;</font></td>
  </tr>
  <?php
		}
	}
}

$SQLDISPLAY->disconnect();
?>
</table>
</font> 
</body>
</html>