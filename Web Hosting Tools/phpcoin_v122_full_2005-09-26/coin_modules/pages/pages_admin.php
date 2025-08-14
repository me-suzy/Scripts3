<?php

/**************************************************************
 * File: 		Pages Module Admin Functions File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_pages.php
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("pages_admin.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01&url=mod.php?mod=pages');
			exit;
		}

/**************************************************************
 * Module Admin Functions
**************************************************************/
# Do Pages Select List / Form
function do_select_list_pages( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Set Query for select.
			$query 		= "SELECT id, subject, pages_title FROM ".$_DBCFG['pages']." ORDER BY time_stamp DESC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="0">'.$_LANG['_PAGES']['Please_Select'].'</option>'.$_nl;

		# Process query results
			while(list($id, $subject, $pages_title) = db_fetch_row($result))
			{
				$_out .= '<option value="'.$id.'"';
				IF ( $id == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_sp.$_sp.$subject.'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


# Do Form for Add / Edit
function do_form_add_edit ( $amode, $adata, $aerr_entry, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Build mode dependent strings
			switch ($amode)
			{
				case "add":
					$mode_proper	= $_LANG['_PAGES']['B_Add'];
					$mode_button	= $_LANG['_PAGES']['B_Add'];
					break;
				case "edit":
					$mode_proper	= $_LANG['_PAGES']['B_Edit'];
					$mode_button	= $_LANG['_PAGES']['B_Save'];
					break;
				default:
					$amode			= "add";
					$mode_proper	= $_LANG['_PAGES']['B_Add'];
					$mode_button	= $_LANG['_PAGES']['B_Add'];
					break;
			}

		# Build Title String, Content String, and Footer Menu String
			$_tstr .= $mode_proper.$_sp.$_LANG['_PAGES']['Pages_Entry'].$_sp.'('.$_LANG['_PAGES']['all_fields_required'].')';

		# Do data entry error string check and build
			IF ($aerr_entry['flag'])
			{
			 	$err_str = $_LANG['_PAGES']['PG_ERR_ERR_HDR1'].'<br>'.$_LANG['_PAGES']['PG_ERR_ERR_HDR2'].'<br>'.$_nl;

		 		IF ($aerr_entry['subject']) 	{ $err_str .= $_LANG['_PAGES']['PG_ERR_ERR01']; $err_prv = 1; }
				IF ($aerr_entry['topic_id']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_PAGES']['PG_ERR_ERR02']; $err_prv = 1; }
				IF ($aerr_entry['cat_id']) 		{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_PAGES']['PG_ERR_ERR03']; $err_prv = 1; }
				IF ($aerr_entry['pages_code']) 	{ IF ($err_prv) { $err_str .= ', '; } $err_str .= $_LANG['_PAGES']['PG_ERR_ERR04']; $err_prv = 1; }

 		 		$_cstr .= '<p align="center"><b>'.$err_str.'</b>'.$_nl;
			}

		# Build common td start tag / col strings (reduce text)
			$_td_str_left			= '<td class="TP1SML_NR" width="25%">';
			$_td_str_left_valign	= '<td class="TP1SML_NR" width="25%" valign="top">';
			$_td_str_right			= '<td class="TP1SML_NL" width="75%">';
			$_td_str_right_just		= '<td class="TP1SML_NJ" width="75%">';

		# Do Main Form
			$_cstr .= '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=pages&mode='.$amode.'">'.$_nl;
			$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Page_Id'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			IF ( $adata['mode'] == 'add' )
				{ $_cstr .= '('.$_LANG['_PAGES']['auto-assigned'].')'.$_nl; }
			ELSE
				{ $_cstr .= $adata[id].$_nl; }
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Subject'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="subject" SIZE=50 value="'.$adata[subject].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Topic'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			# Call select list function
				$aname	= "topic_id";
				$avalue	= $adata[topic_id];
				$_cstr .= do_select_list_topic($aname, $avalue, '1').$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Category'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			# Call select list function
				$aname	= "cat_id";
				$avalue	= $adata[cat_id];
				$_cstr .= do_select_list_cat($aname, $avalue, '1').$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Status'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_select_list_off_on('pages_status', $adata[pages_status], 1);
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Admin'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_select_list_no_yes('pages_admin', $adata[pages_admin], 1);
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.'<hr></td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Block_It'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_select_list_no_yes('pages_block_it', $adata[pages_block_it], 1);
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Block_It_Title'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="pages_title" SIZE=50 value="'.$adata[pages_title].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left_valign.'<b>'.$_LANG['_PAGES']['l_Code'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<TEXTAREA class="PSML_NL" NAME="pages_code" COLS=60 ROWS=25>'.do_stripslashes($adata[pages_code]).'</TEXTAREA>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Link_Menu'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_select_list_no_yes('pages_link_menu', $adata[pages_link_menu], 1);
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Link_Previous'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			# Call select list function
				$aname	= "pages_link_prev";
				$avalue	= $adata[pages_link_prev];
				$_cstr .= do_select_list_pages($aname, $avalue, '1').$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Link_Home'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			# Call select list function
				$aname	= "pages_link_home";
				$avalue	= $adata[pages_link_home];
				$_cstr .= do_select_list_pages($aname, $avalue, '1').$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_PAGES']['l_Link_Next'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			# Call select list function
				$aname	= "pages_link_next";
				$avalue	= $adata[pages_link_next];
				$_cstr .= do_select_list_pages($aname, $avalue, '1').$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= '<td class="TP0SML_NC" width="100%" colspan="2">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="stage" value="1">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="id" value="'.$adata[id].'">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="time_stamp" value="'.$adata[time_stamp].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_input_button_class_sw ('b_edit', 'SUBMIT', $mode_button, 'button_form_h', 'button_form', '1').$_nl;
			$_cstr .= do_input_button_class_sw ('b_reset', 'RESET', $_LANG['_PAGES']['B_Reset'], 'button_form_h', 'button_form', '1').$_nl;
			IF ($amode=="edit")
				{ $_cstr .= do_input_button_class_sw ('b_delete', 'SUBMIT', $_LANG['_PAGES']['B_Delete_Entry'], 'button_form_h', 'button_form', '1').$_nl; }
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;
			$_cstr .= '</FORM>'.$_nl;

			$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=pages&mode=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=pages&mode=edit', $_TCFG['_IMG_SELECT_LIST_M'],$_TCFG['_IMG_SELECT_LIST_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=pages&mode=summary', $_TCFG['_IMG_SUMMARY_M'],$_TCFG['_IMG_SUMMARY_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=pages&mode=list', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}

/**************************************************************
 * End Module Admin Functions
**************************************************************/

?>
