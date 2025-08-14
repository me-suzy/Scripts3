<?php
/****************************************************************/
/*                         phpStatus                            */
/*                       themes.php file                        */
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
   if($HTTP_COOKIE_VARS['user_level'] = '1') {
      $sql = "SELECT * FROM ".$prefix."_themes";
      $result = $db->query($sql);
      
      while($row = $db->fetch($result)) {
            $theme_id = $row['id'];
	    $theme_name = $row['name'];
	    
	    $template->add_block_vars("themes", array(
	                              'ID' => $theme_id,
				      'NAME' => $theme_name)
            );
      }
      
      include("header.php");
      $template->getFile(array(
                         'themes' => 'admin/themes.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
			 'L_THEMES' => $lang['themes'],
			 'L_ADDTHEME' => $lang['add_theme'],
			 'L_THEMEID' => $lang['theme_id'],
			 'L_THEMENAME' => $lang['theme_name'],
			 'L_ACTION' => $lang['action'])
      );
      $template->parse("themes");
      include("footer.php");
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