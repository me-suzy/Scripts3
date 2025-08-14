<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: uninst.php
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
require './common/auth.php';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('uninst.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//authentication
$login = & new auth($rf,'uninst',_UNINSTALL);
if($err->flag) {
  $err->reason('uninst.php||constructor of auth class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//delete all tables
deltables();
if($err->flag) {
  $err->reason('uninst.php||can\'t remove tables from database');
  $err->log_out();
  $err->scr_out();
  exit;
}

//delete all files
delfiles();
if($err->flag) {
  $err->reason('uninst.php||can\'t remove files');
  $err->log_out();
  $err->scr_out();
  exit;
}

echo 'The uninstallation is completed successfully, now you can remove all scripts of the ActualAnalyzer.';
exit;

//=================================================================== FOR uninst
function deltables() {
  global $err,$conf;

  $conf->link=@mysql_connect($conf->dbhost,$conf->dbuser,$conf->dbpass);
  if(!$conf->link) {$err->reason('uninst.php|deltables|connection with mysql server has failed');return;}
  $rez=mysql_select_db($conf->dbase);
  if(!$rez) {$err->reason('uninst.php|deltables|the request \'use '.$conf->dbase.'\' has failed -- '.mysql_error());return;}


  $request='SHOW TABLES LIKE "aa%"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('uninst.php|deltables|the request \'show tables\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) return;

  $tables=array();
  while($row=mysql_fetch_row($result)) $tables[$row[0]]=0;
  mysql_free_result($result);
  if(isset($tables['aa_groups'])) {
      $request='DROP TABLE aa_groups';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_groups\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_pages'])) {
      $request='DROP TABLE aa_pages';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_pages\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_hosts'])) {
      $request='DROP TABLE aa_hosts';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_hosts\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_hours'])) {
      $request='DROP TABLE aa_hours';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_hours\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_days'])) {
      $request='DROP TABLE aa_days';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_days\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_total'])) {
      $request='DROP TABLE aa_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_ref_base'])) {
      $request='DROP TABLE aa_ref_base';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_ref_base\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_ref_total'])) {
      $request='DROP TABLE aa_ref_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_ref_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_domains'])) {
      $request='DROP TABLE aa_domains';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_domains\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_tmp'])) {
      $request='DROP TABLE aa_tmp';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_tmp\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_rdata'])) {
      $request='DROP TABLE aa_rdata';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_rdata\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_confdb'])) {
      $request='DROP TABLE aa_confdb';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_confdb\' has failed -- '.mysql_error());return;}
  }

  if($conf->link) {
    $rez=mysql_close($conf->link);
    if(!$rez) {$err->reason('uninst.php|deltables|closing of connection with mysql server has failed');return;}
  }
}

//delete files
/*----------------------------------------------------------*/
function delfiles() {
  global $err;

  if(file_exists('./cdata.php')) {
    $rez=unlink('./cdata.php');
    if(!$rez) {$err->reason('uninst.php|delfiles|can\'t delete file cdata.php');return;}
  }

  if(file_exists('./cdata_bak.php')) {
    $rez=unlink('./cdata_bak.php');
    if(!$rez) {$err->reason('uninst.php|delfiles|can\'t delete file cdata_bak.php');return;}
  }

  if(file_exists('./errsold.php')) {
    $rez=unlink('./errsold.php');
    if(!$rez) {$err->reason('uninst.php|delfiles|can\'t delete file errsold.php');return;}
  }

  if(file_exists('./errors.php')) {
    $rez=unlink('./errors.php');
    if(!$rez) {$err->reason('uninst.php|delfiles|can\'t delete file errors.php');return;}
  }
}

?>