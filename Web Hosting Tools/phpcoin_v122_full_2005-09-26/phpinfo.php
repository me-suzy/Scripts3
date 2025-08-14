<?php

/**************************************************************
 * File: 	Basic php info function call.
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:	- displays server and php configuration to admin
**************************************************************/

# Turn off pointless "warning" messages, but display others on-screen
	ini_set('error_reporting','E_ALL & ~E_NOTICE');
	ini_set('display_errors', 'On');

	$_CCFG['_IS_PRINT'] = 0;

# Include session file (loads core)
	require_once  ("coin_includes/session_set.php");

# Call Load Component parms
	$_comp_name	= $_GPV['mod'];
	IF ( $_GPV['id'] != '' ) { $_comp_oper = $_GPV['id']; } ELSE { $_comp_oper = ''; }
	$compdata	= do_load_comp_data($_comp_name, $_comp_oper);

# Get security vars
	$_SEC = get_security_flags ();

# Do User Logged in check
	IF (!$_SEC['_sadmin_flg']) {
		do_page_open($compdata, '0');
		echo do_login('', 'admin', '1').$_nl;
		do_page_close($compdata, '0');
	} ELSE {
		# Call Standard php function
		echo phpinfo();
	}

?>
