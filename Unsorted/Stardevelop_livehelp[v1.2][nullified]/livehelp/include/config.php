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

$SQLSETTINGS = new mySQL; 
$SQLSETTINGS->connect();

$query_select_settings = "SELECT setting_name,setting_value FROM " . $table_prefix . "settings";
$rows_settings = $SQLSETTINGS->selectall($query_select_settings);
if (is_array($rows_settings)) {
	foreach ($rows_settings as $row) {
		if (is_array($row)) {
			$setting = $row['setting_name'] . '=' . $row['setting_value'];
			parse_str($setting);
		}
	}
}

$SQLSETTINGS->disconnect();

?>