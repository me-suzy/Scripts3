<?
################################################################################################
#
#  project           	: phpBazar
#  filename          	: picturedisplay.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Display Picture
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("config.php");



mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");

$query = mysql_db_query($database, "SELECT * FROM pictures WHERE picture_name='$id'") or died(mysql_error());

mysql_close();



$data = @MYSQL_RESULT($query,0,"picture_bin");

$type = @MYSQL_RESULT($query,0,"picture_type");



Header( "Content-type: $type");

echo $data;



#  End

#################################################################################################

?>