<?php

/**************************************************************
 * File: 		Command Center Module Functions File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_cc.php
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("cc_funcs.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01&url=mod.php?mod=cc');
			exit;
		}

/**************************************************************
 * Module Functions
**************************************************************/

# Do search list select field for: Vendors
function do_search_select_list_vendors($aname, $avalue, $aret_flag=0)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Set Query for select.
			$query	= "SELECT vendor_id, vendor_name FROM ".$_DBCFG['vendors']." ORDER BY vendor_id ASC";

		# Do select and return check
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build Form row
			$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="0">'.$_sp.'</option>'.$_nl;

			# Process query results
				while(list($vendor_id, $vendor_name) = db_fetch_row($result))
				{
					$_out .= '<option value="'.$vendor_id.'"';
					IF ( $vendor_id == $avalue ) { $_out .= ' selected'; }
					$_out .= '>'.$vendor_id.' - '.$vendor_name.'</option>'.$_nl;
				}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do list select field for: Products
function do_search_select_list_prods($aname, $avalue, $aret_flag=0)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Set Query for select.
			$query	= "SELECT prod_id, prod_name, prod_desc FROM ".$_DBCFG['products']." ORDER BY prod_id ASC";

		# Do select and return check
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build Form row
			$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="0">'.$_sp.'</option>'.$_nl;

			# Process query results
				while(list($prod_id, $prod_name, $prod_desc) = db_fetch_row($result))
				{
					$_out .= '<option value="'.$prod_id.'"';
					IF ( $prod_id == $avalue ) { $_out .= ' selected'; }
					$_out .= '>'.$prod_id.' - '.$prod_name.' - '.$prod_desc.'</option>'.$_nl;
				}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do list select field for: Trans Type
function do_search_select_list_trans_type($aname, $avalue)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="'.''.'"';
			IF ( $avalue == '' ) { $_out .= ' selected'; }
			$_out .= '>'.$_LANG['_CC']['Please_Select'].'</option>'.$_nl;

		# Loop array and load list
			FOR ($i = 0; $i < count($_CCFG[INV_TRANS_TYPE]); $i++)
			{
				$_out .= '<option value="'.$i.'"';
				IF ( "'".$i."'" == "'".$avalue."'" ) { $_out .= ' selected'; }
				$_out .= '>'.$_CCFG['INV_TRANS_TYPE'][$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;
			return $_out;
	}


# Do select field for: Trans Origin
function do_search_select_list_trans_origin($aname, $avalue)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="'.''.'"';
			IF ( $avalue == '' ) { $_out .= ' selected'; }
			$_out .= '>'.$_LANG['_CC']['Please_Select'].'</option>'.$_nl;

		# Loop array and load list
			FOR ($i = 0; $i < count($_CCFG[INV_TRANS_ORIGIN]); $i++)
			{
				$_out .= '<option value="'.$i.'"';
				IF ( "'".$i."'" == "'".$avalue."'" ) { $_out .= ' selected'; }
				$_out .= '>'.$_CCFG['INV_TRANS_ORIGIN'][$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

			return $_out;
	}


# Do summary: Clients
function do_summary_clients( $adata, $aret_flag=0 )
	{
		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Set Query for select.
			$query	= "SELECT cl_status, count(cl_id) as cl_count";

			$query .= " FROM ".$_DBCFG['clients'];
			$query .= " GROUP BY cl_status";
			$query .= " ORDER BY cl_status ASC, cl_id ASC";

		# Do select and return check
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build form output
			$_out .= '<div align="left">'.$_nl;
			$_out .= '<table border="0" cellpadding="0" cellspacing="1">'.$_nl;
			$_out .= '<tr><td class="TP1SML_BL" colspan="4">'.$_nl;
			$_out .= '<b><a href="mod.php?mod=clients">'.$_LANG['_CC']['Clients'].'</a>'.$_sp.$_LANG['_CC']['Summary'].':</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;

			# Process query results
				$cl_count_ttl	= 0;
				while(list($cl_status, $cl_count) = db_fetch_row($result))
				{
					IF ($cl_count ==1 )	{ $_str_02 = $_LANG['_CC']['lc_client']; }
					ELSE 				{ $_str_02 = $_LANG['_CC']['lc_clients']; }

					$_out .= '<tr>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.$_sp.$_sp.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.'<b>('.$cl_count.')</b>'.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.'<a href="mod.php?mod=clients&fb=1&fs='.$cl_status.'"><b>'.$cl_status.'</b></a>'.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.$_str_02.'.</td>'.$_nl;
					$_out .= '</tr>'.$_nl;

					$cl_count_ttl = $cl_count_ttl + $cl_count;
				}

			$_out .= '<tr><td class="TP1SML_BL" colspan="4">'.$_nl;
			$_out .= '<b>'.$_LANG['_CC']['Total_of'].$_sp.$cl_count_ttl.$_sp.$_LANG['_CC']['lc_client_s'].'</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;

			$_out .= '</table>'.$_nl;
			$_out .= '</div>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do summary: Domains  Expired / Expiring
function do_summary_domains_exp( $adata, $aret_flag=0 )
	{
		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Call timestamp function
			$_uts_now		= dt_get_uts();
			$_uts_setpoint	= $_uts_now+(86400*$_CCFG[CC_DOMAIN_EXP_IN_DAYS]);

		# Set Query for select.
			$query		= "SELECT * FROM ".$_DBCFG['domains'];
			$query		.= " WHERE dom_ts_expiration <= ".$_uts_setpoint;
			IF	( $_CCFG['CC_DOMAIN_EXP_LIST_INCL_EXPRD'] )
				{
					$query .= " AND dom_ts_expiration > 0";
					$_str_line_02 = $_LANG['_CC']['Expired'].$_sp.$_LANG['_CC']['or'].$_sp.$_LANG['_CC']['Expiring_In'];
				}
			ELSE
				{
					$query .= " AND dom_ts_expiration > ".$_uts_now;
					$_str_line_02 = $_LANG['_CC']['Expiring_In'];
				}

			# Set to logged in Client ID if not admin to avoid seeing other client domains
			IF ( !$_SEC['_sadmin_flg'] ) { $query .= " AND ".$_DBCFG['domains'].".dom_cl_id = ".$_SEC['_suser_id']; }

			$query		.= " ORDER BY dom_ts_expiration ASC";

		# Do select and return check
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build form output
			$_out .= '<div align="left">'.$_nl;
			$_out .= '<table border="0" cellpadding="0" cellspacing="1">'.$_nl;
			$_out .= '<tr><td class="TP1SML_BL" colspan="4">'.$_nl;
			$_out .= '<b>'.$_LANG['_CC']['Domains'].$_sp.'</b>'.$_nl;
			$_out .= '<br><b>'.$_str_line_02.$_sp.'('.$_CCFG[CC_DOMAIN_EXP_IN_DAYS].')'.$_sp.$_LANG['_CC']['days'].':</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;

		# Check Return and process results
			IF ( $numrows )
				{
					# Process query results
						while ($row = db_fetch_array($result))
						{
							$_out .= '<tr>'.$_nl;
							$_out .= '<td class="TP1SML_NL">'.$_sp.$_sp.'</td>'.$_nl;
							IF ( $_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP06] == 1) )
								{ $_pmode = 'edit'; } ELSE { $_pmode = 'view'; }
							$_out .= '<td class="TP1SML_NL">'.'<a href="mod.php?mod=domains&mode='.$_pmode.'&dom_id='.$row[dom_id].'">'.$row[dom_domain].'</a>'.'</td>'.$_nl;
							$_out .= '<td class="TP1SML_NL">'.dt_make_datetime ( $row[dom_ts_expiration], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] ).'</td>'.$_nl;
							$_days = (($row[dom_ts_expiration] - $_uts_now) / 86400);
							$_out .= '<td class="TP1SML_NL">'.'('.number_format($_days, 2, '.', '').$_sp.$_LANG['_CC']['x_days'].')'.'</td>'.$_nl;
							$_out .= '</tr>'.$_nl;
						}
				}

			$_out .= '</table>'.$_nl;
			$_out .= '</div>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do summary: Domain Server Accounts (SACCS) Expired / Expiring
function do_summary_saccs_exp( $adata, $aret_flag=0 )
	{
		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Call timestamp function
			$_uts_now		= dt_get_uts();
			$_uts_setpoint	= $_uts_now+(86400*$_CCFG[CC_SACC_EXP_IN_DAYS]);

		# Set Query for select.
			$query		= "SELECT * FROM ".$_DBCFG['domains'];
			$query		.= " WHERE dom_sa_expiration <= ".$_uts_setpoint;
			IF	( $_CCFG['CC_SACC_EXP_LIST_INCL_EXPRD'] )
				{
					$query .= " AND dom_sa_expiration > 0";
					$_str_line_02 = $_LANG['_CC']['Expired'].$_sp.$_LANG['_CC']['or'].$_sp.$_LANG['_CC']['Expiring_In'];
				}
			ELSE
				{
					$query .= " AND dom_sa_expiration > ".$_uts_now;
					$_str_line_02 = $_LANG['_CC']['Expiring_In'];
				}

			# Set to logged in Client ID if not admin to avoid seeing other client domains
			IF ( !$_SEC['_sadmin_flg'] ) { $query .= " AND ".$_DBCFG['domains'].".dom_cl_id = ".$_SEC['_suser_id']; }

			$query		.= " ORDER BY dom_sa_expiration ASC";

		# Do select and return check
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build form output
			$_out .= '<div align="left">'.$_nl;
			$_out .= '<table border="0" cellpadding="0" cellspacing="1">'.$_nl;
			$_out .= '<tr><td class="TP1SML_BL" colspan="4">'.$_nl;
			$_out .= '<b>'.$_LANG['_CC']['Server_Accounts'].$_sp.'</b>'.$_nl;
			$_out .= '<br><b>'.$_str_line_02.$_sp.'('.$_CCFG[CC_SACC_EXP_IN_DAYS].')'.$_sp.$_LANG['_CC']['days'].':</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;

		# Check Return and process results
			IF ( $numrows )
				{
					# Process query results
						while ($row = db_fetch_array($result))
						{
							$_out .= '<tr>'.$_nl;
							$_out .= '<td class="TP1SML_NL">'.$_sp.$_sp.'</td>'.$_nl;
							IF ( $_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP06] == 1) )
								{ $_pmode = 'edit'; } ELSE { $_pmode = 'view'; }
							$_out .= '<td class="TP1SML_NL">'.'<a href="mod.php?mod=domains&mode='.$_pmode.'&dom_id='.$row[dom_id].'">'.$row[dom_domain].'</a>'.'</td>'.$_nl;
							$_out .= '<td class="TP1SML_NL">'.dt_make_datetime ( $row[dom_sa_expiration], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] ).'</td>'.$_nl;
							$_days = (($row[dom_sa_expiration] - $_uts_now) / 86400);
							$_out .= '<td class="TP1SML_NL">'.'('.number_format($_days, 2, '.', '').$_sp.$_LANG['_CC']['x_days'].')'.'</td>'.$_nl;
							$_out .= '</tr>'.$_nl;
						}
				}

			$_out .= '</table>'.$_nl;
			$_out .= '</div>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do summary: Orders
function do_summary_orders( $adata, $aret_flag=0 )
	{
		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Set Query for select.
			$query	= "SELECT ord_status, count(ord_id) as ord_count, sum(ord_unit_cost) as ord_sum_cost";
			$query .= " FROM ".$_DBCFG['orders'];

			# Set to logged in Client ID if not admin to avoid seeing other client order id's
			IF ( !$_SEC['_sadmin_flg'] ) { $query .= " WHERE ".$_DBCFG['orders'].".ord_cl_id = ".$_SEC['_suser_id']; }

			$query .= " GROUP BY ord_status";
			$query .= " ORDER BY ord_status ASC, ord_id ASC";

		# Do select and return check
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build form output
			$_out .= '<div align="left">'.$_nl;
			$_out .= '<table border="0" cellpadding="0" cellspacing="1">'.$_nl;
			$_out .= '<tr><td class="TP1SML_BL" colspan="6">'.$_nl;
			$_out .= '<b><a href="mod.php?mod=orders&mode=view">'.$_LANG['_CC']['Orders'].'</a>'.$_sp.$_LANG['_CC']['Summary'].':</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;

			# Process query results
				$ord_count_ttl	= 0;
				$ord_cost_ttl	= 0;
				while(list($ord_status, $ord_count, $ord_sum_cost) = db_fetch_row($result)) {
					IF ($ord_count ==1 )	{ $_str_02 = $_LANG['_CC']['lc_order']; }
					ELSE 					{ $_str_02 = $_LANG['_CC']['lc_orders']; }

					$_out .= '<tr>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.$_sp.$_sp.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.'<b>('.$ord_count.')</b>'.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.'<a href="mod.php?mod=orders&mode=view&fb=1&fs='.$ord_status.'"><b>'.$ord_status.'</b></a>'.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.$_str_02.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.$_LANG['_CC']['totalling'].$_sp.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NR">'.$_CCFG['_CURRENCY_PREFIX'].$_sp.do_currency_format ( $ord_sum_cost ).'</td>'.$_nl;
					$_out .= '</tr>'.$_nl;

					$ord_count_ttl = $ord_count_ttl + $ord_count;
					$ord_cost_ttl = $ord_cost_ttl + $ord_sum_cost;
				}

			$_out .= '<tr><td class="TP1SML_BL" colspan="5">'.$_nl;
			$_out .= '<b>'.$_LANG['_CC']['Total_of'].$_sp.$ord_count_ttl.'</b>'.$_sp.$_LANG['_CC']['lc_order_s'].$_sp.$_LANG['_CC']['totalling'].':'.$_sp.$_nl;
			$_out .= '</td><td class="TP1SML_BR" colspan="1">'.$_nl;
			$_out .= $_sp.$_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format ( $ord_cost_ttl ).$_nl;
			$_out .= '</td></tr>'.$_nl;

			$_out .= '</table>'.$_nl;
			$_out .= '</div>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do summary: Products Ordered
function do_summary_product_orders( $adata, $aret_flag=0 ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT ord_status, count(ord_prod_id) as prod_count, sum(ord_unit_cost) as ord_sum_cost, ";
		IF ($_CCFG['ORDERS_LIST_SHOW_PROD_DESC']) {
			$q2 .= "prod_desc";
		} ELSE {
			$q2 .= "prod_name";
		}
		$query .= $q2;
		$query .= " FROM ".$_DBCFG['orders'].", ".$_DBCFG['products'];
		$query .= " WHERE ".$_DBCFG['orders'].".ord_prod_id=".$_DBCFG['products'].".prod_id";
		$query .= " AND ".$_DBCFG['orders'].".ord_status='".$_CCFG['ORD_STATUS'][0]."'";

	# Set to logged in Client ID if not admin to avoid seeing other client order id's
		IF ( !$_SEC['_sadmin_flg'] ) { $query .= " AND ".$_DBCFG['orders'].".ord_cl_id = ".$_SEC['_suser_id']; }

		$query .= " GROUP BY ".$_DBCFG['products'].".".$q2;
		$query .= " ORDER BY ".$_DBCFG['products'].".".$q2." ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form output
		$_out .= '<div align="left">'.$_nl;
		$_out .= '<table border="0" cellpadding="0" cellspacing="1">'.$_nl;
		$_out .= '<tr><td class="TP1SML_BL" colspan="6">'.$_nl;
		$_out .= '<b>';
		$_out .= $_LANG['_CC']['Active_Products'];
		$_out .= $_sp.$_LANG['_CC']['Summary'].':</b>'.$_nl;
		$_out .= '</td></tr>'.$_nl;

	# Process query results
		$ord_count_ttl	= 0;
		$ord_cost_ttl	= 0;
		while(list($ord_status, $prod_count, $ord_sum_cost, $prod_name) = db_fetch_row($result)) {
			IF ($prod_count ==1 ) {
				$_str_02 = $_LANG['_CC']['lc_order'];
			} ELSE {
				$_str_02 = $_LANG['_CC']['lc_orders'];
			}
			$_out .= '<tr>'.$_nl;
			$_out .= '<td class="TP1SML_NL">'.$_sp.$_sp.'</td>'.$_nl;
			$_out .= '<td class="TP1SML_NL">'.'<b>('.$prod_count.')</b>'.'</td>'.$_nl;
			$_out .= '<td class="TP1SML_NL">'.'<b>'.$prod_name.'</b>'.'</td>'.$_nl;
			$_out .= '<td class="TP1SML_NL">'.$_str_02.'</td>'.$_nl;
			$_out .= '<td class="TP1SML_NL">'.$_LANG['_CC']['totalling'].$_sp.'</td>'.$_nl;
			$_out .= '<td class="TP1SML_NR">'.$_CCFG['_CURRENCY_PREFIX'].$_sp.do_currency_format ( $ord_sum_cost ).'</td>'.$_nl;
			$_out .= '</tr>'.$_nl;
			$ord_cost_ttl = $ord_cost_ttl + $ord_sum_cost;
		}
		$_out .= '<tr><td class="TP1SML_BL" colspan="5">'.$_nl;
		$_out .= '<b>'.$_LANG['_CC']['Total_of'].$_sp.$numrows.'</b>'.$_sp.$_LANG['_CC']['lc_products'].$_sp.$_LANG['_CC']['totalling'].':'.$_sp.$_nl;
		$_out .= '</td><td class="TP1SML_BR" colspan="1">'.$_nl;
		$_out .= $_sp.$_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format ( $ord_cost_ttl ).$_nl;
		$_out .= '</td></tr>'.$_nl;
		$_out .= '</table>'.$_nl;
		$_out .= '</div>'.$_nl;
		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do summary: Invoices (Single Column)
function do_summary_invoices( $adata, $aret_flag=0 ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT invc_status, count(invc_id) as invc_count, sum(invc_total_cost) as invc_sum_cost";
		$query .= " FROM ".$_DBCFG['invoices'];

	# Set to logged in Client ID if not admin to avoid seeing other client invoice id's
		IF ( !$_SEC['_sadmin_flg'] ) {
			$query .= " WHERE ".$_DBCFG['invoices'].".invc_cl_id = ".$_SEC['_suser_id'];
			$query .= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG[INV_STATUS][1]."'";
		# Check show pending enable flag
			IF ( !$_CCFG['INVC_SHOW_CLIENT_PENDING'] ) {
				$query .= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG['INV_STATUS'][4]."'";
			}
		}

		$query .= " GROUP BY invc_status";
		$query .= " ORDER BY invc_status ASC, invc_id ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form output
		$_out .= '<div align="left">'.$_nl;
		$_out .= '<table border="0" cellpadding="0" cellspacing="1">'.$_nl;
		$_out .= '<tr><td class="TP1SML_BL" colspan="6">'.$_nl;
		$_out .= '<b><a href="mod.php?mod=invoices">'.$_LANG['_CC']['Invoices'].'</a>'.$_sp.$_LANG['_CC']['Summary'].':</b>'.$_nl;
		$_out .= '</td></tr>'.$_nl;

		# Process query results
			$invc_count_ttl	= 0;
			$invc_cost_ttl	= 0;
			while(list($invc_status, $invc_count, $invc_sum_cost) = db_fetch_row($result)) {
				IF ($invc_count ==1 )	{ $_str_02 = $_LANG['_CC']['lc_invoice']; }
				ELSE 					{ $_str_02 = $_LANG['_CC']['lc_invoices']; }

				$_out .= '<tr>'.$_nl;
				$_out .= '<td class="TP1SML_NL">'.$_sp.$_sp.'</td>'.$_nl;
				$_out .= '<td class="TP1SML_NL">'.'<b>('.$invc_count.')</b>'.'</td>'.$_nl;
				$_out .= '<td class="TP1SML_NL">'.'<a href="mod.php?mod=invoices&fb=1&fs='.$invc_status.'"><b>'.$invc_status.'</b></a>'.'</td>'.$_nl;
				$_out .= '<td class="TP1SML_NL">'.$_str_02.'</td>'.$_nl;
				$_out .= '<td class="TP1SML_NL">'.$_LANG['_CC']['totalling'].$_sp.'</td>'.$_nl;
				$_out .= '<td class="TP1SML_NR">'.$_CCFG['_CURRENCY_PREFIX'].$_sp.do_currency_format ( $invc_sum_cost ).'</td>'.$_nl;
				$_out .= '</tr>'.$_nl;

				$invc_count_ttl = $invc_count_ttl + $invc_count;
				$invc_cost_ttl = $invc_cost_ttl + $invc_sum_cost;
			}

		$_out .= '<tr><td class="TP1SML_BR" colspan="5">'.$_nl;
		$_out .= '<b>'.$_LANG['_CC']['Total_of'].$_sp.$invc_count_ttl.'</b>'.$_sp.$_LANG['_CC']['lc_invoice_s'].$_sp.$_LANG['_CC']['totalling'].':'.$_sp.$_nl;
		$_out .= '</td><td class="TP1SML_BR" colspan="1">'.$_nl;
		$_out .= $_sp.$_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format ( $invc_cost_ttl ).$_nl;
		$_out .= '</td></tr>'.$_nl;

	# Show the amount of money actually receivable, as opposed to the different invoice status
		$idata = do_get_invc_cl_balance($_SEC['_suser_id'],0);
		$_out .= '<tr><td class="TP1SML_BR" colspan="5">'.$_nl;
		$_out .= '<b>' . $_LANG['_CC']['Balance_Due'] . ':</b>'.$_sp.$_nl;
		$_out .= '</td><td class="TP1SML_NR" colspan="2">'.$_nl;
		$_out .= $_sp.$_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format ($idata['net_balance']).$_nl;
		$_out .= '</td><td class="TP1SML_BR" colspan="3">'.$_sp.$_nl;
		$_out .= '</td></tr>'.$_nl;

	# Close the table
		$_out .= '</table>'.$_nl;
		$_out .= '</div>'.$_nl;

	IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do summary: Invoices (Column By Billing Type)
function do_summary_invoices_columnar( $adata, $aret_flag=0 ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;
		$NumColumns = sizeof($_CCFG['INVC_BILL_CYCLE']);

	# Build generic table row that will be replaced with appropriate data later
		$displayblock='';
		FOR ($x = 0; $x < $NumColumns; $x++) {
	        $displayblock .= '<td class="TP1SML_NR">BLOCK'.$_CCFG['INVC_BILL_CYCLE'][$x].'</td>'.$_nl;
			$TotalThisCycle[$_CCFG[INVC_BILL_CYCLE][$x]] = 0;
		}
		$displayblock .= '<td class="TP1SML_NR">BLOCKTTL</td>'.$_nl;
		$displayblock .= '<td class="TP1SML_NR">BLOCKDUE</td>'.$_nl;
		$NewBlock = $displayblock;

	# Set Query for select.
		$query	= "SELECT invc_id, invc_status, count(invc_id) as invc_count, invc_bill_cycle, sum(invc_total_cost) as invc_sum_cost";
		$query .= " FROM ".$_DBCFG['invoices'];

	# Set to logged in Client ID if not admin to avoid seeing other client invoice id's
		IF ( !$_SEC['_sadmin_flg'] ) {
			$query .= " WHERE ".$_DBCFG['invoices'].".invc_cl_id = ".$_SEC['_suser_id'];
			$query .= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG[INV_STATUS][1]."'";
		# Check show pending enable flag
			IF ( !$_CCFG['INVC_SHOW_CLIENT_PENDING'] ) {
				$query .= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG['INV_STATUS'][4]."'";
			}
		}

		$query .= " GROUP BY invc_bill_cycle, invc_status";
		$query .= " ORDER BY invc_status ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form output
		$_out .= '<div align="left">'.$_nl;
		$_out .= '<table border="1" cellpadding="0" cellspacing="1">'.$_nl;
		$_out .= '<tr><td class="TP1SML_BL">'.$_nl;
		$_out .= '<b><a href="mod.php?mod=invoices">'.$_LANG['_CC']['Invoices'].'</a>'.$_sp.$_LANG['_CC']['Summary'].':</b>'.$_nl;
		$_out .= '</td>'.$_nl;

	# Do column header for each "bill cycle"
		FOR ($x=0; $x < $NumColumns; $x++) {
			$_out .= '<td class="TP1SML_BR"><b>'.$_CCFG['INVC_BILL_CYCLE'][$x].'</b></td>'.$_nl;
		}
		$_out .= '<td class="TP1SML_BR"><b>'.$_LANG['_CC']['Total'].'</b></td>'.$_nl;
		$_out .= '<td class="TP1SML_BR"><b>'.$_LANG['_CC']['Balance_Due'].'</b></td>'.$_nl;

	# Initialize counters
		$invc_count_ttl	= 0;
		$invc_cost_ttl	= 0;
		$invc_status_count_ttl = 0;
		$invc_status_cost_ttl = 0;
		$LastRow = 1;
		$FirstRow = 0;

	# Only create rows table if there is any data
		IF ($numrows) {

		# Process query results
			while(list($invc_id, $invc_status, $invc_count, $invc_bill_cycle, $invc_sum_cost) = db_fetch_row($result)) {

				IF ($invc_count ==1 ) {
					$_str_02 = $_LANG['_CC']['lc_invoice'];
				} ELSE {
					$_str_02 = $_LANG['_CC']['lc_invoices'];
				}

				IF (!$FirstRow) {
					$LastRow = $invc_status;
					$FirstRow++;
				}

			# Output new row if different status from last record
				IF ($LastRow != $invc_status) {
					$_out .= '</tr>'.$_nl;
					$_out .= '<tr>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.$_sp.$_sp.'<b>('.$invc_status_count_ttl.')</b>'.$_nl;
					$_out .= '<a href="mod.php?mod=invoices&fb=1&fs='.$LastRow.'"><b>'.$LastRow.'</b></a>'.$_nl;
					$_out .= $_str_02.$_nl;
					$_out .= $_LANG['_CC']['totalling'].$_sp.'</td>'.$_nl;

				# Replace any non-numeric cells with a space
					FOR ($x=0; $x < $NumColumns; $x++) {
	    	            $ReplaceThis = "BLOCK".$_CCFG['INVC_BILL_CYCLE'][$x];
						$NewBlock = str_replace($ReplaceThis, $_sp, $NewBlock);
					}

				# Build the total for this status
					$TheAmount = $_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format($invc_status_cost_ttl);
					$NewBlock = str_replace("BLOCKTTL", $TheAmount, $NewBlock);
					$AmountDue = get_invoice_balance_by_status($LastRow,$_SEC['_suser_id']);
					$TheAmount = $_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format($AmountDue);
					$NewBlock = str_replace("BLOCKDUE", $TheAmount, $NewBlock);

				# Display our built-up row and grab a  new template row
					$_out .= $NewBlock;
					$NewBlock = $displayblock;

				# Reset status counters
					$invc_status_count_ttl = 0;
					$invc_status_cost_ttl = 0;
					$LastRow = $invc_status;
		        }

			# Add the invoices for the billing cycle to this "status"
				FOR ($x=0; $x < $NumColumns; $x++) {
					IF ($invc_bill_cycle == $x) {
        		        $ReplaceThis = "BLOCK".$_CCFG['INVC_BILL_CYCLE'][$x];
						$TheAmount = $_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format($invc_sum_cost);
						$NewBlock = str_replace($ReplaceThis, $TheAmount, $NewBlock);
						break;
					}
				}

			# Do our running totals
				$invc_count_ttl = $invc_count_ttl + $invc_count;
				$invc_cost_ttl = $invc_cost_ttl + $invc_sum_cost;
				$invc_status_count_ttl += $invc_count;
				$invc_status_cost_ttl += $invc_sum_cost;
				$TotalThisCycle[$x] += $invc_sum_cost;
			}

		# Display the last row
			$_out .= '</tr>'.$_nl;
			$_out .= '<tr>'.$_nl;
			$_out .= '<td class="TP1SML_NL">'.$_sp.$_sp.'<b>('.$invc_status_count_ttl.')</b>'.$_nl;
			$_out .= '<a href="mod.php?mod=invoices&fb=1&fs='.$LastRow.'"><b>'.$LastRow.'</b></a>'.$_nl;
			$_out .= $_str_02.$_nl;
			$_out .= $_LANG['_CC']['totalling'].$_sp.'</td>'.$_nl;

		# Replace any non-numeric cells with a space
			FOR ($x=0; $x < $NumColumns; $x++) {
        	    $ReplaceThis = "BLOCK".$_CCFG['INVC_BILL_CYCLE'][$x];
				$NewBlock = str_replace($ReplaceThis, $_sp, $NewBlock);
			}

		# Build the total for this status
			$TheAmount = $_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format($invc_status_cost_ttl);
			$NewBlock = str_replace("BLOCKTTL", $TheAmount, $NewBlock);
			$AmountDue = get_invoice_balance_by_status($LastRow,$_SEC['_suser_id']);
			$TheAmount = $_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format($AmountDue);
			$NewBlock = str_replace("BLOCKDUE", $TheAmount, $NewBlock);

		# Display our built-up row and grab a new template row
			$_out .= $NewBlock;

		}   # End of displaying data section

	# Display "Total" of all invoices
		$idata = do_get_invc_cl_balance($_SEC['_suser_id'],0);
		$_out .= '</tr><tr>';
		$_out .= '<td class="TP1SML_BR">'.$_nl;
		$_out .= '<b>'.$_LANG['_CC']['Total_of'].$_sp.$invc_count_ttl.'</b>'.$_nl;
		$_out .= $_sp.$_LANG['_CC']['lc_invoice_s'].$_sp.$_LANG['_CC']['totalling'].':</td>'.$_nl;
		FOR ($x=0; $x < $NumColumns; $x++) {
			IF ($TotalThisCycle[$x] > 0.01) {
				$_out .= '<td class="TP1SML_BR">'.$_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format($TotalThisCycle[$x]).'</td>'.$_nl;
			} ELSE {
				$_out .= '<td class="TP1SML_BR">'.$_sp.'</td>'.$_nl;
			}
		}
		$_out .= '<td class="TP1SML_BR">'.$_nl;
		$_out .= $_sp.$_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format( $invc_cost_ttl ).$_nl;
		$_out .= '</td>';
		$_out .= '<td class="TP1SML_BR">'.$_nl;
		$_out .= $_sp.$_CCFG['_CURRENCY_PREFIX'].' '.do_currency_format( $idata['net_balance'] ).$_nl;
		$_out .= '</td>';
		$_out .= '</tr>'.$_nl;

	# Close the table
		$_out .= '</table>'.$_nl;
		$_out .= '</div>'.$_nl;

	# Return the results
		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


function get_invoice_balance_by_status($status,$cl_id) {
	# Dim some vars
		global $_CCFG, $_DBCFG;
		$query = ""; $result= ""; $numrows = 0; $due = 0;

	# Ignore "Pending", "draft", and "void"
		IF ($status == $_CCFG['INV_STATUS'][4]) {return 0;}
		IF ($status == $_CCFG['INV_STATUS'][1]) {return 0;}
		IF ($status == $_CCFG['INV_STATUS'][5]) {return 0;}

	# Set Query for select.
		$query	= "SELECT sum(invc_total_cost), sum(invc_total_paid)";
		$query .= " FROM ".$_DBCFG['invoices'];
		$query .= " WHERE invc_status='".$status."'";
		IF ($cl_id) {$query .= " AND invc_cl_id=".$cl_id;}

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	IF ($numrows) {

	# Process query results
		while(list($invc_total_cost, $invc_total_paid) = db_fetch_row($result)) {
			$due = $invc_total_cost - $invc_total_paid;
		}
	}

	# return result;
		return $due;
}

# Do summary: HelpDesk Support Tickets
function do_summary_support_tickets( $adata, $aret_flag=0 )
	{
		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query_c = ""; $result_c= ""; $numrows_c = 0;
			$query_o = ""; $result_o= ""; $numrows_o = 0;

		# Set Query for Closed Tickets records for list.
			$query_c = "SELECT hd_tt_closed, count(hd_tt_id) as tt_count_c";
			$query_c .= " FROM ".$_DBCFG['helpdesk'];
			$query_c .= " WHERE ".$_DBCFG['helpdesk'].".hd_tt_closed = 1";

			# Set to logged in Client ID if not admin to avoid seeing other client order id's
			IF ( !$_SEC['_sadmin_flg'] )
				{ $query_c .= " AND ".$_DBCFG['helpdesk'].".hd_tt_cl_id = ".$_SEC['_suser_id']; }

			$query_c .= " GROUP BY hd_tt_closed";
			$query_c .= " ORDER BY hd_tt_closed ASC, hd_tt_id ASC";

			# Do select and return check
				$result_c	= db_query_execute($query_c);
				$numrows_c	= db_query_numrows($result_c);

		# Set Query for Open Tickets records for list.
			$query_o = "SELECT hd_tt_status, count(hd_tt_id) as tt_count_o";
			$query_o .= " FROM ".$_DBCFG['helpdesk'];
			$query_o .= " WHERE ".$_DBCFG['helpdesk'].".hd_tt_closed = 0";

			# Set to logged in Client ID if not admin to avoid seeing other client order id's
			IF ( !$_SEC['_sadmin_flg'] )
				{ $query_o .= " AND ".$_DBCFG['helpdesk'].".hd_tt_cl_id = ".$_SEC['_suser_id']; }

			$query_o .= " GROUP BY hd_tt_status";
			$query_o .= " ORDER BY hd_tt_status ASC, hd_tt_id ASC";

			# Do select and return check
				$result_o	= db_query_execute($query_o);
				$numrows_o	= db_query_numrows($result_o);

		# Build form output
			$_out .= '<div align="left">'.$_nl;
			$_out .= '<table border="0" cellpadding="0" cellspacing="1">'.$_nl;
			$_out .= '<tr><td class="TP1SML_BL" colspan="4">'.$_nl;
			$_out .= '<b><a href="mod.php?mod=helpdesk">'.$_LANG['_CC']['HelpDesk'].'</a>'.$_sp.$_LANG['_CC']['Summary'].':</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;
			$_out .= '<tr><td class="TP1SML_BL" colspan="4">'.$_nl;
			$_out .= '<b><a href="mod.php?mod=helpdesk&fb=1&fs=0">'.$_LANG['_CC']['Open'].'</a>'.$_sp.$_LANG['_CC']['Ticket'].$_sp.$_LANG['_CC']['Summary'].':</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;

			# Print out open ticket query results
				$tt_count_ttl_o	= 0;
				while(list($hd_tt_status, $tt_count_o) = db_fetch_row($result_o))
				{
					IF ($tt_count_o ==1 )	{ $_str_02 = $_LANG['_CC']['lc_support_ticket']; }
					ELSE 					{ $_str_02 = $_LANG['_CC']['lc_support_tickets']; }

					$_out .= '<tr>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.$_sp.$_sp.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.'<b>('.$tt_count_o.')</b>'.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.'<a href="mod.php?mod=helpdesk&fb=3&fs='.urlencode($hd_tt_status).'"><b>'.$hd_tt_status.'</b></a>'.'</td>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.$_str_02.'.</td>'.$_nl;
					$_out .= '</tr>'.$_nl;

					$tt_count_ttl_o = $tt_count_ttl_o + $tt_count_o;
				}

			$_out .= '<tr><td class="TP1SML_BL" colspan="4">'.$_nl;
			$_out .= '<b>'.$_LANG['_CC']['Total_of'].$_sp.$tt_count_ttl_o.$_sp.$_LANG['_CC']['Open'].$_sp.$_LANG['_CC']['lc_support_ticket_s'].'</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;

			$_out .= '<tr><td class="TP1MED_BC" colspan="4">'.$_sp.$_nl.'</td></tr>'.$_nl;

			# Print out closed ticket query results
				$tt_count_ttl_c	= 0;
				while(list($hd_tt_closed, $tt_count_c) = db_fetch_row($result_c))
				{
					$tt_count_ttl_c = $tt_count_ttl_c + $tt_count_c;
				}

			$_out .= '<tr><td class="TP1SML_BL" colspan="4">'.$_nl;
			$_out .= '<b><a href="mod.php?mod=helpdesk&fb=1&fs=1">'.$_LANG['_CC']['Closed'].'</a>'.$_sp.$_LANG['_CC']['Ticket'].$_sp.$_LANG['_CC']['Summary'].':</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;
			$_out .= '<tr><td class="TP1SML_BL" colspan="4">'.$_nl;
			$_out .= '<b>'.$_LANG['_CC']['Total_of'].$_sp.$tt_count_ttl_c.$_sp.$_LANG['_CC']['Closed'].$_sp.$_LANG['_CC']['lc_support_ticket_s'].'</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;

			$_out .= '</table>'.$_nl;
			$_out .= '</div>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do summary: Servers
function do_summary_servers( $adata, $aret_flag=0 )
	{
		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Set Query for select.
			$query	.= "SELECT ".$_DBCFG['server_info'].".si_id, ".$_DBCFG['server_info'].".si_name";
			$query	.= ", count(".$_DBCFG['domains'].".dom_id) as si_count";
			$query	.= " FROM ".$_DBCFG['server_info'].", ".$_DBCFG['domains'];
			$query	.= " WHERE ".$_DBCFG['server_info'].".si_id = ".$_DBCFG['domains'].".dom_si_id";

			# Set to logged in Client ID if not admin to avoid seeing other client's stuff
			IF ( !$_SEC['_sadmin_flg'] ) { $query .= " AND ".$_DBCFG['domains'].".dom_cl_id = ".$_SEC['_suser_id']; }

			$query .= " GROUP BY si_name";
			$query .= " ORDER BY si_name ASC";

		# Do select and return check
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build form output
			$_out .= '<div align="left">'.$_nl;
			$_out .= '<table border="0" cellpadding="0" cellspacing="1">'.$_nl;
			$_out .= '<tr><td class="TP1SML_BL" colspan="2">'.$_nl;
			$_out .= $_LANG['_CC']['Servers'].'</a>'.$_sp.$_LANG['_CC']['Summary'].':</b>'.$_nl;
			$_out .= '</td></tr>'.$_nl;

			# Process query results
				$domain_count_ttl	= 0;
				$server_count_ttl	= $numrows;
				while(list($si_id, $si_name, $si_count) = db_fetch_row($result))
				{
					IF ($si_count ==1 )	{ $_str_02 = $_LANG['_CC']['lc_domain']; }
					ELSE 				{ $_str_02 = $_LANG['_CC']['lc_domains']; }

					$_out .= '<tr>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.$_sp.$_sp.'</td>'.$_nl;
					$_link = '<a href="mod.php?mod=domains&mode=view&fb=1&fs='.$si_id.'"><b>'.$si_name.'</b></a>'.$_nl;
					$_out .= '<td class="TP1SML_NL">'.'<b>('.$si_count.')'.$_sp.$_link.'</b>'.$_sp.$_str_02.'</td>'.$_nl;
					$_out .= '</tr>'.$_nl;

					$domain_count_ttl = $domain_count_ttl + $si_count;
				}

			$_out .= '<tr><td class="TP1SML_BL" colspan="2">'.$_nl;
			$_out .= '<b>'.$_LANG['_CC']['Total_of'].$_sp.'('.$domain_count_ttl.')'.'</b>'.$_sp;
			$_out .= $_LANG['_CC']['lc_domain_s'].$_sp.$_LANG['_CC']['on'].$_sp.'('.$server_count_ttl.')'.$_sp.$_LANG['_CC']['lc_server_s'].$_nl;
			$_out .= '</td></tr>'.$_nl;

			$_out .= '</table>'.$_nl;
			$_out .= '</div>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * End Module Functions
**************************************************************/

?>
