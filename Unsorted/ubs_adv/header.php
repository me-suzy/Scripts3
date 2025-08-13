<?php

$rpg_leader = $db_connect->query_first("select username from db_users where userid='1'");
$rpgleader = $rpg_leader[username];

$userhpmax2 = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
$userhpmax = $userhpmax2[hpmax];
$userhp2 = $db_connect->query_first("select userhitpoint from db_users where username='$user_name'");
$userhp = $userhp2[userhitpoint];


$room_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$room = $room_stat[userroom];

if($user_name != Guest && $room != 'lobby'){
$me3000_stat = $db_connect->query_first("select me from $room where id='1'");
$me3000 = $me3000_stat[me];
}

if($userhp < userhpmax && $userhp > 0 && $room != db_arena1 && $room != db_arena2 && $room != db_arena3 && $room != db_arena4 && $room != db_arena5 && $room != db_arena6 && $room != db_arena7 && $room != db_arena8 && $room != db_arena9 && $room != db_arena10){
	$db_connect->query("UPDATE db_users SET userhitpoint='$userhpmax' WHERE username = '$user_name'");
}

if($room != 'lobby' && $user_name == $me3000) {
	header("LOCATION: arena.php");
}

if($user_name == "$rpgleader") {
   eval ("\$ifisadmin = \"".gettemplate("isadmin")."\";");
}

$guests = $db_connect->query_first("SELECT COUNT(zeit)as anzahl FROM db_useronline WHERE userid=''");
$user = $db_connect->query_first("SELECT COUNT(zeit)as anzahl FROM db_useronline WHERE ip=''");
$useronline = $guests[anzahl] + $user[anzahl];

/* ############## user online ############## */
$result = $db_connect->query("select db_useronline.userid, username, invisible from db_useronline LEFT JOIN db_users ON (db_useronline.userid = db_users.userid) WHERE db_useronline.ip = '' ORDER BY username ASC"); 
while($row = $db_connect->fetch_array($result)) {
	if($row[invisible]) continue;
	if($user_on) $user_on .= ", ";
	$user_on .= "<a href=\"members.php?mode=profile&userid=$row[0]$session\"><font color=\"ffffff\">$row[1]</font></a>";
}
$db_connect->free_result($result);	

$anzahluser = $db_connect->query_first("SELECT COUNT(userid) FROM db_users WHERE activation='1'");
$anzahluser = $anzahluser[0];

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
$level25_stat = $db_connect->query_first("select expneeded from db_levels where level='25'");//100
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
	$db_connect->query("UPDATE db_users SET totalexppoints='5', userlevel='$newlevel' WHERE username = '$user_name'");
}

if($userexp > $level2 && $totalexppoints == 5){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='10', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level3 && $totalexppoints == 10){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='15', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level4 && $totalexppoints == 15){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='20', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level5 && $totalexppoints == 20){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='25', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level6 && $totalexppoints == 25){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level7 && $totalexppoints == 30){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level8 && $totalexppoints == 35){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level9 && $totalexppoints == 40){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level10 && $totalexppoints == 45){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level11 && $totalexppoints == 50){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level12 && $totalexppoints == 55){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level13 && $totalexppoints == 60){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level14 && $totalexppoints == 65){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level16 && $totalexppoints == 70){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level17 && $totalexppoints == 75){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level18 && $totalexppoints == 80){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level19 && $totalexppoints == 85){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level20 && $totalexppoints == 90){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level21 && $totalexppoints == 95){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level22 && $totalexppoints == 100){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level23 && $totalexppoints == 105){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level24 && $totalexppoints == 110){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level25 && $totalexppoints == 115){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level26 && $totalexppoints == 120){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level27 && $totalexppoints == 125){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level28 && $totalexppoints == 130){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level29 && $totalexppoints == 135){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

if($userexp > $level30 && $totalexppoints == 140){
	$newlevel=($userlevel+1);
	$newtemppoints=($tempexppoints+5);
	$newtotalexppoints=($totalexppoints+5);
	$newmaxhitpoints=($userhpmax+5);
	$db_connect->query("UPDATE db_users SET hpmax='$newmaxhitpoints' WHERE username = '$user_name'");
	$db_connect->query("UPDATE db_users SET totalexppoints='$newtotalexppoints', tempexppoints='$newtemppoints', userlevel='$newlevel' WHERE username = '$user_name'");
	header("LOCATION: levelup.php");
}

eval ("\$ifloggedon = \"".gettemplate("navichain")."\";");
eval ("\$header = \"".gettemplate("header")."\";");
eval ("\$footer = \"".gettemplate("footer")."\";");
?>