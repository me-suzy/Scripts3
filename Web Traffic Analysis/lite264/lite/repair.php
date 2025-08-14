<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: repair.php
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

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('repair.php||constructor of config class is fail');
  $err->log_out();
  $err->scr_out();
  exit;
}

//database initialisation
db_init();
if($err->flag) {
  $err->reason('repair.php||can\'t init database');
  $err->log_out();
  $err->scr_out();
  exit;
}

//config in database
$configdb = & new confdb;
if($err->flag) {
  $err->reason('repair.php||constructor of confdb class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//authentication
$login = & new auth($rf,'repair',_UPDATE);
if($err->flag) {
  $err->reason('repair.php||constructor of auth class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

repair();
if($err->flag) {
  $err->reason('repair.php||can\'t change structure of database');
  $err->log_out();
  $err->scr_out();
  exit;
}

echo 'Repairing is successfully completed.';
exit;

//===================================================================
function repair() {
  global $err,$conf,$lconf,$module;

  //for analyzer
  $reinst=false;
  $request='SHOW TABLE STATUS LIKE "aa%"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('repair.php|repair|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) {
    $request='CHECK TABLE '.$row->Name;
    $resultc=mysql_query($request,$conf->link);
    if(!$resultc) {$err->reason('repair.php|repair|the request \''.$request.'\' has failed -- '.mysql_error());return;}
    if($rowc=mysql_fetch_object($resultc)) {
      if(strcasecmp($rowc->Msg_type,'status')||strcasecmp($rowc->Msg_text,'OK')) {
        echo 'Trying to repair: '.$row->Name;
        //repair
        $request='REPAIR TABLE '.$row->Name;
        $resultr=mysql_query($request,$conf->link);
        if(!$resultr) {$err->reason('repair.php|repair|the request \''.$request.'\' has failed -- '.mysql_error());return;}
        if($rowr=mysql_fetch_object($resultr)) {
          if(strcasecmp($rowr->Msg_type,'status')||strcasecmp($rowr->Msg_text,'OK')) {
            echo ' -> Fail'."\n<br>";
            $tbl=$row->Name;
            echo 'Removing: '.$tbl;
            $request='DROP TABLE '.$tbl;
            $resultd=mysql_query($request,$conf->link);
            if(!$resultd) {$err->reason('repair.php|repair|the request \''.$request.'\' has failed -- '.mysql_error());return;}
            echo ' -> Ok'."\n<br>";
            if(strstr($tbl,'_base')) {
              $tblt=str_replace('_base','_total',$tbl);
              if(!strcmp($tbl,'aa_bros_base')) $tblt='aa_st_total';
              echo 'Removing: '.$tblt;
              $request='DROP TABLE '.$tblt;
              $resultd=mysql_query($request,$conf->link);
              if(!$resultd) {$err->reason('repair.php|repair|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              echo ' -> Ok'."\n<br>";
            }
            $reinst=true;
          }
          else echo ' -> Ok'."\n<br>";
        }
        else echo ' -> Fail'."\n<br>";
      }
    }
    mysql_free_result($resultc);
  }
  mysql_free_result($result);

  db_close();
  if($err->flag) {$err->reason('repair.php|repair|closing of database has failed');return;}

  if($reinst) {
    echo '-----'."\n<br>";
    $conf->crtables();
    if($err->flag) {$err->reason('repair.php|repair|can\'t reinstall tables for ActualAnalyzer');return;}
    echo 'Reinstallation of removed tables for ActualAnalyzer -> Ok'."\n<br>";
    echo '-----'."\n<br>";
  }

}

?>