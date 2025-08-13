<?php
require "functions.php";
require "header.php";

$rpg_leader = $db_connect->query_first("select username from db_users where userid='1'");
$rpgleader = $rpg_leader[username];
$hitpoint_stat = $db_connect->query_first("select userhitpoint from db_users where username='$user_name'");
$userhitpoint = $hitpoint_stat[userhitpoint];
$userclass_stat = $db_connect->query_first("select userclass from db_users where username='$user_name'");
$userclass = $userclass_stat[userclass];

if($user_name != Guest){
$userroom4_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$userroom4 = $userroom4_stat[userroom];

if($userroom4 != lobby){
$userroom3_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$userroom3 = $userroom3_stat[userroom];
$myname3_stat = $db_connect->query_first("select me from $userroom3 where id='1'");
$myname3 = $myname3_stat[me];
$challenger3_stat = $db_connect->query_first("select challenger from $userroom3 where id='1'");
$challenger3 = $challenger3_stat[challenger];
$battleaccepted_stat = $db_connect->query_first("select accepted from $userroom3 where id='1'");
$battleaccepted = $battleaccepted_stat[accepted];

if($user_name == $challenger3){
$if_accepted = $db_connect->query_first("select accepted from $userroom4 where id='1'");
$ifaccepted = $if_accepted[accepted];
if($ifaccepted == 'yes') {
	header("LOCATION: arena.php");
}
if($userroom3 == db_arena1 || $userroom3 == db_arena2 || $userroom3 == db_arena3 || $userroom3 == db_arena4 || $userroom3 == db_arena5 || $userroom3 == db_arena6 || $userroom3 == db_arena7 || $userroom3 == db_arena8 || $userroom3 == db_arena9 || $userroom3 == db_arena10){
eval ("\$battlealert = \"".gettemplate("acceptreject")."\";");
}

if($user_name == $me3){
	if($battleaccepted == 'accepted'){
	$battlealert="<a href=\"arena.php\">Click to start!</a>";
	}
}
}
}
}

if($userhitpoint < 0){
      $ifdeadtitle="You are dead.";
}

if($userhitpoint > 0){
      $ifdeadtitle="You're not dead.";
}

if($userhitpoint < 0){
      eval ("\$ifdead = \"".gettemplate("iamdead")."\";");
}

if($user_name != Guest){
if($userclass==""){
      $warning="Warning: You have not chosen a class, your stats are all set to 0!";
}
}

$result = $db_connect->query("SELECT id, adminname, message FROM db_announcements ORDER BY id ASC");
while($message = $db_connect->fetch_array($result)) eval ("\$announcements .= \"".gettemplate("announcement_bit")."\";");

eval ("\$ifloggedon = \"".gettemplate("navichain")."\";");
eval("dooutput(\"".gettemplate("main")."\");");

?>