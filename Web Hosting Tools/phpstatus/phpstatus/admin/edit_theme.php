<?php
/****************************************************************/
/*                         phpStatus                            */
/*                     edit_theme.php file                      */
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
      $sql = "SELECT * FROM ".$prefix."_themes WHERE id='$HTTP_GET_VARS[id]'";
      $result = $db->query($sql);
      
      $row = $db->fetch($result);
      $theme_id = $row['id'];
      $theme_name = $row['name'];
      
      include("header.php");
      $template->getFile(array(
                         'edit_theme' => 'admin/edit_theme.tpl')
      );
      $template->add_vars(array(
                          'L_NAV' => $lang['navigation'],
			  'L_EDITTHEME' => $lang['edit_theme'],
			  'L_THEMENAME' => $lang['theme_name'],
			  'ID' => $theme_id,
			  'NAME' => $theme_name)
      );
      $template->parse("edit_theme");
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