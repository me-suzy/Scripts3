<?php
$filename="markread.php";

require ("global.php");

if(isset($_GET['id'])) {
 if(isset($_COOKIE['boardvisit'])) $boardvisit=decode_cookie($_COOKIE['boardvisit']);
 else $boardvisit=array();
 $boardvisit[$_GET['id']]=time();
 if($wbbuserdata['usecookies']==1) encode_cookie("boardvisit");
}
else {
 if($wbbuserdata['userid']) $db->query("UPDATE bb".$n."_users SET lastvisit='".time()."' WHERE userid = '$wbbuserdata[userid]'"); 
 else bbcookie("lastvisit",time(),0);
}
if(isset($_GET['id'])) header("Location: board.php?boardid=$_GET[id]&sid=$session[hash]");
else header("Location: index.php?sid=$session[hash]");
?>
