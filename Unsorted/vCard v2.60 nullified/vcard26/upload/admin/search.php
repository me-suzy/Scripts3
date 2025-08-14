<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
define('IN_VCARD', true);
require('./lib.inc.php');

check_lvl_access($canviewsearch);
dothml_pageheader();

// ############################# DB ACTION #############################
if ($action == 'reindex_db')
{
	echo "<b>$msg_admin_search_reindex</b>: $msg_admin_op_confirm_question<p>";
	echo "<a href=\"search.php?action=reindex_db_yes&s=$s\">$msg_admin_op_confirm_yes</a>";
	exit;
}
// ############################# DB ACTION #############################
if ($action == 'log_restart')
{
	$result = $DB_site->query("DELETE FROM vcard_searchlog ");
	dohtml_result($result,"$msg_admin_searchlog_dbempty");
	$action = 'log';
}

if ($action == 'reindex_db_yes')
{
	$clean_table 	= $DB_site->query("DELETE FROM vcard_search");
	$clean_table 	= $DB_site->query("DELETE FROM vcard_word");
	$index 		= $DB_site->query("SELECT card_id,card_caption,card_keywords FROM vcard_cards");

	$noise_words 	= file("noisewords.txt");
	
	// card index
	while ($indexinfo = $DB_site->fetch_array($index))
	{
		$card_id 	= $indexinfo['card_id'];
		$allwords	= trim($indexinfo['card_caption'])." ".trim($indexinfo['card_keywords']);
		$allwords 	= ereg_replace("^"," ",$allwords);
		for ($i=0; $i<count($noise_words); $i++)
		{
			$filter_words 	= trim($noise_words[$i]);
			$allwords 	= eregi_replace(" $filter_words "," ",$allwords);
		}
		$allwords	= eregi_replace("[\n\t\r,]"," ",$allwords);
		$allwords 	= eregi_replace("/"," ",$allwords);
		$allwords	= preg_replace("/(\.+)($| |\n|\t)/s"," ", $allwords);
		$allwords 	= preg_replace("/[\(\)\"':*;%,\[\]?!#{}.&_$<>|=`\-+\\\\]/s"," ",$allwords); // '
		$allwords 	= eregi_replace(" ([[0-9a-z]{1,2}) "," "," $allwords ");
		$allwords 	= eregi_replace(" ([[0-9a-z]{1,2}) "," "," $allwords ");
		$allwords 	= eregi_replace("[[:space:]]+"," ", $allwords);
		$allwords	= strtolower(trim($allwords));
	
		$wordarray	= explode(" ",$allwords);
	
		for ($k=0; $k<count($wordarray); $k++)
		{
			$wordarray[$k] = stripslashes(trim($wordarray[$k]));
			if (strlen($wordarray[$k])>2 AND strlen($wordarray[$k])<30)
			{
				$check_wordlist = $DB_site->query_first("SELECT word_id,word_str FROM vcard_word WHERE word_str='".addslashes($wordarray[$k])."' ");
				if (!$check_wordlist)
				{
					//withou word_id
					$insert = $DB_site->query("INSERT INTO vcard_word(word_id,word_str) VALUES (NULL, '".addslashes($wordarray[$k])."')  ");
					$word_id = $DB_site->insert_id();
				}
				else
				{
					//with word_id
					$word_id = $check_wordlist[word_id];
				}
				$DB_site->query("INSERT INTO vcard_search (word_id,card_id,cat_id) VALUES ('$word_id','$card_id','$cat_id') ");
			}
		}
	}
	
	$card_id = '';
	
	// category index
	$indexcat = $DB_site->query("SELECT cat_id,cat_name FROM vcard_category ");
	
	while ($indexcatinfo = $DB_site->fetch_array($indexcat))
	{
		$cat_id 	= $indexcatinfo['cat_id'];
		$allwords	= trim($indexcatinfo['cat_name']);
		$allwords 	= ereg_replace("^"," ",$allwords);
		for ($i=0; $i<count($noise_words); $i++)
		{
			$filter_words 	= trim($noise_words[$i]);
			$allwords 	= eregi_replace(" $filter_words "," ",$allwords);
		}
		$allwords	= eregi_replace("[\n\t\r,]"," ",$allwords);
		$allwords 	= eregi_replace("/"," ",$allwords);
		$allwords	= preg_replace("/(\.+)($| |\n|\t)/s"," ", $allwords);
		$allwords 	= preg_replace("/[\(\)\"':*;%,\[\]?!#{}.&_$<>|=`\-+\\\\]/s"," ",$allwords); //'
		$allwords 	= eregi_replace(" ([[0-9a-z]{1,2}) "," "," $allwords ");
		$allwords 	= eregi_replace(" ([[0-9a-z]{1,2}) "," "," $allwords ");
		$allwords 	= eregi_replace("[[:space:]]+"," ", $allwords);
		$allwords	= strtolower(trim($allwords));
	
		$wordarray	= explode(" ",$allwords);
	
		for ($k=0; $k<count($wordarray); $k++)
		{
			$wordarray[$k] = stripslashes(trim($wordarray[$k]));
			if (strlen($wordarray[$k])>2 AND strlen($wordarray[$k])<30)
			{
				$check_wordlist = $DB_site->query_first("SELECT word_id,word_str FROM vcard_word WHERE word_str='".addslashes($wordarray[$k])."' ");
				if (!$check_wordlist)
				{
					//withou word_id
					$insert = $DB_site->query("INSERT INTO vcard_word(word_id,word_str) VALUES (NULL, '".addslashes($wordarray[$k])."')  ");
					$word_id = $DB_site->insert_id();
				}
				else
				{
					//with word_id
					$word_id = $check_wordlist[word_id];
				}
				$DB_site->query("INSERT INTO vcard_search (word_id,card_id,cat_id) VALUES ('$word_id','$card_id','$cat_id') ");
			}
		}
	}
	
	dohtml_result(1,"$msg_admin_search_reindex");
	$action = '';
}


if ($action == 'log_view')
{
	$total_in_db 	= $DB_site->count_records("vcard_searchlog");
	$logs_array = $DB_site->query("SELECT COUNT(search_word) AS score, search_word, search_date
	FROM vcard_searchlog
	GROUP BY search_word
	ORDER BY score DESC
	LIMIT ".addslashes($HTTP_POST_VARS[search_entries])." ");
	dohtml_table_header("logs","$msg_admin_top $search_entries $msg_admin_search_term",4);
	while ($row  =  $DB_site->fetch_array($logs_array))
	{
		$row['search_word'] 	= stripslashes(htmlspecialchars($row['search_word']));
		echo "<tr>";
		echo "<td valign=top><font face=arial size=2><a HREF=\"$site_prog_url/search.php?words=".$row['search_word']."\" target=\"_blank\"><b>$row[search_word]</b>&nbsp;</td>";
		echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $row[score] </b></td>";
		echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b>". get_percent($row['score']/$total_in_db*100) . "%</b></td>";
		echo "<td width=\"30%\">". gethtml_barchart($row[score]/$total_in_db*100) ."</td>";
		echo "</tr>";
	}
	$DB_site->free_result($logs_array);
	dohtml_table_footer();
	exit;
}

// ############################# ACTION SCREENS #############################
// SCREEN = LOG
if ($action == 'log')
{
dohtml_table_header("reindex","$msg_admin_search_viewlog",1);
dohtml_form_infobox("$msg_admin_search_logbox");
dohtml_form_header("search","log_view",0,0);
?>
	<tr>
		<td>Top <select name="search_entries" size="1">
		<option value="10"> 10 </option>
		<option value="20" selected> 20 </option>
		<option value="50"> 50 </option>
		<option value="100"> 100 </option>
		</select>
		</td>
	</tr>
<?php
dohtml_form_footer("$msg_admin_view");
dohtml_table_footer();
dothml_pagefooter();

dohtml_table_header("restart","$msg_admin_search_restart",1);
dohtml_form_infobox("<font color=\"#FF0000\"><b>$msg_admin_warning</b></h2> $msg_admin_search_note_restart");
dohtml_form_header("search","log_restart",0,0);
dohtml_form_footer("$msg_admin_search_restart");
dohtml_table_footer();
dothml_pagefooter();

}
// SCREEN = DEFAULT
if (empty($action))
{
dohtml_table_header("reindex","$msg_admin_search_reindex",1);
dohtml_form_infobox("<font color=\"#FF0000\"><b>$msg_admin_warning</b></h2> $msg_admin_search_reindexnote");
dohtml_form_header("search","reindex_db",0,0);
dohtml_form_footer("$msg_admin_reindex");
dohtml_table_footer();
dothml_pagefooter();
	exit;
}
?>
