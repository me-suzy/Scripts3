<?php
/****************************************************************/
/*                         phpStatus                            */
/*                       groups.php file                        */
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
      $sql = "SELECT * FROM ".$prefix."_groups";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $groupid = $row['id'];
            $groupname = $row['name'];

            $template->add_block_vars("groups", array(
                                       'ID' => $groupid,
                                       'NAME' => $groupname)
            );
      }

      $sql = "SELECT * FROM ".$prefix."_ports";
      $result = $db->query($sql);

      $ports = "";
      while($row = $db->fetch($result)) {
            $portid = $row['id'];
            $portname = $row['name'];

            $ports .= "<input type=\"checkbox\" name=\"ports[]\" value=\"$portid\"> <font class=\"text\">$portname</font>";
      }

      include("header.php");
      $template->getFile(array(
                         'groups' => 'admin/groups.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_GROUPS' => $lang['groups'],
			 'L_ADDGROUP' => $lang['add_group'],
			 'L_GROUPNAME' => $lang['group_name'],
			 'L_GROUPID' => $lang['group_id'],
			 'L_ACTION' => $lang['action'],
			 'L_SERVICES' => $lang['services'],
                         'PORTS' => $ports)
      );
      $template->parse("groups");
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