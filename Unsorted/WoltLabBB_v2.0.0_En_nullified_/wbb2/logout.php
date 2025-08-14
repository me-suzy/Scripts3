<?php
$filename="logout.php";

require ("./global.php");

bbcookie("wbb_userid","",0);
bbcookie("wbb_userpassword","",0);
bbcookie("boardpasswords","",0);
bbcookie("hidecats","",0);
bbcookie("boardvisit","",0);
bbcookie("threadvisit","",0);
bbcookie("postvisit","",0);

$db->query("UPDATE bb".$n."_sessions SET userid = '0' WHERE hash = '$sid'"); 
eval("redirect(\"".$tpl->get("redirect_logout")."\",\"index.php?sid=$session[hash]\");");
?>
