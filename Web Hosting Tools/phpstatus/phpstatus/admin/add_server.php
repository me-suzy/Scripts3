<?php
/****************************************************************/
/*                         phpStatus                            */
/*                    add_server.php file                       */
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

if($HTTP_COOKIE_VARS['loged'] == 'yes') {
   if($HTTP_COOKIE_VARS['user_level'] == '1') {
      if((!$HTTP_POST_VARS['name']) || (!$HTTP_POST_VARS['ip'])) {
          $display = $lang['error_mess12'] . "<br>";
          if(!$HTTP_POST_VARS['name']) {
             $display .= $lang['server_name'] . "<br>";
          }
          if(!$HTTP_POST_VARS['ip']) {
             $display .= $lang['server_ip'] . "<br>";
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

      $sql = "SELECT name FROM ".$prefix."_servers WHERE name='$HTTP_POST_VARS[name]'";
      $result = $db->query($sql);

      $sql2 = "SELECT ip FROM ".$prefix."_servers WHERE ip='$HTTP_POST_VARS[ip]'";
      $result2 = $db->query($sql2);

      $num = $db->num($result);
      $num2 = $db->num($result2);

      if(($num > 0) || ($num2 > 0)) {
          $display = $lang['error_mess13'] . "<br>";
          if($num > 0) {
             $display .= $lang['error_mess19'] . "<br>";
             unset($HTTP_POST_VARS['name']);
          }
          if($num2 > 0) {
             $display .= $lang['error_mess20'] . "<br>";
             unset($HTTP_POST_VARS['ip']);
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

      $sql = "INSERT INTO ".$prefix."_servers (name, ip, groupid) VALUES ('$HTTP_POST_VARS[name]', '$HTTP_POST_VARS[ip]', '$HTTP_POST_VARS[group]')";
      $result = $db->query($sql);

      if(!$result) {
         include("header.php");
         $template->getFile(array(
                            'error' => 'admin/error.tpl')
         );
         $template->add_vars(array(
	                    'L_ERROR' => $lang['error'],
                            'DISPLAY' => $lang['error_mess21'])
         );
         $template->parse("error");
         include("footer.php");
         exit();
      } else {
         include("header.php");
         $link = "servers.php";
         $template->getFile(array(
                            'success' => 'admin/success.tpl')
         );
         $template->add_vars(array(
	                    'L_SUCCESS' => $lang['success'],
                            'DISPLAY' => $lang['success_mess7'],
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
                      'DISPLAY' => $lang['error_6'])
   );
   $template->parse("error");
   include("footer.php");
   exit();
}
?>