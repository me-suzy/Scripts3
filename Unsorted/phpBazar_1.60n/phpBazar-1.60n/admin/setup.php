<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: ./admin/install.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: File to install the Tables for phpBazar V1.60
#  version           	: 1.60
#  last modified     	: 10/26/2002
#
#################################################################################################


#  Include Configs & Variables
#################################################################################################
require ("../config.php");

$title		= "phpBazar Install/Update-Tool nullified by WTN Team";
$sql_file	= "phpBazar.sql";

if (is_file("../sales_config.php")) {include ("../sales_config.php");}
if (is_file("../picturelib_config.php")) {include ("../picturelib_config.php");}

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
    echo "<br><b>phpBazar Tables installed, Ready ...</b>";
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
	echo "<b>phpBazar Table Update from Version 1.xx to Version 1.20</b><p>"

;

	$v_table	="ads";
	$v_command	="ADD";
	$v_field	="field11";
	$v_type		="VARCHAR(50) NOT NULL AFTER field10";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field12";
	$v_type		="VARCHAR(50) NOT NULL AFTER field11";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field13";
	$v_type		="VARCHAR(50) NOT NULL AFTER field12";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field14";
	$v_type		="VARCHAR(50) NOT NULL AFTER field13";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field15";
	$v_type		="VARCHAR(50) NOT NULL AFTER field14";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field16";
	$v_type		="VARCHAR(50) NOT NULL AFTER field15";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field17";
	$v_type		="VARCHAR(50) NOT NULL AFTER field16";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field18";
	$v_type		="VARCHAR(50) NOT NULL AFTER field17";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field19";
	$v_type		="VARCHAR(50) NOT NULL AFTER field18";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field20";
	$v_type		="VARCHAR(50) NOT NULL AFTER field19";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="sfield";
	$v_type		="VARCHAR(50) NOT NULL AFTER picture";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="publicview";
	$v_type		="SMALLINT(1) NOT NULL DEFAULT 0";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_command	="CHANGE";
	$v_field	="text";
	$v_type		="text TEXT NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_command	="CHANGE";
	$v_field	="adipaddr";
	$v_type		="ip VARCHAR(40) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="adcat";
	$v_command	="ADD";
	$v_field	="field11";
	$v_type		="VARCHAR(50) NOT NULL AFTER field10";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field12";
	$v_type		="VARCHAR(50) NOT NULL AFTER field11";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field13";
	$v_type		="VARCHAR(50) NOT NULL AFTER field12";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field14";
	$v_type		="VARCHAR(50) NOT NULL AFTER field13";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field15";
	$v_type		="VARCHAR(50) NOT NULL AFTER field14";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field16";
	$v_type		="VARCHAR(50) NOT NULL AFTER field15";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field17";
	$v_type		="VARCHAR(50) NOT NULL AFTER field16";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field18";
		$v_type		="VARCHAR(50) NOT NULL AFTER field17";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field19";
	$v_type		="VARCHAR(50) NOT NULL AFTER field18";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field20";
	$v_type		="VARCHAR(50) NOT NULL AFTER field19";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="sfield";
	$v_type		="VARCHAR(50) NOT NULL AFTER picture";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_command	="DROP";
	$v_field	="field1tbl";
	$v_type		="";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field2tbl";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field3tbl";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field4tbl";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field5tbl";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field6tbl";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field7tbl";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field8tbl";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field9tbl";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field10tbl";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="banned_ips";
	$v_command	="CHANGE";
	$v_field	="banned_ip";
	$v_type		="ip VARCHAR(40) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="guestbook";
	$v_command	="CHANGE";
	$v_field	="ip";
	$v_type		="ip VARCHAR(40) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="userdata";
	$v_command	="ADD";
	$v_field	="homepage";
	$v_type		="VARCHAR(50) NOT NULL AFTER icq";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="ads";
	$v_field	="publicview='1'";
	$v_type		="WHERE publicview=0";


updatetables($v_table,$v_field,$v_type);

	$v_field	="adeditdate=addate ";
	$v_type		="WHERE adeditdate LIKE '0000%'";


updatetables($v_table,$v_field,$v_type);

	$result = mysql_query("CREATE TABLE favorits (userid INT(11) NOT NULL, adid INT(11) NOT NULL)");
	if (!$result) {
	    echo "Database: [$database] - Creation Table: favorits failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Creation Table: favorits <b>OK!</b><br>";
	}

	$result = mysql_query("CREATE TABLE useronline (timestamp INT(11) NOT NULL, ip VARCHAR(40) NOT NULL, file VARCHAR(50) NOT NULL)");
	if (!$result) {
	    echo "Database: [$database] - Creation Table: useronline failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Creation Table: useronline <b>OK!</b><br>";
	}

	echo "<br><b>phpBazar Tables Updated, Ready ...</b>";

    } elseif ($action=="up2") {
	echo "<b>phpBazar Table Update from Version 1.3x to Version 1.40</b><p>"

;

	$v_table	="adsubcat";
	$v_command	="ADD";
	$v_field	="notify";
	$v_type		="INT(1) DEFAULT '0' NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="votes";
	$v_command	="ADD";
	$v_field	="id";
	$v_type		="INT(1) DEFAULT '0' NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="ads";
	$v_command	="ADD";
	$v_field	="_picture2";
	$v_type		="VARCHAR(50) NOT NULL AFTER picture";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="picture2";
	$v_type		="VARCHAR(50) NOT NULL AFTER _picture2";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="_picture3";
	$v_type		="VARCHAR(50) NOT NULL AFTER picture2";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="picture3";
	$v_type		="VARCHAR(50) NOT NULL AFTER _picture3";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="viewed";
	$v_type		="INT(14) DEFAULT '0' NOT NULL AFTER subcatid";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="answered";
	$v_type		="INT(14) DEFAULT '0' NOT NULL AFTER viewed";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="rating";
	$v_type		="INT(1) DEFAULT '0' NOT NULL AFTER answered";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="ratingcount";
	$v_type		="INT(5) DEFAULT '0' NOT NULL AFTER rating";


altertables($v_table,$v_command,$v_field,$v_type);


	$v_field	="durationdays";
	$v_type		="INT(5) DEFAULT '0' NOT NULL AFTER duration";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="deleted";
	$v_type		="INT(1) DEFAULT '0' NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="timeoutnotify";
	$v_type		="VARCHAR(32) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="timeoutdays";
	$v_type		="INT(10) DEFAULT '0' NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$result = mysql_query("UPDATE ads SET durationdays=duration*7");
	if (!$result) {
	    echo "Database: [$database] - Durationdays Field : failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Durationdays Field : converted <b>OK!</b><br>";
	}

	$result = mysql_query("UPDATE userdata SET sex='m' WHERE sex='male'");
	if (!$result) {
	    echo "Database: [$database] - Sex Field male: failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Sex Field male: converted <b>OK!</b><br>";
	}

	$result = mysql_query("UPDATE userdata SET sex='f' WHERE sex='female'");
	if (!$result) {
	    echo "Database: [$database] - Sex Field female: failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Sex Field female: converted <b>OK!</b><br>";
	}


	$result = mysql_query("CREATE TABLE notify (userid INT(11) NOT NULL, subcatid INT(5) NOT NULL)");
	if (!$result) {
	    echo "Database: [$database] - Creation Table: notify failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Creation Table: notify <b>OK!</b><br>";
	}


	$result = mysql_query("CREATE TABLE pictures (picture_name VARCHAR(50) NOT NULL, picture_type VARCHAR(10) NOT NULL, picture_height VARCHAR(10) NOT NULL, picture_width VARCHAR(10) NOT NULL, picture_size VARCHAR(10) NOT NULL, picture_bin MEDIUMBLOB NOT NULL)");

	if (!$result) {
	    echo "Database: [$database] - Creation Table: pictures failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Creation Table: pictures <b>OK!</b><br>";
	}

	$count=1;
	$result = mysql_query("SELECT * FROM votes");
	while ($db = mysql_fetch_array($result)) {
	    $result2 = mysql_query("UPDATE votes SET id='$count' WHERE name='$db[name]'");
	    $count++;
	}
	echo "Database: [$database] - Table: votes <b>OK!</b><br>";

	echo "<br><b>phpBazar Tables Updated, Ready ...</b>";

    } elseif ($action=="up3") {
	echo "<b>phpBazar Table Update from Version 1.4x to Version 1.5x</b><p>"

;

	$v_table	="userdata";
	$v_command	="ADD";
	$v_field	="field1";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field2";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field3";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field4";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field5";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field6";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field7";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field8";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field9";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field10";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="language";
	$v_type		="VARCHAR(2) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="useronline";
	$v_command	="ADD";
	$v_field	="username";
	$v_type		="VARCHAR(25) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_command	="CHANGE";
	$v_field	="timestamp";
	$v_type		="timestamp INT(14) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="ads";
	$v_command	="ADD";
	$v_field	="attachment1";
	$v_type		="VARCHAR(50) NOT NULL AFTER picture3";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="attachment2";
	$v_type		="VARCHAR(50) NOT NULL AFTER attachment1";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="attachment3";
	$v_type		="VARCHAR(50) NOT NULL AFTER attachment2";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_command	="CHANGE";
	$v_field	="rating";
	$v_type		="rating DOUBLE(5,2) DEFAULT '5.0' NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="confirm";
	$v_command	="ADD";
	$v_field	="newsletter";
	$v_type		="VARCHAR(5) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="firstname";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="lastname";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="address";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="zip";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="city";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="state";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="country";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="phone";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="cellphone";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="icq";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="homepage";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="hobbys";
	$v_type		="VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field1";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field2";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field3";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field4";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field5";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field6";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field7";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field8";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field9";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_field	="field10";
	$v_type		="VARCHAR(255) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="userdata";
	$v_command	="ADD";
	$v_field	="registered";
	$v_type		="VARCHAR(14) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_command	="CHANGE";
	$v_field	="adress";
	$v_type		="address VARCHAR(50) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_command	="CHANGE";
	$v_field	="newsletter";
	$v_type		="newsletter VARCHAR(5) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="adcat";
	$v_command	="ADD";
	$v_field	="passphrase";
	$v_type		="VARCHAR(32) NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$result = mysql_query("UPDATE ads SET rating='5.0' WHERE rating='0.0'");
	if (!$result) {
	    echo "Database: [$database] - ads Field rating: failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - ads Field rating: converted <b>OK!</b><br>";
	}

	$result = mysql_query("UPDATE ads SET ratingcount='1' WHERE ratingcount='0'");
	if (!$result) {
	    echo "Database: [$database] - ads Field ratingcount: failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - ads Field ratingcount: converted <b>OK!</b><br>";
	}

	$result = mysql_query("CREATE TABLE rating (type VARCHAR(3) NOT NULL, id INT(11) NOT NULL, ip VARCHAR(40) NOT NULL, userid INT(11) NOT NULL, ratedate DATETIME NOT NULL)");
	if (!$result) {
	    echo "Database: [$database] - Creation Table: rating failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Creation Table: rating <b>OK!</b><br>";
	}

	$result = mysql_query("CREATE TABLE logging (timestamp INT(14) NOT NULL, userid INT(11) NOT NULL, username VARCHAR(25) NOT NULL, event VARCHAR(50) NOT NULL, ext VARCHAR(250) NOT NULL,ip VARCHAR(40) NOT NULL, ipname VARCHAR(50) NOT NULL, client VARCHAR(100) NOT NULL)");
	if (!$result) {
	    echo "Database: [$database] - Creation Table: logging failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Creation Table: logging <b>OK!</b><br>";
	}

	$result = mysql_query("CREATE TABLE sessions (id VARCHAR(32) NOT NULL, userid INT(11) NOT NULL, username VARCHAR(25) NOT NULL,mod INT(1) NOT NULL,sessiondate DATETIME NOT NULL)");
	if (!$result) {
	    echo "Database: [$database] - Creation Table: sessions failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Creation Table: sessions <b>OK!</b><br>";
	}

	$result = mysql_query("CREATE TABLE webmail (id int(11) NOT NULL auto_increment,fromid int(11) DEFAULT '0' NOT NULL, fromname VARCHAR(25) NOT NULL ,fromemail VARCHAR(50) NOT NULL, toid int(11) DEFAULT '0' NOT NULL, toname VARCHAR(25) NOT NULL , toemail VARCHAR(50) NOT NULL, viewed int(1) DEFAULT '0' NOT NULL,answered int(1) DEFAULT '0' NOT NULL,timestamp int(14) DEFAULT '0' NOT NULL,ip varchar(40) NOT NULL,subject varchar(255) NOT NULL,text text NOT NULL,deleted int(1) DEFAULT '0' NOT NULL,attachment1 varchar(50) NOT NULL,attachment2 varchar(50) NOT NULL,attachment3 varchar(50) NOT NULL,PRIMARY KEY (id))");
	if (!$result) {
	    echo "Database: [$database] - Creation Table: webmail failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Creation Table: webmail <b>OK!</b><br>";
	}

	$result = mysql_query("CREATE TABLE config (id int(11) NOT NULL auto_increment ,type VARCHAR(32) NOT NULL, name VARCHAR(32) NOT NULL, value VARCHAR(255) NOT NULL, value2 VARCHAR(255) NOT NULL, value3 VARCHAR(255) NOT NULL, value4 VARCHAR(255) NOT NULL, value5 VARCHAR(255) NOT NULL, value6 VARCHAR(255) NOT NULL, PRIMARY KEY (id))");
	if (!$result) {
	    echo "Database: [$database] - Creation Table: config failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Creation Table: config <b>OK!</b><br>";

	    mysql_query("INSERT INTO config VALUES ('', 'member', 'sex', 'yes', 'yes', '', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'newsletter', 'yes', 'yes', '', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'firstname', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'lastname', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'address', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'zip', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'city', 'yes', 'no', 'text', '', '', '')");
    	    mysql_query("INSERT INTO config VALUES ('', 'member', 'state', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'country', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'phone', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'cellphone', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'icq', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'homepage', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'hobbys', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'field1', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'field2', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'field3', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'field4', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'field5', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'field6', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'field7', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'field8', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'field9', 'yes', 'no', 'text', '', '', '')");
	    mysql_query("INSERT INTO config VALUES ('', 'member', 'field10', 'yes', 'no', 'text', '', '', '')");

	    echo "Database: [$database] - Calculate cat-configuration Table: config ...";

	    $result=mysql_query("SELECT * FROM adcat");
	    while ($db=mysql_fetch_array($result)) {

		// insert configuration
		if ($db[sfield]) {$enable="yes";} else {$enable="no";}
		$sql="INSERT INTO config (type,name,value,value2) VALUES ('cat','sfield','$db[id]','$enable')";
		mysql_db_query($database, $sql) or die(mysql_error().$sql);

		for($i = 1; $i<= 20; $i++) {
		    if ($db["field".$i]) {$enable="yes";} else {$enable="no";}
		    $select="text";
		    $option="";
		    mysql_db_query($database,"INSERT INTO config (type,name,value,value2,value3,value4) VALUES
				('cat','field$i','$db[id]','$enable','$select','$option')") or die(mysql_error());
		}

		for($i = 1; $i<= 10; $i++) {
		    if ($db["icon".$i]) {$enable="yes";} else {$enable="no";}
		    mysql_db_query($database,"INSERT INTO config (type,name,value,value2) VALUES
				('cat','icon$i','$db[id]','$enable')") or die(mysql_error());
		}

	    }

	    echo "<b>OK</b><br>";

	}

	echo "<br><b>phpBazar Tables Updated, Ready ...</b>";

    } elseif ($action=="del") {

	installdb();

    } elseif ($action=="sal") {

	echo "<b>phpBazarSales Table Update</b><p>"

;

	$v_table	="adcat";
	$v_command	="ADD";
	$v_field	="sales";
	$v_type		="INT(1) NOT NULL DEFAULT '0'";


altertables($v_table,$v_command,$v_field,$v_type);

	$v_table	="adcat";
	$v_command	="ADD";
	$v_field	="salesbase";
	$v_type		="INT(1) DEFAULT '0' NOT NULL";


altertables($v_table,$v_command,$v_field,$v_type);

	$result = mysql_query("CREATE TABLE sales (id int(11) DEFAULT '0' NOT NULL auto_increment,
    					    userid int(11) DEFAULT '0' NOT NULL,
        				    timestamp int(14) DEFAULT '0' NOT NULL,
					    ip varchar(40) NOT NULL,
	    				    type varchar(10) NOT NULL,
	        			    count int(6) DEFAULT '0' NOT NULL,
		    			    amount double(16,4) DEFAULT '0.0000' NOT NULL,
		    			    ordernumber varchar(25) NOT NULL,
					    PRIMARY KEY (id),
			    		    UNIQUE id (id)
			    		    ) ");
	if (!$result) {
	    echo "Database: [$database] - Creation Table: sales failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$database] - Creation Table: sales <b>OK!</b><br>";
	}
	echo "<br><b>phpBazarSales Tables successfully installed/updated ...</b>";

    } elseif ($action=="chat") {

	echo "<b>phpBazarChat Database/Table Update</b><p>"
;

        mysql_create_db($chat_database);

        $result=mysql_db_query($chat_database, "CREATE TABLE users (id int(11) DEFAULT '0' NOT NULL auto_increment, nick varchar(25) NOT NULL, pass varchar(32) NOT NULL, PRIMARY KEY (id), UNIQUE id (id)) ");
	if (!$result) {
	    echo "Database: [$chat_database] - Creation Table: users failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
	} else {
	    echo "Database: [$chat_database] - Creation Table: users <b>OK!</b><br>";
	}

	mysql_db_query($chat_database, "delete from users") or die("Database Delete Error");
	$result = mysql_db_query($database, "select * FROM login") or die("Database Query Error");
	while ($db = mysql_fetch_array($result)) {
	    $username=$db[username];
	    $password=$db[password];
	    include ("../$chat_interface");
	    #echo "$db[username]\n";
	    $count++;
	}
        echo "$count Row's inserted<br>";
        echo "<br><b>phpBazarChat [$chat_database] Tables successfully installed/updated ...</b>";

    } elseif ($action=="bb") {

	echo "<b>Forum Database/Table Update</b><p>"
;

	$result = mysql_db_query($database, "select * FROM userdata") or die("Database Query Error");
	while ($db = mysql_fetch_array($result)) {
	    $result2 = mysql_db_query($database, "select * FROM login WHERE id='$db[id]'") or die("Database Query Error");
	    $db2 = mysql_fetch_array($result2);
            $username=$db[username];
	    $password=$db2[password];
	    $email=$db[email];
	    include ("../$forum_interface");
#	    echo "$username $email <br>\n";
	    $count++;
	}
        echo "$count Row's inserted<br>";
        echo "<br><b>Forum [$forum_database] Tables successfully updated ...</b>";

    } elseif ($action=="pic") {

	echo "<b>phpBazar Picture-to-DB</b><p>"

;

        if (!$tmp_dir = get_cfg_var('upload_tmp_dir')) {
            $tmp_dir = dirname(tempnam('', ''));
        }

        $result = mysql_db_query($database, "SELECT * FROM ads WHERE picture!=''") or died("Record NOT Found");

        while ($db = mysql_fetch_array($result)) {

          $result2 = mysql_db_query($database, "SELECT * FROM pictures WHERE picture_name ='$db[picture]'") or died("Record NOT Found");
          $dbp = mysql_fetch_array($result2);
          if ($dbp[picture_name]) {
            echo "WARNING: Picture $db[picture] already exists in DB, nothing done.<br>";
          } else {
            if (is_file("$bazar_dir/$image_dir/userpics/$db[picture]")) {
        	$picinfo=GetImageSize("$bazar_dir/$image_dir/userpics/$db[picture]");
        	    if ($picinfo[2] == "1" || $picinfo[2] == "2" || $picinfo[2] == "3") {
        		switch ($picinfo[2]) {
        		    case 1 : $ext = ".gif"; $type = "image/gif"; break;
        		    case 2 : $ext = ".jpg"; $type = "image/jpeg"; break;
        		    case 3 : $ext = ".png"; $type = "image/png"; break;
        		}

                        if (strtoupper($convertpath) == "AUTO") {   // simple file handling without convert
        		    if (is_file("$bazar_dir/$image_dir/userpics/$db[picture]")) {
        			$picture_size = filesize("$bazar_dir/$image_dir/userpics/$db[picture]");
        			$picture_bin = addslashes(fread(fopen("$bazar_dir/$image_dir/userpics/$db[picture]", "r"), $picture_size));
        			mysql_db_query($database, "INSERT INTO pictures VALUES ('$db[picture]','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')") or die("DB Update Error $db[picture] ".mysql_error());
        	    		echo "Picture $db[picture] stored in Database. (Type: $ext, Size: $picture_size)<br>";
        		    }

                        } else {                                    // advanced file handling with convert
                            $convertstr=" -geometry $pic_res -quality $pic_quality $bazar_dir/$image_dir/userpics/$db[picture] $tmp_dir/tmp_picture$ext";
                            exec($convertpath.$convertstr);
        		    if (is_file("$tmp_dir/tmp_picture$ext")) {
        			$picture_size = filesize("$tmp_dir/tmp_picture$ext");
        			$picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture$ext", "r"), $picture_size));
        			$picinfo=GetImageSize("$tmp_dir/tmp_picture$ext");
        			mysql_db_query($database, "INSERT INTO pictures VALUES ('$db[picture]','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')") or die("DB Update Error $db[picture] ".mysql_error());
        			suppr("$tmp_dir/tmp_picture$ext");
        	    		echo "Picture $db[picture] stored in Database. (Type: $ext, Size: $picture_size)<br>";
        		    }


                            $_convertstr=" -geometry $pic_lowres -quality $pic_quality $bazar_dir/$image_dir/userpics/$db[picture] $tmp_dir/tmp_picture$ext";
                    	    exec($convertpath.$_convertstr);
        		    if (is_file("$tmp_dir/tmp_picture$ext")) {
        			$picture_size = filesize("$tmp_dir/tmp_picture$ext");
        			$picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture$ext", "r"), $picture_size));
        			$picinfo=GetImageSize("$tmp_dir/tmp_picture$ext");
        			mysql_db_query($database, "INSERT INTO pictures VALUES ('_$db[picture]','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')") or die("DB Update Error $db[picture] ".mysql_error());
        			suppr("$tmp_dir/tmp_picture$ext");
        	    		echo "Picture _$db[picture] stored in Database. (Type: $ext, Size: $picture_size)<br>";
        		    }
                        }

        	    } else {
        		echo "WARNING: Picture $db[picture] wrong Filetype, nothing done.<br>";
        	    }
            } else {
                echo "ERROR: Picture $db[picture] couldn't open.<br>";
            }
          }
        }

	echo "<br><b>phpBazar Convert Pictures, Ready ...</b>";

    } else {

	echo "Database [$database] <b>exist</b>.<br>";
	if ($chat_enable && $chat_database && $chat_interface) echo "Chat [$chat_database] <b>enabled</b>.<br>";
	if ($forum_enable && $forum_database && $forum_interface) echo "Forum [$forum_database] <b>enabled</b>.<br>";
	if ($convertpath=="AUTO") {echo "Converttool [AUTO] <b>not enabled</b>.<br>";}
	elseif (is_file($convertpath)) {echo "Converttool [$convertpath] <b>enabled</b>.<br>";}
	else {echo "Converttool [$convertpath] <b>ERROR</b>.<br>";}
	$right_bazar_dir=substr($SCRIPT_FILENAME,0,-16);
	if ($bazar_dir!=$right_bazar_dir && !strstr($bazar_dir,"-")) echo "<b>ERROR</b> Check config.php <b>\$bazar_dir</b> should set to \"$right_bazar_dir\"<br>";
	if (!is_dir("$bazar_dir/$image_dir")) echo "<b>ERROR</b> Check config.php <b>\$image_dir</b> is not a directory<br>";
	if (!is_dir("$bazar_dir/$admin_dir")) echo "<b>ERROR</b> Check config.php <b>\$admin_dir</b> is not a directory<br>";
	if (!is_dir("$bazar_dir/$backup_dir")) echo "<b>ERROR</b> Check config.php <b>\$backup_dir</b> is not a directory<br>";
	if (!is_dir("$bazar_dir/$languagebase_dir")) echo "<b>ERROR</b> Check config.php <b>\$languagebase_dir</b> is not a directory<br>";
	if ($pic_enable && $pic_path && !$pic_database && !is_writeable("$bazar_dir/$pic_path")) echo "<b>ERROR</b> Check <b>\$pic_path</b> - it is not writeable (check permissions)<br>";
	if ($att_enable && $att_path && !is_writeable("$bazar_dir/$att_path")) echo "<b>ERROR</b> Check <b>\$att_path</b> - it is not writeable (check permissions)<br>";
	if ($webmail_enable && $webmail_path && !is_writeable("$bazar_dir/$webmail_path")) echo "<b>ERROR</b> Check <b>\$webmail_path</b> - it is not writeable (check permissions)<br>";

	echo "<br><table width=\"400\" border=\"1\"><tr><td><center><br>";
	echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"del\"><input type=\"submit\" value=\"INSTALL TABLES Version 1.5x\n!!! this DELETE OLD TABLES if any !!!\"></form><hr>";
	if ($pic_database) {
	    echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"pic\"><input type=\"submit\" value=\"UPDATE DB copy Pic's into DB\" ></form>";
	}
	if ($forum_database && $forum_enable && $forum_interface) {
	    echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"bb\"><input type=\"submit\" value=\"UPDATE DB of Forum\" ></form>";
	}
	if ($sales_option) {
	    echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"sal\"><input type=\"submit\" value=\"INSTALL/UPDATE DB for SalesOption\"></form>";
	}
	if ($chat_database && $chat_enable && $chat_interface) {
	    echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"chat\"><input type=\"submit\" value=\"INSTALL/UPDATE DB for ChatOption\" ></form>";
	}
	echo "<hr>";
        echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"up1\"><input type=\"submit\" value=\"UPDATE DB from Version 1.xx to 1.2x\"></form>";
        echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"up2\"><input type=\"submit\" value=\"UPDATE DB from Version 1.3x to 1.4x\"></form>";
        echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"up3\"><input type=\"submit\" value=\"UPDATE DB from Version 1.4x to 1.5x\"></form>";
	echo "</td></tr></table></center>\n";
    }
    echo "<br><br><input type=\"submit\" value=\"HOME\" onclick=\"javascript:window.location.href='$PHP_SELF'\">&nbsp;<input type=\"submit\" value=\"ADMIN\" onclick=\"javascript:window.location.href='admin.php'\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" value=\"EXIT\" onclick=\"javascript:window.close()\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    mysql_close();

} else {

    @mysql_close();

    if ($action=="inst") {

        mysql_connect($server, $db_user, $db_pass) or die (mysql_error());
        echo "<b>phpBazar Database install</b>"
;
	mysql_create_db($database) or die (mysql_error());
        echo "<b>- OK !!!</b><p>"
;
        mysql_close();
	echo "<form method=\"POST\" action=\"$PHP_SELF\"><input type=\"hidden\" name=\"action\" value=\"del\"><input type=\"submit\" value=\"INSTALL TABLES Version 1.5x\n\"></form><hr>";

    } else {

	echo "Database [$database] does NOT exist or is NOT accessable !!!<p>";
        echo "<form method=\"POST\" action=\"$PHP_SELF\">\n";

	echo "<table width=\"400\">\n";
	echo "<tr><td colspan=2><font face=\"verdana\" size=\"2\"><br>Check your mySQL Data (edit config.php)<br><br></font></td></tr>\n";
	echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-Server: </font></td><td><input type=\"text\" name=\"mysqlserver\" value=\"$server\" size=\"20\" readonly><br></td></tr>\n";
	echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-User: </font></td><td><input type=\"text\" name=\"mysqluser\" value=\"$db_user\" size=\"20\" readonly><br></td></tr>\n";
	echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-Pass: </font></td><td><input type=\"text\" name=\"mysqlpass\" value=\"$db_pass\" size=\"20\" readonly><br></td></tr>\n";
	echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-DB: </font></td><td><input type=\"text\" name=\"mysqldb\" value=\"$database\" size=\"20\" readonly><br></td></tr>\n";

        echo "<tr><td colspan=2><hr><center><input type=\"hidden\" name=\"action\" value=\"inst\"><input type=\"submit\" value=\"if configuration is OK - Create DB\"></form>";
	echo "</center></td></tr></table>\n";
    }
    echo "<br><br><input type=\"submit\" value=\"HOME\" onclick=\"javascript:window.location.href='$PHP_SELF'\">&nbsp;<input type=\"submit\" value=\"EXIT\" onclick=\"javascript:window.close()\">";

}


#  End
#################################################################################################

echo "</body></html>\n";

?>