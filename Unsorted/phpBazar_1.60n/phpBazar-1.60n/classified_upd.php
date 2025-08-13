<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_upd.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Add or edit a classified record
#
#################################################################################################



#  Connect to the DB

#################################################################################################



mysql_connect($server, $db_user, $db_pass);





#  Init Variables

#################################################################################################

$username=$login_check[0];

$userid=$login_check[1];





#  Process

#################################################################################################



if (!$catid && !$subcatid && !$editadid) {



$result = mysql_db_query($database, "SELECT * FROM adcat ORDER by id") or died("Database Query Error");

while ($db = mysql_fetch_array($result)) {

    $optioncat .= "<option value=\"$db[id]\">$db[name]</option>";

    }



echo "<br>\n";

echo "<form action=classified.php METHOD=GET>\n";

echo "<table align=\"center\" width=\"100%\">\n";

echo "<input type=\"hidden\" name=\"choice\" value=\"add\">\n";

echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_user </div></td>\n";

echo "<td class=\"classadd2\">$username</td>\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_ip</div></td>\n";

echo "<td class=\"classadd2\">$ip</td>\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_cat</div></td>\n";

echo "<td class=\"classadd2\"><select name=\"catid\">\n";

echo "$optioncat</select></td>\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd2\"></td>\n";

echo "<td class=\"classadd2\"><br><input type=submit value=$adadd_submit></td>\n";

echo "</tr>\n";

echo "</table>\n";

echo "</form>\n";







} elseif (!$subcatid && !$editadid) {



if ($sales_option) {

    if (!sales_checkaccess(2,$userid,$catid)) { // check access for user and cat

	open_sales_window();

	echo "<script language=javascript>location.replace('classified.php');</script>";

    }

}



$catresult = mysql_db_query($database, "SELECT * FROM adcat WHERE id=$catid") or died("Database Query Error");

$dbcat = mysql_fetch_array($catresult);



$result = mysql_db_query($database, "SELECT * FROM adsubcat WHERE catid=$catid ORDER by id") or died("Database Query Error");



while ($db = mysql_fetch_array($result)) {

    $optionsubcat .= "<option value=$db[id]>$db[name]</option>";

    }



echo "<br>\n";

echo "<form action=classified.php METHOD=GET>\n";

echo "<table align=\"center\" width=\"100%\">\n";

echo "<input type=\"hidden\" name=\"choice\" value=\"add\">\n";

echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_user </div></td>\n";

echo "<td class=\"classadd2\">$username</td>\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_ip</div></td>\n";

echo "<td class=\"classadd2\">$ip</td>\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_cat</div></td>\n";

echo "<td class=\"classadd2\">$dbcat[name]</td>\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_subcat</div></td>\n";

echo "<td class=\"classadd2\"><select name=\"subcatid\">\n";

echo "$optionsubcat</select></td>\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd2\"></td>\n";

echo "<td class=\"classadd2\"><br><input type=submit value=$adadd_submit></td>\n";

echo "</tr>\n";

echo "</table>\n";

echo "</form>\n";



} elseif ($subcatid || $editadid) {



if ($sales_option) {

    if (!sales_checkaccess(2,$userid,$catid)) { // check access for user and cat

	open_sales_window();

	echo "<script language=javascript>location.replace('classified.php');</script>";

    }

}





if (strstr (getenv('HTTP_USER_AGENT'), 'MSIE')) { // Internet Explorer Detection

    $field_size="50";

    $text_field_size="31";

    $input_field_size="28";

} else {

    // Netscape code

    $field_size="28";

    $text_field_size="20";

    $input_field_size="14";

}



unset($db);						// reset value



if ($editadid) {

    if (!$login_check[2]) {$userstr="AND userid='$userid'";}

    $result = mysql_db_query($database, "SELECT * FROM ads WHERE id='$editadid' $userstr") or died("Database Query Error");

    $db = mysql_fetch_array($result);

    $subcatid=$db[subcatid];

    $result2 = mysql_db_query($database, "SELECT * FROM userdata WHERE id=$db[userid]") or died("Database Query Error");

    $dbu = mysql_fetch_array($result2);

    $userid=$db[userid];

    $username=$dbu[username];

}



if ($logging_enable && $floodprotect && $floodprotect_ad && $login_check && !$login_check[2]) { // check floodprotect

    $checktimestamp = $timestamp-(3600*$floodprotect);

    $result = mysql_db_query($database, "SELECT timestamp FROM logging WHERE event='AD: new' AND username='$login_check[0]' AND timestamp>'$checktimestamp'") or died("Database Query Error".mysql_error());

    $count=mysql_num_rows($result);

    if ($floodprotect_ad<=$count) {

        died ("Floodprotect active !!! $count events logged last $floodprotect hour(s)");

    }

}





$subcatresult = mysql_db_query($database, "SELECT * FROM adsubcat WHERE id=$subcatid") or died("Database Query Error");

$dbsubcat = mysql_fetch_array($subcatresult);

$catresult = mysql_db_query($database, "SELECT * FROM adcat WHERE id=$dbsubcat[catid]") or died("Database Query Error");

$dbcat = mysql_fetch_array($catresult);



$cat=$dbcat[name];

$catid=$dbcat[id];

$subcat=$dbsubcat[name];

$subcatid=$dbsubcat[id];



if ($addurations == "week" || $addurations == "day") {

    for ($i=0;$i<count($adduration);$i++) {

	if ($addurations == "week") {

	    $ii=$adduration[$i]*7;

	} else {

	    $ii=$adduration[$i];

	}

	$optionduration .= "<option value=$ii>$adduration[$i]</option>";

    }

}



echo "<br>\n";

echo "<form enctype=\"multipart/form-data\" action=classified_upd_submit.php METHOD=POST>\n";

echo "<table align=\"center\" width=\"100%\">\n";

echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_user </div></td>\n";

echo "<td class=\"classadd2\">$username</td>\n";

echo "<input type=\"hidden\" name=\"in[userid]\" value=\"$userid\">\n";

echo "<input type=\"hidden\" name=\"in[uname]\" value=\"$username\">\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_ip</div></td>\n";

echo "<td class=\"classadd2\">$ip</td>\n";

echo "<input type=\"hidden\" name=\"in[adipaddr]\" value=\"$ip\">\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_cat</div></td>\n";

echo "<td class=\"classadd2\">$cat</td>\n";

echo "<input type=\"hidden\" name=\"in[catid]\" value=\"$catid\">\n";

echo "<input type=\"hidden\" name=\"in[cat]\" value=\"$cat\">\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_subcat</div></td>\n";

echo "<td class=\"classadd2\">$subcat</td>\n";

echo "<input type=\"hidden\" name=\"in[subcatid]\" value=\"$subcatid\">\n";

echo "<input type=\"hidden\" name=\"in[subcat]\" value=\"$subcat\">\n";

echo "</tr>\n";





if ($addurations == "week" || $addurations == "day") {

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_dur</div></td>\n";

    echo "<td class=\"classadd2\"><select name=\"in[duration]\">\n";

    echo "$optionduration</select> ";

    if ($addurations == "week") {

	echo "$adadd_durweeks";

    } else {

	echo "$adadd_durdays";

    }



    echo "</td>\n";

    echo "</tr>\n";

} else {

    echo "<input type=\"hidden\" name=\"in[duration]\" value=\"99999\">\n";

}



echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_loc</div></td>\n";

if ($location_text) {

    echo "<td class=\"classadd2\"><input type=text name=\"in[location]\" size=\"$field_size\" maxlength=\"50\" value=\"$db[location]\">\n";

} else {

    echo "<td class=\"classadd2\"><select name=\"in[location]\">\n";

    if ($db[location]) {

	echo "<option value=\"$db[location]\" SELECTED>$db[location]</option>\n";

    } else {

	echo "<option value=\"0\" SELECTED>$location_sel</option>\n";

    }

    include ("$language_dir/location.inc");

    echo "</select></td>\n";

}

echo "</tr>\n";



// Fields of this Category



if ($dbcat["sfield"]) {

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$dbcat[sfield]$adadd_fieldend</div></td>\n";

    echo "<td class=\"classadd2\"><input type=text name=\"in[sfield]\" size=\"$field_size\" maxlength=\"50\" value=\"$db[sfield]\"></td>\n";

    echo "</tr>\n";

}



for ($i=1;$i<=20;$i++) {

    if ($dbcat["field".$i]) {

	$adadd_field=("field".$i);

	echo adfield($catid,$adadd_field,"$dbcat[$adadd_field]","$db[$adadd_field]");

    }

}



// Icon's of this Category



for ($i=1;$i<=10;$i++) {

    $stricon="icon".$i;

    $striconalt="icon".$i."alt";

    if ($dbcat["$stricon"] && adfield($catid,"$stricon")) {

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

    echo "$iconstr";

    echo "<br>\n";



    for ($i=1;$i<=10;$i++) {

	if ($db["icon".$i]) {$checked[$i]="checked";}

	if ($dbcat["icon$i"] && adfield($catid,"icon$i")) {echo "<input type=\"checkbox\" name=\"in[icon$i]\" $checked[$i]>\n";}

    }



    echo "</td></tr>\n";



}



// Text



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_head </div></td>\n";

echo "<td class=\"classadd2\"><input type=text name=\"in[header]\" size=\"$field_size\" maxlength=\"50\" value=\"$db[header]\"></td>\n";

echo "</tr>\n";



echo "<tr>\n";

echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_text </div><br>\n";

echo "<div class=\"mainpages\"><a href=\"smiliehelp.php\"

      onClick='enterWindow=window.open(\"smiliehelp.php\",\"Smilie\",

      \"width=250,height=450,top=100,left=100,scrollbars=yes\"); return false'

      onmouseover=\"window.status='$smiliehelp'; return true;\"

      onmouseout=\"window.status=''; return true;\">$smiliehelp</a>&nbsp&nbsp\n";

echo "</div></td>\n";

$text=decode_msg($db[text]);

echo "<td class=\"classadd2\"><textarea rows=\"8\" name=\"in[text]\" cols=\"$text_field_size\">$text</textarea></td>\n";

echo "</tr>\n";



if ($convertpath && $pic_enable) {

    if ($db[picture]) {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";

	echo "<td class=\"classadd2\"><input type=text name=in[picture] value=$db[picture] READONLY>\n";

	echo "<input type=hidden name=in[_picture] value=$db[_picture]>\n";

	echo "<input type=\"checkbox\" name=\"pic1del\"> $adadd_delatt</div>\n";

	echo "</td>\n";

	echo "</tr>\n";

    } else {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";

	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";

	echo "<td class=\"classadd2\"><input type=file name=\"pic1\" size=\"$input_field_size\" maxlength=\"50\" value=\"\"><br>\n";

	echo "</td>\n";

	echo "</tr>\n";

    }



    if ($db[picture2]) {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";

	echo "<td class=\"classadd2\"><input type=text name=in[picture2] value=$db[picture2] READONLY>\n";

	echo "<input type=hidden name=in[_picture2] value=$db[_picture2]>\n";

	echo "<input type=\"checkbox\" name=\"pic2del\"> $adadd_delatt</div>\n";

	echo "</td>\n";

	echo "</tr>\n";

    } else {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";

	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";

	echo "<td class=\"classadd2\"><input type=file name=\"pic2\" size=\"$input_field_size\" maxlength=\"50\" value=\"\"><br>\n";

	echo "</td>\n";

	echo "</tr>\n";

    }



    if ($db[picture3]) {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";

	echo "<td class=\"classadd2\"><input type=text name=in[picture3] value=$db[picture3] READONLY>\n";

	echo "<input type=hidden name=in[_picture3] value=$db[_picture3]>\n";

	echo "<input type=\"checkbox\" name=\"pic3del\"> $adadd_delatt</div>\n";

	echo "</td>\n";

	echo "</tr>\n";

    } else {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";

	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";

	echo "<td class=\"classadd2\"><input type=file name=\"pic3\" size=\"$input_field_size\" maxlength=\"50\" value=\"\"><br>\n";

	echo "<div class=\"mainmenu\">[max. $pic_maxsize Byte, $adadd_picnos]</div></td>\n";

	echo "</tr>\n";

    }



}



if ($att_enable) {



    if ($db[attachment1]) {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_att </div></td>\n";

	echo "<td class=\"classadd2\"><input type=text name=in[att1] value=$db[attachment1] READONLY>\n";

	echo "<input type=\"checkbox\" name=\"att1del\"> $adadd_delatt</div>\n";

	echo "</td>\n";

	echo "</tr>\n";

    } else {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_att </div></td>\n";

	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$att_maxsize\">\n";

	echo "<td class=\"classadd2\"><input type=file name=\"att1\" size=\"$input_field_size\" maxlength=\"50\" value=\"\"><br>\n";

	echo "</td>\n";

	echo "</tr>\n";

    }



    if ($db[attachment2]) {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_att </div></td>\n";

	echo "<td class=\"classadd2\"><input type=text name=in[att2] value=$db[attachment2] READONLY>\n";

	echo "<input type=\"checkbox\" name=\"att2del\"> $adadd_delatt</div>\n";

	echo "</td>\n";

	echo "</tr>\n";

    } else {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_att </div></td>\n";

	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$att_maxsize\">\n";

	echo "<td class=\"classadd2\"><input type=file name=\"att2\" size=\"$input_field_size\" maxlength=\"50\" value=\"\"><br>\n";

	echo "</td>\n";

	echo "</tr>\n";

    }



    if ($db[attachment3]) {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_att </div></td>\n";

	echo "<td class=\"classadd2\"><input type=text name=in[att3] value=$db[attachment3] READONLY>\n";

	echo "<input type=\"checkbox\" name=\"att3del\"> $adadd_delatt</div>\n";

	echo "</td>\n";

	echo "</tr>\n";

    } else {

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_att </div></td>\n";

	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$att_maxsize\">\n";

	echo "<td class=\"classadd2\"><input type=file name=\"att3\" size=\"$input_field_size\" maxlength=\"50\" value=\"\"><br>\n";

	echo "<div class=\"mainmenu\">[max. $att_maxsize Byte, $adadd_attnos]</div></td>\n";

	echo "</tr>\n";

    }

}



if ($editadid) {

echo "<input type=\"hidden\" name=\"editadid\" value=\"$editadid\">\n";

}



echo "<tr>\n";

echo "<td class=\"classadd1\"></td>\n";

echo "<td class=\"classadd2\"><div class=\"mainmenu\"><br><input type=submit value=$adadd_submit>\n";

echo " $adadd_submitone</div></td>\n";

echo "</tr>\n";

echo "</table>\n";

echo "</form>\n";



} else {

died("Fatal ERROR");

}



mysql_close();



?>