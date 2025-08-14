<?php
$filename="login.php";
require ("./global.php");

if($wbbuserdata['userid']) access_error();

if(isset($_POST['send'])) {
 $wbb_userpassword=md5($_POST['l_password']);
 $result = $db->query_first("SELECT userid, usecookies, nosessionhash FROM bb".$n."_users WHERE username = '".addslashes(htmlspecialchars($_POST['l_username']))."' AND password = '".$wbb_userpassword."' AND activation = 1");
 if($result['userid']) {
  if($result['usecookies']==1) {
   bbcookie("wbb_userid","$result[userid]",time()+3600*24*365);
   bbcookie("wbb_userpassword","$wbb_userpassword",time()+3600*24*365);
  }
  $db->unbuffered_query("DELETE FROM bb".$n."_sessions WHERE userid = '$result[userid]'",1);
  $db->unbuffered_query("UPDATE bb".$n."_sessions SET userid = '$result[userid]' WHERE hash = '$sid'",1); 
  
  if(isset($_POST['url']) && $_POST['url'] && strstr($_POST['url'],"?")) $url=convert_url($_POST['url'],$sid,$wbbuserdata['nosessionhash']);
  else {
   if($result['nosessionhash']==1) unset($session['hash']);
   $url="index.php?sid=$session[hash]";
  }
  
  eval("redirect(\"".$tpl->get("redirect_login")."\",\"$url\");");
  exit();
 }
 else eval("error(\"".$tpl->get("error_login")."\");");
}
eval("\$tpl->output(\"".$tpl->get("login")."\");"); 
?>
