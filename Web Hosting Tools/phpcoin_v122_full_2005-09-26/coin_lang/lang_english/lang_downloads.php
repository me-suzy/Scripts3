<?php

/**************************************************************
 * File: 		Language- Downloads Module
 * Author:	Stephen M. Kitching (http://phpcoin.com)
 * Date:		2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:	- Global language ($_LANG) vars.
 *		- Language: 		English (USA)
 *		- Translation By:	Stephen M. Kitching
 *		- Translator Email:	support@phpcoin.com
**************************************************************/

# Code to handle file being loaded by URL
	IF (!isset($_SERVER)) { $_SERVER = $HTTP_SERVER_VARS; }
	IF ( eregi("lang_downloads.php", $_SERVER["PHP_SELF"]) ) {
		require_once ('../../coin_includes/session_set.php');
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01');
		exit();
	}

/**************************************************************
 * Language Variables
**************************************************************/

	$_LANG['Downloads']['Title']		= 'Free Downloads';

	$_LANG['Downloads']['Files']		= 'Files';
	$_LANG['Downloads']['Description']	= 'Description';
	$_LANG['Downloads']['Released']	= 'Released';
	$_LANG['Downloads']['Name']		= 'Download Name';
	$_LANG['Downloads']['FileSize']	= 'FileSize';
	$_LANG['Downloads']['Contributor']	= 'Contributor';
	$_LANG['Downloads']['Count']		= 'Downloaded';
	$_LANG['Downloads']['Get_It']		= 'Get It';

	$_LANG['Downloads']['Group_Category']	= 'Group By Category';
	$_LANG['Downloads']['Group_Name']		= 'Group By Name';

	$_LANG['Downloads']['Pre-amble']		= 'This is the text that goes above the downloads table. You can put anything you want here. This text is in lang_downloads.php';
?>
