<?php

/**************************************************************
 * File: 		Download Display file.
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_downloads.php
 **************************************************************/

# Code to handle file being loaded by URL
	IF (!isset($_SERVER)) {$_SERVER = $HTTP_SERVER_VARS;}
	IF (eregi("index.php", $_SERVER["PHP_SELF"])) {
		require_once ('../../coin_includes/session_set.php');
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01&url=index.php');
		exit();
	}


# Include language file (must be after parameter load to use them)
	require_once ( $_CCFG['_PKG_PATH_LANG'].'lang_downloads.php');
	IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_downloads_override.php')) {
		require_once($_CCFG['_PKG_PATH_LANG'].'lang_downloads_override.php');
	}

# Include functions file
//	require_once ( $_CCFG['_PKG_PATH_MDLS'].$_GPV[mod].'/'.$_GPV[mod]."_funcs.php");


# Select data from downloads table
	$numrows	 = 0;
	$query 	 = "SELECT * FROM ".$_DBCFG['downloads'];
	$query 	.= " ORDER BY dload_group ASC, dload_date_str DESC";
	$result 	 = db_query_execute($query);
	$numrows	 = db_query_numrows($result);

# Process returned record
	$_dload_group_pntr	= '';
	while(list( $dload_id, $dload_group, $dload_name, $dload_desc, $dload_count, $dload_date_str, $dload_avail, $dload_filename, $dload_filesize, $dload_contributor ) = db_fetch_row($result)) {
	# Check $dload_group and set misc text
		$_tbls_title = $dload_group;

	# Open up table
		IF ( $_dload_group_pntr != $dload_group ) {
			IF ( $_tbls != '' ) {
				$_tbls .= '</table>'.$_nl;
				$_tbls .= '</div>'.$_nl;
				$_tbls .= '<br>'.$_nl;
			}

			$_tbls .= '<div align="center">'.$_nl;
			$_tbls .= '<table width="100%" border="0" bordercolor="'.$_TCFG['_TAG_TABLE_BRDR_COLOR'].'" bgcolor="'.$_TCFG['_TAG_TRTD_BKGRND_COLOR'].'" cellpadding="0" cellspacing="1">'.$_nl;
			$_tbls .= '<tr class="BLK_DEF_TITLE"><td class="TP3MED_BC" colspan="6">'.$_nl;

			$_tbls .= '<table width="100%" cellpadding="0" cellspacing="0">'.$_nl;
			$_tbls .= '<tr class="BLK_IT_TITLE_TXT">'.$_nl.'<td class="TP0MED_NL" colspan="6">'.$_nl;
			$_tbls .= '<b>'.$_tbls_title.'</b><br>'.$_nl;
			$_tbls .= '</td>'.$_nl.'</tr>'.$_nl.'</table>'.$_nl;

			$_tbls .= '</td></tr>'.$_nl;
			$_tbls .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
			$_tbls .= '<td class="TP3SML_NL"><b>'.$_LANG['Downloads']['Name'].'</b></td>'.$_nl;
			$_tbls .= '<td class="TP3SML_NC" width="70"><b>'.$_LANG['Downloads']['Released'].'</b></td>'.$_nl;
			$_tbls .= '<td class="TP3SML_NC" width="70"><b>'.$_LANG['Downloads']['Contributor'].'</b></td>'.$_nl;
			$_tbls .= '<td class="TP3SML_NL" width="60"><b>'.$_LANG['Downloads']['FileSize'].'</b></td>'.$_nl;
			$_tbls .= '<td class="TP3SML_NC" width="68"><b>'.$_LANG['Downloads']['Count'].'</b></td>'.$_nl;
			$_tbls .= '<td class="TP3SML_NC" width="35"><b>'.$_LANG['Downloads']['Get_It'].'</b></td>'.$_nl;
			$_tbls .= '</tr>'.$_nl;
		}

	# Rows
		$_tbls .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_tbls .= '<td class="TP3SML_NL">'.$dload_name.'</td>'.$_nl;
		$_tbls .= '<td class="TP3SML_NC">'.$dload_date_str.'</td>'.$_nl;
		$_tbls .= '<td class="TP3SML_NC">'.$dload_contributor.'</td>'.$_nl;
		$_tbls .= '<td class="TP3SML_NR">'.$dload_filesize.'</td>'.$_nl;
		$_tbls .= '<td class="TP3SML_NR">'.number_format($dload_count).'&nbsp;</td>'.$_nl;
		$_tbls .= '<td class="TP3SML_NC">'.$_nl;
		IF ( $dload_avail == 1 ) {
			$_tbls .= '[<a href="'.$_CCFG[_PKG_URL_MDLS].'downloads/dload.php?id='.$dload_id.'" target="_blank">file</a>]';
		} ELSE {
			$_tbls .= 'n/a';
		}
		$_tbls .= '</td>'.$_nl;
		$_tbls .= '</tr>'.$_nl;
		$_tbls .= '<tr class="BLK_DEF_ENTRY">'.$_nl;
		$_tbls .= '<td colspan="6" class="TP3SML_NL"><b>'.$_LANG['Downloads']['Description'].':</b>'.$_sp.$dload_desc.'</td>'.$_nl;
		$_tbls .= '</tr>'.$_nl;

		$_dload_group_pntr = $dload_group;
	}

# Closeout- assumes a table being finished
	$_tbls .= '</table>'.$_nl;
	$_tbls .= '</div>'.$_nl;
	$_tbls .= '<br>'.$_nl;


# Build Final output block
	$_tstr  = $_LANG['Downloads']['Title'];

	$_cstr = '<p>'.$_LANG['Downloads']['Pre-amble'].'</p><p>';
	IF ( $_GPV['v'] == 'group' )
		{ $_cstr .= '[<a href="mod.php?mod=downloads&v=name">'.$_LANG['Downloads']['Group_Name'].'</a>]'.$_nl; }
	IF ( $_GPV['v'] == 'name' )
		{ $_cstr .= '[<a href="mod.php?mod=download&v=group">'.$_LANG['Downloads']['Group_Category'].'</a>]'.$_nl; }
	$_cstr .= '</p>'.$_tbls.$_nl;

	$_mstr = ''.$_nl;

	echo do_mod_block_it($_tstr, $_cstr, 0, $_mstr, 1);
?>
