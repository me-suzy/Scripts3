<?php
$filename="thread.php";

require("./global.php");
require("./acp/lib/class_parse.php");

if(!isset($postid) && !isset($threadid)) eval("error(\"".$tpl->get("error_falselink")."\");");

if($wbbuserdata['canuseacp']==1 || $wbbuserdata['issupermod']==1 || ($wbbuserdata['ismod']==1 && $modpermissions['userid'])) $visible="";
else $visible="AND visible=1";

if($_REQUEST['goto']=="lastpost") {
 $result = $db->query_first("SELECT postid FROM bb".$n."_posts WHERE threadid = '$threadid' $visible ORDER BY posttime DESC LIMIT 1");
 header("Location: thread.php?sid=$session[hash]&postid=$result[postid]#post$result[postid]");
 exit();
}

if(isset($_COOKIE['threadvisit'])) $threadvisit=decode_cookie($_COOKIE['threadvisit']);
else $threadvisit=array();

if(isset($_COOKIE['postvisit'])) $postvisit=decode_cookie($_COOKIE['postvisit']);
else $postvisit=array();

if($_REQUEST['goto']=="firstnew") {
 if($threadvisit[$threadid]<$wbbuserdata['lastvisit']) $threadvisit[$threadid]=$wbbuserdata['lastvisit'];
 $result = $db->query_first("SELECT postid FROM bb".$n."_posts WHERE threadid='$threadid' AND posttime>'$threadvisit[$threadid]' $visible ORDER BY posttime ASC",1);
 if($result['postid']) header("Location: thread.php?sid=$session[hash]&postid=$result[postid]#post$result[postid]");
 else header("Location: thread.php?goto=lastpost&threadid=$threadid&sid=$session[hash]");
 exit();
}

if($_REQUEST['goto']=="nextnewest") {
 $result = $db->query_first("SELECT threadid FROM bb".$n."_threads WHERE visible = 1 AND lastposttime>'$thread[lastposttime]' AND closed <> 3 AND boardid = '$boardid' ORDER BY lastposttime ASC",1);
 if(!$result['threadid']) eval("error(\"".$tpl->get("error_nonextnewest")."\");");
 $threadid=$result['threadid'];
 $thread = $db->query_first("SELECT * FROM bb".$n."_threads WHERE threadid = '$threadid'");
}

if($_REQUEST['goto']=="nextoldest") {
 $result = $db->query_first("SELECT threadid FROM bb".$n."_threads WHERE visible = 1 AND lastposttime<'$thread[lastposttime]' AND closed <> 3 AND boardid = '$boardid' ORDER BY lastposttime DESC",1);
 if(!$result['threadid']) eval("error(\"".$tpl->get("error_nonextoldest")."\");");
 $threadid=$result['threadid'];
 $thread = $db->query_first("SELECT * FROM bb".$n."_threads WHERE threadid = '$threadid'");
}

if(isset($_REQUEST['threadview'])) $threadview=intval($_REQUEST['threadview']);
else $threadview=$wbbuserdata['threadview'];


if($wbbuserdata['umaxposts']) $postsperpage=$wbbuserdata['umaxposts'];
elseif($board['postsperpage']) $postsperpage=$board['postsperpage'];
else $postsperpage=$default_postsperpage;
$postorder=$board['postorder'];

if(isset($postid) && $threadview==0) {
 if($postorder==0) $result = $db->query_first("SELECT COUNT(*) AS posts FROM bb".$n."_posts WHERE threadid='$threadid' AND postid<='$postid' $visible");
 else $result = $db->query_first("SELECT COUNT(*) AS posts FROM bb".$n."_posts WHERE threadid='$threadid' AND postid>='$postid' $visible");
 $_GET['page']=ceil($result['posts']/$postsperpage);
}

$db->unbuffered_query("UPDATE bb".$n."_threads SET views=views+1 WHERE threadid='$threadid'",1);

$boardnavcache=array();
if($showboardjump==1) $boardjump=makeboardjump($boardid);
$navbar=getNavbar($board['parentlist']);
eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");

if(trim($wbbuserdata['ignorelist'])!="") $ignore = "AND bb".$n."_posts.userid<>".implode(" AND bb".$n."_posts.userid<>",explode(' ',$wbbuserdata['ignorelist']));
else $ignore = "";


/* flat view */
if($threadview==0) {
 $result = $db->query_first("SELECT COUNT(*) FROM bb".$n."_posts WHERE threadid = '$threadid' $visible $ignore");
 $postcount = $result[0];

 if(isset($_GET['page'])) {
  $page=intval($_GET['page']);
  if($page==0) $page=1;
 }
 else $page=1;
 $pages = ceil($postcount/$postsperpage);
 if($pages>1) $pagelink=makepagelink("thread.php?threadid=$threadid&sid=$session[hash]&threadview=$threadview&hilight=$hilight&hilightuser=$hilightuser",$page,$pages,$showpagelinks-1);
 
 $postids="";
 $result = $db->query("SELECT postid FROM bb".$n."_posts WHERE threadid = '$threadid' $ignore $visible ORDER BY posttime ".ifelse($postorder,"DESC","ASC")." LIMIT ".($postsperpage*($page-1)).",".$postsperpage);
 while($row=$db->fetch_array($result)) $postids .= ",".$row[postid];
}
/* threaded view */
else {
 $firstpostid=0;
 $result = $db->query("SELECT postid, parentpostid, posttopic, username, userid, posttime FROM bb".$n."_posts WHERE threadid='$threadid' $ignore $visible ORDER BY parentpostid ASC, posttime ASC");
 while ($row = $db->fetch_array($result)) {
  if($firstpostid!=0) {
   if($row['parentpostid']==0) $row['parentpostid']=$firstpostid;
  }
  else $firstpostid=$row['postid'];
  $postcache[$row['parentpostid']][$row['postid']] = $row;
 }
 if(!isset($postid)) $postid=$firstpostid;
 $postlist = makepostlist(0,$postid,$thread['topic']);
 $postids=",$postid";
}


$parse = new parse($docensor,75,$board['allowsmilies'],$board['allowbbcode'],$wbbuserdata['showimages'],$_GET['hilight'],$usecode);

if($showuserfieldsinthread==1) {
 $userfields=", uf.*";
 $userfieldsjoin="LEFT JOIN bb".$n."_userfields uf ON (uf.userid=p.userid)";
 $result=$db->query("SELECT profilefieldid, title FROM bb".$n."_profilefields WHERE showinthread=1".ifelse($wbbuserdata['canuseacp']==0," AND hidden=0")." ORDER BY fieldorder ASC");
 while($row=$db->fetch_array($result)) $userfieldcache[]=$row;
}
else {
 $userfields="";
 $userfieldsjoin="";
}

if($showavatar==1) {
 $avatar=", av.avatarid, av.avatarextension, av.width, av.height";
 $avatarjoin="LEFT JOIN bb".$n."_avatars av ON (u.avatarid=av.avatarid)";
}
else {
 $avatar="";
 $avatarjoin="";	
}

if($board['allowicons']==1) {
 $icon=", i.iconpath, i.icontitle";
 $iconjoin="LEFT JOIN bb".$n."_icons i ON (p.iconid=i.iconid)";
}
else {
 $icon="";
 $iconjoin="";	
}
/*
if($showonlineinthread==1) {
 $sessionlastactivity="DISTINCT s.userid, s.lastactivity,";
 $sessionjoin="LEFT JOIN bb".$n."_sessions s ON (p.userid=s.userid AND s.userid>0)";
}
else {
 $sessionlastactivity="";
 $sessionjoin="";
}
*/
$result = $db->query("SELECT
p.*,
u.userposts,
u.regdate,
u.signature,
u.email,
u.homepage,
u.icq,
u.aim,
u.yim,
u.msn,
u.showemail,
u.receivepm,
u.usercanemail,
u.ratingcount,
u.ratingpoints,
u.gender,
u.invisible,
u.title,
u.lastactivity,
r.ranktitle, r.rankimages,
at.attachmentname, at.attachmentid, at.attachmentextension, at.attachmentsize, at.counter
$userfields
$icon
$avatar
FROM bb".$n."_posts p 
LEFT JOIN bb".$n."_users u USING (userid)
LEFT JOIN bb".$n."_ranks r USING (rankid)
LEFT JOIN bb".$n."_attachments at ON (p.attachmentid=at.attachmentid)
$userfieldsjoin
$iconjoin
$avatarjoin
WHERE p.postid IN (0$postids)
ORDER BY p.posttime ".ifelse($postorder,"DESC","ASC"));

$count=0;
while($posts=$db->fetch_array($result)) {
 if($userfieldcache) reset($userfieldcache);
 $userrating="";
 $signature="";
 $threadstarter="";
 $lastedit="";
 $search="";
 $homie="";
 $email="";
 $homepage="";
 $icq="";
 $aim="";
 $yim="";
 $attachmentbit="";
 $user_online="";
 $userfields="";
 $gender="";
 $useravatar="";
 $rankimages="";
 $setvisible="";
 $pm="";
 $userlevel="";
 $invisible="";
 
 /** mod/admin option -> set visible post **/
 if($posts['visible']==0 && $posts['posttime']!=$thread['starttime']) eval ("\$invisible = \"".$tpl->get("thread_invisible")."\";"); 
 
 $tdbgcolor=getone($count,"{tablecolorb}","{tablecolora}");
 $tdid=getone($count,"tableb","tablea");
 
 $posts['message']=$parse->doparse($posts['message'],$posts['allowsmilies']*$board['allowsmilies'],$board['allowhtml'],$board['allowbbcode'],$board['allowimages']);
 $posts['posttopic']=$parse->textwrap($posts['posttopic'],30);
 if($posts['iconid'] && $board['allowicons']==1) $posticon=makeimgtag($posts['iconpath'],$posts['icontitle']);
 else $posticon="";
 if($wbbuserdata['lastvisit']<=$posts['posttime'] && $postvisit[$posts['postid']]!=1) eval ("\$postsign = \"".$tpl->get("thread_newpost")."\";");
 else eval ("\$postsign = \"".$tpl->get("thread_nonewpost")."\";");
 $postdate=formatdate($wbbuserdata['dateformat'],$posts['posttime'],1);
 $posttime=formatdate($wbbuserdata['timeformat'],$posts['posttime']);
 
 if($posts['attachmentid']) {
  if($wbbuserdata['showimages']==1 && $wbbuserdata['candownloadattachments']==1 && ($posts['attachmentextension']=="gif" || $posts['attachmentextension']=="jpg" || $posts['attachmentextension']=="jpeg")) {
   $imgsize=@getimagesize("attachments/attachment-$posts[attachmentid].$posts[attachmentextension]");
   if(($picmaxwidth!=0 && $imgsize[0]>$picmaxwidth) || ($picmaxheight!=0 && $imgsize[1]>$picmaxheight)) {
    if($picmaxwidth!=0) $div1 = $picmaxwidth/$imgsize[0];
    else $div1=1;
    if($picmaxheight!=0) $div2 = $picmaxheight/$imgsize[1];
    else $div2=1;
    
    if($div1<$div2) {
     $imgwidth=$picmaxwidth;
     $imgheight=round($imgsize[1]*$div1);
    }
    else {
     $imgheight=$picmaxheight;
     $imgwidth=round($imgsize[0]*$div2);	
    }
    eval ("\$attachmentbit = \"".$tpl->get("thread_attachmentbit_show_small")."\";");
   }
   else eval ("\$attachmentbit = \"".$tpl->get("thread_attachmentbit_show")."\";");
  }
  else {
   if(!file_exists("images/filetypes/$posts[attachmentextension].gif")) $extensionimage="unknown";
   else $extensionimage=$posts['attachmentextension'];
   $posts['attachmentsize']=ceil($posts['attachmentsize']/1024);
   eval ("\$attachmentbit = \"".$tpl->get("thread_attachmentbit")."\";");
  }
 }
 
 if($posts['editorid']) {
  $editdate=formatdate($wbbuserdata['dateformat'],$posts['edittime']);
  $edittime=formatdate($wbbuserdata['timeformat'],$posts['edittime']);
  eval ("\$lastedit = \"".$tpl->get("thread_lastedit")."\";");
 }
 if($posts['userid']) {
  $rankimages=formatRI($posts['rankimages']);
  if($rankimages) eval ("\$rankimages = \"".$tpl->get("thread_rankimages")."\";");
  if($posts['title']) $posts['ranktitle']=$posts['title'];
  
  if($userratings==1 && $showuserratinginthread==1) $userrating=userrating($posts['ratingcount'],$posts['ratingpoints'],$posts['userid']);
  if($showuserlevels==1) $userlevel=userlevel($posts['userposts'],$posts['regdate']);
    
  if($showonlineinthread==1) {
   if(($posts['invisible']==0 || $wbbuserdata['canuseacp']==1) && $posts['lastactivity']>=time()-$useronlinetimeout*60) eval ("\$user_online = \"".$tpl->get("thread_user_online")."\";");
   else eval ("\$user_online = \"".$tpl->get("thread_user_offline")."\";");
  }
  
  if($showregdateinthread==1) {
   $posts['regdate']=formatdate($wbbuserdata['dateformat'],$posts['regdate']);
   eval ("\$posts[regdate] = \"".$tpl->get("thread_regdate")."\";");
  }
  else $posts['regdate']="";
  
  if($showuserfieldsinthread==1 && is_array($userfieldcache) && count($userfieldcache)) {
   while(list($key,$val)=each($userfieldcache)) {
    $fieldcontent=$parse->textwrap($posts["field".$val['profilefieldid']],20);
    if($fieldcontent) eval ("\$userfields .= \"".$tpl->get("thread_userfields")."\";");
   }
  }
  
  if($showgenderinthread==1 && $posts['gender']>0) {
   if($posts['gender']==1) eval ("\$gender = \"".$tpl->get("thread_gender_male")."\";");
   if($posts['gender']==2) eval ("\$gender = \"".$tpl->get("thread_gender_female")."\";");
  }
  
  if($showuserpostsinthread==1) eval ("\$posts[userposts] = \"".$tpl->get("thread_userposts")."\";");
  else $posts['userposts']="";
  
  eval ("\$search = \"".$tpl->get("thread_search")."\";");
  eval ("\$homie = \"".$tpl->get("thread_homie")."\";");
  if($posts['showemail']==1) eval ("\$email = \"".$tpl->get("thread_email")."\";");
  elseif($posts['usercanemail']==1) eval ("\$email = \"".$tpl->get("thread_formmail")."\";");
  if($posts['homepage']) eval ("\$homepage = \"".$tpl->get("thread_homepage")."\";");
  if($posts['receivepm']==1 && $wbbuserdata['canusepms']==1) eval ("\$pm = \"".$tpl->get("thread_pm")."\";");
  if($posts[icq]) eval ("\$icq = \"".$tpl->get("thread_icq")."\";");
  if($posts[aim]) eval ("\$aim = \"".$tpl->get("thread_aim")."\";");
  if($posts[yim]) eval ("\$yim = \"".$tpl->get("thread_yim")."\";");
   
  if($posts['avatarid'] && $showavatar==1 && $wbbuserdata['showavatars']==1) {
   $avatarname="images/avatars/avatar-$posts[avatarid].$posts[avatarextension]";
   $avatarwidth=$posts['width'];
   $avatarheight=$posts['height'];
   if($posts['avatarextension']=="swf" && $allowflashavatar==1) {
    eval ("\$useravatar = \"".$tpl->get("avatar_flash")."\";");
    eval ("\$useravatar = \"".$tpl->get("thread_useravatar")."\";");
   }
   elseif($posts['avatarextension']!="swf") {
    eval ("\$useravatar = \"".$tpl->get("avatar_image")."\";");
    eval ("\$useravatar = \"".$tpl->get("thread_useravatar")."\";");
   }
  }
  if($_GET['hilightuser']==$posts['userid']) $posts['username']="<hilight>".$posts['username']."</hilight>";
  eval ("\$posts[username] = \"".$tpl->get("thread_username")."\";");
    
  if($showthreadstarter==1 && $thread['starterid']==$posts['userid'] && $thread['starttime']!=$posts['posttime']) eval ("\$threadstarter = \"".$tpl->get("thread_threadstarter")."\";");
   
  if($posts['showsignature']==1 && $wbbuserdata['showsignatures']==1 && $posts['signature']) {
   $posts['signature']=$parse->doparse($posts['signature'],$posts['allowsmilies']*$allowsigsmilies,$allowsightml,$allowsigbbcode,$maxsigimage);
   eval ("\$signature = \"".$tpl->get("thread_signature")."\";");
  }
 }
 else {
  eval ("\$posts[ranktitle] = \"".$tpl->get("anonymous")."\";");
 }
 
 eval ("\$postbit .= \"".$tpl->get("thread_postbit")."\";");
 $count++;
}

if($wbbuserdata['issupermod']==1 || $modpermissions['userid']) eval ("\$modoptions = \"".$tpl->get("thread_modoptions")."\";");
elseif($wbbuserdata['userid'] && $wbbuserdata['userid']==$thread['starterid'] && ($wbbuserdata['cancloseowntopic']==1 || $wbbuserdata['candelowntopic']==1 || $wbbuserdata['caneditowntopic']==1)) eval ("\$modoptions = \"".$tpl->get("thread_useroptions")."\";");

if($thread['pollid']) {
 if($wbbuserdata['issupermod']==1 || $modpermissions['userid']==1) eval ("\$mod_poll_edit = \"".$tpl->get("mod_poll_edit")."\";");
 
 unset($votecheck);
 $poll=$db->query_first("SELECT * FROM bb".$n."_polls WHERE pollid='$thread[pollid]'");
 if($poll['timeout']==0) $timeout=time()+1;
 else $timeout=$poll['starttime']+$poll['timeout']*86400;
 if($_REQUEST['preresult']!=1 && $wbbuserdata['canvotepoll'] && $timeout>=time()) {
  if($wbbuserdata['userid']) $votecheck=$db->query_first("SELECT id AS pollid FROM bb".$n."_votes WHERE id='$thread[pollid]' AND votemode=1 AND userid='$wbbuserdata[userid]'");
  else $votecheck=$db->query_first("SELECT id AS pollid FROM bb".$n."_votes WHERE id='$thread[pollid]' AND votemode=1 AND ipaddress='$REMOTE_ADDR'");
 } 

 if($_REQUEST['preresult']==1 || $votecheck['pollid'] || !$wbbuserdata['canvotepoll'] || $timeout<time()) { // already voted; show result
  $votes=0;
  unset($polloption);
  $totalvotes=0;
  //list($totalvotes)=$db->query_first("SELECT SUM(votes) FROM bb".$n."_polloptions WHERE pollid='$thread[pollid]'");
  $result=$db->query("SELECT * FROM bb".$n."_polloptions WHERE pollid='$thread[pollid]' ORDER BY votes ASC");
  while($row=$db->fetch_array($result)) {
   $totalvotes+=$row['votes'];
   $polloptions[]=$row;
  }
  
  $i=1;
  while(list($key,$row)=each($polloptions)) {
   $row['polloption']=$parse->doparse($row['polloption'],$board['allowsmilies'],$board['allowhtml'],$board['allowbbcode'],$board['allowimages']);
   if($totalvotes) {
    $percent_float = $row['votes']*100/$totalvotes;
    $percent = number_format($percent_float, 2);
    $percent_int = floor($percent_float)*3;
    $percent_int += 1;	
   }
   else $percent = $percent_int = 0; 
   eval ("\$thread_poll_resultbit .= \"".$tpl->get("thread_poll_resultbit")."\";");
   if($i==5) $i=0;
   $i++;
  }
 
  eval ("\$thread_poll = \"".$tpl->get("thread_poll_result")."\";");
 }
 else {
  if($poll['choicecount']>1) $inputtype="checkbox";
  else $inputtype="radio";
 
  $result=$db->query("SELECT * FROM bb".$n."_polloptions WHERE pollid='$thread[pollid]' ORDER BY showorder ASC");
  while($row=$db->fetch_array($result)) {
   $row['polloption']=$parse->doparse($row['polloption'],$board['allowsmilies'],$board['allowhtml'],$board['allowbbcode'],$board['allowimages']);
   eval ("\$thread_pollbit .= \"".$tpl->get("thread_pollbit")."\";");
  }
 
  eval ("\$thread_poll = \"".$tpl->get("thread_poll")."\";");
 }
}

if($thread['voted']) {
 $avarage=number_format($thread['votepoints']/$thread['voted'],2);
 $threads['voted']=$thread['voted'];
 eval ("\$threadrating = \"".$tpl->get("board_threadbit_rating")."\";");
 $threadrating=str_repeat($threadrating, round($avarage));
}
else $threadrating="";

$threadvisit[$threadid]=time();
if($wbbuserdata['usecookies']==1) encode_cookie("threadvisit");

$postids = explode(",",$postids);
for($i=0;$i<count($postids);$i++) $postvisit[$postids[$i]]=1;
if($wbbuserdata['usecookies']==1) encode_cookie("postvisit",0,false);

if($board['closed']==0) eval ("\$newthread = \"".$tpl->get("board_newthread")."\";");
if($threadview==0) $addreply_link="threadid=$threadid";
else $addreply_link="postid=$postid";
if($thread['closed']!=0) eval ("\$addreply = \"".$tpl->get("thread_closed")."\";");
elseif($board['closed']==0) eval ("\$addreply = \"".$tpl->get("thread_addreply")."\";");

if($thread['prefix']!="") {
 $threads['prefix']=$thread['prefix'];	
 eval ("\$thread[prefix] = \"".$tpl->get("board_thread_prefix")."\";");
}

if(strlen($thread['topic'])>60) $thread['topic']=parse::textwrap($thread['topic'],60);
   
if($threadview==0) eval("\$tpl->output(\"".$tpl->get("thread")."\");");
else eval("\$tpl->output(\"".$tpl->get("thread_threaded")."\");");
?>