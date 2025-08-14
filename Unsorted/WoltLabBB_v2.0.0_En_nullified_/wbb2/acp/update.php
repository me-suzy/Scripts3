<?php
@error_reporting(7);
@set_time_limit(1200);
$guest="Guest";
$phpversion=(int)(str_replace(".","",phpversion()));
require("lib/functions.php");
if($phpversion<410) {
 $_REQUEST=array();
 $_COOKIE=array();
 $_POST=array();
 $_GET=array();
 $_SERVER=array();
 $_FILES=array();
 get_vars_old();
}

function information($text,$url="",$urltext="") {
 print("<html><head><link rel=\"stylesheet\" href=\"css/other.css\"></head><body><p align=\"center\">$text</p><p align=\"center\"><a href=\"$url\">$urltext</a></p></body></html>");	
}

function editDBdata($data) {
 $data = str_replace("&acute;","'", $data);
 return str_replace("&quot;","\"", $data); 
}

require("_data.inc.php");

$m=$n;

require("lib/config.inc.php");
require("lib/class_db_mysql.php");

$db = new db($sqlhost,$sqluser,$sqlpassword,$sqldb,$phpversion);

if(isset($_REQUEST['step'])) $step=intval($_REQUEST['step']);
else $step=0;

if(isset($_REQUEST['page'])) $page=intval($_REQUEST['page']);
else $page=0;
if($page==0) $page=1;

if($step==0) information("<b>Welcome to the update from wBB1 to wBB2</b>","update.php?step=1","Click here to begin the update.");

if($step==1) {
 $perpage=500;
 $result=$db->query("SELECT * FROM bb".$m."_user_table",$perpage,$perpage*($page-1));
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   switch($row['groupid']) {
    case 1: $groupid=1; break;	
    case 2: $groupid=3; break;	
    case 5: $groupid=2; break;	
    default: $groupid=4; break;
   }  
     
   $db->query("INSERT IGNORE INTO bb".$n."_users (userid,username,password,email,userposts,groupid,title,regdate,lastvisit,lastactivity,usertext,signature,icq,aim,yim,homepage,gender,showemail,admincanemail,usercanemail,invisible,activation,blocked,daysprune,dateformat,timeformat,umaxposts,timezoneoffset)
   VALUES ('$row[userid]','".addslashes($row[username])."','$row[userpassword]','$row[useremail]','$row[userposts]','$groupid','".addslashes(editDBdata($row[statusextra]))."','$row[regdate]','$row[lastvisit]','$row[lastactivity]','".addslashes(htmlspecialchars(editDBdata($row[usertext])))."',
   '".addslashes(editDBdata($row[signatur]))."','".addslashes(intval($row[usericq]))."','".addslashes(htmlspecialchars(editDBdata($row[aim])))."','".addslashes(htmlspecialchars(editDBdata($row[yim])))."','".addslashes(htmlspecialchars(editDBdata($row[userhp])))."',
   '$row[gender]','$row[show_email_global]','$row[mods_may_email]','$row[users_may_email]','$row[invisible]','$row[activation]','$row[blocked]','$row[prunedays]','d.m.Y','H:i','$row[umaxposts]','0')");	
 
   $db->query("INSERT IGNORE INTO bb".$n."_userfields (userid,field1,field2,field3) VALUES ('$row[userid]','".addslashes(htmlspecialchars(editDBdata($row['location'])))."','".addslashes(htmlspecialchars(editDBdata($row['interests'])))."','".addslashes(htmlspecialchars(editDBdata($row['work'])))."')");	
 	
  }
  refresh("update.php?step=$step&page=".($page+1));
 }
 else information("<b>Members conversion successful</b>","update.php?step=2","Click here to continue with the update.");	
}

if($step==2) {
 $perpage=250;	
 $result=$db->query("SELECT p.*, u.username FROM bb".$m."_posts p LEFT JOIN bb".$m."_user_table u USING (userid)",$perpage,$perpage*($page-1));		
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   if(!$row['username']) $row['username']=$guest;
   
   $db->query("INSERT IGNORE INTO bb".$n."_posts (postid,threadid,userid,username,posttopic,posttime,message,allowsmilies,showsignature,ipaddress,visible,reindex)
   VALUES ('$row[postid]','$row[threadparentid]','$row[userid]','".addslashes($row[username])."','".addslashes(htmlspecialchars(editDBdata($row['posttopic'])))."','$row[posttime]','".addslashes(editDBdata($row['message']))."','".(1-$row['disable_smilies'])."','$row[signature]','$row[ip]','1','1')");	
  }	
  refresh("update.php?step=$step&page=".($page+1));
 }	
 else information("<b>Posts conversion successful</b>","update.php?step=3","Click here to continue with the update.");		
}

if($step==3) {
 $perpage=100;	
 $result=$db->query("SELECT t.*, u.username FROM bb".$m."_threads t LEFT JOIN bb".$m."_user_table u ON (t.authorid=u.userid)",$perpage,$perpage*($page-1));		
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   $lastpost=$db->query_first("SELECT userid, username, posttime FROM bb".$n."_posts WHERE threadid='$row[threadid]' ORDER BY posttime DESC",1);
      
   if(!$row['username']) $row['username']=$guest;
   
   $db->query("INSERT IGNORE INTO bb".$n."_threads (threadid,boardid,topic,starttime,starterid,starter,lastposttime,lastposterid,lastposter,replycount,views,closed,important,visible)
   VALUES ('$row[threadid]','$row[boardparentid]','".addslashes(htmlspecialchars(editDBdata($row['threadname'])))."','$row[starttime]','$row[authorid]','$row[username]','$lastpost[posttime]','$lastpost[userid]','$lastpost[username]','$row[replies]','$row[views]','$row[flags]','$row[important]','1')");	
  }	
  refresh("update.php?step=$step&page=".($page+1));	
 }	
 else information("<b>Threads conversion successful</b>","update.php?step=4","Click here to continue with the update.");
}

if($step==4) {
 $perpage=20;	
 $result=$db->query("SELECT * FROM bb".$m."_boards",$perpage,$perpage*($page-1));		
 if($db->num_rows($result)) {
  function getparentlist($boardid) {
   global $db, $m;	
   $board=$db->query_first("SELECT boardparentid FROM bb".$m."_boards WHERE boardid = '$boardid'");	
   
   if($board['boardparentid']!=0)  return getparentlist($board['boardparentid']).",".$board['boardparentid'];
   else return "0";
  }
  
  while($row=$db->fetch_array($result)) {
   if($row['boardparentid']!=0) $parentlist=getparentlist($row['boardparentid']).",".$row['boardparentid'];
   else $parentlist="0";
   
   $db->query("INSERT IGNORE INTO bb".$n."_boards (boardid,parentid,parentlist,boardorder,title,password,description,isboard,invisible)
   VALUES ('$row[boardid]','$row[boardparentid]','$parentlist','$row[sort]','".addslashes(editDBdata($row[boardname]))."','".addslashes(editDBdata($row[password]))."','".addslashes(editDBdata($row[descriptiontext]))."','$row[isboard]','$row[invisible]')");	
  }	
  refresh("update.php?step=$step&page=".($page+1));	
 }	
 else refresh("update.php?step=5");
}

if($step==5) {
 $perpage=20;	
 $result=$db->query("SELECT boardid FROM bb".$n."_boards",$perpage,$perpage*($page-1));		
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   $childlist="";
   $childs=$db->query("SELECT boardid FROM bb".$n."_boards WHERE INSTR(CONCAT(',',parentlist,','),',$row[boardid],')>0");
   while($child=$db->fetch_array($childs)) $childlist.=",".$child['boardid'];
   
   /* LOCATE('$row[boardid]',CONCAT(',',parentlist,','))>0 */
   $db->query("UPDATE bb".$n."_boards SET childlist='0$childlist' WHERE boardid='$row[boardid]'");	
  }	
  refresh("update.php?step=$step&page=".($page+1));	
 }	
 else refresh("update.php?step=6");
}

if($step==6) {
 $perpage=20;	
 $result=$db->query("SELECT boardid, childlist FROM bb".$n."_boards",$perpage,$perpage*($page-1));		
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   list($threadcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_threads WHERE boardid IN ($row[boardid],$row[childlist])");   
   list($postcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND t.boardid IN ($row[boardid],$row[childlist])");   
   $lastpost=$db->query_first("SELECT p.threadid, p.userid, p.username, p.posttime FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND t.boardid IN ($row[boardid],$row[childlist]) AND p.visible=1 ORDER BY p.posttime DESC",1);
   $db->query("UPDATE bb".$n."_boards SET threadcount='$threadcount', postcount='$postcount', lastthreadid='$lastpost[threadid]', lastposttime='$lastpost[posttime]', lastposterid='$lastpost[userid]', lastposter='$lastpost[username]' WHERE boardid='$row[boardid]'");	
  }	
  refresh("update.php?step=$step&page=".($page+1));	
 }	
 else information("<b>Forums conversion successful</b>","update.php?step=7","Click here to continue with the update.");
}

if($step==7) {
 $perpage=250;	
 $result=$db->query("SELECT boardid, objectid FROM bb".$m."_object2board WHERE mod=1",$perpage,$perpage*($page-1));		
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   $db->query("INSERT IGNORE INTO bb".$n."_moderators (boardid,userid) VALUES ('$row[boardid]','$row[objectid]')");	
  }	
  refresh("update.php?step=$step&page=".($page+1));	
 }	
 else information("<b>Moderators conversion successful</b>","update.php?step=8","Click here to continue with the update.");
}

if($step==8) {
 $perpage=100;	
 $result=$db->query("SELECT * FROM bb".$m."_pms",$perpage,$perpage*($page-1));		
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   $db->query("INSERT IGNORE INTO bb".$n."_privatemessage (privatemessageid,senderid,recipientid,subject,message,sendtime,showsmilies,showsignature,view,reply,forward)
   VALUES ('$row[pmid]','$row[senderid]','$row[recipientid]','".addslashes(htmlspecialchars(editDBdata($row['subject'])))."','".addslashes(editDBdata($row['message']))."','$row[sendtime]','".(1-$row['disable_smilies'])."','$row[signature]','$row[view]','$row[reply]','$row[forward]')");	
  }	
  refresh("update.php?step=$step&page=".($page+1));	
 }	
 else information("<b>Private Messages conversion successful</b>","update.php?step=9","Click here to continue with the update.");
}

if($step==9) {
 $perpage=100;	
 $result=$db->query("SELECT userid, groupid, gender, userposts FROM bb".$n."_users",$perpage,$perpage*($page-1));		
 if($db->num_rows($result)) {
  while($row=$db->fetch_array($result)) {
   list($rankid)=$db->query_first("SELECT rankid FROM bb".$n."_ranks WHERE groupid IN ('0','$row[groupid]') AND needposts<='$row[userposts]' AND gender IN ('0','$row[gender]') ORDER BY needposts DESC, gender DESC",1);
   
   $db->query("UPDATE bb".$n."_users SET rankid='$rankid' WHERE userid='$row[userid]'");	
  }	
  refresh("update.php?step=$step&page=".($page+1));	
 }	
 else {
  $result = $db->query_first("SELECT MIN(regdate) AS installdate FROM bb".$n."_users");
  $db->query("UPDATE bb".$n."_options SET value='$result[installdate]' WHERE varname='installdate'");
  require ("lib/class_options.php");
  $option=new options("lib");
  $option->write();
  information("<b>Update done</b> Please, delete setup.php and update.php from the server.","index.php","Click to access the Admin Control Panel!");
 }
}
?>
