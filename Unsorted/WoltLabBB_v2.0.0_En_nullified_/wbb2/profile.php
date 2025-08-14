<?php
$filename="profile.php";

require("./global.php");
if($wbbuserdata['canviewprofile']==0) access_error();
require("./acp/lib/class_parse.php");

$userid=intval($_GET['userid']);
if(!$userid) eval("error(\"".$tpl->get("error_falselink")."\");");

$user_info = $db->query_first("SELECT
 u.*,
 uf.*,
 r.rankimages, r.ranktitle,
 a.avatarextension, a.width, a.height
 FROM bb".$n."_users u
 LEFT JOIN bb".$n."_userfields uf USING (userid)
 LEFT JOIN bb".$n."_avatars a ON (a.avatarid=u.avatarid)
 LEFT JOIN bb".$n."_ranks r ON (r.rankid=u.rankid)
 WHERE u.userid='$userid'");
 
if(!$user_info['userid']) eval("error(\"".$tpl->get("error_falselink")."\");");

$regdate = formatdate($wbbuserdata['dateformat'],$user_info['regdate']);
$posts['userid'] = $user_info['userid'];
$posts['username'] = $user_info['username'];

if(($user_info['invisible']==0 || $wbbuserdata['canuseacp']==1) && $user_info['lastactivity']>=time()-$useronlinetimeout*60) eval ("\$user_online = \"".$tpl->get("thread_user_online")."\";");
else eval ("\$user_online = \"".$tpl->get("thread_user_offline")."\";");

if($user_info['invisible']==0 || $wbbuserdata['canuseacp']==1) {
 $la_date = formatdate($wbbuserdata['dateformat'],$user_info['lastactivity']);
 $la_time = formatdate($wbbuserdata['timeformat'],$user_info['lastactivity']);
}
else {
 eval ("\$la_date = \"".$tpl->get("profile_nodeclaration")."\";");	
 $la_time="";	
}
	
$regdays = (time() - $user_info[regdate]) / 86400;
if ($regdays < 1) $postperday = "$user_info[userposts]";
else $postperday = sprintf("%.2f",($user_info['userposts'] / $regdays)); 
	
if($user_info['usertext']) $user_text=parse::textwrap($user_info['usertext'],40);
if($user_info['gender']) {
 if($user_info['gender']==1) eval ("\$gender = \"".$tpl->get("profile_male")."\";");
 else eval ("\$gender  = \"".$tpl->get("profile_female")."\";");
}
else eval ("\$gender = \"".$tpl->get("profile_nodeclaration")."\";");
		
if($user_info['title']) $user_info['ranktitle']=$user_info['title'];
$rankimages=formatRI($user_info['rankimages']);
 
if($user_info['avatarid'] && $showavatar==1 && $wbbuserdata['showavatars']==1) {
 $avatarname="images/avatars/avatar-$user_info[avatarid].$user_info[avatarextension]";
 $avatarwidth=$user_info['width'];
 $avatarheight=$user_info['height'];
 if($user_info['avatarextension']=="swf" && $allowflashavatar==1) {
  eval ("\$useravatar = \"".$tpl->get("avatar_flash")."\";");
 }
 elseif($posts['avatarextension']!="swf") eval ("\$useravatar = \"".$tpl->get("avatar_image")."\";");
}

if($user_info['showemail']==1) $useremail = makehreftag("mailto:$user_info[email]",$user_info['email']);
else eval ("\$useremail = \"".$tpl->get("profile_nodeclaration")."\";"); 
	
if($user_info['homepage']) $userhomepage = makehreftag($user_info['homepage'],$user_info['homepage'],"_blank");
else eval ("\$userhomepage = \"".$tpl->get("profile_nodeclaration")."\";");
	
if(!$user_info['icq']) eval ("\$user_info[icq] = \"".$tpl->get("profile_nodeclaration")."\";");
if(!$user_info['aim']) eval ("\$user_info[aim] = \"".$tpl->get("profile_nodeclaration")."\";");
if(!$user_info['yim']) eval ("\$user_info[yim] = \"".$tpl->get("profile_nodeclaration")."\";");
if(!$user_info['msn']) eval ("\$user_info[msn] = \"".$tpl->get("profile_nodeclaration")."\";");
	
if($user_info['birthday'] && $user_info['birthday']!="0000-00-00") {
 $birthday_array = explode("-",$user_info['birthday']);
 if($birthday_array[0]=="0000") $birthday =  $birthday_array[2].".".$birthday_array[1].".";
 else $birthday =  $birthday_array[2].".".$birthday_array[1].".".$birthday_array[0];
}
else eval ("\$birthday = \"".$tpl->get("profile_nodeclaration")."\";");
	
$result = $db->query("SELECT profilefieldid, title FROM bb".$n."_profilefields".ifelse($wbbuserdata['canuseacp']==0," WHERE hidden=0")." ORDER BY fieldorder ASC");
while($row=$db->fetch_array($result)) {
 $fieldid="field".$row['profilefieldid'];
 if(!$user_info[$fieldid]) eval ("\$user_info[$fieldid] = \"".$tpl->get("profile_nodeclaration")."\";");
 else $user_info[$fieldid]=parse::textwrap($user_info[$fieldid],50);
 eval ("\$profilefields .= \"".$tpl->get("profile_userfield")."\";");
}
if($profilefields) eval ("\$hr = \"".$tpl->get("profile_hr")."\";");

if($showlastpostinprofile==1 && $user_info['userposts']!=0) {
 $boardids="";
 
 $result = $db->query("SELECT boardid FROM bb".$n."_permissions WHERE groupid='$wbbuserdata[groupid]' AND boardpermission = 1");
 while($row=$db->fetch_array($result)) $permissioncache[$row['boardid']] = 1;
 if($wbbuserdata['userid'] && $useuseraccess==1) {
  $result = $db->query("SELECT boardid, boardpermission FROM bb".$n."_access WHERE userid='$wbbuserdata[userid]' AND boardpermission = 1");
  while($row=$db->fetch_array($result)) $permissioncache[$row['boardid']] = $row['boardpermission'];
 }
 if(is_array($permissioncache)) while(list($key,$val)=each($permissioncache)) if($val==1) $boardids.=",".$key;
 
 if($boardids) {
  $lastpost=$db->query_first("SELECT p.postid, p.posttime, t.topic, t.boardid, b.title FROM bb".$n."_posts p, bb".$n."_threads t
  LEFT JOIN bb".$n."_boards b ON (t.boardid=b.boardid)
  WHERE p.threadid=t.threadid AND t.boardid IN (0$boardids) AND b.password='' AND p.userid = '$userid'
  ORDER BY p.posttime DESC",1);
  if($lastpost['postid']) {
   $lastpostdate=formatdate($wbbuserdata['dateformat'],$lastpost['posttime']);
   $lastposttime=formatdate($wbbuserdata['timeformat'],$lastpost['posttime']);
    
   eval ("\$profile_lastpost = \"".$tpl->get("profile_lastpost")."\";");
  }
 }
}

if($user_info['showemail']==0 && $user_info['usercanemail']==1) eval ("\$btn_email = \"".$tpl->get("thread_formmail")."\";");
if($user_info['userposts']!=0) eval ("\$btn_search = \"".$tpl->get("thread_search")."\";");
if($user_info['receivepm']==1 && $wbbuserdata['canusepms']==1) eval ("\$btn_pm = \"".$tpl->get("thread_pm")."\";");

if($userratings==1) $userrating=userrating($user_info['ratingcount'],$user_info['ratingpoints'],$user_info['userid']);
else $userrating="";

if($userlevels==1) $userlevel=userlevel($user_info['userposts'],$user_info['regdate']);
else $userlevel="";
  	
eval("\$tpl->output(\"".$tpl->get("profile")."\");");
?>