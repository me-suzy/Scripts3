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

$language_file = '../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('locale/lang_en.php');
}

ignore_user_abort(true);

session_start();
$from_login_id = $_SESSION['LOGIN_ID'];
session_write_close();

$SQLLOGOUT = new mySQL; 
$SQLLOGOUT->connect();

$query_select_to_login_id = "SELECT active FROM " . $table_prefix . "sessions WHERE login_id = '$from_login_id'";
$row_to_login_id = $SQLLOGOUT->selectquery($query_select_to_login_id);
if (is_array($row_to_login_id)) {
	$to_login_id = $row_to_login_id["active"];
}
else {
	$to_login_id = 0;
}

$send_message = '<em>' . addslashes($language['livehelp_system_message']) . ':<br>' . addslashes($language['logout_user_message']) . '</em>';

//send message to notify user they are being ignored or declined
$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message) VALUES('','$from_login_id','$to_login_id',NOW(),'$send_message')";
$SQLLOGOUT->insertquery($query);

$SQLLOGOUT->disconnect();

header('Location: logout_frame.php?' . SID);
?>