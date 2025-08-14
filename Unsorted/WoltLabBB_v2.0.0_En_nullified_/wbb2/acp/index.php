<?php
require ("./global.php");

if(!$wbbuserdata['canuseacp'] && !$wbbuserdata['issupermod'] && !$wbbuserdata['ismod']) {
 if(isset($_COOKIE['wbb_userid'])) list($l_username) = $db->query_first("SELECT username FROM bb".$n."_users WHERE userid='".intval($_COOKIE['wbb_userid'])."'"); 
 else $l_username="";
 
 eval("print(\"".gettemplate("login")."\");");
 exit();	
}

if($wbbuserdata['canuseacp']==1) eval("print(\"".gettemplate("frameset")."\");");
else eval("print(\"".gettemplate("frameset2")."\");");
?>