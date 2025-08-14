<?php


error_reporting(7);

if (!defined('directory')) {
	define('directory', '');
}

include(directory . "var.php");

if (empty($action)) {
	$action = "show_login";
}

if ($action == 'lostpass') {
	head("Lost your Password?");
	include(directory . "tpl/member_password.tpl");
	footer();
}

if ($action == 'show_login') {
	head("Members Login");
	include(directory . "tpl/member_login.tpl");
	footer();
}

if ($action == 'delete') {
	$sql = "SELECT * FROM $pop_tbl WHERE id='$id'";
	$result = $s24_sql->query($sql);
	$row = $s24_sql->fetch_array($result);
	$id = $row[id];
	$account = $row[account];
	$password = $row[password];
	head("Do you really want to delete your Account?");
	include(directory . "tpl/member_delete.tpl");
	footer();
}

if ($action == 'submitdelete') {
	if ($cancel == 'cancel') {
		$action = "login";
	} else {
		$sql = "SELECT * FROM $pop_tbl WHERE id='$id'";
		$result = $s24_sql->query($sql);
		$row = $s24_sql->fetch_array($result);
		if ($passwd != $row[password]) {
			error("The Password did not match with the one in our Database!");
		}
		$sql = "DELETE FROM $pop_tbl WHERE id='$id'";
		$result = $s24_sql->query($sql);
		head("Account deleted!");
		include(directory . "tpl/member_deleted.tpl");
		footer();
	}
}

if ($action == 'submitpass') {
	$sql = "SELECT * FROM $pop_tbl WHERE account='$account'";
    $result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num < 1) {
		error("The Account was not found!");
	}
    $row = $s24_sql->fetch_array($result);
    $account = $row[account];
	$email = $row[email];
    $password = $row[password];
	$sql = "SELECT * FROM $emails_tbl WHERE name='lostpw'";
	$result = $s24_sql->query($sql);
	$row = $s24_sql->fetch_array($result);
	$mailsubject = $row[subject];
	$mailmessage = mailreplace($row[message]);
	mail($email,$mailsubject,$mailmessage,$additional);
	head("Your password has been emailed to your email address!");
	include(directory . "tpl/member_password_sent.tpl");
	footer();
} 

if ($action == 'update') {
	if (empty($name) || empty($title) || empty($password) || empty($email) || empty($url)) {
		error("A required field was left blank");
	}
	if (!ereg('http://', $url) || $url == 'http://') {
		error("URL not allowed!");
	}
	if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email) || ereg("'", $email)) {
		error("Your emailaddress is invalid!");
	}
	$ip = getenv("REMOTE_ADDR");
	$sql = "SELECT * FROM $ban_tbl WHERE type='email'";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		if (eregi("$row[content]", $email)) {
			error("Your emailaddress is invalid!");
		}
	}
	$sql = "SELECT * FROM $ban_tbl WHERE type='domain'";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		if (eregi("$row[content]", $url)) {
			error("URL not allowed!");
		}
	}
	$sql = "SELECT * FROM $ban_tbl WHERE type='ip'";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		if (eregi("$row[content]", $ip)) {
			error("Your IP has been banned!");
		}
	}
	$name = addslashes($name);
	$sql =  "UPDATE $pop_tbl SET name='$name', password='$password', email='$email', title='$title', url='$url' WHERE id='$id'";
	$result = $s24_sql->query($sql);
	$message = "User data changed.";
	$action = "login";
}

if ($action == 'html') {
	$sql = "SELECT * FROM $pop_tbl WHERE account='$account'";
    $result = $s24_sql->query($sql);
    $num = $s24_sql->num_rows($result);
    if ($num < 1) {
		error("User does not exist!");
	}
	$row = $s24_sql->fetch_array($result);
	$id = $row[id];
	$account = $row[account];
	$active = $row[active];
	if ($active == '0') {
		error("Your Account has never been activated! Check your mail to find out how to activate it!");
	}
	if ($row[password] != $password) {
		error("The Password did not match with the one in our Database!");
	}
	head("Welcome");
	include(directory . "tpl/member_html.tpl");
	footer();
}

if ($action == 'login') {
	$sql = "SELECT * FROM $pop_tbl WHERE account='$account'";
    $result = $s24_sql->query($sql);
    $num = $s24_sql->num_rows($result);
    if ($num < 1) {
		error("User does not exist!");
	}
	$row = $s24_sql->fetch_array($result);
	$id = $row[id];
	$account = $row[account];
    $name = $row[name];
	$in = $row[ins];
	$out = $row[out];
	$title = $row[title];
	if ($ratio == '10') {
		$r = "1.0";
	} else {
		$r = "0.".$ratio;
	}
	$x = strtok((($row[ins]*$r)-$row[out]),".");
	if ($x < 0) { $x = 0; }
	$sitecredits = $row[credits]+$x;
    $email = $row[email];
	$url = $row[url];
	$active = $row[active];
	if ($active == '0') {
		error("Your Account has never been activated! Check your mail to find out how to activate it!");
	}
	if ($row[password] != $password) {
		error("The Password did not match with the one in our Database!");
	}
	head("Welcome");
	include(directory . "tpl/member_loggedin.tpl");
}

?>