#!/usr/local/bin/php

<?

################################################################################################

#

#  project           	: phpListPro

#  filename          	: schedule.php

#  last modified by  	: Erich Fuchs

#  e-mail            	: office@smartisoft.com

#  purpose           	: reset the list, with eg. cronjob or from command-line with php (cgi-version)

#

#################################################################################################



#  Include Configs & Variables

#################################################################################################

$returnpath="../";

require ($returnpath."config.php");





#  Start mySQL

#################################################################################################

mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");



    if ($setinactive_on_reset) {

	$sql = "UPDATE sites SET inactive='1' WHERE votes=0";

        mysql_db_query($database, $sql) or die(geterrdesc($sql));

    }

    if ($setactive_on_reset) {

	$sql = "UPDATE sites SET inactive='0' WHERE votes>0";

	mysql_db_query($database, $sql) or die(geterrdesc($sql));

    }

    // reset list

    $sql = "UPDATE sites SET votes='0',hits='0',rating='5.0'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));

    // set new resettimestamp

    $newtimestamp=time()+($days_to_reset*24*3600);

    $sql = "UPDATE variables SET resettimestamp='$newtimestamp'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));



#  The Footer-Section

#################################################################################################





// Variables Routine

$sql = "SELECT * FROM variables";

$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

$listvar = mysql_fetch_array($query);



// End Variables



echo "$list_name reset success.\n";

echo "In/Out resets every $days_to_reset days, next: ".date("M/j/Y h:i a T", $listvar[resettimestamp])."\n";



#  End mySQL

#################################################################################################

mysql_close();



?>
