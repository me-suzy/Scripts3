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

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

if (!isset($_GET['UPDATE'])){ $_GET['UPDATE'] = ""; }
if (!isset($_GET['USER'])){ $_GET['USER'] = ""; }
$error = '';

$user_id = $_GET['UID'];
$username = $_GET['USER'];

$query_user_details = "SELECT * FROM " . $table_prefix . "users WHERE user_id = '$user_id'";
$row_user_details = $SQLDISPLAY->selectquery($query_user_details);

if($_GET['UPDATE'] == "true") {

$username = $_GET['USERNAME'];
$first_name = $_GET['FIRST_NAME'];
$last_name = $_GET['LAST_NAME'];
$email = $_GET['EMAIL'];
$department = $_GET['DEPARTMENT'];

if ($username == "" || $first_name == "" || $last_name == "" ||$email == "" || $department == "") {
$error = "true";
}

else{
$query_edit_user = "UPDATE " . $table_prefix . "users SET username = '$username', first_name = '$first_name', last_name = '$last_name', email = '$email', department = '$department' WHERE user_id = '$user_id'";
$SQLDISPLAY->miscquery($query_edit_user);
header('Location: ./users_index.php');
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
  <form action="./users_edit.php?<?php echo(SID); ?>" method="get">
    <table width="400" border="0">
      <tr> 
        <td width="22"><strong><img src="../../icons/edit_small.gif" alt="<?php echo($language['edit_user_details']); ?>" width="22" height="22" border="0"></strong></td>
        <td colspan="2"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['edit_user_details']); ?> :: <?php echo($username); ?></em></font></td>
      </tr>
      <?php
		if ($error == "true"){
		?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"> <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <strong> <?php echo($language['complet_error']); ?></strong></font><font face="Verdana, Arial, Helvetica, sans-serif"></font></div></td>
      </tr>
      <tr> 
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
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['username']); ?>:</font></div></td>
        <td><input name="USERNAME" type="text" id="USERNAME" value="<?php echo($row_user_details['username']); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['first_name']); ?>:</font></div></td>
        <td><input name="FIRST_NAME" type="text" id="FIRST_NAME" value="<?php echo($row_user_details['first_name']); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['last_name']); ?>:</font></div></td>
        <td><input name="LAST_NAME" type="text" id="LAST_NAME" value="<?php echo($row_user_details['last_name']); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['email']); ?>:</font></div></td>
        <td><input name="EMAIL" type="text" id="EMAIL" value="<?php echo($row_user_details['email']); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['department']); ?>:</font></div></td>
        <td><input name="DEPARTMENT" type="text" id="DEPARTMENT" value="<?php echo($row_user_details['department']); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <input name="UID" type="hidden" id="UID" value="<?php echo($row_user_details['user_id']); ?>">
    <input name="UPDATE" type="hidden" id="UPDATE" value="true">
    <input type="submit" name="Submit" value="<?php echo($language['save']); ?>">
    &nbsp; 
    <input type="reset" name="Reset" value="<?php echo($language['reset']); ?>">
  </form>
</div>
</body>
</html>
