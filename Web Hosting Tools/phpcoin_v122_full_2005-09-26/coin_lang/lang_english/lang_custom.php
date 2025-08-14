<?php

/**************************************************************
 * File: 		Language- Custom Functions
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:	- Global language ($_LANG) vars.
 *		- Language: 		English (USA)
 *		- Translation By:	Mike Lansberry
 *		- Translator Email:	webcontact@phpcoin.com
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF ( eregi("lang_custom.php", $_SERVER["PHP_SELF"]) )
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01');
			exit;
		}

/**************************************************************
 * Language Variables
**************************************************************/
# Language Variables: Custom Common Set
	$_LANG['_CUSTOM']['Please_Select']						= 'Please Select';
	$_LANG['_CUSTOM']['Welcome']							= 'Welcome';

# Language Variables: Some Common Buttons Text.
	$_LANG['_CUSTOM']['B_Submit']							= 'Submit';
	$_LANG['_CUSTOM']['B_Reset']							= 'Reset';

	$_LANG['_CUSTOM']['CP_Supporters_Edit']					= 'Edit Supporters';
	$_LANG['_CUSTOM']['CP_Downloads_Edit']					= 'Edit Downloads';
	$_LANG['_CUSTOM']['LICENSE']							= 'Edit Licenses';
?>
