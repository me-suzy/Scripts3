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
session_start();
$login_id = $_SESSION['LOGIN_ID'];
$username = $_SESSION['USERNAME'];
session_write_close();

if ($login_id == "" || $username == "") {
header('Location: /livehelp/include/auth_error.php?' . SID);
exit;
}

$SQLAUTH = new mySQL; 
$SQLAUTH->connect();

//check if current authorised login_id is equal to the users last login id
$query_select = "SELECT s.login_id FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE u.last_login_id = " . $login_id . " AND u.username = '" . $username . "' AND status = '0'";
$rows = $SQLAUTH->selectall($query_select);
if (!is_array($rows)) {
header('Location: /livehelp/include/auth_error.php?' . SID);
exit;
}

$SQLAUTH->disconnect();
?>