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
$templatesused = 'toppage';
require('./lib.inc.php');

$thisborder = 0;
$thiscellspacing = 1;
$thiscellpadding = 2;
$thisalign = 'center';
$thiswidth = '100%';

$htmlday = '';
if (!isset($page))
{
	$page = 1;
}
$prev_page 	= $page - 1;
$next_page 	= $page + 1;
$page_start 	= ($gallery_thm_per_page * $page) - $gallery_thm_per_page;

$query = "SELECT count(cd.card_id) AS score, cd.card_id, cd.card_category, cd.card_thmfile, cd.card_caption , cd.card_rating,cd.card_date
FROM vcard_stats AS s
   LEFT JOIN vcard_cards AS cd ON s.card_id=cd.card_id
   LEFT JOIN vcard_category AS ct ON cd.card_category=ct.cat_id
WHERE ct.cat_active='1'
GROUP BY s.card_id
ORDER BY score DESC
LIMIT $page_start, $gallery_thm_per_page
";
$card_array	= $DB_site->query($query);

$num_rows 	= $DB_site->num_rows($card_array);

if ($num_rows <= $gallery_thm_per_page)
{
	$num_pages = 1;
}
elseif (($num_rows % $gallery_thm_per_page) == 0)
{
	$num_pages = ($num_rows / $gallery_thm_per_page);
}
else
{
	$num_pages = ($num_rows / $gallery_thm_per_page) + 1;
}
$num_pages = (int) $num_pages;

$htmltotal 	= "<table border='$thisborder' cellspacing='$thiscellspacing' cellpadding='$thiscellpadding' align='$thisalign'><tr>\n";
$icounter 	= 0;
while ($postcardinfo = $DB_site->fetch_array($card_array))
{
	$htmltotal .= '<td align="center" valign="top">';
	$post_imagethm = $postcardinfo['card_thmfile'];
	$post_caption = stripslashes($postcardinfo['card_caption']);
	$post_id = $postcardinfo['card_id'];
	$post_date = get_date_readable($postcardinfo['card_date']);
	$post_rating = star_rating($postcardinfo['card_rating']);
	$post_new = gethml_newbutton($postcardinfo['card_date']);
	$card_thm_image = "<img src='$site_image_url/$post_imagethm' border='0' ". cexpr($gallery_thm_width,"width='$gallery_thm_width' height='$gallery_thm_height' ","") ." hspace='2' vspace='2'>";
	$post_thm_url = (eregi('http://',$post_imagethm))? $post_imagethm : "$site_image_url/$post_imagethm";
	eval("\$htmltotal .= \"".get_template("postcard_imagelink")."\";");
	$htmltotal .= '</td>';
	
	$icounter++;
	if($icounter == $gallery_table_cols)
	{
		$htmltotal .= '</tr><tr>';
		$icounter = 0;
	}
}
while (($icounter > 0) && ($icounter != $gallery_table_cols))
{ 
	$htmltotal	.= '<td>&nbsp;</td>'; 
	$icounter++; 
} 
$htmltotal .= '</tr></table>';
$DB_site->free_result($card_array);

$unixtime = time();
$query ="SELECT UNIX_TIMESTAMP(date), COUNT(s.card_id) AS score, cd.card_id, cd.card_thmfile, cd.card_caption, cd.card_rating, cd.card_date
FROM vcard_stats AS s
   LEFT JOIN vcard_cards AS cd ON s.card_id=cd.card_id
   LEFT JOIN vcard_category AS ct ON ct.cat_id=cd.card_category
WHERE WEEK(date)=WEEK(FROM_UNIXTIME($unixtime)) AND ct.cat_active='1'
GROUP BY s.card_id
ORDER BY score DESC
LIMIT $page_start, $gallery_thm_per_page
";
$card_array	= $DB_site->query($query);

$num_rows 	= $DB_site->num_rows($card_array);

$htmlweek	= "<br><table border='$thisborder' cellspacing='$thiscellspacing' cellpadding='$thiscellpadding' align='$thisalign'><tr>\n";
$icounter 	= 0;
while ($postcardinfo = $DB_site->fetch_array($card_array))
{
	$htmlweek .= '<td align="center" valign="top">';
	$post_imagethm = $postcardinfo['card_thmfile'];
	$post_caption = stripslashes($postcardinfo['card_caption']);
	$post_id = $postcardinfo['card_id'];
	$post_date = get_date_readable($postcardinfo['card_date']);
	$post_rating = star_rating($postcardinfo['card_rating']);
	$post_new = gethml_newbutton($postcardinfo['card_date']);
	$card_thm_image = "<img src='$site_image_url/$post_imagethm' border='0' ". cexpr($gallery_thm_width,"width='$gallery_thm_width' height='$gallery_thm_height' ","") ." hspace='2' vspace='2'>";
	$post_thm_url = (eregi('http://',$post_imagethm))? $post_imagethm : "$site_image_url/$post_imagethm";
	eval("\$htmlweek .= \"".get_template("postcard_imagelink")."\";");
	$htmlweek .= '</td>';
	
	$icounter++;
	if ($icounter == $gallery_table_cols)
	{
		$htmlweek .= '</tr><tr>';
		$icounter = 0;
	}
}
while (($icounter > 0) && ($icounter != $gallery_table_cols))
{ 
	$htmlweek	.= '<td>&nbsp;</td>'; 
	$icounter++; 
} 
$htmlweek .= '</tr></table>';
$DB_site->free_result($card_array);

$content_top_total = $htmltotal;
$content_top_day = $htmlday;
$content_top_week = $htmlweek;
$topx_list_cat 	= $topx_list;
eval("make_output(\"".get_template("toppage")."\");");
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>