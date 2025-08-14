<?php
/****************************************************************/
/*                         phpStatus                            */
/*                     settings.php file                        */
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

if($HTTP_COOKIE_VARS['loged'] == "yes") {
   if($HTTP_COOKIE_VARS['user_level'] == '1') {
      $sql = "SELECT * FROM ".$prefix."_lang";
      $result = $db->query($sql);
      
      while($row = $db->fetch($result)) {
            $lang_id = $row['id'];
	    $lang_name = $row['name'];

            if($default_lang == $lang_name) {
               $selected = "selected";
            } else {
               $selected = "";
            }
	    
	    $template->add_block_vars("lang", array(
	                               'ID' => $lang_id,
				       'NAME' => $lang_name,
                                       'SELECTED' => $selected)
            );
      }
      
      $sql = "SELECT * FROM ".$prefix."_themes";
      $result = $db->query($sql);
      
      while($row = $db->fetch($result)) {
            $theme_id = $row['id'];
	    $theme_name = $row['name'];
	    
	    $template->add_block_vars("theme", array(
	                               'ID' => $theme_id,
				       'NAME' => $theme_name)
            );
      }
      
      $sql = "SELECT * FROM ".$prefix."_config";
      $result = $db->query($sql);
      
      while($row = $db->fetch($result)) {
            $site_title = $row['site_title'];
	    $domain = $row['domain'];
	    $script_path = $row['script_path'];
	    $default_lang = $row['default_lang'];
	    $default_theme = $row['default_theme'];
      }
      include("header.php");
      $template->getFile(array(
                         'settings' => 'admin/settings.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
			 'L_SETTINGS' => $lang['settings'],
			 'L_SITETITLE' => $lang['site_title'],
			 'L_DOMAIN' => $lang['domain'],
			 'L_SCRIPTPATH' => $lang['script_path'],
			 'L_DEFAULTLANG' => $lang['default_lang'],
			 'L_DEFAULTTHEME' => $lang['default_theme'],
			 
			 'SITE_TITLE' => $site_title,
			 'DOMAIN' => $domain,
			 'SCRIPT_PATH' => $script_path,
			 'DEFAULT_LANG' => $default_lang,
			 'DEFAULT_THEME' => $default_theme)
      );
      $template->parse("settings");
      include("footer.php");
      exit(); 
   } else {
      include("header.php");
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $lang['error_mess5'])
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
} else {
   include("header.php");
   $template->getFile(array(
                      'error' => 'admin/error.tpl')
   );
   $template->add_vars(array(
                      'L_ERROR' => $lang['error'],
                      'DISPLAY' => $lang['error_mess6'])
   );
   $template->parse("error");
   include("footer.php");
   exit();
}
?>