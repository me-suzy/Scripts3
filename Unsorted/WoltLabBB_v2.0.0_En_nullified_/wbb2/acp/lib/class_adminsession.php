<?php
class adminsession {
 var $hash = "";
	
 function update($hash="",$ip,$agent) {
  if($hash!="" && strlen($hash)==32) {
   global $db, $n, $adminsession_timeout, $wbbuserdata, $disableverify;
  
   if($disableverify!=0) $session = $db->query_first("SELECT * FROM bb".$n."_adminsessions WHERE hash = '".addslashes($hash)."' AND lastactivity >= '".(time()-$adminsession_timeout)."'");
   else $session = $db->query_first("SELECT * FROM bb".$n."_adminsessions WHERE hash = '".addslashes($hash)."' AND ipaddress = '".addslashes($ip)."' AND useragent = '".addslashes($agent)."' AND lastactivity >= '".(time()-$adminsession_timeout)."'");
   if($session['hash']) {
    $this->hash=$session['hash'];
    $wbbuserdata = $db->query_first("SELECT bb".$n."_users.*, bb".$n."_groups.* FROM bb".$n."_users LEFT JOIN bb".$n."_groups USING (groupid) WHERE userid = '$session[userid]'");
    $db->unbuffered_query("UPDATE bb".$n."_adminsessions SET lastactivity='".time()."' WHERE hash = '".$this->hash."'",1);	
   }
   else {
    eval("print(\"".gettemplate("access_error")."\");");	
    exit();
   }
  }
  else {
   eval("print(\"".gettemplate("access_error")."\");");	
   exit();	
  }	
 }	
	
 function create($userid,$ip,$agent) {
  global $db, $n;
  
  $this->hash=md5(uniqid(microtime()));
  $db->query("INSERT INTO bb".$n."_adminsessions (hash,userid,ipaddress,useragent,starttime,lastactivity) VALUES ('".$this->hash."','$userid','".addslashes($ip)."','".addslashes($agent)."','".time()."','".time()."')");
 }	
}
?>