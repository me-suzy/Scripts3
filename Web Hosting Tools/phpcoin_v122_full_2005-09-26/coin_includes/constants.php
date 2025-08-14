<?php

/**************************************************************
 * File: 		Constants File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-09 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- For system constants
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("constants.php", $_SERVER["PHP_SELF"])) {
		require_once ('session_set.php');
		require_once include ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01');
		exit;
	}


/**************************************************************
 * Misc System Constants (do not translate or edit)
**************************************************************/
# Orders Session Table record age in seconds
	$_CCFG['OS_AGE_IN_SECONDS']			= 3600;

# Session Table record age in seconds
	$_CCFG['S_AGE_IN_SECONDS']			= 900;

# Flood Control Parameters: Once per numbers seconds set.
	$_CCFG['FC_IN_SECONDS_CONTACTS']	= 30;
	$_CCFG['FC_IN_SECONDS_ORDERS']		= 30;

# Menu Block Item Target Select List Params (must be valid target)
	$_CCFG['MBI_LINK_TARGET'][0]	= '_self';				# For Link Target to _self
	$_CCFG['MBI_LINK_TARGET'][1]	= '_blank';				# For Link Target to _blank
	$_CCFG['MBI_LINK_TARGET'][2]	= '_top';					# For Link Target to _top

# Menu Block Item Text Contents Type (text, image, function)
	$_CCFG['MBI_TEXT_TYPE'][0]		= 'Text';				# For Text Contents- Text
	$_CCFG['MBI_TEXT_TYPE'][1]		= 'Image';			# For Text Contents- Image
	$_CCFG['MBI_TEXT_TYPE'][2]		= 'Function';			# For Text Contents- Function

# Order Product List Sort Order Options Select List Params ()
	$_CCFG['ORD_PROD_LIST_SORT'][0]	= 'Product ID';
	$_CCFG['ORD_PROD_LIST_SORT'][1]	= 'Product Name';
	$_CCFG['ORD_PROD_LIST_SORT'][2]	= 'Product Description';
	$_CCFG['ORD_PROD_LIST_SORT'][3]	= 'Product Price';

/**************************************************************
 * Table Name with Prefix Array (must be after DB load)
**************************************************************/

# Build Array for database tables
	$_DBCFG['admins'] 				= $_DBCFG['table_prefix'].'admins';
	$_DBCFG['articles'] 			= $_DBCFG['table_prefix'].'articles';
	$_DBCFG['banned'] 				= $_DBCFG['table_prefix'].'banned';
	$_DBCFG['categories'] 			= $_DBCFG['table_prefix'].'categories';
	$_DBCFG['clients'] 				= $_DBCFG['table_prefix'].'clients';
	$_DBCFG['clients_contacts'] 		= $_DBCFG['table_prefix'].'clients_contacts';
	$_DBCFG['components'] 			= $_DBCFG['table_prefix'].'components';
	$_DBCFG['domains']				= $_DBCFG['table_prefix'].'domains';
	$_DBCFG['downloads'] 			= $_DBCFG['table_prefix'].'downloads';
	$_DBCFG['faq'] 				= $_DBCFG['table_prefix'].'faq';
	$_DBCFG['faq_qa'] 				= $_DBCFG['table_prefix'].'faq_qa';
	$_DBCFG['helpdesk'] 			= $_DBCFG['table_prefix'].'helpdesk';
	$_DBCFG['helpdesk_msgs'] 		= $_DBCFG['table_prefix'].'helpdesk_msgs';
	$_DBCFG['icons'] 				= $_DBCFG['table_prefix'].'icons';
	$_DBCFG['invoices'] 			= $_DBCFG['table_prefix'].'invoices';
	$_DBCFG['invoices_items']		= $_DBCFG['table_prefix'].'invoices_items';
	$_DBCFG['invoices_trans']		= $_DBCFG['table_prefix'].'invoices_trans';
	$_DBCFG['mail_archive'] 			= $_DBCFG['table_prefix'].'mail_archive';
	$_DBCFG['mail_contacts'] 		= $_DBCFG['table_prefix'].'mail_contacts';
	$_DBCFG['mail_queue'] 			= $_DBCFG['table_prefix'].'mail_queue';
	$_DBCFG['mail_templates'] 		= $_DBCFG['table_prefix'].'mail_templates';
	$_DBCFG['menu_blocks'] 			= $_DBCFG['table_prefix'].'menu_blocks';
	$_DBCFG['menu_blocks_items']		= $_DBCFG['table_prefix'].'menu_blocks_items';
	$_DBCFG['orders'] 				= $_DBCFG['table_prefix'].'orders';
	$_DBCFG['orders_sessions']		= $_DBCFG['table_prefix'].'orders_sessions';
	$_DBCFG['pages'] 				= $_DBCFG['table_prefix'].'pages';
	$_DBCFG['parameters'] 			= $_DBCFG['table_prefix'].'parameters';
	$_DBCFG['products'] 			= $_DBCFG['table_prefix'].'products';
	$_DBCFG['server_info'] 			= $_DBCFG['table_prefix'].'server_info';
	$_DBCFG['sessions'] 			= $_DBCFG['table_prefix'].'sessions';
	$_DBCFG['site_info'] 			= $_DBCFG['table_prefix'].'site_info';
	$_DBCFG['topics'] 				= $_DBCFG['table_prefix'].'topics';
	$_DBCFG['vendors'] 				= $_DBCFG['table_prefix'].'vendors';
	$_DBCFG['vendors_prods'] 		= $_DBCFG['table_prefix'].'vendors_prods';
	$_DBCFG['versions'] 			= $_DBCFG['table_prefix'].'versions';
	$_DBCFG['whois'] 				= $_DBCFG['table_prefix'].'whois';
	$_DBCFG['reminders'] 			= $_DBCFG['table_prefix'].'reminders';

# For additional items (humor me)
	$_DBCFG['mnews'] 				= $_DBCFG['table_prefix'].'mnews';

?>
