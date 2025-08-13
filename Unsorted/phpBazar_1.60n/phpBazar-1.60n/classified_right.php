<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_right.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Show the (right side) long description of the classified cat
#
#################################################################################################



if ($show_advert3) {$table_height=1;}



echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\" height=\"$table_height\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";



if ($catid && $choice!="search") {





#  Connect to the DB

#################################################################################################



    mysql_connect($server, $db_user, $db_pass);





#  Get Entrys for current page

#################################################################################################

    $result = mysql_db_query($database, "SELECT * FROM adcat where id=$catid");

    $db = mysql_fetch_array($result);

    echo "<div class=\"sideheader\">$db[name]</div>\n";

    echo "<div class=\"sidetext\">\n";

    echo "$db[longdescription]\n";

    echo "</div>";





#  Disconnect DB

#################################################################################################

    mysql_close();





} elseif ($choice=="add") {

    include ("$language_dir/classified_right_add.inc");

} elseif ($choice=="search") {

    include ("$language_dir/classified_right_search.inc");

} elseif ($choice=="my") {

    include ("$language_dir/classified_right_my.inc");

} elseif ($choice=="fav") {

    include ("$language_dir/classified_right_fav.inc");

} elseif ($choice=="notify") {

    include ("$language_dir/classified_right_not.inc");

} else {

    include ("$language_dir/classified_right_main.inc");

}





# End of Page reached

#################################################################################################

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo"  </table>\n";





# Advertising Window 3

#################################################################################################

if ($show_advert3) {



include ("spacer.inc");



echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";



include ("$language_dir/advert3.inc");



echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo"  </table>\n";



}





?>