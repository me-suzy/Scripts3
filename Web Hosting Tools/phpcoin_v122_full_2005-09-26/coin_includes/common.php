<?php

/**************************************************************
 * File: 		Common Functions- File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-09 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_base.php
 *
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("common.php", $_SERVER["PHP_SELF"])) {
		require_once ('session_set.php');
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01');
		exit;
	}


/**************************************************************
 *              Start Common Module Functions
**************************************************************/
# Common function to return permissions insufficient message
function do_no_permission_message () {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build Title String, Content String, and Footer Menu String
		$_tstr	= $_LANG['_BASE']['Permission_Title'];
		$_cstr	= '<center><br>'.$_LANG['_BASE']['Permission_Msg'].'<br><br></center>';
		$_mstr	= do_nav_link (getenv("HTTP_REFERER"), $_TCFG['_IMG_RETURN_M'],$_TCFG['_IMG_RETURN_M_MO'],'','');

	# Call block it function
		$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
		$_out .= '<br>'.$_nl;

	# Echo final output
		echo $_out;
}

# Common function for seelcting billing cycle
function do_select_list_billing_cycle($aname, $avalue)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

		# Loop array and load list
			FOR ($i = 0; $i < count($_CCFG[INVC_BILL_CYCLE]); $i++)
			{
				$_out .= '<option value="'.$i.'"';
				IF ( $i == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_CCFG['INVC_BILL_CYCLE'][$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

			return $_out;
	}


# Common function to return contact flood control message
function do_no_contact_flood_message () {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build Title String, Content String, and Footer Menu String
		$_tstr	= $_LANG['_BASE']['Flood_Contact_Title'];
		$_cstr	= '<center><br>'.$_LANG['_BASE']['Flood_Contact_Message'].'<br><br></center>';
		$_mstr	= do_nav_link (getenv("HTTP_REFERER"), $_TCFG['_IMG_RETURN_M'],$_TCFG['_IMG_RETURN_M_MO'],'','');

	# Call block it function
		$_out .= do_mod_block_it ($_tstr, $_cstr, '0', $_mstr, '1');
		$_out .= '<br>'.$_nl;

	# Echo final output
		echo $_out;
}


# Common function to format currency value using php
# number_format function
# @ http://www.php.net/manual/en/function.number-format.php
function do_currency_format ($avalue) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Example number 1234.56
	# 	Value = 1		Output: 1234
	# 	Value = 2		Output: 1234.56
	# 	Value = 3		Output: 1,234.56
	# 	Value = 4		Output: 1 234,56
	# 	Value = 5		Output: 1.234,56

	switch($_CCFG['_NUMBER_FORMAT_ID']) {
		case 1:
			return number_format($avalue, 0, '', '');
			break;
		case 2:
			return number_format($avalue, 2, '.', '');
			break;
		case 3:
			return number_format($avalue, 2, '.', ',');
			break;
		case 4:
			return number_format($avalue, 2, ',', ' ');
			break;
		case 5:
			return number_format($avalue, 2, ',', '.');
			break;
		default:
			return number_format($avalue, 2, '.', ',');
			break;
	}
}


# Common function to format decimal numbers (percent) using php
# number_format function
# @ http://www.php.net/manual/en/function.number-format.php
function do_decimal_format ($avalue) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Example number 1234.56
	# 	Value = 1		Output: 1234
	# 	Value = 2		Output: 1234.56
	# 	Value = 3		Output: 1,234.56
	# 	Value = 4		Output: 1 234,56
	# 	Value = 5		Output: 1.234,56

	$_format = 2;
	switch($_format) {
		case 1:
			return number_format($avalue, 0, '', '');
			break;
		case 2:
			return number_format($avalue, 2, '.', '');
			break;
		case 3:
			return number_format($avalue, 2, '.', ',');
			break;
		case 4:
			return number_format($avalue, 2, ',', ' ');
			break;
		case 5:
			return number_format($avalue, 2, ',', '.');
			break;
		default:
			return number_format($avalue, 2, '.', ',');
			break;
	}
}


# Do return string from value for: Title Row with search dropdown
function do_tstr_search_list($atitle) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Search form
		$_sform .= '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=cc&mode=search">'.$_nl;
		#	$_sform .= 'Please Select:'.$_sp.$_nl;
		$_sform .= '<select class="select_form" name="sw" size="1" value="Search" onchange="submit();">'.$_nl;
		$_sform .= '<option value="" selected>'.$_LANG['_BASE']['Search'].'</option>'.$_nl;
		$_sform .= '<option value="clients">'.$_LANG['_BASE']['Clients'].'</option>'.$_nl;
		IF ($_CCFG['DOMAINS_ENABLE']) {$_sform .= '<option value="domains">'.$_LANG['_BASE']['Domains'].'</option>'.$_nl;}
		IF ($_CCFG['HELPDESK_ENABLE']) {$_sform .= '<option value="helpdesk">'.$_LANG['_BASE']['HelpDesk'].'</option>'.$_nl;}
		IF ($_CCFG['INVOICES_ENABLE']) {$_sform .= '<option value="invoices">'.$_LANG['_BASE']['Invoices'].'</option>'.$_nl;}
		IF ($_CCFG['ORDERS_ENABLE']) {$_sform .= '<option value="orders">'.$_LANG['_BASE']['Orders'].'</option>'.$_nl;}
		IF ($_CCFG['INVOICES_ENABLE']) {$_sform .= '<option value="trans">'.$_LANG['_BASE']['Transactions'].'</option>'.$_nl;}
		$_sform .= '</select>'.$_nl;
		$_sform .= '</FORM>'.$_nl;

		$_tstr 	.= '<table width="100%" cellpadding="0" cellspacing="0"><tr class="BLK_IT_TITLE_TXT">';
		$_tstr 	.= '<td class="TP0MED_BL" valign="top">'.$_nl.$atitle.$_nl.'</td>'.$_nl;
		$_tstr	.= '<td class="TP0MED_BR" valign="top">'.$_nl.$_sform.$_nl.'</td>'.$_nl;
		$_tstr 	.= '</tr></table>';

	# Return form output
		return $_tstr;
}


# Create date edit list (year, month, day)
function do_date_edit_list ($aname, $avalue, $aret_flag=0) {
	# Requires $avalue to be unix timestamp format datetime
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Get datetime array from passed timestamp
		$_dt = dt_make_datetime_array ( $avalue );

	# Build list array for year, month, and day
	# Year (list now minus 10 and plus 10)
		$_dt_now	= getdate( dt_get_uts() );
		$_ymin		= $_dt_now[year]-10; $_ymax = $_dt_now[year]+10;
		for ($y = $_ymin; $y <= $_ymax; $y++) { $i++; $_list_year[$i] = $y; }

	# Build list for year:
		$_out .= '<select class="select_form" name="'.$aname.'_year'.'" size="1" value="'.$_dt[year].'">'.$_nl;
		for ($i = 0; $i <= count($_list_year); $i++) {
			$_out .= '<option value="'.$_list_year[$i].'"';
			IF ( $_list_year[$i] == $_dt[year] ) { $_out .= ' selected'; }
			$_out .= '>'.$_list_year[$i];
			$_out .= '</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;
		#	$_out .= $_sp.$_nl;

	# Month
		$_list_month[num][1] = '01'; $_list_month[text][1] = $_LANG['_BASE']['DS_Jan'];
		$_list_month[num][2] = '02'; $_list_month[text][2] = $_LANG['_BASE']['DS_Feb'];
		$_list_month[num][3] = '03'; $_list_month[text][3] = $_LANG['_BASE']['DS_Mar'];
		$_list_month[num][4] = '04'; $_list_month[text][4] = $_LANG['_BASE']['DS_Apr'];
		$_list_month[num][5] = '05'; $_list_month[text][5] = $_LANG['_BASE']['DS_May'];
		$_list_month[num][6] = '06'; $_list_month[text][6] = $_LANG['_BASE']['DS_Jun'];
		$_list_month[num][7] = '07'; $_list_month[text][7] = $_LANG['_BASE']['DS_Jul'];
		$_list_month[num][8] = '08'; $_list_month[text][8] = $_LANG['_BASE']['DS_Aug'];
		$_list_month[num][9] = '09'; $_list_month[text][9] = $_LANG['_BASE']['DS_Sep'];
		$_list_month[num][10] = '10'; $_list_month[text][10] = $_LANG['_BASE']['DS_Oct'];
		$_list_month[num][11] = '11'; $_list_month[text][11] = $_LANG['_BASE']['DS_Nov'];
		$_list_month[num][12] = '12'; $_list_month[text][12] = $_LANG['_BASE']['DS_Dec'];

	# Build list for month:
		$_out .= '<select class="select_form" name="'.$aname.'_month'.'" size="1" value="'.$_dt[month].'">'.$_nl;
		for ($i = 0; $i <= count($_list_month[num]); $i++) {
			$_out .= '<option value="'.$_list_month[num][$i].'"';
			IF ( $_list_month[num][$i] == $_dt[month] ) { $_out .= ' selected'; }
			$_out .= '>'.$_list_month[text][$i];
			$_out .= '</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;
		#	$_out .= $_sp.$_nl;

	# Day
		for ($i = 1; $i <= 31; $i++) {
			IF ( $i < 10 ) { $_list_day[$i] = '0'.$i; } ELSE { $_list_day[$i] = $i; }
		}

	# Build list for day:
		$_out .= '<select class="select_form" name="'.$aname.'_day'.'" size="1" value="'.$_dt[day].'">'.$_nl;
		for ($i = 0; $i <= count($_list_day); $i++) {
			$_out .= '<option value="'.$_list_day[$i].'"';
			IF ( $_list_day[$i] == $_dt[day] ) { $_out .= ' selected'; }
			$_out .= '>'.$_list_day[$i];
			$_out .= '</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;
		$_out .= $_sp.'(year-month-day)'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Create datetime edit list (year, month, day, 24hr, min, sec)
function do_datetime_edit_list ($aname, $avalue, $aret_flag=0) {
	# Requires $avalue to be unix timestamp format datetime
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Get datetime array from passed timestamp
		$_dt = dt_make_datetime_array ( $avalue );

	# Build list array for year, month, and day
	# Year (list now minus 10 and plus 10)
		$_dt_now	= getdate( dt_get_uts() );
		$_ymin		= $_dt_now[year]-10; $_ymax = $_dt_now[year]+10;
		for ($y = $_ymin; $y <= $_ymax; $y++) { $i++; $_list_year[$i] = $y; }

	# Build list for year:
		$_out .= '<select class="select_form" name="'.$aname.'_year'.'" size="1" value="'.$_dt[year].'">'.$_nl;
		for ($i = 0; $i <= count($_list_year); $i++) {
			$_out .= '<option value="'.$_list_year[$i].'"';
			IF ( $_list_year[$i] == $_dt[year] ) { $_out .= ' selected'; }
			$_out .= '>'.$_list_year[$i];
			$_out .= '</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;
		#	$_out .= $_sp.$_nl;

	# Month
		$_list_month[num][1] = '01'; $_list_month[text][1] = $_LANG['_BASE']['DS_Jan'];
		$_list_month[num][2] = '02'; $_list_month[text][2] = $_LANG['_BASE']['DS_Feb'];
		$_list_month[num][3] = '03'; $_list_month[text][3] = $_LANG['_BASE']['DS_Mar'];
		$_list_month[num][4] = '04'; $_list_month[text][4] = $_LANG['_BASE']['DS_Apr'];
		$_list_month[num][5] = '05'; $_list_month[text][5] = $_LANG['_BASE']['DS_May'];
		$_list_month[num][6] = '06'; $_list_month[text][6] = $_LANG['_BASE']['DS_Jun'];
		$_list_month[num][7] = '07'; $_list_month[text][7] = $_LANG['_BASE']['DS_Jul'];
		$_list_month[num][8] = '08'; $_list_month[text][8] = $_LANG['_BASE']['DS_Aug'];
		$_list_month[num][9] = '09'; $_list_month[text][9] = $_LANG['_BASE']['DS_Sep'];
		$_list_month[num][10] = '10'; $_list_month[text][10] = $_LANG['_BASE']['DS_Oct'];
		$_list_month[num][11] = '11'; $_list_month[text][11] = $_LANG['_BASE']['DS_Nov'];
		$_list_month[num][12] = '12'; $_list_month[text][12] = $_LANG['_BASE']['DS_Dec'];

	# Build list for month:
		$_out .= '<select class="select_form" name="'.$aname.'_month'.'" size="1" value="'.$_dt[month].'">'.$_nl;
		FOR ($i = 0; $i <= count($_list_month[num]); $i++) {
			$_out .= '<option value="'.$_list_month[num][$i].'"';
			IF ( $_list_month[num][$i] == $_dt[month] ) { $_out .= ' selected'; }
			$_out .= '>'.$_list_month[text][$i];
			$_out .= '</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;
		#	$_out .= $_sp.$_nl;

	# Day
		FOR ($i = 1; $i <= 31; $i++) {
			IF ( $i < 10 ) { $_list_day[$i] = '0'.$i; } ELSE { $_list_day[$i] = $i; }
		}

	# Build list for day:
		$_out .= '<select class="select_form" name="'.$aname.'_day'.'" size="1" value="'.$_dt[day].'">'.$_nl;
		FOR ($i = 0; $i <= count($_list_day); $i++) {
			$_out .= '<option value="'.$_list_day[$i].'"';
			IF ( $_list_day[$i] == $_dt[day] ) { $_out .= ' selected'; }
			$_out .= '>'.$_list_day[$i];
			$_out .= '</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;
		$_out .= $_sp.'('.$_LANG['_BASE']['DS_Format_Date'].')<p>'.$_nl;

	# 24-Hour
		FOR ($i = 0; $i <= 23; $i++) {
			IF ( $i < 10 ) { $_list_hour[$i] = '0'.$i; } ELSE { $_list_hour[$i] = $i; }
		}

	# Build list for hour:
		$_out .= '<select class="select_form" name="'.$aname.'_hour'.'" size="1" value="'.$_dt[hour].'">'.$_nl;
		FOR ($i = 0; $i < count($_list_hour); $i++) {
			$_out .= '<option value="'.$_list_hour[$i].'"';
			IF ( $_list_hour[$i] == $_dt[hour] ) { $_out .= ' selected'; }
			$_out .= '>'.$_list_hour[$i];
			$_out .= '</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;
		$_out .= '<b>:</b>'.$_nl;

	# Minute
		FOR ($i = 0; $i <= 59; $i++) {
			IF ( $i < 10 ) { $_list_minute[$i] = '0'.$i; } ELSE { $_list_minute[$i] = $i; }
		}

	# Build list for minute:
		$_out .= '<select class="select_form" name="'.$aname.'_minute'.'" size="1" value="'.$_dt[minute].'">'.$_nl;
		FOR ($i = 0; $i < count($_list_minute); $i++) {
			$_out .= '<option value="'.$_list_minute[$i].'"';
			IF ( $_list_minute[$i] == $_dt[minute] ) { $_out .= ' selected'; }
			$_out .= '>'.$_list_minute[$i];
			$_out .= '</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;
		$_out .= '<b>:</b>'.$_nl;

	# Second
		FOR ($i = 0; $i <= 59; $i++) {
			IF ( $i < 10 ) { $_list_second[$i] = '0'.$i; } ELSE { $_list_second[$i] = $i; }
		}

	# Build list for second:
		$_out .= '<select class="select_form" name="'.$aname.'_second'.'" size="1" value="'.$_dt[second].'">'.$_nl;
		FOR ($i = 0; $i < count($_list_second); $i++) {
			$_out .= '<option value="'.$_list_second[$i].'"';
			IF ( $_list_second[$i] == $_dt[second] ) { $_out .= ' selected'; }
			$_out .= '>'.$_list_second[$i];
			$_out .= '</option>'.$_nl;
		}
		$_out .= '</select>'.$_nl;
		$_out .= $_sp.'('.$_LANG['_BASE']['DS_Format_Time'].')'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do return string from value for: No or Yes Options
function do_valtostr_no_yes($avalue) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build form output
		IF ( $avalue == 1 ) { return $_LANG['_BASE']['Yes']; }
		ELSE				{ return $_LANG['_BASE']['No']; }
}


# Do list select field for: No or Yes Options
function do_select_list_no_yes($aname, $avalue, $aret_flag=0) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build form output
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0"';
		IF ( $avalue == 0 ) { $_out .= ' selected'; }
		$_out .= '>'.$_LANG['_BASE']['No'].'</option>'.$_nl;
		$_out .= '<option value="1"';
		IF ( $avalue == 1 ) { $_out .= ' selected'; }
		$_out .= '>'.$_LANG['_BASE']['Yes'].'</option>'.$_nl;
		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do return string from value for: Off or On Options
function do_valtostr_off_on($avalue) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build form output
		IF ( $avalue == 1 ) { return $_LANG['_BASE']['On']; }
		ELSE				{ return $_LANG['_BASE']['Off']; }
}


# Do list select field for: Off or On Options
function do_select_list_off_on($aname, $avalue, $aret_flag=0) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build form output
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0"';
		IF ( $avalue == 0 ) { $_out .= ' selected'; }
		$_out .= '>'.$_LANG['_BASE']['Off'].'</option>'.$_nl;
		$_out .= '<option value="1"';
		IF ( $avalue == 1 ) { $_out .= ' selected'; }
		$_out .= '>'.$_LANG['_BASE']['On'].'</option>'.$_nl;
		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Get site mail contacts info (core??)
function get_contact_info( $amc_id ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select and execute
		$query		= "SELECT mc_id, mc_name, mc_email FROM ".$_DBCFG['mail_contacts'];
		$query		.= " WHERE mc_id = $amc_id ORDER BY mc_id ASC";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get value and set return
		while(list($mc_id, $mc_name, $mc_email) = db_fetch_row($result)) {
			$_cinfo['c_id']		= $mc_id;
			$_cinfo['c_name']	= $mc_name;
			$_cinfo['c_email']	= $mc_email;
		}
		return $_cinfo;
}


# Get admin contact info
function get_contact_admin_info( $aca_admin_id ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select and execute
		$query		= "SELECT admin_id, admin_name_first, admin_name_last, admin_user_name, admin_email FROM ".$_DBCFG['admins'];
		$query		.= " WHERE admin_id = $aca_admin_id ORDER BY admin_name_last ASC, admin_name_first ASC";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get value and set return
		while(list($admin_id, $admin_name_first, $admin_name_last, $admin_user_name, $admin_email) = db_fetch_row($result)) {
			$_cinfo['admin_id']			= $admin_id;
			$_cinfo['admin_name_first']	= $admin_name_first;
			$_cinfo['admin_name_last']	= $admin_name_last;
			$_cinfo['admin_user_name']	= $admin_user_name;
			$_cinfo['admin_email']		= $admin_email;
			$_cinfo['c_id']				= $admin_id;
			$_cinfo['c_name']			= $admin_name_first.' '.$admin_name_last;
			$_cinfo['c_email']			= $admin_email;
		}

		return $_cinfo;
}

############## MTP Info Calls start ################
# Get MTP array for: Client Info
function get_mtp_client_info( $acl_id ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Include language file (must be after parameter load to use them)
		require_once ( $_CCFG['_PKG_PATH_LANG'].'lang_clients.php');
		IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_clients_override.php')) {
			require_once($_CCFG['_PKG_PATH_LANG'].'lang_clients_override.php');
		}

	# Set Query for select.
		$query	.= "SELECT *";
		$query	.= " FROM ".$_DBCFG['clients'];

	# Set to logged in Client ID if user to avoid seeing other client id's
		IF ( !$_SEC['_sadmin_flg'] && $_SEC['_suser_flg'] ) {
			$query .= " WHERE ".$_DBCFG['clients'].".cl_id = ".$_SEC['_suser_id'];
		} ELSE	{
			$query .= " WHERE ".$_DBCFG['clients'].".cl_id = ".$acl_id;
		}

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Check Return and process results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {

			# Set data array
				$_clinfo					= $row;
				$_clinfo['numrows']			= $numrows;
				$_clinfo['cl_found']		= 1;
				$_clinfo['cl_join_ts']		= dt_make_datetime ( $row[cl_join_ts], $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] );

				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_01'].$row[cl_id].$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_02'].dt_make_datetime ( $row[cl_join_ts], $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_03'].$row[cl_user_name].$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_04'].$row[cl_email].$_nl;
				$_clinfo['cl_info'] .= '-------------------'.$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_05'].$row[cl_company].$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_06'].$row[cl_name_first].' '.$row[cl_name_last].$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_07'].$row[cl_addr_01].$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_08'].$row[cl_addr_02].$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_09'].$row[cl_city].$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_10'].$row[cl_state_prov].$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_11'].$row[cl_country].$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_12'].$row[cl_zip_code].$_nl;
				$_clinfo['cl_info'] .= $_LANG['_CLIENTS']['CL_EMAIL_13'].$row[cl_phone];
			}
		}

		return $_clinfo;
}


# Get MTP array for: Helpdesk Trouble Ticket Info
function get_mtp_hdtt_info( $ahd_tt_id ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Get helpdesk ticket information
		$query	.= "SELECT *";
		$query	.= " FROM ".$_DBCFG['helpdesk'].", ".$_DBCFG['clients'];
		$query	.= " WHERE ".$_DBCFG['helpdesk'].".hd_tt_cl_id = ".$_DBCFG['clients'].".cl_id";
		$query	.= " AND ".$_DBCFG['helpdesk'].".hd_tt_id = ".$ahd_tt_id;

	# Set to logged in Client ID if not admin to avoid seeing other client ticket id's
		IF ( !$_SEC['_sadmin_flg'] && $_SEC['_suser_flg'] ) {
			$query .= " AND ".$_DBCFG['helpdesk'].".hd_tt_cl_id = ".$_SEC['_suser_id'];
		}

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Check Return and process results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {

			# Set data array
				$_ttinfo						= $row;
				$_ttinfo['numrows']				= $numrows;
				$_ttinfo['hd_tt_id']			= $row[hd_tt_id];
				$_ttinfo['hd_tt_cl_id']			= $row[hd_tt_cl_id];
				$_ttinfo['hd_tt_cl_email']		= $row[hd_tt_cl_email];
				$_ttinfo['hd_tt_time_stamp']	= dt_make_datetime ( $row[hd_tt_time_stamp], $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] );
				$_ttinfo['hd_tt_priority']		= $row[hd_tt_priority];
				$_ttinfo['hd_tt_category']		= $row[hd_tt_category];
				$_ttinfo['hd_tt_subject']		= do_stripslashes($row[hd_tt_subject]);

				#	$_ttinfo['hd_tt_message'] 		.= '------------------------------'.$_nl;
				$_ttinfo['hd_tt_message'] 		= do_stripslashes($row[hd_tt_message]).$_nl;
				#	$_ttinfo['hd_tt_message'] 		.= '------------------------------'.$_nl;

				$_ttinfo['hd_tt_cd_id']			= $row[hd_tt_cd_id];
				$_ttinfo['hd_tt_url']			= $row[hd_tt_url];
				$_ttinfo['hd_tt_status']		= $row[hd_tt_status];
				$_ttinfo['hd_tt_closed']		= do_valtostr_open_closed($row[hd_tt_closed]);
				$_ttinfo['hd_tt_rating']		= do_valtostr_rate_ticket($row[hd_tt_rating]);

				$_ttinfo['cl_company']			= $row[cl_company];
				$_ttinfo['cl_name_first']		= $row[cl_name_first];
				$_ttinfo['cl_name_last']		= $row[cl_name_last];
				$_ttinfo['cl_email']			= $row[cl_email];
				$_ttinfo['cl_user_name']		= $row[cl_user_name];

			# Get domain name if id exists.
				IF ( $row[hd_tt_cd_id] > 0 && $_CCFG['DOMAINS_ENABLE']) {
					$query_cd = ""; $result_cd= ""; $numrows_cd = 0;
					$query_cd	.= "SELECT dom_domain";
					$query_cd	.= " FROM ".$_DBCFG['domains'];
					$query_cd	.= " WHERE ".$_DBCFG['domains'].".dom_id = ".$row[hd_tt_cd_id];

				# Do select
					$result_cd	= db_query_execute($query_cd);
					$numrows_cd	= db_query_numrows($result_cd);

				# Get value and set return
					while(list($dom_domain) = db_fetch_row($result_cd)) { $_ttinfo['cd_cl_domain'] = $dom_domain; }
				}
			}
		}

		return $_ttinfo;
}


# Get MTP array for: Helpdesk Trouble Ticket Items / Messages Info
function get_mtp_hdti_info( $ahd_tt_id ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Include language file (must be after parameter load to use them)
		require_once ( $_CCFG['_PKG_PATH_LANG'].'lang_helpdesk.php');
		IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_helpdesk_override.php')) {
			require_once($_CCFG['_PKG_PATH_LANG'].'lang_helpdesk_override.php');
		}

	# Get helpdesk ticket information
		$query = ""; $result= ""; $numrows = 0;
		$query	.= "SELECT *";
		$query	.= " FROM ".$_DBCFG['helpdesk'].", ".$_DBCFG['helpdesk_msgs'];
		$query	.= " WHERE ".$_DBCFG['helpdesk'].".hd_tt_id = ".$_DBCFG['helpdesk_msgs'].".hdi_tt_id";
		$query	.= " AND ".$_DBCFG['helpdesk'].".hd_tt_id = ".$ahd_tt_id;

	# Set to logged in Client ID if not admin to avoid seeing other client ticket id's
		IF ( !$_SEC['_sadmin_flg'] && $_SEC['_suser_flg'] ) {
			$query .= " AND ".$_DBCFG['helpdesk'].".hd_tt_cl_id = ".$_SEC['_suser_id'];
		}

	# Check config for limit of messages.
		IF ( !$_CCFG['HELPDESK_REPLY_EMAIL_SET_LIMIT'] ) {
			$query	.= " ORDER BY ".$_DBCFG['helpdesk_msgs'].".hdi_tt_time_stamp ASC";
			$_MTP['messages_included'] = $_LANG['_HDESK']['HD_EMAIL_MSGS_NO_LIMIT_STRING'];
		} ELSE {
			$query	.= " ORDER BY ".$_DBCFG['helpdesk_msgs'].".hdi_tt_time_stamp DESC";
			$query	.= " LIMIT ".$_CCFG['HELPDESK_REPLY_EMAIL_LIMIT'];
			$_MTP['messages_included'] = $_LANG['_HDESK']['HD_EMAIL_MSGS_LIMIT_STRING'];
		}

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Check Return and process results
		IF ( $numrows ) {
			$_tt_msgs_cnt = 0;
			while ($row = db_fetch_array($result)) {
				$_tt_msgs_cnt = $_tt_msgs_cnt + 1;

			# Set data array
				$_tiinfo['numrows']			= $numrows;

			# Get name of user or admin who replied
				IF ( $row[hdi_tt_cl_id] != 0 ) {
					$_name = get_user_name($row[hdi_tt_cl_id], 'user');
				} ELSE IF ( $row[hdi_tt_ad_id] != 0 ) {
					IF ( $_CCFG['HELPDESK_ADMIN_REVEAL_ENABLE'] == 1 ) {
						$_name = get_user_name($row[hdi_tt_ad_id], 'admin');
					} ELSE {
						$_sinfo = get_contact_info($_CCFG['MC_ID_SUPPORT']);
						$_name = $_sinfo['c_name'];
					}
				}

			# Parse out space in name
				$_str_search	= '&nbsp;';
				$_str_replace	= ' ';
				$_name	= str_replace( $_str_search, $_str_replace, $_name );

				IF ( $_tt_msgs_cnt > 1 ) { $_tiinfo['tt_msgs'] .= $_nl; }

				$_tiinfo['tt_msgs'] .= '------------------------------'.$_nl;
				$_tiinfo['tt_msgs'] .= $_LANG['_HDESK']['HD_EMAIL_01'].$_name.$_nl;
				$_tiinfo['tt_msgs'] .= $_LANG['_HDESK']['HD_EMAIL_02'].dt_make_datetime ( $row[hdi_tt_time_stamp], $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).$_nl;
				$_tiinfo['tt_msgs'] .= $_LANG['_HDESK']['HD_EMAIL_03'].$_nl;
				$_tiinfo['tt_msgs'] .= '----------------'.$_nl;
				$_tiinfo['tt_msgs'] .= do_stripslashes($row[hdi_tt_message]).$_nl;
				#	$_tiinfo['tt_msgs'] .= '----------------'.$_nl;

				#	IF ( $_tt_msgs_cnt < $numrows_msgs ) { $_tiinfo['tt_msgs'] .= $_nl; }
			}
		}

		return $_tiinfo;
}

# Get MTP array for: Invoice Info
function get_mtp_invoice_info( $ainvc_id ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Get invoice information (invoice header, client information)
		$query	.= "SELECT *";
		$query	.= " FROM ".$_DBCFG['invoices'].", ".$_DBCFG['clients'];

		$query	.= " WHERE ".$_DBCFG['invoices'].".invc_cl_id = ".$_DBCFG['clients'].".cl_id";
		$query	.= " AND ".$_DBCFG['invoices'].".invc_id = ".$ainvc_id;

	# Set to logged in Client ID if not admin to avoid seeing other client invoice id's
		IF ( !$_SEC['_sadmin_flg'] && $_SEC['_suser_flg'] ) {
			$query	.= " AND ".$_DBCFG['invoices'].".invc_cl_id = ".$_SEC['_suser_id'];
			$query	.= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG['INV_STATUS'][1]."'";

		# Check show pending enable flag
			IF ( !$_CCFG['INVC_SHOW_CLIENT_PENDING'] ) {
				$query .= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG['INV_STATUS'][4]."'";
			}
		}

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Check Return and process results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {

			# Set data array
				$_ininfo						= $row;
				$_ininfo['numrows']				= $numrows;
				$_ininfo['invc_id']				= $row[invc_id];
				$_ininfo['invc_status']			= $row[invc_status];
				$_ininfo['invc_deliv_method']	= $row[invc_deliv_method];
				$_ininfo['invc_delivered']		= do_valtostr_no_yes($row[invc_delivered]);
				$_ininfo['invc_subtotal_cost']	= $_CCFG['_CURRENCY_PREFIX'].''.do_currency_format ( $row[invc_subtotal_cost] ).'  '.$_CCFG['_CURRENCY_SUFFIX'];
				$_ininfo['invc_tax_01_percent']	= $row[invc_tax_01_percent];
				$_ininfo['invc_tax_01_amount']	= $_CCFG['_CURRENCY_PREFIX'].''.do_currency_format ( $row[invc_tax_01_amount] ).'  '.$_CCFG['_CURRENCY_SUFFIX'];
				$_ininfo['invc_tax_02_percent']	= $row[invc_tax_02_percent];
				$_ininfo['invc_tax_02_amount']	= $_CCFG['_CURRENCY_PREFIX'].''.do_currency_format ( $row[invc_tax_02_amount] ).'  '.$_CCFG['_CURRENCY_SUFFIX'];
				$_ininfo['invc_total_cost']		= $_CCFG['_CURRENCY_PREFIX'].''.do_currency_format ( $row[invc_total_cost] ).'  '.$_CCFG['_CURRENCY_SUFFIX'];
				$_ininfo['invc_total_paid']		= $_CCFG['_CURRENCY_PREFIX'].''.do_currency_format ( $row[invc_total_paid] ).'  '.$_CCFG['_CURRENCY_SUFFIX'];
				$_ininfo['invc_ts']				= dt_make_datetime ( $row[invc_ts], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] );
				$_ininfo['invc_ts_due']			= dt_make_datetime ( $row[invc_ts_due], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] );
				IF ( $row[invc_status] == $_CCFG['INV_STATUS'][3] ) {
					$_ininfo['invc_ts_paid']	= dt_make_datetime ( $row[invc_ts_paid], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] );
				} ELSE {
					$_ininfo['invc_ts_paid']	= '';
				}

				$_ininfo['invc_bill_cycle']		= $_CCFG['INVC_BILL_CYCLE'][$row[invc_bill_cycle]];
				$_ininfo['invc_terms']			= do_stripslashes($row[invc_terms]);
				$_ininfo['invc_pay_link']		= do_stripslashes($row[invc_pay_link]);
			}
		}

		return $_ininfo;
}


# Get MTP array for: Invoice Items Info
function get_mtp_invcitem_info( $ainvc_id ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Include language file (must be after parameter load to use them)
		require_once ( $_CCFG['_PKG_PATH_LANG'].'lang_invoices.php');
		IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_invoices_override.php')) {
			require_once($_CCFG['_PKG_PATH_LANG'].'lang_invoices_override.php');
		}

	# Get invoice information (invoice header, client information)
		$query	.= "SELECT *";
		$query	.= " FROM ".$_DBCFG['invoices'].", ".$_DBCFG['invoices_items'];
		$query	.= " WHERE ".$_DBCFG['invoices'].".invc_id = ".$_DBCFG['invoices_items'].".ii_invc_id";
		$query	.= " AND ".$_DBCFG['invoices'].".invc_id = ".$ainvc_id;
		$query	.= " ORDER BY ".$_DBCFG['invoices_items'].".ii_item_no ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Check Return and process results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {
				$_invc_items_cnt = $_invc_items_cnt + 1;

			# Set data array
				$_iiinfo['numrows']					= $numrows;

				$_ii_item_no[$_invc_items_cnt]		= $row[ii_item_no];
				$_ii_item_name[$_invc_items_cnt]	= $row[ii_item_name];
				$_ii_item_desc[$_invc_items_cnt]	= $row[ii_item_desc];
				$_ii_item_cost[$_invc_items_cnt]	= $row[ii_item_cost];

				IF ($_CCFG[SINGLE_LINE_EMAIL_INVOICE_ITEMS]) {

				// Do single-line invoice items
					$itemno = sprintf("%-6s", $row[ii_item_no]);
					$itemname = sprintf("%-12s", $row[ii_item_name]);
					$itemdesc = sprintf("%-40s", $row[ii_item_desc]);
					$itemcost = $_CCFG['_CURRENCY_PREFIX'].sprintf("%8.2f", $row[ii_item_cost]).' '.$_CCFG['_CURRENCY_SUFFIX'];
					$_iiinfo['iitems'] .= $itemno . ' ' . $itemname . ' ' . $itemdesc . ' ' . $itemcost . $_nl;
				} ELSE {

				// Do multi-line invoice items (default)
					IF ( $_invc_items_cnt > 1 ) { $_MTP['iitems'] .= $_nl; }
					$_iiinfo['iitems'] .= $_LANG['_INVCS']['INV_EMAIL_01'].$row[ii_item_no].$_nl;
					$_iiinfo['iitems'] .= $_LANG['_INVCS']['INV_EMAIL_02'].$row[ii_item_name].$_nl;
					$_iiinfo['iitems'] .= $_LANG['_INVCS']['INV_EMAIL_03'].$row[ii_item_desc].$_nl;
					$_iiinfo['iitems'] .= $_LANG['_INVCS']['INV_EMAIL_04'].$_CCFG['_CURRENCY_PREFIX'].''.do_currency_format ( $row[ii_item_cost] ).'  '.$_CCFG['_CURRENCY_SUFFIX'];
					IF ( $_invc_items_cnt < $numrows ) { $_iiinfo['iitems'] .= $_nl; }
				}
			}
		}

		return $_iiinfo;
}


# Get MTP array for: Invoice Transaction Info
function get_mtp_trans_info( $ait_id ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select.
		$query	.= "SELECT *";
		$query	.= " FROM ".$_DBCFG['invoices_trans'];
		$query	.= " WHERE ".$_DBCFG['invoices_trans'].".it_id = ".$ait_id;

		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Check Return and process results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {

			# Set data array
				$_itinfo				= $row;
				$_itinfo['numrows']		= $numrows;
				$_itinfo['it_id']		= str_pad($row['it_id'],5,'0',STR_PAD_LEFT);
				$_itinfo['it_ts']		= dt_make_datetime ( $row['it_ts'], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] );
				$_itinfo['it_invc_id']	= $row[it_invc_id];
				$_itinfo['it_type']		= $_CCFG['INV_TRANS_TYPE'][$row['it_type']];
				$_itinfo['it_origin']	= $_CCFG['INV_TRANS_ORIGIN'][$row['it_origin']];
				$_itinfo['it_desc']		= do_stripslashes($row[it_desc]);
				$_itinfo['it_amount']	= do_currency_format ( ($row['it_amount'] * 1) );
			}
		}

		return $_itinfo;
}


# Get MTP array for: Order Info
function get_mtp_order_info( $aord_id ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Include language file (must be after parameter load to use them)
		require_once ( $_CCFG['_PKG_PATH_LANG'].'lang_orders.php');
		IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_orders_override.php')) {
			require_once($_CCFG['_PKG_PATH_LANG'].'lang_orders_override.php');
		}

	# Get order information
		$query	.= "SELECT *";
		$query	.= " FROM ".$_DBCFG['orders'].", ".$_DBCFG['clients'].", ".$_DBCFG['vendors'];
		$query	.= ", ".$_DBCFG['products'];

		$query	.= " WHERE ".$_DBCFG['orders'].".ord_cl_id = ".$_DBCFG['clients'].".cl_id";
		$query	.= " AND ".$_DBCFG['orders'].".ord_vendor_id = ".$_DBCFG['vendors'].".vendor_id";
		$query	.= " AND ".$_DBCFG['orders'].".ord_prod_id = ".$_DBCFG['products'].".prod_id";
		$query	.= " AND ".$_DBCFG['orders'].".ord_id = ".$aord_id;

	# Set to logged in Client ID if not admin to avoid seeing other client order id's
		IF ( !$_SEC['_sadmin_flg'] && $_SEC['_suser_flg'] ) {
			$query .= " AND ".$_DBCFG['orders'].".ord_cl_id = ".$_SEC['_suser_id'];
		}

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Check Return and process results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {

			# Set data array
				$_orinfo				= $row;
				$_orinfo['numrows']		= $numrows;
				$_orinfo['ord_id']		= $row[ord_id];
				$_orinfo['cl_id']       = $row[ord_cl_id];
				$_orinfo['ord_ts']		= dt_make_datetime ( $row[ord_ts], $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] );
				$_orinfo['ord_status']	= $row[ord_status];
				$_orinfo['ord_domain']	= $row[ord_domain];
				$_orinfo['vendor_name']	= $row[vendor_name];
				$_orinfo['prod_name']	= $row[prod_name];
				$_orinfo['prod_desc']	= $row[prod_desc];

				$_orinfo['order'] .= $_LANG['_ORDERS']['ORD_EMAIL_01'].$row[ord_id].$_nl;
				$_orinfo['order'] .= $_LANG['_ORDERS']['ORD_EMAIL_02'].dt_make_datetime ( $row[ord_ts], $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).$_nl;
				$_orinfo['order'] .= $_LANG['_ORDERS']['ORD_EMAIL_03'].$row[ord_status].$_nl;
				$_orinfo['order'] .= '---------------'.$_nl;
				$_orinfo['order'] .= $_LANG['_ORDERS']['ORD_EMAIL_04'].$row[prod_name].$_nl;
				$_orinfo['order'] .= $_LANG['_ORDERS']['ORD_EMAIL_05'].$row[prod_desc].$_nl;
				$_orinfo['order'] .= $_LANG['_ORDERS']['ORD_EMAIL_06'].$_CCFG['_CURRENCY_PREFIX'].''.do_currency_format ( $row[ord_unit_cost] ).'  '.$_CCFG['_CURRENCY_SUFFIX'];
			}
		}

		return $_orinfo;
}
############## MTP Info Calls end ################


# Get client contact info
function get_contact_client_info( $acc_cl_id ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select and execute
		$query		= "SELECT cl_id, cl_name_first, cl_name_last, cl_user_name, cl_email FROM ".$_DBCFG['clients'];
		$query		.= " WHERE cl_id = $acc_cl_id ORDER BY cl_name_last ASC, cl_name_first ASC";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get value and set return
		while(list($cl_id, $cl_name_first, $cl_name_last, $cl_user_name, $cl_email) = db_fetch_row($result)) {
			$_cinfo['cl_id']			= $cl_id;
			$_cinfo['cl_name_first']	= $cl_name_first;
			$_cinfo['cl_name_last']		= $cl_name_last;
			$_cinfo['cl_user_name']		= $cl_user_name;
			$_cinfo['cl_email']			= $cl_email;
			$_cinfo['c_id']				= $cl_id;
			$_cinfo['c_name']			= $cl_name_first.' '.$cl_name_last;
			$_cinfo['c_email']			= $cl_email;
		}

		return $_cinfo;
}


# Get client contact info for additional email addresses
function get_contact_client_info_alias($alias_id, $idtype) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select and execute
		$query		 = "SELECT contacts_id, contacts_cl_id, contacts_name_first, contacts_name_last, contacts_email FROM ".$_DBCFG['clients_contacts'];
		IF ($idtype) {
			$query		.= " WHERE contacts_cl_id = $alias_id";
		} ELSE {
			$query		.= " WHERE contacts_id = $alias_id";
		}
		$query		.= " ORDER BY contacts_name_last, contacts_name_first ASC";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get value and set return
		$x=0;
		while(list($contact_id, $cl_id, $cl_name_first, $cl_name_last, $cl_email) = db_fetch_row($result)) {
			$x++;
			$_cinfo[$x]['cl_id']			= $acc_cl_id;
			$_cinfo[$x]['cl_name_first']	= $cl_name_first;
			$_cinfo[$x]['cl_name_last']		= $cl_name_last;
			$_cinfo[$x]['cl_user_name']		= '';
			$_cinfo[$x]['cl_email']			= $cl_email;
			$_cinfo[$x]['c_id']				= $cl_id;
			$_cinfo[$x]['c_name']			= $cl_name_first.' '.$cl_name_last;
			$_cinfo[$x]['c_email']			= $cl_email;
		}

		return $_cinfo;
}


# Do select list for: Icons
function do_select_list_icon($aname, $avalue, $aret_flag=0) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query	= "";	$result	= "";	$numrows = 0;

	# Set Query for select.
		$query		= "SELECT icon_id, icon_name, icon_desc, icon_filename FROM ".$_DBCFG['icons']." ORDER BY icon_name ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0">'.$_LANG['_BASE']['Please_Select'].'</option>'.$_nl;

	# Process query results
		while(list($icon_id, $icon_name, $icon_desc, $icon_filename) = db_fetch_row($result)) {
			$_out .= '<option value="'.$icon_id.'"';
			IF ( $icon_id == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.str_pad($icon_id,3,'0',STR_PAD_LEFT).' - '.$icon_name.'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 *           Start Common Module Functions phpCOIN
**************************************************************/
# Note- this return is just boolean checked (0 or >0) so this could be
# swapped out with code in do_get_domain_id to serve both functions.
# Was written before cd_id field added.
function do_domain_exist_check($adomain) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# return 'OK' if Domains Disabled
		IF (!$_CCFG['DOMAINS_ENABLE']) {return 0;}

	# Return 0 if domain name "NONE"
		IF (strtolower($adomain) == "none") {return 0;}

	# Set Query for select
		$query		= "SELECT dom_cl_id FROM ".$_DBCFG['domains']." WHERE dom_domain = '$adomain' ORDER BY dom_cl_id ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

		return $numrows;
}


function do_get_domain_id($adomain) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select
		$query		= "SELECT dom_id FROM ".$_DBCFG['domains']." WHERE dom_domain = '$adomain' ORDER BY dom_cl_id ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get Value
		$_ret = 0;
		while(list($dom_id) = db_fetch_row($result)) { $_ret = $dom_id; }

		return $_ret;
}


function do_get_client_domain_id($adomain, $acl_id) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select
		$query		= "SELECT dom_id FROM ".$_DBCFG['domains'];
		$query		.= " WHERE dom_domain = '$adomain' AND dom_cl_id = '$acl_id'";
		$query		.= " ORDER BY dom_cl_id ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get Value
		$_ret = 0;
		while(list($dom_id) = db_fetch_row($result)) { $_ret = $dom_id; }

		return $_ret;
}


# Do get server name for passed si_id
function do_get_server_name($asi_id) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Check for vlaid argument:
		IF ($asi_id > 0 ) {

		# Set Query and do select.
			$query	= "SELECT si_name FROM ".$_DBCFG['server_info']." WHERE si_id =".$asi_id;
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Check return and Process query results
			IF ( !$numrows ) {
				$_ret = 'error';
			} ELSE {
				while(list($si_name) = db_fetch_row($result)) { $_ret = $si_name; }
			}
		} ELSE {
			$_ret = 'error';
		}

	# Set return
		return $_ret;
}


function do_get_max_cl_id ( ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query and select for max field value.
		$query		= "SELECT max(cl_id) FROM ".$_DBCFG['clients'];
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get Max Value
		while(list($_max_cl_id) = db_fetch_row($result)) { $max_cl_id = $_max_cl_id; }

	# Check / Set Value for return
		IF ( !$max_cl_id) {
			return $_CCFG['BASE_CLIENT_ID'];
		} ELSE {
			return $max_cl_id;
		}
}


function do_get_max_ord_id ( ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query and select for max field value.
		$query		= "SELECT max(ord_id) FROM ".$_DBCFG['orders'];
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get Max Value
		while(list($_max_ord_id) = db_fetch_row($result)) { $max_ord_id = $_max_ord_id; }

	# Check / Set Value for return
		IF ( !$max_ord_id) {
			return $_CCFG['BASE_ORDER_ID'];
		} ELSE {
			return $max_ord_id;
		}
}

function do_get_max_domain_id ( ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query and select for max field value.
		$query		= "SELECT max(dom_id) FROM ".$_DBCFG['domains'];
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get Max Value
		while(list($_max_dom_id) = db_fetch_row($result)) { $max_dom_id = $_max_dom_id; }

	# Check / Set Value for return
		IF ( !$max_dom_id) {
			return 1;
		} ELSE {
			return $max_dom_id;
		}
}

function do_get_max_invc_id ( ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query and select for max field value.
		$query		= "SELECT max(invc_id) FROM ".$_DBCFG['invoices'];
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get Max Value
		while(list($_max_invc_id) = db_fetch_row($result)) { $max_invc_id = $_max_invc_id; }

	# Check / Set Value for return
		IF ( !$max_invc_id) {
			return $_CCFG['BASE_INVOICE_ID'];
		} ELSE {
			return $max_invc_id;
		}
}


function do_calc_invc_values ( $adata ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Array passed / and returned (?=a passed, ?=i return)
		# $adata['invc_id']
		# $?data['invc_total_cost']		- To be calcd
		# $?data['invc_subtotal_cost']	- To be calcd
		# $?data['invc_tax_01_percent']
		# $?data['invc_tax_01_amount']	- To be calcd
		# $?data['invc_tax_02_percent']
		# $?data['invc_tax_02_amount']	- To be calcd
		# $?data['invc_tax_autocalc']

	# Check / Set Incoming Data
		IF ( !$adata['invc_tax_01_percent'])	{ $adata['invc_tax_01_percent'] = '0.00'; }
		IF ( !$adata['invc_tax_02_percent'])	{ $adata['invc_tax_02_percent'] = '0.00'; }

		$idata['invc_tax_autocalc']		= $adata['invc_tax_autocalc'];
		$idata['invc_tax_01_percent']	= $adata['invc_tax_01_percent'];
		$idata['invc_tax_01_amount']	= $adata['invc_tax_01_amount'];
		$idata['invc_tax_02_percent']	= $adata['invc_tax_02_percent'];
		$idata['invc_tax_02_amount']	= $adata['invc_tax_02_amount'];

	# Build query and select by invoice id
		$query		= "SELECT * FROM ".$_DBCFG['invoices_items'];
		$query		.= " WHERE ".$_DBCFG['invoices_items'].".ii_invc_id = ".$adata['invc_id'];
		$query		.= " ORDER BY ii_item_no ASC";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Process query results and get values
		$_cost_subtotal_all = 0; $_cost_subtotal_01 = 0; $_cost_subtotal_02 = 0;
		$_tax_subtotal_all = 0; $_tax_subtotal_01 = 0; $_tax_subtotal_02 = 0;
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {

			# If prices include tax, then remove applicable taxes BEFORE we add taxes.
				IF ($_CCFG['PRICES_INCLUDE_TAXES']) {
					$done=0;

				# Start with tax2 because it's the last one applied
				# If Tax 2 is applied, remove it
					IF ($row['ii_apply_tax_02'] == 1) {
						IF ($row['ii_calc_tax_02_pb']) {

						# If Tax2 piggybacked, remove it
							$row['ii_item_cost'] = $row['ii_item_cost'] / (1+($idata['invc_tax_02_percent'] / 100));
						} ELSE {

						# If NOT piggybacked, remove both taxes together and then set "done" flag
							$row['ii_item_cost'] = $row['ii_item_cost'] / (1+(($idata['invc_tax_02_percent'] + $idata['invc_tax_01_percent']) / 100));
							$done++;
						}
					}

				# If Tax1 is applied and we are NOT done, remove Tax1
					IF (($row['ii_apply_tax_01'] == 1) && (!$done)) {
						$row['ii_item_cost'] = $row['ii_item_cost'] / (1+($idata['invc_tax_01_percent'] / 100));
					}
				}

			# Now calculate our taxes as normal
				$_cost_subtotal_all = $_cost_subtotal_all + $row['ii_item_cost'];
				IF ( $row['ii_apply_tax_01'] == 1 ) {
					$_cost_subtotal_01	= $_cost_subtotal_01 + $row['ii_item_cost'];
					$_tax_subtotal_01	= $_tax_subtotal_01 + round(($row['ii_item_cost'] * ( $idata['invc_tax_01_percent'] / 100)), 2);
				}

				IF ( $row['ii_apply_tax_02'] == 1 ) {
					IF ( $row['ii_calc_tax_02_pb'] != 1 ) {
						$_cost_subtotal_02	= $_cost_subtotal_02 + $row['ii_item_cost'];
						$_tax_subtotal_02	= $_tax_subtotal_02 + round(($row['ii_item_cost'] * ( $idata['invc_tax_02_percent'] / 100)), 2);
					} ELSE {
						$_tax_01			= round(($row['ii_item_cost'] * ( $idata['invc_tax_01_percent'] / 100)), 2);
						$_tax_02_amount		= $row['ii_item_cost'] + $_tax_01;
						$_cost_subtotal_02	= $_cost_subtotal_02 + $_tax_02_amount;
						$_tax_subtotal_02	= $_tax_subtotal_02 + round(($_tax_02_amount * ( $idata['invc_tax_02_percent'] / 100)), 2);
					}
				}
			}
		}

	# Calc tax amounts on total cost
		$_tax_subtotal_01_all = round(($_cost_subtotal_all * ( $idata['invc_tax_01_percent'] / 100)), 2);
		$_tax_subtotal_02_all = round(($_cost_subtotal_all * ( $idata['invc_tax_02_percent'] / 100)), 2);

	# Check for tax enabled and set zero if not.
		IF ( $_CCFG['INVC_TAX_01_ENABLE'] != 1 ) {
			$_tax_subtotal_01_all = 0.00; $_tax_subtotal_01 = 0.00; $idata['invc_tax_01_amount'] = 0.00;
		}
		IF ( $_CCFG['INVC_TAX_02_ENABLE'] != 1 ) {
			$_tax_subtotal_02_all = 0.00; $_tax_subtotal_02 = 0.00; $idata['invc_tax_02_amount'] = 0.00;
		}

	# Set return values based on various config items
		IF ( $idata['invc_tax_autocalc'] == 1 ) {
			IF ( $_CCFG['INVC_TAX_BY_ITEM'] == 1 ) {
				$idata['invc_tax_01_amount'] 	= ( round($_tax_subtotal_01, 2) );
				$idata['invc_tax_02_amount'] 	= ( round($_tax_subtotal_02, 2) );
				$idata['invc_subtotal_cost'] 	= ( round($_cost_subtotal_all, 2) );
				$idata['invc_total_cost']		= ( $idata['invc_subtotal_cost'] + $idata['invc_tax_01_amount'] + $idata['invc_tax_02_amount'] );
			} ELSE {
				$idata['invc_tax_01_amount'] 	= ( round($_tax_subtotal_01_all, 2) );
				$idata['invc_tax_02_amount'] 	= ( round($_tax_subtotal_02_all, 2) );
				$idata['invc_subtotal_cost'] 	= ( round($_cost_subtotal_all, 2) );
				$idata['invc_total_cost']		= ( $idata['invc_subtotal_cost'] + $idata['invc_tax_01_amount'] + $idata['invc_tax_02_amount'] );
			}
		} ELSE {
			$idata['invc_tax_01_amount'] 	= ( round($adata['invc_tax_01_amount'], 2) );
			$idata['invc_tax_02_amount'] 	= ( round($adata['invc_tax_02_amount'], 2) );
			$idata['invc_subtotal_cost'] 	= ( round($_cost_subtotal_all, 2) );
			$idata['invc_total_cost']		= ( $idata['invc_subtotal_cost'] + $idata['invc_tax_01_amount'] + $idata['invc_tax_02_amount'] );
		}

	# Check / Outgoing Data
		IF ( !$idata['invc_total_cost'])		{ $idata['invc_total_cost'] = '0.00'; }
		IF ( !$idata['invc_subtotal_cost'])		{ $idata['invc_subtotal_cost'] = '0.00'; }
		IF ( !$idata['invc_tax_01_percent'])	{ $idata['invc_tax_01_percent'] = '0.00'; }
		IF ( !$idata['invc_tax_01_amount'])		{ $idata['invc_tax_01_amount'] = '0.00'; }
		IF ( !$idata['invc_tax_02_percent'])	{ $idata['invc_tax_02_percent'] = '0.00'; }
		IF ( !$idata['invc_tax_02_amount'])		{ $idata['invc_tax_02_amount'] = '0.00'; }

	# Check / Set Value for return
		return $idata;
}


function do_get_invc_values ( $ainvc_id ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select and execute
		$query = "SELECT * FROM ".$_DBCFG['invoices'];
		$query .= " WHERE invc_id = '$ainvc_id'";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Process query results (assumes one returned row above- need to verify)
		while ($row = db_fetch_array($result)) {

		# Rebuild Data Array with returned record
			$idata['invc_id']				= $row[invc_id];
			$idata['invc_ts']				= $row[invc_ts];
			$idata['invc_ts_due']			= $row[invc_ts_due];
			$idata['invc_ts_paid']			= $row[invc_ts_paid];
			$idata['invc_total_cost']		= $row[invc_total_cost];
			$idata['invc_total_paid']		= $row[invc_total_paid];
			$idata['invc_subtotal_cost']	= $row[invc_subtotal_cost];
			$idata['invc_tax_01_percent']	= $row[invc_tax_01_percent];
			$idata['invc_tax_01_amount']	= $row[invc_tax_01_amount];
			$idata['invc_tax_02_percent']	= $row[invc_tax_02_percent];
			$idata['invc_tax_02_amount']	= $row[invc_tax_02_amount];
			$idata['invc_tax_autocalc']		= $row[invc_tax_autocalc];
		}

	# Check / Outgoing Data
		IF ( !$idata['invc_total_cost'])		{ $idata['invc_total_cost'] = '0.00'; }
		IF ( !$idata['invc_total_paid'])		{ $idata['invc_total_paid'] = '0.00'; }
		IF ( !$idata['invc_subtotal_cost'])		{ $idata['invc_subtotal_cost'] = '0.00'; }
		IF ( !$idata['invc_tax_01_percent'])	{ $idata['invc_tax_01_percent'] = '0.00'; }
		IF ( !$idata['invc_tax_01_amount'])		{ $idata['invc_tax_01_amount'] = '0.00'; }
		IF ( !$idata['invc_tax_02_percent'])	{ $idata['invc_tax_02_percent'] = '0.00'; }
		IF ( !$idata['invc_tax_02_amount'])		{ $idata['invc_tax_02_amount'] = '0.00'; }
		IF ( $idata['invc_tax_autocalc'] == '')	{ $idata['invc_tax_autocalc'] = '1'; }

	# Check / Set Value for return
		return $idata;
}


function do_set_invc_values ( $ainvc_id ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Get invoice values now (need tax percent for recalc
		$idata_now = do_get_invc_values ( $ainvc_id );

	# Get invoice calc new values
		$idata_now['invc_id'] = $ainvc_id;
		$idata_new = do_calc_invc_values ( $idata_now );

	# Do update
		$query 		= "UPDATE ".$_DBCFG['invoices'];
		$query 		.= " SET invc_total_cost = '$idata_new[invc_total_cost]', invc_subtotal_cost = '$idata_new[invc_subtotal_cost]'";
		$query 		.= ", invc_tax_01_amount = '$idata_new[invc_tax_01_amount]', invc_tax_02_amount = '$idata_new[invc_tax_02_amount]'";
		$query 		.= " WHERE invc_id = $ainvc_id";
		$result		= db_query_execute($query) OR DIE("Unable to complete request");
		$numrows	= db_query_affected_rows ();

	# Update Invoice Debit Transaction
		$q_it = ""; $r_it = ""; $n_it = 0;
		$q_it 	= "UPDATE ".$_DBCFG['invoices_trans'];
		$q_it 	.= " SET it_amount = '$idata_new[invc_total_cost]'";
		$q_it 	.= " WHERE it_invc_id = $ainvc_id AND it_type = 0";
		$r_it	= db_query_execute($q_it) OR DIE("Unable to complete request");
		$n_it	= db_query_affected_rows ();

	# Set return
		return $numrows;
}


function do_get_invc_cl_balance ( $ainvc_cl_id, $ainvc_id=0 ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Array returned
		# $idata['total_cost']	- Sum Invoice Cost Column
		# $idata['total_paid']	- Sum Invoice Paid Column
		# $idata['net_balance']	- (Cost*-1)+Paid

	# Set Query for select and execute
		$query = "SELECT * ";
		$query .= " FROM ".$_DBCFG['invoices_trans'].", ".$_DBCFG['invoices'].", ".$_DBCFG['clients'];
		$query .= " WHERE ".$_DBCFG['invoices_trans'].".it_invc_id = ".$_DBCFG['invoices'].".invc_id";
		$query .= " AND ".$_DBCFG['invoices'].".invc_cl_id = ".$_DBCFG['clients'].".cl_id";

	# Block out draft [1], and void [5]
		$query .= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG['INV_STATUS'][1]."'";
		$query .= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG['INV_STATUS'][5]."'";

	# Check show pending enable flag if not admin
		IF ( !$_SEC['_sadmin_flg'] ) {
			IF ( !$_CCFG['INVC_SHOW_CLIENT_PENDING'] ) {
				$query .= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG['INV_STATUS'][4]."'";
			}
		}

	# If specific client requested
		IF ( $ainvc_cl_id ) { $query .= " AND invc_cl_id = '$ainvc_cl_id'"; }

	# If specific invoice requested
		IF ( $ainvc_id ) { $query .= " AND invc_id = '$ainvc_id'"; }

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Process query results (assumes one returned row above)
		$_total_cost = 0; $_total_paid = 0;
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {

			# Summ debits and credits
				IF ( $row[it_type] == 0 ) {
					$_total_cost = $_total_cost + $row[it_amount];
				} ELSE {
					$_total_paid = $_total_paid + $row[it_amount];
				}
			}
		}

	# Set return array
		$idata['total_cost']	= round($_total_cost,2);
		$idata['total_paid']	= round($_total_paid,2);
		$idata['net_balance']	= round( (($idata['total_cost'])+$idata['total_paid']*-1),2);

	# Check / Set Value for return
		return $idata;
}


function do_get_invc_PTD ( $ainvc_id ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Set Query for select and execute
		$query = ""; $result = ""; $numrows = 0;
		$query 		= "SELECT sum(it_amount) as PTD FROM ".$_DBCFG['invoices_trans'];
		$query 		.= " WHERE it_invc_id = '$ainvc_id' AND it_type != 0";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);
		while(list($PTD) = db_fetch_row($result)) { $_PTD = $PTD; }

	# Check / Set Value for return
		return $_PTD;
}


function do_set_trans_values ( $atdata ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_SERVER, $_nl, $_sp;

	# Update Invoice Debit Transaction
		$q_it = ""; $r_it = ""; $n_it = 0;
		$q_it 	= "UPDATE ".$_DBCFG['invoices_trans']." SET ";
		IF ( $atdata[it_ts] != '' ) { $q_it .= "it_ts = '$atdata[it_ts]'"; $c = ','; }
		IF ( $atdata[it_invc_id] != '' ) { $q_it .= $c."it_invc_id = '$atdata[it_invc_id]'"; $c = ','; }
		IF ( $atdata[it_type] != '' ) { $q_it .= $c."it_type = '$atdata[it_type]'"; $c = ','; }
		IF ( $atdata[it_origin] != '' ) { $q_it .= $c."it_origin = '$atdata[it_origin]'"; $c = ','; }
		IF ( $atdata[it_desc] != '' ) { $q_it .= $c."it_desc = '$atdata[it_desc]'"; $c = ','; }
		IF ( $atdata[it_amount] != '' ) { $q_it .= $c."it_amount = '$atdata[it_amount]'"; $c = ','; }

		IF ( $atdata[it_type] == 0 ) {
			$q_it .= " WHERE it_invc_id = $atdata[it_invc_id] AND it_type = 0";
		} ELSE {
			$q_it .= " WHERE it_id = $atdata[it_id]";
		}
		$r_it	= db_query_execute($q_it) OR DIE("Unable to complete request");
		$n_it	= db_query_affected_rows ();

	# Set return
		return $n_it;
}


function do_get_trans_values ( $ait_id ) {
	# Set Query for select and execute
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_SERVER, $_nl, $_sp;

		$query = "SELECT * FROM ".$_DBCFG['invoices_trans'];
		$query .= " WHERE it_id = '$ait_id'";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Process query results (assumes one returned row above- need to verify)
		while ($row = db_fetch_array($result)) {

		# Rebuild Data Array with returned record
			$tdata['numrows']		= $numrows;
			$tdata['it_id']			= $row[it_id];
			$tdata['it_ts']			= $row[it_ts];
			$tdata['it_invc_id']	= $row[it_invc_id];
			$tdata['it_type']		= $row[it_type];
			$tdata['it_origin']		= $row[it_origin];
			$tdata['it_desc']		= $row[it_desc];
			$tdata['it_amount']		= $row[it_amount];
		}

	# Check / Set Value for return
		return $tdata;
}


function do_set_invc_status ( $ainvc_id, $astatus ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query_is = ""; $result_is = ""; $numrows_is = 0;

	# Do update
		$query_is 	= "UPDATE ".$_DBCFG['invoices']." SET invc_status = '$astatus'";
		$query_is 	.= " WHERE invc_id = $ainvc_id";
		$result_is	= db_query_execute($query_is) OR DIE("Unable to complete request");
		$numrows_is	= db_query_affected_rows ();

	# Set return
		return $numrows_is;
}


function do_set_invc_delivered ( $ainvc_id, $avalue ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query_id = ""; $result_id = ""; $numrows_id = 0;

	# Do update
		$query_id 	= "UPDATE ".$_DBCFG['invoices']." SET invc_delivered = '$avalue'";
		$query_id 	.= " WHERE invc_id = $ainvc_id";
		$result_id	= db_query_execute($query_id) OR DIE("Unable to complete request");
		$numrows_id	= db_query_affected_rows ();

	# Set return
		return $numrows_id;
}


function do_set_invc_recurr_proc ( $ainvc_id, $avalue ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query_id = ""; $result_id = ""; $numrows_id = 0;

	# Do update
		$query_id 	= "UPDATE ".$_DBCFG['invoices']." SET invc_recurr_proc = '$avalue'";
		$query_id 	.= " WHERE invc_id = $ainvc_id";
		$result_id	= db_query_execute($query_id) OR DIE("Unable to complete request");
		$numrows_id	= db_query_affected_rows ();

	# Set return
		return $numrows_id;
}


function do_get_max_invc_item_no ( $ainvc_id ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query and select for max field value.
		$query		= "SELECT max(ii_item_no) FROM ".$_DBCFG['invoices_items'];
		$query		.= " WHERE ".$_DBCFG['invoices_items'].".ii_invc_id = ".$ainvc_id;
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Get Max Value
		while(list($_max_item_no) = db_fetch_row($result)) { $max_item_no = $_max_item_no; }

	# Check / Set Value for return
		IF ( !$max_item_no) { return 0; } ELSE { return $max_item_no; }
}


# Do client status select list
function do_select_list_status_client ($aname, $avalue) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query	= "";	$result	= "";	$numrows = 0;

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

	# Loop array and load list
		FOR ($i = 1; $i < count($_CCFG[CL_STATUS]); $i++) {
			$_out .= '<option value="'.$_CCFG['CL_STATUS'][$i].'"';
			IF ( $_CCFG['CL_STATUS'][$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_CCFG['CL_STATUS'][$i].'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		return $_out;
}


# Do order status select list
function do_select_list_status_order($aname, $avalue) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query	= "";	$result	= "";	$numrows = 0;

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

	# Loop array and load list
		FOR ($i = 0; $i < count($_CCFG[ORD_STATUS]); $i++) {
			$_out .= '<option value="'.$_CCFG['ORD_STATUS'][$i].'"';
			IF ( $_CCFG['ORD_STATUS'][$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_CCFG['ORD_STATUS'][$i].'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		return $_out;
}


function do_select_list_status_invoice($aname, $avalue) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query	= "";	$result	= "";	$numrows = 0;

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

	# Loop array and load list
		FOR ($i = 0; $i < count($_CCFG[INV_STATUS]); $i++) {
			$_out .= '<option value="'.$_CCFG['INV_STATUS'][$i].'"';
			IF ( $_CCFG['INV_STATUS'][$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_CCFG['INV_STATUS'][$i].'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		return $_out;
}


# Do domain status select list
function do_select_list_domain_status($aname, $avalue) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

	# Loop array and load list
		FOR ($i = 0; $i < count($_CCFG['DOM_STATUS']); $i++) {
			$_out .= '<option value="'.$i.'"';
			IF ( $i == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_CCFG['DOM_STATUS'][$i].'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		return $_out;
}


# Do domain type select list
function do_select_list_domain_type($aname, $avalue) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

	# Loop array and load list
		FOR ($i = 0; $i < count($_CCFG['DOM_TYPE']); $i++) {
			$_out .= '<option value="'.$i.'"';
			IF ( $i == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_CCFG['DOM_TYPE'][$i].'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		return $_out;
}


# Do select list for: Mail Contacts
function do_select_list_mail_contacts($aname, $avalue, $astatus=0) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select.
		$query		= "SELECT mc_id, mc_name, mc_email, mc_status";
		$query		.= " FROM ".$_DBCFG['mail_contacts'];
		IF ( $astatus == 1 ) { $query .= " WHERE mc_status = 1"; }
		$query		.= " ORDER BY mc_name ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build Form row
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

	# Process query results
		while(list($mc_id, $mc_name, $mc_email, $mc_status) = db_fetch_row($result)) {
			$_out .= '<option value="'.$mc_id.'"';
			IF ( $mc_id == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$mc_name.'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;
		return $_out;
}


# Do return string from value for: Mail Contacts
function do_valtostr_mail_contacts($avalue) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT mc_id, mc_name";
		$query	.= " FROM ".$_DBCFG['mail_contacts'];
		$query	.= " WHERE mc_id = $avalue";
		$query	.= " ORDER BY mc_id ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Process query results
		while(list($mc_id, $mc_name) = db_fetch_row($result)) { $_out = $mc_id.'- '.$mc_name; }

		return $_out;
}


# Do select list for: Clients Additional Emails
function do_select_list_clients_additional_emails($avalue,$aname) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0; $_out = '';

	# Set Query for select.
		$query	= "SELECT contacts_id, contacts_cl_id, contacts_name_first, contacts_name_last, contacts_email FROM ".$_DBCFG['clients_contacts'];
		IF ($avalue) {$query .= " WHERE contacts_cl_id=$avalue";}
		$query .= " ORDER BY contacts_name_last, contacts_name_first ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

		IF ($numrows) {
		# Process query results to list individual clients
			while(list($contacts_id, $contacts_cl_id, $contacts_name_first, $contacts_name_last, $contacts_email) = db_fetch_row($result)) {
		    	$i++;
				$_out .= '<option value="'.'alias|'.$contacts_id.'">';
				$_out .= str_pad($avalue,3,'0',STR_PAD_LEFT).' * '.$contacts_name_last.',&nbsp;'.$contacts_name_first.' - '.$aname.'</option>'.$_nl;
			}
			return $_out;
		} ELSE {
		    return '';
		}
}

# Do select list for: Clients
function do_select_list_clients($aname, $avalue, $ashowalloption=0) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT cl_id, cl_name_first, cl_name_last, cl_user_name FROM ".$_DBCFG['clients']." ORDER BY cl_name_last ASC, cl_name_first ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form field output
		$_out .= '<select class="select_form" name="'.$aname.'" size="1">'.$_nl;
		$_out .= '<option value="0">'.$_LANG['_BASE']['Please_Select'].'</option>'.$_nl;

		IF ( $ashowalloption ) {

		# Show All Servers
			$srv_query = ""; $srv_result= ""; $srv_numrows = 0;
			$srv_query	= "SELECT si_id, si_name FROM ".$_DBCFG['server_info']." ORDER BY si_name ASC";
			$srv_result	= db_query_execute($srv_query);
			$srv_numrows = db_query_numrows($srv_result);
			while(list($si_id, $si_name) = db_fetch_row($srv_result)) {
				$_out .= '<option value="server|'.$si_id.'">'.$_LANG['_MAIL']['Clients_On'].' '.$si_name.'</option>'.$_nl;
			}

		# Show All Groups
			$_out .= '<option value="group|1">'.$_LANG['_BASE']['User_Groups_01'].'</option>'.$_nl;
			$_out .= '<option value="group|2">'.$_LANG['_BASE']['User_Groups_02'].'</option>'.$_nl;
			$_out .= '<option value="group|3">'.$_LANG['_BASE']['User_Groups_03'].'</option>'.$_nl;
			$_out .= '<option value="group|4">'.$_LANG['_BASE']['User_Groups_04'].'</option>'.$_nl;
			$_out .= '<option value="group|5">'.$_LANG['_BASE']['User_Groups_05'].'</option>'.$_nl;
			$_out .= '<option value="group|6">'.$_LANG['_BASE']['User_Groups_06'].'</option>'.$_nl;
			$_out .= '<option value="group|7">'.$_LANG['_BASE']['User_Groups_07'].'</option>'.$_nl;
			$_out .= '<option value="group|8">'.$_LANG['_BASE']['User_Groups_08'].'</option>'.$_nl;

		# Show "All Clients"
			$_out .= '<option value="-1"';
			IF ( $avalue == -1 ) { $_out .= ' selected'; } {
				$_out .= '>'.$_LANG['_BASE']['All_Active_Clients'].'</option>'.$_nl;
			}
		}

	# Process query results to list individual clients
		while(list($cl_id, $cl_name_first, $cl_name_last, $cl_user_name) = db_fetch_row($result)) {
			$_out .= '<option value="'.$cl_id.'"';
			IF ( $cl_id == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.str_pad($cl_id,3,'0',STR_PAD_LEFT).' - '.$cl_name_last.',&nbsp;'.$cl_name_first.' - '.$cl_user_name.'</option>'.$_nl;

			IF ( $ashowalloption ) {
			# Grab any additional emails for this client, so they are all together in the list
				$more = do_select_list_clients_additional_emails($cl_id,$cl_user_name);
				If ($more) {
					$_out .= '<option value="contacts|'.$cl_id.'">'.str_pad($cl_id,3,'0',STR_PAD_LEFT).' '.$_LANG['_BASE']['All'].' - '.$cl_name_last.',&nbsp;'.$cl_name_first.' - '.$cl_user_name.'</option>'.$_nl;
					$_out .= $more;
				}
			}
		}
		$_out .= '</select>'.$_nl;
		return $_out;
}


# Do select list for: Clients
# To allow Admin to enter trouble ticket for client
function do_select_list_clients_emails($aname) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT cl_id, cl_name_first, cl_name_last, cl_user_name, cl_email FROM ".$_DBCFG['clients'];
		IF ($aname) {$query .= " WHERE cl_email='".$aname."'";}
		$query .= " ORDER BY cl_name_last ASC, cl_name_first ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form field output
		$_out .= '<select class="select_form" name="hd_tt_cl_email" size="1" value="'.$avalue.'">'.$_nl;

	# Process query results
		$itsselected =0;
		while(list($cl_id, $cl_name_first, $cl_name_last, $cl_user_name, $cl_email) = db_fetch_row($result)) {
			$_out .= '<option value="'.$cl_email.'"';

		# Grab first one as "selected"
			IF ( !$itsselected ) { $_out .= ' selected'; $itsselected++;}

		# Build the line
			$_out .= '>'.str_pad($cl_id,3,'0',STR_PAD_LEFT).' - '.$cl_name_first.'&nbsp;'.$cl_name_last.' - '.$cl_user_name.'</option>'.$_nl;

		# Grab any additional emails for this client, so they are all together in the list
			$_out .= do_select_list_clients_additional_emails($cl_id,$cl_user_name);
		}

		$_out .= '</select>'.$_nl;
		return $_out;
}


# Do select list for: Vendors
function do_select_list_vendors($aname, $avalue) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT vendor_id, vendor_name FROM ".$_DBCFG['vendors']." ORDER BY vendor_id ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build Form row
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

	# Process query results
		while(list($vendor_id, $vendor_name) = db_fetch_row($result)) {
			$_out .= '<option value="'.$vendor_id.'"';
			IF ( $vendor_id == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$vendor_name.'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;
		return $_out;
}


# Do select list for: Countriess
function do_select_list_countries($aname, $avalue) {
	# Dim some Vars
		global $_Countries, $_nl, $_sp;
		$ListSize = sizeof($_Countries);

	# Build Form row
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

	# Loop through countries list array
		FOR ($i=0; $i< $ListSize; $i++) {
			$_out .= '<option value="'.$_Countries[$i].'"';
			IF ( $_Countries[$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_Countries[$i].'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

	# return results
		return $_out;
}


# Do select list for: Products (for Invoices and Orders Editors- Admin only so show all)
function do_select_list_products($aname, $avalue) {
	# Get security vars
		$_SEC = get_security_flags ();

	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result = ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT prod_id, prod_name, prod_desc";
		$query	.= " FROM ".$_DBCFG['products'];
		$query	.= " ORDER BY prod_id ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build Form row
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

	# Process query results
		while(list($prod_id, $prod_name, $prod_desc) = db_fetch_row($result)) {
			$_out .= '<option value="'.$prod_id.'"';
			IF ( $prod_id == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$prod_name.' - '.$prod_desc.'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;
		return $_out;
}


# Do select list for: Server Info
function do_select_list_server_info($aname, $avalue, $aret_flag=0) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT si_id, si_name, si_ip, si_cp_url, si_cp_url_port FROM ".$_DBCFG['server_info']." ORDER BY si_id ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form output
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0">'.$_LANG['_BASE']['Please_Select'].'</option>'.$_nl;

	# Process query results
		while(list($si_id, $si_name, $si_ip, $si_cp_url, $si_cp_url_port) = db_fetch_row($result)) {
			$_out .= '<option value="'.$si_id.'"';
			IF ( $si_id == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.str_pad($si_id,3,'0',STR_PAD_LEFT).' - '.$si_name.'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do return string from value for: Server Info
function do_valtostr_server_info($avalue) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT si_id, si_name";
		$query	.= " FROM ".$_DBCFG['server_info'];
		$query	.= " WHERE si_id = $avalue";
		$query	.= " ORDER BY si_id ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Process query results
		while(list($si_id, $si_name) = db_fetch_row($result)) { $_out = $si_id.'- '.$si_name; }

		return $_out;
}


# Do select list for: SiteInfo
function do_select_list_siteinfo($aname, $avalue, $aret_flag=0) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT si_id, si_group, si_name, si_desc, si_title";
		$query	.= " FROM ".$_DBCFG['site_info']." ORDER BY si_id ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form output
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0">'.$_LANG['_BASE']['Please_Select'].'</option>'.$_nl;

	# Process query results
		while(list($si_id, $si_group, $si_name, $si_desc, $si_title) = db_fetch_row($result)) {
			$_out .= '<option value="'.$si_id.'"';
			IF ( $si_id == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.str_pad($si_id,3,'0',STR_PAD_LEFT).' - '.$si_name.'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do return string from value for: SiteInfo Page
function do_valtostr_siteinfo($avalue) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query for select.
		$query	= "SELECT si_id, si_name";
		$query	.= " FROM ".$_DBCFG['site_info'];
		$query	.= " WHERE si_id = $avalue";
		$query	.= " ORDER BY si_id ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Process query results
		while(list($si_id, $si_name) = db_fetch_row($result)) { $_out = $si_id.'- '.$si_name; }

		return $_out;
}

# Do item in use check for: Vendor ID
function do_inuse_vendor_id($avendorid) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$_in_use_count = 0;

	# Set Query for select and select in orders
		$query = ""; $result = ""; $numrows = 0;
		$query		= "SELECT ord_id FROM ".$_DBCFG['orders']." WHERE ord_vendor_id = '$avendorid'";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);
		$_in_use_count	= $_in_use_count + $numrows;

	# Set Query for select and select in vendors products table
		$query = ""; $result = ""; $numrows = 0;
		$query		= "SELECT vprod_id FROM ".$_DBCFG['vendors_prods']." WHERE vprod_vendor_id = '$avendorid'";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);
		$_in_use_count	= $_in_use_count + $numrows;

		return $_in_use_count;
}


# Do item in use check for: Product ID
function do_inuse_prod_id($aprodid) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$_in_use_count = 0;

	# Set Query for select and select in orders
		$query = ""; $result = ""; $numrows = 0;
		$query		= "SELECT ord_id FROM ".$_DBCFG['orders']." WHERE ord_prod_id = '$aprodid'";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);
		$_in_use_count	= $_in_use_count + $numrows;

	# Set Query for select and select in vendors products table
		$query = ""; $result = ""; $numrows = 0;
		$query		= "SELECT vprod_id FROM ".$_DBCFG['vendors_prods']." WHERE vprod_prod_id = '$aprodid'";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);
		$_in_use_count	= $_in_use_count + $numrows;

		return $_in_use_count;
}

// Displays a login form as a "menu box" item
function do_Display_Login_Menu_Form() {
	# Dim some Vars
		global $_TCFG, $_CCFG, $_LANG;
		$_SEC = get_security_flags();

	# If not logged in, draw form
		IF (!$_SEC['_sadmin_flg'] && !$_SEC['_suser_flg']) {
		    $loginbutton = eregi_replace('<img ','',$_TCFG['_IMG_MT_LOGIN_B']);
			$loginbutton = eregi_replace(' align="middle"','',$loginbutton);
			$loginform  = '<form action="coin_includes/session_user.php" method="post" name="login">';
			$loginform .= $_LANG['_BASE']['l_User_Name'] . '<br>';
			$loginform .= '<INPUT class="PMED_NL" type="text" name="username" size="20" maxlength="'.$_CCFG['CLIENT_MAX_LEN_UNAME'].'">';
			$loginform .= '<br>' . $_LANG['_BASE']['l_Password'] . '<br>';
			$loginform .= '<INPUT class="PMED_NL" type="password" name="password" size="20" maxlength="'.$_CCFG['CLIENT_MAX_LEN_PWORD'].'">';
			$loginform .= '<br>' . $_LANG['_BASE']['Forgot_your_password'] . '<br>' . $_LANG['_BASE']['Click'] . ' ';
			$loginform .= '<a href="mod.php?mod=mail&mode=reset&w=user">' . $_LANG['_BASE']['here'] . '</a> ' . $_LANG['_BASE']['for reset'] . '<br><br>';
			$loginform .= '<INPUT TYPE=hidden name="mod" value="">';
			$loginform .= '<INPUT TYPE=hidden name="mode" value="">';
			$loginform .= '&nbsp;&nbsp;&nbsp;<input type="image" ' . $loginbutton;
			$loginform .= '</form>';
		} ELSE {

		# display "logged in " message
			$loginform .= $_LANG['_BASE']['Welcome_Back'];
			$loginform .= '<br><br>' . $_LANG['_BASE']['Logout_When_Done'] . '.<br><br>';
			$loginform .= '&nbsp;&nbsp;&nbsp;<a href="login.php?w=';
			IF ($_SEC['_sadmin_flg']) {$loginform .= 'admin';} ELSE {$loginform .= 'user';}
			$loginform .= '&amp;o=logout">'.$_TCFG['_IMG_MT_LOGOUT_B'].'</a><br><br>';
		}
		return $loginform;
}


# Do helpdesk support ticket priority select list
function do_select_list_priority( $aname, $avalue, $aret_flag=0 ) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0">'.$_LANG['_HDESK']['Select_Priority'].'</option>'.$_nl;

	# Loop array and load list
		FOR ($i = 1; $i <= count($_CCFG[HD_TT_PRIORITY]); $i++) {
			$_out .= '<option value="'.$_CCFG['HD_TT_PRIORITY'][$i].'"';
			IF ( $_CCFG['HD_TT_PRIORITY'][$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_CCFG['HD_TT_PRIORITY'][$i].'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do helpdesk support ticket category select list
function do_select_list_category( $aname, $avalue, $aret_flag=0 ) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0">'.$_LANG['_HDESK']['Select_Category'].'</option>'.$_nl;

	# Load config array and sort
		$_tmp_array = $_CCFG[HD_TT_CATEGORY];
		sort($_tmp_array);

	# Loop array and load list
		FOR ($i = 0; $i < count($_tmp_array); $i++) {
			$_out .= '<option value="'.$_tmp_array[$i].'"';
			IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_tmp_array[$i].'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

	IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do helpdesk support ticket status select list
function do_select_list_status( $aname, $avalue, $aret_flag=0 ) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Build Form row
		$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0">'.$_LANG['_HDESK']['Select_Status'].'</option>'.$_nl;

	# Load config array and sort
		$_tmp_array = $_CCFG[HD_TT_STATUS];
		sort($_tmp_array);

	# Loop array and load list
		FOR ($i = 0; $i < count($_tmp_array); $i++) {
			$_out .= '<option value="'.$_tmp_array[$i].'"';
			IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_tmp_array[$i].'</option>'.$_nl;
		}

		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do return string from value for: Open or Closed Options
function do_valtostr_open_closed($avalue) {
	# Dim some Vars:
		global $_LANG;

	# Build form output
		IF ( $avalue == 1 ) {
			return $_LANG['_HDESK']['Status_Closed'];
		} ELSE {
			return $_LANG['_HDESK']['Status_Open'];
		}
}


# Do list select field for: Open or Closed Options
function do_select_list_open_closed($aname, $avalue, $aret_flag=0) {
	# Dim some Vars:
		global $_LANG;

	# Build form output
		$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
		$_out .= '<option value="0"';
		IF ( $avalue == 0 ) { $_out .= ' selected'; }
		$_out .= '>'.$_LANG['_HDESK']['Select_Open'].'</option>'.$_nl;
		$_out .= '<option value="1"';
		IF ( $avalue == 1 ) { $_out .= ' selected'; }
		$_out .= '>'.$_LANG['_HDESK']['Select_Closed'].'</option>'.$_nl;
		$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do domain activation email (build, set email))
function do_mail_domain($adata, $aret_flag=0)
	{
		# Dim some vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Set MTP array equal to data array
			$_MTP = $adata;

		# Do cross-table select for key fields
			# Dim some vars
				$query = ""; $result= ""; $numrows = 0;

			# Set Query for select.
				$query	.= "SELECT *";
				$query	.= " FROM ".$_DBCFG['domains'].", ".$_DBCFG['server_info'].", ".$_DBCFG['clients'];
				$query	.= " WHERE ".$_DBCFG['domains'].".dom_si_id = ".$_DBCFG['server_info'].".si_id";
				$query	.= " AND ".$_DBCFG['domains'].".dom_cl_id = ".$_DBCFG['clients'].".cl_id";
				$query	.= " AND ".$_DBCFG['domains'].".dom_id = ".$adata['dom_id'];

			# Do select
				$result		= db_query_execute($query);
				$numrows	= db_query_numrows($result);
				# $_test	.= "<br>NumRows=".$numrows.$_nl;

			# Check Return and process results
				IF ( $numrows )
					{
						# Process query results
							while ($row = db_fetch_array($result))
							{
								# Rebuild Data Array with returned record: Server Account Fields
									$_MTP								= $row;
									$_MTP['dom_id']						= $row[dom_id];
									$_MTP['dom_domain']					= $row[dom_domain];
									$_MTP['dom_notes']					= $row[dom_notes];
									$_MTP['dom_status']					= $row[dom_status];
									$_MTP['dom_type']					= $row[dom_type];
									$_MTP['dom_cl_id']					= $row[dom_cl_id];
									$_MTP['dom_registar']				= $row[dom_registar];
									$_MTP['dom_ts_expiration']			= dt_make_datetime ( $row[dom_ts_expiration], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] );
									$_MTP['dom_sa_expiration']			= dt_make_datetime ( $row[dom_sa_expiration], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] );
									$_MTP['dom_si_id']					= $row[dom_si_id];
									$_MTP['dom_ip']						= $row[dom_ip];
									$_MTP['dom_path']					= $row[dom_path];
									$_MTP['dom_path_temp']				= $row[dom_path_temp];
									$_MTP['dom_url_cp']					= $row[dom_url_cp];
									$_MTP['dom_user_name_cp']			= $row[dom_user_name_cp];
									$_MTP['dom_user_pword_cp']			= $row[dom_user_pword_cp];
									$_MTP['dom_user_name_ftp']			= $row[dom_user_name_ftp];
									$_MTP['dom_user_pword_ftp']			= $row[dom_user_pword_ftp];
									$_MTP['dom_allow_domains']			= $row[dom_allow_domains];
									$_MTP['dom_allow_subdomains']		= $row[dom_allow_subdomains];
									$_MTP['dom_allow_disk_space_mb']	= $row[dom_allow_disk_space_mb];
									$_MTP['dom_allow_traffic_mb']		= $row[dom_allow_traffic_mb];
									$_MTP['dom_allow_mailboxes']		= $row[dom_allow_mailboxes];
									$_MTP['dom_allow_databases']		= $row[dom_allow_databases];
									$_MTP['dom_enable_www_prefix']		= do_valtostr_no_yes($row[dom_enable_www_prefix]);
									$_MTP['dom_enable_wu_scripting']	= do_valtostr_no_yes($row[dom_enable_wu_scripting]);
									$_MTP['dom_enable_webmail']			= do_valtostr_no_yes($row[dom_enable_webmail]);
									$_MTP['dom_enable_frontpage']		= do_valtostr_no_yes($row[dom_enable_frontpage]);
									$_MTP['dom_enable_fromtpage_ssl']	= do_valtostr_no_yes($row[dom_enable_fromtpage_ssl]);
									$_MTP['dom_enable_ssi']				= do_valtostr_no_yes($row[dom_enable_ssi]);
									$_MTP['dom_enable_php']				= do_valtostr_no_yes($row[dom_enable_php]);
									$_MTP['dom_enable_cgi']				= do_valtostr_no_yes($row[dom_enable_cgi]);
									$_MTP['dom_enable_mod_perl']		= do_valtostr_no_yes($row[dom_enable_mod_perl]);
									$_MTP['dom_enable_asp']				= do_valtostr_no_yes($row[dom_enable_asp]);
									$_MTP['dom_enable_ssl']				= do_valtostr_no_yes($row[dom_enable_ssl]);
									$_MTP['dom_enable_stats']			= do_valtostr_no_yes($row[dom_enable_stats]);
									$_MTP['dom_enable_err_docs']		= do_valtostr_no_yes($row[dom_enable_err_docs]);

								# Rebuild Data Array with returned record: Server Info Fields
									$_MTP['si_id']					= $row[si_id];
									$_MTP['si_name']				= $row[si_name];
									$_MTP['si_ip']					= $row[si_ip];
									$_MTP['si_ns_01']				= $row[si_ns_01];
									$_MTP['si_ns_01_ip']			= $row[si_ns_01_ip];
									$_MTP['si_ns_02']				= $row[si_ns_02];
									$_MTP['si_ns_02_ip']			= $row[si_ns_02_ip];
									$_MTP['si_cp_url']				= $row[si_cp_url];
									$_MTP['si_cp_url_port']			= $row[si_cp_url_port];

								# Rebuild Data Array with returned record: Clients fields
									$_MTP['cl_company']				= $row[cl_company];
									$_MTP['cl_name_first']			= $row[cl_name_first];
									$_MTP['cl_name_last']			= $row[cl_name_last];
									$_MTP['cl_email']				= $row[cl_email];
									$_MTP['cl_user_name']			= $row[cl_user_name];

									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_01'].$row[cl_id].$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_02'].dt_make_datetime ( $row[cl_join_ts], $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_03'].$row[cl_user_name].$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_04'].$row[cl_email].$_nl;
									$_MTP['cl_info'] .= '-------------------'.$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_05'].$row[cl_company].$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_06'].$row[cl_name_first].' '.$row[cl_name_last].$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_07'].$row[cl_addr_01].$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_08'].$row[cl_addr_02].$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_09'].$row[cl_city].$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_10'].$row[cl_state_prov].$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_11'].$row[cl_country].$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_12'].$row[cl_zip_code].$_nl;
									$_MTP['cl_info'] .= $_LANG['_DOMS']['DOM_EMAIL_13'].$row[cl_phone];
							}
					}
		# End Get Domain Record

		# Get contact information array
			$_cinfo = get_contact_info($_CCFG['MC_ID_SUPPORT']);

		# Set eMail Parameters (pre-eval template, some used in template)
			IF ($_CCFG['_PKG_SAFE_EMAIL_ADDRESS']) {
    			$mail['recip']		= $_MTP['cl_email'];
				$mail['from']		= $_cinfo['c_email'];
			} ELSE {
				$mail['recip']		= $_MTP['cl_name_first'].' '.$_MTP['cl_name_last'].' <'.$_MTP['cl_email'].'>';
				$mail['from']		= $_CCFG['_PKG_NAME_SHORT'].'-'.$_cinfo['c_name'].' <'.$_cinfo['c_email'].'>';
			}
			IF ( $_CCFG['DOM_EMAIL_CC_ENABLE'] ) {
				IF ($_CCFG['_PKG_SAFE_EMAIL_ADDRESS']) {
  					$mail['cc']	= $_cinfo['c_email'];
				} ELSE {
					$mail['cc']	= $_CCFG['_PKG_NAME_SHORT'].'-'.$_cinfo['c_name'].' <'.$_cinfo['c_email'].'>';
				}
			} ELSE {
				$mail['cc']	= '';
			}
			$mail['subject']	= $_CCFG['_PKG_NAME_SHORT'].$_LANG['_DOMS']['DOM_EMAIL_SUBJECT'];

		# Set MTP (Mail Template Parameters) array
			$_MTP['to_name']		= $_MTP['cl_name_first'].' '.$_MTP['cl_name_last'];
			$_MTP['to_email']		= $_MTP['cl_email'];
			$_MTP['from_name']		= $_cinfo['c_name'];
			$_MTP['from_email']		= $_cinfo['c_email'];
			$_MTP['subject']		= $mail['subject'];
			$_MTP['site']			= $_CCFG['_PKG_NAME_SHORT'];
			$_MTP['cl_url']			= $_CCFG['_PKG_URL_BASE'].'mod.php?mod=clients&mode=view&cl_id='.$row['cl_id'];

		# Check returned records, don't send if not 1
			$_ret = 1;
			IF ( $numrows == 1 )
				{
					# Load message template (processed)
						$mail['message']	.= get_mail_template ('email_domain_acc_activate', $_MTP);

					# Call basic email function (ret=1 on error)
						$_ret = do_mail_basic ($mail);

					# Check return
						IF ( $_ret )
							{
								$_ret_msg = $_LANG['_DOMS']['DOM_EMAIL_MSG_02_L1'];
								$_ret_msg .= '<br>'.$_LANG['_DOMS']['DOM_EMAIL_MSG_02_L2'];
							}
						ELSE
							{	$_ret_msg = $_LANG['_DOMS']['DOM_EMAIL_MSG_03_PRE'].$_sp.$adata['dom_id'].$_sp.$_LANG['_DOMS']['DOM_EMAIL_MSG_03_SUF'];	}
				}
			ELSE
				{	$_ret_msg = $_LANG['_DOMS']['DOM_EMAIL_MSG_01_PRE'].$_sp.$adata['dom_id'].$_sp.$_LANG['_DOMS']['DOM_EMAIL_MSG_01_SUF'];	}

		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_DOMS']['DOM_EMAIL_RESULT_TITLE'];

			$_cstr .= '<center>'.$_nl;
			$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= $_ret_msg.$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;
			$_cstr .= '</center>'.$_nl;

			$_mstr_flag	= 0;
			$_mstr		= '&nbsp;'.$_nl;

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# See if desired image exists, in any format
function do_check_if_image_exists($imagename) {
	$imageURL = '';
	IF (file_exists($imagename . '.jpg')) {
		$imageURL = $imagename . '.jpg';
	} ELSEIF (file_exists($imagename . '.jpeg')) {
		$imageURL = $imagename . '.jpeg';
	} ELSEIF (file_exists($imagename . '.gif')) {
		$imageURL = $imagename . '.gif';
	} ELSEIF (file_exists($imagename . '.png')) {
		$imageURL = $imagename . '.png';
	} ELSEIF (file_exists($imagename . '.bmp')) {
		$imageURL = $imagename . '.bmp';
	}
	return $imageURL;
}
?>
