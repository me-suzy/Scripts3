<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: aa.php
// Source: http://www.actualscripts.com/
// Copyright: (c) 2002-2004 ActualScripts, Company. All rights reserved.
//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.
// SEE LICENSE AGREEMENT FOR MORE DETAILS
/*------------------------------------------------------------------------*/

ignore_user_abort(true);

//p3p header
require './data/p3pheader.php';

//root folder of Actualanalyzer
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';
require './common/confdb.php';
require './count/cdb.php';
require './count/cvis.php';
require './count/count.php';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('aa.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_pic();
  exit;
}

//database initialisation
db_init();
if($err->flag) {
  $err->reason('aa.php||can\'t init database');
  $err->log_out();
  $err->scr_pic();
  exit;
}

//config in database
$configdb = & new confdb;
if($err->flag) {
  $err->reason('aa.php||constructor of confdb class has failed');
  $err->log_out();
  $err->scr_pic();
  exit;
}

$cdb = & new cdb;
if($err->flag) {
  $err->reason('aa.php||constructor of cdb class has failed');
  $err->log_out();
  $err->scr_pic();
  exit;
}

//visualisation
$cvis = & new cvis;
if($err->flag) {
  $err->reason('aa.php||constructor of cvis class has failed');
  $err->log_out();
  $err->scr_pic();
  exit;
}

//counting
$count = & new count;
if($err->flag) {
  $err->reason('aa.php||constructor of count class has failed');
  $err->log_out();
  $err->scr_pic();
  exit;
}

//database closing
db_close();
if($err->flag) {
  $err->reason('aa.php||can\'t close connection with database');
  $err->log_out();
  $err->scr_pic();
  exit;
}

?>