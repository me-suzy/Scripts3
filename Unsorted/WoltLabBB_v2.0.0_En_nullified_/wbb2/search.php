<?php
$filename="search.php";

require("./global.php");

if(!$wbbuserdata['canusesearch']) access_error();

if(!isset($_GET['action'])) $_GET['action']="";

if($_GET['action']=="new") {
 $boardids="";
 list($boardcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_boards");
 if($useuseraccess==1 && $wbbuserdata['userid']) {
  $result=$db->query("SELECT b.boardid FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  LEFT JOIN bb".$n."_access a ON (a.userid='$wbbuserdata[userid]' AND b.boardid=a.boardid)
  WHERE b.password='' AND ((p.boardpermission=1 AND a.userid IS NULL) OR (a.userid IS NOT NULL AND a.boardpermission=1))");
 }
 else {
  $result=$db->query("SELECT b.boardid FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  WHERE b.password='' AND p.boardpermission=1");
  }
 if($db->num_rows($result)<$boardcount) {
  while($row=$db->fetch_array($result)) {
   if($boardids!="") $boardids.=','.$row[boardid];
   else $boardids=$row[boardid];
  }
  if(!$boardids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
 }	

 if(isset($_COOKIE['postvisit'])) $postvisit=decode_cookie($_COOKIE['postvisit']);
 else $postvisit=array();
	
 $savepostids="";
 $result=$db->query("SELECT p.postid FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND p.visible=1 AND p.posttime>'$wbbuserdata[lastvisit]'
 ".ifelse($boardids,"AND t.boardid IN ($boardids)"));
 while($row=$db->fetch_array($result)) if($postvisit[$row['postid']]!=1) $savepostids.=','.$row['postid'];
  
 if(!$savepostids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
 $result=$db->query_first("SELECT searchid FROM bb".$n."_searchs WHERE postids='$savepostids' AND showposts='1' AND sortby='lastpost' AND sortorder='desc' AND userid='$wbbuserdata[userid]' AND ipaddress='$REMOTE_ADDR'");
 if($result['searchid']) {
  header("Location: search.php?searchid=$result[searchid]&sid=$session[hash]");	
  exit();
 }
 $db->query("INSERT INTO bb".$n."_searchs (searchid,postids,showposts,sortby,sortorder,searchtime,userid,ipaddress)
 VALUES (NULL,'$savepostids','0','lastpost','desc','".time()."','$wbbuserdata[userid]','$REMOTE_ADDR')");
 $searchid=$db->insert_id();
  
 header("Location: search.php?searchid=$searchid&sid=$session[hash]");
 exit(); 	
}
if($_GET['action']=="24h") {
 $boardids="";
 list($boardcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_boards");
 if($useuseraccess==1 && $wbbuserdata['userid']) {
  $result=$db->query("SELECT b.boardid FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  LEFT JOIN bb".$n."_access a ON (a.userid='$wbbuserdata[userid]' AND b.boardid=a.boardid)
  WHERE b.password='' AND ((p.boardpermission=1 AND a.userid IS NULL) OR (a.userid IS NOT NULL AND a.boardpermission=1))");
 }
 else {
  $result=$db->query("SELECT b.boardid FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  WHERE b.password='' AND p.boardpermission=1");
  }
 if($db->num_rows($result)<$boardcount) {
  while($row=$db->fetch_array($result)) {
   if($boardids!="") $boardids.=','.$row[boardid];
   else $boardids=$row[boardid];
  }
  if(!$boardids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
 }	
	
 $savepostids="";
 $datecute=time()-86400;
 $result=$db->query("SELECT p.postid FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND p.visible=1 AND p.posttime>'$datecute'
 ".ifelse($boardids,"AND t.boardid IN ($boardids)"));
 while($row=$db->fetch_array($result)) $savepostids.=','.$row[postid];
  
 if(!$savepostids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
 $result=$db->query_first("SELECT searchid FROM bb".$n."_searchs WHERE postids='$savepostids' AND showposts='0' AND sortby='lastpost' AND sortorder='desc' AND userid='$wbbuserdata[userid]' AND ipaddress='$REMOTE_ADDR'");
 if($result['searchid']) {
  header("Location: search.php?searchid=$result[searchid]&sid=$session[hash]");	
  exit();
 }
 $db->query("INSERT INTO bb".$n."_searchs (searchid,postids,showposts,sortby,sortorder,searchtime,userid,ipaddress)
 VALUES (NULL,'$savepostids','0','lastpost','desc','".time()."','$wbbuserdata[userid]','$REMOTE_ADDR')");
 $searchid=$db->insert_id();
  
 header("Location: search.php?searchid=$searchid&sid=$session[hash]");
 exit(); 	
}
if($_GET['action']=="user") {
 if(!isset($_GET['userid'])) eval("error(\"".$tpl->get("error_falselink")."\");");
 
 $boardids="";
 list($boardcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_boards");
 if($useuseraccess==1 && $wbbuserdata['userid']) {
  $result=$db->query("SELECT b.boardid FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  LEFT JOIN bb".$n."_access a ON (a.userid='$wbbuserdata[userid]' AND b.boardid=a.boardid)
  WHERE b.password='' AND ((p.boardpermission=1 AND a.userid IS NULL) OR (a.userid IS NOT NULL AND a.boardpermission=1))");
 }
 else {
  $result=$db->query("SELECT b.boardid FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  WHERE b.password='' AND p.boardpermission=1");
  }
 if($db->num_rows($result)<$boardcount) {
  while($row=$db->fetch_array($result)) {
   if($boardids!="") $boardids.=','.$row[boardid];
   else $boardids=$row[boardid];
  }
  if(!$boardids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
 }	
	
 $savepostids="";
 $userid=intval($_GET['userid']);
 $result=$db->query("SELECT p.postid FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND p.visible=1 AND p.userid='$userid'
 ".ifelse($boardids,"AND t.boardid IN ($boardids)"));
 while($row=$db->fetch_array($result)) $savepostids.=','.$row[postid];
  
 if(!$savepostids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
 $result=$db->query_first("SELECT searchid FROM bb".$n."_searchs WHERE postids='$savepostids' AND showposts='1' AND sortby='lastpost' AND sortorder='desc' AND userid='$wbbuserdata[userid]' AND ipaddress='$REMOTE_ADDR'");
 if($result['searchid']) {
  header("Location: search.php?searchid=$result[searchid]&sid=$session[hash]");	
  exit();
 }
 $db->query("INSERT INTO bb".$n."_searchs (searchid,postids,showposts,sortby,sortorder,searchtime,userid,ipaddress)
 VALUES (NULL,'$savepostids','1','lastpost','desc','".time()."','$wbbuserdata[userid]','$REMOTE_ADDR')");
 $searchid=$db->insert_id();
  
 header("Location: search.php?searchid=$searchid&sid=$session[hash]");
 exit();	
}
if($_GET['action']=="polls") {
 $boardids="";
 list($boardcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_boards");
 if($useuseraccess==1 && $wbbuserdata['userid']) {
  $result=$db->query("SELECT b.boardid FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  LEFT JOIN bb".$n."_access a ON (a.userid='$wbbuserdata[userid]' AND b.boardid=a.boardid)
  WHERE b.password='' AND ((p.boardpermission=1 AND a.userid IS NULL) OR (a.userid IS NOT NULL AND a.boardpermission=1))");
 }
 else {
  $result=$db->query("SELECT b.boardid FROM bb".$n."_boards b
  LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
  WHERE b.password='' AND p.boardpermission=1");
  }
 if($db->num_rows($result)<$boardcount) {
  while($row=$db->fetch_array($result)) {
   if($boardids!="") $boardids.=','.$row[boardid];
   else $boardids=$row[boardid];
  }
  if(!$boardids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
 }	
	
 $savepostids="";
 $datecute=time()-86400;
 $result=$db->query("SELECT p.postid FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND p.visible=1 AND t.pollid<>0
 ".ifelse($boardids,"AND t.boardid IN ($boardids)"));
 while($row=$db->fetch_array($result)) $savepostids.=','.$row[postid];
  
 if(!$savepostids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
 $result=$db->query_first("SELECT searchid FROM bb".$n."_searchs WHERE postids='$savepostids' AND showposts='0' AND sortby='lastpost' AND sortorder='desc' AND userid='$wbbuserdata[userid]' AND ipaddress='$REMOTE_ADDR'");
 if($result['searchid']) {
  header("Location: search.php?searchid=$result[searchid]&sid=$session[hash]");	
  exit();
 }
 $db->query("INSERT INTO bb".$n."_searchs (searchid,postids,showposts,sortby,sortorder,searchtime,userid,ipaddress)
 VALUES (NULL,'$savepostids','0','lastpost','desc','".time()."','$wbbuserdata[userid]','$REMOTE_ADDR')");
 $searchid=$db->insert_id();
  
 header("Location: search.php?searchid=$searchid&sid=$session[hash]");
 exit();	
}

if(isset($_GET['searchid'])) {
 require("./acp/lib/class_parse.php");

 $searchid=intval($_GET['searchid']);
 if($wbbuserdata['userid']) $search=$db->query_first("SELECT * FROM bb".$n."_searchs WHERE searchid='$searchid' AND userid='$wbbuserdata[userid]'");
 else $search=$db->query_first("SELECT * FROM bb".$n."_searchs WHERE searchid='$searchid' AND ipaddress='$REMOTE_ADDR'");

 if(!$search['searchid']) access_error();
 
 if(isset($_COOKIE['threadvisit'])) $threadvisit=decode_cookie($_COOKIE['threadvisit']);
 else $threadvisit=array();
 
 if(isset($_COOKIE['boardvisit'])) $boardvisit=decode_cookie($_COOKIE['boardvisit']);
 else $boardvisit=array();
 
 if($search['showposts']==1) {
  if(isset($_COOKIE['postvisit'])) $postvisit=decode_cookie($_COOKIE['postvisit']);
  else $postvisit=array();
   
  switch($search['sortby']) {
   case "topic": $sortby="p.posttopic"; break;
   case "replycount": $sortby="t.replycount"; break;
   case "lastpost": $sortby="p.posttime"; break;
   case "author": $sortby="p.username"; break;
   case "board": $sortby="b.title";
   case "views": $sortby="t.views";
   default: $sortby="p.posttime"; break;
  }
  
  switch($search['sortorder']) {
   case "asc": $sortorder="asc"; break;
   case "desc": $sortorder="desc"; break;
   default: $sortorder="desc"; break;
  }
 
  list($postcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_posts WHERE postid IN (0$search[postids])");
 
  if($wbbuserdata['umaxposts']) $postsperpage=$wbbuserdata['umaxposts'];
  else $postsperpage=$default_postsperpage;
  if(isset($_GET['page'])) {
   $page=intval($_GET['page']);
   if($page==0) $page=1;
  }
  else $page=1;
  $pages = ceil($postcount/$postsperpage);
  if($pages>1) $pagelink=makepagelink("search.php?searchid=$searchid&sid=$session[hash]",$page,$pages,$showpagelinks-1);

  $l_posts = ($page-1)*$postsperpage+1;
  $h_posts = $page*$postsperpage;
  if($h_posts > $postcount) $h_posts = $postcount;
  
  $threadjoin="";
  $boardjoin="";
  if(strstr($sortby,'t.') || strstr($sortby,'b.')) $threadjoin="LEFT JOIN bb".$n."_threads t USING (threadid)";
  if(strstr($sortby,'b.')) $boardjoin="LEFT JOIN bb".$n."_boards b USING (boardid)";
 
  $postids="";
  $result = $db->query("SELECT p.postid FROM bb".$n."_posts p
   $threadjoin
   $boardjoin
   WHERE p.postid IN (0$search[postids])
   ORDER BY $sortby $sortorder LIMIT ".($postsperpage*($page-1)).",".$postsperpage);

  while($row=$db->fetch_array($result)) $postids .= ",".$row[postid];
 
  $parse = new parse($docensor,75,0,0,$wbbuserdata['showimages'],$search['searchstring'],$usecode);
  $result = $db->query("SELECT
   p.*,
   t.topic, t.replycount, t.views, t.boardid, t.lastposttime, t.closed, t.prefix,
   b.title, b.allowsmilies AS b_allowsmilies, b.allowhtml, b.allowbbcode, b.allowimages, b.allowicons,
   b.hotthread_reply, b.hotthread_view,
   i.iconpath, i.icontitle
   FROM bb".$n."_posts p
   LEFT JOIN bb".$n."_threads t USING (threadid)
   LEFT JOIN bb".$n."_boards b USING (boardid)
   LEFT JOIN bb".$n."_icons i ON (p.iconid=i.iconid)
   WHERE p.postid IN (0$postids)
   ORDER BY $sortby $sortorder");
 
  $count=0;
  
  while($posts=$db->fetch_array($result)) {
   $tdbgcolor=getone($count,"{tablecolora}","{tablecolorb}");
   $tdid=getone($count,"tablea","tableb");
 
   if($posts['hotthread_reply']==0) $posts['hotthread_reply']=$default_hotthread_reply;
   if($posts['hotthread_view']==0) $posts['hotthread_view']=$default_hotthread_view;
   
   $foldericon=ifelse($wbbuserdata['lastvisit']<$posts['lastposttime'] && $threadvisit[$posts['threadid']]<$posts['lastposttime'] && $boardvisit[$posts['boardid']]<$posts['lastposttime'],"new").ifelse($posts['replycount']>=$posts['hotthread_reply'] || $posts['views']>=$posts['hotthread_view'],"hot").ifelse($posts['closed']!=0,"lock")."folder";
   
   $posts['message']=$parse->doparse($posts['message'],$posts['allowsmilies']*$posts['b_allowsmilies'],$posts['allowhtml'],$posts['allowbbcode'],$posts['allowimages']);
   $posts['posttopic']=$parse->textwrap($posts['posttopic'],60);
   $posts['topic']=$parse->textwrap($posts['topic'],60);
   
   if($posts[iconid] && $posts[allowicons]==1) $posticon="<img src=\"$posts[iconpath]\" alt=\"$posts[icontitle]\">";
   else $posticon="";
   if($wbbuserdata['lastvisit']<$posts['posttime'] && $postvisit[$posts['postid']]!=1 && $boardvisit[$posts['boardid']]<$posts['lastposttime']) eval ("\$postsign = \"".$tpl->get("thread_newpost")."\";");
   else eval ("\$postsign = \"".$tpl->get("thread_nonewpost")."\";");
   $postdate=formatdate($wbbuserdata['dateformat'],$posts['posttime'],1);
   $posttime=formatdate($wbbuserdata['timeformat'],$posts['posttime']);
 
   if($posts['userid']) eval ("\$posts[username] = \"".$tpl->get("thread_username")."\";");
   if($posts['prefix']!="") {
    $threads['prefix']=$posts['prefix'];	
    eval ("\$posts[prefix] = \"".$tpl->get("board_thread_prefix")."\";");
   }
   
   eval ("\$postbit .= \"".$tpl->get("search_postbit")."\";");
   $count++;
  }
  eval("\$tpl->output(\"".$tpl->get("search_post")."\");");
 }
 else {
  switch($search['sortby']) {
   case "topic": $sortby="t.topic"; break;
   case "replycount": $sortby="t.replycount"; break;
   case "lastpost": $sortby="t.lastposttime"; break;
   case "author": $sortby="t.starter"; break;
   case "board": $sortby="b.title";
   case "views": $sortby="t.views";
   default: $sortby="t.lastposttime"; break;
  }
  
  switch($search['sortorder']) {
   case "asc": $sortorder="asc"; break;
   case "desc": $sortorder="desc"; break;
   default: $sortorder="desc"; break;
  }
  
  
  $threadids="";
  $result=$db->query("SELECT DISTINCT threadid FROM bb".$n."_posts WHERE postid IN (0$search[postids])");
  $threadcount=$db->num_rows($result);
  while($row=$db->fetch_array($result)) $threadids.=','.$row['threadid'];
 
  $postsperpage=$default_postsperpage;
  $threadsperpage=$default_threadsperpage;
  if(isset($_GET['page'])) {
   $page=intval($_GET['page']);
   if($page==0) $page=1;
  }
  else $page=1;
  $pages = ceil($threadcount/$threadsperpage);
  if($pages>1) $pagelink=makepagelink("search.php?searchid=$searchid&sid=$session[hash]",$page,$pages,$showpagelinks-1);

  $result=$db->query("SELECT t.threadid FROM bb".$n."_threads t
  ".ifelse($sortby=="f.title","LEFT JOIN bb".$b."_boards b USING (boardid)")."
  WHERE t.threadid IN (0$threadids)
  ORDER BY $sortby $sortorder LIMIT ".($threadsperpage*($page-1)).",".$threadsperpage);
  $threadids="";
  while($row=$db->fetch_array($result)) $threadids.=','.$row['threadid'];
 
  if ($showown && $wbbuserdata['userid']) {
   $ownuserid = "DISTINCT p.userid,";
   $ownjoin = "LEFT JOIN bb".$n."_posts p ON (t.threadid = p.threadid AND p.userid = '$wbbuserdata[userid]')";
  }
  else {
   $ownuserid = "";
   $ownjoin = "";	
  }
   
  $result = $db->query("SELECT
   $ownuserid
   t.*,
   b.title, b.hotthread_reply, b.hotthread_view,
   i.*
   FROM bb".$n."_threads t
   LEFT JOIN bb".$n."_icons i USING (iconid)
   LEFT JOIN bb".$n."_boards b ON (b.boardid=t.boardid)
   $ownjoin
   WHERE t.threadid IN (0$threadids)
   ORDER BY $sortby $sortorder");

  while($threads=$db->fetch_array($result)) {
   unset($firstnew);
   unset($multipages);
   unset($attachments);
   $prefix="";
 
   if(strlen($threads['topic'])>60) $threads['topic']=parse::textwrap($threads['topic'],60);
   if($threads['voted']) {
    $avarage=number_format($threads['votepoints']/$threads['voted'],2);
    eval ("\$threadrating = \"".$tpl->get("board_threadbit_rating")."\";");
    $threadrating=str_repeat($threadrating, round($avarage));
   }
   else $threadrating="&nbsp;";
 
   if($threads['pollid']!=0) eval ("\$prefix .= \"".$tpl->get("board_thread_poll")."\";");
   if($threads['prefix']!="") eval ("\$prefix .= \"".$tpl->get("board_thread_prefix")."\";");
 
   if($threads['hotthread_reply']==0) $threads['hotthread_reply']=$default_hotthread_reply;
   if($threads['hotthread_view']==0) $threads['hotthread_view']=$default_hotthread_view;
    
   if($threads['pollid']!=0) $foldericon="poll";
   else $foldericon=ifelse($threads[userid],"dot").ifelse($wbbuserdata['lastvisit']<$threads['lastposttime'] && $threadvisit[$threads['threadid']]<$threads['lastposttime'] && $boardvisit[$threads['boardid']]<$threads['lastposttime'],"new").ifelse($threads[replycount]>=$threads[hotthread_reply] || $threads[views]>=$threads[hotthread_view],"hot").ifelse($threads[closed]!=0,"lock")."folder";
   if($wbbuserdata[lastvisit]<$threads[lastposttime] && $threadvisit[$threads[threadid]]<$threads[lastposttime]) eval ("\$firstnew = \"".$tpl->get("board_threadbit_firstnew")."\";");
   if($threads[iconid]) $threadicon="<img src=\"$threads[iconpath]\" alt=\"$threads[icontitle]\">";
   else $threadicon="&nbsp;";
   if($threads[starterid]!=0) eval ("\$threads[starter] = \"".$tpl->get("board_threadbit_starter")."\";");
   if($threads[lastposterid]!=0) eval ("\$threads[lastposter] = \"".$tpl->get("board_threadbit_lastposter")."\";");
 
   $lastpostdate=formatdate($wbbuserdata[dateformat],$threads[lastposttime],1);
   $lastposttime=formatdate($wbbuserdata[timeformat],$threads[lastposttime]);
 
   if($threads[replycount]+1>$postsperpage && $showmultipages!=0) {
    unset($multipage);
    unset($multipages_lastpage);
    $xpages=ceil(($threads[replycount]+1)/$postsperpage);
    if($xpages>$showmultipages) {
     eval ("\$multipages_lastpage = \"".$tpl->get("board_threadbit_multipages_lastpage")."\";");
     $xpages=$showmultipages;
    }
    for($i=1;$i<=$xpages;$i++) {
     $multipage.=" ".makehreftag("thread.php?threadid=$threads[threadid]&page=$i&sid=$session[hash]",$i);
    }
    eval ("\$multipages = \"".$tpl->get("board_threadbit_multipages")."\";");
   }
 
   if($threads[attachments]) eval ("\$attachments = \"".$tpl->get("board_threadbit_attachments")."\";");
 
   eval ("\$threadbit .= \"".$tpl->get("search_threadbit")."\";");
  }
  
  $l_threads = ($page-1)*$threadsperpage+1;
  $h_threads = $page*$threadsperpage;
  if($h_threads > $threadcount) $h_threads = $threadcount;
  
  eval("\$tpl->output(\"".$tpl->get("search_thread")."\");");
 }
}
else {
 if(isset($_POST['send'])) {
  $searchstring=trim($_POST['searchstring']);
  $searchuser=trim($_POST['searchuser']);
  
  if(!$searchstring && !$searchuser) eval("error(\"".$tpl->get("error_invalidsearch")."\");");
  
  $postids="";
  if($searchstring) {
   $topiconly=$_POST[topiconly];
   
   $searchstring=preg_replace("/[\/:;'\"\(\)\[\]?!#{}%\-+\\\\]/s","",$searchstring);
   $searchstring=preg_replace("/\s{2,}/"," ",$searchstring);
   $tempsearchstring=$searchstring;
   $searchstring=str_replace("*","%",$searchstring);
   $searchstring=preg_replace("/(%){2,}/s", "%", $searchstring);
   $searchwords=preg_split("/[\s]/", strtolower($searchstring), -1, PREG_SPLIT_NO_EMPTY);
  
   $badwords=array();
   if($badsearchwords) {
    $temp=explode("\n",$badsearchwords);
    while(list($key,$val)=each($temp)) $badwords[trim($val)]=1;
   }
   
   $count_total=0;
   $count_bad=0;
   $firstloop=1;
   $addsplit="";
   $wordids="";
   $tempwordids=array();
   $wordidcache=array();
   $andlist=array();
   $orlist=array();
   $notlist=array();
   $tempwordids=array();
   $foundwordids=array();
   $wordcache=array();
   $doublecount=0;
   $i=array("AND" => 0,"OR" => 0,"NOT" => 0);
   while(list($key,$val)=each($searchwords)) {
    if($val=="and" || $val=="or" || $val=="not") {
     $addsplit=strtoupper($val);	
     continue;
    }
    
    $count_total++;   
    if($badwords[$val]==1 || strlen($val)<$minwordlength || strlen($val)>$maxwordlength) {
     $count_bad++;   
     continue;
    }   
     
    $result=$db->query("SELECT wordid FROM bb".$n."_wordlist WHERE word LIKE '$val'");
    if($db->num_rows($result)) {
     while($row=$db->fetch_array($result)) {
      if($firstloop==1) $tempwordids[]=$row['wordid'];
      else {
       if($addsplit=="") $addsplit="AND";
       $wordidcache[$addsplit][$i[$addsplit]][]=$row['wordid'];
       if(count($tempwordids)) {
        reset($tempwordids);
        $doublecount=1;
        while(list($key2,$wordid)=each($tempwordids)) {
         if($addsplit=="NOT") $wordidcache['AND'][$i[$addsplit]+1][]=$wordid;
         else $wordidcache[$addsplit][$i[$addsplit]+1][]=$wordid;
        }
        $tempwordids=array();
       }
      }
      $wordids.=",".$row['wordid'];
     }
     $firstloop=0;
    }
    elseif($firstloop==0 && $addsplit=="AND") {
     unset($wordids);
     break;
    }
    
    
    if($doublecount==1) {
     $i[$addsplit]++;
     $doublecount=0;
    }
    $i[$addsplit]++;
   }
   
   if($count_bad>0 && $count_bad==$count_total) eval("error(\"".$tpl->get("error_searchbad")."\");");
   
   if(count($tempwordids)) {
    reset($tempwordids);
    while(list($key2,$wordid)=each($tempwordids)) $wordidcache['AND'][$i[$addsplit]][]=$wordid;
   }
   
   $foundpostids=array();
   if($wordids) {
    $result=$db->query("SELECT wordid, postid FROM bb".$n."_wordmatch WHERE wordid IN (0$wordids)".ifelse($topiconly==1," AND intopic=1"));
    while($row=$db->fetch_array($result)) {
     $foundpostids[$row['wordid']][$row['postid']]=1;
    }
   }
   
   function myArrayMerge($array,$add) {
    while(list($key,$val)=each($add)) $array[$key]=$val;
    return $array;	
   }
   
   function mySearchArray($array,$add,$mode) {
    if($mode=="OR") return myArrayMerge($array,$add);
    if($mode=="AND") {
     $newarray=array();
     while(list($key,$val)=each($array)) if($add[$key]==1) $newarray[$key]=1;
     return $newarray;
    }
    if($mode=="NOT") {
     while(list($key,$val)=each($add)) if($array[$key]==1) $array[$key]=0;
     return $array;	
    }
   }
   
   $globalarray=array();
   $addsplit=array("AND","OR","NOT");
   for($i=0;$i<3;$i++) {
    $savearray=array();
    $count=0;
    if(count($wordidcache[$addsplit[$i]])) {
     reset($wordidcache[$addsplit[$i]]);
     while(list($key,$wordids)=each($wordidcache[$addsplit[$i]])) {
      $savearray[$count]=array();
      $badx = 1;
      while(list($key2,$wordid)=each($wordids)) {
       if(isset($foundpostids[$wordid])) {
        $badx=0;
        $savearray[$count]=myArrayMerge($savearray[$count],$foundpostids[$wordid]);
       }
      }
     
      if($badx==0) {
       if(!count($globalarray)) $globalarray=$savearray[$count];
       else $globalarray = mySearchArray($globalarray,$savearray[$count],$addsplit[$i]);
      }
     
      $count++;
     }
    }
   }
   
   $postids="";
   while(list($key,$val)=each($globalarray)) {
    if($val!=1) continue;
    if($postids=="") $postids=$key;
    else $postids.=",$key";	
   }
  }
  
  if($searchuser) {
   $userids="";
   if($_POST['name_exactly']==1) $result=$db->query("SELECT userid FROM bb".$n."_users WHERE username='".addslashes(htmlspecialchars($searchuser))."'");
   else $result=$db->query("SELECT userid FROM bb".$n."_users WHERE username LIKE '%".addslashes(htmlspecialchars($searchuser))."%'");
   while($row=$db->fetch_array($result)) {
    if($userids!="") $userids.=','.$row[userid];
    else $userids=$row[userid];
   }
  }
  
  if(!$userids && !$postids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
  
  if($_POST['searchdate']) {
   $cutetime=time()-86400*intval($_POST['searchdate']);
   if($_POST['beforeafter']=="after") $searchdate="posttime>='$cutetime'";
   else $searchdate="posttime<'$cutetime'";
  }
  reset($_POST['boardids']);
  if(count($_POST['boardids']) && $_POST['boardids'][0]!='*') {
   $tempids="";
   while(list($key,$val)=each($_POST['boardids'])) if($val>0) $tempids.=",".$val;
   if($tempids) {
    $result=$db->query("SELECT boardid, childlist FROM bb".$n."_boards WHERE boardid IN (0$tempids)");
    $selectedids="";
    while($row=$db->fetch_array($result)) {
     $selectedids.=",".$row[boardid];
     if($row[childlist]) $selectedids.=",".$row[childlist];
    }
   }
  }
  
  $boardids="";
  list($boardcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_boards");
  if($useuseraccess==1 && $wbbuserdata[userid]) {
   $result=$db->query("SELECT b.boardid FROM bb".$n."_boards b
   LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
   LEFT JOIN bb".$n."_access a ON (a.userid='$wbbuserdata[userid]' AND b.boardid=a.boardid)
   WHERE ".ifelse($_POST['boardids'][0]!='*',"b.boardid IN (0$selectedids) AND ")."b.password='' AND ((p.boardpermission=1 AND a.userid IS NULL) OR (a.userid IS NOT NULL AND a.boardpermission=1))");
  }
  else {
   $result=$db->query("SELECT b.boardid FROM bb".$n."_boards b
   LEFT JOIN bb".$n."_permissions p ON (p.groupid='$wbbuserdata[groupid]' AND b.boardid=p.boardid)
   WHERE ".ifelse($_POST['boardids'][0]!='*',"b.boardid IN (0$selectedids) AND ")."b.password='' AND p.boardpermission=1");
  }
  if($db->num_rows($result)<$boardcount) {
   while($row=$db->fetch_array($result)) {
    if($boardids!="") $boardids.=','.$row[boardid];
    else $boardids=$row[boardid];
   }
   if(!$boardids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
  }

  $savepostids="";
  $result=$db->query("SELECT p.postid FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND p.visible=1
  ".ifelse($postids,"AND p.postid IN ($postids)")."
  ".ifelse($userids,"AND p.userid IN ($userids)")."
  ".ifelse($boardids,"AND t.boardid IN ($boardids)")."
  ".ifelse($searchdate,"AND $searchdate"));
  while($row=$db->fetch_array($result)) $savepostids.=','.$row[postid];
  
  if(!$savepostids) eval("error(\"".$tpl->get("error_searchnoresult")."\");");
  $result=$db->query_first("SELECT searchid FROM bb".$n."_searchs WHERE postids='$savepostids' AND showposts='$_POST[showposts]' AND sortby='$_POST[sortby]' AND sortorder='$_POST[sortorder]' AND userid='$wbbuserdata[userid]' AND ipaddress='$REMOTE_ADDR'");
  if($result['searchid']) {
   header("Location: search.php?searchid=$result[searchid]&sid=$session[hash]");	
   exit();
  }
  
  $db->query("INSERT INTO bb".$n."_searchs (searchid,searchstring,searchuserid,postids,showposts,sortby,sortorder,searchtime,userid,ipaddress)
  VALUES (NULL,'".addslashes($tempsearchstring)."','".ifelse(!strstr($userids,','),$userids,0)."','$savepostids','".intval($_POST['showposts'])."','".addslashes($_POST['sortby'])."','".addslashes($_POST['sortorder'])."','".time()."','$wbbuserdata[userid]','$REMOTE_ADDR')");
  $searchid=$db->insert_id();
  
  header("Location: search.php?searchid=$searchid&sid=$session[hash]");
  exit(); 
 }
 else {
  $result = $db->query("SELECT boardid, parentid, boardorder, title, invisible FROM bb".$n."_boards ORDER by parentid ASC, boardorder ASC");
  while ($row = $db->fetch_array($result)) $boardcache[$row[parentid]][$row[boardorder]][$row[boardid]] = $row;
    
  $result = $db->query("SELECT * FROM bb".$n."_permissions WHERE groupid = '$wbbuserdata[groupid]'");
  while ($row = $db->fetch_array($result)) $permissioncache[$row[boardid]] = $row;
  if($wbbuserdata[userid] && $useuseraccess==1) {
   $result = $db->query("SELECT * FROM bb".$n."_access WHERE userid = '$wbbuserdata[userid]'");
   while ($row = $db->fetch_array($result)) $permissioncache[$row[boardid]] = $row;
  }
  
  $board_options=makeboardselect(0);
  
  eval("\$tpl->output(\"".$tpl->get("search")."\");");
 }
}
?>