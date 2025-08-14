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

// ############################# DB ACTION #############################
if ($action == 'delete')
{
	dohtml_form_header("cardsgroup","cardsgroup_delete_yes",0,1);
	dohtml_table_header("delete","$msg_admin_op_confirm_question",2);
	dohtml_form_hidden("cardsgroup_id",$HTTP_POST_VARS['cardsgroup_id']);
	$html .= "<b>$msg_admin_menu_delete</b>";
	dohtml_form_label($msg_admin_op_confirm_question,$html);
	dohtml_table_footer();
	dohtml_form_footer($msg_button_confirm);
	exit;
}

if ($action == 'delete_yes')
{
	$result = $DB_site->query(" DELETE FROM vcard_cardsgroup WHERE cardsgroup_id='$HTTP_POST_VARS[cardsgroup_id]' ");
	dohtml_result($result,"$msg_admin_reg_delete");
	$action = '';
}

if ($action == 'update')
{
	$result = $DB_site->query("
	UPDATE vcard_cardsgroup
	SET
		cardsgroup_name = '".addslashes($HTTP_POST_VARS[cardsgroup_name])."',
		cardsgroup_fontface = '$HTTP_POST_VARS[cardsgroup_fontface]',
		cardsgroup_fontcolor = '$HTTP_POST_VARS[cardsgroup_fontcolor]',
		cardsgroup_fontsize = '$HTTP_POST_VARS[cardsgroup_fontsize]',
		cardsgroup_cardcolor = '$HTTP_POST_VARS[cardsgroup_cardcolor]',
		cardsgroup_background = '$HTTP_POST_VARS[cardsgroup_background]',
		cardsgroup_poem = '$HTTP_POST_VARS[cardsgroup_poem]',
		cardsgroup_advancedate = '$HTTP_POST_VARS[cardsgroup_advancedate]',
		cardsgroup_stamp = '$HTTP_POST_VARS[cardsgroup_stamp]',
		cardsgroup_music = '$HTTP_POST_VARS[cardsgroup_music]',
		cardsgroup_notify = '$HTTP_POST_VARS[cardsgroup_notify]',
		cardsgroup_copy = '$HTTP_POST_VARS[cardsgroup_copy]',
		cardsgroup_layout = '$HTTP_POST_VARS[cardsgroup_layout]',
		cardsgroup_heading = '$HTTP_POST_VARS[cardsgroup_heading]',
		cardsgroup_signature = '$HTTP_POST_VARS[cardsgroup_signature]'
	WHERE cardsgroup_id='$HTTP_POST_VARS[cardsgroup_id]'
	");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'insert')
{
	checkfieldempty($HTTP_POST_VARS['cardsgroup_name'],"$msg_admin_title $msg_admin_error_formempty");
	// Check if exist

	$result = $DB_site->query("
	INSERT INTO vcard_cardsgroup 
	(cardsgroup_id, cardsgroup_name, cardsgroup_fontface, cardsgroup_fontcolor, cardsgroup_fontsize, cardsgroup_cardcolor, cardsgroup_background, cardsgroup_poem, cardsgroup_advancedate, cardsgroup_stamp, cardsgroup_music, cardsgroup_notify, cardsgroup_copy, cardsgroup_layout, cardsgroup_heading, cardsgroup_signature)
	VALUES
	(NULL,'".addslashes($HTTP_POST_VARS[cardsgroup_name])."','$HTTP_POST_VARS[cardsgroup_fontface]','$HTTP_POST_VARS[cardsgroup_fontcolor]','$HTTP_POST_VARS[cardsgroup_fontsize]','$HTTP_POST_VARS[cardsgroup_cardcolor]','$HTTP_POST_VARS[cardsgroup_background]','$HTTP_POST_VARS[cardsgroup_poem]','$HTTP_POST_VARS[cardsgroup_advancedate]','$HTTP_POST_VARS[cardsgroup_stamp]','$HTTP_POST_VARS[cardsgroup_music]','$HTTP_POST_VARS[cardsgroup_notify]','$HTTP_POST_VARS[cardsgroup_copy]','$HTTP_POST_VARS[cardsgroup_layout]','$HTTP_POST_VARS[cardsgroup_heading]','$HTTP_POST_VARS[cardsgroup_signature]') 
	");
	dohtml_result($result,"$msg_admin_reg_add");
	$action = 'add';
}


// ############################# ACTION SCREENS #############################
// SCREEN = EDIT
if ($action == 'edit')
{
	$groupinfo = $DB_site->query_first("SELECT * FROM vcard_cardsgroup WHERE cardsgroup_id='$cardsgroup_id' ");
	extract($groupinfo);
	$cardsgroup_name = stripslashes(htmlspecialchars($cardsgroup_name));
	$temp_catlist = categoryoption(0,$userinfo[canworkcategory]);

dohtml_form_header("cardsgroup","update",0,0);
dohtml_table_header("edit","$msg_admin_menu_edit");
dohtml_form_hidden("cardsgroup_id",$cardsgroup_id);
dohtml_form_input($msg_admin_name,"cardsgroup_name",$cardsgroup_name);
dohtml_form_yesno($msg_admin_fontface,"cardsgroup_fontface",$cardsgroup_fontface);
dohtml_form_yesno($msg_admin_fontcolor,"cardsgroup_fontcolor",$cardsgroup_fontcolor);
dohtml_form_yesno($msg_admin_fontsize,"cardsgroup_fontsize",$cardsgroup_fontsize);
dohtml_form_yesno($msg_admin_cardcolor,"cardsgroup_cardcolor",$cardsgroup_cardcolor);
dohtml_form_yesno($msg_admin_background,"cardsgroup_background",$cardsgroup_background);
dohtml_form_yesno($msg_admin_poem,"cardsgroup_poem",$cardsgroup_poem);
dohtml_form_yesno($msg_admin_advancedate,"cardsgroup_advancedate",$cardsgroup_advancedate);
dohtml_form_yesno($msg_admin_stamp,"cardsgroup_stamp",$cardsgroup_stamp);
dohtml_form_yesno($msg_admin_music,"cardsgroup_music",$cardsgroup_music);
dohtml_form_yesno($msg_admin_notify,"cardsgroup_notify",$cardsgroup_notify);
dohtml_form_yesno($msg_admin_copy,"cardsgroup_copy",$cardsgroup_copy);
dohtml_form_yesno($msg_admin_layout,"cardsgroup_layout",$cardsgroup_layout);
dohtml_form_yesno($msg_admin_heading,"cardsgroup_heading",$cardsgroup_heading);
dohtml_form_yesno($msg_admin_signature,"cardsgroup_signature",$cardsgroup_signature);
dohtml_form_infobox($msg_admin_cardsgroup_note);
dohtml_form_footer($msg_admin_reg_edit);
dothml_pagefooter();
exit;
}

// SCREEN = ADD
if ($action == 'add')
{
dohtml_form_header("cardsgroup","insert",0,1);
dohtml_table_header("add","$msg_admin_menu_add");
dohtml_form_input($msg_admin_name,"cardsgroup_name","");
dohtml_form_yesno($msg_admin_fontface,"cardsgroup_fontface","");
dohtml_form_yesno($msg_admin_fontcolor,"cardsgroup_fontcolor","");
dohtml_form_yesno($msg_admin_fontsize,"cardsgroup_fontsize","");
dohtml_form_yesno($msg_admin_cardcolor,"cardsgroup_cardcolor","");
dohtml_form_yesno($msg_admin_background,"cardsgroup_background","");
dohtml_form_yesno($msg_admin_poem,"cardsgroup_poem","");
dohtml_form_yesno($msg_admin_advancedate,"cardsgroup_advancedate","");
dohtml_form_yesno($msg_admin_stamp,"cardsgroup_stamp","");
dohtml_form_yesno($msg_admin_music,"cardsgroup_music","");
dohtml_form_yesno($msg_admin_notify,"cardsgroup_notify","");
dohtml_form_yesno($msg_admin_copy,"cardsgroup_copy","");
dohtml_form_yesno($msg_admin_layout,"cardsgroup_layout","");
dohtml_form_yesno($msg_admin_heading,"cardsgroup_heading","");
dohtml_form_yesno($msg_admin_signature,"cardsgroup_signature","");
dohtml_form_infobox($msg_admin_cardsgroup_note);
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// SCREEN = EDIT
if (empty($action))
{
dohtml_table_header("delete","$msg_admin_menu_edit",2);
	$getgroup = $DB_site->query(" SELECT * FROM vcard_cardsgroup ORDER BY cardsgroup_name ");
	while($groupinfo = $DB_site->fetch_array($getgroup))
	{
		$html .= "<li class=\"".get_row_bg()."\">$groupinfo[cardsgroup_name] - 
		<a href=\"cardsgroup.php?action=edit&cardsgroup_id=$groupinfo[cardsgroup_id]&s=$s\">[$msg_admin_menu_edit]</a>
		<a href=\"cardsgroup.php?action=delete&cardsgroup_id=$groupinfo[cardsgroup_id]&s=$s\">[$msg_admin_menu_delete]</a>
		</li>";
	}
	$DB_site->free_result($getgroup);
dohtml_form_label($msg_admin_cardsgroup,$html);
dohtml_form_infobox($msg_admin_cardsgroup_notes);
dohtml_table_footer();
dothml_pagefooter();
exit;
}
?>