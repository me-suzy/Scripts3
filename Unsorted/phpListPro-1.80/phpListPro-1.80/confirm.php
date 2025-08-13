<?

################################################################################################

#

#  project           	: phpListPro

#  filename          	: out.php

#  last modified by  	: Erich Fuchs

#  e-mail            	: office@smartisoft.com

#  purpose           	: Count out Links

#

#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("config.php");



mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");

$id = decrypt($check,$secret);

$sql = "SELECT * FROM sites WHERE id='$id' ";

$query=mysql_db_query($database, $sql) or die(geterrdesc($sql));

$db=mysql_fetch_array($query);







if ($db) {

    if ($db[cat]>0) {

	$catlink="?cat=$db[cat]";

    }

    $sql = "UPDATE sites SET emailapproved='1' WHERE id='$id' ";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));

    echo"<html><head><title>$list_name - $lang[confirmation]</title><style type=\"text/css\">";

    include("style.css");

    echo"</style><meta http-equiv=\"refresh\" content=\"2; URL=$list_url/list.php$catlink\"></head><body>";

    echo"<div class=\"maincenter\"><p><b>$list_name - $lang[confirmation]</b></p>\n";

    echo "$lang[emailconfirmed]<p><small>$lang[redirect]</small>";

    echo "</body></html>";

} else {

    echo"<html><head><title>$list_name - $lang[confirmation]</title><style type=\"text/css\">";

    include("style.css");

    echo"</style><meta http-equiv=\"refresh\" content=\"5; URL=$list_url\"></head><body>";

    echo"<div class=\"maincenter\"><p><b>$list_name - $lang[confirmation]</b></p>\n";

    echo "$lang[emailnotconf]<p><small>$lang[redirect]</small>";

    echo "</body></html>";

}



mysql_close();

exit;





#  End

#################################################################################################

?>
