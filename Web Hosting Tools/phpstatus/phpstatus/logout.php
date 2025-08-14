<?php
/****************************************************************/
/*                         phpStatus                            */
/*                       logout.php file                        */
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

define("PHPSTATUS_REAL_PATH","./");
include(PHPSTATUS_REAL_PATH . 'common.php');

if(!isset($HTTP_GET_VARS['logmeout'])) {
   include(PHPSTATUS_REAL_PATH . 'includes/header.php');
   $template->getFile(array(
                      'logout' => 'logout.tpl')
   );
   $template->add_vars(array(
                     'L_LOGOUT' => $lang['logout'],
		     'L_LOGOUTMESS' => $lang['logout_mess'])
   );
   $template->parse("logout");
   include(PHPSTATUS_REAL_PATH . 'includes/footer.php');
} else {
   setcookie("loged","no",time()-3600,$script_path);
   setcookie("username","",time()-3600,$script_path);
   setcookie("user_level","",time()-3600,$script_path);
   $session = uniqid("logout_");
   if(!session_is_registered('user_level')) {
      include(PHPSTATUS_REAL_PATH . "includes/header.php");
      $link = "index.php";
      $template->getFile(array(
                         'success' => 'success.tpl')
      );
      $template->add_vars(array(
                         'L_SUCCESS' => $lang['success'],
			 'DISPLAY' => $lang['success_mess17'],
			 'LINK' => $link)
      );
      $template->parse("success");
      include(PHPSTATUS_REAL_PATH . 'includes/footer.php');
      exit();
   }
}
?>