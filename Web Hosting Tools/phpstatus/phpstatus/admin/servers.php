<?php
/****************************************************************/
/*                         phpStatus                            */
/*                      servers.php file                        */
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
      $sql = "SELECT * FROM ".$prefix."_servers ORDER BY 'name' ASC";
      $result = $db->query($sql) or die(mysql_error());

      while($row = $db->fetch($result)) {
            $id = $row['id'];
            $name = $row['name'];
            $ip = $row['ip'];

            $template->add_block_vars("servers", array(
                                      'ID' => $id,
                                      'NAME' => $name,
                                      'IP' => $ip)
            );
      }

      $sql = "SELECT * FROM ".$prefix."_groups";
      $result = $db->query($sql) or die(mysql_error());

      while($row = $db->fetch($result)) {
            $groupid = $row['id'];
            $groupname = $row['name'];

            $template->add_block_vars("groups", array(
                                       'ID' => $groupid,
                                       'NAME' => $groupname)
            );
      }

      include("header.php");
      $template->getFile(array(
                         'servers' => 'admin/servers.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_SERVERS' => $lang['servers'],
			 'L_ADDSERVER' => $lang['add_server'],
			 'L_SERVERID' => $lang['server_id'],
			 'L_SERVERIP' => $lang['server_ip'],
			 'L_SERVERNAME' => $lang['server_name'],
			 'L_ACTION' => $lang['action'],
			 'L_GROUP' => $lang['group'])
      );
      $template->parse("servers");
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