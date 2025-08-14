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

// ############################# DB ACTION #############################
if ($action == 'delete')
{
	dohtml_form_header("poem","delete_yes",0,1);
	dohtml_table_header("edit","$msg_admin_op_confirm_question",2);
	dohtml_form_hidden("poem_id",$HTTP_GET_VARS['poem_id']);
	$html .= "<b>$msg_admin_menu_delete</b>";
	dohtml_form_label($msg_admin_op_confirm_question,$html);
	dohtml_table_footer();
	dohtml_form_footer($msg_button_confirm);
	exit;
}

if ($action == 'delete_yes' && ($superuser==1 || $vcuser[candeletepoem]==1))
{
	$result = $DB_site->query(" DELETE FROM vcard_poem WHERE poem_id='$HTTP_POST_VARS[poem_id]' ");
	dohtml_result($result,"$msg_admin_reg_delete");
	$action = '';
}

if ($action == 'active')
{
	$result = $DB_site->query(" UPDATE vcard_poem SET poem_active='1' WHERE poem_id='$HTTP_GET_VARS[poem_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'deactive')
{
	$result = $DB_site->query(" UPDATE vcard_poem SET poem_active='0' WHERE poem_id='$HTTP_GET_VARS[poem_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'update')
{
	$result = $DB_site->query(" UPDATE vcard_poem SET poem_title='".addslashes($HTTP_POST_VARS['poem_title'])."', poem_text='".addslashes($HTTP_POST_VARS['poem_text'])."', poem_style='".addslashes($HTTP_POST_VARS['poem_style'])."' WHERE poem_id='$HTTP_POST_VARS[poem_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'insert')
{
	checkfieldempty($HTTP_POST_VARS['poem_title'],"$msg_admin_title $msg_admin_error_formempty");
	checkfieldempty($HTTP_POST_VARS['poem_text'],"$msg_admin_text $msg_admin_error_formempty");
	// Check if exist

	$result = $DB_site->query(" INSERT INTO vcard_poem ( poem_title, poem_text, poem_style) VALUES ('".addslashes($HTTP_POST_VARS['poem_title'])."','".addslashes($HTTP_POST_VARS['poem_text'])."',".addslashes($HTTP_POST_VARS['poem_style']).") ");
	dohtml_result($result,"$msg_admin_reg_add");
	$action = 'add';
}


// ############################# ACTION SCREENS #############################
// SCREEN = EDIT
if ($action == 'edit')
{
	$poem_info = $DB_site->query_first(" SELECT * FROM vcard_poem WHERE poem_id='$HTTP_GET_VARS[poem_id]' ");
	$poem_title = stripslashes($poem_info['poem_title']);
	$poem_text = stripslashes($poem_info['poem_text']);
	
	
dohtml_form_header("poem","update",1,1);
dohtml_table_header("edit","$msg_admin_menu_edit");
dohtml_form_hidden("poem_id",$HTTP_GET_VARS['poem_id']);
dohtml_form_input($msg_admin_title,"poem_title",$poem_title);
dohtml_form_textarea($msg_admin_text,"poem_text",$poem_text,25,80);
dohtml_form_yesno($msg_admin_poem_style,"poem_style",$poem_info['poem_style']);
dohtml_form_infobox($msg_admin_poem_note_style);
dohtml_form_footer($msg_admin_reg_edit);
dothml_pagefooter();
exit;
}

// SCREEN = ADD
if ($action == 'add')
{
dohtml_form_header("poem","insert",0,1);
dohtml_table_header("add","$msg_admin_menu_add");
dohtml_form_input($msg_admin_title,"poem_title");
dohtml_form_textarea($msg_admin_text,"poem_text","",25,80);
dohtml_form_yesno($msg_admin_poem_style,"poem_style","");
dohtml_form_infobox($msg_admin_poem_note_style);
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// SCREEN = ADD
if ($action == 'view')
{

	$poem_info = $DB_site->query_first(" SELECT * FROM vcard_poem WHERE poem_id='$HTTP_GET_VARS[poem_id]' ");
	if($poem_info['poem_style']==1)
	{
		$poem_title 	= stripslashes($poem_info['poem_title']);
		$poem_text 	= stripslashes($poem_info['poem_text']);
	}else{
		$poem_title 	= stripslashes(htmlspecialchars($poem_info['poem_title']));
		$poem_text 	= nl2br(stripslashes(htmlspecialchars($poem_info['poem_text'])));
		$poem_text 	= eregi_replace("<br />", "<br>", $poem_text); 
	}
	$html = "<b>$poem_title</b><p>$poem_text</p>";
	dohtml_table_header("view",$msg_admin_menu_view);
	dohtml_form_label($msg_admin_poem,$html);
	dohtml_table_footer();
	dothml_pagefooter();
exit;
}

// SCREEN = EDIT
if (empty($action))
{
dohtml_table_header("delete","$msg_admin_menu_delete",2);
	$poemlist = $DB_site->query(" SELECT * FROM vcard_poem ORDER BY ".cexpr($site_lang_special,"poem_id","poem_title")." ");
	while ($poem = $DB_site->fetch_array($poemlist))
	{
		extract($poem);
		$poem_title = stripslashes(htmlspecialchars($poem_title));
		// Display list of poemfile
		$html .= "<li class=\"".get_row_bg()."\">".
		cexpr(($superuser==1 OR $vcuser[caneditpoem]==1),"<a href=\"poem.php?action=edit&poem_id=$poem_id&s=$s\">[$msg_admin_menu_edit]</a>" ,"").
		cexpr($poem_active==1,"<a href=\"poem.php?action=deactive&poem_id=$poem_id&s=$s\">[$msg_admin_menu_deactive]</a>","<a href=\"poem.php?action=active&poem_id=$poem_id&s=$s\">[$msg_admin_menu_active]</a>") . 
		cexpr(($superuser==1 OR $vcuser[candeletepoem]==1),"<a href=\"poem.php?action=delete&poem_id=$poem_id&s=$s\">[$msg_admin_menu_delete]</a>","") .
		"<a href=\"poem.php?action=view&poem_id=$poem_id&s=$s\">[$msg_admin_menu_view]</a> - $poem_title </li>";
	}
	$DB_site->free_result($poemlist);
dohtml_form_label($msg_admin_poem,$html);
dohtml_table_footer();
dothml_pagefooter();
exit;
}
?>