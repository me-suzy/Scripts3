<?php

if (!isset($_GET['TIMEOUT'])){ $_GET['TIMEOUT'] = ""; }

$timeout = $_GET['TIMEOUT'];

if ($timeout == "") {
	$timeout = "30";
}

include('./config_database.php');
include('./class.mysql.php');
include('./config.php');


function pendingUsers($timeoutValue){

	global $table_prefix;
	
	$SQLDISPLAY = new mySQL; 
	$SQLDISPLAY->connect();

	//PENDING USERS QUERY displays pending users not loged in on users users table
	$query_select = "SELECT DISTINCT (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.datetime)) AS display_flag FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $timeoutValue AND s.active = 0";
	$rows = $SQLDISPLAY->selectall($query_select);

	//initalise user status to false
	$user_status = 'false';

	
	if (is_array($rows)) {
		foreach ($rows as $row) {
			if (is_array($row)) {
				$display_flag = $row['display_flag'];
			
				if ($display_flag < $timeoutValue) {
					$user_status = 'true';
				}
			}
		}
	}

	$SQLDISPLAY->disconnect();

	return $user_status;
}

function browsingUsers($timeoutValue){

	global $table_prefix;
	
	$SQLDISPLAY = new mySQL; 
	$SQLDISPLAY->connect();

	//PENDING USERS QUERY displays pending users not loged in on users users table
	$query_select = "SELECT (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_request)) AS display_flag FROM " . $table_prefix . "requests WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_request)) < $timeoutValue";
	$rows = $SQLDISPLAY->selectall($query_select);

	//initalise user status to false
	$user_status = 'false';

	
	if (is_array($rows)) {
		foreach ($rows as $row) {
			if (is_array($row)) {
				$display_flag = $row['display_flag'];
			
				if ($display_flag < $timeoutValue) {
					$user_status = 'true';
				}
			}
		}
	}

	$SQLDISPLAY->disconnect();

	return $user_status;
}
header('Content-type: text/xml; charset=utf-8');
echo('<?xml version="1.0" encoding="utf-8"?>' . "\n")
?>
<XMLService>
      <Pending><?php echo(pendingUsers($timeout)); ?></Pending>
      <Browsing><?php echo(browsingUsers($timeout)); ?></Browsing>
</XMLService>