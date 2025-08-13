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
//
//
//	Special thanks to Richard Bannister for having such an
// 	old PHP version.  :)
// <

error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING );

?>
<html><head><title>UBB.classic Accelerator Test</title></head>
<body bgcolor="white" text="black"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">

<h2>UBB.classic Accelerator Test</h2>
<?PHP

	// Define the success / fail strings
	$strings['fail'] = "<font color='red'>Fail - too old!</font>";
	$strings['fail_other'] = "<font color='red'>Fail - see below</font>";
	$strings['okold'] = "<font color='blue'>OK - old, but it should work</font>";
	$strings['ok'] = "<font color='green'>OK</font>";
	$strings['zlibno'] = "<font color='red'>No</font>";
	$strings['zlibold'] = "<font color='blue'>Yes, but your PHP version is too old to use zlib properly</font>";
	$strings['zlibyes'] = "<font color='green'>Yes</font>";

	// Determine our PHP version
	$php_version_stringified = phpversion();
	$php_version_array = explode(".", $php_version_stringified);
	$php_version_string = sprintf("%1d.%02d%02d", $php_version_array[0], $php_version_array[1], $php_version_array[2]);

	// If possible, see if we have our basic functionality
	if($php_version_string > 3.0006) {
		$func_iniget =		(function_exists('ini_get') && ini_get('precision') ? 1 : 0);
		if(!$func_iniget) {
			$func_file = 1;		// Assume yes
			$func_chmod = 1;	// Assume yes
			$func_iniget = 0;	// only 4
			$func_eloaded = 0;	// only 4
			$func_obstart = 0;	// only 4
		} else {
			$func_file =	function_exists('file') && !preg_match("/file/", ini_get('disable_functions'));
			$func_chmod = 	function_exists('chmod') && !preg_match("/chmod/", ini_get('disable_functions'));
			$func_eloaded =	function_exists('extension_loaded') && !preg_match("/extension_loaded/", ini_get('disable_functions'));
			$func_obstart =	function_exists('ob_start') && !preg_match("/ob_start/", ini_get('disable_functions'));
		} // end if
	} else {
		$func_file = 1;		// Assume yes
		$func_chmod = 1;	// Assume yes
		$func_iniget = 0;	// only 4
		$func_eloaded = 0;	// only 4
		$func_obstart = 0;	// only 4
	} // end if

	// Again, see if we have basic functionality
	if($php_version_string > 3.0006) {
		if($func_eloaded) {
			$zlib_ok = extension_loaded("zlib");
		} // end if
		if($func_iniget) {
			$safe_mode_check = ini_get('safe_mode');
		} // end if
	} else {
		$zlib_ok = 0;
		$safe_mode_check = 0;
	} // end if

	// Now determine what we can do
	if(($php_version_string > 4.0004) && ($zlib_ok)) {
		// Ideal configuration
		$php_infos = $strings['ok'];
		$zlib_infos = $strings['zlibyes'];
		$extra_infos = "<p>UBB.classic Accelerator should have no issues under this version of PHP.  zlib compression is installed and will be used.</p>";
	} elseif(($php_version_string > 4.0004) && (!$zlib_ok)) {
		// Version is OK, but no zlib.
		$php_infos = $strings['ok'];
		$zlib_infos = $strings['zlibno'];
		$extra_infos = "<p>UBB.classic Accelerator should work under this PHP version.  The Accelerator will be unable to use zlib compression, as it is not installed.</p>";
	} elseif(($php_version_string >= 4) && ($php_version_string < 4.0005) && ($zlib_ok)) {
		// Older version, so turn off zlib.
		$php_infos = $strings['okold'];
		$zlib_infos = $strings['zlibold'];
		$extra_infos = "<p>UBB.classic Accelerator should work under this PHP version.  This PHP version does seem to be a little out of date.  The Accelerator will be unable to use zlib compression.</p> <p>You may wish to speak to your web hosting provider about upgrading their PHP version.</p>";
	} elseif(($php_version_string >= 4) && ($php_version_string < 4.0005) && (!$zlib_ok)) {
		// Older version, no zlib
		$php_infos = $strings['okold'];
		$zlib_infos = $strings['zlibno'];
		$extra_infos = "<p>UBB.classic Accelerator should work under this PHP version.  The Accelerator will be unable to use zlib compression, as it is not installed.</p><p>You may wish to speak to your web hosting provider about upgrading their PHP version.</p>";
	} elseif($php_version_string < 4.0000) {
		// Too old to use
		$php_infos = $strings['fail'];
		$zlib_infos = $strings['zlibno'];
		$extra_infos = "<p>Your PHP version is too low to run the UBB.classic Accelerator.</p> <p>You may wish to speak to your web hosting provider about upgrading their PHP version.</p>";
	} else {
		// Something else happened
		$php_infos = "<font color='red'>(error, see below)</font>";
		$zlib_infos = "<font color='red'>(error, see below)</font>";
		$extra_infos = "<p>There was an error determining your PHP version and zlib compression information.  The UBB.classic Accelerator will probably not work properly.</p>";
	} // end if

// Turn off parinoid mode.  :)
//	if($php_version_string < 4.0101) {
//		$extra_infos .= "<p><font color='red'><b>WARNING</b>: The installed PHP version has a number of known security issues.  You should urge your web hosting provider to upgrade to the latest PHP version immediately!<br />(The UBB.classic Accelerator is not subject to many of these security issues, however it is always wise to keep on top of such things.)</font></p>";
//	} // end if

	$combostrings = "";

	// Now alter the error if we don't have basic functionality
	if(!$func_chmod) {
		$combostrings .= "chmod, ";
	}
	if(!$func_file) {
		$combostrings .= "file, ";
	}
	// And if we can, check the PHP4 stuff
	if($php_version_string > 3.99) {
		if(!$func_iniget) {
			$combostrings .= "ini_get, ";
		}
		if(!$func_eloaded) {
			$combostrings .= "extension_loaded, ";
		}
		if(!$func_obstart) {
			$combostrings .= "ob_start, ";
		}
	} // end if

	$combostrings = preg_replace("/\, $/", "", $combostrings);
	if($combostrings) {
		$extra_infos = "<p><font color='red'>I can not seem to find the '<b>$combostrings</b>' function(s).  Your web hosting provider may be restricting these functions, or your PHP version may not have these functions.</p><p>The UBB.classic Accelerator will be unable to run.  Please contact your web hosting provider or Infopop Support.</font></p>";
		$php_infos = $strings['fail_other'];
	} // end if

// Now start telling the user what we found
?>
Your PHP version is <?PHP echo "<b>$php_version_stringified</b>: $php_infos"; ?>
<br />
Your PHP version has zlib: <?PHP echo $zlib_infos; ?>
<br />

<?PHP echo $extra_infos; ?>

<?PHP

if($safe_mode_check) {

?>
<p><font color="red"><b>WARNING:</b> Your web hosting provider is using Safe Mode.  If they have not configured Safe Mode properly, you may encounter errors.</font> <a href="#safe_mode_warning">Please read this information for details.</a></p>
<?PHP
}
?>


<p>&nbsp;<hr width="75%">&nbsp;</p>


<h3>What is the UBB.classic Accelerator?</h3>

<p>The UBB.classic Accelerator is an optional component that can greatly enhance the speed at which your UBB.classic operates.  The UBB.classic Accelerator is written in the PHP scripting language.  It can optionally use a method known as zlib compression to compress its output, which can help reduce bandwidth use.</p>

<p>In order to use the UBB.classic Accelerator, you must have version 4 or higher of the PHP scripting language installed.  Versions prior to 4 will not operate properly.</p>


<br /> &nbsp; <br />


<h3>How does the UBB.classic Accelerator Work?</h3>

<p>The UBB.classic scripts can be very intense on your web server.  In order to reduce the potential load, the scripts create cached copies of many commonly requested pages, such as topics, forums, and the forum summary.   The UBB.classic Accelerator uses a shortcut to get to these cached copies faster than the UBB.classic scripts.</p>

<p>For additional speed, the UBB.classic Accelerator may use zlib compression.  The majority of web browsers can accept zlib compressed pages.  Normally, zlib compressed pages are between 50% and 75% smaller than uncompressed versions.  This can save your board quite a bit of bandwidth.</p>

<p>The UBB.classic Accelerator can normally serve between 30% and 60% of all requests for UBB.classic pages.</p>

<?PHP
if($safe_mode_check) {
?>

<p>&nbsp;<hr width="75%">&nbsp;</p>

<a name="safe_mode_warning" /><h3>Safe Mode Warning</h3>

<p><small>Note: This area applies mainly to users on Unix servers.  Users on Windows NT or Windows 2000 based servers generally do not need to worry about Safe Mode, but should still read this warning completely.
</small></p>

<p>Your web hosting provider appears to be running in PHP's Safe Mode.  You should be able to use the UBB.classic Accelerator under Safe Mode.  Safe Mode is a series of checks performed by PHP to make sure scripts do not attempt to misbehave.  Running under Safe Mode is a good practice.</p>

<p>Unfortunately, web hosting providers using an improperly configured Safe Mode may cause the UBB.classic Accelerator to return an error message similar to:</p>

<blockquote><small>quote:</small><hr height="1"><b>Warning</b>:  SAFE MODE Restriction in effect.  The script whose uid is <font color="red">1234</font> is not allowed to access /path/to/a/file owned by uid <font color="red">99</font> in <b>/path/to/ultimatebb.php</b> on line <b>246</b><hr height="1"></blockquote>

<p>The 'uid' numbers, colored in red above, should be different in the real error message, as will the path to the files (/path/to/...) and the line number reported by the error.</p>

<p>If you do not encounter this error while the UBB.classic Accelerator is running, your web hosting provider is properly configured for Safe Mode use.</p>

<p>If you <b>do</b> encounter this error, or an error very similar to it, your web hosting provider is using a non-standard and potentially troublesome configuration.  You will be unable to use the UBB.classic Accelerator until the configuration is changed.</p>

<p>You may wish to read the Technical Details section below for the exact cause of this error, and what your web hosting provider must do in order to resolve the problem.  You should ask your web hosting provider to read the Technical Details section as well.</p>

<br /> &nbsp; <br />


<h3>Technical Details</h3>

<p>You may wish to read <a href="http://www.php.net/manual/en/features.safe-mode.php">PHP's Safe Mode Documentation</a>.</p>

<p>Safe Mode prevents PHP scripts from opening files that are not owned by the user that owns the PHP script.  If this script is owned by the user named '<i>anna</i>', and it tries to open a file owned by the user named '<i>nobody</i>' while Safe Mode is enabled, PHP will kill the script with the error message presented above.</p>

<p>UBB.classic runs as a CGI script.  Under many web hosting providers, the web server program runs as the user named '<i>nobody</i>'.  Because the web server program itself runs as '<i>nobody</i>', CGI scripts normally also run as '<i>nobody</i>'.  Because '<i>nobody</i>' owns the CGI script, '<i>nobody</i>' will also own all the files created by that script.</p>

<p>CGI does not natively have a feature similar to PHP's Safe Mode.  Instead, the web server program itself has to be altered to run CGI scripts properly.  Two common "wrappers" that perform this function using the popular Apache web server are <a href="http://httpd.apache.org/docs/suexec.html">suexec</a> and <a href="http://cgiwrap.unixtools.org/">cgiwrap</a>.</p>

<p><b>If your web hosting provider is running CGI scripts without a wrapper (meaning they execute as the web server program's user), but they have PHP running in Safe Mode, the UBB.classic Accelerator will be unable to open the cache files written by UBB.classic.</b>  This problem may also occur if the situation is reversed, meaning that your host is using a CGI wrapper, but isn't using Safe Mode.</p>

<p>Most web hosting providers are either running PHP in Safe Mode in addition to using a CGI wrapper, or are using neither Safe Mode nor a wrapper.  Very few use one without using the other, as they complement each other very well.</p>

<p>If the UBB.classic Accelerator is returning Safe Mode errors, please speak to your web hosting provider.  They are the only ones capable of changing the server configuration.</p>

<?PHP
}
?>

<br /> &nbsp; <br />

<p><small>The UBB.classic Accelerator was originally written by <a href="mailto:philipp@ntcompatible.com">Philipp Esselbach</a>, and is included with UBB.classic with his permission.  Thank you, Philipp!</small></p>

</font></body></html>
<!-- $Id: ubbaccel_test.php,v 1.9 2002/07/09 00:35:17 cvscapps Exp $ -->
