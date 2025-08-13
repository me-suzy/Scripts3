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

$include_path = ($_SERVER['DOCUMENT_ROOT'] == "") ? str_replace($_SERVER["SCRIPT_NAME"], "", str_replace("\\\\", "/", $_SERVER["PATH_TRANSLATED"])) : $_SERVER['DOCUMENT_ROOT'];

ignore_user_abort(true);

session_start();
$login_id = $_SESSION['LOGIN_ID'];
session_write_close();

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

//update last refresh so user is online
$query_update_refresh = "UPDATE " . $table_prefix . "sessions SET last_refresh=NOW() WHERE login_id = '$login_id'";
$SQLDISPLAY->miscquery($query_update_refresh);

$SQLDISPLAY->disconnect();

//output 1x1 pixel to the admin users_header.php javascript page to stay online
header('Content-type: image/gif');
readfile($include_path . '/livehelp/include/image_tracker.gif');
?>