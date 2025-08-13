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
include('./include/config_database.php');
include('./include/class.mysql.php');
include('./include/config.php');

ignore_user_abort(true);

if (!isset($_GET['TO_LOGIN_ID'])){ $_GET['TO_LOGIN_ID'] = ""; }

$SQLMESSAGE = new mySQL; 
$SQLMESSAGE->connect();

$from_login_id = $_GET['FROM_LOGIN_ID'];
$to_login_id = $_GET['TO_LOGIN_ID'];
$message = $_GET['MESSAGE'];

// Check if the message contains any content else return headers
if ($message == '') {
	header('HTTP/1.0 204 No Content');
	//Various Windows clients not supporting the HTTP protocol.
	//header('Status: 204 No Content');
	exit();
}

//Checks if the FROM_LOGIN_ID is an admin user else remove JavaScript tags
$query_select_users_online = "SELECT s.login_id FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND u.last_login_id = '$from_login_id'";
$rows_users_online = $SQLMESSAGE->selectall($query_select_users_online);
if(!is_array($rows_users_online)) {
	$message = str_replace("<", "&lt;", $message);
	$message = str_replace(">", "&gt;", $message); 
}

$message = nl2br(preg_replace("/(\r\n¦\n¦\r)/", "\n", $message));

if (!get_magic_quotes_gpc()) {
	$send_message = addslashes($message);
}
else {
	$send_message = $message;
}

//get current supporter to send msgs to the supporters login id
if ($to_login_id == "") {
	$query_select_to_login_id = "SELECT active FROM " . $table_prefix . "sessions WHERE login_id = '$from_login_id'";
	$row_to_login_id = $SQLMESSAGE->selectquery($query_select_to_login_id);
	if (is_array($row_to_login_id)) {
		$to_login_id = $row_to_login_id["active"];
	}
	else {
		$to_login_id = 0;
	}
}
	
//send messages from POSTed data
$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message) VALUES('','$from_login_id','$to_login_id',NOW(),'$send_message')";
$SQLMESSAGE->insertquery($query);

$SQLMESSAGE->disconnect();

header('HTTP/1.0 204 No Content');

//Various Windows clients not supporting the HTTP protocol.
//header('Status: 204 No Content');
?>