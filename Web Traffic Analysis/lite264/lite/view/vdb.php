<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: vdb.php
// Source: http://www.actualscripts.com/
// Copyright: (c) 2002-2004 ActualScripts, Company. All rights reserved.
//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.
// SEE LICENSE AGREEMENT FOR MORE DETAILS
/*------------------------------------------------------------------------*/

class vdb {

//===================================================================
function getgrs() {
  global $conf,$err;

  if($conf->locktab) {
    $request='LOCK TABLES aa_groups READ';
    $result=mysql_query($request,$conf->link);
    if(!$result) {$err->reason('vdb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  $mas=array();
  $request='SELECT name,id FROM aa_groups WHERE id=201';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) { mysql_free_result($result); return $mas; }
  $row=mysql_fetch_object($result);
  $mas['201']=$row->name;
  mysql_free_result($result);

  $request='SELECT id,name,added FROM aa_groups WHERE added!=0 AND id!=201 ORDER BY name ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) $mas[$row->id]=$row->name;
  mysql_free_result($result);

  if($conf->locktab) {
    $request='UNLOCK TABLES';
    $resultu=mysql_query($request,$conf->link);
    if(!$resultu) {$err->reason('vdb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  return $mas;
}//function getgrs
//===================================================================
function getpages() {
  global $conf,$err;

  $mas=array();
  $request='SELECT id,name,added FROM aa_pages WHERE added!=0 ORDER BY name ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) $mas[$row->id]=$row->name;
  mysql_free_result($result);

  return $mas;
}//function getpages
//===================================================================
function getnamegrpg($page_id,&$name,&$url) {
  global $conf,$err;

  if($page_id<201) { $table='aa_pages'; $u=',url'; }
  else { $table='aa_groups'; $u=''; }

  $request='SELECT name,id'.$u.' FROM '.$table.' WHERE id='.$page_id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|getnamegrpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) { mysql_free_result($result); $err->reason('vdb.php|getnamegrpg|the page with id='.$page_id.' is not found');return ''; }
  $row=mysql_fetch_object($result);
  mysql_free_result($result);
  $name=$row->name;
  if($page_id<201) $url=$row->url;
  else $url='';
}//function getnamegrpg
//===================================================================
function vis_all($page_id,&$vars,$name,$url) {
  global $err,$conf;
  require './view/vdb/vis_all.php';
}
//===================================================================
function vis_tim($page_id,&$vars,$name,$url,$sort,$tint,$year) {
  global $err,$conf;
  require './view/vdb/vis_tim.php';
}
//===================================================================
function ref($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,$flag) {
  global $err,$conf;
  require './view/vdb/ref.php';
}
//===================================================================
function vis_grpg($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) { //page_id=221-all by groups,other-group by pages
  global $err,$conf;
  require './view/vdb/vis_grpg.php';
}
//===================================================================
function opttabs() {
  global $err,$conf;

  //for analyzer
  $request='SHOW TABLE STATUS LIKE "aa%"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|opttabs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) {
    if($row->Data_free>10000) {
      $request='OPTIMIZE TABLE '.$row->Name;
      $resulto=mysql_query($request,$conf->link);
      if(!$resulto) {$err->reason('vdb.php|opttabs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
    }
  }
  mysql_free_result($result);

  $module=array();
  if(isset($conf->aa_mod)) {
      $tmp=split('\|',$conf->aa_mod);
      for($i=0;$i<sizeof($tmp);$i++) $module[$tmp[$i]]=1;
  }
}

}

?>