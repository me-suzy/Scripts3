<?php


error_reporting(7);

if (!defined('directory')) {
	define('directory', '../');
}

include(directory . "var.php");
$s24_http = new http;

if ($action == 'checkurl') {
	$check = $s24_http->check($url);
	if (eregi("[2][0-9][0-9]", $check)) {
		$space = strpos($check, ' ');
		$check = substr($check, $space+1);
		$verify = "URL Appears To Be Functional: $check";
		adminhead("ExitPopup Administration");
		include(directory . "admin/tpl/popup_url_verify.tpl");
		footer();
		exit;
	}
}

if ($action == 'priveleges') {
	$sql = "SELECT * FROM $moderator_tbl WHERE id='$id'";
	$result = $s24_sql->query($sql);
	$tot = $s24_sql->num_rows($result);
	$row = $s24_sql->fetch_array($result);
	if ($row[mail] == "1") {
		$mail = "Yes";
	} else {
		$mail = "No";
	}
	if ($row[process] == "1") {
		$process = "Yes";
	} else {
		$process = "No";
	}
	if ($row[setup] == "1") {
		$setup = "Yes";
	} else {
		$setup = "No";
	}
	if ($row[html] == "1") {
		$html = "Yes";
	} else {
		$html = "No";
	}
	if ($row[blacklist] == "1") {
		$blacklist = "Yes";
	} else {
		$blacklist = "No";
	}
	if ($row[moderator] == "1") {
		$moderator = "Yes";
	} else {
		$moderator = "No";
	}
	if ($row[super] == "1") {
		$super = "Yes";
		$mail = "Yes";
		$process = "Yes";
		$setup = "Yes";
		$html = "Yes";
		$blacklist = "Yes";
		$moderator = "Yes";
	} else {
		$super = "No";
	}
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/popup_priveleges.tpl");
	footer();
}

if ($action == 'checkip') {
	$data = gethostbyaddr($ip);
	if (empty($data)) {
		$ipmessage = "Could Not Resolve $ip";
	} else {
		$ipmessage = "<b>IP Address:</b> $ip<br>";
		$ipmessage .= "<b>Resolved To:</b> $data";
	}
	adminhead("ExitPopup Administration");
	include(directory . "admin/tpl/popup_checkip.tpl");
	footer();
}

?>