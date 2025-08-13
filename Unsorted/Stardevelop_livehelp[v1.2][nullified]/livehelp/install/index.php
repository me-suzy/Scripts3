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
if (!isset($_GET['lang'])){ $_GET['lang'] = ''; }

if ($_GET['lang'] == '') {
$lang = 'en';
}
else {
$lang = $_GET['lang'];
}

$language_path = '../locale/lang_' . $lang . '.php';
include($language_path);
?>
<html>
<head>
<title>stardevelop.com Live Help</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="../icons/helplogo.gif" alt="stardevelop.com Live Help" width="250" height="83"> 
  <br>
  <font size="4"><strong><?php echo($language['installation']); ?></strong></font></font></p>
<p align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['install_welcome']); ?><br><?php echo($language['install_welcome_cont']); ?>:</font></p>
<div align="center">
<table width="600" border="0" cellpadding="0" cellspacing="0" bgcolor="#CBE7F7">
    <tr>
      <td><img src="../images/bottom_left.gif"></td>
      <td>&nbsp;</td>
      <td><img src="../images/bottom_right.gif" width="20" height="20"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr> 
            <td><div align="right"><font size="3" face="Verdana, Arial, Helvetica, sans-serif">- 
                <?php echo($language['install_docs_title']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><br>
                <?php echo($language['install_docs_details']); ?></font><br>
              </div></td>
            <td width="80"><div align="center"><a href="http://livehelp.stardevelop.com/documentation/index.htm" target="_blank"><img src="../icons/docs.gif" alt="<?php echo($language['install_docs_title']); ?>" width="48" height="48" border="0"></a><br>
                <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['click_here']); ?></font></div></td>
          </tr>
        </table>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><img src="../images/header_left.gif" width="20" height="20"></td>
      <td>&nbsp;</td>
      <td><img src="../images/header_right.gif"></td>
    </tr>
  </table>
  <br>
  <table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['select_language']); ?>:</em></font></div></td>
    </tr>
    <tr> 
      <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="index.php?lang=en"><img src="../images/us.gif" alt="English (US)" width="18" height="12" border="0"></a>&nbsp;<img src="../images/de.gif" alt="German (Unavailable)" width="18" height="12" border="0">&nbsp;<img src="../images/fr.gif" alt="French - Unavailable" width="18" height="12">&nbsp;<img src="../images/it.gif" alt="Italian - Unavailable" width="18" height="12"></font></div></td>
    </tr>
  </table>
  <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><br>
    <?php echo($language['install_problems']); ?></font> </p>
</div>
<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
<?php
$licensefilename = "../LICENSE.TXT";
$handle = fopen ($licensefilename, "r");
$licensecontents = fread ($handle, filesize ($licensefilename));
fclose ($handle);

?>
</font> 
<form name="install" method="POST" action="installer.php?lang=<?php echo($lang); ?>">
  <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
    <textarea name="textarea" cols="60" rows="10"><?php echo($licensecontents); ?></textarea>
    <br>
    <br>
    <input type="submit" name="Submit" value="<?php echo($language['accept']); ?>">
    &nbsp; 
    <input type="button" name="Submit" value="<?php echo($language['decline']); ?>">
    </font></div>
</form>
<div align="center"> 
  <table width="100" height="25" border="0" align="center">
    <tr> 
      <td><div align="center"><a href="javascript:history.go(-1)"><img src="../icons/back.gif" alt="<?php echo($language['back']); ?>" width="22" height="22" border="0"></a></div></td>
      <td><div align="center"><a href="/livehelp/admin/" target="displayFrame"><img src="../icons/home.gif" alt="<?php echo($language['home']); ?>" width="22" height="22" border="0"></a></div></td>
      <td><div align="center"><a href="javascript:history.go(1)"><img src="../icons/forward.gif" alt="<?php echo($language['forward']); ?>" width="22" height="22" border="0"></a></div></td>
    </tr>
  </table>
</div>
</body>
</html>