<?php
/****************************************************************/
/*                         phpStatus                            */
/*                      services.php file                       */
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
      $sql = "SELECT * FROM ".$prefix."_ports";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $portid = $row['id'];
            $portname = $row['name'];
      
            $template->add_block_vars("ports", array(
                                       'ID' => $portid,
                                       'NAME' => $portname)
            );
      }

      include("header.php");
      $template->getFile(array(
                         'services' => 'admin/services.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_PORTID' => $lang['port_id'],
			 'L_PORTNAME' => $lang['port_name'],
			 'L_ACTION' => $lang['action'],
			 'L_ADDSERVICE' => $lang['add_service'],
			 'L_SERVICES' => $lang['services'],
			 'L_PORTNUMBER' => $lang['port_number'])
      );
      $template->parse("services");
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