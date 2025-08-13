<?php
require "functions.php";

$level1_stat = $db_connect->query_first("select expneeded from db_levels where level='1'");
$level1 = $level1_stat[expneeded];
$level2_stat = $db_connect->query_first("select expneeded from db_levels where level='2'");
$level2 = $level2_stat[expneeded];
$level3_stat = $db_connect->query_first("select expneeded from db_levels where level='3'");
$level3 = $level3_stat[expneeded];
$level4_stat = $db_connect->query_first("select expneeded from db_levels where level='4'");
$level4 = $level4_stat[expneeded];
$level5_stat = $db_connect->query_first("select expneeded from db_levels where level='5'");
$level5 = $level5_stat[expneeded];
$level6_stat = $db_connect->query_first("select expneeded from db_levels where level='6'");
$level6 = $level6_stat[expneeded];
$level7_stat = $db_connect->query_first("select expneeded from db_levels where level='7'");
$level7 = $level7_stat[expneeded];
$level8_stat = $db_connect->query_first("select expneeded from db_levels where level='8'");
$level8 = $level8_stat[expneeded];
$level9_stat = $db_connect->query_first("select expneeded from db_levels where level='9'");
$level9 = $level9_stat[expneeded];
$level10_stat = $db_connect->query_first("select expneeded from db_levels where level='10'");
$level10 = $level10_stat[expneeded];
$level11_stat = $db_connect->query_first("select expneeded from db_levels where level='11'");
$level11 = $level11_stat[expneeded];
$level12_stat = $db_connect->query_first("select expneeded from db_levels where level='12'");
$level12 = $level12_stat[expneeded];
$level13_stat = $db_connect->query_first("select expneeded from db_levels where level='13'");
$level13 = $level13_stat[expneeded];
$level14_stat = $db_connect->query_first("select expneeded from db_levels where level='14'");
$level14 = $level14_stat[expneeded];
$level15_stat = $db_connect->query_first("select expneeded from db_levels where level='15'");
$level15 = $level15_stat[expneeded];
$level16_stat = $db_connect->query_first("select expneeded from db_levels where level='16'");
$level16 = $level16_stat[expneeded];
$level17_stat = $db_connect->query_first("select expneeded from db_levels where level='17'");
$level17 = $level17_stat[expneeded];
$level18_stat = $db_connect->query_first("select expneeded from db_levels where level='18'");
$level18 = $level18_stat[expneeded];
$level19_stat = $db_connect->query_first("select expneeded from db_levels where level='19'");
$level19 = $level19_stat[expneeded];
$level20_stat = $db_connect->query_first("select expneeded from db_levels where level='20'");
$level20 = $level20_stat[expneeded];
$level21_stat = $db_connect->query_first("select expneeded from db_levels where level='21'");
$level21 = $level21_stat[expneeded];
$level22_stat = $db_connect->query_first("select expneeded from db_levels where level='22'");
$level22 = $level22_stat[expneeded];
$level23_stat = $db_connect->query_first("select expneeded from db_levels where level='23'");
$level23 = $level23_stat[expneeded];
$level24_stat = $db_connect->query_first("select expneeded from db_levels where level='24'");
$level24 = $level24_stat[expneeded];
$level25_stat = $db_connect->query_first("select expneeded from db_levels where level='25'");
$level25 = $level25_stat[expneeded];
$level26_stat = $db_connect->query_first("select expneeded from db_levels where level='26'");
$level26 = $level26_stat[expneeded];
$level27_stat = $db_connect->query_first("select expneeded from db_levels where level='27'");
$level27 = $level27_stat[expneeded];
$level28_stat = $db_connect->query_first("select expneeded from db_levels where level='28'");
$level28 = $level28_stat[expneeded];
$level29_stat = $db_connect->query_first("select expneeded from db_levels where level='29'");
$level29 = $level29_stat[expneeded];
$level30_stat = $db_connect->query_first("select expneeded from db_levels where level='30'");
$level30 = $level30_stat[expneeded];

$userexp_stat = $db_connect->query_first("select userexp from db_users where username='$user_name'");
$userexp = $userexp_stat[userexp];
$userlevel_stat = $db_connect->query_first("select userlevel from db_users where username='$user_name'");
$userlevel = $userlevel_stat[userlevel];

$totalexppoints_stat = $db_connect->query_first("select totalexppoints from db_users where username='$user_name'");
$totalexppoints = $totalexppoints_stat[totalexppoints];
$tempexppoints_stat = $db_connect->query_first("select tempexppoints from db_users where username='$user_name'");
$tempexppoints = $tempexppoints_stat[tempexppoints];

if($userexp > $level1 && $totalexppoints == 0){
	$newlevel=($userlevel+1);
	$levelup='yes';
	$newtemppoints=($tempexppoints+5);
	$db_connect->query("UPDATE db_users SET totalexppoints='5', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
}

if($userexp > $level3 && $totalexppoints == 5){
	$newlevel=($userlevel+1);
	$levelup='yes';
	$db_connect->query("UPDATE db_users SET totalexppoints='10', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
}

if($userexp > $level4 && $totalexppoints == 10){
	$newlevel=($userlevel+1);
	$levelup='yes';
	$db_connect->query("UPDATE db_users SET totalexppoints='15', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
}


eval("dooutput(\"".gettemplate("leveltest")."\");");
?>