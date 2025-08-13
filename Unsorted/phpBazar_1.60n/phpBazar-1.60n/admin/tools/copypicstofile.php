<?

################################################################################################
#
#  project           	: phpBazar
#  filename          	: picturedisplay.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Copy Pictures from mySQL-DB to Files
#
#################################################################################################



       ####################################################################################

       ### !!! COPY THIS FILE TO YOUR BAZAR-DIR - AFTER SUCCESSFULL RUN - DELETE IT !!! ###

       ####################################################################################



#  Include Configs & Variables

#################################################################################################

require ("config.php");

#$filepath="/tmp/pics";			// set FULL Path to your pictures dir

$filepath="$bazar_dir/$pic_path";	// or use config.php settings



mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");



$result = mysql_db_query($database, "SELECT * FROM pictures") or die(mysql_error());

while ($db=mysql_fetch_array($result)) {

    $query = mysql_db_query($database, "SELECT * FROM pictures WHERE picture_name='$db[picture_name]'") or die(mysql_error());

    $data = @MYSQL_RESULT($query,0,"picture_bin");

    $type = @MYSQL_RESULT($query,0,"picture_type");

#Header( "Content-type: $type");

#echo $data;

    $filename = "$filepath/$db[picture_name]";

    $fd = fopen ($filename, "w");

    fwrite ($fd, $data);

    fclose ($fd);

    echo "Picture: $db[picture_name] converted to file <br>\n";

}

mysql_close();





#  End

#################################################################################################

?>