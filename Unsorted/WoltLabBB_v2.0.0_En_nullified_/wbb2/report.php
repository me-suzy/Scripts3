<?php
$filename="report.php";

$templatelist="report";

require ("./global.php");

if(!$wbbuserdata['userid']) access_error();
if(!isset($postid)) eval("error(\"".$tpl->get("error_falselink")."\");");

if(isset($_POST['send'])) {
 $reason=trim($_POST['reason']);
 $mod=$db->query_first("SELECT email, username FROM bb".$n."_users WHERE userid='".intval($_POST['modid'])."'");
 eval ("\$mail_text = \"".$tpl->get("mt_report")."\";");
 eval ("\$mail_subject = \"".$tpl->get("ms_report")."\";");
 mailer($mod['email'],$mail_subject,$mail_text);
 header("Location: thread.php?sid=$session[hash]&postid=$postid#post$postid");
 exit(); 	
}

$mod_options="";
//$result=$db->query("SELECT u.userid, u.username FROM bb".$n."_users u LEFT JOIN bb".$n."_groups g USING (groupid) WHERE g.ismod=1 ORDER BY u.username ASC");
$result=$db->query("SELECT u.userid, u.username FROM bb".$n."_groups g LEFT JOIN bb".$n."_users u USING (groupid) WHERE g.ismod=1 AND u.userid IS NOT NULL ORDER BY u.username ASC");
while($row=$db->fetch_array($result)) $mod_options.=makeoption($row['userid'],$row['username'],"",0);

eval("\$tpl->output(\"".$tpl->get("report")."\");");
?>