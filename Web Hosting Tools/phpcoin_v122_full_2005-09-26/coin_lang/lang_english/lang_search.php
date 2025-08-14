<?php

/**************************************************************
 * File: 		Language- Search Module
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
	IF ( eregi("lang_search.php", $_SERVER["PHP_SELF"]) )
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
		$_LANG['_SEARCH']['Search_Results']						= 'Search Results';
		$_LANG['_SEARCH']['Search_Site']						= 'Search Site';

		$_LANG['_SEARCH']['sl_Entire_Site']						= 'Entire Site';
		$_LANG['_SEARCH']['sl_Articles']						= 'Articles';
		$_LANG['_SEARCH']['sl_FAQ']								= 'FAQ';
		$_LANG['_SEARCH']['sl_Guest_Book']						= 'Guest Book';
		$_LANG['_SEARCH']['sl_Journal']							= 'Journal';
		$_LANG['_SEARCH']['sl_Links']							= 'Links';
		$_LANG['_SEARCH']['sl_Pages']							= 'Pages';
		$_LANG['_SEARCH']['sl_SiteInfo']						= 'SiteInfo';

		$_LANG['_SEARCH']['sl_All_Possible']					= 'All Possible';
		$_LANG['_SEARCH']['sl_Content_Entry']					= 'Content / Entry';
		$_LANG['_SEARCH']['sl_Subject_Title']					= 'Subject / Title';

		$_LANG['_SEARCH']['items']								= 'items';
		$_LANG['_SEARCH']['New_Win_Message']					= 'Clicking Result Link Opens New Window';
		$_LANG['_SEARCH']['No_Items_Found']						= 'No Items Found';

# Language Variables: Some Buttons
		$_LANG['_SEARCH']['B_Reset']							= 'Reset';
		$_LANG['_SEARCH']['B_Search']							= 'Search';

# Language Variables: Common Labels (note : on end)
		$_LANG['_SEARCH']['l_Search_For']						= 'Search For:';
		$_LANG['_SEARCH']['l_Where']							= 'Where:';
		$_LANG['_SEARCH']['l_In']								= 'In:';

?>
