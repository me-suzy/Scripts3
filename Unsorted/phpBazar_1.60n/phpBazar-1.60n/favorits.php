<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: favorits.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Add Ad to Favorits
#
#################################################################################################



#  Include Configs & Variables

#################################################################################################

require ("library.php");



#  HTML Header Start

#################################################################################################

?>



<DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">



<html>

 <head>

  <title>Favorites</title>

  <link rel="stylesheet" type="text/css" href="<?echo $STYLE;?>">

  <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

 </head>



<?

$login_check = $authlib->is_logged();



if (!$login_check || (!$adid && !$deladid)) {

    include ("$language_dir/nologin.inc");

} else {



#  Connect to the DB

#################################################################################################

mysql_connect($server, $db_user, $db_pass);





#  Main

#################################################################################################

echo "<div class=\"mainheader\">$favorits_header</div>\n";

echo "<div class=\"maintext\"><br><center>\n";

$uid=$login_check[1];



if ($deladid) {

    $query = mysql_db_query($database,"delete from favorits where adid='$deladid' AND userid='$uid'") or died("Database Query Error");

    echo "$favorits_del<br>\n";

    echo "<br><form action=javascript:window.opener.location.reload();window.close() METHOD=POST>\n";

    echo "<input type=submit value=$close></form>\n";

} elseif ($adid) {

    $query = mysql_db_query($database, "SELECT * FROM favorits WHERE userid=$uid AND adid=$adid") or died ("Database Error");

    $result = mysql_num_rows($query);

    if ($result<1) {

        $query = mysql_db_query($database, "INSERT INTO favorits VALUES('$uid','$adid')") or died ("Database Error");

	echo "$favorits_done<br>\n";

    } else {

	echo "$favorits_exist<br>\n";

    }

    echo "<br><form action=javascript:window.close() METHOD=POST>\n";

    echo "<input type=submit value=$close></form>\n";

} else {

    echo "ERROR !!!\n";

}



echo "</div>\n";





#  End

#################################################################################################

mysql_close();

}					// from Login_Check

echo"</body></html>"

?>