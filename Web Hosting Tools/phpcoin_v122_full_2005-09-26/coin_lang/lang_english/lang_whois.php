<?php
/**************************************************************
 * File: 		Language- WhoIs Module
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:	- Global language ($_LANG) vars.
 *		- Language: 		English (USA)
 *		- Translation By:
 *			Mike Lansberry (http://www.phpcoin.com)
 *			Stephen Kitching (http://www.cantexgroup.ca)
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF ( eregi("lang_whois.php", $_SERVER["PHP_SELF"]) )
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01');
			exit;
		}

/**************************************************************
 * Language Variables
**************************************************************/
# Language Variables: Common
		$_LANG['_WHOIS']['Text_Title']					= 'Domain Name Search';
		$_LANG['_WHOIS']['Option_All_Domains']			= 'All Domains';
		$_LANG['_WHOIS']['Text_Instructions_Short']		= '<b>Please enter a suggested domain name</b><br>'.$_nl.'(Selecting "all domains" may take several minutes before the results are displayed)<br>';
		$_LANG['_WHOIS']['Text_Instructions_Long']		= 'A domain name is one word, with no periods or spaces<br>(use underscores instead), and <i>without</i> the leading www.<br>Enter the desired domain name in the space provided,<br>then select the desired extension to check.<br>Selecting "all domains" may take several minutes before the results are displayed.<br>';

		$_LANG['_WHOIS']['Link_Register']				= 'Register';
		$_LANG['_WHOIS']['Link_Order']					= 'Order';
		$_LANG['_WHOIS']['Link_Details']				= 'Details';
		$_LANG['_WHOIS']['Link_Goto']					= 'Goto';

		$_LANG['_WHOIS']['Title_Available']				= 'Available';
		$_LANG['_WHOIS']['Title_Taken']					= 'Taken';

		$_LANG['_WHOIS']['Title_Domain']				= 'Domain Name';
		$_LANG['_WHOIS']['Title_Extension']				= 'Extension';

# Language Variables: Some Buttons
		$_LANG['_WHOIS']['B_Check']						= 'Check';

# Language Variables:
	# Misc Errors:
		$_LANG['_WHOIS']['Error_Too_Short']				= 'The domain name you typed is too short - it must contain minimum 3 characters.';
		$_LANG['_WHOIS']['Error_Too_Long']				= 'The domain name you typed is to long - it may contain maximum 63 characters.';
		$_LANG['_WHOIS']['Error_Hyphens']				= 'Domain names cannot begin or end with a hyphen or contain double hyphens.';
		$_LANG['_WHOIS']['Error_AlphaNum']				= 'Domain names can only contain alphanumerical characters and hyphens.';
?>
