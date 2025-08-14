<?php
require("global.php");
isAdmin();

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="view";

if($action == "view"){
 $count=0;
 $ranks_viewbit="";
 $result1 = $db->query("SELECT rankid, ranktitle FROM bb".$n."_ranks ORDER BY rankid ASC");
 while($row1 = $db->fetch_array($result1)){
  $rowclass = getone($count++,"firstrow","secondrow");
  eval ("\$ranks_viewbit .= \"".gettemplate("ranks_viewbit")."\";");
 }
 eval("print(\"".gettemplate("ranks_view")."\");");
}

if($action == "add"){
 if(isset($_POST['send'])){
  $images = preg_replace("/\s*\n\s*/","\n",trim($_POST['images']));
  $db->query("INSERT INTO bb".$n."_ranks (rankid,groupid,gender,needposts,ranktitle,rankimages) VALUES (NULL,'".$_POST['group']."','".$_POST['gender']."','".intval($_POST['quantity'])."','".addslashes($_POST['title'])."','".addslashes(implode(";",explode("\n",$images)))."')");
  header("Location: ranks.php?action=view&sid=$session[hash]");
  exit();
 }
 $result = $db->query("SELECT groupid, title FROM bb".$n."_groups WHERE default_group <> 1 ORDER BY title ASC");
 while($row = $db->fetch_array($result)) $ranks_groupsbit.=makeoption($row['groupid'],$row['title'],"",0);
 eval("print(\"".gettemplate("ranks_add")."\");");
}

if($action == "edit"){
 if(isset($_POST['send'])){
  $images = preg_replace("/\s*\n\s*/","\n",trim($_POST['images']));
  $db->query("UPDATE bb".$n."_ranks SET groupid = '".$_POST['group']."', gender = '".$_POST['gender']."', needposts = '".intval($_POST['quantity'])."', ranktitle = '".addslashes($_POST['title'])."', rankimages = '".addslashes(implode(";",explode("\n",$images)))."' WHERE rankid = '".intval($_POST['rankid'])."'");
  header("Location: ranks.php?action=view&sid=$session[hash]");
  exit();
 }
	
 $ranks = $db->query_first("SELECT rankid, groupid, gender, needposts, ranktitle, rankimages FROM bb".$n."_ranks WHERE rankid = '".intval($_REQUEST['rankid'])."'");
 if($ranks['gender'] == "2") $rankgendersel[2] = " SELECTED";
 elseif($ranks['gender'] == "1") $rankgendersel[1] = " SELECTED";
 else $rankgendersel[0] = " SELECTED";
 $result = $db->query("SELECT groupid, title FROM bb".$n."_groups WHERE default_group <> 1 ORDER BY title ASC");
 while($row = $db->fetch_array($result)) $ranks_groupsbit.=makeoption($row['groupid'],$row['title'],$ranks['groupid']);
		 
 $ranks['rankimages'] = implode("\n",explode(";",$ranks['rankimages'])); 
 eval("print(\"".gettemplate("ranks_edit")."\");");
}

if($action == "del"){
 if(isset($_POST['send'])){
  $db->query("DELETE FROM bb".$n."_ranks WHERE rankid = '".intval($_POST['rankid'])."'");
  $db->query("UPDATE bb".$n."_users SET rankid = '0' WHERE rankid = '".intval($_POST['rankid'])."'");
  header("Location: ranks.php?action=view&sid=$session[hash]");
  exit();
 }
	
 $rank_delete_row = $db->query_first("SELECT ranktitle FROM bb".$n."_ranks WHERE rankid = '".intval($_REQUEST['rankid'])."'");
 eval("print(\"".gettemplate("ranks_del_confirm")."\");");
}
?>