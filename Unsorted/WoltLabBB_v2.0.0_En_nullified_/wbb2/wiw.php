<?php
$filename="wiw.php";
require("global.php");
require("acp/lib/class_useronline.php");

// Define
if(!isset($_GET['sortby'])) $_GET['sortby']="";
if(!isset($_GET['order'])) $_GET['order']="";
$sel_sortby['username']="";
$sel_sortby['ipaddress']="";
$sel_sortby['useragent']="";
$sel_sortby['lastactivity']="";
$sel_sortby['request_uri']="";
$sel_order['ASC']="";
$sel_order['DESC']="";
$useronline="";

if($wiw_autorefresh > 0) eval ("\$wiw_auto_refresh = \"".$tpl->get("wiw_auto_refresh")."\";");

switch($_GET['sortby']) {
 case "username": break;
 case "ipaddress": break;
 case "useragent": break;
 case "lastactivity": break;
 case "request_uri": break;
 default: $_GET['sortby'] = "lastactivity"; break;
}

switch($_GET['order']) {
 case "ASC": break;
 case "DESC": break;
 default: $_GET['order'] = "DESC"; break;
}

$sel_sortby[$_GET['sortby']]=" selected";
$sel_order[$_GET['order']]=" selected";
	
$i=0;
$boardids="";
$threadids="";
$result = $db->query("SELECT s.*, u.username, u.invisible, u.groupid, 0 AS script FROM bb".$n."_sessions s LEFT JOIN bb".$n."_users u USING (userid) WHERE s.lastactivity >= '".(time()-60*$useronlinetimeout)."'".ifelse($wiw_showonlyusers==1," AND s.userid<>0")." ORDER BY ".$_GET['sortby']." ".$_GET['order']."");
while($row=$db->fetch_array($result)) {
 list($script,)=split('[?]',$row['request_uri']);
 $row['script']=$script;
 if($script=="board.php" || $script=="newthread.php") $boardids.=",".$row['boardid'];
 elseif($script=="thread.php" || $script=="addreply.php") $threadids.=",".$row['threadid'];				
 $useronlinecache[$i]=$row;
 $i++;
}

$permissioncache=array();
$result = $db->query("SELECT p.boardpermission, IF(b.password='',p.boardpermission,-1) AS boardpermission2, p.boardid FROM bb".$n."_permissions p LEFT JOIN bb".$n."_boards b USING(boardid) WHERE p.groupid = '$wbbuserdata[groupid]'");
while ($row = $db->fetch_array($result)) {
 $permissioncache[$row['boardid']] = $row['boardpermission'];
 $permissioncache2[$row['boardid']] = $row['boardpermission2'];
}
if($wbbuserdata['userid'] && $useuseraccess==1) {
 $result = $db->query("SELECT boardid, boardpermission FROM bb".$n."_access WHERE userid = '".$wbbuserdata['userid']."'");
 while ($row = $db->fetch_array($result)) {
  $permissioncache[$row['boardid']] = $row['boardpermission'];
  if($permissioncache2[$row['boardid']]!=-1) $permissioncache2[$row['boardid']] = $row['boardpermission'];
 }
}

$permissions="";
$permissions2="";

if(is_array($permissioncache)) while(list($key,$val)=each($permissioncache)) if($val==1) $permissions.=",".$key;
if(is_array($permissioncache2)) while(list($key,$val)=each($permissioncache2)) if($val==1) $permissions2.=",".$key;

if($boardids) {
 $result = $db->query("SELECT boardid, title FROM bb".$n."_boards WHERE boardid IN (0$boardids) AND (invisible=0 OR boardid IN (0$permissions))");
 while($row=$db->fetch_array($result)) $boardcache[$row['boardid']]=$row['title'];
}

if($threadids) {
 $result = $db->query("SELECT threadid, topic FROM bb".$n."_threads WHERE threadid IN (0$threadids) AND boardid IN (0$permissions2)");
 while($row=$db->fetch_array($result)) $threadcache[$row['threadid']]=$row['topic'];
}

$guestcount=1;
$online = new useronline($wbbuserdata['canuseacp'],$modids,$smodids,$adminids,$wbbuserdata['buddylist']);
for($i=0;$i<count($useronlinecache);$i++) {
 if($useronlinecache[$i]['invisible']==1 && $wbbuserdata['canuseacp']!=1) continue; 
 
 if(!$useronlinecache[$i]['userid']) {
  eval ("\$username = \"".$tpl->get("wiw_guest")."\";");
  $guestcount++;
 }
 else $username=$online->parse($useronlinecache[$i]['userid'],$useronlinecache[$i]['username'],$useronlinecache[$i]['groupid'],$useronlinecache[$i]['invisible']);
 $time = formatdate($wbbuserdata['timeformat'], $useronlinecache[$i]['lastactivity']);
 switch($useronlinecache[$i]['script']) {
  CASE "index.php":
   eval ("\$location = \"".$tpl->get("wiw_index")."\";");
   break;
  CASE "wiw.php":
   eval ("\$location = \"".$tpl->get("wiw_wiw")."\";");
   break;
  CASE "register.php":
   eval ("\$location = \"".$tpl->get("wiw_register")."\";");
   break;
  CASE "usercp.php":
   eval ("\$location = \"".$tpl->get("wiw_usercp")."\";");
   break;
  CASE "login.php":
   eval ("\$location = \"".$tpl->get("wiw_login")."\";");
   break;
  CASE "profile.php":
   eval ("\$location = \"".$tpl->get("wiw_profile")."\";");
   break;
  CASE "logout.php":
   eval ("\$location = \"".$tpl->get("wiw_logout")."\";");
   break;
  CASE "memberslist.php":
   eval ("\$location = \"".$tpl->get("wiw_memberlist")."\";");
   break;
  CASE "search.php":
   eval ("\$location = \"".$tpl->get("wiw_search")."\";");
   break;
  CASE "calender.php":
   eval ("\$location = \"".$tpl->get("wiw_calender")."\";");
   break;
  CASE "team.php":
   eval ("\$location = \"".$tpl->get("wiw_team")."\";");
   break;
  CASE "pms.php":
   eval ("\$location = \"".$tpl->get("wiw_pms")."\";");
   break;
  CASE "modcp.php":
   eval ("\$location = \"".$tpl->get("wiw_modcp")."\";");
   break;
  CASE "editpost.php":
   eval ("\$location = \"".$tpl->get("wiw_editpost")."\";");
   break;
  CASE "board.php":
   if(isset($boardcache[$useronlinecache[$i]['boardid']])){
	  $boardlocation = $useronlinecache[$i]['boardid'];
	  $boardname = $boardcache[$useronlinecache[$i]['boardid']];
	  eval ("\$location = \"".$tpl->get("wiw_board")."\";");
  }else{
	  eval ("\$location = \"".$tpl->get("wiw_unknown")."\";");
  }
   break;
  CASE "newthread.php":
   if(isset($boardcache[$useronlinecache[$i]['boardid']])){
	  $boardlocation = $useronlinecache[$i]['boardid'];
	  $boardname = $boardcache[$useronlinecache[$i]['boardid']];
	  eval ("\$location = \"".$tpl->get("wiw_newthread")."\";");
  }else{
	  eval ("\$location = \"".$tpl->get("wiw_unknown")."\";");
  }
   break;
  CASE "thread.php":
   if(isset($threadcache[$useronlinecache[$i]['threadid']])){
	  $threadlocation = $useronlinecache[$i]['threadid'];
	  $threadname = $threadcache[$useronlinecache[$i]['threadid']];
	  eval ("\$location = \"".$tpl->get("wiw_thread")."\";");
  }else{
	  eval ("\$location = \"".$tpl->get("wiw_unknown")."\";");
  }
   break;
  CASE "addreply.php":
   if(isset($threadcache[$useronlinecache[$i]['threadid']])){
	  $threadlocation = $useronlinecache[$i]['threadid'];
	  $threadname = $threadcache[$useronlinecache[$i]['threadid']];
	  eval ("\$location = \"".$tpl->get("wiw_addreply")."\";");
  }else{
	  eval ("\$location = \"".$tpl->get("wiw_unknown")."\";");
  }
   break;
  DEFAULT:
   eval ("\$location = \"".$tpl->get("wiw_unknown")."\";");
   break;
 }
 
 if($wbbuserdata['canuseacp']==1) {
  $ipadress=$useronlinecache[$i]['ipaddress'];
  $browser=$useronlinecache[$i]['useragent'];
  if(strlen($browser)>50) $browser=substr($browser, 0, 50)."...";
 }
 else {
  eval ("\$ipadress = \"".$tpl->get("wiw_line")."\";");
  eval ("\$browser = \"".$tpl->get("wiw_line")."\";");
 }
 
eval ("\$useronline .= \"".$tpl->get("wiw_userbit")."\";");
}

eval("\$tpl->output(\"".$tpl->get("wiw")."\");");
?>