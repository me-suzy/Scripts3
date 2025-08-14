<?php
/****************************************************************/
/*                         phpStatus                            */
/*                        login.php file                        */
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

if($HTTP_COOKIE_VARS['loged'] == "yes") {
   header("Location: admin/index.php");
   exit();
}

include(PHPSTATUS_REAL_PATH . 'includes/header.php');
$template->getFile(array(
                   'login' => 'login.tpl')
);
$template->add_vars(array(
                   'L_LOGIN' => $lang['login'],
		   'L_USERNAME' => $lang['username'],
		   'L_PASSWORD' => $lang['password'])
);
$template->parse("login");
include(PHPSTATUS_REAL_PATH . 'includes/footer.php');
?>   