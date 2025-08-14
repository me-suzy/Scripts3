<?php
/****************************************************************/
/*                         phpStatus                            */
/*                    edit_server.php file                      */
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
      $sql = "SELECT * FROM ".$prefix."_servers WHERE id='$HTTP_GET_VARS[id]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $id = $row['id'];
            $name = $row['name'];
            $ip = $row['ip'];
            $groupid = $row['groupid'];

            $hidden = "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"$id\">";
            $name2 = "<input type=\"text\" name=\"name\" value=\"$name\">";
            $ip2 = "<input type=\"text\" name=\"ip\" id=\"ip\" value=\"$ip\">";
      }

      $sql2 = "SELECT * FROM ".$prefix."_groups";
      $result2 = $db->query($sql2);

      while($row2 = $db->fetch($result2)) {
            $id2 = $row2['id'];
            $name3 = $row2['name'];

            if($groupid == $id2) {
               $selected = "selected";
            } else {
               $selected = "";
            }

            $template->add_block_vars("groups", array(
                                       'ID' => $id2,
                                       'NAME' => $name3,
                                       'SELECT' => $selected)
            );
      }

      include("header.php");
      $template->getFile(array(
                         'edit_server' => 'admin/edit_server.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_EDITSERVER' => $lang['edit_server'],
			 'L_SERVERNAME' => $lang['server_name'],
			 'L_SERVERIP' => $lang['server_ip'],
			 'L_GROUP' => $lang['group'],
                         'HIDDEN' => $hidden,
                         'NAME' => $name2,
                         'IP' => $ip2)
      );
      $template->parse("edit_server");
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