<?
require "functions.php";

$userroom2000_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$myuserroom2000 = $userroom2000_stat2[userroom];
$userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$myuserroom = $userroom_stat2[userroom];
$validname_stat2 = $db_connect->query_first("select me from $myuserroom2000 where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from $myuserroom2000 where id='1'");
$challenger = $challenger_stat[challenger];

if($myuserroom2000 == lobby){
   eval("dooutput(\"".gettemplate("nobattle")."\");");
}

if($duringbattle == 'no' && $user_name == $me){

$userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$myuserroom = $userroom_stat2[userroom];
$validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
$challenger = $challenger_stat[challenger];
$user = $db_connect->query_first("select * from db_users where username='$me'");
$user2 = $db_connect->query_first("select * from db_users where username='$challenger'");

$ride='index.php';

$winnersexp=($user[userexp]+500+($user[userlevel]/2));
$winnersmoney=($user[usermoney]+1000+$user[userlevel]);
$db_connect->query("UPDATE db_users SET userexp='$winnersexp', usermoney='$winnersmoney', canusetheshop='yes', userroom='lobby' WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes' WHERE username = '$challenger'");
$db_connect->query("UPDATE $myuserroom SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("imawinner")."\");");
}

if($duringbattle == 'no' && $user_name == $challenger){

$userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$myuserroom = $userroom_stat2[userroom];
$validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
$challenger = $challenger_stat[challenger];
      $user = $db_connect->query_first("select * from db_users where username='$me'");
      $user2 = $db_connect->query_first("select * from db_users where username='$challenger'");

$ride='index.php';

$winnersexp=($user2[userexp]+500+($user2[userlevel]/2));
$winnersmoney=($user2[usermoney]+1000+$user2[userlevel]);
$db_connect->query("UPDATE db_users SET userexp='$winnersexp', usermoney='$winnersmoney', canusetheshop='yes', userroom='lobby' WHERE username = '$challenger'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes' WHERE username = '$isvalidname'");
$db_connect->query("UPDATE $myuserroom SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("yourawinner")."\");");
}

if($action == escape){
$userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$myuserroom = $userroom_stat2[userroom];
$validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
$me = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
$challenger = $challenger_stat[challenger];
$user = $db_connect->query_first("select * from db_users where username='$me'");
$user2 = $db_connect->query_first("select * from db_users where username='$challenger'");

$challenger_hp_stat = $db_connect->query_first("select userhitpoint from db_users where username='$challenger'");
$challenger_hp = $challenger_hp_stat[userhitpoint];

$me_hp_stat = $db_connect->query_first("select userhitpoint from db_users where username='$me'");
$me_hp = $me_hp_stat[userhitpoint];
if($me_hp_stat > $challenger_hp_stat){
   $thewinner=$methewinner;
}

if($challenger_hp_stat > $me_hp_stat){
   $thewinner=$challengerthewinner;
}

$theonewhoran = $whoran;

$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes' WHERE username = '$me'");
$db_connect->query("UPDATE db_users SET userroom='lobby', canusetheshop='yes' WHERE username = '$challenger'");
$db_connect->query("UPDATE $myuserroom SET whosturn='0', status='Empty', me='none', challenger='none', battlemusic='none'  WHERE id = '1'");
eval("dooutput(\"".gettemplate("escape")."\");");
}
?>