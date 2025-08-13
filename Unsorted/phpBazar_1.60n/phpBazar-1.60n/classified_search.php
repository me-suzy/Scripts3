<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_search.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Search classified record's
#
#################################################################################################



#  Connect to the DB

#################################################################################################



mysql_connect($server, $db_user, $db_pass);





#  Process

#################################################################################################





if (strstr (getenv('HTTP_USER_AGENT'), 'MSIE')) { // Internet Explorer Detection

    $field_size="50";

} else {

    // Shit !!! fucking Netscape code

    $field_size="28";

}



#if ($catid || $subcatid) {



     echo"

         <SCRIPT LANGUAGE=\"JavaScript\"><!--

         function changecat(newcat) {

             site = \"classified.php?choice=search&catid=\"+(newcat);

             top.location.href=site;

         }

         //--></SCRIPT>";



    $optioncat = "<option value=>$adseek_all</option>";

    $optionsubcat = "<option value=>$adseek_all</option>";



    $result = mysql_db_query($database, "SELECT * FROM adcat ORDER by id") or died("Database Query Error");

    while ($db = mysql_fetch_array($result)) {

	if ($db[id]==$catid) {$catselected="SELECTED";} else {$catselected="";}

	$optioncat .= "<option value=$db[id] $catselected>$db[name]</option>";

    }



    if ($subcatid) {

	$subcatresult = mysql_db_query($database, "SELECT * FROM adsubcat WHERE id=$subcatid") or died("Database Query Error");

	$dbsubcat = mysql_fetch_array($subcatresult);

	$catid = $dbsubcat[catid];

    }

    if ($catid) {

        $catresult = mysql_db_query($database, "SELECT * FROM adcat WHERE id=$catid") or died("Database Query Error");

	$dbcat = mysql_fetch_array($catresult);

        $cat=$dbcat[name];

	$catid=$dbcat[id];



	$result = mysql_db_query($database, "SELECT * FROM adsubcat WHERE catid=$catid ORDER by id") or died("Database Query Error");

	while ($db = mysql_fetch_array($result)) {

	    if ($db[id]==$subcatid) {$subcatselected="SELECTED";} else {$subcatselected="";}

	    $optionsubcat .= "<option value=$db[id] $subcatselected>$db[name]</option>";

	}

    }

#} else {

#}





# --- Simple Search ------------------------------------------------------------------------------------



echo "<br><b>$adseek_simple</b><hr>\n";

echo "<form enctype=\"text\" action=classified_search_submit.php METHOD=POST>\n";

echo "<table align=\"center\" width=\"100%\">\n";

echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adseek_adnr </div></td>\n";

echo "<td class=\"classadd2\"><input type=text name=\"in[adid]\" size=\"$field_size\" maxlength=\"6\" value=\"\"></td>\n";

echo "<input type=\"hidden\" name=\"in[catid]\" value=\"$catid\">\n";

echo "<input type=\"hidden\" name=\"in[subcatid]\" value=\"$subcatid\">\n";

echo "<input type=\"hidden\" name=\"in[searchmode]\" value=\"simple\">\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"></td>\n";

echo "<td class=\"classadd2\"><div class=\"mainmenu\"><br><input type=submit value=$adseek_submit>\n";

echo " $adseek_submitone</div></td>\n";

echo "</tr>\n";

echo "</table>\n";

echo "</form>\n";



# --- Advanced Search ------------------------------------------------------------------------------------





echo "<b>$adseek_adv</b><hr>\n";

echo "<form enctype=\"text\" action=classified_search_submit.php METHOD=POST>\n";

echo "<table align=\"center\" width=\"100%\">\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adseek_cat</div></td>\n";

echo "<td class=\"classadd2\">\n";

echo "<select name=\"in[catid]\" style=\"width:200px;\" onchange=\"changecat(this.options[this.selectedIndex].value)\">$optioncat</select>";

echo "</td></tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adseek_subcat</div></td>\n";

echo "<td class=\"classadd2\">\n";

echo "<select name=\"in[subcatid]\" style=\"width:200px;\">$optionsubcat</select>";

echo "</td></tr>\n";



echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adseek_loc</div></td>\n";

if ($location_text) {

    echo "<td class=\"classadd2\"><input type=text name=\"in[location]\" size=\"$field_size\" maxlength=\"50\" value=\"$db[location]\">\n";

} else {

    echo "<td class=\"classadd2\"><select name=\"in[location]\" style=\"width:200px;\">\n";

    echo "<option value=>$adseek_all</option>";

    include ("$language_dir/location.inc");

    echo "</select></td>\n";

}

echo "</tr>\n";





// Fields of this Category



if (searchfield($dbcat[id],"sfield")) {

     echo "<tr>\n";

     echo "<td class=\"classadd1\"><div class=\"maininputleft\">$dbcat[sfield]$adadd_fieldend</div></td>\n";

     echo "<td class=\"classadd2\"><input type=text name=\"in[sfield]\" size=\"$field_size\" maxlength=\"50\"></td>\n";

     echo "</tr>\n";

}



for ($i=1;$i<=20;$i++) {

     if (searchfield($dbcat[id],"field".$i)) {

         $adadd_field=("field".$i);

	 echo searchfield($dbcat[id],$adadd_field,"$dbcat[$adadd_field]","","$field_size");

     }

}





// Icon's of this Category

for ($i=1;$i<=10;$i++) {

    $stricon="icon".$i;

    $striconalt="icon".$i."alt";

    if ($dbcat["$stricon"] && searchfield($catid,"$stricon")) {

	if (substr(base_convert("$i", 10, 2),-1)) {$hspace="3";} else {$hspace="2";}

        $iconstr.= "<img src=\"$dbcat[$stricon]\" alt=\"$dbcat[$striconalt]\" hspace=\"$hspace\">\n";

        $x++;

    }

}



if ($x) {



     echo "<tr><td><div class=\"spaceleft\">&nbsp</div></td></tr>\n";

     echo "<tr>\n";

     echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_selicon</div></td>\n";

     echo "<td class=\"classadd2\" height=\"50\">\n";

     echo "$iconstr<br>\n";



     for ($i=1;$i<=10;$i++) {

         if ($dbcat["icon".$i] && searchfield($catid,"icon$i"))  {echo "<input type=\"checkbox\" name=\"in[icon$i]\">\n";}

     }



     echo "</td></tr>\n";



 }



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adseek_text </div></td>\n";

echo "<td class=\"classadd2\"><input type=text name=\"in[text]\" size=\"$field_size\" maxlength=\"50\" value=\"*\"></td>\n";

echo "</tr>\n";



if ($pic_enable) {

echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adseek_pic </div></td>\n";

echo "<td class=\"classadd2\"><input type=\"checkbox\" name=\"in[picture]\"></td>\n";

echo "</tr>\n";

}



if ($att_enable) {

echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adseek_att </div></td>\n";

echo "<td class=\"classadd2\"><input type=\"checkbox\" name=\"in[attachment]\"></td>\n";

echo "</tr>\n";

}



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adseek_sort </div></td>\n";

echo "<td class=\"classadd2\"><select name=\"in[search_sort]\">\n";

include ("$language_dir/sortoption1.inc");

echo "</select>\n";

echo "<select name=\"in[search_sort2]\">\n";

include ("$language_dir/sortoption2.inc");

echo "</select>\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"></td>\n";

echo "<td class=\"classadd2\"><div class=\"mainmenu\"><br>\n";

echo "<input type=\"hidden\" name=\"in[searchmode]\" value=\"advanced\">\n";

echo "<input type=submit value=$adseek_submit><br>\n";

echo " $adseek_submitone</div></td>\n";

echo "</tr>\n";

echo "</table>\n";

echo "</form>\n";





mysql_close();



?>