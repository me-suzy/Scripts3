<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: smiliehelp.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Show's the Smilie DB
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

  <title>Smilie Help</title>

  <link rel="stylesheet" type="text/css" href="<? echo $STYLE;?>">

  <? echo "$lang_metatag\n"; ?>

 </head>



<?

echo"<body bgcolor=\"$bgcolor\">\n";

$login_check = $authlib->is_logged();



if (!$login_check) {

    include ("$language_dir/nologin.inc");

} else {



#  Connect to the DB

#################################################################################################



mysql_connect($server, $db_user, $db_pass);





#  Get Entrys for current page

#################################################################################################



    echo "<table>\n";

    echo "<tr>\n";

    echo "<td><div class=\"maininputleft\">Code</div></td>\n";

    echo "<td></td>\n";

    echo "<td><div class=\"maintext\"></div></td>\n";

    echo "</tr>\n";



    $result = mysql_db_query($database, "SELECT * FROM smilies") or died("Query Error");

    while ($db = mysql_fetch_array($result)) {



	echo "<tr>\n";

        echo "<td><div class=\"maininputleft\">$db[code]</div></td>\n";

	echo "<td>&nbsp&nbsp<img src=".$image_dir."/smilies/".$db[file]."></td>\n";

        echo "<td><div class=\"maintext\">$db[name]</div></td>\n";

	echo "</tr>\n";



    }



    echo "</table>\n";



mysql_close();

}					// from Login_Check



?>





</body>

</html>