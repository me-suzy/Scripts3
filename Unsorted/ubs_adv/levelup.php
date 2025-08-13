<?php
require "functions.php";

$user = $db_connect->query_first("select * from db_users where username='$user_name'");

$totalexppoints_stat = $db_connect->query_first("select totalexppoints from db_users where username='$user_name'");
$totalexppoints = $totalexppoints_stat[totalexppoints];
$tempexppoints_stat = $db_connect->query_first("select tempexppoints from db_users where username='$user_name'");
$tempexppoints = $tempexppoints_stat[tempexppoints];

eval("\$admin = \"".gettemplate("admincp")."\";");

if($action == upstrength && $tempexppoints > 0){
	$newstrength=($user[userattack]+1);
	$newtemppoints=($tempexppoints-1);
	$db_connect->query("UPDATE db_users SET userattack='$newstrength', tempexppoints='$newtemppoints' WHERE username = '$user_name'");
	header("LOCATION: levelup.php?$sessionid");
}

if($action == upmagic && $tempexppoints > 0){
	$newmagic=($user[usermagic]+1);
	$newtemppoints=($tempexppoints-1);
	$db_connect->query("UPDATE db_users SET userstrength='$newmagic', tempexppoints='$newtemppoints' WHERE username = '$user_name'");
	header("LOCATION: levelup.php?$sessionid"); 
}

if($action == upspeed && $tempexppoints > 0){
	$newspeed=($user[userspeed]+1);
	$newtemppoints=($tempexppoints-1);
	$db_connect->query("UPDATE db_users SET userspeed='$newspeed', tempexppoints='$newtemppoints' WHERE username = '$user_name'");
	header("LOCATION: levelup.php?$sessionid"); 
}

if($action == updefense && $tempexppoints > 0){
	$newdeffense=($user[userdefense]+1);
	$newtemppoints=($tempexppoints-1);
	$db_connect->query("UPDATE db_users SET userdefense='$newdeffense', tempexppoints='$newtemppoints' WHERE username = '$user_name'");
	header("LOCATION: levelup.php?$sessionid"); 
}

if($tempexppoints == 0){
	header("LOCATION: index.php?sid=$session"); 
	$error="You are not ready to level up yet!";
}

eval("dooutput(\"".gettemplate("levelup")."\");");
?>