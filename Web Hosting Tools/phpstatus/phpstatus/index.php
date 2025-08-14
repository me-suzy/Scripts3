<?php
/****************************************************************/
/*                         phpStatus                            */
/*                        index.php file                        */
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
include(PHPSTATUS_REAL_PATH . 'common.php');

$install = @opendir(PHPSTATUS_REAL_PATH . 'install/');

if($install) {
   include(PHPSTATUS_REAL_PATH . 'includes/header.php');
   $display = "Please make sure the install directory is deleted and that config.php is chmodded back to 666.";
   $template->getFile(array(
                     'error' => 'error.tpl')
   );
   $template->add_vars(array(
                     'L_ERROR' => $lang['error'],
		     'DISPLAY' => $display)
   );
   $template->parse("error");
   include(PHPSTATUS_REAL_PATH . 'includes/footer.php');
   exit();
}

$sql = "SELECT * FROM ".$prefix."_ports";
$getports = $db->query($sql);
while($row2 = $db->fetch($getprots)) {
      $portnames[$row2['id']]['name'] = $row2['name'];
}

$sql = "SELECT * FROM ".$prefix."_groups";
$result = $db->query($sql) or die(mysql_error());

$group_rows = array();
while($group_rows[] = $db->fetch($result));

if(($total_groups = count($group_rows))) {

    if(!($total_ports = count($portnames))) {
       include(PHPSTATUS_REAL_PATH . 'includes/header.php');
       $template->getFile(array(
                          'error' => 'error.tpl')
       );
       $template->add_vars(array(
                          'L_ERROR' => $lang['error'],
			  'DISPLAY' => $lang['error_mess3'])
       );
       $template->parse("error");
       include(PHPSTATUS_REAL_PATH . 'includes/footer.php');
       exit();
    }

    $sql = "SELECT * FROM ".$prefix."_servers";
    $result = $db->query($sql) or die(mysql_error());

    $server_data = array();

    while($row = $db->fetch($result)) {
          $server_data[] = $row;
    }

    if(!($total_servers = count($server_data))) {
       include(PHPSTATUS_REAL_PATH . 'includes/header.php');
       $template->getFile(array(
                          'error' => 'error.tpl')
       );
       $template->add_vars(array(
                          'L_ERROR' => $lang['error'],
			  'DISPLAY' => $lang['error_mess4'])
       );
       $template->parse("error");
       include(PHPSTATUS_REAL_PATH . 'includes/footer.php');
       exit();
    }
    
    if($HTTP_COOKIE_VARS['loged'] == "yes") {
       if($HTTP_COOKIE_VARS['user_level'] == '1') {
          $admin = "<a href=\"admin/index.php\">Admin CP</a>";
       } else {
          $admin = "";
       }
    }       

    include(PHPSTATUS_REAL_PATH . 'includes/header.php');
    $template->getFile(array(
                       'index' => 'index.tpl')
    );
    $template->add_vars(array(
                       'L_SERVER' => $lang['server'],
		       'L_ONLINE' => $lang['online'],
		       'L_OFFLINE' => $lang['offline'],
		       'ADMIN' => $admin)
    );

    for($i = 0; $i < $total_groups; $i++) {
        $groupid = $group_rows[$i]['id'];

        $display_servers = false;
        for($j = 0; $j < $total_servers; $j++) {
            if($server_data[$j]['groupid'] == $groupid) {
               $display_servers = true;
            }
        }

        if($display_servers) {
           $template->add_block_vars("groups", array(
                                      'NAME' => $group_rows[$i]['name'])
           );

           for($j = 0; $j < $total_servers; $j++) {
               if($server_data[$j]['groupid'] == $groupid) {
                  $ports = preg_split("/[,]/",$group_rows[$i]['ports']);
                  sort($ports);
 
                  foreach($ports AS $thisport) {
                          
                          $server_data[$j]['port'] .= "<td align=\"center\" class=\"block-header\"><font class=\"header\">" . $portnames[$thisport]['name'] . "</font></td>";
                          $tcpconn = @fsockopen($server_data[$j]['ip'],$thisport,$errno,$errstr, "3");
                          if($tcpconn) {
                             $server_data[$j]['stats'] .= "<td align=\"center\"><img src=\"templates/" . $default_theme . "/images/online.gif\" border=\"0\" alt=\"Up\" title=\"Up\"></td>";
                             fclose($tcpconn);
                          } else {
                             $server_data[$j]['stats'] .= "<td align=\"center\"><img src=\"templates/" . $default_theme . "/images/offline.gif\" border=\"0\" alt=\"Down\" title=\"Down\"></td>";
                          }

                          
                          
                  
                  }
                  
                  $server_id = $server_data[$j]['id'];

                  $template->add_block_vars("groups.servers", array(
                                             'PORTS' => $server_data[$j]['port'],
                                             'NAME2' => $server_data[$j]['name'],
                                             'NAME3' => strtolower($server_data[$j]['name']),
                                             'IP' => $server_data[$j]['ip'],
                                             'STATS' => $server_data[$j]['stats'])
                  );
               }
           }
        }
    }
}
    


$template->parse("index");
include(PHPSTATUS_REAL_PATH . 'includes/footer.php');
?>