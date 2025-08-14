<?php

if (file_exists("install.php")) {
	die("Security Error: Please delete install.php before you continue. Or run it first if you did not already set it up.");
}
if (file_exists("upgrade1.php")) {
	die("Security Error: Please delete upgrade1.php before you continue. Or run it first if you did not already update the database.");
}

error_reporting(7);

if (!defined('directory')) {
	define('directory', '../');
}

include(directory . "var.php");

$link = $s24_sql->pconnect();
$s24_sql->select_db();

function auth($msg) {
	Header("WWW-Authenticate: Basic realm=\"ExitPopup Administration\", stale=FALSE");
	Header("HTTP/1.0 401 Unauthorized");
	error($msg);
}

if (isset($PHP_AUTH_USER)) {
	$sql = "SELECT * FROM $moderator_tbl WHERE username='$PHP_AUTH_USER'";
	$result = $s24_sql->query($sql);
	$row = $s24_sql->fetch_array($result);
	if ($row[super] == '1') {
		$modprocess = "1";
		$modsetup = "1";
		$modhtml = "1";
		$modblacklist = "1";
		$modmail = "1";
		$modmoderator = "1";
	} else {
		$modprocess = $row[process];
		$modsetup = $row[setup];
		$modhtml = $row[html];
		$modblacklist = $row[blacklist];
		$modmail = $row[mail];
		$modmoderator = $row[moderator];
	}
	$modpass = $row[password];
	$modlogin = $row[username];
	if (($modlogin != $PHP_AUTH_USER) || empty($PHP_AUTH_USER)) {
		auth("User does not exist!");
		exit;
	}
	if (($modpass != $PHP_AUTH_PW) || empty($PHP_AUTH_PW)) {
		auth("Wrong password!");
		exit;
	}
} else {
	auth("User does not exist!");
}

if (!isset($action)) {
	$action = "show_main";
}

if ($action == 'logout') {
	auth(1);
}

if ($action == 'show_inactive') {
	$l = time() - ($lastuse*86400);
	$condition = "lastuse < '$l'";
	$sql = "SELECT * FROM $emails_tbl WHERE type='rejection'";
	$result = $s24_sql->query($sql);
	$reject = "<select name=\"rejection[]\">";
	$reject .= "<option value=\"nomail\">do not send an email</option>";
	$reject .= "<option value=\"delete\">standard delete notice</option>";
	while($row = $s24_sql->fetch_array($result)) {
		$reject .= "<option value=\"$row[name]\">$row[name]</option>";
	}
	$reject .= "</select>";
	$sql = "SELECT * FROM $pop_tbl WHERE $condition";
	$result = $s24_sql->query($sql);
	$tot = $s24_sql->num_rows($result);
	$headline = "Displaying $tot Inactive Sites";
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/show_head.tpl");
	if ($tot > 0) {
		while($row = $s24_sql->fetch_array($result)) {
			$id = $row[id];
			$name = $row[name];
			$email = $row[email];
			$url = $row[url];
			$title = $row[title];
			$account = $row[account];
			$out = $row[out];
			$in = $row[ins];
			if ($ratio == '10') {
				$r = "1.0";
			} else {
				$r = "0.".$ratio;
			}
			$x = strtok((($row[ins]*$r)-$row[out]),".");
			if ($x < 0) { $x = 0; }
			$sitecredits = "$row[credits]"." [".($row[credits]+$x)."]";
			$ip = $row[ip];
			$apptime = xtime($row[apptime]);
			$time = xtime($row[time]);
			$moderator = $row[moderator];
			include(directory . "admin/tpl/show_template.tpl");
		}
	}
	if ($tot == 0) {
		echo "<tr><td colspan=\"3\" align=\"center\">No Accounts found</td></tr>"; 
	}
	include(directory . "admin/tpl/show_foot.tpl");
	adminfooter();
}

if ($action == 'do_backup') {
	if ($modsetup != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	function dump_structure ($name) {
		global $file, $filename;
		$sql = "EXPLAIN $name";
		$result  = mysql_query($sql);
		$num  = mysql_num_rows($result);
		if ($num <= 0) {
			echo "Error";
			exit;
		}
		$out = "DROP TABLE IF EXISTS $name;\r\n";
		$out .= "CREATE TABLE $name (";
		$primary = "";
		while ($row = mysql_fetch_array($result)) {
			if ($row["Key"] == "PRI") {
				$primary = $row["Field"];
			}
			$out .= "  $row[Field] $row[Type]";
			if ($row["Default"] != "") {
			 	$out .= " DEFAULT '$row[Default]'";
			}
			if ($row["Null"] != "YES") {
				$out .= " NOT NULL";
			}
			if ($row["Extra"] != "") {
				$out .= " $row[Extra]";
			}
			$out .= ",";
		}
		$out = ereg_replace(",$", "", $out);
		if ($primary != "") {
			$out .= ",";
			$out .= "  PRIMARY KEY ($primary)";
		}
		$out .= ");";
		$out .= "\r\n";
		$out .= "\r\n";
		fwrite($file,$out) or error("Error writing to file &quot;$filename&quot;");
	}
	function dump_data($name) {
		global $file, $filename;
		$zeug = "SELECT * FROM $name";
		$result  = mysql_query($zeug);
		while ($row = mysql_fetch_array($result)) {
			$out = "INSERT INTO $name VALUES (";
			$fields = mysql_num_fields ($result);
			for ($i = 0; $i < $fields; $i++) {
				$out .= " '$row[$i]',";
			}
			$out = ereg_replace(",$", "", $out);
			$out = str_replace("\n", "\\r\\n", $out);
			$out = str_replace("\r", "", $out);
			$out = trim($out);
			$out .= " );";
			$out .= "\r\n";
			$output .= $out;
		}
		$output .= "\r\n";
		$output .= "\r\n";
		fwrite($file,$output) or error("Error writing to file &quot;$filename&quot;");
	}
	$tables = array (
$ban_tbl,
$emails_tbl,
$moderator_tbl,
$options_tbl,
$pop_tbl,
$site_tbl
	);
	$file = @fopen(directory . "admin/backup/".$filename,"w") or error("Could not open/create file &quot;$filename&quot;<br>Please check if the backup folder is chmodded 777!");
	fwrite($file,"# ExitPopup $version MySQL Dump\r\n\r\n") or error("Error writing to file &quot;$filename&quot;");
	foreach($tables as $table) {
		dump_structure($table);
		dump_data($table);
	}
	fclose($file);
	$message = "Database backed up to $filename";
	$action = "show_backup";
}

if ($action == 'do_restore') {
	if ($modsetup != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	function restoredb() {
	    global $filename, $s24_sql;
		if (empty($filename)) {
			error("No filename selected!");
		}
    	$lines = @file(directory . "admin/backup/" . $filename);
		if (!eregi("# ExitPopup", $lines[0])) {
			error("The SQL file you are trying to restore seems to be corrupted.");
		}
		echo "<p>";
		foreach ($lines as $line) {
			$line = trim($line);
			if (!empty($line) && !ereg("^#", $line)) {
		    	$result = $s24_sql->query($line);
				echo "Inserting line . . . <b>OK!</b><br>";
				flush();
			}
		}
		echo "</p>";
    	echo "<p><b>Tables restored</b></p>";
		echo "<p><b><a href=\"admin.php\">Back To Main Page</a></b></p>";
	}
	adminhead("Restore Database");
	restoredb();
	adminfooter();
}

if ($action == 'show_backup') {
	if ($modsetup != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$restore = "<select name=\"filename\">";
    $restoredir = opendir(directory . 'admin/backup');
    while ($file = readdir($restoredir)) {
		if (eregi(".sql",$file)) {
			$restore .= "<option value=\"$file\">$file</option>\n";
		}
	}
    closedir($restoredir);
	$restore .= "</select>";
	adminhead("Restore/Backup Database");
	$filename = "ExitPopup".date('ymd').".sql";
	include(directory . "admin/tpl/backup.tpl");
	adminfooter();
}

if ($action == 'tpl_edit') {
	if ($modsetup != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$content = readtpl(directory . "tpl/$tpl");
	$content = str_replace('&','&amp;',$content);
	$content = str_replace('<','&lt;',$content);
	$content = str_replace('>','&gt;',$content);
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/tpl_edit.tpl");
	adminfooter();
}

if ($action == 'tpl_update') {
	$contentx = stripslashes($contentx);
	$file = @fopen(directory . "tpl/$tpl","w+")
		or error("Unable to write file (" . directory . "tpl/$tpl)<br>Make sure that the folder &quot;tpl&quot; is chmodded 777, and all files in the folder are chmodded 666");
	fwrite($file,$contentx);
	fclose($file);
	$message = "&quot;$tpl&quot; updated";
	$action = "show_main";
}

if ($action == 'list_emails') {
	adminhead("ExitPopup Administration");
	$sql = "SELECT * FROM $pop_tbl";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		if (!empty($mailtox)) {
			if (!eregi($row[email], $mailtox)) {
				$mailtox .= "$row[email] ";
				$account_idx .= "$row[id] ";
			}
		} else {
			$mailtox .= "$row[email] ";
			$account_idx .= "$row[id] ";
		}
	}
	$mailtox = str_replace(" ","\n",$mailtox);
	echo "<table align=\"center\"><tr><td>";
	echo "<pre>$mailtox</pre>";
	echo "<b><a href=\"admin.php\">Back To Main Page</a></b>";
	echo "</td></tr></table>";
	adminfooter();
}

if ($action == 'list_members') {
	adminhead("ExitPopup Administration");
	echo "<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>";
	$sql = "SELECT * FROM $pop_tbl WHERE status='Approved'";
	$result = $s24_sql->query($sql);
	echo "<br>Current Account Database:<br>";
	$tot = $s24_sql->num_rows($result);
	if ($tot < 1) {
		echo "No Such Entry<br>";
	} else {
		while($row = $s24_sql->fetch_array($result)) {
			$id = $row[id];
			$account = $row[account];
			echo "<a href=\"$PHP_SELF?action=show_account&id=$id\">Account: $account</a><br>";
		}
	}
	$sql = "SELECT * FROM $pop_tbl WHERE status='Queue'";
	$result = $s24_sql->query($sql);
	echo "<br>Currently Queued Accounts:<br>";
	$tot = $s24_sql->num_rows($result);
	if ($tot < 1) {
		echo "No Such Entry<br>";
	} else {
		while($row = $s24_sql->fetch_array($result)) {
			$id = $row[id];
			$account = $row[account];
			echo "<a href=\"$PHP_SELF?action=show_account&id=$id\">Account: $account</a><br>";
		}
	}
	echo "<p align=\"center\"><b><a href=\"$PHP_SELF\">Back To Main Page</a></b></p>";
	echo "</td></tr></table>";
	adminfooter();
}

#############
####setup####

if ($action == 'show_setup') {
	if ($modsetup != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$xratio = "<select name=\"sratio\">";
	$xratio .= "<option value=\"10\"";
	if ($ratio == '10') { $xratio .= " selected"; }
	$xratio .= ">10:10</option>";
	$xratio .= "<option value=\"9\"";
	if ($ratio == '9') { $xratio .= " selected"; }
	$xratio .= ">10:9</option>";
	$xratio .= "<option value=\"8\"";
	if ($ratio == '8') { $xratio .= " selected"; }
	$xratio .= ">10:8</option>";
	$xratio .= "<option value=\"7\"";
	if ($ratio == '7') { $xratio .= " selected"; }
	$xratio .= ">10:7</option>";
	$xratio .= "<option value=\"6\"";
	if ($ratio == '6') { $xratio .= " selected"; }
	$xratio .= ">10:6</option>";
	$xratio .= "<option value=\"5\"";
	if ($ratio == '5') { $xratio .= " selected"; }
	$xratio .= ">10:5</option>";
	$xratio .= "<option value=\"4\"";
	if ($ratio == '4') { $xratio .= " selected"; }
	$xratio .= ">10:4</option>";
	$xratio .= "<option value=\"3\"";
	if ($ratio == '3') { $xratio .= " selected"; }
	$xratio .= ">10:3</option>";
	$xratio .= "<option value=\"2\"";
	if ($ratio == '2') { $xratio .= " selected"; }
	$xratio .= ">10:2</option>";
	$xratio .= "<option value=\"1\"";
	if ($ratio == '1') { $xratio .= " selected"; }
	$xratio .= ">10:1</option>";
	$xratio .= "<option value=\"0\"";
	if ($ratio == '0') { $xratio .= " selected"; }
	$xratio .= ">10:0</option>";
	$xratio .= "</select>";

	adminhead("Setup ExitPopup");
	include(directory . "admin/tpl/setup.tpl");
	adminfooter();
	exit;
}

if ($action == 'setup_submit') {
	if ($modsetup != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	if (!is_numeric($stimeoffset)) {
		error("&quot;Time Zone Offset&quot; has to be numeric");
	}
	if (empty($ssitetitle)) {
		error("&quot;Site Title&quot; is a required field");
	}
	if (empty($sadminemail)) {
		error("&quot;Your Email Address&quot; is a required field");
	}
	$sql = "UPDATE $options_tbl SET adminemail='$sadminemail', sitetitle='$ssitetitle', dateformat='$sdateformat', timeformat='$stimeformat', timeoffset='$stimeoffset', checkdup='$scheckdup', verifyurl='$sverifyurl', verifyemail='$sverifyemail', notify='$snotify', scripturl='$sscripturl', ratio='$sratio', moderate='$smoderate', credits='$scredits', hours='$shours'";
	$result = $s24_sql->query($sql);
	$message = "setup updated!";
	$action = "show_main";
}

#####ban#####

if ($action == 'rem_ban') {
	if ($modblacklist != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$count = count ($id);
	for ($i=0;$i<$count;$i++) {
		$sql = "DELETE FROM $ban_tbl WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
	}
	$action = "show_ban";
}

if ($action == 'add_ban') {
	if ($modblacklist != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	if (empty($ban)) {
		error("A required field was left blank");
	}
	$sql = "SELECT id FROM $ban_tbl WHERE content='$ban'";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num > 0) {
		error("A blacklist entry &quot;$ban&quot; does already exist!");
	}
	$sql = "INSERT INTO $ban_tbl (type, content) VALUES ('$type', '$ban')";
	$result = $s24_sql->query($sql);
$action = "show_ban";
}

if ($action == 'show_ban') {
	if ($modblacklist != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$sql = "SELECT * FROM $ban_tbl WHERE type='email'";
	$result = $s24_sql->query($sql);
	while ($row = $s24_sql->fetch_array($result)) {
		$email .= "<input type=\"checkbox\" name=\"id[]\" value=\"$row[id]\" class=\"white\"> $row[content]\n";
	}
	$sql = "SELECT * FROM $ban_tbl WHERE type='domain'";
	$result = $s24_sql->query($sql);
	while ($row = $s24_sql->fetch_array($result)) {
		$domain .= "<input type=\"checkbox\" name=\"id[]\" value=\"$row[id]\" class=\"white\"> $row[content]\n";
	}
	$sql = "SELECT * FROM $ban_tbl WHERE type='word'";
	$result = $s24_sql->query($sql);
	while ($row = $s24_sql->fetch_array($result)) {
		$word .= "<input type=\"checkbox\" name=\"id[]\" value=\"$row[id]\" class=\"white\"> $row[content]\n";
	}
	unset($ip);
	$sql = "SELECT * FROM $ban_tbl WHERE type='ip'";
	$result = $s24_sql->query($sql);
	while ($row = $s24_sql->fetch_array($result)) {
		$ip .= "<input type=\"checkbox\" name=\"id[]\" value=\"$row[id]\" class=\"white\"> $row[content]\n";
	}
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/blacklist.tpl");
	adminfooter();
}

#####sysmail#####

if ($action == 'update_sysmail') {
	if ($modhtml != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$count = count ($id);
	for ($i=0;$i<$count;$i++) {
		if (empty($mailmessage[$i]) || empty($mailsubject[$i])) {
			error("A required field was left blank!");
		}
		$sql = "UPDATE $emails_tbl SET name='$name[$i]',subject='$mailsubject[$i]',message='$mailmessage[$i]' WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
	}
	$message = "Email(s) updated!";
	$action = "show_reject";
}

if ($action == 'edit_sysmail') {
	if ($modhtml != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/mail_edit_head.tpl");
	$count = count ($id);
	for ($i=0;$i<$count;$i++) {
		$sql ="SELECT * FROM $emails_tbl WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
		while($row = $s24_sql->fetch_array($result)) {
			$mailsubject = $row[subject];
			$mailname = $row[name];
			$mailmessage = $row[message];
			$mailid = $row[id];
			include(directory . "admin/tpl/mail_edit_template.tpl");
		}
	}
	include(directory . "admin/tpl/mail_edit_foot.tpl");
	adminfooter();
}

#####reject email#####

if ($action == 'add_reject') {
	if ($modhtml != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	if (empty($name) || empty($mailsubject) || empty($mailmessage)) {
		error("A required field was left blank");
	}
	$sql = "SELECT * FROM $emails_tbl WHERE name='$name'";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num > 0) {
		error("A email &quot;$name&quot; does already exist!");
	}
	$sql = "INSERT INTO $emails_tbl (name, subject, message, type) VALUES ('$name', '$mailsubject', '$mailmessage', 'rejection')";
	$result = $s24_sql->query($sql);
	$action = "show_reject";
}

if ($action == 'delete_reject') {
	if ($modhtml != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$count = count ($id);
	for ($i=0;$i<$count;$i++) {
		$sql = "SELECT * FROM $emails_tbl WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
		$row = $s24_sql->fetch_array($result);
		if ($row[type] != 'rejection') {
			error("Cannot delete system mails! You can only delete and add rejection mails!");
		}
		$sql = "DELETE FROM $emails_tbl WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
		$message .= "Email &quot;$id[$i]&quot; deleted!<br>";
	}
	$action = "show_reject";
}

if ($action == 'show_reject') {
	if ($modhtml != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/reject_head.tpl");
	$sql = "SELECT * FROM $emails_tbl WHERE type='system'";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		$mailmessage = $row[message];
		$id = $row[id];
		$name = $row[name];
		$mailsubject = $row[subject];
		include(directory . "admin/tpl/reject_template.tpl");
	}
	include(directory . "admin/tpl/reject_mid.tpl");
	$sql = "SELECT * FROM $emails_tbl WHERE type='rejection'";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		$mailmessage = $row[message];
		$id = $row[id];
		$name = $row[name];
		$mailsubject = $row[subject];
		include(directory . "admin/tpl/reject_template.tpl");
	}
	include(directory . "admin/tpl/reject_foot.tpl");
	adminfooter();
}

#####disable/enable signup#####

if ($action == 'enable_signup') {
	if ($modsetup != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$sql = "UPDATE $options_tbl SET signup='1'";
	$result = $s24_sql->query($sql);
	$message = "signup.php enabled";
	$action = "show_main";
}

if ($action == 'disable_signup') {
	if ($modsetup != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$sql = "UPDATE $options_tbl SET signup='0'";
	$result = $s24_sql->query($sql);
	$message = "signup.php disabled";
	$action = "show_main";
}

#####search#####

if ($action == "show_search") {
	$sql = "SELECT * FROM $emails_tbl WHERE type='rejection'";
	$result = $s24_sql->query($sql);
	$reject = "<select name=\"rejection[]\">";
	$reject .= "<option value=\"nomail\">do not send an email</option>";
	$reject .= "<option value=\"none\">standard delete notice</option>";
	while($row = $s24_sql->fetch_array($result)) {
		$reject .= "<option value=\"$row[name]\">$row[name]</option>";
	}
	$reject .= "</select>";
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/search_head.tpl");
	if ($title == "") {$title = '%';}
	$sql = "SELECT * FROM $pop_tbl WHERE $field LIKE '%$title%'";
	$result = $s24_sql->query($sql);
	$tot = $s24_sql->num_rows($result);
	unset($ip);
	while($row = $s24_sql->fetch_array($result)) {
		$id = $row[id];
		$name = $row[name];
		$email = $row[email];
		$url = $row[url];
		$title = $row[title];
		$account = $row[account];
		$out = $row[out];
		$status = $row[status];
		$in = $row[ins];
		if ($ratio == '10') {
			$r = "1.0";
		} else {
			$r = "0.".$ratio;
		}
		$x = strtok((($row[ins]*$r)-$row[out]),".");
		if ($x < 0) { $x = 0; }
		$sitecredits = "$row[credits]"." [".($row[credits]+$x)."]";
		$ip = $row[ip];
		$apptime = xtime($row[apptime]);
		$time = xtime($row[time]);
		$moderator = $row[moderator];
		include(directory . "admin/tpl/search_template.tpl");
	}
	include(directory . "admin/tpl/search_foot.tpl");
	adminfooter();
}

#####email#####

if ($action == 'sendmail') {
	if ($modmail != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$mailto = split (" ",$to);
	$count = count ($mailto);
	for ($i=0;$i<$count;$i++) {
		$sql = "SELECT * FROM $mailwho WHERE id='$mailto[$i]'";
		$result = $s24_sql->query($sql);
		$row = $s24_sql->fetch_array($result);
		$account_id = $row[id];
		$to = $row[email];
		$name = $row[name];
		$url = $row[url];
		$title = $row[title];
		$account = $row[account];
		$mailsubject = $subject;
		$mailmessage = mailreplace($message);
    	@mail($to,$mailsubject,$mailmessage,$additional);
		$mailed .= "$to ";
	}
	$message = "Email sent to: $mailed";
	$action = "show_main";
}

if ($action == 'show_mailall') {
	if ($modmail != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$sql = "SELECT * FROM $pop_tbl";
	$result = $s24_sql->query($sql);
	while($row = $s24_sql->fetch_array($result)) {
		if (!empty($mailtox)) {
			if (!eregi($row[email], $mailtox)) {
				$mailtox .= "$row[email] ";
				$account_idx .= "$row[id] ";
			}
		} else {
			$mailtox .= "$row[email] ";
			$account_idx .= "$row[id] ";
		}
	}
	$mailwho = $pop_tbl;
	$action = "show_mail";
}

if ($action == 'show_mail') {
	if ($modmail != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$count = count ($id);
	for ($i=0;$i<$count;$i++) {
		$sql = "SELECT * FROM $mailwho WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
		$row = $s24_sql->fetch_array($result);
		$mailtox .= "$row[email] ";
		$account_idx .= "$row[id] ";
	}
	$mailto = rtrim ($mailtox);
	$account_id = rtrim ($account_idx);
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/mail.tpl");
	adminfooter();
}

#####display_account#####

if ($action == 'show_account') {
	$sql = "SELECT * FROM $emails_tbl WHERE type='rejection'";
	$result = $s24_sql->query($sql);
	$reject = "<select name=\"rejection[]\">";
	$reject .= "<option value=\"nomail\">do not send an email</option>";
	$reject .= "<option value=\"none\">standard delete notice</option>";
	while($row = $s24_sql->fetch_array($result)) {
		$reject .= "<option value=\"$row[name]\">$row[name]</option>";
	}
	$reject .= "</select>";
	$sql = "SELECT * FROM $pop_tbl WHERE id='$id'";
	$result = $s24_sql->query($sql);
	$tot = $s24_sql->num_rows($result);
	$row = $s24_sql->fetch_array($result);
	$id = $row[id];
	$name = $row[name];
	$email = $row[email];
	$url = $row[url];
	$title = $row[title];
	$account = $row[account];
	$ip = $row[ip];
	$in = $row[ins];
	$out = $row[out];
	if ($ratio == '10') {
		$r = "1.0";
	} else {
		$r = "0.".$ratio;
	}
	$x = strtok((($row[ins]*$r)-$row[out]),".");
	if ($x < 0) { $x = 0; }
	$sitecredits = "$row[credits]"." [".($row[credits]+$x)."]";
	$moderator = $row[moderator];
	$appdate = xtime($row[apptime]);
	$date = xtime($row[time]);
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/account_detail.tpl");
	adminfooter();
}

#####mod#####

if ($action == 'show_allmod') {
	if ($modmoderator != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$sql = "SELECT * FROM $moderator_tbl";
	$result = $s24_sql->query($sql);
	$tot = $s24_sql->num_rows($result);
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/moderator_display_head.tpl");
	while($row = $s24_sql->fetch_array($result)) {
		$id = $row[id];
		$email = $row[email];
		$username = $row[username];
		include(directory . "admin/tpl/moderator_display_template.tpl");
	}
	include(directory . "admin/tpl/moderator_display_foot.tpl");
	adminfooter();
}

if ($action == 'deletemods') {
	if ($modmoderator != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$count = count ($id);
	for ($i=0;$i<$count;$i++) {
		$sql = "DELETE FROM $moderator_tbl WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
		$message .= "Moderator &quot;$id[$i]&quot; deleted!<br>";
	}
	$action = "show_main";
}

if ($action == 'update_mod') {
	if ($modmoderator != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$count = count ($id);
	for ($i=0;$i<$count;$i++) {
		$sql = "UPDATE $moderator_tbl SET username='$username[$i]', password='$password[$i]', email='$email[$i]', super='$super[$i]', process='$process[$i]', setup='$setup[$i]', html='$html[$i]', blacklist='$blacklist[$i]', mail='$mail[$i]', moderator='$moderator[$i]' WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
		$message .= "Moderator &quot;$username[$i]&quot; updated<br>";
	}
	$action = "show_main";
}

if ($action == 'editmod') {
	if ($modmoderator != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/moderator_edit_head.tpl");
	$count = count ($id);
	for ($i=0;$i<$count;$i++) {
		$sql = "SELECT * FROM $moderator_tbl WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
		$tot = $s24_sql->num_rows($result);
		$row = $s24_sql->fetch_array($result);
		$modid = $row[id];
		$email = $row[email];
		$username = $row[username];
		$password = $row[password];
		if ($row[super] == "1") {
			$super = "checked";
		} else {
			$super = "";
		}
		if ($row[mail] == "1") {
			$mail = "mail";
		} else {
			$mail = "";
		}
		if ($row[process] == "1") {
			$process = "checked";
		} else {
			$process = "";
		}
		if ($row[setup] == "1") {
			$setup = "checked";
		} else {
			$setup = "";
		}
		if ($row[html] == "1") {
			$html = "checked";
		} else {
			$html = "";
		}
		if ($row[blacklist] == "1") {
			$blacklist = "checked";
		} else {
			$blacklist = "";
		}
		if ($row[moderator] == "1") {
			$moderator = "checked";
		} else {
			$moderator = "";
		}
		include(directory . "admin/tpl/moderator_edit_template.tpl");
	}
	include(directory . "admin/tpl/moderator_edit_foot.tpl");
	adminfooter();
}

if ($action == 'addmod') {
	if ($modmoderator != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	if (empty($username) || empty($password) || empty($email)) {
		error("A required field was left blank");
	}
	$sql = "SELECT * FROM $moderator_tbl WHERE username='$username'";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num > 0) {
		error("A moderator &quot;$username&quot; does already exist!");
	}
	$sql = "INSERT INTO $moderator_tbl (username, password, email, super, process, setup, html, blacklist, mail, moderator) VALUES ('$username', '$password', '$email', '$super', '$process', '$setup', '$html', '$blacklist', '$mail', '$moderator')";
	$result = $s24_sql->query($sql);
	$message = "Moderator &quot;$username&quot; added!";
	$action = "show_main";
}

if ($action == 'show_addmod') {
	if ($modmoderator != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/moderator_add.tpl");
	adminfooter();
}

#####update_account#####

if ($action == 'update_account') {
	if ($modprocess != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$count = count ($update);
	for ($i=0;$i<$count;$i++) {
		$sql = "UPDATE $pop_tbl SET name='$name[$i]', email='$email[$i]', url='$url[$i]', title='$title[$i]', credits='$sitecredits[$i]', password='$password[$i]' WHERE id='$update[$i]'";
		$result = $s24_sql->query($sql);
		$message .= "Account &quot;$update[$i]&quot; updated<br>";
	}
	$action = "show_main";
}

##x##

if ($action == 'show_edit_account') {
	if ($modprocess != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/edit_head.tpl");
	$count = count ($id);
	for ($i=0;$i<$count;$i++) {
		$sql = "SELECT * FROM $pop_tbl WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
		$row = $s24_sql->fetch_array($result);
		$account_id = $row[id];
		$email = $row[email];
		$account = $row[account];
		$name = $row[name];
		$sitecredits = $row[credits];
		$password = $row[password];
		$url = $row[url];
		$title = $row[title];
		include(directory . "admin/tpl/edit_template.tpl");
	}
	include(directory . "admin/tpl/edit_foot.tpl");
	adminfooter();
}

if ($action == 'process') {
	if ($modprocess != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}

	if (!empty($ban)) {
		foreach ($ban as $x) {
			$count = count($id);
			for ($i=0;$i<$count;$i++) {
				if ($id[$i] == $x) {
					if ($rejection[$i] != 'nomail') {
						$sql = "SELECT * FROM $pop_tbl WHERE id='$x'";
						$result = $s24_sql->query($sql);
						$row = $s24_sql->fetch_array($result);
						$account_id = $row[id];
						$to = $row[email];
						$name = $row[name];
						$url = $row[url];
						$title = $row[title];
						$account = $row[account];
						$sql = "SELECT * FROM $emails_tbl WHERE name='$rejection[$i]'";
						$result = $s24_sql->query($sql);
						$row = $s24_sql->fetch_array($result);
						$mailsubject = $row[subject];
						$mailmessage = mailreplace($row[message]);
						@mail($to,$mailsubject,$mailmessage,$additional);
						echo "$rejection[$i]<br>";
					}
					$sql = "DELETE FROM $pop_tbl WHERE id='$x'";
					$result = $s24_sql->query($sql);
					$message = "Account &quot;$x&quot; deleted!<br>";
					$sql = "SELECT id FROM $ban_tbl WHERE type='email' AND content='$email[$i]'";
					$result = $s24_sql->query($sql);
					$num = $s24_sql->num_rows($result);
					if ($num < 1 && !empty($email[$i])) {
						$sql = "INSERT INTO $ban_tbl (type, content) VALUES ('email', '$email[$i]')";
						$result = $s24_sql->query($sql);
						$message .= "&quot;$email[$i]&quot; blacklisted!<br>";
					}
					$sql = "SELECT id FROM $ban_tbl WHERE type='ip' AND content='$ip[$i]'";
					$result = $s24_sql->query($sql);
					$num = $s24_sql->num_rows($result);
					if ($num < 1 && !empty($ip[$i])) {
						$sql = "INSERT INTO $ban_tbl (type, content) VALUES ('ip', '$ip[$i]')";
						$result = $s24_sql->query($sql);
						$message .= "&quot;$ip[$i]&quot; blacklisted!<br>";
					}
					$sql = "SELECT id FROM $ban_tbl WHERE type='domain' AND content='$domain[$i]'";
					$result = $s24_sql->query($sql);
					$num = $s24_sql->num_rows($result);
					if ($num < 1 && !empty($domain[$i])) {
						$sql = "INSERT INTO $ban_tbl (type, content) VALUES ('domain', '$domain[$i]')";
						$result = $s24_sql->query($sql);
						$message .= "&quot;$domain[$i]&quot; blacklisted!<br>";
					}
				}
			}
		}
	}
	if (!empty($rej)) {
		foreach ($rej as $x) {
			$count = count($id);
			for ($i=0;$i<$count;$i++) {
				if ($id[$i] == $x) {
					if ($rejection[$i] != 'nomail') {
						$sql = "SELECT * FROM $pop_tbl WHERE id='$x'";
						$result = $s24_sql->query($sql);
						$row = $s24_sql->fetch_array($result);
						$account_id = $row[id];
						$to = $row[email];
						$name = $row[name];
						$url = $row[url];
						$title = $row[title];
						$account = $row[account];
						$sql = "SELECT * FROM $emails_tbl WHERE name='$rejection[$i]'";
						$result = $s24_sql->query($sql);
						$row = $s24_sql->fetch_array($result);
						$mailsubject = $row[subject];
						$mailmessage = mailreplace($row[message]);
						@mail($to,$mailsubject,$mailmessage,$additional);
					}
				}
			}
			$sql = "DELETE FROM $pop_tbl WHERE id='$x'";
			$result = $s24_sql->query($sql);
			$message .= "Account &quot;$x&quot; deleted!<br>";
		}
	}
	if (!empty($app)) {
		foreach ($app as $x) {
			$sql = "SELECT * FROM $pop_tbl WHERE id='$x'";
			$result = $s24_sql->query($sql);
			$row = $s24_sql->fetch_array($result);
			$account_id = $row[id];
			$to = $row[email];
			$name = $row[name];
			$password = $row[password];
			$account = $row[account];
			$url = $row[url];
			$title = $row[title];
			$time = $row[time];
			$appurl = "$scripturl" . "members.php?action=html&account=$account&password=$password";
			$acturl = "$scripturl" . "signup.php?action=activate&account=$account&time=$time";
			if ($verifyemail == '1') {
				$sql = "SELECT * FROM $emails_tbl WHERE name='signup'";
			} else {
				$sql = "SELECT * FROM $emails_tbl WHERE name='approve'";
			}
			$result = $s24_sql->query($sql);
			$row = $s24_sql->fetch_array($result);
			$mailsubject = $row[subject];
			$mailmessage = mailreplace($row[message]);
			@mail($to,$mailsubject,$mailmessage,$additional);
			$time = time();
			$appdate = xdate($time,1);
			$sql = "UPDATE $pop_tbl SET status='Approved', moderator='$modlogin', apptime='$time' WHERE id='$x'";
			$result = $s24_sql->query($sql);
			$message .= "Account &quot;$x&quot; approved!<br>";
		}
	}
	$data = "Queue";
	$action = "show_accounts";
}

if ($action == 'approve') {
	if ($modprocess != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	foreach ($id as $x) {
		$sql = "SELECT * FROM $pop_tbl WHERE id='$x'";
		$result = $s24_sql->query($sql);
		$row = $s24_sql->fetch_array($result);
		$account_id = $row[id];
		$to = $row[email];
		$name = $row[name];
		$password = $row[password];
		$account = $row[account];
		$url = $row[url];
		$title = $row[title];
		$time = $row[time];
		$appurl = "$scripturl" . "members.php?action=html&account=$account&password=$password";
		$acturl = "$scripturl" . "signup.php?action=activate&account=$account&time=$time";
		if ($verifyemail == '1') {
			$sql = "SELECT * FROM $emails_tbl WHERE name='signup'";
		} else {
			$sql = "SELECT * FROM $emails_tbl WHERE name='approve'";
		}
		$result = $s24_sql->query($sql);
		$row = $s24_sql->fetch_array($result);
		$mailsubject = $row[subject];
		$mailmessage = mailreplace($row[message]);
		@mail($to,$mailsubject,$mailmessage,$additional);
		$apptime = time();
		$appdate = xdate($time,1);
		$sql = "UPDATE $pop_tbl SET status='Approved', moderator='$modlogin', apptime='$apptime' WHERE id='$x'";
		$result = $s24_sql->query($sql);
	}
	$message = "Approved All Accounts!";
	$action = "show_main";
}

if ($action == 'reject') {
	if ($modprocess != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	foreach ($id as $x) {
		if ($rejection[$i] != 'nomail') {
			$sql = "SELECT * FROM $pop_tbl WHERE id='$x'";
			$result = $s24_sql->query($sql);
			$row = $s24_sql->fetch_array($result);
			$account_id = $row[id];
			$to = $row[email];
			$name = $row[name];
			$url = $row[url];
			$title = $row[title];
			$account = $row[account];
			$sql = "SELECT * FROM $emails_tbl WHERE name='$rejection[$i]'";
			$result = $s24_sql->query($sql);
			$row = $s24_sql->fetch_array($result);
			$mailsubject = $row[subject];
			$mailmessage = mailreplace($row[message]);
			@mail($to,$mailsubject,$mailmessage,$additional);
		}
		$sql = "DELETE FROM $pop_tbl WHERE id='$x'";
		$result = $s24_sql->query($sql);
	}
	$message = "Rejected All Accounts!";
	$action = "show_main";
}

if ($action == 'ban_accounts') {
	if ($modprocess != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	if (!empty($id)) {
		foreach ($id as $x) {
			$count = count($ids);
			for ($i=0;$i<$count;$i++) {
				if ($ids[$i] == $x) {
					$sql = "SELECT * FROM $pop_tbl WHERE id='$x'";
					$result = $s24_sql->query($sql);
					$row = $s24_sql->fetch_array($result);
					$account_id = $row[id];
					$to = $row[email];
					$name = $row[name];
					$url = $row[url];
					$title = $row[title];
					$account = $row[account];
					$ip = $row[ip];
					if ($rejection[$i] != 'nomail') {
						$sql = "SELECT * FROM $emails_tbl WHERE name='$rejection[$i]'";
						$result = $s24_sql->query($sql);
						$row = $s24_sql->fetch_array($result);
						$mailsubject = $row[subject];
						$mailmessage = mailreplace($row[message]);
						@mail($to,$mailsubject,$mailmessage,$additional);
						echo "$rejection[$i]<br>";
					}
					$sql = "DELETE FROM $pop_tbl WHERE id='$x'";
					$result = $s24_sql->query($sql);
					$message .= "Account &quot;$x&quot; deleted!<br>";
					$sql = "SELECT id FROM $ban_tbl WHERE type='email' AND content='$to'";
					$result = $s24_sql->query($sql);
					$num = $s24_sql->num_rows($result);
					if ($num < 1 && !empty($to)) {
						$sql = "INSERT INTO $ban_tbl (type, content) VALUES ('email', '$to')";
						$result = $s24_sql->query($sql);
						$message .= "&quot;$to&quot; blacklisted!<br>";
					}
					$sql = "SELECT id FROM $ban_tbl WHERE type='ip' AND content='$ip'";
					$result = $s24_sql->query($sql);
					$num = $s24_sql->num_rows($result);
					if ($num < 1 && !empty($ip)) {
						$sql = "INSERT INTO $ban_tbl (type, content) VALUES ('ip', '$ip')";
						$result = $s24_sql->query($sql);
						$message .= "&quot;$ip&quot; blacklisted!<br>";
					}
					$sql = "SELECT id FROM $ban_tbl WHERE type='domain' AND content='$url'";
					$result = $s24_sql->query($sql);
					$num = $s24_sql->num_rows($result);
					if ($num < 1 && !empty($url)) {
						$sql = "INSERT INTO $ban_tbl (type, content) VALUES ('domain', '$url')";
						$result = $s24_sql->query($sql);
						$message .= "&quot;$url&quot; blacklisted!<br>";
					}
				}
			}
		}
	}
	$data = "Approved";
	$action = $next;
}

if ($action == 'delete_accounts') {
	if ($modprocess != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	if (!empty($id)) {
		foreach ($id as $x) {
			$count = count($ids);
			for ($i=0;$i<$count;$i++) {
				if ($ids[$i] == $x) {
					if ($rejection[$i] != 'nomail') {
						$sql = "SELECT * FROM $pop_tbl WHERE id='$x'";
						$result = $s24_sql->query($sql);
						$row = $s24_sql->fetch_array($result);
						$account_id = $row[id];
						$to = $row[email];
						$name = $row[name];
						$url = $row[url];
						$title = $row[title];
						$account = $row[account];
						$sql = "SELECT * FROM $emails_tbl WHERE name='$rejection[$i]'";
						$result = $s24_sql->query($sql);
						$row = $s24_sql->fetch_array($result);
						$mailsubject = $row[subject];
						$mailmessage = mailreplace($row[message]);
						@mail($to,$mailsubject,$mailmessage,$additional);
					}
					$sql = "DELETE FROM $pop_tbl WHERE id='$x'";
					$result = $s24_sql->query($sql);
					$message .= "Account &quot;$x&quot; deleted!<br>";
				}
			}
		}
	}
	$data = "Approved";
	$action = $next;
}

if ($action == 'show_accounts') {
	if ($data == 'Archive') {
		$dat = $data;
		$data = "status='Approved'";
	} else {
		$dat = $data;
		$data = "status='$data'";
	}
	if ($modprocess != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	$sql = "SELECT * FROM $pop_tbl WHERE $data ORDER BY id";
	$result = $s24_sql->query($sql);
	$tot = $s24_sql->num_rows($result);
	$seiten = ceil($tot / $lim);
	if (!$site) {
		$site = 1;
	}
	$y = $site * $lim;
	$x = $y - $lim;
	if ($y > $tot) {
		$y = $tot;
	}
	if ($tot == 0) {
		if ($data == "status='Queue'") {
			adminhead("ExitPopup Administration");
			include(directory . "admin/tpl/validate_head.tpl");
		} else {
			$headline = "Displaying current Database ($tot Accounts)";
			adminhead("ExitPopup Administration");
			include(directory . "admin/tpl/show_head.tpl");
		}
		echo "<tr><td colspan=\"3\" align=\"center\">There are no entries in this database!</td></tr>";
		if ($data == "status='Queue'") {
			include(directory . "admin/tpl/validate_foot.tpl");
			adminfooter();
		} else {
			$next = "show_accounts";
			include(directory . "admin/tpl/show_foot.tpl");
			adminfooter();
		}
		exit;
	}
	if ($tot > 0) {
		$sql = "SELECT * FROM $pop_tbl WHERE $data ORDER BY id LIMIT $x,$lim";
		$result = $s24_sql->query($sql);
		if ($data == "status='Queue'") {
			adminhead("ExitPopup Administration");
			include(directory . "admin/tpl/validate_head.tpl");
		} else {
			$headline = "Displaying current Database ($tot Accounts)";
			adminhead("ExitPopup Administration");
			include(directory . "admin/tpl/show_head.tpl");
		}
		while($row = $s24_sql->fetch_array($result)) {
			$id = $row[id];
			$name = $row[name];
			$email = $row[email];
			$url = $row[url];
			$title = $row[title];
			$account = $row[account];
			$out = $row[out];
			$in = $row[ins];
			if ($ratio == '10') {
				$r = "1.0";
			} else {
				$r = "0.".$ratio;
			}
			$x = strtok((($row[ins]*$r)-$row[out]),".");
			if ($x < 0) { $x = 0; }
			$sitecredits = "$row[credits]"." [".($row[credits]+$x)."]";
			$ip = $row[ip];
			$apptime = xtime($row[apptime]);
			$time = xtime($row[time]);
			$moderator = $row[moderator];
			$sql5 = "SELECT name FROM $emails_tbl WHERE type='rejection'";
			$result5 = $s24_sql->query($sql5);
			$reject = "<select name=\"rejection[]\">";
			$reject .= "<option value=\"nomail\">do not send an email</option>";
			$reject .= "<option value=\"none\">standard delete notice</option>";
			while($row5 = $s24_sql->fetch_row($result5)) {
				$reject .= "<option value=\"$row5[0]\">$row5[0]</option>";
			}
			$reject .= "</select>";
			if ($data == "status='Queue'") {
				include(directory . "admin/tpl/validate_template.tpl");
			} else {
				include(directory . "admin/tpl/show_template.tpl");
			}
		}
	}
	if ($tot > $lim) {
		$vor = $site - 1;
		$next = $site + 1;
		if ($vor != 0) {
			$sitechange .= "<a href=\"$PHP_SELF?site=$vor&action=$action&lim=$lim&data=$dat\">";
		}
	  	$sitechange .= "Previous"; 
		if ($vor != 0) {
			$sitechange .= "</a>";
		}
		$sitechange .= " | ";
		if ($next <= $seiten) {
			$sitechange .= "<a href=\"$PHP_SELF?site=$next&action=$action&lim=$lim&data=$dat\">";
		}
		$sitechange .= "Next";
		if ($next <= $seiten) {
			$sitechange .= "</a>";
		}
	}
	if ($data == "status='Queue'") {
		include(directory . "admin/tpl/validate_foot.tpl");
		adminfooter();
	} else {
		$next = "show_accounts";
		include(directory . "admin/tpl/show_foot.tpl");
		adminfooter();
	}
}

if ($action == 'check_urls') {
	$s24_http = new http;
	if (empty($start)) {
		$start = "0";
	}
	$end = "25";
	$sql = "SELECT * FROM $pop_tbl LIMIT $start,$end";
	$result = $s24_sql->query($sql);
	echo "<p>";
	while ($row = $s24_sql->fetch_array($result)) {
		$next = "1";
		$check = $s24_http->check($row[url]);
		if (eregi("[2][0-9][0-9]", $check)) {
			$space = strpos($check, ' ');
			$check = substr($check, $space+1);
			echo "$row[url]: <font color=\"#0000ff\">$check</font> (URL Appears To Be Functional)<br>";
			flush();
		}
		if (eregi("[4][0-9][0-9]", $check)) {
			$space = strpos($check, ' ');
			$check = substr($check, $space+1);
			echo "$row[url]: <font color=\"#ff0000\">$check</font> (URL Appears To Be Down)<br>";
			flush();
			$downids .= "$row[id]::";
		}
	}
	echo "</p>";
	if ($next == '1') {
	    echo "<p><script language=\"javascript\">window.location=\"$PHP_SELF?action=check_urls&downids=$downids&start=".($start+$end)."\";</script><a href=\"$PHP_SELF?action=check_urls&downids=$downids&start=".($start+$end)."\">Please click here if you are not automatically redirected!</a></p>\n";
	} else {
		$action = "show_down_urls";
	}
}

if ($action == 'show_down_urls') {
	$ids = explode("::",$downids);
	$headline = "The following URLs appear to be down:";
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/show_head.tpl");
	foreach ($ids as $id) {
		if (!empty($id)) {
			$sql = "SELECT * FROM $pop_tbl WHERE id='$id'";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			unset($rec);
			$id = $row[id];
			if (!empty($id)) {
				$name = $row[name];
				$email = $row[email];
				$url = $row[url];
				$title = $row[title];
				$account = $row[account];
				$ip = $row[ip];
				$apptime = xtime($row[apptime]);
				$time = xtime($row[time]);
				$moderator = $row[moderator];
				$date = xtime($row[time]);
				$sql5 = "SELECT name FROM $emails_tbl WHERE type='rejection'";
				$result5 = $s24_sql->query($sql5);
				$reject = "<select name=\"rejection[]\">";
				$reject .= "<option value=\"nomail\">do not send an email</option>";
				$reject .= "<option value=\"none\">standard delete notice</option>";
				while($row5 = $s24_sql->fetch_row($result5)) {
					$reject .= "<option value=\"$row5[0]\">$row5[0]</option>";
				}
				$reject .= "</select>";
				include(directory . "admin/tpl/show_template.tpl");
			}
		}
	}
	$next = "show_down_urls";
	include(directory . "admin/tpl/show_foot.tpl");
	adminfooter();
	echo "</p>";
}

if ($action == 'add_url') {
	if ($modprocess != '1') {
		error("Access Denied! You don't have enough privileges to access this area!");
	}
	if (empty($url)) {
		error("A required field was left blank");
	}
	$sql = "INSERT INTO $site_tbl (url, type, out) VALUES ('$url', '$type', '0')";
	$result = $s24_sql->query($sql);
	$message = "Account Added To Database";
	$action = "show_own";
}

if ($action == 'update_site') {
	$count = count ($id);
	for ($i=0;$i<$count;$i++) {
		$sql = "UPDATE $site_tbl SET url='$url[$i]' WHERE id='$id[$i]'";
		$result = $s24_sql->query($sql);
		$message .= "Site &quot;$url[$i]&quot; updated<br>";
	}
	$action = "show_own";
}

if ($action == 'edit_site') {
	adminhead("ExitPopup");
	include(directory . "admin/tpl/own_sites_edit_head.tpl");
	foreach($id as $x) {
		$sql = "SELECT * FROM $site_tbl WHERE id='$x'";
		$result = $s24_sql->query($sql);
		$row = $s24_sql->fetch_array($result);
		$id = $row[id];
		$url = $row[url];
		include(directory . "admin/tpl/own_sites_edit_template.tpl");
	}
	include(directory . "admin/tpl/own_sites_edit_foot.tpl");
	adminfooter();
}

if ($action == 'delete_site') {
#	if ($modblacklist != '1') {
#		error("Access Denied! You don't have enough privileges to access this area!");
#	}
	foreach($id as $x) {
		$sql = "DELETE FROM $site_tbl WHERE id='$x'";
		$result = $s24_sql->query($sql);
		$message .= "Site &quot;$x&quot; deleted<br>";
	}
	$action = "show_own";
}

if ($action == 'show_own') {
	adminhead("ExitPopup");
	include(directory . "admin/tpl/own_sites_head.tpl");
	$sql = "SELECT * FROM $site_tbl";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) {
		$id = $row[id];
		$url = $row[url];
		include(directory . "admin/tpl/own_sites_template.tpl");
	}
	include(directory . "admin/tpl/own_sites_foot.tpl");
	adminfooter();
}

if ($action == 'show_main') {
	$tpl2 = "<select name=\"tpl\">";
    $tpldir = opendir(directory . 'tpl');
    while ($file = readdir($tpldir)) {
		if (eregi(".tpl",$file) && !eregi("admin_",$file)) {
			$tpl2 .= "<option value=\"$file\">$file</option>\n";
		}
	}
    closedir($tpldir);
	$tpl2 .= "</select>";
	if ($signup == '1') {
		$signup = "<option value=\"disable_signup\">Disable signup.php</option>";
	} else {
		$signup = "<option value=\"enable_signup\">Enable signup.php</option>";
	}
	$sql = "SELECT id FROM $pop_tbl WHERE status='Queue'";
	$result = $s24_sql->query($sql);
	$queue = $s24_sql->num_rows($result);
	$sql = "SELECT id FROM $pop_tbl WHERE status='Approved'";
	$result = $s24_sql->query($sql);
	$total = $s24_sql->num_rows($result);
	$sql = "SELECT id FROM $site_tbl";
	$result = $s24_sql->query($sql);
	$own =  $s24_sql->num_rows($result);

	$v = '1.0.1';
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/main.tpl");
	adminfooter();
}

?>