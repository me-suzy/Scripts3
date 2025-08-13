<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: register_submit.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Submit Member Registration
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("library.php");



$username=trim($username);

$password=trim($password);

$password2=trim($password2);

$email=trim($email);

if (!memberfield("1","sex","","")) {$sex="n";}

if ($homepage=="http://") {$homepage="";}

$register = $authlib->register($username, $password, $password2, $email, $sex, $acceptterms,

                    $newsletter, $firstname, $lastname, $address, $zip, $city, $state, $country,

                    $phone, $cellphone, $icq, $homepage, $hobbys, $field1, $field2, $field3,

                    $field4, $field5, $field6, $field7, $field8, $field9, $field10);





if ($no_confirmation && $register == 2) {

    if ($force_addad) {

	$cookietime=time()+(3600*24*356);

	setcookie("ForceAddAd", "1", $cookietime, "$cookiepath"); // 1 Year

    }

    header ("Location: $url_to_start/main.php");

}



#  The Head-Section

#################################################################################################

include($HEADER);





#  The Main-Section

#################################################################################################

echo"<p>&nbsp; \n";

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";

		if ($register == 2) {

        	    include ("$language_dir/register_done.inc");

		} else {

        	    include ("$language_dir/register_error.inc");

		}

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo"  </table>\n";







#  The Foot-Section

#################################################################################################

include($FOOTER);

?>