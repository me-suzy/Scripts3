<?
/**************************************************************
 * File: 		Setup Configuration File
 * Author:	Stephen M. Kitching (http://phpcoin.com)
 * Date:		2004-04-13 (V1.2.0)
 * Changed: 	2005-03-09 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:	- create or upgrade database tables
 *		- populate tables
**************************************************************/

# Code to handle file being loaded by URL
	IF (!isset($_SERVER)) {$_SERVER = $HTTP_SERVER_VARS;}
	IF (eregi("setup_config.php", $_SERVER["PHP_SELF"])) {
		require_once("../coin_includes/session_set.php");
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01');
		exit;
	}


# Dim Some Vars;
	$ThisVersion	= '1.2.2';

# Initialize our various upgrade MySQL command files
# First element is current installed version, next is the upgrade commands filename
	$SQL_Files[1]	= '121|upgrade_to_v121.sql';
	$SQL_Files[2]	= '122|upgrade_to_v122.sql';


/*	Why did we do the array above?  Simple.
	In the future, when releasing a new version we only need to:
		1) Create a MySQL dump of the database changes:
			a) Save the dump as upgrade_to_vXXXX.sql, AND
			b) Append it to the end of setup_vXXXX.sql
		2) Add another element in the array above.
	There is NO need to change the install/upgrade script.
	The install/upgrade script will process each *necessary*
	file in turn, making both install and upgrade super-simple
	to program.
*/


# Build Table Array
	$_TBL_NAMES[]	= $_DBCFG['admins'];
	$_TBL_NAMES[]	= $_DBCFG['articles'];
	$_TBL_NAMES[]	= $_DBCFG['banned'];
	$_TBL_NAMES[]	= $_DBCFG['categories'];
	$_TBL_NAMES[]	= $_DBCFG['clients'];
	$_TBL_NAMES[]	= $_DBCFG['clients_contacts'];
	$_TBL_NAMES[]	= $_DBCFG['components'];
	$_TBL_NAMES[]	= $_DBCFG['domains'];
	$_TBL_NAMES[]	= $_DBCFG['downloads'];
	$_TBL_NAMES[]	= $_DBCFG['faq'];
	$_TBL_NAMES[]	= $_DBCFG['faq_qa'];
	$_TBL_NAMES[]	= $_DBCFG['helpdesk'];
	$_TBL_NAMES[]	= $_DBCFG['helpdesk_msgs'];
	$_TBL_NAMES[]	= $_DBCFG['icons'];
	$_TBL_NAMES[]	= $_DBCFG['invoices'];
	$_TBL_NAMES[]	= $_DBCFG['invoices_items'];
	$_TBL_NAMES[]	= $_DBCFG['invoices_trans'];
	$_TBL_NAMES[]	= $_DBCFG['mail_archive'];
	$_TBL_NAMES[]	= $_DBCFG['mail_contacts'];
	$_TBL_NAMES[]	= $_DBCFG['mail_queue'];
	$_TBL_NAMES[]	= $_DBCFG['mail_templates'];
	$_TBL_NAMES[]	= $_DBCFG['menu_blocks'];
	$_TBL_NAMES[]	= $_DBCFG['menu_blocks_items'];
	$_TBL_NAMES[]	= $_DBCFG['orders'];
	$_TBL_NAMES[]	= $_DBCFG['orders_sessions'];
	$_TBL_NAMES[]	= $_DBCFG['pages'];
	$_TBL_NAMES[]	= $_DBCFG['parameters'];
	$_TBL_NAMES[]	= $_DBCFG['products'];
	$_TBL_NAMES[]	= $_DBCFG['reminders'];
	$_TBL_NAMES[]	= $_DBCFG['server_info'];
	$_TBL_NAMES[]	= $_DBCFG['sessions'];
	$_TBL_NAMES[]	= $_DBCFG['site_info'];
	$_TBL_NAMES[]	= $_DBCFG['topics'];
	$_TBL_NAMES[]	= $_DBCFG['vendors'];
	$_TBL_NAMES[]	= $_DBCFG['vendors_prods'];
	$_TBL_NAMES[]	= $_DBCFG['versions'];
	$_TBL_NAMES[]	= $_DBCFG['whois'];
?>
