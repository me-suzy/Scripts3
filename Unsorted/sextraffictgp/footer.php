<?php
$time = date ( "Y-m-d" );
if(@mysql_fetch_array(@mysql_query("SELECT date FROM st_counter2 WHERE date='$time'"))) {
@mysql_query("UPDATE st_counter2 set count=count+1 WHERE date='$time'");}
else { mysql_query("INSERT INTO st_counter2 SET date=NOW(), count='1'");}

// Site counter
mysql_query("UPDATE st_counter set count=count+1");

// Refer links
$refer = getenv("HTTP_REFERER");
$host = getenv("HTTP_HOST");
if(empty($refer)) {
  $refer = "Direct Request";}
if(empty($host)) {
  $host = "localhost";}
if(!eregi("$host",$refer)) {
  if(@mysql_fetch_array(@mysql_query("SELECT counter FROM st_ref WHERE site='$refer'"))) {
    @mysql_query("UPDATE st_ref SET counter=counter+1 WHERE site='$refer'");
  }
  else {
    @mysql_query("INSERT INTO st_ref VALUES ('1','$refer')");
  }
}
?>