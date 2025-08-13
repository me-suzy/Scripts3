<?
require "functions.php";

$rpg_leader = $db_connect->query_first("select username from db_users where userid='1'");
$rpgleader = $rpg_leader[username];

if($user_name == $rpgleader && $room == 1){
$validname_stat2 = $db_connect->query_first("select me from db_arena1 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from db_arena1 where id='1'");
$challenger = $challenger_stat[challenger];
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$challenger'");
$db_connect->query("UPDATE db_arena1 SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("quickmod")."\");");
}

if($user_name == $rpgleader && $room == 2){
$validname_stat2 = $db_connect->query_first("select me from db_arena2 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from db_arena2 where id='1'");
$challenger = $challenger_stat[challenger];
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$challenger'");
$db_connect->query("UPDATE db_arena2 SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("quickmod")."\");");
}

if($user_name == $rpgleader && $room == 3){
$validname_stat2 = $db_connect->query_first("select me from db_arena3 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from db_arena3 where id='1'");
$challenger = $challenger_stat[challenger];
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$challenger'");
$db_connect->query("UPDATE db_arena3 SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("quickmod")."\");");
}

if($user_name == $rpgleader && $room == 4){
$validname_stat2 = $db_connect->query_first("select me from db_arena4 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from db_arena4 where id='1'");
$challenger = $challenger_stat[challenger];
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$challenger'");
$db_connect->query("UPDATE db_arena4 SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("quickmod")."\");");
}

if($user_name == $rpgleader && $room == 5){
$validname_stat2 = $db_connect->query_first("select me from db_arena5 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from db_arena5 where id='1'");
$challenger = $challenger_stat[challenger];
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$challenger'");
$db_connect->query("UPDATE db_arena5 SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("quickmod")."\");");
}

if($user_name == $rpgleader && $room == 6){
$validname_stat2 = $db_connect->query_first("select me from db_arena6 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from db_arena6 where id='1'");
$challenger = $challenger_stat[challenger];
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$challenger'");
$db_connect->query("UPDATE db_arena6 SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("quickmod")."\");");
}

if($user_name == $rpgleader && $room == 7){
$validname_stat2 = $db_connect->query_first("select me from db_arena7 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from db_arena7 where id='1'");
$challenger = $challenger_stat[challenger];
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$challenger'");
$db_connect->query("UPDATE db_arena7 SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("quickmod")."\");");
}

if($user_name == $rpgleader && $room == 8){
$validname_stat2 = $db_connect->query_first("select me from db_arena8 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from db_arena8 where id='1'");
$challenger = $challenger_stat[challenger];
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$challenger'");
$db_connect->query("UPDATE db_arena8 SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("quickmod")."\");");
}

if($user_name == $rpgleader && $room == 9){
$validname_stat2 = $db_connect->query_first("select me from db_arena9 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from db_arena9 where id='1'");
$challenger = $challenger_stat[challenger];
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$challenger'");
$db_connect->query("UPDATE db_arena9 SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("quickmod")."\");");
}

if($user_name == $rpgleader && $room == 10){
$validname_stat2 = $db_connect->query_first("select me from db_arena10 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from db_arena10 where id='1'");
$challenger = $challenger_stat[challenger];
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes'  WHERE username = '$challenger'");
$db_connect->query("UPDATE db_arena10 SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("quickmod")."\");");
}

?>