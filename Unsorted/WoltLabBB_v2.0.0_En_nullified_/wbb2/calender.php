<?php
$filename="calender.php";

require ("./global.php");

if($wbbuserdata['canviewcalender']==0) access_error();

$startofweek=intval($wbbuserdata['startweek']);
function daynumber($time) {
 global $startofweek;
 $daynumber=intval(date('w', $time))-$startofweek;
 if($daynumber<0) $daynumber=7+$daynumber;
 return $daynumber;
}

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";
	
if($action=="") {

 $today_day = formatdate("j",time());
 $today_month = formatdate("n",time());
 $today_year = formatdate("Y",time());

 if(isset($_GET['month'])) $month=intval($_GET['month']);
 else $month=0;
 if(isset($_GET['year'])) $year=intval($_GET['year']);
 else $year=0;
 if(!$month || $month<1 || $month>12) $month=$today_month;
 if(!$year || $year<1970) $year=$today_year;
 $countdays=1;
 $monthname = getmonth($month);

 if($month==1) {
  $prev_month=12;
  $prev_year=$year-1;
  $prev_monthname=getmonth($prev_month);
 }
 else {
  $prev_month=$month-1;
  $prev_year=$year;
  $prev_monthname=getmonth($prev_month);
 }
 if($month==12) {
  $next_month=1;
  $next_year=$year+1;
  $next_monthname=getmonth($next_month);
 }
 else {
  $next_month=$month+1;
  $next_year=$year;
  $next_monthname=getmonth($next_month);
 }

 $result = $db->query("SELECT userid, username, birthday FROM bb".$n."_users WHERE activation = 1 AND birthday LIKE '%-".ifelse($month<10,"0")."$month-%' ORDER BY username ASC");
 if($listallbirthdays==1) while($row=$db->fetch_array($result)) $birthdaycache[intval(substr($row['birthday'],8))][]=$row;
 else {
  while($row=$db->fetch_array($result)) {
   $tempday=intval(substr($row['birthday'],8));
   if(isset($birthdaycount[$tempday])) $birthdaycount[$tempday]++;
   else $birthdaycount[$tempday]=1;
  }	
 }

 $currentmonth="$year-".ifelse($month<10,"0","")."$month";
 $result = $db->query("SELECT eventid, subject, eventdate, public FROM bb".$n."_events WHERE eventdate LIKE '$currentmonth-%' AND (public=2 OR (public=1 AND groupid = '$wbbuserdata[groupid]') OR (public=0 AND userid = '$wbbuserdata[userid]')) ORDER BY public ASC, subject ASC");
 while($row=$db->fetch_array($result)) $eventcache[intval(substr($row['eventdate'],8))][]=$row;
 
 $j=0;
 for($i=0;$i<7;$i++) {
  $dayid=$j+$startofweek;
  $daynames[$i]=getday($dayid);
  if($dayid==6 && $i!=6) $j=$startofweek*-1;
  else $j++;
 }
 for($i=$today_year-1;$i<$today_year+4;$i++) $yearbits.=makeoption($i,$i,"",0);

 while (checkdate($month,$countdays,$year)) $countdays++;

 $day=1;
 $weeknumber = ceil((date('z', mktime(0,0,0,$month,$day,$year))+1-daynumber(mktime(0,0,0,$month,$day,$year)))/7);
 
 $day_bits="";
 while($day<$countdays) {
  $events="";
  $week="";
 
  $daynumber = daynumber(mktime(0,0,0,$month,$day,$year));
  if($day==1 && $daynumber>0) $day_bits .= str_repeat("<td bgcolor=\"{mainbgcolor}\">&nbsp;</td>", $daynumber);
   
  if($daynumber==0) eval ("\$week = \"".$tpl->get("calender_week")."\";");
 
  $events="";
  if(count($eventcache[$day])) {
   while(list($key,$event)=each($eventcache[$day])) {
    if($event['public']==2) eval ("\$events .= \"".$tpl->get("calender_publicevent")."\";");
    else eval ("\$events .= \"".$tpl->get("calender_privateevent")."\";");
   }
  }
 
  if($listallbirthdays==1) {
   if(count($birthdaycache[$day])) {
    while(list($key,$birthday)=each($birthdaycache[$day])) {
     $age = $year-substr($birthday['birthday'],0,4);
     if($age<1 || $age>200) $age="";
     else $age="&nbsp;($age)";
     eval ("\$events .= \"".$tpl->get("calender_birthday")."\";");
    }
   }
  }
  elseif(isset($birthdaycount[$day]) && $birthdaycount[$day]) eval ("\$events .= \"".$tpl->get("calender_birthdays")."\";");
 
  if($month==$today_month && $year==$today_year && $day==$today_day) eval ("\$day_bits .= \"".$tpl->get("calender_todaybits")."\";");
  else eval ("\$day_bits .= \"".$tpl->get("calender_daybits")."\";");

  if($day+1==$countdays) {
   if($daynumber<6) $day_bits .= str_repeat("<td bgcolor=\"{mainbgcolor}\">&nbsp;</td>", 6-$daynumber)."</tr>";
   else $day_bits."</tr>";
  }
  elseif($daynumber==6) {
   $day_bits .= "</tr><tr>";
   $weeknumber++;
   //if($weeknumber>52) $weeknumber=1;
  }
  $day++;
 }

 if($wbbuserdata['canpublicevent']==1) eval ("\$addpublicevent = \"".$tpl->get("calender_addpublicevent")."\";");
 if($wbbuserdata['canprivateevent']==1) eval ("\$addprivateevent = \"".$tpl->get("calender_addprivateevent")."\";");

 eval("\$tpl->output(\"".$tpl->get("calender_view")."\");");
}

if($action=="viewevent") {
 if(isset($_GET['id'])) $eventid=intval($_GET['id']);	
 else eval("error(\"".$tpl->get("error_falselink")."\");");
 
 $event = $db->query_first("SELECT e.*, u.username FROM bb".$n."_events e LEFT JOIN bb".$n."_users u USING (userid) WHERE eventid='$eventid'");
 if(!$event['eventid']) eval("error(\"".$tpl->get("error_falselink")."\");");
 if(($event['public']==0 && $event['userid']!=$wbbuserdata['userid']) || ($event['public']==1 && $event['groupid']!=$wbbuserdata['groupid'])) access_error();	
 
 require("./acp/lib/class_parse.php");
 $parse = new parse($docensor,90,$event['allowsmilies']*$event_allowsmilies,$event_allowbbcode,$wbbuserdata['showimages'],"",$usecode);
 $event['event']=$parse->doparse($event['event'],$event['allowsmilies']*$event_allowsmilies,$event_allowhtml,$event_allowbbcode,$event_allowimages);
 $event['subject']=$parse->textwrap($event['subject'],30);
 $eventdate=explode('-',$event['eventdate']); 
 eval("\$tpl->output(\"".$tpl->get("calender_viewevent")."\");");
}

if($action=="viewbirthdays") {
 if(isset($_GET['day'])) $eventdate=explode('-',$_GET['day']);
 else eval("error(\"".$tpl->get("error_falselink")."\");");
 
 $eventdate[0]=intval($eventdate[0]);
 $eventdate[1]=intval($eventdate[1]);
 $eventdate[2]=intval($eventdate[2]);
 
 $currentdate = ifelse($eventdate[1]<10,"0").$eventdate[1]."-".ifelse($eventdate[2]<10,"0").$eventdate[2];
 $result = $db->query("SELECT userid, username, birthday FROM bb".$n."_users WHERE activation = 1 AND birthday LIKE '%-$currentdate' ORDER BY username ASC");
 while($row = $db->fetch_array($result)) {
  $birthyear = intval(substr($row['birthday'], 0, 4));
  $age = $eventdate[0]-$birthyear;
  if($age<1 || $age>200) $age="";
  else $age="&nbsp;($age)";
  if(isset($birthdaybit)) eval ("\$birthdaybit .= \", ".$tpl->get("index_birthdaybit")."\";");
  else eval ("\$birthdaybit = \"".$tpl->get("index_birthdaybit")."\";");
 }	
 eval("\$tpl->output(\"".$tpl->get("calender_viewbirthdays")."\");");	
}

if($action=="addevent") {
 $type=$_REQUEST['type'];	

 if(($type=="public" && $wbbuserdata['canpublicevent']==0) || ($type=="private" && $wbbuserdata['canprivateevent']==0)) access_error();
 
 if(isset($_POST['send'])) {
  if($_POST['parseurl']==1) $checked[0]="checked";
  if($_POST['disablesmilies']==1) $checked[1]="checked";	
  if(isset($_POST['grouppublic']) && $_POST['grouppublic']==1) $checked[2]="checked";	
  $day=intval($_POST['day']);
  $month=intval($_POST['month']);
  $year=intval($_POST['year']);	
  
  $subject=trim($_POST['subject']);
  $message=stripcrap(trim($_POST['message']));
 
  $error="";
  
  if(!$subject || !$message) eval ("\$error .= \"".$tpl->get("newthread_error1")."\";");
  if(!checkdate($month,$day,$year)) eval ("\$error .= \"".$tpl->get("calender_addevent_error1")."\";");
  if($error) eval ("\$event_error = \"".$tpl->get("newthread_error")."\";");
  else {
   if($_POST['parseurl']==1) $message=parseURL($message);
   $allowsmilies=1-intval($_POST['disablesmilies']);
   if($type=="public") $public=2;
   elseif(isset($_POST['grouppublic']) && $_POST['grouppublic']==1) $public=1;
   else $public=0;
   
   $eventdate=$year."-".ifelse($month<10,"0").$month."-".ifelse($day<10,"0").$day;
   
   $db->unbuffered_query("INSERT INTO bb".$n."_events (eventid,userid,groupid,subject,event,eventdate,public,allowsmilies) VALUES (NULL,'$wbbuserdata[userid]','$wbbuserdata[groupid]','".addslashes(htmlspecialchars($subject))."','".addslashes($message)."','$eventdate','$public','$allowsmilies')",1);	
   header("Location: calender.php?sid=$session[hash]");
   exit();	
  }
 }
 else {
  if($event_default_checked_0==1) $checked[0]="checked";
  if($event_default_checked_1==1) $checked[1]="checked";
  if($event_default_checked_2==1) $checked[2]="checked";
 }
 
 if($event_allowbbcode) $bbcode_buttons = getcodebuttons();
 if($event_allowsmilies) $bbcode_smilies = getclickysmilies($smilie_table_cols,$smilie_table_rows);

 if(!isset($day)) $day = formatdate("j",time());
 if(!isset($month)) $month = formatdate("n",time());
 $current_year = formatdate("Y",time());
 if(!isset($year)) $year = $current_year;
 
 $day_options="";
 $month_options="";
 $year_options="";
 for($i=1;$i<32;$i++) $day_options .= makeoption($i,$i,$day,1);
 for($i=1;$i<13;$i++) $month_options .= makeoption($i,getmonth($i),$month,1);
 for($i=$current_year;$i<$current_year+5;$i++) $year_options .= makeoption($i,$i,$year,1);

 eval ("\$note = \"".$tpl->get("note_html_".ifelse($event_allowhtml==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_bbcode_".ifelse($event_allowbbcode==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_smilies_".ifelse($event_allowsmilies==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_images_".ifelse($event_allowimages==0,"not_")."allow")."\";");

 if($type=="private") eval ("\$option_grouppublic = \"".$tpl->get("calender_addevent_grouppublic")."\";");

 if(isset($message)) $message=htmlspecialchars($message);
 if(isset($subject)) $topic=str_replace("\"","&quot;",$subject);
 eval("\$tpl->output(\"".$tpl->get("calender_addevent")."\");");
}

if($action=="editevent") {
 if(isset($_REQUEST['id'])) $id=intval($_REQUEST['id']);	
 else eval("error(\"".$tpl->get("error_falselink")."\");");
 require("./acp/lib/class_parse.php");
 
 $event = $db->query_first("SELECT * FROM bb".$n."_events WHERE eventid='$id'");
 if($event['userid']!=$wbbuserdata['userid'] && ($event['public']!=2 || $wbbuserdata['canpublicevent']==0)) access_error();
 if(isset($_POST['send'])) {
  if($_POST['deleteevent']==1) {
   $db->unbuffered_query("DELETE FROM bb".$n."_events WHERE eventid='$id'",1);	
   header("Location: calender.php?sid=$session[hash]");
   exit();	
  }
  
  if($_POST['parseurl']==1) $checked[0]="checked";
  if($_POST['disablesmilies']==1) $checked[1]="checked";	
  
  $day=intval($_POST['day']);
  $month=intval($_POST['month']);
  $year=intval($_POST['year']);	
  
  $subject=trim($_POST['subject']);
  $message=stripcrap(trim($_POST['message']));
 
  $error="";
  
  if(!$subject || !$message) eval ("\$error .= \"".$tpl->get("newthread_error1")."\";");
  if(!checkdate($month,$day,$year)) eval ("\$error .= \"".$tpl->get("calender_addevent_error1")."\";");
  if($error) eval ("\$event_error = \"".$tpl->get("newthread_error")."\";");
  else {
   $allowsmilies=1-intval($_POST['disablesmilies']);
   $eventdate=$year."-".ifelse($month<10,"0").$month."-".ifelse($day<10,"0").$day;
   
   $db->unbuffered_query("UPDATE bb".$n."_events SET subject='".addslashes(htmlspecialchars($subject))."', event='".addslashes($message)."', eventdate='$eventdate', allowsmilies='$allowsmilies' WHERE eventid='$id'",1);	
   header("Location: calender.php?sid=$session[hash]");
   exit();	
  }	
 }
 else {
  $temp=explode('-',$event['eventdate']);
  $day=intval($temp[2]);	
  $month=intval($temp[1]);
  $year=intval($temp[0]);
  $subject=$event['subject'];
  $message=$event['event'];
  if($event_default_checked_0==1) $checked[0]="checked";
  if($event[allowsmilies]==0) $checked[1]="checked";
 }

 if($event_allowbbcode) $bbcode_buttons = getcodebuttons();
 if($event_allowsmilies) $bbcode_smilies = getclickysmilies($smilie_table_cols,$smilie_table_rows);

 $current_year = formatdate("Y",time());
 
 $day_options="";
 $month_options="";
 $year_options="";
 for($i=1;$i<32;$i++) $day_options .= makeoption($i,$i,$day,1);
 for($i=1;$i<13;$i++) $month_options .= makeoption($i,getmonth($i),$month,1);
 for($i=$current_year;$i<$current_year+5;$i++) $year_options .= makeoption($i,$i,$year,1);

 eval ("\$note = \"".$tpl->get("note_html_".ifelse($event_allowhtml==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_bbcode_".ifelse($event_allowbbcode==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_smilies_".ifelse($event_allowsmilies==0,"not_")."allow")."\";");
 eval ("\$note .= \"".$tpl->get("note_images_".ifelse($event_allowimages==0,"not_")."allow")."\";");
 
 if(isset($message)) $message=parse::convertHTML($message);
 if(isset($subject)) $topic=str_replace("\"","&quot;",$subject);
 
 eval("\$tpl->output(\"".$tpl->get("calender_editevent")."\");");
}

if($action=="eventcalender") {
 require("./acp/lib/class_parse.php");
 	
 $today_day = formatdate("j",time());
 $today_month = formatdate("n",time());
 $today_year = formatdate("Y",time());
 
 $result=$db->query("SELECT
 SUBSTRING(e.eventdate,1,4) AS year,
 SUBSTRING(e.eventdate,6,2) AS month,
 SUBSTRING(e.eventdate,9,2) AS day,
 e.userid, e.subject, e.event, e.allowsmilies, u.username
 FROM bb".$n."_events e
 LEFT JOIN bb".$n."_users u USING(userid)
 WHERE SUBSTRING(e.eventdate,1,4)='$today_year' AND (public=2 OR (e.public=1 AND e.groupid = '$wbbuserdata[groupid]') OR (e.public=0 AND e.userid = '$wbbuserdata[userid]'))
 ORDER BY month ASC, day ASC, e.subject ASC");	

 $monthbit="";
 $eventbit="";
 $lastmonth=0;
 $parse = new parse($docensor,90,$event_allowsmilies,$event_allowbbcode,$wbbuserdata['showimages'],"",$usecode);
 while($row=$db->fetch_array($result)) {
  if($lastmonth!=0 && $lastmonth!=$row['month']) {
   $monthname=getmonth($lastmonth);
   eval ("\$monthbit .= \"".$tpl->get("calender_monthbit")."\";");	
   $eventbit="";
  }
  $row['event']=$parse->doparse($row['event'],$row['allowsmilies']*$event_allowsmilies,$event_allowhtml,$event_allowbbcode,$event_allowimages);
  $row['subject']=$parse->textwrap($row['subject'],30);
   
  $dayname=getday(date("w",mktime(0,0,0,$row['month'],$row['day'],$row['year'])));
  eval ("\$eventbit .= \"".$tpl->get("calender_eventbit")."\";");
  $lastmonth=$row['month'];
 }
 
 if($lastmonth!=0) {
  $monthname=getmonth($lastmonth);
  eval ("\$monthbit .= \"".$tpl->get("calender_monthbit")."\";");	
 }
 eval("\$tpl->output(\"".$tpl->get("calender_events")."\");");
}
?>