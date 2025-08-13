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
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body marginwidth="0" leftmargin="0" topmargin="5" bottommargin="0" rightmagin="0">
<table height="100%" border="0" align="right" cellpadding="0" cellspacing="0">
  <tr> 
    <td><div align="right"> 
        <table border="0" align="center" cellpadding="2" cellspacing="2">
          <tr> 
            <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="logout.php?<?php echo(SID); ?>" target="_parent"><img src="../icons/logout.gif" alt="<?php echo($language['logout']); ?>" width="22" height="22" border="0"></a></font></div></td>
          </tr>
          <tr> 
            <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="logout.php?<?php echo(SID); ?>" target="_parent" class="normlink"><?php echo($language['logout']); ?></a></font></div></td>
          </tr>
        </table>
        
      </div></td>
  </tr>
  <tr>
    <td valign="middle"><table width="90" border="0" align="right" cellpadding="4" cellspacing="4">
        <tr>
          <td><div align="center"><a href="visitors_index.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/online_visitors.gif" width="32" height="32" border="0"></a><br>
                <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="visitors_index.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['visitors']); ?></a></font>
            </div></td>
        </tr>
        <tr> 
          <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="overall_statistics.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/newtodo.gif" alt="<?php echo($language['statistics']); ?>" width="32" height="32" border="0"></a><br>
              <a href="overall_statistics.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['statistics']); ?></a></font></div></td>
        </tr>
        <tr> 
          <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="reports_index.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/folder2_html.gif" alt="<?php echo($language['reports']); ?>" width="32" height="32" border="0"></a><br>
              <a href="reports_index.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['reports']); ?></a></font></div></td>
        </tr>
        <tr> 
          <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="control_panel/users_index.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/users.gif" alt="<?php echo($language['users']); ?>" width="32" height="32" border="0"></a><br>
              <a href="control_panel/users_index.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['users']); ?></a></font></div></td>
        </tr>
        <tr> 
          <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="control_panel/db_index.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/dbase.gif" alt="<?php echo($language['database']); ?>" width="32" height="32" border="0"></a><br>
              <a href="control_panel/db_index.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['database']); ?></a></font></div></td>
        </tr>
        <tr> 
          <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="control_panel/settings_index.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/configure.gif" alt="<?php echo($language['settings']); ?>" width="32" height="32" border="0"></a><br>
              <a href="control_panel/settings_index.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['settings']); ?></a></font></div></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>