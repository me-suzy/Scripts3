<?php

/**************************************************************
 * File: 		Orders Module Functions File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_orders.php
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("orders_funcs.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01&url=mod.php?mod=orders');
			exit;
		}

# Do orders session purge, update, or insert as required
function do_orders_session_set()
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Set some vars
			$_si = session_id();
			$_tm = dt_get_uts();
			IF (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
				$_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} ELSE {
				$_ip = $_SERVER["REMOTE_ADDR"];
			}

		# Do Purge (time in seconds)
			$_pv = $_CCFG['OS_AGE_IN_SECONDS'];
			$query	= ""; $result = ""; $numrows = 0;
			$query 	= "DELETE FROM ".$_DBCFG['orders_sessions']." WHERE ($_tm - os_s_time_last) > $_pv";
			$result 	= db_query_execute($query);
			$eff_rows	= db_query_affected_rows ();

		# Try select existing for either update or insert
			$query = ""; $result = ""; $numrows = 0;
			$query 	= "SELECT os_s_id FROM ".$_DBCFG['orders_sessions'];
			$query 	.= " WHERE os_s_id = '".$_si."'";
			$result	= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Check if exist, update or insert as required
			IF ( $numrows == 1 )
				{
					# Do update existing
						$query = ""; $result = ""; $numrows = 0;
						$query 	= "UPDATE ".$_DBCFG['orders_sessions'];
						$query 	.= " SET os_s_time_last = '$_tm',os_s_ip = '$_ip'";
						$query 	.= " WHERE os_s_id = '".$_si."'";
						$result	= db_query_execute($query);
						$numrows	= db_query_affected_rows ();
				}
			ELSE
				{
					# Do Insert
						$query = ""; $result = ""; $numrows = 0;
						$query 		= "INSERT INTO ".$_DBCFG['orders_sessions']." (";
						$query 		.= " os_s_id, os_s_time_init, os_s_time_last, os_s_ip";
						$query 		.= " ) VALUES ( ";
						$query 		.= "'$_si','$_tm','$_tm','$_ip'";
						$query 		.= ")";
						$result 	= db_query_execute($query);
						$insert_id	= db_query_insertid ();
				}

		return 1;
	}

# Do orders session update data- set processed
function do_orders_session_set_proc($adata)
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows = 0;

		# Set some vars
			$_si = session_id();
			$_tm = dt_get_uts();
			$_fp = 'flushed'; # Flush password

		# Do update existing
			$query = ""; $result = ""; $numrows = 0;
			$query 		= "UPDATE ".$_DBCFG['orders_sessions']." SET ";
			$query 		.= "os_s_time_last = '$_tm',os_ord_id = '$adata[os_ord_id]'";
			$query 		.= ",os_ord_processed = '$adata[os_ord_processed]'";
			$query 		.= ",os_ord_user_pword = '$_fp'";
			IF ( $adata[os_ord_cl_id] > 0 )
				{ $query .= ", os_ord_cl_id = '$adata[os_ord_cl_id]'"; }
			IF ( $adata[ord_unit_cost] > 0 )
				{ $query .= ", os_ord_unit_cost = '$adata[ord_unit_cost]'"; }
			$query 		.= " WHERE os_s_id = '".$_si."'";
			$result		= db_query_execute($query);
			$numrows	= db_query_affected_rows ();

		return 1;
	}


# Do orders session select data- get processed
function do_orders_session_qet_proc()
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows = 0;

		# Set some vars
			$_si = session_id();
			$_tm = dt_get_uts();

		# Do select existing
			$query = ""; $result = ""; $numrows = 0;
			$query 		= "SELECT * FROM ".$_DBCFG['orders_sessions'];
			$query 		.= " WHERE os_s_id = '".$_si."'";
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$data['numrows'] = $numrows;
			while ($row = db_fetch_array($result))
			{
				# Rebuild Data Array with returned record
					$data['numrows']			= $numrows;
					$data['ord_processed']		= $row['os_ord_processed'];
					$data['ord_ret_processed']	= $row['os_ord_ret_processed'];
					$data['ord_id']				= $row['os_ord_id'];
					$data['ord_vendor_id']		= $row['os_ord_vendor_id'];
					$data['ord_prod_id']		= $row['os_ord_prod_id'];
			}

		return $data;
	}


# Do orders session set return from billing processed
function do_orders_session_set_ret_proc($adata)
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows = 0;

		# Set some vars
			$_si = session_id();
			$_tm = dt_get_uts();

		# Do update existing
			$query = ""; $result = ""; $numrows = 0;
			$query 		= "UPDATE ".$_DBCFG['orders_sessions']." SET ";
			$query 		.= "os_s_time_last = '$_tm'";
			$query 		.= ", os_ord_ret_processed = '$adata[os_ord_ret_processed]'";
			$query 		.= " WHERE os_s_id = '".$_si."'";
			$result		= db_query_execute($query);
			$numrows	= db_query_affected_rows ();

		return 1;
	}


# Do orders session update data
function do_orders_session_update($adata)
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows = 0;

		# Get security vars
			$_SEC = get_security_flags ();
			IF ( ($adata[os_ord_cl_id] == '' || $adata[os_ord_cl_id] == 0 ) && $_SEC['_suser_flg'] == 1 ) { $adata[os_ord_cl_id] = $_SEC['_suser_id']; }

		# Set some vars
			$_si = session_id();
			$_tm = dt_get_uts();
			$_ip = $_SERVER["REMOTE_ADDR"];

		# Process inputs for quotes
			$adata[ord_comments]	= do_addslashes($adata[ord_comments]);
			$adata[cor_comments]	= do_addslashes($adata[cor_comments]);

		# Do update existing (uw-update what)
			$query = ""; $result = ""; $numrows = 0;
			$query 		= "UPDATE ".$_DBCFG['orders_sessions']." SET os_s_time_last = '$_tm',os_s_ip = '$_ip'";
			IF ( $adata['uw'] == 'ORD' || $adata['uw'] == 'ALL' )
				{
					$query 		.= ",os_cor_flag = '$adata[cor_flag]'";
					$query 		.= ",os_ord_processed = '$adata[os_ord_processed]',os_ord_ret_processed = '$adata[os_ord_ret_processed]'";
					$query 		.= ",os_ord_id = '$adata[os_ord_id]',os_ord_ts = '$adata[os_ord_ts]'";
					$query 		.= ",os_ord_status = '$adata[os_ord_status]'";
					IF ( $adata[os_ord_cl_id] )
						{ $query .= ",os_ord_cl_id = '$adata[os_ord_cl_id]'"; }
					$query 		.= ",os_ord_company = '$adata[ord_company]',os_ord_name_first = '$adata[ord_name_first]'";
					$query 		.= ",os_ord_name_last = '$adata[ord_name_last]',os_ord_addr_01 = '$adata[ord_addr_01]'";
					$query 		.= ",os_ord_addr_02 = '$adata[ord_addr_02]',os_ord_city = '$adata[ord_city]'";
					$query 		.= ",os_ord_state_prov = '$adata[ord_state_prov]',os_ord_country = '$adata[ord_country]'";
					$query 		.= ",os_ord_zip_code = '$adata[ord_zip_code]',os_ord_phone = '$adata[ord_phone]'";
					$query 		.= ",os_ord_email = '$adata[ord_email]',os_ord_domain = '$adata[ord_domain]'";
					$query 		.= ",os_ord_domain_action = '$adata[ord_domain_action]',os_ord_user_name = '$adata[ord_user_name]'";
					$query 		.= ",os_ord_user_pword = '$adata[ord_user_pword]'";
					IF ( $adata[ord_vendor_id] )
						{ $query .= ",os_ord_vendor_id = '$adata[ord_vendor_id]'"; }
					IF ( $adata[ord_prod_id] )
						{ $query .= ",os_ord_prod_id = '$adata[ord_prod_id]'"; }
					$query 		.= ",os_ord_unit_cost = '$adata[ord_unit_cost]'";
					$query 		.= ",os_ord_accept_tos = '$adata[ord_accept_tos]',os_ord_accept_aup = '$adata[ord_accept_aup]'";
					$query 		.= ",os_ord_referred_by = '$adata[ord_referred_by]',os_ord_comments = '$adata[ord_comments]'";
					$query 		.= ",os_ord_optfld_01 = '$adata[ord_optfld_01]',os_ord_optfld_02 = '$adata[ord_optfld_02]'";
					$query 		.= ",os_ord_optfld_03 = '$adata[ord_optfld_03]',os_ord_optfld_04 = '$adata[ord_optfld_04]'";
					$query 		.= ",os_ord_optfld_05 = '$adata[ord_optfld_05]'";
				}
			IF ( $adata['uw'] == 'COR' || $adata['uw'] == 'ALL' )
				{
					$query 		.= ",os_cor_flag = '$adata[cor_flag]'";
					$query 		.= ",os_cor_type = '$adata[cor_type]',os_cor_opt_bill_cycle = '$adata[cor_opt_bill_cycle]'";
					$query 		.= ",os_cor_opt_payment = '$adata[cor_opt_payment]',os_cor_disk = '$adata[cor_disk]'";
					$query 		.= ",os_cor_disk_units = '$adata[cor_disk_units]',os_cor_traffic = '$adata[cor_traffic]'";
					$query 		.= ",os_cor_traffic_units = '$adata[cor_traffic_units]',os_cor_dbs = '$adata[cor_dbs]'";
					$query 		.= ",os_cor_mailboxes = '$adata[cor_mailboxes]',os_cor_unique_ip = '$adata[cor_unique_ip]'";
					$query 		.= ",os_cor_shop_cart = '$adata[cor_shop_cart]',os_cor_sec_cert = '$adata[cor_sec_cert]'";
					$query 		.= ",os_cor_site_pages = '$adata[cor_site_pages]',os_cor_comments = '$adata[cor_comments]'";
					$query 		.= ",os_cor_optfld_01 = '$adata[cor_optfld_01]',os_cor_optfld_02 = '$adata[cor_optfld_02]'";
					$query 		.= ",os_cor_optfld_03 = '$adata[cor_optfld_03]',os_cor_optfld_04 = '$adata[cor_optfld_04]'";
					$query 		.= ",os_cor_optfld_05 = '$adata[cor_optfld_05]'";
				}
			$query 		.= " WHERE os_s_id = '".$_si."'";
			$result		= db_query_execute($query);
			$numrows	= db_query_affected_rows ();

		return 1;
	}

# Do orders session select data
function do_orders_session_select()
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows = 0;

		# Set some vars
			$_si = session_id();
			$_tm = dt_get_uts();

		# Do select existing
			$query = ""; $result = ""; $numrows = 0;
			$query 		= "SELECT * FROM ".$_DBCFG['orders_sessions'];
			$query 		.= " WHERE os_s_id = '".$_si."'";
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$data['numrows'] = $numrows;
			while ($row = db_fetch_array($result))
			{
				# Rebuild Data Array with returned record
					$data['numrows']			= $numrows;
					$data['os_s_id']			= $row['os_s_id'];
					$data['os_s_time_init']		= $row['os_s_time_init'];
					$data['os_s_time_last']		= $row['os_s_time_last'];
					$data['os_s_ip']			= $row['os_s_ip'];

					$data['ord_processed']		= $row['os_ord_processed'];
					$data['ord_ret_processed']	= $row['os_ord_ret_processed'];
					$data['ord_id']			= $row['os_ord_id'];
					$data['ord_ts']			= $row['os_ord_ts'];
					$data['ord_ip']			= $row['os_s_ip'];
					$data['ord_status']			= $row['os_ord_status'];
					$data['ord_cl_id']			= $row['os_ord_cl_id'];
					$data['ord_company']		= $row['os_ord_company'];
					$data['ord_name_first']		= $row['os_ord_name_first'];
					$data['ord_name_last']		= $row['os_ord_name_last'];
					$data['ord_addr_01']		= $row['os_ord_addr_01'];
					$data['ord_addr_02']		= $row['os_ord_addr_02'];
					$data['ord_city']			= $row['os_ord_city'];
					$data['ord_state_prov']		= $row['os_ord_state_prov'];
					$data['ord_country']		= $row['os_ord_country'];
					$data['ord_zip_code']		= $row['os_ord_zip_code'];
					$data['ord_phone']			= $row['os_ord_phone'];
					$data['ord_email']			= $row['os_ord_email'];
					$data['ord_domain']			= $row['os_ord_domain'];
					$data['ord_domain_action']	= $row['os_ord_domain_action'];
					$data['ord_user_name']		= $row['os_ord_user_name'];
					$data['ord_user_pword']		= $row['os_ord_user_pword'];
					$data['ord_vendor_id']		= $row['os_ord_vendor_id'];
					$data['ord_prod_id']		= $row['os_ord_prod_id'];
					$data['ord_unit_cost']		= $row['os_ord_unit_cost'];
					$data['ord_accept_tos']		= $row['os_ord_accept_tos'];
					$data['ord_accept_aup']		= $row['os_ord_accept_aup'];
					$data['ord_referred_by']	= $row['os_ord_referred_by'];
					$data['ord_comments']		= $row['os_ord_comments'];
					$data['ord_optfld_01']		= $row['os_ord_optfld_01'];
					$data['ord_optfld_02']		= $row['os_ord_optfld_02'];
					$data['ord_optfld_03']		= $row['os_ord_optfld_03'];
					$data['ord_optfld_04']		= $row['os_ord_optfld_04'];
					$data['ord_optfld_05']		= $row['os_ord_optfld_05'];

					$data['cor_flag']			= $row['os_cor_flag'];
					$data['cor_type']			= $row['os_cor_type'];
					$data['cor_opt_bill_cycle']	= $row['os_cor_opt_bill_cycle'];
					$data['cor_opt_payment']	= $row['os_cor_opt_payment'];
					$data['cor_disk']			= $row['os_cor_disk'];
					$data['cor_disk_units']		= $row['os_cor_disk_units'];
					$data['cor_traffic']		= $row['os_cor_traffic'];
					$data['cor_traffic_units']	= $row['os_cor_traffic_units'];
					$data['cor_dbs']			= $row['os_cor_dbs'];
					$data['cor_mailboxes']		= $row['os_cor_mailboxes'];
					$data['cor_unique_ip']		= $row['os_cor_unique_ip'];
					$data['cor_shop_cart']		= $row['os_cor_shop_cart'];
					$data['cor_sec_cert']		= $row['os_cor_sec_cert'];
					$data['cor_site_pages']		= $row['os_cor_site_pages'];
					$data['cor_comments']		= $row['os_cor_comments'];
					$data['cor_optfld_01']		= $row['os_cor_optfld_01'];
					$data['cor_optfld_02']		= $row['os_cor_optfld_02'];
					$data['cor_optfld_03']		= $row['os_cor_optfld_03'];
					$data['cor_optfld_04']		= $row['os_cor_optfld_04'];
					$data['cor_optfld_05']		= $row['os_cor_optfld_05'];
			}

		return $data;
	}


/**************************************************************
 * Module Functions
**************************************************************/
# Do Data Input Validate
function do_input_validation( $_GPV )
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Get field enabled vars
			$_BV = do_decode_DB16($_CCFG['ORDERS_FIELD_ENABLE_ORD']);

		# Check modes and data as required
			IF ( $_GPV[mode]=='add' )
				{
					# Check required fields (err / action generated later in cade as required)
					#	IF ( !$_GPV[ord_id] )				{ $err_entry['flag'] = 1; $err_entry['ord_id'] = 1; }
						IF ( !$_GPV[ord_ts] )				{ $err_entry['flag'] = 1; $err_entry['ord_ts'] = 1; }
						IF ( !$_GPV[ord_status] )			{ $err_entry['flag'] = 1; $err_entry['ord_status'] = 1; }
						IF ( !$_GPV[ord_cl_id] )			{ $err_entry['flag'] = 1; $err_entry['ord_cl_id'] = 1; }
						IF ($_CCFG['SHOW_PAYMENT_METHOD'] && (!$_GPV[ord_vendor_id] )) { $err_entry['flag'] = 1; $err_entry['ord_vendor_id'] = 1; }
						IF ( !$_GPV[ord_prod_id] )			{ $err_entry['flag'] = 1; $err_entry['ord_prod_id'] = 1; }
				}

			IF ( $_GPV[mode]=='edit' )
				{
					# Check required fields (err / action generated later in cade as required)
						IF ( !$_GPV[ord_id] )				{ $err_entry['flag'] = 1; $err_entry['ord_id'] = 1; }
						IF ( !$_GPV[ord_ts] )				{ $err_entry['flag'] = 1; $err_entry['ord_ts'] = 1; }
						IF ( !$_GPV[ord_status] )			{ $err_entry['flag'] = 1; $err_entry['ord_status'] = 1; }
						IF ( !$_GPV[ord_cl_id] )			{ $err_entry['flag'] = 1; $err_entry['ord_cl_id'] = 1; }
						IF ( $_BV['B16'] == 1 && 1==2 )
							{ IF ( !$_GPV[ord_company] )	{ $err_entry['flag'] = 1; $err_entry['ord_company'] = 1; } }
						IF ( !$_GPV[ord_name_first] )		{ $err_entry['flag'] = 1; $err_entry['ord_name_first'] = 1; }
						IF ( !$_GPV[ord_name_last] )		{ $err_entry['flag'] = 1; $err_entry['ord_name_last'] = 1; }
						IF ( $_BV['B15'] == 1 )
							{ IF ( !$_GPV[ord_addr_01] )	{ $err_entry['flag'] = 1; $err_entry['ord_addr_01'] = 1; } }
						IF ( $_BV['B14'] == 1 && 1==2 )
							{ IF ( !$_GPV[ord_addr_02] )	{ $err_entry['flag'] = 1; $err_entry['ord_addr_02'] = 1; } }
						IF ( $_BV['B13'] == 1 )
							{ IF ( !$_GPV[ord_city] )		{ $err_entry['flag'] = 1; $err_entry['ord_city'] = 1; } }
						IF ( $_BV['B12'] == 1 )
							{ IF ( !$_GPV[ord_state_prov] )	{ $err_entry['flag'] = 1; $err_entry['ord_state_prov'] = 1; } }
						IF ( $_BV['B11'] == 1 )
							{ IF ( !$_GPV[ord_country] )	{ $err_entry['flag'] = 1; $err_entry['ord_country'] = 1; } }
						IF ( $_BV['B10'] == 1 )
							{ IF ( !$_GPV[ord_zip_code] )	{ $err_entry['flag'] = 1; $err_entry['ord_zip_code'] = 1; } }
						IF ( $_BV['B09'] == 1 && 1==2 )
							{ IF ( !$_GPV[ord_phone] )		{ $err_entry['flag'] = 1; $err_entry['ord_phone'] = 1; } }
						IF ( !$_GPV[ord_email] )			{ $err_entry['flag'] = 1; $err_entry['ord_email'] = 1; }
						IF ( !$_GPV[ord_user_name] )		{ $err_entry['flag'] = 1; $err_entry['ord_user_name'] = 1; }
						IF ($_CCFG['SHOW_PAYMENT_METHOD'] && (!$_GPV[ord_vendor_id] ))	{ $err_entry['flag'] = 1; $err_entry['ord_vendor_id'] = 1; }
						IF ( !$_GPV[ord_prod_id] )			{ $err_entry['flag'] = 1; $err_entry['ord_prod_id'] = 1; }
						IF ( !$_GPV[ord_unit_cost] )		{ $err_entry['flag'] = 1; $err_entry['ord_unit_cost'] = 1; }
				}

			IF ( $_GPV[mode]=='order' && $_GPV[stage]==3 )
				{
					# Check required fields (err / action generated later in cade as required)
						IF ( $_BV['B16'] == 1 && 1==2 )
							{ IF ( !$_GPV[ord_company] )	{ $err_entry['flag'] = 1; $err_entry['ord_company'] = 1; } }
						IF ( !$_GPV[ord_name_first] )		{ $err_entry['flag'] = 1; $err_entry['ord_name_first'] = 1; }
						IF ( !$_GPV[ord_name_last] )		{ $err_entry['flag'] = 1; $err_entry['ord_name_last'] = 1; }
						IF ( $_BV['B15'] == 1 )
							{ IF ( !$_GPV[ord_addr_01] )	{ $err_entry['flag'] = 1; $err_entry['ord_addr_01'] = 1; } }
						IF ( $_BV['B14'] == 1 && 1==2 )
							{ IF ( !$_GPV[ord_addr_02] )	{ $err_entry['flag'] = 1; $err_entry['ord_addr_02'] = 1; } }
						IF ( $_BV['B13'] == 1 )
							{ IF ( !$_GPV[ord_city] )		{ $err_entry['flag'] = 1; $err_entry['ord_city'] = 1; } }
						IF ( $_BV['B12'] == 1 )
							{ IF ( !$_GPV[ord_state_prov] )	{ $err_entry['flag'] = 1; $err_entry['ord_state_prov'] = 1; } }
						IF ( $_BV['B11'] == 1 )
							{ IF ( !$_GPV[ord_country] )	{ $err_entry['flag'] = 1; $err_entry['ord_country'] = 1; } }
						IF ( $_BV['B10'] == 1 )
							{ IF ( !$_GPV[ord_zip_code] )	{ $err_entry['flag'] = 1; $err_entry['ord_zip_code'] = 1; } }
						IF ( $_BV['B09'] == 1 && 1==2 )
							{ IF ( !$_GPV[ord_phone] )		{ $err_entry['flag'] = 1; $err_entry['ord_phone'] = 1; } }
						IF ( !$_GPV[ord_email] )			{ $err_entry['flag'] = 1; $err_entry['ord_email'] = 1; }
						IF ( !$_GPV[ord_user_name] )		{ $err_entry['flag'] = 1; $err_entry['ord_user_name'] = 1; }
				}

			IF ($_CCFG['DOMAINS_ENABLE'] && !$_GPV[ord_domain] )	{ $err_entry['flag'] = 1; $err_entry['ord_domain'] = 1; }
			IF ($_CCFG['DOMAINS_ENABLE'] && $_BV['B08'] == 1 && 1==2 )
				{ IF ( !$_GPV[ord_domain_action] )	{ $err_entry['flag'] = 1; $err_entry['ord_domain_action'] = 1; } }
			IF ( $_CCFG['ORDERS_TOS_ENABLE'] == 1 && $_GPV['cor_flag'] != 1 )
				{ IF ( !$_GPV[ord_accept_tos] )				{ $err_entry['flag'] = 1; $err_entry['ord_accept_tos'] = 1; } }
			IF ( $_CCFG['ORDERS_AUP_ENABLE'] == 1 && $_GPV['cor_flag'] != 1 )
				{ IF ( !$_GPV[ord_accept_aup] )				{ $err_entry['flag'] = 1; $err_entry['ord_accept_aup'] = 1; } }
			IF ( $_BV['B07'] == 1 && 1==2 )
				{ IF ( !$_GPV[ord_referred_by] )			{ $err_entry['flag'] = 1; $err_entry['ord_referred_by'] = 1; } }


		# Validate some data (submitting data entered)
			# Email
				IF (do_validate_email($_GPV[ord_email],0) && ($_GPV[mode] == 'edit' || $_GPV[mode] == 'order') )
					{ $err_entry['flag'] = 1; $err_entry['ord_email'] = 1; $err_entry['err_email_invalid'] = 1; }

			# Domain name to top level doms
				IF ($_CCFG['DOMAINS_ENABLE'] && !do_domain_validate ( $_GPV[ord_domain] ) )
					{ $err_entry['flag'] = 1; $err_entry['ord_domain'] = 1; $err_entry['err_domain_invalid'] = 1; }

			# Unique domain name- must be unique or belong to this user
				IF ( $_GPV[mode]=='add' || ( $_GPV[mode]=='edit' && $_GPV[ord_domain] != $_GPV[ord_domain_orig] ) )
					{
						IF ($_CCFG['DOMAINS_ENABLE'] && do_domain_exist_check ( $_GPV[ord_domain] ) && !do_get_client_domain_id ( $_GPV[ord_domain], $_GPV[ord_cl_id] ) )
							{ $err_entry['flag'] = 1; $err_entry['ord_domain'] = 1; $err_entry['err_domain_exist'] = 1; }
					}
				IF ( $_GPV[mode]=='order' && $_GPV[stage]==3)
					{
						# Validate some data (submitting data entered)
							# If new client, unique check that domain does not exist
							# If existing client, unique check that if exists, belongs to client
							IF ( !$_SEC['_suser_flg'] || $_SEC['_suser_id'] == 0)
								{
									IF ($_CCFG['DOMAINS_ENABLE'] && do_domain_exist_check ( $_GPV[ord_domain] ) )
										{ $err_entry['flag'] = 1; $err_entry['ord_domain'] = 1; $err_entry['err_domain_exist'] = 1; }
								}
							ELSEIF ( $_SEC['_suser_flg'] && $_SEC['_suser_id'] > 0)
								{
									IF ($_CCFG['DOMAINS_ENABLE'] && do_domain_exist_check ( $_GPV[ord_domain] ) && !do_get_client_domain_id ( $_GPV[ord_domain], $_SEC['_suser_id'] ) )
										{ $err_entry['flag'] = 1; $err_entry['ord_domain'] = 1; $err_entry['err_domain_exist'] = 1; }
								}
					}

			# Unique user name- does exist
				IF ( $_GPV[mode]=='add' || ( $_GPV[mode]=='edit' && $_GPV[ord_user_name] != $_GPV[ord_user_name_orig] ) )
					{
						IF ( do_user_name_exist_check($_GPV[ord_user_name], 'user') )
							{ $err_entry['flag'] = 1; $err_entry['ord_user_name'] = 1; $err_entry['err_user_name_exist'] = 1; }
					}
				IF ( $_GPV[mode]=='order' && $_GPV[stage]==3)
					{
						IF ( do_user_name_exist_check($_GPV[ord_user_name], 'user') )
							{
								IF ( !$_SEC['_suser_flg'] )
									{ $err_entry['flag'] = 1; $err_entry['ord_user_name'] = 1;  $err_entry['err_user_name_exist'] = 1; }
							}
					}

			# Passwords equal
				IF ( $_GPV[ord_user_pword] && $_GPV[ord_user_pword_re] && ( $_GPV[ord_user_pword] != $_GPV[ord_user_pword_re] ) )
					{
						$err_entry['flag'] = 1;
						$err_entry['err_pword_match'] = 1;
						$err_entry['ord_user_pword'] = 1;
						$err_entry['ord_user_pword_re'] = 1;
					}

			# Passwords empty on ordering
				IF ( $_GPV[mode]=='order' && $_GPV[stage]==3)
					{
						# Check existing client- existing can leave passwords blank- match check below
							IF ( !$_SEC['_suser_flg'] || $_SEC['_suser_id'] == 0)
								{
									IF ( !$_GPV[ord_user_pword] )		{ $err_entry['flag'] = 1; $err_entry['ord_user_pword'] = 1; }
									IF ( !$_GPV[ord_user_pword_re] )	{ $err_entry['flag'] = 1; $err_entry['ord_user_pword_re'] = 1; }
								}
					}

		return $err_entry;
	}


# Do COR Request Type select list
function do_select_list_cor_req_type( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="none">'.$_LANG['_ORDERS']['Select_Request_Type'].'</option>'.$_nl;

		# Load config array and sort
			$_tmp_array = $_CCFG[COR_REQ_TYPE];
			sort($_tmp_array);

		# Loop array and load list
			FOR ($i = 0; $i < count($_tmp_array); $i++)
			{
				$_out .= '<option value="'.$_tmp_array[$i].'"';
				IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_tmp_array[$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do COR Optional Billing select list
function do_select_list_cor_opt_bill_cycle( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="none">'.$_LANG['_ORDERS']['Select_Billing_Cycle'].'</option>'.$_nl;

		# Loop array and load list
			FOR ($i = 0; $i < count($_CCFG[COR_OPT_BILL_CYCLE]); $i++)
			{
				$_out .= '<option value="'.$_CCFG['COR_OPT_BILL_CYCLE'][$i].'"';
				IF ( $_CCFG['COR_OPT_BILL_CYCLE'][$i] == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_CCFG['COR_OPT_BILL_CYCLE'][$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do COR Optional Payment select list
function do_select_list_cor_opt_payment( $aname, $avalue, $aret_flag=0 ) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="none">'.$_LANG['_ORDERS']['Select_Payment_Type'].'</option>'.$_nl;

	# Load config array and sort
		$_tmp_array = $_CCFG[COR_OPT_PAYMENT];
		sort($_tmp_array);

	# Loop array and load list
		FOR ($i = 0; $i < count($_tmp_array); $i++) {
			$_out .= '<option value="'.$_tmp_array[$i].'"';
			IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_tmp_array[$i].'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

	# return results
		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do Order Products select list
function do_select_list_order_products($aname, $avalue, $group="") {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS = do_decode_perms_admin($_SEC[_sadmin_perms]);
		$_GROUPS = do_decode_groups_user($_SEC[_suser_groups]);

	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;
		$oldproduct = ""; $v_new_product = "";

	# Set Query for select.
		$query = "SELECT prod_id, prod_name, prod_desc, prod_unit_cost";
		$query .= " FROM ".$_DBCFG['products'];
		$query .= " WHERE ".$_DBCFG['products'].".prod_status = 1";
		IF ( !$_SEC['_sadmin_flg'] && !$group) {
			$query .= " AND ".$_DBCFG['products'].".prod_client_scope = 0";
		}

		IF ($group) {$query .= " AND ".$_DBCFG['products'].".prod_cg_0".$group." = 1"; }

		IF ( $_SEC['_suser_flg'] ) {
			$query .= " OR ".$_DBCFG['products'].".prod_client_scope = -1";
			$query .= " OR ".$_DBCFG['products'].".prod_client_scope = ".$_SEC['_suser_id'];
			IF ( $_GROUPS[UG08] == 1 ) { $query .= " OR ".$_DBCFG['products'].".prod_cg_08 = 1"; }
			IF ( $_GROUPS[UG07] == 1 ) { $query .= " OR ".$_DBCFG['products'].".prod_cg_07 = 1"; }
			IF ( $_GROUPS[UG06] == 1 ) { $query .= " OR ".$_DBCFG['products'].".prod_cg_06 = 1"; }
			IF ( $_GROUPS[UG05] == 1 ) { $query .= " OR ".$_DBCFG['products'].".prod_cg_05 = 1"; }
			IF ( $_GROUPS[UG04] == 1 ) { $query .= " OR ".$_DBCFG['products'].".prod_cg_04 = 1"; }
			IF ( $_GROUPS[UG03] == 1 ) { $query .= " OR ".$_DBCFG['products'].".prod_cg_03 = 1"; }
			IF ( $_GROUPS[UG02] == 1 ) { $query .= " OR ".$_DBCFG['products'].".prod_cg_02 = 1"; }
			IF ( $_GROUPS[UG01] == 1 ) { $query .= " OR ".$_DBCFG['products'].".prod_cg_01 = 1"; }
		}

	# Set sort order based on config
		switch($_CCFG['ORDERS_PROD_LIST_SORT_ORDER']) {
			case "0":
				$query .= " ORDER BY prod_id ASC";
				break;
			case "1":
				$query .= " ORDER BY prod_name ASC";
				break;
			case "2":
				$query .= " ORDER BY prod_desc ASC";
				break;
			case "3":
				$query .= " ORDER BY prod_unit_cost ASC";
				break;
			default:
				$query .= " ORDER BY prod_name ASC";
				break;
		}

	# Do select
		$result = db_query_execute($query);
		$numrows = db_query_numrows($result);

	# Do select list
		IF ($_CCFG['ORDERS_PROD_LIST_SIZE']) {
			$_out .= '<select class="select_form" name="'.$aname.'" size="'.$_CCFG['ORDERS_PROD_LIST_SIZE'].'" value="'.$avalue.'">'.$_nl;
		}


	# Process query results
		while(list($prod_id, $prod_name, $prod_desc, $prod_unit_cost) = db_fetch_row($result)) {
			IF (!$avalue && !$looped) {
				$avalue = $prod_id;
				$looped++;
			}

		# Build our desired product listing sequence display
			IF ($_CCFG['ORDERS_PROD_DISPLAY_SEQUENCE'] == "1") {
				$part1 = $prod_desc . ' - ' . $_CCFG['_CURRENCY_PREFIX'] . $_sp . do_currency_format($prod_unit_cost) . $_sp . $_CCFG['_CURRENCY_SUFFIX'];
			} ELSEIF ($_CCFG['ORDERS_PROD_DISPLAY_SEQUENCE'] == "2") {
				$part1 = $_CCFG['_CURRENCY_PREFIX'] . $_sp . do_currency_format($prod_unit_cost) . $_sp . $_CCFG['_CURRENCY_SUFFIX'] . ' - ' . $prod_desc;
			} ELSEIF ($_CCFG['ORDERS_PROD_DISPLAY_SEQUENCE'] == "3") {
				$part1 = $prod_name . ' - ' . $_CCFG['_CURRENCY_PREFIX'] . $_sp . do_currency_format($prod_unit_cost) . $_sp . $_CCFG['_CURRENCY_SUFFIX'];
			} ELSEIF ($_CCFG['ORDERS_PROD_DISPLAY_SEQUENCE'] == "4") {
				$part1 = $_CCFG['_CURRENCY_PREFIX'] . $_sp . do_currency_format($prod_unit_cost) . $_sp . $_CCFG['_CURRENCY_SUFFIX'] . ' - ' . $prod_name;
			} ELSEIF ($_CCFG['ORDERS_PROD_DISPLAY_SEQUENCE'] == "5") {
				$part1 = $prod_name . ' - ' . $prod_desc . ' - ' . $_CCFG['_CURRENCY_PREFIX'] . $_sp . do_currency_format($prod_unit_cost) . $_sp . $_CCFG['_CURRENCY_SUFFIX'];
			} ELSEIF ($_CCFG['ORDERS_PROD_DISPLAY_SEQUENCE'] == "6") {
				$part1 = $prod_desc . ' - ' . $prod_name . ' - ' . $_CCFG['_CURRENCY_PREFIX'] . $_sp . do_currency_format($prod_unit_cost) . $_sp . $_CCFG['_CURRENCY_SUFFIX'];
			} ELSEIF ($_CCFG['ORDERS_PROD_DISPLAY_SEQUENCE'] == "7") {
				$part1 = $_CCFG['_CURRENCY_PREFIX'] . $_sp . do_currency_format($prod_unit_cost) . $_sp . $_CCFG['_CURRENCY_SUFFIX'] . ' - ' . $prod_name . ' - ' . $prod_desc;
			} ELSE {
				$part1 = $_CCFG['_CURRENCY_PREFIX'] . $_sp . do_currency_format($prod_unit_cost) . $_sp . $_CCFG['_CURRENCY_SUFFIX'] . ' - ' . $prod_desc . ' - ' . $prod_name;
			}


			IF ($_CCFG['ORDERS_ITEMS_SUB-LIST'] && !$_CCFG['ORDERS_PROD_LIST_SIZE']) {

			# Take $part1 from the beginning up to the first blank and
			# put that in a variable called V_new_product.
				$pieces = explode(" ", $part1);
				$v_new_product = $pieces[0];

			# if old value is not equal to  new value, output in bold the Product name
				IF ( $oldproduct != $v_new_product ) {
					$oldproduct = $v_new_product;
					$_out .= '<br><br><b>"' .$v_new_product. '"</b><br>';
				}
			}


		# Build the output
			IF ($_CCFG['ORDERS_PROD_LIST_SIZE']) {
				$_out .= '<option value="'.$prod_id.'"';
				IF ( $prod_id == $avalue ) { $_out .= ' selected'; }
				$_out .= '>' . $part1 . '</option>'.$_nl;
			} ELSE {
				# suggested by ays, http://www.quantumcity.com/
				$_out .= '<label>';
				IF ($_CCFG['ORDERS_ITEMS_SUB-LIST']) {$_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';}
				$_out .= '<input type="radio" name="'.$aname.'" value="'.$prod_id.'"';
				IF ( $prod_id == $avalue ) { $_out .= ' checked'; }
				$_out .= '>' . $part1 . '<br></label>'.$_nl;
			}
		}
		IF ($_CCFG['ORDERS_PROD_LIST_SIZE']) {$_out .= '</select>'.$_nl;}

	# return form fields
		return $_out;
}


# Do Order Vendors select list
function do_select_list_order_vendors($aname, $avalue)
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows = 0;

		# Set Query for select.
			$query	= "SELECT vendor_id, vendor_name";
			$query	.= " FROM ".$_DBCFG['vendors'];
			$query	.= " WHERE ".$_DBCFG['vendors'].".vendor_status = 1";
			$query	.= " ORDER BY vendor_id ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build Form row
			$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

				# Process query results
					while(list($vendor_id, $vendor_name) = db_fetch_row($result))
					{
						$_out .= '<option value="'.$vendor_id.'"';
						IF ( $vendor_id == $avalue ) { $_out .= ' selected'; }
						$_out .= '>'.$vendor_name.'</option>'.$_nl;
					}

			$_out .= '</select>'.$_nl;
			return $_out;
	}


# Do Domain Action select list
function do_select_list_dom_action( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
	#		$_out .= '<option value="none">'.$_LANG['_ORDERS']['Select_Request_Type'].'</option>'.$_nl;

		# Set default if no value
			IF ( $avalue == '' ) { $avalue = 0; };

		# Loop array and load list
			FOR ($i = 0; $i < count($_CCFG['ORD_DOM_ACT']); $i++)
			{
				$_out .= '<option value="'.$i.'"';
				IF ( $i == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_CCFG['ORD_DOM_ACT'][$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}

############################################
# Do display entry (individual order entry)
function do_display_entry ( $adata, $aret_flag=0 ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	.= "SELECT *";
		$query	.= " FROM ".$_DBCFG['orders'];
		IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$query .=", ".$_DBCFG['vendors'];}
		$query .= ", ".$_DBCFG['products'];
		$query  .= ' WHERE ';
		IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$query	.= " ".$_DBCFG['orders'].".ord_vendor_id = ".$_DBCFG['vendors'].".vendor_id".' AND ';}
		$query	.= $_DBCFG['orders'].".ord_prod_id = ".$_DBCFG['products'].".prod_id";
		$query	.= " AND ".$_DBCFG['orders'].".ord_id = ".$adata['ord_id'];

	# Set to logged in Client ID if not admin to avoid seeing other client order id's
		IF ( !$_SEC['_sadmin_flg'] ) {
			$query	.= " AND ".$_DBCFG['orders'].".ord_cl_id = ".$_SEC['_suser_id'];
		}

		$query .= " ORDER BY ".$_DBCFG['orders'].".ord_id";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build Title String, Content String, and Footer Menu String
		$_tstr .= $_LANG['_ORDERS']['View_Client_Order_ID'].' '.$adata['ord_id'];
		IF ($_SEC['_sadmin_flg'] && !$_CCFG['_IS_PRINT']) {

		# Read order so we can get the client_id, then output the button
			$clinfo = get_mtp_order_info($adata[ord_id]);
			$_tstr .= ' <a href="mod.php?mod=clients&mode=view&cl_id='.$clinfo[ord_cl_id].'">'.$_TCFG['_IMG_BACK_TO_CLIENT_M'].'</a>'.$_nl;
		}

	# Build common td start tag / col strings (reduce text)
		$_td_str_left		= '<td class="TP1SML_NR" width="40%">';
		$_td_str_right		= '<td class="TP1SML_NL" width="60%">';
		$_td_str_center		= '<td class="TP1SML_NC">';
		$_td_str_span_2		= '<td class="TP1SML_NC" colspan="2" valign="top">';
		$_spacer_row		= '<tr class="BLK_DEF_ENTRY">'.$_nl.$_td_str_span_2.'<b>'.$_sp.'</b></td>'.$_nl.'</tr>';

	# Process query results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {
				$firstname = $row['ord_name_first'];
				$lastname = $row['ord_name_last'];
				$_cstr .= '<br>'.$_nl.'<center>'.$_nl;
				$_cstr .= '<table cellpadding="0" width="96%">'.$_nl;

				$_cstr .= '<tr>'.$_nl;

			# Order Info Cell
				$_cstr .= '<td class="TP3SML_NC" valign="top" width="50%">'.$_nl;

				$_cstr .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_TITLE"><td class="TP3MED_BL">'.$_nl;
				$_cstr .= $_LANG['_ORDERS']['l_Order_Information'].$_nl;
				$_cstr .= '</td></tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_td_str_center.$_nl;

				$_cstr .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">'.$_nl;
				#	$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl.$_td_str_span_2.'<b>'.$_sp.'</b></td>'.$_nl.'</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Order_ID'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_id'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Order_Date'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.dt_make_datetime ( $row['ord_ts'], $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Order_Status'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_status'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl.$_td_str_span_2.'<b>'.$_sp.'</b></td>'.$_nl.'</tr>'.$_nl;

				IF ($_CCFG['DOMAINS_ENABLE']) {
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Domain'].$_sp.'</b></td>'.$_nl;
					$_cstr .= $_td_str_right.$row['ord_domain'];

				# Order with no domain but domains are still enabled
    	            IF ($row['ord_domain'] && $row['ord_domain'] != 'NONE' && !$_CCFG['_IS_PRINT'] && ($_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP06] == 1))) {
						$_cstr .= ' <A href="mod.php?mod=domains&mode=edit&dom_id=';
						$_cstr .= do_get_domain_id($row['ord_domain']);
						$_cstr .= '">';
						$_cstr .= $_TCFG['_S_IMG_EDIT_S'].'</a>';
					}

					$_cstr .= '</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Domain_Action'].$_sp.'</b></td>'.$_nl;
					$_cstr .= $_td_str_right.$_CCFG['ORD_DOM_ACT'][$row['ord_domain_action']].'</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
				}

				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Referred_By'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_referred_by'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl.$_td_str_span_2.'<b>'.$_sp.'</b></td>'.$_nl.'</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Accepted_TOS'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.do_valtostr_no_yes($row['ord_accept_tos']).'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Accepted_AUP'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.do_valtostr_no_yes($row['ord_accept_aup']).'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				IF ( $row['ord_optfld_01'] ) {
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_cstr .= $_td_str_left.'<b>'.$_CCFG['ORD_LABEL_OPTFLD_01'].$_sp.'</b></td>'.$_nl;
					$_cstr .= $_td_str_right.$row['ord_optfld_01'].'</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
				}
				IF ( $row['ord_optfld_02'] ) {
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_cstr .= $_td_str_left.'<b>'.$_CCFG['ORD_LABEL_OPTFLD_02'].$_sp.'</b></td>'.$_nl;
					$_cstr .= $_td_str_right.$row['ord_optfld_02'].'</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
				}
				IF ( $row['ord_optfld_03'] ) {
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_cstr .= $_td_str_left.'<b>'.$_CCFG['ORD_LABEL_OPTFLD_03'].$_sp.'</b></td>'.$_nl;
					$_cstr .= $_td_str_right.$row['ord_optfld_03'].'</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
				}
				IF ( $row['ord_optfld_04'] ) {
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_cstr .= $_td_str_left.'<b>'.$_CCFG['ORD_LABEL_OPTFLD_04'].$_sp.'</b></td>'.$_nl;
					$_cstr .= $_td_str_right.$row['ord_optfld_04'].'</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
				}
				IF ( $row['ord_optfld_05'] ) {
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_cstr .= $_td_str_left.'<b>'.$_CCFG['ORD_LABEL_OPTFLD_05'].$_sp.'</b></td>'.$_nl;
					$_cstr .= $_td_str_right.$row['ord_optfld_05'].'</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
				}
				IF ($_SEC['_sadmin_flg']) {
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Client_IP'].$_sp.'</b></td>'.$_nl;
					$_cstr .= $_td_str_right.$row['ord_ip'].'</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
				} ELSE {
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl.$_td_str_span_2.'<b>'.$_sp.'</b></td>'.$_nl.'</tr>'.$_nl;
				}

			# display invoice number and "view" link
				IF ( $row['ord_invc_id'] ) {
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Invoice_ID'].$_sp.'</b></td>'.$_nl;
					$_cstr .= $_td_str_right;
					IF ($row['ord_invc_id']) {
						$_cstr .= $row['ord_invc_id'];
						IF (!$_CCFG['_IS_PRINT']) {
							$_cstr .= $_sp.$_sp.$_sp;
							$_cstr .= '<a href="mod.php?mod=invoices&invc_id=';
							$_cstr .= $row['ord_invc_id'].'">'.$_TCFG['_S_IMG_VIEW_S'].'</a>'.$_nl;
						}
					}
					$_cstr .= '</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
				}

				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl.$_td_str_span_2.'<b>'.$_sp.'</b></td>'.$_nl.'</tr>'.$_nl;
				#	$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl.$_td_str_span_2.'<b>'.$_sp.'</b></td>'.$_nl.'</tr>'.$_nl;
				$_cstr .= '</table>'.$_nl.'</td>'.$_nl;

				$_cstr .= '</td>'.$_nl.'</tr>'.$_nl;
				$_cstr .= '</table>'.$_nl;

				$_cstr .= '</td>'.$_nl;

			# Client Info Cell
				$_cstr .= '<td class="TP3SML_NC" valign="top" width="50%">'.$_nl;

				$_cstr .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_TITLE"><td class="TP3MED_BL">'.$_nl;
				$_cstr .= $_LANG['_ORDERS']['l_Client_Information'].$_nl;
				$_cstr .= '</td></tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_td_str_center.$_nl;

				$_cstr .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">'.$_nl;
				#	$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl.$_td_str_span_2.'<b>'.$_sp.'</b></td>'.$_nl.'</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Client_ID'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_cl_id'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Username'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_user_name'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Company'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_company'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Client_Name:'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_name_first'].$_sp.$row['ord_name_last'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Address_Street_1'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_addr_01'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Address_Street_2'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_addr_02'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_City'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_city'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_State_Province'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_state_prov'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Country'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_country'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Zip_Postal_Code'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_zip_code'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Email'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_email'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Phone'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$row['ord_phone'].'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				IF ( $row['ord_optfld_01'] ) { $_spacer_row.$_nl; }
				IF ( $row['ord_optfld_02'] ) { $_spacer_row.$_nl; }
				IF ( $row['ord_optfld_03'] ) { $_spacer_row.$_nl; }
				IF ( $row['ord_optfld_04'] ) { $_spacer_row.$_nl; }
				IF ( $row['ord_optfld_05'] ) { $_spacer_row.$_nl; }
				#	$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl.$_td_str_span_2.'<b>'.$_sp.'</b></td>'.$_nl.'</tr>'.$_nl;
				$_cstr .= '</table>'.$_nl;

				$_cstr .= '</td>'.$_nl.'</tr>'.$_nl;
				$_cstr .= '</table>'.$_nl;

				$_cstr .= '</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;

			# Client Order Comments Row
				IF ( $row['ord_comments'] != '' ) {
					$_cstr .= '<tr>'.$_nl;
					$_cstr .= '<td class="TP3SML_NC" colspan="2" valign="top">'.$_nl;
					$_cstr .= '<div align="center">'.$_nl;
					$_cstr .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
					$_cstr .= '<tr class="BLK_DEF_TITLE"><td class="TP3SML_NC">'.$_nl;
					$_cstr .= '<b>'.$_LANG['_ORDERS']['l_Additional_Comments'].'</b><br>'.$_nl;
					$_cstr .= '</td></tr>'.$_nl;
					$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_cstr .= '<td class="TP3SML_NJ">'.nl2br(do_stripslashes($row['ord_comments'])).'</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
					$_cstr .= '</table>'.$_nl;
					$_cstr .= '</div>'.$_nl;
					$_cstr .= '</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
				}

			# Product(s) Ordered Row
				$_cstr .= '<tr>'.$_nl;
				$_cstr .= '<td class="TP3SML_NC" colspan="2" valign="top">'.$_nl;
				$_cstr .= '<div align="center">'.$_nl;
				$_cstr .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_TITLE"><td class="TP3SML_NC" colspan="5">'.$_nl;
				$_cstr .= '<b>'.$_LANG['_ORDERS']['l_Products_Ordered'].'</b><br>'.$_nl;
				$_cstr .= '</td></tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= '<td class="TP3SML_NC"><b>'.$_LANG['_ORDERS']['l_Item_No'].'</b></td>'.$_nl;

				IF ($_CCFG['SHOW_PAYMENT_METHOD']) {
					$_cstr .= '<td class="TP3SML_NL"><b>';
					$_cstr .= $_LANG['_ORDERS']['l_Vendor'];
					$_cstr .= '</b></td>'.$_nl;
				}
				$_cstr .= '<td class="TP3SML_NL"><b>'.$_LANG['_ORDERS']['l_Product_Name'].'</b></td>'.$_nl;
				$_cstr .= '<td class="TP3SML_NL"><b>'.$_LANG['_ORDERS']['l_Product_Description'].'</b></td>'.$_nl;
				$_cstr .= '<td class="TP3SML_NR"><b>'.$_LANG['_ORDERS']['l_Unit_Cost'].'</b>'.$_sp.$_sp.'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_cstr .= '<td class="TP3SML_NC">'.'1'.'</td>'.$_nl;
				IF ($_CCFG['SHOW_PAYMENT_METHOD']) {
					$_cstr .= '<td class="TP3SML_NL">';
					$_cstr .= $row['vendor_name'];
					$_cstr .= '</td>'.$_nl;
				}
				$_cstr .= '<td class="TP3SML_NL">'.$row['prod_name'].'</td>'.$_nl;
				$_cstr .= '<td class="TP3SML_NL">'.$row['prod_desc'].'</td>'.$_nl;
				$_cstr .= '<td class="TP3SML_NR">'.do_currency_format ( $row['ord_unit_cost'] ).$_sp.$_sp.'</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
				$_cstr .= '</table>'.$_nl;
				$_cstr .= '</div>'.$_nl;
				$_cstr .= '</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;

				$_cstr .= '</table>'.$_nl;
				$_cstr .= '</center>'.$_nl.'<br>'.$_nl;
			}
		} ELSE {

		# Build Title String, Content String, and Footer Menu String
			$_tstr .= $_LANG['_ORDERS']['View_Client_Order_ID'];
			$_cstr .= '<center>'.$_nl;
			$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= '<td class="TP3MED_NC"><b>'.$_LANG['_ORDERS']['Error_Order_Not_Found'].'</b></td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;
			$_cstr .= '</center>'.$_nl;
		}

		IF ( $_CCFG['_IS_PRINT'] != 1 ) {
			IF ($_SEC['_sadmin_flg']) {

			# Build function argument text
				$_mstr_flag = '1';
				$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
				$_mstr .= do_nav_link ('mod_print.php?mod=orders&mode=view&ord_id='.$adata[ord_id], $_TCFG['_IMG_PRINT_M'],$_TCFG['_IMG_PRINT_M_MO'],'_new','');
				$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=mail&ord_id='.$adata[ord_id], $_TCFG['_IMG_EMAIL_M'],$_TCFG['_IMG_EMAIL_M_MO'],'','');
				IF ( $_PERMS[AP16] == 1 || $_PERMS[AP08] == 1 ) {
					$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=edit&ord_id='.$adata[ord_id], $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
					$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=delete&stage=1&ord_id='.$adata['ord_id'].'&ord_name_first='.$firstname.'&ord_name_last='.$lastname, $_TCFG['_IMG_DELETE_M'],$_TCFG['_IMG_DELETE_M_MO'],'','');
					$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
				}
				$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');
			} ELSE {

			# Build function argument text
				$_mstr_flag = '1';
				$_mstr .= do_nav_link ('mod_print.php?mod=orders&mode=view&ord_id='.$adata[ord_id], $_TCFG['_IMG_PRINT_M'],$_TCFG['_IMG_PRINT_M_MO'],'_new','');
				$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=mail&ord_id='.$adata[ord_id], $_TCFG['_IMG_EMAIL_M'],$_TCFG['_IMG_EMAIL_M_MO'],'','');
				$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');
			}
		} ELSE {
			$_mstr_flag = '0';
		}

	# Call block it function
		$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
		$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do list field form for: Client Orders
function do_view_orders( $adata, $aret_flag=0 ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	.= "SELECT *";
		$query	.= " FROM ".$_DBCFG['orders'];
		IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$query .= ", ".$_DBCFG['vendors'];}
		$query .= ", ".$_DBCFG['products'].' WHERE ';
		IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$_where	.= $_DBCFG['orders'].".ord_vendor_id = ".$_DBCFG['vendors'].".vendor_id AND ";}
		$_where	.= $_DBCFG['orders'].".ord_prod_id = ".$_DBCFG['products'].".prod_id";

	# Set to logged in Client ID if not admin to avoid seeing other client order id's
		IF ( !$_SEC['_sadmin_flg'] ) {
			$_where	.= " AND ".$_DBCFG['orders'].".ord_cl_id = ".$_SEC['_suser_id'];
		} ELSE {
			IF ( $adata['ord_cl_id'] > 0 ) {
				$_where	.= " AND ".$_DBCFG['orders'].".ord_cl_id = ".$adata['ord_cl_id'];
			}
		}

	# Set Filters
		IF ( !$adata['fb'] )		{ $adata['fb']='';	}
		IF ( $adata['fb']=='1' )	{ $_where .= " AND ".$_DBCFG['orders'].".ord_status='".$adata['fs']."'";	}

	# Set Order ASC / DESC part of sort
		IF ( !$adata['so'] )		{ $adata['so']='D'; }
		IF ( $adata['so']=='A' )	{ $order_AD = " ASC"; }
		IF ( $adata['so']=='D' )	{ $order_AD = " DESC"; }

	# Set Order
		IF ( !$adata['sb'] )			{ $adata['sb']='1';	}
		IF ( $adata['sb']=='1' )		{ $_order = " ORDER BY ".$_DBCFG['orders'].".ord_id ".$order_AD;	}
		IF ( $adata['sb']=='2' )		{ $_order = " ORDER BY ".$_DBCFG['orders'].".ord_status ".$order_AD;	}
		IF ( $adata['sb']=='3' )		{ $_order = " ORDER BY ".$_DBCFG['orders'].".ord_ts ".$order_AD;	}
		IF ( $adata['sb']=='4' )		{ $_order = " ORDER BY ".$_DBCFG['orders'].".ord_domain ".$order_AD;	}
		IF ( $adata['sb']=='5' )		{ $_order = " ORDER BY ".$_DBCFG['vendors'].".vendor_name ".$order_AD;	}
		IF ( $adata['sb']=='6' )		{ $_order = " ORDER BY ".$_DBCFG['products'].".prod_name ".$order_AD;	}
		IF ( $adata['sb']=='7' )		{ $_order = " ORDER BY ".$_DBCFG['products'].".prod_desc ".$order_AD;	}

	# Set / Calc additional paramters string
		IF ($adata['sb'])	{ $_argsb= '&sb='.$adata['sb']; }
		IF ($adata['so'])	{ $_argso= '&so='.$adata['so']; }
		IF ($adata['fb'])	{ $_argfb= '&fb='.$adata['fb']; }
		IF ($adata['fs'])	{ $_argfs= '&fs='.$adata['fs']; }
		$_link_xtra			= $_argsb.$_argso.$_argfb.$_argfs;

	# Build Page menu
	# Get count of rows total for pages menu:
		$query_ttl = "SELECT COUNT(*)";
		$query_ttl .= " FROM ".$_DBCFG['orders'];
		IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$query_ttl .= ", ".$_DBCFG['vendors'];}
		$query_ttl .= ", ".$_DBCFG['products'].' WHERE ';
		$query_ttl .= $_where;

		$result_ttl= db_query_execute($query_ttl);
		while(list($cnt) = db_fetch_row($result_ttl)) {	$numrows_ttl = $cnt; }

		# Page Loading first rec number
			# $_rec_next	- is page loading first record number
			# $_rec_start	- is a given page start record (which will be rec_next)
			$_rec_page	= $_CCFG['IPP_ORDERS'];
			$_rec_next	= $adata['rec_next'];
			IF (!$_rec_next) { $_rec_next=0; }

		# Range of records on current page
			$_rec_next_lo = $_rec_next+1;
			$_rec_next_hi = $_rec_next+$_rec_page;
			IF ( $_rec_next_hi > $numrows_ttl) { $_rec_next_hi = $numrows_ttl; }

		# Calc no pages,
			$_num_pages = round(($numrows_ttl/$_rec_page), 0);
			IF ( $_num_pages < ($numrows_ttl/$_rec_page) ) { $_num_pages = $_num_pages+1; }

		# Loop Array and Print Out Page Menu HTML
			$_page_menu = $_LANG['_ORDERS']['l_Pages'].' ';
			for ($i = 1; $i <= $_num_pages; $i++) {
				$_rec_start = ( ($i*$_rec_page)-$_rec_page);
				IF ( $_rec_start == $_rec_next ) {
					# Loading Page start record so no link for this page.
					$_page_menu .= "$i";
				} ELSE {
					$_page_menu .= '<a href="'.$_SERVER["PHP_SELF"].'?mod=orders&mode=view'.$_link_xtra.'&rec_next='.$_rec_start.'">'.$i.'</a>';
				}

				IF ( $i < $_num_pages ) { $_page_menu .= ",".$_sp; }
			}
		# End page menu

		# Finish out query with record limits and do data select for display and return check
			$query		.= $_where.$_order." LIMIT $_rec_next, $_rec_page";
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Generate links for sorting
			$_hdr_link_prefix = '<a href="'.$_SERVER["PHP_SELF"].'?mod=orders&mode=view&sb=';
			$_hdr_link_suffix = '&fb='.$adata['fb'].'&fs='.$adata['fs'].'&fc='.$adata['fc'].'&rec_next='.$_rec_next.'">';

			$_hdr_link_1 .= $_LANG['_ORDERS']['l_Id'].$_sp.'<br>';
			$_hdr_link_1 .= $_hdr_link_prefix.'1&so=A'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_ASC_S'].'</a>';
			$_hdr_link_1 .= $_hdr_link_prefix.'1&so=D'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_DSC_S'].'</a>';

			$_hdr_link_2 .= $_LANG['_ORDERS']['l_Status'].$_sp.'<br>';
			$_hdr_link_2 .= $_hdr_link_prefix.'2&so=A'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_ASC_S'].'</a>';
			$_hdr_link_2 .= $_hdr_link_prefix.'2&so=D'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_DSC_S'].'</a>';

			$_hdr_link_3 .= $_LANG['_ORDERS']['l_Date'].$_sp.'<br>';
			$_hdr_link_3 .= $_hdr_link_prefix.'3&so=A'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_ASC_S'].'</a>';
			$_hdr_link_3 .= $_hdr_link_prefix.'3&so=D'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_DSC_S'].'</a>';

			$_hdr_link_4 .= $_LANG['_ORDERS']['l_Domain'].$_sp.'<br>';
			$_hdr_link_4 .= $_hdr_link_prefix.'4&so=A'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_ASC_S'].'</a>';
			$_hdr_link_4 .= $_hdr_link_prefix.'4&so=D'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_DSC_S'].'</a>';

			$_hdr_link_5 .= $_LANG['_ORDERS']['l_Vendor'].$_sp.'<br>';
			$_hdr_link_5 .= $_hdr_link_prefix.'5&so=A'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_ASC_S'].'</a>';
			$_hdr_link_5 .= $_hdr_link_prefix.'5&so=D'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_DSC_S'].'</a>';

			$_hdr_link_6 .= $_LANG['_ORDERS']['l_Product'].$_sp.'<br>';
			$_hdr_link_6 .= $_hdr_link_prefix.'6&so=A'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_ASC_S'].'</a>';
			$_hdr_link_6 .= $_hdr_link_prefix.'6&so=D'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_DSC_S'].'</a>';

			$_hdr_link_7 .= $_LANG['_ORDERS']['l_Product_Description'].$_sp.'<br>';
			$_hdr_link_7 .= $_hdr_link_prefix.'7&so=A'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_ASC_S'].'</a>';
			$_hdr_link_7 .= $_hdr_link_prefix.'7&so=D'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_DSC_S'].'</a>';

			$_hdr_link_8 .= $_LANG['_ORDERS']['l_Client_Name:'].$_sp.'<br>';
			$_hdr_link_8 .= $_hdr_link_prefix.'8&so=A'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_ASC_S'].'</a>';
			$_hdr_link_8 .= $_hdr_link_prefix.'8&so=D'.$_hdr_link_suffix.$_TCFG['_IMG_SORT_DSC_S'].'</a>';

		# Build form output
			IF ( $_CCFG['ORDERS_LIST_SHOW_PROD_DESC'] == 1 ) { $_temp_span = 7; } ELSE { $_temp_span = 8; }
			$_out .= '<div align="center">'.$_nl;
			$_out .= '<table width="95%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
			$_out .= '<tr class="BLK_DEF_TITLE"><td class="TP3MED_NC" colspan="'.$_temp_span.'">'.$_nl;

			$_out .= '<table width="100%" cellpadding="0" cellspacing="0">'.$_nl;
			$_out .= '<tr class="BLK_IT_TITLE_TXT">'.$_nl.'<td class="TP0MED_NL">'.$_nl;
			$_out .= '<b>'.$_LANG['_ORDERS']['l_Clients_Orders'].' ('.$_rec_next_lo.'-'.$_rec_next_hi.' '.$_LANG['_ORDERS']['of'].' '.$numrows_ttl.' '.$_LANG['_ORDERS']['total_entries'].')</b><br>'.$_nl;
			$_out .= '</td>'.$_nl.'<td class="TP0MED_NR">'.$_nl;
			IF ( $_CCFG['_IS_PRINT'] != 1 ) {
				IF ( $_SEC['_sadmin_flg'] ) {
					$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=orders', $_TCFG['_S_IMG_SEARCH_S'],$_TCFG['_S_IMG_SEARCH_S_MO'],'','');
				}
			} ELSE {
				$_out .= $_sp;
			}
			$_out .= '</td>'.$_nl.'</tr>'.$_nl.'</table>'.$_nl;

			$_out .= '</td></tr>'.$_nl;
			$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
			$_out .= '<td class="TP3SML_NC" valign="top"><b>'.$_hdr_link_1.'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC" valign="top"><b>'.$_hdr_link_2.'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC" valign="top"><b>'.$_hdr_link_3.'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC" valign="top"><b>'.$_hdr_link_8.'</b></td>'.$_nl;
			IF ($_CCFG['DOMAINS_ENABLE']) {$_out .= '<td class="TP3SML_NL" valign="top"><b>'.$_hdr_link_4.'</b></td>'.$_nl;}
			IF ( $_CCFG['ORDERS_LIST_SHOW_PROD_DESC'] == 1 ) {
				$_out .= '<td class="TP3SML_NL" valign="top"><b>'.$_hdr_link_7.'</b></td>'.$_nl;
			} ELSE {
				IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$_out .= '<td class="TP3SML_NL" valign="top"><b>'.$_hdr_link_5.'</b></td>'.$_nl;}
				$_out .= '<td class="TP3SML_NL" valign="top"><b>'.$_hdr_link_6.'</b></td>'.$_nl;
			}
			$_out .= '<td class="TP3SML_NC"><b>'.$_sp.'</b></td>'.$_nl;
			$_out .= '</tr>'.$_nl;

		# Process query results
			IF ( $numrows ) {
				while ($row = db_fetch_array($result)) {
					$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_out .= '<td class="TP3SML_NC">'.$row['ord_id'].'</td>'.$_nl;
					$_out .= '<td class="TP3SML_NC">'.$row['ord_status'].'</td>'.$_nl;
					$_out .= '<td class="TP3SML_NC">'.dt_make_datetime ( $row['ord_ts'], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] ).'</td>'.$_nl;
					$_out .= '<td class="TP3SML_NL">'.$row['ord_name_first'].' '.$row['ord_name_last'].'</td>'.$_nl;
					IF ($_CCFG['DOMAINS_ENABLE']) {$_out .= '<td class="TP3SML_NL">'.$row['ord_domain'].'</td>'.$_nl;}
					IF ( $_CCFG['ORDERS_LIST_SHOW_PROD_DESC'] == 1 ) {
						$_out .= '<td class="TP3SML_NL">'.$row['prod_desc'].'</td>'.$_nl;
					} ELSE {
						IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$_out .= '<td class="TP3SML_NL">'.$row['vendor_name'].'</td>'.$_nl;}
						$_out .= '<td class="TP3SML_NL">'.$row['prod_name'].'</td>'.$_nl;
					}
					$_out .= '<td class="TP3SML_NL"><nobr>'.$_nl;

					IF ( $_CCFG['_IS_PRINT'] != 1 ) {
						$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=mail&ord_id='.$row['ord_id'], $_TCFG['_S_IMG_EMAIL_S'],$_TCFG['_S_IMG_EMAIL_S_MO'],'','');
						$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=view&ord_id='.$row['ord_id'], $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
						$_out .= do_nav_link ('mod_print.php?mod=orders&mode=view&ord_id='.$row['ord_id'], $_TCFG['_S_IMG_PRINT_S'],$_TCFG['_S_IMG_PRINT_S_MO'],'_new','');

						IF ( $_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP08] == 1) ) {
							$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=edit&ord_id='.$row['ord_id'], $_TCFG['_S_IMG_EDIT_S'],$_TCFG['_S_IMG_EDIT_S_MO'],'','');
							$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=delete&stage=1&ord_id='.$row['ord_id'].'&ord_name_first='.$row['ord_name_first'].'&ord_name_last='.$row['ord_name_last'], $_TCFG['_S_IMG_DEL_S'],$_TCFG['_S_IMG_DEL_S_MO'],'','');
						}
					}

					$_out .= '</nobr></td>'.$_nl;
					$_out .= '</tr>'.$_nl;
				}
			}

			$_out .= '<tr class="BLK_DEF_ENTRY"><td class="TP3MED_NC" colspan="'.$_temp_span.'">'.$_nl;
			$_out .= $_page_menu.$_nl;
			$_out .= '</td></tr>'.$_nl;

			$_out .= '</table>'.$_nl;
			$_out .= '</div>'.$_nl;
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do email Client Order
function do_mail_order($adata, $aret_flag=0) {
		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Call common.php function for client mtp data (see function for array values) / merge with current.
			$_or_info 	= get_mtp_order_info( $adata['ord_id'] );

			IF ( $_or_info['numrows'] > 0 ) {
				$data_new	= array_merge( $_MTP, $_or_info );
				$_MTP		= $data_new;
			} ELSE {
				$_ret_msg_flag = 1;
				$_ret_msg = '<br>'.$_LANG['_ORDERS']['ORD_EMAIL_MSG_01_PRE'].' '.$adata['ord_id'].' '.$_LANG['_ORDERS']['ORD_EMAIL_MSG_01_SUF'];
			}

		# Call common.php function for client mtp data (see function for array values) / merge with current.
			$_cl_info 	= get_mtp_client_info( $_or_info['cl_id'] );

			IF ( $_cl_info['numrows'] > 0 ) {
				$data_new	= array_merge( $_MTP, $_cl_info );
				$_MTP		= $data_new;
			}

		# Get mail contact information array
			$_cinfo	= get_contact_info($_CCFG['MC_ID_BILLING']);

		# Set eMail Parameters (pre-eval template, some used in template)
			IF ($_CCFG['_PKG_SAFE_EMAIL_ADDRESS']) {
   				$mail['recip']		= $_MTP['cl_email'];
				$mail['from']		= $_cinfo['c_email'];
				$mail['cc']			= $_cinfo['c_email'];
			} ELSE {
				$mail['recip']		= $_MTP['cl_name_first'].' '.$_MTP['cl_name_last'].' <'.$_MTP['cl_email'].'>';
				$mail['from']		= $_CCFG['_PKG_NAME_SHORT'].'-'.$_cinfo['c_name'].' <'.$_cinfo['c_email'].'>';
				$mail['cc']			= $_CCFG['_PKG_NAME_SHORT'].'-'.$_cinfo['c_name'].' <'.$_cinfo['c_email'].'>';
			}
			$mail['subject']	= $_CCFG['_PKG_NAME_SHORT'].$_LANG['_ORDERS']['ORD_EMAIL_SUBJECT'];

		# Set MTP (Mail Template Parameters) array
			$_MTP['to_name']		= $_MTP['cl_name_first'].' '.$_MTP['cl_name_last'];
			$_MTP['to_email']		= $_MTP['cl_email'];
			$_MTP['from_name']		= $_cinfo['c_name'];
			$_MTP['from_email']		= $_cinfo['c_email'];
			$_MTP['subject']		= $mail['subject'];
			$_MTP['domain_name']	= $_cinfo['ord_domain'];
			$_MTP['site']			= $_CCFG['_PKG_NAME_SHORT'];
			$_MTP['ord_url']		= $_CCFG['_PKG_URL_BASE'].'mod.php?mod=orders&mode=view&ord_id='.$adata['ord_id'];

		# Check returned records, don't send if not 1
			$_ret = 1;
			IF ( $_or_info['numrows'] == 1 )
				{
					# Load mail template (processed)
						IF ( $adata['template'] == '' || $adata['template'] == 'email_order_copy' )
							{ $mail['message']	.= get_mail_template ('email_order_copy', $_MTP); }
						IF ( $adata['template'] == 'email_order_ack' )
							{ $mail['message']	.= get_mail_template ('email_order_ack', $_MTP); }

					# Call basic email function (ret=1 on error)
						$_ret = do_mail_basic ($mail);

					# Check return
						IF ( $_ret )
							{
								$_ret_msg = $_LANG['_ORDERS']['ORD_EMAIL_MSG_02_L1'];
								$_ret_msg .= '<br>';
								$_ret_msg .= $_LANG['_ORDERS']['ORD_EMAIL_MSG_02_L2'];
							}
						ELSE
							{
								$_ret_msg = $_LANG['_ORDERS']['ORD_EMAIL_MSG_03_PRE'].' '.$adata['ord_id'].' '.$_LANG['_ORDERS']['ORD_EMAIL_MSG_03_SUF'];
							}
				}


		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_ORDERS']['ORD_EMAIL_RESULT_TITLE'];

			$_cstr .= '<center>'.$_nl;
			$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= $_ret_msg.$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;
			$_cstr .= '</center>'.$_nl;

			$_mstr_flag	= 0;
			$_mstr		= $_sp.$_nl;

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}

# Do custom-order-request email (build, set email))
function do_cor_email ($adata)
	{
		# Dim Some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Get contact information array
			$_cinfo = get_contact_info($_CCFG['MC_ID_ORDERS']);

		# Get all Client Information
			$query	.= "SELECT *";
			$query	.= " FROM ".$_DBCFG['clients'];
			$query	.= " WHERE ".$_DBCFG['clients'].".cl_id = ".$adata['ord_cl_id'];

		# Do select and return check
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Set MTP array equal to data array
			$_MTP = $adata;

		# Get Order field enabled vars
			$_BVO = do_decode_DB16($_CCFG['ORDERS_FIELD_ENABLE_ORD']);

		# Check Return and process results
			IF ( $numrows )
				{
					# Process query results
					while ($row = db_fetch_array($result))
					{
						# Set data array
						$_MTP['cl_name_first']	= $row[cl_name_first];
						$_MTP['cl_name_last']	= $row[cl_name_last];
						$_MTP['cl_email']		= $row[cl_email];
						$_MTP['cl_user_name']	= $row[cl_user_name];

						$_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_01'].$row[cl_id].$_nl;
						$_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_02'].dt_make_datetime ( $row[cl_join_ts], $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).$_nl;
						$_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_03'].$row[cl_user_name].$_nl;
						$_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_04'].$row[cl_email].$_nl;
						$_MTP['cl_info'] .= '-------------------'.$_nl;
						IF ($_BVO['B16'] == 1) { $_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_05'].$row[cl_company].$_nl; }
						$_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_06'].$row[cl_name_first].' '.$row[cl_name_last].$_nl;
						IF ($_BVO['B15'] == 1) { $_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_07'].$row[cl_addr_01].$_nl; }
						IF ($_BVO['B14'] == 1) { $_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_08'].$row[cl_addr_02].$_nl; }
						IF ($_BVO['B13'] == 1) { $_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_09'].$row[cl_city].$_nl; }
						IF ($_BVO['B12'] == 1) { $_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_10'].$row[cl_state_prov].$_nl; }
						IF ($_BVO['B10'] == 1) { $_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_11'].$row[cl_country].$_nl; }
						IF ($_BVO['B11'] == 1) { $_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_12'].$row[cl_zip_code].$_nl; }
						IF ($_BVO['B09'] == 1) { $_MTP['cl_info'] .= $_LANG['_ORDERS']['COR_EMAIL_13'].$row[cl_phone]; }
					}
				}
		# End Get all Client Information

		# Get COR field enabled vars
			$_BVC = do_decode_DB16($_CCFG['ORDERS_FIELD_ENABLE_COR']);

		# Get COR Info and build MTP Variable
			# Set data array
				$_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_14'].$adata[cor_type].$_nl;
				IF ($_BVC['B16'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_15'].$adata[cor_opt_bill_cycle].$_nl; }
				IF ($_BVC['B15'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_16'].$adata[cor_opt_payment].$_nl; }
				IF ($_BVC['B14'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_17'].$adata[cor_disk].' '.$adata[cor_disk_units].$_nl; }
				IF ($_BVC['B13'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_18'].$adata[cor_traffic].' '.$adata[cor_traffic_units].$_nl; }
				IF ($_BVC['B12'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_19'].$adata[cor_dbs].$_nl; }
				IF ($_BVC['B11'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_20'].$adata[cor_mailboxes].$_nl; }
				IF ($_BVC['B10'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_21'].do_valtostr_no_yes($adata[cor_unique_ip]).$_nl; }
				IF ($_BVC['B09'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_22'].do_valtostr_no_yes($adata[cor_shop_cart]).$_nl; }
				IF ($_BVC['B08'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_23'].do_valtostr_no_yes($adata[cor_sec_cert]).$_nl; }
				IF ($_BVC['B07'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_24'].$adata[cor_site_pages].' pages'.$_nl; }
				IF ($_BVC['B01'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_26'].$adata[cor_optfld_01].$_nl; }
				IF ($_BVC['B02'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_27'].$adata[cor_optfld_02].$_nl; }
				IF ($_BVC['B03'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_28'].$adata[cor_optfld_03].$_nl; }
				IF ($_BVC['B04'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_29'].$adata[cor_optfld_04].$_nl; }
				IF ($_BVC['B05'] == 1) { $_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_30'].$adata[cor_optfld_05].$_nl; }
				IF ($_BVC['B06'] == 1)
					{
						$_MTP['cor_info'] .= $_LANG['_ORDERS']['COR_EMAIL_25'].$_nl;
					#	$_MTP['cor_info'] .= '--------------------'.$_nl;
						$_MTP['cor_info'] .= do_stripslashes($adata[cor_comments]).$_nl;
					#	$_MTP['cor_info'] .= '--------------------'.$_nl;
					}

		# Set eMail Parameters (pre-eval template, some used in template)
			IF ($_CCFG['_PKG_SAFE_EMAIL_ADDRESS']) {
   				$mail['recip']		= $_MTP['cl_email'];
				$mail['from']		= $_cinfo['c_email'];
			} ELSE {
				$mail['recip']		= $_MTP['cl_name_first'].' '.$_MTP['cl_name_last'].' <'.$_MTP['cl_email'].'>';
				$mail['from']		= $_CCFG['_PKG_NAME_SHORT'].'-'.$_cinfo['c_name'].' <'.$_cinfo['c_email'].'>';
			}
			IF ( $_CCFG['ORDER_EMAIL_CC_ENABLE'] ) {
			    IF ($_CCFG['_PKG_SAFE_EMAIL_ADDRESS']) {
   					$mail['cc']	= $_cinfo['c_email'];
				} ELSE {
					$mail['cc']	= $_CCFG['_PKG_NAME_SHORT'].'-'.$_cinfo['c_name'].' <'.$_cinfo['c_email'].'>';
				}
			} ELSE {
				$mail['cc']	= '';
			}
			$mail['subject']	= $_CCFG['_PKG_NAME_SHORT'].$_LANG['_ORDERS']['COR_EMAIL_SUBJECT'];

		# Set MTP (Mail Template Parameters) array
			$_MTP['to_name']		= $_MTP['cl_name_first'].' '.$_MTP['cl_name_last'];
			$_MTP['to_email']		= $_MTP['cl_email'];
			$_MTP['from_name']		= $_cinfo['c_name'];
			$_MTP['from_email']		= $_cinfo['c_email'];
			$_MTP['subject']		= $mail['subject'];
			$_MTP['site']			= $_CCFG['_PKG_NAME_SHORT'];
			$_MTP['cl_url']			= $_CCFG['_PKG_URL_BASE'].'mod.php?mod=clients&mode=view&cl_id='.$adata['cl_id'];

		# Load message template (processed)
			$mail['message']	.= get_mail_template ('email_custom_order_request', $_MTP);

		# Call basic email function (ret=0 on error)
			$_ret = do_mail_basic ($mail);

		return $_ret;
	}


# AUTO-CREATE INVOICE from an order
function do_auto_create_invoice($ord_info) {
	# Dim some Vars:
		global $_CCFG, $_ACFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Create and insert basic invoice
	# Process inputs for quotes
		$_GPV[invc_terms]		= do_addslashes($_ord_info[invc_terms]);
		$_GPV[invc_pay_link]	= do_addslashes(str_replace("'","\'",$ord_info[ord_pay_link]));
		$_GPV[prod_name]		= do_addslashes($_ord_info[prod_name]);
		$_GPV[prod_desc]		= do_addslashes($_ord_info[prod_desc]);

	# Build query
		$query = "INSERT INTO ".$_DBCFG['invoices']." (";
		$query .= "invc_id, invc_status, invc_cl_id, invc_deliv_method";
		$query .= ", invc_delivered, invc_total_cost, invc_total_paid, invc_subtotal_cost";
		$query .= ", invc_tax_01_percent, invc_tax_01_amount, invc_tax_02_percent, invc_tax_02_amount";
		$query .= ", invc_tax_autocalc, invc_ts, invc_ts_due, invc_ts_paid, invc_bill_cycle";
		$query .= ", invc_recurring, invc_recurr_proc, invc_pay_link, invc_terms";
		$query .= ")";

	# Get max / create new invc_id
		$_max_invc_id	= do_get_max_invc_id ( );

	# Determine terms auto entry
		IF ( $_CCFG['INVC_TERMS_INSERT_DEF'] && !$_GPV[invc_terms] ) {
			IF ( $_CCFG['INV_TERMS_DEF_LINE_01'] != '' ) { $_invc_terms = $_CCFG['INV_TERMS_DEF_LINE_01']; }
			IF ( $_CCFG['INV_TERMS_DEF_LINE_02'] != '' ) { $_invc_terms .= $_nl.$_CCFG['INV_TERMS_DEF_LINE_02']; }
			IF ( $_CCFG['INV_TERMS_DEF_LINE_03'] != '' ) { $_invc_terms .= $_nl.$_CCFG['INV_TERMS_DEF_LINE_03']; }
		} ELSE {
			$_invc_terms = $_GPV[invc_terms];
		}

	# Set some default invoice values
		$todayis	= dt_get_uts();
		$status     = $_ACFG['AUTO_INV_STATUS'];
		$delivery   = $_ACFG['AUTO_INV_DELIVERY'];
		$delivered  = $_ACFG['AUTO_INV_DELIVERED'];
		$cycle		= $_ACFG['AUTO_INVC_BILL_CYCLE'];
		$data = dt_get_uts();
		$dueday     = mktime(date("H",$data),date("i",$data),date("s",$data),date("m",$data),(date("d",$data)+$_CCFG[INVC_DUE_DAYS_OFFSET]),date("y",$data));
		$tax1rate	= $_CCFG['INVC_TAX_01_DEF_VAL'];
		$tax2rate   = $_CCFG['INVC_TAX_02_DEF_VAL'];

		$query .= " VALUES ( $_max_invc_id+1";
		$query .= ",'$status','$ord_info[os_ord_cl_id]','$delivery'";
		$query .= ",'$delivered','$ord_info[invc_total_cost]','0','$ord_info[invc_subtotal_cost]'";
		$query .= ",'$tax1rate','$ord_info[invc_tax_01_amount]','$tax2rate','$ord_info[invc_tax_02_amount]'";
		$query .= ",'1','$todayis','$dueday','','$cycle'";
		$query .= ",'0','0','$_GPV[invc_pay_link]','$_invc_terms'";
		$query .= ")";

	# Update the database with the new invoice
		$result 		= db_query_execute ($query) OR DIE("Unable to complete request");
		$_ins_invc_id	= $_max_invc_id+1;
		$_GPV[invc_id]	= $_ins_invc_id;


    # Insert Invoice Debit Transaction
		$_it_def = 0;
		$_it_desc	= $_LANG['_ORDERS']['l_Invoice_ID'].$sp.$_ins_invc_id;
		$q_it = ""; $r_it = ""; $n_it = 0;
		$q_it = "INSERT INTO ".$_DBCFG['invoices_trans']." (";
		$q_it .= "it_ts, it_invc_id, it_type";
		$q_it .= ", it_origin, it_desc, it_amount";
		$q_it .= ") VALUES ( ";
		$q_it .= "'$todayis','$_ins_invc_id','$_it_def'";
		$q_it .= ",'$_it_def','$_it_desc','$ord_info[invc_total_cost]'";
		$q_it .= ")";
		$r_it = db_query_execute($q_it);
		$n_it = db_query_numrows($r_it);

	#########################################################################################################
	# API Output Hook:
	# APIO_trans_new: Trasaction Created hook
		$_isfunc = 'APIO_trans_new';
		IF ( $_CCFG['APIO_MASTER_ENABLE'] == 1 && $_CCFG['APIO_TRANS_NEW_ENABLE'] == 1 ) {
			IF (function_exists($_isfunc))  {
				$_APIO = $_isfunc($_GPV); $_APIO_ret .= '<br>'.$_APIO['msg'].'<br>';
			} ELSE {
				$_APIO_ret .= '<br>'.'Error- no function'.'<br>';
			}
		}
	#########################################################################################################


	# Create Invoice line item(s)
	# Get max / create new ii_item_no
		$_max_invc_item_no	= do_get_max_invc_item_no ( $_GPV[invc_id] );

	# Build SQL and execute.
		$query	= "INSERT INTO ".$_DBCFG['invoices_items']." (";
		$query	.= "ii_invc_id, ii_item_no, ii_item_name";
		$query	.= ", ii_item_desc, ii_item_cost";
		$query	.= ", ii_apply_tax_01, ii_apply_tax_02, ii_calc_tax_02_pb";
		$query	.= ") VALUES (";
		$query	.= " '$_ins_invc_id',($_max_invc_item_no+1),'$ord_info[prod_name]'";
		$query	.= ",'$ord_info[prod_desc]','$ord_info[ord_unit_cost]'";
		$query	.= ",'$ord_info[prod_apply_tax_01]','$ord_info[prod_apply_tax_02]','$ord_info[prod_calc_tax_02_pb]'";
		$query	.= ")";

		$result	= db_query_execute ($query) OR DIE("Unable to complete request");

	# Calculate taxes and update
		$updated = do_set_invc_values($_ins_invc_id);

	# return results;
	return $_ins_invc_id;
}


# Insert a payment transaction on an order "buy" return from billing vendor.
function do_auto_create_payment($ord_id) {
	global $_CCFG, $_DBCFG;
	$tdata[it_ts] = dt_get_uts();
	$tdata[it_type] = 2;

	# Lookup order info
		$query = ""; $result = ""; $numrows = 0;
		$query = "SELECT * FROM ".$_DBCFG[orders]." WHERE ord_id=".$ord_id;
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);
		while ($row = db_fetch_array($result)) {
			$tdata[it_invc_id] = $row[ord_invc_id];
			$tdata[it_origin] = $row[ord_vendor_id];
			$tdata[it_amount] = $row[ord_unit_cost];
		}

	# Lookup Vendor info
		$query = ""; $result = ""; $numrows = 0;
		$query = "SELECT * FROM ".$_DBCFG[vendors]." WHERE vendor_id=".$tdata[it_origin];
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);
		while ($row = db_fetch_array($result)) {$tdata[it_desc] = $row[vendor_name];}

	# Create invoice "payment" transaction
		$query = ""; $result = ""; $numrows = 0;
		$query  = "INSERT INTO ".$_DBCFG['invoices_trans']." (";
		$query .= "it_ts, it_invc_id, it_type";
		$query .= ", it_origin, it_desc, it_amount";
		$query .= ") VALUES ( ";
		$query .= "'$tdata[it_ts]','$tdata[it_invc_id]','$tdata[it_type]'";
		$query .= ",'$tdata[it_origin]','$tdata[it_desc]','$tdata[it_amount]'";
		$query .= ")";
		$result = db_query_execute($query);
		$numrows = db_query_numrows($result);

	# Do status calc
		$ptd = do_get_invc_PTD($tdata[it_invc_id]);

	# Get invoice amount
		$idata	= do_get_invc_values($tdata[it_invc_id]);

	# Check against PTD
		IF ($idata[invc_total_cost] <= $ptd) {
			$_us = 1;
			$tdata[invc_status] = $_CCFG['INV_STATUS'][3];
		}

	# Do update invoice record
		$query = ""; $result = ""; $numrows = 0;
		$query		= "UPDATE ".$_DBCFG['invoices']." SET ";
		$query		.= " invc_ts_paid = '$tdata[it_ts]'";
		$query		.= ", invc_total_paid = '$ptd'";
		IF ($_us == 1) {$query .= ", invc_status='$tdata[invc_status]'";}
		$query		.= " WHERE invc_id = $tdata[it_invc_id]";
		$result		= db_query_execute($query) OR DIE("Unable to complete request");
		$numrows	= db_query_affected_rows();

		return 1;
}

/**************************************************************
 * End Module Functions
**************************************************************/

?>
