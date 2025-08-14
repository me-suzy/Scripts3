<?php

/**************************************************************
 * File: 		Site Index File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
**************************************************************/

# Turn off pointless "warning" messages, and display errors on-screen
	ini_set('error_reporting','E_ALL & ~E_NOTICE');
	ini_set('display_errors', 'On');

	$_CCFG['_IS_PRINT'] = 0;

# Include session file (loads core)
	require_once ('coin_includes/session_set.php');

# Set index global, Call Load Component parms
	$_comp_name	= 'index';
	$_comp_oper	= '';
	$compdata		= do_load_comp_data($_comp_name, $_comp_oper);

# Call page open (start to content)
	do_page_open($compdata, '0');

# Call display site info function for: site - announcement
	$si_code = '';
	$si_code .= do_site_info_display("", 'site', 'announce', '1');
	IF ( $si_code ) { echo $si_code.'<br>'; }

# Call display site info function for: site - greeting
	$si_code = '';
	$si_code .= do_site_info_display("", 'site', 'greeting', '1');
	IF ( $si_code ) { echo $si_code.'<br>'; }

# Call display site info function for: site - index
	$si_code = '';
	$si_code .= do_site_info_display("", 'site', 'index', '1');
	IF ( $si_code ) { echo $si_code; }

# Call page close (content to finish)
	do_page_close($compdata, '0');

?>