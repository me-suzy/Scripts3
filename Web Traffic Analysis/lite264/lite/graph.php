<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: graph.php
// Source: http://www.actualscripts.com/
// Copyright: (c) 2002-2004 ActualScripts, Company. All rights reserved.
//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.
// SEE LICENSE AGREEMENT FOR MORE DETAILS
/*------------------------------------------------------------------------*/

//p3p header
require './data/p3pheader.php';

//root folder of Actualanalyzer
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';
require './common/confdb.php';
require './common/auth.php';
require './view/gdb.php';
require './view/vvis.php';

//HTML code accumulator
$pagehtml='';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('graph.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//database initialisation
db_init();
if($err->flag) {
  $err->reason('graph.php||can\'t init database');
  $err->log_out();
  $err->scr_out();
  exit;
}

//config in database
$configdb = & new confdb;
if($err->flag) {
  $err->reason('graph.php||constructor of confdb class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//check the entrance
$pref='';
if(isset($_POST['graph'])) $fdata=$_POST['graph'];
elseif(isset($HTTP_POST_VARS['graph'])) $fdata=$HTTP_POST_VARS['graph'];
else $fdata='';
if(empty($fdata)) {
  Header('Location: ./view.php');
  exit;
}
else {
  $tarr = preg_split("/\=/",$fdata);
  if(isset($tarr[4])) $pref=$tarr[4];
}

//authentication
$login = & new auth($rf,'view',_VIEWAREA);
if($err->flag) {
  $err->reason('graph.php||constructor of auth class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//database
$gdb = & new gdb;
if($err->flag) {
  $err->reason('graph.php||constructor of gdb class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//visualisation
$vis = & new vvis($rf);
if($err->flag) {
  $err->reason('graph.php||constructor of vvis class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//show
$vis->show();
if($err->flag) {
  $err->reason('graph.php||function \'show\' has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//database closing
db_close();
if($err->flag) {
  $err->reason('graph.php||can\'t close connection with database');
  $err->log_out();
  $err->scr_out();
  exit;
}

//output HTML page
out();

?>