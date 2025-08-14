<?php
$filename="pms.php";

require ("./global.php");
require("./acp/lib/class_parse.php");
	
if(!$wbbuserdata['userid'] || $wbbuserdata['canusepms']==0) access_error();

if(isset($_REQUEST['folderid'])) $folderid=$_REQUEST['folderid'];
else $folderid="0";

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";

/* view pms from folder x */	
if(!$action) {
 list($pmcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_privatemessage WHERE recipientid='$wbbuserdata[userid]' AND deletepm<>1");
 $temp=$pmcount/$maxpms;
 $percent=round($temp*100);
 $x=round($temp*190);
 if(isset($_REQUEST['daysprune'])) $daysprune=intval($_REQUEST['daysprune']);
 elseif($wbbuserdata['daysprune']!=0) $daysprune = $wbbuserdata['daysprune'];
 else $daysprune = $default_daysprune;
 
 if($daysprune==1000) $datecute=0;
 elseif($daysprune==1500) $datecute=$wbbuserdata['lastvisit'];
 else $datecute=time() - $daysprune * 86400;
 
 $result = $db->query("SELECT folderid, title FROM bb".$n."_folders WHERE userid='$wbbuserdata[userid]' ORDER BY title ASC");
 $folder_bit="";
 $moveto_options="";
 $folder['title']="";
 while($row=$db->fetch_array($result)) {
  eval ("\$folder_bit .= \"".$tpl->get("pms_folderbit")."\";");
  if($row['folderid']==$folderid) $folder['title']=$row['title'];
  else {
   eval ("\$moveto_options .= \"".$tpl->get("pms_moveto_options")."\";");
  }
 }
 if($folderid!="outbox" && $folderid!=0 && !$folder['title']) access_error(); 
 if(!$folder['title']) $folder['title']="Inbox"; 
 if($folderid!="outbox" && $folderid!=0) eval ("\$folder_rename = \"".$tpl->get("pms_folder_rename")."\";");
 else $folder_rename="";
 $pms_bit="";
  
 $d_select[1]="";
 $d_select[2]="";
 $d_select[5]="";
 $d_select[10]="";
 $d_select[20]="";
 $d_select[30]="";
 $d_select[45]="";
 $d_select[60]="";
 $d_select[75]="";
 $d_select[100]="";
 $d_select[365]="";
 $d_select[$daysprune]=" selected";
 
 if($folderid=="outbox") {
  $result=$db->query("SELECT
   p.privatemessageid, p.subject, p.sendtime, p.iconid,
   i.iconpath, i.icontitle,
   u.userid, u.username
   FROM bb".$n."_privatemessage p
   LEFT JOIN bb".$n."_icons i USING(iconid)
   LEFT JOIN bb".$n."_users u ON (p.recipientid=u.userid)
   WHERE p.senderid='$wbbuserdata[userid]' AND p.sendtime>'$datecute' AND p.deletepm<>2
   ORDER BY sendtime DESC");
  while($row=$db->fetch_array($result)) {
   if($row['iconid']) $icon=makeimgtag($row['iconpath'],$row['icontitle']);
   else $icon="&nbsp;";
  
   $senddate=formatdate($wbbuserdata['dateformat'],$row['sendtime']);
   $sendtime=formatdate($wbbuserdata['timeformat'],$row['sendtime']);
  
   eval ("\$pms_bit .= \"".$tpl->get("pms_bit_outbox")."\";");
  }
 
  eval("\$tpl->output(\"".$tpl->get("pms_outbox")."\");");
 }
 else {
  $result=$db->query("SELECT
   p.privatemessageid, p.subject, p.sendtime, p.view, p.reply, p.forward, p.iconid,
   i.iconpath, i.icontitle,
   u.userid, u.username
   FROM bb".$n."_privatemessage p
   LEFT JOIN bb".$n."_icons i USING(iconid)
   LEFT JOIN bb".$n."_users u ON (p.senderid=u.userid)
   WHERE p.recipientid='$wbbuserdata[userid]' AND p.folderid='".addslashes($folderid)."' AND p.sendtime>'$datecute' AND p.deletepm<>1
   ORDER BY sendtime DESC");
  while($row=$db->fetch_array($result)) {
   if($row['iconid']) $icon=makeimgtag($row['iconpath'],$row['icontitle']);
   else $icon="&nbsp;";
  
   $senddate=formatdate($wbbuserdata['dateformat'],$row['sendtime']);
   $sendtime=formatdate($wbbuserdata['timeformat'],$row['sendtime']);
  
   if($row['sendtime'] >= $wbbuserdata['lastvisit'] && $row['view']==0) $pm_image = makeimgtag("{imagefolder}/pm_new.gif");
   elseif($row['view']==0) $pm_image = makeimgtag("{imagefolder}/pm_unread.gif");
   else {
    if($row['reply']==1 && $row['forward']==1) $pm_image = makeimgtag("{imagefolder}/pm_reward.gif");
    elseif($row['reply']==1) $pm_image = makeimgtag("{imagefolder}/pm_reply.gif");
    elseif($row['forward']==1) $pm_image = makeimgtag("{imagefolder}/pm_forward.gif");
    else $pm_image = makeimgtag("{imagefolder}/pm_normal.gif");
   } 
    
   eval ("\$pms_bit .= \"".$tpl->get("pms_bit")."\";");
  }
 
  eval("\$tpl->output(\"".$tpl->get("pms_folder")."\");");
 }
 exit();
}

/** message tracking **/
if($action=="tracking") {
 list($pmcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_privatemessage WHERE recipientid='$wbbuserdata[userid]' AND deletepm<>1");
 $temp=$pmcount/$maxpms;
 $percent=round($temp*100);
 $x=round($temp*190);
 if(isset($_REQUEST['daysprune'])) $daysprune=intval($_REQUEST['daysprune']);
 elseif($wbbuserdata['daysprune']!=0) $daysprune = $wbbuserdata['daysprune'];
 else $daysprune = $default_daysprune;
 
 $d_select[1]="";
 $d_select[2]="";
 $d_select[5]="";
 $d_select[10]="";
 $d_select[20]="";
 $d_select[30]="";
 $d_select[45]="";
 $d_select[60]="";
 $d_select[75]="";
 $d_select[100]="";
 $d_select[365]="";
 $d_select[$daysprune]=" selected";
 
 if($daysprune==1000) $datecute=0;
 elseif($daysprune==1500) $datecute=$wbbuserdata['lastvisit'];
 else $datecute=time() - $daysprune * 86400;
 
 $folder_bit="";
 $result = $db->query("SELECT folderid, title FROM bb".$n."_folders WHERE userid='$wbbuserdata[userid]' ORDER BY title ASC");
 while($row=$db->fetch_array($result)) eval ("\$folder_bit .= \"".$tpl->get("pms_folderbit")."\";");
 
 $readbit="";
 $unreadbit="";
 $read="";
 $unread="";
 $activtime=time()-60*$useronlinetimeout;
 $result=$db->query("SELECT p.privatemessageid, p.recipientid, p.subject, p.view, p.sendtime, u.username, u.invisible, u.lastactivity, i.iconpath, i.icontitle FROM bb".$n."_privatemessage p
  LEFT JOIN bb".$n."_icons i USING (iconid)
  LEFT JOIN bb".$n."_users u ON (u.userid=p.recipientid)
  WHERE p.senderid='$wbbuserdata[userid]' AND p.tracking=1 AND p.sendtime>'$datecute'
  ORDER BY sendtime DESC");
 while($row=$db->fetch_array($result)) {
  $senddate=formatdate($wbbuserdata['dateformat'],$row['sendtime']);
  $sendtime=formatdate($wbbuserdata['timeformat'],$row['sendtime']);
  	
  $posts['username']=$row['username'];
  if($row['lastactivity']>=$activtime && ($row['invisible']==0 || $wbbuserdata['canuseacp']==1)) eval ("\$user_online = \"".$tpl->get("thread_user_online")."\";");	
  else eval ("\$user_online = \"".$tpl->get("thread_user_offline")."\";");
 
  if($row['iconid']) $icon=makeimgtag($row['iconpath'],$row['icontitle']);
  else $icon="&nbsp;";
 
  if($row['view']==0) eval ("\$unreadbit .= \"".$tpl->get("pms_tracking_unreadbit")."\";");
  else {
   $readdate=formatdate($wbbuserdata['dateformat'],$row['view']);
   $readtime=formatdate($wbbuserdata['timeformat'],$row['view']);
   eval ("\$readbit .= \"".$tpl->get("pms_tracking_readbit")."\";"); 
  }
 }
 
 if($readbit) eval ("\$read = \"".$tpl->get("pms_tracking_read")."\";");
 if($unreadbit) eval ("\$unread = \"".$tpl->get("pms_tracking_unread")."\";");
  
 eval("\$tpl->output(\"".$tpl->get("pms_tracking")."\");");
}

/** create a folder **/
if(isset($_POST['action']) && $_POST['action']=="createfolder") {
 $foldertitle=trim($_POST['foldertitle']);
 if(!$foldertitle) eval("redirect(\"".$tpl->get("redirect_falsefolder")."\",\"pms.php?sid=$session[hash]\",5);");
 
 list($foldercount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_folders WHERE userid='$wbbuserdata[userid]'");
 if($foldercount>=$maxfolders) eval("redirect(\"".$tpl->get("redirect_toomanyfolders")."\",\"pms.php?sid=$session[hash]\",5);");
 
 $db->query("INSERT INTO bb".$n."_folders (folderid,userid,title) VALUES (NULL,'$wbbuserdata[userid]','".addslashes(htmlspecialchars($foldertitle))."')");
 $folderid=$db->insert_id();
 header("Location: pms.php?folderid=$folderid&sid=$session[hash]");
 exit();
}

/** rename a folder **/
if(isset($_POST['action']) && $_POST['action']=="renamefolder") {
 $foldertitle=trim($_POST['foldertitle']);
 $folderid=intval($_POST['folderid']);
 
 list($controluser)=$db->query_first("SELECT userid FROM bb".$n."_folders WHERE folderid='$folderid'");
 if($controluser!=$wbbuserdata['userid']) access_error();
 
 $db->unbuffered_query("UPDATE bb".$n."_folders SET title = '".addslashes(htmlspecialchars($foldertitle))."' WHERE folderid='$folderid'",1);
 header("Location: pms.php?folderid=$folderid&sid=$session[hash]");
 exit();
}

/** remove a folder **/
if(isset($_GET['action']) && $_GET['action']=="removefolder") {
 $folderid=intval($_GET['folderid']);
  
 list($controluser)=$db->query_first("SELECT userid FROM bb".$n."_folders WHERE folderid='$folderid'");
 if($controluser!=$wbbuserdata['userid']) access_error();
 
 $db->unbuffered_query("UPDATE bb".$n."_privatemessage SET folderid = '0' WHERE folderid='$folderid'",1);
 $db->unbuffered_query("DELETE FROM bb".$n."_folders WHERE folderid='$folderid'",1);
 header("Location: pms.php?sid=$session[hash]");
 exit();
}

/** delete marked msgs **/
if(isset($_POST['action']) && $_POST['action']=="delmark") {
 if($_POST['pmid'] && count($_POST['pmid'])) $pmids=implode(',',$_POST['pmid']);
 else $pmids="";
 if($pmids) {
  if($_POST['folderid']=="outbox") {
   $db->query("DELETE FROM bb".$n."_privatemessage WHERE senderid='$wbbuserdata[userid]' AND deletepm=1 AND privatemessageid IN (".addslashes($pmids).")");
   $db->unbuffered_query("UPDATE bb".$n."_privatemessage SET deletepm=2 WHERE senderid='$wbbuserdata[userid]' AND privatemessageid IN (".addslashes($pmids).")",1);
  }	
  else {
   $db->query("DELETE FROM bb".$n."_privatemessage WHERE recipientid='$wbbuserdata[userid]' AND deletepm=2 AND privatemessageid IN (".addslashes($pmids).")");
   $db->unbuffered_query("UPDATE bb".$n."_privatemessage SET deletepm=1, tracking=0 WHERE recipientid='$wbbuserdata[userid]' AND privatemessageid IN (".addslashes($pmids).")",1);	
  }
 }
 header("Location: pms.php?folderid=$folderid&sid=$session[hash]");
 exit();		
}

/** delete all msgs **/ 
if(isset($_POST['action']) && $_POST['action']=="delall") {
 if($_POST['folderid']=="outbox") {
  $db->query("DELETE FROM bb".$n."_privatemessage WHERE senderid='$wbbuserdata[userid]' AND deletepm=1");
  $db->unbuffered_query("UPDATE bb".$n."_privatemessage SET deletepm=2 WHERE senderid='$wbbuserdata[userid]'",1);
 }	
 else {
  $db->query("DELETE FROM bb".$n."_privatemessage WHERE recipientid='$wbbuserdata[userid]' AND folderid='".intval($_POST['folderid'])."' AND deletepm=2");
  $db->unbuffered_query("UPDATE bb".$n."_privatemessage SET deletepm=1, tracking=0 WHERE recipientid='$wbbuserdata[userid]' AND folderid='".intval($_POST['folderid'])."'",1);	
 }
 header("Location: pms.php?folderid=$folderid&sid=$session[hash]");
 exit();	
}

/** view a pm **/
if(isset($_GET['action']) && $_GET['action']=="viewpm") {
 if(isset($_GET['outbox'])) {
  $pmid=intval($_GET['pmid']);
  $pm=$db->query_first("SELECT 
   p.*, 
   i.iconpath, i.icontitle,
   u.userid, u.username, u.signature
   FROM bb".$n."_privatemessage p
   LEFT JOIN bb".$n."_icons i USING(iconid)
   LEFT JOIN bb".$n."_users u ON (p.recipientid=u.userid)
   WHERE p.privatemessageid='$pmid' AND p.deletepm<>2");
  if($pm['senderid']!=$wbbuserdata['userid']) eval("error(\"".$tpl->get("error_falselink")."\");");
  
  $senddate=formatdate($wbbuserdata['dateformat'],$pm['sendtime']);
  $sendtime=formatdate($wbbuserdata['timeformat'],$pm['sendtime']);
  if($pm['iconid']) $icon=makeimgtag($pm['iconpath'],$pm['icontitle']);
  else $icon="";
  
  $parse = new parse($docensor,90,$pm['showsmilies']*$pm_allowsmilies,$pm_allowbbcode,$wbbuserdata['showimages'],"",$usecode);
  $pm['message']=$parse->doparse($pm['message'],$pm['showsmilies']*$pm_allowsmilies,$pm_allowhtml,$pm_allowbbcode,$pm_allowimages);
  $pm['subject']=$parse->textwrap($pm['subject'],30);
  if($pm['showsignature']==1 && $wbbuserdata['showsignatures']==1 && $wbbuserdata['signature']) {
   $posts['signature']=$parse->doparse($wbbuserdata['signature'],$pm['showsmilies']*$allowsigsmilies,$allowsightml,$allowsigbbcode,$maxsigimage);
   eval ("\$signature = \"".$tpl->get("thread_signature")."\";");
  }
  eval("\$tpl->output(\"".$tpl->get("pms_viewpm_outbox")."\");");	
 }
 else {
  $pmid=intval($_GET['pmid']);
  $pm=$db->query_first("SELECT 
   p.*, f.*,
   i.iconpath, i.icontitle,
   u.userid, u.username, u.signature
   FROM bb".$n."_privatemessage p
   LEFT JOIN bb".$n."_icons i USING(iconid)
   LEFT JOIN bb".$n."_users u ON (p.senderid=u.userid)
   LEFT JOIN bb".$n."_folders f ON (p.folderid=f.folderid)
   WHERE p.privatemessageid='$pmid' AND p.deletepm<>1");
  if($pm['recipientid']!=$wbbuserdata['userid']) eval("error(\"".$tpl->get("error_falselink")."\");");
  if($pm['view']==0) $db->query("UPDATE bb".$n."_privatemessage SET view='".time()."' WHERE privatemessageid='$pmid'");
  
  $senddate=formatdate($wbbuserdata['dateformat'],$pm['sendtime']);
  $sendtime=formatdate($wbbuserdata['timeformat'],$pm['sendtime']);
  if($pm['iconid']) $icon=makeimgtag($pm['iconpath'],$pm['icontitle']);
  else $icon="";
  
  if($pm['folderid']==0) $pm['title']="Inbox"; 
  $parse = new parse($docensor,90,$pm['showsmilies']*$pm_allowsmilies,$pm_allowbbcode,$wbbuserdata['showimages'],"",$usecode);
  $pm['message']=$parse->doparse($pm['message'],$pm['showsmilies']*$pm_allowsmilies,$pm_allowhtml,$pm_allowbbcode,$pm_allowimages);
  $pm['subject']=$parse->textwrap($pm['subject'],30);
  if($pm['showsignature']==1 && $wbbuserdata['showsignatures']==1 && $pm['signature']) {
   $posts['signature']=$parse->doparse($pm['signature'],$pm['showsmilies']*$allowsigsmilies,$allowsightml,$allowsigbbcode,$maxsigimage);
   eval ("\$signature = \"".$tpl->get("thread_signature")."\";");
  }
  eval("\$tpl->output(\"".$tpl->get("pms_viewpm")."\");");
 }	
}

/** create a new pm **/
if($_REQUEST['action']=="newpm" || $_REQUEST['action']=="replypm" || $_REQUEST['action']=="forwardpm") {
 if($newpm_default_checked_0==1) $checked[0]="checked";
 if($newpm_default_checked_1==1) $checked[1]="checked";
 if($newpm_default_checked_2==1) $checked[2]="checked";
 if($newpm_default_checked_3==1) $checked[3]="checked";
 if($newpm_default_checked_4==1) $checked[4]="checked";
 if(isset($_REQUEST['pmid'])) $pmid=intval($_REQUEST['pmid']);
 
 if(isset($_POST['send'])) {
  $subject=trim($_POST['subject']);
  $recipient=trim($_POST['recipient']);
  $message=stripcrap(trim($_POST['message']));
  if(isset($_POST['iconid'])) $iconid=intval($_POST['iconid']);
  else $iconid=0;
  
  if(!isset($_POST['preview'])) {
   $error="";
   if(!$subject || !$recipient || !$message) eval ("\$error .= \"".$tpl->get("newthread_error1")."\";");
   if($recipient) {
    $result=$db->query_first("SELECT userid, username, email, ignorelist, receivepm, emailonpm, pmpopup FROM bb".$n."_users WHERE username='".addslashes(htmlspecialchars($recipient))."'");
    if(!$result['userid']) eval ("\$error .= \"".$tpl->get("pms_newpm_error1")."\";");
    else {
     if($result['receivepm']==0) eval ("\$error .= \"".$tpl->get("pms_newpm_error2")."\";");
     elseif(add2list($result['ignorelist'],$wbbuserdata['userid'])==-1) eval ("\$error .= \"".$tpl->get("pms_newpm_error3")."\";");
     else {
      list($countpms)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_privatemessage WHERE recipientid='$result[userid]' AND deletepm<>1");	
      if($countpms>=$maxpms) eval ("\$error .= \"".$tpl->get("pms_newpm_error4")."\";");
     }
    }
   }
   if($error) eval ("\$pm_error = \"".$tpl->get("newthread_error")."\";");
   else {
    if($_POST['parseurl']==1) $message=parseURL($message);
    $db->unbuffered_query("INSERT INTO bb".$n."_privatemessage (privatemessageid,senderid,recipientid,subject,message,sendtime,showsmilies,showsignature,iconid,deletepm,tracking) VALUES (NULL,'$wbbuserdata[userid]','$result[userid]','".addslashes(htmlspecialchars($subject))."','".addslashes($message)."','".time()."','".(1-intval($_POST['disablesmilies']))."','".intval($_POST['showsignature'])."','$iconid','".ifelse($_POST['savecopy']==1,0,2)."','".intval($_POST['tracking'])."')",1);	
    if($result['pmpopup']==1) $db->unbuffered_query("UPDATE bb".$n."_users SET pmpopup=2 WHERE userid='$result[userid]'",1);	
  
    if($result['emailonpm']==1) {
     eval ("\$mail_text = \"".$tpl->get("mt_newpm")."\";");
     eval ("\$mail_subject = \"".$tpl->get("ms_newpm")."\";");
     mailer($result['email'],$mail_subject,$mail_text);  	
    }
   
    if($_REQUEST['action']=="replypm") $db->unbuffered_query("UPDATE bb".$n."_privatemessage SET reply=1 WHERE privatemessageid='$pmid' AND recipientid='$wbbuserdata[userid]'",1);
    if($_REQUEST['action']=="forwardpm") $db->unbuffered_query("UPDATE bb".$n."_privatemessage SET forward=1 WHERE privatemessageid='$pmid' AND recipientid='$wbbuserdata[userid]'",1);
    header("Location: pms.php?sid=$session[hash]");
    exit();
   }
  }
  else {
   $allowsmilies=1-intval($_POST['disablesmilies']);
   $parse = new parse($docensor,75,$allowsmilies*$pm_allowsmilies,$pm_allowbbcode,$wbbuserdata['showimages'],"",$usecode);
   $preview_subject=$parse->textwrap(htmlspecialchars($subject),30);
   $preview_message=$parse->doparse(ifelse($_POST['parseurl']==1,parseURL($message),$message),$allowsmilies*$pm_allowsmilies,$pm_allowhtml,$pm_allowbbcode,$pm_allowimages);
   if($iconid) {
    $result = $db->query_first("SELECT * FROM bb".$n."_icons WHERE iconid = '$iconid'");
    $preview_posticon=makeimgtag($result['iconpath'],$result['icontitle']);
   } 
   eval ("\$preview_window = \"".$tpl->get("pms_newpm_preview")."\";");	
  }
  if($_POST['parseurl']==1) $checked[0]="checked";
  else $checked[0]="";
  if($_POST['disablesmilies']==1) $checked[1]="checked";
  else $checked[1]="";
  if($_POST['showsignature']==1) $checked[2]="checked";
  else $checked[2]="";
  if($_POST['savecopy']==1) $checked[3]="checked";
  else $checked[3]="";
  if($_POST['tracking']==1) $checked[4]="checked";
  else $checked[4]="";
 }
 else {
  if(isset($_GET['userid'])) list($recipient)=$db->query_first("SELECT username FROM bb".$n."_users WHERE userid='".intval($_GET['userid'])."'");
  if($_REQUEST['action']=="replypm" || $_REQUEST['action']=="forwardpm") {
   $pm = $db->query_first("SELECT p.senderid, p.subject, p.message, p.sendtime, u.username FROM bb".$n."_privatemessage p LEFT JOIN bb".$n."_users u ON (u.userid=p.senderid) WHERE p.privatemessageid='$pmid' AND p.recipientid='$wbbuserdata[userid]'");
   $sendtime=formatdate($wbbuserdata['dateformat']." ".$wbbuserdata['timeformat'],$pm['sendtime']);
   if($docensor==1) {
    if($parse) $pm['message']=$parse->censor($pm['message']);	
    else {
     $parse = new parse(1);
     $pm['message']=$parse->censor($pm['message']);	
    }	
   }
  
   $pm['username']=rehtmlspecialchars($pm['username']);
  
   if($_REQUEST['action']=="replypm") {
    $pm['subject']=preg_replace("/^RE: /i","",$pm['subject']);
    eval ("\$subject = \"".$tpl->get("pms_reply_subject")."\";");
    eval ("\$message = \"".$tpl->get("pms_reply_message")."\";");	
    $recipient=$pm['username'];
   }	
   if($_REQUEST['action']=="forwardpm") {
    $pm['subject']=preg_replace("/^FW: /i","",$pm['subject']);
    eval ("\$subject = \"".$tpl->get("pms_forward_subject")."\";");
    eval ("\$message = \"".$tpl->get("pms_forward_message")."\";");	
   }		
  }
 }
 
 if(!isset($iconid)) $iconid=0;
 
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
 eval ("\$pm_icons .= \"".$tpl->get("newthread_icons")."\";");
 

 if($pm_allowbbcode==1) $bbcode_buttons = getcodebuttons();
 if($pm_allowsmilies==1) $bbcode_smilies = getclickysmilies($smilie_table_cols,$smilie_table_rows);
	
 eval ("\$note = \"".$tpl->get("note_html_".ifelse($pm_allowhtml==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_bbcode_".ifelse($pm_allowbbcode==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_smilies_".ifelse($pm_allowsmilies==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_images_".ifelse($pm_allowimages==0,"not_")."allow")."\";");

 if(isset($message)) $message=parse::convertHTML($message);
 if(isset($subject)) $topic=str_replace("\"","&quot;",$subject);
 if(isset($recipient)) $topic=str_replace("\"","&quot;",$recipient);
	
 eval("\$tpl->output(\"".$tpl->get("pms_newpm")."\");");
}

/** stop tracking **/
if(isset($_POST['action']) && $_POST['action']=="endtracking") {
 if($_POST['pmid'] && count($_POST['pmid'])) $pmids=implode(',',$_POST['pmid']);
 else $pmids="";
 if($pmids) $db->unbuffered_query("UPDATE bb".$n."_privatemessage SET tracking=0 WHERE senderid='$wbbuserdata[userid]' AND privatemessageid IN (".addslashes($pmids).")",1);
 header("Location: pms.php?action=tracking&sid=$session[hash]");
 exit();		
}

/** cancel message **/
if(isset($_POST['action']) && $_POST['action']=="cancel") {
 if($_POST['pmid'] && count($_POST['pmid'])) $pmids=implode(',',$_POST['pmid']);
 else $pmids="";
 if($pmids) $db->unbuffered_query("DELETE FROM bb".$n."_privatemessage WHERE senderid='$wbbuserdata[userid]' AND privatemessageid IN (".addslashes($pmids).") AND view=0");
 header("Location: pms.php?action=tracking&sid=$session[hash]");
 exit();		
}

/** download a message -> txt file **/
if(isset($_GET['action']) && $_GET['action']=="downloadpm") {
 $pm=$db->query_first("SELECT p.privatemessageid, p.subject, p.message, p.sendtime, u.username FROM bb".$n."_privatemessage p LEFT JOIN bb".$n."_users u ON (u.userid=p.senderid) WHERE privatemessageid='".intval($_GET['pmid'])."' AND recipientid='$wbbuserdata[userid]'");	
 if(!$pm['privatemessageid']) eval("error(\"".$tpl->get("error_falselink")."\");");	
 $sendtime=formatdate($wbbuserdata['dateformat']." ".$wbbuserdata['timeformat'],$pm['sendtime']);	

 $mime_type = (USR_BROWSER_AGENT == 'IE' || USR_BROWSER_AGENT == 'OPERA') ? 'application/octetstream' : 'application/octet-stream';
 $content_disp = (USR_BROWSER_AGENT == 'IE') ? 'inline; ' : 'attachment; ';
 header('Content-Type: '.$mime_type);
 header('Content-disposition: '.$content_disp.'filename="pm-'.$pm['privatemessageid'].'.txt"');
 header('Pragma: no-cache');
 header('Expires: 0');
 
 eval("print(\"".$tpl->get("pms_download")."\");");
}

/** delete one message **/
if($_REQUEST['action']=="deletepm") {
 $pmid=intval($_REQUEST['pmid']);
 if(isset($_REQUEST['outbox'])) $outbox=intval($_REQUEST['outbox']);
 else $outbox=0;
 if(isset($_POST['send']) && $_POST['send']=="send") {
  if($outbox==1) {
   $db->query("DELETE FROM bb".$n."_privatemessage WHERE senderid='$wbbuserdata[userid]' AND deletepm=1 AND privatemessageid='$pmid'");
   $db->unbuffered_query("UPDATE bb".$n."_privatemessage SET deletepm=2 WHERE senderid='$wbbuserdata[userid]' AND privatemessageid='$pmid'",1);
   header("Location: pms.php?folderid=outbox&sid=$session[hash]");
  }
  else {
   $db->query("DELETE FROM bb".$n."_privatemessage WHERE recipientid='$wbbuserdata[userid]' AND deletepm=2 AND privatemessageid='$pmid'");
   $db->unbuffered_query("UPDATE bb".$n."_privatemessage SET deletepm=1, tracking=0 WHERE recipientid='$wbbuserdata[userid]' AND privatemessageid='$pmid'",1);	
   header("Location: pms.php?sid=$session[hash]");
  }
  exit();
 }	

 eval("\$tpl->output(\"".$tpl->get("pms_deletepm")."\");");
}

/** print message **/
if($_REQUEST['action']=="printpm") {
 $pmid=intval($_REQUEST['pmid']);
 $pm=$db->query_first("SELECT 
  p.*, i.iconpath, i.icontitle,
  u.userid, u.username, u.signature
  FROM bb".$n."_privatemessage p
  LEFT JOIN bb".$n."_icons i USING(iconid)
  LEFT JOIN bb".$n."_users u ON (p.senderid=u.userid)
  WHERE p.privatemessageid='$pmid' AND p.deletepm<>1");
 if($pm['recipientid']!=$wbbuserdata['userid']) eval("error(\"".$tpl->get("error_falselink")."\");");
  
 $senddate=formatdate($wbbuserdata['dateformat'],$pm['sendtime']);
 $sendtime=formatdate($wbbuserdata['timeformat'],$pm['sendtime']);
 
 if($pm['iconid']) $icon=makeimgtag($pm['iconpath'],$pm['icontitle']);
 else $icon="";
  
 $parse = new parse($docensor,90,$pm['showsmilies']*$pm_allowsmilies,$pm_allowbbcode,$wbbuserdata['showimages'],"",$usecode);
 $pm['message']=$parse->doparse($pm['message'],$pm['showsmilies']*$pm_allowsmilies,$pm_allowhtml,$pm_allowbbcode,$pm_allowimages);
 $pm['subject']=$parse->textwrap($pm['subject'],30);
 if($pm['showsignature']==1 && $wbbuserdata['showsignatures']==1 && $pm['signature']) {
  $posts['signature']=$parse->doparse($pm['signature'],$pm['showsmilies']*$allowsigsmilies,$allowsightml,$allowsigbbcode,$maxsigimage);
  eval ("\$signature = \"".$tpl->get("thread_signature")."\";");
 }
 
 eval("\$tpl->output(\"".$tpl->get("pms_printpm")."\");");	
}

if($_REQUEST['action']=="popup") {
 $result=$db->query("SELECT
  p.privatemessageid, p.subject, p.sendtime, p.iconid,
  i.iconpath, i.icontitle,
  u.userid, u.username
  FROM bb".$n."_privatemessage p
  LEFT JOIN bb".$n."_icons i USING(iconid)
  LEFT JOIN bb".$n."_users u ON (p.senderid=u.userid)
  WHERE p.recipientid='$wbbuserdata[userid]' AND p.sendtime>'$wbbuserdata[lastvisit]' AND p.view=0 AND p.deletepm<>1
  ORDER BY p.sendtime DESC");
  
 $pmscount=$db->num_rows($result);
  
 $pmbit="";
 while($row=$db->fetch_array($result)) {
  if($row['iconid']) $icon=makeimgtag($row['iconpath'],$row['icontitle']);
  else $icon="&nbsp;";
  
  $senddate=formatdate($wbbuserdata['dateformat'],$row['sendtime']);
  $sendtime=formatdate($wbbuserdata['timeformat'],$row['sendtime']);	
    
  eval ("\$pmbit .= \"".$tpl->get("pmpopup_pmbit")."\";");	
 }
 
 eval ("\$tpl->output(\"".$tpl->get("pmpopup")."\");");
 exit();
}


/** move marked msgs to x **/
if(isset($_POST['action']) && substr($_POST['action'],0,6)=="moveto") {
 $tofolderid=substr($_POST['action'],7);	
 if($_POST['pmid'] && count($_POST['pmid'])) $pmids=implode(',',$_POST['pmid']);
 else $pmids="";
 if($pmids) {
  list($controluser)=$db->query_first("SELECT userid FROM bb".$n."_folders WHERE folderid='$tofolderid'");
  if($controluser!=$wbbuserdata['userid']) access_error();
 
  $db->query("UPDATE bb".$n."_privatemessage SET folderid='$tofolderid' WHERE recipientid='$wbbuserdata[userid]' AND privatemessageid IN (".addslashes($pmids).")");	
 }
 header("Location: pms.php?folderid=$folderid&sid=$session[hash]");
 exit();
}
?>
