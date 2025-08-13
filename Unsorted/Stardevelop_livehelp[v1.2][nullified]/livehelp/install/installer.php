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
<div align="center">
  <p><img src="../icons/helplogo.gif" alt="stardevelop.com Live Help" width="250" height="83"> 
  </p>
  <table width="450" border="0" cellspacing="2" cellpadding="2">
    <tr> 
      <td>
<div align="center">
<table width="190" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="32"><img src="../icons/setup.gif" width="32" height="32"></td>
              <td><div align="center"><strong><font size="4" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['installation']); ?></font></strong></div></td>
            </tr>
          </table>
		  <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['install_welcome_detailed']); ?></font></p>
          <?php
		  $config_db_file = '../include/config_database.php';
		  if (!is_writable($config_db_file)) {
		  ?>
		  <table width="400" border="0">
              <tr> 
                <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['installation_warning']); ?>" width="32" height="32"></td>
                <td><div align="center"> 
                    <p><em><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['installation_warning']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="4"><strong><br>
                      </strong></font><?php echo($language['install_db_file_warning']); ?></font></em></p>
                  </div></td>
              </tr>
          </table>
		  <?php
		  }
		  ?>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['install_welcome_enjoy']); ?></font></p>
        </div></td>
    </tr>
  </table>
  
</div>
<form name="install" method="POST" action="process.php?lang=<?php echo($lang); ?>">
  <div align="center">
<table width="400" border="0">
      <tr> 
        <td colspan="2"><div align="left"> 
            <table width="200" border="0" align="center">
              <tr> 
                <td width="32"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="../icons/dbase.gif" width="32" height="32"></font></td>
                <td><div align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['database_setup']); ?></font></div></td>
              </tr>
            </table>
          </div></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_hostname']); ?>:</strong></font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DB_HOSTNAME" type="text" id="DB_HOSTNAME" value="localhost">
          </font></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_type']); ?>:</strong></font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <select name="DB_TYPE" id="DB_TYPE">
            <option value="mySQL" selected>mySQL</option>
          </select>
          </font></td>
      </tr>
      <tr> 
        <td width="175"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_name']); ?>:</strong></font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DB_NAME" type="text" id="DB_NAME" value="livehelp">
          </font></td>
      </tr>
      <tr> 
        <td width="175"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_username']); ?>:</strong> </font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DB_USERNAME" type="text" id="DB_USERNAME">
          </font></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_password']); ?>:</strong></font> </div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="DB_PASSWORD" type="password" id="DB_PASSWORD">
          </font></td>
      </tr>
      <tr> 
        <td width="175"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['database_table_prefix']); ?>:</strong></font> </div></td>
        <td><p> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <input name="DB_TABLE_PREFIX" type="text" id="DB_TABLE_PREFIX" value="livehelp_">
            </font></p></td>
      </tr>
    </table>
    <br>
    <table width="400" border="0">
      <tr> 
        <td colspan="2"><div align="left"> 
            <table width="160" border="0" align="center">
              <tr> 
                <td width="32"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="../icons/users.gif" width="32" height="32"></font></td>
                <td><div align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['user_setup']); ?></font></div></td>
              </tr>
            </table>
          </div></td>
      </tr>
      <tr> 
        <td width="175"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['username']); ?>:</strong></font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="USER_USERNAME" type="text" id="USER_USERNAME" value="admin">
          </font></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['password']); ?>:</strong></font> 
          </div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="USER_PASSWORD" type="password" id="USER_PASSWORD">
          </font></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['retype_password']); ?>:</strong></font> 
          </div></td>
        <td><p> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <input name="USER_PASSWORD_RETYPE" type="password" id="USER_PASSWORD_RETYPE">
            </font></p></td>
      </tr>
      <tr>
        <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['install_site_domain']); ?>:</strong></font></div></td>
        <td><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <input name="SITE_DOMAIN" type="text" id="SITE_DOMAIN">
            <br>
            <font size="1">eg. http://www.mydomain.com
            </font></font></p>        </td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo($language['offline_email']); ?>:</strong></font></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
          <input name="OFFLINE_EMAIL" type="text" id="OFFLINE_EMAIL">
          </font></td>
      </tr>
    </table>
    <input type="submit" name="Submit" value="<?php echo($language['install']); ?>">
    &nbsp; 
    <input name="Reset" type="reset" id="Reset" value="<?php echo($language['reset']); ?>">
  </div>
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