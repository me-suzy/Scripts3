<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_cat_show.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Show the classified cat entry's
#
#################################################################################################





#  Connect to the DB

#################################################################################################

mysql_connect($server, $db_user, $db_pass) or died("Database NOT connected");



#  Main Categories

#################################################################################################

if (!$catid) {  // show the main categories



echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

echo "<tr><td><div class=\"maincatnav\">";

echo "$ad_home\n";

echo "</div>";

echo "</td></tr></table>";





$result = mysql_db_query($database, "SELECT * FROM adcat ORDER by id") or died("Record NOT Found");



echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";



while ($db = mysql_fetch_array($result)) {



    echo " <tr>\n";

    echo "   <td class=\"classcat1\">\n";

    if ($db[picture]) { echo "<img src=\"$db[picture] \">\n";} else {echo "&nbsp;";}

    echo "   </td>\n";

    echo "   <td class=\"classcat2\">\n";

    if ($sales_option) {

	if ($db[sales]) {

	    if ($db[sales]==1) {

		$salesalt=$sales_lang_paid1;

	    } elseif ($db[sales]==2) {

		$salesalt=$sales_lang_paid2;

	    } elseif ($db[sales]==3) {

		$salesalt=$sales_lang_paid3;

	    }

	    echo "<img src=\"$image_dir/cats/paid".$db[sales].".gif\" border=\"0\" align=\"right\"

		    alt=\"$salesalt\"

		    onmouseover=\"window.status='$salesalt'; return true;\"

		    onmouseout=\"window.status=''; return true;\">\n";

	}

    }

    echo "   <a href=\"classified.php?catid=$db[id]\" onmouseover=\"window.status='$db[description]';

		return true;\" onmouseout=\"window.status=''; return true;\">$db[name]</a> ($db[ads])<br>\n";

    echo "   <div class=\"smallleft\">\n";

    echo "   $db[description]<br>\n";



    if ($db[passphrase]) {

	echo "<img src=\"$image_dir/icons/key.gif\" alt=\"$cat_pass\" align=\"right\" vspace=\"2\"

    	    onmouseover=\"window.status='$cat_pass'; return true;\"

    	    onmouseout=\"window.status=''; return true;\">";

    }



    if ($show_newicon) {

	$query = mysql_db_query($database, "SELECT id FROM ads WHERE catid='$db[id]' AND (TO_DAYS(addate)>TO_DAYS(now())-$show_newicon)") or died(mysql_error());

	if (mysql_num_rows($query)) {

	    echo "<img src=\"$image_dir/icons/new.gif\" alt=\"$cat_new\" align=\"right\" vspace=\"2\"

    	        onmouseover=\"window.status='$cat_new'; return true;\"

	        onmouseout=\"window.status=''; return true;\">";

	}

    }



    echo "   </div>";

    echo "   </td>\n";



    $db = mysql_fetch_array($result);



    echo "   <td class=\"classcat1\">\n";

    if ($db[picture]) { echo "<img src=\"$db[picture] \">\n";} else {echo "&nbsp;";}

    echo "   </td>\n";

    echo "   <td class=\"classcat2\">\n";

    if ($sales_option) {

	if ($db[sales]) {

	    if ($db[sales]==1) {

		$salesalt=$sales_lang_paid1;

	    } elseif ($db[sales]==2) {

		$salesalt=$sales_lang_paid2;

	    } elseif ($db[sales]==3) {

		$salesalt=$sales_lang_paid3;

	    }

	    echo "<img src=\"$image_dir/cats/paid".$db[sales].".gif\" border=\"0\" align=\"right\"

		    alt=\"$salesalt\"

		    onmouseover=\"window.status='$salesalt'; return true;\"

		    onmouseout=\"window.status=''; return true;\">\n";

	}

    }

    if ($db) {

	echo "   <a href=\"classified.php?catid=$db[id]\" onmouseover=\"window.status='$db[description]';

		return true;\" onmouseout=\"window.status=''; return true;\">$db[name]</a> ($db[ads])<br>\n";

	echo "   <div class=\"smallleft\">\n";

	echo "   $db[description]<br>\n";



	if ($db[passphrase]) {

	    echo "<img src=\"$image_dir/icons/key.gif\" alt=\"$cat_pass\" align=\"right\" vspace=\"2\"

    	    onmouseover=\"window.status='$cat_pass'; return true;\"

    	    onmouseout=\"window.status=''; return true;\">";

	}



	if ($show_newicon) {

	    $query = mysql_db_query($database, "SELECT id FROM ads WHERE catid='$db[id]' AND (TO_DAYS(addate)>TO_DAYS(now())-$show_newicon)") or died(mysql_error());

	    if (mysql_num_rows($query)) {

		echo "<img src=\"$image_dir/icons/new.gif\" alt=\"$cat_new\" align=\"right\" vspace=\"2\"

    	    	    onmouseover=\"window.status='$cat_new'; return true;\"

	    	    onmouseout=\"window.status=''; return true;\">";

	    }

	}

    } else {

	echo "&nbsp;";

    }



    echo "   </div>";

    echo "   </td>\n";

    echo " </tr>\n";

    } //End while



#  Sub Categories

#################################################################################################

} else { // show the subcategories



if ($sales_option) {

    if ($login_check) {$userid=$login_check[1];}

    if (!sales_checkaccess(1,$userid,$catid)) {	// check access for user and cat

	open_sales_window();

	#echo "<script language=javascript>location.replace('classified.php?textmessage=$sales_lang_noaccess');</script>";

	echo "<script language=javascript>location.replace('classified.php');</script>";



    }

}



$result = mysql_db_query($database, "SELECT * FROM adcat WHERE id=$catid") or died("Record NOT Found");

$db = mysql_fetch_array($result);



echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

echo "<tr><td><div class=\"maincatnav\">";

echo "<a href=\"classified.php\" onmouseover=\"window.status='$ad_home'; return true;\"

        onmouseout=\"window.status=''; return true;\">$ad_home</a> / $db[name]\n";

echo "</td></tr></table>";

echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";





$result = mysql_db_query($database, "SELECT * FROM adsubcat where catid=$catid ORDER by id") or died("Record NOT Found");



while ($db = mysql_fetch_array($result)) {



    $resultc = mysql_db_query($database, "SELECT * FROM adcat where id=$catid") or died("Record NOT Found");

    $dbc = mysql_fetch_array($resultc);



    echo " <tr>\n";

    echo "   <td class=\"classcat1\">\n";

    if ($db[picture]) { echo "<img src=\"$db[picture] \">\n";} else {echo "&nbsp;";}

    echo "   </td>\n";

    echo "   <td class=\"classcat2\">\n";

    echo "   <a href=\"classified.php?catid=$catid&subcatid=$db[id]\" onmouseover=\"window.status='$db[description]';

		return true;\" onmouseout=\"window.status=''; return true;\">$db[name]</a> ($db[ads])<br>\n";

    echo "   <div class=\"smallleft\">\n";

    echo "   $db[description]<br>\n";

    if ($catnotify && $db[id] && $login_check) {

	echo "   <a href=\"notify.php?addid=$db[id]\"

    	    onClick='enterWindow=window.open(\"notify.php?addid=$db[id]\",\"Notify\",\"width=400,height=200,top=200,left=200\"); return false'

    	    onmouseover=\"window.status='$notify_add'; return true;\"

    	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/mail.gif\" border=\"0\" alt=\"$notify_add\" align=\"right\" vspace=\"2\"></a>\n";

    }



    if ($dbc[passphrase]) {

	echo "<img src=\"$image_dir/icons/key.gif\" alt=\"$cat_pass\" align=\"right\" vspace=\"2\"

    	    onmouseover=\"window.status='$cat_pass'; return true;\"

    	    onmouseout=\"window.status=''; return true;\">";

    }



    if ($show_newicon) {

	$query = mysql_db_query($database, "SELECT id FROM ads WHERE subcatid='$db[id]' AND (TO_DAYS(addate)>TO_DAYS(now())-$show_newicon)") or died(mysql_error());

	if (mysql_num_rows($query)) {

	    echo "<img src=\"$image_dir/icons/new.gif\" alt=\"$cat_new\" align=\"right\" vspace=\"2\"

    		onmouseover=\"window.status='$cat_new'; return true;\"

		onmouseout=\"window.status=''; return true;\">";

	}

    }



    echo "   </div>";

    echo "   </td>\n";



    $db = mysql_fetch_array($result);



    $resultc = mysql_db_query($database, "SELECT * FROM adcat where id=$catid") or died("Record NOT Found");

    $dbc = mysql_fetch_array($resultc);



    echo "   <td class=\"classcat1\">\n";

    if ($db[picture]) { echo "<img src=\"$db[picture] \">\n";} else {echo "&nbsp;";}

    echo "   </td>\n";

    echo "   <td class=\"classcat2\">\n";

    if ($db) {

    echo "   <a href=\"classified.php?catid=$catid&subcatid=$db[id]\" onmouseover=\"window.status='$db[description]';

		return true;\" onmouseout=\"window.status=''; return true;\">$db[name]</a> ($db[ads])<br>\n";

    }

    echo "   <div class=\"smallleft\">\n";

    echo "   $db[description]<br>\n";

    if ($catnotify && $db[id] && $login_check) {

	echo "   <a href=\"notify.php?addid=$db[id]\"

    	    onClick='enterWindow=window.open(\"notify.php?addid=$db[id]\",\"Notify\",\"width=400,height=200,top=200,left=200\"); return false'

    	    onmouseover=\"window.status='$notify_add'; return true;\"

    	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/mail.gif\" border=\"0\" alt=\"$notify_add\" align=\"right\" vspace=\"2\"></a>\n";



	if ($dbc[passphrase]) {

	    echo "<img src=\"$image_dir/icons/key.gif\" alt=\"$cat_pass\" align=\"right\" vspace=\"2\"

    	    onmouseover=\"window.status='$cat_pass'; return true;\"

    	    onmouseout=\"window.status=''; return true;\">";

	}



	if ($show_newicon) {

	    $query = mysql_db_query($database, "SELECT id FROM ads WHERE subcatid='$db[id]' AND (TO_DAYS(addate)>TO_DAYS(now())-$show_newicon)") or died(mysql_error());

	    if (mysql_num_rows($query)) {

		echo "<img src=\"$image_dir/icons/new.gif\" alt=\"$cat_new\" align=\"right\" vspace=\"2\"

        	    onmouseover=\"window.status='$cat_new'; return true;\"

		    onmouseout=\"window.status=''; return true;\">";

	    }

	}

    }



    echo "   </div>";

    echo "   </td>\n";

    echo " </tr>\n";



    } //End while





}



# End of Page reached

#################################################################################################

echo "</table>\n";





#  Disconnect DB

#################################################################################################

mysql_close();

?>