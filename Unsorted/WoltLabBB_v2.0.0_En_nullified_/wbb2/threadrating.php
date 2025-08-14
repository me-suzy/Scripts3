<?php
$filename="threadrating.php";

require ("global.php");

if(!isset($threadid)) eval("error(\"".$tpl->get("error_falselink")."\");");
if($wbbuserdata['canratethread']==0) access_error();
if($thread[closed]==2) eval("error(\"".$tpl->get("error_threadarchive")."\");");
$rating=intval($_POST['rating']);
if($rating<1 || $rating>5) eval("error(\"".$tpl->get("error_falserating")."\");");

$dorate=0;
if($wbbuserdata['userid']) {
 $result = $db->query_first("SELECT id AS threadid FROM bb".$n."_votes WHERE id='$threadid' AND votemode=2 AND userid='$wbbuserdata[userid]'");
 if(!$result[0]) $dorate=1;
}
else {
 $result = $db->query_first("SELECT id AS threadid FROM bb".$n."_votes WHERE id='$threadid' AND votemode=2 AND ipaddress='$REMOTE_ADDR'");
 if(!$result[0]) $dorate=1;
}

if($dorate==1) {
 $db->query("UPDATE bb".$n."_threads SET voted=voted+1, votepoints=votepoints+$rating WHERE threadid='$threadid'");
 $db->query("INSERT INTO bb".$n."_votes (id,votemode,userid,ipaddress) VALUES ('$threadid','2','$wbbuserdata[userid]','$REMOTE_ADDR')");
 header("Location: thread.php?threadid=$threadid&sid=$session[hash]");
}
else eval("error(\"".$tpl->get("error_alreadyrate")."\");");
?>
