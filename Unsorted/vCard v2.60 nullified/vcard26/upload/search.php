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
$templatesused = 'eventsearch,new_card_list,new_card_list_item';
require('./lib.inc.php');

$thisborder = 0;
$thiscellspacing = 1;
$thiscellpadding = 2;
$thisalign = 'center';
$thiswidth = $gallery_table_width; //'100%';

function removeaccents($string)
{
	//$string = strtr($string,"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿýÑñ","AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyyNn");
	$string = strtr($string,"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ",
				"AAAAAAACEEEEIIIIDNOOOOOxOUUUUYbBaaaaaaaceeeeiiiionoooooouuuuypy");
	return $string; 
} 

if (isset($HTTP_GET_VARS['event_id']))
{
	$event_id = addslashes($HTTP_GET_VARS['event_id']);
	$event_id = (int) $event_id;
	
	if ( !empty($HTTP_GET_VARS['page']) ) { $page = addslashes($HTTP_GET_VARS['page']); }else{ $page='';}

	if ( empty($page))
	{
		$page = 1;
	}
	$prev_page = $page - 1;
	$next_page = $page + 1;
	$page_start =($gallery_thm_per_page * $page) - $gallery_thm_per_page;
	
	$query = ("SELECT * FROM vcard_cards WHERE card_event='$event_id' ");
	
	$result = $DB_site->query($query);
	$num_rows = $DB_site->num_rows($result);
	
	if ($num_rows <= $gallery_thm_per_page){
		$num_pages = 1;
	}elseif (($num_rows % $gallery_thm_per_page) == 0){
		$num_pages =($num_rows / $gallery_thm_per_page);
	}else{
		$num_pages =($num_rows / $gallery_thm_per_page) + 1;
	}
	$num_pages = (int) $num_pages;
	
	
	if (($page > $num_pages) || ($page < 0))
	{
		$categories_textlist = get_html_table_cattex();
		$category_totalcards = "$num_rows $MsgPostcards";
		$category_subcategorylist = $T_SubCatList;
		$navbar = $T_GalleryNavBar;
		$cards_table = $MsgErrorInvalidePageNumber;
		$mainpagelink = $T_BackMainPage;
		eval("make_output(\"".get_template("eventsearch")."\");");
		exit;
	}
	
	$query = $query . " LIMIT $page_start, $gallery_thm_per_page";
	$result = $DB_site->query($query); 
	if ($num_rows == 0)
	{
		$cards_table = "<blockquote>$MsgErrorNoCardsEvents</blockquote>";
	}else{
		$cards_table = "<table border='$thisborder' cellspacing='$thiscellspacing' cellpadding='$thiscellpadding' align='$thisalign'><tr>";
		$icounter = 0;
		while ($row = $DB_site->fetch_array($result))
		{
			$post_template = $row['card_template'];
			$post_image = $row['card_imgfile'];
			$post_imagethm = $row['card_thmfile'];
			$post_cat = $row['card_category'];
			$post_caption = $row['card_caption'];
			$post_id = $row['card_id'];
			$post_date = get_date_readable($row['card_date']);
			$cards_table.= "<td align='center' valign='middle' width='". get_widthpercent($gallery_table_cols) ."'>";
			$post_thm_url = (eregi('http://',$post_imagethm))? $post_imagethm : "$site_image_url/$post_imagethm";
			eval("\$cards_table .= \"".get_template("postcard_imagelink")."\";");
			$cards_table .= '</td>';
			$icounter++;
			if ($icounter == $gallery_table_cols)
			{
				$cards_table .= '</tr><tr>';
				$icounter = 0;
			}
		}
		while (($icounter > 0) && ($icounter != $gallery_table_cols))
		{ 
			$cards_table .= '<td>&nbsp;</td>'; 
			$icounter++; 
		}
	}
	$DB_site->free_result($query);
	$cards_table .= '</tr></table>';
	
	// NAV BAR LINKS
	/////////////////////////////////////////////////////////////////
	$tmp_navbar = '';
	// Previous Link
	if (isset($prev_page))
	{
		$tmp_navbar = "&nbsp;<a href='search.php?event_id=$event_id&page=$prev_page'>$MsgPrevious</a>";  
	}
	
	if ($num_rows != 0)
	{
		for ($i = 1; $i <= $num_pages; $i++)
		{
			if($i != $page)
			{
				$tmp_navbar.= " <a href='search.php?event_id=$event_id&page=$i'>[$i]</a> ";
			}else{
				$tmp_navbar.= " $i ";
			}
		}
	}
	
	$PresentPage ="$page $MsgPageOf $num_pages";
	
	// Next Link
	if ($page != $num_pages)
	{
		$tmp_navbar.= "&nbsp;<a href='search.php?event_id=$event_id&page=$next_page'>$MsgNext</a><br><br>";
	}
	$mainpagelink 	= "<p><div align='center'><a href='index.php'>$MsgBackCatMain</a></div>";
	$navbar	= "$MsgPage $PresentPage &raquo; $tmp_navbar";
}

if (isset($HTTP_POST_VARS['words']))
{
	$words = $HTTP_POST_VARS['words'];
	//echo $words.'<hr>';
	$words = ereg_replace("[\n,]"," ",$words);
	$words = str_replace(". ", " ", $words);
	$words = preg_replace("/[\(\)\"':;\[\]!#{}_\-+\\\\]/s","",$words); //'
	$words = ereg_replace("( ){2,}", " ", $words);
	$words = trim($words);
	$words = str_replace("%", "\\%", $words);
    	$words = str_replace("_", "\\_", $words);
	$words =  preg_replace("/(%){1,}/s", '%', $words);
	//$words = explode(" ",strtolower(addslashes($words)));
	//$words = str_replace("'","",$words);
	$num_rows = '';
	$querywords = str_replace('%','',$words);
	//echo '<br>|'.$querywords.'|<br>';
	//if (strlen($querywords)>2 && strlen($querywords)<30)
	if (!empty($querywords))
	{
		//$querywords = preg_replace("/[\(\)\"':*;%,\[\]?!#{}.&_$<>|=`\-+\\\\]/s"," ",$querywords); // "'
		$querywords = eregi_replace("/"," ",$querywords);
		$querywords = eregi_replace("'",'',$querywords);
		$querywords = eregi_replace(" ([[0-9a-z]{1,2}) "," "," $querywords ");
		$querywords = eregi_replace(" ([[0-9a-z]{1,2}) "," "," $querywords ");
		$querywords = trim(strtolower($querywords));
		$querywords = eregi_replace("[[:space:]]+"," ", $querywords);
		$querywords = '%'.$querywords.'%';
		$querywords = eregi_replace(' ','% %',$querywords);
		$querywords = eregi_replace(' %%','',$querywords);
		$querywords = eregi_replace('%%','',$querywords);
		//$querywords = strtolower($querywords);
		$search_query_array = explode(" ", $querywords);
		$word_id_list = '';
		while (list($key,$val) = each($search_query_array))
		{
			save_data_search($val);
			$val = removeaccents($val);
			$query = " SELECT word_id, word_str FROM vcard_word WHERE word_str LIKE '".addslashes($val)."' ";
			$getwordid_array = $DB_site->query($query);
			//echo '<br>'.$query.'<br>';
			if($DB_site->num_rows($getwordid_array))
			{
				while( $wordidinfo = $DB_site->fetch_array($getwordid_array))
				{
					$word_id_list .="w $wordidinfo[word_id] ";
				}
			}
			$DB_site->free_result($getwordid_array);
		}
		//$DB_site->free_result($search_query_array);
		$word_id_list	= "'$word_id_list'";
		$word_id_list	= eregi_replace(" ","','",$word_id_list);
		$word_id_list	= eregi_replace(" ",",",$word_id_list);
		$word_id_list 	= eregi_replace(",''","",$word_id_list);
		//echo $word_id_list;
		//exit;
		$getcardlistresults = $DB_site->query("
			SELECT COUNT(vcard_search.card_id) AS score, vcard_search.card_id, vcard_cards.card_thmfile,vcard_cards.card_category, vcard_cards.card_imgfile, vcard_cards.card_caption, vcard_category.cat_active, vcard_category.cat_id,vcard_cards.card_rating,vcard_cards.card_date
			FROM vcard_search, vcard_cards, vcard_category 
			WHERE (vcard_cards.card_id = vcard_search.card_id) AND (vcard_category.cat_id = vcard_cards.card_category AND vcard_category.cat_active='1') AND vcard_search.word_id IN ($word_id_list)
			GROUP BY vcard_search.card_id
			ORDER BY score DESC
			");
		$resultsnumber = $DB_site->num_rows($getcardlistresults);
		// to category
		$cat_getresults = $DB_site->query("
			SELECT COUNT(vcard_search.cat_id) AS score, vcard_search.cat_id, vcard_category.cat_name,vcard_category.cat_name,vcard_category.cat_img, vcard_category.cat_active
			FROM vcard_search, vcard_category 
			WHERE (vcard_category.cat_id = vcard_search.cat_id) AND cat_active='1' AND vcard_search.word_id IN ($word_id_list)
			GROUP BY vcard_search.cat_id
			ORDER BY score DESC
			");
		$cat_resultsnumber = $DB_site->num_rows($cat_getresults);
	}else{
		//echo 'tamanho minimo';
		$resultsnumber 		= 0;
		$cat_resultsnumber 	= 0;
	}
	
	if ($resultsnumber == 0)
	{
		$cards_table		= $MsgSearch_noresults;
		$subcategory_table 	= '';
		
	// result number > 0
	}elseif ($resultsnumber > 0){
		$cards_table = "<dd>$MsgSearch_returned $resultsnumber $MsgSearch_results</dd><dd>$MsgSearch_relevance</dd><BR><BR>";
		$cards_table .= "<table border='$thisborder' cellspacing='$thiscellspacing' cellpadding='$thiscellpadding' align='$thisalign'>";
		$relevance = 1;
		$icounter = 0;
		while ($search_result = $DB_site->fetch_array($getcardlistresults))
		{
			//$post_template 	= $search_result['card_template'];
			$post_image = $search_result['card_imgfile'];
			$post_imagethm = $search_result['card_thmfile'];
			$post_cat = $search_result['card_category'];
			$post_caption = $search_result['card_caption'];
			$post_id = $search_result['card_id'];
			$post_date = $search_result['card_date'];
			$post_rating = star_rating($search_result['card_rating']);
			$post_new = gethml_newbutton($search_result['card_date']);
			$cards_table .= "<td align='center' valign='middle' width='". get_widthpercent($gallery_table_cols) ."'> $relevance <br>";
			$post_thm_url = (eregi('http://',$post_imagethm))? $post_imagethm : "$site_image_url/$post_imagethm";
			eval("\$cards_table .= \"".get_template("postcard_imagelink")."\";");
			$cards_table .= '</td>';
			$icounter++;
			$relevance++;
			if ($icounter == $gallery_table_cols)
			{
				$cards_table .= '</tr><tr>';
				$icounter = 0;
			}
		}
		while (($icounter > 0) && ($icounter != $gallery_table_cols))
		{ 
			$cards_table .= '<td>&nbsp;</td>'; 
			$icounter++; 
		}
		$cards_table.= '</tr></table>';
		$DB_site->free_result($getcardlistresults);
	}
	$tmp_category_table = '';
	if ($cat_resultsnumber > 0)
	{
		$tmp_category_table .= "<table border='$thisborder' cellspacing='$thiscellspacing' cellpadding='$thiscellpadding' align='$thisalign'>";
		$icounter 	= 0;
		while ($catinfo = $DB_site->fetch_array($cat_getresults))
		{
			$tmp_category_table .= '<td align="center" valign="top">';
			$catinfo['cat_name'] = stripslashes(htmlspecialchars($catinfo[cat_name]));
			$catinfo['totalcards'] = get_total_ncards($catinfo[cat_id]);
			$cat_img_url = (eregi('http://',$catinfo['cat_img']))? $catinfo['cat_img'] : "$site_image_url/$catinfo[cat_img]";
			eval("\$tmp_category_table .= \"".get_template("category_imagelink")."\";");
			$tmp_category_table .= '</td>';
			$icounter++;
			if($icounter == $gallery_table_cols)
			{
				$tmp_category_table .='</tr><tr>';
				$icounter = 0;
			}
		}
		while (($icounter > 0) && ($icounter != $gallery_table_cols))
		{ 
			$tmp_category_table .= '<td>&nbsp;</td>';
			$icounter++; 
		}
		$tmp_category_table .='</tr></table>';
		$DB_site->free_result($cat_getresults);
	}
}


$categories_textlist = get_html_table_cattex();
$category_table = $tmp_category_table;
$totalcards = "$num_rows $MsgPostcards";
//$navbar = '';
$cards_table = $cards_table;
//$mainpagelink = '';
eval("make_output(\"".get_template("eventsearch")."\");");
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>