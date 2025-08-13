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
include('../include/functions.php');

session_start();
$login_id = $_SESSION['LOGIN_ID'];
session_write_close();

$support_username = $_GET['USER'];
$support_login_id = $_GET['LOGIN_ID'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
<!--
function writeMessage(messageText) {
    top.displayFrame.displayContentsFrame.document.write(messageText);  
}

function rescroll() {
	top.displayFrame.displayContentsFrame.window.scrollTo( 0, 99999999 );
	top.messengerFrame.document.message_form.MESSAGE.focus();
}
//-->
</script>
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>">
<?php

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

$query_select_msgs = "SELECT message_id,from_login_id,to_login_id,DATE_FORMAT(message_datetime,'%H:%i:%s') AS message_time,message,src_flag,dest_flag,username AS from_username FROM " . $table_prefix . "messages," . $table_prefix . "sessions WHERE from_login_id = login_id AND (from_login_id = '$support_login_id' OR to_login_id = '$support_login_id') ORDER BY message_datetime";
$rows_msgs = $SQLDISPLAY->selectall($query_select_msgs);

if (is_array($rows_msgs)) {
	foreach ($rows_msgs as $row) {
		if (is_array($row)) {
		
			$message = str_replace('\'', '\\\'', $row['message']);
			$message = str_replace("\r\n", "\\r\\n", $message); 
			
			//search and replace smilies with images if smilies are on
			if ($admin_smilies == "true") {
			$displayMessage = htmlSmilies($message, '../icons/');
			}
			else {
			$displayMessage = $message;
			}
			
			//outputs sent message
			if(($row['from_login_id'] == "$login_id") && ($row['src_flag'] == "0")){

?>
<script language="JavaScript">
<!--
writeMessage('<table width="450" border="0" align="center"><tr><td><div align="left"><font size="<?php echo($admin_chat_font_size); ?>" color="<?php echo($sent_font_color); ?>" face="Verdana, Arial, Helvetica, sans-serif"><strong>[<?php echo($row['message_time']); ?>] <?php echo($row['from_username']); ?></strong>: <?php echo($displayMessage); ?><br></font></div></td></tr></table>');
rescroll();
//-->
</script>
<?php
				//updates displayed flag
				$query_update_displayed = "UPDATE " . $table_prefix . "messages SET src_flag=1 WHERE message_id = '" . $row['message_id'] . "'";
				$SQLDISPLAY->miscquery($query_update_displayed);
			}
			//outputs received message
			if(($row['to_login_id'] == "$login_id") && ($row['dest_flag'] == "0")){
				
?>
<script language="JavaScript">
<!--
writeMessage('<table width="450" border="0" align="center"><tr><td><div align="left"><font size="<?php echo($admin_chat_font_size); ?>" color="<?php echo($received_font_color); ?>" face="Verdana, Arial, Helvetica, sans-serif"><strong>[<?php echo($row['message_time']); ?>] <?php echo($row['from_username']); ?></strong>: <?php echo($displayMessage); ?><br></font></div></td></tr></table>');
rescroll();
//-->
</script>
<?php
				//updates displayed flag
				$query_update_displayed = "UPDATE " . $table_prefix . "messages SET dest_flag=1 WHERE message_id = '" . $row['message_id'] . "'";
				$SQLDISPLAY->miscquery($query_update_displayed);
			}
		}
	}
}
$SQLDISPLAY->disconnect();
?>
<script language="JavaScript" type="text/JavaScript">
<!--
window.setTimeout('parent.displayRefreshFrame.location.reload(true);', <?php echo((int)$chat_refresh_rate * 1000); ?>);
//-->
</script>
</body>
</html>