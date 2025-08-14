<?php


error_reporting(7);

if (!defined('directory')) {
	define('directory', '');
}

include(directory . "config.php");
include(directory . "mysql.php");
include(directory . "functions.php");
include(directory . "http.php");

if ($pconnect == '1') {
	$link = $s24_sql->pconnect();
} else {
	$link = $s24_sql->connect();
}
$s24_sql->select_db();
$sql = "SELECT * FROM $options_tbl";
$result = $s24_sql->query($sql);
$row = $s24_sql->fetch_array($result);

$cookiename = "ExitPopup";
$signup = $row[signup];
$hours = $row[hours];
$version = $row[version];
$scripturl = $row[scripturl];
$adminemail = $row[adminemail];
$ratio = $row[ratio];
$checkdup = $row[checkdup];
$moderate = $row[moderate];
$timeoffset = $row[timeoffset];
$dateformat = $row[dateformat];
$timeformat = $row[timeformat];
$sitetitle = $row[sitetitle];
$verifyurl = $row[verifyurl];
$verifyemail = $row[verifyemail];
$notify = $row[notify];
$signup = $row[signup];
$credits = $row[credits];

if (!empty($scripturl)) {
	$count = strlen($scripturl);
	$z = $count - 1;
	$test = substr($scripturl,$z);
	if ($test != "/") {
		$scripturl .= "/";
	}
}

$additional = "From: $adminemail\nReply-To: $adminemail";

?>