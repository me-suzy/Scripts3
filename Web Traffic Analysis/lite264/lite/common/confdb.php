<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: confdb.php
// Source: http://www.actualscripts.com/
// Copyright: (c) 2002-2004 ActualScripts, Company. All rights reserved.
//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.
// SEE LICENSE AGREEMENT FOR MORE DETAILS
/*------------------------------------------------------------------------*/

class confdb
{

var $pref;

function confdb() {
  global $err,$conf;

  $this->pref='aa_';

  //get variables
  $vars=array();
  $this->getparam($this->pref,$vars);
  if($err->flag) {$err->reason('confdb.php|confdb|can\'t receive variables');return;}

  if(empty($vars)) {
    $vars['aa_mod']='';
    $this->setparam($vars);
    if($err->flag) {$err->reason('confdb.php|confdb|can\'t save local variables');return;}
  }
  else {
    //add variables to config
    reset($vars);
    while($e=each($vars)) {
      $k=$e[0];
      $conf->$k=$vars[$k];
    }
  }
}

//===================================================================
function getparam($prefix,&$resmas) {
  global $conf,$err;

  $request='SELECT * FROM aa_confdb WHERE BINARY var REGEXP "^'.$prefix.'"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('confdb.php|getparam|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) {
      $resmas[trim($row->var)]=$row->val;
  }
  mysql_free_result($result);
}
//===================================================================
function setparam(&$varmas) {
  global $conf,$err;

  if($conf->locktab) {
    $request='LOCK TABLES aa_confdb WRITE';
    $result=mysql_query($request,$conf->link);
    if(!$result) {$err->reason('confdb.php|setparam|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  reset($varmas);
  while($e=each($varmas)) {
      $k=$e[0];
      $request='SELECT * FROM aa_confdb WHERE BINARY var="'.trim($k).'"';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('confdb.php|setparam|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($result)) {
          $request='UPDATE aa_confdb SET val="'.$varmas[$k].'" WHERE var="'.trim($k).'"';
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('confdb.php|setparam|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
      else {
          $request='INSERT INTO aa_confdb (var,val) VALUES ("'.$k.'","'.$varmas[$k].'")';
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('confdb.php|setparam|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
      mysql_free_result($result);
  }
  if($conf->locktab) {
    $request='UNLOCK TABLES';
    $result=mysql_query($request,$conf->link);
    if(!$result) {$err->reason('confdb.php|setparam|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
}
//===================================================================
function delparam($prefix) {
  global $conf,$err;

  $request='DELETE FROM aa_confdb WHERE BINARY var REGEXP "^'.$prefix.'"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('confdb.php|delparam|the request \''.$request.'\' has failed -- '.mysql_error());return;}
}
//===================================================================

}
?>