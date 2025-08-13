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

if (!isset($_POST['REMEMBER_LOGIN'])){ $_POST['REMEMBER_LOGIN'] = ""; }

$username = $_POST['USER_NAME'];
$password = $_POST['PASSWORD'];
$server = $_POST['SERVER'];
$remember_login = $_POST['REMEMBER_LOGIN'];

session_start();
$current_session = session_id();
$ip_address = $_SERVER['REMOTE_ADDR'];

$_SESSION['USERNAME'] = $username;
$_SESSION['IP_ADDRESS'] = $ip_address;

$SQLCONNECT = new mySQL; 
$SQLCONNECT->connect();

//grab username,password and email from database and authorise
$query_select = "SELECT username,password,email FROM " . $table_prefix . "users WHERE username = '$username' AND password='$password'";
$row = $SQLCONNECT->selectquery($query_select);
if (!is_array($row)) {
header("Location: ./index.php?STATUS=error&" . SID);
exit;
}

$email = $row['email'];

//set cookie to remember the username and password
if ($remember_login == "true") {
$domain = '.' . $_SERVER['HTTP_HOST'];
setcookie('cookie_login[username]', $username, time() + 7776000, '/', $domain, 0);
setcookie('cookie_login[password]', $password, time() + 7776000, '/', $domain, 0);
}

//add inactive session to database
$query_sessions = "INSERT INTO " . $table_prefix . "sessions (login_id,session_id,username,datetime,email,ip_address,server,department,rating,active) VALUES ('','$current_session','$username',NOW(),'$email','$ip_address','$server','',-1,'')";
$result = $SQLCONNECT->insertquery($query_sessions);

//find login id for relative session and set as session variable
$query_select = "SELECT login_id FROM " . $table_prefix . "sessions WHERE session_id = '$current_session' AND username='$username' ORDER BY datetime DESC LIMIT 1";
$row = $SQLCONNECT->selectquery($query_select);
if (is_array($row)) {
	$login_id = $row['login_id'];
	$_SESSION["LOGIN_ID"] = $login_id;
	
	//update admin user table with new last_login_id
	$query_update_user_login = "UPDATE " . $table_prefix . "users SET last_login_id = '$login_id' WHERE username = '$username'";
	$SQLCONNECT->miscquery($query_update_user_login);
}
session_write_close();

$SQLCONNECT->disconnect();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>Admin <?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<frameset rows="20,*" frameborder="NO" border="0" framespacing="0">
  <frame src="header.php?<?php echo(SID); ?>" name="headerFrame" scrolling="NO">
<frameset cols="300,*,90" frameborder="NO" border="0" framespacing="0">
  <frameset rows="180,60,*" frameborder="NO" border="0" framespacing="0">
    <frame src="users_header.php?<?php echo(SID); ?>" name="usersHeaderFrame" scrolling="NO">
    <frame src="sound_controls.php?<?php echo(SID); ?>" name="soundControlsFrame" scrolling="NO">
    <frame src="users.php?<?php echo(SID); ?>" name="usersFrame">
  </frameset>
<frameset rows="*,25,190,40" frameborder="NO" border="0" framespacing="0">
  <frame src="../blank.php?<?php echo(SID); ?>" name="displayFrame">
  <frame src="toolbar.php?<?php echo(SID); ?>" name="toolbarFrame" scrolling="NO">
  <frame src="messenger.php?<?php echo(SID); ?>" name="messengerFrame" scrolling="NO">
  <frame src="connection.php?<?php echo(SID); ?>" name="connectionFrame" scrolling="NO" noresize>
</frameset>
  <frame src="control_panel.php?<?php echo(SID); ?>" name="menuFrame">
</frameset>
</frameset>
<noframes>
<body>
</body>
</noframes>
</html>