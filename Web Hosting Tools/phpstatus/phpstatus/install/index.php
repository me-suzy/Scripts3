<?php
/****************************************************************/
/*                         phpStatus                            */
/*                        index.php file                        */
/*                      (c)copyright 2003                       */
/*                       By hinton design                       */
/*                 http://www.hintondesign.org                  */
/*                  support@hintondesign.org                    */
/*                                                              */
/* This program is free software. You can redistrabute it and/or*/
/* modify it under the terms of the GNU General Public Licence  */
/* as published by the Free Software Foundation; either version */
/* 2 of the license.                                            */
/*                                                              */
/****************************************************************/

define("PHPSTATUS_REAL_PATH","./../");
include(PHPSTATUS_REAL_PATH . 'common.php');

if($HTTP_GET_VARS['step'] == '2') {
   if($HTTP_POST_VARS['password'] !== $HTTP_POST_VARS['password2']) {
      include("header.php");
      $display = "Your paswords dont match";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
			 'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
   
   if((!$HTTP_POST_VARS['dbhost']) || (!$HTTP_POST_VARS['dbname']) || (!$HTTP_POST_VARS['dbuser']) || (!$HTTP_POST_VARS['dbpass']) || (!$HTTP_POST_VARS['prefix']) || (!$HTTP_POST_VARS['username']) || (!$HTTP_POST_VARS['password']) || (!$HTTP_POST_VARS['password2']) || (!$HTTP_POST_VARS['domain']) || (!$HTTP_POST_VARS['script_path'])) {
      include("header.php");
      $display = "All fields are required. Please fill them in.";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                          'L_ERROR' => $lang['error'],
                          'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
   
    if(!eregi("[0-9a-z]{4,10}$", $HTTP_POST_VARS['username'])) {
      include("header.php");
      $display = "The admin username must be atleast 4 characters long.";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                          'L_ERROR' => $lang['error'],
                          'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
   
   $php_beg = "<?php";
   $error = 'if(eregi("config.php",$HTTP_SERVER_VARS[\'PHP_SELF\'])) {' . "\r\n";
   $error .= 'header("Location: index.php");' . "\r\n";
   $error .= 'exit();' . "\r\n";
   $error .= '}';
   $dbhost = '$dbhost = "' . $HTTP_POST_VARS['dbhost'] . '";';
   $dbuser = '$dbuser = "' . $HTTP_POST_VARS['dbuser'] . '";';
   $dbpass = '$dbpass = "' . $HTTP_POST_VARS['dbpass'] . '";';
   $dbname = '$dbname = "' . $HTTP_POST_VARS['dbname'] . '";';
   $prefix = '$prefix = "' . $HTTP_POST_VARS['prefix'] . '";';
   $php_end = "?>";

   $file = PHPSTATUS_REAL_PATH . 'config.php';
   $message = $php_beg . "\r\n" . $error . "\r\n" . $dbhost . "\r\n" . $dbuser . "\r\n" . $dbpass . "\r\n" . $dbname . "\r\n" . $prefix . "\r\n" . $php_end;

   if(is_writable($file)) {
      if(!$handle = fopen($file, 'a')) {
         include("header.php");
         $display = "Could not open file $file";
         $template->getFile(array(
                            'error' => 'admin/error.tpl')
         );
         $template->add_vars(array(
                             'L_ERROR' => $lang['error'],
                             'DISPLAY' => $display)
         );
         $template->parse("error");
         include("footer.php");
         exit();
      }

      if(!fwrite($handle, $message)) {
         include("header.php");
         $display = "Could not write to file $file";
         $template->getFile(array(
                            'error' => 'admin/error.tpl')
         );
         $template->add_vars(array(
                            'L_ERROR' => $lang['error'],
                            'DISPLAY' => $display)
         );
         $template->parse("error");
         include("footer.php");
         exit();
      } else {
         include("header.php");
         $display = "<font class=\"text\">Step 2 Complete. Please click the button below to go to step three.</font><br><br>
                     <form method=\"post\" action=\"index.php?step=3\">
                     <input type=\"hidden\" name=\"email\" value=\"$HTTP_POST_VARS[email]\">
                     <input type=\"hidden\" name=\"domain\" value=\"$HTTP_POST_VARS[domain]\">
                     <input type=\"hidden\" name=\"script_path\" value=\"$HTTP_POST_VARS[script_path]\">
                     <input type=\"hidden\" name=\"username\" value=\"$HTTP_POST_VARS[username]\">
                     <input type=\"hidden\" name=\"password\" value=\"$HTTP_POST_VARS[password]\">
                     <center><input type=\"submit\" name=\"submit\" value=\"Step 3\" class=\"mainoption\"></center>
                     </form>";
         $template->getFile(array(
                            'error' => 'admin/error.tpl')
         );
         $template->add_vars(array(
                             'L_ERROR' => $lang['success'],
                             'DISPLAY' => $display)
         );
         $template->parse("error");
         include("footer.php");
         exit();
      }
   } else {
      include("header.php");
      $display = "The file $file is not writable.";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
}

if($HTTP_GET_VARS['step'] == '3') {
   $sql = "CREATE TABLE ".$prefix."_admin (
           userid int(35) NOT NULL auto_increment,
	   username varchar(100) NOT NULL default '',
	   password varchar(255) NOT NULL default '',
	   user_level enum('0','1') NOT NULL default '0',
	   PRIMARY KEY (`userid`)
	   ) TYPE=MyISAM AUTO_INCREMENT=2";
   $result = $db->query($sql);
   
   if(!$result) {
      include("header.php");
      $sql = "DROP TABLE ".$prefix."_admin";
      $result = $db->query($sql);
      $display = "Could not create table ".$prefix."_admin<br>";
      $display .= mysql_error();
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
   
   $sql2 = "CREATE TABLE ".$prefix."_config (
            id int(35) NOT NULL auto_increment,
	    site_title varchar(100) NOT NULL default '',
	    domain varchar(100) NOT NULL default '',
	    script_path varchar(100) NOT NULL default '',
	    default_lang varchar(100) NOT NULL default '',
	    default_theme varchar(100) NOT NULL default '',
	    PRIMARY KEY (id)
	    ) Type=MyISAM AUTO_INCREMENT=2";
   $result2 = $db->query($sql2);
   
   if(!$result2) {
      $sql8 = "DROP TABLE ".$prefix."_admin";
      $result8 = $db->query($sql8);
      include("header.php");
      $display = "Could not create the table " . $prefix. "_config<br>";
      $display .= mysql_error();
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
   
   $sql3 = "CREATE TABLE ".$prefix."_groups (
            id int(35) NOT NULL auto_increment,
	    name varchar(255) NOT NULL default '',
	    ports varchar(255) NOT NULL default '',
	    PRIMARY KEY (id)
	    ) Type=MyISAM AUTO_INCREMENT=3";
   $result3 = $db->query($sql3);
   
   if(!$result3) {
      $sql9 = "DROP TABLE ".$prefix."_config, ".$prefix."_admin";
      $result9 = $db->query($sql9);
      include("header.php");
      $display = "Could not create the table ".$prefix."_groups<br>";
      $display .= mysql_error();
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      exit();
   }
   
   $sql4= "CREATE TABLE ".$prefix."_lang (
           id int(35) NOT NULL auto_increment,
	   name varchar(100) NOT NULL default '',
	   PRIMARY KEY (id)
	   ) Type=MyISAM AUTO_INCREMENT=4";
   $result4 = $db->query($sql4);
   
   if(!$result4) {
      $sql10 = "DROP TABLE ".$prefix."_groups, ".$prefix."_config, ".$prefix."_admin";
      $result10 = $db->query($sql10);
      include("header.php");
      $display = "Could not create table ".$prefix."_lang<br>";
      $display .= mysql_error();
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
   
   $sql5 = "CREATE TABLE ".$prefix."_ports (
            id int(35) NOT NULL auto_increment,
	    name varchar(100) NOT NULL default '',
	    PRIMARY KEY (id)
	    ) Type=MyISAM";
   $result5 = $db->query($sql5);
   
   if(!$result5) {
      $sql11 = "DROP TABLE ".$prefix."_lang, ".$prefix."_groups, ".$prefix."_config, ".$prefix."_admin";
      $result11 = $db->query($sql11);
      include("header.php");
      $display = "Could not create table ".$prefix."_ports<br>";
      $display .= mysql_error();
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
   
   $sql6 = "CREATE TABLE ".$prefix."_servers (
            id int(35) NOT NULL auto_increment,
	    name varchar(255) NOT NULL default '',
	    ip varchar(255) NOT NULL default '',
	    hostname varchar(255) NOT NULL default '',
	    groupid int(35) NOT NULL default '0',
	    PRIMARY KEY (id)
	    ) Type=MyISAM AUTO_INCREMENT=3";
   $result6 = $db->query($sql6);
   
   if(!$result6) {
      $sql12 = "DROP TABLE ".$prefix."_ports, ".$prefix."_groups, ".$prefix."_lang, ".$prefix."_config, ".$prefix."_admin";
      $result12 = $db->query($sql12);
      include("header.php");
      $display = "Could not create the table ".$prefix."_servers<br>";
      $display .= mysql_error();
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
   
   $sql7 = "CREATE TABLE ".$prefix."_themes (
           id int(35) NOT NULL auto_increment,
	   name varchar(100) NOT NULL default '',
	   PRIMARY KEY (id)
	   ) Type=MyISAM AUTO_INCREMENT=3";
   $result7 = $db->query($sql7);
   
   if(!$result7) {
      include("header.php");
      $sql13 = "DROP TABLE ".$prefix."_ports, ".$prefix."_servers, ".$prefix."_groups, ".$prefix."_lang, ".$prefix."_config, ".$prefix."_admin";
      $result13 = $db->query($sql13);
      $display = "Could not create the table ".$prefix."_themes";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   } else {
      include("header.php");
      $display = "<font class=\"text\">Step 3 Complete. Please click the button below to go to step 4.</font><br><br>
                  <form method=\"post\" action=\"index.php?step=4\">
                  <input type=\"hidden\" name=\"domain\" value=\"$HTTP_POST_VARS[domain]\">
                  <input type=\"hidden\" name=\"script_path\" value=\"$HTTP_POST_VARS[script_path]\">
                  <input type=\"hidden\" name=\"username\" value=\"$HTTP_POST_VARS[username]\">
                  <input type=\"hidden\" name=\"password\" value=\"$HTTP_POST_VARS[password]\">
                  <center><input type=\"submit\" name=\"submit\" value=\"Step 4\" class=\"mainoption\"></center>
                  </form>";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['success'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
}

if($HTTP_GET_VARS['step'] == '4') {
   $site_name = "PHPStatus v1.0";
   
   $sql14 = "INSERT INTO ".$prefix."_config (site_title,domain,script_path,default_lang,default_theme) VALUES ('$site_name','$HTTP_POST_VARS[domain]','$HTTP_POST_VARS[script_path]','english','default')";
   $result14 = $db->query($sql14) or die(mysql_error());
   
   $sql15 = "INSERT INTO ".$prefix."_lang (name) VALUES ('english'),('german')";
   $result15 = $db->query($sql15) or die(mysql_error());
   
   $sql16 = "INSERT INTO ".$prefix."_themes (name) VALUES ('default')";
   $result16 = $db->query($sql16) or die(mysql_error());
   
   $admin_pass = md5($HTTP_POST_VARS['password']);
   $sql17 = "INSERT INTO ".$prefix."_admin (username, password,user_level) VALUES ('$HTTP_POST_VARS[username]', '$admin_pass', '1')";
   $result17 = $db->query($sql17);
   
   if(!$result17) {
      include("header.php");
      $display = "Could not install the script.<br>";
      $display .= mysql_error();
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   } else {
      include("header.php");
      $display = "The script has been installed";
      $link = PHPSTATUS_REAL_PATH . "index.php";
      $template->getFile(array(
                         'success' => 'admin/success.tpl')
      );
      $template->add_vars(array(
                         'L_SUCCESS' => $lang['success'],
                         'DISPLAY' => $display,
			 'LINK' => $link)
      );
      $template->parse("success");
      include("footer.php");
      exit();
   }
}

$domain = $HTTP_SERVER_VARS['HTTP_HOST'];
$script_path = (!empty($HTTP_POST_VARS['script_path'])) ? $HTTP_POST_VARS['script_path'] : str_replace('install', '', dirname($HTTP_SERVER_VARS['PHP_SELF']));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>PHPStatus Installation</title>
<link rel="stylesheet" href="../templates/default/default.css" type="text/css">
</head>
<body>
<center>
<table class="borderline" cellspacing="0" cellpadding="0" width="80%%">
<tr>
<td width="100%" align="center">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><img src="../templates/default/images/logo.gif" border="0"></td>
</tr>
</table>
<table class="borderline" cellspacing="0" cellpadding="0" width="70%">
<tr>
<td width="100%" valign="top" align="center"><font class="header">PHPStatus Installation</font></td>
</tr>
<tr>
<td valign="top" width="100%">
<form method="post" action="index.php?step=2">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="50%" valign="top" align="right"><font class="text">Database Server:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="dbhost" id="dbhost" value="localhost"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Database Name:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="dbname" id="dbname"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Database Username:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="dbuser" id="dbuser"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Database Password:</font></td>
<td width="50%" valign="top" align="left"><input type="password" name="dbpass" id="dbpass"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Database Prefix:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="prefix" id="prefix" value="phpstatus"></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="header">Admin Configuration</font></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="50%" valign="top" align="right"><font class="text">Admin Username:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="username" id="username"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Admin Password:</font></td>
<td width="50%" valign="top" align="left"><input type="password" name="password" id="password"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Confirm-Password:</font></td>
<td width="50%" valign="top" align="left"><input type="password" name="password2" id="password2"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Domain Name:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="domain" id="domain" value="<?php echo $domain; ?>"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Script_path:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="script_path" id="script_path" value="<?php echo $script_path; ?>"></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><input type="submit" name="submit" value="Step 2"></td>
</tr>
</table>
</form>
</td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="copyright">Powered By <a href="http://www.hintondesign.org" tager="_blank">PHPStatus</a></font></td>
</tr>
</table>
</td>
</tr>
</table>
</center>
</body>
</html>