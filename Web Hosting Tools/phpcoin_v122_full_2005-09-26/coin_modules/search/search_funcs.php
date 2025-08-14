<?php

/**************************************************************
 * File: 		Search Module Functions File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_search.php
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("search_funcs.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01&url=mod.php?mod=search');
			exit;
		}

/**************************************************************
 * Module Functions
**************************************************************/
function do_search_form( $adata, $aret_flag=0 )
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim globals
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Build common td start tag / strings (reduce text)
			$_td_str_left			= '<td class="TP1SML_NR" width="25%">';
			$_td_str_left_valign	= '<td class="TP1SML_NR" width="25%" valign="top">';
			$_td_str_right			= '<td class="TP1SML_NL" width="75%">';
			$_td_str_right_just		= '<td class="TP1SML_NJ" width="75%">';

		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_SEARCH']['Search_Site'];

			$_cstr .= '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=search">'.$_nl;
			$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_SEARCH']['l_Search_For'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" TYPE=TEXT NAME="search_str" SIZE=50 value="'.$adata[t_search_str].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_SEARCH']['l_Where'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<select class="select_form" name="search_where" size="1" value="'.$adata[search_where].'">'.$_nl;
			$_cstr .= '<option value="all"';
				IF ( $adata[search_where] == 'all' ) { $_cstr .= ' selected'; }
			$_cstr .= '>'.$_LANG['_SEARCH']['sl_Entire_Site'].'</option>'.$_nl;
			$_cstr .= '<option value="articles"';
				IF ( $adata[search_where] == 'articles' ) { $_cstr .= ' selected'; }
			$_cstr .= '>'.$_LANG['_SEARCH']['sl_Articles'].'</option>'.$_nl;
			$_cstr .= '<option value="faq"';
				IF ( $adata[search_where] == 'faq' ) { $_cstr .= ' selected'; }
			$_cstr .= '>'.$_LANG['_SEARCH']['sl_FAQ'].'</option>'.$_nl;
		#	$_cstr .= '<option value="guestbook"';
		#		IF ( $adata[search_where] == 'guestbook' ) { $_cstr .= ' selected'; }
		#	$_cstr .= '>'.$_LANG['_SEARCH']['sl_Guest_Book'].'</option>'.$_nl;
		#	$_cstr .= '<option value="journal"';
		#		IF ( $adata[search_where] == 'journal' ) { $_cstr .= ' selected'; }
		#	$_cstr .= '>'.$_LANG['_SEARCH']['sl_Journal'].'</option>'.$_nl;
		#	$_cstr .= '<option value="links"';
		#		IF ( $adata[search_where] == 'links' ) { $_cstr .= ' selected'; }
		#	$_cstr .= '>'.$_LANG['_SEARCH']['sl_Links'].'</option>'.$_nl;
			$_cstr .= '<option value="pages"';
				IF ( $adata[search_where] == 'pages' ) { $_cstr .= ' selected'; }
			$_cstr .= '>'.$_LANG['_SEARCH']['sl_Pages'].'</option>'.$_nl;
			$_cstr .= '<option value="siteinfo"';
				IF ( $adata[search_where] == 'siteinfo' ) { $_cstr .= ' selected'; }
			$_cstr .= '>'.$_LANG['_SEARCH']['sl_SiteInfo'].'</option>'.$_nl;
			$_cstr .= '</select>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_LANG['_SEARCH']['l_In'].$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<select class="select_form" name="search_in" size="1" value="'.$adata[search_in].'">'.$_nl;
			$_cstr .= '<option value="all"';
				IF ( $adata[search_in] == 'all' ) { $_cstr .= ' selected'; }
			$_cstr .= '>'.$_LANG['_SEARCH']['sl_All_Possible'].'</option>'.$_nl;
			$_cstr .= '<option value="content"';
				IF ( $adata[search_in] == 'content' ) { $_cstr .= ' selected'; }
			$_cstr .= '>'.$_LANG['_SEARCH']['sl_Content_Entry'].'</option>'.$_nl;
			$_cstr .= '<option value="subjtitle"';
				IF ( $adata[search_in] == 'subjtitle' ) { $_cstr .= ' selected'; }
			$_cstr .= '>'.$_LANG['_SEARCH']['sl_Subject_Title'].'</option>'.$_nl;
			$_cstr .= '</select>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
				 IF ( $adata[sr_click_new]==1 ) { $_set .= ' CHECKED'; }
			$_cstr .= '<INPUT TYPE=CHECKBOX NAME="sr_click_new" value="1"'.$_set.' border="0">'.$_nl;
			$_cstr .= $_sp.$_LANG['_SEARCH']['New_Win_Message'].$_nl;
			$_cstr .= '</td></tr>'.$_nl;

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= '<td class="TP0MED_NC" width="100%" colspan="2">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="stage" value="1">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.'<b>'.$_sp.'</b></td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_input_button_class_sw ('b_search', 'SUBMIT', $_LANG['_SEARCH']['B_Search'], 'button_form_h', 'button_form', '1').$_nl;
			$_cstr .= do_input_button_class_sw ('b_reset', 'RESET', $_LANG['_SEARCH']['B_Reset'], 'button_form_h', 'button_form', '1').$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;
			$_cstr .= '</FORM>'.$_nl;

			IF ( $_SEC['_sadmin_flg'] )
				{
					$_mstr_flg	= 1;
					$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
				}
			ELSE
				{
					$_mstr_flg	= 0;
					$_mstr 		= ''.$_nl;
				}

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flg, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


function do_search( $adata, $aret_flag=0 )
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim globals
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$adata['t_search_str'] = $adata['search_str'];
			$adata['search_str'] = do_addslashes($adata['search_str']);

		# Reload search form
			$_out = do_search_form($adata, '1');

		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_SEARCH']['Search_Results'];

			IF ( $adata['search_where'] == 'all' || $adata['search_where'] == 'articles')
				{ $_cstr .= do_search_articles( $adata ); }
			IF ( $adata['search_where'] == 'all' || $adata['search_where'] == 'faq')
				{ $_cstr .= do_search_faq( $adata ); }
		#	IF ( $adata['search_where'] == 'all' || $adata['search_where'] == 'guestbook')
		#		{ $_cstr .= do_search_guestbook( $adata ); }
		#	IF ( $adata['search_where'] == 'all' || $adata['search_where'] == 'journal')
		#		{ $_cstr .= do_search_journal( $adata ); }
		#	IF ( $adata['search_where'] == 'all' || $adata['search_where'] == 'links')
		#		{ $_cstr .= do_search_links( $adata ); }
			IF ( $adata['search_where'] == 'all' || $adata['search_where'] == 'pages')
				{ $_cstr .= do_search_pages( $adata ); }
			IF ( $adata['search_where'] == 'all' || $adata['search_where'] == 'siteinfo')
				{ $_cstr .= do_search_siteinfo( $adata ); }

			IF ( $_SEC['_sadmin_flg'] )
				{
					$_mstr_flg	= 1;
					$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
				}
			ELSE
				{
					$_mstr_flg	= 0;
					$_mstr 		= ''.$_nl;
				}

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flg, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


function do_search_articles( $adata )
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim globals
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows	= 0;

		# Set target same / new window
			IF ( $adata[sr_click_new]==1 ) { $_target = ' target="_blank"'; } ELSE { $_target = ''; }

		# Search Articles Title and Entry
			$where = " WHERE (";
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'subjtitle' )
				{
					$where .= "subject like '$adata[search_str]%'";
					$where .= " OR subject like '%$adata[search_str]%'";
					$where .= " OR subject like '%$adata[search_str]'";
				}
			IF ( $adata[search_in] == 'all' )
				{
					$where .= " OR ";
				}
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'content' )
				{
					$where .= "entry like '$adata[search_str]%'";
					$where .= " OR entry like '%$adata[search_str]%'";
					$where .= " OR entry like '%$adata[search_str]'";
				}
			$where .= ")";

			$query = "SELECT id, subject, topic_id, cat_id, time_stamp";
			$query .= " FROM ".$_DBCFG['articles'];
			$query .= $where;
			$query .= " ORDER BY subject ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$_list_cnt = 0;
			while(list($id, $subject, $topic_id, $cat_id, $time_stamp) = db_fetch_row($result))
				{
					$_list_cnt = $_list_cnt + 1;
					$_list .= '<li><a href="'.$_SERVER["PHP_SELF"].'?mod=articles&mode=view&id='.$id.'&ss='.$adata[search_str].'"'.$_target.'>'.$subject.'</a>';
					$_list .= $_sp.$_sp.$_sp.$_sp.'<i>('.dt_make_datetime ( $time_stamp, $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).')</i>'.$_nl;
				}

			IF ( !$numrows ) { $_list .= '<li><i>'.$_LANG['_SEARCH']['No_Items_Found'].'</i>'.$_nl; }

			$_list_articles = '<b>'.$_LANG['_SEARCH']['sl_Articles'].'</b>:'.$_sp.'('.$_list_cnt.$_sp.$_LANG['_SEARCH']['items'].')'.$_nl;
			$_list_articles .= '<ul>'.$_nl;
			$_list_articles .= $_list.$_nl;
			$_list_articles .= '</ul>'.$_nl;
			$_list_articles .= '<hr>'.$_nl;

			return $_list_articles;
	}


function do_search_faq( $adata )
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim globals
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows	= 0;

		# Set target same / new window
			IF ( $adata[sr_click_new]==1 ) { $_target = ' target="_blank"'; } ELSE { $_target = ''; }

		# Search FAQ Title and Entry
			$where = " WHERE (";
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'subjtitle' )
				{
					$where .= "faqqa_question like '$adata[search_str]%'";
					$where .= " OR faqqa_question like '%$adata[search_str]%'";
					$where .= " OR faqqa_question like '%$adata[search_str]'";
				}
			IF ( $adata[search_in] == 'all' )
				{
					$where .= " OR ";
				}
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'content' )
				{
					$where .= "faqqa_answer like '$adata[search_str]%'";
					$where .= " OR faqqa_answer like '%$adata[search_str]%'";
					$where .= " OR faqqa_answer like '%$adata[search_str]'";
				}
			$where .= ")";

			$query = "SELECT faqqa_id, faqqa_faq_id, faqqa_time_stamp_mod, faqqa_question";
			$query .= " FROM ".$_DBCFG['faq_qa'];
			$query .= $where;
			$query .= " ORDER BY faqqa_question ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$_list_cnt = 0;
			while(list($faqqa_id, $faqqa_faq_id, $faqqa_time_stamp_mod, $faqqa_question) = db_fetch_row($result))
				{
					$_list_cnt = $_list_cnt + 1;
					$_list .= '<li><a href="'.$_SERVER["PHP_SELF"].'?mod=faq&mode=view&faqqa_id='.$faqqa_id.'&ss='.$adata[search_str].'"'.$_target.'>'.$faqqa_question.'</a>';
					$_list .= $_sp.$_sp.$_sp.$_sp.'<i>('.dt_make_datetime ( $faqqa_time_stamp_mod, $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).')</i>'.$_nl;
				}

			IF ( !$numrows ) { $_list .= '<li><i>'.$_LANG['_SEARCH']['No_Items_Found'].'</i>'.$_nl; }

			$_list_faq = '<b>'.$_LANG['_SEARCH']['sl_FAQ'].'</b>:'.$_sp.'('.$_list_cnt.$_sp.$_LANG['_SEARCH']['items'].')'.$_nl;
			$_list_faq .= '<ul>'.$_nl;
			$_list_faq .= $_list.$_nl;
			$_list_faq .= '</ul>'.$_nl;
			$_list_faq .= '<hr>'.$_nl;

			return $_list_faq;
	}


function do_search_guestbook( $adata )
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim globals
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows	= 0;

		# Set target same / new window
			IF ( $adata[sr_click_new]==1 ) { $_target = ' target="_blank"'; } ELSE { $_target = ''; }

		# Search Guest Book Title and Entry
			$where = " WHERE (";
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'subjtitle' )
				{
					$where .= "name_first like '$adata[search_str]%'";
					$where .= " OR name_first like '%$adata[search_str]%'";
					$where .= " OR name_first like '%$adata[search_str]'";
					$where .= " OR name_last like '$adata[search_str]%'";
					$where .= " OR name_last like '%$adata[search_str]%'";
					$where .= " OR name_last like '%$adata[search_str]'";
				}
			IF ( $adata[search_in] == 'all' )
				{
					$where .= " OR ";
				}
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'content' )
				{
					$where .= "comment like '$adata[search_str]%'";
					$where .= " OR comment like '%$adata[search_str]%'";
					$where .= " OR comment like '%$adata[search_str]'";
				}
			$where .= ")";

			$query = "SELECT id, name_first, name_last, date";
			$query .= " FROM ".$_DBCFG['guestbook'];
			$query .= $where;
			$query .= " ORDER BY name_last ASC, name_first ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$_list_cnt = 0;
			while(list($id, $name_first, $name_last, $date) = db_fetch_row($result))
				{
					$_list_cnt = $_list_cnt + 1;
					$_list .= '<li><a href="'.$_SERVER["PHP_SELF"].'?mod=guestbook&mode=view&id='.$id.'&ss='.$adata[search_str].'"'.$_target.'>'.$name_first.' '.$name_last.'</a>';
					$_list .= $_sp.$_sp.$_sp.$_sp.'<i>('.$date.')</i>'.$_nl;
				}

			IF ( !$numrows ) { $_list .= '<li><i>'.$_LANG['_SEARCH']['No_Items_Found'].'</i>'.$_nl; }

			$_list_gbook = '<b>'.$_LANG['_SEARCH']['sl_Guest_Book'].'</b>:'.$_sp.'('.$_list_cnt.$_sp.$_LANG['_SEARCH']['items'].')'.$_nl;
			$_list_gbook .= '<ul>'.$_nl;
			$_list_gbook .= $_list.$_nl;
			$_list_gbook .= '</ul>'.$_nl;
			$_list_gbook .= '<hr>'.$_nl;

			return $_list_gbook;
	}


function do_search_journal( $adata )
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim globals
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows	= 0;

		# Set target same / new window
			IF ( $adata[sr_click_new]==1 ) { $_target = ' target="_blank"'; } ELSE { $_target = ''; }

		# Search Journal Title and Entry
			$where = " WHERE (";
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'subjtitle' )
				{
					$where .= "subject like '$adata[search_str]%'";
					$where .= " OR subject like '%$adata[search_str]%'";
					$where .= " OR subject like '%$adata[search_str]'";
				}
			IF ( $adata[search_in] == 'all' )
				{
					$where .= " OR ";
				}
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'content' )
				{
					$where .= "entry like '$adata[search_str]%'";
					$where .= " OR entry like '%$adata[search_str]%'";
					$where .= " OR entry like '%$adata[search_str]'";
				}
			$where .= ")";

			$query = "SELECT id, subject, topic_id, cat_id, date";
			$query .= " FROM ".$_DBCFG['journal'];
			$query .= $where;
			$query .= " ORDER BY subject ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$_list_cnt = 0;
			while(list($id, $subject, $topic_id, $cat_id, $date) = db_fetch_row($result))
				{
					$_list_cnt = $_list_cnt + 1;
					$_list .= '<li><a href="'.$_SERVER["PHP_SELF"].'?mod=journal&mode=view&id='.$id.'&ss='.$adata[search_str].'"'.$_target.'>'.$subject.'</a>';
					$_list .= $_sp.$_sp.$_sp.$_sp.'<i>('.$date.')</i>'.$_nl;
				}

			IF ( !$numrows ) { $_list .= '<li><i>'.$_LANG['_SEARCH']['No_Items_Found'].'</i>'.$_nl; }

			$_list_journal = '<b>'.$_LANG['_SEARCH']['sl_Journal'].'</b>:'.$_sp.'('.$_list_cnt.$_sp.$_LANG['_SEARCH']['items'].')'.$_nl;
			$_list_journal .= '<ul>'.$_nl;
			$_list_journal .= $_list.$_nl;
			$_list_journal .= '</ul>'.$_nl;
			$_list_journal .= '<hr>'.$_nl;

			return $_list_journal;
	}


function do_search_links( $adata )
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim globals
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows	= 0;

		# Set target same / new window
			IF ( $adata[sr_click_new]==1 ) { $_target = ' target="_blank"'; } ELSE { $_target = ''; }

		# Search Links Title and Entry
			$where = " WHERE (";
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'subjtitle' )
				{
					$where .= "link_site_name like '$adata[search_str]%'";
					$where .= " OR link_site_name like '%$adata[search_str]%'";
					$where .= " OR link_site_name like '%$adata[search_str]'";
				}
			IF ( $adata[search_in] == 'all' )
				{
					$where .= " OR ";
				}
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'content' )
				{
					$where .= "link_descrip like '$adata[search_str]%'";
					$where .= " OR link_descrip like '%$adata[search_str]%'";
					$where .= " OR link_descrip like '%$adata[search_str]'";
				}
			$where .= ")";

			$query = "SELECT link_id, link_date, link_site_name, link_site_url, link_descrip";
			$query .= " FROM ".$_DBCFG['links_links'];
			$query .= $where;
			$query .= " ORDER BY link_site_name ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$_list_cnt = 0;
			while(list($link_id, $link_date, $link_site_name, $link_site_url, $link_descrip) = db_fetch_row($result))
				{
					$_list_cnt = $_list_cnt + 1;
					$_list .= '<li><a href="'.$link_site_url.'" target="_blank">'.$link_site_name.'</a>';
					$_list .= $_sp.$_sp.$_sp.$_sp.'<i>('.$link_date.')</i>'.$_nl;
					$_list .= '<br>'.do_stripslashes($link_descrip).$_nl;
				}

			IF ( !$numrows ) { $_list .= '<li><i>'.$_LANG['_SEARCH']['No_Items_Found'].'</i>'.$_nl; }

			$_list_links = '<b>'.$_LANG['_SEARCH']['sl_Links'].'</b>:'.$_sp.'('.$_list_cnt.$_sp.$_LANG['_SEARCH']['items'].')'.$_nl;
			$_list_links .= '<ul>'.$_nl;
			$_list_links .= $_list.$_nl;
			$_list_links .= '</ul>'.$_nl;
			$_list_links .= '<hr>'.$_nl;

			return $_list_links;
	}


function do_search_pages( $adata )
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim globals
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows	= 0;

		# Set target same / new window
			IF ( $adata[sr_click_new]==1 ) { $_target = ' target="_blank"'; } ELSE { $_target = ''; }

		# Search Pages Title and Entry
			$where = " WHERE (";
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'subjtitle' )
				{
					$where .= "pages_title like '$adata[search_str]%'";
					$where .= " OR pages_title like '%$adata[search_str]%'";
					$where .= " OR pages_title like '%$adata[search_str]'";
				}
			IF ( $adata[search_in] == 'all' )
				{
					$where .= " OR ";
				}
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'content' )
				{
					$where .= "pages_code like '$adata[search_str]%'";
					$where .= " OR pages_code like '%$adata[search_str]%'";
					$where .= " OR pages_code like '%$adata[search_str]'";
				}
			$where .= ")";

			# Don't forget admin / status checks

			$query = "SELECT id, subject, time_stamp, pages_title";
			$query .= " FROM ".$_DBCFG['pages'];
			$query .= $where;
			$query .= " ORDER BY pages_title ASC";

		# Do select
			$result 	= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$_list_cnt = 0;
			while(list($id, $subject, $time_stamp, $pages_title) = db_fetch_row($result))
				{
					$_list_cnt = $_list_cnt + 1;
					$_list .= '<li><a href="'.$_SERVER["PHP_SELF"].'?mod=pages&mode=view&id='.$id.'&ss='.$adata[search_str].'"'.$_target.'>'.$pages_title.'</a>';
					$_list .= $_sp.$_sp.$_sp.$_sp.'<i>('.dt_make_datetime ( $time_stamp, $_CCFG['_PKG_DATE_FORMAT_SHORT_DTTM'] ).')</i>'.$_nl;
				}

			IF ( !$numrows ) { $_list .= '<li><i>'.$_LANG['_SEARCH']['No_Items_Found'].'</i>'.$_nl; }

			$_list_pages = '<b>'.$_LANG['_SEARCH']['sl_Pages'].'</b>:'.$_sp.'('.$_list_cnt.$_sp.$_LANG['_SEARCH']['items'].')'.$_nl;
			$_list_pages .= '<ul>'.$_nl;
			$_list_pages .= $_list.$_nl;
			$_list_pages .= '</ul>'.$_nl;
			$_list_pages .= '<hr>'.$_nl;

			return $_list_pages;
	}


function do_search_siteinfo( $adata )
	{
		# Get security vars
			$_SEC = get_security_flags ();

		# Dim globals
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows	= 0;

		# Set target same / new window
			IF ( $adata[sr_click_new]==1 ) { $_target = ' target="_blank"'; } ELSE { $_target = ''; }

		# Search SiteInfo Title and Entry
			$where = " WHERE (";
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'subjtitle' )
				{
					$where .= "si_title like '$adata[search_str]%'";
					$where .= " OR si_title like '%$adata[search_str]%'";
					$where .= " OR si_title like '%$adata[search_str]'";
				}
			IF ( $adata[search_in] == 'all' )
				{
					$where .= " OR ";
				}
			IF ( $adata[search_in] == 'all' || $adata[search_in] == 'content' )
				{
					$where .= "si_code like '$adata[search_str]%'";
					$where .= " OR si_code like '%$adata[search_str]%'";
					$where .= " OR si_code like '%$adata[search_str]'";
				}
			$where .= ")";

			# Don't forget admin / status checks

			$query = "SELECT si_id, si_title";
			$query .= " FROM ".$_DBCFG['site_info'];
			$query .= $where;
			$query .= " ORDER BY si_title ASC";

		# Do select
			$result 	= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$_list_cnt = 0;
			while(list($si_id, $si_title) = db_fetch_row($result))
				{
					$_list_cnt = $_list_cnt + 1;
					$_list .= '<li><a href="'.$_SERVER["PHP_SELF"].'?mod=siteinfo&id='.$si_id.'&ss='.$adata[search_str].'"'.$_target.'>'.$si_title.'</a>'.$_nl;
				}

			IF ( !$numrows ) { $_list .= '<li><i>'.$_LANG['_SEARCH']['No_Items_Found'].'</i>'.$_nl; }

			$_list_siteinfo = '<b>'.$_LANG['_SEARCH']['sl_SiteInfo'].'</b>:'.$_sp.'('.$_list_cnt.$_sp.$_LANG['_SEARCH']['items'].')'.$_nl;
			$_list_siteinfo .= '<ul>'.$_nl;
			$_list_siteinfo .= $_list.$_nl;
			$_list_siteinfo .= '</ul>'.$_nl;
			$_list_siteinfo .= '<hr>'.$_nl;

			return $_list_siteinfo;
	}

?>
