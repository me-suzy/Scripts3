<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: left.inc
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Left Side
#
#################################################################################################





$login_check = $authlib->is_logged();



if (strstr (getenv('HTTP_USER_AGENT'), 'MSIE')) { 	// Internet Explorer Detection

    $field_size="20";

} else {    						// Netscape code

    $field_size="11";

}





# Status Window

#################################################################################################



echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\" height=\"40\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"40\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";

echo"        <div class=\"sideheader\">$status_header</div>\n";

echo"        <div class=\"sideleft\">$status_msg[$status]</div>\n";

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";



include ("spacer.inc");



if ($errormessage) {

#    $errormessage=rawurlencode($errormessage);

    echo "<script language=\"JavaScript\">

    var winl = (screen.width - 300) / 2;

    var wint = (screen.height - 150) / 2;

    window.open(\"message.php?msg=$errormessage&msgheader=$msghead_error\",\"Error\",\"width=300,height=150,top=\"+wint+\",left=\"+winl+\",resizeable=no\");

    </script>\n";

}



if ($textmessage) {

#    $textmessage=rawurlencode($textmessage);

    echo "<script language=\"JavaScript\">

    var winl = (screen.width - 300) / 2;

    var wint = (screen.height - 150) / 2;

    window.open(\"message.php?msg=$textmessage&msgheader=$msghead_message\",\"Message\",\"width=300,height=150,top=\"+wint+\",left=\"+winl+\",resizeable=no\");

    </script>\n";

}

if ($show_languages) {

    $raw_url=rawurlencode($url_to_start."/main.php?status=5");

    echo"

    <SCRIPT LANGUAGE=\"JavaScript\"><!--

        function changelang(newlang) {

            exit=false;

            site = \"lang.php?language=\"+(newlang)+\"&url=$raw_url\";

            if (newlang!=0) {

                top.location.href=site;

            } else {

                top.location.href=\"list.php\";

            }

        }

     //--></SCRIPT>";



    $langstr.="<td align=\"right\"><div class=\"smallright\">\n";

    $langstr.="<select name=\"lang\" onchange=\"changelang(this.options[this.selectedIndex].value)\">\n";

    for($i = 0; $i<count($language); $i++) {

        if ($language[$i]==$language_user) {$selected="SELECTED";} else {$selected="";}

        $langstr.= "<option value=\"$language[$i]\" $selected>$language[$i]</option>\n";

    }

    $langstr.="</select>\n";

    $langstr.="</div></td>\n";

}



# Login Window

#################################################################################################

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\" height=\"175\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"175\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";





if (!$login_check) {



    if ($login == "lostpass"){

	echo"          <form method=\"post\" action=\"lostpass.php\" name=\"\">\n";

	echo"          <div class=\"sideheader\">$lostpw_header</div>\n";

	echo"          <table width=\"100%\">\n";

	echo"           <tr><td>\n";

	echo"            <div class=\"sideleft\">\n";

	echo"            $lostpw_email<br><input type=\"text\" name=\"email\" size=\"$field_size\" maxlength=\"50\"><br><br>\n";

	echo"            </div>\n";

	echo"           </td></tr>\n";

	echo"           <tr><td>\n";

	echo"            <input type=\"submit\" value=\"$lostpw_button\" name=\"submit\">\n";

	echo"           </td></tr>\n";

	echo"          </table>\n";

	echo"          </form>\n";

    } else {

	echo"          <form method=\"post\" action=\"login.php\" name=\"\">\n";

	echo"          <div class=\"sideheader\">$login_header</div>\n";

	echo"          <table width=\"100%\">\n";

	echo"           <tr><td colspan=\"2\">\n";

	echo"            <div class=\"sideleft\">\n";

	echo"  	         $login_username<br><input type=\"text\" name=\"username\" size=\"$field_size\" maxlength=\"22\"><br>\n";

	echo"            </div>\n";

	echo"           </td></tr>\n";

	echo"           <tr><td colspan=\"2\">\n";

	echo"            <div class=\"sideleft\">\n";

	echo"            $login_password<br><input type=\"password\" name=\"password\" size=\"$field_size\" maxlength=\"22\"><br><br>\n";

	echo"            </div>\n";

	echo"           </td></tr>\n";

	echo"           <tr><td valign=\"top\">\n";

	echo"  	         <input type=\"hidden\" name=\"loginlink\" value=\"".getenv(REQUEST_URI)."\">\n";

	echo"            <input type=\"submit\" value=\" Login \" name=\"submit\">\n";

	echo"           </td>\n";

	echo"            $langstr\n";

	echo"          </tr>\n";

	echo"          </table>\n";

	echo"          </form>\n";



	echo"          <div class=\"sideleft\">\n";

	echo"          <a href=\"main.php?login=lostpass\" onmouseover=\"window.status='$logi_link1desc'; return true;\" onmouseout=\"window.status=''; return true;\">$logi_link1</a><br>\n";

	if ($no_confirmation) {$target="_self";} else {$target="_blank";}

	echo"          <a href=\"register.php\"  target=\"$target\" onmouseover=\"window.status='$logi_link2desc'; return true;\" onmouseout=\"window.status=''; return true;\">$logi_link2</a>\n";

	echo"          </div>\n";

    }



} else {

    $membernumber=$login_check[1]+$memberoffset;

    echo"          <form method=\"post\" action=\"logout.php\" name=\"\">\n";

    echo"          <div class=\"sideheader\">$login_header</div>\n";

    echo"          <table width=\"100%\">\n";

    echo"           <tr><td colspan=\"2\">\n";

    echo"            <div class=\"sideleft\">\n";

    echo"            $login_username<br><input type=\"text\" name=\"username\" size=\"$field_size\" maxlength=\"22\" value=\"$login_check[0]\" readonly><br>\n";

    echo"            </div>\n";

    echo"           </td></tr>\n";

    echo"           <tr><td colspan=\"2\">\n";

    echo"            <div class=\"sideleft\">\n";

    echo"            $login_member<br><input type=\"text\" name=\"memberid\" size=\"$field_size\" maxlength=\"22\" value=\"$membernumber\" readonly><br><br>\n";

    echo"            </div>\n";

    echo"           </td></tr>\n";

    echo"           <tr><td valign=\"top\">\n";

    echo"            <input type=\"submit\" value=\"Logout\" name=\"submit\">\n";

    echo"           </td>\n";

    echo"           $langstr\n";

    echo"          </tr>\n";

    echo"          </table>\n";

    echo"          </form>\n";

    if ($show_useronline) {

        mysql_connect($server, $db_user, $db_pass);

        $timeout=$timestamp-300;  // value in seconds

        mysql_db_query($database, "DELETE FROM useronline WHERE timestamp<$timeout");

        $result=mysql_db_query($database, "SELECT username FROM useronline WHERE username!='' GROUP by username");

        $user =mysql_num_rows($result);

        $result=mysql_db_query($database, "SELECT ip FROM useronline WHERE username='' GROUP by ip");

        $user+=mysql_num_rows($result);

        mysql_close();

	if ($user>1) {

	    $uostr=$users_online;

	} else {

	    $uostr=$user_online;

	}

	if ($show_useronline_detail) {

    	    echo"	<div class=\"smallright\"><a href=\"useronline.php\">$user $uostr</a></div>";

	} else {

    	    echo"	<div class=\"smallright\">$user $uostr</div>";

	}

    }

    if ($login_check[2]) {		// if Moderator or Administrator

	echo"<div class=\"smallright\"><a href=\"$url_to_start/$admin_dir/admin.php\" target=\"_blank\">Admin-Panel</a></div>";

    } else {

	echo"<br>";

    }

}



echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";







# Advertising Window 1

#################################################################################################

if ($show_advert1) {



include ("spacer.inc");



echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";



    include ("$language_dir/advert1.inc");



echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";



}





# Advertising Window 2

#################################################################################################

if ($picadoftheday || $show_advert2) {



include ("spacer.inc");



echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";



if ($show_picadday) {

    include ("$language_dir/picadday.inc");



    mysql_connect($server, $db_user, $db_pass);

    $query=mysql_db_query($database, "SELECT * FROM config WHERE type='config' AND name='fix_adoftheday'") or die("Database Query Error");

    $db=mysql_fetch_array($query);

    if ($db[value]) { // Fixed AdoftheDay



	$result=mysql_db_query($database, "SELECT * FROM ads WHERE id='$db[value]'") or die("Database Query Error".mysql_error());

    	$db=mysql_fetch_array($result);



    } else {



	$result=mysql_db_query($database, "SELECT ads.* FROM ads LEFT JOIN adcat ON ads.catid=adcat.id WHERE ads.picture!= '' AND ads.publicview='1' AND adcat.passphrase=''") or die("Database Query Error".mysql_error());

	// if you like also pictures from categories with passphrase included in picoftheday rotation use the next line instead of prev line

        // $result=mysql_db_query($database, "SELECT * FROM ads WHERE picture!= '' AND publicview='1'") or die("Database Query Error".mysql_error());

	$count=mysql_num_rows($result);

        $query=mysql_db_query($database, "SELECT * FROM favorits WHERE userid>'100000000'") or die("Database Query Error".mysql_error());

	$getad=mysql_fetch_array($query);

        if ($getad<1) { //NOT found, calculate NEW ad of the day, and stor it to favorits ;-)

	    if ($count>1) {

		srand((double)microtime()*1000000);

		$dboffset=rand(0,$count-1);

		if ($dboffset>0) {

		    mysql_db_query($database, "INSERT INTO favorits VALUES('$timestamp','$dboffset')") or die("Database Query Error".mysql_error());

		}

	    }

	} else {

    	    $dboffset=$getad[adid];

    	    if (!is_int($show_picadday)) {$show_picadday=24;} 	// if no value, set to 24 hours

    	    if ($getad[userid]<($timestamp-3600*$show_picadday)) { 	// if timed out, delete it

    		mysql_db_query($database, "DELETE FROM favorits WHERE userid>'100000000'") or die("Database Query Error".mysql_error());

	    }

	}



	if ($dboffset>0 && $count>$dboffset) {

	    if (mysql_data_seek($result,$dboffset)) {

		$db=mysql_fetch_array($result);

	    }

	} else {

    	    $db=mysql_fetch_array($result);

	}



    }



    if ($db[_picture]) {        	// Thumbnail exist

	if (!$pic_database) {

    	    echo" <div class=\"smallcenter\"><a href=\"classified.php?catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]\"

		onmouseover=\"window.status='".addslashes($db[header])."'; return true;\" onmouseout=\"window.status=''; return true;\">

		<img src=\"$pic_path/$db[_picture]\" border=\"0\" vspace=\"2\" hspace=\"2\"></a></div>";

	} else {

    	    echo" <div class=\"smallcenter\"><a href=\"classified.php?catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]\"

		onmouseover=\"window.status='".addslashes($db[header])."'; return true;\" onmouseout=\"window.status=''; return true;\">

		<img src=\"picturedisplay.php?id=$db[_picture]\" border=\"0\" vspace=\"2\" hspace=\"2\"></a></div>";

	}

    } elseif ($db[picture]) {		// Calculate Thumbnail

	if (!$pic_database) {

    	    $picinfo=GetImageSize("$pic_path/$db[picture]");

    	    $picsize=explode("x",$pic_lowres);

    	    if ($picinfo[0]>intval($picsize[0]) || $picinfo[1]>intval($picsize[1])) {

        	$div[0]=$picinfo[0]/$picsize[0];

        	$div[1]=$picinfo[1]/$picsize[1];

        	if ($div[0]>$div[1]) {

            	    $sizestr="width=".intval($picinfo[0]/$div[0])." height=".intval($picinfo[1]/$div[0]);

        	} else {

            	    $sizestr="width=".intval($picinfo[0]/$div[1])." height=".intval($picinfo[1]/$div[1]);

        	}

    	    } else {

        	$sizestr=$picinfo[3];

    	    }



    	    echo" <div class=\"smallcenter\"><a href=\"classified.php?catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]\"

		onmouseover=\"window.status='".addslashes($db[header])."'; return true;\" onmouseout=\"window.status=''; return true;\">

		<img src=\"$pic_path/$db[picture]\" $sizestr border=\"0\" vspace=\"2\" hspace=\"2\"></a></div>";

	} else {

            $result = mysql_db_query($database, "SELECT * FROM pictures WHERE picture_name='$db[picture]'") or die(mysql_error());

            $dbp = mysql_fetch_array($result);

            $picsize=explode("x",$pic_lowres);

            if ($dbp[picture_width]>intval($picsize[0]) || $dbp[picture_height]>intval($picsize[1])) {

                $div[0]=$dbp[picture_width]/$picsize[0];

                $div[1]=$dbp[picture_height]/$picsize[1];

                if ($div[0]>$div[1]) {

                    $sizestr="width=".intval($dbp[picture_width]/$div[0])." height=".intval($dbp[picture_height]/$div[0]);

                } else {

                    $sizestr="width=".intval($dbp[picture_width]/$div[1])." height=".intval($dbp[picture_height]/$div[1]);

                }

            } else {

                $sizestr="width=$dbp[picture_width] height=$dbp[picture_height]";

            }



    	    echo" <div class=\"smallcenter\"><a href=\"classified.php?catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]\"

		onmouseover=\"window.status='".addslashes($db[header])."'; return true;\" onmouseout=\"window.status=''; return true;\">

		<img src=\"picturedisplay.php?id=$db[picture]\" $sizestr border=\"0\" vspace=\"2\" hspace=\"2\"></a></div>";

	}

    } else {

	echo"ERROR: Ad $db[id] Pic NOT found !";

    }



    mysql_close();



} else {

    include ("$language_dir/advert2.inc");

}



echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo"  </table>\n";



}



?>