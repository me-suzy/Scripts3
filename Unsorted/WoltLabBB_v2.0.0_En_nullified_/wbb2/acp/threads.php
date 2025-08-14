<?php
require ("./global.php");
isAdmin(-1);

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="spinning";

if($action=="spinning") {
 if(isset($_POST['send'])) {
  $boardid=intval($_POST['boardid']);
  if(!$wbbuserdata['canuseacp'] && !$wbbuserdata['issupermod']) {
   $verifymod=$db->query_first("SELECT userid FROM bb".$n."_moderators WHERE userid='$wbbuserdata[userid]' AND boardid='$boardid'");	
   if(!$verifymod['userid']) eval("acp_error(\"".gettemplate("error_verifymod")."\");");
  }
  
  $limit=intval($_POST['limit']);
  if(!$limit) $limit=20;
  $offset=intval($_POST['offset']);
  if($offset<1) $offset=1;
  $offset-=1;
  
  $sortby=$_REQUEST['sortby'];
  $sortorder=$_REQUEST['sortorder'];
  
  switch($sortorder) {
   case "ASC": break;
   case "DESC": break;
   default: $sortorder="DESC"; break;	
  }
 
  switch($sortby) {
   case "topic": break;
   case "starttime": break;
   case "starter": break;
   case "lastposttime": break;
   case "lastposter": break;
   case "replycount": break;
   case "views": break;
   default: $sortby="lastposttime"; break;	
  }
  
  $threadbit="";
  $count=0;
  $result=$db->query("SELECT threadid, topic, starter, starterid FROM bb".$n."_threads WHERE boardid='$boardid' ORDER BY $sortby $sortorder",$limit,$offset);
  if(!$db->num_rows($result)) eval("acp_error(\"".gettemplate("error_noresult")."\");");
  while($row=$db->fetch_array($result)) {
   $rowclass=getone($count++,"firstrow","secondrow");
   if($row['starterid']!=0) $row['starter']=makehreftag("../profile.php?userid=$row[starterid]",$row['starter'],"_blank");
   eval ("\$threadbit .= \"".gettemplate("threads_spinbit")."\";");	
  }
  eval("print(\"".gettemplate("threads_spin")."\");");
  exit();	
 }
 
 if($useuseraccess==1 && $wbbuserdata['userid']) {
  $result=$db->query("SELECT b.boardid, b.parentid, b.boardorder, b.title FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  LEFT JOIN bb".$n."_access a ON (a.userid='$wbbuserdata[userid]' AND b.boardid=a.boardid)
  WHERE invisible = 0 OR (p.boardpermission=1 AND a.userid IS NULL) OR (a.userid IS NOT NULL AND a.boardpermission=1)");
 }
 else {
  $result=$db->query("SELECT b.boardid, b.parentid, b.boardorder, b.title FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  WHERE invisible = 0 OR p.boardpermission=1");
 }
 while ($row = $db->fetch_array($result)) $boardcache[$row[parentid]][$row[boardorder]][$row[boardid]] = $row;	

 $boardid_options = makeboardoptions(0);	
	
 eval("print(\"".gettemplate("threads")."\");");	
}

if($action=="verify") {
 $boardid=intval($_REQUEST['boardid']);
 if(isset($_POST['send'])) {
  $closethread=$_POST['closethread'];
  
  $close_threadids="";
  if(is_array($closethread)) {
   reset($closethread);
   while(list($key,$val)=each($closethread)) if($val==1) $close_threadids.=",$key";
  }	
  if($close_threadids) $db->unbuffered_query("UPDATE bb".$n."_threads SET closed=1-closed WHERE threadid IN (0$close_threadids)",1);	
 	
  $delthread=$_POST['delthread'];
  $del_threadids="";
  if(is_array($delthread)) {
   reset($delthread);
   while(list($key,$val)=each($delthread)) if($val==1) $del_threadids.=",$key";
  }	
  if($del_threadids) {
   $board=$db->query_first("SELECT * FROM bb".$n."_boards WHERE boardid='$boardid'");	
  
   $result=$db->query("SELECT pollid, replycount FROM bb".$n."_threads WHERE threadid IN (0$del_threadids)");
   $threadcount=$db->num_rows($result); // thread count for board counter
   $pollids="";
   $postcount=0;
   while($row=$db->fetch_array($result)) {
    $postcount+=1+$row['replycount']; // post count for board counter
    if($row['pollid']) $pollids.=",$row[pollid]";	
   }
   $db->query("DELETE FROM bb".$n."_threads WHERE threadid IN (0$del_threadids)"); // delete thread
   $db->unbuffered_query("DELETE FROM bb".$n."_threads WHERE pollid IN (0$del_threadids) AND closed=3",1); // delete redirect thread
   $db->unbuffered_query("DELETE FROM bb".$n."_announcements WHERE threadid IN (0$del_threadids)",1); // delete announcements
   $db->unbuffered_query("DELETE FROM bb".$n."_subscribethreads WHERE threadid IN (0$del_threadids)",1); // delete subscriptions
 
   if($pollids) { // delete poll 
    $db->query("DELETE FROM bb".$n."_polls WHERE pollid IN (0$pollids)");
    $pollvotes=" OR (id IN (0$pollids) AND votemode=1)";
    $db->query("DELETE FROM bb".$n."_polloptions WHERE pollid IN (0$pollids)");
   }
   else $pollvotes="";
   $db->query("DELETE FROM bb".$n."_votes WHERE (id IN (0$del_threadids) AND votemode=2)$pollvotes"); // delete ratings
   
   /* delete attachments */
   $result = $db->query("SELECT attachmentid FROM bb".$n."_posts WHERE threadid IN (0$del_threadids) AND attachmentid>0");
   while($row=$db->fetch_array($result)) $attachmentids.=",".$row[attachmentid];
   $result = $db->query("SELECT attachmentid, attachmentextension FROM bb".$n."_attachments WHERE attachmentid IN (0$attachmentids)");
   while($row=$db->fetch_array($result)) @unlink("attachments/attachment-".$row[attachmentid].".".$row[attachmentextension]);
   $db->query("DELETE FROM bb".$n."_attachments WHERE attachmentid IN (0$attachmentids)");
   
   if($board['countuserposts']==1) { // delete userpost
    $result = $db->query("SELECT COUNT(postid) AS posts, userid FROM bb".$n."_posts WHERE threadid IN (0$del_threadids) AND visible=1 AND userid>0 GROUP BY userid");
    while($row=$db->fetch_array($result)) $db->query("UPDATE bb".$n."_users SET userposts=userposts-'$row[posts]' WHERE userid='$row[userid]'");
   }
  
   $db->query("DELETE FROM bb".$n."_posts WHERE threadid IN (0$del_threadids)"); // delete posts
   
   /* update boardcount */
   $db->query("UPDATE bb".$n."_boards SET threadcount=threadcount-'$threadcount', postcount=postcount-'$postcount' WHERE boardid IN ($boardid,$board[parentlist])");
   updateBoardInfo("$boardid,$board[parentlist]");
  }
  
  $movethread=$_POST['movethread'];
  $newboardid=$_POST['newboardid'];
  
  $move_threadids="";
  if(is_array($movethread)) {
   reset($movethread);
   while(list($key,$val)=each($movethread)) if($val) $move_threadids.=",$key";
  }
  if($move_threadids) {
   $board=$db->query_first("SELECT * FROM bb".$n."_boards WHERE boardid='$boardid'");	
   $result=$db->query("SELECT * FROM bb".$n."_threads WHERE threadid IN (0$move_threadids)");
   while($thread=$db->fetch_array($result)) movethread($thread['threadid'],$movethread[$thread['threadid']],$newboardid[$thread['threadid']]);
  }
  header("Location: threads.php?action=spinning&sid=$session[hash]");
  exit();	
 }
 
 $threadaction=$_POST['threadaction'];
 reset($threadaction);
 $threadids="";
 while(list($key,$val)=each($threadaction)) if($val) $threadids.=",$key";
	
 if(!$threadids) eval("acp_error(\"".gettemplate("error_noresult")."\");");
 
 if($useuseraccess==1 && $wbbuserdata['userid']) {
  $result=$db->query("SELECT b.boardid, b.parentid, b.boardorder, b.title FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  LEFT JOIN bb".$n."_access a ON (a.userid='$wbbuserdata[userid]' AND b.boardid=a.boardid)
  WHERE invisible = 0 OR (p.boardpermission=1 AND a.userid IS NULL) OR (a.userid IS NOT NULL AND a.boardpermission=1)");
 }
 else {
  $result=$db->query("SELECT b.boardid, b.parentid, b.boardorder, b.title FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  WHERE invisible = 0 OR p.boardpermission=1");
 }
  
 while ($row = $db->fetch_array($result)) $boardcache[$row[parentid]][$row[boardorder]][$row[boardid]] = $row;	

 $boardid_options = makeboardoptions(0);
 
 $result=$db->query("SELECT threadid, topic FROM bb".$n."_threads WHERE threadid IN (0$threadids)");
 if(!$db->num_rows($result)) eval("acp_error(\"".gettemplate("error_noresult")."\");");
 $threadbit1="";
 $threadbit2="";
 $threadbit3="";
 $count1=0;
 $count2=0;
 $count3=0;
 
 while($row=$db->fetch_array($result)) {
  if($threadaction[$row['threadid']]=="del") {
   $rowclass=getone($count1++,"firstrow","secondrow");
   eval ("\$threadbit1 .= \"".gettemplate("threads_spindelbit")."\";");
  }	
  if($threadaction[$row['threadid']]=="move") {
   $rowclass=getone($count2++,"firstrow","secondrow");
   eval ("\$threadbit2 .= \"".gettemplate("threads_spinmovebit")."\";");
  }	
  if($threadaction[$row['threadid']]=="close") {
   $rowclass=getone($count3++,"firstrow","secondrow");
   eval ("\$threadbit3 .= \"".gettemplate("threads_spinclosebit")."\";");
  }	
 }
 
 if($threadbit1) eval ("\$spindel = \"".gettemplate("threads_spindel")."\";");
 else $spindel="";
 if($threadbit2) eval ("\$spinmove = \"".gettemplate("threads_spinmove")."\";");
 else $spinmove="";
 if($threadbit3) eval ("\$spinclose = \"".gettemplate("threads_spinclose")."\";");
 else $spinclose="";
 
 eval("print(\"".gettemplate("threads_spinverify")."\");");		
}

if($action=="moderate") {
 if(isset($_POST['send'])) {
  if(isset($_POST['setvisible']) && is_array($_POST['setvisible']) && count($_POST['setvisible'])) {
   $first=1;
   $threadids=implode(",",$_POST['setvisible']);
   $threads=$db->query("SELECT 
    t.topic, t.boardid, t.starttime, t.threadid, t.starter,
    b.parentlist, b.countuserposts, b.lastposttime, b.title,
    u.groupid, u.userposts, u.gender, u.rankid, u.userid
    FROM bb".$n."_threads t
    LEFT JOIN bb".$n."_boards b ON (t.boardid=b.boardid)
    LEFT JOIN bb".$n."_users u ON (u.userid=t.starterid)
    WHERE t.threadid IN ($threadids)");
   while($thread=$db->fetch_array($threads)) {
    $db->unbuffered_query("UPDATE bb".$n."_boards SET threadcount=threadcount+1, postcount=postcount+1 WHERE boardid IN ($thread[parentlist],$thread[boardid])",1);
    if($thread['countuserposts']==1 && $thread['userid']) {
     $thread[userposts]+=1;
     list($rankid)=$db->query_first("SELECT rankid FROM bb".$n."_ranks WHERE groupid IN ('0','$thread[groupid]') AND needposts<='$thread[userposts]' AND gender IN ('0','$thread[gender]') ORDER BY needposts DESC, gender DESC",1);
     $db->unbuffered_query("UPDATE bb".$n."_users SET userposts=userposts+1".ifelse($rankid!=$thread['rankid'],", rankid='$rankid'","")." WHERE userid = '$thread[userid]'",1);
    }
    
    $db->unbuffered_query("UPDATE bb".$n."_threads SET visible=1 WHERE threadid IN ($threadids)");
    $db->unbuffered_query("UPDATE bb".$n."_posts SET visible=1 WHERE threadid IN ($threadids)");	
    
    if($thread['lastposttime']<$thread['starttime']) {
     $result = $db->query("SELECT boardid, childlist FROM bb".$n."_boards WHERE boardid IN ($thread[boardid],$thread[parentlist]) AND lastposttime<'$thread[starttime]'");
     while($row=$db->fetch_array($result)) {
      $lastpost=$db->query_first("SELECT p.threadid, p.userid, p.username, p.posttime FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND p.visible = 1 AND t.boardid IN ($row[boardid],$row[childlist]) ORDER BY p.posttime DESC",1);
      $db->unbuffered_query("UPDATE bb".$n."_boards SET lastthreadid='$lastpost[threadid]', lastposttime='$lastpost[posttime]', lastposterid='$lastpost[userid]', lastposter='".addslashes($lastpost[username])."' WHERE boardid='$row[boardid]'",1);
     }
    }
    
    if($first==1) {
     if($usedbtemplates==1) {
      require ("./lib/class_tpl_db.php");
      $tpl = new tpl(0,0);
      $tpl->templatelist="ms_newthread,mt_newthread_lastone,mt_newthread";
      $tpl->cache();
     }
     else {
      require("./lib/class_tpl_file.php");
      $tpl = new tpl(0,0,"../");
     }
     $first=0;
    }
        
    $board['title']=$thread['title'];
    $topic=$thread['topic'];
    $threadid=$thread['threadid'];
    $wbbuserdata['username']=$thread['starter'];
    
    $result=$db->query("SELECT u.email, u.username, s.countemails FROM bb".$n."_subscribeboards s LEFT JOIN bb".$n."_users u USING(userid) WHERE s.boardid='$thread[boardid]' AND s.userid<>'$thread[userid]' AND s.emailnotify=1 AND s.countemails<'$maxnotifymails' AND u.email is not null");
    while($row=$db->fetch_array($result)) {
     eval ("\$mail_subject = \"".$tpl->get("ms_newthread")."\";");
     if($row['countemails']==$maxnotifymails-1) eval ("\$mail_text = \"".$tpl->get("mt_newthread_lastone")."\";");
     else eval ("\$mail_text = \"".$tpl->get("mt_newthread")."\";");
     mailer($row['email'],$mail_subject,$mail_text);
    }
    $db->unbuffered_query("UPDATE bb".$n."_subscribeboards SET countemails=countemails+1 WHERE boardid='$thread[boardid]' AND userid<>'$thread[userid]' AND emailnotify=1 AND countemails<'$maxnotifymails'",1);
   }
  }	
 }
 
 $boardids="";
 if($wbbuserdata['canuseacp']==0 && $wbbuserdata['issupermod']==0) {
  $result=$db->query("SELECT boardid FROM bb".$n."_moderators WHERE userid='$wbbuserdata[userid]'");
  while($row=$db->fetch_array($result))	$boardids.=",$row[boardid]";
 }	
	
 $threadbit="";
 $count=0;
 $result=$db->query("SELECT threadid, topic, starter, starterid FROM bb".$n."_threads WHERE visible=0".ifelse($boardids," AND boardid IN (0$boardids)")." ORDER BY starttime DESC");	
 if(!$db->num_rows($result)) eval ("\$threadbit = \"".gettemplate("threads_moderate_nothing")."\";");
 while($row=$db->fetch_array($result)) {
  $rowclass=getone($count++,"firstrow","secondrow");
  if($row['starterid']!=0) $row['starter']=makehreftag("../profile.php?userid=$row[starterid]",$row['starter'],"_blank");
  eval ("\$threadbit .= \"".gettemplate("threads_moderatebit")."\";");	
 }
 
 eval("print(\"".gettemplate("threads_moderate")."\");");
}

if($action=="moderateposts") {
 if(isset($_POST['send'])) {
  if(isset($_POST['setvisible']) && is_array($_POST['setvisible']) && count($_POST['setvisible'])) {
   $first=1;
   $postids=implode(",",$_POST['setvisible']);
   $posts=$db->query("SELECT 
    p.postid, p.posttime, p.userid, p.username, p.threadid,
    t.topic, t.boardid, t.lastposttime,
    b.parentlist, b.countuserposts, b.lastposttime as blastposttime, b.title,
    u.groupid, u.userposts, u.gender, u.rankid, u.userid
    FROM bb".$n."_posts p
    LEFT JOIN bb".$n."_users u USING (userid)
    LEFT JOIN bb".$n."_threads t ON (t.threadid=p.threadid)
    LEFT JOIN bb".$n."_boards b ON (t.boardid=b.boardid)
    WHERE p.postid IN ($postids)");
   while($post=$db->fetch_array($posts)) {
    if($post['posttime']>$post['lastposttime']) $db->unbuffered_query("UPDATE bb".$n."_threads SET lastposttime = '$post[posttime]', lastposterid = '$post[userid]', lastposter = '".addslashes($post['username'])."', replycount = replycount+1 WHERE threadid = '$post[threadid]'",1);
    else $db->unbuffered_query("UPDATE bb".$n."_threads SET replycount = replycount+1 WHERE threadid = '$post[threadid]'",1);
    
    $db->unbuffered_query("UPDATE bb".$n."_boards SET postcount=postcount+1 WHERE boardid IN ($post[parentlist],$post[boardid])",1);
    
    if($post['countuserposts']==1 && $post['userid']) {
     $post[userposts]+=1;
     list($rankid)=$db->query_first("SELECT rankid FROM bb".$n."_ranks WHERE groupid IN ('0','$post[groupid]') AND needposts<='$post[userposts]' AND gender IN ('0','$post[gender]') ORDER BY needposts DESC, gender DESC",1);
     $db->unbuffered_query("UPDATE bb".$n."_users SET userposts=userposts+1".ifelse($rankid!=$post['rankid'],", rankid='$rankid'","")." WHERE userid = '$post[userid]'",1);
    }
    
    $db->unbuffered_query("UPDATE bb".$n."_posts SET visible=1 WHERE postid IN ($postids)");
    
    if($post['blastposttime']<$post['posttime']) {
     $result = $db->query("SELECT boardid, childlist FROM bb".$n."_boards WHERE boardid IN ($post[boardid],$post[parentlist]) AND lastposttime<'$post[posttime]'");
     while($row=$db->fetch_array($result)) {
      $lastpost=$db->query_first("SELECT p.threadid, p.userid, p.username, p.posttime FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND p.visible = 1 AND t.boardid IN ($row[boardid],$row[childlist]) ORDER BY p.posttime DESC",1);
      $db->unbuffered_query("UPDATE bb".$n."_boards SET lastthreadid='$lastpost[threadid]', lastposttime='$lastpost[posttime]', lastposterid='$lastpost[userid]', lastposter='".addslashes($lastpost[username])."' WHERE boardid='$row[boardid]'",1);
     }
    }
   
    if($first==1) {
     if($usedbtemplates==1) {
      require ("./lib/class_tpl_db.php");
      $tpl = new tpl(0,0);
      $tpl->templatelist="ms_newpost,mt_newpost_lastone,mt_newpost";
      $tpl->cache();
     }
     else {
      require("./lib/class_tpl_file.php");
      $tpl = new tpl(0,0,"../");
     }
     $first=0;
    }
    
    $thread['topic']=$post['topic'];
    $postid=$post['postid'];
    $wbbuserdata['username']=$post['username'];
    
    $result=$db->query("SELECT u.email, u.username, s.countemails FROM bb".$n."_subscribethreads s LEFT JOIN bb".$n."_users u USING(userid) WHERE s.threadid='$post[threadid]' AND s.userid<>'$post[userid]' AND s.emailnotify=1 AND s.countemails<'$maxnotifymails' AND u.email is not null");
    while($row=$db->fetch_array($result)) {
     if($row[countemails]==$maxnotifymails-1) eval ("\$mail_text = \"".$tpl->get("mt_newpost_lastone")."\";");
     else eval ("\$mail_text = \"".$tpl->get("mt_newpost")."\";");
     eval ("\$mail_subject = \"".$tpl->get("ms_newpost")."\";");
     mailer($row[email],$mail_subject,$mail_text);
    }
    $db->unbuffered_query("UPDATE bb".$n."_subscribethreads SET countemails=countemails+1 WHERE threadid='$post[threadid]' AND userid<>'$post[userid]' AND emailnotify=1 AND countemails<'$maxnotifymails'",1);
   }
  }	
 }
 
 $boardids="";
 if($wbbuserdata['canuseacp']==0 && $wbbuserdata['issupermod']==0) {
  $result=$db->query("SELECT boardid FROM bb".$n."_moderators WHERE userid='$wbbuserdata[userid]'");
  while($row=$db->fetch_array($result))	$boardids.=",$row[boardid]";
 }	
	
 $postbit="";
 $count=0;
 $result=$db->query("SELECT t.topic, p.username, p.userid, p.postid FROM bb".$n."_posts p, bb".$n."_threads t WHERE t.threadid=p.threadid AND t.visible=1 AND p.visible=0".ifelse($boardids," AND t.boardid IN (0$boardids)")." ORDER BY posttime DESC");	
 if(!$db->num_rows($result)) eval ("\$postbit = \"".gettemplate("threads_moderate_nothing")."\";");
 while($row=$db->fetch_array($result)) {
  $rowclass=getone($count++,"firstrow","secondrow");
  if($row['userid']!=0) $row['username']=makehreftag("../profile.php?userid=$row[userid]",$row['username'],"_blank");
  eval ("\$postbit .= \"".gettemplate("threads_moderatepostsbit")."\";");	
 }
 
 eval("print(\"".gettemplate("threads_moderateposts")."\");");
}
?>
