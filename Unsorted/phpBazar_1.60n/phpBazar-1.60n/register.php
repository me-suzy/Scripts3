<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: register.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Member Registration
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("library.php");



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

echo"        <div class=\"mainheader\">$newmemb_head</div>\n";

echo"        <div class=\"maintext\">\n";

echo"        <br> \n";

echo"        <table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";

echo"        <FORM ACTION=\"register_submit.php\" METHOD=\"POST\">\n";

echo"         <tr>\n";

echo"          <td width=\"50%\"><div class=\"maininputleft\">$memf_username : </div></td>\n";

echo"          <td><input type=text name=username></td>\n";

echo"         </tr>\n";

echo"         <tr>\n";

echo"          <td>\n";

echo"        	<div class=\"maininputleft\">$memf_email <em id=\"red\">$memb_newvalid</em> : </div></td>\n";

echo"          <td><input type=text name=email></td>\n";

echo"         </tr>\n";

echo"         <tr>\n";

echo"          <td><div class=\"maininputleft\">$memf_password : </div></td>\n";

echo"          <td><input type=password name=password></td>\n";

echo"         </tr>\n";

echo"         <tr>\n";

echo"          <td><div class=\"maininputleft\">$memf_password2 : </div></td>\n";

echo"          <td><input type=password name=password2></td>\n";

echo"         </tr>\n";



echo"         <tr>\n";

echo"          <td><div class=\"maininputleft\">&nbsp;</div></td>\n";

echo"         </tr>\n";



if (memberfield("1","sex","","")) {

echo"         <tr>\n";

echo"          <td><div class=\"maininputleft\">$memf_sex : </div></td>\n";

echo"          <td><select name=sex>\n";

for($i = 0; $i<count($genders); $i++) {

    $letter=$genders[$i];

    if ($sex==$letter) {$selected="SELECTED";} else {$selected="";}

    echo "           <option value=\"$letter\" $selected>$gender[$letter]</option>\n";

}

echo"        	</select></td>\n";

echo"         </tr>\n";

}



if (memberfield("1","newsletter","","")) {

echo"         <tr>\n";

echo"          <td><div class=\"maininputleft\">$memf_newsletter : </div></td>\n";

echo"          <td><input type=checkbox name=newsletter CHECKED></td>\n";

echo"         </tr>\n";

}



echo memberfield("1","firstname","$memf_firstname","");

echo memberfield("1","lastname","$memf_lastname","");

echo memberfield("1","address","$memf_address","");

echo memberfield("1","zip","$memf_zip","");

echo memberfield("1","city","$memf_city","");

echo memberfield("1","state","$memf_state","");

echo memberfield("1","country","$memf_country","");

echo memberfield("1","phone","$memf_phone","");

echo memberfield("1","cellphone","$memf_cellphone","");

echo memberfield("1","icq","$memf_icq","");

echo memberfield("1","homepage","$memf_homepage","http://");

echo memberfield("1","hobbys","$memf_hobbys","");

echo memberfield("1","field1","$memf_field1","");

echo memberfield("1","field2","$memf_field2","");

echo memberfield("1","field3","$memf_field3","");

echo memberfield("1","field4","$memf_field4","");

echo memberfield("1","field5","$memf_field5","");

echo memberfield("1","field6","$memf_field6","");

echo memberfield("1","field7","$memf_field7","");

echo memberfield("1","field8","$memf_field8","");

echo memberfield("1","field9","$memf_field9","");

echo memberfield("1","field10","$memf_field10","");



echo"          <td colspan=2><div class=\"smallcenter\"><br>\n";

echo"        	              <a href=\"termsofuse.php\" onClick='enterWindow=window.open(\"termsofuse.php\",\"Fenster\",

        	              \"width=750,height=550,top=50,left=50,scrollbars=yes\"); return false'>

        	              $memb_newterms</a>

        	              <input type=checkbox name=acceptterms CHECKED></div></td>\n";

echo"         </tr>\n";

echo"         <tr>\n";

echo"          <td>&nbsp;</td>\n";

echo"          <td><br><input type=submit value=\"$memb_newsubmit\" name=\"submit\"></td>\n";

echo"         </tr>\n";

echo"        </form>\n";

echo"        </table>\n";

echo"        </div>\n";

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";





#  The Foot-Section

#################################################################################################

include($FOOTER);

?>