<?php


error_reporting(7);

if (!defined('directory')) {
	define('directory', '');
}

include(directory . "var.php");

if (empty($action)) {
	if ($signup != '1') {
		error("Signup has been disabled by the Administrator.");
	}
	head("Signup!");
	include(directory . "tpl/signup.tpl");
	footer();
}

if ($action == 'terms') {
	include(directory . "tpl/signup_terms.tpl");
}

if ($action == 'activate') {
	$sql = "SELECT * FROM $pop_tbl WHERE account='$account'";
    $result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num <= 0) {
		error("The account was not found!");
	}
    $row = $s24_sql->fetch_array($result);
	if ($row[status] == 'Queue') {
		error("Your Account has not been approved be an admin!");
	}
	if ($row[active] == '1') {
		error("Account has already been activated!");
	}
	if ($row[time] != $time) {
		error("Timecheck failed!<br>Please check your email again for the correct activation link.<br>If you still have problems activating your account you might want to <a href=\"mailto:$adminemail\">contact us</a>.");
	} else {
		$sql = "UPDATE $pop_tbl SET active='1' WHERE account='$account'";
		$result = $s24_sql->query($sql);
		head("Account activated!");
		include(directory . "tpl/member_activated.tpl");
		footer();
	}
}

if ($action == 'signup') {
	$sql = "SELECT account FROM $pop_tbl WHERE account='$account'";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num > 0 ) {
		error("Account already exists");
	}

	if (empty($account)) {
		error("&quot;Account&quot; is a required field");
	}
	if (empty($password)) {
		error("&quot;Password&quot; is a required field");
	}
	if (empty($name)) {
		error("&quot;Name&quot; is a required field");
	}
	if (empty($email)) {
		error("&quot;Email&quot; is a required field");
	}
	if (empty($title)) {
		error("&quot;Title&quot; is a required field");
	}
	if (empty($url)) {
		error("&quot;URL&quot; is a required field");
	}
	if (!ereg('http://', $url) || $url == 'http://') {
		error("URL not allowed");
	}
	if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email) || ereg("'", $email)) {
		error("Email not allowed");
	}
	$ip = getenv("REMOTE_ADDR");
	$sql = "SELECT * FROM $ban_tbl WHERE type='email'";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		if (eregi("$row[content]", $email)) {
			error("Email not allowed");
		}
	}
	$sql = "SELECT * FROM $ban_tbl WHERE type='word'";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		if (eregi("$row[content]", $account)) {
			error("Account not allowed");
		}
	}
	$sql = "SELECT * FROM $ban_tbl WHERE type='domain'";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		if (eregi("$row[content]", $url)) {
			error("URL not allowed");
		}
	}
	$sql = "SELECT * FROM $ban_tbl WHERE type='ip'";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		if (eregi("$row[content]", $ip)) {
			error("IP banned");
		}
	}
	if ($checkdup == 1) {
		$sql = "SELECT * FROM $pop_tbl WHERE email='$email'";
		$result = $s24_sql->query($sql);
		$num = $s24_sql->num_rows($result);
		if ($num > 0 ) {
			error("Email already exists");
		}
		$sql = "SELECT * FROM $pop_tbl WHERE url='$url'";
		$result = $s24_sql->query($sql);
		$num = $s24_sql->num_rows($result);
		if ($num > 0 ) {
			error("URL already exists");
		}
	}
	if ($terms != "on") {
		error("By submitting this application, you must agree to the <a href=\"javascript:winopen('signup.php?action=terms');\">Terms and Conditions</a> of membership.");
	}
	$time = time();
	if ($moderate == '1') {
		$status = "Queue";
	} else {
		$status = "Approved";
		$lastuse = $time;
		$apptime = $time;
		$moderator = "--";
	}
	$name = addslashes($name);
	if ($verifyemail == '1') {
		$active = "0";
	} else {
		$active = "1";
	}
	$acturl = "$scripturl" . "signup.php?action=activate&account=$account&time=$time";
	$sql = "INSERT INTO $pop_tbl (account, name, password, title, url, email, type, active, status, time, apptime, moderator, lastuse, credits, ip) VALUES ('$account', '$name', '$password', '$title', '$url', '$email', '$type', '$active', '$status', '$time', '$apptime', '$moderator', '$lastuse', '$credits', '$ip');";
	$result = $s24_sql->query($sql);
	if ($moderate == '1') {
   	    $emailmessage = "The admin has chosen to review new signups first.<br>You cannot use your account until he has done so.<br>You will get an mail when your account is approved!";
	}
	if ($verifyemail != '0' && $moderate == '0') {
		$sql = "SELECT * FROM $emails_tbl WHERE name='signup'";
		$result = $s24_sql->query($sql);
		$row = $s24_sql->fetch_array($result);
		$mailsubject = $row[subject];
		$mailmessage = mailreplace($row[message]);
		mail($email,$mailsubject,$mailmessage,$additional);
		$emailmessage = "You will immediately get an email with instructions how to activate your account!";
	}
	if ($notify == '1') {
 		$sql = "SELECT * FROM $emails_tbl WHERE name='notify'";
		$result = $s24_sql->query($sql);
		$row = $s24_sql->fetch_array($result);
		$mailsubject = $row[subject];
		$mailmessage = mailreplace($row[message]);
 		mail($adminemail,$mailsubject,$mailmessage,$additional);
	}
	if ($active == '1' && $status != 'Queue') {
		$htmlcode = "<p>Add the following CODE to your pages:<br><br>

1.) Insert this Code in your &lt;HEAD&gt;-Section:<br>
<pre>&lt;SCRIPT LANGUAGE=&quot;JavaScript&quot; SRC=&quot;".$scripturl."popup.php?account=".$account."&quot;&gt;&lt;/SCRIPT&gt;</pre><br><br>

2.) Insert this Code in your &lt;BODY&gt;-Tag;<br>
<pre>onUnload=popup()</pre><br><br>

Example:<br>
<pre>
  &lt;HTML&gt;
  &lt;HEAD&gt;
    &lt;SCRIPT LANGUAGE=&quot;JavaScript&quot; SRC=&quot;".$scripturl."popup.php?account=".$account."&quot;&gt;&lt;/SCRIPT&gt;
  &lt;/HEAD&gt;

  &lt;BODY onUnload=popup()&gt;
  ...
  &lt;/BODY&gt;
  &lt;/HTML&gt;
</pre>

</p>
<p>You can log into the members section <a href=\"members.php\">here</a></p>
";
	}
	head("User $account added to database!");
	include(directory . "tpl/signup_useradded.tpl");
	exit;
}

?>    
    
