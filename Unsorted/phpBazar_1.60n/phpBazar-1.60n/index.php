<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: index.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Index (Start) File
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("library.php");



#  The Head-Section

#################################################################################################

include ($HEADER);





#  The Main-Section

#################################################################################################

#echo"<p>&nbsp; \n";

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$wel_table_width\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";

               include ("$language_dir/welcome.inc");

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";





#  The Foot-Section

#################################################################################################

include ($FOOTER);



#  Browser Detection

#################################################################################################

#include ("$language_dir/browser.inc"); Maybe you want display Message if Browser NOT MSIE



?>