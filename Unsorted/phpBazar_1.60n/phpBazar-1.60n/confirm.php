<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: confirm.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Confirmation Reg.
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("library.php");



$confirm = $authlib->confirm($hash, $nick);



if ($force_addad && ($confirm==2 || $confirm==3)) {

    $cookietime=time()+(3600*24*356);

    setcookie("ForceAddAd", "1", $cookietime, "$cookiepath"); // 1 Year

}



if ($auto_login && $confirm==3) {

    if ($force_addad && $cookietime){

	header("Location: $url_to_start/classified.php?status=1");

    } else {

	header("Location: $url_to_start/main.php?status=1");

    }

}



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

        	if ($confirm == 2) {

include ("$language_dir/confirm_done.inc");

		} else {

include ("$language_dir/confirm_error.inc");

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