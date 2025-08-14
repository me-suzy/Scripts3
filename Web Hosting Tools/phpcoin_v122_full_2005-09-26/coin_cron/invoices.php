<?php
/**************************************************************
 * File: 		Invoices Module Auto-Send
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_invoices.php
 *
**************************************************************/


# include the "where are we" code
	$cronfile = 'invoices.php';
	require_once ('cron_config.php');


# Include core file
	require_once ($_PACKAGE[DIR]."coin_includes/core.php");

# Include language file (must be after parameter load to use them)
	require_once ( $_CCFG['_PKG_PATH_LANG'].'lang_invoices.php');
	IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_invoices_override.php')) {
		require_once($_CCFG['_PKG_PATH_LANG'].'lang_invoices_override.php');
	}

# Include required functions file
	require_once ( $_CCFG['_PKG_PATH_MDLS'].'invoices/invoices_funcs.php');
	require_once ( $_CCFG['_PKG_PATH_MDLS'].'invoices/invoices_admin.php');

# Call status update and email functions (if enabled)
	# Invoice Status Auto-Update
		IF ( $_ACFG['INVC_AUTO_UPDATE_ENABLE'] )	{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Update_Status'].$_sp.do_auto_invoice_set_status ( ); }
	# Invoice Status Auto-Copy
		IF ( $_ACFG['INVC_AUTO_COPY_ENABLE'] )		{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Copy_Recurring'].$_sp.do_auto_invoice_copy ( ); }
	# Invoice Status Auto-Email
		IF ( $_CCFG['INVC_AUTO_EMAIL_ENABLE'] )		{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Email_Due'].$_sp.do_auto_invoice_emails (); }
	# Invoice Overdue Auto-reminder
		IF ( $_CCFG['INVC_AUTO_EMAIL_ENABLE'] && $_ACFG['INVC_AUTO_REMINDERS_ENABLE'] ) {do_auto_overdue_invoice_emails();}
echo $_cstr;
?>
