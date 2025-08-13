<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: right.inc
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Right Side
#
#################################################################################################



if ($show_news) {



echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";

include ("$language_dir/news.inc");

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo"  </table>\n";



}



if ($show_votes) {



include ("spacer.inc");



echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";

include ("$language_dir/voting.inc");

include ("vote_show.php");

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo"  </table>\n";



}



?>