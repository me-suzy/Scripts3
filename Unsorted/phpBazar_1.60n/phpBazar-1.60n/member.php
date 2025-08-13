<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: member.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: My Profile Area
#
#################################################################################################





if ($change=="email") {

    include ("$language_dir/member_chemail.inc");

} elseif ($change=="pass") {

    include ("$language_dir/member_chpass.inc");

} elseif ($change=="delete") {

    include ("$language_dir/member_delete.inc");

} elseif ($change=="sales" && $sales_option) {

    include ("sales_member.php");

} else {



    list($email, $sex, $newsletter, $level, $votes, $lastvotedate, $ads, $lastaddate, $firstname,

    $lastname, $address, $zip, $city, $state, $country, $phone, $cellphone, $icq, $homepage, $hobbys,

    $field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8, $field9, $field10) = $authlib->edit_retrieve($login_check[1]);



    if (!$homepage) $homepage = "http://";



    echo"           <table align=\"center\">\n";

    echo"            <tr>\n";

    echo"             <td>\n";

    echo"              <div class=\"mainmenu\">\n";

    echo" 		   <a href=\"members.php?choice=myprofile&change=email\" onmouseover=\"window.status='$memb_link1desc'; return true;\" onmouseout=\"window.status=''; return true;\">$memb_link1</a>$menusep\n";

    echo" 		   <a href=\"members.php?choice=myprofile&change=pass\" onmouseover=\"window.status='$memb_link2desc'; return true;\" onmouseout=\"window.status=''; return true;\">$memb_link2</a>$menusep\n";

    echo" 		   <a href=\"members.php?choice=myprofile&change=delete\" onmouseover=\"window.status='$memb_link3desc'; return true;\" onmouseout=\"window.status=''; return true;\">$memb_link3</a>\n";

    if ($sales_option) {

	echo" 		   $menusep<a href=\"members.php?choice=myprofile&change=sales\" onmouseover=\"window.status='$sales_lang_linkdesc'; return true;\" onmouseout=\"window.status=''; return true;\">$sales_lang_link</a>\n";

    }

    echo"              </div>\n";

    echo"             </td>\n";

    echo"            </tr>\n";

    echo"           </table>\n";



    echo"           <div class=\"maintext\">\n";

    echo"       	<br>\n";

    echo"        	<table align=center>\n";

    echo"       	<form action=member_update.php METHOD=POST>\n";

    echo"       	<tr>\n";

    echo"       	 <td width=\"50%\"><div class=\"maininputleft\">$memf_username : </div></td>\n";

    echo"            <td>$login_check[0]</td>\n";

    echo"           </tr>\n";

    echo"         	<tr>\n";

    echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_email : </div></td>\n";

    echo"            <td>$email</td>\n";

    echo"           </tr>\n";

    echo"         	<tr>\n";

    echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_level : </div></td>\n";

    echo"            <td>$level ($userlevel[$level])</td>\n";

    echo"           </tr>\n";

    echo"         	<tr>\n";

    echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_votes : </div></td>\n";

    echo"            <td>$votes</td>\n";

    echo"           </tr>\n";

    if ($votes) {

        echo"         	<tr>\n";

	echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_lastvote : </div></td>\n";

        echo"            <td>".dateToStr($lastvotedate)."</td>\n";

	echo"          	</tr>\n";

    }

    echo"         	<tr>\n";

    echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_ads : </div></td>\n";

    echo"            <td>$ads</td>\n";

    echo"           </tr>\n";

    if ($ads) {

	echo"         	<tr>\n";

        echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_lastad : </div></td>\n";

	echo"            <td>".dateToStr($lastaddate)."</td>\n";

        echo"           </tr>\n";

    }



    if (memberfield("0","sex","","")) {

	echo"         <tr>\n";

	echo"          <td><div class=\"maininputleft\">$memf_sex : </div></td>\n";

	echo"          <td><select name=sex>\n";



	for($i = 0; $i<count($genders); $i++) {

	    $letter=$genders[$i];

    	    if ($sex==$letter) {$selected="SELECTED";} else {$selected="";}

    	    echo "           <option value=\"$letter\" $selected>$gender[$letter]</option>\n";

        }



	echo"           </select></td>\n";

	echo"         </tr>\n";

    }



    if (memberfield("0","newsletter","","")) {

	if ($newsletter) $newschecked = "CHECKED";

	echo"         <tr>\n";

	echo"          <td><div class=\"maininputleft\">$memf_newsletter : </div></td>\n";

	echo"          <td><input type=checkbox name=newsletter $newschecked></td>\n";

	echo"         </tr>\n";

    }



    echo memberfield("0","firstname","$memf_firstname","$firstname");

    echo memberfield("0","lastname","$memf_lastname","$lastname");

    echo memberfield("0","address","$memf_address","$address");

    echo memberfield("0","zip","$memf_zip","$zip");

    echo memberfield("0","city","$memf_city","$city");

    echo memberfield("0","state","$memf_state","$state");

    echo memberfield("0","country","$memf_country","$country");

    echo memberfield("0","phone","$memf_phone","$phone");

    echo memberfield("0","cellphone","$memf_cellphone","$cellphone");

    echo memberfield("0","icq","$memf_icq","$icq");

    echo memberfield("0","homepage","$memf_homepage","$homepage");

    echo memberfield("0","hobbys","$memf_hobbys","$hobbys");

    echo memberfield("0","field1","$memf_field1","$field1");

    echo memberfield("0","field2","$memf_field2","$field2");

    echo memberfield("0","field3","$memf_field3","$field3");

    echo memberfield("0","field4","$memf_field4","$field4");

    echo memberfield("0","field5","$memf_field5","$field5");

    echo memberfield("0","field6","$memf_field6","$field6");

    echo memberfield("0","field7","$memf_field7","$field7");

    echo memberfield("0","field8","$memf_field8","$field8");

    echo memberfield("0","field9","$memf_field9","$field9");

    echo memberfield("0","field10","$memf_field10","$field10");



    echo"            <tr>\n";

    echo"            <td>&nbsp;</td>\n";

    echo"            <td><br><input type=submit value=$update></td>\n";

    echo"          	</tr>\n";

    echo"          	</table>\n";

    echo"          	</form>\n";

    echo"           </div>\n";

}



?>