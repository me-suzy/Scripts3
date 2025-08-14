<?php
require("global.php");
isAdmin();

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="view";

if($action == "view"){
 $count="";
 $smilie_viewbit="";
	
 $result = $db->query("SELECT smilieid, smiliepath, smilietitle, smiliecode, smilieorder FROM bb".$n."_smilies ORDER BY smilieorder ASC");
 $result2 = $db->query_first("SELECT substitute FROM bb".$n."_subvariables WHERE variable = '{imagefolder}'");
 while($row = $db->fetch_array($result)){
  if(stristr($row['smiliepath'],"http://")) $smiliepathimage = makeimgtag($row['smiliepath'],$row['smilietitle']);
  else {
   $row['smiliepath'] = "../".str_replace("{imagefolder}","$result2[substitute]", $row['smiliepath'])."";
   if(is_file($row['smiliepath'])) $smiliepathimage = makeimgtag($row['smiliepath'],$row['smilietitle']);
   else $smiliepathimage = "n/a";
  }
  $rowclass = getone($count++,"firstrow","secondrow");
  eval ("\$smilie_viewbit .= \"".gettemplate("smilie_viewbit")."\";");
 }
 eval("print(\"".gettemplate("smilie_view")."\");");
}

if($action == "add"){
 if(isset($_POST['send'])){
  $db->query("INSERT INTO bb".$n."_smilies (smilieid,smiliepath,smilietitle,smiliecode,smilieorder) VALUES (NULL, '".addslashes($_POST['smiliepath'])."', '".addslashes($_POST['smilietitle'])."', '".addslashes($_POST['smiliecode'])."', '".intval($_POST['smilieorder'])."')");
  header("Location: smilie.php?action=view&sid=$session[hash]");
  exit();
 }
 eval("print(\"".gettemplate("smilie_add")."\");");
}

if($action == "edit"){
 if(isset($_POST['send'])){
  $db->query("UPDATE bb".$n."_smilies SET smiliepath = '".addslashes($_POST['smiliepath'])."', smilietitle = '".addslashes($_POST['smilietitle'])."', smiliecode = '".addslashes($_POST['smiliecode'])."', smilieorder = '".intval($_POST['smilieorder'])."' WHERE smilieid = '".intval($_POST['smilieid'])."'");
  header("Location: smilie.php?action=view&sid=$session[hash]");
  exit();
 }
 $smilie = $db->query_first("SELECT * FROM bb".$n."_smilies WHERE smilieid = '".intval($_REQUEST['smilieid'])."'");
 eval("print(\"".gettemplate("smilie_edit")."\");");
}

if($action == "del"){
 if(isset($_POST['send'])){
  $db->query("DELETE FROM bb".$n."_smilies WHERE smilieid = '".intval($_POST['smilieid'])."'");
  header("Location: smilie.php?action=view&sid=$session[hash]");
  exit();
 }
 $smilie = $db->query_first("SELECT smilieid, smilietitle FROM bb".$n."_smilies WHERE smilieid = '".intval($_REQUEST['smilieid'])."'");
 eval("print(\"".gettemplate("smilie_del_confirm")."\");");
}
?>