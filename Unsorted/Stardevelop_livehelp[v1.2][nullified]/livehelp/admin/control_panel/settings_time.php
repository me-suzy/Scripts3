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

if (isset($_POST['CONNECTION_TIMEOUT'])){ $connection_timeout = $_POST['CONNECTION_TIMEOUT']; }
if (isset($_POST['KEEP_ALIVE_TIMEOUT'])){ $keep_alive_timeout = $_POST['KEEP_ALIVE_TIMEOUT']; }
if (isset($_POST['USER_PANEL_REFRESH_RATE'])){ $user_panel_refresh_rate = $_POST['USER_PANEL_REFRESH_RATE']; }
if (isset($_POST['CHAT_REFRESH_RATE'])){ $chat_refresh_rate = $_POST['CHAT_REFRESH_RATE']; }
if (isset($_POST['TIMEZONE'])){ $timezone = $_POST['TIMEZONE']; }
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center"> 
  <form name="UPDATE_SETTINGS" method="post" action="settings_time.php?<?php echo(SID); ?>">
    <table width="400" border="0" align="center">
      <tr> 
        <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['time']); ?>" width="22" height="22"></td>
        <td colspan="5"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['manage_settings']); ?> 
          :: <?php echo($language['time']); ?></em></font> <div align="right"></div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="5"><?php include('./settings_toolbar.php'); ?></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="5"><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <?php
        echo($config_status);
        ?>
            </font></strong></div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="5"><div align="right"></div>
          <em><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> </font></em> 
          <table border="0" cellspacing="2" cellpadding="2">
            <tr> 
              <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['connection_timeout']); ?>:</font></div></td>
              <td width="52%"><em><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
                <input name="CONNECTION_TIMEOUT" type="text" value="<?php echo($connection_timeout); ?>" size="2">
                <?php echo($language['seconds']); ?></font></em></td>
            </tr>
            <tr> 
              <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['keep_alive_timeout']); ?>:</font></div></td>
              <td background=""><em><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
                <input name="KEEP_ALIVE_TIMEOUT" type="text" value="<?php echo($keep_alive_timeout); ?>" size="2">
                <?php echo($language['seconds']); ?></font></em></td>
            </tr>
            <tr>
              <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['chat_refresh']); ?>:</font></div></td>
              <td><em><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input name="CHAT_REFRESH_RATE" type="text" value="<?php echo($chat_refresh_rate); ?>" size="2">
                <?php echo($language['seconds']); ?></font></em></td>
            </tr>
            <tr> 
              <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['user_refresh']); ?>:</font></div></td>
              <td><em><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
                <input name="USER_PANEL_REFRESH_RATE" type="text" value="<?php echo($user_panel_refresh_rate); ?>" size="2">
                <?php echo($language['seconds']); ?></font></em></td>
            </tr>
          </table></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['timezone']); ?>:</font></div></td>
        <td colspan="4"><select name="TIMEZONE" width="200">
            <option value="-12" <?php if($timezone == '-12') { echo('selected'); } ?>>(GMT-12:00 
            <?php echo($language['hours']); ?>) Internat. Date Line West</option>
            <option value="-11" <?php if($timezone == '-11') { echo('selected'); } ?>>(GMT-11:00 
            <?php echo($language['hours']); ?>) Midway Island, Samoa</option>
            <option value="-10" <?php if($timezone == '-10') { echo('selected'); } ?>>(GMT-10:00 
            <?php echo($language['hours']); ?>) Hawaii</option>
            <option value="-9" <?php if($timezone == '-9') { echo('selected'); } ?>>(GMT-09:00 
            <?php echo($language['hours']); ?>) Alaska</option>
            <option value="-8" <?php if($timezone == '-8') { echo('selected'); } ?>>(GMT-08:00 
            <?php echo($language['hours']); ?>) Pacific Time</option>
            <option value="-7" <?php if($timezone == '-7') { echo('selected'); } ?>>(GMT-07:00 
            <?php echo($language['hours']); ?>) Mountain Time</option>
            <option value="-6" <?php if($timezone == '-6') { echo('selected'); } ?>>(GMT-06:00 
            <?php echo($language['hours']); ?>) Central Time</option>
            <option value="-5" <?php if($timezone == '-5') { echo('selected'); } ?>>(GMT-05:00 
            <?php echo($language['hours']); ?>) Eastern Time</option>
            <option value="-4" <?php if($timezone == '-4') { echo('selected'); } ?>>(GMT-04:00 
            <?php echo($language['hours']); ?>) Atlantic Time</option>
            <option value="-3.5" <?php if($timezone == '-3.5') { echo('selected'); } ?>>(GMT-03:30 
            <?php echo($language['hours']); ?>) Newfoundland</option>
            <option value="-3" <?php if($timezone == '-3') { echo('selected'); } ?>>(GMT-03:00 
            <?php echo($language['hours']); ?>) Brazil, Buenos Aires</option>
            <option value="-2" <?php if($timezone == '-2') { echo('selected'); } ?>>(GMT-02:00 
            <?php echo($language['hours']); ?>) Mid-Atlantic.</option>
            <option value="-1" <?php if($timezone == '-1') { echo('selected'); } ?>>(GMT-01:00 
            <?php echo($language['hours']); ?>) Cape Verde Islands</option>
            <option value="0" <?php if($timezone == '0') { echo('selected'); } ?>>(GMT) 
            Greenwich Mean Time: London</option>
            <option value="1" <?php if($timezone == '1') { echo('selected'); } ?>>(GMT+01:00 
            <?php echo($language['hours']); ?>) Berlin, Paris, Rome</option>
            <option value="2" <?php if($timezone == '2') { echo('selected'); } ?>>(GMT+02:00 
            <?php echo($language['hours']); ?>) South Africa</option>
            <option value="3" <?php if($timezone == '3') { echo('selected'); } ?>>(GMT+03:00 
            <?php echo($language['hours']); ?>) Baghdad, Moscow</option>
            <option value="3.5" <?php if($timezone == '3.5') { echo('selected'); } ?>>(GMT+03:30 
            <?php echo($language['hours']); ?>) Tehran</option>
            <option value="4" <?php if($timezone == '4') { echo('selected'); } ?>>(GMT+04:00 
            <?php echo($language['hours']); ?>) Adu Dhabi, Baku</option>
            <option value="4.5" <?php if($timezone == '4.5') { echo('selected'); } ?>>(GMT+04:30 
            <?php echo($language['hours']); ?>) Kabul</option>
            <option value="5" <?php if($timezone == '4.5') { echo('selected'); } ?>>(GMT+05:00 
            <?php echo($language['hours']); ?>) Islamabad</option>
            <option value="5.5" <?php if($timezone == '5.5') { echo('selected'); } ?>>(GMT+05:30 
            <?php echo($language['hours']); ?>) Calcutta, Madras</option>
            <option value="6" <?php if($timezone == '6') { echo('selected'); } ?>>(GMT+06:00 
            <?php echo($language['hours']); ?>) Almaty, Colomba</option>
            <option value="7" <?php if($timezone == '7') { echo('selected'); } ?>>(GMT+07:00 
            <?php echo($language['hours']); ?>) Bangkok, Jakarta</option>
            <option value="8" <?php if($timezone == '8') { echo('selected'); } ?>>(GMT+08:00 
            <?php echo($language['hours']); ?>) Singapore, Perth</option>
            <option value="9" <?php if($timezone == '9') { echo('selected'); } ?>>(GMT+09:00 
            <?php echo($language['hours']); ?>) Osaka, Seoul, Tokyo</option>
            <option value="9.5" <?php if($timezone == '9.5') { echo('selected'); } ?>>(GMT+09:30 
            <?php echo($language['hours']); ?>) Adelaide, Darwin</option>
            <option value="10" <?php if($timezone == '10') { echo('selected'); } ?>>(GMT+10:00 
            <?php echo($language['hours']); ?>) Melbourne, Sydney</option>
            <option value="11" <?php if($timezone == '11') { echo('selected'); } ?>>(GMT+11:00 
            <?php echo($language['hours']); ?>) New Caledonia</option>
            <option value="12" <?php if($timezone == '12') { echo('selected'); } ?>>(GMT+12:00 
            <?php echo($language['hours']); ?>) Auckland, Wellington, Fiji</option>
          </select></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="5"><div align="center"> 
            <input name="SAVE" type="hidden" id="SAVE" value="true">
            <input name="Submit" type="submit" id="Submit" value="<?php echo($language['save']); ?>">
          </div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="5"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            </font></div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>
