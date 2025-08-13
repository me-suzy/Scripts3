<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: logout.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Member's logout
#
#################################################################################################





require ("library.php");



$authlib->logout();



// clear useronline

mysql_connect($server, $db_user, $db_pass);

mysql_db_query($database, "DELETE FROM useronline WHERE ip='$ip'");

mysql_close();



if (substr(getenv('SERVER_SOFTWARE'),0,6)=="Apache") {

    header("Location: $url_to_start/main.php?status=0");

} else {

    header("Refresh: 0;url=$url_to_start/main.php?status=0");

}

exit;



?>