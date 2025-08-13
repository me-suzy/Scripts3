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

session_start();
$login_id = $_SESSION['LOGIN_ID'];
$_SESSION = array();
session_destroy();

$SQLCONNECT = new mySQL; 
$SQLCONNECT->connect();

//update session table active field to -1 disconnected
$query_update_user_login = "UPDATE " . $table_prefix . "sessions SET active = '-1' WHERE login_id = '$login_id'";
$SQLCONNECT->miscquery($query_update_user_login);

$SQLCONNECT->disconnect();

header('Location: index.php?' . SID);
?>