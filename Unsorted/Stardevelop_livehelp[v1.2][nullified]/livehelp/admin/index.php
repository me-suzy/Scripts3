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
include('../include/settings_default.php');

$database_config_include = include('../include/config_database.php');
if ($database_config_include == 'true') {
	include('../include/class.mysql.php');
	include('../include/config.php');
	$installed = 'true';
}
else {
	$installed = 'false';
}

if (!isset($language_type)){ $language_type = ""; }
$language_file = '../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('../locale/lang_en.php');
}

$install_dir = '../install';

if (isset($_COOKIE['cookie_login'])) {
	$cookie_login = array();
	$cookie_login = $_COOKIE['cookie_login'];
}
else {
	$cookie_login['username'] = '';
	$cookie_login['password'] = '';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Admin <?php echo($livehelp_name) ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body link="#000000" vlink="#000000" alink="#000000">
<br>
<div align="center"> 
  <p><img src="../icons/helplogo.gif" alt="stardevelop.com Live Help" width="250" height="83" border="0" /></p>
  <p><font size="4" face="Verdana, Arial, Helvetica, sans-serif"> <strong><?php echo($language['administration']); ?></strong> 
    </font></p>
  <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['welcome_message']); ?>
  <?php
  if ($installed == 'true') {
  ?>
  <br>
  <?php
  echo($language['enter_user_pass']);
  }
  ?>
  </font></p>
    <?php
if (!isset($_GET['STATUS'])){ $_GET['STATUS'] = ""; }
$log_status = $_GET['STATUS'];
if($log_status == "error") {
$ip_address = $_SERVER['REMOTE_ADDR'];
?>
  <p><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['access_denied']); ?></font></strong>
    <?php
}
if ($installed == 'true') {
?>
  <form name="login" method="POST" action="frames.php">
    <table width="300" border="0">
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['username']); ?>:</font></div></td>
        <td><font face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="USER_NAME" type="text" value="<?php echo($cookie_login['username']); ?>">
          </font></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['password']); ?>:</font></div></td>
        <td><font face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="PASSWORD" type="password" value="<?php echo($cookie_login['password']); ?>">
          </font></td>
      </tr>
      <tr> 
        <td colspan="2"><div align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
            <input name="REMEMBER_LOGIN" type="checkbox" value="true" <?php if (isset($_COOKIE['cookie_login'])) { echo('checked'); } ?>>
            <?php
		  	echo($language['remember_login_details']);
	?>
            </font></div></td>
      </tr>
    </table>
    <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['admin_supports_line_one']); ?><br>
      </em></font><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['admin_supports_line_two']); ?></em></font><br>
      <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['admin_supports_line_three']); ?></em></font></p>
    <p> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="#" onClick="javascript:window.open('index.php?WINDOW=true', 'ADMINISTRATION', 'width=900,height=700');"><?php echo($language['new_window']); ?></a></font> 
      <font face="Verdana, Arial, Helvetica, sans-serif"> 
      <input type="hidden" name="SERVER" value="<?php echo($_SERVER['HTTP_HOST']); ?>">
      </font> </p>
    <p><font face="Verdana, Arial, Helvetica, sans-serif"> 
      <input name="Submit" type="submit" id="Submit" value="<?php echo($language['login']); ?>">
      &nbsp; 
      <input type="Reset" name="Reset" value="<?php echo($language['reset']); ?>">
      </font> </p>
  </form>
 <?php
}

if ($installed == 'false') {
?>
  <table width="300" border="0">
    <tr> 
      <td width="32"><a href="../install/index.php" target="_blank"><img src="../icons/setup.gif" alt="<?php echo($language['install']); ?>" width="32" height="32" border="0"></a></td>
      <td><div align="center"> 
          <p><em><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['install']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="4"><strong><br>
            </strong></font><?php echo($language['install_instructions']); ?></font></em></p>
        </div></td>
    </tr>
  </table>
  <?php
}
else if ($installed == 'true' && file_exists($install_dir)) {
?>
  <table width="300" border="0">
    <tr> 
      <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['warning']); ?>" width="32" height="32"></td>
      <td><div align="center"> 
          <p><em><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['security_warning']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="4"><strong><br>
            </strong></font><?php echo($language['security_instructions']); ?></font></em></p>
        </div></td>
    </tr>
  </table>
  <?php
}
?>
  <table width="300" border="0">
    <tr> 
      <td width="32"><a href="http://livehelp.stardevelop.com/documentation/" target="_blank"><img src="../icons/docs_small.gif" alt="<?php echo($language['install']); ?>" width="32" height="32" border="0"></a></td>
      <td><div align="center"> 
          <p><em><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['documentation']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="4"><strong><br>
            </strong></font><?php echo($language['docs_instructions']); ?></font></em></p>
        </div></td>
    </tr>
  </table>
<?php
if ($installed != 'true') {
?>
  <br>
  <table width="600" border="0" cellspacing="2" cellpadding="2">
    <tr> 
      <td> <p align="center"><em><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['please_note']); ?></strong>: 
          <?php echo($language['install_first']); ?></font></em></p></td>
    </tr>
  </table>
<?php
}
?>
<p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['stardevelop_copyright']); ?></font></p>
</div>
</body>
</html>