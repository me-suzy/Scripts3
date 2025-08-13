<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: guestbook.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Guestbook Area
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("library.php");



if (strstr (getenv('HTTP_USER_AGENT'), 'MSIE')) { // Internet Explorer Detection

    $in_field_size="50";

    $text_field_size="31";

} else {

    $in_field_size="30";

    $text_field_size="24";

}





#  The Head-Section

#################################################################################################

include ($HEADER);





#  The Menu-Section

#################################################################################################

include ("menu.inc");



#  The Left-Side-Section

#################################################################################################

$tmp_width = ($table_width+(2*$table_width_side)+10);

echo"<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$tmp_width\">\n";

echo"<tr>\n";

echo"<td valign=\"top\" align=\"right\">\n";

include ("left.inc");

echo"</td>\n";



#  The Main-Section

#################################################################################################

echo"<td valign=\"top\" align=\"left\">\n";

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" margin=1 width=\"$table_width\" height=\"$table_height\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";



$login_check = $authlib->is_logged();



if (!$login_check && !$guestbookfree) {

    include ("$language_dir/nologin.inc");

} else {





if ($entry=="add") {

    echo "	    <div class=\"mainheader\">$gb_link1head</div>\n";

    echo "	    <div class=\"maintext\">\n";

    echo " <br>\n";

    echo " <table align=\"center\">\n";

    echo " <Form action=\"guestbook_submit.php\" method=\"post\">\n";

    echo "     <tr>\n";

    echo "             <td><div class=\"maininputleft\">$gbadd_name</div></td>\n";

    echo "             <td><input type=\"text\" name=\"in[name]\" size=\"$in_field_size\" maxlength=\"35\"></td>\n";

    echo "     </tr>\n";

    echo "     <tr>\n";

    echo "             <td><div class=\"maininputleft\">$gbadd_location</td>\n";

if ($location_text) {

    echo "             <td><input type=\"text\" name=\"in[location]\" size=\"$in_field_size\" maxlength=\"35\"></td>\n";

} else {

    echo "<td class=\"classadd2\"><select name=\"in[location]\">\n";

    echo "<option value=\"0\" SELECTED>$location_sel</option>\n";

    include ("$language_dir/location.inc");

    echo "</select></td>\n";

}

    echo "     </tr>\n";

    echo "     <tr>\n";

    echo "             <td><div class=\"maininputleft\">$gbadd_email</td>\n";

    echo "             <td><input type=\"text\" name=\"in[email]\" size=\"$in_field_size\" maxlength=\"35\"></td>\n";

    echo "     </tr>\n";

    echo "     <tr>\n";

    echo "             <td><div class=\"maininputleft\">$gbadd_icq</td>\n";

    echo "             <td><input type=\"text\" name=\"in[icq]\" size=\"$in_field_size\" value=\"\" maxlength=\"12\"></td>\n";

    echo "     </tr>\n";

    echo "     <tr>\n";

    echo "             <td><div class=\"maininputleft\">$gbadd_url</td>\n";

    echo "             <td><input type=\"text\" name=\"in[http]\" size=\"$in_field_size\" maxlength=\"60\" value=\"http://\"></td>\n";

    echo "     </tr>\n";

    echo "     <tr>\n";

    echo "             <td valign=\"top\"><div class=\"maininputleft\">$gbadd_msg<br><br>\n";

    echo "		<div class=\"mainpages\"><a href=\"smiliehelp.php\"

			    onClick='enterWindow=window.open(\"smiliehelp.php\",\"Smilie\",

			    \"width=250,height=450,top=100,left=100,scrollbars=yes\"); return false'

			    onmouseover=\"window.status='$smiliehelp'; return true;\"

			    onmouseout=\"window.status=''; return true;\">SmilieHelp</a>&nbsp&nbsp\n";

    echo "		</div></td>\n";

    echo "             <td><textarea rows=\"8\" name=\"in[message]\" cols=\"$text_field_size\"></textarea></td>\n";

    echo "     </tr>\n";

    echo "     <tr>\n";

    echo "             <td></td>\n";

    echo "             <td><br><input type=\"submit\" Value=\"$submit\"</td>\n";

    echo "     </tr>\n";

    echo " </table>\n";

    echo " </form>\n";





    echo"           </div>\n";

} else {

    echo"	    <table align=\"center\">\n";

    echo"            <tr>\n";

    echo"             <td>\n";

    echo"              <div class=\"mainmenu\">\n";

    echo" 		    <a href=\"guestbook.php?entry=add\" onmouseover=\"window.status='$gb_link1desc'; return true;\" onmouseout=\"window.status=''; return true;\">$gb_link1</a>\n";

    echo"              </div>\n";

    echo"             </td>\n";

    echo"             <td width=\"1%\">\n";

    echo"              <div class=\"mainheader\">$guestbook_head</div>\n";

    echo"             </td>\n";

    echo"            </tr>\n";

    echo"           </table>\n";

    echo"           <div class=\"maintext\">\n";

    include ("guestbook_show.php");

    echo"           </div>\n";

}



} //from login_check



echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";



#  PLEASE DO NOT REMOVE OR EDIT THIS COPYRIGHT-NOTICE !!! THANKS !!! ################################################

echo" <p><div class=\"footer\">phpBook Ver. $book_version &copy 2001-".date("Y")." by SmartISoft<!--CyKuH [WTN]--></div>\n";

#####################################################################################################################



echo"</td>\n";





#  The Right-Side-Section

#################################################################################################

echo"<td valign=\"top\" align=\"left\">\n";

include ("right.inc");

echo"</td>\n";

echo"</tr>\n";

echo"</table>\n";





#  The Foot-Section

#################################################################################################

include ($FOOTER);

?>