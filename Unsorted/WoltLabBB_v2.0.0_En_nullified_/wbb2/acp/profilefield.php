<?php
require("global.php");
isAdmin();

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="view";

if($action == "view"){
 $count=0;
 $profilefield_viewbit="";
 $result = $db->query("SELECT profilefieldid, title, fieldorder FROM bb".$n."_profilefields");
 while($row = $db->fetch_array($result)){
  $rowclass = getone($count++,"firstrow","secondrow");
  eval ("\$profilefield_viewbit .= \"".gettemplate("profilefield_viewbit")."\";");
 }
 eval("print(\"".gettemplate("profilefield_view")."\");");
}

if($action == "add"){
 if(isset($_POST['send'])){
  $db->query("INSERT INTO bb".$n."_profilefields (profilefieldid,title,description,required,showinthread,hidden,maxlength,fieldsize,fieldorder) VALUES (NULL,'".addslashes($_POST['title'])."', '".addslashes($_POST['description'])."','".intval($_POST['required'])."','".intval($_POST['showinthread'])."','".intval($_POST['hidden'])."','".intval($_POST['maxlength'])."','".intval($_POST['fieldsize'])."','".intval($_POST['fieldorder'])."')");
  $id = $db->insert_id();
  $db->query("ALTER TABLE bb".$n."_userfields ADD field$id varchar(250) NOT NULL");
  header("Location: profilefield.php?action=view&sid=$session[hash]");
  exit();
 }
 eval("print(\"".gettemplate("profilefield_add")."\");");
}

if($action == "edit"){
 if(isset($_POST['send'])) {
  $db->query("UPDATE bb".$n."_profilefields SET title = '".addslashes($_POST['title'])."', description = '".addslashes($_POST['description'])."', required = '".$_POST['required']."', showinthread = '".$_POST['showinthread']."', maxlength = '".intval($_POST['maxlength'])."', fieldsize = '".intval($_POST['fieldsize'])."', fieldorder = '".intval($_POST['fieldorder'])."', hidden = '".intval($_POST['hidden'])."' WHERE profilefieldid = '".intval($_POST['profilefieldid'])."'");
  header("Location: profilefield.php?action=view&sid=$session[hash]");
  exit();
 }
	
 $profile = $db->query_first("SELECT * FROM bb".$n."_profilefields WHERE profilefieldid = '".intval($_REQUEST['profilefieldid'])."'");
 if($profile['required'] == "1")$profilesel[0] = " SELECTED";
 else $profilesel[1] = " SELECTED";
 if($profile['showinthread'] == "1") $profilesel[2] = " SELECTED";
 else $profilesel[3] = " SELECTED";
 if($profile['hidden'] == "1") $profilesel[4] = " SELECTED";
 else $profilesel[5] = " SELECTED";
 eval("print(\"".gettemplate("profilefield_edit")."\");");
}

if($action == "del"){
 if(isset($_POST['send'])){
  $db->query("DELETE FROM bb".$n."_profilefields WHERE profilefieldid = '".intval($_POST['profilefieldid'])."'");
  $db->query("ALTER TABLE bb".$n."_userfields DROP field".intval($_POST['profilefieldid'])."");
  header("Location: profilefield.php?action=view&sid=$session[hash]");
  exit();
 }
 $profile = $db->query_first("SELECT title FROM bb".$n."_profilefields WHERE profilefieldid = '".intval($_REQUEST['profilefieldid'])."'");
 eval("print(\"".gettemplate("profilefield_del_confirm")."\");");
}
?>