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
$templatesused = 'newcardspage';
require('./lib.inc.php');

$thisborder = 0;
$thiscellspacing = 2;
$thiscellpadding = 8;
$thisalign = 'center';
$thiswidth = '100%';

if ( !empty($HTTP_GET_VARS['page']) ) { $page = addslashes($HTTP_GET_VARS['page']); }else{ $page='';}
if (empty($page))
{
	$page = 1;
}
$prev_page 	= $page - 1;
$next_page 	= $page + 1;
$page_start 	= ($gallery_thm_per_page * $page) - $gallery_thm_per_page;

$carddate_range = date ("Y-m-d", mktime (0,0,0,date("m"),date("d")-$site_new_days, date("Y")));
//echo $carddate_range;
$query = ("SELECT vcard_cards.card_id, vcard_cards.card_date, vcard_cards.card_category, vcard_cards.card_thmfile, vcard_cards.card_caption, vcard_category.cat_id, vcard_category.cat_name,vcard_cards.card_rating,vcard_cards.card_date
	FROM vcard_cards, vcard_category
	WHERE vcard_cards.card_date >='$carddate_range' AND vcard_cards.card_category=vcard_category.cat_id AND vcard_category.cat_active='1'
	GROUP BY card_date,card_id
	ORDER BY card_id DESC ");
//echo $query;
// $site_new_days > $days
// $last_cardday = date ("Y-m-d", mktime (0,0,0,date("m"),date("d")-$site_new_days, date("Y")))


$result = $DB_site->query($query);
$num_rows = $DB_site->num_rows($result);

if ($num_rows <= $gallery_thm_per_page)
{
	$num_pages = 1;
}
elseif (($num_rows % $gallery_thm_per_page) == 0)
{
	$num_pages =($num_rows / $gallery_thm_per_page);
}
else
{
	$num_pages =($num_rows / $gallery_thm_per_page) + 1;
}
$num_pages = (int) $num_pages;

$query = $query . " LIMIT $page_start, $gallery_thm_per_page";
$result = $DB_site->query($query); 
	

$html 		= "<table width=\"$thiswidth\" border=\"$thisborder\" cellspacing=\"$thiscellspacing\" cellpadding=\"$thiscellpadding\" align=\"$thisalign\">";
$icounter 	= 0;

while ($postcardinfo = $DB_site->fetch_array($result))
{
	$html		.= "<td align=\"center\" valign=\"top\">";
	$post_imagethm 	= $postcardinfo['card_thmfile'];
	$post_caption 	= stripslashes($postcardinfo['card_caption']);
	$post_id 	= $postcardinfo['card_id'];
	$post_date	= get_date_readable($postcardinfo['card_date']);
	$post_rating	= star_rating($postcardinfo['card_rating']);
	$post_new	= gethml_newbutton($postcardinfo['card_date']);
	$card_thm_image  = "<img src=\"$site_image_url/$post_imagethm\" border=\"0\" ". cexpr($gallery_thm_width,"width=\"$gallery_thm_width\" height=\"$gallery_thm_height\" ","") ." hspace=\"2\" vspace=\"2\">";
	$post_thm_url   = (eregi('http://',$post_imagethm))? $post_imagethm : "$site_image_url/$post_imagethm";
	eval("\$html 	.= \"".get_template("postcard_imagelink")."\";");
	$html .= "</td>";
	
	$icounter++;
	if ($icounter == $gallery_table_cols)
	{
		$html.="</tr><tr>";
		$icounter = 0;
	}
}
while (($icounter > 0) && ($icounter != $gallery_table_cols))
{ 
	$html.="<td>&nbsp;</td>"; 
	$icounter++; 
} 
$html .= "</tr></table>";
$DB_site->free_result($result);

// NAV BAR LINKS
/////////////////////////////////////////////////////////////////
$tmp_navbar = '';
// Previous Link
if (isset($prev_page) && $prev_page!=0)
{
	$tmp_navbar = "&nbsp;<a href=\"gbrowse.php?page=$prev_page\">$MsgPrevious</a>";  
}

if ($num_rows != 0)
{
for ($i = 1; $i <= $num_pages; $i++)
{
	if($i != $page)
	{
		$tmp_navbar.= " <a href=\"newcards.php?page=$i\">[$i]</a> ";
	}else{
		$tmp_navbar.= " $i ";
	}
}
}

$PresentPage ="$page $MsgPageOf $num_pages";

// Next Link
if ($page != $num_pages)
{
$tmp_navbar.= "&nbsp;<a href=\"newcards.php?page=$next_page\">$MsgNext</a><br><br>";
}
$mainpagelink 	= "<p><div align=\"center\"><a href=\"index.php\">$MsgBackCatMain</a></div>";
$navbar	= "$MsgPage $PresentPage &raquo; $tmp_navbar";
	
$content 	= $html;
//$topx_list_cat 	= get_html_toplist();
$topx_list_cat 	= $topx_list;
eval("make_output(\"".get_template("newcardspage")."\");");
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>