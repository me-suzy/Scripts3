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

session_start();
session_write_close();

if (!isset($_GET['TO_LOGIN_ID'])){ $_GET['TO_LOGIN_ID'] = ""; }

$from_login_id = $_GET['FROM_LOGIN_ID'];
$to_login_id = $_GET['TO_LOGIN_ID'];

$SQLMESSAGE = new mySQL; 
$SQLMESSAGE->connect();

$send_message = '<em>'. addslashes($language['livehelp_system_message']) . ':<br>' . addslashes($language['ignore_user_message']) . '</em>';

//send message to notify user they are being ignored or declined
$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message) VALUES('','$from_login_id','$to_login_id',NOW(),'$send_message')";
$SQLMESSAGE->insertquery($query);


//update active of user to -2 to remove from users panel
$query_update_status = "UPDATE " . $table_prefix . "sessions SET active = '-2' WHERE login_id = '$to_login_id'";
$SQLMESSAGE->miscquery($query_update_status);

$SQLMESSAGE->disconnect();

header('Location: users.php?SID');
?>