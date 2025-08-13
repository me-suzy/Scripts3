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

ignore_user_abort(true);

session_start();
session_write_close();

$username = $_GET['USER'];
$login_id = $_GET['LOGIN_ID'];
$support_login_id = $_GET['SLOGIN_ID'];

$SQLSTATUS = new mySQL; 
$SQLSTATUS->connect();

//check if already assigned to operator
$query_select_active = "SELECT active FROM " . $table_prefix . "sessions WHERE login_id = '$login_id'";
$row_select_active = $SQLSTATUS->selectquery($query_select_active);
if (is_array($row_select_active)) {
	if ($row_select_active[active] == '0') {

		//update active of user to the id of there supporter
		$query_update_status = "UPDATE " . $table_prefix . "sessions SET active = '$support_login_id' WHERE login_id = '$login_id'";
		$SQLSTATUS->miscquery($query_update_status);

		$query_update_messages = "UPDATE " . $table_prefix . "messages SET to_login_id = '$support_login_id' WHERE from_login_id = '$login_id'";
		$SQLSTATUS->miscquery($query_update_messages);

	}
}

$SQLSTATUS->disconnect();

header('Location: users.php?' . SID);
?>