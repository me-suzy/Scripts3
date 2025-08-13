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
include('./include/config_database.php');
include('./include/class.mysql.php');
include('./include/config.php');

$language_file = '../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('locale/lang_en.php');
}

session_start();
$login_id = $_SESSION['LOGIN_ID'];
session_write_close();

if (!isset($_GET['COMPLETE'])){ $_GET['COMPLETE'] = ""; }
$complete = $_GET['COMPLETE'];

$SQLCONNECT = new mySQL; 
$SQLCONNECT->connect();

$query_select = "SELECT server FROM " . $table_prefix . "sessions WHERE login_id = $login_id";
$row = $SQLCONNECT->selectquery($query_select);
if (is_array($row)) {
	$server = $row['server'];
}

$SQLCONNECT->disconnect();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="include/styles.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>">
<div align="center"> <img src="<?php echo($server . $livehelp_logo); ?>" alt="<?php echo($livehelp_name); ?>" border="0" /> 
  <table border="0" align="right" cellpadding="2" cellspacing="2">
    <tr> 
      <td width="30"><div align="center"><a href="print_display.php" target="printFrame"><img src="icons/fileprint.gif" alt="<?php echo($language['print_chat']); ?>" width="22" height="22" border="0"></a></div></td>
      <td><font size="1" face="<?php echo($font_type); ?>"><a href="print_display.php" target="printFrame" class="normlink"><?php echo($language['print_chat']); ?></a></font></td>
      <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">::</font></td>
      <td width="30"><div align="center"><a href="#" onClick="parent.close();"><img src="icons/close.gif" alt="<?php echo($language['close_window']); ?>" width="22" height="22" border="0"></a></div></td>
      <td><font size="1" face="<?php echo($font_type); ?>"><a href="#" onClick="parent.close();" class="normlink"><?php echo($language['close_window']); ?></a></font></td>
    </tr>
  </table>
  <p align="center"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><br>
    <?php echo($language['logout_message']); ?></font></p>
  <p align="center"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"> 
  <form name="rateSession" method="post" action="rate_session.php">
    <p><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['please_rate_service']); ?>:</font></p>
<?php
if ($complete == 'true') {
?>
    <strong><?php echo($language['rating_thank_you']); ?></strong> 
    <?php
}
?>
    <table border="0" cellspacing="2" cellpadding="2">
      <tr> 
        <td><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['rate_service']); ?>: 
          </font></td>
        <td><select name="RATING" id="RATING">
            <option value="5"><?php echo($language['excellent']); ?></option>
            <option value="4"><?php echo($language['very_good']); ?></option>
            <option value="3"><?php echo($language['good']); ?></option>
            <option value="2"><?php echo($language['fair']); ?></option>
            <option value="1"><?php echo($language['poor']); ?></option>
          </select>
        </td>
        <td> <input name="Submit" type="image" src="icons/forward.gif" alt="<?php echo($language['rate']); ?>" width="22" height="22" border="0"></td>
      </tr>
    </table>
  </form>
    
  </font><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['further_assistance']); ?></font></p> 
  <p><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><br>
    </font></p>
  <p><font size="1" face="<?php echo($font_type); ?>"><?php echo($language['stardevelop_copyright']); ?></font></p>
</div>
</body>
</html>