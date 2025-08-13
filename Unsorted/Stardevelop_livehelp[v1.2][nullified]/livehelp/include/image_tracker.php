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
if (!isset($_SERVER['DOCUMENT_ROOT'])){ $_SERVER['DOCUMENT_ROOT'] = ""; }
$include_path = ($_SERVER['DOCUMENT_ROOT'] == "") ? str_replace($_SERVER["SCRIPT_NAME"], "", str_replace("\\\\", "/", $_SERVER["PATH_TRANSLATED"])) : $_SERVER['DOCUMENT_ROOT'];
include($include_path . '/livehelp/include/config_database.php');
include($include_path . '/livehelp/include/class.mysql.php');
include($include_path . '/livehelp/include/config.php');

$current_title = $_GET['TITLE'];

ignore_user_abort(true);

session_start();
$current_session = session_id();
session_write_close();

if (isset($_COOKIE['PHPSESSID'])) {

	$SQL = new MySQL;
	$SQL->connect();

	//update last refresh so user is online
	$query_update_refresh = "UPDATE " . $table_prefix . "requests SET last_refresh=NOW(), current_page_title = '$current_title' WHERE session_id = '$current_session'";
	$SQL->miscquery($query_update_refresh);

	$SQL->disconnect();
}

header('Content-type: image/gif');
readfile($include_path . '/livehelp/include/image_tracker.gif');
?>