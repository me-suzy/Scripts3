<?php
$filename="editpost.php";

require("./global.php");
require("./acp/lib/class_parse.php");

if(!isset($postid)) eval("error(\"".$tpl->get("error_falselink")."\");");
$isuser=0;
$ismod=0;
if($wbbuserdata['userid'] && $wbbuserdata['userid']==$post['userid'] && $wbbuserdata['caneditownpost']==1) $isuser=1;
if($wbbuserdata['issupermod']==1 || $modpermissions['userid']) $ismod=1;

if(($isuser==0 && $ismod==0) || ($isuser==1 && $thread['closed']>0 && $ismod==0)) access_error();

if(isset($_POST['send']) && $_POST['send']=="send2") {
 if($_POST['deletepost']==1) {
  if($wbbuserdata['candelownpost']==1 || $ismod==1) {
   if($post['postid']) {
    $db->query("DELETE FROM bb".$n."_posts WHERE postid = '$postid'");
    if($thread['replycount']==0) {
     $db->unbuffered_query("UPDATE bb".$n."_boards SET threadcount=threadcount-1, postcount=postcount-1 WHERE boardid IN ($boardid,$board[parentlist])",1);
     $db->unbuffered_query("DELETE FROM bb".$n."_subscribethreads WHERE threadid = '$threadid'",1);
     if($thread['pollid']) {
      $db->unbuffered_query("DELETE FROM bb".$n."_polls WHERE pollid = '$thread[pollid]'",1);
      $pollvotes=" OR (id = '$thread[pollid]' AND votemode=1)";
      $db->unbuffered_query("DELETE FROM bb".$n."_polloptions WHERE pollid = '$thread[pollid]'",1);
     }
     else $pollvotes="";
     $db->unbuffered_query("DELETE FROM bb".$n."_votes WHERE (id = '$threadid' AND votemode=2)$pollvotes",1);
  
     $db->unbuffered_query("DELETE FROM bb".$n."_threads WHERE threadid = '$threadid'",1);
     $db->unbuffered_query("DELETE FROM bb".$n."_threads WHERE pollid = '$threadid' AND closed=3",1);
     if($thread['important']==2) $db->unbuffered_query("DELETE FROM bb".$n."_announcements WHERE threadid = '$threadid'",1);
    }
    else {
     /* for threaded view -> */
     $db->unbuffered_query("UPDATE bb".$n."_posts SET parentpostid = '$post[parentpostid]' WHERE parentpostid = '$postid'",1);
     
     $db->unbuffered_query("UPDATE bb".$n."_boards SET postcount=postcount-1 WHERE boardid IN ($boardid,$board[parentlist])",1);
     if($thread['lastposttime']<=$post['posttime']) {
      $result=$db->query_first("SELECT userid, username, posttime FROM bb".$n."_posts WHERE threadid='$threadid' ORDER BY posttime DESC",1);
      $db->unbuffered_query("UPDATE bb".$n."_threads SET replycount=replycount-1, lastposttime='$result[posttime]', lastposterid='$result[userid]', lastposter='".addslashes($result['username'])."'".ifelse($post['attachmentid'],", attachments=attachments-1")." WHERE threadid='$threadid'",1);
     }
     else $db->unbuffered_query("UPDATE bb".$n."_threads SET replycount=replycount-1 WHERE threadid='$threadid'",1);
    }
    
    updateBoardInfo("$boardid,$board[parentlist]",$post['posttime']);
        
    if($post['attachmentid']) {
     $attachment=$db->query_first("SELECT attachmentextension FROM bb".$n."_attachments WHERE attachmentid = '$post[attachmentid]'");
     @unlink("attachments/attachment-".$attachment['attachmentid'].".".$attachment['attachmentextension']);
     $db->unbuffered_query("DELETE FROM bb".$n."_attachments WHERE attachmentid = '$post[attachmentid]'",1);
    }
    if($board['countuserposts'] && $post['userid']) $db->unbuffered_query("UPDATE bb".$n."_users SET userposts=userposts-1 WHERE userid = '$post[userid]'",1);
    if($thread['replycount']==0) header("Location: board.php?boardid=$boardid&sid=$session[hash]");
    else header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
    exit();
   }
  }
  else access_error();
 }
 else {
  header("Location: thread.php?sid=$session[hash]&postid=$postid#post$postid");
  exit();	
 }
}

if($editpost_default_checked_0==1) $checked[0]="checked";
if($wbbuserdata['emailnotify']==1) $checked[1]="checked";

if(isset($_POST['send'])) {
 $topic=trim($_POST['topic']);
 if($dostopshooting==1) $topic=stopShooting($topic);
 $message=stripcrap(trim($_POST['message']));
 if(isset($_POST['iconid'])) $iconid=intval($_POST['iconid']);
 else $iconid=0;
 
 if(!isset($_POST['preview'])) { 
  $error="";
  if(!$message) eval ("\$error .= \"".$tpl->get("newthread_error1")."\";");
  if($wbbuserdata['maxpostimage']!=-1 && substr_count(strtolower($message),"[img]")>$wbbuserdata['maxpostimage']) eval ("\$error .= \"".$tpl->get("newthread_error4")."\";");
  if($error) eval ("\$editpost_error .= \"".$tpl->get("newthread_error")."\";");
  else {
   if(!isset($_POST['attachment_id'])) $_POST['attachment_id']=0;
   if($_POST['parseurl']==1) $message=parseURL($message);
   $db->query("UPDATE bb".$n."_posts SET iconid='$iconid', posttopic='".addslashes(htmlspecialchars_wbb($topic))."', message='".addslashes($message)."', ".ifelse($_POST['appendeditnote']==1,"edittime='".time()."', editorid='$wbbuserdata[userid]', editor='".addslashes($wbbuserdata[username])."', editcount=editcount+1,","")."allowsmilies='".(1-intval($_POST['disablesmilies']))."', showsignature='".intval($_POST['showsignature'])."', attachmentid='".intval($_POST['attachment_id'])."', reindex='1' WHERE postid='$postid'");
   if(!$_POST['attachment_id'] && $post['attachmentid']) $db->query("UPDATE bb".$n."_threads SET attachments=attachments-1 WHERE threadid='$threadid'");
   if($_POST['attachment_id'] && !$post['attachmentid']) $db->query("UPDATE bb".$n."_threads SET attachments=attachments+1 WHERE threadid='$threadid'");
   if($_POST['attachment_id'] && $_POST['attachment_id']!=$post['attachmentid']) $db->query("UPDATE bb".$n."_attachments SET postid='$postid' WHERE attachmentid='".intval($_POST['attachment_id'])."'");
  
   if($_POST['emailnotify']==1 && $wbbuserdata['userid']) {
    $result = $db->query_first("SELECT userid, emailnotify FROM bb".$n."_subscribethreads WHERE userid='$wbbuserdata[userid]' AND threadid='$threadid'");
    if(!$result['userid']) $db->query("INSERT INTO bb".$n."_subscribethreads (userid,threadid,emailnotify,countemails) VALUES ($wbbuserdata[userid],$threadid,1,0)");
    elseif($result['emailnotify']==0) $db->query("UPDATE bb".$n."_subscribethreads SET emailnotify=1 WHERE userid='$wbbuserdata[userid]' AND threadid='$threadid'");
   }
   header("Location: thread.php?sid=$session[hash]&postid=$postid#post$postid");
   exit();
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
 if($_POST[appendeditnote]==1) $checked[4]="checked";
 else $checked[4]="";
}
else {
 $message=$post['message'];
 $topic=$post['posttopic'];
 $iconid=$post['iconid'];
 $disablesmilies=1-$post['allowsmilies'];
 $showsignature=$post['showsignature'];
 if($disablesmilies==1) $checked[2]="checked";
 else $checked[2]="";
 if($showsignature==1) $checked[3]="checked";
 else $checked[3]="";
 
 if($post['attachmentid']) {
  $result=$db->query_first("SELECT * FROM bb".$n."_attachments WHERE attachmentid='$post[attachmentid]'");
  $attachment_id=$result['attachmentid'];
  $attachmentname=$result['attachmentname'].".".$result['attachmentextension'];
 }
}

$navbar=getNavbar($board['parentlist']);
eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");

if($wbbuserdata[userid]==0) eval ("\$newthread_username .= \"".$tpl->get("newthread_username_input")."\";");
else eval ("\$newthread_username .= \"".$tpl->get("newthread_username")."\";");

if($board[allowicons]==1) {
 $ICONselected[$iconid]="checked";
 $result = $db->query("SELECT * FROM bb".$n."_icons ORDER BY iconorder ASC");
 $iconcount=0;
 while($row=$db->fetch_array($result)) {
  $row_iconid=$row[iconid];
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

if($wbbuserdata['appendeditnote']==0) eval ("\$input_appendeditnote = \"".$tpl->get("editpost_appendeditnote")."\";");
else $input_appendeditnote="<input type=\"hidden\" name=\"appendeditnote\" value=\"1\">";

if($wbbuserdata['candelownpost']==1 || $ismod==1) eval ("\$editpost_delpost = \"".$tpl->get("editpost_delpost")."\";");

if($wbbuserdata['canuploadattachments']) eval ("\$attachment .= \"".$tpl->get("newthread_attachment")."\";");

if(isset($message)) $message=parse::convertHTML($message);
if(isset($topic)) $topic=str_replace("\"","&quot;",$topic);

if(strlen($thread['topic'])>60) $thread['topic']=parse::textwrap($thread['topic'],60);

eval("\$tpl->output(\"".$tpl->get("editpost")."\");");
?>