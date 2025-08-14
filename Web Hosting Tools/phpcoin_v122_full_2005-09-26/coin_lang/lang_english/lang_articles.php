<?php

/**************************************************************
 * File: 		Language- Articles Module
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:	- Global language ($_LANG) vars.
 *		- Language: 		English (USA)
 *		- Translation By:	Mike Lansberry
 *		- Translator Email:	webcontact@phpcoin.com
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF ( eregi("lang_articles.php", $_SERVER["PHP_SELF"]) )
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01');
			exit;
		}

/**************************************************************
 * Language Variables
**************************************************************/
# Language Variables: Common
		$_LANG['_ARTICLES']['An_error_occurred']					= 'An error occurred.';
		$_LANG['_ARTICLES']['Add_Articles_Entry_Results']			= 'Add Articles Entry Results (Inserted ID';
		$_LANG['_ARTICLES']['Add_To_Articles']						= 'Add To Articles';
		$_LANG['_ARTICLES']['all_fields_required']					= 'all fields required';
		$_LANG['_ARTICLES']['Articles']								= 'Articles';
		$_LANG['_ARTICLES']['Articles_Editor']						= 'Articles Editor';
		$_LANG['_ARTICLES']['Articles_Entry']						= 'Articles Entry';
		$_LANG['_ARTICLES']['Articles_Summary']						= 'Articles Summary';
		$_LANG['_ARTICLES']['auto-assigned']						= 'auto-assigned';
		$_LANG['_ARTICLES']['Convert_New_Line_2_Break']				= 'Convert New Line To HTML Break';
		$_LANG['_ARTICLES']['Delete_Articles_Entry_Confirmation']	= 'Delete Articles Entry Confirmation';
		$_LANG['_ARTICLES']['Delete_Articles_Entry_Message']		= 'Are You Sure You Want to Delete Entry ID';
		$_LANG['_ARTICLES']['Delete_Articles_Entry_Results']		= 'Delete Articles Entry Results';
		$_LANG['_ARTICLES']['denotes_optional_items']				= 'denotes optional items';
		$_LANG['_ARTICLES']['Edit_Articles_Entry_Results']			= 'Edit Articles Entry Results';
		$_LANG['_ARTICLES']['Entry_Deleted']						= 'Entry Deleted.';
		$_LANG['_ARTICLES']['of']									= 'of';
		$_LANG['_ARTICLES']['Please_Select']						= 'Please Select';
		$_LANG['_ARTICLES']['total_entries']						= 'total entries';
		$_LANG['_ARTICLES']['View_All']								= 'View All';
		$_LANG['_ARTICLES']['View_Article']							= 'View Article';

# Language Variables: Some Buttons
		$_LANG['_ARTICLES']['B_Add']								= 'Add';
		$_LANG['_ARTICLES']['B_Continue']							= 'Continue';
		$_LANG['_ARTICLES']['B_Delete_Entry']						= 'Delete Entry';
		$_LANG['_ARTICLES']['B_Do_It']								= 'Do It';
		$_LANG['_ARTICLES']['B_Edit']								= 'Edit';
		$_LANG['_ARTICLES']['B_Load_Entry']							= 'Load Entry';
		$_LANG['_ARTICLES']['B_Reset']								= 'Reset';
		$_LANG['_ARTICLES']['B_Save']								= 'Save';
		$_LANG['_ARTICLES']['B_Send_Email']							= 'Send Email';

# Language Variables: Common Labels (note : on end)
		$_LANG['_ARTICLES']['l_Article_ID']							= 'Article ID:';
		$_LANG['_ARTICLES']['l_Category']							= 'Category:';
		$_LANG['_ARTICLES']['l_Conver_NL2BR']						= 'Convert NL2BR:';
		$_LANG['_ARTICLES']['l_DateTime']							= 'DateTime:';
		$_LANG['_ARTICLES']['l_Entries_By_Category']				= 'Entries By Category:';
		$_LANG['_ARTICLES']['l_Entries_By_Topic']					= 'Entries By Topic:';
		$_LANG['_ARTICLES']['l_Entry']								= 'Entry:';
		$_LANG['_ARTICLES']['l_Pages']								= 'Page(s):';
		$_LANG['_ARTICLES']['l_Subject']							= 'Subject:';
		$_LANG['_ARTICLES']['l_Topic']								= 'Topic:';

# Language Variables:
	# Misc Errors:
		$_LANG['_ARTICLES']['ART_ERR_NONE_FOUND']					= 'Sorry, no records were found for that selection.';

	# Page: Admin Data Entry error
		$_LANG['_ARTICLES']['ART_ERR_ERR_HDR1']						= 'Entry error- required fields may not have been completed.';
		$_LANG['_ARTICLES']['ART_ERR_ERR_HDR2']						= 'Please check the following:';

		$_LANG['_ARTICLES']['ART_ERR_ERR01']						= 'Subject';
		$_LANG['_ARTICLES']['ART_ERR_ERR02']						= 'Topic';
		$_LANG['_ARTICLES']['ART_ERR_ERR03']						= 'Category';
		$_LANG['_ARTICLES']['ART_ERR_ERR04']						= 'Entry';


?>
