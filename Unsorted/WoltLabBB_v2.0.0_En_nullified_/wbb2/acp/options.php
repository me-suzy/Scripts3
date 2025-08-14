<?php
require ("global.php");
isAdmin();

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";

if(!$action) {
 $result=$db->query("SELECT og.*, COUNT(o.optionid) AS options FROM bb".$n."_optiongroups og LEFT JOIN bb".$n."_options o USING(optiongroupid) GROUP BY og.optiongroupid ORDER BY og.showorder ASC");
 $groupbit="";
 $count=0;
 while($row=$db->fetch_array($result)) {
  $rowclass=getone($count,"firstrow","secondrow");
  eval("\$groupbit .= \"".gettemplate("options_groupbit")."\";");
  $count++;
 }
 eval("print(\"".gettemplate("options_group")."\");"); 	
}

if($action=="edit") {
 if(isset($_REQUEST['optiongroupid'])) $optiongroupid=intval($_REQUEST['optiongroupid']);
 else $optiongroupid=0;
 
 if(isset($_POST['send'])) {
  if(is_array($_POST['option'])) {
   reset($_POST['option']);
   while(list($optionid,$value)=each($_POST['option'])) $db->query("UPDATE bb".$n."_options SET value='".addslashes($value)."' WHERE optionid='$optionid'");
  }
  require ("lib/class_options.php");
  $option=new options("lib");
  $option->write();
  header("Location: options.php?sid=$session[hash]");
  exit();	
 }
 
 $optiongroup=$db->query_first("SELECT title FROM bb".$n."_optiongroups WHERE optiongroupid='$optiongroupid'");
 
 $result=$db->query("SELECT * FROM bb".$n."_options WHERE optiongroupid='$optiongroupid' ORDER BY showorder ASC");
 $optionbit="";
 $count=0;
 while($row=$db->fetch_array($result)) {
  $rowclass=getone($count,"firstrow","secondrow");
  if($row['optioncode']=="text") $optioncode="<input type=\"text\" name=\"option[$row[optionid]]\" value=\"".htmlspecialchars($row['value'])."\" size=\"35\">";
  elseif($row['optioncode']=="truefalse") {
   $checked=array();
   $checked[$row['value']]=" checked";
   eval("\$optioncode = \"".gettemplate("options_optioncode")."\";");
  }
  elseif($row['optioncode']=="textarea") $optioncode="<textarea name=\"option[$row[optionid]]\" rows=\"8\" cols=\"50\" wrap=\"soft\">".htmlspecialchars($row[value])."</textarea>";
  else eval ("\$optioncode = \"$row[optioncode]\";");
  
  eval("\$optionbit .= \"".gettemplate("options_optionbit")."\";");
  $count++;
 }
 eval("print(\"".gettemplate("options_option")."\");"); 	
}
?>
