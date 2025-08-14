<?php
/****************************************************************/
/*                         phpStatus                            */
/*                    edit_groups.php file                      */
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
   if($_COOKIE['user_level'] == '1') {
      $sql = "SELECT * FROM ".$prefix."_groups WHERE id='$HTTP_GET_VARS[id]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $groupid = $row['id'];
            $groupname = $row['name'];
            $port = $row['ports'];

            $port2 = explode(",", $port);

            $groupname2 = "<input type=\"text\" name=\"name\" id=\"name\" value=\"$groupname\">";
            $hidden = "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"$groupid\">";
      }

      $sql = "SELECT * FROM ".$prefix."_ports";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            if(!isset($portid)) {
               $portid = array($row['id']);
            } else {
               array_push($portid,$row['id']);
            }
      }

      $check = array_intersect_assoc($port2, $portid);

      $sql = "SELECT * FROM ".$prefix."_ports";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $id = $row['id'];
            $name = $row['name'];

            if($check) {
               $port4 = "checked=checked";
            } else {
               $port4 = "";
            }

            $template->add_block_vars("ports", array(
                                       'ID' => $id,
                                       'NAME' => $name,
                                       'CHECK' => $port4)
            );
      }

      include("header.php");
      $template->getFile(array(
                         'edit_group' => 'admin/edit_group.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_EDITGROUP' => $lang['edit_group'],
			 'L_GROUPNAME' => $lang['group_name'],
			 'L_SERVICES' => $lang['services'],
                         'NAME' => $groupname2,
                         'HIDDEN' => $hidden)
      );
      $template->parse("edit_group");
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