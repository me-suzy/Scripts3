<?php
include("../stconfig.php");
include("global.php");

if($access[cangalleries]=="1"){
## Delete Banned words, url or email
if ($action=="deletebannedurl"){
$delbanned = mysql_db_query($DBname, "DELETE FROM st_banned WHERE bid='$bid'");}

## Delete HTTP_Referrers
if ($action=="deletelink"){
$Deletelink = mysql_db_query($DBname, "DELETE FROM st_links WHERE linkid=$linkid");
?>
<font size="3" face="arial"><b>Link Deleted</b></font>
<meta http-equiv ="Refresh" content = "4 ; URL=index.php">
<?php 
exit;}
}


if($access[canstats]="1"){
## Delete website refferals under 5 count
if ($action=="removeunder5"){
$Deleterefs = mysql_db_query($DBname, "DELETE FROM st_ref WHERE counter < 5");}}


if($access[canadmin]=="1"){
## Delete admin account
if($action=="deleteadmin"){
$Deleteadmin = mysql_db_query($DBname, "DELETE FROM st_admin WHERE id='$id'");}

## Delete group settings
if($action=="deletegroup"){
$Deletegroup = mysql_db_query($DBname, "DELETE FROM st_admin_group WHERE usergroupid='$usergroupid'");}
}

?>
<meta http-equiv ="Refresh" content = "0 ; URL=<?=$HTTP_REFERER?>">