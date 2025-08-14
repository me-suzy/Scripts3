<?php
require ("./global.php");
isAdmin();
@set_time_limit(1200);

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";

if(!$action) {
 list($reindexposts)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_posts WHERE reindex=1");
 
 eval("print(\"".gettemplate("otherstuff")."\");");
}

if($action=="doing") eval("print(\"".gettemplate("doing")."\");");

if($action=="delindex") {
 if(isset($_POST['send'])) {
  $db->unbuffered_query("DELETE FROM bb".$n."_wordlist",1);	
  $db->unbuffered_query("DELETE FROM bb".$n."_wordmatch",1);	
  $db->unbuffered_query("UPDATE bb".$n."_posts SET reindex=1",1);	
  header("Location: otherstuff.php?sid=$session[hash]");
  exit();	
 }	
	
 eval("print(\"".gettemplate("delindex")."\");");	
}

if($action=="userposts") {
 if(isset($_REQUEST['perpage'])) $perpage=intval($_REQUEST['perpage']);
 else $perpage=0;
 if($perpage==0) $perpage=1;
 if(isset($_REQUEST['page'])) $page=intval($_REQUEST['page']);
 else $page=0;
 if($page==0) $page=1;
 
 $result=$db->query("SELECT userid, userposts FROM bb".$n."_users ORDER BY userid ASC",$perpage,$perpage*($page-1));
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   list($userposts)=$db->query_first("SELECT COUNT(postid) FROM bb".$n."_posts p, bb".$n."_threads t LEFT JOIN bb".$n."_boards b ON (t.boardid=b.boardid) WHERE t.threadid=p.threadid AND p.userid='$row[userid]' AND p.visible=1 AND b.countuserposts=1");	
   if($userposts!=$row['userposts']) $db->unbuffered_query("UPDATE bb".$n."_users SET userposts='$userposts' WHERE userid='$row[userid]'",1);
  }
  refresh("otherstuff.php?sid=$session[hash]&action=userposts&perpage=$perpage&page=".($page+1));
 }
 else eval("print(\"".gettemplate("working_done")."\");");	
}

if($action=="threads") {
 if(isset($_REQUEST['perpage'])) $perpage=intval($_REQUEST['perpage']);
 else $perpage=0;
 if($perpage==0) $perpage=1;
 if(isset($_REQUEST['page'])) $page=intval($_REQUEST['page']);
 else $page=0;
 if($page==0) $page=1;
 
 $result=$db->query("SELECT COUNT(p.postid) AS attachments, t.threadid FROM bb".$n."_threads t LEFT JOIN bb".$n."_posts p ON (t.threadid=p.threadid AND p.attachmentid>0) GROUP BY t.threadid",$perpage,$perpage*($page-1));
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) $db->unbuffered_query("UPDATE bb".$n."_threads SET attachments='$row[attachments]' WHERE threadid='$row[threadid]'",1);
  refresh("otherstuff.php?sid=$session[hash]&action=threads&perpage=$perpage&page=".($page+1));
 }	
 else eval("print(\"".gettemplate("working_done")."\");");	
}

if($action=="ranks") {
 if(isset($_REQUEST['perpage'])) $perpage=intval($_REQUEST['perpage']);
 else $perpage=0;
 if($perpage==0) $perpage=1;
 if(isset($_REQUEST['page'])) $page=intval($_REQUEST['page']);
 else $page=0;
 if($page==0) $page=1;
 
 $result=$db->query("SELECT userid, groupid, gender, userposts FROM bb".$n."_users",$perpage,$perpage*($page-1));		
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   list($rankid)=$db->query_first("SELECT rankid FROM bb".$n."_ranks WHERE groupid IN ('0','$row[groupid]') AND needposts<='$row[userposts]' AND gender IN ('0','$row[gender]') ORDER BY needposts DESC, gender DESC",1);
   $db->unbuffered_query("UPDATE bb".$n."_users SET rankid='$rankid' WHERE userid='$row[userid]'",1);	
  }	
  refresh("otherstuff.php?sid=$session[hash]&action=ranks&perpage=$perpage&page=".($page+1));	
 }	
 else eval("print(\"".gettemplate("working_done")."\");");	
}

if($action=="reindex") {
 if(isset($_REQUEST['perpage'])) $perpage=intval($_REQUEST['perpage']);
 else $perpage=0;
 if($perpage==0) $perpage=1;
 
 $caching=array();
 $result=$db->query("SELECT postid, message, posttopic FROM bb".$n."_posts WHERE reindex=1",$perpage);
 if($db->num_rows($result)) {
  $postids="0";
  while($row=$db->fetch_array($result)) {
   $caching[]=$row;
   $postids.=",".$row['postid'];
  }
  
  $db->unbuffered_query("DELETE FROM bb".$n."_wordmatch WHERE postid IN ($postids)",1);
  
  reset($caching);
  while(list($key,$val)=each($caching)) {
   wordmatch($val['postid'],$val['message'],$val['posttopic']);	
  }
  
  $db->unbuffered_query("UPDATE bb".$n."_posts SET reindex=0 WHERE postid IN ($postids)",1);
  refresh("otherstuff.php?sid=$session[hash]&action=reindex&perpage=$perpage");
 }	
 else eval("print(\"".gettemplate("working_done")."\");");
}

if($action=="loademail") eval("print(\"".gettemplate("working_loademail")."\");");

if($action=="email") {
 $perpage=250;
 
 if(isset($_REQUEST['page'])) $page=intval($_REQUEST['page']);
 else $page=0;
 if($page==0) $page=1;
 
 $userids=$_REQUEST['userids'];
 $subject=$_REQUEST['subject'];
 $message=$_REQUEST['message'];
 $emailtype=$_REQUEST['emailtype'];
 if(!$userids) $userids="0";
 
 if($userids=="all") $result=$db->query("SELECT username, email FROM bb".$n."_users WHERE admincanemail = 1",$perpage,$perpage*($page-1));
 else $result=$db->query("SELECT username, email FROM bb".$n."_users WHERE userid IN ($userids) AND admincanemail = 1",$perpage,$perpage*($page-1));
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   $temp=str_replace("{username}",$row['username'],$message);
   mailer($row['email'],$subject,$temp,"",ifelse($emailtype=="html","\nMIME-Version: 1.0\nContent-type: text/html; charset=iso-8859-1"));
  }
  $page+=1;
  $subject=htmlspecialchars($subject);
  $message=htmlspecialchars($message);
  eval("print(\"".gettemplate("refresh_email")."\");");
 }	
 else eval("print(\"".gettemplate("working_emaildone")."\");");
}

if($action=="wordmatch") {
 $result = $db->query("SELECT wordlist.word, wordlist.wordid, COUNT(wordmatch.postid) AS mount
 FROM bb".$n."_wordlist wordlist
 LEFT JOIN bb".$n."_wordmatch wordmatch USING (wordid)
 GROUP BY wordlist.wordid ORDER BY mount DESC",100);

 $wordbit="";	
 while($row=$db->fetch_array($result)) {
  $row['word']=htmlspecialchars($row['word']);
  eval ("\$wordbit .= \"".gettemplate("wordmatch_wordbit")."\";");	
 }
 
 eval("print(\"".gettemplate("wordmatch")."\");");	
}

if($action=="wordmatch2") {
 if($_POST['wordids']) $wordids = implode(",",$_POST['wordids']);
 
 if($wordids) {
  $badsearchwords=trim($badsearchwords);
  $result=$db->query("SELECT word FROM bb".$n."_wordlist WHERE wordid IN ($wordids)");
  while($row=$db->fetch_array($result)) {
   if($badsearchwords!="") $badsearchwords.="\n".$row['word'];
   else $badsearchwords=$row['word'];	
  }
  
  $db->query("UPDATE bb".$n."_options SET value='".addslashes($badsearchwords)."' WHERE varname='badsearchwords'");
  
  require ("./lib/class_options.php");
  $option=new options("lib");
  $option->write();	

  $db->unbuffered_query("DELETE FROM bb".$n."_wordmatch WHERE wordid IN ($wordids)",1);
  $db->unbuffered_query("DELETE FROM bb".$n."_wordlist WHERE wordid IN ($wordids)",1);	
 }
 
 eval("print(\"".gettemplate("wordmatch2")."\");");
}

if($action=="adminsessions") {
 if(isset($_POST['send'])) {
  if(isset($_POST['all']) && $_POST['all']) {
   $db->query("DELETE FROM bb".$n."_adminsessions WHERE lastactivity<='".(time()-$adminsession_timeout)."'");
  }
  else {
   $kicksession=$_POST['kicksession'];
   if(is_array($kicksession) && count($kicksession)) {
    $sessionlist = str_replace(",","','",implode(",",$kicksession));
    $db->query("DELETE FROM bb".$n."_adminsessions WHERE hash IN ('$sessionlist') AND lastactivity<='".(time()-$adminsession_timeout)."'");	
   }
  }	
 }
  
 $perpage=30;
 $sortby=$_REQUEST['sortby'];
 $sortorder=$_REQUEST['sortorder'];
 
 switch($sortby) {
  case "username": break;
  case "starttime": break;
  case "lastactivity": break;
  case "ipaddress": break;
  case "useragent": break;
  default: $sortby="starttime";
 }
 
 switch($sortorder) {
  case "ASC": break;
  case "DESC": break;
  default: $sortorder="DESC";
 }
 
 $page=intval($_REQUEST['page']);
 if($page==0) $page=1;
 
 list($sessioncount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_adminsessions");

 $pages = ceil($sessioncount/$perpage);
 if($pages>1) $pagelink = makeadminpagelink("otherstuff.php?action=adminsessions&sid=$session[hash]&sortby=$sortby&sortorder=$sortorder",$page,$pages,2);
 else $pagelink="";

 $offset=$perpage*($page-1);
 $result=$db->query("SELECT a.*, u.username FROM bb".$n."_adminsessions a LEFT JOIN bb".$n."_users u USING(userid) ORDER BY $sortby $sortorder",$perpage,$offset);	
 $count=0;
 while($row=$db->fetch_array($result)) {
   if($row['lastactivity']>time()-$adminsession_timeout) $disabled=" DISABLED";
   else $disabled="";
   
   $row['starttime']=formatdate($wbbuserdata['dateformat']." ".$wbbuserdata['timeformat'],$row['starttime']);
   $row['lastactivity']=formatdate($wbbuserdata['dateformat']." ".$wbbuserdata['timeformat'],$row['lastactivity']);
   if(strlen($row['useragent'])>50) $row['useragent']=substr($row['useragent'],0,48)."...";
   $rowclass=getone($count++,"firstrow","secondrow");
      
   eval("\$sessionbit .= \"".gettemplate("adminsession_bit")."\";");		
 }
 
 $s_sortby[$sortby]=" SELECTED";
 $s_sortorder[$sortorder]=" SELECTED";
 	
 eval("print(\"".gettemplate("adminsessions")."\");");	
}

if($action=="selectstats") {
 if($usedbtemplates==1) {
  require ("./lib/class_tpl_db.php");
  $tpl = new tpl(0,0);
  $tpl->templatelist="months";
  $tpl->cache();
 }
 else {
  require("./lib/class_tpl_file.php");
  $tpl = new tpl(0,0,"../");
 } 
 
 $installday = date("j",$installdate);
 $installmonth = date("n",$installdate);
 $installyear = date("Y",$installdate);
 $currentyear = date("Y");
 $currentday = date("j");
 $currentmonth = date("n");
 
 $from_day = "";
 $from_month = "";
 $from_year = "";
 
 for($i=1;$i<32;$i++) $from_day .= makeoption($i,$i,$installday);
 for($i=1;$i<13;$i++) $from_month .= makeoption($i,getmonth($i),$installmonth);
 for($i=$installyear;$i<=$currentyear;$i++) $from_year .= makeoption($i,$i,$installyear);

 $to_day = "";
 $to_month = "";
 $to_year = "";
 
 for($i=1;$i<32;$i++) $to_day .= makeoption($i,$i,$currentday);
 for($i=1;$i<13;$i++) $to_month .= makeoption($i,getmonth($i),$currentmonth);
 for($i=$installyear;$i<=$currentyear;$i++) $to_year .= makeoption($i,$i,$currentyear);

 eval("print(\"".gettemplate("stats_select")."\");");	
}

if($action=="showstats") {
 switch($_REQUEST['type']) {
  case 1: 
   $table = "bb".$n."_users";
   $datefield = "regdate";
   break;
  case 2: 
   $table = "bb".$n."_threads";
   $datefield = "starttime";
   break;
  case 3: 
   $table = "bb".$n."_posts";
   $datefield = "posttime";
   break;
  case 4: 
   $table = "bb".$n."_polls";
   $datefield = "starttime";
   break;
  default: 
   $table = "bb".$n."_privatemessage";
   $datefield = "sendtime";
 }
 
 switch($_POST['timeorder']) {
  case 1:
   $sqlformat = "%w %U %m %Y";
   $phpformat = "w~, ".$wbbuserdata['dateformat'];
   break;
  case 2:
   $sqlformat = "%U %Y";
   $phpformat = "# (n~ Y)";
   break;
  default:
   $sqlformat = "%m %Y";
   $phpformat = "n~ Y";
 }
 
 switch($_POST['sortorder']) {
  case "asc": break;
  default: $_POST['sortorder']="desc";
 }
  
 $to = mktime(24,0,0,$_POST['to_month'],$_POST['to_day'],$_POST['to_year']);
 $from = mktime(0,0,0,$_POST['from_month'],$_POST['from_day'],$_POST['from_year']);
 
 if($usedbtemplates==1) {
  require ("./lib/class_tpl_db.php");
  $tpl = new tpl(0,0);
  $tpl->templatelist="months,days";
  $tpl->cache();
 }
 else {
  require("./lib/class_tpl_file.php");
  $tpl = new tpl(0,0,"../");
 }
 
 $max=0;
 $cache=array();
 $result = $db->query("SELECT COUNT(*), DATE_FORMAT(FROM_UNIXTIME($datefield),'$sqlformat') AS timeorder, MAX($datefield) AS statdate FROM $table WHERE $datefield > '$from' AND $datefield < '$to' GROUP BY timeorder ORDER BY $datefield $_POST[sortorder]");
 while($row=$db->fetch_array($result)) {
  if($row[0]>$max) $max=$row[0];
  
  $statdate=date($phpformat,$row['statdate']);
  
  if($_POST['timeorder']==1) $statdate=preg_replace("/(\d+)~/e","getday('\\1')",$statdate);
  if($_POST['timeorder']>1) $statdate=preg_replace("/(\d+)~/e","getmonth('\\1')",$statdate);
  if($_POST['timeorder']==2) $statdate=str_replace("#","#".ceil(date("z",$row['statdate'])/7),$statdate);
  
  $cache[]=array($row[0],$statdate);
 }
 
 $showbit="";
 if(count($cache)) {
  while(list($key,$stat)=each($cache)) {
   $width=round($stat[0]/$max*500);
   eval("\$showbit .= \"".gettemplate("stats_showbit")."\";");	
  }
 }

 eval("print(\"".gettemplate("stats_show")."\");");
}
?>
