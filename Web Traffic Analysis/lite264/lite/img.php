<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: img.php
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
require './count/cvis.php';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('img.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_pic();
  exit;
}

//visualisation
$cvis = & new cvis;
if($err->flag) {
  $err->reason('img.php||constructor of cvis class has failed');
  $err->log_out();
  $err->scr_pic();
  exit;
}

//get color and flag
if(isset($_GET['img'])) $img=$_GET['img'];
elseif(isset($HTTP_GET_VARS['img'])) $img=$HTTP_GET_VARS['img'];
else $img=2;
if(isset($_GET['color'])) $dcolor=$_GET['color'];
elseif(isset($HTTP_GET_VARS['color'])) $dcolor=$HTTP_GET_VARS['color'];
else $dcolor=0;
if(isset($_GET['flag'])) $dflag=$_GET['flag'];
elseif(isset($HTTP_GET_VARS['flag'])) $dflag=$HTTP_GET_VARS['flag'];
else $dflag=7;

//out button
if($img>100) {
  $cvis->out_digits($img,$dflag,123456789,12345,123,$dcolor);
  if($err->flag) {
    $err->reason('img.php||can not create button with digits');
    $err->log_out();
    $err->scr_pic();
    exit;
  }
}
else {
  $cvis->out_pic($img);
  if($err->flag) {
    $err->reason('img.php||can not create simple button');
    $err->log_out();
    $err->scr_pic();
    exit;
  }
}

?>