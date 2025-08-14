<?php

/**************************************************************
 * File: 		Control Panel Core Functions File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-09 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_admin.php
 *
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (!eregi("admin.php", $_SERVER["PHP_SELF"])) {
		require_once ('../coin_includes/session_set.php');
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01&url=admin.php');
		exit;
	}

/**************************************************************
 * CP Core Functions Code
**************************************************************/
# Do decode Decimal to Binary (16-bit 0-65535)
function cp_do_decode_DB16($_DV) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Decode decimal value into array
		$_bin = str_pad(decbin($_DV), 16, "0", STR_PAD_LEFT);
		$_BV[B00]	= $_bin;
		$_BV[B16]	= $_bin{0};
		$_BV[B15]	= $_bin{1};
		$_BV[B14]	= $_bin{2};
		$_BV[B13]	= $_bin{3};
		$_BV[B12]	= $_bin{4};
		$_BV[B11]	= $_bin{5};
		$_BV[B10]	= $_bin{6};
		$_BV[B09]	= $_bin{7};
		$_BV[B08]	= $_bin{8};
		$_BV[B07]	= $_bin{9};
		$_BV[B06]	= $_bin{10};
		$_BV[B05]	= $_bin{11};
		$_BV[B04]	= $_bin{12};
		$_BV[B03]	= $_bin{13};
		$_BV[B02]	= $_bin{14};
		$_BV[B01]	= $_bin{15};

	# Return decoded binary values array
		return $_BV;
}


# Do encode Binary to Decimal (16-bit 0-65535)
function cp_do_encode_BD16($_BV) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Encode into 16-bit binary string
		IF ( $_BV[B16] != 1 ) { $_BV[B16] = 0; }
		IF ( $_BV[B15] != 1 ) { $_BV[B15] = 0; }
		IF ( $_BV[B14] != 1 ) { $_BV[B14] = 0; }
		IF ( $_BV[B13] != 1 ) { $_BV[B13] = 0; }
		IF ( $_BV[B12] != 1 ) { $_BV[B12] = 0; }
		IF ( $_BV[B11] != 1 ) { $_BV[B11] = 0; }
		IF ( $_BV[B10] != 1 ) { $_BV[B10] = 0; }
		IF ( $_BV[B09] != 1 ) { $_BV[B09] = 0; }
		IF ( $_BV[B08] != 1 ) { $_BV[B08] = 0; }
		IF ( $_BV[B07] != 1 ) { $_BV[B07] = 0; }
		IF ( $_BV[B06] != 1 ) { $_BV[B06] = 0; }
		IF ( $_BV[B05] != 1 ) { $_BV[B05] = 0; }
		IF ( $_BV[B04] != 1 ) { $_BV[B04] = 0; }
		IF ( $_BV[B03] != 1 ) { $_BV[B03] = 0; }
		IF ( $_BV[B02] != 1 ) { $_BV[B02] = 0; }
		IF ( $_BV[B01] != 1 ) { $_BV[B01] = 0; }
		$_bin	= $_BV[B16].$_BV[B15].$_BV[B14].$_BV[B13].$_BV[B12].$_BV[B11].$_BV[B10].$_BV[B09];
		$_bin	.= $_BV[B08].$_BV[B07].$_BV[B06].$_BV[B05].$_BV[B04].$_BV[B03].$_BV[B02].$_BV[B01];
		$_dec	= bindec($_bin);

	# Return decoded array
		return $_dec;
}


# Do edit field for: Order Order Info Form Field Enables
function cp_do_edit_orders_field_enable($avalue, $aform, $aret_flag=0) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		$_BV = cp_do_decode_DB16($avalue);

		IF ( $aform == 'COR' ) {
			$_FF['l09_Field_16'] = $_LANG['_ADMIN']['l09_COR_Field_16'];
			$_FF['l09_Field_15'] = $_LANG['_ADMIN']['l09_COR_Field_15'];
			$_FF['l09_Field_14'] = $_LANG['_ADMIN']['l09_COR_Field_14'];
			$_FF['l09_Field_13'] = $_LANG['_ADMIN']['l09_COR_Field_13'];
			$_FF['l09_Field_12'] = $_LANG['_ADMIN']['l09_COR_Field_12'];
			$_FF['l09_Field_11'] = $_LANG['_ADMIN']['l09_COR_Field_11'];
			$_FF['l09_Field_10'] = $_LANG['_ADMIN']['l09_COR_Field_10'];
			$_FF['l09_Field_09'] = $_LANG['_ADMIN']['l09_COR_Field_09'];
			$_FF['l09_Field_08'] = $_LANG['_ADMIN']['l09_COR_Field_08'];
			$_FF['l09_Field_07'] = $_LANG['_ADMIN']['l09_COR_Field_07'];
			$_FF['l09_Field_06'] = $_LANG['_ADMIN']['l09_COR_Field_06'];
			$_FF['l09_Field_05'] = $_LANG['_ADMIN']['l09_COR_Field_05'];
			$_FF['l09_Field_04'] = $_LANG['_ADMIN']['l09_COR_Field_04'];
			$_FF['l09_Field_03'] = $_LANG['_ADMIN']['l09_COR_Field_03'];
			$_FF['l09_Field_02'] = $_LANG['_ADMIN']['l09_COR_Field_02'];
			$_FF['l09_Field_01'] = $_LANG['_ADMIN']['l09_COR_Field_01'];
		}
		IF ( $aform == 'ORD' ) {
			$_FF['l09_Field_16'] = $_LANG['_ADMIN']['l09_ORD_Field_16'];
			$_FF['l09_Field_15'] = $_LANG['_ADMIN']['l09_ORD_Field_15'];
			$_FF['l09_Field_14'] = $_LANG['_ADMIN']['l09_ORD_Field_14'];
			$_FF['l09_Field_13'] = $_LANG['_ADMIN']['l09_ORD_Field_13'];
			$_FF['l09_Field_12'] = $_LANG['_ADMIN']['l09_ORD_Field_12'];
			$_FF['l09_Field_11'] = $_LANG['_ADMIN']['l09_ORD_Field_11'];
			$_FF['l09_Field_10'] = $_LANG['_ADMIN']['l09_ORD_Field_10'];
			$_FF['l09_Field_09'] = $_LANG['_ADMIN']['l09_ORD_Field_09'];
			$_FF['l09_Field_08'] = $_LANG['_ADMIN']['l09_ORD_Field_08'];
			$_FF['l09_Field_07'] = $_LANG['_ADMIN']['l09_ORD_Field_07'];
			$_FF['l09_Field_06'] = $_LANG['_ADMIN']['l09_ORD_Field_06'];
			$_FF['l09_Field_05'] = $_LANG['_ADMIN']['l09_ORD_Field_05'];
			$_FF['l09_Field_04'] = $_LANG['_ADMIN']['l09_ORD_Field_04'];
			$_FF['l09_Field_03'] = $_LANG['_ADMIN']['l09_ORD_Field_03'];
			$_FF['l09_Field_02'] = $_LANG['_ADMIN']['l09_ORD_Field_02'];
			$_FF['l09_Field_01'] = $_LANG['_ADMIN']['l09_ORD_Field_01'];
		}

		$_out .= '<table width="100%"><tr><td class="TP0SML_NL">';
		IF ( $_BV[B16]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B16] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B16" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_16'].'</b>'.$_nl;
		$_out .= '</td><td class="TP0SML_NL">';
		IF ( $_BV[B08]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B08] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B08" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_08'].'</b>'.$_nl;
		$_out .= '</td></tr><tr><td class="TP0SML_NL">';
		IF ( $_BV[B15]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B15] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B15" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_15'].'</b>'.$_nl;
		$_out .= '</td><td class="TP0SML_NL">';
		IF ( $_BV[B07]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B07] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B07" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_07'].'</b>'.$_nl;
		$_out .= '</td></tr><tr><td class="TP0SML_NL">';
		IF ( $_BV[B14]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B14] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B14" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_14'].'</b>'.$_nl;
		$_out .= '</td><td class="TP0SML_NL">';
		IF ( $_BV[B06]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B06] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B06" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_06'].'</b>'.$_nl;
		$_out .= '</td></tr><tr><td class="TP0SML_NL">';
		IF ( $_BV[B13]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B13] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B13" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_13'].'</b>'.$_nl;
		$_out .= '</td><td class="TP0SML_NL">';
		IF ( $_BV[B05]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B05] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B05" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_05'].'</b>'.$_nl;
		$_out .= '</td></tr><tr><td class="TP0SML_NL">';
		IF ( $_BV[B12]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B12] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B12" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_12'].'</b>'.$_nl;
		$_out .= '</td><td class="TP0SML_NL">';
		IF ( $_BV[B04]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B04] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B04" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_04'].'</b>'.$_nl;
		$_out .= '</td></tr><tr><td class="TP0SML_NL">';
		IF ( $_BV[B11]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B11] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B11" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_11'].'</b>'.$_nl;
		$_out .= '</td><td class="TP0SML_NL">';
		IF ( $_BV[B03]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B03] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B03" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_03'].'</b>'.$_nl;
		$_out .= '</td></tr><tr><td class="TP0SML_NL">';
		IF ( $_BV[B10]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B10] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B10" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_10'].'</b>'.$_nl;
		$_out .= '</td><td class="TP0SML_NL">';
		IF ( $_BV[B02]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B02] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B02" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_02'].'</b>'.$_nl;
		$_out .= '</td></tr><tr><td class="TP0SML_NL">';
		IF ( $_BV[B09]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B09] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B09" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_09'].'</b>'.$_nl;
		$_out .= '</td><td class="TP0SML_NL">';
		IF ( $_BV[B01]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[B01] = 0; }
		$_out .= '<INPUT TYPE=CHECKBOX NAME="B01" value="1"'.$_set.' border="0">'.$_nl;
		$_out .= $_sp.'<b>'.$_FF['l09_Field_01'].'</b>'.$_nl;
		$_out .= '</td></tr></table>';

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do list select field for: Client Domains
function cp_do_select_list_client_domain($aname, $avalue, $aret_flag=0) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT cd_id, cd_cl_domain FROM ".$_DBCFG['clients_domains']." ORDER BY cd_cl_domain ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form output
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0">'.$_LANG['_ADMIN']['Please_Select'].'</option>'.$_nl;

	# Process query results
		while(list($cd_id, $cd_cl_domain) = db_fetch_row($result)) {
			$_out .= '<option value="'.$cd_id.'"';
			IF ( $cd_id == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.str_pad($cd_id,4,'0',STR_PAD_LEFT).' - '.$cd_cl_domain.'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}

# Do select list for: product name/description/price display sequence on order form
function do_select_list_prod_display_sequence($aname, $avalue) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

	# Loop array and load list
		FOR ($i = 1; $i <= count($_CCFG['ORD_PROD_SEQUENCE']); $i++) {
			$_out .= '<option value="'.$i.'"';
			IF ( $i == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_CCFG['ORD_PROD_SEQUENCE'][$i].'</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;

	# return list
		return $_out;
}


# Do list select field for: Products
function cp_do_select_list_prods($aname, $avalue, $aret_flag=0) {
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
		$_out .= '<option value="0">'.$_LANG['_ADMIN']['Please_Select'].'</option>'.$_nl;

	# Process query results
		while(list($prod_id, $prod_name, $prod_desc) = db_fetch_row($result)) {
			$_out .= '<option value="'.$prod_id.'"';
			IF ( $prod_id == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.str_pad($prod_id,3,'0',STR_PAD_LEFT).' - '.$prod_name.' - '.$prod_desc.'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do list select field for: Vendors
function cp_do_select_list_vendors($aname, $avalue, $aret_flag=0) {
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
		$_out .= '<option value="0">'.$_LANG['_ADMIN']['Please_Select'].'</option>'.$_nl;

	# Process query results
		while(list($vendor_id, $vendor_name) = db_fetch_row($result)) {
			$_out .= '<option value="'.$vendor_id.'"';
			IF ( $vendor_id == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.str_pad($vendor_id,3,'0',STR_PAD_LEFT).' - '.$vendor_name.'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


#################################
# Do araay fill for: integers
function do_array_fill_integer( $amin, $amax ) {
	for ($i = $amin; $i <= $amax; $i++) {	$_list[$i] = $i;	}
	return $_list;
}


# Do parameter editor select list for: integer
function do_select_list_integer( $aname, $avalue, $amin, $amax, $aret_flag=0 ) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Load config array and sort
		$_tmp_array = do_array_fill_integer( $amin, $amax );

	# Build input field for form
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		FOR ($i = $amin; $i <= ($amax); $i++) {
			$_out .= '<option value="'.$_tmp_array[$i].'"';
			IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_tmp_array[$i].'</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do parameter editor select list for: client default pageview upon login
function do_select_list_default_page( $aname, $avalue, $aret_flag=0 ) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Load config array and sort
		$_tmp_array = do_array_fill_integer( 1, 6 );

	# Build input field for form
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		FOR ($i = 1; $i <= 6; $i++) {
			$_out .= '<option value="'.$_tmp_array[$i].'"';
			IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_DISPLAY'][$i].'</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do parameter editor select list for: display columns on client list
function do_select_list_client_display_columns( $aname, $avalue, $aret_flag=0 ) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Load config array and sort
		$_tmp_array = do_array_fill_integer( 1, 6 );

	# Build input field for form
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		FOR ($i = 1; $i <= 3; $i++) {
			$_out .= '<option value="'.$_tmp_array[$i].'"';
			IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_CCFG['CLIENT_LIST_DISPLAY_TEXT'][$i].'</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do parameter editor select list for: themes
function do_select_list_theme( $aname, $avalue, $aret_flag=0 ) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Get open themes directory and get list
		$idx = 0;
		$_themes	= Array();
		$_td 	= opendir($_CCFG['_PKG_PATH_BASE'].'coin_themes/');

        while (($_dir = @readdir($_td)) == TRUE) {
       		if ($_dir <> '.' && $_dir <> '..') {
				clearstatcache();
				$_themes[$idx] = $_dir;
				$idx++;
			}
			# echo '<br>'.$_dir;
		}

	# Load config array and sort
		$_tmp_array = $_themes;
		sort($_tmp_array);

	# Build input field for form
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		FOR ($i = 0; $i < count($_tmp_array); $i++) {
			$_out .= '<option value="'.$_tmp_array[$i].'"';
			IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_tmp_array[$i].'</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do parameter editor select list for: language
function do_select_list_lang( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Get open lang directory and get list
			$idx = 0;
			$_langs	= Array();
			$_td		= opendir($_CCFG['_PKG_PATH_BASE'].'coin_lang/');

	        while (($_dir = @readdir($_td)) == TRUE)
	        	{
	        		if ($_dir <> '.' && $_dir <> '..')
	        			{
							clearstatcache();
							$_langs[$idx] = $_dir;
							$idx++;
						}
					# echo '<br>'.$_dir;
				}

		# Load config array and sort
			$_tmp_array = $_langs;
			sort($_tmp_array);

		# Build input field for form
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			FOR ($i = 0; $i < count($_tmp_array); $i++)
				{
					$_out .= '<option value="'.$_tmp_array[$i].'"';
					IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
					$_out .= '>'.$_tmp_array[$i].'</option>'.$_nl;
				}
			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do invoice delivery method select list
function do_select_list_delivery_invoice($aname, $avalue)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

		# Loop array and load list
			FOR ($i = 0; $i < count($_CCFG[INV_DELIVERY]); $i++)
			{
				$_out .= '<option value="'.$_CCFG['INV_DELIVERY'][$i].'"';
				IF ( $_CCFG['INV_DELIVERY'][$i] == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_CCFG['INV_DELIVERY'][$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

			return $_out;
	}


# Do menu block item target
function do_select_list_mbi_target($aname, $avalue)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

		# Loop array and load list
			FOR ($i = 0; $i < count($_CCFG[MBI_LINK_TARGET]); $i++)
			{
				$_out .= '<option value="'.$i.'"';
				IF ( $i == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_CCFG['MBI_LINK_TARGET'][$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

			return $_out;
	}


# Do menu block item text contents type
function do_select_list_mbi_type($aname, $avalue)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

		# Loop array and load list
			FOR ($i = 0; $i < count($_CCFG[MBI_TEXT_TYPE]); $i++)
			{
				$_out .= '<option value="'.$i.'"';
				IF ( $i == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_CCFG['MBI_TEXT_TYPE'][$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

			return $_out;
	}


# Do list select field for: default username / domainname
function do_select_list_def_uname($aname, $avalue)
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build form output
			IF ( $avalue == '' ) { $avalue = $_CCFG['DOM_DEFAULT_USERNAME']; }
			$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="'.'username'.'"';
					IF ( $avalue == 'username' ) { $_out .= ' selected'; }
			$_out .= '>'.'username'.'</option>'.$_nl;
			$_out .= '<option value="'.'domain'.'"';
					IF ( $avalue == 'domain' ) { $_out .= ' selected'; }
			$_out .= '>'.'domain'.'</option>'.$_nl;
			$_out .= '<option value="'.'username@domain'.'"';
					IF ( $avalue == 'username@domaindomain' ) { $_out .= ' selected'; }
			$_out .= '>'.'username@domain'.'</option>'.$_nl;
			$_out .= '</select>'.$_nl;
			return $_out;
	}


# Do list select field for: login box display
function do_select_list_left_right_none($aname, $avalue) {
	# Dim some Vars
		global $_LANG, $_nl;

	# Build form output
		IF ( $avalue == '' ) { $avalue = 0; }
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0"';
				IF ( $avalue == '0' ) { $_out .= ' selected'; }
		$_out .= '>'.$_LANG['_ADMIN']['Column_None'].'</option>'.$_nl;
		$_out .= '<option value="1"';
				IF ( $avalue == '1' ) { $_out .= ' selected'; }
		$_out .= '>'.$_LANG['_ADMIN']['Column_Left'].'</option>'.$_nl;
		$_out .= '<option value="2"';
				IF ( $avalue == '2' ) { $_out .= ' selected'; }
		$_out .= '>'.$_LANG['_ADMIN']['Column_Right'].'</option>'.$_nl;
		$_out .= '</select>'.$_nl;

		return $_out;
}


# Do display for: login box display
function do_valtostr_left_right_none($avalue,$ret) {
	global $_LANG;
	IF ($avalue == '0') {
		$valstr = $_LANG['_ADMIN']['Column_None'];
	} ELSEIF ($avalue == '1') {
		$valstr = $_LANG['_ADMIN']['Column_Left'];
	} ELSE {
		$valstr = $_LANG['_ADMIN']['Column_Right'];
	}
	return $valstr;
}

# Do order product list sort order select list
function do_select_list_prod_sort_order($aname, $avalue)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

		# Loop array and load list
			FOR ($i = 0; $i < count($_CCFG[ORD_PROD_LIST_SORT]); $i++)
			{
				$_out .= '<option value="'.$i.'"';
				IF ( $i == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_CCFG['ORD_PROD_LIST_SORT'][$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

			return $_out;
	}


# Do parameter editor value field entry form
function do_parm_value_edit_field( $aparam, $aname, $avalue, $aret_flag=0 ) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		switch($aparam) {
			case '_DB_PKG_LANG':
				$_ret = do_select_list_lang( $aname, $avalue, 1 );
				break;
			case '_DB_PKG_THEME':
				$_ret = do_select_list_theme( $aname, $avalue, 1 );
				break;
			case '_MAX_MENU_BLK_POS':
				$_ret = do_select_list_integer( $aname, $avalue, 0, 30, 1 );
				break;
			case '_MAX_MENU_ITM_NO':
				$_ret = do_select_list_integer( $aname, $avalue, 0, 30, 1 );
				break;
			case '_NUMBER_FORMAT_ID':
				$_ret = do_select_list_integer( $aname, $avalue, 1, 5, 1 );
				break;
			case '_PKG_DATE_SERVER_OFFSET':
				$_ret = do_select_list_integer( $aname, $avalue, -24, 24, 1 );
				break;
			case 'CC_DOMAIN_EXP_IN_DAYS':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 90, 1 );
				break;
			case 'CC_SACC_EXP_IN_DAYS':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 90, 1 );
				break;
			case 'CLIENT_DEF_STATUS_NEW':
				$_ret = do_select_list_status_client($aname, $avalue);
				break;
			case 'DOM_DEFAULT_SERVER':
				$_ret = do_select_list_server_info($aname, $avalue, 1);
				break;
			case 'DOM_DEFAULT_USERNAME':
				$_ret = do_select_list_def_uname($aname, $avalue, 1);
				break;
			case 'HELPDESK_REPLY_EMAIL_LIMIT':
				$_ret = do_select_list_integer( $aname, $avalue, 1, 99, 1 );
				break;
			case 'INVC_DEL_MTHD_DEFAULT':
				$_ret = do_select_list_delivery_invoice($aname, $avalue, 1);
				break;
			case 'INVC_TAX_01_DEF_VAL':
				$_ret = '<INPUT class="PSML_NL" TYPE=TEXT NAME="'.$aname.'" SIZE=5 value="'.do_decimal_format($avalue).'" maxlength="5">'.$_nl;
				break;
			case 'INVC_TAX_02_DEF_VAL':
				$_ret = '<INPUT class="PSML_NL" TYPE=TEXT NAME="'.$aname.'" SIZE=5 value="'.do_decimal_format($avalue).'" maxlength="5">'.$_nl;
				break;
			case 'IPP_ARTICLES':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 50, 1 );
				break;
			case 'IPP_CLIENTS':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 50, 1 );
				break;
			case 'IPL_CLIENTS_ACCOUNT':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 50, 1 );
				break;
			case 'IPP_DOMAINS':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 50, 1 );
				break;
			case 'IPP_HELPDESK':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 50, 1 );
				break;
			case 'IPP_INVOICES':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 50, 1 );
				break;
			case 'IPP_MNEWS':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 50, 1 );
				break;
			case 'IPP_ORDERS':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 50, 1 );
				break;
			case 'IPP_PAGES':
				$_ret = do_select_list_integer( $aname, $avalue, 5, 50, 1 );
				break;
			case 'MC_ID_BILLING':
				$_ret = do_select_list_mail_contacts($aname, $avalue);
				break;
			case 'MC_ID_ORDERS':
				$_ret = do_select_list_mail_contacts($aname, $avalue);
				break;
			case 'MC_ID_SUPPORT':
				$_ret = do_select_list_mail_contacts($aname, $avalue);
				break;
			case 'MC_ID_WEBMASTER':
				$_ret = do_select_list_mail_contacts($aname, $avalue);
				break;
			case 'ORDER_POLICY_SI_ID_AUP':
				$_ret = do_select_list_siteinfo($aname, $avalue, 1);
				break;
			case 'ORDER_POLICY_SI_ID_BC':
				$_ret = do_select_list_siteinfo($aname, $avalue, 1);
				break;
			case 'ORDER_POLICY_SI_ID_PP':
				$_ret = do_select_list_siteinfo($aname, $avalue, 1);
				break;
			case 'ORDER_POLICY_SI_ID_TOS':
				$_ret = do_select_list_siteinfo($aname, $avalue, 1);
				break;
			case 'ORDERS_DEF_STATUS_NEW':
				$_ret = do_select_list_status_order($aname, $avalue);
				break;
			case 'AUTO_INV_STATUS':
				$_ret = do_select_list_status_invoice($aname, $avalue);
				break;
			case 'AUTO_INV_DELIVERY':
				$_ret = do_select_list_delivery_invoice($aname, $avalue);
				break;
			case 'ORDERS_FIELD_ENABLE_COR':
				$_ret = cp_do_edit_orders_field_enable($avalue, 'COR', 1);
				break;
			case 'ORDERS_FIELD_ENABLE_ORD':
				$_ret = cp_do_edit_orders_field_enable($avalue, 'ORD', 1);
				break;
			case 'ORDERS_FIELD_REQUIRE_COR':
				$_ret = cp_do_edit_orders_field_enable($avalue, 'COR', 1);
				break;
			case 'ORDERS_FIELD_REQUIRE_ORD':
				$_ret = cp_do_edit_orders_field_enable($avalue, 'ORD', 1);
				break;
			case 'ORDERS_PROD_LIST_SORT_ORDER':
				$_ret = do_select_list_prod_sort_order($aname, $avalue);
				break;
			case 'AUTO_INVC_BILL_CYCLE':
				$_ret = do_select_list_billing_cycle($aname, $avalue);
				break;
			case 'USE_LOGIN_MENUBOX':
				$_ret = do_select_list_left_right_none($aname, $avalue, 1);
				break;
			case 'CLIENT_VIEW_PAGE_UPON_LOGIN':
				$_ret = do_select_list_default_page($aname, $avalue, 1);
				break;
			case 'CLIENT_LIST_DISPLAY':
				$_ret = do_select_list_client_display_columns($aname, $avalue, 1);
				break;
			case 'ORDERS_PROD_DISPLAY_SEQUENCE':
				$_ret = do_select_list_prod_display_sequence($aname, $avalue);
				break;
			case 'HELPDESK_AUTO_IMPORT_DEFAULT_CATEGORY':
				$_ret = do_select_list_category($aname, $avalue, 1);
				break;
			case 'HELPDESK_AUTO_IMPORT_DEFAULT_STATUS':
				$_ret = do_select_list_status($aname, $avalue, 1);
				break;
			case 'HELPDESK_AUTO_IMPORT_DEFAULT_PRIORITY':
				$_ret = do_select_list_priority($aname, $avalue, 1);
				break;
			default:
				$_ret = '<INPUT class="PSML_NL" TYPE=TEXT NAME="'.$aname.'" SIZE=50 value="'.$avalue.'" maxlength="100">'.$_nl;
				break;
		}

		IF ( $aret_flag ) { return $_ret; } ELSE { echo $_ret; }
	}


# Do parameter editor value field display
function do_parm_value_display_field( $aparam, $avalue, $aret_flag=0 ) {
	# Dim some Vars:
		global $_CCFG, $_ACFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		switch($aparam) {
			case 'MC_ID_BILLING':
				$_ret = do_valtostr_mail_contacts($avalue);
				break;
			case 'MC_ID_ORDERS':
				$_ret = do_valtostr_mail_contacts($avalue);
				break;
			case 'MC_ID_SUPPORT':
				$_ret = do_valtostr_mail_contacts($avalue);
				break;
			case 'MC_ID_WEBMASTER':
				$_ret = do_valtostr_mail_contacts($avalue);
				break;
			case 'ORDER_POLICY_SI_ID_PP':
				$_ret = do_valtostr_siteinfo($avalue);
				break;
			case 'ORDER_POLICY_SI_ID_TOS':
				$_ret = do_valtostr_siteinfo($avalue);
				break;
			case 'ORDERS_PROD_LIST_SORT_ORDER':
				$_ret = $_CCFG['ORD_PROD_LIST_SORT'][$avalue];
				break;
			case 'ORDERS_PROD_DISPLAY_SEQUENCE':
				$_ret = $_CCFG['ORD_PROD_SEQUENCE'][$avalue];
				break;
			case 'USE_LOGIN_MENUBOX':
				$_ret = do_valtostr_left_right_none($avalue, 1);
				break;
			case 'CLIENT_VIEW_PAGE_UPON_LOGIN':
				$_ret = $_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_DISPLAY'][$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN']];
				break;
			case 'CLIENT_LIST_DISPLAY':
				$_ret = $_CCFG['CLIENT_LIST_DISPLAY_TEXT'][$_CCFG['CLIENT_LIST_DISPLAY']];
				break;
			case 'AUTO_INVC_BILL_CYCLE':
				$_ret = $_CCFG['INVC_BILL_CYCLE'][$_ACFG['AUTO_INVC_BILL_CYCLE']];
				break;
			default:
				$_ret = $avalue;
				break;
		}

		IF ( $aret_flag ) { return $_ret; } ELSE { echo $_ret; }
	}

?>
