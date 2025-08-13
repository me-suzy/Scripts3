<?php
//////////////////////////////////////////////////////////////////
//arena.php created by Dark Wolf                                                     //
//                                                                                                         //
//Created for the RPG battle system                                              //
//                                                                                                     //
////////////////////////////////////////////////////////////
require "functions.php";
require "header.php";

if($check == 'done'){
header("LOCATION: arena.php");
}

if($validation == 'true' && $accepted == 'accept'){
   $userroom5_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
   $myuserroom = $userroom5_stat[userroom];
   $myname_stat = $db_connect->query_first("select me from $myuserroom where id='1'");
   $me = $myname_stat[me];
   $challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
   $challenger = $challenger_stat[challenger];

   $db_connect->query("UPDATE $myuserroom SET accepted='yes' WHERE id = '1'");
   $db_connect->query("UPDATE db_users SET canusetheshop='no' WHERE username = '$me'");
   $db_connect->query("UPDATE db_users SET canusetheshop='no' WHERE username = '$challenger'");
   eval("dooutput(\"".gettemplate("validistrue")."\");");
} 

if($accepted2 == 'rejected') {
   $userroom5_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
   $myuserroom = $userroom5_stat[userroom];
   $myname_stat = $db_connect->query_first("select me from $myuserroom where id='1'");
   $me = $myname_stat[me];
   $challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
   $challenger = $challenger_stat[challenger];

   $db_connect->query("UPDATE db_users  SET userroom='lobby' WHERE username = '$me'");
   $db_connect->query("UPDATE db_users  SET userroom='lobby' WHERE username = '$challenger'");
   $db_connect->query("UPDATE $myuserroom SET status='Empty' WHERE id = '1'");
eval("dooutput(\"".gettemplate("battlerejected")."\");");
}

if($action == 'new') {
eval("dooutput(\"".gettemplate("waitingforbattle")."\");");
} 
?>