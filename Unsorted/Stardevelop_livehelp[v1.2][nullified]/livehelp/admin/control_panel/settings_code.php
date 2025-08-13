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

if (!isset($_SERVER['DOCUMENT_ROOT'])){ $_SERVER['DOCUMENT_ROOT'] = ""; }
$include_path = ($_SERVER['DOCUMENT_ROOT'] == "") ? str_replace($_SERVER["SCRIPT_NAME"], "", str_replace("\\\\", "/", $_SERVER["PATH_TRANSLATED"])) : $_SERVER['DOCUMENT_ROOT'];

?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center"> 
  <table width="400" border="0" align="center">
    <tr> 
      <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['general']); ?>" width="22" height="22"></td>
      <td width="160"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['manage_settings']); ?> 
        :: <?php echo($language['code']); ?></em></font> </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><?php include("./settings_toolbar.php"); ?> </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['online_tracker']); ?>:</font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="center"> 
<textarea name="textarea" cols="35" rows="3" style="width:300px;height:50px;overflow:hidden;">
<!-- stardevelop.com Live Help International Copyright - All Rights Reserved //-->
<script language="JavaScript" type="text/JavaScript" src="/livehelp/include/check_status_js.php?STATUS=false">
</script>
</textarea>
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['online_tracker_details']); ?></em></font></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['status_indicator']); ?>:</font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="center"> 
<textarea name="textarea" cols="35" rows="3" style="width:300px;height:50px;overflow:hidden;">
<!-- stardevelop.com Live Help International Copyright - All Rights Reserved //-->
<script language="JavaScript" type="text/JavaScript" src="/livehelp/include/check_status_js.php">
</script>
</textarea>
        </div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['status_indicator_details']); ?></em></font></div></td>
    </tr>
  </table>
</div>
</body>
</html>
