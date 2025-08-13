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
include('../../include/config_database.php');
include('../../include/class.mysql.php');
include('../../include/config.php');
include('../../include/auth.php');

$language_file = '../../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('../../locale/lang_en.php');
}

if (!isset($_GET['COMPLETE'])){ $_GET['COMPLETE'] = ""; }
if (!isset($_GET['UPDATE'])){ $_GET['UPDATE'] = ""; }
$error = '';
$error_password = '';
$error_incorrect = '';

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

$user_id = $_GET['UID'];
$username = $_GET['USER'];
$complete = $_GET['COMPLETE'];

if($_GET['UPDATE'] == "true") {

$current_password = $_GET['CURRENT_PASSWORD'];
$new_password = $_GET['NEW_PASSWORD'];
$confirm_password = $_GET['CONFIRM_PASSWORD'];

if ($current_password == "" || $new_password == "" || $confirm_password == "") {
$error = "true";
}
elseif ($new_password != $confirm_password) {
$error_password = "true";
}
elseif (($error != "true") && ($error_password != "true")) {
$query_user_password = "SELECT * FROM " . $table_prefix . "users WHERE password = '$current_password' AND user_id = '$user_id'";
$row_user_password = $SQLDISPLAY->selectquery($query_user_password);
	if (!is_array($row_user_password)) {
	$error_incorrect = "true";
	}
	else {
	$query_edit_password = "UPDATE " . $table_prefix . "users SET password = '$new_password' WHERE user_id = '$user_id'";
	$SQLDISPLAY->miscquery($query_edit_password);
	header('Location: ./users_password.php?COMPLETE=true');
	}
}
}
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<div align="center">
  <form action="./users_password.php?<?php echo(SID); ?>" method="get">
    <table width="400" border="0">
      <tr> 
        <td width="22"><img src="../../icons/encrypted_small.gif" alt="<?php echo($language['change_user_password']); ?>" width="22" height="22"></td>
        <td colspan="2"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['change_user_password']); ?> :: <?php echo($username); ?></em></font></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"></div>
          <table width="300" border="0" align="center">
            <tr> 
              <td width="32"><img src="../../icons/error.gif" alt="<?php echo($language['warning']); ?>" width="32" height="32"></td>
              <td><div align="center"> 
                  <p><em><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['warning']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="4"><strong><br>
                    </strong></font><?php echo($language['change_user_warning']); ?></font></em></p>
                </div></td>
            </tr>
          </table></td>
      </tr>
      <?php
		if ($error == "true"){
		?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"> <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['complete_error']); ?></strong></font> <font face="Verdana, Arial, Helvetica, sans-serif"></font></div></td>
      </tr>
      <?php
		}
		if ($error_password == "true"){
		?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['change_user_match_error']); ?></strong></font></div></td>
      </tr>
      <?php
		}
		if ($error_incorrect == "true"){
		?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['change_user_password_error']); ?></strong></font></div></td>
      </tr>
      <?php
		}
		if ($complete == "true"){
		?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['change_user_changed']); ?></strong></font></div></td>
      </tr>
      <?php
		}
		?>
      <tr> 
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['current_password']); ?>: </font></div></td>
        <td><input name="CURRENT_PASSWORD" type="password" id="CURRENT_PASSWORD"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <?php echo($language['new_password']); ?>:</font></div></td>
        <td><input name="NEW_PASSWORD" type="password" id="NEW_PASSWORD"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['retype_password']); ?>:</font></div></td>
        <td><input name="CONFIRM_PASSWORD" type="password" id="CONFIRM_PASSWORD"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font> 
    <input name="USER" type="hidden" id="USER" value="<?php echo($username); ?>">
    <input name="UID" type="hidden" id="UID" value="<?php echo($user_id); ?>">
    <input name="UPDATE" type="hidden" id="UPDATE" value="true">
    <input type="submit" name="Submit" value="<?php echo($language['update_password']); ?>">
    &nbsp; 
    <input type="reset" name="Reset" value="<?php echo($language['reset']); ?>">
  </form>
</div>
</body>
</html>
