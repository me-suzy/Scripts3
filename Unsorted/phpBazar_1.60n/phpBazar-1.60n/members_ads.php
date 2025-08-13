<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: members_ads.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: show ads of a member
#
#################################################################################################



mysql_connect($server, $db_user, $db_pass);

if (function_exists("sales_checkuser")) {$is_sales_user=sales_checkuser($userid);}



if ($uid && $uname && $show_members_ads && !($sales_option && $sales_members && !$is_sales_user)) {



    echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

    echo "<tr><td><div class=\"smallleft\">\n";

    echo "$admy_member$uname\n";

    echo "</div></td>\n";

    echo "</tr></table>\n";





    $result = mysql_db_query($database, "SELECT * FROM ads WHERE userid=$uid $approval ORDER by $ad_sort") or died(mysql_error());



    echo "<table align=\"center\" cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

    while ($db = mysql_fetch_array($result)) {



        $result2 = mysql_db_query($database, "SELECT * FROM userdata WHERE id=$db[userid]") or died("Record NOT Found");

        $dbu = mysql_fetch_array($result2);

        $result3 = mysql_db_query($database, "SELECT * FROM adcat WHERE id=$db[catid]") or died("Record NOT Found");

        $dbc = mysql_fetch_array($result3);



	include("classified_ads.inc.php");



    } //End while

    echo "</table>\n";



} else {

    echo "$memb_notenabled";

}



mysql_close();

?>