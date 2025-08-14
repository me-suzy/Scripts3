<?php
$filename="register.php";

require("./global.php");

if(isset($_REQUEST['action']) && $_REQUEST['action']=="activation") {
 if(isset($_REQUEST['usrid']) && isset($_REQUEST['a'])) {
  $result=$db->query_first("SELECT userid, activation FROM bb".$n."_users WHERE userid='".intval($_REQUEST['usrid'])."'");	
  if(!$result['userid']) eval("error(\"".$tpl->get("error_usernotexist")."\");");
  if($result['activation']==1) eval("error(\"".$tpl->get("error_accountalreadyactive")."\");");
  if($result['activation']!=intval($_REQUEST['a'])) eval("error(\"".$tpl->get("error_falseactivationcode")."\");");
  $db->query("UPDATE bb".$n."_users SET activation=1 WHERE userid='$result[userid]'");
  eval("redirect(\"".$tpl->get("redirect_accountactive")."\",\"index.php?sid=$session[hash]\",10);");
 }	
 else eval("\$tpl->output(\"".$tpl->get("register_activation")."\");");
 exit();
}

if($wbbuserdata['userid']!=0) access_error();
if($allowregister!=1) eval("error(\"".$tpl->get("error_register_disabled")."\");");
if($showdisclaimer==1 && $_POST['disclaimer']!="viewed") {
 eval("\$tpl->output(\"".$tpl->get("register_disclaimer")."\");");
 exit();
}
else {
 if(isset($_POST['disclaimer'])) $disclaimer = $_POST['disclaimer'];
 $group = $db->query_first("SELECT * FROM bb".$n."_groups WHERE default_group = 2");
 if(isset($_POST['send'])) {
  if(is_array($_POST['field'])) $field = trim_array($_POST['field']);
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
  if(isset($_POST['r_username'])) $r_username = trim($_POST['r_username']);
  if(isset($_POST['r_password'])) $r_password = trim($_POST['r_password']);
  if(isset($_POST['r_confirmpassword'])) $r_confirmpassword = trim($_POST['r_confirmpassword']);
  if(isset($_POST['r_signature'])) $r_signature = trim($_POST['r_signature']);
  
  if(isset($_POST['r_invisible'])) $r_invisible = $_POST['r_invisible'];
  if(isset($_POST['r_nosessionhash'])) $r_nosessionhash = $_POST['r_nosessionhash'];
  if(isset($_POST['r_usecookies'])) $r_usecookies = $_POST['r_usecookies'];
  if(isset($_POST['r_admincanemail'])) $r_admincanemail = $_POST['r_admincanemail'];
  if(isset($_POST['r_showemail'])) $r_showemail = $_POST['r_showemail'];
  if(isset($_POST['r_usercanemail'])) $r_usercanemail = $_POST['r_usercanemail'];
  if(isset($_POST['r_emailnotify'])) $r_emailnotify = $_POST['r_emailnotify'];
  if(isset($_POST['r_receivepm'])) $r_receivepm = $_POST['r_receivepm'];
  if(isset($_POST['r_emailonpm'])) $r_emailonpm = $_POST['r_emailonpm'];
  if(isset($_POST['r_pmpopup'])) $r_pmpopup = $_POST['r_pmpopup'];
  if(isset($_POST['r_showsignatures'])) $r_showsignatures = $_POST['r_showsignatures'];
  if(isset($_POST['r_showavatars'])) $r_showavatars = $_POST['r_showavatars'];
  if(isset($_POST['r_showimages'])) $r_showimages = $_POST['r_showimages'];
  if(isset($_POST['r_daysprune'])) $r_daysprune = $_POST['r_daysprune'];
  if(isset($_POST['r_umaxposts'])) $r_umaxposts = $_POST['r_umaxposts'];
  if(isset($_POST['r_threadview'])) $r_threadview = $_POST['r_threadview'];
  if(isset($_POST['r_dateformat'])) $r_dateformat = trim($_POST['r_dateformat']);
  if(isset($_POST['r_timeformat'])) $r_timeformat = trim($_POST['r_timeformat']);
  if(isset($_POST['r_startweek'])) $r_startweek = $_POST['r_startweek'];
  if(isset($_POST['r_timezoneoffset'])) $r_timezoneoffset = $_POST['r_timezoneoffset'];
  if(isset($_POST['r_styleid'])) $r_styleid = $_POST['r_styleid'];
  
  $r_username=preg_replace("/\s{2,}/"," ",$r_username);
  
  $error="";
  $userfield_error=0;
  $fieldvalues="";
  
  $result = $db->query("SELECT profilefieldid, required FROM bb".$n."_profilefields ORDER BY profilefieldid ASC");
  while($row=$db->fetch_array($result)) {
   if($row['required']==1 && !$field[$row['profilefieldid']]) {
    $userfield_error=1;
    break;
   }
   $fieldvalues.=",'".addslashes(htmlspecialchars($field[$row['profilefieldid']]))."'";
  }
    
  if($userfield_error==1 || !$r_username || !$r_email || ($emailverifymode!=3 && (!$r_password || !$r_confirmpassword))) eval ("\$error .= \"".$tpl->get("register_error1")."\";");
  if($emailverifymode!=3 && $r_password!=$r_confirmpassword) eval ("\$error .= \"".$tpl->get("register_error2")."\";"); 
  if(!verify_username($r_username)) eval ("\$error .= \"".$tpl->get("register_error3")."\";"); 
  if(!verify_email($r_email)) eval ("\$error .= \"".$tpl->get("register_error4")."\";");
  if(strlen($r_signature)>$group['maxsiglength']) eval ("\$error .= \"".$tpl->get("register_error5")."\";");
  if($group['maxsigimage']!=-1 && substr_count(strtolower($r_signature),"[img]")>$group['maxsigimage']) eval ("\$error .= \"".$tpl->get("register_error6")."\";");
  if(strlen($r_usertext)>$group['maxusertextlength']) eval ("\$error .= \"".$tpl->get("register_error7")."\";");
  if($error) eval ("\$register_error .= \"".$tpl->get("register_error")."\";");
  else {
   if($emailverifymode==3) $r_password=password_generate();
   if($emailverifymode==1 || $emailverifymode==2) $activation=code_generate();
   else $activation=1;
   
   if($r_homepage && !preg_match("/[a-zA-Z]:\/\//si", $r_homepage)) $r_homepage = "http://".$r_homepage;  
   if($r_day && $r_month && $r_year) $birthday=ifelse(strlen($r_year)==4,$r_year,ifelse(strlen($r_year)==2,"19$r_year","0000"))."-".ifelse($r_month<10,"0$r_month",$r_month)."-".ifelse($r_day<10,"0$r_day",$r_day);
   else $birthday = "0000-00-00";
   
   $groupid = $group['groupid'];
   $rankid = $db->query_first("SELECT rankid FROM bb".$n."_ranks WHERE groupid IN ('0','$groupid') AND needposts='0' AND gender IN ('0','".intval($r_gender)."') ORDER BY gender DESC",1);
   
   if(!$r_dateformat) $r_dateformat=$dateformat;
   if(!$r_timeformat) $r_timeformat=$timeformat;
   
   $db->query("INSERT INTO bb".$n."_users (userid,username,password,email,groupid,rankid,regdate,lastvisit,lastactivity,usertext,signature,icq,aim,yim,msn,homepage,birthday,gender,showemail,admincanemail,usercanemail,invisible,usecookies,styleid,activation,daysprune,timezoneoffset,startweek,dateformat,timeformat,emailnotify,receivepm,emailonpm,pmpopup,umaxposts,showsignatures,showavatars,showimages,nosessionhash,threadview) 
    VALUES (NULL,'".addslashes(htmlspecialchars($r_username))."','".md5($r_password)."','".addslashes(htmlspecialchars($r_email))."','$groupid','$rankid[rankid]','".time()."','".time()."','".time()."','".addslashes(htmlspecialchars($r_usertext))."','".addslashes($r_signature)."','".intval($r_icq)."','".addslashes(htmlspecialchars($r_aim))."','".addslashes(htmlspecialchars($r_yim))."','".addslashes(htmlspecialchars($r_msn))."','".addslashes(htmlspecialchars($r_homepage))."','".addslashes(htmlspecialchars($birthday))."','".intval($r_gender)."','".intval($r_showemail)."','".intval($r_admincanemail)."','".intval($r_usercanemail)."','".intval($r_invisible)."','".intval($r_usecookies)."','".intval($r_styleid)."','".intval($activation)."','".intval($r_daysprune)."','".addslashes($r_timezoneoffset)."','".intval($r_startweek)."','".addslashes(htmlspecialchars($r_dateformat))."','".addslashes(htmlspecialchars($r_timeformat))."','".intval($r_emailnotify)."','".intval($r_receivepm)."','".intval($r_emailonpm)."','".intval($r_pmpopup)."','".intval($r_umaxposts)."','".intval($r_showsignatures)."','".intval($r_showavatars)."','".intval($r_showimages)."','".intval($r_nosessionhash)."','".intval($r_threadview)."')");
   $insertid = $db->insert_id();
   
   $db->query("INSERT INTO bb".$n."_userfields VALUES (".$insertid.$fieldvalues.")");
   
   if($regnotify==1) {
    eval ("\$subject = \"".$tpl->get("ms_regnotify")."\";");
    eval ("\$content = \"".$tpl->get("mt_regnotify")."\";");
    mailer($webmastermail,$subject,$content);	
   }
      
   if($r_nosessionhash==1) $session['hash']="";
   if($emailverifymode==0) {
    if($r_usecookies==1) {
     bbcookie("wbb_userid","$insertid",time()+3600*24*365);
     bbcookie("wbb_userpassword",md5($r_password),time()+3600*24*365);
    }
    $db->query("UPDATE bb".$n."_sessions SET userid = '".$insertid."' WHERE hash = '$sid'"); 
    header("Location: index.php?sid=$session[hash]");
    exit();
   }
   if($emailverifymode==1) {
    eval ("\$subject = \"".$tpl->get("register_mail1_subject")."\";");
    eval ("\$content = \"".$tpl->get("register_mail1_content")."\";");
    mailer($r_email,$subject,$content);
    eval("redirect(\"".$tpl->get("redirect_register1")."\",\"index.php?sid=$session[hash]\",20);");
   }
   if($emailverifymode==2) {
    eval("redirect(\"".$tpl->get("redirect_register2")."\",\"index.php?sid=$session[hash]\",20);");
   }
   if($emailverifymode==3) {
    eval ("\$subject = \"".$tpl->get("register_mail3_subject")."\";");
    eval ("\$content = \"".$tpl->get("register_mail3_content")."\";");
    mailer($r_email,$subject,$content);
    eval("redirect(\"".$tpl->get("redirect_register3")."\",\"index.php?sid=$session[hash]\",20);");
   }
  }
 }
 else {
  $r_invisible=$default_register_invisible;
  $r_nosessionhash=$default_register_nosessionhash;
  $r_usecookies=$default_register_usecookies;
  $r_admincanemail=$default_register_admincanemail;
  $r_showemail=1-$default_register_showemail;
  $r_usercanemail=$default_register_usercanemail;
  $r_emailnotify=$default_register_emailnotify;
  $r_receivepm=$default_register_receivepm;
  $r_emailonpm=$default_register_emailonpm;
  $r_pmpopup=$default_register_pmpopup;
  $r_showsignatures=$default_register_showsignatures;
  $r_showavatars=$default_register_showavatars;
  $r_showimages=$default_register_showimages;
  $r_threadview=$default_register_threadview;
  $r_timezoneoffset=$default_timezoneoffset;
  $r_startweek=$default_startweek;
  $r_dateformat=$dateformat;
  $r_timeformat=$timeformat;
 }
 
 for($i=1;$i<=31;$i++) $day_options.=makeoption($i,$i,$r_day);
 for($i=1;$i<=12;$i++) $month_options.=makeoption($i,getmonth($i),$r_month);

 for($i=0;$i<7;$i++) $startweek_options.=makeoption($i,getday($i),$r_startweek);
 
 if(isset($r_gender)) $gender[$r_gender]=" selected";
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
 $z=1;
 $y=ifelse($emailverifymode!=3,0,1);
 $result=$db->query("SELECT * FROM bb".$n."_profilefields ORDER BY fieldorder ASC");
 while($row=$db->fetch_array($result)) {
  $field_value=$field[$row['profilefieldid']];
  
  if($row[required]==1) {
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
 
 $result = $db->query("SELECT styleid, stylename FROM bb".$n."_styles WHERE default_style = 0 ORDER BY stylename ASC");
 while($row=$db->fetch_array($result)) $style_options.=makeoption($row['styleid'],$row['stylename'],$r_styleid);
 
 if($emailverifymode!=3) eval ("\$register_password .= \"".$tpl->get("register_password")."\";");
 
 eval ("\$note .= \"".$tpl->get("note_html_".ifelse($allowsightml==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_bbcode_".ifelse($allowsigbbcode==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_smilies_".ifelse($allowsigsmilies==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_images_".ifelse($maxsigimage==0,"not_")."allow")."\";");
 
 if(!$r_icq) $r_icq="";
 if($r_year=="0000") $r_year="";
 
 eval("\$tpl->output(\"".$tpl->get("register")."\");"); 
}
?>