<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: pict.php
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
require './view/gdb.php';
require './view/vvis.php';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('pict.php||constructor of config class has failed');
  $err->log_out();
  exit;
}

//database
$gdb = & new gdb;
if($err->flag) {
  $err->reason('pict.php||constructor of gdb class has failed');
  $err->log_out();
  exit;
}

//visualisation
$vis = & new vvis($rf);
if($err->flag) {
  $err->reason('pict.php||constructor of vvis class has failed');
  $err->log_out();
  exit;
}

//get ID of graph's data
if(isset($_GET['gid'])) $gid=$_GET['gid'];
elseif(isset($HTTP_GET_VARS['gid'])) $gid=$HTTP_GET_VARS['gid'];
else {
  $err->reason('pict.php||can\'t get ID of graph\'s data');
  $err->log_out();
  exit;
}

//get ID of graph's data
if(isset($_GET['act'])) $act=$_GET['act'];
elseif(isset($HTTP_GET_VARS['act'])) $act=$HTTP_GET_VARS['act'];
else {
  $err->reason('pict.php||can\'t get action for graph\'s data');
  $err->log_out();
  exit;
}

//get statistics parameter
if(isset($_GET['stat'])) $stat=$_GET['stat'];
elseif(isset($HTTP_GET_VARS['stat'])) $stat=$HTTP_GET_VARS['stat'];
else {
  $err->reason('pict.php||can\'t get statistics parameter for graph\'s data');
  $err->log_out();
  exit;
}

//get graph type
if(isset($_GET['type'])) $type=$_GET['type'];
elseif(isset($HTTP_GET_VARS['type'])) $type=$HTTP_GET_VARS['type'];
else {
  $err->reason('pict.php||can\'t get type of graph image');
  $err->log_out();
  exit;
}

db_init();
if($err->flag) {
  $err->reason('pict.php||initialization of database has failed');
  $err->log_out();
  exit;
}

//image
$vis->img($gid,$stat,$type,$act);
if($err->flag) {
  $err->reason('pict.php||function \'img\' has failed');
  $err->log_out();
  exit;
}

db_close();
if($err->flag) {
  $err->reason('pict.php||closing of connection with database has failed');
  $err->log_out();
  exit;
}

?>