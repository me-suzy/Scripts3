<?
/**************************************************************
 * File: 		Cron Files Config Settings
 * Author:	Stephen M. Kitching (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	2005-03-08 (v1.2.2)
 * Changed:	2005-06-15 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:
 * If you wish to run cron jobs from the command line (without wget or similar),
 * you MUST set the variable below for *nix AND for Windows
 * If you are calling the cron jobs via web-browser or wget/curl or other browser
 * simulator, then ignore this file entirely ~ touch absolutely nothing.
 *
 * Here are some samples to get you started. Note that if you are running on a port
 * other than the standard port 80, the format IS protocol server port path, as shown below:
 * 	$_COINURL = 'http://www.phpcoin.com/phpcoin'		// installed in "phpcoin" subdirectory, no SSL
 * 	$_COINURL = 'https://www.phpcoin.com/phpcoin'	// installed in "phpcoin" subdirectory, with SSL
 * 	$_COINURL = 'http://www.phpcoin.com'			// installed in site root, no SSL
 * 	$_COINURL = 'https://www.phpcoin.com'			// installed in site root, with SSL
 * 	$_COINURL = 'http://www.phpcoin.com:8080'		// installed in site root, port 8080, no SSL
 * 	$_COINURL = 'http://www.phpcoin.com:8080/phpcoin'	// installed in "phpcoin" subdirectory, port 8080, no SSL
*****************************************************************************************************************/

# What is the URL to your phpCOIN installation?
	$_COINURL = 'http://www.mysite.com/phpcoin';




#############################################
#	Do NOT touch anything below this line  #
#############################################

# If cronfile called via CLI
	IF (!$_SERVER["SERVER_NAME"]) {

		# First we will fix any mistakes the user made in setting the URL above,
		# then we will build all the variables that would be set if the cronfile
		# was called via web-browser

		# Strip trailing slash in URL, if slash exists
			$_tx = substr($_COINURL, -1, 1);
			IF ($_tx == '/') {$_COINURL = substr($_COINURL, 0, -1);}

		# Strip leading slash in URL, if slash exists
		# this is in case the user forgot the protocol part
			$_tl = substr($_COINURL, 0,1);
			IF ($_tl == '/') {$_COINURL = substr($_COINURL, 1);}

		# Strip leading slash in cronfile, if slash exists
			$_tc = substr($cronfile, 0,1);
			IF ($_tc == '/') {$cronfile = substr($cronfile, 1);}

		# Append /coin_cron/ and our cronfilename, to create "browser URL"
			$_theURL = $_COINURL.'/coin_cron/'.$cronfile;

		# Check that protocol was supplied in URL, make it http if not
			$_http = substr($_theURL, 0, 4);
			$_https = substr($_theURL, 0, 5);
			IF (($_http != 'http') && ($_https != 'https')) {$_theURL = 'http://'.$_theURL;}

		# now break URL up into pieces so we can set the $_SERVER variables
			$_pieces = parse_url($_theURL);

		# Set server_name
			$_SERVER["SERVER_NAME"] = $_pieces['host'];

		# set server_port, making empty if standard port 80
			IF ($_pieces['port'] == 80) {$_pieces['port'] = '';}
			$_SERVER["SERVER_PORT"] = $_pieces['port'];

		# Set https flag
			$_scheme = $_pieces['scheme'];
			IF ($_scheme == 'https') {$_SERVER["HTTPS"] = "on";} ELSE {$_SERVER["HTTPS"] = "off";}

		# Make php-self
			$_SERVER["PHP_SELF"] = $_pieces['path'];
	}


# Figure out our location
	$separat = '/coin_';

# Build the web path
	$Location = $_SERVER["PHP_SELF"];
	$Location = str_replace("\\","/",$Location);
	$PathWeb = explode("/", $Location);
	$FileName = array_pop($PathWeb);
	$_PACKAGE['PATH'] = implode("/", $PathWeb);
	$data_array = explode("$separat",$_PACKAGE['PATH']);
	$_PACKAGE['PATH'] = $data_array[0];
	$_PACKAGE['PATH'] .= '/';

# Build the URL
	$_PACKAGE['URL'] = (($_SERVER["HTTPS"]=="on")?"https":"http").'://'.$_SERVER["SERVER_NAME"].((!empty($_SERVER["SERVER_PORT"]))?":".$_SERVER["SERVER_PORT"]:"").$_PACKAGE['PATH'];

# build the file path
	$tempdocroot = (substr(PHP_OS, 0, 3) == 'WIN') ? strtolower(getcwd()) : getcwd();
	$_PACKAGE['DIR'] = str_replace("\\","/",$tempdocroot);
	$data_array = explode("$separat",$_PACKAGE['DIR']);
	$_PACKAGE['DIR'] = $data_array[0];
	$_PACKAGE['DIR'] .= '/';

?>