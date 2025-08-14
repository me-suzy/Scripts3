<?php
require("global.php");
isAdmin();

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="view";

if($action == "view"){
 $count="";
 $icon_viewbit="";
 
 $result2 = $db->query_first("SELECT substitute FROM bb".$n."_subvariables WHERE variable = '{imagefolder}'");
 $result = $db->query("SELECT iconid, iconpath, icontitle, iconorder FROM bb".$n."_icons ORDER BY iconorder ASC");
 while($row = $db->fetch_array($result)) {
  if(stristr($row['iconpath'],"http://")) $iconpathimage = makeimgtag($row['iconpath'],$row['icontitle']);
  else {
   $row['iconpath'] = "../".str_replace("{imagefolder}","$result2[substitute]", $row['iconpath'])."";
   if(is_file($row['iconpath'])) $iconpathimage = makeimgtag($row['iconpath'],$row['icontitle']);
   else $iconpathimage = "n/a";
  }
  $rowclass = getone($count++,"firstrow","secondrow");
  eval ("\$icon_viewbit .= \"".gettemplate("icon_viewbit")."\";");
 }
 eval("print(\"".gettemplate("icon_view")."\");");
}

if($action == "add"){
 if(isset($_POST['send'])) {
  $db->query("INSERT INTO bb".$n."_icons (iconid,iconpath,icontitle,iconorder) VALUES (NULL, '".addslashes($_POST['iconpath'])."', '".addslashes($_POST['icontitle'])."', '".intval($_POST['iconorder'])."')");
  header("Location: icon.php?action=view&sid=$session[hash]");
  exit();
 }
 eval("print(\"".gettemplate("icon_add")."\");");
}

if($action == "edit"){
 if($_POST['send'] == "send") {
  $db->query("UPDATE bb".$n."_icons SET iconpath = '".addslashes($_POST['iconpath'])."', icontitle = '".addslashes($_POST['icontitle'])."', iconorder = '".intval($_POST['iconorder'])."' WHERE iconid = '".$_POST['iconid']."'");
  header("Location: icon.php?action=view&sid=$session[hash]");
  exit();
 }
 $icon = $db->query_first("SELECT iconid, iconpath, icontitle, iconorder FROM bb".$n."_icons WHERE iconid = '".$_REQUEST['iconid']."'");
 eval("print(\"".gettemplate("icon_edit")."\");");
}

if($action == "del"){
 if(isset($_POST['send'])) {
  $db->query("DELETE FROM bb".$n."_icons WHERE iconid = '".$_POST['iconid']."'");
  $db->query("UPDATE bb".$n."_threads SET iconid = '0' WHERE iconid = '".$_POST['iconid']."'");
  $db->query("UPDATE bb".$n."_posts SET iconid = '0' WHERE iconid = '".$_POST['iconid']."'");
  $db->query("UPDATE bb".$n."_privatemessage SET iconid = '0' WHERE iconid = '".$_POST['iconid']."'");
  header("Location: icon.php?action=view&sid=$session[hash]");
  exit();
 }
 $icon = $db->query_first("SELECT iconid, icontitle FROM bb".$n."_icons WHERE iconid = '".$_REQUEST['iconid']."'");
 eval("print(\"".gettemplate("icon_del_confirm")."\");");
}
?>