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
$templatesused = 'topratedpage';
require('./lib.inc.php');

$thisborder = 0;
$thiscellspacing = 1;
$thiscellpadding = 2;
$thisalign = 'center';
$thiswidth = '100%';

if ( !empty($HTTP_GET_VARS['page']) ) { $page = addslashes($HTTP_GET_VARS['page']); }else{ $page='';}
if (empty($page))
{
	$page = 1;
}
$prev_page = $page - 1;
$next_page = $page + 1;
$page_start = ($gallery_thm_per_page * $page) - $gallery_thm_per_page;

$query = "SELECT cd.card_id, cd.card_date, cd.card_rating, cd.card_category, cd.card_thmfile, cd.card_caption, ct.cat_id, ct.cat_name, cd.card_rating, cd.card_date
	FROM vcard_cards AS cd
	  LEFT JOIN vcard_category AS ct ON cd.card_category=ct.cat_id
	WHERE ct.cat_active='1' AND cd.card_rating>'0'
	GROUP BY cd.card_rating,cd.card_id
	ORDER BY cd.card_rating DESC
	";
$query2 = "SELECT avg(rat.rating_value) AS final, cd.card_id, cd.card_date, cd.card_rating, cd.card_category, cd.card_thmfile, cd.card_caption, ct.cat_id, ct.cat_name, ct.cat_active, cd.card_rating, cd.card_date
	FROM vcard_rating AS rat, vcard_cards AS cd, vcard_category AS ct
	WHERE (cd.card_id=rat.rating_card)  AND
	      ( cd.card_category=ct.cat_id AND ct.cat_active='1' )
	GROUP BY rat.rating_card
	ORDER BY final DESC";
$query3 = "SELECT COUNT(rat.rating_card) AS score, cd.card_id, cd.card_thmfile, cd.card_caption, ct.cat_id, ct.cat_name, ct.cat_active, cd.card_rating, cd.card_date
FROM vcard_rating rat, vcard_cards cd, vcard_category ct
WHERE (cd.card_id=rat.rating_card)  AND
      ( cd.card_category=ct.cat_id AND ct.cat_active='1' )
GROUP BY rat.rating_card
ORDER BY score DESC
LIMIT 30
";
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

$query = $query . " LIMIT $page_start, $gallery_thm_per_page";
$result = $DB_site->query($query); 

$html 		= "<table border='$thisborder' cellspacing='$thiscellspacing' cellpadding='$thiscellpadding' align='$thisalign'><tr>\n";
$icounter 	= 0;

while ($postcardinfo = $DB_site->fetch_array($result))
{
	$html .= '<td align="center" valign="top">';
	$post_imagethm = $postcardinfo['card_thmfile'];
	$post_caption = stripslashes($postcardinfo['card_caption']);
	$post_id = $postcardinfo['card_id'];
	$post_date = get_date_readable($postcardinfo['card_date']);
	$post_rating = star_rating($postcardinfo['card_rating']);
	$post_new = gethml_newbutton($postcardinfo['card_date']);
	$card_thm_image = "<img src='$site_image_url/$post_imagethm' border='0' ". cexpr($gallery_thm_width,"width='$gallery_thm_width' height='$gallery_thm_height' ",'') ." hspace='2' vspace='2'>";
	$post_thm_url = (eregi('http://',$post_imagethm))? $post_imagethm : "$site_image_url/$post_imagethm";
	eval("\$html .= \"".get_template("postcard_imagelink")."\";");
	$html .= '</td>';
	
	$icounter++;
	if ($icounter == $gallery_table_cols)
	{
		$html.='</tr><tr>';
		$icounter = 0;
	}
}
while (($icounter > 0) && ($icounter != $gallery_table_cols))
{ 
	$html.='<td>&nbsp;</td>'; 
	$icounter++; 
} 
$html .= '</tr></table>';
$DB_site->free_result($card_array);

// NAV BAR LINKS
/////////////////////////////////////////////////////////////////
$tmp_navbar = '';
if (isset($prev_page) && $prev_page!=0) // Previous Link
{
	$tmp_navbar = "&nbsp;<a href='toprated.php?page=$prev_page'>$MsgPrevious</a>";  
}
if ($num_rows != 0)
{
	for ($i = 1; $i <= $num_pages; $i++)
	{
		if($i != $page)
		{
			$tmp_navbar.= " <a href='toprated.php?page=$i'>[$i]</a> ";
		}else{
			$tmp_navbar.= " $i ";
		}
	}
}
$PresentPage ="$page $MsgPageOf $num_pages";
if ($page != $num_pages)// Next Link
{
	$tmp_navbar.= "&nbsp;<a href='toprated.php?page=$next_page'>$MsgNext</a><br><br>";
}
$mainpagelink = "<p><div align='center'><a href='index.php'>$MsgBackCatMain</a></div>";
$navbar	= "$MsgPage $PresentPage &raquo; $tmp_navbar";


$content = $html;
$topx_list_cat = $topx_list;
eval("make_output(\"".get_template("topratedpage")."\");");
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>