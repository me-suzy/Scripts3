<?php
require ("global.php");
isAdmin(-1);

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";

if($action=="top2") {
 eval("print(\"".gettemplate("top2")."\");");
 exit();
}
if($action=="logo2") {
 eval("print(\"".gettemplate("logo2")."\");");
 exit();
}
if($action=="menue2") {
 eval("print(\"".gettemplate("menue2")."\");");
 exit();
}
if($action=="slice") {
 eval("print(\"".gettemplate("slice")."\");");
 exit();
}

isAdmin();
if($action=="menue") {
 $result=$db->query("SELECT optiongroupid, title FROM bb".$n."_optiongroups ORDER BY showorder ASC");
 $optiongroupbit="";
 while($row=$db->fetch_array($result)) $optiongroupbit.="<b>»</b> ".makehreftag("options.php?sid=$session[hash]&action=edit&optiongroupid=$row[optiongroupid]",$row[title],"main")."<br>";
 
 eval("print(\"".gettemplate("menue")."\");");
}
if($action=="storage") eval("print(\"".gettemplate("storage")."\");");
if($action=="logo") eval("print(\"".gettemplate("logo")."\");");
if($action=="storagetop") eval("print(\"".gettemplate("storagetop")."\");");
if($action=="top") eval("print(\"".gettemplate("top")."\");");
if($action=="working") eval("print(\"".gettemplate("working")."\");");
if($action=="sync") {
 if(isset($_POST['send'])) {
  $userids=$_POST['userids'];
  if($userids) {
   $result=$db->query("SELECT userid, username FROM bb".$n."_users WHERE userid IN ($userids)");	
   $count=$db->num_rows($result);
   if($count<20) $sboxsize=$count;
   else $sboxsize=20;
   while($row=$db->fetch_array($result)) $users.=makeoption($row['userid'],$row['username'],$row['userid'],1);
   eval("print(\"".gettemplate("sync_show")."\");");
  }	
  exit();	
 }
 eval("print(\"".gettemplate("sync")."\");");
}
?>
