<?php

/**************************************************************
 * File: 	Control Panel: SiteInfo
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
	IF (!eregi("admin.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01&url=admin.php');
			exit;
		}

/**************************************************************
 * CP Functions Code
**************************************************************/
# Do Data Input Validate
function cp_do_input_validation($_GPV)
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Check modes and data as required
		#	IF (!$_GPV[si_id])			{ $err_entry['flag'] = 1; $err_entry['si_id'] = 1; }
			IF (!$_GPV[si_group])		{ $err_entry['flag'] = 1; $err_entry['si_group'] = 1; }
			IF (!$_GPV[si_name])		{ $err_entry['flag'] = 1; $err_entry['si_name'] = 1; }
			IF (!$_GPV[si_desc])		{ $err_entry['flag'] = 1; $err_entry['si_desc'] = 1; }
		#	IF (!$_GPV[si_block_it])	{ $err_entry['flag'] = 1; $err_entry['si_block_it'] = 1; }
			IF (!$_GPV[si_title])		{ $err_entry['flag'] = 1; $err_entry['si_title'] = 1; }
			IF (!$_GPV[si_code])		{ $err_entry['flag'] = 1; $err_entry['si_code'] = 1; }
		#	IF (!$_GPV[si_status])		{ $err_entry['flag'] = 1; $err_entry['si_status'] = 1; }

		return $err_entry;
	}


# Do list field form for: SiteInfo
function cp_do_select_form_siteinfo($aaction, $aname, $avalue, $aret_flag=0)
	{
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
			$_out .= '<FORM METHOD="POST" ACTION="'.$aaction.'">'.$_nl;
			$_out .= '<table cellpadding="5" width="100%">'.$_nl;
			$_out .= '<tr><td class="TP3SML_NC">'.$_nl;
			$_out .= '<b>'.$_LANG['_ADMIN']['l12_SiteInfo_Select'].$_sp.'('.$numrows.')</b><br>'.$_nl;
			$_out .= '</td></tr>'.$_nl;
			$_out .= '<tr><td class="TP3SML_NC">'.$_nl;
			$_out .= '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'" onchange="submit();">'.$_nl;
			$_out .= '<option value="0">'.$_LANG['_ADMIN']['Please_Select'].'</option>'.$_nl;

			# Process query results
				while(list($si_id, $si_group, $si_name, $si_desc, $si_title) = db_fetch_row($result))
				{
					$_out .= '<option value="'.$si_id.'">'.str_pad($si_id,3,'0',STR_PAD_LEFT).' - '.$si_name.'</option>'.$_nl;
				}

			$_out .= '</select>'.$_nl;
			$_out .= '</td></tr>'.$_nl;
			$_out .= '<tr><td class="TP3SML_NC">'.$_nl;
			$_out .= do_input_button_class_sw ('b_load', 'SUBMIT', $_LANG['_ADMIN']['B_Load_Entry'], 'button_form_h', 'button_form', '1').$_nl;
			$_out .= '</td></tr>'.$_nl;
			$_out .= '</table>'.$_nl;
			$_out .= '</FORM>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do Form for Add / Edit
function cp_do_form_add_edit_siteinfo( $adata, $aerr_entry, $aret_flag=0)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Build op dependent strings
			switch ($adata['op'])
			{
				case "add":
					$op_proper		= $_LANG['_ADMIN']['B_Add'];
					$op_button		= $_LANG['_ADMIN']['B_Add'];
					break;
				case "edit":
					$op_proper		= $_LANG['_ADMIN']['B_Edit'];
					$op_button		= $_LANG['_ADMIN']['B_Save'];
					break;
				default:
					$adata['op']	= 'add';
					$op_proper		= $_LANG['_ADMIN']['B_Add'];
					$op_button		= $_LANG['_ADMIN']['B_Add'];
					break;
			}

		# Build common td start tag / strings (reduce text)
			$_td_str_left_vtop	= '<td class="TP1SML_NR" width="25%" valign="top">';
			$_td_str_left		= '<td class="TP1SML_NR" width="25%">';
			$_td_str_right		= '<td class="TP1SML_NL" width="75%">';

		# Build Title String, Content String, and Footer Menu String
			$_tstr .= $op_proper.$_sp.$_LANG['_ADMIN']['SiteInfo_Entry'].$_sp.'('.$_LANG['_ADMIN']['all_fields_required'].')';

		# Do data entry error string check and build
			IF ($aerr_entry['flag'])
				{
				 	$err_str = $_LANG['_ADMIN']['AD_ERR00__HDR1'].'<br>'.$_LANG['_ADMIN']['AD_ERR00__HDR1'].'<br>';

			 		IF ($aerr_entry['si_id']) 		{ $err_str .= $_LANG['_ADMIN']['AD12_ERR_01']; $err_prv = 1; }
					IF ($aerr_entry['si_group']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ADMIN']['AD12_ERR_02']; $err_prv = 1; }
					IF ($aerr_entry['si_name']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ADMIN']['AD12_ERR_03']; $err_prv = 1; }
					IF ($aerr_entry['si_desc']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ADMIN']['AD12_ERR_04']; $err_prv = 1; }
					IF ($aerr_entry['si_title']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ADMIN']['AD12_ERR_05']; $err_prv = 1; }
					IF ($aerr_entry['si_code']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_ADMIN']['AD12_ERR_06']; $err_prv = 1; }

	 		 		$_cstr .= '<p align="center"><b>'.$err_str.'</b>'.$_nl;
				}

		# Do Main Form
			$_cstr .= '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?cp=siteinfo&op='.$adata['op'].'">'.$_nl;
			$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SiteInfo_ID'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			IF ( $adata['op'] == 'add' )
				{ $_cstr .= '('.$_LANG['_ADMIN']['auto-assigned'].')'.$_nl; }
			ELSE
				{ $_cstr .= $adata[si_id].$_nl; }
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SI_Group'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="si_group" SIZE=32 value="'.$adata[si_group].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SI_Name'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="si_name" SIZE=32 value="'.$adata[si_name].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SI_Seq_No'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="si_seq_no" SIZE=6 value="'.$adata[si_seq_no].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SI_Description'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="si_desc" SIZE=50 value="'.$adata[si_desc].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_Status'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_select_list_off_on('si_status', $adata[si_status], 1);
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.'<hr></td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SI_Block_It'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_select_list_no_yes('si_block_it', $adata[si_block_it], 1);
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_Footer_Menu'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_select_list_no_yes('si_footer_menu', $adata[si_footer_menu], 1);
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_Title'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="si_title" SIZE=50 value="'.$adata[si_title].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left_vtop.'<b>'.$_LANG['_ADMIN']['l12_Code'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<TEXTAREA class="PSML_NL" NAME="si_code" COLS=60 ROWS=25>'.$adata[si_code].'</TEXTAREA>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= '<td class="TP0SML_NC" width="100%" colspan="2">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="stage" value="1">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="si_id" value="'.$adata[si_id].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_input_button_class_sw ('b_edit', 'SUBMIT', $op_button, 'button_form_h', 'button_form', '1').$_nl;
			$_cstr .= do_input_button_class_sw ('b_reset', 'RESET', $_LANG['_ADMIN']['B_Reset'], 'button_form_h', 'button_form', '1').$_nl;
			IF ($adata['op']=="edit")
				{ $_cstr .= do_input_button_class_sw ('b_delete', 'SUBMIT', $_LANG['_ADMIN']['B_Delete_Entry'], 'button_form_h', 'button_form', '1').$_nl; }
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;
			$_cstr .= '</FORM>'.$_nl;

			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"], $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo&op=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do display entry (individual entry)
function cp_do_display_entry_siteinfo ( $adata, $aret_flag=0)
	{
		# Get security vars
			$_SEC = get_security_flags ();
			$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Build common td start tag / strings (reduce text)
			$_td_str_left_vtop	= '<td class="TP1SML_NR" width="25%" valign="top">';
			$_td_str_colsp2		= '<td class="TP1SML_NJ" width="25%" colspan="2">';
			$_td_str_left		= '<td class="TP1SML_NR" width="25%">';
			$_td_str_right		= '<td class="TP1SML_NL" width="75%">';

		# Build Title String, Content String, and Footer Menu String
			$_tstr .= '<table width="100%">'.$_nl;
			$_tstr .= '<tr class="BLK_IT_TITLE_TXT" valign="bottom">'.$_nl;
			$_tstr .= '<td class="TP3MED_BL">'.$adata[si_name].'</td>'.$_nl;
			$_tstr .= '<td class="TP3MED_BR">'.$_sp.'</td>'.$_nl;
			$_tstr .= '</tr>'.$_nl;
			$_tstr .= '</table>'.$_nl;

			$_cstr .= '<table width="100%">'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SiteInfo_ID'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$adata[si_id].'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SI_Group'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$adata[si_group].'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SI_Name'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$adata[si_name].'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SI_Seq_No'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$adata[si_seq_no].'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SI_Description'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.do_stripslashes($adata[si_desc]).'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_Status'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.do_valtostr_off_on($adata[si_status]).'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_SI_Block_It'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.do_valtostr_no_yes($adata[si_block_it]).'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_Footer_Menu'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.do_valtostr_no_yes($adata[si_footer_menu]).'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_ADMIN']['l12_Title'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.do_stripslashes($adata[si_title]).'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_left_vtop.'<b>'.$_LANG['_ADMIN']['l12_Code'].$_sp.'('.$_LANG['_ADMIN']['output_below'].')</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_sp.'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_colsp2.'<hr></td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr valign="bottom">'.$_nl;
			$_cstr .= $_td_str_colsp2.do_stripslashes($adata[si_code]).'</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;

			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"], $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
			IF ( $_PERMS[AP16] == 1 || $_PERMS[AP15] == 1 || $_PERMS[AP14] == 1 )
				{
					$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo&op=edit&si_id='.$adata[si_id], $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
					$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo&op=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
				}
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * CP Base Code
**************************************************************/
# Get security vars
	$_SEC 	= get_security_flags ();
	$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

# Check $_GPV[op] (operation switch)
	switch($_GPV[op])
	{
		case "add":
			IF ( $_GPV['b_delete'] != '' ) { $_GPV[op] = 'delete'; }
			break;
		case "delete":
			break;
		case "edit":
			IF ( $_GPV['b_delete'] != '' ) { $_GPV[op] = 'delete'; }
			break;
		case "view":
			break;
		default:
			$_GPV[op]="none";
			break;
	} #end cp switch

# Check required fields (err / action generated later in cade as required)
	IF ( $_GPV[stage]==1 )
		{
			# Call validate input function
				$err_entry = cp_do_input_validation($_GPV);
		}

# Build Data Array (may also be over-ridden later in code)
	$data	= $_GPV;


##############################
# Operation:	Any Perm Check
# Summary:
#	- Exit out on perm error.
##############################
IF ( $_PERMS[AP16] != 1 && $_PERMS[AP15] != 1 && $_PERMS[AP14] != 1 )
	{
		IF ( $_PERMS[AP10] == 1 )
			{ $_GPV[op] = 'view'; }
		ELSE
			{
				$_out .= '<!-- Start content -->'.$_nl;
				$_out .= do_no_permission_message ();
				$_out .= '<br>'.$_nl;
				echo $_out;
				exit;
			}
	}


##############################
# Operation:	View Entry
# Summary:
#	- For viewing entry
#	- Must preceed "none"
##############################
IF ( $_GPV[op]=='view' )
	{
		# Check for valid $_GPV[si_id] no
			IF ( $_GPV[si_id] )
				{
					# Set Query for select.
						$query = ""; $result= ""; $numrows = 0;
						$query		= "SELECT * FROM ".$_DBCFG['site_info'];
						$query		.= " WHERE si_id=".$_GPV[si_id];
						$query		.= " ORDER BY si_id ASC";

					# Do select
						$result		= db_query_execute($query);
						$numrows	= db_query_numrows($result);

					# Process query results (assumes one returned row above)
						IF ( $numrows )
							{
								# Process query results
									while ($row = db_fetch_array($result))
									{
										# Merge Data Array with returned row
											$data_new	= array_merge( $data, $row );
											$data		= $data_new;
									}
							}

					# Call function for displaying item
						$_out = '<!-- Start content -->'.$_nl;
						$_out .= cp_do_display_entry_siteinfo ( $data, '1').$_nl;

					# Echo final output
						echo $_out;
				}
			ELSE
				{ $_GPV[op] = 'none'; }
	}


##############################
# Operation:	None
# Summary:
#	- For loading select menu.
#	- For no actions specified.
##############################
IF ( $_GPV[op]=='none' )
	{
		# Content start flag
			$_out .= '<!-- Start content -->'.$_nl;

		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_ADMIN']['SiteInfo_Editor'];

			# Call function for create select form: SiteInfo
				$aaction = $_SERVER["PHP_SELF"].'?cp=siteinfo&op=edit';
				$aname	= "si_id";
				$avalue	= $_GPV[si_id];
				$_cstr .= cp_do_select_form_siteinfo($aaction, $aname, $avalue, '1');

			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"], $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
			IF ( $_PERMS[AP16] == 1 || $_PERMS[AP15] == 1 || $_PERMS[AP14] == 1 )
				{ $_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo&op=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'',''); }

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Operation: 	Add Entry
# Summary:
#	- For intial entry
#	- For re-entry on error
##############################
IF ($_GPV[op]=='add' && (!$_GPV[stage] || $err_entry['flag']))
	{
		# Call function for add/edit form
			$_out = '<!-- Start content -->'.$_nl;
			$_out .= cp_do_form_add_edit_siteinfo ($data, $err_entry,'1').$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Operation:	Add Entry Results
# Summary:
#	- For processing added entry
#	- Do table insert
#	- Display results
##############################
IF ($_GPV[op]=='add' && $_GPV[stage]==1 && !$err_entry['flag'])
	{
		# Dim some vars
			$query = ""; $result= ""; $numrows = 0;

		# Process inputs for quotes
			$_GPV[si_desc]	= do_addslashes($_GPV[si_desc]);
			$_GPV[si_title]	= do_addslashes($_GPV[si_title]);
			$_GPV[si_code]	= do_addslashes($_GPV[si_code]);

		# Do select
			$query		= "INSERT INTO ".$_DBCFG['site_info']." (si_group, si_name, si_seq_no";
			$query		.= ", si_desc, si_block_it, si_title, si_code, si_status, si_footer_menu)";
			$query		.= " VALUES ( '$_GPV[si_group]', '$_GPV[si_name]', '$_GPV[si_seq_no]'";
			$query		.= ", '$_GPV[si_desc]', '$_GPV[si_block_it]', '$_GPV[si_title]', '$_GPV[si_code]', '$_GPV[si_status]', '$_GPV[si_footer_menu]')";
			$result		= db_query_execute($query) OR DIE("Unable to complete request");
			$insert_id	= db_query_insertid ();

		# Content start flag
			$_out .= '<!-- Start content -->'.$_nl;

		# Call function to open block
			$title_text = $_LANG['_ADMIN']['Add_SiteInfo_Entry_Results'].$_sp.'('.$_LANG['_ADMIN']['Inserted_ID'].$_sp.$insert_id.')';
			$_out .= do_subj_block_it ($title_text, '1');

		# Adjust Data Array with returned record
			$data['insert_id']		= $insert_id;
			$data['si_id']			= $insert_id;

		# Call function for Display Entry
			$_out .= '<br>'.$_nl;
			$_out .= cp_do_display_entry_siteinfo ( $data, '1').$_nl;
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Operation:	Edit Entry
# Summary:
#	- For editing entry
#	- For re-editing on error
##############################
IF ($_GPV[op]=='edit' && (!$_GPV[stage] || $err_entry['flag']))
	{
		# Dim some vars
			$query = ""; $result= ""; $numrows = 0;

		# If Stage and Error Entry, pass field vars to form,
		# Otherwise, pass looked up record to form
		IF ( $_GPV[stage] == 1 && $err_entry['flag'] )
			{
				# Call function for add/edit form
					$_out = '<!-- Start content -->'.$_nl;
					$_out .= cp_do_form_add_edit_siteinfo ( $data, $err_entry,'1').$_nl;

				# Echo final output
					echo $_out;
			}
		ELSE
			{
				# Check for valid $_GPV[si_id] no
					IF ( $_GPV[si_id] )
					{
						# Set Query for select.
							$query		= "SELECT * FROM ".$_DBCFG['site_info'];
							$query		.= " WHERE si_id=".$_GPV[si_id];
							$query		.= " ORDER BY si_id ASC";

						# Do select
							$result		= db_query_execute($query);
							$numrows	= db_query_numrows($result);

						# Process query results (assumes one returned row above)
							IF ( $numrows )
								{
									# Process query results
										while ($row = db_fetch_array($result))
										{
											# Merge Data Array with returned row
												$data_new	= array_merge( $data, $row );
												$data		= $data_new;
										}
								}

						# Call function for add/edit form
							$_out = '<!-- Start content -->'.$_nl;
							$_out .= cp_do_form_add_edit_siteinfo ($data, $err_entry,'1').$_nl;
					}
					ELSE
					{
						# Content start flag
							$_out .= '<!-- Start content -->'.$_nl;

						# Build Title String, Content String, and Footer Menu String
							$_tstr = $_LANG['_ADMIN']['SiteInfo_Editor'];

							# Call function for create select form: SiteInfo
								$aaction = $_SERVER["PHP_SELF"].'?cp=siteinfo&op=edit';
								$aname	= "si_id";
								$avalue	= $_GPV[si_id];
								$_cstr .= cp_do_select_form_siteinfo($aaction, $aname, $avalue, '1');

							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"], $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo&op=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');

						# Call block it function
							$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
							$_out .= '<br>'.$_nl;
					}

				# Echo final output
					echo $_out;
			}
	}


##############################
# Operation: 	Edit Entry Results
# Summary:
#	- For processing edited entry
#	- Do table update
#	- Display results
##############################
IF ($_GPV[op]=='edit' && $_GPV[stage]==1 && !$err_entry['flag'])
	{
		# Dim some vars
			$query = ""; $result= ""; $numrows = 0;

		# Process inputs for quotes
			$_GPV[si_desc]	= do_addslashes($_GPV[si_desc]);
			$_GPV[si_title]	= do_addslashes($_GPV[si_title]);
			$_GPV[si_code]	= do_addslashes($_GPV[si_code]);

		# Do select
			$query	= "UPDATE ".$_DBCFG['site_info']." SET si_group = '$_GPV[si_group]', si_name = '$_GPV[si_name]'";
			$query	.= ", si_seq_no = '$_GPV[si_seq_no]', si_desc = '$_GPV[si_desc]', si_block_it = '$_GPV[si_block_it]'";
			$query	.= ", si_title = '$_GPV[si_title]', si_code = '$_GPV[si_code]', si_status = '$_GPV[si_status]'";
			$query	.= ", si_footer_menu = '$_GPV[si_footer_menu]'";
			$query	.= " WHERE si_id = $_GPV[si_id]";
			$result	= db_query_execute($query) OR DIE("Unable to complete request");

		# Content start flag
			$_out .= '<!-- Start content -->'.$_nl;

		# Call function to open block
			$title_text = $_LANG['_ADMIN']['Edit_SiteInfo_Entry_Results'];
			$_out .= do_subj_block_it ($title_text, '1');

		# Call function for Display Entry
			$_out .= '<br>'.$_nl;
			$_out .= cp_do_display_entry_siteinfo ( $data, '1' ).$_nl;
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Operation: 	Delete Entry
# Summary Stage 1:
#	- Confirm delete entry
# Summary Stage 2:
#	- Do table update
#	- Display results
##############################
IF ($_GPV[op]=='delete' && $_GPV[stage]==1)
	{
		# Content start flag
			$_out .= '<!-- Start content -->'.$_nl;

		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_ADMIN']['Delete_SiteInfo_Entry_Confirmation'];

			# Do confirmation form to content string
			$_cstr = '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?cp=siteinfo&op=delete">'.$_nl;
			$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= '<b>'.$_LANG['_ADMIN']['Delete_SiteInfo_Entry_Message'].$_sp.'='.$_sp.$_GPV[si_id].'?</b>'.$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '<tr><td class="TP0MED_NC">'.$_sp.'</td></tr>'.$_nl;
			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= $_GPV[si_name].'-'.$_sp.$_GPV[si_desc].$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '<tr><td class="TP0MED_NC">'.$_sp.'</td></tr>'.$_nl;
			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="stage" value="2">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="si_id" value="'.$_GPV[si_id].'">'.$_nl;
			$_cstr .= do_input_button_class_sw ('b_delete', 'SUBMIT', $_LANG['_ADMIN']['B_Delete_Entry'], 'button_form_h', 'button_form', '1').$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;
			$_cstr .= '</FORM>'.$_nl;

			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"], $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo&op=edit&si_id='.$_GPV[si_id], $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo&op=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}

IF ($_GPV[op]=='delete' && $_GPV[stage]==2)
	{
		# Dim some Vars:
			$query = ""; $result = ""; $numrows	= 0;

		# Do select
			$query		= "DELETE FROM ".$_DBCFG['site_info']." WHERE si_id = $_GPV[si_id]";
			$result		= db_query_execute($query) OR DIE("Unable to complete request");
			$eff_rows	= db_query_affected_rows ();

		# Content start flag
			$_out .= '<!-- Start content -->'.$_nl;

		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_ADMIN']['Delete_SiteInfo_Entry_Results'];

			IF (!$eff_rows)
			{	$_cstr .= '<center>'.$_LANG['_ADMIN']['An_error_occurred'].'</center>';	}
			ELSE
			{	$_cstr .= '<center>'.$_LANG['_ADMIN']['Entry_Deleted'].'</center>';	}

			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"], $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo&op=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?cp=siteinfo', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}

?>
