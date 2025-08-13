<?
require "functions.php";

$userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$myuserroom = $userroom_stat2[userroom];
$validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
$isvalidname = $validname_stat2[me];
$challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
$challenger = $challenger_stat[challenger];

$ride="arena.php?duringbattle=yes&myuserroom=$myuserroom&isvalidname=$user_name";

eval("dooutput(\"".gettemplate("wait")."\");");
?>