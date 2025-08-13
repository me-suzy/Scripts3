<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: login.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Member's login
#
#################################################################################################



require ("library.php");



if (strstr("$loginlink","errormessage")) {

    $loginlink=substr("$loginlink",0,(strpos("$loginlink","errormessage")-1));

}



if (substr(getenv('SERVER_SOFTWARE'),0,6)=="Apache"
) {

    if (strstr("$loginlink","?")) {

	$headerstr="Location: ".$loginlink."&";

    } else {

	$headerstr="Location: ".$loginlink."?";

    }

} else {

    $headerstr="Refresh: 0;url=$url_to_start/main.php?";

}



if (!$username || !$password) {

    header($headerstr."status=3");

} else {

    $login = $authlib->login($username, $password);



    if ($login!="2") {

	$errormessage=rawurlencode($login);

	header($headerstr."status=2&errormessage=$errormessage");

    } else {

	// clear useronline (guest entry)

    	mysql_connect($server, $db_user, $db_pass);

    	mysql_db_query($database, "DELETE FROM useronline WHERE ip='$ip' AND username=''");

	mysql_close();

	header($headerstr."status=1");

    }

}

exit;

?>