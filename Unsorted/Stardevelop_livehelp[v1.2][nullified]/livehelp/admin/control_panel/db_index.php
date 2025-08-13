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

$total_length = '';
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<div align="center"> 
  <table width="400" border="0">
    <tr> 
      <td width="22"><img src="../../icons/db_name.gif" alt="<?php echo($language['manage_db']); ?>" width="22" height="22"></td>
      <td colspan="3"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['manage_db']); ?> :: <?php echo(DB_NAME); ?> </em></font></td>
    </tr>
    <tr> 
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['table_name']); ?></strong></font></td>
      <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['size']); ?></strong></font></div></td>
      <td></td>
    </tr>
    <?php
$query_table_status = "SHOW TABLE STATUS";
$rows_table_status = $SQLDISPLAY->selectall($query_table_status);

if (is_array($rows_table_status)) {
	foreach ($rows_table_status as $row) {
		if (is_array($row)) {
?>
    <tr> 
      <td>&nbsp;</td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row["Name"]); ?></font></td>
      <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($row['Data_length']+$row['Index_length']); ?> 
          bytes </font></div></td>
      <td width="22"><div align="center"><img src="../../icons/db_clear.gif" width="22" height="22"></div></td>
    </tr>
    <?php
		}
		$total_length += ($row['Data_length']+$row['Index_length']);
	}
}
?>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['total']); ?>:</font></strong></div></td>
      <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo(round($total_length/1024,2)); ?> 
          KB</font></div></td>
      <td width="30"><div align="center"></div></td>
    </tr>
  </table>
  <form action="./db_sql_dump.php?<?php echo(SID); ?>" method="get" name="sql_dump" id="sql_dump">
    <strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
    <input name="SQL_DUMP" type="hidden" id="SQL_DUMP" value="true">
    <input name="SUBMIT" type="image" src="../../icons/db_dl.gif" width="22" height="22" border="0">
    <br>
    </font></strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['download']); ?><br>
    <?php echo($language['table_data']); ?></font> 
  </form>
</div>
</body>
</html>
