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
require('./lib.inc.php');

dothml_pageheader();
if (!empty($HTTP_GET_VARS['page'])){
	$page = $HTTP_GET_VARS['page']; 
}else{
	$page = $HTTP_POST_VARS['page'];
}
if (empty($page)){
	$page = 1; 
}
if (!empty($HTTP_GET_VARS['cat_id'])){
	$cat_id = $HTTP_GET_VARS['cat_id']; 
}else{
	$cat_id = $HTTP_POST_VARS['cat_id'];
}

$prev_page = $page - 1;
$next_page = $page + 1;
$page_start = ($admin_gallery_thm_per_page * $page) - $admin_gallery_thm_per_page; 

$query = " SELECT * FROM vcard_cards WHERE card_category='$cat_id' ";
$result = $DB_site->query($query);
$num_rows = $DB_site->num_rows($result);

if ($num_rows <= $admin_gallery_thm_per_page)
{
	$num_pages = 1;
}
elseif (($num_rows % $admin_gallery_thm_per_page) == 0)
{
	$num_pages = ($num_rows / $admin_gallery_thm_per_page);
}
else
{
	$num_pages = ($num_rows / $admin_gallery_thm_per_page) + 1;
}
$num_pages = (int) $num_pages;

if (($page > $num_pages) || ($page < 0))
{
	echo get_html_font($site_font_face,3,1,1,"$MsgInvalidePageNumber");
	echo gethtml_backbar("","browse.php?s=$s",1,"$MsgBack");
	dothml_pagefooter();
	exit;
}

$query = $query . " LIMIT $page_start, $admin_gallery_thm_per_page";  
$result = $DB_site->query($query); 
$i = 0;		

if ($num_rows == $i)
{
	echo get_html_font($site_font_face,3,1,1,"$MsgNoCardsinDB");
	echo gethtml_backbar("","browse?s=$s",1,"$MsgBack");
	exit;
}
else
{
	$result2 = $DB_site->query(" SELECT * FROM vcard_category WHERE cat_id='$cat_id' ");
	$myrow2 = $DB_site->fetch_array($result2);
	$catname2 = "$myrow2[cat_name]";
	dohtml_table_header("extrainfo","$num_rows $msg_admin_postcard");
	echo "<tr><td><table width=\"80%\" border=0 cellspacing=2 cellpadding=8 align=\"center\"><tr>";
	$icounter ="0";
	while ($cardinfo = $DB_site->fetch_array($result))
	{
		extract($cardinfo);
		$card_caption 	= stripslashes($card_caption);
		$post_thm_url   = (eregi('http://',$card_thmfile))? $card_thmfile : "$site_image_url/$card_thmfile";
		echo "<td align=\"center\" valign=\"middle\"><img src=\"$post_thm_url\" border=\"1\"><br>$card_caption<p>" .
		cexpr(($superuser==1 || $vcuser[caneditcard]==1),"<a href=\"cards.php?action=card_edit&card_id=$card_id&s=$s\">[ $msg_admin_menu_edit ]</a>","").
		cexpr(($superuser==1 || $vcuser[candeletecard]==1),"<a href=\"cards.php?action=delete&card_id=$card_id&s=$s\">[ $msg_admin_menu_delete ]</a>","").
		"</tr>\n";
		$icounter++;
		if ($icounter == $admin_gallery_cols)
		{
			echo "</tr><tr>\n"; $icounter="0";
		}
	}
}
$DB_site->free_result($result);

// Previous Link
if($prev_page)
{
	$nav_bar = "<a href=\"browse.php?cat_id=$cat_id&page=$prev_page&s=$s\">$MsgPrevious</a>";
}

for($i = 1; $i <= $num_pages; $i++)
{
	if($i != $page)
	{
		$nav_bar .= " <a href=\"browse.php?cat_id=$cat_id&page=$i&s=$s\">$i</a> ";
	}else{
		$nav_bar .= "<b>[$i]</b>";
	}
}

// Next Link
if($page != $num_pages)
{
	$nav_bar .= "<a href=\"browse.php?cat_id=$cat_id&page=$next_page&s=$s\">$MsgNext</a>";
}
echo "</td></tr></table>\n<div align=\"center\">";
echo get_html_font($site_font_face,2,0,0,"$nav_bar");
echo "</div></td></tr>\n";
dohtml_table_footer();

dothml_pagefooter();
exit;
?>
