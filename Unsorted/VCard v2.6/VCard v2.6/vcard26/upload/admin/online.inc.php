<?php
define('IN_VCARD', true);
$online_timeoutseconds = 300; 
$online_timestamp = time(); 
$online_timeout = $online_timestamp - $online_timeoutseconds; 
$online_phpself = $_SERVER["PHP_SELF"];
$online_ip = $session['ip'];

$insert_online = $DB_site->query("INSERT IGNORE INTO vcard_useronline VALUES ('$online_timestamp','$online_ip','$online_phpself') "); 
if(!($insert_online))
{
	print "Useronline Insert Failed > "; 
}
$delete_online = $DB_site->query(" DELETE FROM vcard_useronline WHERE timestamp<$online_timeout "); 
if(!($delete_online))
{
    print "Useronline Delete Failed > "; 
} 
$result_online = $DB_site->query("SELECT DISTINCT ip FROM vcard_useronline WHERE file='$online_phpself'"); 
if(!($result_online))
{ 
	print "Useronline Select Error > "; 
}
$usersonline = $DB_site->num_rows($result_online); 
$DB_site->free_result($result_online);

?>