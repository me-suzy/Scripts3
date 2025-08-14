<?php

/**************************************************************
 * File: 		SiteInfo Module Index.php
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: none
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("index.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01&url=mod.php?mod=siteinfo');
			exit;
		}

# Get security vars
	$_SEC = get_security_flags ();

# Include journal functions file
	require_once ( $_CCFG['_PKG_PATH_MDLS'].$_GPV[mod].'/'.$_GPV[mod].'_funcs.php');

/**************************************************************
 * Module Code
**************************************************************/

##############################
# Mode Call: Load page
# Summary:
#	- List entries
##############################
IF ( ( $_GPV[group] && $_GPV[name] ) || $_GPV[id] )
	{
		# Check for index to also load announce if on.
			IF ( $_GPV[id] == 1 || ($_GPV[group] == 'site' && $_GPV[name] == 'index') )
				{
					$_out .= do_site_info_display( 0, 'site', 'announce', '1', $_GPV[ss] );
					IF ( $_out != '' ) { $_out .= '<br>'.$_nl; }
				}

		# Call display site info function for:
			$_out .= do_site_info_display( $_GPV[id], $_GPV[group], $_GPV[name], '1', $_GPV[ss] );
			echo $_out;
	}

?>
