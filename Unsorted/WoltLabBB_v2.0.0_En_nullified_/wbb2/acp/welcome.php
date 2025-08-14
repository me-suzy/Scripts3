<?php
require ("./global.php");
isAdmin();

$db->unbuffered_query("DELETE FROM bb".$n."_sessions WHERE lastactivity<".(time()-$sessiontimeout),1);
$db->unbuffered_query("DELETE FROM bb".$n."_searchs WHERE searchtime<".(time()-86400*7),1);
 
$install_date=formatdate($wbbuserdata['dateformat']." ".$wbbuserdata['timeformat'],$installdate);
list($usercount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_users");
list($useronlinecount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_sessions WHERE lastactivity >= '".(time()-60*$useronlinetimeout)."'");
list($postcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_posts");
list($threadcount)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_threads");

$installdays = (time() - $installdate) / 86400;
if($installdays < 1) {
 $postsperday = $postcount;
 $threadsperday = $threadcount;
}
else {
 $postsperday = sprintf("%.2f",($postcount / $installdays)); 
 $threadsperday = sprintf("%.2f",($threadcount / $installdays)); 
}

list($waiting4Activation)=$db->query_first("SELECT COUNT(*) FROM bb".$n."_users WHERE activation<>1");

if($uptime = @exec("uptime")) {
 if(preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$uptime,$match)) {
 
  $match[1]*=100;
  $match[2]*=100;
  $match[3]*=100;
  
  eval("\$serverinfo = \"".gettemplate("welcome_serverinfo")."\";"); 
 }
}

eval("print(\"".gettemplate("welcome")."\");"); 
?>
