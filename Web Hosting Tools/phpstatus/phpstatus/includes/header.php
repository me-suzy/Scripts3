<?php
/****************************************************************/
/*                         phpStatus                            */
/*                       header.php file                        */
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

$sql = "SELECT site_title FROM ".$prefix."_config";
$result = $db->query($sql);

$row = $db->fetch($result);
$site_title = $row['site_title'];

$template->getFile(array(
                   'header' => 'header.tpl')
);
$template->add_vars(array(
                   'SITE_TITLE' => $site_title)
);
$template->parse("header");
?>