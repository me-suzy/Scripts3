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

if (isset($_POST['SITE_NAME'])){ $site_name = $_POST['SITE_NAME']; }
if (isset($_POST['SITE_ADDRESS'])){ $site_address = $_POST['SITE_ADDRESS']; }
if (isset($_POST['OFFLINE_EMAIL'])){ $offline_email = $_POST['OFFLINE_EMAIL']; }
if (isset($_POST['LIVEHELP_NAME'])){ $livehelp_name = $_POST['LIVEHELP_NAME']; }
if (isset($_POST['LIVEHELP_LOGO'])){ $livehelp_logo = $_POST['LIVEHELP_LOGO']; }
if (isset($_POST['WELCOME_NOTE'])){ $welcome_note = $_POST['WELCOME_NOTE']; }
if (isset($_POST['GUEST_SMILIES'])){ $guest_smilies = $_POST['GUEST_SMILIES']; }
if (isset($_POST['ADMIN_SMILIES'])){ $admin_smilies = $_POST['ADMIN_SMILIES']; }
if (isset($_POST['DEPARTMENTS'])){ $departments = $_POST['DEPARTMENTS']; }

?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center"> 
  <form name="UPDATE_SETTINGS" method="post" action="settings_index.php?<?php echo(SID); ?>">
    <table width="400" border="0" align="center">
      <tr> 
        <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['general']); ?>" width="22" height="22"></td>
        <td colspan="2"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['manage_settings']); ?> 
          :: <?php echo($language['general']); ?></em></font> <div align="right"></div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><?php include("./settings_toolbar.php"); ?> </td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <?php
        echo($config_status);
        ?>
            </font></strong></div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td width="160"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['site_name']); ?>: 
            </font></div></td>
        <td><input name="SITE_NAME" type="text" id="SITE_NAME" value="<?php echo($site_name); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['site_address']); ?>:</font></div></td>
        <td><input name="SITE_ADDRESS" type="text" id="SITE_ADDRESS" value="<?php echo($site_address); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['offline_email']); ?>:</font></div></td>
        <td><input name="OFFLINE_EMAIL" type="text" id="OFFLINE_EMAIL" value="<?php echo($offline_email); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['live_help_name']); ?>:</font></div></td>
        <td><input name="LIVEHELP_NAME" type="text" id="LIVEHELP_NAME" value="<?php echo($livehelp_name); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['live_help_logo']); ?>:</font></div></td>
        <td><input name="LIVEHELP_LOGO" type="text" id="LIVEHELP_LOGO" value="<?php echo($livehelp_logo); ?>"> 
          <img src="../../icons/filefind.gif" alt="<?php echo($language['find_file']); ?>" width="22" height="22"> 
        </td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['welcome_note']); ?>: 
            </font></div></td>
        <td><input name="WELCOME_NOTE" type="text" id="WELCOME_NOTE" value="<?php echo($welcome_note); ?>"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['guest_smilies']); ?>:</font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="GUEST_SMILIES" type="radio" value="true" <?php if ($guest_smilies == "true") { echo("checked"); }?>>
          <?php echo($language['on']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input type="radio" name="GUEST_SMILIES" value="false" <?php if ($guest_smilies == "false") { echo("checked"); }?>>
          <?php echo($language['off']); ?></font></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['admin_smilies']); ?>:</font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="ADMIN_SMILIES" type="radio" value="true" <?php if ($admin_smilies == "true") { echo("checked"); }?>>
          <?php echo($language['on']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input type="radio" name="ADMIN_SMILIES" value="false" <?php if ($admin_smilies == "false") { echo("checked"); }?>>
          <?php echo($language['off']); ?></font></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['departments']); ?>:</font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DEPARTMENTS" type="radio" value="true" <?php if ($departments == "true") { echo("checked"); }?>>
          <?php echo($language['on']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DEPARTMENTS" type="radio" value="false" <?php if ($departments == "false") { echo("checked"); }?>>
          <?php echo($language['off']); ?></font></td>
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
