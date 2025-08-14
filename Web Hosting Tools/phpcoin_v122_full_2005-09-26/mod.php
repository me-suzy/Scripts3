<?php

/**************************************************************
 * File: 		Standard Mod Load File.
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:
 *	- calcs path from referrer string.
 * 	- argument: $mod
**************************************************************/

# Turn off pointless "warning" messages, and display errors on-screen
	ini_set('error_reporting','E_ALL & ~E_NOTICE');
	ini_set('display_errors', 'On');

# Include session file (loads core)
	require_once ("coin_includes/session_set.php");

# Check for hack attempts to include external files
	IF (!eregi("^([a-zA-Z0-9]{1,255})$", $_GPV['mod'])) {$_GPV['mod'] = 'cc';}

# Validate requested module
	$_fr = is_readable( $_CCFG['_PKG_PATH_MDLS'].$_GPV['mod'].'/index.php' );
	IF ( !$_fr ) { html_header_location('error.php?err=04'); exit; }

# Call Load Component parms
	$_comp_name	= $_GPV['mod'];
	IF ( $_GPV['id'] != '' ) { $_comp_oper = $_GPV['id']; } ELSE { $_comp_oper = ''; }
	$compdata	= do_load_comp_data($_comp_name, $_comp_oper);

# Call page open (start to content)
	do_page_open($compdata, '0');

/*************************************************************/
# Module Load / Include files
	require_once ( $_CCFG['_PKG_PATH_MDLS'].$_GPV['mod'].'/index.php' );
/*************************************************************/

# Call page open (content to finish)
	do_page_close($compdata, '0');

?>
