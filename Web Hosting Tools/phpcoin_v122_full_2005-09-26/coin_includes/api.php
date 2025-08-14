<?php

/**************************************************************
 * File: 		API Functions- File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-09 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_api.php
 *	- For API Functions
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("api.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01');
			exit;
		}


/**************************************************************
 *              Start Custom Module Functions
**************************************************************/
function do_yourcustom2 ($adata, $aret_flag=0)
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build your return / output
			$_out .= 'Generally set to the $_out variable'.$_nl;
			$_out .= ' to control the output on error handling.'.$_nl;

		# Either return output or echo here
			IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 *	API Output (APIO) Triggers
 *	All are flagged in code (for search) by following:
 *		# API Output Hook:
 *		# APIO_order_new_client: Order new client hook
 *			(for example- each has correct name)
 *	Notes-
 *		- Data array in scope at time of fcall passed in.
 *		- Return array for "dn"- success, and "msg" string
 *		- All globals / parameters in scope.
 *		- Database connected and available.
**************************************************************/

/**************************************************************
 *	API Output (APIO) Trigger: APIO_order_cor_proc ($_APIO_AData)
 *	Notes-
 *		- Trigger during the place order process.
 *		- Fires on custom order request emails sent
 *		  Scope is during "process_order" script.
**************************************************************/
function APIO_order_cor_proc ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_order_out_proc ($_APIO_AData)
 *	Notes-
 *		- Trigger during the place order process.
 *		- Fires on order inserted into database during order
 *		  process. Scope is during "process_order" script.
**************************************************************/
function APIO_order_out_proc ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_order_ret_proc ($_APIO_AData)
 *	Notes-
 *		- Trigger during the place order process.
 *		- Fires on return from billing vendor and order return
 *		  processing. Buy / Cancel should be known at this time.
**************************************************************/
function APIO_order_ret_proc ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_order_new_client ($_APIO_AData)
 *	Notes-
 *		- Trigger during the place order process.
 *		- Fires on new client inserted into database during order
 *		  process. Scope is during "process_order" script.
**************************************************************/
function APIO_order_new_client ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_order_new_domain ($_APIO_AData)
 *	Notes-
 *		- Trigger during the place order process.
 *		- Fires on new domain inserted into database during order
 *		  process. Scope is during "process_order" script.
**************************************************************/
function APIO_order_new_domain ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_client_new ($_APIO_AData)
 *	Notes-
 *		- Trigger admin editing clients.
 *		- Fires on new client inserted into database.
 *		  Scope is during admin editing.
**************************************************************/
function APIO_client_new ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_client_del ($_APIO_AData)
 *	Notes-
 *		- Trigger admin editing clients.
 *		- Fires on deleting a client from the database.
 *		  Scope is during admin editing.
**************************************************************/
function APIO_client_del ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_domain_new ($_APIO_AData)
 *	Notes-
 *		- Trigger admin editing domains.
 *		- Fires on new domain inserted into database.
 *		  Scope is during admin editing.
**************************************************************/
function APIO_domain_new ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_domain_del ($_APIO_AData)
 *	Notes-
 *		- Trigger admin editing domains.
 *		- Fires on deleting a domain from the database.
 *		  Scope is during admin editing.
**************************************************************/
function APIO_domain_del ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_order_new ($_APIO_AData)
 *	Notes-
 *		- Trigger admin editing orders.
 *		- Fires on new order inserted into database.
 *		  Scope is during admin editing.
**************************************************************/
function APIO_order_new ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_order_del ($_APIO_AData)
 *	Notes-
 *		- Trigger admin editing orders.
 *		- Fires on deleting a order from the database.
 *		  Scope is during admin editing.
**************************************************************/
function APIO_order_del ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_product_new ($_APIO_AData)
 *	Notes-
 *		- Trigger admin editing products.
 *		- Fires on new product inserted into database.
 *		  Scope is during admin editing.
**************************************************************/
function APIO_product_new ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_product_del ($_APIO_AData)
 *	Notes-
 *		- Trigger admin editing products.
 *		- Fires on deleting a product from the database.
 *		  Scope is during admin editing.
**************************************************************/
function APIO_product_del ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_trans_new ($_APIO_AData)
 *	Notes-
 *		- Trigger admin editing transactions / payments.
 *		- Fires on new transaction inserted into database.
 *		  Scope is during admin editing.
**************************************************************/
function APIO_trans_new ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


/**************************************************************
 *	API Output (APIO) Trigger: APIO_trans_del ($_APIO_AData)
 *	Notes-
 *		- Trigger admin editing transactions / payments.
 *		- Fires on deleting a transaction from the database.
 *		  Scope is during admin editing.
**************************************************************/
function APIO_trans_del ($_APIO_AData)
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do whatever, set returns
			$_APIO_Ret['dn']	= 1;
			$_APIO_Ret['msg']	= 'none';

		# Return output
			return $_APIO_Ret;
	}


?>
