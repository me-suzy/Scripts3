<?php

/**************************************************************
 * File: 		Orders Module Admin Functions File
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
	IF (eregi("orders_admin.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01&url=mod.php?mod=orders');
			exit;
		}

/**************************************************************
 * Module Admin Functions
**************************************************************/
# Do Form for Add / Edit
function do_form_add_edit ( $amode, $adata, $aerr_entry, $aret_flag=0 ) {
	# Get security vars
		$_SEC = get_security_flags ();

	# Dim some Vars:
		global $_CCFG, $_ACFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Get field enabled vars
		$_BV = do_decode_DB16($_CCFG['ORDERS_FIELD_ENABLE_ORD']);

	# Build mode dependent strings
		switch ($amode) {
			case "add":
				$mode_proper	= $_LANG['_ORDERS']['B_Add'];
				$mode_button	= $_LANG['_ORDERS']['B_Add'];
				break;
			case "edit":
				$mode_proper	= $_LANG['_ORDERS']['B_Edit'];
				$mode_button	= $_LANG['_ORDERS']['B_Save'];
				break;
			default:
				$amode			= "add";
				$mode_proper	= $_LANG['_ORDERS']['B_Add'];
				$mode_button	= $_LANG['_ORDERS']['B_Add'];
				break;
		}

	# Build Title String, Content String, and Footer Menu String
		$_tstr .= $mode_proper.' '.$_LANG['_ORDERS']['Orders_Entry'].' ( <b>(*)</b> '.$_LANG['_ORDERS']['denotes_optional_items'].')';

	# Do data entry error string check and build
		IF ($aerr_entry['flag']) {
		 	$err_str = $_LANG['_ORDERS']['ORD_ERR_ERR_HDR1'].'<br>'.$_LANG['_ORDERS']['ORD_ERR_ERR_HDR2'].'<br>'.$_nl;

 			IF ($aerr_entry['ord_id']) 			{ $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR01']; $err_prv = 1; }
			IF ($aerr_entry['ord_ts']) 			{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR02']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_status']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR03']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_cl_id']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR04']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_company']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR05']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_name_first']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR06']; $err_prv = 1; }
	 		IF ($aerr_entry['ord_name_last']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR07']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_addr_01']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR08']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_addr_02']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR09']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_city']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR10']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_state_prov']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR11']; $err_prv = 1; }
	 		IF ($aerr_entry['ord_country']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR12']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_zip_code']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR13']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_phone']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR14']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_email']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR15']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_domain']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR16']; $err_prv = 1; }
	 		IF ($aerr_entry['ord_domain_action']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR17']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_user_name']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR18']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_user_pword']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR19']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_vendor_id']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR20']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_prod_id']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR21']; $err_prv = 1; }
	 		IF ($aerr_entry['ord_unit_cost']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR22']; $err_prv = 1; }
			IF ($aerr_entry['ord_accept_tos']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR23']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_accept_aup']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR24']; $err_prv = 1; }
		 	IF ($aerr_entry['ord_referred_by']) { IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ORDERS']['ORD_ERR_ERR25']; $err_prv = 1; }

 			$_cstr .= '<p align="center"><b>'.$err_str.'</b>'.$_nl;
		}

	# Check Stage for extra data validation
		IF ( $adata['stage']==1 ) {

		# Email
			IF ( $err_entry['err_email_invalid'] ) {
				$_err_more .= '<br>'.$_LANG['_ORDERS']['ORD_ERR_ERR30'].$_nl;
			}

		# Domain name to top level doms
			IF ( $err_entry['err_domain_invalid'] ) {
				$_err_more .= '<br>'.$_LANG['_ORDERS']['ORD_ERR_ERR31'].'- mydom.'.do_domain_ext_valid_list( none, none, 1).' ).'.$_nl;
			}

		# Unique domain name- must be unique of belong to this user
			IF ( $err_entry['err_domain_exist'] ) {
				$_err_more .= '<br>'.$_LANG['_ORDERS']['ORD_ERR_ERR32'].$_nl;
			}

		# Unique user name- does exist
			IF ( $err_entry['err_user_name_exist'] ) {
				$_err_more .= '<br>'.$_LANG['_ORDERS']['ORD_ERR_ERR33'].$_nl;
			}

		# Passwords equal
			IF ( $err_entry['err_pword_match'] ) {
				$_err_more .= '<br>'.$_LANG['_ORDERS']['ORD_ERR_ERR34'].$_nl;
				$adata['ord_user_pword'] = "";
				$adata['ord_user_pword_re'] = "";
			}

		# Print out more errors
			IF ( $_err_more ) { $_cstr .= '<br><b>'.$_err_more.'</b>'.$_nl; }
		}

	# Build common td start tag / col strings (reduce text)
		$_td_str_left			= '<td class="TP1SML_NR" width="35%">';
		$_td_str_left_valign	= '<td class="TP1SML_NR" width="35%" valign="top">';
		$_td_str_right			= '<td class="TP1SML_NL" width="65%">';
		$_td_str_right_just		= '<td class="TP1SML_NJ" width="65%">';
		$_td_str_center_span	= '<td class="TP1SML_NC" width="100%" colspan="2">';

	# Misc mode check for display values
		IF ( $amode=='add' ) {
			$adata[ord_id] = '('.$_LANG['_ORDERS']['auto-assigned'].')'; $adata[ord_ts] = '('.$_LANG['_ORDERS']['auto-assigned'].')';
		}

	# Do Main Form
		$_cstr .= '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=orders&mode='.$amode.'">'.$_nl;
		$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;

	# If adding, add note on current operation
		IF ( $amode=='add' ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_center_span.$_nl;
			$_cstr .= '<b>'.$_LANG['_ORDERS']['ORD_ADD_NOTE_H1'].'</b>'.$_sp.$_LANG['_ORDERS']['ORD_ADD_NOTE_L1'].$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_td_str_center_span.$_sp.'</td></tr>'.$_nl;
		}

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Order_ID'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= $adata[ord_id].$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= '<td class="TP3SML_NR" width="35%" valign="top">'.'<b>'.$_LANG['_ORDERS']['l_Order_DateTime'].$_sp.'</b></td>'.$_nl;
		$_cstr .= '<td class="TP3SML_NL" width="65%">'.$_nl;
		IF ( $adata[ord_ts] <= 0 || $adata[ord_ts] == '') { $adata[ord_ts] = dt_get_uts().$_nl; }
		$_cstr .= do_datetime_edit_list (ord_ts, $adata[ord_ts], 1).$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Status'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
	# Call select list function
		IF ( $adata[ord_status] == '') { $adata[ord_status] = $_CCFG['ORD_STATUS'][4]; }
		$aname	= "ord_status";
		$avalue	= $adata[ord_status];
		$_cstr .= do_select_list_status_order($aname, $avalue).$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Client_ID'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
	# Call select list function
		$aname	= "ord_cl_id";
		$avalue	= $adata[ord_cl_id];
		$_cstr .= do_select_list_clients($aname, $avalue,0).$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		IF ( $amode=='edit' ) {
			IF ( $_BV['B16'] == 1 ) {
				$_cstr .= '<tr>'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Company_NReq'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$_nl;
				$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_company" SIZE="40" value="'.$adata[ord_company].'" maxlength="50">'.$_nl;
				$_cstr .= '</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
			}

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_First_Name'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_name_first" SIZE=20 value="'.$adata[ord_name_first].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Last_Name'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_name_last" SIZE=20 value="'.$adata[ord_name_last].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			IF ( $_BV['B15'] == 1 ) {
				$_cstr .= '<tr>'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Address_Street_1'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$_nl;
				$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_addr_01" SIZE=40 value="'.$adata[ord_addr_01].'" maxlength="50">'.$_nl;
				$_cstr .= '</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
			}

			IF ( $_BV['B14'] == 1 ) {
				$_cstr .= '<tr>'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Address_Street_2_NReq'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$_nl;
				$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_addr_02" SIZE=40 value="'.$adata[ord_addr_02].'" maxlength="50">'.$_nl;
				$_cstr .= '</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
			}

			IF ( $_BV['B13'] == 1 ) {
				$_cstr .= '<tr>'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_City'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$_nl;
				$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_city" SIZE=40 value="'.$adata[ord_city].'" maxlength="50">'.$_nl;
				$_cstr .= '</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
			}

			IF ( $_BV['B12'] == 1 ) {
				$_cstr .= '<tr>'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_State_Province'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$_nl;
				$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_state_prov" SIZE=40 value="'.$adata[ord_state_prov].'" maxlength="50">'.$_nl;
				$_cstr .= '</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
			}

			IF ( $_BV['B10'] == 1 ) {
				$_cstr .= '<tr>'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Country'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$_nl;
				$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_country" SIZE=40 value="'.$adata[ord_country].'" maxlength="50">'.$_nl;
				$_cstr .= '</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
			}

			IF ( $_BV['B11'] == 1 ) {
				$_cstr .= '<tr>'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Zip_Postal_Code'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$_nl;
				$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_zip_code" SIZE=12 value="'.$adata[ord_zip_code].'">'.$_nl;
				$_cstr .= '</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
			}

			IF ( $_BV['B09'] == 1 ) {
				$_cstr .= '<tr>'.$_nl;
				$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Phone_NReq'].$_sp.'</b></td>'.$_nl;
				$_cstr .= $_td_str_right.$_nl;
				$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_phone" SIZE=20 value="'.$adata[ord_phone].'">'.$_nl;
				$_cstr .= '</td>'.$_nl;
				$_cstr .= '</tr>'.$_nl;
			}

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Email_Address'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_email" SIZE=40 value="'.$adata[ord_email].'" maxlength="50">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}

		IF ($_CCFG['DOMAINS_ENABLE'] || $_SERVER["SERVER_NAME"] == "www.phpcoin.com") {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Domain'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_domain" SIZE=40 value="'.$adata['ord_domain'].'" maxlength="50">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		} ELSE {
            $ord_domain = 'NONE';
		}

		IF ($_CCFG['DOMAINS_ENABLE'] && $_BV['B08'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Domain_Action'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_select_list_dom_action( ord_domain_action, $adata[ord_domain_action], 1 );
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		} ELSE {
            $ord_domain_action = 1;
		}

		IF ( $amode=='edit' ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_User_Name'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_user_name" SIZE=20 value="'.$adata['ord_user_name'].'" maxlength="'.$_CCFG['CLIENT_MAX_LEN_UNAME'].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_sp.'</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= $_LANG['_ORDERS']['Password_Note'].$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Password'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_user_pword" SIZE=20 value="'.$adata['ord_user_pword'].'" maxlength="'.$_CCFG['CLIENT_MAX_LEN_PWORD'].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Password_Confirm'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_user_pword_re" SIZE=20 value="'.$adata['ord_user_pword_re'].'" maxlength="'.$_CCFG['CLIENT_MAX_LEN_PWORD'].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}

		IF ($_CCFG['SHOW_PAYMENT_METHOD']) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Vendor'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
		# Call select list function
			$aname	= "ord_vendor_id";
			$avalue	= $adata[ord_vendor_id];
			$_cstr .= do_select_list_vendors($aname, $avalue).$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		} ELSE {
			$aname = "ord_vendor_id";
			$avalue = $adata[ord_vendor_id];
			$_cstr .= '<input type="hidden" name="'.$aname.'" value="'.$avalue.'">';
		}

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Product'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
	# Call select list function
		$aname	= "ord_prod_id";
		$avalue	= $adata[ord_prod_id];
		$_cstr .= do_select_list_products($aname, $avalue).$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		IF ( $amode=='edit' ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Unit_Cost'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_unit_cost" SIZE=12 value="'.$adata[ord_unit_cost].'">'.' (no commas)'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}

		IF ( $adata[ord_accept_tos] == '' ) { $adata[ord_accept_tos] = 1; }
		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Accepted_TOS'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= do_select_list_no_yes('ord_accept_tos', $adata[ord_accept_tos], 1);
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		IF ( $adata[ord_accept_aup] == '' ) { $adata[ord_accept_aup] = 1; }
		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Accepted_AUP'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= do_select_list_no_yes('ord_accept_aup', $adata[ord_accept_aup], 1);
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		IF ( $_BV['B07'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ORDERS']['l_Referred_By_NReq'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="ord_referred_by" SIZE=30 value="'.$adata[ord_referred_by].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}

	# Set Optional Fields 01 thru 05
		IF ( $_BV['B01'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_nl;
			$_cstr .= '<b>'.$_CCFG['ORD_LABEL_OPTFLD_01'].$_sp.'</b>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT name="ord_optfld_01" size="32" maxlength="50" value="'.$adata[ord_optfld_01].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}
		IF ( $_BV['B02'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_nl;
			$_cstr .= '<b>'.$_CCFG['ORD_LABEL_OPTFLD_02'].$_sp.'</b>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT name="ord_optfld_02" size="32" maxlength="50" value="'.$adata[ord_optfld_02].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}
		IF ( $_BV['B03'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_nl;
			$_cstr .= '<b>'.$_CCFG['ORD_LABEL_OPTFLD_03'].$_sp.'</b>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT name="ord_optfld_03" size="32" maxlength="50" value="'.$adata[ord_optfld_03].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}
		IF ( $_BV['B04'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_nl;
			$_cstr .= '<b>'.$_CCFG['ORD_LABEL_OPTFLD_04'].$_sp.'</b>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT name="ord_optfld_04" size="32" maxlength="50" value="'.$adata[ord_optfld_04].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}
		IF ( $_BV['B05'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_nl;
			$_cstr .= '<b>'.$_CCFG['ORD_LABEL_OPTFLD_05'].$_sp.'</b>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT name="ord_optfld_05" size="32" maxlength="50" value="'.$adata[ord_optfld_05].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}

   # Add invoice number entry box unless invoice auto-added
		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.$_nl;
		$_cstr .= '<b>'.$_LANG['_ORDERS']['l_Invoice_ID'].$_sp.'</b>'.$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		IF ( $amode=='add' && $_ACFG['ORDER_AUTO_CREATE_INVOICE']) {
			$adata[ord_invc_id] = '('.$_LANG['_ORDERS']['auto-assigned'].')';
			$_cstr .= $adata[ord_invc_id];
		} ELSE {
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT name="ord_invc_id" size="5" maxlength="11" value="'.$adata[ord_invc_id].'">'.$_nl;
		}
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= '<td class="TP0MED_NC" width="100%" colspan="2">'.$_nl;
		$_cstr .= '<INPUT TYPE=hidden name="stage" value="1">'.$_nl;
		$_cstr .= '<INPUT TYPE=hidden name="ord_id" value="'.$adata[ord_id].'">'.$_nl;
	# Mode check for edit to save original user name and domain
		IF ( $amode=='edit' ) {
			IF ( !$adata[ord_user_name_orig] ) { $adata[ord_user_name_orig]=$adata[ord_user_name]; }
			$_cstr .= '<INPUT TYPE=hidden name="ord_user_name_orig" value="'.$adata[ord_user_name_orig].'">'.$_nl;
			IF (!$_CCFG['DOMAINS_ENABLE']) {
				IF ( !$adata[ord_domain_orig] ) { $adata[ord_domain_orig]=$adata[ord_domain]; }
				$_cstr .= '<INPUT TYPE=hidden name="ord_domain_orig" value="'.$adata[ord_domain_orig].'">'.$_nl;
			}
		}
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;
		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= do_input_button_class_sw ('b_edit', 'SUBMIT', $mode_button, 'button_form_h', 'button_form', '1').$_nl;
		$_cstr .= do_input_button_class_sw ('b_reset', 'RESET', $_LANG['_ORDERS']['B_Reset'], 'button_form_h', 'button_form', '1').$_nl;
		IF ($amode=="edit") {
			$_cstr .= do_input_button_class_sw ('b_delete', 'SUBMIT', $_LANG['_ORDERS']['B_Delete_Entry'], 'button_form_h', 'button_form', '1').$_nl;
		}
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		$_cstr .= '</table>'.$_nl;
		$_cstr .= '</FORM>'.$_nl;

	# Build function argument text
		$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
		$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
		$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

	# Call block it function
		$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
		$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * End Module Admin Functions
**************************************************************/

?>
