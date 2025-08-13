<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: lang.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Change the Language
#
#################################################################################################



#  Include Configs & Variables

#################################################################################################

$cookietime=time()+(3600*24*356);

$userlanguage=$language;

setcookie("Language", "$userlanguage", $cookietime, "$cookiepath"); // 1 Year

include ("library.php");

$login_check = $authlib->is_logged();

mysql_connect($server, $db_user, $db_pass);

mysql_db_query($database, "UPDATE userdata SET language='$userlanguage' WHERE username='$login_check[0]'");

mysql_close();

header("Location: $url");

?>