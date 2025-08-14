<?php
$filename="pollvote.php";
require ("global.php");

if(!isset($pollid)) eval("error(\"".$tpl->get("error_falselink")."\");");
if(!count($_POST['polloptionid'])) eval("error(\"".$tpl->get("error_falsevote")."\");");
if($poll['timeout'] && time()>$poll['starttime']+$poll['timeout']*86400) eval("error(\"".$tpl->get("error_polltimeout")."\");"); 
if(count($_POST['polloptionid'])>$poll['choicecount']) eval("error(\"".$tpl->get("error_tomanyvotes")."\");");

if($wbbuserdata['userid']) $votecheck=$db->query_first("SELECT id AS pollid FROM bb".$n."_votes WHERE id='$pollid' AND votemode='1' AND userid='$wbbuserdata[userid]'");
else $votecheck=$db->query_first("SELECT id AS pollid FROM bb".$n."_votes WHERE id='$pollid' AND votemode='1' AND ipaddress='$REMOTE_ADDR'");
if($votecheck['pollid']) eval("error(\"".$tpl->get("error_alreadyvoted")."\");");

$db->query("INSERT INTO bb".$n."_votes (id,votemode,userid,ipaddress) VALUES ('$pollid','1','$wbbuserdata[userid]','$REMOTE_ADDR')");
$polloptionsids=implode(",",$_POST['polloptionid']);
$polloptionsids=preg_replace("/[^0-9,]/","",$polloptionsids);
$db->query("UPDATE bb".$n."_polloptions SET votes=votes+1 WHERE polloptionid IN ($polloptionsids)");
header("Location: thread.php?threadid=$poll[threadid]&sid=$session[hash]");
exit();
?>
