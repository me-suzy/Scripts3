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

if (!isset($_GET['LOGIN_ID'])){ $_GET['LOGIN_ID'] = ""; }

$login_id = $_GET['LOGIN_ID'];

$SQL = new mySQL; 
$SQL->connect();

//update active of admin session to enter offline hidden mode ie. -1
$query_update_status = "UPDATE " . $table_prefix . "sessions SET active = '-1' WHERE login_id = '$login_id'";
$SQL->miscquery($query_update_status);

$SQL->disconnect();

header('Location: users_header.php?SID&STATUS=offline');
?>