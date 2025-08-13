<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: ./admin/notify.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: notify members
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require_once ("../config.php");



#  Procedure 1

#################################################################################################

mysql_connect($server, $db_user, $db_pass) or die ("Database connect Error");

$result = mysql_db_query($database, "SELECT * FROM notify GROUP BY userid") or die("Record NOT Found");

while ($db = mysql_fetch_array($result)) {



    $result2 = mysql_db_query($database, "SELECT * FROM userdata WHERE id ='$db[userid]'") or die("Record NOT Found");

    $dbu = mysql_fetch_array($result2);

    echo "         ID:$db[userid] $dbu[username][$dbu[email]]: CatNotify ";

    $link="";

    $result3 = mysql_db_query($database, "SELECT * FROM notify WHERE userid ='$db[userid]' ORDER BY subcatid asc") or die("Record NOT Found");

    while ($dbn = mysql_fetch_array($result3)) {

	$result4 = mysql_db_query($database, "SELECT * FROM adsubcat WHERE id ='$dbn[subcatid]' AND notify='1'") or die("Record NOT Found");

	$dbs = mysql_fetch_array($result4);

	if ($dbs) {

	    $result5 = mysql_db_query($database, "SELECT id,name FROM adcat WHERE id ='$dbs[catid]'") or die("Record NOT Found");

	    $dbc = mysql_fetch_array($result5);

    	    echo "|$dbs[id]";

	    $link.="$dbc[name]/$dbs[name]: $url_to_start/classified.php?catid=$dbs[catid]&subcatid=$dbs[id]\n";

	}

    }

    $message[1]="Hello $dbu[username]\n\nNEW ads have been posted within your selected categories at $bazar_name.\nFollow this link to check them out:\n\n";

    $message[2]="\nThis message is generated automatically. To change your settings go to\nthe Auto-Notify section in our system:\n$url_to_start/classified.php?choice=notify\n\n Your Webmaster";

    $subj = "AUTO-NOTIFY - NEW Ads within selected Categories";



    $msg=$message[1].$link.$message[2];

    $from = "From: $admin_email";

    if ($link) {

    	@mail($dbu[email], $subj, $msg, $from);

	echo "| EMail sent\n";

    } else {

	echo "| Nothing to send\n";

    }

}

mysql_db_query($database, "UPDATE adsubcat SET notify='0'") or die("Update Error");

mysql_close();



?>