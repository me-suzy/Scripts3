<?php
/****************************************************************/
/*                         phpStatus                            */
/*                       common.php file                        */
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

if(eregi("common.php", $_SERVER['PHP_SELF'])) {

   header("Location: index.php");

   exit();

}
include(PHPSTATUS_REAL_PATH . 'config.php');
include(PHPSTATUS_REAL_PATH . 'includes/db.php');
include(PHPSTATUS_REAL_PATH . 'includes/Template.php');
include(PHPSTATUS_REAL_PATH . 'includes/functions.php');

$db = new db("$dbhost", "$dbuser", "$dbpass", "$dbname");

$sql = "SELECT domain,script_path,default_lang,default_theme FROM ".$prefix."_config";
$result = $db->query($sql);

$row = $db->fetch($result);

$domain = $row['domain'];
$script_path = $row['scipt_path'];
$default_lang = $row['default_lang'];
$default_theme = $row['default_theme'];

if((!$default_theme) || (!$default_lang)) {
    $default_theme = "default";
    $default_lang = "english";
} else {
   $default_theme = $default_theme;
   $default_lang = $default_lang;
}

include(PHPSTATUS_REAL_PATH . 'language/lang_' . $default_lang . '.php');

$template = new Template(PHPSTATUS_REAL_PATH . 'templates/' . $default_theme . '/');

?>