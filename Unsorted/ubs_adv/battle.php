<?php
//////////////////////////////////////////////////////////////////
//Battle.php created by Dark Wolf                                                     //
//                                                                                                         //
//Created for the RPG battle system                                              //
//                                                                                                     //
////////////////////////////////////////////////////////////
require "functions.php";
require "header.php";

$arena1_stat = $db_connect->query_first("select status from db_arena1 where id='1'");
$room1status = $arena1_stat[status];
$arena2_stat = $db_connect->query_first("select status from db_arena2 where id='1'");
$room2status = $arena2_stat[status];
$arena3_stat = $db_connect->query_first("select status from db_arena3 where id='1'");
$room3status = $arena3_stat[status];
$arena4_stat = $db_connect->query_first("select status from db_arena4 where id='1'");
$room4status = $arena4_stat[status];
$arena5_stat = $db_connect->query_first("select status from db_arena5 where id='1'");
$room5status = $arena5_stat[status];
$arena6_stat = $db_connect->query_first("select status from db_arena6 where id='1'");
$room6status = $arena6_stat[status];
$arena7_stat = $db_connect->query_first("select status from db_arena7 where id='1'");
$room7status = $arena7_stat[status];
$arena8_stat = $db_connect->query_first("select status from db_arena8 where id='1'");
$room8status = $arena8_stat[status];
$arena9_stat = $db_connect->query_first("select status from db_arena9 where id='1'");
$room9status = $arena9_stat[status];
$arena10_stat = $db_connect->query_first("select status from db_arena10 where id='1'");
$room10status = $arena10_stat[status];
$rpg_leader = $db_connect->query_first("select username from db_users where userid='1'");
$rpgleader = $rpg_leader[username];

if($user_name == $rpgleader){
$ifisadmin1="<a href=\"open.php?room=1\"><font color=\"ffffff\">Open</font></a>";
$ifisadmin2="<a href=\"open.php?room=2\"><font color=\"ffffff\">Open</font></a>";
$ifisadmin3="<a href=\"open.php?room=3\"><font color=\"ffffff\">Open</font></a>";
$ifisadmin4="<a href=\"open.php?room=4\"><font color=\"ffffff\">Open</font></a>";
$ifisadmin5="<a href=\"open.php?room=5\"><font color=\"ffffff\">Open</font></a>";
$ifisadmin6="<a href=\"open.php?room=6\"><font color=\"ffffff\">Open</font></a>";
$ifisadmin7="<a href=\"open.php?room=7\"><font color=\"ffffff\">Open</font></a>";
$ifisadmin8="<a href=\"open.php?room=8\"><font color=\"ffffff\">Open</font></a>";
$ifisadmin9="<a href=\"open.php?room=9\"><font color=\"ffffff\">Open</font></a>";
$ifisadmin10="<a href=\"open.php?room=10\"><font color=\"ffffff\">Open</font></a>";
}

if($user_name == Guest){
        header("LOCATION: index.php");
        exit;
}

if($action == "enter"){
if($userroom == 'db_arena1'){
	$roomstatus=$room1status;
}
if($userroom == 'db_arena2'){
	$roomstatus=$room2status;
}
if($userroom == 'db_arena3'){
	$roomstatus=$room3status;
}
if($userroom == 'db_arena4'){
	$roomstatus=$room4status;
}
if($userroom == 'db_arena5'){
	$roomstatus=$room5status;
}
if($userroom == 'db_arena6'){
	$roomstatus=$room6status;
}
if($userroom == 'db_arena7'){
	$roomstatus=$room7status;
}
if($userroom == 'db_arena8'){
	$roomstatus=$room8status;
}
if($userroom == 'db_arena9'){
	$roomstatus=$room9status;
}
if($userroom == 'db_arena10'){
	$roomstatus=$room10status;
}

$Player2 = $db_connect->query_first("select userhitpoint from db_users where username='$challenger'");

	if($roomstatus == 'Closed'){
  		$entererror="-> The room you have selected is closed please choose another.";
		header("LOCATION: battle.php?entererror=$entererror");
	}

	if($Player2[userhitpoint] <= 0){
		$entererror="-> Player is dead. you cannot battle them.";
		header("LOCATION: battle.php?entererror=$entererror");
		exit;
	}

	if($roomstatus == 'Empty'){
      $db_connect->query("UPDATE $userroom SET me='$user_name', challenger='$challenger', battlemusic='$mybattlemusic', whosturn='myturn', accepted='wait', messages=' ' WHERE id = '1'");
      $db_connect->query("UPDATE db_users  SET userroom='$userroom' WHERE username = '$user_name'");
      $db_connect->query("UPDATE db_users  SET userroom='$userroom' WHERE username = '$challenger'");
      $db_connect->query("UPDATE $userroom SET status='Closed' WHERE id = '1'");
      header("LOCATION: entrancecheck.php?action=new");
	}
}

   $user = $db_connect->query_first("SELECT * FROM db_users WHERE userid='$user_id'");
		
		if($userroom==db_arena1) $userroom[1] = "selected";
                                if($userroom==db_arena2) $userroom[2] = "selected";
                                if($userroom==db_arena3) $userroom[3] = "selected";
                                if($userroom==db_arena4) $userroom[4] = "selected";
                                if($userroom==db_arena5) $userroom[5] = "selected";
                                if($userroom==db_arena6) $userroom[6] = "selected";
                                if($userroom==db_arena7) $userroom[7] = "selected";
                                if($userroom==db_arena8) $userroom[8] = "selected";
                                if($userroom==db_arena9) $userroom[9] = "selected";
                                if($userroom==db_arena10) $userroom[10] = "selected";


eval("dooutput(\"".gettemplate("battle_entrance")."\");");
?>