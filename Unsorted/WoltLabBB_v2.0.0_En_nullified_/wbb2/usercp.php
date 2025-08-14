<?php
$filename="usercp.php";

require ("./global.php");

if(!$wbbuserdata['userid']) access_error();

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";

if(!$action) eval("\$tpl->output(\"".$tpl->get("usercp")."\");");

if($action=="profile_edit") {
 if(isset($_POST['send'])) {
  if(is_array($_POST['field'])) $field=trim_array($_POST['field']);
  if(isset($_POST['r_email'])) $r_email = trim($_POST['r_email']);
  if(isset($_POST['r_homepage'])) $r_homepage = trim($_POST['r_homepage']);
  if(isset($_POST['r_icq'])) $r_icq = trim($_POST['r_icq']);
  if(isset($_POST['r_aim'])) $r_aim = trim($_POST['r_aim']);
  if(isset($_POST['r_yim'])) $r_yim = trim($_POST['r_yim']);
  if(isset($_POST['r_msn'])) $r_msn = trim($_POST['r_msn']);
  if(isset($_POST['r_day'])) $r_day = trim($_POST['r_day']);
  if(isset($_POST['r_month'])) $r_month = trim($_POST['r_month']);
  if(isset($_POST['r_year'])) $r_year = trim($_POST['r_year']);
  if(isset($_POST['r_gender'])) $r_gender = trim($_POST['r_gender']);
  if(isset($_POST['r_usertext'])) $r_usertext = trim($_POST['r_usertext']);
  
  $error="";
  $userfield_error=0;
  $fieldvalues="";
  
  $result = $db->query("SELECT profilefieldid, required FROM bb".$n."_profilefields ORDER BY profilefieldid ASC");
  while($row=$db->fetch_array($result)) {
   if($row['required']==1 && !$field[$row['profilefieldid']]) {
    $userfield_error=1;
    break;
   }
   if($fieldvalues) $fieldvalues.=", field$row[profilefieldid] = '".addslashes(htmlspecialchars($field[$row['profilefieldid']]))."'";
   else $fieldvalues="field$row[profilefieldid] = '".addslashes(htmlspecialchars($field[$row['profilefieldid']]))."'";
  }
  
  if($userfield_error==1 || !$r_email) eval ("\$error .= \"".$tpl->get("register_error1")."\";");
  if($r_email != $wbbuserdata['email'] && !verify_email($r_email)) eval ("\$error .= \"".$tpl->get("register_error4")."\";");
  if(strlen($r_usertext)>$wbbuserdata['maxusertextlength']) eval ("\$error .= \"".$tpl->get("register_error7")."\";");
  if($error) eval ("\$usercp_error .= \"".$tpl->get("usercp_error")."\";");
  else {
   if($r_homepage && !preg_match("/[a-zA-Z]:\/\//si", $r_homepage)) $r_homepage = "http://".$r_homepage;  
   if($r_day && $r_month) $birthday=ifelse(strlen($r_year)==4,$r_year,ifelse(strlen($r_year)==2,"19$r_year","0000"))."-".ifelse($r_month<10,"0$r_month",$r_month)."-".ifelse($r_day<10,"0$r_day",$r_day);
   else $birthday = "0000-00-00";
   
   list($rankid)=$db->query_first("SELECT rankid FROM bb".$n."_ranks WHERE groupid IN ('0','$wbbuserdata[groupid]') AND needposts<='$wbbuserdata[userposts]' AND gender IN ('0','".intval($r_gender)."') ORDER BY needposts DESC, gender DESC",1);
   
   $db->query("UPDATE bb".$n."_users SET email='".addslashes(htmlspecialchars($r_email))."', usertext='".addslashes(htmlspecialchars($r_usertext))."', icq='".intval($r_icq)."', aim='".addslashes(htmlspecialchars($r_aim))."', yim='".addslashes(htmlspecialchars($r_yim))."', msn='".addslashes(htmlspecialchars($r_msn))."', homepage='".addslashes(htmlspecialchars($r_homepage))."', birthday='".addslashes(htmlspecialchars($birthday))."', gender='".intval($r_gender)."'".ifelse($rankid!=$wbbuserdata['rankid'],", rankid='$rankid'","")." WHERE userid = '$wbbuserdata[userid]'");
   if($fieldvalues) $db->query("UPDATE bb".$n."_userfields SET $fieldvalues WHERE userid = '$wbbuserdata[userid]'");
   
   if($r_email != $wbbuserdata['email'] && $emailverifymode!=0) {
    if($emailverifymode==3) {
     $r_password=password_generate();
     $db->query("UPDATE bb".$n."_users SET password='".md5($r_password)."' WHERE userid = '$wbbuserdata[userid]'");
     $db->query("UPDATE bb".$n."_sessions SET userid=0 WHERE hash='$sid'");
    
     eval ("\$subject = \"".$tpl->get("ms_emailchange3")."\";");
     eval ("\$content = \"".$tpl->get("mt_emailchange3")."\";");
     mailer($r_email,$subject,$content);	
     	
     eval("redirect(\"".$tpl->get("redirect_emailchange3")."\",\"index.php?sid=$session[hash]\",20);");
    }
    if($emailverifymode==1 || $emailverifymode==2) {
     $activation=code_generate();	
     $db->query("UPDATE bb".$n."_users SET activation='$activation' WHERE userid = '$wbbuserdata[userid]'");
     
     if($emailverifymode==1) {
      eval ("\$subject = \"".$tpl->get("ms_emailchange1")."\";");
      eval ("\$content = \"".$tpl->get("mt_emailchange1")."\";");
      mailer($r_email,$subject,$content);	
     	
      eval("redirect(\"".$tpl->get("redirect_emailchange1")."\",\"index.php?sid=$session[hash]\",20);");	
     }
     else eval("redirect(\"".$tpl->get("redirect_emailchange2")."\",\"index.php?sid=$session[hash]\",20);");
    }
    exit();
   }
   else {
    header("Location: usercp.php?action=profile_edit&sid=$session[hash]");
    exit();	
   }
  }
 }
 else {
  $r_email = $wbbuserdata['email'];
  $r_homepage = $wbbuserdata['homepage'];
  $r_icq = $wbbuserdata['icq'];
  $r_aim = $wbbuserdata['aim'];
  $r_yim = $wbbuserdata['yim'];
  $r_msn = $wbbuserdata['msn'];
  $birthday=explode("-",$wbbuserdata['birthday']);
  $r_day = $birthday[2]; 
  $r_month = $birthday[1]; 
  $r_year = ifelse($birthday[0],$birthday[0],""); 
  $r_gender = $wbbuserdata['gender'];
  $r_usertext = $wbbuserdata['usertext'];
  $userfields = $db->query_first("SELECT * FROM bb".$n."_userfields WHERE userid='$wbbuserdata[userid]'");
 }
 
 for($i=1;$i<=31;$i++) $day_options.=makeoption($i,$i,$r_day);
 for($i=1;$i<=12;$i++) $month_options.=makeoption($i,getmonth($i),$r_month);

 if(isset($r_gender)) $gender[$r_gender]=" selected";
 
 $z=0;
 $y=1;
 $result=$db->query("SELECT * FROM bb".$n."_profilefields ORDER BY fieldorder ASC");
 while($row=$db->fetch_array($result)) {
  if(isset($_POST['send'])) $field_value=$field[$row['profilefieldid']];
  else $field_value=$userfields["field".$row['profilefieldid']];
  
  if($row['required']==1) {
   $tdbgcolor=getone($y,"{tablecolora}","{tablecolorb}");
   $tdid=getone($y,"tablea","tableb");
  
   eval ("\$profilefields_required .= \"".$tpl->get("register_userfield")."\";");
   $y++;
  }
  else {
   $tdbgcolor=getone($z,"{tablecolora}","{tablecolorb}");
   $tdid=getone($z,"tablea","tableb");
  
   eval ("\$profilefields .= \"".$tpl->get("register_userfield")."\";");
   $z++;
  }
 }
 
 if(!$r_icq) $r_icq="";
 if($r_year=="0000") $r_year="";
  
 eval("\$tpl->output(\"".$tpl->get("usercp_profile_edit")."\");");
}

if($action=="signature_edit") {
 require("./acp/lib/class_parse.php");
 
 if(isset($_POST['send'])) {
  $message=trim($_POST['message']);
   
  if(!$_POST['preview']) {
   $error="";
   if(strlen($message)>$wbbuserdata['maxsiglength']) eval ("\$error .= \"".$tpl->get("register_error5")."\";");
   if($wbbuserdata['maxsigimage']!=-1 && substr_count(strtolower($message),"[img]")>$wbbuserdata['maxsigimage']) eval ("\$error .= \"".$tpl->get("register_error6")."\";");
   if($error) eval ("\$usercp_error = \"".$tpl->get("usercp_error")."\";");
   else {
    $db->query("UPDATE bb".$n."_users SET signature='".addslashes($message)."' WHERE userid='$wbbuserdata[userid]'");
    header("Location: usercp.php?action=signature_edit&sid=$session[hash]");
    exit();
   }
  }
  else {
   $parse = new parse($docensor,75,$allowsigsmilies,$allowsigbbcode,$wbbuserdata['showimages'],"",$usecode);
   $preview_signature=$parse->doparse($message,$allowsigsmilies,$allowsightml,$allowsigbbcode,$maxsigimage);
   eval ("\$usercp_signature_edit_preview = \"".$tpl->get("usercp_signature_edit_preview")."\";");	
  }
 }
 else $message=$wbbuserdata['signature'];
 
 if($wbbuserdata['signature']) {
  if(!$parse) $parse = new parse($docensor,75,$allowsigsmilies,$allowsigbbcode,$wbbuserdata['showimages'],"",$usecode);
  $old_signature=$parse->doparse($wbbuserdata['signature'],$allowsigsmilies,$allowsightml,$allowsigbbcode,$maxsigimage);
  eval ("\$usercp_signature_edit_old = \"".$tpl->get("usercp_signature_edit_old")."\";");
 }
 
 if($allowsigbbcode==1) $bbcode_buttons = getcodebuttons();
 if($allowsigsmilies==1) $bbcode_smilies = getclickysmilies($smilie_table_cols,$smilie_table_rows);

 eval ("\$note .= \"".$tpl->get("note_html_".ifelse($allowsightml==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_bbcode_".ifelse($allowsigbbcode==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_smilies_".ifelse($allowsigsmilies==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_images_".ifelse($maxsigimage==0,"not_")."allow")."\";");
 
 if(isset($message)) $message=parse::convertHTML($message);

 eval("\$tpl->output(\"".$tpl->get("usercp_signature_edit")."\");");
}

if($action=="options_change") {
 if(isset($_POST['send'])) {
  
  if(isset($_POST['r_invisible'])) $r_invisible = trim($_POST['r_invisible']);
  if(isset($_POST['r_nosessionhash'])) $r_nosessionhash = trim($_POST['r_nosessionhash']);
  if(isset($_POST['r_usecookies'])) $r_usecookies = trim($_POST['r_usecookies']);
  if(isset($_POST['r_admincanemail'])) $r_admincanemail = trim($_POST['r_admincanemail']);
  if(isset($_POST['r_showemail'])) $r_showemail = trim($_POST['r_showemail']);
  if(isset($_POST['r_usercanemail'])) $r_usercanemail = trim($_POST['r_usercanemail']);
  if(isset($_POST['r_emailnotify'])) $r_emailnotify = trim($_POST['r_emailnotify']);
  if(isset($_POST['r_receivepm'])) $r_receivepm = trim($_POST['r_receivepm']);
  if(isset($_POST['r_emailonpm'])) $r_emailonpm = trim($_POST['r_emailonpm']);
  if(isset($_POST['r_pmpopup'])) $r_pmpopup = trim($_POST['r_pmpopup']);
  if(isset($_POST['r_showsignatures'])) $r_showsignatures = trim($_POST['r_showsignatures']);
  if(isset($_POST['r_showavatars'])) $r_showavatars = trim($_POST['r_showavatars']);
  if(isset($_POST['r_showimages'])) $r_showimages = trim($_POST['r_showimages']);
  if(isset($_POST['r_daysprune'])) $r_daysprune = trim($_POST['r_daysprune']);
  if(isset($_POST['r_umaxposts'])) $r_umaxposts = trim($_POST['r_umaxposts']);
  if(isset($_POST['r_threadview'])) $r_threadview = trim($_POST['r_threadview']);
  if(isset($_POST['r_dateformat'])) $r_dateformat = trim($_POST['r_dateformat']);
  if(isset($_POST['r_timeformat'])) $r_timeformat = trim($_POST['r_timeformat']);
  if(isset($_POST['r_startweek'])) $r_startweek = trim($_POST['r_startweek']);
  if(isset($_POST['r_timezoneoffset'])) $r_timezoneoffset = trim($_POST['r_timezoneoffset']);
  if(isset($_POST['r_styleid'])) $r_styleid = trim($_POST['r_styleid']);
  
  if(!$r_dateformat) $r_dateformat=$dateformat;
  if(!$r_timeformat) $r_timeformat=$timeformat;
   
  
  $db->query("UPDATE bb".$n."_users SET showemail='".intval($r_showemail)."', admincanemail='".intval($r_admincanemail)."', usercanemail='".intval($r_usercanemail)."', invisible='".intval($r_invisible)."', usecookies='".intval($r_usecookies)."', styleid='".intval($r_styleid)."', daysprune='".intval($r_daysprune)."', timezoneoffset='".addslashes(htmlspecialchars($r_timezoneoffset))."', startweek='".intval($r_startweek)."', dateformat='".addslashes(htmlspecialchars($r_dateformat))."', timeformat='".addslashes(htmlspecialchars($r_timeformat))."', emailnotify='".intval($r_emailnotify)."', receivepm='".intval($r_receivepm)."', emailonpm='".intval($r_emailonpm)."', pmpopup='".intval($r_pmpopup)."', umaxposts='".intval($r_umaxposts)."', showsignatures='".intval($r_showsignatures)."', showavatars='".intval($r_showavatars)."', showimages='".intval($r_showimages)."', nosessionhash='".intval($r_nosessionhash)."', threadview='".intval($r_threadview)."' WHERE userid = '$wbbuserdata[userid]'");
  if($r_styleid!=$session['styleid']) $db->unbuffered_query("UPDATE bb".$n."_sessions SET styleid='".intval($r_styleid)."' WHERE hash='$sid'",1);
  header("Location: usercp.php?action=options_change&sid=$session[hash]");
  exit();	
 }
 else {
  $r_invisible = $wbbuserdata['invisible'];
  $r_nosessionhash = $wbbuserdata['nosessionhash'];
  $r_usecookies = $wbbuserdata['usecookies'];
  $r_admincanemail = $wbbuserdata['admincanemail'];
  $r_showemail = $wbbuserdata['showemail'];
  $r_usercanemail = $wbbuserdata['usercanemail'];
  $r_emailnotify = $wbbuserdata['emailnotify'];
  $r_receivepm = $wbbuserdata['receivepm'];
  $r_emailonpm = $wbbuserdata['emailonpm'];
  $r_pmpopup = $wbbuserdata['pmpopup'];
  $r_showsignatures = $wbbuserdata['showsignatures'];
  $r_showavatars = $wbbuserdata['showavatars'];
  $r_showimages = $wbbuserdata['showimages'];
  $r_daysprune = $wbbuserdata['daysprune'];
  $r_umaxposts = $wbbuserdata['umaxposts'];
  $r_dateformat = $wbbuserdata['dateformat'];
  $r_timeformat = $wbbuserdata['timeformat'];
  $r_startweek = $wbbuserdata['startweek'];
  $r_timezoneoffset = $wbbuserdata['timezoneoffset'];
  $r_styleid = $wbbuserdata['styleid'];
  $r_threadview = $wbbuserdata['threadview'];
 }

 for($i=0;$i<7;$i++) $startweek_options.=makeoption($i,getday($i),$r_startweek);
 
 if(isset($r_invisible)) $invisible[$r_invisible]=" selected";
 if(isset($r_nosessionhash)) $nosessionhash[$r_nosessionhash]=" selected";
 if(isset($r_usecookies)) $usecookies[$r_usecookies]=" selected";
 if(isset($r_admincanemail)) $admincanemail[$r_admincanemail]=" selected";
 if(isset($r_showemail)) $showemail[$r_showemail]=" selected";
 if(isset($r_usercanemail)) $usercanemail[$r_usercanemail]=" selected";
 if(isset($r_emailnotify)) $emailnotify[$r_emailnotify]=" selected";
 if(isset($r_receivepm)) $receivepm[$r_receivepm]=" selected";
 if(isset($r_emailonpm)) $emailonpm[$r_emailonpm]=" selected";
 if(isset($r_pmpopup)) $spmpopup[$r_pmpopup]=" selected";
 if(isset($r_showsignatures)) $showsignatures[$r_showsignatures]=" selected";
 if(isset($r_showavatars)) $showavatars[$r_showavatars]=" selected";
 if(isset($r_showimages)) $showimages[$r_showimages]=" selected";
 if(isset($r_daysprune)) $sdaysprune[$r_daysprune]=" selected";
 if(isset($r_umaxposts)) $sumaxposts[$r_umaxposts]=" selected";
 if(isset($r_threadview)) $sthreadview[$r_threadview]=" selected";
 
 $timezones = explode("\n", $tpl->get("timezones"));
 for($i=0;$i<count($timezones);$i++) {
  $parts = explode("|", trim($timezones[$i]));  
  $timezone_options .= makeoption($parts[0],"(GMT".ifelse($parts[1]," ".$parts[1],"").") $parts[2]",$r_timezoneoffset);
 }
 
 $result = $db->query("SELECT styleid, stylename FROM bb".$n."_styles WHERE default_style = 0 ORDER BY stylename ASC");
 while($row=$db->fetch_array($result)) $style_options.=makeoption($row['styleid'],$row['stylename'],$r_styleid);
 
 eval("\$tpl->output(\"".$tpl->get("usercp_options_change")."\");");
}

if($action=="password_change") {
 if($_POST['send']=="send") {
  $old_password=$_POST['old_password'];
  $new_password=$_POST['new_password'];
  $confirm_new_password=$_POST['confirm_new_password'];
    
  if(!$old_password || !$new_password || !$confirm_new_password) eval("error(\"".$tpl->get("error_emptyfields")."\");");
  elseif($new_password!=$confirm_new_password) eval("error(\"".$tpl->get("error_pwnotidentical")."\");");
  elseif(md5($old_password)!=$wbbuserdata[password]) eval("error(\"".$tpl->get("error_falsepassword")."\");");
  else {
   $new_password=md5($new_password);
   $db->query("UPDATE bb".$n."_users SET password='".$new_password."' WHERE userid='$wbbuserdata[userid]'");
   if($wbbuserdata[usecookies]==1) bbcookie("wbb_userpassword","$new_password",time()+3600*24*365);
   header("Location: usercp.php?sid=$session[hash]");
   exit();
  }
 }

 eval("\$tpl->output(\"".$tpl->get("usercp_password_change")."\");");
}

if($action=="buddy_list") {
 if($_POST['send']=="send") {
  list($userid)=$db->query_first("SELECT userid FROM bb".$n."_users WHERE username='".addslashes(htmlspecialchars(trim($_POST['addtolist'])))."'");
  if(!$userid) eval("error(\"".$tpl->get("error_usernotexist")."\");");
  elseif($userid==$wbbuserdata[userid]) eval("error(\"".$tpl->get("error_cantaddyourself")."\");");
  else {
   $buddylist=add2list($wbbuserdata['buddylist'],$userid);
   if($buddylist!=-1) $db->query("UPDATE bb".$n."_users SET buddylist='$buddylist' WHERE userid='$wbbuserdata[userid]'");
   header("Location: usercp.php?action=buddy_list&sid=$session[hash]");
   exit();
  }
 }
 
 $listbit="";
 if($wbbuserdata['buddylist']!="") {
  $result = $db->query("SELECT u.userid, u.username, IF(s.lastactivity>=".(time()-$useronlinetimeout*60).ifelse($wbbuserdata['canuseacp']==1,""," AND u.invisible=0").",1,0) AS online FROM bb".$n."_users u 
  LEFT JOIN bb".$n."_sessions s USING (userid)
  WHERE u.userid IN ('".str_replace(" ","','",$wbbuserdata[buddylist])."') ORDER BY online DESC, u.username ASC");
  while($row=$db->fetch_array($result)) {
   $posts['username']=$row['username'];
   if($row['online']) eval ("\$user_online = \"".$tpl->get("thread_user_online")."\";");
   else eval ("\$user_online = \"".$tpl->get("thread_user_offline")."\";");
   eval ("\$listbit .= \"".$tpl->get("usercp_buddy_listbit")."\";");
  }
 }

 eval("\$tpl->output(\"".$tpl->get("usercp_buddy_list")."\");");
}

if($action=="ignore_list") {
 if($_POST['send']=="send") {
  $result=$db->query_first("SELECT userid, ismod+issupermod+canuseacp AS status FROM bb".$n."_users LEFT JOIN bb".$n."_groups USING (groupid) WHERE username='".addslashes(htmlspecialchars(trim($_POST['addtolist'])))."'");
  if(!$result[userid]) eval("error(\"".$tpl->get("error_usernotexist")."\");");
  elseif($result[userid]==$wbbuserdata[userid]) eval("error(\"".$tpl->get("error_cantaddyourself")."\");");
  elseif($result[status]!=0) eval("error(\"".$tpl->get("error_cantaddmods")."\");");
  else {
   $ignorelist=add2list($wbbuserdata['ignorelist'],$result['userid']);
   if($ignorelist!=-1) $db->query("UPDATE bb".$n."_users SET ignorelist='$ignorelist' WHERE userid='$wbbuserdata[userid]'");
   header("Location: usercp.php?action=ignore_list&sid=$session[hash]");
   exit();
  }
 }
 
 $listbit="";
 if($wbbuserdata['ignorelist']!="") {
  $result = $db->query("SELECT userid, username FROM bb".$n."_users WHERE userid IN ('".str_replace(" ","','",$wbbuserdata['ignorelist'])."') ORDER BY username ASC");
  while($row=$db->fetch_array($result)) eval ("\$listbit .= \"".$tpl->get("usercp_ignore_listbit")."\";");
 }

 eval("\$tpl->output(\"".$tpl->get("usercp_ignore_list")."\");");
}

if($action=="buddy") {
 if($_GET['remove']) {
  list($userid)=$db->query_first("SELECT userid FROM bb".$n."_users WHERE userid='".intval($_GET['remove'])."'");
  if(!$userid) eval("error(\"".$tpl->get("error_usernotexist")."\");");
  else {
   $buddylist=removeFromlist($wbbuserdata['buddylist'],$userid);
   if($buddylist!=-1) $db->query("UPDATE bb".$n."_users SET buddylist='$buddylist' WHERE userid='$wbbuserdata[userid]'");
   header("Location: usercp.php?action=buddy_list&sid=$session[hash]");
   exit();
  }
 }
 if($_GET['add']) {
  list($userid)=$db->query_first("SELECT userid FROM bb".$n."_users WHERE userid='".intval($_GET['add'])."'");
  if(!$userid) eval("error(\"".$tpl->get("error_usernotexist")."\");");
  elseif($userid==$wbbuserdata['userid']) eval("error(\"".$tpl->get("error_cantaddyourself")."\");");
  else {
   $buddylist=add2list($wbbuserdata['buddylist'],$userid);
   if($buddylist!=-1) $db->query("UPDATE bb".$n."_users SET buddylist='$buddylist' WHERE userid='$wbbuserdata[userid]'");
   header("Location: usercp.php?action=buddy_list&sid=$session[hash]");
   exit();
  }
 }
}

if($action=="ignore") {
 if($_GET['remove']) {
  list($userid)=$db->query_first("SELECT userid FROM bb".$n."_users WHERE userid='".intval($_GET['remove'])."'");
  if(!$userid) eval("error(\"".$tpl->get("error_usernotexist")."\");");
  else {
   $ignorelist=removeFromlist($wbbuserdata['ignorelist'],$userid);
   if($ignorelist!=-1) $db->query("UPDATE bb".$n."_users SET ignorelist='$ignorelist' WHERE userid='$wbbuserdata[userid]'");
   header("Location: usercp.php?action=ignore_list&sid=$session[hash]");
   exit();
  }
 }
 if($_GET['add']) {
  $result=$db->query_first("SELECT userid, ismod+issupermod+canuseacp AS status FROM bb".$n."_users LEFT JOIN bb".$n."_groups USING (groupid) WHERE userid='".intval($_GET['add'])."'");
  if(!$result['userid']) eval("error(\"".$tpl->get("error_usernotexist")."\");");
  elseif($result['userid']==$wbbuserdata['userid']) eval("error(\"".$tpl->get("error_cantaddyourself")."\");");
  elseif($result['status']!=0) eval("error(\"".$tpl->get("error_cantaddmods")."\");");
  else {
   $ignorelist=add2list($wbbuserdata['ignorelist'],$result['userid']);
   if($ignorelist!=-1) $db->query("UPDATE bb".$n."_users SET ignorelist='$ignorelist' WHERE userid='$wbbuserdata[userid]'");
   header("Location: usercp.php?action=ignore_list&sid=$session[hash]");
   exit();
  }
 }
}

if($action=="avatars") {
 if(isset($_POST['send'])) {
  if($_POST['avatarid']!="useown") {
   $oldavatar = $db->query_first("SELECT avatarid, avatarextension FROM bb".$n."_avatars WHERE userid = '$wbbuserdata[userid]'");
   if($oldavatar['avatarid']) {
    @unlink("./images/avatars/avatar-".$oldavatar['avatarid'].".".$oldavatar['avatarextension']);
    $db->query("DELETE FROM bb".$n."_avatars WHERE avatarid = '$oldavatar[avatarid]'");
   }
   $db->query("UPDATE bb".$n."_users SET avatarid = '".intval($_POST['avatarid'])."' WHERE userid = '$wbbuserdata[userid]'");
   header("Location: usercp.php?action=avatars&sid=$session[hash]&page=$_POST[page]");
   exit();
  }
  else {
   $uploaderror=0;
   if($_FILES['avatar_file']['tmp_name'] && $_FILES['avatar_file']['tmp_name']!="none") {
    $badavatar=0;
    $avatar_file_extension = strtolower(substr(strrchr($_FILES['avatar_file']['name'],"."),1));
    $avatar_file_name2 = substr($_FILES['avatar_file']['name'],0,(intval(strlen($avatar_file_extension))+1)*-1);
    $allowedavatarextensions=explode("\n",$wbbuserdata['allowedavatarextensions']);
        
    if(in_array($avatar_file_extension,$allowedavatarextensions) && $_FILES['avatar_file']['size']<=$wbbuserdata['maxavatarsize']) { /*  &&  */
     $db->query("INSERT INTO bb".$n."_avatars (avatarname,avatarextension,userid) VALUES ('".addslashes(htmlspecialchars($avatar_file_name2))."','".addslashes(htmlspecialchars($avatar_file_extension))."','$wbbuserdata[userid]')");
     $avatarid=$db->insert_id("bb".$n."_avatars","avatarid");
   	
     if(move_uploaded_file($_FILES['avatar_file']['tmp_name'],"./images/avatars/avatar-".$avatarid.".".$avatar_file_extension)) {
      @chmod ("./images/avatars/avatar-".$avatarid.".".$avatar_file_extension,0777);
      
      $imgsize=@getimagesize("./images/avatars/avatar-".$avatarid.".".$avatar_file_extension);
      $width=$imgsize[0];
      $height=$imgsize[1];
      if($avatar_file_extension=="swf") {
       if($width>$wbbuserdata['maxavatarwidth']) $width=$wbbuserdata['maxavatarwidth'];
       if($height>$wbbuserdata['maxavatarheight']) $height=$wbbuserdata['maxavatarheight'];
      }
      
      if($width>$wbbuserdata['maxavatarwidth'] || $height>$wbbuserdata['maxavatarheight']) $badavatar=2;
      
      if($badavatar==0) {
       $oldavatar = $db->query_first("SELECT avatarid, avatarextension FROM bb".$n."_avatars WHERE userid = '$wbbuserdata[userid]' AND avatarid='$wbbuserdata[avatarid]'");
       if($oldavatar['avatarid']) {
        @unlink("./images/avatars/avatar-".$oldavatar['avatarid'].".".$oldavatar['avatarextension']);
        $db->unbuffered_query("DELETE FROM bb".$n."_avatars WHERE avatarid = '$oldavatar[avatarid]'",1);
       }
       $db->unbuffered_query("UPDATE bb".$n."_users SET avatarid='$avatarid' WHERE userid='$wbbuserdata[userid]'",1);
       $db->unbuffered_query("UPDATE bb".$n."_avatars SET width='$width', height='$height' WHERE avatarid='$avatarid'",1);
       header("Location: usercp.php?action=avatars&sid=$session[hash]&page=$page");
       exit();
      }
     }
     else $badavatar=1;
     if($badavatar!=0) {
      if($badavatar==2) @unlink("./images/avatars/avatar-".$avatarid.".".$avatar_file_extension);
      $db->query("DELETE FROM bb".$n."_avatars WHERE avatarid='$avatarid'");
      $uploaderror=1;
     }
    }
    else $uploaderror=1;
    if($uploaderror==1) eval("error(\"".$tpl->get("error_falseavatar")."\");");	       			
   }
   elseif(!$havatarid) eval("error(\"".$tpl->get("error_falseavatar")."\");");
  }
 }
        	
 if($wbbuserdata['avatarid']==0 || ($wbbuserdata['canuseavatar']==0 && $wbbuserdata['canuploadavatar']==0)) $noavatar_checked = " CHECKED";
 if($wbbuserdata['canuseavatar']==1) {
  list($avatarcount) = $db->query_first("SELECT COUNT(*) FROM bb".$n."_avatars WHERE (groupid = '$wbbuserdata[groupid]' OR groupid = 0) AND needposts <= '$wbbuserdata[userposts]' AND userid = 0 ORDER BY needposts DESC");
  if($avatarcount) {
   if(isset($_GET['page'])) {
    $page=intval($_GET['page']);
    if($page==0) $page=1;
   }
   else $page=1;
   $pages=ceil($avatarcount/$avatarsperpage);
   $result = $db->query("SELECT avatarid, avatarextension, width, height FROM bb".$n."_avatars WHERE (groupid = '$wbbuserdata[groupid]' OR groupid = 0) AND needposts <= '$wbbuserdata[userposts]' AND userid = 0 ORDER BY needposts DESC",$avatarsperpage,$avatarsperpage*($page-1));
   while($row = $db->fetch_array($result)) {
    $avatarname="images/avatars/avatar-$row[avatarid].$row[avatarextension]";
    $avatarwidth=$row['width'];
    $avatarheight=$row['height'];
    if($row['avatarextension']=="swf") eval ("\$avatarchoice = \"".$tpl->get("avatar_flash")."\";");
    else eval ("\$avatarchoice = \"".$tpl->get("avatar_image")."\";");
    
    if($row['avatarid']==$wbbuserdata['avatarid']) $checked=" checked";
    else $checked="";
    eval ("\$avatarArray[] = \"".$tpl->get("usercp_avatarbit")."\";");
   }
        			
   $tableRows = ceil(count($avatarArray)/5);
   $count = 0;
   for ($i=0; $i<$tableRows; $i++) {
    unset($avatarbit_td);
    for ($j=0; $j<5; $j++) {
     eval ("\$avatarbit_td .= \"".$tpl->get("usercp_avatarbit_td")."\";");
     $count++;
    }
    eval ("\$avatarbit_tr .= \"".$tpl->get("usercp_avatarbit_tr")."\";");
   }
                   
   $countfrom = 1+$avatarsperpage*($page-1);
   $countto = $avatarsperpage*$page;
   if($countto > $avatarcount) $countto = $avatarcount;
                        	
   if($pages>1) $pagelink=makepagelink("usercp.php?action=avatars&sid=$session[hash]",$page,$pages,$showpagelinks-1);
                       	
   eval ("\$avatar_choice = \"".$tpl->get("usercp_avatar_choice")."\";");
  }
 }
 if($wbbuserdata['canuploadavatar']==1) {
  $ownavatar = $db->query_first("SELECT avatarid, avatarextension, width, height FROM bb".$n."_avatars WHERE userid = '$wbbuserdata[userid]'");
  if($ownavatar['avatarid']) {
   $avatarname="images/avatars/avatar-$ownavatar[avatarid].$ownavatar[avatarextension]";
   $avatarwidth=$ownavatar['width'];
   $avatarheight=$ownavatar['height'];   
   if($ownavatar['avatarextension']=="swf") eval ("\$ownavatar = \"".$tpl->get("avatar_flash")."\";"); 
   else eval ("\$ownavatar = \"".$tpl->get("avatar_image")."\";");
   $havatar = "<input type=\"hidden\" name=\"havatarid\" value=\"$ownavatar[avatarid]\">";
   $ownavatar_checked = " CHECKED";
  }
  eval ("\$avatar_choice .= \"".$tpl->get("usercp_avatar_useown")."\";");
 }
 eval("\$tpl->output(\"".$tpl->get("usercp_avatars")."\");");
}

if($_REQUEST['action']=="addsubscription") {
 if(isset($threadid)) {
  $db->query("INSERT IGNORE INTO bb".$n."_subscribethreads (userid,threadid,emailnotify) VALUES ('$wbbuserdata[userid]','$threadid','1')");
  header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
 }
 elseif(isset($boardid)) {
  $db->query("INSERT IGNORE INTO bb".$n."_subscribeboards (userid,boardid,emailnotify) VALUES ('$wbbuserdata[userid]','$boardid','1')");
  header("Location: board.php?boardid=$boardid&sid=$session[hash]");
 }
 exit();	
}

if($_REQUEST['action']=="removesubscription") {
 if(isset($threadid)) $db->query("DELETE FROM bb".$n."_subscribethreads WHERE userid='$wbbuserdata[userid]' AND threadid='$threadid'");
 elseif(isset($boardid)) $db->query("DELETE FROM bb".$n."_subscribeboards WHERE userid='$wbbuserdata[userid]' AND boardid='$boardid'");
 
 header("Location: usercp.php?action=favorites&sid=$session[hash]");
 exit();	
}

if($_REQUEST['action']=="favorites") {
 require("./acp/lib/class_parse.php");
 /** update emailcount **/
 $db->query("UPDATE bb".$n."_subscribethreads SET countemails=0 WHERE userid='$wbbuserdata[userid]'");
 $db->query("UPDATE bb".$n."_subscribeboards SET countemails=0 WHERE userid='$wbbuserdata[userid]'");
 $boardvisit=decode_cookie($_COOKIE['boardvisit']);
 $threadvisit=decode_cookie($_COOKIE['threadvisit']);
  
 /** boards **/
 $result = $db->query("SELECT boardid, threadid, lastposttime FROM bb".$n."_threads WHERE visible = 1 AND lastposttime > '$wbbuserdata[lastvisit]'");
 while($row=$db->fetch_array($result)) $visitcache[$row['boardid']][$row['threadid']]=$row['lastposttime'];
  
 $result = $db->query("
 SELECT
 s.emailnotify, b.*".ifelse($showlastposttitle==1,", t.topic, i.*")."
 FROM bb".$n."_subscribeboards s
 LEFT JOIN bb".$n."_boards b USING(boardid)
 ".ifelse($showlastposttitle==1,"LEFT JOIN bb".$n."_threads t ON (t.threadid=b.lastthreadid)
 LEFT JOIN bb".$n."_icons i USING (iconid)")."
 WHERE s.userid='$wbbuserdata[userid]' AND b.isboard=1
 ORDER by b.title ASC");	
 
 $boardbit="";	
 while($boards=$db->fetch_array($result)) {
  if($boards['description']) eval ("\$boards['description'] = \"".$tpl->get("index_boarddescription")."\";");	
  
  if($wbbuserdata['lastvisit'] > $boards['lastposttime'] || $boardvisit[$boards['boardid']] > $boards['lastposttime']) $onoff="off";
  else {
   $onoff="off";
   $tempids = explode(",","$boards[boardid],$boards[childlist]");
   for($j=0;$j<count($tempids);$j++) {
    if($tempids[$j]==0) continue;
    if(is_array($visitcache[$tempids[$j]]) && count($visitcache[$tempids[$j]])) {
     reset($visitcache[$tempids[$j]]);
     while(list($threadid,$lastposttime)=each($visitcache[$tempids[$j]])) {
      if($threadvisit[$threadid]<$lastposttime && $boardvisit[$tempids[$j]]<$lastposttime) {
       $onoff="on";
       break 2;
      } // end if
     } // end while
    } // end if
   } // end for	
  } // end else
    
  if($boards['threadcount']) {
   $lastpostdate=formatdate($wbbuserdata['dateformat'],$boards['lastposttime'],1); 
   $lastposttime=formatdate($wbbuserdata['timeformat'],$boards['lastposttime']); 
   
   if($boards['lastposterid']) eval ("\$lastposter = \"".$tpl->get("index_lastposter")."\";");
   else eval ("\$lastposter = \"".$tpl->get("index_lastposter_guest")."\";");
   if($showlastposttitle==1) {
    if(strlen($boards['topic'])>30) $topic=cutTopic($boards['topic']);
    else $topic=$boards['topic'];
    eval ("\$lastposttitle = \"".$tpl->get("index_lastpost_title_show")."\";");
     
    if(isset($boards['iconid'])) $ViewPosticon=makeimgtag($boards['iconpath'],$boards['icontitle']);
    else $ViewPosticon=makeimgtag("{imagefolder}/icons/icon14.gif");
    eval ("\$lastpost = \"".$tpl->get("index_lastpost_title")."\";");
   }
   else eval ("\$lastpost = \"".$tpl->get("index_lastpost")."\";");
  }
  else eval ("\$lastpost = \"".$tpl->get("index_nolastpost")."\";");
  eval ("\$boardbit .= \"".$tpl->get("usercp_boardbit")."\";");	
 }
 if($boardbit) eval ("\$boardheader = \"".$tpl->get("usercp_boardheader")."\";");
 else eval ("\$boardheader = \"".$tpl->get("usercp_noboards")."\";");	
 
 
 /** threads **/
 if($wbbuserdata['umaxposts']) $postsperpage=$wbbuserdata['umaxposts'];
 elseif($board['postsperpage']) $postsperpage=$board['postsperpage'];
 else $postsperpage=$default_postsperpage;

 if($board['hotthread_reply']==0) $board['hotthread_reply']=$default_hotthread_reply;
 if($board['hotthread_view']==0) $board['hotthread_view']=$default_hotthread_view;
 
 if(isset($_GET['daysprune'])) $daysprune = intval($_GET['daysprune']);
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
 $d_select[$daysprune]="selected";
 if($daysprune!=1000) {
  if($daysprune==1500) $datecute = " AND lastposttime >= '".$wbbuserdata['lastvisit']."'";	
  else {
   $tempdate=time()-($daysprune*86400);
   $datecute = " AND t.lastposttime >= '".$tempdate."'";
  }
 }
 else $datecute="";
 
 if ($showown==1) {
  $ownuserid = "DISTINCT p.userid,";
  $ownjoin = "LEFT JOIN bb".$n."_posts p ON (t.threadid = p.threadid AND p.userid = '$wbbuserdata[userid]')";
 }
 else {
  $ownuserid="";
  $ownjoin="";
 }
 
 $threadids="";
 $result = $db->query("SELECT t.threadid FROM bb".$n."_subscribethreads s LEFT JOIN bb".$n."_threads t USING(threadid) WHERE s.userid='$wbbuserdata[userid]' AND t.visible = 1 $datecute ORDER BY t.lastposttime DESC");
 while($row=$db->fetch_array($result)) $threadids .= ",".$row['threadid'];

 $result = $db->query("SELECT
  $ownuserid
  t.*,
  IF(t.voted>0,t.votepoints/t.voted,0) AS vote,
  i.*
  FROM bb".$n."_threads t
  LEFT JOIN bb".$n."_icons i USING (iconid)
  $ownjoin
  WHERE t.threadid IN (0$threadids)
  ORDER BY t.lastposttime DESC");
 
 $threadbit="";
 while($threads=$db->fetch_array($result)) {
  $firstnew="";
  $multipages="";
  $attachments="";
  $prefix="";
 
  if(strlen($threads['topic'])>30) $threads['topic']=parse::textwrap($threads['topic'],30);
 
  if($threads['voted']) {
   $avarage=number_format($threads['votepoints']/$threads['voted'],2);
   eval ("\$threadrating = \"".$tpl->get("board_threadbit_rating")."\";");
   $threadrating=str_repeat($threadrating, round($avarage));
  }
  else $threadrating="&nbsp;";
  
  if($threads['pollid']!=0) eval ("\$prefix .= \"".$tpl->get("board_thread_poll")."\";");
 
  if($threads['pollid']!=0) $foldericon="poll";
  else $foldericon=ifelse($threads['userid'],"dot").ifelse($wbbuserdata['lastvisit']<$threads['lastposttime'] && $threadvisit[$threads['threadid']]<$threads['lastposttime'],"new").ifelse($threads['replycount']>=$board['hotthread_reply'] || $threads['views']>=$board['hotthread_view'],"hot").ifelse($threads['closed']!=0,"lock")."folder";
  if($wbbuserdata['lastvisit']<$threads['lastposttime'] && $threadvisit[$threads['threadid']]<$threads['lastposttime']) eval ("\$firstnew = \"".$tpl->get("board_threadbit_firstnew")."\";");
  if($threads['iconid']) $threadicon=makeimgtag($threads['iconpath'],$threads['icontitle']);
  else $threadicon="&nbsp;";
  if($threads['starterid']!=0) eval ("\$threads['starter'] = \"".$tpl->get("board_threadbit_starter")."\";");
  if($threads['lastposterid']!=0) eval ("\$threads['lastposter'] = \"".$tpl->get("board_threadbit_lastposter")."\";");
 
  $lastpostdate=formatdate($wbbuserdata['dateformat'],$threads['lastposttime'],1);
  $lastposttime=formatdate($wbbuserdata['timeformat'],$threads['lastposttime']);
 
  if($threads['replycount']+1>$postsperpage && $showmultipages!=0) {
   unset($multipage);
   unset($multipages_lastpage);
   $xpages=ceil(($threads['replycount']+1)/$postsperpage);
   if($xpages>$showmultipages) {
    eval ("\$multipages_lastpage = \"".$tpl->get("board_threadbit_multipages_lastpage")."\";");
    $xpages=$showmultipages;
   }
   for($i=1;$i<=$xpages;$i++) $multipage.=" ".makehreftag("thread.php?threadid=$threads[threadid]&page=$i&sid=$session[hash]",$i);
   eval ("\$multipages = \"".$tpl->get("board_threadbit_multipages")."\";");
  }
 
  if($threads['attachments']) eval ("\$attachments = \"".$tpl->get("board_threadbit_attachments")."\";");
 
  eval ("\$threadbit .= \"".$tpl->get("usercp_threadbit")."\";");
 }
 if($threadbit) eval ("\$threadheader = \"".$tpl->get("usercp_threadheader")."\";");
 else eval ("\$threadheader = \"".$tpl->get("usercp_nothreads")."\";");	
 
 eval("\$tpl->output(\"".$tpl->get("usercp_favorites")."\");");
}
?>