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

$session_id = $_GET['SESSION_ID'];
$support_login_id = $_GET['SLOGIN_ID'];

$SQL = new mySQL; 
$SQL->connect();

//update active of user to the id of there supporter
$query_update_status = "UPDATE " . $table_prefix . "requests SET request_flag = '$support_login_id' WHERE session_id = '$session_id'";
$SQL->miscquery($query_update_status);

$SQL->disconnect();

header('Location: visitors_index.php?' . SID);
?>