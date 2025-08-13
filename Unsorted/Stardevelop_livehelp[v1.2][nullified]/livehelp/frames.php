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

$SQLCONNECT = new mySQL; 
$SQLCONNECT->connect();

if (!isset($_POST['USER_NAME'])){ $_POST['USER_NAME'] = ""; }
if (!isset($_POST['EMAIL'])){ $_POST['EMAIL'] = ""; }
if (!isset($_POST['DEPARTMENT'])){ $_POST['DEPARTMENT'] = ""; }

$username = $_POST['USER_NAME'];
$email = $_POST['EMAIL'];
$department = $_POST['DEPARTMENT'];
$referer = $_GET['REFERER'];
$server = $_GET['SERVER'];
$ip_address = $_SERVER['REMOTE_ADDR'];

// Get the applicable hostname
$current_host = $server;
for ($i = 0; $i < 2; $i++) {
	$substr_pos = strpos($current_host, '/');
	if ($substr_pos === false) {
		$current_host = '';
		break;
	}
	if ($i <= 2) {
		$current_host = substr($current_host, $substr_pos + 1);
	}

}

if (substr($current_host, 0, 4) == 'www.') { $current_host = substr($current_host, 4); }

if ($username == "") { $username = 'guest'; }

$query_select_admin_online = "SELECT UNIX_TIMESTAMP(s.datetime) AS datetime FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '0'";

if ($departments == 'true') {
	$query_select_admin_online .= " AND u.department = '$department'";
}

$query_select_admin_online .= " ORDER BY datetime DESC LIMIT 1";


$row_admin_online = $SQLCONNECT->selectquery($query_select_admin_online);

if(is_array($row_admin_online)) {
	$datetime = $row_admin_online['datetime'];
}
else {
	header('Location: index_offline.php');
}

$query_select_count = "SELECT count(session_id) FROM " . $table_prefix . "sessions WHERE username LIKE '$username%' AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_refresh)) < $datetime";
$row_count = $SQLCONNECT->selectquery($query_select_count);
if(is_array($row_count)) {

if ($row_count['count(session_id)'] > 0) {
	$query_select = "SELECT username FROM " . $table_prefix . "sessions WHERE username LIKE '$username%' AND UNIX_TIMESTAMP(datetime) > $datetime ORDER BY login_id DESC LIMIT 1";
	$row = $SQLCONNECT->selectquery($query_select);
	if (is_array($row)) {
		$length = strlen($username);
		$num_users = substr($row['username'], $length);
		$username_id = (int)$num_users + 1;
		$username = "$username" . "$username_id";
	}
}
}

if ($email == "") { $email = $username . '@' . $current_host; }

session_start();

$current_session = session_id();

$_SESSION['USERNAME'] = $username;
$_SESSION['EMAIL'] = $email;
$_SESSION['IP_ADDRESS'] = $ip_address;
$_SESSION['FIRST_LOAD'] = "true";
$_SESSION['REFERER_URL'] = $referer;
$_SESSION['CURRENT_HOST'] = $current_host;

//add session to database
$query_sessions = "INSERT INTO " . $table_prefix . "sessions (login_id,session_id,username,datetime,email,ip_address,server,department,rating,active,last_refresh) VALUES ('','$current_session','$username',NOW(),'$email','$ip_address','$server','$department',-1,'',NOW())";
$result = $SQLCONNECT->insertquery($query_sessions);


//find login id for relative session and set as session variable
$query_select = "SELECT login_id FROM " . $table_prefix . "sessions WHERE session_id = '$current_session' AND username='$username' ORDER BY datetime DESC LIMIT 1";
$rows = $SQLCONNECT->selectall($query_select);
if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
		$_SESSION['LOGIN_ID'] = $row['login_id'];
		}
	}
}

$SQLCONNECT->disconnect();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<frameset rows="115,2,26,*,100,45" frameborder="NO" border="0" framespacing="0">
  <frame src="header.php?<?php echo(SID); ?>" name="headerFrame" scrolling="NO">
  <frame src="blank.php?<?php echo(SID); ?>" name="statisticsFrame" scrolling="NO">
  <frame src="displayer_menu.php?<?php echo(SID); ?>" name="displayMenuFrame" scrolling="NO">
  <frame src="displayer_js_frame.php?<?php echo(SID); ?>" name="displayFrame">
  <frame src="messenger.php?<?php echo(SID); ?>" name="messengerFrame" scrolling="NO">
  <frame src="connection.php?<?php echo(SID); ?>" name="connectionFrame" scrolling="NO" noresize>
</frameset>
<noframes>
<body>
</body>
</noframes>
</html>