<?php

/**************************************************************
 * File: 		Clients Module Functions File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_clients.php
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("clients_funcs.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01&url=mod.php?mod=clients');
			exit;
		}

/**************************************************************
 * Module Functions
**************************************************************/
# Delete an "additional email"
function do_delete_additional_email($contacts_id) {
	# Dim some Vars:
		global $_DBCFG;
		$query = ""; $result= "";

	# Do purge contact
		$query 			= "DELETE FROM ".$_DBCFG['clients_contacts']." WHERE contacts_id = $contacts_id";
		$result 		= db_query_execute($query) OR DIE("Unable to complete request");
		$eff_rows		= db_query_affected_rows ();

	# Return result;
		return $eff_rows;
}

# Insert an "additional email"
function do_insert_additional_email($cl_id,$fname,$lname,$email) {
	# Dim some Vars:
		global $_DBCFG;
		$query = ""; $result= ""; $eff_rows;

	# Do purge contact
		$query 		 = "INSERT INTO ".$_DBCFG['clients_contacts'];
		$query		.= " (contacts_id, contacts_cl_id, contacts_name_first, contacts_name_last, contacts_email)";
		$query		.= "VALUES ('', '$cl_id', '$fname', '$lname', '$email')";
		$result 	 = db_query_execute($query) OR DIE("Unable to complete request");
		$eff_rows	 = db_query_affected_rows();

	# Return result;
		return $eff_rows;
}

# Update an "additional email"
function do_update_additional_email($cl_id,$fname,$lname,$email,$contacts_id) {
	# Dim some Vars:
		global $_DBCFG;
		$query = ""; $result= ""; $eff_rows;

	# Do purge contact
		$query 		 = "UPDATE ".$_DBCFG['clients_contacts']." SET ";
		$query		.= "contacts_name_first='$fname', ";
		$query		.= "contacts_name_last='$lname', ";
		$query		.= "contacts_email='$email' ";
		$query		.= "WHERE contacts_id=$contacts_id";
		$result 	 = db_query_execute($query) OR DIE("Unable to complete request");
		$eff_rows	 = db_query_affected_rows();

	# Return result;
		return $eff_rows;
}


# Build a form for add/edit/delete additional client emails
function do_form_additional_emails($cl_id) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0; $_out = "";

	# Build common td start tag / col strings (reduce text)
		$_td_str_right	= '<td class="TP1SML_NR">'.$_nl;
		$_td_str_left	= '<td class="TP1SML_NL">'.$_nl;
		$_td_str_ctr	= '<td class="TP1SML_NC">'.$_nl;

	# Build table row beginning and ending
		$_cstart  = '<b>'.$_LANG['_CLIENTS']['l_Email_Address_Additional'].$_sp.'</b><br>'.$_nl;
		$_cstart .= '<table border="0" cellpadding="3" cellspacing="0"><tr>'.$_nl;
		$_cstart .= $_td_str_left.$_LANG['_CLIENTS']['l_First_Name'].'</td>'.$_nl;
		$_cstart .= $_td_str_left.$_LANG['_CLIENTS']['l_Last_Name'].'</td>'.$_nl;
		$_cstart .= $_td_str_left.$_LANG['_CLIENTS']['l_Email_Address'].'</td>'.$_nl;
		$_cstart .= $_td_str_ctr.$_LANG['_CLIENTS']['l_Action'].'</td></tr>'.$_nl;

		$_cend    = '</table>'.$_nl;

	# Set Query for select (additional emails).
		$query	= "SELECT *";
		$query .= " FROM ".$_DBCFG['clients_contacts'];
		$query .= " WHERE contacts_cl_id=".$cl_id;
		$query .= " ORDER BY contacts_email ASC";

	# Do select and return check
		IF (is_numeric($cl_id)) {
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);
		}
	# Process query results
		IF ( $numrows ) {
			$button = str_replace("<img src=\"","",$_TCFG['_IMG_SAVE_S']);
			while ($row = db_fetch_array($result)) {

			# Display the "edit/delete data" form for this row
				$_out .= '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=clients">'.$_nl;
				$_out .= '<input type="hidden" name="cl_id" value="'.$cl_id.'">'.$_nl;
				$_out .= '<input type="hidden" name="stage" value="">'.$_nl;
				$_out .= '<input type="hidden" name="op" value="update">'.$_nl;
				$_out .= '<input type="hidden" name="contacts_id" value="'.$row['contacts_id'].'">'.$_nl;
				$_out .= '<input type="hidden" name="mode" value="ae_mails">'.$_nl;
				$_out .= '<tr>'.$_nl;
				$_out .= $_td_str_left.'<input class="PSML_NL" type="text" name="ae_fname" size="15" value="' . $row['contacts_name_first'] . '"></td>'.$_nl;
				$_out .= $_td_str_left.'<input class="PSML_NL" type="text" name="ae_lname" size="15" value="' . $row['contacts_name_last'] . '"></td>'.$_nl;
				$_out .= $_td_str_left.'<input class="PSML_NL" type="text" name="ae_email" size="35" value="' . $row['contacts_email'] . '"></td>'.$_nl;
				$_out .= $_td_str_left.'&nbsp;&nbsp;&nbsp;'.$_nl;

			# Display "update" button
				$_out .= '<input type="image" src="'.$button.$_nl;
			# Display "Delete" button
				$_out .= '&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?mod=clients&mode=ae_mails&stage=0&cl_id='.$cl_id.'&op=del&contacts_id='.$row['contacts_id'].'">'.$_TCFG['_IMG_DEL_S'].'</a>'.$_nl;
			# End row
				$_out .= '</td></tr></form>'.$_nl;
			}
		}

	# Display form for adding a new entry
		$button = str_replace("<img src=\"","",$_TCFG['_IMG_ADD_S']);
		$_out .= '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=clients">'.$_nl;
		$_out .= '<input type="hidden" name="cl_id" value="'.$cl_id.'">'.$_nl;
		$_out .= '<input type="hidden" name="stage" value="">'.$_nl;
		$_out .= '<input type="hidden" name="mode" value="ae_mails">'.$_nl;
		$_out .= '<input type="hidden" name="op" value="add">'.$_nl;
		$_out .= '<tr>'.$_nl;
		$_out .= $_td_str_left.'<input class="PSML_NL" type="text" name="new_ae_fname" size="15" value=""></td>'.$_nl;
		$_out .= $_td_str_left.'<input class="PSML_NL" type="text" name="new_ae_lname" size="15" value=""></td>'.$_nl;
		$_out .= $_td_str_left.'<input class="PSML_NL" type="text" name="new_ae_email" size="35" value=""></td>'.$_nl;
		$_out .= $_td_str_left.'&nbsp;&nbsp;&nbsp;'.$_nl;
		$_out .= '<input type="image" src="'.$button.'</td></tr></form>'.$_nl;

	# Build return string
		$returning = $_cstart.$_out.$_cend;

	# Return the form
		return $returning;
}


# Do encode groups: User Groups (8-bit 0-255)
function do_encode_groups_user($_UG) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Encode into 8-bit binary string
		IF ( $_UG[UG08] != 1 ) { $_UG[UG08] = 0; }
		IF ( $_UG[UG07] != 1 ) { $_UG[UG07] = 0; }
		IF ( $_UG[UG06] != 1 ) { $_UG[UG06] = 0; }
		IF ( $_UG[UG05] != 1 ) { $_UG[UG05] = 0; }
		IF ( $_UG[UG04] != 1 ) { $_UG[UG04] = 0; }
		IF ( $_UG[UG03] != 1 ) { $_UG[UG03] = 0; }
		IF ( $_UG[UG02] != 1 ) { $_UG[UG02] = 0; }
		IF ( $_UG[UG01] != 1 ) { $_UG[UG01] = 0; }
		$_bin	.= $_UG[UG08].$_UG[UG07].$_UG[UG06].$_UG[UG05].$_UG[UG04].$_UG[UG03].$_UG[UG02].$_UG[UG01];
		$_dec	= bindec($_bin);

	# Return decoded array
		return $_dec;
}


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
			IF ( $_GPV[mode]=='edit' || $_GPV[mode]=='add' )
				{
					# Check required fields (err / action generated later in cade as required)
//						IF ( !$_GPV[cl_id] )				{ $err_entry['flag'] = 1; $err_entry['cl_id'] = 1; }
						IF ( !$_GPV[cl_join_ts] )			{ $err_entry['flag'] = 1; $err_entry['cl_join_ts'] = 1; }
						IF ( !$_GPV[cl_status] )			{ $err_entry['flag'] = 1; $err_entry['cl_status'] = 1; }
						IF ( $_BV['B16'] == 1 && 1==2 )
							{ IF ( !$_GPV[cl_company] )		{ $err_entry['flag'] = 1; $err_entry['cl_company'] = 1; } }
						IF ( !$_GPV[cl_name_first] )		{ $err_entry['flag'] = 1; $err_entry['cl_name_first'] = 1; }
						IF ( !$_GPV[cl_name_last] )			{ $err_entry['flag'] = 1; $err_entry['cl_name_last'] = 1; }
						IF ( $_BV['B15'] == 1 )
							{ IF ( !$_GPV[cl_addr_01] )		{ $err_entry['flag'] = 1; $err_entry['cl_addr_01'] = 1; } }
						IF ( $_BV['B14'] == 1 && 1==2 )
							{ IF ( !$_GPV[cl_addr_02] )		{ $err_entry['flag'] = 1; $err_entry['cl_addr_02'] = 1; } }
						IF ( $_BV['B13'] == 1 )
							{ IF ( !$_GPV[cl_city] )		{ $err_entry['flag'] = 1; $err_entry['cl_city'] = 1; } }
						IF ( $_BV['B12'] == 1 )
							{ IF ( !$_GPV[cl_state_prov] )	{ $err_entry['flag'] = 1; $err_entry['cl_state_prov'] = 1; } }
						IF ( $_BV['B11'] == 1 )
							{ IF ( !$_GPV[cl_country] )		{ $err_entry['flag'] = 1; $err_entry['cl_country'] = 1; } }
						IF ( $_BV['B10'] == 1 )
							{ IF ( !$_GPV[cl_zip_code] )	{ $err_entry['flag'] = 1; $err_entry['cl_zip_code'] = 1; } }
						IF ( $_BV['B09'] == 1 && 1==2 )
							{ IF ( !$_GPV[cl_phone] )		{ $err_entry['flag'] = 1; $err_entry['cl_phone'] = 1; } }
						IF ( !$_GPV[cl_email] )				{ $err_entry['flag'] = 1; $err_entry['cl_email'] = 1; }
						IF ( !$_GPV[cl_user_name] )			{ $err_entry['flag'] = 1; $err_entry['cl_user_name'] = 1; }
						IF ( 1==2 )
							{ IF ( !$_GPV[cl_notes] )		{ $err_entry['flag'] = 1; $err_entry['cl_notes'] = 1; } }
				}

		# Validate some data (submitting data entered)
			# Email
				IF (do_validate_email($_GPV[cl_email],0))
					{ $err_entry['flag'] = 1; $err_entry['cl_email'] = 1; $err_entry['err_email_invalid'] = 1; }

			# Unique user name- does exist
				IF ( $_GPV[mode]=='add' || ( $_GPV[mode]=='edit' && $_GPV[cl_user_name] != $_GPV[cl_user_name_orig] ) )
					{
						IF ( do_user_name_exist_check($_GPV[cl_user_name], 'user') )
							{ $err_entry['flag'] = 1; $err_entry['cl_user_name'] = 1; $err_entry['err_user_name_exist'] = 1; }
					}

			# Passwords equal
				IF ( $_GPV[cl_user_pword] && $_GPV[cl_user_pword_re] && ( $_GPV[cl_user_pword] != $_GPV[cl_user_pword_re] ) )
					{
						$err_entry['flag'] = 1;
						$err_entry['err_pword_match'] = 1;
						$err_entry['cl_user_pword'] = 1;
						$err_entry['cl_user_pword_re'] = 1;
					}

			# Passwords empty on adding new
				IF ( $_GPV[mode]=='add' )
					{
						IF ( !$_GPV[cl_user_pword] )		{ $err_entry['flag'] = 1; $err_entry['cl_user_pword'] = 1; }
						IF ( !$_GPV[cl_user_pword_re] )		{ $err_entry['flag'] = 1; $err_entry['cl_user_pword_re'] = 1; }
					}

		return $err_entry;

	}


# Do Form for Add / Edit
function do_form_add_edit ( $amode, $adata, $aerr_entry, $aret_flag=0 ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Get field enabled vars
		$_BV = do_decode_DB16($_CCFG['ORDERS_FIELD_ENABLE_ORD']);

	# Build mode dependent strings
		switch ($amode) {
			case "add":
				$mode_proper	= $_LANG['_CLIENTS']['B_Add'];
				$mode_button	= $_LANG['_CLIENTS']['B_Add'];
				break;
			case "edit":
				$mode_proper	= $_LANG['_CLIENTS']['B_Edit'];
				$mode_button	= $_LANG['_CLIENTS']['B_Save'];
				break;
			default:
				$amode			= "add";
				$mode_proper	= $_LANG['_CLIENTS']['B_Add'];
				$mode_button	= $_LANG['_CLIENTS']['B_Add'];
				break;
		}

	# Build Title String, Content String, and Footer Menu String
		$_tstr .= $mode_proper.$_sp.$_LANG['_CLIENTS']['Client_Info_Entry'].$_sp.'( <b>(*)</b>'.$_sp.$_LANG['_CLIENTS']['denotes_optional_items'].')';

	# Do data entry error string check and build
		IF ($aerr_entry['flag']) {
		 	$err_str = $_LANG['_CLIENTS']['CL_ERR_ERR_HDR1'].'<br>'.$_LANG['_CLIENTS']['CL_ERR_ERR_HDR2'].'<br>'.$_nl;
	 		IF ($aerr_entry['cl_id']) 			{ $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR01']; $err_prv = 1; }
			IF ($aerr_entry['cl_join_ts']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR02']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_status']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR03']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_company']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR04']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_name_first']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR05']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_name_last']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR06']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_addr_01']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR07']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_addr_02']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR08']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_city']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR09']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_state_prov']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR10']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_country']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR11']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_zip_code']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR12']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_phone']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR13']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_email']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR14']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_user_name']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR15']; $err_prv = 1; }
		 	IF ($aerr_entry['cl_user_pword']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR16']; $err_prv = 1; }
		 	IF ($aerr_entry['err_email_invalid']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_CLIENTS']['CL_ERR_ERR25']; $err_prv = 1; }

	 		$_cstr .= '<p align="center"><b>'.$err_str.'</b>'.$_nl;
		}

	# Check Stage for extra data validation
		IF ( $adata['stage']==1 ) {
		# Email
			IF ( $err_entry['err_email_invalid'] )
				{ $_err_more .= '<br>'.$_LANG['_CLIENTS']['CL_ERR_ERR30'].$_nl; }

		# Unique user name- does exist
			IF ( $err_entry['err_user_name_exist'] )
				{ $_err_more .= '<br>'.$_LANG['_CLIENTS']['CL_ERR_ERR31'].$_nl; }

		# Passwords equal
			IF ( $err_entry['err_pword_match'] ) {
				$_err_more .= '<br>'.$_LANG['_CLIENTS']['CL_ERR_ERR32'].$_nl;
				$adata['cl_user_pword'] = "";
				$adata['cl_user_pword_re'] = "";
			}

		# Print out more errors
			IF ( $_err_more ) { $_cstr .= '<br><b>'.$_err_more.'</b>'.$_nl; }
		}

	# Build common td start tag / col strings (reduce text)
		$_td_str_left_vtop		= '<td class="TP1SML_NR" width="30%" valign="top">';
		$_td_str_left			= '<td class="TP1SML_NR" width="30%">';
		$_td_str_right			= '<td class="TP1SML_NL" width="70%">';
		$_td_str_right_just		= '<td class="TP1SML_NJ" width="70%">';
		$_td_str_center_span	= '<td class="TP1SML_NC" width="100%" colspan="2">';

	# Misc mode check for display values
		IF ( $amode=='add' )
			{ $adata[cl_id] = '('.$_LANG['_CLIENTS']['auto-assigned'].')'; $adata[cl_join_ts] = '('.$_LANG['_CLIENTS']['auto-assigned'].')'; }

	# Do Main Form
		$_cstr .= '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=clients&mode='.$amode.'">'.$_nl;
		$_cstr .= '<table cellpadding="5" cellspacing="0" width="100%">'.$_nl;
		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Client_ID'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= $adata[cl_id].$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= '<td class="TP3SML_NR" width="30%" valign="top">'.'<b>'.$_LANG['_CLIENTS']['l_Join_DateTime'].$_sp.'</b></td>'.$_nl;
		$_cstr .= '<td class="TP3SML_NL" width="70%">'.$_nl;
		IF ( $adata[cl_join_ts] <= 0 || $adata[cl_join_ts] == '') { $adata[cl_join_ts] = dt_get_uts(); }
		IF ( $_SEC['_sadmin_flg'] ) {
			$_cstr .= do_datetime_edit_list (cl_join_ts, $adata[cl_join_ts], 1).$_nl;
		} ELSE {
			$_cstr .= dt_make_datetime ( $adata[cl_join_ts], $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).$_nl;
		}
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Status'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;

	# Admin check for either edit or display
		IF ( $_SEC['_sadmin_flg'] ) {
		# Call select list function
			$aname	= "cl_status";
			$avalue	= $adata[cl_status];
			$_cstr .= do_select_list_status_client($aname, $avalue).$_nl;
		} ELSE {
			$_cstr .= $adata[cl_status].$_nl;
		}
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		IF ( $_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP] == 1) ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left_vtop.'<b>'.$_LANG['_CLIENTS']['l_Groups'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_GROUPS = do_decode_groups_user($adata[cl_groups]);

			$_cstr .= '<table width="100%"><tr><td class="TP0SML_NL">';
			IF ( $_GROUPS[UG08]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[UG08] = 0; }
			$_cstr .= '<INPUT TYPE=CHECKBOX NAME="UG08" value="1"'.$_set.' border="0">'.$_nl;
			$_cstr .= $_sp.'<b>'.$_LANG['_BASE']['User_Groups_08'].'</b>'.$_nl;
			$_cstr .= '</td><td class="TP0SML_NL">';
			IF ( $_GROUPS[UG04]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[UG04] = 0; }
			$_cstr .= '<INPUT TYPE=CHECKBOX NAME="UG04" value="1"'.$_set.' border="0">'.$_nl;
			$_cstr .= $_sp.'<b>'.$_LANG['_BASE']['User_Groups_04'].'</b>'.$_nl;
			$_cstr .= '</td></tr><tr><td class="TP0SML_NL">';
			IF ( $_GROUPS[UG07]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[UG07] = 0; }
			$_cstr .= '<INPUT TYPE=CHECKBOX NAME="UG07" value="1"'.$_set.' border="0">'.$_nl;
			$_cstr .= $_sp.'<b>'.$_LANG['_BASE']['User_Groups_07'].'</b>'.$_nl;
			$_cstr .= '</td><td class="TP0SML_NL">';
			IF ( $_GROUPS[UG03]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[UG03] = 0; }
			$_cstr .= '<INPUT TYPE=CHECKBOX NAME="UG03" value="1"'.$_set.' border="0">'.$_nl;
			$_cstr .= $_sp.'<b>'.$_LANG['_BASE']['User_Groups_03'].'</b>'.$_nl;
			$_cstr .= '</td></tr><tr><td class="TP0SML_NL">';
			IF ( $_GROUPS[UG06]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[UG06] = 0; }
			$_cstr .= '<INPUT TYPE=CHECKBOX NAME="UG06" value="1"'.$_set.' border="0">'.$_nl;
			$_cstr .= $_sp.'<b>'.$_LANG['_BASE']['User_Groups_06'].'</b>'.$_nl;
			$_cstr .= '</td><td class="TP0SML_NL">';
			IF ( $_GROUPS[UG02]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[UG02] = 0; }
			$_cstr .= '<INPUT TYPE=CHECKBOX NAME="UG02" value="1"'.$_set.' border="0">'.$_nl;
			$_cstr .= $_sp.'<b>'.$_LANG['_BASE']['User_Groups_02'].'</b>'.$_nl;
			$_cstr .= '</td></tr><tr><td class="TP0SML_NL">';
			IF ( $_GROUPS[UG05]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[UG05] = 0; }
			$_cstr .= '<INPUT TYPE=CHECKBOX NAME="UG05" value="1"'.$_set.' border="0">'.$_nl;
			$_cstr .= $_sp.'<b>'.$_LANG['_BASE']['User_Groups_05'].'</b>'.$_nl;
			$_cstr .= '</td><td class="TP0SML_NL">';
			IF ( $_GROUPS[UG01]==1 ) { $_set = ' CHECKED'; } ELSE { $_set = ''; $adata[UG01] = 0; }
			$_cstr .= '<INPUT TYPE=CHECKBOX NAME="UG01" value="1"'.$_set.' border="0">'.$_nl;
			$_cstr .= $_sp.'<b>'.$_LANG['_BASE']['User_Groups_01'].'</b>'.$_nl;
			$_cstr .= '</td></tr></table>';

			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}

		IF ( $_BV['B16'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Company_NReq'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_company" SIZE=20 value="'.$adata[cl_company].'" maxlength="50">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_First_Name'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_name_first" SIZE=20 value="'.$adata[cl_name_first].'">'.$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Last_Name'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_name_last" SIZE=20 value="'.$adata[cl_name_last].'">'.$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		IF ( $_BV['B15'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Address_Street_1'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_addr_01" SIZE=40 value="'.$adata[cl_addr_01].'" maxlength="50">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}
		IF ( $_BV['B14'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Address_Street_2_NReq'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_addr_02" SIZE=40 value="'.$adata[cl_addr_02].'" maxlength="50">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}
		IF ( $_BV['B13'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_City'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_city" SIZE=40 value="'.$adata[cl_city].'" maxlength="50">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}
		IF ( $_BV['B12'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_State_Province'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_state_prov" SIZE=40 value="'.$adata[cl_state_prov].'" maxlength="50">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}
		IF ( $_BV['B11'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Country'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
//			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_country" SIZE=40 value="'.$adata[cl_country].'" maxlength="50">'.$_nl;
			$_cstr .= do_select_list_countries("cl_country",$adata[cl_country]);

			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}
		IF ( $_BV['B10'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Zip_Postal_Code'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_zip_code" SIZE=12 value="'.$adata[cl_zip_code].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}
		IF ( $_BV['B09'] == 1 ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Phone_NReq'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_phone" SIZE=20 value="'.$adata[cl_phone].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Email_Address'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_email" SIZE=40 value="'.$adata[cl_email].'" maxlength="50">'.$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_User_Name'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_user_name" SIZE=20 value="'.$adata[cl_user_name].'" maxlength="'.$_CCFG['CLIENT_MAX_LEN_UNAME'].'">'.$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

	# If existing user, add note password for change only
		IF ( $amode=='edit' ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_sp.'</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= $_LANG['_CLIENTS']['Password_Note'].$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Password'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_user_pword" SIZE=20 value="'.$adata[cl_user_pword].'" maxlength="'.$_CCFG['CLIENT_MAX_LEN_PWORD'].'">'.$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Password_Confirm'].$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="cl_user_pword_re" SIZE=20 value="'.$adata[cl_user_pword_re].'" maxlength="'.$_CCFG['CLIENT_MAX_LEN_PWORD'].'">'.$_nl;
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;

		IF ( $_SEC['_sadmin_flg'] ) {
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left_vtop.'<b>'.$_LANG['_CLIENTS']['l_Notes'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<TEXTAREA class="PSML_NL" NAME="cl_notes" COLS=60 ROWS=15>'.$adata[cl_notes].'</TEXTAREA>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
		}

		$_cstr .= '<tr>'.$_nl;
		$_cstr .= '<td class="TP0SML_NC" width="100%" colspan="2">'.$_nl;
		$_cstr .= '<INPUT TYPE=hidden name="stage" value="1">'.$_nl;
		$_cstr .= '<INPUT TYPE=hidden name="cl_id" value="'.$adata[cl_id].'">'.$_nl;

	# Admin check to save if not admin
		IF ( !$_SEC['_sadmin_flg'] ) {
			$_cstr .= '<INPUT TYPE=hidden name="cl_join_ts" value="'.$adata[cl_join_ts].'">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="cl_status" value="'.$adata[cl_status].'">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="cl_notes" value="'.$adata[cl_notes].'">'.$_nl;
		}

	# Mode check for edit to save original user name
		IF ( $amode=='edit' ) {
			IF ( !$adata[cl_user_name_orig] ) { $adata[cl_user_name_orig]=$adata[cl_user_name]; }
			$_cstr .= '<INPUT TYPE=hidden name="cl_user_name_orig" value="'.$adata[cl_user_name_orig].'">'.$_nl;
		}
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;
		$_cstr .= '<tr>'.$_nl;
		$_cstr .= $_td_str_left.'<b>'.$_sp.'</b></td>'.$_nl;
		$_cstr .= $_td_str_right.$_nl;
		$_cstr .= do_input_button_class_sw ('b_edit', 'SUBMIT', $mode_button, 'button_form_h', 'button_form', '1').$_nl;
		$_cstr .= do_input_button_class_sw ('b_reset', 'RESET', $_LANG['_CLIENTS']['B_Reset'], 'button_form_h', 'button_form', '1').$_nl;
		IF ( $_SEC['_sadmin_flg'] && $amode=="edit")
			{ $_cstr .= do_input_button_class_sw ('b_delete', 'SUBMIT', $_LANG['_CLIENTS']['B_Delete_Entry'], 'button_form_h', 'button_form', '1').$_nl; }
		$_cstr .= '</td>'.$_nl;
		$_cstr .= '</tr>'.$_nl;
		$_cstr .= '</table>'.$_nl;
		$_cstr .= '</FORM>'.$_nl;

	# Build a form for showing/adding/editing/deleting additional emails
	# Client must exist before this form will show.
	# If client does not exist, a notice about form availability will appear instead.
		IF ($amode == "edit") {
			$_cstr .= do_form_additional_emails($adata[cl_id]).$_nl;
		} ELSE {
			$_cstr .= $_LANG['_CLIENTS']['l_Email_Address_Additional_later'];
		}

		IF ( $amode=='edit' )
			{ $_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=clients&mode=view&cl_id='.$adata[cl_id], $_TCFG['_IMG_VIEW_M'],$_TCFG['_IMG_VIEW_M_MO'],'',''); }
		IF ( $_SEC['_sadmin_flg'] )
			{ $_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=clients&mode=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'',''); }
		$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=clients', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

	# Call block it function
		$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
		$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do display entry (individual entry)
function do_view_client_info ( $adata, $aret_flag=0 ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;
		$ae_query = ""; $ae_result= ""; $ae_numrows = 0;

	# Set Query for select.
		$query	= "SELECT *";
		$query .= " FROM ".$_DBCFG['clients'];
		$query .= " WHERE cl_id=".$adata['_suser_id'];
		$query .= " ORDER BY cl_id ASC";

	# Do select and return check
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Process query results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {
				$cl_id			= $row['cl_id'];
				$cl_join_ts		= $row['cl_join_ts'];
				$cl_status		= $row['cl_status'];
				$cl_company		= $row['cl_company'];
				$cl_name_first	= $row['cl_name_first'];
				$cl_name_last	= $row['cl_name_last'];
				$cl_addr_01		= $row['cl_addr_01'];
				$cl_addr_02		= $row['cl_addr_02'];
				$cl_city		= $row['cl_city'];
				$cl_state_prov	= $row['cl_state_prov'];
				$cl_country		= $row['cl_country'];
				$cl_zip_code	= $row['cl_zip_code'];
				$cl_phone		= $row['cl_phone'];
				$cl_email		= $row['cl_email'];
				$cl_user_name	= $row['cl_user_name'];
				$cl_notes		= $row['cl_notes'];
				$cl_groups		= $row['cl_groups'];
			}
		}

	# Check return
		IF ( !numrows ) { $_err_str = $_LANG['_CLIENTS']['Error_Client_Not_Found'].$_nl; }

	# Build common td start tag / strings (reduce text)
		$_td_str_left_vtop		= '<td class="TP1SML_NR" valign="top">';
		$_td_str_left			= '<td class="TP1SML_NR">';
		$_td_str_left_span3		= '<td class="TP1SML_NR" colspan="3">';
		$_td_str_right			= '<td class="TP1SML_NL">';
		$_td_str_right_span3	= '<td class="TP1SML_NJ" colspan="3">';
		$_td_str_span			= '<td class="TP1SML_NC" colspan="4">';

	# Build output
		$_out .= '<br>'.$_nl;
		$_out .= '<div align="center">'.$_nl;
		$_out .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
		$_out .= '<tr class="BLK_DEF_TITLE"><td class="TP3MED_BC">'.$_nl;

		$_out .= '<table width="100%" cellpadding="0" cellspacing="0">'.$_nl;
		$_out .= '<tr class="BLK_IT_TITLE_TXT">'.$_nl.'<td class="TP0MED_NL">'.$_nl;
		$_out .= '<b>'.$_LANG['_CLIENTS']['Client_Information'].'</b>';
		$_out .= '</td>'.$_nl.'<td class="TP0MED_NR">'.$_nl;
		IF ( $_CCFG['_IS_PRINT'] != 1 ) {
   			$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=clients&mode=mail&cl_id='.$cl_id, $_TCFG['_S_IMG_EMAIL_S'],$_TCFG['_S_IMG_EMAIL_S_MO'],'','');
			IF ( $_SEC['_suser_flg'] || ($_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP07] == 1)) ) {
				$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=clients&mode=edit&cl_id='.$cl_id, $_TCFG['_S_IMG_EDIT_S'],$_TCFG['_S_IMG_EDIT_S_MO'],'','');
			}
			IF ($_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP07] == 1)) {
				$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=clients&mode=delete&stage=1&cl_id='.$cl_id.'&cl_name_first='.$cl_name_first.'&cl_name_last='.$cl_name_last, $_TCFG['_S_IMG_DEL_S'],$_TCFG['_S_IMG_DEL_S_MO'],'','');
			}
			IF ( $_SEC['_sadmin_flg'] ) {
				$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=clients', $_TCFG['_S_IMG_SEARCH_S'],$_TCFG['_S_IMG_SEARCH_S_MO'],'','');
			}
		} ELSE {
			$_out .= $_sp;
		}
		$_out .= '</td>'.$_nl.'</tr>'.$_nl.'</table>'.$_nl;

		$_out .= '</td></tr>'.$_nl;
		$_out .= '<tr class="BLK_DEF_ENTRY"><td class="BLK_IT_ENTRY">'.$_nl;

		$_out .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">'.$_nl;
		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Client_ID'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.$cl_id.'</td>'.$_nl;
		$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Client'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.$cl_name_last.', '.$cl_name_first.'</td>'.$_nl;
		$_out .= '</tr>'.$_nl;

		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_User_Name'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.$cl_user_name.'</td>'.$_nl;
		IF ($cl_company) {
			$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Company'].$_sp.'</b></td>'.$_nl;
			$_out .= $_td_str_right.$cl_company.'</td>'.$_nl;
		} ELSE {
			$_out .= $_td_str_left.$_sp.'</td>'.$_nl;
			$_out .= $_td_str_right.$_sp.'</td>'.$_nl;
		}
		$_out .= '</tr>'.$_nl;

		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Email'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.$cl_email.'</td>'.$_nl;

	# Set Query for select (additional emails).
		$ae_query	= "SELECT *";
		$ae_query .= " FROM ".$_DBCFG['clients_contacts'];
		$ae_query .= " WHERE contacts_cl_id=".$adata['_suser_id'];
		$ae_query .= " ORDER BY contacts_email ASC";

	# Do select and return check
		$ae_result	= db_query_execute($ae_query);
		$ae_numrows	= db_query_numrows($ae_result);

	# Process query results
		IF ( $ae_numrows ) {
			$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
			$_out .= $_td_str_left_vtop.'<b>'.$_LANG['_CLIENTS']['l_Email_Address_Additional'].$_sp.'</b></td>'.$_nl;
			$_out .= $_td_str_right.$_nl;
			while ($ae_row = db_fetch_array($ae_result)) {
				$_out .= $ae_row['contacts_email'].'<br>'.$_nl;
       	    }
			$_out .= '</td>'.$_nl;
		}

		$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Address'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.$cl_addr_01.'</td>'.$_nl;
		$_out .= '</tr>'.$_nl;

		IF ( $cl_addr_02 ) {
			$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
			$_out .= $_td_str_left_span3.'<b>'.$_sp.'</b></td>'.$_nl;
			$_out .= $_td_str_right.$cl_addr_02.'</td>'.$_nl;
			$_out .= '</tr>'.$_nl;
		}

		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= $_td_str_left_span3.'<b>'.$_LANG['_CLIENTS']['l_City'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.$cl_city.'</td>'.$_nl;
		$_out .= '</tr>'.$_nl;
		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Status'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.$cl_status.'</td>'.$_nl;
		$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_State_Province'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.$cl_state_prov.'</td>'.$_nl;
		$_out .= '</tr>'.$_nl;
		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Join_DateTime'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.dt_make_datetime ( $cl_join_ts, $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).'</td>'.$_nl;
		$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Country'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.$cl_country.'</td>'.$_nl;
		$_out .= '</tr>'.$_nl;
		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= $_td_str_left_span3.'<b>'.$_LANG['_CLIENTS']['l_Zip_Postal_Code'].$_sp.'</b></td>'.$_nl;
		$_out .= $_td_str_right.$cl_zip_code.'</td>'.$_nl;
		$_out .= '</tr>'.$_nl;
		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		IF ($cl_phone) {
			$_out .= $_td_str_left.'<b>'.$_LANG['_CLIENTS']['l_Phone'].$_sp.'</b></td>'.$_nl;
			$_out .= $_td_str_right.$cl_phone.'</td>'.$_nl;
		} ELSE {
			$_out .= $_td_str_left.$_sp.'</td>'.$_nl;
			$_out .= $_td_str_right.$_sp.'</td>'.$_nl;
		}
		$_out .= '</tr>'.$_nl;

		IF ( $_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP10] == 1 || $_PERMS[AP07] == 1) ) {
			$_GROUPS = do_decode_groups_user($cl_groups);
			$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
			$_out .= $_td_str_left_vtop.'<b>'.$_LANG['_CLIENTS']['l_Groups'].$_sp.'</b></td>'.$_nl;
			$_out .= $_td_str_right_span3.$_nl;
			IF ($cl_groups) {
				$_out .= $cl_groups.$_nl;
			} ELSE {
				$_out .= $_LANG['_CLIENTS']['l_None'];
			}
			IF ( $_GROUPS[UG08] == 1 && $_LANG['_BASE']['User_Groups_08'] != '' )
				{ IF ($_p != '') { $_p .= ', '; } $_p .= $_LANG['_BASE']['User_Groups_08']; }
			IF ( $_GROUPS[UG07] == 1 && $_LANG['_BASE']['User_Groups_07'] != '' )
				{ IF ($_p != '') { $_p .= ', '; } $_p .= $_LANG['_BASE']['User_Groups_07']; }
			IF ( $_GROUPS[UG06] == 1 && $_LANG['_BASE']['User_Groups_06'] != '' )
				{ IF ($_p != '') { $_p .= ', '; } $_p .= $_LANG['_BASE']['User_Groups_06']; }
			IF ( $_GROUPS[UG05] == 1 && $_LANG['_BASE']['User_Groups_05'] != '' )
				{ IF ($_p != '') { $_p .= ', '; } $_p .= $_LANG['_BASE']['User_Groups_05']; }
			IF ( $_GROUPS[UG04] == 1 && $_LANG['_BASE']['User_Groups_04'] != '' )
				{ IF ($_p != '') { $_p .= ', '; } $_p .= $_LANG['_BASE']['User_Groups_04']; }
			IF ( $_GROUPS[UG03] == 1 && $_LANG['_BASE']['User_Groups_03'] != '' )
				{ IF ($_p != '') { $_p .= ', '; } $_p .= $_LANG['_BASE']['User_Groups_03']; }
			IF ( $_GROUPS[UG02] == 1 && $_LANG['_BASE']['User_Groups_02'] != '' )
				{ IF ($_p != '') { $_p .= ', '; } $_p .= $_LANG['_BASE']['User_Groups_02']; }
			IF ( $_GROUPS[UG01] == 1 && $_LANG['_BASE']['User_Groups_01'] != '' )
				{ IF ($_p != '') { $_p .= ', '; } $_p .= $_LANG['_BASE']['User_Groups_01']; }
			IF ($_p != '') { $_out .= '<br>'.$_p.$_nl; }
			$_out .= '</td>'.$_nl;
			$_out .= '</tr>'.$_nl;
		}

		IF ( $_SEC['_sadmin_flg'] ) {
			$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl.$_td_str_span.'<hr>'.'</td>'.$_nl.'</tr>'.$_nl;
			$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
			$_out .= $_td_str_left_vtop.'<b>'.$_LANG['_CLIENTS']['l_Notes'].$_sp.'</b></td>'.$_nl;
			$_out .= $_td_str_right_span3.nl2br(do_stripslashes($cl_notes)).'</td>'.$_nl;
			$_out .= '</tr>'.$_nl;
		}
		$_out .= '</table>'.$_nl;

		$_out .= '</td></tr>'.$_nl;
		$_out .= '</table>'.$_nl;
		$_out .= '</div>'.$_nl;
		#	$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do list field form for: Client Domains
function do_view_client_domains( $adata, $aret_flag=0 )	{
		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Set Query for select.
			$query	= "SELECT dom_id, dom_domain, dom_registrar, dom_ts_expiration, dom_sa_expiration, dom_url_cp, dom_si_id";
			$_from	= " FROM ".$_DBCFG['domains'];
			$_where	= " WHERE dom_cl_id=".$adata['_suser_id'];
			$_order	= " ORDER BY dom_domain ASC";

			IF ( !$_CCFG['IPL_CLIENTS_ACCOUNT'] > 0 ) { $_CCFG['IPL_CLIENTS_ACCOUNT'] = 5; }
			$_limit .= " LIMIT 0, ".$_CCFG['IPL_CLIENTS_ACCOUNT'];

		# Get count of rows total:
			$query_ttl	= "SELECT COUNT(*)";
			$query_ttl .= $_from;
			$query_ttl .= $_where;
			$result_ttl	= db_query_execute($query_ttl);
			while(list($cnt) = db_fetch_row($result_ttl)) {	$numrows_ttl = $cnt; }

		# Do select listing records and return check
			$query		.= $_from.$_where.$_order.$_limit;
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build form output
			$_out .= '<br>'.$_nl;
			$_out .= '<div align="center">'.$_nl;
			$_out .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
			$_out .= '<tr class="BLK_DEF_TITLE"><td class="TP3MED_BC" colspan="6">'.$_nl;

			$_out .= '<table width="100%" cellpadding="0" cellspacing="0">'.$_nl;
			$_out .= '<tr class="BLK_IT_TITLE_TXT">'.$_nl.'<td class="TP0MED_NL">'.$_nl;
			$_out .= '<b>'.$_LANG['_CLIENTS']['Client_Domains'];
			$_out .= ' ('.$numrows.$_sp.$_LANG['_CLIENTS']['of'].$_sp.$numrows_ttl.$_sp.$_LANG['_CLIENTS']['total_entries'].')</b><br>'.$_nl;
			$_out .= '</td>'.$_nl.'<td class="TP0MED_NR">'.$_nl;
			IF ( $_CCFG['_IS_PRINT'] != 1 ) {
				IF ($numrows_ttl > $_CCFG['IPL_CLIENTS_ACCOUNT']) {
	   				$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=domains&mode=view&dom_cl_id='.$adata['_suser_id'], $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
	   			}
				IF ( $_SEC['_sadmin_flg'] ) {
					$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=domains', $_TCFG['_S_IMG_SEARCH_S'],$_TCFG['_S_IMG_SEARCH_S_MO'],'','');
				}
			} ELSE {
				$_out .= $_sp;
			}
			$_out .= '</td>'.$_nl.'</tr>'.$_nl.'</table>'.$_nl;

			$_out .= '</td></tr>'.$_nl;
			$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
			$_out .= '<td class="TP3SML_NL"><b>'.$_LANG['_CLIENTS']['l_Domains'].'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Registrar'].'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Server'].'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Domain_Expires'].'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_SACC_Expires'].'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_SACC'].'</b></td>'.$_nl;
			$_out .= '</tr>'.$_nl;

			# Process query results
            $todayis = dt_get_uts();
			while(list($dom_id, $dom_domain, $dom_registrar, $dom_ts_expiration, $dom_sa_expiration, $dom_url_cp, $dom_si_id) = db_fetch_row($result)) {
				$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				IF ( $_SEC['_sadmin_flg'] ) {
					IF ( $_PERMS[AP16] == 1 || $_PERMS[AP06] == 1 )
						{ $_pmode = 'edit'; } ELSE { $_pmode = 'view'; }
					$_edit_str = '<a href="'.$_SERVER["PHP_SELF"].'?mod=domains&mode='.$_pmode.'&dom_id='.$dom_id.'">'.$dom_domain.'</a>';
					$_out .= '<td class="TP3SML_NL">'.$_edit_str.'</td>'.$_nl;
				} ELSE {
					$_out .= '<td class="TP3SML_NL">'.$dom_domain.'</td>'.$_nl;
				}
				$_out .= '<td class="TP3SML_NC">'.$dom_registrar.'</td>'.$_nl;
				$_out .= '<td class="TP3SML_NC">'.do_get_server_name($dom_si_id).'</td>'.$_nl;
				$_out .= '<td class="TP3SML_NC">';

				# Display text "expired" if in past, else display date
					IF (($todayis > $dom_ts_expiration) && ($dom_ts_expiration)) {
    					$_out .= $_LANG['_CLIENTS']['l_Expired'];
					} ELSE {
					    $_out .= dt_make_datetime ( $dom_ts_expiration, $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] );
					}
					$_out .= '</td>'.$_nl;

					$_out .= '<td class="TP3SML_NC">';
				# Display text "expired" if in past, else display date
					IF (($todayis > $dom_sa_expiration) && ($dom_sa_expiration)) {
					    $_out .= $_LANG['_CLIENTS']['l_Expired'];
					} ELSE {
					    $_out .= dt_make_datetime ( $dom_sa_expiration, $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] );
					}
					$_out .= '</td>'.$_nl;

					$_out .= '<td class="TP3SML_NL"><nobr>'.$_nl;
					IF ( $_CCFG['_IS_PRINT'] != 1 ) {
						$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=domains&mode=mail&dom_id='.$dom_id, $_TCFG['_S_IMG_EMAIL_S'],$_TCFG['_S_IMG_EMAIL_S_MO'],'','');
						$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=domains&mode=view&dom_id='.$dom_id, $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
						$_out .= do_nav_link ('mod_print.php?mod=domains&mode=view&dom_id='.$dom_id, $_TCFG['_S_IMG_PRINT_S'],$_TCFG['_S_IMG_PRINT_S_MO'],'','');
						IF ( $dom_url_cp != '' && $_CCFG['DOM_CP_URL_LINK_ENABLE'] == 1 ) {
							$_out .= do_nav_link ($dom_url_cp, $_TCFG['_S_IMG_CP_S'],$_TCFG['_S_IMG_CP_S_MO'],'_new','');
						} ELSE {
							$_out .= $_TCFG['_IMG_BLANK_S'];
						}
						IF ( $_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP07] == 1) ) {
							$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=domains&mode=edit&dom_id='.$dom_id, $_TCFG['_S_IMG_EDIT_S'],$_TCFG['_S_IMG_EDIT_S_MO'],'','');
							$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=domains&mode=delete&stage=1&dom_id='.$dom_id.'&dom_domain='.$dom_domain, $_TCFG['_S_IMG_DEL_S'],$_TCFG['_S_IMG_DEL_S_MO'],'','');
						}
					}
					$_out .= '</nobr></td>'.$_nl;
					$_out .= '</tr>'.$_nl;
				}

			$_out .= '</table>'.$_nl;
			$_out .= '</div>'.$_nl;
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do list field form for: Client Orders
function do_view_client_orders( $adata, $aret_flag=0 ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query parameters for select.
		$query	.= "SELECT *";
		$_from	.= " FROM ".$_DBCFG['orders'];
		IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$_from .= ", ".$_DBCFG['vendors'];}
		$_from .= ", ".$_DBCFG['products'].' WHERE ';
		IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$_where	.= $_DBCFG['orders'].".ord_vendor_id = ".$_DBCFG['vendors'].".vendor_id AND ";}
		$_where	.= $_DBCFG['orders'].".ord_prod_id = ".$_DBCFG['products'].".prod_id";
		$_where	.= " AND ".$_DBCFG['orders'].".ord_cl_id = ".$adata['_suser_id'];
		$_order .= " ORDER BY ".$_DBCFG['orders'].".ord_id DESC";

		IF ( !$_CCFG['IPL_CLIENTS_ACCOUNT'] > 0 ) { $_CCFG['IPL_CLIENTS_ACCOUNT'] = 5; }
		$_limit .= " LIMIT 0, ".$_CCFG['IPL_CLIENTS_ACCOUNT'];

	# Get count of rows total:
		$query_ttl	= "SELECT COUNT(*)";
		$query_ttl .= $_from;
		$query_ttl .= $_where;
		$result_ttl	= db_query_execute($query_ttl);
		while(list($cnt) = db_fetch_row($result_ttl)) {	$numrows_ttl = $cnt; }

	# Do select listing records and return check
		$query		.= $_from.$_where.$_order.$_limit;
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form output
		IF ( $_CCFG['ORDERS_LIST_SHOW_PROD_DESC'] == 1 ) { $_temp_span = 6; } ELSE { $_temp_span = 7; }
		#	$_out .= '<br>'.$_nl;
		$_out .= '<div align="center">'.$_nl;
		$_out .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
		$_out .= '<tr class="BLK_DEF_TITLE"><td class="TP3MED_BC" colspan="'.$_temp_span.'">'.$_nl;

		$_out .= '<table width="100%" cellpadding="0" cellspacing="0">'.$_nl;
		$_out .= '<tr class="BLK_IT_TITLE_TXT">'.$_nl.'<td class="TP0MED_NL">'.$_nl;
		$_out .= '<b>'.$_LANG['_CLIENTS']['Client_Orders'];
		$_out .= ' ('.$numrows.$_sp.$_LANG['_CLIENTS']['of'].$_sp.$numrows_ttl.$_sp.$_LANG['_CLIENTS']['total_entries'].')</b><br>'.$_nl;
		$_out .= '</td>'.$_nl.'<td class="TP0MED_NR">'.$_nl;
		IF ( $_CCFG['_IS_PRINT'] != 1 ) {
			IF ($numrows_ttl > $_CCFG['IPL_CLIENTS_ACCOUNT']) {
				$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=view&ord_cl_id='.$adata['_suser_id'], $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
			}
			IF ( $_SEC['_sadmin_flg'] ) {
				$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=orders', $_TCFG['_S_IMG_SEARCH_S'],$_TCFG['_S_IMG_SEARCH_S_MO'],'','');
			}
		} ELSE {
			$_out .= $_sp;
		}
		$_out .= '</td>'.$_nl.'</tr>'.$_nl.'</table>'.$_nl;

		$_out .= '</td></tr>'.$_nl;
		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Order_Id'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Status'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Date'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NL"><b>'.$_LANG['_CLIENTS']['l_Domain'].'</b></td>'.$_nl;

		IF ( $_CCFG['ORDERS_LIST_SHOW_PROD_DESC'] == 1 ) {
			$_out .= '<td class="TP3SML_NL"><b>'.$_LANG['_CLIENTS']['l_Product_Description'].'</b></td>'.$_nl;
		} ELSE {
			IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$_out .= '<td class="TP3SML_NL"><b>'.$_LANG['_CLIENTS']['l_Vendor'].'</b></td>'.$_nl;}
			$_out .= '<td class="TP3SML_NL"><b>'.$_LANG['_CLIENTS']['l_Product'].'</b></td>'.$_nl;
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
					$_out .= '<td class="TP3SML_NL">'.$row['ord_domain'].'</td>'.$_nl;
					IF ( $_CCFG['ORDERS_LIST_SHOW_PROD_DESC'] == 1 )
						{	$_out .= '<td class="TP3SML_NL">'.$row['prod_desc'].'</td>'.$_nl; }
					ELSE
						{
							IF ($_CCFG['SHOW_PAYMENT_METHOD']) {$_out .= '<td class="TP3SML_NL">'.$row['vendor_name'].'</td>'.$_nl;}
							$_out .= '<td class="TP3SML_NL">'.$row['prod_name'].'</td>'.$_nl;
						}
					$_out .= '<td class="TP3SML_NL"><nobr>'.$_nl;

					IF ( $_CCFG['_IS_PRINT'] != 1 ) {
							$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=mail&ord_id='.$row['ord_id'], $_TCFG['_S_IMG_EMAIL_S'],$_TCFG['_S_IMG_EMAIL_S_MO'],'','');
							$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=view&ord_id='.$row['ord_id'], $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
							$_out .= do_nav_link ('mod_print.php?mod=orders&mode=view&ord_id='.$row['ord_id'], $_TCFG['_S_IMG_PRINT_S'],$_TCFG['_S_IMG_PRINT_S_MO'],'','');
							IF ( $_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP07] == 1) ) {
								$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=edit&ord_id='.$row['ord_id'], $_TCFG['_S_IMG_EDIT_S'],$_TCFG['_S_IMG_EDIT_S_MO'],'','');
								$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=orders&mode=delete&stage=1&ord_id='.$row['ord_id'].'&ord_name_first='.$row['ord_name_first'].'&ord_name_last='.$row['ord_name_last'], $_TCFG['_S_IMG_DEL_S'],$_TCFG['_S_IMG_DEL_S_MO'],'','');
							}
						}

					$_out .= '</nobr></td>'.$_nl;
					$_out .= '</tr>'.$_nl;
				}
			}

			$_out .= '</table>'.$_nl;
			$_out .= '</div>'.$_nl;
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do list field form for: Client Invoices
function do_view_client_invoices( $adata, $aret_flag=0 ) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Set Query parameters for select.
		$query	.= "SELECT *";
		$_from	.= " FROM ".$_DBCFG['invoices'].", ".$_DBCFG['clients'];
		$_where	.= " WHERE ".$_DBCFG['invoices'].".invc_cl_id = ".$_DBCFG['clients'].".cl_id";
		$_where	.= " AND ".$_DBCFG['invoices'].".invc_cl_id = ".$adata['_suser_id'];

		IF ( !$_SEC['_sadmin_flg'] ) {
			$_where .= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG['INV_STATUS'][1]."'";

		# Check show pending enable flag
			IF ( !$_CCFG['INVC_SHOW_CLIENT_PENDING'] ) {
				$_where .= " AND ".$_DBCFG['invoices'].".invc_status != '".$_CCFG['INV_STATUS'][4]."'";
			}
		}

		$_order	.= " ORDER BY ".$_DBCFG['invoices'].".invc_id DESC";

		IF ( !$_CCFG['IPL_CLIENTS_ACCOUNT'] > 0 ) { $_CCFG['IPL_CLIENTS_ACCOUNT'] = 5; }
		$_limit .= " LIMIT 0, ".$_CCFG['IPL_CLIENTS_ACCOUNT'];

	# Get count of rows total:
		$query_ttl	= "SELECT COUNT(*)";
		$query_ttl .= $_from;
		$query_ttl .= $_where;
		$result_ttl	= db_query_execute($query_ttl);
		while(list($cnt) = db_fetch_row($result_ttl)) {	$numrows_ttl = $cnt;	}

	# Do select listing records and return check
		$query		.= $_from.$_where.$_order.$_limit;
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form output
	#	$_out .= '<br>'.$_nl;
		$_out .= '<div align="center">'.$_nl;
		$_out .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
		$_out .= '<tr class="BLK_DEF_TITLE"><td class="TP3MED_NC" colspan="8">'.$_nl;

		$_out .= '<table width="100%" cellpadding="0" cellspacing="0">'.$_nl;
		$_out .= '<tr class="BLK_IT_TITLE_TXT">'.$_nl.'<td class="TP0MED_NL">'.$_nl;
		$_out .= '<b>'.$_LANG['_CLIENTS']['Client_Invoices'];
		$_out .= ' ('.$numrows.$_sp.$_LANG['_CLIENTS']['of'].$_sp.$numrows_ttl.$_sp.$_LANG['_CLIENTS']['total_entries'].')</b><br>'.$_nl;
		$_out .= '</td>'.$_nl.'<td class="TP0MED_NR">'.$_nl;
		IF ( $_CCFG['_IS_PRINT'] != 1 ) {
			IF ($numrows_ttl > $_CCFG['IPL_CLIENTS_ACCOUNT']) {
				$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&invc_cl_id='.$adata['_suser_id'], $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
			}
			IF ( $_SEC['_sadmin_flg'] ) {
				$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=invoices', $_TCFG['_S_IMG_SEARCH_S'],$_TCFG['_S_IMG_SEARCH_S_MO'],'','');
			}
		} ELSE {
			$_out .= $_sp;
		}
		$_out .= '</td>'.$_nl.'</tr>'.$_nl.'</table>'.$_nl;

		$_out .= '</td></tr>'.$_nl;
		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Invoice_Id'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Status'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Date'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Date_Due'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NR"><b>'.$_LANG['_CLIENTS']['l_Amount'].$_sp.$_sp.'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NR"><b>'.$_LANG['_CLIENTS']['l_Balance'].$_sp.$_sp.'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NL"><b>'.$_LANG['_CLIENTS']['l_Full_Name'].'</b></td>'.$_nl;
		#	$_out .= '<td class="TP3SML_NL"><b>'.$_LANG['_CLIENTS']['l_User_Name'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_sp.'</b></td>'.$_nl;
		$_out .= '</tr>'.$_nl;

	# Process query results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {

			# Color code recurring invoices
				IF ($row['invc_recurring']) {
					$_out .= '<tr class="GRN_DEF_ENTRY">'.$_nl;
				} ELSE {
					$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				}
				$_out .= '<td class="TP3SML_NC">'.$row['invc_id'].'</td>'.$_nl;
				$_out .= '<td class="TP3SML_NC">'.$row['invc_status'].'</td>'.$_nl;
				$_out .= '<td class="TP3SML_NC">'.dt_make_datetime ( $row['invc_ts'], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] ).'</td>'.$_nl;
				$_out .= '<td class="TP3SML_NC">'.dt_make_datetime ( $row['invc_ts_due'], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] ).'</td>'.$_nl;
				$_out .= '<td class="TP3SML_NR">'.do_currency_format( $row['invc_total_cost'] ).$_sp.$_sp.'</td>'.$_nl;

			# Show curent invoice balance
				$idata = do_get_invc_cl_balance($adata['_suser_id'], $row['invc_id']);
				$_out .= '<td class="TP3SML_NR">'.do_currency_format( $idata['net_balance'] ).$_sp.'</td>'.$_nl;

				$_out .= '<td class="TP3SML_NL">'.$row['cl_name_last'].','.$_sp.$row['cl_name_first'].'</td>'.$_nl;
				#	$_out .= '<td class="TP3SML_NL">'.$row['cl_user_name'].'</td>'.$_nl;
				$_out .= '<td class="TP3SML_NL"><nobr>'.$_nl;

				IF ( $_CCFG['_IS_PRINT'] != 1 ) {
					$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=mail&invc_id='.$row['invc_id'], $_TCFG['_S_IMG_EMAIL_S'],$_TCFG['_S_IMG_EMAIL_S_MO'],'','');
					$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&invc_id='.$row['invc_id'], $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
					$_out .= do_nav_link ('mod_print.php?mod=invoices&mode=view&invc_id='.$row['invc_id'], $_TCFG['_S_IMG_PRINT_S'],$_TCFG['_S_IMG_PRINT_S_MO'],'_new','');
					IF ( $_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP07] == 1) ) {
						$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=edit&invc_id='.$row['invc_id'], $_TCFG['_S_IMG_EDIT_S'],$_TCFG['_S_IMG_EDIT_S_MO'],'','');
						$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=delete&stage=1&invc_id='.$row['invc_id'].'&invc_ts='.$row[invc_ts].'&invc_status='.$row[invc_status], $_TCFG['_S_IMG_DEL_S'],$_TCFG['_S_IMG_DEL_S_MO'],'','');
					}
				}
				$_out .= '</nobr></td>'.$_nl;
				$_out .= '</tr>'.$_nl;
			}
		}

	# Show totals footer row
		$idata = do_get_invc_cl_balance($adata['_suser_id']);
		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= '<td class="TP3SML_BR" colspan="4">'.$_nl;
		$_out .= $_LANG['_CLIENTS']['l_Amount'].$_nl;
		$_out .= '</td><td class="TP3SML_BR">'.$_nl;
		$_out .= do_currency_format ( $idata['total_cost'] ).$_sp.$_sp.$_nl;
		$_out .= '</td><td class="TP3SML_BR">'.$_nl;
		$_out .= do_currency_format ( $idata['net_balance'] ).$_sp.$_sp.$_nl;
		$_out .= '</td><td class="TP3SML_BL" colspan="2">'.$_nl;
		$_out .= $_sp.$_nl;
		$_out .= '</td></tr>'.$_nl;

		$_out .= '</table>'.$_nl;
		$_out .= '</div>'.$_nl;
		$_out .= '<br>'.$_nl;

	# Return results
		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do list field form for: Tickets
function do_view_client_tickets($adata, $aret_flag=0)
	{
		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Set Query parameters for select.
			$query	.= "SELECT *";
			$_from	.= " FROM ".$_DBCFG['helpdesk'];
			$_where	.= " WHERE ".$_DBCFG['helpdesk'].".hd_tt_cl_id = ".$adata['_suser_id'];
			$_order	.= " ORDER BY ".$_DBCFG['helpdesk'].".hd_tt_id DESC";

			IF ( !$_CCFG['IPL_CLIENTS_ACCOUNT'] > 0 ) { $_CCFG['IPL_CLIENTS_ACCOUNT'] = 5; }
			$_limit .= " LIMIT 0, ".$_CCFG['IPL_CLIENTS_ACCOUNT'];

		# Get count of rows total:
			$query_ttl	= "SELECT COUNT(*)";
			$query_ttl .= $_from;
			$query_ttl .= $_where;
			$result_ttl	= db_query_execute($query_ttl);
			while(list($cnt) = db_fetch_row($result_ttl)) {	$numrows_ttl = $cnt;	}

		# Do select listing records and return check
			$query		.= $_from.$_where.$_order.$_limit;
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build form output
			$_out .= '<div align="center">'.$_nl;
			$_out .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
			$_out .= '<tr class="BLK_DEF_TITLE"><td class="TP3MED_NC" colspan="7">'.$_nl;

			$_out .= '<table width="100%" cellpadding="0" cellspacing="0">'.$_nl;
			$_out .= '<tr class="BLK_IT_TITLE_TXT">'.$_nl.'<td class="TP0MED_NL">'.$_nl;
			$_out .= '<b>'.$_LANG['_CLIENTS']['Client_Support_Tickets'];
			$_out .= ' ('.$numrows.$_sp.$_LANG['_CLIENTS']['of'].$_sp.$numrows_ttl.$_sp.$_LANG['_CLIENTS']['total_entries'].')</b><br>'.$_nl;
			$_out .= '</td>'.$_nl.'<td class="TP0MED_NR">'.$_nl;
			IF ( $_CCFG['_IS_PRINT'] != 1 ) {
				IF ($numrows_ttl > $_CCFG['IPL_CLIENTS_ACCOUNT']) {
	   				$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=helpdesk&hd_tt_cl_id='.$adata['_suser_id'], $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
	   			}
				IF ( $_SEC['_sadmin_flg'] ) {
					$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=cc&mode=search&sw=helpdesk', $_TCFG['_S_IMG_SEARCH_S'],$_TCFG['_S_IMG_SEARCH_S_MO'],'','');
				}
			} ELSE {
				$_out .= $_sp;
			}
			$_out .= '</td>'.$_nl.'</tr>'.$_nl.'</table>'.$_nl;

			$_out .= '</td></tr>'.$_nl;
			$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Id'].'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Date'].'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NL"><b>'.$_LANG['_CLIENTS']['l_Subject'].'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Priority'].'</b></td>'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Status'].'</b> </td>'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Closed'].'</b> </td>'.$_nl;
			$_out .= '<td class="TP3SML_NC"><b>'.$_sp.'</b></td>'.$_nl;
			$_out .= '</tr>'.$_nl;

			# Process query results
			IF ( $numrows ) {
				while ($row = db_fetch_array($result))
				{
					$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
					$_out .= '<td class="TP3SML_NC">'.$row['hd_tt_id'].'</td>'.$_nl;
					$_out .= '<td class="TP3SML_NC">'.dt_make_datetime ( $row['hd_tt_time_stamp'], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] ).'</td>'.$_nl;
					$_out .= '<td class="TP3SML_NL">'.do_stripslashes($row['hd_tt_subject']).'</td>'.$_nl;
					$_out .= '<td class="TP3SML_NC">'.$row['hd_tt_priority'].'</td>'.$_nl;
					$_out .= '<td class="TP3SML_NC">'.$row['hd_tt_status'].' </td>'.$_nl;
					$_out .= '<td class="TP3SML_NC">'. do_valtostr_no_yes($row['hd_tt_closed']).' </td>'.$_nl;
					$_out .= '<td class="TP3SML_NL"><nobr>'.$_nl;
					IF ( $_CCFG['_IS_PRINT'] != 1 ) {
						$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=helpdesk&mode=mail&hd_tt_id='.$row['hd_tt_id'], $_TCFG['_S_IMG_EMAIL_S'],$_TCFG['_S_IMG_EMAIL_S_MO'],'','');
						$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=helpdesk&mode=view&hd_tt_id='.$row['hd_tt_id'], $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
						$_out .= do_nav_link ('mod_print.php?mod=helpdesk&mode=view&hd_tt_id='.$row['hd_tt_id'], $_TCFG['_S_IMG_PRINT_S'],$_TCFG['_S_IMG_PRINT_S_MO'],'_new','');
						IF ( $_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP09] == 1) ) {
							$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=helpdesk&mode=delete&hd_tt_id='.$row['hd_tt_id'], $_TCFG['_S_IMG_DEL_S'],$_TCFG['_S_IMG_DEL_S_MO'],'','');
						}
					}
					$_out .= '</nobr></td>'.$_nl;
					$_out .= '</tr>'.$_nl;
				}
			}

			$_out .= '</table>'.$_nl;
			$_out .= '</div>'.$_nl;
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do list field form for: Emails
function do_view_client_emails($adata, $aret_flag=0) {
	# Get security vars
		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = ""; $result= ""; $numrows = 0;

	# Get all email addresses for a client
		$clinfo = get_contact_client_info( $adata['_suser_id'] );
	    $cl_emails = get_contact_client_info_alias($adata['_suser_id'],1);
	    $x = sizeof($cl_emails);

	# Set Query parameters for select.
		$query	.= "SELECT *";
		$_from	.= " FROM ".$_DBCFG['mail_archive'];
		$_where	= " WHERE ".$_DBCFG['mail_archive'].".ma_fld_from LIKE '%".$clinfo['cl_email']."%'";
		$_where	.= " OR ".$_DBCFG['mail_archive'].".ma_fld_recip LIKE '%".$clinfo['cl_email']."%'";
		IF ($x) {
			FOR ($i=1; $i<=$x; $i++) {
	   			$_where	.= " OR ".$_DBCFG['mail_archive'].".ma_fld_from LIKE '%".$cl_emails[$i]['c_email']."%'";
				$_where	.= " OR ".$_DBCFG['mail_archive'].".ma_fld_recip LIKE '%".$cl_emails[$i]['c_email']."%'";
			}
		}

		$_order	.= " ORDER BY ".$_DBCFG['mail_archive'].".ma_id DESC";

		IF ( !$_CCFG['IPL_CLIENTS_ACCOUNT'] > 0 ) { $_CCFG['IPL_CLIENTS_ACCOUNT'] = 5; }
		$_limit .= " LIMIT 0, ".$_CCFG['IPL_CLIENTS_ACCOUNT'];

	# Get count of rows total:
		$query_ttl	= "SELECT COUNT(*)";
		$query_ttl .= $_from;
		$query_ttl .= $_where;
		$result_ttl	= db_query_execute($query_ttl);
		while(list($cnt) = db_fetch_row($result_ttl)) {$numrows_ttl = $cnt;}

	# Do select listing records and return check
		$query		.= $_from.$_where.$_order.$_limit;
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Build form output
		$_out .= '<div align="center">'.$_nl;
		$_out .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
		$_out .= '<tr class="BLK_DEF_TITLE"><td class="TP3MED_NC" colspan="7">'.$_nl;

		$_out .= '<table width="100%" cellpadding="0" cellspacing="0">'.$_nl;
		$_out .= '<tr class="BLK_IT_TITLE_TXT">'.$_nl.'<td class="TP0MED_NL">'.$_nl;
		$_out .= '<b>'.$_LANG['_CLIENTS']['l_Email'];
		$_out .= ' ('.$numrows.$_sp.$_LANG['_CLIENTS']['of'].$_sp.$numrows_ttl.$_sp.$_LANG['_CLIENTS']['total_entries'].')</b><br>'.$_nl;
		$_out .= '</td>'.$_nl.'<td class="TP0MED_NR">'.$_nl;
		IF ( $_CCFG['_IS_PRINT'] != 1 ) {
			IF ($numrows_ttl > $_CCFG['IPL_CLIENTS_ACCOUNT']) {
				$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=mail&mode=search&sw=archive&search_type=1&s_to='.$clinfo['cl_email'].'&s_from='.$clinfo['cl_email'], $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
			}
			IF ( $_SEC['_sadmin_flg'] ) {
				$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=mail&mode=search&sw=archive&search_type=1&s_to='.$clinfo['cl_email'].'&s_from='.$clinfo['cl_email'], $_TCFG['_S_IMG_SEARCH_S'],$_TCFG['_S_IMG_SEARCH_S_MO'],'','');
			}
		} ELSE {
			$_out .= $_sp;
		}
		$_out .= '</td>'.$_nl.'</tr>'.$_nl.'</table>'.$_nl;

		$_out .= '</td></tr>'.$_nl;
		$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Id'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_LANG['_CLIENTS']['l_Date'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NL"><b>'.$_LANG['_CLIENTS']['l_Subject'].'</b></td>'.$_nl;
		$_out .= '<td class="TP3SML_NC"><b>'.$_sp.'</b></td>'.$_nl;
		$_out .= '</tr>'.$_nl;

	# Process query results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {
				$_out .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
				$_out .= '<td class="TP3SML_NC">'.$row['ma_id'].'</td>'.$_nl;
				$_out .= '<td class="TP3SML_NC">'.dt_make_datetime ( $row['ma_time_stamp'], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] ).'</td>'.$_nl;
				$_out .= '<td class="TP3SML_NL">'.do_stripslashes($row['ma_fld_subject']).'</td>'.$_nl;
				$_out .= '<td class="TP3SML_NL"><nobr>'.$_nl;
				IF ( $_CCFG['_IS_PRINT'] != 1 ) {
					$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=mail&mode=resend&obj=arch&ma_id='.$row['ma_id'], $_TCFG['_S_IMG_EMAIL_S'],$_TCFG['_S_IMG_EMAIL_S_MO'],'','');
					$_out .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=mail&mode=view&obj=arch&ma_id='.$row['ma_id'], $_TCFG['_S_IMG_VIEW_S'],$_TCFG['_S_IMG_VIEW_S_MO'],'','');
					$_out .= do_nav_link ('mod_print.php?mod=mail&mode=view&obj=arch&ma_id='.$row['ma_id'], $_TCFG['_S_IMG_PRINT_S'],$_TCFG['_S_IMG_PRINT_S_MO'],'_new','');
					IF ( $_SEC['_sadmin_flg'] && ($_PERMS[AP16] == 1 || $_PERMS[AP05] == 1) ) {
						$_out .= '&nbsp;'.do_nav_link ($_SERVER["PHP_SELF"].'?mod=mail&mode=delete&obj=arch&stage=2&ma_id='.$row['ma_id'], $_TCFG['_S_IMG_DEL_S'],$_TCFG['_S_IMG_DEL_S_MO'],'','');
					}
				}
				$_out .= '</nobr></td>'.$_nl;
				$_out .= '</tr>'.$_nl;
			}
		}

		$_out .= '</table>'.$_nl;
		$_out .= '</div>'.$_nl;
		$_out .= '<br>'.$_nl;

	# Return results
		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


# Do email Client Profile
function do_mail_profile($adata, $aret_flag=0) {
    # Get security vars
		$_SEC = get_security_flags ();

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Call common.php function for client info.
		$_cl_info = get_mtp_client_info( $adata['cl_id'] );

	# Check Return and process results
		IF ( $_cl_info['numrows'] > 0 ) {

    	# Set data array
			$_MTP		= $adata;

		# Merge Data Array with returned row
			$data_new	= array_merge( $_MTP, $_cl_info );
			$_MTP		= $data_new;
		} ELSE {
			$_mail_error_flg = 1;
			$_mail_error_str .= '<br>'.$_LANG['_CLIENTS']['CL_EMAIL_MSG_01_PRE'].$_sp.$adata['cl_id'].$_sp.$_LANG['_CLIENTS']['CL_EMAIL_MSG_01_SUF'];
		}

	# Get mail contact information array
		$_cinfo	= get_contact_info($_CCFG['MC_ID_SUPPORT']);

	# Set eMail Parameters (pre-eval template, some used in template)

		IF ($_CCFG['_PKG_SAFE_EMAIL_ADDRESS']) {
   			$mail['recip']		= $_MTP['cl_email'];
			$mail['from']		= $_cinfo['c_email'];
		} ELSE {
			$mail['recip']		= $_MTP['cl_name_first'].' '.$_MTP['cl_name_last'].' <'.$_MTP['cl_email'].'>';
			$mail['from']		= $_CCFG['_PKG_NAME_SHORT'].'-'.$_cinfo['c_name'].' <'.$_cinfo['c_email'].'>';
		}
		IF ( $_CCFG['CLIENT_EMAIL_CC_ENABLE'] ) {
    		IF ($_CCFG['_PKG_SAFE_EMAIL_ADDRESS']) {
   				$mail['cc']	= $_cinfo['c_email'];
			} ELSE {
				$mail['cc']	= $_CCFG['_PKG_NAME_SHORT'].'-'.$_cinfo['c_name'].' <'.$_cinfo['c_email'].'>';
			}
		} ELSE {
			$mail['cc']	= '';
		}
		$mail['subject']	= $_CCFG['_PKG_NAME_SHORT'].$_LANG['_CLIENTS']['CL_EMAIL_SUBJECT'];

	# Set MTP (Mail Template Parameters) array
		$_MTP['to_name']		= $_MTP['cl_name_first'].' '.$_MTP['cl_name_last'];
		$_MTP['to_email']		= $_MTP['cl_email'];
		$_MTP['from_name']		= $_cinfo['c_name'];
		$_MTP['from_email']		= $_cinfo['c_email'];
		$_MTP['subject']		= $mail['subject'];
		$_MTP['site']			= $_CCFG['_PKG_NAME_SHORT'];
		$_MTP['cl_url']			= $_CCFG['_PKG_URL_BASE'].'mod.php?mod=clients&mode=view&cl_id='.$adata['cl_id'];

	# Check returned records, don't send if not 1
		$_ret = 1;
		IF ( $_cl_info['numrows'] == 1 ) {

		# Load message template (processed)
			$mail['message']	.= get_mail_template ('email_profile_copy', $_MTP);
		# Call basic email function (ret=1 on error)
			$_ret = do_mail_basic ($mail);
		}

	# Check return
		IF ( $_ret ) {
			$_ret_msg  = $_LANG['_CLIENTS']['CL_EMAIL_MSG_02_L1'];
			$_ret_msg .= '<br>'.$_LANG['_CLIENTS']['CL_EMAIL_MSG_02_L2'];
		} ELSE {
			$_ret_msg  = $_LANG['_CLIENTS']['CL_EMAIL_MSG_03_PRE'].$_sp.$adata['cl_id'].$_sp.$_LANG['_CLIENTS']['CL_EMAIL_MSG_03_SUF'];
		}

	# Build Title String, Content String, and Footer Menu String
		$_tstr = $_LANG['_CLIENTS']['CL_EMAIL_RESULT_TITLE'];

		$_cstr .= '<center>'.$_nl;
		$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
		$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
		$_cstr .= $_ret_msg.$_nl;
		$_cstr .= '</td></tr>'.$_nl;
		$_cstr .= '</table>'.$_nl;
		$_cstr .= '</center>'.$_nl;

		$_mstr_flag	= '1';
		IF ($_SEC['_sadmin_flg']) { $_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'',''); }
		$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=clients&mode=view&cl_id='.$adata[cl_id], $_TCFG['_IMG_BACK_TO_CLIENT_M'],$_TCFG['_IMG_BACK_TO_CLIENT_M_MO'],'','');

	# Call block it function
		$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
		$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * End Module Functions
**************************************************************/

?>
