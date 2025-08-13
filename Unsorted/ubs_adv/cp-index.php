<?php
require "functions.php";
require "header.php";

if($user_name == Guest){
        header("LOCATION: index.php");
        exit;
}

if($user_name) {
$userclass_stat = $db_connect->query_first("select userclass from db_users where userid='$user_id]'");
$userclass = $userclass_stat[userclass];

$alignment_stat = $db_connect->query_first("select useralignment from db_users where userid='$user_id]'");
$useralignment = $alignment_stat[useralignment];

$userlevel_stat = $db_connect->query_first("select userlevel from db_users where userid='$user_id]'");
$userlevel = $userlevel_stat[userlevel];

$userexp_stat = $db_connect->query_first("select userexp from db_users where userid='$user_id]'");
$userexp = $userexp_stat[userexp];

$hitpoint_stat = $db_connect->query_first("select userhitpoint from db_users where userid='$user_id]'");
$userhitpoint = $hitpoint_stat[userhitpoint];

$usermagicpoint_stat = $db_connect->query_first("select usermagicpoint from db_users where userid='$user_id]'");
$usermagicpoint = $usermagicpoint_stat[usermagicpoint];

$userdefense_stat = $db_connect->query_first("select userdefense from db_users where userid='$user_id]'");
$userdefense = $userdefense_stat[userdefense];

$userstrength_stat = $db_connect->query_first("select userattack from db_users where userid='$user_id]'");
$userstrength = $userstrength_stat[userattack];

$usermagic_stat = $db_connect->query_first("select usermagic from db_users where userid='$user_id]'");
$usermagic = $usermagic_stat[usermagic];

$userspeed_stat = $db_connect->query_first("select userspeed from db_users where userid='$user_id]'");
$userspeed = $userspeed_stat[userspeed];

$usermoney_stat = $db_connect->query_first("select usermoney from db_users where userid='$user_id]'");
$usermoney = $usermoney_stat[usermoney];

$userdescription_stat = $db_connect->query_first("select userbackground from db_users where userid='$user_id]'");
$userdescription = $userdescription_stat[userbackground];

$mypicurl_stat = $db_connect->query_first("select mypic from db_users where userid='$user_id]'");
$mypicurl = $mypicurl_stat[mypic];

eval("dooutput(\"".gettemplate("cp-main")."\");");
}
else
eval("dooutput(\"".gettemplate("action_error")."\");");
?>