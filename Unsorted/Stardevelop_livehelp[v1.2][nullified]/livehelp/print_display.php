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
include('./include/functions.php');

$language_file = './locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('./locale/lang_en.php');
}

session_start();
$login_id = $_SESSION['LOGIN_ID'];
$username = $_SESSION['USERNAME'];
session_write_close();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>" onLoad="parent.printFrame.focus();window.print();">
<table width="375" border="0" align="center">
    <tr> 
      <td width="22"><img src="icons/chat.gif" alt="<?php echo($language['chat_transcript']); ?>" width="22" height="22"></td>
      <td><font size="3" face="<?php echo($font_type); ?>"><em><?php echo($language['chat_transcript']); ?> :: <?php echo($username); ?></em></font> <div align="right"></div></td>
    </tr>
  </table>
<?php
include('./include/functions.php');

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

$query_select_msgs = "SELECT message_id,from_login_id,to_login_id,DATE_FORMAT(message_datetime,'%H:%i:%s') AS message_time,message,src_flag,dest_flag,username AS from_username FROM " . $table_prefix . "messages," . $table_prefix . "sessions WHERE from_login_id = login_id AND (from_login_id = '$login_id' OR to_login_id = '$login_id') ORDER BY message_datetime";
$rows_msgs = $SQLDISPLAY->selectall($query_select_msgs);

if (is_array($rows_msgs)) {
	foreach ($rows_msgs as $row) {
		if (is_array($row)) {
			
			//search and replace smilies with images if smilies are on
			if ($guest_smilies == "true") {
			$displayMessage = htmlSmilies($row['message'], '../icons/');
			}
			else {
			$displayMessage = $row['message'];
			}
		
			//outputs sent message
			if(($row['from_login_id'] == "$login_id")){
?>
<table width="375" border="0" align="center">
  <tr> 
    <td> <div align="left"><font size="<?php echo($font_size); ?>" color="<?php echo($sent_font_color); ?>" face="<?php echo($font_type); ?>"> 
        <strong>[<?php echo($row['message_time']); ?>] <?php echo($row['from_username']); ?></strong>: 
        <?php echo($displayMessage); ?><br>
        </font></div></td>
  </tr>
</table>
<?php
			}
			//outputs received message
			if(($row['to_login_id'] == "$login_id")){
?>
<table width="375" border="0" align="center">
  <tr> 
    <td> <div align="left"><font size="<?php echo($font_size); ?>" color="<?php echo($received_font_color); ?>" face="<?php echo($font_type); ?>"> 
        <strong>[<?php echo($row['message_time']); ?>] <?php echo($row['from_username']); ?></strong>: 
        <?php echo($displayMessage); ?><br>
        </font></div></td>
  </tr>
</table>
<?php
			}
		}
	}
}
$SQLDISPLAY->disconnect();

?>
</body>
</html>