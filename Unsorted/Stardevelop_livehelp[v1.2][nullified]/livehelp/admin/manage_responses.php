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
include('../include/config_database.php');
include('../include/class.mysql.php');
include('../include/config.php');
include('../include/auth.php');

$language_file = '../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('../locale/lang_en.php');
}

$SQLDISPLAY = new MySQL;
$SQLDISPLAY->connect();

session_start();
$username = $_SESSION['USERNAME'];
session_write_close();

if (!isset($_POST['UPDATE'])){ $_POST['UPDATE'] = ""; }
if (!isset($_POST['DELETE'])){ $_POST['DELETE'] = ""; }
$status = '';
$contents = '';

if ($_POST['UPDATE'] == "true") {

$contents = $_POST['CONTENTS'];

if(($contents != "")) {
$query_insert_response = "INSERT INTO " . $table_prefix . "responses(response_id,contents) VALUES('','$contents')";
$SQLDISPLAY->insertquery($query_insert_response);
$status = $language['response_added'];
}
else {
$status = $language['complete_all_fields_responses'];
}
}
elseif($_POST['DELETE'] == "true") {

$response_id = $_POST['RESPONSES'];

$query_delete_response = "DELETE FROM " . $table_prefix . "responses WHERE response_id = '$response_id'";
$SQLDISPLAY->miscquery($query_delete_response);
$status = $language['response_removed'];
}
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?php
if ($_POST['UPDATE'] == "true" || $_POST['DELETE'] == "true") {
?>
<script language="JavaScript" type="text/JavaScript">
<!--
parent.messengerFrame.location.reload();
//-->
</script>
<?php
}
?>
<body>
<div align="center"> 
  <table width="401" border="0" align="center">
    <tr> 
      <td width="22"><img src="../icons/mail_edit.gif" alt="<?php echo($language['manage_responses']); ?>" width="22" height="22"></td>
      <td><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['manage_responses']); ?> :: <?php echo($username); ?></em></font> 
        <div align="right"></div></td>
      <td width="48">&nbsp;</td>
    </tr>
    <form name="updateSettings" method="post" action="manage_responses.php?<?php echo(SID); ?>">
      <tr> 
        <td>&nbsp;</td>
        <td><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
<?php
echo($status);
?>
            <input name="UPDATE" type="hidden" id="UPDATE" value="true">
            </font></strong></div></td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td width="317"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['add_responses']); ?>:</font></div></td>
        <td width="48">&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"> 
            <input name="CONTENTS" type="text" id="CONTENTS" value="<?php echo($contents); ?>" size="40">
          </div></td>
        <td><input name="SUBMIT" type="image" id="SUBMIT" src="../icons/filenew.gif" alt="<?php echo($language['add_responses']); ?>" width="22" height="22" border="0"></td>
      </tr>
    </form>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['response_instructions']); ?></em></font></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['or']); ?></font></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><?php echo($language['delete_responses']); ?>:</font></font></div></td>
      <td>&nbsp;</td>
    </tr>
    <form name="deleteResponse" method="post" action="manage_responses.php?<?php echo(SID); ?>">
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"></font> 
            <select name="RESPONSES" id="RESPONSES" width="300" style="width:300px;">
              <?php
		$query_select_responses = "SELECT * FROM " . $table_prefix . "responses";
		$rows = $SQLDISPLAY->selectall($query_select_responses);
		if (is_array($rows)) {
			foreach($rows as $row) {
				if (is_array($row)) {
					?>
              <option value="<?php echo($row['response_id']); ?>"><?php echo($row['contents']); ?></option>
              <?php
				}
			}
		}
		?>
            </select>
            <input name="DELETE" type="hidden" id="DELETE" value="true">
          </div></td>
        <td> <input name="SUBMIT" type="image" src="../icons/editdelete_small.gif" alt="<?php echo($language['delete_responses']); ?>" width="22" height="22" border="0"></td>
      </tr>
    </form>
  </table>
</div>
</body>
</html>