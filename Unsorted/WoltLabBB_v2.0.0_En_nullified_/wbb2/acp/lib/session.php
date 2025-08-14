<?php
mt_srand(intval(substr(microtime(), 2, 8)));
if(mt_rand(1,100)==50) {
 $db->unbuffered_query("DELETE FROM bb".$n."_sessions WHERE lastactivity<".(time()-$sessiontimeout),1);
 $db->unbuffered_query("DELETE FROM bb".$n."_searchs WHERE searchtime<".(time()-86400*7),1);
}
$REMOTE_ADDR = getIpAddress();
$HTTP_USER_AGENT = substr(getenv("HTTP_USER_AGENT"), 0, 100);
$REQUEST_URI = getenv("REQUEST_URI");
if(!$REQUEST_URI) {
 if(getenv("PATH_INFO")) $REQUEST_URI = getenv("PATH_INFO");
 else $REQUEST_URI = getenv("PHP_SELF");
 if(getenv("QUERY_STRING")) $REQUEST_URI.="?".getenv("QUERY_STRING");
}
$REQUEST_URI = substr(basename($REQUEST_URI), 0, 250);
if(!strstr($REQUEST_URI,".")) $REQUEST_URI="index.php";

unset($wbbuserdata);
unset($session);
unset($wbb_userid);
if(isset($_COOKIE['wbb_userid'])) $wbb_userid=$_COOKIE['wbb_userid'];

if($isacp!=1) {
 if(isset($_REQUEST['styleid'])) $styleid = intval($styleid);
 else unset($styleid);
}
if(isset($boardid)) $sboardid=intval($boardid);
else $sboardid=0;
if(isset($threadid)) $sthreadid=$threadid;
else $sthreadid=0;

if(isset($_GET['sid'])) $sid=$_GET['sid'];
elseif(isset($_POST['sid'])) $sid=$_POST['sid'];
else $sid="";

if(!$sid && isset($_COOKIE['cookiehash'])) $sid=$_COOKIE['cookiehash'];
if($sid && isset($_COOKIE['cookiehash']) && $_COOKIE['cookiehash'] && $sid!=$_COOKIE['cookiehash']) $falsecookiehash=1;

$createsession=0;
if($sid) {
 $session = $db->query_first("SELECT * FROM bb".$n."_sessions WHERE hash = '".addslashes($sid)."' AND ipaddress = '".addslashes($REMOTE_ADDR)."' AND useragent = '".addslashes($HTTP_USER_AGENT)."'");
 if($session['hash']) {
  $wbb_userid=$session['userid'];
  $session['lastactivity'] = time();
  if($isacp!=1 && isset($styleid)) $styleid_add = " styleid = '$styleid',";
  else $styleid_add = "";
  $db->unbuffered_query("UPDATE bb".$n."_sessions SET lastactivity = '".$session['lastactivity']."', request_uri = '".addslashes($REQUEST_URI)."',$styleid_add boardid = '$sboardid', threadid = '$sthreadid' WHERE hash = '$sid'",1);
 }
 else $createsession = 1;
}
else $createsession = 1;

if($createsession==1 || $session['userid']==0) {
 if(isset($_COOKIE['wbb_userid']) && isset($_COOKIE['wbb_userpassword'])) { /* maybe member */
  $wbbuserdata = $db->query_first("SELECT bb".$n."_users.*, bb".$n."_groups.* FROM bb".$n."_users LEFT JOIN bb".$n."_groups USING (groupid) WHERE userid = '$_COOKIE[wbb_userid]'");
  if($_COOKIE['wbb_userpassword']==$wbbuserdata['password']) { /* member */
   $session = array();
   $session['hash'] = md5(uniqid(microtime()));
   $session['userid'] = $_COOKIE['wbb_userid'];
   $session['ipaddress'] = $REMOTE_ADDR;
   $session['useragent'] = addslashes($HTTP_USER_AGENT);
   $session['lastactivity'] = time();
   $session['request_uri'] = $REQUEST_URI;
   if($icacp!=1 && isset($styleid)) $session['styleid'] = $styleid;
   else $session['styleid']=$wbbuserdata['styleid'];
   $db->unbuffered_query("DELETE FROM bb".$n."_sessions WHERE userid = '$session[userid]'",1);
   $db->unbuffered_query("INSERT INTO bb".$n."_sessions VALUES ('$session[hash]','$session[userid]','".addslashes($session[ipaddress])."','".addslashes($session[useragent])."','$session[lastactivity]','".addslashes($session[request_uri])."','$session[styleid]','$sboardid','$sthreadid')",1);
   bbcookie("cookiehash","$session[hash]",0);
  }
  else {
   if($createsession==1) $guestsession = 1;
   unset($wbb_userid);
   unset($wbbuserdata);
   bbcookie("wbb_userid","",1);
   bbcookie("wbb_userpassword","",1);
  } 
 }
 elseif($createsession==1) {
  unset($wbb_userid);
  $guestsession = 1;
 }
 if(isset($guestsession)) { /* guest */
  $session=$db->query_first("SELECT * FROM bb".$n."_sessions WHERE userid='0' AND ipaddress = '".addslashes($REMOTE_ADDR)."' AND useragent = '".addslashes($HTTP_USER_AGENT)."'");
  if($session['hash']) {
   $sid=$session['hash'];
   $session['lastactivity'] = time();
   if($isacp==1 || !isset($styleid)) $styleid = $session['styleid'];
   $db->unbuffered_query("UPDATE bb".$n."_sessions SET lastactivity = '$session[lastactivity]', request_uri = '".addslashes($REQUEST_URI)."', styleid = '$styleid', boardid = '$sboardid', threadid = '$sthreadid' WHERE hash = '$sid'",1);
  }
  else {
   $session['hash'] = md5(uniqid(microtime()));
   $session['userid'] = 0;
   $session['ipaddress'] = $REMOTE_ADDR;
   $session['useragent'] = addslashes($HTTP_USER_AGENT);
   $session['lastactivity'] = time();
   $session['request_uri'] = $REQUEST_URI;
   if($isacp!=1 && isset($styleid)) $session['styleid'] = $styleid;
   else $session['styleid']=0;
   $db->unbuffered_query("INSERT INTO bb".$n."_sessions VALUES ('$session[hash]','0','".addslashes($session[ipaddress])."','".addslashes($session[useragent])."','$session[lastactivity]','".addslashes($session[request_uri])."','$session[styleid]','$sboardid','$sthreadid')",1);
  }
  bbcookie("cookiehash","$session[hash]",0);
 }
}

if(!isset($wbbuserdata)) {
 if(isset($wbb_userid) && $wbb_userid!=0) $wbbuserdata = $db->query_first("SELECT bb".$n."_users.*, bb".$n."_groups.* FROM bb".$n."_users LEFT JOIN bb".$n."_groups USING (groupid) WHERE userid = '$wbb_userid'");
 else {
  if(!isset($_COOKIE['lastvisit'])) bbcookie("lastvisit",time(),0);
  $wbbuserdata = $db->query_first("SELECT * FROM bb".$n."_groups WHERE default_group = 1");
  $wbbuserdata['userid'] = 0;
  $wbbuserdata['username'] = "guest"; //default guestname
  if(!isset($_COOKIE['lastvisit'])) $wbbuserdata['lastvisit'] = time();
  else $wbbuserdata['lastvisit']=$_COOKIE['lastvisit'];
  $wbbuserdata['lastactivity'] = time();
  $wbbuserdata['showsignatures'] = $default_register_showsignatures;
  $wbbuserdata['showavatars'] = $default_register_showavatars;
  $wbbuserdata['showimages'] = $default_register_showimages;
  $wbbuserdata['timezoneoffset']=$default_timezoneoffset;
  $wbbuserdata['usecookies']=$default_register_usecookies;
  $wbbuserdata['threadview']=$default_register_threadview;
  
  if($wbbuserdata['lastactivity']< time()-$sessiontimeout) {
   bbcookie("lastvisit",$wbbuserdata['lastactivity'],0);
   $wbbuserdata['lastvisit'] = $wbbuserdata['lastactivity'];
  }
 } 
}
$sid = $session['hash'];
if(isset($falsecookiehash)) {
 bbcookie("cookiehash","$session[hash]",0);
 $wbbuserdata['nosessionhash']=0;
}
if($wbbuserdata['nosessionhash']==1) $session['hash']="";
if($isacp!=1 && isset($styleid)) $wbbuserdata['styleid']=$styleid;
if(!$wbbuserdata['dateformat']) $wbbuserdata['dateformat']=$dateformat;
if(!$wbbuserdata['timeformat']) $wbbuserdata['timeformat']=$timeformat;

if($wbbuserdata['userid']!=0) {
 if($wbbuserdata['lastactivity']< time()-$sessiontimeout) {
  $db->unbuffered_query("UPDATE bb".$n."_users SET lastvisit=lastactivity, lastactivity = '".time()."', styleid = '$wbbuserdata[styleid]'".ifelse($wbbuserdata['pmpopup']==2,", pmpopup=1")." WHERE userid = '$wbbuserdata[userid]'",1);
  $wbbuserdata['lastvisit'] = $wbbuserdata['lastactivity'];
  $wbbuserdata['lastactivity'] = time();
 }
 else {
  $db->unbuffered_query("UPDATE bb".$n."_users SET lastactivity = '".time()."', styleid = '$wbbuserdata[styleid]'".ifelse($wbbuserdata['pmpopup']==2,", pmpopup=1")." WHERE userid = '$wbbuserdata[userid]'",1);
  $wbbuserdata['lastactivity'] = time();
 }
}
?>