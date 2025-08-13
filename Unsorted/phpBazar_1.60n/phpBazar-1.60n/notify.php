<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_notify_del.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Delete notify categories
#
#################################################################################################



#  Include Configs & Variables

#################################################################################################

require ("library.php");



#  HTML Header Start

#################################################################################################

?>



<html>

 <head>

  <title>Notify</title>

  <link rel="stylesheet" type="text/css" href="<? echo $STYLE;?>">

  <? echo "$lang_metatag\n"; ?>

 </head>



<?



$login_check = $authlib->is_logged();



if (!$login_check) {

    include ("$language_dir/nologin.inc");

} else {



#  Connect to the DB

#################################################################################################



mysql_connect($server, $db_user, $db_pass);

$uid=$login_check[1];



#  Get Entrys for current page

#################################################################################################

if ($delid && $confirm) {  		// Delete



    $result = mysql_db_query($database, "SELECT * FROM notify WHERE subcatid='$delid' AND userid='$uid'");

    $db = mysql_fetch_array($result);



    if ($db) {



	mysql_db_query($database,"delete from notify WHERE subcatid='$delid' AND userid='$uid'")

	or died("Database Query Error");



        echo "<div class=\"mainheader\">$notifydel_head</div>\n";

	echo "<br>\n";

	echo "<div class=\"smsubmit\">$notifydel_done<br><br>\n";



	echo "<form action=javascript:window.opener.location.reload();window.close(); METHOD=POST><input type=submit value=$close></form>\n";

	echo "</div>\n";





    } else {

	died ("FATAL Error !!!");

    }

} elseif ($addid) {

    echo "<div class=\"mainheader\">$notify_head</div>\n";

    echo "<div class=\"maintext\"><br><center>\n";

    $query = mysql_db_query($database, "SELECT * FROM notify WHERE userid=$uid AND subcatid=$addid") or died ("Database Error");

    $result = mysql_num_rows($query);

    if ($result<1) {

        $query = mysql_db_query($database, "INSERT INTO notify VALUES('$uid','$addid')") or died ("Database Error");

        echo "$notify_done<br>\n";

    } else {

        echo "$notify_exist<br>\n";

    }

    echo "<br><form action=javascript:window.close() METHOD=POST>\n";

    echo "<input type=submit value=$close></form>\n";

    echo "</center></div>\n";



} elseif ($delid) {          		// Ask for sure



    $result = mysql_db_query($database, "SELECT * FROM notify WHERE subcatid='$delid' AND userid='$uid'");

    $db = mysql_fetch_array($result);



    if ($db) {

        echo "<div class=\"mainheader\">$notifydel_head</div>\n";



	echo "<br>\n";

        echo "<form action=\"$SELF_PHP\" METHOD=\"POST\">\n";

	echo "<div class=\"smsubmit\">$notifydel_msg<br>(ID: $db[subcatid])\n";

	echo "<input type=\"hidden\" name=\"delid\" value=\"$delid\">\n";

        echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";

        echo "<br><br><input type=submit value=$admydel_submit>\n";

	echo "</div></form>\n";



    } else {

	died ("FATAL Error !!!");

    }





} else {				// Error

died ("FATAL Error !!!");

}



mysql_close();

}					// from Login_Check



?>





</body>

</html>