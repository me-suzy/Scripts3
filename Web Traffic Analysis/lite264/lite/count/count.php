<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: count.php
// Source: http://www.actualscripts.com/
// Copyright: (c) 2002-2004 ActualScripts, Company. All rights reserved.
//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.
// SEE LICENSE AGREEMENT FOR MORE DETAILS
/*------------------------------------------------------------------------*/

class count {

function count() {
  global $err,$conf,$cdb,$cvis;

  //referrer
  $ref='';         //referrer
  $engine='';      //search engine
  $sphrase='';     //search phrase
  $swords = array();    //array of search words
  $this->getref($ref,$fullref,$engine,$sphrase,$swords);

  //pages (hit)
  $pref='';        //url of page with analyzer
  $fullpref='';      //url of tracked page with parameters
  $ident='';     //custom identificator of page
  $uid=$this->getpage($pref,$fullpref,$ident);
  if($err->flag) {$err->reason('count.php|count|can\'t get page');return;}

  //change the full addresses
  $fullpref=preg_replace("/^(https?:\/\/)/i",'',$fullpref);
  $fullpref=addslashes($fullpref);
  $fullref=preg_replace("/^(https?:\/\/)/i",'',$fullref);
  $fullref=addslashes($fullref);

  //information about current groups/page
  $img=2;  //image by default
  $pagesid=$cdb->getpages($uid,$ident,$pref,$fullpref,$img);
  if($err->flag) {$err->reason('count.php|count|can\'t get identifiers of the groups');return;}

  //IP (host)
  $ip='';
  $proxy='';
  $this->getips($ip,$proxy);
  $cdb->gethosts($ip,$pagesid);
  if($err->flag) {$err->reason('count.php|count|can\'t get hosts');return;}

  //visitor
  $retime=array();   //time of first visit on page/groups
  $this->getvis($pagesid,$retime);
  if($err->flag) {$err->reason('count.php|count|can\'t get visitors');return;}
  $cdb->updatevis($pagesid);
  if($err->flag) {$err->reason('count.php|count|can\'t save visitors');return;}

  //update referrer
  $cdb->updateref($ref,$fullref,$pagesid);
  if($err->flag) {$err->reason('count.php|count|can\'t save referrer');return;}

  //output picture with statistics
  if($img>100) {
    $flag=1;
    $d1=0;
    $d2=0;
    $d3=0;
    $color=0;
    $cdb->getstat($flag,$d1,$d2,$d3,$color);
    $cvis->out_digits($img,$flag,$d1,$d2,$d3,$color);
  }
  //output simple picture
  else $cvis->out_pic($img);
}

//get current page
function getpage(&$pref,&$fullpref,&$ident) {
  global $err,$conf,$HTTP_SERVER_VARS,$_SERVER,$HTTP_GET_VARS,$_GET;

  if(isset($_SERVER['HTTP_REFERER'])) $page=$_SERVER['HTTP_REFERER'];
  elseif(isset($HTTP_SERVER_VARS['HTTP_REFERER'])) $page=$HTTP_SERVER_VARS['HTTP_REFERER'];
  else {
    if(isset($_GET['anp'])) $page=$_GET['anp'];
    elseif(isset($HTTP_GET_VARS['anp'])) $page=$HTTP_GET_VARS['anp'];
    else $page='';
    if(!strcmp($page,'null')) $page='';
  }
  if(empty($page)) {$err->reason('count.php|getpage|can\'t get URL of the page');return;}
  //to decode
  $page=urldecode($page);
  //to correct back slashes            http://actualscripts.com\index\
  $page=str_replace("\\","/",$page);
  //to remove unnecessary points       http://actualscripts.com.
  $page=preg_replace("/\/\./",'/',$page);
  $page=preg_replace("/\.\//",'/',$page);
  $page=preg_replace("/\.*$/",'',$page);
  $page=preg_replace("/(\"|\')*$/",'',$page);
  //to remove unnecessary duplicates of slashes             http://actualscripts.com///
  $page=preg_replace("/([^:])(\/)+/",'$1/',$page);

  $fullpref=$page;

  $page=preg_replace("/^(https?:\/\/)(www\.)?/i",'',$page);
  $page=preg_replace("/[\?|&|#|;].*$/i",'',$page);
  $page=preg_replace("/(\/)*$/",'',$page);
  $page=trim($page);
  $page=preg_replace("/\.*$/",'',$page);
  $page=preg_replace("/(\"|\')*$/",'',$page);
  $page=trim($page);
  $pref=$page;
  if(preg_match("/^([^\/]+)/i",$page,$matches)) $pdomain=$matches[1];
  else $pdomain='';
  $pdomain=preg_replace("/(:\d+)*$/",'',$pdomain);

  $domain=$conf->url;
  if(preg_match("/^(https?:\/\/)([^\/]+)/i",$domain,$matches)) $domain=$matches[2];
  elseif(isset($_SERVER['SERVER_NAME'])) $domain=$_SERVER['SERVER_NAME'];
  elseif(isset($HTTP_SERVER_VARS['SERVER_NAME'])) $domain=$HTTP_SERVER_VARS['SERVER_NAME'];
  elseif(isset($_SERVER['HTTP_HOST'])) $domain=$_SERVER['HTTP_HOST'];
  elseif(isset($HTTP_SERVER_VARS['HTTP_HOST'])) $domain=$HTTP_SERVER_VARS['HTTP_HOST'];
  else {$err->reason('count.php|getpage|can\'t get current domain name');return;}
  $domain=preg_replace("/^(www\.)/i",'',$domain);
  $domain=preg_replace("/(:\d+)*$/",'',$domain);

  if(strcasecmp($pdomain,$domain)) {
    if(!file_exists('./data/aliases.php')) {$err->reason('count.php|getpage|unknown external page \''.$page.'\'');return;}
    require './data/aliases.php';

    //check aliases
    $falias=false;
    if(isset($alias)) {
      reset($alias);
      while($e=each($alias)) {
        $cdm=preg_replace("/^www\./i",'',$e[1]);
        if(!strcasecmp($pdomain,$cdm)) {
           $falias=true;
           break;
        }
      }
    }
    if(!$falias) {$err->reason('count.php|getpage|external page \''.$page.'\'  not in aliases list');return;}
  }

  if(isset($_GET['anuid'])) $uid=$_GET['anuid'];
  elseif(isset($HTTP_GET_VARS['anuid'])) $uid=$HTTP_GET_VARS['anuid'];
  else $uid='';

  if(isset($_GET['anident'])) $ident=$_GET['anident'];
  elseif(isset($HTTP_GET_VARS['anident'])) $ident=$HTTP_GET_VARS['anident'];
  else $ident='';
  if(!empty($ident)) $ident=urldecode($ident);

  //predefined variables
  $fprefident=preg_replace("/^(https?:\/\/)(www\.)?/i",'',$fullpref);
  $fprefident=addslashes($fprefident);
  $urlarr=parse_url('http://'.$fprefident);      //array with address
  $paramarr=array();                 //array with parameters
  $markarr=array();                  //array with markers
  if(isset($urlarr['query'])) {
    $prmarr=preg_split("/&/",$urlarr['query']);
    reset($prmarr);
    while($e=each($prmarr)) {
      $tarr=preg_split("/=/",$e[1]);
      if(sizeof($tarr)==2) {
        $paramarr[$tarr[0]]=$tarr[1];
        $markarr[$tarr[0]]='0';
      }
    }
  }

  $pflag=0;
  //ADDRESS_PARAMETERS_ALL
  if(!strcasecmp($ident,'ADDRESS_PARAMETERS_ALL')) {
    if(isset($urlarr['query'])) {
      $ident=$fprefident;
    }
    else $ident='';
  }
  //PARAMETERS_ALL
  elseif(!strcasecmp($ident,'PARAMETERS_ALL')) {
      if(isset($urlarr['query'])) $ident=$urlarr['query'];
      else $ident='';
  }
  //ADDRESS_PARAMETERS_INCLUDE_xxxxxx
  elseif(preg_match("/^ADDRESS_PARAMETERS_INCLUDE_(.+)$/i",$ident,$matches)) {
      $sparams=$matches[1];
      $pflag=1;
  }
  //ADDRESS_PARAMETERS_EXCLUDE_xxxxxx
  elseif(preg_match("/^ADDRESS_PARAMETERS_EXCLUDE_(.+)$/i",$ident,$matches)) {
      $sparams=$matches[1];
      $pflag=2;
  }
  //PARAMETERS_INCLUDE_xxxxxx
  elseif(preg_match("/^PARAMETERS_INCLUDE_(.+)$/i",$ident,$matches)) {
      $sparams=$matches[1];
      $pflag=3;
  }
  //PARAMETERS_EXCLUDE_xxxxxx
  elseif(preg_match("/^PARAMETERS_EXCLUDE_(.+)$/i",$ident,$matches)) {
      $sparams=$matches[1];
      $pflag=4;
  }

  if($pflag) {
    //set markers
    $sprarr=preg_split("/_/",$sparams);
    reset($sprarr);
    while($e=each($sprarr)) {
      if(isset($paramarr[$e[1]])) $markarr[$e[1]]='1';
    }

    //parameters
    $prmstr='';
    if($pflag==1||$pflag==3) {
      reset($paramarr);
      while($e=each($paramarr)) {
        if($markarr[$e[0]]==1) {
          if(empty($prmstr)) $prmstr.=$e[0].'='.$e[1];
          else $prmstr.='&'.$e[0].'='.$e[1];
        }
      }
    }
    elseif($pflag==2||$pflag==4) {
      reset($paramarr);
      while($e=each($paramarr)) {
        if($markarr[$e[0]]==0) {
          if(empty($prmstr)) $prmstr.=$e[0].'='.$e[1];
          else $prmstr.='&'.$e[0].'='.$e[1];
        }
      }
    }

    //address
    $ident=$prmstr;
    if($pflag==1||$pflag==2) {
      if(!empty($prmstr)) {
        if(isset($urlarr['host'])) $ident=$urlarr['host'];
        if(isset($urlarr['path'])) $ident.=$urlarr['path'];
        $ident.='?'.$prmstr;
        $ident=preg_replace("/^www\./i",'',$ident);
      }
    }
  }

  return $uid;
}

//get current IP
function getips(&$ip,&$proxy) {
  global $err,$conf,$HTTP_SERVER_VARS,$_SERVER;

  if(isset($_SERVER['REMOTE_ADDR'])) $ra=$_SERVER['REMOTE_ADDR'];
  elseif(isset($HTTP_SERVER_VARS['REMOTE_ADDR'])) $ra=$HTTP_SERVER_VARS['REMOTE_ADDR'];
  else $ra='';

  if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    $proxy=$ra;
  }
  elseif(isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'])) {
    $ip=$HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'];
    $proxy=$ra;
  }
  if(isset($_SERVER['HTTP_CLIENT_IP'])) {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  }
  elseif(isset($HTTP_SERVER_VARS['HTTP_CLIENT_IP'])) {
    $ip=$HTTP_SERVER_VARS['HTTP_CLIENT_IP'];
  }
  else $ip=$ra;

  if(!empty($ip)) {
    $iparr=preg_split("/,/",$ip);
    $ip=$iparr[0];
    $ip=(int)ip2long($ip);
  }
  else $ip=0;

  if(!empty($proxy)) {
    $proxy=(int)ip2long($proxy);
  }
  else $proxy=0;
}

//get unique visitors
function getvis(&$pagesid,&$retime) {
  global $err,$conf,$HTTP_COOKIE_VARS,$_COOKIE;

  if(isset($_COOKIE['ant'])) $cot=$_COOKIE['ant'];
  elseif(isset($HTTP_COOKIE_VARS['ant'])) $cot=$HTTP_COOKIE_VARS['ant'];
  else $cot='';
  $cos=preg_split("/x/",$cot);
  $max=sizeof($cos);
  for($c=0;$c<$max;$c++) {
    if(strlen($cos[$c])==10) {
      $id=substr($cos[$c],0,2);
      eval("\$id=0x$id;");
      $anct[$id]=$cos[$c];
    }
  }

  if(isset($_COOKIE['anm'])) $com=$_COOKIE['anm'];
  elseif(isset($HTTP_COOKIE_VARS['anm'])) $com=$HTTP_COOKIE_VARS['anm'];
  else $com='';
  $cos=preg_split("/x/",$com);
  $max=sizeof($cos);
  for($c=0;$c<$max;$c++) {
    if(strlen($cos[$c])==10) {
      $id=substr($cos[$c],0,2);
      eval("\$id=0x$id;");
      $tim=substr($cos[$c],2,8);
      eval("\$tim=0x$tim;");
      if($tim>=$conf->mtime) $ancm[$id]=$cos[$c];
    }
  }

  if(isset($_COOKIE['anw'])) $cow=$_COOKIE['anw'];
  elseif(isset($HTTP_COOKIE_VARS['anw'])) $cow=$HTTP_COOKIE_VARS['anw'];
  else $cow='';
  $cos=preg_split("/x/",$cow);
  $max=sizeof($cos);
  for($c=0;$c<$max;$c++) {
    if(strlen($cos[$c])==10) {
      $id=substr($cos[$c],0,2);
      eval("\$id=0x$id;");
      $tim=substr($cos[$c],2,8);
      eval("\$tim=0x$tim;");
      if($tim>=$conf->wtime) $ancw[$id]=$cos[$c];
    }
  }

  if(isset($_COOKIE['an1'])) $co1=$_COOKIE['an1'];
  elseif(isset($HTTP_COOKIE_VARS['an1'])) $co1=$HTTP_COOKIE_VARS['an1'];
  else $co1='';
  $cos=preg_split("/x/",$co1);
  $max=sizeof($cos);
  for($c=0;$c<$max;$c++) {
    if(strlen($cos[$c])==10) {
      $id=substr($cos[$c],0,2);
      eval("\$id=0x$id;");
      $tim=substr($cos[$c],2,8);
      eval("\$tim=0x$tim;");
      if($tim>=$conf->dtime) $anc1[$id]=$cos[$c];
    }
  }

  //current time in HEX
  if($conf->ctime>0x7FFFFFFF) {
    $t1=$conf->ctime/16;
    $t2=$conf->ctime&0xF;
    $ctimestr=sprintf("%07X%01X",$t1,$t2);
  }
  else {
    $ctimestr=sprintf("%08X",$conf->ctime);
  }

  reset($pagesid);
  while($e=each($pagesid)) {
    $k=$e[0];
    if(isset($anct[$k])) {
      $pagesid[$k].='|0';
      if(strlen($anct[$k])==10) {
        $tim=substr($anct[$k],2,8);
        eval("\$tim=0x$tim;");
        $retime[$k]=$tim;
      }
    }
    else {
      $pagesid[$k].='|1';
      $anct[$k]=sprintf("%02X%s",$k,$ctimestr);
    }

    if(isset($ancm[$k])) $pagesid[$k].='|0';
    else {
      $pagesid[$k].='|1';
      $ancm[$k]=sprintf("%02X%s",$k,$ctimestr);
    }

    if(isset($ancw[$k])) $pagesid[$k].='|0';
    else {
      $pagesid[$k].='|1';
      $ancw[$k]=sprintf("%02X%s",$k,$ctimestr);
    }

    if(isset($anc1[$k])) $pagesid[$k].='|0';
    else {
      $pagesid[$k].='|1';
      $anc1[$k]=sprintf("%02X%s",$k,$ctimestr);
    }
  }

  $cot=join('x',$anct);
  $com=join('x',$ancm);
  $cow=join('x',$ancw);
  $co1=join('x',$anc1);

  //get path
  $path=$conf->url;
  $path=preg_replace("/^(http:\/\/)([^\/]+)/i",'',$path);
  $path.='aa.php';
  //set cookie
  SetCookie('ant',$cot,time()+($conf->time1*3000),$path);
  SetCookie('anm',$com,time()+($conf->time1*31),$path);
  SetCookie('anw',$cow,time()+($conf->time1*7),$path);
  SetCookie('an1',$co1,time()+$conf->time1,$path);
}

//get current referrer
function getref(&$ref,&$fullref,&$sengine,&$sphrase,&$swords) {
  global $err,$conf,$HTTP_GET_VARS,$_GET;

  if(isset($_GET['anr'])) $refer=$_GET['anr'];
  elseif(isset($HTTP_GET_VARS['anr'])) $refer=$HTTP_GET_VARS['anr'];
  else $refer='undefined';

  //to decode
  $refer=urldecode($refer);
  //to correct back slashes            http://actualscripts.com\index\
  $refer=str_replace("\\","/",$refer);
  //to remove unnecessary points       http://actualscripts.com.
  $refer=preg_replace("/\/\./",'/',$refer);
  $refer=preg_replace("/\.\//",'/',$refer);
  $refer=preg_replace("/\.*$/",'',$refer);
  $refer=preg_replace("/(\"|\')*$/",'',$refer);
  //to remove unnecessary duplicates of slashes             http://actualscripts.com///
  $refer=preg_replace("/([^:])(\/)+/",'$1/',$refer);
  $fullref=$refer;

  $refer=preg_replace("/^(https?:\/\/)(www\.)?/i",'',$refer);
  $ref=preg_replace("/[\?|&|#|;].*$/i",'',$refer);
  $ref=preg_replace("/(\/)*$/",'',$ref);
  $ref=trim($ref);
  $ref=preg_replace("/\.*$/",'',$ref);
  $ref=preg_replace("/(\"|\')*$/",'',$ref);
  $ref=trim($ref);

  //check referrer (bad)
  if(empty($ref)) $ref='undefined';
  //search "." in domain name
  if(!preg_match("/^([^\.\/]+\.)+([^\.\/])+/i",$ref))  $ref='undefined';
}

}

?>