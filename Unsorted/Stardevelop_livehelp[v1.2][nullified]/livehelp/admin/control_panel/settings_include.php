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
if (!isset($_POST['SAVE'])){ $_POST['SAVE'] = ""; }
$config_status = '';

if ($_POST['SAVE'] == "true") {

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

//for every post value check all the lines and update them.
foreach($_POST as $post_key => $post_value) {
	//discard the unused post values ie submit buttons and save indict.
	if ($post_key != "SAVE" && $post_key != "Submit") { 
		$setting_name = strtolower($post_key);
		$setting_value = stripslashes($post_value);
		$query_edit_user = "UPDATE " . $table_prefix . "settings SET setting_value = '$post_value' WHERE setting_name = '$setting_name'";
		$SQLDISPLAY->miscquery($query_edit_user);
	}
}
$config_status = $language['settings_saved'];
$SQLDISPLAY->disconnect();
}
?>
