<?php
/****************************************************************/
/*                         phpStatus                            */
/*                        check.php file                        */
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

define("PHPSTATUS_REAL_PATH", "./");
include(PHPSTATUS_REAL_PATH . "common.php");

if((!$HTTP_POST_VARS['username']) || (!$HTTP_POST_VARS['password'])) {
   include(PHPSTATUS_REAL_PATH . 'includes/header.php');
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                       'L_ERROR' => $lang['error'],
		       'DISPLAY' => $lang['error_mess1'])
   );
   $template->parse("error");
   include(PHPSTATUS_REAL_PATH . 'includes/footer.php');
   exit();
}

$password = md5($HTTP_POST_VARS['password']);

$sql = "SELECT * FROM ".$prefix."_admin WHERE username='$HTTP_POST_VARS[username]' AND password='$password'";
$result = $db->query($sql);

$num = $db->num($result);

if($num > 0) {
   while($row = $db->fetch($result)) {
      foreach($row AS $key => $val) {
         $$key = stripslashes($val);
      }
      setcookie("loged","yes",time()+3600, $script_path);
      setcookie("username","$username",time()+3600,$script_path);
      setcookie("user_level","$user_level",time()+3600,$script_path); 
      $session = uniqid("login_");
      header("Location: admin/index.php");
   }
} else {
   include(PHPSTATUS_REAL_PATH . 'includes/header.php');
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                       'L_ERROR' => $lang['error'],
		       'DISPLAY' => $lang['error_mess2'])
   );
   $template->parse("error");
   include(PHPSTATUS_REAL_PATH . 'includes/footer.php');
   exit();
}
?>