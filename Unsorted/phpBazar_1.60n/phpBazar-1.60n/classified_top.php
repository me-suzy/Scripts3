<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_top.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Show the classified ads top entry's
#
#################################################################################################



#  Connect to the DB

#################################################################################################

mysql_connect($server, $db_user, $db_pass);



#  Ads Overview

#################################################################################################

if ($login_check) {$mod=$login_check[2];}

if ($login_check) {$userid=$login_check[1];}

if ($adapproval && !$mod) {$approval="AND publicview='1'";}

if (empty($top_sortorder)) $top_sortorder = "answered desc";

if (empty($top_maximum)) $top_maximum = "10";



    $result = mysql_db_query($database, "SELECT * FROM ads WHERE 1=1 $approval ORDER by $top_sortorder LIMIT 0, $top_maximum") or died("Record NOT Found");



    echo "<table align=\"center\" cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";



    while ($db = mysql_fetch_array($result)) {



	$result2 = mysql_db_query($database, "SELECT * FROM userdata WHERE id=$db[userid]") or died("Record NOT Found");

	$dbu = mysql_fetch_array($result2);

	$result3 = mysql_db_query($database, "SELECT * FROM adcat WHERE id=$db[catid]") or died("Record NOT Found");

	$dbc = mysql_fetch_array($result3);

	include("classified_ads.inc.php");

    } //End while

    echo "</table>\n";



# End of Page reached

#################################################################################################





#  Disconnect DB

#################################################################################################

mysql_close();

?>