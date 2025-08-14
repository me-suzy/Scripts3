<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: direct.php
// Source: http://www.actualscripts.com/
// Copyright: (c) 2002-2004 ActualScripts, Company. All rights reserved.
//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.
// SEE LICENSE AGREEMENT FOR MORE DETAILS
/*------------------------------------------------------------------------*/

if(!isset($rf)) $rf='./';

require $rf.'common/error.php';
require $rf.'common/global.php';
require $rf.'common/config.php';
require $rf.'common/dbaccess.php';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('direct.php||constructor of config class has failed');
  $err->log_out();
}

//database access
$dbaccess = & new dbaccess($rf);
if($err->flag) {
  $err->reason('direct.php||constructor of dbaccess class has failed');
  $err->log_out();
}

//get ID's of groups/pages
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($HTTP_GET_VARS['action'])) $action=$HTTP_GET_VARS['action'];
else $action='';
if(!strcmp($action,'getids')) {
  $dbaccess->getids();
  if($err->flag) {
    $err->reason('direct.php||can\'t get id\'s of groups/pages');
    $err->log_out();
  }
}

?>