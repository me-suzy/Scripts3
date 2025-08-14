<?php
require("./global.php");
isAdmin();

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="view";

if($action=="view") {
 $bbcodes_viewbit="";
 $count = "";
 $result=$db->query("SELECT bbcodeid, bbcodetag FROM bb".$n."_bbcodes");
 while($row=$db->fetch_array($result)) {
  $rowclass = getone($count++,"firstrow","secondrow");
  eval ("\$bbcodes_viewbit .= \"".gettemplate("bbcodes_viewbit")."\";");	
 }	
	
 eval("print(\"".gettemplate("bbcodes_view")."\");");	
}

if($action=="edit") {
 $bbcodeid=intval($_REQUEST['bbcodeid']);
 
 if(isset($_POST['send'])) {
  $bbcodereplacement=$_POST['bbcodereplacement'];
  if($_POST['params']>1) {
   $bbcodereplacement=str_replace("{param1}","\\2",$bbcodereplacement);
   $bbcodereplacement=str_replace("{param2}","\\3",$bbcodereplacement);
   $bbcodereplacement=str_replace("{param3}","\\4",$bbcodereplacement);
  }
  else $bbcodereplacement=str_replace("{param1}","\\1",$bbcodereplacement);
  
  $db->unbuffered_query("UPDATE bb".$n."_bbcodes SET bbcodetag='".addslashes(trim($_POST['bbcodetag']))."', bbcodereplacement='".addslashes($bbcodereplacement)."', bbcodeexample='".addslashes(trim($_POST['bbcodeexample']))."', bbcodeexplanation='".addslashes(trim($_POST['bbcodeexplanation']))."', params='$_POST[params]', multiuse='".intval($_POST['multiuse'])."' WHERE bbcodeid='$bbcodeid'",1);	
  header("Location: bbcodes.php?action=view&sid=$session[hash]");
  exit();
 }
 
 
 $bbcode=$db->query_first("SELECT * FROM bb".$n."_bbcodes WHERE bbcodeid='$bbcodeid'");	

 $bbcode['bbcodereplacement']=str_replace("\\1","{param1}",$bbcode['bbcodereplacement']);
 $bbcode['bbcodereplacement']=str_replace("\\2","{param1}",$bbcode['bbcodereplacement']);
 $bbcode['bbcodereplacement']=str_replace("\\3","{param2}",$bbcode['bbcodereplacement']);
 $bbcode['bbcodereplacement']=str_replace("\\4","{param3}",$bbcode['bbcodereplacement']);

 $bbcode=htmlspecialchars_array($bbcode);
 
 $sel_params[$bbcode['params']]=" SELECTED";
	
 eval("print(\"".gettemplate("bbcodes_edit")."\");");	
}

if($action=="add") {
 if(isset($_POST['send'])) {
  $bbcodereplacement=$_POST['bbcodereplacement'];
  if($_POST['params']>1) {
   $bbcodereplacement=str_replace("{param1}","\\2",$bbcodereplacement);
   $bbcodereplacement=str_replace("{param2}","\\3",$bbcodereplacement);
   $bbcodereplacement=str_replace("{param3}","\\4",$bbcodereplacement);
  }
  else $bbcodereplacement=str_replace("{param1}","\\1",$bbcodereplacement);
  
  $db->unbuffered_query("INSERT INTO bb".$n."_bbcodes (bbcodeid,bbcodetag,bbcodereplacement,bbcodeexample,bbcodeexplanation,params,multiuse) VALUES (NULL,'".addslashes(trim($_POST['bbcodetag']))."','".addslashes($bbcodereplacement)."','".addslashes(trim($_POST['bbcodeexample']))."','".addslashes(trim($_POST['bbcodeexplanation']))."','$_POST[params]','".intval($_POST['multiuse'])."')",1);	
  header("Location: bbcodes.php?action=view&sid=$session[hash]");
  exit();
 }
 
 eval("print(\"".gettemplate("bbcodes_add")."\");");	
}

/*
 ****
 **
 *******
 */

if($action=="del") {
 $bbcodeid=intval($_REQUEST['bbcodeid']);
 
 if(isset($_POST['send'])) {
  $db->unbuffered_query("DELETE FROM bb".$n."_bbcodes WHERE bbcodeid='$bbcodeid'",1);	
  header("Location: bbcodes.php?action=view&sid=$session[hash]");
  exit();
 }
 
 $bbcode=$db->query_first("SELECT bbcodetag FROM bb".$n."_bbcodes WHERE bbcodeid='$bbcodeid'");	
 eval("print(\"".gettemplate("bbcodes_del")."\");");	
}
?>