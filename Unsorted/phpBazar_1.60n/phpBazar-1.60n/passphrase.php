<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: passphrase.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Passphrase Function
#
#################################################################################################



#  Include Configs & Variables

#################################################################################################

require ("library.php");



mysql_connect($server, $db_user, $db_pass);



if ($enteredsecret && $catid) {

    $md5secret=md5($enteredsecret);

    $cookietime=time()+$passphrase_cookie_time;

    setcookie("Passphrase_$catid", "$md5secret", $cookietime, "$cookiepath");

    setcookie("PassphraseUser_$catid", "$userid", $cookietime, "$cookiepath");

    // close window

    echo "<SCRIPT language=Javascript>opener.location.href=opener.location.href;window.close();</script>";



} elseif ($catid) {



    echo"<html>\n";

    echo" <head>\n";

    echo"  <title>$pass_head</title>\n";

    echo"  <link rel=\"stylesheet\" type=\"text/css\" href=\"$STYLE\">\n";

    echo"  $lang_metatag\n";

    echo" </head>\n";

    echo"<body>\n";



    echo "<div class=\"mainheader\">$pass_head</div>\n";

    echo "<br>\n";

    echo "<table align=\"center\" width=\"100%\">\n";

    echo "<form enctype=\"text\" action=$PHP_SELF METHOD=POST>

	<div class=\"maininputleft\"><center>

        <input type=text name=\"enteredsecret\" value=\"\">

        <input type=hidden name=\"userid\" value=\"$userid\">

	<input type=hidden name=\"catid\" value=\"$catid\"><br><br>

        <input type=submit value=$submit></center></div>\n";

    echo "</form>\n";



}



mysql_close();

?>

</body>

</html>