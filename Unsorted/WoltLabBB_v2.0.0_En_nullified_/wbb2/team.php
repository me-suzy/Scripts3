<?php
$filename="team.php";

require("./global.php");

$users = $db->query("SELECT
 u.userid, u.username, u.invisible, u.receivepm, u.lastactivity,
 uf.*,
 g.canuseacp
 FROM bb".$n."_groups g, bb".$n."_users u
 LEFT JOIN bb".$n."_userfields uf ON (u.userid=uf.userid)
 WHERE u.groupid = g.groupid AND (g.canuseacp = 1 OR g.issupermod = 1) ORDER BY g.canuseacp DESC, u.username ASC");

$acount=1;
$scount=1;	

while($user = $db->fetch_array($users)) {
 $pm="";
 
 $posts['userid']=$user['userid'];
 $posts['username']=$user['username'];
 if(($user['invisible']==0 || $wbbuserdata['canuseacp']==1) && $user['lastactivity']>=time()-$useronlinetimeout*60) eval ("\$user_online = \"".$tpl->get("thread_user_online")."\";");
 else eval ("\$user_online = \"".$tpl->get("thread_user_offline")."\";");
 if($wbbuserdata['canusepms']==1 && $user['receivepm']!=0) eval ("\$pm = \"".$tpl->get("thread_pm")."\";");
 
 if($user['canuseacp']) {
  $tdbgcolor=getone($acount,"{tablecolorb}","{tablecolora}");
  $tdid=getone($acount,"tableb","tablea");
  eval("\$adminbits .= \"".$tpl->get("team_adminbit")."\";");
  $acount++;
 }
 else {
  $tdbgcolor=getone($scount,"{tablecolorb}","{tablecolora}");
  $tdid=getone($scount,"tableb","tablea");
  eval("\$supermodbits .= \"".$tpl->get("team_adminbit")."\";");
  $scount++;
 }
}

$boardids="";
$boardcache=array();
$modcache=array();
if($wbbuserdata['userid'] && $useuseraccess==1) {
 $result=$db->query("SELECT b.boardid, b.title, b.invisible,
 IF(a.userid IS NOT NULL,a.boardpermission,p.boardpermission) AS boardpermission
 FROM bb".$n."_boards b
 LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
 LEFT JOIN bb".$n."_access a ON (a.userid='$wbbuserdata[userid]' AND b.boardid=a.boardid)");	
}
else $result = $db->query("SELECT b.boardid, b.title, b.invisible, p.boardpermission FROM bb".$n."_boards b LEFT JOIN bb".$n."_permissions p ON (b.boardid=p.boardid AND p.groupid='$wbbuserdata[groupid]')");
while($row=$db->fetch_array($result)) {
 if($row['invisible']==2 || ($row['invisible']==1 && !$row['boardpermission'])) continue; 
 $boardcache[$row['boardid']]=$row;
 $boardids.=",".$row['boardid'];
}

$count=1;
$result = $db->query("SELECT userid, boardid FROM bb".$n."_moderators WHERE boardid IN (0$boardids) ORDER BY userid ASC");
while ($row = $db->fetch_array($result)) $modcache[$row['userid']][] = $row['boardid'];

$users = $db->query("SELECT
 u.userid, u.username, u.invisible, u.receivepm, u.lastactivity,
 uf.*
 FROM bb".$n."_users u, bb".$n."_groups g
 LEFT JOIN bb".$n."_userfields uf ON (u.userid=uf.userid)
 WHERE u.groupid = g.groupid AND g.ismod=1 ORDER BY u.username ASC");

while ($user = $db->fetch_array($users)) {
 $forumbits="";
 $pm="";
 
 $tdbgcolor=getone($count,"{tablecolorb}","{tablecolora}");
 $tdid=getone($count,"tableb","tablea");
  
 $posts['userid']=$user['userid'];
 $posts['username']=$user['username'];
 if(($user['invisible']==0 || $wbbuserdata['canuseacp']==1) && $user['lastactivity']>=time()-$useronlinetimeout*60) eval ("\$user_online = \"".$tpl->get("thread_user_online")."\";");
 else eval ("\$user_online = \"".$tpl->get("thread_user_offline")."\";");
 if($wbbuserdata['canusepms']==1 && $user['receivepm']!=0) eval ("\$pm = \"".$tpl->get("thread_pm")."\";");

 for($i=0;$i<count($modcache[$user['userid']]);$i++) {
  $boardid=$modcache[$user['userid']][$i];
  if(!$boardcache[$boardid]['boardpermission'] && $boardcache[$boardid]['invisible']==1) continue;
  $forumbits .= makehreftag("board.php?boardid=$boardid&sid=$session[hash]",$boardcache[$boardid]['title'])."<br>";
 }

 eval("\$moderatorbits .= \"".$tpl->get("team_modbit")."\";");
 $count++;
}

unset($boardcache);

if($showboardjump==1) $boardjump=makeboardjump(0);
eval("\$tpl->output(\"".$tpl->get("team")."\");");
?>