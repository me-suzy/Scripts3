<?

#################################################################################################

#

#  project           	: phpListPro

#  filename          	: doc/updatetables.php

#  last modified by  	: Erich Fuchs

#  e-mail            	: office@smartisoft.com

#  purpose           	: File to upgrade the Tables for phpBazar from V1.13x to V1.20

#

#################################################################################################





#  Include Configs & Variables

#################################################################################################

$returnpath="../";

require ("../config.php");



#  or use the variables below and comment out the above line ...

#$server	= "localhost";

#$database   	= "phpListPro";

#$db_user   	= "mysql_username";

#$db_pass   	= "mysql_password";



#  Start

#################################################################################################

echo "<html>\n";

echo "<head>\n";

echo "<title>phpListPro TablesUpdate</title>\n";

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n";

echo "</head>\n";



echo "<body bgcolor=\"#E2E2E2\"><font face=\"verdana\" size=\"2\">\n";



mysql_connect($server, $db_user, $db_pass) or die ("Database connect Error");

    @mysql_select_db($database) or die ("Database select Error");





#  Declaration of Functions

#################################################################################################

function altertables($v_table,$v_command,$v_field,$v_type) {

    global $database;

    $result = mysql_query("ALTER TABLE $v_table $v_command $v_field $v_type");

    if (!$result) {

	echo "Database: $database - Alteration of Table: $v_table failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";

    } else {

        echo "Database: $database - Alteration of Table: $v_table -> Field: $v_field <b>OK!</b><br>";

    }

}



function updatetables($v_table,$v_field,$v_type) {

    global $database;

    $result = mysql_query("UPDATE $v_table SET $v_field $v_type");

    if (!$result) {

	echo "Database: $database - Update of Table: $v_table failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";

    } else {

        echo "Database: $database - Update of Table: $v_table -> Fields: $v_field <b>OK!</b><br>";

    }

}





#  Main

#################################################################################################

echo "<h2>phpListPro Table Update</h2><p>"

;



$v_table	="sites";

$v_command	="CHANGE";

$v_field	="reserve1";

$v_type		="approved INT(1) NOT NULL";



altertables($v_table,$v_command,$v_field,$v_type);



$v_table	="sites";

$v_command	="CHANGE";

$v_field	="reserve2";

$v_type		="emailapproved INT(1) NOT NULL";



altertables($v_table,$v_command,$v_field,$v_type);



$v_table	="sites";

$v_command	="CHANGE";

$v_field	="site_name";

$v_type		="site_name VARCHAR(100) NOT NULL";



altertables($v_table,$v_command,$v_field,$v_type);



$v_table	="sites";

$v_command	="ADD";

$v_field	="tilt_time";

$v_type		="INT(14) NOT NULL DEFAULT '0'";



altertables($v_table,$v_command,$v_field,$v_type);



$v_table        ="sites";

$v_field        ="approved='1'";

$v_type         ="WHERE 1=1";

updatetables($v_table,$v_field,$v_type);



$v_table        ="sites";

$v_field        ="emailapproved='1'";

$v_type         ="WHERE 1=1";

updatetables($v_table,$v_field,$v_type);



#  End

#################################################################################################

echo "<br>phpListPro Tables Updated, Ready ...";

echo "</body></html>\n";

mysql_close();



?>
