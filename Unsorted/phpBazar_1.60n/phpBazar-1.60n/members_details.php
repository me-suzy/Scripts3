<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: member_details.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: show details of a member
#
#################################################################################################



mysql_connect($server, $db_user, $db_pass);

if (function_exists("sales_checkuser")) {$is_sales_user=sales_checkuser($userid);}



if ($uid && $uname && $show_members_details && !($sales_option && $sales_members && !$is_sales_user)) {

  list($email, $sex, $newsletter, $level, $votes, $lastvotedate, $ads, $lastaddate, $firstname,

  $lastname, $address, $zip, $city, $state, $country, $phone, $cellphone, $icq, $homepage, $hobbys,

  $field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8, $field9, $field10) = $authlib->edit_retrieve($uid);



  if ($email) {

    echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

    echo "<tr><td><div class=\"smallleft\">\n";

    echo "$admy_member$uname&nbsp;&nbsp;\n";



	    if ($email) {

    		if ($sales_option && $sales_members && !$is_sales_user) { // check access for user

	    	    ico_email("","absmiddle");

		} else {

	    	    ico_email("username=$uname","absmiddle");

		}

	    }

	    if ($icq) {

    		if ($sales_option && $sales_members && !$is_sales_user) { // check access for user

		    ico_icq("","absmiddle");

		} else {

		    ico_icq("$icq","absmiddle");

		}

	    }

	    if ($homepage) {

    		if ($sales_option && $sales_members && !$is_sales_user) { // check access for user

	    	    ico_url("","absmiddle");

		} else {

	    	    ico_url("$homepage","absmiddle");

		}

	    }



    echo "</div></td>\n";

    echo "</tr></table>\n";



    echo"           <div class=\"maintext\">\n";

    echo"       	<br>\n";

    echo"        	<table align=center width=\"100%\">\n";

    echo"       	<tr>\n";

    echo"       	 <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">$memf_username : </div></td>\n";

    echo"            <td class=\"gbtable2\">$uname</td>\n";

    echo"           </tr>\n";

    echo"         	<tr>\n";

    echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">$memf_level : </div></td>\n";

    echo"            <td class=\"gbtable2\">$level ($userlevel[$level])</td>\n";

    echo"           </tr>\n";

    echo"         	<tr>\n";

    echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">$memf_votes : </div></td>\n";

    echo"            <td class=\"gbtable2\">$votes</td>\n";

    echo"           </tr>\n";

    if ($votes) {

        echo"         	<tr>\n";

	echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">$memf_lastvote : </div></td>\n";

        echo"            <td class=\"gbtable2\">".dateToStr($lastvotedate)."</td>\n";

        echo"          	</tr>\n";

    }

    echo"         	<tr>\n";

    echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">";

    if ($ads) {

	echo"                <a href=\"$PHP_SELF?choice=ads&uid=$uid&uname=$uname\">$memf_ads</a> :";

    } else {

	echo"                $memf_ads :";

    }

    echo"                </div></td>\n";

    echo"            <td class=\"gbtable2\">$ads</td>\n";

    echo"           </tr>\n";

    if ($ads) {

        echo"	    <tr>\n";

	echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">$memf_lastad : </div></td>\n";

        echo"            <td class=\"gbtable2\">".dateToStr($lastaddate)."</td>\n";

	echo"           </tr>\n";

    }



    echo memberfield("2","sex","$memf_sex","$gender[$sex]");

    echo memberfield("2","firstname","$memf_firstname","$firstname");

    echo memberfield("2","lastname","$memf_lastname","$lastname");

    echo memberfield("2","address","$memf_address","$address");

    echo memberfield("2","zip","$memf_zip","$zip");

    echo memberfield("2","city","$memf_city","$city");

    echo memberfield("2","state","$memf_state","$state");

    echo memberfield("2","country","$memf_country","$country");

    echo memberfield("2","phone","$memf_phone","$phone");

    echo memberfield("2","cellphone","$memf_cellphone","$cellphone");

    echo memberfield("2","icq","$memf_icq","$icq");

    echo memberfield("2","homepage","$memf_homepage","$homepage");

    echo memberfield("2","hobbys","$memf_hobbys","$hobbys");

    echo memberfield("2","field1","$memf_field1","$field1");

    echo memberfield("2","field2","$memf_field2","$field2");

    echo memberfield("2","field3","$memf_field3","$field3");

    echo memberfield("2","field4","$memf_field4","$field4");

    echo memberfield("2","field5","$memf_field5","$field5");

    echo memberfield("2","field6","$memf_field6","$field6");

    echo memberfield("2","field7","$memf_field7","$field7");

    echo memberfield("2","field8","$memf_field8","$field8");

    echo memberfield("2","field9","$memf_field9","$field9");

    echo memberfield("2","field10","$memf_field10","$field10");



    echo"          	</table>\n";

    echo"           </div><br><br>\n";



  } else {

    echo $mess_noentry;

  }



} else {

    echo "$memb_notenabled";

}

@mysql_close();

?>