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
if (!isset($_GET['DEPARTMENT'])){ $_GET['DEPARTMENT'] = ""; }
if (!isset($_GET['SERVER'])){ $_GET['SERVER'] = ""; }
if (!isset($_GET['TRACKER'])){ $_GET['TRACKER'] = ""; }
if (!isset($_GET['STATUS'])){ $_GET['STATUS'] = ""; }

$include_path = ($_SERVER['DOCUMENT_ROOT'] == "") ? str_replace($_SERVER["SCRIPT_NAME"], "", str_replace("\\\\", "/", $_SERVER["PATH_TRANSLATED"])) : $_SERVER['DOCUMENT_ROOT'];
include($include_path . '/livehelp/include/config_database.php');
include($include_path . '/livehelp/include/class.mysql.php');
include($include_path . '/livehelp/include/config.php');

$language_file = $include_path . '/livehelp/locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include($include_path . '/livehelp/locale/lang_en.php');
}

session_start();
$current_session = session_id();
session_write_close();

$department = $_GET['DEPARTMENT'];
$server = $_GET['SERVER'];
$tracker_enabled = $_GET['TRACKER'];
$status_enabled = $_GET['STATUS'];

if ($server == '') { $server = $site_address; }
if ($tracker_enabled == '') { $tracker_enabled = 'true'; }
if ($status_enabled == '') { $status_enabled = 'true'; }

if ($tracker_enabled == 'true') {
?>
<!--

// stardevelop.com Live Help International Copyright 2003
// JavaScript Check Status Functions

function currentTime() {
	var date = new Date();
	return date.getTime();
}

function onlineTracker() {
	var tracker = new Image;
	var time = currentTime();
	var title = parent.document.title;
	
	tracker.src = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title;
	var timer = window.setTimeout('onlineTracker();', 10000);
}

//-->

<?php
}

$SQL = new mySQL;
$SQL->connect();

if (isset($_COOKIE['PHPSESSID'])) {

	$ip_address = $_SERVER['REMOTE_ADDR'];
	$hostname = gethostbyaddr($ip_address);
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$referer = $_SERVER['HTTP_REFERER'];
	
	// Get the current page from the referer (the page the JavaScript was called from)
	$current_page = $_SERVER['HTTP_REFERER'];
	for ($i = 0; $i < 3; $i++) {
		$substr_pos = strpos($current_page, '/');
		if ($substr_pos === false) {
			$current_page = '';
			break;
		}
		if ($i < 2) {
			$current_page = substr($current_page, $substr_pos + 1);
		}
		elseif ($i >= 2) {
			$current_page = substr($current_page, $substr_pos);
		}
	}

	// Get the current host from the referer (the page the JavaScript was called from)
	$current_host = $_SERVER['HTTP_REFERER'];
	$str_start = 0; 
	for ($i = 0; $i < 3; $i++) {
		$substr_pos = strpos($current_host, '/');
		if ($substr_pos === false) {
			break;
		}
		if ($i < 2) {
			$current_host = substr($current_host, $substr_pos + 1);
			$str_start += $substr_pos + 1;
		}
		elseif ($i >= 2) {
			$current_host = substr($_SERVER['HTTP_REFERER'], 0, $substr_pos + $str_start);
		}
	}
	
	if ($current_page == '') { $current_page = '/'; }
 
	$select_session_id = "SELECT request_id, request_flag, page_path FROM " . $table_prefix . "requests WHERE session_id = '$current_session'";
	$row = $SQL->selectquery($select_session_id);
	if (is_array($row)) {
		$prev_path = explode(';  ', $row['page_path']);
		$current_path = $row['page_path'];
	
		end($prev_path);
		$index = key($prev_path);
			
		if ($current_page != $prev_path[$index]) {
			$update_current_url_stat = "UPDATE " . $table_prefix . "requests SET last_request=NOW(), current_page='$referer', page_path = '$current_path;  $current_page' WHERE session_id = '$current_session'";
			$SQL->insertquery($update_current_url_stat);
		}
	}
	else {
		$insert_current_url_stat = "INSERT INTO " . $table_prefix . "requests(request_id,session_id,ip_address,user_agent,last_request,last_refresh,current_page,current_page_title,page_path) VALUES('', '$current_session', '$hostname', '$user_agent', NOW(), NOW(), '$referer', '', '$current_page')";
		$SQL->insertquery($insert_current_url_stat);
	}
}

if ($status_enabled == 'true') {

	//counts the total number of support users online
	$query_select_count_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '0'";
	if($department != '' && $departments == 'true') { $query_select_count_users .= " AND s.department = '$department'"; }
	$row_count_users = $SQL->selectquery($query_select_count_users);
	$num_support_users = $row_count_users['count(login_id)'];

	//counts the total number of support users in be right back mode -2
	$query_select_count_brb_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-2'";
	if($department != '' && $departments == 'true') { $query_select_count_brb_users  .= " AND s.department = '$department'"; }
	$row_count_brb_users = $SQL->selectquery($query_select_count_brb_users);
	$num_support_brb_users = $row_count_brb_users['count(login_id)'];

	//set brb active status mode for brb display if all users are in brb mode
	if ($num_support_users == 0 && $num_support_brb_users > 0) {
		$brb_mode_active = 'true';
	}
	else {
		$brb_mode_active = 'false';
	}

	if($num_support_users > 0 || ($num_support_users == 0 && $num_support_brb_users > 0)) {
		if ($brb_mode_active == "true") {
?>

<!--

document.write('<img src="<?php echo($server . $online_brb_logo); ?>" alt="<?php echo($language['livehelp_brb_status']); ?>" border="0">');

//-->

<?php
		}
		elseif ($disable_login_details == "true") {
?>

<!--

document.write('<a href="#" onclick="javascript:window.open(\'<?php echo($site_address); ?>/livehelp/frames.php?REFERER=<?php echo($referer); ?>&SERVER=<?php echo($server); ?><?php if ($department != '') { echo('&DEPARTMENT=' . $department); } ?>\', \'SUPPORTER\', \'width=425,height=425\')"><img src="<?php echo($server . $online_logo); ?>" alt="<?php echo($language['livehelp_online_status']); ?>" border="0"></a>');

//-->

<?php
		}
		elseif ($disable_login_details == "false") {
?>

<!--

document.write('<a href="#" onclick="javascript:window.open(\'<?php echo($site_address); ?>/livehelp/index.php?REFERER=<?php echo($referer); ?>&SERVER=<?php echo($server); ?><?php if ($department != '') { echo('&DEPARTMENT=' . $department); } ?>\', \'SUPPORTER\', \'width=425,height=425\')"><img src="<?php echo($server . $online_logo); ?>" alt="<?php echo($language['livehelp_online_status']); ?>" border="0"></a>');

//-->

<?php
		}
	}
	else {
		if ($disable_offline_email == "true") {
?>

<!--

document.write('<img src="<?php echo($server . $offline_logo_without_email); ?>" alt="<?php echo($language['livehelp_offline_status']); ?>" border="0">');

//-->

<?php
		}
		else {
?>

<!--

document.write('<a href="#" onclick="javascript:window.open(\'<?php echo($site_address); ?>/livehelp/index.php?REFERER=<?php echo($referer); ?>&SERVER=<?php echo($server); ?><?php if ($department != '') { echo('&DEPARTMENT=' . $department); } ?>\', \'SUPPORTER\', \'width=425,height=425\')"><img src="<?php echo($server . $offline_logo); ?>" alt="<?php echo($language['livehelp_offline_status']); ?>" border="0"></a>');

//-->

<?php
		}
	}
}

$SQL->disconnect();

if ($tracker_enabled == 'true') {
?>

<!--

// stardevelop.com Live Help International Copyright 2003
// JavaScript Check Status Called Functions

onlineTracker();

//-->
<?php
}
?>