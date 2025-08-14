<?php
/***************************************************************************
 *   script               : vCard PRO
 *   copyright            : (C) 2001-2002 Belchior Foundry
 *   website              : www.belchiorfoundry.com
 *
 *   This program is commercial software; you can´t redistribute it under
 *   any circumstance without explicit authorization from Belchior Foundry.
 *   http://www.belchiorfoundry.com/
 *
 ***************************************************************************/
define('IN_VCARD', true);
$cat_id = addslashes($HTTP_GET_VARS['cat_id']);
$cat_id = (int) $cat_id;

$templatesused = 'browsecategory,category_infotable,new_card_list,new_card_list_item,box_subcategory,box_randomcards';
$chaceitemused = 'topcat_'.$cat_id;
require('./lib.inc.php');

$thisborder = 0;
$thiscellspacing = 1;
$thiscellpadding = 2;
$thisalign = 'center';
$thiswidth = '100%';

if ( !empty($HTTP_GET_VARS['page']) ) { $page = addslashes($HTTP_GET_VARS['page']); }else{ $page='';}
if ( !empty($HTTP_GET_VARS['ra']) )   { $ra = addslashes($HTTP_GET_VARS['ra']);     }else{ $ra='';}
if ( empty($page))
{
	$page = 1;
}
$page_start = ($gallery_thm_per_page * $page) - $gallery_thm_per_page;
$create_ra = 0;
$use_randarray = 0;
$category_info = get_catinfo($cat_id);
if ($category_info['cat_sort'] == 0){ 			$query = " SELECT * FROM vcard_cards WHERE card_category='$cat_id' ORDER BY card_caption ";}
elseif ($category_info['cat_sort'] == 1 && empty($ra)){ $query = " SELECT * FROM vcard_cards WHERE card_category='$cat_id' ORDER BY RAND() "; $create_ra =1; } // random
elseif ($category_info['cat_sort'] == 1 && !empty($ra)){$query = " SELECT * FROM vcard_cards WHERE card_category='$cat_id' "; $use_randarray =1; } // random
elseif ($category_info['cat_sort'] == 2){ 		$query = " SELECT * FROM vcard_cards WHERE card_category='$cat_id' ORDER BY card_caption ";} // caption
elseif ($category_info['cat_sort'] == 3){ 		$query = " SELECT * FROM vcard_cards WHERE card_category='$cat_id' ORDER BY card_date ASC ";} // date asc
elseif ($category_info['cat_sort'] == 4){ 		$query = " SELECT * FROM vcard_cards WHERE card_category='$cat_id' ORDER BY card_date DESC ";} // date desc

$cardsincat = $DB_site->query($query);
$num_rows = $DB_site->num_rows($cardsincat);

if ($num_rows <= $gallery_thm_per_page){
	$num_pages = 1;
}elseif (($num_rows % $gallery_thm_per_page) == 0){
	$num_pages = ($num_rows / $gallery_thm_per_page);
}else{
	$num_pages = ($num_rows / $gallery_thm_per_page) + 1;
}
$num_pages = (int) $num_pages;
if (($page > $num_pages) ||($page < 0))
{
	$category_totalcards = "$num_rows $MsgPostcards";
	$category_subcategorylist = $temp_category_subcategorylist;
	$cards_table = $MsgErrorInvalidePageNumber;
	$DB_site->free_result($cardsincat);
	eval("make_output(\"".get_template("browsecategory")."\");");
	if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
	exit;
}

$temp_categories_list = '';
$array = make_array_sort($cat_count_array, array('+cat_order','+cat_name'));
for($i=0; $i < sizeof($array); $i++)
{
	if($array[$i]['cat_subid']==$cat_id && $array[$i]['cat_active']==1)
	{
		$total_cards_insubcat = $array[$i]['cat_ncards'];
		$temp_category_subcategorylist .= ' <span class="list_subcat_bullet"></span><a href="gbrowse.php?cat_id='. $array[$i]['cat_id'] . '">' . stripslashes($array[$i]['cat_name']) . '</a> ('.$array[$i]['cat_ncards'].')';
	}
	$temp_total_subcards += $subcat['cat_ncards'];
}


/* ############################ RANDOM SORT @@@@@@@@@@@@@@@@@@@@@@@@@@@ */
if ($use_randarray == 1)
{
	$temp_cards_table ="<table width='$gallery_table_width' border='$thisborder' cellspacing='$thiscellspacing' cellpadding='$thiscellpadding' align='$thisalign'>\n<tr>";
	$icounter = 0;
	$ra_ecardidarray = split(',', $ra);
	$ra_n = count($ra_ecardidarray);
	$ra_start = ($gallery_thm_per_page * $page) - $gallery_thm_per_page;
	for ($i=1; $i <= $gallery_thm_per_page ;$i++)
	{
		$ra_ecardid = $ra_ecardidarray[$ra_start + $i];
		if($postcardinfo = $DB_site->query_first("SELECT * FROM vcard_cards WHERE card_id='$ra_ecardid' "))
		{
			$post_imagethm 	= $postcardinfo['card_thmfile'];
			$post_caption 	= stripslashes($postcardinfo['card_caption']);
			$post_id 	= $postcardinfo['card_id'];
			$post_date	= get_date_readable($postcardinfo['card_date']);
			$post_rating	= star_rating($postcardinfo['card_rating']);
			$post_new	= gethml_newbutton($postcardinfo['card_date']);
			$temp_cards_table.= "<td align=\"center\" valign=\"middle\" width=\"". get_widthpercent($gallery_table_cols) ."\">";
			$post_thm_url   = (eregi('http://',$post_imagethm))? $post_imagethm : "$site_image_url/$post_imagethm";
			eval("\$temp_cards_table .= \"".get_template("postcard_imagelink")."\";");
			$temp_cards_table.= "</td>";

			$icounter++;
			if ($icounter == $gallery_table_cols)
			{
				$temp_cards_table.= "</tr><tr>\n"; $icounter="0";
			}
		}
		else
		{
			break;
		}	

	}
	while (($icounter > 0) && ($icounter != $gallery_table_cols))
	{ 
		$temp_cards_table.="<td>&nbsp;</td>"; 
		$icounter++; 
	} 
	$temp_cards_table.="</tr></table>\n";

// ################################ NO RANDOM SORT #############################
}else{	
	if($create_ra != 1) // first page from random cat
	{
		$query = $query . " LIMIT $page_start, $gallery_thm_per_page";
	}
	$result = $DB_site->query($query); 
	
	$temp_cards_table ="<table width=\"$gallery_table_width\" border=\"$thisborder\" cellspacing=\"$thiscellspacing\" cellpadding=\"$thiscellpadding\" align=\"$thisalign\">\n<tr>";
	$icounter = 0;
	$total_ecards = 0;
	$ra = 0;
	while ($postcardinfo = $DB_site->fetch_array($result))
	{
		if ($total_ecards < $gallery_thm_per_page)
		{
			$post_imagethm = $postcardinfo['card_thmfile'];
			$post_caption = stripslashes($postcardinfo['card_caption']);
			$post_id = $postcardinfo['card_id'];
			$post_date = get_date_readable($postcardinfo['card_date']);
			$post_rating = star_rating($postcardinfo['card_rating']);
			$post_new = gethml_newbutton($postcardinfo['card_date']);
			$temp_cards_table.= "<td align='center' valign='middle' width='". get_widthpercent($gallery_table_cols) ."'>";
			$post_thm_url = (eregi('http://',$post_imagethm))? $post_imagethm : "$site_image_url/$post_imagethm";
			eval("\$temp_cards_table .= \"".get_template("postcard_imagelink")."\";");
			$temp_cards_table.= '</td>';
			$icounter++;
			if ($icounter == $gallery_table_cols)
			{
				$temp_cards_table.= "</tr><tr>\n";
				$icounter = 0;
			}
		}
		$total_ecards++;
		$ra .= ','.$postcardinfo['card_id'];
	}
	
	while (($icounter > 0) && ($icounter != $gallery_table_cols))
	{ 
		$temp_cards_table.='<td>&nbsp;</td>'; 
		$icounter++; 
	} 
	$temp_cards_table.='</tr></table>';

}// norandom
	

$temp_navbar ='';
for ($i = 1; $i<=$num_pages; $i++)
{
	if ($i != $page)
	{
		$temp_navbar .= " <a href='gbrowse.php?cat_id=$cat_id&page=$i". cexpr($category_info[cat_sort] == 1,"&ra=$ra","")."'>[$i]</a> ";
	}else{
		$temp_navbar .= " $i ";
	}
}
$temp_category_name= '';
if ($category_info['cat_subid'] !=0 && !empty($category_info['cat_subid']))
{
	$parentcatinfo = $DB_site->query_first("SELECT cat_id,cat_name FROM vcard_category WHERE cat_id='$category_info[cat_subid]'");
	$parentcatinfo['cat_name'] = stripslashes($parentcatinfo['cat_name']);
	$temp_category_name .= "<a href='gbrowse.php?cat_id=$parentcatinfo[cat_id]'>$parentcatinfo[cat_name]</a> &raquo; ";
}
$temp_category_name = "<a href='$site_url'>$MsgHome</a> &raquo; $temp_category_name <a href='gbrowse.php?cat_id=$cat_id'>$category_info[cat_name]</a>";


$temp_navbar = "$MsgPage $page $MsgPageOf $num_pages &raquo; $temp_navbar";

if ($temp_total_subcards != 0)
{
	$total_subcards = "$temp_total_subcards $MsgPostcards";
}
if ($num_rows != 0)
{
	$navbar = $temp_navbar;
}

if($vcachesys != 1)
{
	$topx_list_cat = get_html_toplist($cat_id);
}else{
	$topx_list_cat = get_vc_cached_cattoplist($cat_id);
}
$box_randomcards = get_html_boxrandomcards($cat_id);
$subcategory_table = getsubcat($cat_id);
$box_subcategory = getboxsubcategory($cat_id);

$topx = "$MsgTop $gallery_toplist_value $MsgPostcards";
$category_totalcards = "$num_rows $MsgPostcards";
$totalcards_catandsubcats = get_total_ncards_catandsubcat($cat_id)." $MsgPostcards";
$mainpagelink = "<p><div align='center'><a href='index.php'>$MsgBackCatMain</a></div>";

$category_name = $temp_category_name;
$category_header = stripslashes($category_info['cat_header']);
$category_footer = stripslashes($category_info['cat_footer']);
$category_subcategorylist = $temp_category_subcategorylist;
$cards_table = $temp_cards_table;

eval("make_output(\"".get_template("browsecategory")."\");");
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>