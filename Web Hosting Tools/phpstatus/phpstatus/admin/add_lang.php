<?php
/****************************************************************/
/*                         phpStatus                            */
/*                     add_lang.php file                        */
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
      if(!$HTTP_POST_VARS['name']) {
         include("header.php");
	 $template->getFile(array(
	                    'error' => 'admin/error.tpl')
	 );
	 $template->add_vars(array(
	                   'L_ERROR' => $lang['error'],
			   'DISPLAY' => $lang['error_mess25'])
	 );
	 $template->parse("error");
	 include("footer.php");
	 exit();
      }
      
      $sql = "SELECT name FROM ".$prefix."_lang WHERE name='$HTTP_POST_VARS[name]'";
      $result = $db->query($sql);
      
      $num = $db->num($result);
      
      if($num > 0) {
         include("header.php");
	 unset($HTTP_POST_VARS['name']);
	 $template->getFile(array(
	                    'error' => 'admin/error.tpl')
	 );
	 $template->add_vars(array(
	                    'L_ERROR' => $lang['error'],
			    'DISPLAY' => $lang['error_mess26'])
	 );
	 $template->parse("error");
	 include("footer.php");
	 exit();
      }
      
      $sql = "INSERT INTO ".$prefix."_lang (name) VALUES ('$HTTP_POST_VARS[name]')";
      $result = $db->query($sql);
      
      if(!$result) {
         include("header.php");
	 $template->getFile(array(
	                    'error' => 'admin/error.tpl')
	 );
	 $template->add_vars(array(
	                    'L_ERROR' => $lang['error'],
			    'DISPLAY' => $lang['error_mess27'])
	 );
	 $template->parse("error");
	 include("footer.php");
	 exit();
      } else {
         include("header.php");
	 $link = "lang.php";
	 $template->getFile(array(
	                    'success' => 'admin/success.tpl')
	 );
	 $template->add_vars(array(
	                    'L_SUCCESS' => $lang['success'],
			    'DISPLAY' => $lang['success_mess11'],
			    'LINK' => $link)
	 );
	 $template->parse("success");
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