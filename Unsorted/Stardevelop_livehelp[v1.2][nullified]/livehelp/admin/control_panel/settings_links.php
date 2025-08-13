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

include('settings_include.php');

if (isset($_POST['ONLINE_LOGO'])){ $online_logo = $_POST['ONLINE_LOGO']; }
if (isset($_POST['OFFLINE_LOGO'])){ $offline_logo = $_POST['OFFLINE_LOGO']; }
if (isset($_POST['ONLINE_BRB_LOGO'])){ $offline_logo = $_POST['ONLINE_BRB_LOGO']; }
if (isset($_POST['DISABLE_LOGIN_DETAILS'])){ $disable_login_details = $_POST['DISABLE_LOGIN_DETAILS']; }
if (isset($_POST['DISABLE_OFFLINE_EMAIL'])){ $disable_offline_email = $_POST['DISABLE_OFFLINE_EMAIL']; }
if (isset($_POST['OFFLINE_LOGO_WITHOUT_EMAIL'])){ $offline_logo_without_email = $_POST['OFFLINE_LOGO_WITHOUT_EMAIL']; }
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center"> 
  <form name="UPDATE_SETTINGS" method="post" action="settings_links.php?<?php echo(SID); ?>">
    <table width="400" border="0" align="center">
      <tr> 
        <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['links']); ?>" width="22" height="22"></td>
        <td colspan="2"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['manage_settings']); ?> 
          :: <?php echo($language['links']); ?></em></font> <div align="right"></div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><?php include('./settings_toolbar.php'); ?> </td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <strong> <?php
            echo($config_status);
            ?>
            </strong></font><strong></strong></strong></div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td width="160"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['online_logo']); ?>: 
            </font></div></td>
        <td><input name="ONLINE_LOGO" type="text" id="ONLINE_LOGO" value="<?php echo($online_logo); ?>" size="25"> 
          <img src="../../icons/filefind.gif" alt="<?php echo($language['find_file']); ?>" width="22" height="22"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['offline_logo']); ?>:</font></div></td>
        <td><input name="OFFLINE_LOGO" type="text" id="OFFLINE_LOGO" value="<?php echo($offline_logo); ?>" size="25"> 
          <img src="../../icons/filefind.gif" alt="<?php echo($language['find_file']); ?>" width="22" height="22"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['brb_logo']); ?>:</font></div></td>
        <td><input name="textfield" type="text" value="<?php echo($online_brb_logo); ?>" size="25">
          <img src="../../icons/filefind.gif" alt="<?php echo($language['find_file']); ?>" width="22" height="22"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['disable_login_details']); ?>:</font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DISABLE_LOGIN_DETAILS" type="radio" value="false" <?php if ($disable_login_details == "false") { echo("checked"); }?>>
          <?php echo($language['on']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DISABLE_LOGIN_DETAILS" type="radio"  value="true" <?php if ($disable_login_details == "true") { echo("checked"); }?>>
          <?php echo($language['off']); ?></font> </td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['disable_offline_email']); ?>:</font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DISABLE_OFFLINE_EMAIL" type="radio" value="false" <?php if ($disable_offline_email == "false") { echo("checked"); }?>>
          <?php echo($language['on']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input type="radio" name="DISABLE_OFFLINE_EMAIL" value="true" <?php if ($disable_offline_email == "true") { echo("checked"); }?>>
          <?php echo($language['off']); ?></font></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['offline_logo_without_email']); ?>:</font></div></td>
        <td><input name="OFFLINE_LOGO_WITHOUT_EMAIL" type="text" id="OFFLINE_LOGO_WITHOUT_EMAIL" value="<?php echo($offline_logo_without_email); ?>" size="25"> 
          <img src="../../icons/filefind.gif" alt="<?php echo($language['find_file']); ?>" width="22" height="22"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
          </font></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"> 
            <input name="SAVE" type="hidden" id="SAVE" value="true">
            <input name="Submit" type="submit" id="Submit" value="<?php echo($language['save']); ?>">
          </div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>