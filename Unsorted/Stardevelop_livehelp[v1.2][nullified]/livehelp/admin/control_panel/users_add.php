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

if (!isset($_GET['ADD'])){ $_GET['ADD'] = ""; }
$error = '';
$error_username = '';
$username = '';
$first_name = '';
$last_name = '';
$password = '';
$password_retype = '';
$email = '';
$department = '';

if($_GET['ADD'] == "true") {

$username = $_GET['USERNAME'];
$first_name = $_GET['FIRST_NAME'];
$last_name = $_GET['LAST_NAME'];
$password = $_GET['PASSWORD'];
$password_retype = $_GET['PASSWORD_RETYPE'];
$email = $_GET['EMAIL'];
$department = $_GET['DEPARTMENT'];

if ($username == "" || $first_name == "" || $last_name == "" || $password == "" || $password_retype == "" || $email == "" || $department == "" || $password != $password_retype) {
$error = "true";
}
else{
// check username doesn't already exist
$query_check_username = "SELECT user_id FROM " . $table_prefix . "users WHERE username = '$username'";
$row = $SQLDISPLAY->selectquery($query_check_username);
if (is_array($row)) {
$error_username = "true";
}
else {
$query_add_user = "INSERT INTO " . $table_prefix . "users(user_id,username,first_name,last_name,password,email,department,last_login_id) VALUES('','$username','$first_name','$last_name','$password','$email','$department','')";
$SQLDISPLAY->insertquery($query_add_user);
header("Location: ./users_index.php");
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
  <form action="./users_add.php?<?php echo(SID); ?>" method="get">
    <table width="400" border="0">
      <tr> 
        <td width="22"><img src="../../icons/user_add.gif" alt="<?php echo($language['add_user_details']); ?>" width="22" height="22"></td>
        <td colspan="2"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['add_user_details']); ?></em></font></td>
      </tr>
      <?php
		if ($error == "true"){
		?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"> <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <strong><?php echo($language['add_user_error']); ?></strong></font> <font face="Verdana, Arial, Helvetica, sans-serif"></font></div></td>
      </tr>
      <tr> 
        <?php
		}
		?>
        <?php
		if ($error_username == "true"){
		?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"> <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <strong><?php echo($language['add_user_exists']); ?></strong></font> <font face="Verdana, Arial, Helvetica, sans-serif"></font></div></td>
      </tr>
      <tr> 
        <?php
		}
		?>
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['username']); ?>:</font></div></td>
        <td><input name="USERNAME" type="text" id="USERNAME" value="<?php echo($username); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['first_name']); ?>:</font></div></td>
        <td><input name="FIRST_NAME" type="text" id="FIRST_NAME" value="<?php echo($first_name); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['last_name']); ?>:</font></div></td>
        <td><input name="LAST_NAME" type="text" id="LAST_NAME" value="<?php echo($last_name); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['password']); ?>:</font></div></td>
        <td><input name="PASSWORD" type="password" id="PASSWORD"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['retype_password']); ?>:</font></div></td>
        <td><input name="PASSWORD_RETYPE" type="password" id="PASSWORD_RETYPE"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['email']); ?>:</font></div></td>
        <td><input name="EMAIL" type="text" id="EMAIL" value="<?php echo($email); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['department']); ?>:</font></div></td>
        <td><input name="DEPARTMENT" type="text" id="DEPARTMENT" value="<?php echo($department); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <input name="ADD" type="hidden" id="ADD" value="true">
    <input type="submit" name="Submit" value="<?php echo($language['add_user']); ?>">
    &nbsp; 
    <input type="reset" name="Reset" value="<?php echo($language['reset']); ?>">
  </form>
</div>
</body>
</html>