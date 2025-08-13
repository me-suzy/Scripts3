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
$pretyped_option = '';
$contents = '';
$description = '';

if ($_POST['UPDATE'] == "true") {

$pretyped_option = $_POST['PRETYPED_OPTION'];
$contents = $_POST['CONTENTS'];
$description = $_POST['DESCRIPTION'];

if(($pretyped_option == "LINK") && ($description != "" || $contents != "")) {
$command_contents = "<a href=\'$contents\' target=\'_BLANK\'>$description</a>";
$query_insert_command = "INSERT INTO " . $table_prefix . "commands(command_id,type,description,contents) VALUES('','$pretyped_option','$description','$command_contents')";
$SQLDISPLAY->insertquery($query_insert_command);
$status = $language['command_added'];
}
else if(($pretyped_option == "IMAGE") && ($description != "" || $contents != "")) {
$command_contents = "<img src=\'$contents\' alt=\'$description\'>";
$query_insert_command = "INSERT INTO " . $table_prefix . "commands(command_id,type,description,contents) VALUES('','$pretyped_option','$description','$command_contents')";
$SQLDISPLAY->insertquery($query_insert_command);
$status = $language['command_added'];
}
else {
$status = $language['complete_all_fields_commands'];
}
}
elseif($_POST['DELETE'] == "true") {

$command_id = $_POST['COMMANDS'];

$query_delete_command = "DELETE FROM " . $table_prefix . "commands WHERE command_id = '$command_id'";
$SQLDISPLAY->miscquery($query_delete_command);
$status = $language['command_removed'];
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
      <td width="22"><img src="../icons/mail_edit.gif" alt="<?php echo($language['manage_commands']); ?>" width="22" height="22"></td>
      <td colspan="2"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['manage_commands']); ?> :: <?php echo($username); ?></em></font> 
        <div align="right"></div></td>
    </tr>
    <form name="updateSettings" method="post" action="manage_commands.php?<?php echo(SID); ?>">
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
        <td width="317"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['add_commands']); ?>:</font></div></td>
        <td width="48">&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            </font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <input name="PRETYPED_OPTION" type="radio" value="LINK" <?php if ($pretyped_option == "LINK") { echo("checked"); }?>>
            <?php echo($language['link']); ?> 
            <input name="PRETYPED_OPTION" type="radio" value="IMAGE" <?php if ($pretyped_option == "IMAGE") { echo("checked"); }?>>
            <?php echo($language['image']); ?> </font></div></td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['description']); ?>: 
            <input name="DESCRIPTION" type="text" id="DESCRIPTION" value="<?php echo($description); ?>" size="30">
            </font> </div></td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['contents']); ?>:</font>
<input name="CONTENTS" type="text" id="CONTENTS" value="<?php echo($contents); ?>" size="30">
          </div></td>
        <td><input name="SUBMIT" type="image" id="SUBMIT" src="../icons/filenew.gif" alt="<?php echo($language['add_commands']); ?>" width="22" height="22" border="0"></td>
      </tr>
    </form>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['command_instructions']); ?></em></font></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['or']); ?></font></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><?php echo($language['delete_commands']); ?>:</font></font></div></td>
      <td>&nbsp;</td>
    </tr>
    <form name="deleteCommand" method="post" action="manage_commands.php?<?php echo(SID); ?>">
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"></font> 
            <select name="COMMANDS" id="COMMANDS" width="300" style="width:300px;">
              <?php
		$query_select_commands = "SELECT * FROM " . $table_prefix . "commands";
		$rows = $SQLDISPLAY->selectall($query_select_commands);
		if (is_array($rows)) {
			foreach($rows as $row) {
				if (is_array($row)) {
					?>
              <option value="<?php echo($row['command_id']); ?>"><?php echo($row['type'] . " " . $row['description']); ?></option>
              <?php
				}
			}
		}
		$SQLDISPLAY->disconnect();
		?>
            </select>
            <input name="DELETE" type="hidden" id="DELETE" value="true">
          </div></td>
        <td> <input name="SUBMIT" type="image" src="../icons/editdelete_small.gif" alt="<?php echo($language['delete_commands']); ?>" width="22" height="22" border="0"></td>
      </tr>
    </form>
  </table>
</div>
</body>
</html>