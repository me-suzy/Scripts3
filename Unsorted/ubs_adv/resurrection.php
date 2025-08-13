<?php
require "functions.php";
require "header.php";

if($action == resurrect){
$hitpoint_stat = $db_connect->query_first("select userhitpoint from db_users where username='$user_name'");
$userhitpoint = $hitpoint_stat[userhitpoint];
$maxhitpoint_stat = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
$maxhitpoint = $maxhitpoint_stat[hpmax];
$usermoney_stat = $db_connect->query_first("select usermoney from db_users where username='$user_name'");
$usermoney = $usermoney_stat[usermoney];
      
      $newhitpoints=($maxhitpoint);
      $newmoney=($usermoney/2);

      $db_connect->query("UPDATE db_users SET userhitpoint='$newhitpoints', usermoney='$newmoney' WHERE username = '$user_name'");

eval("dooutput(\"".gettemplate("resurrect")."\");");
}
?>