<?php
$filename="newthread.php";

require("./global.php");

if($_REQUEST['action']=="announce") {
 if(!isset($threadid) || $thread['important']!=2) eval("error(\"".$tpl->get("error_falselink")."\");");
 if(($wbbuserdata['issupermod']==0 && $modpermissions['userid']!=$wbbuserdata['userid']) || !$wbbuserdata['userid']) access_error();
 $action="announce";
 
 if(isset($_POST['send'])) {
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
  
 $board_options=makeboardselect(0,1,$boardid);

 $navbar=getNavbar($board['parentlist']);
 eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");
 	
 eval("\$tpl->output(\"".$tpl->get("newthread_announce")."\");");	
 exit();	
}

require("./acp/lib/class_parse.php");

unset($message);
unset($topic);
unset($guestname);

if(!isset($boardid)) eval("error(\"".$tpl->get("error_falselink")."\");");
if($wbbuserdata['canstarttopic']==0 || $board['startpermission']==0 || $board['closed']==1 || $board['isboard']==0) access_error();

if($newthread_default_checked_0==1) $checked[0]="checked";
if($wbbuserdata['emailnotify']==1) $checked[1]="checked";
if($newthread_default_checked_2==1) $checked[2]="checked";
if($newthread_default_checked_3==1) $checked[3]="checked";
$imp_checked[0]="checked";


if(isset($_POST['send'])) {
 $topic=trim($_POST['topic']);
 if($dostopshooting==1) $topic=stopShooting($topic);
 $message=stripcrap(trim($_POST['message']));
 if(isset($_POST['iconid'])) $iconid=intval($_POST['iconid']);
 else $iconid=0;
 if(!$wbbuserdata['userid']) $guestname=trim($_POST['guestname']);
 if(isset($_POST['attachment_id'])) {
  $attachment_id=intval($_POST['attachment_id']);
  list($attachment_verify) = $db->query_first("SELECT COUNT(*) FROM bb".$n."_posts WHERE attachmentid = '$attachment_id'");
  if($attachment_verify!=0) $attachment_id=0;
 }
 else $attachment_id=0;
 
 if(!isset($_POST['preview'])) {
  $error="";
  if(!$wbbuserdata['userid']) {
   $wbbuserdata['username']=$guestname;
   if(!$wbbuserdata['username'] || !verify_username($wbbuserdata['username'])) eval ("\$error .= \"".$tpl->get("newthread_error2")."\";");
   $wbbuserdata['username']=htmlspecialchars($wbbuserdata['username']);
  }
  if(!$topic || !$message) eval ("\$error .= \"".$tpl->get("newthread_error1")."\";");
  if(flood_control($wbbuserdata['userid'],$REMOTE_ADDR,$wbbuserdata['avoidfc'])) eval ("\$error .= \"".$tpl->get("newthread_error3")."\";");
  if($wbbuserdata['maxpostimage']!=-1 && substr_count(strtolower($message),"[img]")>$wbbuserdata['maxpostimage']) eval ("\$error .= \"".$tpl->get("newthread_error4")."\";");
  if($error) eval ("\$newthread_error .= \"".$tpl->get("newthread_error")."\";");
  else {
   if($_POST['parseurl']==1) $message=parseURL($message);
   $result=$db->query_first("SELECT postid FROM bb".$n."_posts WHERE userid='$wbbuserdata[userid]' AND username='".addslashes($wbbuserdata['username'])."' AND iconid='$iconid' AND posttopic='".addslashes(htmlspecialchars($topic))."' AND message='".addslashes($message)."' AND attachmentid='$attachment_id' AND ipaddress='".$REMOTE_ADDR."' AND posttime>='".(time()-$dpvtime)."'",1);
   if($result['postid']) {
    header("Location: thread.php?postid=$result[postid]&sid=$session[hash]#post$result[postid]");
    exit();	
   }
  
   if(isset($_POST['poll_id'])) {
    $poll_id=intval($_POST['poll_id']);
    $poll_verify = $db->query_first("SELECT threadid FROM bb".$n."_polls WHERE pollid = '$poll_id'");
    if($poll_verify['threadid'] || $poll_verify['threadid']!=0) $poll_id=0;
   }
   else $poll_id=0;
   
   if($wbbuserdata['canpostwithoutmoderation']==1) $board['moderatenew']=0;
   $time=time();
   if(isset($_POST['important']) && ($wbbuserdata['issupermod'] || ($modpermissions['userid']==$wbbuserdata['userid'] && $wbbuserdata['userid']))) $important=intval($_POST['important']);
   else $important=0;
   
   $db->query("INSERT INTO bb".$n."_threads (threadid,boardid,prefix,topic,iconid,starttime,starterid,starter,lastposttime,lastposterid,lastposter,attachments,pollid,important,visible) VALUES (NULL,'$boardid','".addslashes(htmlspecialchars($_POST['prefix']))."','".addslashes(htmlspecialchars($topic))."','$iconid','$time','$wbbuserdata[userid]','".addslashes($wbbuserdata[username])."','$time','$wbbuserdata[userid]','".addslashes($wbbuserdata[username])."','".ifelse($attachment_id,1,0)."','$poll_id','$important','".ifelse($board['moderatenew']==10 || $board['moderatenew']==11,0,1)."')");
   $threadid=$db->insert_id();
  
   if($_POST['poll_id']) $db->unbuffered_query("UPDATE bb".$n."_polls SET threadid='$threadid' WHERE pollid='$poll_id'",1);
  
   $db->query("INSERT INTO bb".$n."_posts (postid,threadid,userid,username,iconid,posttopic,posttime,message,attachmentid,allowsmilies,showsignature,ipaddress,visible) VALUES (NULL,'$threadid','$wbbuserdata[userid]','".addslashes($wbbuserdata['username'])."','$iconid','".addslashes(htmlspecialchars($topic))."','$time','".addslashes($message)."','$attachment_id','".(1-intval($_POST['disablesmilies']))."','".intval($_POST['showsignature'])."','".$REMOTE_ADDR."','".ifelse($board['moderatenew']==10 || $board['moderatenew']==11,0,1)."')");
   $postid=$db->insert_id();
   if($attachment_id) $db->unbuffered_query("UPDATE bb".$n."_attachments SET postid='$postid' WHERE attachmentid='$attachment_id'",1);
  
   if($_POST['emailnotify']==1 && $wbbuserdata['userid']) $db->query("INSERT INTO bb".$n."_subscribethreads (userid,threadid,emailnotify,countemails) VALUES ($wbbuserdata[userid],$threadid,1,0)");
   
   wordmatch($postid,$message,$topic);
   if($board['moderatenew']==10 || $board['moderatenew']==11) eval("redirect(\"".$tpl->get("redirect_waiting4moderation")."\",\"board.php?boardid=$boardid&sid=$session[hash]\",10);");
   else {
    $db->query("UPDATE bb".$n."_boards SET threadcount=threadcount+1, postcount=postcount+1, lastthreadid='$threadid', lastposttime='$time', lastposterid='$wbbuserdata[userid]', lastposter='".addslashes($wbbuserdata[username])."' WHERE boardid IN ($board[parentlist],$boardid)");
    if($board['countuserposts']==1  && $wbbuserdata['userid']) {
     $wbbuserdata['userposts']+=1;
     list($rankid)=$db->query_first("SELECT rankid FROM bb".$n."_ranks WHERE groupid IN ('0','$wbbuserdata[groupid]') AND needposts<='$wbbuserdata[userposts]' AND gender IN ('0','$wbbuserdata[gender]') ORDER BY needposts DESC, gender DESC LIMIT 1");
     $db->query("UPDATE bb".$n."_users SET userposts=userposts+1".ifelse($rankid!=$wbbuserdata['rankid'],", rankid='$rankid'","")." WHERE userid = '$wbbuserdata[userid]'");
    }
   
    $result=$db->query("SELECT u.email, u.username, s.countemails FROM bb".$n."_subscribeboards s LEFT JOIN bb".$n."_users u USING(userid) WHERE s.boardid='$boardid' AND s.userid<>'$wbbuserdata[userid]' AND s.emailnotify=1 AND s.countemails<'$maxnotifymails' AND u.email is not null");
    while($row=$db->fetch_array($result)) {
     eval ("\$mail_subject = \"".$tpl->get("ms_newthread")."\";");
     if($row['countemails']==$maxnotifymails-1) eval ("\$mail_text = \"".$tpl->get("mt_newthread_lastone")."\";");
     else eval ("\$mail_text = \"".$tpl->get("mt_newthread")."\";");
     mailer($row['email'],$mail_subject,$mail_text);
    }
    $db->query("UPDATE bb".$n."_subscribeboards SET countemails=countemails+1 WHERE boardid='$boardid' AND userid<>'$wbbuserdata[userid]' AND emailnotify=1 AND countemails<'$maxnotifymails'");
   
    if($important==2) {
     $db->unbuffered_query("INSERT INTO bb".$n."_announcements (boardid,threadid) VALUES ('$boardid','$threadid')",1);
     header("Location: newthread.php?action=announce&threadid=$threadid&sid=$session[hash]");
    }
    else header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
    exit();
   }
  }
 }
 else {
  $allowsmilies=1-intval($_POST['disablesmilies']);
  $parse = new parse($docensor,75,$allowsmilies*$board['allowsmilies'],$board['allowbbcode'],$wbbuserdata['showimages'],"",$usecode);
  $preview_topic=$parse->textwrap(htmlspecialchars($topic),30);
  $preview_message=$parse->doparse(ifelse($_POST['parseurl']==1,parseURL($message),$message),$allowsmilies*$board['allowsmilies'],$board['allowhtml'],$board['allowbbcode'],$board['allowimages']);
  if($iconid) {
   $result = $db->query_first("SELECT * FROM bb".$n."_icons WHERE iconid = '$iconid'");
   $preview_posticon=makeimgtag($result['iconpath'],$result['icontitle']);
  }
  eval ("\$preview_window = \"".$tpl->get("newthread_preview")."\";");	
 }
 if($_POST['parseurl']==1) $checked[0]="checked";
 else $checked[0]="";
 if($_POST['emailnotify']==1) $checked[1]="checked";
 else $checked[1]="";
 if($_POST['disablesmilies']==1) $checked[2]="checked";
 else $checked[2]="";
 if($_POST['showsignature']==1) $checked[3]="checked";
 else $checked[3]="";
 if(isset($_POST['important'])) {
  if($_POST['important']==2) $imp_checked[2]="checked";	
  if($_POST['important']==1) $imp_checked[1]="checked";	
  if($_POST['important']!=0) $imp_checked[0]="";	
 }
 
 if(isset($_POST['attachmentname'])) $attachmentname=$_POST['attachmentname'];
 else $attachmentname="";
 if(isset($_POST['attachment_id'])) $attachment_id=$_POST['attachment_id'];
 else $attachment_id=0;
}

$navbar=getNavbar($board['parentlist']);
eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");

if($wbbuserdata['userid']==0) eval ("\$newthread_username .= \"".$tpl->get("newthread_username_input")."\";");
else eval ("\$newthread_username .= \"".$tpl->get("newthread_username")."\";");

if(!isset($iconid)) $iconid=0;

if($board['allowicons']==1) {
 $ICONselected[$iconid]="checked";
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
 eval ("\$newthread_icons .= \"".$tpl->get("newthread_icons")."\";");
}

if($board['allowbbcode']==1) $bbcode_buttons = getcodebuttons();
if($board['allowsmilies']==1) $bbcode_smilies = getclickysmilies($smilie_table_cols,$smilie_table_rows);

eval ("\$note .= \"".$tpl->get("note_html_".ifelse($board['allowhtml']==0,"not_")."allow")."\";");
eval ("\$note .= \"".$tpl->get("note_bbcode_".ifelse($board['allowbbcode']==0,"not_")."allow")."\";");
eval ("\$note .= \"".$tpl->get("note_smilies_".ifelse($board['allowsmilies']==0,"not_")."allow")."\";");
eval ("\$note .= \"".$tpl->get("note_images_".ifelse($board['allowimages']==0,"not_")."allow")."\";");

if($wbbuserdata['canuploadattachments']==1 && $board['allowattachments']==1) eval ("\$attachment = \"".$tpl->get("newthread_attachment")."\";");
if($wbbuserdata['canpostpoll'] && $board['allowpolls']==1) eval ("\$poll = \"".$tpl->get("newthread_poll")."\";");
if($wbbuserdata['issupermod'] || ($wbbuserdata['userid'] && $modpermissions['userid']==$wbbuserdata['userid'])) eval ("\$newthread_important = \"".$tpl->get("newthread_important")."\";");

if(isset($message)) $message=parse::convertHTML($message);
if(isset($topic)) $topic=str_replace("\"","&quot;",$topic);
if(isset($guestname)) $guestname=str_replace("\"","&quot;",$guestname);

if($board['prefixuse']>0) {
 if($board['prefixuse']==1) $ch_prefix = $default_prefix;
 if($board['prefixuse']==2) $ch_prefix = $default_prefix."\n".$board['prefix'];
 if($board['prefixuse']==3) $ch_prefix = $board['prefix'];
 
 $ch_prefix = preg_replace("/\s*\n\s*/","\n",trim($ch_prefix));
 $ch_prefix = explode("\n",$ch_prefix);	
 sort($ch_prefix);
 
 $prefix_options="";
 for($i=0;$i<count($ch_prefix);$i++) $prefix_options.=makeoption($ch_prefix[$i],$ch_prefix[$i],$_POST['prefix'],1);	
	
 if($prefix_options!="") eval ("\$select_prefix = \"".$tpl->get("newthread_prefix")."\";");
}

eval("\$tpl->output(\"".$tpl->get("newthread")."\");");
?>