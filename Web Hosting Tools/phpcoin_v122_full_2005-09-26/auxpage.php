<?php

/**************************************************************
 * File: 		Standard Auxilliary Pages Load File.
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:	- calcs filename from referrer string.
 *		- argument: $page
**************************************************************/

# Turn off pointless "warning" messages, and display errors on-screen
	ini_set('error_reporting','E_ALL & ~E_NOTICE');
	ini_set('display_errors', 'On');

# Include session file (loads core)
	require_once ("coin_includes/session_set.php");

# Check for hack attempts to include external files
	IF (!eregi("^([a-zA-Z0-9_]{1,255})$", $_GPV['page'])) {$_GPV['page'] = 'h';}

# Validate requested page
	$_fr = is_readable( $_CCFG['_PKG_PATH_AUXP'].$_GPV['page'].'.php' );
	IF ( !$_fr ) { html_header_location('error.php?err=03'); exit; }

# Call Load Component parms
	$_comp_name	= $_GPV['page'];
	$_comp_oper = '';
	$compdata	= do_load_comp_data($_comp_name, $_comp_oper);

# Call page open (start to content)
	do_page_open($compdata, '0');

/*************************************************************/
# Auxpage Load / Include files
	require_once ( $_CCFG['_PKG_PATH_AUXP'].$_GPV['page'].'.php' );
/*************************************************************/

# Call page close (content to finish)
	do_page_close($compdata, '0');

?>