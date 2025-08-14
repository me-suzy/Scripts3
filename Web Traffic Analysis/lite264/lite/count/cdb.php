<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: cdb.php
// Source: http://www.actualscripts.com/
// Copyright: (c) 2002-2004 ActualScripts, Company. All rights reserved.
//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.
// SEE LICENSE AGREEMENT FOR MORE DETAILS
/*------------------------------------------------------------------------*/

class cdb {

var $pgtoday;
var $pgtotal;
var $pgonline;
var $pgflag;
var $pgrgb;
var $pgimg;
var $pgcid;
var $module;

//===================================================================
function getpages($uid,$ident,&$defurl,$fullpref,&$imgid) {        //receive list of page id and groups id containing this page
  global $err,$conf;

  $this->pgtoday=0;
  $this->pgtotal=0;
  $this->pgonline=0;
  $this->pgflag=0;
  $this->pgrgb=0;
  $this->pgimg=0;
  $this->pgcid=0;

  $this->module=array();
  if(isset($conf->aa_mod)) {
      $tmp=split('\|',$conf->aa_mod);
      for($i=0;$i<sizeof($tmp);$i++) $this->module[$tmp[$i]]=1;
  }
  $request='SELECT GET_LOCK("aa_lockc",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('cdb.php|getpages|\'cdb\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);

  //get time of last record
  $request='SELECT MAX(time) AS lt FROM aa_days';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_object($result);
  $numr=mysql_num_rows($result);
  mysql_free_result($result);

  $drop=0;
  //if time of last not today then delete all records from aa_hosts
  if($numr) {
      if($row->lt<$conf->dnum) {
          $request='DELETE FROM aa_hosts';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
  }

  $request='SELECT RELEASE_LOCK("aa_lockc")';
  $reslock=mysql_query($request,$conf->link);
  if(!$reslock) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  mysql_free_result($reslock);

  //WWW prefix for page
  $pwww='';
  if(preg_match("/^www\./i",$fullpref,$matches)) {
    $fullpref=preg_replace("/^www\./i",'',$fullpref);
    $pwww='www.';
  }

  if($conf->locktab) {
    $request='LOCK TABLES aa_pages WRITE, aa_groups WRITE';
    $result=mysql_query($request,$conf->link);
    if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  //check unexistent pages
  if(preg_match("/\.(gif|png)$/i",$defurl,$matches)) {$err->reason('cdb.php|getpages|unexistent page with url "'.$defurl.'"');return;}

  //address for search
  $surl=$defurl;

  //if default page
  $dpflag=0;
  if(preg_match("/\/(index|default)\.[^\/]+$/i",$surl,$matches)) {
    $surl=preg_replace("/\/[^\/]+$/i",'',$surl);
    $dpflag=1;
  }

  if($uid) $request='SELECT * FROM aa_pages WHERE uid='.$uid.' AND added!=0';
  elseif($ident) $request='SELECT * FROM aa_pages WHERE ident="'.$ident.'" AND added!=0';
  else {
    $request='SELECT * FROM aa_pages WHERE (url="http://'.$surl.'" OR url="http://www.'.$surl.'" OR ';
    $request.='url LIKE "http://'.$surl.'/index.%" OR url LIKE "http://www.'.$surl.'/index.%" OR ';
    $request.='url LIKE "http://'.$surl.'/default.%" OR url LIKE "http://www.'.$surl.'/default.%") AND added!=0 AND ident=""';
  }
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {  //if page do not exists
      mysql_free_result($result);
      if(strcmp($conf->amode,'auto')||$uid) {
          if($uid) $err->reason('cdb.php|getpages|the page with uid '.$uid.' was not found');
          else $err->reason('cdb.php|getpages|the default page with url "'.$defurl.'" was not found');
          return;
      }
      //else add new page with given URL
      $lastuid=1;
      $request='SELECT MAX(uid) AS lastuid,COUNT(*) AS nrec FROM aa_pages';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) { if($row->nrec) $lastuid=$row->lastuid+1; }
      mysql_free_result($result);
      //receive first free id (where added=0)
      $request='SELECT id FROM aa_pages WHERE added=0 ORDER BY id ASC LIMIT 1';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($result)) {        //if exists free id then place page here (UPDATE)
          $row = mysql_fetch_object($result);
          mysql_free_result($result);
          $id=$row->id;
          if($ident) {
              $request='UPDATE aa_pages SET name="'.$ident.'",url="http://'.$pwww.$fullpref.'",defpg=0,uid='.$lastuid.',ident="'.$ident.'",imgid='.$conf->amimg.',flags='.$conf->amstat.',rgb='.$conf->amcolor.',added='.$conf->ctime.',first_t=0,last_t=0,vmin=1000000,hsmin=1000000,htmin=1000000,rmin=1000000,vmax=0,hsmax=0,htmax=0,rmax=0 WHERE id='.$id;
          }
          else {
              $request='UPDATE aa_pages SET name="http://'.$pwww.$defurl.'",url="http://'.$pwww.$defurl.'",defpg=0,uid='.$lastuid.',ident="",imgid='.$conf->amimg.',flags='.$conf->amstat.',rgb='.$conf->amcolor.',added='.$conf->ctime.',first_t=0,last_t=0,vmin=1000000,hsmin=1000000,htmin=1000000,rmin=1000000,vmax=0,hsmax=0,htmax=0,rmax=0 WHERE id='.$id;
          }
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
      else {                        //if do not exists free id (INSERT)
          //receive last id (where max)
          mysql_free_result($result);
          $request='SELECT id FROM aa_pages ORDER BY id DESC LIMIT 1';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(mysql_num_rows($result)) {        //if exists last id then id=max+1
              $row = mysql_fetch_object($result);
              mysql_free_result($result);
              $id=$row->id+1;
              //check page limit
              if($id>200) {$err->reason('cdb.php|getpages|adding of new page has failed(limit=200)');return;}
          }
          else {                                //if do not exists last id (clear table)
              mysql_free_result($result);
              $id=1;
          }
          //insert page into table
          if($ident) {
              $request='INSERT INTO aa_pages (id,uid,ident,name,url,imgid,flags,rgb,defpg,added,first_t,last_t,vmin,vmax,hsmin,hsmax,htmin,htmax,rmin,rmax) VALUES ('.$id.','.$lastuid.',"'.$ident.'","'.$ident.'","http://'.$pwww.$fullpref.'",'.$conf->amimg.','.$conf->amstat.','.$conf->amcolor.',0,'.$conf->ctime.',0,0,1000000,0,1000000,0,1000000,0,1000000,0)';
          }
          else {
              $request='INSERT INTO aa_pages (id,uid,ident,name,url,imgid,flags,rgb,defpg,added,first_t,last_t,vmin,vmax,hsmin,hsmax,htmin,htmax,rmin,rmax) VALUES ('.$id.','.$lastuid.',"","http://'.$pwww.$defurl.'","http://'.$pwww.$defurl.'",'.$conf->amimg.','.$conf->amstat.','.$conf->amcolor.',0,'.$conf->ctime.',0,0,1000000,0,1000000,0,1000000,0,1000000,0)';
          }
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
      //add this page to 201 group
      //calculate numbers of field and bit for id
      $fieldn=(int)($id/32)+1;
      $bit=(int)($id%32);
      if($bit) { $bit--; $flag=1073741824>>$bit; }
      else $flag=2147483648;
      $request='UPDATE aa_groups SET flags'.$fieldn.'=flags'.$fieldn.'|'.$flag.' WHERE id=201';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      //get data by this page
      $request='SELECT * FROM aa_pages WHERE uid='.$lastuid.' AND added!=0';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  $row = mysql_fetch_object($result);
  mysql_free_result($result);

  //if folder -> change to address
  if($dpflag&&empty($row->ident)) {
      if((!strcasecmp($row->url,'http://'.$surl))||(!strcasecmp($row->url,'http://www.'.$surl))) {
          $request='UPDATE aa_pages SET url="http://'.$pwww.$defurl.'",defpg=1 WHERE id='.$row->id;
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $row->url='http://'.$pwww.$defurl;
      }
  }

  $this->pgflag=$row->flags;
  $this->pgrgb=$row->rgb;
  $this->pgimg=$row->imgid;
  $this->pgcid=$row->id;
  //result's massive
  $pages[$row->id]=$row->id.'|1';        //key=pages/groups id, value=1
  $imgid=$row->imgid;
  //template for search page by flags in aa_groups
  $id=$row->id;
  $fieldn=(int)($id/32)+1;
  $bit=$id%32;
  if($bit) { $bit--; $flag=1073741824>>$bit; }
  else $flag=2147483648;
  $field='flags'.$fieldn;
  //search groups id that contain this page
  $request='SELECT * FROM aa_groups WHERE added!=0 AND '.$field.'&'.$flag;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //add groups in result
  while($row = mysql_fetch_object($result)) $pages[$row->id]=$row->id.'|1';
  mysql_free_result($result);

  if($conf->locktab) {
    $request='UNLOCK TABLES';
    $result=mysql_query($request,$conf->link);
    if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  return $pages;
}
//===================================================================
function gethosts($ip,&$pagesid) {                //update/add IP and flags of pages/groups
  global $err,$conf;

  $mrecinlog=$conf->mrhosts;

  if(!$ip) {
      reset($pagesid);
      while($e=each($pagesid)) $pagesid[$e[0]].='|1';
      return;
  }

  if($conf->locktab) {
    $request='LOCK TABLES aa_hosts WRITE';
    $result=mysql_query($request,$conf->link);
    if(!$result) {$err->reason('cdb.php|gethosts|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  //search record with IP and receive flags
  $request='SELECT flags1,flags2,flags3,flags4,flags5,flags6,flags7,ip FROM aa_hosts WHERE ip='.$ip;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|gethosts|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  $numr=mysql_num_rows($result);
  mysql_free_result($result);

  // Templates for update/insert flags in aa_hosts
  $mflag=array();        //key=number of flags, value=flags
  reset($pagesid);
  while($e=each($pagesid)) {
      $id=$e[0];
      $fieldn=(int)($id/32)+1;
      $bit=(int)($id%32);
      if($bit) { $bit--; $flag=1073741824>>$bit; }
      else $flag=2147483648;
      if(!isset($mflag[$fieldn])) $mflag[$fieldn]=$flag;
      if($flag==2147483648 || $mflag[$fieldn]>2147483647) {
          $rlast=$mflag[$fieldn]%2;
          $flast=$flag%2;
          $mflag[$fieldn]=(int)($mflag[$fieldn]/2);
          $flag=(int)($flag/2);
          $mflag[$fieldn]|=$flag;
          $mflag[$fieldn]*=2;
          if($rlast||$flast) $mflag[$fieldn]+=1;
      }
      else $mflag[$fieldn]|=$flag;

      //returned results
      if(!$numr) $pagesid[$e[0]].='|1';
      else {
          if($row[$fieldn-1]&$flag) $pagesid[$e[0]].='|0';        //if this host was already today
          else $pagesid[$e[0]].='|1';                            //if was'nt this host today
      }
  }

  if(!$numr) {                //if this IP was already today
      $request='SELECT COUNT(*) AS nrec FROM aa_hosts';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|gethosts|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $row=mysql_fetch_object($result);
      mysql_free_result($result);
      if($row->nrec>$mrecinlog) return;
      //generate query for insert flags
      $k='';
      $v='';
      reset($mflag);
      while($e=each($mflag)) {
          $k.=',flags'.$e[0];
          $v.=','.$e[1];
      }
      $request='INSERT INTO aa_hosts (ip'.$k.') VALUES ('.$ip.$v.')';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|gethosts|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  else {
      //generate query for update flags
      $k='';
      reset($mflag);
      while($e=each($mflag)) {
          if(empty($k))$k='flags'.$e[0].'=flags'.$e[0].'|'.$e[1];
          else $k.=',flags'.$e[0].'=flags'.$e[0].'|'.$e[1];
      }
      $request='UPDATE aa_hosts SET '.$k.' WHERE ip='.$ip;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|gethosts|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  if($conf->locktab) {
    $request='UNLOCK TABLES';
    $result=mysql_query($request,$conf->link);
    if(!$result) {$err->reason('cdb.php|gethosts|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
}
//===================================================================
function updatevis(&$pagesid) {                //update aa_hours,aa_days,aa_total,aa_pages and aa_groups
                                               //increment visitors,hosts,hits and calculate min,max,first and last time
  global $err,$conf;

  if($conf->locktab) {
    $request='LOCK TABLES aa_days WRITE, aa_hours WRITE, aa_total WRITE, aa_pages WRITE, aa_groups WRITE';
    $result=mysql_query($request,$conf->link);
    if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  $resd=array();
  reset($pagesid);
  while($e=each($pagesid)) {
      $mas=split("\|",$e[1]);
      $tmpht=$mas[1];
      $tmphs=$mas[2];
      $tmpvt=$mas[3];
      $tmpv30=$mas[4];
      $tmpv7=$mas[5];
      $tmpv=$mas[6];
      // aa_days - select last records
      $request='SELECT id,time,visitors_t,visitors_m,visitors_w,hosts,hits FROM aa_days WHERE id='.$e[0].' ORDER BY time DESC LIMIT 1';
      $resultd=mysql_query($request,$conf->link);
      if(!$resultd) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($resultd)) {
          $row=mysql_fetch_object($resultd);
          $resd[$e[0]]['time']=$row->time;
          $resd[$e[0]]['visitors_t']=$row->visitors_t;
          $resd[$e[0]]['visitors_m']=$row->visitors_m;
          $resd[$e[0]]['visitors_w']=$row->visitors_w;
          $resd[$e[0]]['hosts']=$row->hosts;
          $resd[$e[0]]['hits']=$row->hits;
      }
      mysql_free_result($resultd);

      // Update AA_HOURS
      $request='UPDATE aa_hours SET visitors=visitors+'.$tmpv.',hosts=hosts+'.$tmphs.',hits=hits+'.$tmpht.' WHERE time='.$conf->hnum.' AND id='.$e[0];
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows()) {
         $request='INSERT INTO aa_hours (time,id,visitors,hosts,hits) VALUES ('.$conf->hnum.','.$e[0].','.$tmpv.','.$tmphs.','.$tmpht.')';
         $result=mysql_query($request,$conf->link);
         if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }

      // Update AA_DAYS
      $up=0;
      if(isset($resd[$e[0]])) {
         if($resd[$e[0]]['time']==$conf->dnum) {
             $up=1;
             $request='UPDATE aa_days SET visitors_t=visitors_t+'.$tmpvt.',visitors_m=visitors_m+'.$tmpv30.',visitors_w=visitors_w+'.$tmpv7.',hosts=hosts+'.$tmphs.',hits=hits+'.$tmpht.' WHERE time='.$conf->dnum.' AND id='.$e[0];
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
         }
         elseif($resd[$e[0]]['time']>$conf->dnum) {
                $request='SELECT * FROM aa_days WHERE time='.$conf->dnum.' AND id='.$e[0];
                $result1=mysql_query($request,$conf->link);
                if(!$result1) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                if(mysql_num_rows($result1)) {
                        $up=1;
                        $request='UPDATE aa_days SET visitors_t=visitors_t+'.$tmpvt.',visitors_m=visitors_m+'.$tmpv30.',visitors_w=visitors_w+'.$tmpv7.',hosts=hosts+'.$tmphs.',hits=hits+'.$tmpht.' WHERE time='.$conf->dnum.' AND id='.$e[0];
                        $result=mysql_query($request,$conf->link);
                        if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                        $db=getdate($conf->btime);
                        $hbtime=mktime(0,0,0,$db['mon'],$db['mday'],$db['year']);
                        $dlast=$conf->dnum*86400+$hbtime;// begin time of last record(day)
                        $dl=getdate($dlast);
                        $mnuml=($dl['year']-$db['year'])*12+$dl['mon']-$db['mon'];// month number of last record(day)
                        $request='UPDATE aa_total SET visitors=visitors+'.$tmpvt.',hosts=hosts+'.$tmphs.',hits=hits+'.$tmpht.' WHERE time='.$mnuml.' AND id='.$e[0];
                        $result=mysql_query($request,$conf->link);
                        if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                        $row=mysql_fetch_object($result1);
                        $resd[$e[0]]['time']=$row->time;
                        $resd[$e[0]]['visitors_t']=$row->visitors_t+$tmpvt;
                        $resd[$e[0]]['visitors_m']=$row->visitors_m+$tmpv30;
                        $resd[$e[0]]['visitors_w']=$row->visitors_w+$tmpv7;
                        $resd[$e[0]]['hosts']=$row->hosts+$tmphs;
                        $resd[$e[0]]['hits']=$row->hits+$tmpht;
                        if($e[0]<=200) $table='aa_pages';
                        else $table='aa_groups';
                        $mv=$resd[$e[0]]['visitors_t'];
                        $mhs=$resd[$e[0]]['hosts'];
                        $mht=$resd[$e[0]]['hits'];
                        $mr=$resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t'];
                        $request='UPDATE '.$table.' SET vmin=IF(vmin>'.$mv.','.$mv.',vmin),vmax=IF(vmax<'.$resd[$e[0]]['visitors_t'].','.$resd[$e[0]]['visitors_t'].',vmax),hsmin=IF(hsmin>'.$mhs.','.$mhs.',hsmin),hsmax=IF(hsmax<'.$resd[$e[0]]['hosts'].','.$resd[$e[0]]['hosts'].',hsmax),htmin=IF(htmin>'.$mht.','.$mht.',htmin),htmax=IF(htmax<'.$resd[$e[0]]['hits'].','.$resd[$e[0]]['hits'].',htmax),rmin=IF(rmin>'.$mr.','.$mr.',rmin),rmax=IF(rmax<'.($resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t']).','.($resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t']).',rmax) WHERE id='.$e[0];
                        $result=mysql_query($request,$conf->link);
                        if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}

                }
                else {
                        $resd[$e[0]]['time']=$conf->dnum;
                        $resd[$e[0]]['visitors_t']=$tmpvt;
                        $resd[$e[0]]['visitors_m']=$tmpv30;
                        $resd[$e[0]]['visitors_w']=$tmpv7;
                        $resd[$e[0]]['hosts']=$tmphs;
                        $resd[$e[0]]['hits']=$tmpht;
                }
                mysql_free_result($result1);
         }
      }//if(mysql_num_rows($resultd))
      if(!isset($resd[$e[0]])||!$up) {        // Add new record into aa_days and aa_total
         $request='INSERT INTO aa_days (time,id,visitors_t,visitors_m,visitors_w,hosts,hits) VALUES ('.$conf->dnum.','.$e[0].','.$tmpvt.','.$tmpv30.','.$tmpv7.','.$tmphs.','.$tmpht.')';
         $result=mysql_query($request,$conf->link);
         if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}

         if(isset($resd[$e[0]])&&!$up) {
             // Update AA_TOTAL if new record was added in AA_DAYS (Last record is included in AA_TOTAL)
             // bmonth of last record by time
             $db=getdate($conf->btime);
             $hbtime=mktime(0,0,0,$db['mon'],$db['mday'],$db['year']);
             $dlast=$resd[$e[0]]['time']*86400+$hbtime;// begin time of last record(day)
             $dl=getdate($dlast);
             $mnuml=($dl['year']-$db['year'])*12+$dl['mon']-$db['mon'];// month number of last record(day)
             // AA_TOTAL
             $request='UPDATE aa_total SET visitors=visitors+'.$resd[$e[0]]['visitors_t'].',hosts=hosts+'.$resd[$e[0]]['hosts'].',hits=hits+'.$resd[$e[0]]['hits'].' WHERE time='.$mnuml.' AND id='.$e[0];
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
             if(!mysql_affected_rows()) {
                 $request='INSERT INTO aa_total (time,id,visitors,hosts,hits) VALUES ('.$mnuml.','.$e[0].','.$resd[$e[0]]['visitors_t'].','.$resd[$e[0]]['hosts'].','.$resd[$e[0]]['hits'].')';
                 $result=mysql_query($request,$conf->link);
                 if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
             }
          }//if(mysql_num_rows($resultd)&&!$up)
      }//if(!mysql_num_rows($resultd)||!$up)

      //update min and max in pages or groups AND first/last time
      if($e[0]<=200) $table='aa_pages';
      else $table='aa_groups';
      //where insert new record into aa_days (record from aa_days -> aa_total)//update existing record in aa_total
      if(isset($resd[$e[0]])&&!$up) {
          $mv=$resd[$e[0]]['visitors_t'];
          $mhs=$resd[$e[0]]['hosts'];
          $mht=$resd[$e[0]]['hits'];
          $mr=$resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t'];
          if(($conf->dnum-$resd[$e[0]]['time'])>1) {        //if day was passed
              $mv=0;
              $mhs=0;
              $mht=0;
              $mr=0;
          }
          $request='UPDATE '.$table.' SET last_t='.$conf->ctime.',vmin=IF(vmin>'.$mv.','.$mv.',vmin),vmax=IF(vmax<'.$resd[$e[0]]['visitors_t'].','.$resd[$e[0]]['visitors_t'].',vmax),hsmin=IF(hsmin>'.$mhs.','.$mhs.',hsmin),hsmax=IF(hsmax<'.$resd[$e[0]]['hosts'].','.$resd[$e[0]]['hosts'].',hsmax),htmin=IF(htmin>'.$mht.','.$mht.',htmin),htmax=IF(htmax<'.$resd[$e[0]]['hits'].','.$resd[$e[0]]['hits'].',htmax),rmin=IF(rmin>'.$mr.','.$mr.',rmin),rmax=IF(rmax<'.($resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t']).','.($resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t']).',rmax) WHERE id='.$e[0];
      }
      else $request='UPDATE '.$table.' SET last_t='.$conf->ctime.',first_t=IF(first_t,first_t,'.$conf->ctime.') WHERE id='.$e[0];
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }//while($e=each($pagesid))

  //if imgid>100
  //  if flag==1 v total
  //  if flag==2 hs total
  //  if flag==3 ht total
  //  if flag==4 v total,today
  //  if flag==5 hs total,today
  //  if flag==6 ht total,today
  //  if flag==7 v total,today,online
  //  if flag==8 hs total,today, v online
  //  if flag==9 ht total,today, v online
  if($this->pgimg>100) {
      //total result
      $request='SELECT visitors_t AS v,hosts AS hs,hits AS ht FROM aa_days WHERE time='.$conf->dnum.' AND id='.$this->pgcid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          if($this->pgflag==1||$this->pgflag==4||$this->pgflag==7) $this->pgtotal=$row->v;
          elseif($this->pgflag==2||$this->pgflag==5||$this->pgflag==8) $this->pgtotal=$row->hs;
          elseif($this->pgflag==3||$this->pgflag==6||$this->pgflag==9) $this->pgtotal=$row->ht;
      }
      mysql_free_result($result);
      $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_total WHERE id='.$this->pgcid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          if($row->nrec) {
              if($this->pgflag==1||$this->pgflag==4||$this->pgflag==7) $this->pgtotal+=$row->v;
              elseif($this->pgflag==2||$this->pgflag==5||$this->pgflag==8) $this->pgtotal+=$row->hs;
              elseif($this->pgflag==3||$this->pgflag==6||$this->pgflag==9) $this->pgtotal+=$row->ht;
          }
      }
      mysql_free_result($result);
      //today result
      if($this->pgflag>3) {
          $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600;
          $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_hours WHERE time>='.$rbeg.' AND id='.$this->pgcid;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($result)) {
              if($row->nrec) {
                  if($this->pgflag==4||$this->pgflag==7) $this->pgtoday=$row->v;
                  elseif($this->pgflag==5||$this->pgflag==8) $this->pgtoday=$row->hs;
                  elseif($this->pgflag==6||$this->pgflag==9) $this->pgtoday=$row->ht;
              }
          }
          mysql_free_result($result);
      }
  }
  // Delete records where time>72
  //begin hour of (yesterday-24)
  if($conf->hnum>71) {
      $hbday=$conf->hnum-($conf->htime-$conf->dtime)/3600-48;
      $request='DELETE FROM aa_hours WHERE time<'.$hbday;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }//if($conf->hnum>71)

  // Delete records where time>93
  if($conf->dnum>92) {
      $hbday=$conf->dnum-93;
      $request='DELETE FROM aa_days WHERE time<='.$hbday;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  if($conf->locktab) {
    $request='UNLOCK TABLES';
    $result=mysql_query($request,$conf->link);
    if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
}
//===================================================================
function updateref($url,$fullref,&$pagesid) {
  global $err,$conf,$HTTP_SERVER_VARS,$_SERVER;

  //WWW prefix for referrer
  $rwww='';
  if(preg_match("/^www\./i",$fullref,$matches)) {
    $fullref=preg_replace("/^www\./i",'',$fullref);
    $rwww='www.';
  }

  //refer settings(full)
  if(!empty($fullref)) $url=$fullref;

  $mrecinbase=$conf->mrrefb;
  $mrecinlog=$conf->mrrefl;
  $restr=0;
  //ref domain
  $pdomain='';
  $page=preg_replace("/[\?|&|#].*$/i",'',$url);
  if(preg_match("/([^\/]+)/i",$page,$matches)) $pdomain=$matches[1];
  $pdomain=preg_replace("/(:\d+)*$/",'',$pdomain);
  //our domain
  $domain=$conf->url;
  if(preg_match("/^(http:\/\/)([^\/]+)/i",$domain,$matches)) $domain=$matches[2];
  elseif(isset($_SERVER['SERVER_NAME'])) $domain=$_SERVER['SERVER_NAME'];
  elseif(isset($HTTP_SERVER_VARS['SERVER_NAME'])) $domain=$HTTP_SERVER_VARS['SERVER_NAME'];
  elseif(isset($_SERVER['HTTP_HOST'])) $domain=$_SERVER['HTTP_HOST'];
  elseif(isset($HTTP_SERVER_VARS['HTTP_HOST'])) $domain=$HTTP_SERVER_VARS['HTTP_HOST'];
  else {$err->reason('cdb.php|updateref|can\'t get current domain name');return;}
  $domain=preg_replace("/^(www\.)/i",'',$domain);
  $domain=preg_replace("/(:\d+)*$/",'',$domain);
  if(!strcmp($pdomain,$domain)) $cd=1;                //internal
  else {
      $cd=0;                                        //external
      if(file_exists('./data/aliases.php')) {
        require './data/aliases.php';
        //check aliases
        if(isset($alias)) {
          reset($alias);
          while($e=each($alias)) {
            $cdm=preg_replace("/^www\./i",'',$e[1]);
            if(!strcasecmp($pdomain,$cdm)) {
                $cd=1;
                break;
            }
          }
        }
      }
  }

  $request='SELECT GET_LOCK("aa_ref",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('cdb.php|updateref|\'aa_ref\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);

  // Check and add to aa_ref_base the new referrer and get referrer_id.
  $request='SELECT refid,url FROM aa_ref_base WHERE url="'.$url.'" OR url="www.'.$url.'"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {                //referrer is not exists
       mysql_free_result($result);
       if(!$restr) {
           $delids=array();
           restrict('ref','aa_ref_base',$mrecinbase,sizeof($pagesid),$this->module,$delids);
           if($err->flag) {$err->reason('cdb.php|updateref|\'restrict\' function has failed');return;}
           $restr=1;
       }
       $request='SELECT refid FROM aa_ref_base WHERE url="" ORDER BY refid ASC LIMIT 1';
       $result=mysql_query($request,$conf->link);
       if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       if(mysql_num_rows($result)) {
           $row=mysql_fetch_row($result);
           mysql_free_result($result);
           $refid=$row[0];
           if($cd) $request='UPDATE aa_ref_base SET url="'.$rwww.$url.'",added='.$conf->ctime.',count=1,flag=1 WHERE refid='.$refid;
           else $request='UPDATE aa_ref_base SET url="'.$rwww.$url.'",added='.$conf->ctime.',count=1,flag=2 WHERE refid='.$refid;
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       }
       else {
           mysql_free_result($result);
           $request='SELECT refid FROM aa_ref_base ORDER BY refid DESC LIMIT 1';
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
           //search max id
           if(mysql_num_rows($result)) {
               $row=mysql_fetch_row($result);
               mysql_free_result($result);
               if($row[0]==65535){$err->reason('cdb.php|updateref|the limit of referrers (65535 referrers) is achieved');return;}
               $refid=$row[0]+1;
           }
           else {
               mysql_free_result($result);
               $refid=1;
           }
           if($cd) $request='INSERT INTO aa_ref_base (refid,flag,added,count,url) VALUES ('.$refid.',1,'.$conf->ctime.',1,"'.$rwww.$url.'")';
           else $request='INSERT INTO aa_ref_base (refid,flag,added,count,url) VALUES ('.$refid.',2,'.$conf->ctime.',1,"'.$rwww.$url.'")';
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       }
   }
   else {                                        //referrer is exists
       $row=mysql_fetch_row($result);
       $refid=$row[0];
       mysql_free_result($result);
       $request='UPDATE aa_ref_base SET count=count+1 WHERE refid='.$refid;
       $result=mysql_query($request,$conf->link);
       if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
   }

  // Check and add to aa_domains the new domain and get domain_id.
  $request='SELECT domid,domain FROM aa_domains WHERE domain="'.$pdomain.'" OR domain="www.'.$pdomain.'"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {             //referrer is not exists
       mysql_free_result($result);
       $request='SELECT domid FROM aa_domains WHERE domain="" ORDER BY domid ASC LIMIT 1';
       $result=mysql_query($request,$conf->link);
       if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       if(mysql_num_rows($result)) {
           $row=mysql_fetch_row($result);
           mysql_free_result($result);
           $domid=$row[0];
           $request='UPDATE aa_domains SET domain="'.$rwww.$pdomain.'" WHERE domid='.$domid;
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       }
       else {
           mysql_free_result($result);
           $request='SELECT domid FROM aa_domains ORDER BY domid DESC LIMIT 1';
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
           //search max id
           if(mysql_num_rows($result)) {
               $row=mysql_fetch_row($result);
               mysql_free_result($result);
               if($row[0]==65535){$err->reason('cdb.php|updateref|the limit of domains (65535 domains) is achieved');return;}
               $domid=$row[0]+1;
           }
           else {
               mysql_free_result($result);
               $domid=1;
           }
           $request='INSERT INTO aa_domains (domid,domain) VALUES ('.$domid.',"'.$rwww.$pdomain.'")';
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       }
   }
   else {                                    //referrer is exists
       $row=mysql_fetch_row($result);
       $domid=$row[0];
       mysql_free_result($result);
   }

  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  reset($pagesid);
  while($e=each($pagesid)) {
      $k=$e[0];
      $mas=split("\|",$pagesid[$k]);
      $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
      // FRAMES TOTAL
      // !!! SELECT for get modify !!!
      $request='SELECT * FROM aa_ref_total WHERE id='.$k.' AND refid='.$refid;
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_num_rows($result1)) {
          mysql_free_result($result1);
          if(!$restr) {
              $delids=array();
              restrict('ref','aa_ref_total',$mrecinlog,sizeof($pagesid),$this->module,$delids);
              if($err->flag) {$err->reason('cdb.php|updateref|\'restrict\' function has failed');return;}
              $restr=1;
              if(isset($delids[$refid])) { break; }
          }
          $request='INSERT INTO aa_ref_total (id,refid,domid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$refid.','.$domid.','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_num_rows($result1))
      else {
          while($row=mysql_fetch_object($result1)) {
             $request='UPDATE aa_ref_total SET modify='.$conf->ctime;
             $this->ndadd($request,$row,$pagesid[$k],1);
             $request.=' WHERE id='.$row->id.' AND refid='.$row->refid;
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//while($row=mysql_fetch_object($result1))
          mysql_free_result($result1);
      }//else
  }

  $request='SELECT RELEASE_LOCK("aa_ref")';
  $reslock=mysql_query($request,$conf->link);
  if(!$reslock) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  mysql_free_result($reslock);
}
//===================================================================
function ndadd(&$request,&$row,$stat,$num) {
  global $conf;

  $mas=split("\|",$stat);
  $cht=$mas[$num]; $chs=$mas[$num+1]; $cvt=$mas[$num+2]; $cv30=$mas[$num+3]; $cv7=$mas[$num+4]; $cv=$mas[$num+5];
  $db=getdate($conf->btime);
  if(!$row->modify) $dc=getdate($conf->btime);
  else $dc=getdate($row->modify);
  $hbtime=mktime(0,0,0,$db['mon'],$db['mday'],$db['year']);
  $ldtime=mktime(0,0,0,$dc['mon'],$dc['mday'],$dc['year']);
  $ldnum=round(($ldtime-$hbtime)/$conf->time1);
  $begw=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->wtime)/$conf->time1);
  $beglw=$begw-7;
  $begm=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
  $beglm=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  // YEAR
  $request.=',v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht;
  // DAY
  if($conf->dnum!=$ldnum) {
      $request.=',vt='.$cv.',hst='.$chs.',htt='.$cht;
      if($conf->dnum-$ldnum==1) $request.=',vy='.$row->vt.',hsy='.$row->hst.',hty='.$row->htt;
      else $request.=',vy=0,hsy=0,hty=0';
  }
  else $request.=',vt=vt+'.$cv.',hst=hst+'.$chs.',htt=htt+'.$cht;
  // WEEK
  if($ldnum<$begw) {
      $request.=',vw='.$cv7.',hsw='.$chs.',htw='.$cht;
      if($ldnum>=$beglw) $request.=',vlw='.$row->vw.',hslw='.$row->hsw.',htlw='.$row->htw;
      else $request.=',vlw=0,hslw=0,htlw=0';
  }
  else $request.=',vw=vw+'.$cv7.',hsw=hsw+'.$chs.',htw=htw+'.$cht;
  // MONTH
  if($ldnum<$begm) {
      $request.=',vm='.$cv30.',hsm='.$chs.',htm='.$cht;
      if($ldnum>=$beglm) $request.=',vlm='.$row->vm.',hslm='.$row->hsm.',htlm='.$row->htm;
      else $request.=',vlm=0,hslm=0,htlm=0';
  }
  else $request.=',vm='.$row->vm.'+'.$cv30.',hsm='.$row->hsm.'+'.$chs.',htm='.$row->htm.'+'.$cht;
}
//===================================================================
function getstat(&$flag,&$tot,&$tod,&$onl,&$color) {
    $flag=$this->pgflag;
    $tot=$this->pgtotal;
    $tod=$this->pgtoday;
    $onl=$this->pgonline;
    $color=$this->pgrgb;
}
//===================================================================

}

?>