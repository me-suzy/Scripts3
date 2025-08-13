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

if (isset($_POST['FONT_TYPE'])){ $font_type = $_POST['FONT_TYPE']; }
if (isset($_POST['FONT_SIZE'])){ $font_size = $_POST['FONT_SIZE']; }
if (isset($_POST['FONT_COLOR'])){ $font_color = $_POST['FONT_COLOR']; }
if (isset($_POST['FONT_LINK_COLOR'])){ $font_link_color = $_POST['FONT_LINK_COLOR']; }
if (isset($_POST['BACKGROUND_COLOR'])){ $background_color = $_POST['BACKGROUND_COLOR']; }
if (isset($_POST['BACKGROUND_IMAGE'])){ $background_image = $_POST['BACKGROUND_IMAGE']; }
if (isset($_POST['GUEST_CHAT_FONT_SIZE'])){ $guest_chat_font_size = $_POST['GUEST_CHAT_FONT_SIZE']; }
if (isset($_POST['ADMIN_CHAT_FONT_SIZE'])){ $admin_chat_font_size = $_POST['ADMIN_CHAT_FONT_SIZE']; }
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center"> 
  <form name="UPDATE_SETTINGS" method="post" action="settings_display.php?<?php echo(SID); ?>">
    <table width="400" border="0" align="center">
      <tr> 
        <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['display']); ?>" width="22" height="22"></td>
        <td colspan="5"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['manage_settings']); ?> 
          :: <?php echo($language['display']); ?></em></font> <div align="right"></div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="5"><?php include("./settings_toolbar.php"); ?></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="5"><div align="center"> <strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <?php
		echo($config_status);
		?>
            </font></strong></div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['font_type']); ?>: 
            </font></div></td>
        <td colspan="4"><input name="FONT_TYPE" type="text" id="FONT_TYPE" value="<?php echo($font_type); ?>" size="30"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['font_size']); ?>:</font></div></td>
        <td colspan="4"><select name="FONT_SIZE" id="FONT_SIZE">
            <option value="1" <?php if ($font_size == '1') { echo('selected'); } ?>>1</option>
            <option value="2" <?php if ($font_size == '2') { echo('selected'); } ?>>2</option>
            <option value="3" <?php if ($font_size == '3') { echo('selected'); } ?>>3</option>
            <option value="4" <?php if ($font_size == '4') { echo('selected'); } ?>>4</option>
            <option value="5" <?php if ($font_size == '5') { echo('selected'); } ?>>5</option>
            <option value="6" <?php if ($font_size == '6') { echo('selected'); } ?>>6</option>
            <option value="7" <?php if ($font_size == '7') { echo('selected'); } ?>>7</option>
          </select></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['font_color']); ?>:</font></div></td>
        <td colspan="4"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="FONT_COLOR" type="text" id="FONT_COLOR" value="<?php echo($font_color); ?>" size="7" maxlength="7">
          </font></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['font_link_color']); ?>:</font></div></td>
        <td colspan="4"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="FONT_LINK_COLOR" type="text" id="FONT_LINK_COLOR" value="<?php echo($font_link_color); ?>" size="7" maxlength="7">
          </font></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['background_color']); ?>:</font></div></td>
        <td colspan="4"><input name="BACKGROUND_COLOR" type="text" id="BACKGROUND_COLOR" value="<?php echo($background_color); ?>" size="7" maxlength="7"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['background_image']); ?>:</font></div></td>
        <td colspan="4"><input name="BACKGROUND_IMAGE" type="text" id="BACKGROUND_IMAGE" value="<?php echo($background_image); ?>"> 
          <img src="../../icons/filefind.gif" alt="<?php echo($language['find_file']); ?>" width="22" height="22"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['guest_chat_font_size']); ?>:</font></div></td>
        <td colspan="4"><select name="GUEST_CHAT_FONT_SIZE" id="GUEST_CHAT_FONT_SIZE">
          <option value="1" <?php if ($guest_chat_font_size == '1') { echo('selected'); } ?>>1</option>
          <option value="2" <?php if ($guest_chat_font_size == '2') { echo('selected'); } ?>>2</option>
          <option value="3" <?php if ($guest_chat_font_size == '3') { echo('selected'); } ?>>3</option>
          <option value="4" <?php if ($guest_chat_font_size == '4') { echo('selected'); } ?>>4</option>
          <option value="5" <?php if ($guest_chat_font_size == '5') { echo('selected'); } ?>>5</option>
          <option value="6" <?php if ($guest_chat_font_size == '6') { echo('selected'); } ?>>6</option>
          <option value="7" <?php if ($guest_chat_font_size == '7') { echo('selected'); } ?>>7</option>
        </select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['admin_chat_font_size']); ?>:</font></div></td>
        <td colspan="4"><select name="ADMIN_CHAT_FONT_SIZE" id="ADMIN_CHAT_FONT_SIZE">
          <option value="1" <?php if ($admin_chat_font_size == '1') { echo('selected'); } ?>>1</option>
          <option value="2" <?php if ($admin_chat_font_size == '2') { echo('selected'); } ?>>2</option>
          <option value="3" <?php if ($admin_chat_font_size == '3') { echo('selected'); } ?>>3</option>
          <option value="4" <?php if ($admin_chat_font_size == '4') { echo('selected'); } ?>>4</option>
          <option value="5" <?php if ($admin_chat_font_size == '5') { echo('selected'); } ?>>5</option>
          <option value="6" <?php if ($admin_chat_font_size == '6') { echo('selected'); } ?>>6</option>
          <option value="7" <?php if ($admin_chat_font_size == '7') { echo('selected'); } ?>>7</option>
        </select></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="5"><div align="center"> 
            <input name="SAVE" type="hidden" id="SAVE" value="true">
            <input name="Submit" type="submit" id="Submit" value="<?php echo($language['save']); ?>">
          </div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>
