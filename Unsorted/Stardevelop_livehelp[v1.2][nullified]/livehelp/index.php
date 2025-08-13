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

if (!isset($language_type)){ $language_type = ""; }
if (!isset($_GET['DEPARTMENT'])){ $_GET['DEPARTMENT'] = ""; }

$database_config_include = include('./include/config_database.php');
if ($database_config_include == 'true') {
	include('./include/class.mysql.php');
	include('./include/config.php');
	$installed = 'true';
}
else {
	$installed = 'false';
	include('./include/settings_default.php');
}

$language_file = './locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('./locale/lang_en.php');
}

if ($installed == 'true') {
	$SQLSTATUS = new mySQL; 
	$SQLSTATUS->connect();

	$department = $_GET['DEPARTMENT'];
	$server = $_GET['SERVER'];

	//checks if any users in user table are online
	$query_select_users_online = "SELECT login_id FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '0'";
	if ($department != ''  && $departments == 'true') {
		$query_select_users_online .= " AND u.department = '$department'";
	}
	$rows_users_online = $SQLSTATUS->selectall($query_select_users_online);
	
	if(!is_array($rows_users_online)) {
		header('Location: index_offline.php?SERVER=' . $server);
	}
	else {
		if ($disable_login_details == "true") {
			$referer = $_GET['REFERER'];
			header("Location: frames.php?REFERER=$referer&SERVER=$server&" . SID);
		}
	}
	
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>">
<div align="center"> 
  <p>&nbsp;</p>
  <p><img src="<?php echo($_GET['SERVER'] . $livehelp_logo); ?>" alt="<?php echo($livehelp_name); ?>" border="0" /></p>
  <p><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['welcome_to']); ?> 
    <?php echo($site_name); ?>, <?php echo($language['our_live_help']); ?><br>
    <?php echo($language['enter_guest_details']); ?></font></p>
  <form name="login" method="POST" action="frames.php?REFERER=<?php echo($_GET['REFERER']); ?>&SERVER=<?php echo($_GET['SERVER']); ?>">
    <table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td><div align="right"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['username']); ?>: 
            </font></div></td>
        <td><font face="<?php echo($font_type); ?>">
          <input type="text" name="USER_NAME" style="width:150px;">
          </font></td>
      </tr>
      <tr>
        <td><div align="right"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['email']); ?>: 
            </font></div></td>
        <td><font face="<?php echo($font_type); ?>">
          <input type="text" name="EMAIL" style="width:150px;">
          </font></td>
      </tr>
<?php
if (($departments == 'true') && ($department == ''))  {
?>
      <tr>
        <td><div align="right"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['department']); ?>: 
            </font></div></td>
        <td><font face="<?php echo($font_type); ?>">
          <select name="DEPARTMENT" style="width:150px;">
		  <?php
		  	$query_select_departments = "SELECT DISTINCT u.department FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '0'";
			$rows_departments = $SQLSTATUS->selectall($query_select_departments);
			if (is_array($rows_departments)) {
				foreach ($rows_departments as $row_department) {
					if (is_array($row_department)) {
					?>
			<option value="<?php echo($row_department['department']); ?>"><?php echo($row_department['department']); ?></option>
					<?php
					}
				}
			}
		  ?>
          </select>
          </font></td>
      </tr>
<?php
}
elseif (($departments == 'true') || ($department != '')) {
?>
      <input name="DEPARTMENT" type="hidden" value="<?php echo($department); ?>">
<?php
}

$SQLSTATUS->disconnect();
?>
    </table>
    <p><font face="<?php echo($font_type); ?>"> 
      <input name="Submit" type="submit" id="Submit" value="<?php echo($language['login']); ?>">
      </font> </p>
  </form>
  <p><font size="1" face="<?php echo($font_type); ?>"><?php echo($language['stardevelop_copyright']); ?></font></p>
</div>
</body>
</html>