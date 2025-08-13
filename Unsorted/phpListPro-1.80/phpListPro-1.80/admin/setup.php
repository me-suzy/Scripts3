<?
#################################################################################################
#
#  project           	: phpListPro
#  filename          	: ./admin/install.php
#  last modified by  	: Erich Fuchs
#  e-mail            	: office@smartisoft.com
#  purpose           	: File to install the Tables for phpListPro V1.4x
#  version           	: 1.75
#  last modified     	: 10/23/2001
#
#################################################################################################


#  Include Configs & Variables
#################################################################################################
$returnpath="../";
require ($returnpath."config.php");

#  or use the variables below and comment out the above line ...
#$server  	= "localhost";
#$db_user   	= "mysql_user";
#$db_pass   	= "mysql_pass";
#$database   	= "phpListPro";

$title		= "phpListPro Install/Update-Tool V1.40";
$sql_file	= "phpListPro.sql";


#  Function-Declaration
#################################################################################################

function split_sql($sql) {
    $sql = trim($sql);
    $sql = ereg_replace("#[^\n]*\n", "", $sql);
    $buffer = array();
    $ret = array();
    $in_string = false;

    for($i=0; $i<strlen($sql)-1; $i++) {
	if($sql[$i] == ";" && !$in_string) {
            $ret[] = substr($sql, 0, $i);
            $sql = substr($sql, $i + 1);
            $i = 0;
        }
        if($in_string && ($sql[$i] == $in_string) && $buffer[0] != "\\") {
             $in_string = false;
        } elseif(!$in_string && ($sql[$i] == "\"" || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
             $in_string = $sql[$i];
        }
        if(isset($buffer[1])) {
            $buffer[0] = $buffer[1];
        }
        $buffer[1] = $sql[$i];
     }
    if(!empty($sql)) {
        $ret[] = $sql;
    }
    return($ret);
}

function installdb() {
    global $database,$sql_file;

    mysql_create_db($database) or die ("ERROR: ".mysql_error());
    mysql_select_db($database) or die ("ERROR: ".mysql_error());

    $sql_query = addslashes(fread(fopen($sql_file, "r"), filesize($sql_file)));
    $pieces  = split_sql($sql_query);

    if (count($pieces) == 1 && !empty($pieces[0])) {
	echo "Error !!!";
    }

    for ($i=0; $i<count($pieces); $i++) {
	$pieces[$i] = stripslashes(trim($pieces[$i]));
        if(!empty($pieces[$i]) && $pieces[$i] != "#") {
	    $result = mysql_query ($pieces[$i]);
	    if (!$result) {
		echo "Database: [$database] - MYSQL-ERROR: ".mysql_error()."<br>Command: ".stripslashes($pieces[$i])."<br>";
    	    } else {
		echo "Database: [$database] - mySQL-command: <b>OK!</b><br>";
	    }
        }
    }
    echo "<br><b>phpListpro Tables installed, Ready ...</b>";
}

function altertables($v_table,$v_command,$v_field,$v_type) {
    global $database;
    $result = mysql_query("ALTER TABLE $v_table $v_command $v_field $v_type");
    if (!$result) {
	echo "Database: [$database] - Alteration of Table: $v_table failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$database] - Alteration of Table: $v_table -> Field: $v_field <b>OK!</b><br>";
    }
}

function updatetables($v_table,$v_field,$v_type) {
    global $database;
    $result = mysql_query("UPDATE $v_table SET $v_field $v_type");
    if (!$result) {
	echo "Database: [$database] - Update of Table: $v_table failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$database] - Update of Table: $v_table -> Fields: $v_field <b>OK!</b><br>";
    }
}

function suppr($file) {
    $delete = @unlink($file);
    if (@file_exists($file)) {
	$filesys = eregi_replace("/","\\",$file);
	$delete = @system("del $filesys");
	if (@file_exists($file)) {
	    $delete = @chmod ($file, 0775);
	    $delete = @unlink($file);
	    $delete = @system("del $filesys");
	}
    }
}

#  Start
#################################################################################################
@set_time_limit(1000);
$timestamp=time();

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    			// Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  	// always modified
header ("Cache-Control: no-cache, must-revalidate");  			// HTTP/1.1
header ("Pragma: no-cache");                          			// HTTP/1.0

echo "<html>\n";
echo "<head>\n";
echo "<title>$title</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n";
echo "</head>\n";

echo "<body bgcolor=\"#E2E2E2\"><font face=\"verdana\" size=\"2\">\n";
echo "<h3>$title</h3>";

@mysql_connect($server, $db_user, $db_pass);

if (mysql_select_db($database)) {

    @mysql_close();
    mysql_connect($server, $db_user, $db_pass) or die ("Database connect Error");
    mysql_select_db($database);

    if ($action=="up1") {
	echo "<b>phpListPro Table Update from Version 1.xx to Version 1.20</b><p>"

;

	$v_table	="ads";
	$v_command	="ADD";
	$v_field	="field11";
	$v_type		="VARCHAR(50) NOT NULL AFTER field10";
	

altertables($v_table,$v_command,$v_field,$v_type);


    } elseif ($action=="del") {

	mysql_drop_db($database);
        echo "Database: [$database] - deleted !!!<br>";
	installdb();


    } else {

	echo "Database [$database] does exist !!!<p>";
	echo "<table width=\"400\" border=\"1\"><tr><td><center><br>";
#        echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"up1\"><input type=\"submit\" value=\"UPDATE DB from Version 1.xx to 1.20\"></form>";
	echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"del\"><input type=\"submit\" value=\"DELETE DB install NEW Version 1.4X\"></form>";
	echo "</td></tr></table></center>\n";
    }
    echo "<br><br><input type=\"submit\" value=\"HOME\" onclick=\"javascript:window.location.href='$PHP_SELF'\">&nbsp;<input type=\"submit\" value=\"ADMIN\" onclick=\"javascript:window.location.href='admin.php'\">&nbsp;<input type=\"submit\" value=\"MEMBERAREA\" onclick=\"javascript:window.location.href='http://www.smartisoft.com/listpromember'\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" value=\"EXIT\" onclick=\"javascript:window.close()\">";
    mysql_close();

} else {

    @mysql_close();

    if ($action=="inst" && $email && $bill) {

        mysql_connect($server, $db_user, $db_pass) or die ("Database connect Error");
        echo "<b>phpListPro Database/Table install</b><p>"
;
	installdb();
        mysql_close();

    } else {

	echo "Database [$database] does NOT exist !!!<p>";
        echo "<form method=\"POST\" action=\"$PHP_SELF\">\n";

	echo "<table width=\"400\">\n";
	echo "<tr><td colspan=2><font face=\"verdana\" size=\"2\">PLEASE Enter your license Data !<br><br></font></td></tr>\n";
	echo "<tr><td><font face=\"verdana\" size=\"2\">Order#: </font></td><td width=280><input type=\"text\" name=\"bill\" value=\"$bill\" size=\"10\">\n";
	if ($action=="inst" && !$bill) {echo "<font face=\"verdana\" size=\"2\"><b>EMPTY FIELD !</b></font>";}
	echo "	<br></td></tr>\n";
	echo "<tr><td><font face=\"verdana\" size=\"2\">E-Mail: </font></td><td><input type=\"text\" name=\"email\" value=\"$email\" size=\"20\">\n";
	if ($action=="inst" && !$email) {echo "<font face=\"verdana\" size=\"2\"><b>EMPTY FIELD !</b></font>";}
	echo "	<br></td></tr>\n";
	echo "<tr><td colspan=2><font face=\"verdana\" size=\"2\"><br>Check your mySQL Data (edit config.php)<br><br></font></td></tr>\n";
	echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-Server: </font></td><td><input type=\"text\" name=\"mysqlserver\" value=\"$server\" size=\"20\" readonly><br></td></tr>\n";
	echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-User: </font></td><td><input type=\"text\" name=\"mysqluser\" value=\"$db_user\" size=\"20\" readonly><br></td></tr>\n";
	echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-Pass: </font></td><td><input type=\"text\" name=\"mysqlpass\" value=\"$db_pass\" size=\"20\" readonly><br></td></tr>\n";
	echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-DB: </font></td><td><input type=\"text\" name=\"mysqldb\" value=\"$database\" size=\"20\" readonly><br></td></tr>\n";

        echo "<tr><td colspan=2><hr><center><input type=\"hidden\" name=\"action\" value=\"inst\"><input type=\"submit\" value=\"INSTALL DB V1.40 new\"></form>";
	echo "</center></td></tr></table>\n";
    }
    echo "<br><br><input type=\"submit\" value=\"HOME\" onclick=\"javascript:window.location.href='$PHP_SELF'\">&nbsp;<input type=\"submit\" value=\"EXIT\" onclick=\"javascript:window.close()\">";

}


#  End
#################################################################################################

echo "</body></html>\n";

?>
