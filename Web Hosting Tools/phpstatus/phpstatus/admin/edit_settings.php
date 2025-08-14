<?php
/****************************************************************/
/*                         phpStatus                            */
/*                    edit_settings.php file                    */
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
      if((!$HTTP_POST_VARS['site_title']) || (!$HTTP_POST_VARS['domain']) || (!$HTTP_POST_VARS['script_path'])) {
         $display = $lang['error_mess12'] . "<br>";
	 if(!$HTTP_POST_VARS['site_title']) {
	    $display .= $lang['site_title'] . "<br>";
	 }
	 if(!$HTTP_POST_VARS['domain']) {
	    $display .= $lang['domain'] . "<br>";
	 }
	 if(!$HTTP_POST_VARS['script_path']) {
	    $display .= $lang['script_path'] . "<br>";
	 }
	 include("header.php");
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
      
      $sql = "UPDATE ".$prefix."_config SET site_title='$HTTP_POST_VARS[site_title]', domain='$HTTP_POST_VARS[domain]', script_path='$HTTP_POST_VARS[script_path]', default_lang='$HTTP_POST_VARS[default_lang]', default_theme='$HTTP_POST_VARS[default_theme]'";
      $result = $db->query($sql) or die(mysql_error());
      
      if(!$result) {
         include("header.php");
	 $template->getFile(array(
	                    'error' => 'admin/error.tpl')
	 );
	 $template->add_vars(array(
	                    'L_ERROR' => $lang['error'],
			    'DISPLAY' => $lang['error_mess24'])
	 );
	 $template->parse("error");
	 include("footer.php");
	 exit();
      } else {
         include("header.php");
	 $link = "index.php";
	 $template->getFile(array(
	                    'success' => 'admin/success.tpl')
	 );
	 $template->add_vars(array(
	                    'L_SUCCESS' => $lang['success'],
			    'DISPLAY' => $lang['success_mess10'],
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