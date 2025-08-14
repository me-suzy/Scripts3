<?php
$filename="polledit.php";
require ("global.php");

if(!isset($pollid)) eval("error(\"".$tpl->get("error_falselink")."\");");
if(!$wbbuserdata['issupermod'] && !$modpermissions['userid']) access_error();

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";
if(isset($_REQUEST['polloptionid'])) $polloptionid=intval($_REQUEST['polloptionid']);
else $polloptionid=0;

if($action=="polldelete") {
 if(isset($_POST['deletepoll']) && $_POST['deletepoll']==1) {
  $db->unbuffered_query("DELETE FROM bb".$n."_polls WHERE pollid='$pollid'",1);	
  $db->unbuffered_query("DELETE FROM bb".$n."_polloptions WHERE pollid='$pollid'",1);	
  $db->unbuffered_query("DELETE FROM bb".$n."_votes WHERE id='$pollid' AND votemode='1'",1);	
  $db->unbuffered_query("UPDATE bb".$n."_threads SET pollid='0' WHERE pollid='$pollid'",1);	
 }	

 header("Location: thread.php?threadid=$poll[threadid]&sid=$session[hash]");
 exit();	
}

if($action=="ShiftToTop" && $polloptionid) {
 list($showorder)=$db->query_first("SELECT showorder FROM bb".$n."_polloptions WHERE polloptionid='$polloptionid'");
 if($showorder>1) {
  $db->query("UPDATE bb".$n."_polloptions SET showorder=showorder+1 WHERE pollid='$pollid' AND showorder<'$showorder'");	
  $db->query("UPDATE bb".$n."_polloptions SET showorder=1 WHERE polloptionid='$polloptionid'");	
 }
}

if($action=="ShiftUp" && $polloptionid) {
 list($showorder)=$db->query_first("SELECT showorder FROM bb".$n."_polloptions WHERE polloptionid='$polloptionid'");
 if($showorder>1) {
  $db->query("UPDATE bb".$n."_polloptions SET showorder=showorder+1 WHERE pollid='$pollid' AND showorder='".($showorder-1)."'");	
  $db->query("UPDATE bb".$n."_polloptions SET showorder=showorder-1 WHERE polloptionid='$polloptionid'");
 }
}

if($action=="ShiftDown" && $polloptionid) {
 list($optioncount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_polloptions WHERE pollid='$pollid'");
 list($showorder)=$db->query_first("SELECT showorder FROM bb".$n."_polloptions WHERE polloptionid='$polloptionid'");
 if($showorder<$optioncount) {
  $db->query("UPDATE bb".$n."_polloptions SET showorder=showorder-1 WHERE pollid='$pollid' AND showorder='".($showorder+1)."'");	
  $db->query("UPDATE bb".$n."_polloptions SET showorder=showorder+1 WHERE polloptionid='$polloptionid'");	
 }
}

if($action=="ShiftToBottom" && $polloptionid) {
 list($optioncount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_polloptions WHERE pollid='$pollid'");
 list($showorder)=$db->query_first("SELECT showorder FROM bb".$n."_polloptions WHERE polloptionid='$polloptionid'");
 if($showorder<$optioncount) {
  $db->query("UPDATE bb".$n."_polloptions SET showorder=showorder-1 WHERE pollid='$pollid' AND showorder>'$showorder'");	
  $db->query("UPDATE bb".$n."_polloptions SET showorder='$optioncount' WHERE polloptionid='$polloptionid'");	
 }
}

if($action=="delentry" && $polloptionid) {
 list($showorder)=$db->query_first("SELECT showorder FROM bb".$n."_polloptions WHERE polloptionid='$polloptionid'");
 $db->query("DELETE FROM bb".$n."_polloptions WHERE polloptionid='$polloptionid'");
 $db->query("UPDATE bb".$n."_polloptions SET showorder=showorder-1 WHERE pollid='$pollid' AND showorder>'$showorder'");
}

if($action=="addentry") {
 list($showorder)=$db->query_first("SELECT MAX(showorder) FROM bb".$n."_polloptions WHERE pollid='$pollid'");
 $db->query("INSERT INTO bb".$n."_polloptions (polloptionid,pollid,polloption,votes,showorder) VALUES (NULL,'$pollid','".addslashes($_REQUEST['option'])."','0','".($showorder+1)."')");	
}

if($action=="saveentry" && $polloptionid) $db->query("UPDATE bb".$n."_polloptions SET polloption='".addslashes($_REQUEST['option'])."' WHERE polloptionid='$polloptionid'");

if($action=="savepoll") {
 $db->unbuffered_query("UPDATE bb".$n."_polls SET question='".addslashes($_REQUEST['question'])."', choicecount='".addslashes($_REQUEST['choicecount'])."', timeout='".addslashes($_REQUEST['timeout'])."' WHERE pollid='$pollid'");	
 header("Location: thread.php?threadid=$poll[threadid]&sid=$session[hash]");
 exit();
}

if(!$action) {
 $question=$poll['question'];
 $choicecount=$poll['choicecount'];
 $timeout=$poll['timeout'];
}

$result=$db->query("SELECT * FROM bb".$n."_polloptions WHERE pollid='$pollid' ORDER BY showorder ASC");
while($row=$db->fetch_array($result)) $polloptions.=makeoption($row['polloptionid'],htmlspecialchars($row['polloption']),$polloptionid);

$question=htmlspecialchars($question);
$choicecount=intval($choicecount);
$timeout =intval($timeout);

eval("\$tpl->output(\"".$tpl->get("polledit")."\");");
?>