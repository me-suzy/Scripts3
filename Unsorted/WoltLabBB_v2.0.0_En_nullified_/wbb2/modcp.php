<?php
$filename="modcp.php";

require ("./global.php");

$ismod=0;
$isuser=0;
if(isset($threadid)) {
 if($wbbuserdata['issupermod']==1 || $modpermissions['userid']) $ismod=1;
 elseif($wbbuserdata['userid'] && $wbbuserdata['userid']==$thread['starterid'] && ($wbbuserdata['cancloseowntopic']==1 || $wbbuserdata['candelowntopic']==1 || $wbbuserdata['caneditowntopic']==1)) $isuser=1;
}

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";

if($action=="post_del") {
 if(!$ismod) access_error();	
	
 if(isset($_POST['send'])) {
  if(is_array($_POST['postids']) && count($_POST['postids'])) $postids=implode(',',$_POST['postids']);
  else $postids="";
  if(!$postids) {
   header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
   exit();	
  }
  list($postcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_posts WHERE threadid='$threadid' AND postid IN ($postids)");
  if($postcount==$thread['replycount']+1) {
   deletethread($threadid);
   header("Location: board.php?boardid=$boardid&sid=$session[hash]");
   exit();
  }	
  else {
   deleteposts($postids,$threadid,$postcount);
   header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
   exit();	
  }	
 }
 
 list($postcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_posts WHERE threadid='$threadid'");
 if($wbbuserdata['umaxposts']) $postsperpage=$wbbuserdata['umaxposts'];
 elseif($board['postsperpage']) $postsperpage=$board['postsperpage'];
 else $postsperpage=$default_postsperpage;
 $postorder=$board['postorder'];
 
 if(isset($_GET['page'])) {
  $page=intval($_GET['page']);
  if($page==0) $page=1;
 }
 else $page=1;
 $pages = ceil($postcount/$postsperpage);
 if($pages>1) $pagelink=makepagelink("modcp.php?action=post_del&threadid=$threadid&sid=$session[hash]",$page,$pages,$showpagelinks-1);
 $navbar=getNavbar($board['parentlist']);
 eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");
  
 $postbit="";
 $count=0;
 $result=$db->query("SELECT postid, username, posttime, message FROM bb".$n."_posts WHERE threadid='$threadid' ORDER BY posttime ".ifelse($postorder,"DESC","ASC"),$postsperpage,$postsperpage*($page-1));	
 while($row=$db->fetch_array($result)) {
  $tdbgcolor=getone($count,"{tablecolorb}","{tablecolora}");
  $tdid=getone($count,"tableb","tablea");
   
  $postdate=formatdate($wbbuserdata['dateformat'],$row['posttime']);
  $posttime=formatdate($wbbuserdata['timeformat'],$row['posttime']);
  if(strlen($row['message'])>100) $row['message']=substr($row['message'],0,100)."...";
  $row['message']=htmlspecialchars($row['message']);
  eval ("\$postbit .= \"".$tpl->get("modcp_postbit")."\";");
  $count++;
 }	
 eval("\$tpl->output(\"".$tpl->get("modcp_post_del")."\");");
}

if($action=="thread_cut") {
 if(!$ismod) access_error();	
 
 if(isset($_POST['send'])) {
  if(is_array($_POST['postids']) && count($_POST['postids'])) $postids=implode(',',$_POST['postids']);
  $newboardid=intval($_POST['newboardid']);
  if(!$postids) {
   header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
   exit();	
  }
  list($postcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_posts WHERE threadid='$threadid' AND postid IN ($postids)");
  if($postcount==$thread[replycount]+1) eval("error(\"".$tpl->get("error_cantcut")."\");");
  else {
   $topic=trim($_POST['topic']);
   if(!$topic || $newboardid==-1) eval("error(\"".$tpl->get("error_emptyfields")."\");");
   $newboard=$db->query_first("SELECT * FROM bb".$n."_boards WHERE boardid='$newboardid'");
   if(!$newboard['boardid']) eval("error(\"".$tpl->get("error_emptyfields")."\");");
   
   $firstpost=$db->query_first("SELECT userid, username, posttime FROM bb".$n."_posts WHERE postid IN ($postids) ORDER BY posttime ASC",1);
   $lastpost=$db->query_first("SELECT userid, username, posttime FROM bb".$n."_posts WHERE postid IN ($postids) ORDER BY posttime DESC",1);
   
   $db->query("INSERT INTO bb".$n."_threads (threadid,boardid,topic,starttime,starterid,starter,lastposttime,lastposterid,lastposter,replycount,visible) VALUES (NULL,'$newboardid','".addslashes(htmlspecialchars($topic))."','$firstpost[posttime]','$firstpost[userid]','".addslashes($firstpost['username'])."','$lastpost[posttime]','$lastpost[userid]','".addslashes($lastpost['username'])."','".($postcount-1)."',1)");
   $newthreadid=$db->insert_id();
   
   $db->query("UPDATE bb".$n."_posts SET threadid='$newthreadid' WHERE postid IN ($postids)");
   
   $result=$db->query_first("SELECT userid, username, posttime FROM bb".$n."_posts WHERE threadid='$threadid' ORDER BY posttime DESC",1);
   $db->query("UPDATE bb".$n."_threads SET replycount=replycount-'$postcount', lastposttime='$result[posttime]', lastposterid='$result[userid]', lastposter='".addslashes($result['username'])."'".ifelse($post['attachmentid'],", attachments=attachments-1")." WHERE threadid='$threadid'");
 
   $db->query("UPDATE bb".$n."_boards SET postcount=postcount-'$postcount' WHERE boardid IN ($boardid,$board[parentlist])");
   $db->query("UPDATE bb".$n."_boards SET postcount=postcount+'$postcount', threadcount=threadcount+1 WHERE boardid IN ($newboardid,$newboard[parentlist])");
   
   if($board['lastthreadid']==$threadid) updateBoardInfo("$boardid,$board[parentlist]",0,$threadid);
   if($newboard['lastposttime']<=$lastpost['posttime']) updateBoardInfo("$newboardid,$newboard[parentlist]",$lastpost['posttime']);
     
   if($board['countuserposts']==1 && $newboard['countuserposts']==0) {
    $result = $db->query("SELECT COUNT(postid) AS posts, userid FROM bb".$n."_posts WHERE postid IN ($postids) AND visible = 1 AND userid>0 GROUP BY userid");
    while($row=$db->fetch_array($result)) $db->query("UPDATE bb".$n."_users SET userposts=userposts-'$row[posts]' WHERE userid='$row[userid]'");
   }
   if($board['countuserposts']==0 && $newboard['countuserposts']==1) {
    $result = $db->query("SELECT COUNT(postid) AS posts, userid FROM bb".$n."_posts WHERE postid IN ($postids) AND visible = 1 AND userid>0 GROUP BY userid");
    while($row=$db->fetch_array($result)) $db->query("UPDATE bb".$n."_users SET userposts=userposts+'$row[posts]' WHERE userid='$row[userid]'");
   }
   
   header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
   exit();	
  }	
 }
 
 list($postcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_posts WHERE threadid='$threadid'");
 if($wbbuserdata['umaxposts']) $postsperpage=$wbbuserdata['umaxposts'];
 elseif($board['postsperpage']) $postsperpage=$board['postsperpage'];
 else $postsperpage=$default_postsperpage;
 $postorder=$board['postorder'];
 
 if(isset($_GET['page'])) {
  $page=intval($_GET['page']);
  if($page==0) $page=1;
 }
 else $page=1;
 $pages = ceil($postcount/$postsperpage);
 if($pages>1) $pagelink=makepagelink("modcp.php?action=thread_cut&threadid=$threadid&sid=$session[hash]",$page,$pages,$showpagelinks-1);
 $navbar=getNavbar($board['parentlist']);
 eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");
  
 $postbit="";
 $count=0;
 $result=$db->query("SELECT postid, username, posttime, message FROM bb".$n."_posts WHERE threadid='$threadid' ORDER BY posttime ".ifelse($postorder,"DESC","ASC"),$postsperpage,$postsperpage*($page-1));	
 while($row=$db->fetch_array($result)) {
  $tdbgcolor=getone($count,"{tablecolorb}","{tablecolora}");
  $tdid=getone($count,"tableb","tablea");
   
  $postdate=formatdate($wbbuserdata['dateformat'],$row['posttime']);
  $posttime=formatdate($wbbuserdata['timeformat'],$row['posttime']);
  if(strlen($row['message'])>100) $row['message']=substr($row['message'],0,100)."...";
  $row['message']=htmlspecialchars($row['message']);
  eval ("\$postbit .= \"".$tpl->get("modcp_postbit2")."\";");
  $count++;
 }
 
 $result = $db->query("SELECT boardid, parentid, boardorder, IF(isboard=1,title,CONCAT(title,' *')) AS title, invisible, isboard FROM bb".$n."_boards ORDER by parentid ASC, boardorder ASC");
 while ($row = $db->fetch_array($result)) $boardcache[$row[parentid]][$row[boardorder]][$row[boardid]] = $row;
    
 $result = $db->query("SELECT * FROM bb".$n."_permissions WHERE groupid = '$wbbuserdata[groupid]'");
 while ($row = $db->fetch_array($result)) $permissioncache[$row[boardid]] = $row;
 if($wbbuserdata[userid] && $useuseraccess==1) {
  $result = $db->query("SELECT * FROM bb".$n."_access WHERE userid = '$wbbuserdata[userid]'");
  while ($row = $db->fetch_array($result)) $permissioncache[$row[boardid]] = $row;
 }
  
 $board_options=makeboardselect(0);
  	
 eval("\$tpl->output(\"".$tpl->get("modcp_thread_cut")."\");");
}

if($action=="thread_close") {
 if(!$ismod && (!$isuser || $wbbuserdata['cancloseowntopic']==0)) access_error();
 $db->query("UPDATE bb".$n."_threads SET closed=1-'$thread[closed]' WHERE threadid='$threadid'");
 header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
 exit();
}

if($action=="thread_top") {
 if(!$ismod) access_error();
 if($thread['important']==2) $thread['important']=1;
 $db->query("UPDATE bb".$n."_threads SET important=1-'$thread[important]' WHERE threadid='$threadid'");
 header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
 exit();
}

if($action=="thread_del") {
 if(!$ismod && (!$isuser || $wbbuserdata['candelowntopic']==0)) access_error();
 if(isset($_POST['send'])) {
  deletethread($threadid);
  header("Location: board.php?boardid=$boardid&sid=$session[hash]");
  exit();
 }
 else {
  $navbar=getNavbar($board['parentlist']);
  eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");
  eval("\$tpl->output(\"".$tpl->get("modcp_thread_del")."\");");
 }
}

if($action=="thread_move") {
 if(!$ismod) access_error();
 if($_POST['send']=="send") {
  $newboardid=intval($_POST['newboardid']);
  $mode=$_POST['mode'];
  if(!$newboardid || $newboardid==-1 || $newboardid==$boardid) eval("error(\"".$tpl->get("error_cantmove")."\");");
  $newboard = $db->query_first("SELECT 
   b.*,".ifelse($useuseraccess==1 && $wbbuserdata[userid],"
   IF(a.boardid=$newboardid,a.boardpermission,p.boardpermission) AS boardpermission,
   IF(a.boardid=$newboardid,a.startpermission,p.startpermission) AS startpermission,
   IF(a.boardid=$newboardid,a.replypermission,p.replypermission) AS replypermission
   ","p.*")." 
   FROM bb".$n."_boards b
   LEFT JOIN bb".$n."_permissions p ON (p.boardid='$newboardid' AND p.groupid='$wbbuserdata[groupid]')
   ".ifelse($useuseraccess==1 && $wbbuserdata[userid],"LEFT JOIN bb".$n."_access a ON (a.boardid='$newboardid' AND a.userid='$wbbuserdata[userid]')")."
   WHERE b.boardid = '$newboardid'");
  
  if(!$newboard['boardpermission'] || $newboard['isboard']==0) eval("error(\"".$tpl->get("error_cantmove")."\");");
  
  movethread($threadid,$mode,$newboardid);
  
  header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
  exit();
 }
 else {
  $navbar=getNavbar($board['parentlist']);
  eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");
  
  $result = $db->query("SELECT boardid, parentid, boardorder, IF(isboard=1,title,CONCAT(title,' *')) AS title, invisible, isboard FROM bb".$n."_boards ORDER by parentid ASC, boardorder ASC");
  while ($row = $db->fetch_array($result)) $boardcache[$row['parentid']][$row['boardorder']][$row['boardid']] = $row;
    
  $result = $db->query("SELECT * FROM bb".$n."_permissions WHERE groupid = '$wbbuserdata[groupid]'");
  while ($row = $db->fetch_array($result)) $permissioncache[$row['boardid']] = $row;
  if($wbbuserdata[userid] && $useuseraccess==1) {
   $result = $db->query("SELECT * FROM bb".$n."_access WHERE userid = '$wbbuserdata[userid]'");
   while ($row = $db->fetch_array($result)) $permissioncache[$row['boardid']] = $row;
  }
  
  $newboard_options=makeboardselect(0);
  
  eval("\$tpl->output(\"".$tpl->get("modcp_thread_move")."\");");
 }
}

if($action=="thread_merge") {
 if(!$ismod) access_error();
 if(isset($_POST['send'])) {
  $merge_threadid=0;
  $merge_postid=0;
  $mergeurl=$_POST['mergeurl'];
  list ($script, $query) = split ('[?]', $mergeurl);
  $values = explode( "&", $query);
  while (list($key, $val)=each($values)) {
   list ($varname, $value) = explode( "=", $val);
   if($varname=="threadid") {
    $merge_threadid=intval($value);
    break;
   }
   if($varname=="postid") {
    $merge_postid=intval($value);
    break;
   }
  } 
  
  if((!$merge_postid && !$merge_threadid) || $merge_threadid==$threadid) eval("error(\"".$tpl->get("error_cantmerge")."\");");
 
  if($merge_postid) $merge_thread=$db->query_first("SELECT t.* FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND p.postid='$merge_postid'");
  if($merge_threadid) $merge_thread=$db->query_first("SELECT * FROM bb".$n."_threads WHERE threadid='$merge_threadid'");
 
  if(!$merge_thread['threadid']) eval("error(\"".$tpl->get("error_cantmerge")."\");");
 
  if($merge_thread['lastposttime']>$thread['lastposttime']) $db->query("UPDATE bb".$n."_threads SET replycount=replycount+'".($merge_thread['replycount']+1)."', lastposttime='$merge_thread[lastposttime]', lastposterid='$merge_thread[lastposterid]', lastposter='".addslashes($merge_thread['lastposter'])."' WHERE threadid='$threadid'");
  else $db->query("UPDATE bb".$n."_threads SET replycount=replycount+'".($merge_thread['replycount']+1)."' WHERE threadid='$threadid'");
  
  $db->query("DELETE FROM bb".$n."_threads WHERE threadid = '$merge_threadid'");
  $db->query("DELETE FROM bb".$n."_threads WHERE pollid = '$merge_threadid' AND closed=3");
  if($merge_thread['important']==2) $db->unbuffered_query("DELETE FROM bb".$n."_announcements WHERE threadid = '$merge_threadid'",1);
  $db->query("DELETE FROM bb".$n."_subscribethreads WHERE threadid = '$merge_threadid'");
  if($thread['pollid']) {
   $db->query("DELETE FROM bb".$n."_polls WHERE pollid = '$merge_thread[pollid]'");
   $pollvotes=" OR (id = '$merge_thread[pollid]' AND votemode=1)";
   $db->query("DELETE FROM bb".$n."_polloptions WHERE pollid = '$merge_thread[pollid]'");
  }
  else $pollvotes="";
  $db->query("DELETE FROM bb".$n."_votes WHERE (id = '$merge_threadid' AND votemode=2)$pollvotes");
  
  if($merge_thread['boardid']==$boardid) {
   $db->query("UPDATE bb".$n."_boards SET threadcount=threadcount-1 WHERE boardid IN ($boardid,$board[parentlist])");
   $db->query("UPDATE bb".$n."_boards SET lastthreadid='$threadid', lastposttime='$thread[lastposttime]', lastposterid='$thread[lastposterid]', lastposter='$thread[lastposter]' WHERE boardid IN ($boardid,$board[parentlist]) AND lastthreadid='$merge_threadid'");
   $db->query("UPDATE bb".$n."_posts SET threadid='$threadid' WHERE threadid='$merge_threadid'");
  }
  else {
   $oldboard=$db->query_first("SELECT * FROM bb".$n."_boards WHERE boardid='$merge_thread[boardid]'");
   $merge_thread['replycount']+=1;
   $db->query("UPDATE bb".$n."_boards SET threadcount=threadcount-1, postcount=postcount-'$merge_thread[replycount]' WHERE boardid IN ($oldboard[boardid],$oldboard[parentlist])");
   $db->query("UPDATE bb".$n."_boards SET threadcount=threadcount+1, postcount=postcount+'$merge_thread[replycount]' WHERE boardid IN ($boardid,$board[parentlist])");
  
   if($oldboard['countuserposts']==1 && $board['countuserposts']==0) {
    $result = $db->query("SELECT COUNT(postid) AS posts, userid FROM bb".$n."_posts WHERE threadid='$merge_threadid' AND visible = 1 AND userid>0 GROUP BY userid");
    while($row=$db->fetch_array($result)) $db->query("UPDATE bb".$n."_users SET userposts=userposts-'$row[posts]' WHERE userid='$row[userid]'");
   }
   if($oldboard['countuserposts']==0 && $board['countuserposts']==1) {
    $result = $db->query("SELECT COUNT(postid) AS posts, userid FROM bb".$n."_posts WHERE threadid='$merge_threadid' AND visible = 1 AND userid>0 GROUP BY userid");
    while($row=$db->fetch_array($result)) $db->query("UPDATE bb".$n."_users SET userposts=userposts+'$row[posts]' WHERE userid='$row[userid]'");
   }
   
   $db->query("UPDATE bb".$n."_posts SET threadid='$threadid' WHERE threadid='$merge_threadid'");
   
   if($oldboard['lastthreadid']==$merge_threadid) updateBoardInfo("$oldboard[boardid],$oldboard[parentlist]",0,$merge_threadid);
   if($board['lastposttime']<=$merge_thread['lastposttime']) updateBoardInfo("$boardid,$board[parentlist]",$merge_thread['lastposttime']);
  }
  
  header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
  exit();
 }
 
 $navbar=getNavbar($board['parentlist']);
 eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");
 eval("\$tpl->output(\"".$tpl->get("modcp_thread_merge")."\");"); 
}

if($action=="thread_edit") {
 if(!isset($threadid)) eval("error(\"".$tpl->get("error_falselink")."\");");
 if(!$ismod && (!$isuser || $wbbuserdata['caneditowntopic']==0)) access_error();
 if(isset($_POST['send'])) {
  $topic=trim($_POST['topic']);
  if(isset($_POST['iconid'])) $iconid=intval($_POST['iconid']);
  else $iconid=0;
  if($dostopshooting==1) $topic=stopShooting($topic);
  if(!$topic) eval("error(\"".$tpl->get("error_emptyfields")."\");");
  if($ismod==1) $important=intval($_POST['important']);
  else $important=$thread['important'];
  if($important==2 && $thread['important']!=2) $db->unbuffered_query("INSERT IGNORE INTO bb".$n."_announcements (boardid,threadid) VALUES ($boardid,$threadid)");  
  if($important!=2 && $thread['important']==2) $db->unbuffered_query("DELETE FROM bb".$n."_announcements WHERE threadid = '$threadid'",1);  
  
  // remove redirect(s)
  if($ismod==1 && isset($_POST['rm_redirect']) && $_POST['rm_redirect']==1) $db->unbuffered_query("DELETE FROM bb".$n."_threads WHERE pollid='$threadid' AND closed=3",1);
  
  $db->unbuffered_query("UPDATE bb".$n."_threads SET topic='".addslashes(htmlspecialchars($topic))."', iconid='$iconid', closed='".intval($_POST['closed'])."', important='$important', prefix='".addslashes(htmlspecialchars($_POST['prefix']))."' WHERE threadid='$threadid'",1);
  
  header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
  exit();
 }

 if($board['allowicons']==1) {
  $ICONselected[$thread['iconid']]="checked";
  $result = $db->query("SELECT * FROM bb".$n."_icons ORDER BY iconorder ASC");
  $iconcount=0;
  while($row=$db->fetch_array($result)) {
   $row_iconid=$row['iconid'];
   eval ("\$choice_posticons .= \"".$tpl->get("newthread_iconbit")."\";");
   if($iconcount==6) {
    $choice_posticons.="<br>";
    $iconcount=0;
   }
   else $iconcount++;
  } 
  eval ("\$newthread_icons = \"".$tpl->get("newthread_icons")."\";");
 }
 
 if($thread['closed']==1) $checked=" checked";
 
 $navbar=getNavbar($board[parentlist]);
 eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");
 
 $oldboardid_checked="";
 if($ismod==1) {
  $imp_checked[$thread['important']]=" checked";
  eval ("\$change_important = \"".$tpl->get("modcp_thread_edit_important")."\";");
  
  list($redirectlink) = $db->query_first("SELECT COUNT(*) FROM bb".$n."_threads WHERE pollid='$threadid' AND closed=3");
  if($redirectlink>0) eval ("\$remove_redirect = \"".$tpl->get("modcp_thread_remove_redirect")."\";");
 }
 
 if($board['prefixuse']>0) {
  if($board['prefixuse']==1) $ch_prefix = $default_prefix;
  if($board['prefixuse']==2) $ch_prefix = $default_prefix."\n".$board['prefix'];
  if($board['prefixuse']==3) $ch_prefix = $board['prefix'];
 
  $ch_prefix = preg_replace("/\s*\n\s*/","\n",trim($ch_prefix));
  $ch_prefix = explode("\n",$ch_prefix);	
  sort($ch_prefix);
 
  $prefix_options="";
  for($i=0;$i<count($ch_prefix);$i++) $prefix_options.=makeoption($ch_prefix[$i],$ch_prefix[$i],$thread['prefix'],1);	
	
  if($prefix_options!="") eval ("\$select_prefix = \"".$tpl->get("newthread_prefix")."\";");
 }
 
 eval("\$tpl->output(\"".$tpl->get("modcp_thread_edit")."\");");
}

if($action=="announce") {
 if(!isset($threadid) || $thread['important']!=2) eval("error(\"".$tpl->get("error_falselink")."\");");
 if($ismod==0) access_error(); 
 if(isset($_POST['send'])) {
  $db->query("DELETE FROM bb".$n."_announcements WHERE boardid<>'$boardid' AND threadid='$threadid'");
  $boardids = $_POST['boardids'];
  if(count($boardids)) {
   $boardids = implode("','$threadid'),('",$boardids);
   $db->query("INSERT IGNORE INTO bb".$n."_announcements (boardid,threadid) VALUES ('$boardids','$threadid')");
  }
  	
  header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
  exit(); 	
 }	
	
 $result = $db->query("SELECT boardid, parentid, boardorder, title, invisible FROM bb".$n."_boards ORDER by parentid ASC, boardorder ASC");
 while ($row = $db->fetch_array($result)) $boardcache[$row['parentid']][$row['boardorder']][$row['boardid']] = $row;
    
 $result = $db->query("SELECT * FROM bb".$n."_permissions WHERE groupid = '$wbbuserdata[groupid]'");
 while ($row = $db->fetch_array($result)) $permissioncache[$row['boardid']] = $row;
 if($wbbuserdata['userid'] && $useuseraccess==1) {
  $result = $db->query("SELECT * FROM bb".$n."_access WHERE userid = '$wbbuserdata[userid]'");
  while ($row = $db->fetch_array($result)) $permissioncache[$row['boardid']] = $row;
 }
  
 $boardids=array();
 $result = $db->query("SELECT boardid FROM bb".$n."_announcements WHERE threadid='$threadid'");
 while($row=$db->fetch_array($result)) $boardids[]=$row['boardid'];
 
 $board_options=makeboardselect(0,1,$boardids);	
 
 $navbar=getNavbar($board[parentlist]);
 eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");
 
 eval("\$tpl->output(\"".$tpl->get("modcp_announce")."\");");	
}
?>