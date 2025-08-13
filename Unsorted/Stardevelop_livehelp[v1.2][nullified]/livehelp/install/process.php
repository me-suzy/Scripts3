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
//error_reporting(E_ERROR | E_PARSE);

if (!isset($_GET['lang'])){ $_GET['lang'] = ''; }
$sql_error_msgs = '';
$config_error_msgs = ''; 

if ($_GET['lang'] == '') {
	$lang = 'en';
}
else {
	$lang = $_GET['lang'];
}

$language_path = '../locale/lang_' . $lang . '.php';
include($language_path);

$db_hostname = $_POST['DB_HOSTNAME'];
$db_type = $_POST['DB_TYPE'];
$db_name = $_POST['DB_NAME'];
$db_username = $_POST['DB_USERNAME'];
$db_password = $_POST['DB_PASSWORD'];
$db_table_prefix = $_POST['DB_TABLE_PREFIX'];
$user_username = $_POST['USER_USERNAME'];
$user_password = $_POST['USER_PASSWORD'];
$user_password_retype = $_POST['USER_PASSWORD_RETYPE'];
$offline_email = $_POST['OFFLINE_EMAIL'];
$site_domain = $_POST['SITE_DOMAIN'];

//Connect to mySQL database with provided form information
$link = mysql_connect("$db_hostname", "$db_username", "$db_password") or $sql_error_msgs .= $language['db_connection_error'] . ' ' . mysql_error() . ', ' . $language['db_confirm_setup'] . '<br>';
$install_status = $language['db_connected'] . "<br>";
$selected = mysql_select_db("$db_name", $link) or $sql_error_msgs .= $language['db_connection_error'] . ' ' . mysql_error() . ', ' . $language['db_confirm_setup'] . '<br>';

if ($user_password == '') {
	$sql_error_msgs .= $language['password_error_blank'] . ', ' . $language['password_confirm_setup'] . '<br>';
}

if ($user_password != $user_password_retype) {
$sql_error_msgs .= $language['password_error_match'] . ', ' . $language['password_confirm_setup'] . "<br>";
}

if ($site_domain == '') {
	$sql_error_msgs .= $language['site_domain_blank'] . '<br>';
}

if (($link && !$sql_error_msgs ) || ($selected && !$sql_error_msgs )){

$sqlfile = file('mysql.schema.txt');
$dump = "";
foreach ($sqlfile as $line) {
	if (trim($line)!='' && substr(trim($line),0,1)!='#') {
		$line = str_replace("table_prefix_livehelp_", $db_table_prefix, $line);
		$dump .= trim($line);
	}
}

$dump = trim($dump,';');
$tables = explode(';',$dump);

foreach ($tables as $sql) {
	mysql_query($sql, $link);
}
if (mysql_error()) {
	$sql_error_msgs .= $language['db_error_schema'] . "<br>";
	$sql_error_status = 'true';
}

// Insert the settings data into the database, and alter the offline email address.
$sqlfile = file('mysql.schema.settings.txt');
$dump = "";
foreach ($sqlfile as $line) {
	if (trim($line)!='' && substr(trim($line),0,1)!='#') {
		$line = str_replace("table_prefix_livehelp_", $db_table_prefix, $line);
		$line = str_replace('enquiry@stardevelop.com', $offline_email, $line);
		$line = str_replace('http://livehelp.stardevelop.com', $site_domain, $line);
		$dump .= trim($line);
	}
}

$dump = trim($dump,';');
$tables = explode(';',$dump);

foreach ($tables as $sql) {
	mysql_query($sql, $link);
}
if (mysql_error()) {
	$sql_error_msgs .= $language['db_error_settings'] . "<br>";
	$sql_error_status = 'true';
}

$insert_initial_user = "INSERT INTO " . $db_table_prefix . "users (user_id, username, password, first_name, last_name, email, department, last_login_id, status) VALUES ('', '$user_username', '$user_password', 'Default', 'Account', '$offline_email', 'Support', '0', '0')";
mysql_query($insert_initial_user, $link);
if (mysql_error()) {
	$sql_error_msgs .= $language['db_error_user'] . "<br>";
	$sql_error_status = 'true';
}
else {
$install_status .= $language['the_username'] . " '$user_username' " . $language['username_sucessfully_created'] . "<br>";
}

if ($sql_error_status == 'true') {
	$sql_error_msgs .= $language['db_sql_error'] . '<br>';
}

mysql_close($link);

$config_db_file = '../include/config_database.php';

$config_db_content = "<?php\n";
$config_db_content .= 'define("DB_HOST", "' . $db_hostname . '");' . "\n";
$config_db_content .= 'define("DB_NAME", "' . $db_name . '");' . "\n";
$config_db_content .= 'define("DB_USER", "' . $db_username . '");' . "\n";
$config_db_content .= 'define("DB_PASS", "' . $db_password . '");' . "\n";
$config_db_content .= "\n";
$config_db_content .= '$table_prefix =  "' . $db_table_prefix . '";' . "\n";
$config_db_content .= "\n";
$config_db_content .= '$install_status = \'true\';' . "\n";
$config_db_content .= 'return($install_status);' . "\n";
$config_db_content .= "\n";
$config_db_content .= "?>";

}
?> 
<html>
<head>
<title>stardevelop.com Live Help</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<div align="center"> 
  <p><img src="../icons/helplogo.gif" alt="stardevelop.com Live Help" width="250" height="83"> 
    <br>
  </p>
</div>
<table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
<table width="190" border="0" align="center" cellpadding="2" cellspacing="2">
        <tr> 
          <td width="32"><img src="../icons/setup.gif" width="32" height="32"></td>
          <td><div align="center"><strong><font size="4" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['installation']); ?></font></strong></div></td>
        </tr>
      </table>
      
    </td>
  </tr>
  <tr> 
    <td>
<div align="center">
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <?php

if (is_writable($config_db_file)) {
    if (!$handle = fopen($config_db_file, 'w')) {
		$config_error_msgs = $language['file_error_open'] . "<br>";
    }
    else {
    	if (!fwrite($handle, $config_db_content)) {
			$config_error_msgs =  $language['file_error_write'] . "<br>";
    	}
		else {
			$install_status .=  $language['config_created'] . "<br>";
    		fclose($handle);
		}
	}	
}
else {
	$config_error_msgs = $language['file_not_found'] . "<br>";
}
?>
          <br>
          <?php echo($language['install_thank_you']); ?><br>
          <?php echo($install_status); ?><br>
          <?php
if ($sql_error_msgs || $config_error_msgs) {
?>
          </font></p>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td><div align="center"> 
                <table width="250" border="0">
                  <tr> 
                    <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['warning']); ?>" width="32" height="32"></td>
                    <td><div align="center"> 
                        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="4"><?php echo($language['installation_warning']); ?></font></font></p>
                      </div></td>
                  </tr>
                </table>
              </div></td>
          </tr>
          <tr> 
            <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
                <?php
	echo($sql_error_msgs);
	echo($config_error_msgs);
	echo($language['consult_support']);
?>
                </font></div></td>
          </tr>
        </table>
      </div></td>
  </tr>
</table>
<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> </font> 
<form name="install" method="POST" action="process.php?lang=<?php echo($lang); ?>">
  <div align="center"> 
    <table width="400" border="0">
      <tr> 
        <td colspan="2"><div align="left"> 
            <table width="200" border="0" align="center">
              <tr> 
                <td width="32"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="../icons/dbase.gif" width="32" height="32"></font></td>
                <td><div align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['database_setup']); ?></font></div></td>
              </tr>
            </table>
          </div></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_hostname']); ?>:</strong></font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DB_HOSTNAME" type="text" id="DB_HOSTNAME" value="<?php echo($db_hostname); ?>">
          </font></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_type']); ?>:</strong></font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <select name="DB_TYPE" id="select">
            <option value="mySQL" selected>mySQL</option>
          </select>
          </font></td>
      </tr>
      <tr> 
        <td width="175"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_name']); ?>:</strong></font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DB_NAME" type="text" id="DB_NAME" value="<?php echo($db_name); ?>">
          </font></td>
      </tr>
      <tr> 
        <td width="175"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_username']); ?>:</strong> 
            </font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DB_USERNAME" type="text" id="DB_USERNAME">
          </font></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_password']); ?>:</strong></font> 
          </div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DB_PASSWORD" type="password" id="DB_PASSWORD">
          </font></td>
      </tr>
      <tr> 
        <td width="175"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_table_prefix']); ?>:</strong></font> 
          </div></td>
        <td><p> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <input name="DB_TABLE_PREFIX" type="text" id="DB_TABLE_PREFIX" value="<?php echo($db_table_prefix); ?>">
            </font></p></td>
      </tr>
    </table>
    <br>
    <table width="400" border="0">
      <tr> 
        <td colspan="2"><div align="left"> 
            <table width="160" border="0" align="center">
              <tr> 
                <td width="32"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="../icons/users.gif" width="32" height="32"></font></td>
                <td><div align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['user_setup']); ?></font></div></td>
              </tr>
            </table>
          </div></td>
      </tr>
      <tr> 
        <td width="175"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['username']); ?>:</strong></font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="USER_USERNAME" type="text" id="USER_USERNAME" value="<?php echo($user_username); ?>">
          </font></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['password']); ?>:</strong></font> 
          </div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="USER_PASSWORD" type="password" id="USER_PASSWORD">
          </font></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['retype_password']); ?>:</strong></font> 
          </div></td>
        <td><p> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <input name="USER_PASSWORD_RETYPE" type="password" id="USER_PASSWORD_RETYPE">
            </font></p></td>
      </tr>
      <tr>
        <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['install_site_domain']); ?>:</strong></font></div></td>
        <td><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <input name="SITE_DOMAIN" type="text" id="SITE_DOMAIN">
            <br>
            <font size="1">eg. http://www.mydomain.com </font></font></p></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['offline_email']); ?>:</strong></font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="OFFLINE_EMAIL" type="text" id="OFFLINE_EMAIL" value="<?php echo($offline_email); ?>">
          </font></td>
      </tr>
    </table>
    <input type="submit" name="Submit" value="<?php echo($language['retry_install']); ?>">
    &nbsp; 
    <input type="reset" name="Reset" value="<?php echo($language['reset']); ?>">
  </div>
</form>
  <div align="center"> 
  <?php
}
else {
?>
  <a href="/livehelp/admin/"><img src="../images/finished_install.gif" alt="<?php echo($language['installation']); ?>" width="290" height="343" border="0"></a> 
  <?php
}
?>
  <table width="100" height="25" border="0" align="center">
    <tr> 
      <td><div align="center"><a href="javascript:history.go(-1)"><img src="../icons/back.gif" alt="<?php echo($language['back']); ?>" width="22" height="22" border="0"></a></div></td>
      <td><div align="center"><a href="/livehelp/admin/" target="displayFrame"><img src="../icons/home.gif" alt="<?php echo($language['home']); ?>" width="22" height="22" border="0"></a></div></td>
      <td><div align="center"><a href="javascript:history.go(1)"><img src="../icons/forward.gif" alt="<?php echo($language['forward']); ?>" width="22" height="22" border="0"></a></div></td>
    </tr>
  </table>
</div>
</body>
</html>