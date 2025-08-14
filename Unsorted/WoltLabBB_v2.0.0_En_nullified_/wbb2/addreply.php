<?php
$filename="addreply.php";

require("./global.php");
require("./acp/lib/class_parse.php");

if(!isset($threadid)) eval("error(\"".$tpl->get("error_falselink")."\");");
if(($wbbuserdata['canreplyowntopic']==0 && $thread['starterid'] && $thread['starterid']==$wbbuserdata['userid']) || $thread['visible']==0 || $wbbuserdata['canreplytopic']==0 || $board['replypermission']==0 || $board['closed']==1 || $board['isboard']==0 || ($thread['closed']!=0 && $wbbuserdata['issupermod']==0 && !$modpermissions['userid'])) access_error();

if($addreply_default_checked_0==1) $checked[0]="checked";
if($wbbuserdata['emailnotify']==1) $checked[1]="checked";
if($addreply_default_checked_2==1) $checked[2]="checked";
if($addreply_default_checked_3==1) $checked[3]="checked";

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
 
 if(isset($_POST['postid'])) $postid=intval($_POST['postid']);
 else $postid=0;
 if(!isset($_POST['preview'])) { 
  $error="";
  if(!$wbbuserdata['userid']) {
   $wbbuserdata['username']=$guestname;
   if(!$wbbuserdata['username'] || !verify_username($wbbuserdata['username'])) eval ("\$error .= \"".$tpl->get("newthread_error2")."\";");
   $wbbuserdata['username']=htmlspecialchars($wbbuserdata['username']);
  }
  if(!$message) eval ("\$error .= \"".$tpl->get("newthread_error1")."\";");
  if(flood_control($wbbuserdata['userid'],$REMOTE_ADDR,$wbbuserdata['avoidfc'])) eval ("\$error .= \"".$tpl->get("newthread_error3")."\";");
  if($wbbuserdata['maxpostimage']!=-1 && substr_count(strtolower($message),"[img]")>$wbbuserdata['maxpostimage']) eval ("\$error .= \"".$tpl->get("newthread_error4")."\";");
  if($error) eval ("\$addreply_error .= \"".$tpl->get("newthread_error")."\";");
  else {
   if($_POST['parseurl']==1) $message=parseURL($message);
   $result=$db->query_first("SELECT postid FROM bb".$n."_posts WHERE threadid='$threadid' AND userid='$wbbuserdata[userid]' AND username='".addslashes($wbbuserdata['username'])."' AND iconid='$iconid' AND posttopic='".addslashes(htmlspecialchars($topic))."' AND message='".addslashes($message)."' AND attachmentid='$attachment_id' AND ipaddress='".$REMOTE_ADDR."' AND posttime>='".(time()-$dpvtime)."' LIMIT 1");
   if($result['postid']) {
    header("Location: thread.php?sid=$session[hash]&postid=$result[postid]#post$result[postid]");
    exit();	
   }
  
   if(!isset($_POST['attachment_id'])) $_POST['attachment_id']=0;
   if($wbbuserdata['canpostwithoutmoderation']==1) $board['moderatenew']=0;
   $time=time();
   $db->query("INSERT INTO bb".$n."_posts (parentpostid,threadid,userid,username,iconid,posttopic,posttime,message,attachmentid,allowsmilies,showsignature,ipaddress,visible) VALUES ('$postid','$threadid','$wbbuserdata[userid]','".addslashes($wbbuserdata['username'])."','$iconid','".addslashes(htmlspecialchars($topic))."','$time','".addslashes($message)."','$attachment_id','".ifelse($_POST['disablesmilies']==1,"0","1")."','".intval($_POST[showsignature])."','".$REMOTE_ADDR."','".ifelse($board['moderatenew']==1 || $board['moderatenew']==11,0,1)."')");
   $postid = $db->insert_id();
   if($attachment_id) {
    $db->unbuffered_query("UPDATE bb".$n."_attachments SET postid='$postid' WHERE attachmentid='$attachment_id'",1);
    $attachmentcount=", attachments=attachments+1";
   }
   else $attachmentcount="";
   if($_POST['emailnotify']==1 && $wbbuserdata['userid']) $db->unbuffered_query("REPLACE INTO bb".$n."_subscribethreads (userid,threadid,emailnotify,countemails) VALUES ($wbbuserdata[userid],$threadid,1,0)");
   wordmatch($postid,$message,$topic);
   if($board['moderatenew']==1 || $board['moderatenew']==11) eval("redirect(\"".$tpl->get("redirect_waiting4moderation")."\",\"board.php?boardid=$boardid&sid=$session[hash]\");");
   else {
    if(isset($_POST['threadclose']) && $_POST['threadclose']==1 && ($wbbuserdata['issupermod'] || $modpermissions['userid'] || ($wbbuserdata['userid'] && $wbbuserdata['userid']==$thread['starterid'] && $wbbuserdata['cancloseowntopic']))) $threadclose=", closed=1";
    else $threadclose="";
    
    $db->unbuffered_query("UPDATE bb".$n."_threads SET lastposttime = '$time', lastposterid = '$wbbuserdata[userid]', lastposter = '".addslashes($wbbuserdata['username'])."', replycount = replycount+1$attachmentcount$threadclose WHERE threadid = '$threadid'",1);
    $db->unbuffered_query("UPDATE bb".$n."_boards SET postcount=postcount+1, lastthreadid='$threadid', lastposttime='$time', lastposterid='$wbbuserdata[userid]', lastposter='".addslashes($wbbuserdata['username'])."' WHERE boardid IN ($board[parentlist],$boardid)",1);
    if($board['countuserposts']==1 && $wbbuserdata['userid']) {
     $wbbuserdata['userposts']+=1;
     list($rankid)=$db->query_first("SELECT rankid FROM bb".$n."_ranks WHERE groupid IN ('0','$wbbuserdata[groupid]') AND needposts<='$wbbuserdata[userposts]' AND gender IN ('0','$wbbuserdata[gender]') ORDER BY needposts DESC, gender DESC LIMIT 1");
     $db->unbuffered_query("UPDATE bb".$n."_users SET userposts=userposts+1".ifelse($rankid!=$wbbuserdata['rankid'],", rankid='$rankid'","")." WHERE userid = '$wbbuserdata[userid]'",1);
    }
   
    $thread['topic']=rehtmlspecialchars($thread['topic']);
    $result=$db->query("SELECT u.email, u.username, s.countemails FROM bb".$n."_subscribethreads s LEFT JOIN bb".$n."_users u USING(userid) WHERE s.threadid='$threadid' AND s.userid<>'$wbbuserdata[userid]' AND s.emailnotify=1 AND s.countemails<'$maxnotifymails' AND u.email is not null");
    while($row=$db->fetch_array($result)) {
     if($row['countemails']==$maxnotifymails-1) eval ("\$mail_text = \"".$tpl->get("mt_newpost_lastone")."\";");
     else eval ("\$mail_text = \"".$tpl->get("mt_newpost")."\";");
     eval ("\$mail_subject = \"".$tpl->get("ms_newpost")."\";");
     mailer($row['email'],$mail_subject,$mail_text);
    }
    $db->unbuffered_query("UPDATE bb".$n."_subscribethreads SET countemails=countemails+1 WHERE threadid='$threadid' AND emailnotify=1 AND countemails<'$maxnotifymails'",1);
   
    header("Location: thread.php?sid=$session[hash]&postid=$postid#post$postid");
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
  eval ("\$preview_window .= \"".$tpl->get("newthread_preview")."\";");	
 }
 if($_POST['parseurl']==1) $checked[0]="checked";
 else $checked[0]="";
 if($_POST['emailnotify']==1) $checked[1]="checked";
 else $checked[1]="";
 if($_POST['disablesmilies']==1) $checked[2]="checked";
 else $checked[2]="";
 if($_POST['showsignature']==1) $checked[3]="checked";
 else $checked[3]="";
 if($_POST['threadclose']==1) $checked[4]="checked";
 else $checked[4]="";
}
elseif(isset($postid)) {
 if($post['posttopic']!="") {
  $post['posttopic']=preg_replace("/^RE: /i","",$post['posttopic']);
  eval ("\$topic = \"".$tpl->get("addreply_quote_topic")."\";");
 }
 if(isset($_REQUEST['action']) && $_REQUEST['action']=="quote") {
  if($docensor==1) {
   $parse = new parse(1);
   $post['message']=$parse->censor($post['message']);
  }
 
  $post['username']=rehtmlspecialchars($post['username']);
  eval ("\$message = \"".$tpl->get("addreply_quote_message")."\";");
 }
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
 $choice_posticons="";
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

eval ("\$note .= \"".$tpl->get("note_html_".ifelse($board[allowhtml]==0,"not_")."allow")."\";");
eval ("\$note .= \"".$tpl->get("note_bbcode_".ifelse($board[allowbbcode]==0,"not_")."allow")."\";");
eval ("\$note .= \"".$tpl->get("note_smilies_".ifelse($board[allowsmilies]==0,"not_")."allow")."\";");
eval ("\$note .= \"".$tpl->get("note_images_".ifelse($board[allowimages]==0,"not_")."allow")."\";");

$postcount=$db->query_first("SELECT COUNT(*) FROM bb".$n."_posts WHERE threadid='$threadid'");
$result = $db->query("SELECT bb".$n."_posts.*, bb".$n."_icons.* FROM bb".$n."_posts LEFT JOIN bb".$n."_icons USING (iconid) WHERE threadid='$threadid' AND visible = 1 ORDER BY posttime DESC",$showpostsinreply);
$postcount=$postcount[0];
if($postcount>$showpostsinreply) {
 $postcount=$showpostsinreply;
 eval ("\$complete_thread = \"".$tpl->get("addreply_complete_thread")."\";");
}
$count=0;
if(!$parse) $parse = new parse($docensor,75,$board['allowsmilies'],$board['allowbbcode'],$wbbuserdata['showimages'],"",$usecode);
while($posts=$db->fetch_array($result)) {
 $tdbgcolor=getone($count,"{tablecolorb}","{tablecolora}");
 $tdid=getone($count,"tableb","tablea");
 $posts['message']=$parse->doparse($posts['message'],$posts['allowsmilies']*$board['allowsmilies'],$board['allowhtml'],$board['allowbbcode'],$board['allowimages']);
 $posts['posttopic']=$parse->textwrap($posts['posttopic'],30);
 if($posts['iconid'] && $board['allowicons']==1) $posticon=makeimgtag($posts['iconpath'],$posts['icontitle']);
 else $posticon="";
  
 eval ("\$postbit .= \"".$tpl->get("addreply_postbit")."\";");
 $count++;
}

if($wbbuserdata['canuploadattachments']==1 && $board['allowattachments']==1) eval ("\$attachment = \"".$tpl->get("newthread_attachment")."\";");
if($wbbuserdata['issupermod'] || $modpermissions['userid'] || ($wbbuserdata['userid'] && $wbbuserdata['userid']==$thread['starterid'] && $wbbuserdata['cancloseowntopic'])) eval ("\$input_threadclose = \"".$tpl->get("addreply_threadclose")."\";");

if(isset($message)) $message=parse::convertHTML($message);
if(isset($topic)) $topic=str_replace("\"","&quot;",$topic);
if(isset($guestname)) $guestname=str_replace("\"","&quot;",$guestname);

if(strlen($thread['topic'])>60) $thread['topic']=parse::textwrap($thread['topic'],60);

eval("\$tpl->output(\"".$tpl->get("addreply")."\");");
?>