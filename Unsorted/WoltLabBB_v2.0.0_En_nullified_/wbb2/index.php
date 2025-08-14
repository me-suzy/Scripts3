<?php
$filename="index.php";

require("./global.php");
require("./acp/lib/class_useronline.php");

if(isset($_COOKIE['hidecats'])) $hidecats=decode_cookie($_COOKIE['hidecats']);
else $hidecats=array();

if(isset($_GET['hidecat'])) {
 $hidecats[$_GET['hidecat']]=1;
 if($wbbuserdata['usecookies']==1) encode_cookie("hidecats",time()+3600*24*365,false);
 else encode_cookie("hidecats");
}
if(isset($_GET['showcat'])) {
 $hidecats[$_GET['showcat']]=0;
 if($wbbuserdata['usecookies']==1) encode_cookie("hidecats",time()+3600*24*365,false);
 else encode_cookie("hidecats");
}

if(isset($_COOKIE['boardvisit'])) $boardvisit=decode_cookie($_COOKIE['boardvisit']);
else $boardvisit=array();

if(isset($_COOKIE['threadvisit'])) $threadvisit=decode_cookie($_COOKIE['threadvisit']);
else $threadvisit=array();

$boardcache=array();
$permissioncache=array();
$modcache=array();

switch($boardordermode) {
 case 1: $boardorder="b.title ASC"; break;
 case 2: $boardorder="b.title DESC"; break;
 case 3: $boardorder="b.lastposttime DESC"; break;
 default: $boardorder="b.boardorder ASC"; break;
}
$activtime=time()-60*$useronlinetimeout;

$result = $db->query("
 SELECT
 b.*".ifelse($showlastposttitle==1,", t.topic, t.prefix AS threadprefix, i.*")."
 ".ifelse($showuseronlineinboard==1,", COUNT(s.hash) AS useronline")."
 FROM bb".$n."_boards b
 ".ifelse($showlastposttitle==1,"LEFT JOIN bb".$n."_threads t ON (t.threadid=b.lastthreadid)
 LEFT JOIN bb".$n."_icons i USING (iconid)")."
 ".ifelse($showuseronlineinboard==1,"LEFT JOIN bb".$n."_sessions s ON (s.boardid=b.boardid AND s.lastactivity>='$activtime')")."
 WHERE b.invisible<2
 ".ifelse($showuseronlineinboard==1,"GROUP BY b.boardid")."
 ORDER by b.parentid ASC, $boardorder");
while ($row = $db->fetch_array($result)) $boardcache[$row['parentid']][$row['boardorder']][$row['boardid']] = $row;

if($showuseronlineinboard==2) {
 $userinboard=array();
 $online = new useronline($wbbuserdata['canuseacp'],$modids,$smodids,$adminids,$wbbuserdata['buddylist']);
 $result=$db->query("SELECT s.userid, s.boardid, u.username, u.groupid, u.invisible FROM bb".$n."_sessions s LEFT JOIN bb".$n."_users u USING (userid) WHERE s.userid>0 AND s.lastactivity>='$activtime' ORDER BY u.username ASC");	
 while($row=$db->fetch_array($result)) $userinboard[$row['boardid']][]=$row;	
}

$result = $db->query("SELECT boardid, threadid, lastposttime FROM bb".$n."_threads WHERE visible = 1 AND lastposttime > '$wbbuserdata[lastvisit]' AND closed <> 3");
while($row=$db->fetch_array($result)) $visitcache[$row['boardid']][$row['threadid']]=$row['lastposttime'];

$result = $db->query("SELECT * FROM bb".$n."_permissions WHERE groupid = '$wbbuserdata[groupid]'");
while ($row = $db->fetch_array($result)) $permissioncache[$row['boardid']] = $row;

if($wbbuserdata['userid'] && $useuseraccess==1) {
 $result = $db->query("SELECT * FROM bb".$n."_access WHERE userid = '$wbbuserdata[userid]'");
 while ($row = $db->fetch_array($result)) $permissioncache[$row['boardid']] = $row;
}

$result = $db->query("SELECT bb".$n."_moderators.*, username FROM bb".$n."_moderators LEFT JOIN bb".$n."_users USING (userid) ORDER BY username ASC");
while ($row = $db->fetch_array($result)) $modcache[$row['boardid']][] = $row;

$boardbit = makeboardbit(0);

$index_pms="";
$quicklogin="";
$index_showevents="";
$index_useronline="";
$index_stats="";

/* ############## STATS ############## */
if($showstats==1) {
 $members=$db->query_first("SELECT COUNT(*) AS members, MAX(userid) AS userid FROM bb".$n."_users WHERE activation = 1");
 $newestmember=$db->query_first("SELECT userid, username FROM bb".$n."_users WHERE userid = '$members[userid]'");
 $posts=$db->query_first("SELECT COUNT(*) AS posts FROM bb".$n."_posts");
 $threads=$db->query_first("SELECT COUNT(*) AS threads FROM bb".$n."_threads");
 
 $installdays = (time() - $installdate) / 86400;
 if ($installdays < 1) $postperday = $posts['posts'];
 else $postperday = sprintf("%.2f",($posts['posts'] / $installdays)); 

 eval ("\$index_stats = \"".$tpl->get("index_stats")."\";");
}
/* ############## USERONLINE ############## */
if($showuseronline==1) {
 $guestcount=0;
 $membercount=0;
 $invisiblecount=0;
 $online = new useronline($wbbuserdata['canuseacp'],$modids,$smodids,$adminids,$wbbuserdata['buddylist']);
 $result = $db->query("SELECT bb".$n."_sessions.userid, username, groupid, invisible FROM bb".$n."_sessions LEFT JOIN bb".$n."_users USING (userid) WHERE bb".$n."_sessions.lastactivity >= '".(time()-60*$useronlinetimeout)."' ORDER BY username ASC"); 
 while($row = $db->fetch_array($result)) {
  if($row['userid']==0) {
   $guestcount++;
   continue;	
  }
  $membercount++;
  if($row['invisible']==1) $invisiblecount++;
  $online->user($row['userid'],$row['username'],$row['groupid'],$row['invisible']);
 }
 $useronline=$online->useronlinebit;
 $totaluseronline = $membercount+$guestcount;
 if($totaluseronline>$rekord) {
  $rekord=$totaluseronline;
  $rekordtime=time();
  $db->unbuffered_query("UPDATE bb".$n."_options SET value='$rekord' WHERE varname='rekord'",1);
  $db->unbuffered_query("UPDATE bb".$n."_options SET value='$rekordtime' WHERE varname='rekordtime'",1);
  require ("./acp/lib/class_options.php");
  $option=new options("acp/lib");
  $option->write();
 }
 $rekorddate = formatdate($wbbuserdata['dateformat'],$rekordtime);
 $rekordtime = formatdate($wbbuserdata['timeformat'],$rekordtime);
 eval ("\$index_useronline = \"".$tpl->get("index_showuseronline")."\";");
}
/* ############## BIRTHDAYS ############## */
if($showbirthdays==1  && $wbbuserdata['canviewcalender']!=0) {
 $currentdate = formatdate("m-d", time());
 $currentyear = intval(formatdate("Y", time()));
 $result = $db->query("SELECT userid, username, birthday FROM bb".$n."_users WHERE birthday LIKE '%-$currentdate' AND activation = 1 ORDER BY username ASC");
 while($row = $db->fetch_array($result)) {
  $birthyear = intval(substr($row[birthday], 0, 4));
  $age = $currentyear-$birthyear;
  if($age<1 || $age>200) $age="";
  else $age="&nbsp;($age)";
  if(isset($birthdaybit)) eval ("\$birthdaybit .= \", ".$tpl->get("index_birthdaybit")."\";");
  else eval ("\$birthdaybit = \"".$tpl->get("index_birthdaybit")."\";");
 }
 $db->free_result($result);
 if(isset($birthdaybit)) eval ("\$birthdays = \"".$tpl->get("index_birthdays")."\";");
}

/* ############## EVENTS ############## */
if($showevents==1 && $wbbuserdata['canviewcalender']!=0) {
 $currentdate = date("Y-m-d"); 
 $result = $db->query("SELECT eventid, subject, public FROM bb".$n."_events WHERE eventdate = '$currentdate' AND (public=2 OR (public=1 AND groupid = '$wbbuserdata[groupid]') OR (public=0 AND userid = '$wbbuserdata[userid]')) ORDER BY public ASC, subject ASC");
 while($row = $db->fetch_array($result)) {
  if($row['public']==1) eval ("\$eventbit .= \"".ifelse(isset($eventbit),", ","")."".$tpl->get("index_publicevent")."\";");
  else eval ("\$eventbit .= \"".ifelse(isset($eventbit),", ","")."".$tpl->get("index_privateevent")."\";");
 }
 $db->free_result($result);
 if(isset($eventbit)) eval ("\$events = \"".$tpl->get("index_events")."\";");
}

if(isset($birthdays) || isset($events)) eval ("\$index_showevents = \"".$tpl->get("index_showevents")."\";");

if(!$wbbuserdata['userid']) {
 eval ("\$welcome = \"".$tpl->get("index_welcome")."\";");
 eval ("\$quicklogin = \"".$tpl->get("index_quicklogin")."\";");
}
else {
 $currenttime=formatdate($wbbuserdata['timeformat'],time());
 $toffset=ifelse($wbbuserdata['timezoneoffset']>=0,"+").$wbbuserdata['timezoneoffset'];
 $lastvisitdate = formatdate($wbbuserdata['dateformat'],$wbbuserdata['lastvisit']);
 $lastvisittime = formatdate($wbbuserdata['timeformat'],$wbbuserdata['lastvisit']);
 eval ("\$welcome = \"".$tpl->get("index_hello")."\";");
 if($wbbuserdata['canusepms']==1 && $showpmonindex==1) {
  $counttotal=0; $countunread=0; $countnew=0;
  $result = $db->query("SELECT view, sendtime FROM bb".$n."_privatemessage WHERE deletepm <> 1 AND recipientid = '$wbbuserdata[userid]'");
  while($row=$db->fetch_array($result)) {
   $counttotal++;
   if($row['view']==0) {
    $countunread++;
    if($row['sendtime']>$wbbuserdata['lastvisit']) $countnew++;
   }
  }
  
  if($countnew>0) eval ("\$new_notnew = \"".$tpl->get("index_newpm")."\";");
  else eval ("\$new_notnew = \"".$tpl->get("index_nonewpm")."\";");
  eval ("\$index_pms = \"".$tpl->get("index_pms")."\";");
 }
}
eval("\$tpl->output(\"".$tpl->get("index")."\");"); 
?>