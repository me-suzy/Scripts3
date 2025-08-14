<?php
/****************************************************************/
/*                         phpStatus                            */
/*                    edit_service.php file                     */
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
      $sql = "SELECT * FROM ".$prefix."_ports WHERE id='$HTTP_GET_VARS[id]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $portid = $row['id'];
            $portname = $row['name'];

            $portid2 = "<input type=\"text\" name=\"port\" id=\"port\" value=\"$portid\">";
            $portname2 = "<input type=\"text\" name=\"name\" id=\"name\" value=\"$portname\">";
            $hidden = "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"$portid\">";
      }

      include("header.php");
      $template->getFile(array(
                         'edit_service' => 'admin/edit_service.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_EDITSERVICE' => $lang['edit_service'],
			 'L_PORTNAME' => $lang['port_name'],
			 'L_PORTNUMBER' => $lang['port_number'],
                         'ID' => $portid2,
                         'NAME' => $portname2,
                         'HIDDEN' => $hidden)
      );
      $template->parse("edit_service");
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