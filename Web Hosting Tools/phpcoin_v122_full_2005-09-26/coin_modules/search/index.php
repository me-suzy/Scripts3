<?php

/**************************************************************
 * File: 		Search Module Index.php
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_search.php
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("index.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01&url=mod.php?mod=search');
			exit;
		}

# Get security vars
	$_SEC = get_security_flags ();

# Include language file (must be after parameter load to use them)
	require_once ( $_CCFG['_PKG_PATH_LANG'].'lang_search.php');
	IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_search_override.php')) {
		require_once($_CCFG['_PKG_PATH_LANG'].'lang_search_override.php');
	}

# Include search functions file
	require_once ( $_CCFG['_PKG_PATH_MDLS'].$_GPV[mod].'/'.$_GPV[mod].'_funcs.php');

/**************************************************************
 * Module Code
**************************************************************/


##############################
# Mode Call: Load Search Form
# Summary:
#	- Load Search Form
##############################
IF ( !$_GPV[search_str] )
	{
		# Call function for search form
			$_out = '<!-- Start content -->'.$_nl;
			$_out .= do_search_form( $_GPV, '1' );

		# Echo final output
			echo $_out;
	}


##############################
# Mode Call: Do Search
# Summary:
#	- And load output
##############################
IF ( $_GPV[search_str] && $_GPV[stage] )
	{
		# Call function for search and output
			$_out = '<!-- Start content -->'.$_nl;
			$_out .= do_search( $_GPV, '1' );

		# Echo final output
			echo $_out;
	}

?>
