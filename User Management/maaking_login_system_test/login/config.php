<?
###########################################
#-----------Users login system------------#
###########################################
/*=========================================\
Author      :  Mohammed Ahmed(M@@king)    \\
Version     :  1.0                        \\
Date Created:  Aug 20  2005               \\
----------------------------              \\
Last Update:   August 22 2005             \\
----------------------------              \\
Country    :   Palestine                  \\
City       :   Gaza                       \\
E-mail     :   m@maaking.com              \\
MSN        :   m@maaking.com              \\
AOL-IM     :   maa2pal                    \\
WWW        :   http://www.maaking.com     \\
Mobile/SMS :   00972-599-622235           \\
                                          \\
===========================================\
------------------------------------------*/
//skip the config file if somebody call it from the browser.
if (eregi("config.php", $_SERVER['SCRIPT_NAME'])) {
    Header("Location: index.php"); die();
}

//your databse hostname.
$dbhost = "localhost";
//your database username.
$dbuname = "";
//your db password
$dbpass = "";
$dbname = "database_name_here";
//don't change unless you change this value in the db.
$prefix = "maaking";

//change this
$site_name  = "maaking.com user login system";
$site_email = "m@maaking.com";
$site_url = "http://www.maaking.com/";

//added new code to fix compatibility issues.
//09-Nov-2005
$phpver = phpversion();
if ($phpver < '4.1.0') {
	$_GET = $HTTP_GET_VARS;
	$_POST = $HTTP_POST_VARS;
	$_SERVER = $HTTP_SERVER_VARS;
}
if ($phpver >= '4.0.4pl1' && strstr($_SERVER["HTTP_USER_AGENT"],'compatible')) {
	if (extension_loaded('zlib')) {
		ob_end_clean();
		ob_start('ob_gzhandler');
	}
} else if ($phpver > '4.0') {
	if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip')) {
		if (extension_loaded('zlib')) {
			$do_gzip_compress = TRUE;
			ob_start(array('ob_gzhandler',5));
			ob_implicit_flush(0);
			header('Content-Encoding: gzip');
		}
	}
}
$phpver = explode(".", $phpver);
$phpver = "$phpver[0]$phpver[1]";
if ($phpver >= 41) {
	$PHP_SELF = $_SERVER['PHP_SELF'];
}

if (!ini_get("register_globals")) {
	import_request_variables('GPC');
}


include("mysql.class.php");
$db = new sql_db($dbhost, $dbuname, $dbpass, $dbname, false);
if(!$db->db_connect_id) {
      include("header.php");

      echo "<br><font color=red><h3><br><center>Error:</b><br><hr><br>
            <b>Connection to database faild</center>
            <br><br><br><br><br><br><br><br><br>";

      include("footer.php");
      exit();
}


//global function for checkig user is logged in or not.
//you will notice we will use it everwhere in the script.
function is_logged_in($user) {
    global $db,$prefix;

    if(!is_array($user)) {

	$user = explode("|", base64_decode($user));
        $uid = "$user[0]";
	$pwd = "$user[2]";
    } else {
        $uid = "$user[0]";
	$pwd = "$user[2]";
    }
    $uid = addslashes($uid);
        $uid = intval($uid);
    if ($uid != "" AND $pwd != "") {
        $result = mysql_query("SELECT password FROM ".$prefix."_users WHERE userid='$uid'");
	$row = mysql_fetch_array($result);
        $pass = $row['password'];
	if($pass == $pwd && $pass != "") {

           return 1;
	}
    }
    return 0;
}

?>
