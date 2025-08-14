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

check_lvl_access($superuser);
dothml_pageheader();
// Category

function indexcat($cat_id,$reindex="0") {
	global $DB_site;
	
	if ($reindex == 1)
	{
		$DB_site->query("DELETE FROM vcard_search WHERE cat_id='$cat_id' ");
	}
	$catinfo = $DB_site->query_first("SELECT cat_id,cat_name FROM vcard_category WHERE cat_id='$cat_id' ");
	/* Open the noise words into an array */
	$allwords	= trim($catinfo['cat_name']);
	$allwords 	= ereg_replace("^"," ",$allwords);
	$noise_words 	= file("noisewords.txt");
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
	//echo $allwords;
	$wordarray	= explode(" ",$allwords);

	$words 		= $DB_site->query("SELECT word_id,word_str FROM vcard_word");
	
	while ($word = $DB_site->fetch_array($words))
	{
		$wordcache[$word[word_str]] = $word['word_id'];
	}
	$DB_site->free_result($words);

	for ($k=0; $k<count($wordarray); $k++)
	{
		if (strlen($wordarray[$k])>2 && strlen($wordarray[$k])<30)
		{
			$wordarray[$k] = stripslashes(trim($wordarray[$k]));
			//echo "|$wordarray[0]|\n";
			if (isset($wordcache[$wordarray[$k]]))
			{
				$word_id = $wordcache[$wordarray[$k]];
			}else{
				$insert = $DB_site->query("INSERT IGNORE INTO vcard_word(word_id,word_str) VALUES (NULL, '".addslashes($wordarray[$k])."')  ");
				$word_id = $DB_site->insert_id();
			}
			if ($word_id)
			{
				$DB_site->query("INSERT INTO vcard_search (word_id,card_id,cat_id) VALUES ('$word_id','$card_id','$cat_id') ");
			}
		}
	}
}

// ############################# DB ACTION #############################
// Order Categories
if ($action == 'order_cat')
{
	while (list($key,$val)=each($HTTP_POST_VARS['order']))
	{
		$DB_site->query("UPDATE vcard_category SET cat_order='$val' WHERE cat_id='$key'");
	}
	// echo "<p>Order updated!</p>";
	$action = '';
}

if ($action == 'cat_modify')
{
	$result = $DB_site->query(" UPDATE vcard_category SET cat_name='".addslashes($HTTP_POST_VARS['cat_name'])."', cat_subid='$HTTP_POST_VARS[cat_subid]', cat_img='".addslashes($HTTP_POST_VARS['cat_img'])."',cat_link='$HTTP_POST_VARS[cat_link]', cat_header='".addslashes($HTTP_POST_VARS['cat_header'])."', cat_footer='".addslashes($HTTP_POST_VARS['cat_footer'])."', cat_sort='$HTTP_POST_VARS[cat_sort]' WHERE cat_id='$HTTP_POST_VARS[cat_id]' ");
	indexcat($HTTP_POST_VARS['cat_id'],1);
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'cat_active')
{
	$result = $DB_site->query(" UPDATE vcard_category SET cat_active='1' WHERE cat_id='$HTTP_GET_VARS[cat_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	make_cachereflash();
	$action = '';
}

if ($action == 'cat_deactive')
{
	$result = $DB_site->query(" UPDATE vcard_category SET cat_active='0' WHERE cat_id='$HTTP_GET_VARS[cat_id]' ");
	$result2 = $DB_site->query(" UPDATE vcard_category SET cat_active='0' WHERE cat_subid='$HTTP_GET_VARS[cat_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	make_cachereflash();
	$action = '';
}

if ($action == 'cat_delete')
{
	dohtml_form_header("category","cat_delete_yes",0,1);
	dohtml_table_header("edit","$msg_admin_op_confirm_question",2);
	dohtml_form_hidden("cat_id",$HTTP_GET_VARS['cat_id']);
	dohtml_form_hidden("sub_cat_of",$HTTP_GET_VARS['sub_cat_of']);
	$html .= "<b>$msg_admin_menu_delete</b>";
	dohtml_form_label($msg_admin_op_confirm_question,$html);
	dohtml_table_footer();
	dohtml_form_footer($msg_button_confirm);
	exit;
}

if ($action == 'cat_delete_yes')
{

	$main_cat = $HTTP_POST_VARS['cat_id'];
	$getcatlist = $DB_site->query(" SELECT cat_id FROM vcard_category WHERE cat_subid='$main_cat' ORDER BY cat_order ");
	$cat_id_list .= $HTTP_POST_VARS['cat_id']." ";
	// give me the list of subcats
	while ($catinfo = $DB_site->fetch_array($getcatlist))
	{
		$cat_id_list .= $catinfo['cat_id']." ";
	}
	$DB_site->free_result($getcatlist);
	$cat_id_list = get_sqlinlist($cat_id_list);
	
	$getcardlist = $DB_site->query(" SELECT card_id FROM vcard_cards WHERE card_category IN ($cat_id_list) ");
	while ($cardinfo = $DB_site->fetch_array($getcardlist))
	{
		$card_id_list .= $cardinfo['card_id']." ";
	}
	$DB_site->free_result($getcardlist);
	$card_id_list = get_sqlinlist($card_id_list);
	
	//echo "$cat_id_list/$card_id_list";
	//exit;

	$kill_cards = $DB_site->query(" DELETE FROM vcard_cards WHERE card_category IN ($cat_id_list) ");
	$kill_cats =  $DB_site->query(" DELETE FROM vcard_category WHERE cat_id IN ($cat_id_list) ");
	$kill_index =   $DB_site->query(" DELETE FROM vcard_search WHERE ( cat_id IN ($cat_id_list) ) OR ( card_id IN ($card_id_list)) ");
	dohtml_result($kill_cats,"$msg_admin_reg_delete");
	make_cachereflash();
	$action = '';
}

if ($action == 'cat_include')
{
	// check if is a valide data
	checkfieldempty($HTTP_POST_VARS['cat_name'],"$msg_admin_category $msg_admin_error_formempty");
	$result = $DB_site->query(" INSERT INTO vcard_category (cat_subid,cat_order, cat_name,cat_img,cat_link,cat_header,cat_footer,cat_sort) VALUES ('".addslashes($HTTP_POST_VARS['cat_subid'])."','01','".addslashes($HTTP_POST_VARS['cat_name'])."','".addslashes($HTTP_POST_VARS['cat_img'])."','".addslashes($HTTP_POST_VARS['cat_link'])."','".addslashes($HTTP_POST_VARS['cat_header'])."','".addslashes($HTTP_POST_VARS['cat_footer'])."','".addslashes($HTTP_POST_VARS['cat_sort'])."') ");
	$index_cat_id = $DB_site->insert_id();
	indexcat($index_cat_id);
	dohtml_result($result,"$msg_admin_reg_add");
	make_cachereflash();
	$action = 'cat_add';
}


// ############################# ACTION SCREENS #############################
// SCREEN = EDIT CATEGORY
if ($action == 'cat_edit')
{
	$catinfo 		= $DB_site->query_first("SELECT * FROM vcard_category WHERE cat_id='$HTTP_GET_VARS[cat_id]' ");
	$main_cat_id 		= $catinfo['cat_id'];
	$main_cat_subid 	= $catinfo['cat_subid'];
	$main_cat_name 		= stripslashes(htmlspecialchars($catinfo['cat_name']));
	$main_cat_img 		= $catinfo['cat_img'];
	$main_cat_header	= stripslashes($catinfo['cat_header']);
	$main_cat_footer	= stripslashes($catinfo['cat_footer']);
	$main_cat_sort		= $catinfo['cat_sort'];
	
dohtml_form_header("category","cat_modify",0,1);
dohtml_form_hidden("cat_id",$main_cat_id);
dohtml_table_header("edit","$msg_admin_menu_edit");
dohtml_form_input($msg_admin_name,"cat_name",$main_cat_name);

	$html = "<option value=\"\">$msg_admin_cat_main</option><option value=\"\"></option>";
	$subcatlist = $DB_site->query("SELECT * FROM vcard_category WHERE (cat_subid='') AND (cat_id!='$HTTP_GET_VARS[cat_id]') ORDER BY cat_order");
	while ($subcat_info = $DB_site->fetch_array($subcatlist))
	{
		//extract($subcat);
		$subcat_name 	= stripslashes(htmlspecialchars($subcat_info['cat_name']));
		$html .= "<option value=\"$subcat_info[cat_id]\"" . cexpr($subcat_info[cat_id]==$main_cat_subid," selected>$subcat_name</option>\n",">$subcat_name</option>\n") . "";
	}
$sort_options = "
<option value=\"0\"".cexpr($main_cat_sort == 0," selected","").">$msg_admin_sort_default</option>
<option value=\"1\"".cexpr($main_cat_sort == 1," selected","").">$msg_admin_sort_rad</option>
<option value=\"2\"".cexpr($main_cat_sort == 2," selected","").">$msg_admin_sort_caption</option>
<option value=\"3\"".cexpr($main_cat_sort == 3," selected","").">$msg_admin_sort_dateasc</option>
<option value=\"4\"".cexpr($main_cat_sort == 4," selected","").">$msg_admin_sort_datedesc</option>
";

dohtml_form_select($msg_admin_cat_sub,"cat_subid",$html);
dohtml_form_input($msg_admin_cat_image,"cat_img",$main_cat_img);
dohtml_form_yesno($msg_admin_cat_linktext,"cat_link",$catinfo['cat_link']);
dohtml_form_textarea($msg_admin_headercontent,"cat_header",$main_cat_header,10,50);
dohtml_form_textarea($msg_admin_footercontent,"cat_footer",$main_cat_footer,10,50);
dohtml_form_select($msg_admin_sort_title,"cat_sort",$sort_options);
dohtml_form_infobox($msg_admin_help_imagepath);	
dohtml_form_footer($msg_admin_reg_update);
dothml_pagefooter();
exit;

}

// SCREEN = ADD CATEGORY
if ($action == 'cat_add')
{
dohtml_form_header("category","cat_include",0,1);
dohtml_table_header("add","$msg_admin_menu_add");
dohtml_form_input($msg_admin_category,"cat_name","");
	$html = "
		<option value=\"\">$msg_admin_none</option>
		<option value=\"\"></option>";
$catlist = $DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='' ORDER BY cat_order ");
while ($cat = $DB_site->fetch_array($catlist))
{
	extract($cat);
	$cat_name = stripslashes(htmlspecialchars($cat_name));
	$html .= "<option value=\"$cat_id\">$cat_name</option>\n";
}
$sort_options = "
<option value=\"0\">$msg_admin_sort_default</option>
<option value=\"1\">$msg_admin_sort_rad</option>
<option value=\"2\">$msg_admin_sort_caption</option>
<option value=\"3\">$msg_admin_sort_dateasc</option>
<option value=\"4\">$msg_admin_sort_datedesc</option>
";
dohtml_form_select($msg_admin_cat_assub,"cat_subid",$html);
dohtml_form_input($msg_admin_cat_image,"cat_img","");
dohtml_form_yesno($msg_admin_cat_linktext,"cat_link","");
dohtml_form_textarea($msg_admin_headercontent,"cat_header","",10,50);
dohtml_form_textarea($msg_admin_footercontent,"cat_footer","",10,50);
dohtml_form_select($msg_admin_sort_title,"cat_sort",$sort_options);
dohtml_form_infobox($msg_admin_help_imagepath);	
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// SCREEN = DEFAULT
if (empty($action))
{
	dohtml_form_header("category","order_cat",0,1);
	dohtml_table_header("edit","$msg_admin_menu_edit");
	$html = "<table><tr><td>";
	$catlist = $DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='' ORDER BY cat_order ");
	while ($cat = $DB_site->fetch_array($catlist))
	{
		extract($cat);
		$cat_name 	= stripslashes(htmlspecialchars($cat_name));
		// Display list of categories
		$html .= "<ul>\n";
		$html .= "<li class=\"".get_row_bg()."\"><b>$cat_name</b> &nbsp; ($msg_admin_menu_order: <input type=\"text\" name=\"order[$cat_id]\" value=\"$cat_order\" size=\"3\">) <a href=\"category.php?action=cat_edit&cat_id=$cat_id&s=$s\">[$msg_admin_menu_edit]</a>&nbsp; " . cexpr($cat_active==1,"<a href=\"category.php?action=cat_deactive&cat_id=$cat_id&s=$s\">[$msg_admin_menu_deactivate]</a>","<a href=\"category.php?action=cat_active&cat_id=$cat_id&s=$s\">[<b>$msg_admin_menu_activate</b>]</a>") . "&nbsp;  <a href=\"category.php?action=cat_delete&cat_id=$cat_id&s=$s\">[$msg_admin_menu_delete]</a></li>\n";
		$subcatlist =$DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='$cat_id' ORDER BY cat_order ");
		$html .= "<ul>\n";
		while ($subcat = $DB_site->fetch_array($subcatlist))
		{
			extract($subcat);
			$cat_name = stripslashes(htmlspecialchars($cat_name));
			$html .= "<li class=\"".get_row_bg()."\"><b>$cat_name</b> &nbsp; ($msg_admin_menu_order: <input type=\"text\" name=\"order[$cat_id]\" value=\"$cat_order\" size=\"3\">) <a href=\"category.php?action=cat_edit&cat_id=$cat_id&s=$s\">[$msg_admin_menu_edit]</a>&nbsp;" . cexpr($cat_active==1,"<a href=\"category.php?action=cat_deactive&cat_id=$cat_id&s=$s\">[$msg_admin_menu_deactivate]</a>","<a href=\"category.php?action=cat_active&cat_id=$cat_id&s=$s\">[<b>$msg_admin_menu_activate</b>]</a>") . "&nbsp; <a href=\"category.php?action=cat_delete&cat_id=$cat_id&sub_cat_of=$cat_subid&s=$s\">[$msg_admin_menu_delete]</a></li>\n";
		}
		$DB_site->free_result($subcatlist);
		$html .= "</ul>\n";
		$html .= "</ul>\n";
	}
	$html .= "</td></tr></table>";
	$DB_site->free_result($catlist);
	dohtml_form_label($msg_admin_category,$html);
	dohtml_form_footer($msg_admin_reg_order);
	dothml_pagefooter();
	exit;
}

?>