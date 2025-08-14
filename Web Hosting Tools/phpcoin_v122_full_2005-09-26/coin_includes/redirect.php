<?php

/**************************************************************
 * File: 		Redirect Function- File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-09 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:	- for redirect to passed file (server by cfg)
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("redirect.php", $_SERVER["PHP_SELF"])) {
		require_once ('session_set.php');
		html_header_location('error.php?err=01');
		exit;
	}

function html_header_location($url) {
	global $_CCFG, $_SERVER;

 	$url_full	= $_CCFG['_PKG_REDIRECT_ROOT'].$url;
	$_uagent	= $_SERVER[HTTP_USER_AGENT];

	# Some Browsers require header() to be postfixed by exit to execute headers send
		IF (stristr($_uagent, 'msie'))
			{ Header("Refresh: 0;url=$url_full"); exit; }
		ELSE
			{ Header("Location: $url_full"); exit; }
}
?>
