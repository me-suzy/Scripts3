<?php

/**************************************************************
 * File: 		Standard Admin Load File.
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:
 *	- Translation File: lang_admin.php
 * 	- argument: $cp (which id filename)
 *
**************************************************************/

# Turn off pointless "warning" messages, and display errors on-screen
	ini_set('error_reporting','E_ALL & ~E_NOTICE');
	ini_set('display_errors', 'On');

# Include session file (loads core)
	require_once ("coin_includes/session_set.php");

# Check for hack attempts to include external files
	IF (!eregi("^([a-zA-Z0-9_]{1,255})$", $_GPV['cp'])) {$_GPV['cp'] = '';}

# Include language file (must be after parameter load to use them)
	require_once ($_CCFG['_PKG_PATH_LANG'].'lang_admin.php');
	IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_admin_override.php')) {
		require_once($_CCFG['_PKG_PATH_LANG'].'lang_admin_override.php');
	}
	require_once ($_CCFG['_PKG_PATH_LANG'].'lang_custom.php');
	IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_custom_override.php')) {
		require_once($_CCFG['_PKG_PATH_LANG'].'lang_custom_override.php');
	}

# Validate requested control panel
	IF ($_GPV['cp']) {
		$_fr = is_readable($_CCFG['_PKG_PATH_ADMN'].'cp_'.$_GPV['cp'].'.php');
		IF (!$_fr) {html_header_location('error.php?err=02&url=admin.php'); exit();}
	}

# Call Load Component parms
	IF (!$_GPV['cp']) {
		$_cp_mode = "cp_"."index";
	} ELSE {
		$_cp_mode = "cp_".$_GPV['cp'];
	}
	$_comp_name	= $_cp_mode;
	$_comp_oper 	= '';
	$compdata		= do_load_comp_data($_comp_name, $_comp_oper);

# Call page open (start to content)
	do_page_open($compdata, '0');

# Include cp_core.php file-
	require_once ($_CCFG['_PKG_PATH_ADMN'].'cp_core.php');

# Check cp- if none- set to index
	IF (!$_GPV['cp'])	{
		require_once ($_CCFG['_PKG_PATH_ADMN'].'index.php');
	} ELSE {
		require_once ($_CCFG['_PKG_PATH_ADMN'].'cp_'.$_GPV['cp'].'.php');
	}

# Call page close (content to finish)
	do_page_close($compdata, '0');

?>