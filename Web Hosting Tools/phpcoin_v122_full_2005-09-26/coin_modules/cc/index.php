<?php

/**************************************************************
 * File: 		Command Center Module Index.php
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
	IF (!isset($_SERVER)) { $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("index.php", $_SERVER["PHP_SELF"])) {
		require_once ('../../coin_includes/session_set.php');
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01&url=mod.php?mod=cc');
		exit();
	}

# Get security vars
	$_SEC = get_security_flags ();
	$_PERMS	= do_decode_perms_admin($_SEC['_sadmin_perms']);

# Include language file (must be after parameter load to use them)
	require_once ( $_CCFG['_PKG_PATH_LANG'].'lang_cc.php');
	IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_cc_override.php')) {
		require_once($_CCFG['_PKG_PATH_LANG'].'lang_cc_override.php');
	}

# Include functions file
	require_once ( $_CCFG['_PKG_PATH_MDLS'].$_GPV['mod'].'/'.$_GPV['mod'].'_funcs.php');

# Include admin functions file if admin
	IF ($_SEC['_sadmin_flg'])	{ require_once ( $_CCFG['_PKG_PATH_MDLS'].$_GPV['mod'].'/'.$_GPV['mod'].'_admin.php'); }

/**************************************************************
 * Module code
**************************************************************/
# Check $_GPV['mode'] and set default to list
	switch($_GPV['mode']) {
		case "default":
			break;
		case "search":
			break;
		default:
			$_GPV['mode']="none";
			break;
	}

# Build time_stamp values when search
	IF ( $_GPV['mode'] == 'search' ) {
		IF ( $_GPV['s_ts_01_hour'] == '' )	{ $_GPV['s_ts_01_hour'] = 0; }
		IF ( $_GPV['s_ts_01_minute'] == '' )	{ $_GPV['s_ts_01_minute'] = 0; }
		IF ( $_GPV['s_ts_01_second'] == '' )	{ $_GPV['s_ts_01_second'] = 0; }

		IF ( $_GPV['s_ts_01_year'] == '' || $_GPV['s_ts_01_month'] == '' || $_GPV['s_ts_01_day'] == '' )
				{ $_GPV['s_ts_01'] = ''; }
		ELSE	{ $_GPV['s_ts_01'] = mktime( $_GPV['s_ts_01_hour'],$_GPV['s_ts_01_minute'],$_GPV['s_ts_01_second'],$_GPV['s_ts_01_month'],$_GPV['s_ts_01_day'],$_GPV['s_ts_01_year'] ); }
		#	ELSE	{ $_GPV['s_ts_01'] = mktime( 0,0,0,$_GPV['s_ts_01_month'],$_GPV['s_ts_01_day'],$_GPV['s_ts_01_year'] ); }

		IF ( $_GPV['s_ts_02_hour'] == '' )	{ $_GPV['s_ts_02_hour'] = 0; }
		IF ( $_GPV['s_ts_02_minute'] == '' )	{ $_GPV['s_ts_02_minute'] = 0; }
		IF ( $_GPV['s_ts_02_second'] == '' )	{ $_GPV['s_ts_02_second'] = 0; }

		IF ( $_GPV['s_ts_02_year'] == '' || $_GPV['s_ts_02_month'] == '' || $_GPV['s_ts_02_day'] == '' )
					{ $_GPV['s_ts_02'] = ''; }
		ELSE	{ $_GPV['s_ts_02'] = mktime( $_GPV['s_ts_02_hour'],$_GPV['s_ts_02_minute'],$_GPV['s_ts_02_second'],$_GPV['s_ts_02_month'],$_GPV['s_ts_02_day'],$_GPV['s_ts_02_year'] ); }
		#	ELSE	{ $_GPV['s_ts_02'] = mktime( 0,0,0,$_GPV['s_ts_02_month'],$_GPV['s_ts_02_day'],$_GPV['s_ts_02_year'] ); }
	}

# Build Data Array (may also be over-ridden later in code)
	$data	= $_GPV;


##############################
# Mode Call: 	All modes
# Summary:
#	- Check if login required
##############################
IF ( !$_SEC['_suser_flg'] && !$_SEC['_sadmin_flg']) {
	# Set login flag
		$_login_flag = 1;

	# Call function for articles listings
		$_out = '<!-- Start content -->'.$_nl;
		$_out .= do_login($data, 'user', '1').$_nl;

	# Echo final output
		echo $_out;
}

##############################
# Operation:	None
# Summary:
#	- For loading select menu.
#	- For no actions specified.
##############################
IF ( !$_login_flag && $_GPV['mode']=='none' ) {
	# Content start flag
		$_out .= '<!-- Start content -->'.$_nl;

	# Build Title String, Content String, and Footer Menu String
	# Do Outer Table
		$_cstr 	.= '<center><table width="100%" cellspacing="3" border="0">'.$_nl;
		$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;

	# Summary Cell (left)
		$_cstr 	.= '<center><table width="95%" cellspacing="5" border="0">'.$_nl;

		IF ( $_CCFG['HELPDESK_ENABLE'] == 1 && ($_SEC['_suser_flg'] || ($_SEC['_sadmin_flg'] && $_PERMS['AP16'] == 1 || $_PERMS['AP10'] == 1 || $_PERMS['AP09'] == 1)) ) {
			$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
			$_cstr 	.= do_summary_support_tickets($data, '1');
			$_cstr 	.= '</td></tr>'.$_nl;
			$_cstr 	.= '<tr><td align="center" valign="top"><hr></td></tr>'.$_nl;
		}

		IF ( $_SEC['_sadmin_flg'] ) {
			$_tstr1 = $_LANG['_CC']['Administrator_Command_Center'];

		# Add "edit parameters" button
			IF ($_CCFG['ENABLE_QUICK_EDIT'] && ($_PERMS['AP16'] == 1 || $_PERMS['AP10'] == 1 || $_PERMS['AP15'] == 1)) {
				$_tstr1 .= ' <a href="admin.php?cp=parms&op=edit&fpg=&fpgs=summary">'.$_TCFG['_S_IMG_PM_S'].'</a>';
			}
			$_tstr 	= do_tstr_search_list($_tstr1);

			IF ($_PERMS['AP16'] == 1 || $_PERMS['AP10'] == 1 || $_PERMS['AP07'] == 1) {
				$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
				$_cstr 	.= do_summary_clients($data, '1');
				$_cstr 	.= '</td></tr>'.$_nl;
				$_cstr 	.= '<tr><td align="center" valign="top"><hr></td></tr>'.$_nl;
			}
		} ELSE IF ( $_SEC['_suser_flg'] ) {
			$_tstr 	= $_LANG['_CC']['Welcome'].$_sp.$_SEC['_suser_name'];
		}

		IF ( $_CCFG['ORDERS_ENABLE'] == 1 && ($_SEC['_suser_flg'] || ($_SEC['_sadmin_flg'] && $_PERMS['AP16'] == 1 || $_PERMS['AP10'] == 1 || $_PERMS['AP08'] == 1))) {
			$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
			$_cstr 	.= do_summary_orders($data, '1');
			$_cstr 	.= '</td></tr>'.$_nl;
			$_cstr 	.= '<tr><td align="center" valign="top"><hr></td></tr>'.$_nl;
		}

	# Do summary products narrow-screen
		IF ( $_CCFG['PRODUCT_SUMMARY_ENABLE'] == 1 && $_CCFG['ORDERS_ENABLE'] == 1 && !$_CCFG['ORDERS_LIST_SHOW_PROD_DESC'] && ($_SEC['_suser_flg'] || ($_SEC['_sadmin_flg'] && $_PERMS['AP16'] == 1 || $_PERMS['AP10'] == 1 || $_PERMS['AP08'] == 1))) {
			$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
			$_cstr 	.= do_summary_product_orders($data, '1');
			$_cstr 	.= '</td></tr>'.$_nl;
			$_cstr 	.= '<tr><td align="center" valign="top"><hr></td></tr>'.$_nl;
		}

	# Do summary invoices narrow-screen
		IF ($_CCFG['INVOICES_ENABLE'] && !$_CCFG['SUMMARY_INVOICES_BY_TYPE'] && ($_SEC['_suser_flg'] || ($_SEC['_sadmin_flg'] && $_PERMS['AP16'] == 1 || $_PERMS['AP10'] == 1 || $_PERMS['AP08'] == 1))) {
			$_cstr 	.= '<tr><td align="center" valign="top" colspan="2">'.$_nl;
			$_cstr 	.= do_summary_invoices($data, '1');
			$_cstr 	.= '</td></tr>'.$_nl;
			$_cstr 	.= '<tr><td align="center" valign="top"><hr></td></tr>'.$_nl;
		}

		$_cstr 	.= '</table></center>'.$_nl;

	# Summary Cell (right)
		$_cstr 	.= '</td><td align="center" valign="top">'.$_nl;
		$_cstr 	.= '<center><table width="95%" cellspacing="5" border="0">'.$_nl;

		IF ($_CCFG['DOMAINS_ENABLE']) {

			IF ( $_CCFG['CC_SERVER_LIST_ENABLE'] ) {
				$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
				$_cstr 	.= do_summary_servers($data, '1').$_nl;
				$_cstr 	.= '</td></tr>'.$_nl;
				$_cstr 	.= '<tr><td align="center" valign="top"><hr></td></tr>'.$_nl;
			}

			IF ( $_CCFG['CC_DOMAIN_EXP_LIST_ENABLE'] ) {
				$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
				$_cstr 	.= do_summary_domains_exp($data, '1').$_nl;
				$_cstr 	.= '</td></tr>'.$_nl;
				$_cstr 	.= '<tr><td align="center" valign="top"><hr></td></tr>'.$_nl;
			}
			IF ( $_CCFG['CC_SACC_EXP_LIST_ENABLE'] ) {
				$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
				$_cstr 	.= do_summary_saccs_exp($data, '1').$_nl;
				$_cstr 	.= '</td></tr>'.$_nl;
				$_cstr 	.= '<tr><td align="center" valign="top"><hr></td></tr>'.$_nl;
			}
		}

	# Sorry, but this section is so that I do not have to maintain
	# several version sof the single code-base
		IF ($_SERVER["SERVER_NAME"] == "www.phpcoin.com" || $_SERVER["SERVER_NAME"] == "hosting.cantexgroup.ca") {
			$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
			$_cstr 	.= do_summary_licenses('1').$_nl;
			$_cstr 	.= '</td></tr>'.$_nl;
			$_cstr 	.= '<tr><td align="center" valign="top"><hr></td></tr>'.$_nl;
		}


		$_cstr 	.= '</table></center>'.$_nl;

	# Do "products ordered" summary wide-screen
		IF ( $_CCFG['PRODUCT_SUMMARY_ENABLE'] == 1 && $_CCFG['ORDERS_ENABLE'] == 1 && $_CCFG['ORDERS_LIST_SHOW_PROD_DESC']  && ($_SEC['_suser_flg'] || ($_SEC['_sadmin_flg'] && $_PERMS['AP16'] == 1 || $_PERMS['AP10'] == 1 || $_PERMS['AP08'] == 1))) {
			$_cstr 	.= '<tr><td align="center" valign="top" colspan="2">'.$_nl;
			$_cstr 	.= do_summary_product_orders($data, '1');
			$_cstr 	.= '</td></tr>'.$_nl;
			$_cstr 	.= '<tr><td align="center" valign="top" colspan="2"><hr></td></tr>'.$_nl;
		}

	# Do summary invoices wide-screen
		IF ($_CCFG['INVOICES_ENABLE'] && $_CCFG['SUMMARY_INVOICES_BY_TYPE'] && ($_SEC['_suser_flg'] || ($_SEC['_sadmin_flg'] && $_PERMS['AP16'] == 1 || $_PERMS['AP10'] == 1 || $_PERMS['AP08'] == 1))) {
			$_cstr 	.= '<tr><td align="center" valign="top" colspan="2">'.$_nl;
			$_cstr 	.= do_summary_invoices_columnar($data, '1');
			$_cstr 	.= '</td></tr>'.$_nl;
		}

	# Close Outer table
		$_cstr .= $_sp.$_nl;
		$_cstr 	.= '</td></tr>'.$_nl;
		$_cstr 	.= '</table></center>'.$_nl;

		IF ( $_SEC['_sadmin_flg'] ) {
			$_mstr_flag	= '1';
			$_mstr		.= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
		} ELSE IF ( $_SEC['_suser_flg'] ) {
			$_mstr_flag	= '0';
			$_mstr 		.= ''.$_nl;
		}

	# Call block it function
		$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
		$_out .= '<br>'.$_nl;

	# Echo final output
		echo $_out;
}

##############################
# Operation:	Search
# Summary:
#	-
##############################
IF ( !$_login_flag && $_GPV['mode']=='search' )
	IF ( !$_SEC['_sadmin_flg'] )
		{
			# Content start flag
				$_out 		.= '<!-- Start content -->'.$_nl;
				$_tstr 		= $_LANG['_CC']['Search_Options'];
				$_cstr 		.= '<center>'.$_LANG['_CC']['Sorry_Administrative_Function_Only'].'</center>'.$_nl;
				$_mstr_flag	= '0';
				$_mstr 		.= '';

			# Call block it function
				$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
				$_out .= '<br>'.$_nl;

			# Echo final output
				echo $_out;
		}
	ELSE
		{
			# Check what to search, call code accordingly:
			IF ( $_GPV['sw'] == '' )
				{
					# Content start flag
						$_out 	.= '<!-- Start content -->'.$_nl;
						$_tstr 	.= do_tstr_search_list($_LANG['_CC']['Search_Options']);

						$_cstr 	.= '<div align="center" valign="top" height="100%">'.$_nl;
						$_cstr 	.= '<table width="200px" cellspacing="5">'.$_nl;
						$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
						$_cstr 	.= '<div class="button"><a href="'.$_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=clients">'.$_LANG['_CC']['Search_Clients'].'</a></div>';
						$_cstr 	.= '</td></tr>'.$_nl;

						IF ($_CCFG['DOMAINS_ENABLE']) {
							$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
							$_cstr 	.= '<div class="button"><a href="'.$_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=domains">'.$_LANG['_CC']['Search_Domains'].'</a></div>';
							$_cstr 	.= '</td></tr>'.$_nl;
						}

						If ($_CCFG['HELPDESK_ENABLE']) {
							$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
							$_cstr 	.= '<div class="button"><a href="'.$_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=helpdesk">'.$_LANG['_CC']['Search_Helpdesk'].'</a></div>';
							$_cstr 	.= '</td></tr>'.$_nl;
						}

						IF ($_CCFG['INVOICES_ENABLE']) {
							$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
							$_cstr 	.= '<div class="button"><a href="'.$_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=invoices">'.$_LANG['_CC']['Search_Invoices'].'</a></div>';
							$_cstr 	.= '</td></tr>'.$_nl;
						}

						If ($_CCFG['ORDERS_ENABLE']) {
							$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
							$_cstr 	.= '<div class="button"><a href="'.$_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=orders">'.$_LANG['_CC']['Search_Orders'].'</a></div>';
							$_cstr 	.= '</td></tr>'.$_nl;
						}

                        IF ($_CCFG['INVOICES_ENABLE']) {
							$_cstr 	.= '<tr><td align="center" valign="top">'.$_nl;
							$_cstr 	.= '<div class="button"><a href="'.$_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=trans">'.$_LANG['_CC']['Search_Transactions'].'</a></div>';
							$_cstr 	.= '</td></tr>'.$_nl;
						}

						$_cstr 	.= '</table>'.$_nl;
						$_cstr 	.= '</div>'.$_nl;

						$_mstr_flag	= '1';
						$_mstr		.= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
						$_mstr		.= do_nav_link ($_SERVER["PHP_SELF"].'?mod=cc', $_TCFG['_IMG_SUMMARY_M'],$_TCFG['_IMG_SUMMARY_M_MO'],'','');

					# Call block it function
						$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
						$_out .= '<br>'.$_nl;
				}
			IF ( $_GPV['sw'] == 'clients' )
				{
					IF ( $_PERMS['AP16'] != 1 && $_PERMS['AP10'] != 1 && $_PERMS['AP07'] != 1 )
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_no_permission_message ();
							$_out .= '<br>'.$_nl;
							echo $_out;
							exit;
						}
					ELSE
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_form_search_clients( $data, 1);
						}
				}
			IF ( $_GPV['sw'] == 'domains' )
				{
					IF ( $_PERMS['AP16'] != 1 && $_PERMS['AP10'] != 1 && $_PERMS['AP06'] != 1 )
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_no_permission_message ();
							$_out .= '<br>'.$_nl;
							echo $_out;
							exit;
						}
					ELSE
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_form_search_domains( $data, 1);
						}
				}
			IF ( $_GPV['sw'] == 'helpdesk' )
				{
					IF ( $_PERMS['AP16'] != 1 && $_PERMS['AP10'] != 1 && $_PERMS['AP09'] != 1 )
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_no_permission_message ();
							$_out .= '<br>'.$_nl;
							echo $_out;
							exit;
						}
					ELSE
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_form_search_helpdesk( $data, 1);
						}
				}
			IF ( $_GPV['sw'] == 'invoices' )
				{
					IF ( $_PERMS['AP16'] != 1 && $_PERMS['AP10'] != 1 && $_PERMS['AP08'] != 1 )
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_no_permission_message ();
							$_out .= '<br>'.$_nl;
							echo $_out;
							exit;
						}
					ELSE
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_form_search_invoices( $data, 1);
						}
				}
			IF ( $_GPV['sw'] == 'orders' )
				{
					IF ( $_PERMS['AP16'] != 1 && $_PERMS['AP10'] != 1 && $_PERMS['AP08'] != 1 )
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_no_permission_message ();
							$_out .= '<br>'.$_nl;
							echo $_out;
							exit;
						}
					ELSE
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_form_search_orders( $data, 1);
						}
				}
			IF ( $_GPV['sw'] == 'trans' )
				{
					IF ( $_PERMS['AP16'] != 1 && $_PERMS['AP10'] != 1 && $_PERMS['AP08'] != 1 )
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_no_permission_message ();
							$_out .= '<br>'.$_nl;
							echo $_out;
							exit;
						}
					ELSE
						{
							$_out .= '<!-- Start content -->'.$_nl;
							$_out .= do_form_search_trans( $data, 1);
						}
				}

			# Echo final output
				echo $_out;
		}

/**************************************************************
 * End Module Code
**************************************************************/

?>
