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
$templatesused = 'mainpage,categories_list,categories_list_maincat,categories_list_subcat,calendar_list,calendar_list_day,calendar_list_month,new_card_list,new_card_list_item';
$chaceitemused = 'today_topcard,week_topcard,newcard,categories_table_upcat,categories_table_cat,categories_extended_list';
require('./lib.inc.php');

if (!isset($gallery_table_cols))
{
	$gallery_table_cols = 3;
}

if ($user_upload_allow == 1)
{
	$Upload_Option = "<a href='upload.php'>$MsgUploadYourOwnFileTitle</a>";
	$T_Upload_Option_Note = "$MsgUploadYourOwnFileInfo";
}

if($vcachesys != 1)
{
	$topcardofday = get_html_day_topcard();
	$topcardofweek = get_html_week_topcard();
	$new_card_list = get_html_newcard();
	$categories_list = get_html_cat_extended_list();
	get_categories_table();
}else{
	$topcardofday = get_vc_cached_item('today_topcard');
	$topcardofweek = get_vc_cached_item('week_topcard');
	$new_card_list = get_vc_cached_item('newcard');
	$categories_list = get_vc_cached_item('categories_extended_list');
	$categories_table = get_vc_cached_item('categories_table_cat');
	$categories_table_maincat = get_vc_cached_item("categories_table_upcat");
}

$totalcards_sent = $DB_site->count_records("vcard_user");

eval("make_output(\"".get_template("mainpage")."\");");
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>