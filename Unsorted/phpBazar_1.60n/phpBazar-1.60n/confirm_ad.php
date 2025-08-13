<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: confirm.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Ad Confirmation Reg.
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("library.php");



$confirm = $authlib->confirm_ad($id,$hash);





#  The Head-Section

#################################################################################################

include ($HEADER);



#  The Main-Section

#################################################################################################

echo"<p>&nbsp; \n";

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";

        	if ($confirm != 2) {

include ("$language_dir/confirm_ad_error.inc");

		} else {

include ("$language_dir/confirm_ad_done.inc");

		}

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo"  </table>\n";





#  The Foot-Section

#################################################################################################

include ($FOOTER);

?>