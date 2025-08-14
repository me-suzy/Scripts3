<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: crtables.php
// Source: http://www.actualscripts.com/
// Copyright: (c) 2002-2004 ActualScripts, Company. All rights reserved.
//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.
// SEE LICENSE AGREEMENT FOR MORE DETAILS
/*------------------------------------------------------------------------*/

  $this->link=mysql_connect($this->dbhost,$this->dbuser,$this->dbpass);
  if(!$this->link) {$err->reason('crtables.php|crtables|connection with mysql server has failed');return;}
  $rez=mysql_select_db($this->dbase);
  if(!$rez) {$err->reason('crtables.php|crtables|the request \'use '.$this->dbase.'\' has failed -- '.mysql_error());return;}

  $request='SHOW TABLES LIKE "aa_%"';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('crtables.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $tables=array();
  while($row=mysql_fetch_row($result)) $tables[$row[0]]=0;
  mysql_free_result($result);

  require $this->rf.'common/config/create.php';

  // LITE
  if(!isset($tables['aa_groups'])) {
      newgr($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newgr\' function has failed');return;}
  }
  if(!isset($tables['aa_pages'])) {
      newpg($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newpg\' function has failed');return;}
  }
  if(!isset($tables['aa_hosts'])) {
      newip($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newip\' function has failed');return;}
  }
  if(!isset($tables['aa_hours'])) {
      newhours($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newhours\' function has failed');return;}
  }
  if(!isset($tables['aa_days'])) {
      newdays($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newdays\' function has failed');return;}
  }
  if(!isset($tables['aa_total'])) {
      newtotal($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newtotal\' function has failed');return;}
  }
  if(!isset($tables['aa_ref_base'])) {
      newrb($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newrb\' function has failed');return;}
  }
  if(!isset($tables['aa_domains'])) {
      newdm($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newdm\' function has failed');return;}
  }
  if(!isset($tables['aa_ref_total'])) {
      newrt($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newrt\' function has failed');return;}
  }
  if(!isset($tables['aa_tmp'])) {
      newtmp($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newtmp\' function has failed');return;}
  }
  if(!isset($tables['aa_rdata'])) {
      newrdata($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newrdata\' function has failed');return;}
  }
  if(!isset($tables['aa_confdb'])) {
      newconfdb($this->link);
      if($err->flag) {$err->reason('crtables.php|crtables|\'newconfdb\' function has failed');return;}
  }

  if($this->locktab) {
    $request='LOCK TABLES aa_groups WRITE';
    $result=mysql_query($request,$this->link);
    if(!$result) {$err->reason('crtables.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  $request='SELECT * FROM aa_groups WHERE id=201';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('crtables.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  if(!mysql_num_rows($result)) {
      mysql_free_result($result);
      $request='INSERT INTO aa_groups (id,flags1,flags2,flags3,flags4,flags5,flags6,flags7,name,added,vmin,vmax,hsmin,hsmax,htmin,htmax,rmin,rmax,first_t,last_t) VALUES (201,0,0,0,0,0,0,0,"'._ALLPGS.'",'.$this->ctime.',0,0,0,0,0,0,0,0,0,0)';
      $result=mysql_query($request,$this->link);
      if(!$result) {$err->reason('crtables.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  else mysql_free_result($result);

  if($this->locktab) {
    $request='UNLOCK TABLES';
    $resultu=mysql_query($request,$this->link);
    if(!$resultu) {$err->reason('crtables.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  if($this->link) {
    $rez=mysql_close($this->link);
    if(!$rez) {$err->reason('crtables.php|crtables|disconnect with mysql server has failed');return;}
  }

?>