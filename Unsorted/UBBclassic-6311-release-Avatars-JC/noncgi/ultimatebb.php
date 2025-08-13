<?PHP
// >
//	Whoops!  If you see this text in your browser,
//	your web hosting provider has not installed PHP.
//
//	You will be unable to use the UBB.classic Accelerator.
//
//	You may wish to ask your web hosting provider to install PHP.
//	Both Windows and Unix versions are available on the PHP website,
//	http://www.php.net/
//
//
//
//	UBB.classic Accelerator Version 2.3a
//
//	Originally by Philipp Esselbach <philipp@ntcompatible.com>
//	Copyright (C) 2001-2002 Infopop Corporation.
//
//	You may not distribute this program in any manner, modified or
//	otherwise, without the express, written written consent from
//	Infopop Corporation.
//
//	You may make modifications, but only for your own use and
//	within the confines of the UBB.classic License Agreement
//	(see our website for that).
//
//	You may not distribute "hacks" for UBB.classic without approval
//	from Infopop.
//
//	Note: if you modify ANY code within your UBB.classic, we at Infopop
//	Corporation cannot offer you support -- thus modify at your own peril :)

error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING );

// The previous author thought phpversion() returned an array... oops.
$php_version_stringified = phpversion();
$php_version_array = explode(".", $php_version_stringified);
$php_version_string = sprintf("%1d.%02d%02d", $php_version_array[0], $php_version_array[1], $php_version_array[2]);

if($php_version_string < 4.0000) {
	ubberror("Your PHP version, <b>$php_version_stringified</b>, is too old to run the UBB.classic Accelerator.  Please use the <a href='ubbaccel_test.php'>Accelerator Test</a> for more information.");
	exit;
} // end if

$qs = "";
$qs = find_environmental("QUERY_STRING");

// PHP won't let you define ; and & as query string seperators
// without either tinkering with php.ini or using ini_set, which
// is often extremely restricted.  This kludge was introduced
// by qasic, but has been made a little more safe.

global $ubb, $f, $t, $p, $DaysPrune, $hardset, $start_point, $category;

$ubb = ""; $f = ""; $t = ""; $p = ""; $DaysPrune = "";
$hardset = ""; $start_point = ""; $category = "";

$query_string = split('[;&]', trim($qs));
while (list (, $key_value) = each($query_string)) {
	if (preg_match('/=/', $key_value)) {
		$key_value = trim($key_value);
		list($key, $value) = explode("=", $key_value);

		if(preg_match("/^(ubb|f|t|p|daysprune|hardset|start_point|category)$/i", $key)) {
			// No hacking for you!
			global $key;
			${"$key"} = $value;
		} // end if
	} // end if
} // end if

$TheFile = "";

$fx = file_exists("./vars_config.inc.php");

if (!$fx) {
	ubberror("I was unable to locate my variables file.  Please submit Configuration Settings in your UBB.classic control panel.");
	exit;
}

require ("./vars_config.inc.php");

// If the user did not come from our UBB.classic, and if he does not
// have the session cookie, assume that he needs to be logged in...

// This only works in PHP 4.06 and higher, apparently...

if($php_version_string > 4.0005) {
	$ref = find_environmental("HTTP_REFERER");
	$this = checksession();
	$CGIURL2 = preg_replace("!\/!", "\\\/", $CGIURL);
	$NonCGIURL2 = preg_replace("!\/!", "\\\/", $NonCGIURL);
	if(($ref != "") && (!preg_match("/^($CGIURL2|$NonCGIURL2)\/ultimatebb\.(php|cgi)/", $ref)) && ($this[0] == "")) {
		$location = figurelocation();
		silly_redirection ("Location: $CGIURL/ultimatebb.cgi$location");
		exit;
	} // if
} // another if

// Get cookie
$cookie = getuserid();
$userid = $cookie[4];

// Get profile
if ($userid) {
	$profile = getprofile($userid);
} // end if

// Not logged in or closed board?
if(($MembersOnlyAccess == "YES" && !$userid) || ($BBStatus == "OFF")) {
	$location = figurelocation();
	silly_redirection ("Location: $CGIURL/ultimatebb.cgi$location", "bboff");
	exit;
}

if ($ubb) {
	if ($ubb == "forum")
		getforum ($f,$hardset,$start_point);

	if ($ubb == "get_topic")
		gettopic ($f,$t,$p);

	if ($ubb == "get_daily")
		getdaily();

	if ($ubb == "ubb_code_page")
		ubbcode();

	if ($ubb == "faq")
		faq();

} else {
	if ($category)
		getcategory($category);

	getsummary();
} // end if

exit;

// ============================================================================

function faq () {

	global $CGIURL, $NonCGIPath, $cache_pw;

	ubboutput ("$NonCGIPath/cache-$cache_pw/ubb_files/misc/faq.cgi",
		       "$CGIURL/ultimatebb.cgi?ubb=faq");

}

// ============================================================================

function ubbcode () {

	global $CGIURL, $NonCGIPath, $cache_pw;

	ubboutput ("$NonCGIPath/cache-$cache_pw/ubb_files/misc/ubb_code_page.cgi",
		       "$CGIURL/ultimatebb.cgi?ubb=ubb_code_page");

}

// ============================================================================

function getdaily () {

	global $CGIURL, $NonCGIPath, $cache_pw;

	ubboutput ("$NonCGIPath/cache-$cache_pw/ubb_files/misc/ubb_daily_topics.cgi",
		       "$CGIURL/ultimatebb.cgi?ubb=get_daily");

}

// ============================================================================

function getsummary () {

	global $CGIURL, $NonCGIPath, $cache_pw;

	ubboutput ("$NonCGIPath/cache-$cache_pw/ubb_files/summary/summary.html",
		       "$CGIURL/ultimatebb.cgi");

}

// ============================================================================

function getcategory ($cat) {

	global $CGIURL, $NonCGIPath, $cache_pw;

	ubboutput ("$NonCGIPath/cache-$cache_pw/ubb_files/summary/summary-$cat.html",
		       "$CGIURL/ultimatebb.cgi?category=$cat");

}

// ============================================================================

function gettopic ($f,$t,$p) {

	global $CGIURL, $NonCGIPath, $cache_pw, $profile;

	checkit ($f);
	checkit ($t);
	checkit ($p);

	$privateforums = array('');

	// Private forums?
	if ($profile) {
		$status = rtrim($profile[4]);
		$permissions = explode("&",$status);

		if ($permissions[1]) {
			$privateforums = explode(",",$permissions[1]);
		} // end if
	} // end if

	$privatepath = "";

	if ($privateforums) {
		for($i=0;$i<count($privateforums);$i++) {
			if ($privateforums[$i] == $f) {
				$pp = getprivate($f);
				if($pp) {
					$privatepath = "/private-$pp";
				} // end if
		  	} // end if
		} // end for
	} // end if

	if (!$privateforums && preg_match("/Admin/i", $profile[4])) {
		$privateforum = getprivate($f);
		if ($privateforum) {
			$privatepath = "/private-$privateforum";
		} // end if
	} // end if

	if ($p > 1) {
		$page = "-$p";
	} else {
		$page = "";
	} // end if

	$topicfile = "$NonCGIPath/Forum$f$privatepath/$t.cgi";
	$topicdata = file($topicfile);
	$stat_line = explode("||", rtrim($topicdata[0]));

	$fto = "$NonCGIPath/cache-$cache_pw/ubb_files/forums/Forum$f$privatepath/$t$page.cgi";

	if($stat_line[10] != '') {
		// Timeout the topic every 30 minutes if it's a poll
		if(file_exists($fto)) {
			$info = stat($fto);
			if($info[9] < (time() - (60 * 30))) {
				$fo = join("", file($fto));
				unlink($fto); // Delete the cache file we just read so the next visit will go to the CGI script to regen PNTF.
				echo($fo);
				exit;
			} // end if
		} // end if
	} // end if

	if($p > 1) {
		$pg = ";p=$p";
	} else {
		$pg = "";
	} // end if
	ubboutput ($fto, "$CGIURL/ultimatebb.cgi?ubb=get_topic;f=$f;t=$t$pg");

}

// ============================================================================

function getforum ($f,$hardset,$start_point) {

	global $CGIURL, $NonCGIPath, $DaysPrune, $DaysPruneDefault, $cache_pw, $profile, $userid;

	checkit ($f);
	checkit ($hardset);
	checkit ($DaysPrune);
	checkit ($start_point);

	$privateforums = array('');

	// Get "Default Topic View" from logged user
	if ($profile) {
		$DaysPrune = rtrim($profile[21]);
		$status = rtrim($profile[4]);
		$permissions = explode("&",$status);

		// Private forums?
		if ($permissions[1]) {
			$privateforums = explode(",",$permissions[1]);
		}
	} // end if

	$privatepath = "";

	if ($privateforums) {
		for($i = 0; $i < count($privateforums); $i++) {
			if ($privateforums[$i] == $f) {
				$pp = getprivate($f);
				if($pp) {
					$privatepath = "/private-$pp";
				} // end if
			} // end if
		} // end if
	} // end if

	if (!$privateforums && preg_match("/Admin/", $profile[4])) {
		$privateforum = getprivate($f);
		if ($privateforum) {
			$privatepath = "/private-$privateforum";
		}
	}

	if (!$hardset && !$userid && !$DaysPrune)
		$DaysPrune = $DaysPruneDefault;

	if ($start_point > 0)
		$start = "_$start_point";

	if ($hardset) {
		ubboutput ("$NonCGIPath/cache-$cache_pw/ubb_files/forum_page/Forum$f$privatepath/forum$f-$hardset$start.cgi",
		        "$CGIURL/ultimatebb.cgi?ubb=forum;f=$f;hardset=$hardset;start_point=$start_point");
	} else {
		ubboutput ("$NonCGIPath/cache-$cache_pw/ubb_files/forum_page/Forum$f$privatepath/forum$f-$DaysPrune.cgi",
		        "$CGIURL/ultimatebb.cgi?ubb=forum;f=$f;DaysPrune=$DaysPrune");
	}
}


// ============================================================================

function getprivate ($var) {

	global $VariablesPath;

	$pforums = file("$VariablesPath/vars_forums.cgi");
	for($i = 0; $i < count($pforums); $i++) {
		 $row = explode("|^|",$pforums[$i]);
		if ($row[8] == $var) {
			$fpw = $row[7];
		} // end if
	} // end for

	return $fpw;

}

// ============================================================================

function checkban () {

	global $ReverseIPBans, $NonCGIPath, $profile, $CGIURL;

	$fx=file_exists("$NonCGIPath/BanLists/IPBan.cgi");

	if ($fx) {

		$myips = file("$NonCGIPath/BanLists/IPBan.cgi");
		$ipaddr = get_ip();

		$thisip = 0;
		for($ip = 0; $ip < count($myips); $ip++) {
			$myips[$ip] = rtrim($myips[$ip]);
			if(preg_match("/^(\d+\.){1,}$/", $myips[$ip])) {
				if (preg_match("/^$myips[$ip]/i", $ipaddr)) {
					$thisip++;
				}
			} // end if
		} // end for

		if ($ReverseIPBans == "NO" && $thisip) {
			$location = figurelocation();
			silly_redirection ("Location: $CGIURL/ultimatebb.cgi$location", "normalipban");
			exit;
			//ubberror ("Sorry, but your IP Number is currently banned in our forums.");
		}

		if ($ReverseIPBans == "YES" && !$thisip) {
			$location = figurelocation();
			silly_redirection ("Location: $CGIURL/ultimatebb.cgi$location", "reverseipban");
			exit;
			//ubberror ("Sorry, but your IP Number is currently banned in our forums.");
		}

	} // end if

	if ($profile) {
		if (!preg_match("/Write/i", $profile[4])) {
			$location = figurelocation();
			silly_redirection ("Location: $CGIURL/ultimatebb.cgi$location", "profile");
			exit;
			//ubberror ("Sorry, but you have not the permission to access this forum. (Found no write permission in your profile!)");
	 	}
	}
}

// ============================================================================

function checkit ($var) {

	if (preg_match("/\W/",$var)) {
		$location = figurelocation();
		silly_redirection ("Location: $CGIURL/ultimatebb.cgi$location", "checkit");
		exit;
		// ubberror ("No such topic number exists.");
	}
}

// ============================================================================

function hit_me () {

	global $NonCGIPath, $cache_pw, $CGIURL;

	$cfile = "$NonCGIPath/cache-$cache_pw/ubb_files/counter/".date("Y-m")."-cache.cgi";
	if(file_exists($cfile)) {
		$handle = fopen($cfile, "r+");
		if($handle) {
			if(flock($handle, 2)) {
				$count = fread($handle, filesize($cfile));
				$count++;
			} else {
				ubberror("Oops, I can't get a flock on the counter file.");
			} // end if
		} else {
			ubberror("I can't seem to open the existing counter file (<pre>$cfile</pre>)<br />Check that its parent directory exists.  Perhaps the path is incorrect?");
		} // end if
	} else {
		$handle = fopen($cfile, "w");
		if($handle) {
			if(flock($handle, 2)) {
				$count = 0;
			} else {
				ubberror("Oops, I can't get a flock on the new counter file.");
			} // end if
		} else {
			ubberror("I can't seem to create the new counter file (<pre>$cfile</pre>)<br />Check that its parent directory exists.  Perhaps the path is incorrect?");
		} // end if
	} // end if

	fseek($handle, 0);
	fwrite($handle, $count);
	flock($handle, 3);
	fclose($handle);
	chmod($cfile, 0777);
}

// ============================================================================

function getuserid () {

	global $Cookie_Number;

	// Get UBB.classic cookies
	$cookies = explode("; ", find_environmental("HTTP_COOKIE"));

	$cookie = array('');

	for($i = 0; $i < count($cookies); $i++) {
		if (preg_match("/ubber$Cookie_Number/i", $cookies[$i])) {
		 	$cookie = explode("&",$cookies[$i]);
		 	//$var = $cookie[4];
		 } // end if
	} // end for

	return $cookie;

}

// ============================================================================

function checksession () {

	global $Cookie_Number;

	// Get UBB.classic cookies
	$cookies = explode("; ", find_environmental("HTTP_COOKIE"));

	$cookie = array('');

	for($i=0;$i<count($cookies);$i++) {
		if (preg_match("/session$Cookie_Number/i", $cookies[$i])) {
		 	$cookie = explode("&",$cookies[$i]);
		 	//$var = $cookie[4];
		} // end if
	} // end for

	return $cookie;

}

// ============================================================================

function getprofile ($var) {

	global $MembersPath; global $cookie; global $CGIURL;

	$fx = file_exists("$MembersPath/$var.cgi");

	if (!$fx) {
		$location = figurelocation();
		silly_redirection ("Location: $CGIURL/ultimatebb.cgi$location", "noprofile");
	} // end if
	//	ubberror("There is a problem with member file $var.cgi (I was unable to find it - make sure that you set up the Accelerator properly.)");

	$profile = file("$MembersPath/$var.cgi");
	$password = rtrim($profile[1]);

	if ($password != urldecode($cookie[1])) {
		$location = figurelocation();
		silly_redirection ("Location: $CGIURL/ultimatebb.cgi$location", "forumpass");
		exit;
	} // end if
//		ubberror ("Sorry, but you have not the permission to access this forum. <br />(Password mismatch!  <a href=\"$CGIURL/ultimatebb.cgi?ubb=login\">Do you need to log in?</a>)");

	return $profile;

}

// ============================================================================

function ubberror ($var) {

expireme();
echo "Accelerator Error:<br />$var";
exit;

}
// ============================================================================

function ubboutput ($tpath, $redirect) {

	global $TheFile, $CGIURL, $php_version_string;

	expireme();

	// is this page cached?
	$fx = file_exists("$tpath");

	// Redirect to ultimatebb.cgi if the page is not cached or a poll
	if (!$fx) {
		silly_redirection ("Location: $redirect", "redirect");
		exit;
	}

	$TheFile = $tpath;

	// Counter
	hit_me();

	// IP banned? or Reverse IP Ban
	checkban();

	// zLib and PHP 4.0.5 or higher installed? Then use GZIP output via ob_gzhandler
	if (($php_version_string > 4.0004) && extension_loaded("zlib") && !ini_get("zlib.output_compression") && !ini_get("output_handler")) {
		// Note: ob_gzhandler is also supported in PHP 4.0.4 but isn't loaded due to bugs
		ob_start("ob_gzhandler");
	} // end if

	pntf_append();

	// Output the UBB.classic page from cache
	include ("$tpath");
	exit;
}

// ============================================================================

// Expires the page
function expireme () {
	$twominago = time();
	$minago = $twominago + 60;
	header ("Date: " . gmdate("D, d M Y H:i:s") . " GMT");
	header ("Expires: " . gmdate("D, d M Y H:i:s", $minago) . " GMT");
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s", $twominago) . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
} // endfunc

// ============================================================================

function figurelocation () {

	// Yes, I'm intentionally not passing the query string back.

	global $ubb;
	global $f;
	global $t;
	global $p;
	global $start_point;
	global $hardset;
	global $DaysPrune;

	if ($ubb == "forum")
		return("?ubb=forum;f=$f;hardset=$hardset;start_point=$start_point;DaysPrune=$DaysPrune");

	if ($ubb == "get_topic")
		return("?ubb=get_topic;f=$f;t=$t;p=$p");

	if ($ubb == "get_daily")
		return("?ubb=get_daily");

	if ($ubb == "ubb_code_page")
		return("?ubb=ubb_code_page");

	if ($ubb == "faq")
		return("?ubb=faq");

	return("");
} // endfunc

// ============================================================================

function pntf_append () {

	global $NonCGIPath, $cache_pw, $Cookie_Number, $EnablePNTF;

	if($EnablePNTF != "yes") { return; }

	$pntffile = "$NonCGIPath/cache-$cache_pw/pntf/now-accel.cgi";
	if(file_exists($pntffile)) {
		$handle = fopen($pntffile, "a");
		if($handle) {
			if(!flock($handle, 2)) {
				ubberror("Oops, I can't get a flock on the PNTF file.");
			} // end if
		} else {
			ubberror("I can't seem to open the existing PNTF file (<pre>$pntffile</pre>).  Perhaps the path is incorrect?");
		} // end if
	} else {
		$handle = fopen($pntffile, "w");
		if($handle) {
			if(!flock($handle, 2)) {
				ubberror("Oops, I can't get a flock on the new PNTF file.");
			} // end if
		} else {
			ubberror("I can't seem to open the new PNTF file (<pre>$pntffile</pre>).  Perhaps the path is incorrect?");
		} // end if
	} // end if

	$ip = get_ip();
	$t = time();
	$now = (($t - ($t % 60)) / 60);
	$in = rawurlencode(ereg_replace(";", "&", find_environmental("QUERY_STRING")));

	$pntf_cookie_string = "";
	$ubber_cookie_string = "";

	$cookies = explode("; ", find_environmental("HTTP_COOKIE"));
	for($i = 0; $i < count($cookies); $i++) {
		if (preg_match("/PnTf_$Cookie_Number/i", $cookies[$i])) {
			$pntf_cookie = preg_split("/(\&|=)/", $cookies[$i]);
			array_shift($pntf_cookie);
			$pntf_cookie_string = join("|!|", $pntf_cookie);
		} // end if

		if (preg_match("/ubber$Cookie_Number/i", $cookies[$i])) {
			$ubber_cookie = preg_split("/(\&|=)/", $cookies[$i]);
			array_shift($ubber_cookie);
			$ubber_cookie[1] = ""; // Password
			$ubber_cookie_string = join("|!|", $ubber_cookie);
		} // end if
	} // end for


	if(!preg_match("/^\w{16,}\|\!\|/", $pntf_cookie_string)) {
		$pntf_cookie = "|!|$ip|!|$now|!||!|0|!|0";
	} // end if

	if(!preg_match("/^\w{1,}\|\!\|/", $ubber_cookie_string)) {
		$pntf_cookie = "|!||!||!||!|";
	} // end if


	fwrite($handle, "|#|$ip|!^!|$pntf_cookie_string|!^!|$ubber_cookie_string|!^!|$now|!^!|$in|#|\n");
	flock($handle, 3);
	fclose($handle);
	chmod($pntffile, 0777);
} // endfunc

// ============================================================================

function get_ip () {
	$ipaddr = find_environmental("REMOTE_ADDR");
	if ($ipaddr == "127.0.0.1" && find_environmental("HTTP_X_FORWARDED_FOR") != "")
		 $ipaddr = find_environmental("HTTP_X_FORWARDED_FOR");

	return $ipaddr;
} // end func

// ============================================================================

function silly_redirection ($location, $from) {
	header("X-Reason: $from");
	header("$location");
	exit;
} // end silly_redirection

// ============================================================================

function pntfGetCacheForSummary ($category, $TBT, $TBB, $colspan) {
	global $NonCGIPath, $cache_pw, $PNTFPrune, $TheFile;

	$TBT = rawurldecode($TBT);
	$TBB = rawurldecode($TBB);

	$fto = $NonCGIPath . "/cache-" . $cache_pw . "/pntf/9999";
	if($category != "") {
		$fto .= "_$category";
	} // end if
	$fto .= ".cgi";
	if(file_exists($fto)) {
		$info = stat($fto);
		if($info[9] > (time() - ($PNTFPrune * 60))) {
			$fo = join("", file($fto));
			echo("$TBT$fo$TBB");
			// echo "Found the file, and got these: '$category', '$TBT', '$TBB', '$colspan'";
		} else {
			unlink($TheFile); // Delete the cache file we just read so the next visit will go to the CGI script to regen PNTF.
			$fo = join("", file($fto));
			echo("$TBT$fo$TBB");
		} // end if
	} // end if
} // end func

// ============================================================================

function pntfGetCacheForForum ($forum, $TBT, $TBB, $colspan) {
	global $NonCGIPath, $cache_pw, $PNTFPrune, $TheFile;

	$TBT = rawurldecode($TBT);
	$TBB = rawurldecode($TBB);

	$fto = $NonCGIPath . "/cache-" . $cache_pw . "/pntf/";
	$fto .= sprintf("%04d1000_0000", $forum);
	$fto .= ".cgi";
	if(file_exists($fto)) {
		$info = stat($fto);
		if($info[9] > (time() - ($PNTFPrune * 60))) {
			$fo = join("", file($fto));
			echo("$TBT$fo$TBB");
			// echo "Found the file, and got these: '$forum', '$TBT', '$TBB', '$colspan'";
		} else {
			unlink($TheFile); // Delete the cache file we just read so the next visit will go to the CGI script to regen PNTF.
			$fo = join("", file($fto));
			echo("$TBT$fo$TBB");
		} // end if
	} else {
		unlink($TheFile); // Delete the cache file we just read so the next visit will go to the CGI script to regen PNTF.
	} // end if
} // end func

function find_environmental ($name) {
	// Workaround for the new 4.1 way of accessing the environment
	// while still being able to use the 4.0 way... and use
	// getenv(), which doesn't work in ISAPI or Apache 2 filter mode

	global $HTTP_SERVER_VARS;

	$this = "";

	// If we're running under Zend Optimizer, try looking for a REDIRECT_
	if(getenv("SCRIPT_NAME") == '/phpHandler.cgi') {
		// Regular way
		if(getenv("REDIRECT_$name") != '') {
			$this = getenv("REDIRECT_$name");
		} // end if

		// Irregular way
		if(($this == '') && ($HTTP_SERVER_VARS["REDIRECT_$name"] != '')) {
			$this = $HTTP_SERVER_VARS["REDIRECT_$name"];
		} // end if

		// 4.1 way
		if(($this == '') && ($_ENV["REDIRECT_$name"] != '')) {
			$this = $_ENV["REDIRECT_$name"];
		} // end if
	} // end if

	// Okay, no REDIRECT_ vars, try to find the regular ones now...
	// Regular way
	if(getenv($name) != '') {
		$this = getenv("$name");
	} // end if

	// Irregular way
	if(($this == '') && ($HTTP_SERVER_VARS["$name"] != '')) {
		$this = $HTTP_SERVER_VARS["$name"];
	} // end if

	// 4.1 way
	if(($this == '') && ($_ENV["$name"] != '')) {
		$this = $_ENV["$name"];
	} // end if

	return $this;
} // end func

// $Id: ultimatebb.php,v 1.10 2002/07/09 00:35:17 cvscapps Exp $

?>
