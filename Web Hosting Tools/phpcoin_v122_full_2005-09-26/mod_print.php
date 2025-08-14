<?php

/**************************************************************
 * File: 		Print Mod Load File.
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:
 *	- calcs path from referrer string.
 * 	- argument: $mod
**************************************************************/

# Turn off pointless "warning" messages, but display others on-screen
	ini_set('error_reporting','E_ALL & ~E_NOTICE');
	ini_set('display_errors', 'On');

# Include session file (loads core)
	require_once ("coin_includes/session_set.php");

# Check for hack attempts to include external files
	IF (!eregi("^([a-zA-Z0-9]{1,255})$", $_GPV['mod'])) {$_GPV['mod'] = 'cc';}

# Validate requested module
	$_fr = is_readable( $_CCFG['_PKG_PATH_MDLS'].$_GPV[mod].'/index.php' );
	IF ( !$_fr ) { html_header_location('error.php?err=04'); exit; }

# Call Load Component parms
	$_comp_name	= $_GPV[mod];
	$_comp_oper = '';
	$compdata	= do_load_comp_data($_comp_name, $_comp_oper);

# Get security vars- must be logged in as something
	$_SEC = get_security_flags ();

	IF ( !$_SEC['_suser_flg'] && !$_SEC['_sadmin_flg'])
		{
			# Redirect to login
				html_header_location("login.php?w=user&o=login");
				exit;
		}

# Set Global Print Flag
	$_CCFG['_IS_PRINT'] = 1;

# Call page header function-
	do_page_header($compdata['comp_ptitle']);

/*************************************************************/
# Module Load / Include files
	require_once ( $_CCFG['_PKG_PATH_MDLS'].$_GPV['mod'].'/index.php');
/*************************************************************/

# Call page closeout function-
	do_page_closeout('0');

?>
