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

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

$support_login_id = $_GET['LOGIN_ID'];

$query_user_stats = "SELECT *,((UNIX_TIMESTAMP(last_refresh) - UNIX_TIMESTAMP(datetime))) AS time_online FROM " . $table_prefix . "sessions, " . $table_prefix . "statistics WHERE " . $table_prefix . "sessions.login_id = " . $table_prefix . "statistics.login_id AND " . $table_prefix . "sessions.login_id = '$support_login_id'";
$row_user_stats = $SQLDISPLAY->selectquery($query_user_stats);

$query_previous_sessions = "SELECT login_id,username,active FROM " . $table_prefix . "sessions WHERE session_id = '" . $row_user_stats["session_id"] . "'";
$rows_previous_sessions = $SQLDISPLAY->selectall($query_previous_sessions);

$current_session = $row_user_stats['session_id'];
$query_current_page = "SELECT current_page,page_path FROM " . $table_prefix . "requests WHERE session_id = '$current_session'";
$row_current_page = $SQLDISPLAY->selectquery($query_current_page);
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center"> 
  <table width="450" border="0">
    <tr> 
      <td width="22"><strong><img src="../icons/stats_small.gif" alt="<?php echo($language['user_stats']); ?>" width="22" height="22" border="0"></strong></td>
      <td colspan="2"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['user_stats']); ?> 
        :: <?php echo($row_user_stats['username']); ?></em></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td width="100">&nbsp;</td>
      <td width="216">&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['login_id']); ?>:</font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row_user_stats['login_id']); ?></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['session_id']); ?>: 
          </font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row_user_stats['session_id']); ?></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['username']); ?>:</font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row_user_stats['username']); ?></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['ip_address']); ?>:</font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row_user_stats['ip_address']); ?></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['hostname']); ?>:</font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row_user_stats['hostname']); ?></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['email']); ?>:</font></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row_user_stats['email']); ?></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['user_agent']); ?>:</font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row_user_stats['user_agent']); ?></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['resolution']); ?>:</font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row_user_stats['resolution']); ?></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['time_online']); ?>:</font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <?php
	  $minutes = (int)($row_user_stats['time_online'] / 60);
	  if ($minutes < 10) {
	  $minutes = '0' . (int)($row_user_stats['time_online'] / 60);
	  }
	  $seconds = ($row_user_stats['time_online'] % 60);
	  if ($seconds < 10) {
	  $seconds = '0' . ($row_user_stats['time_online'] % 60);
	  }
	  echo($minutes . ':' . $seconds);
	  ?>
        <?php echo($language['minutes']); ?> </font></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['rating']); ?>:</font></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php if ($row_user_stats['rating'] > 0 ) { echo($row_user_stats['rating'] . ' / 5'); } else { echo($language['unavailable']); } ?></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['prior_chats']); ?>:</font></td>
      <td> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <?php
	if (is_array($rows_previous_sessions)) {
		$count = 0;
		$num_prev_sess_rows = count($rows_previous_sessions);
		if ($num_prev_sess_rows == 1) {
			echo($language['none']);
		}
		foreach ($rows_previous_sessions as $row_previous_sessions) {
		$count += 1;
			if ((is_array($row_previous_sessions)) && ($num_prev_sess_rows > 1)) {
				if($count < $num_prev_sess_rows) {
				?>
        <a href="view_transcript.php?<?php echo(SID); ?>&SLOGIN_ID=<?php echo($row_previous_sessions['active']); ?>&LOGIN_ID=<?php echo($row_previous_sessions['login_id']); ?>&USER=<?php echo($row_previous_sessions['username']); ?>" target="displayFrame" class="normlink"> 
        <?php
						echo($row_previous_sessions['login_id']);
				?>
        </a>, 
        <?php
				}
				else if($count = $num_prev_sess_rows) {
				?>
        <a href="view_transcript.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($row_previous_sessions['login_id']); ?>&USER=<?php echo($row_previous_sessions['username']); ?>" target="displayFrame" class="normlink"> 
        <?php
						echo($row_previous_sessions['login_id']);
				?>
        </a> 
        <?php
				}
			}
		}
	}
?>
        </font> </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['referer_url']); ?>:</font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row_user_stats['referer_url']); ?></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['current_url']); ?>:</font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <?php
	  $current_url = $row_current_page['current_page'];
	  if($current_url == "") {
	  echo($language['unavailable']);
	  }
	  else {
	  echo($current_url);
	  }
	  ?>
        </font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['page_path']); ?>:</font></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <?php
	  $page_path = $row_current_page['page_path'];
	  if($page_path == "") {
	  echo($language['unavailable']);
	  }
	  else {
	  echo($page_path);
	  }
	  ?>
        </font></td>
    </tr>
  </table>
</div>
</body>
</html>