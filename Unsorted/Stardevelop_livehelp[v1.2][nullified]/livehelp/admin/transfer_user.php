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

session_start();
$login_id = $_SESSION['LOGIN_ID'];
session_write_close();

if (!isset($_GET['TRANSFER'])){ $_GET['TRANSFER'] = ""; }
if (!isset($_GET['COMPLETE'])){ $_GET['COMPLETE'] = ""; }
if (!isset($_GET['LOGIN_ID'])){ $_GET['LOGIN_ID'] = ""; }
if (!isset($_GET['RADIO_TRANSFER_ID'])){ $_GET['RADIO_TRANSFER_ID'] = ""; }
if (!isset($_GET['USER'])){ $_GET['USER'] = ""; }
$error = '';
$status = '';

$transfer_login_id = $_GET['LOGIN_ID'];
$transfer_username = $_GET['USER'];

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

$query_select = "SELECT s.login_id,s.username FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(s.last_refresh)) < " . $connection_timeout . " ORDER  BY s.username";
$rows = $SQLDISPLAY->selectall($query_select);

if ($_GET['TRANSFER'] == "true") {
	if ($_GET['RADIO_TRANSFER_ID'] == "") {
	$transfer_id = $_GET['COMBO_TRANSFER_ID'];
	}
	elseif ($_GET['COMBO_TRANSFER_ID'] == "") {
	$transfer_id = $_GET['RADIO_TRANSFER_ID'];
	}
	if ($transfer_id == "" ) {
	$error = "true";
	}
	else {
	$query_transfer_user = "UPDATE " . $table_prefix . "sessions SET active = '$transfer_id' WHERE login_id = '$transfer_login_id'";
	$SQLDISPLAY->miscquery($query_transfer_user);
	
	$query_update_user_msgs_snt = "UPDATE " . $table_prefix . "messages SET to_login_id = '$transfer_id' WHERE from_login_id = '$transfer_login_id'";
	$SQLDISPLAY->miscquery($query_update_user_msgs_snt);
	
	$query_update_user_msgs_rcv = "UPDATE " . $table_prefix . "messages SET from_login_id = '$transfer_id' WHERE to_login_id = '$transfer_login_id'";
	$SQLDISPLAY->miscquery($query_update_user_msgs_rcv);
	
	header("Location: ./transfer_user.php?COMPLETE=true");
	}
}
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
if ($_GET['COMPLETE'] == "true") {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
  parent.messengerFrame.location.href='messenger.php';
  parent.usersFrame.location.href='users.php';
//-->
</script>
<?php
$status = "true";
}
?>
</head>
<body>
<div align="center"> 
  <table width="400" border="0">
    <tr> 
      <td width="22"><strong><img src="../icons/reload.gif" alt="<?php echo($language['transfer_user']); ?>" width="22" height="22" border="0"></strong></td>
      <td colspan="2"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['transfer_user']); ?> 
        :: <?php echo($transfer_username); ?></em></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2" align="center"> <table width="300" border="0">
          <tr> 
            <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['warning']); ?>" width="32" height="32"></td>
            <td><div align="center"> 
                <p><em><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['warning']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="4"><strong><br>
                  </strong></font><?php echo($language['transfer_warning']); ?></font></em></p>
              </div></td>
          </tr>
        </table></td>
    </tr>
    <form method="get" action="transfer_user.php?<?php echo(SID); ?>">
      <?php
	  if ($error == "true"){
	  ?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"> <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <strong><?php echo($language['transfer_select_user']); ?>: </strong></font> 
          </div></td>
      </tr>
      <?php
	  }
	  ?>
      <?php
	  if ($status == "true"){
	  ?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"> <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <strong><?php echo($language['transfer_complete']); ?></strong></font> 
          </div></td>
      </tr>
      <?php
	  }
	  ?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"> 
            <table width="150" border="0" cellspacing="2" cellpadding="2">
              <?php
			if (is_array($rows)) {
				foreach ($rows as $row) {
					if (is_array($row) && $row['login_id'] != $login_id) {
			?>
              <tr> 
                <td width="25"> <input type="radio" name="RADIO_TRANSFER_ID" value="<?php echo($row['login_id']); ?>"></td>
                <td width="25"><font size="2"><img src="../icons/21.gif" alt="<?php echo($language['online_admin']); ?>" width="22" height="22" border="0"></font></td>
                <td width="100"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
                  <label><?php echo($row['username']); ?></label>
                  </font></td>
              </tr>
              <?php
					}
				}
			}
			?>
            </table>
          </div></td>
      </tr>
      <?php
	  if ((is_array($rows)) && (count($rows) > 1)) {
	  ?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['or']); ?></em></font></div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td width="150">&nbsp;</td>
        <td width="159">&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['transfer_to_user']); ?>:</font></div></td>
        <td><select name="COMBO_TRANSFER_ID" id="COMBO_TRANSFER_ID">
            <option value=""></option>
            <?php
				foreach ($rows as $row) {
					if (is_array($row) && $row['login_id'] != $login_id) {
			?>
            <option value="<?php echo($row['login_id']); ?>"> 
            <?php
						echo($row['username']);
			?>
            </option>
            <?php
					}
				}
			?>
          </select></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"> <div align="center"> 
            <input name="USER" type="hidden" id="USER" value="<?php echo($transfer_username); ?>">
            <input name="LOGIN_ID" type="hidden" id="LOGIN_ID" value="<?php echo($transfer_login_id); ?>">
            <input name="TRANSFER" type="hidden" id="TRANSFER" value="true">
            <input type="submit" name="Submit" value="<?php echo($language['transfer']); ?>">
          </div></td>
      </tr>
    </form>
	<?php
	}
	else {
	?>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2"><div align="center"> <strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['please_note']); ?>: </font></strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['transfer_error_no_admins']); ?></font> </div></td>
    </tr>
    <?php
    }
    ?>
  </table>
</div>
</body>
</html>
