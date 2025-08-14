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
require('./lib.inc.php');

check_lvl_access($canaddreplace);

dothml_pageheader();
// Category

// ############################# DB ACTION #############################
if ($action == 'replace_modify')
{
	$result = $DB_site->query(" UPDATE vcard_replace SET replaceword='".addslashes($HTTP_POST_VARS['replaceword'])."' WHERE replace_id='$HTTP_POST_VARS[replace_id]' ");
	dohtml_result($result,$msg_admin_reg_update);
	$action = '';
}

if ($action == 'replace_delete')
{
	echo "<b>$msg_admin_menu_delete</b>: $msg_admin_op_confirm_question<p>";
	echo "<a href='replace.php?action=replace_delete_yes&replace_id=$HTTP_GET_VARS[replace_id]&s=$HTTP_GET_VARS[s]'>$msg_admin_op_confirm_yes</a>";
	exit;
}

if ($action == 'replace_delete_yes')
{
	$result = $DB_site->query(" DELETE FROM vcard_replace WHERE replace_id='$HTTP_GET_VARS[replace_id]' ");
	// give me the list of subcats
	dohtml_result($result,$msg_admin_reg_delete);
	$action = '';
}

// ############################# DB ACTION #############################
if ($action == 'replace_add')
{
	checkfieldempty($HTTP_POST_VARS['findword'],"$msg_admin_find $msg_admin_error_formempty");
	checkfieldempty($HTTP_POST_VARS['replaceword'],"$msg_admin_replace $msg_admin_error_formempty");
	
	if (!$preexists = $DB_site->query_first("SELECT replace_id FROM vcard_replace WHERE findword='".addslashes($HTTP_POST_VARS['findword'])."' "))
	{
		$result = $DB_site->query(" INSERT INTO vcard_replace (replace_id,findword,replaceword) VALUES (NULL,'".addslashes($HTTP_POST_VARS['findword'])."','".addslashes($HTTP_POST_VARS['replaceword'])."') ");
	}else{
		$result = $DB_site->query("UPDATE vcard_replace SET replaceword='".addslashes($HTTP_POST_VARS['replaceword'])."' WHERE findword='".addslashes($HTTP_POST_VARS['findword'])."' ");
	}
	dohtml_result($result,$msg_admin_reg_add);
	$action = "";
}


// ############################# ACTION SCREENS #############################
// SCREEN = EDIT EVENT
if ($action == 'replace_edit')
{
	$replace_info = $DB_site->query_first(" SELECT * FROM vcard_replace WHERE replace_id='$HTTP_GET_VARS[replace_id]' ");
	$findword = stripslashes(htmlspecialchars($replace_info['findword']));
	$replaceword = stripslashes($replace_info['replaceword']);
	$replace_id = $replace_info['replace_id'];
dohtml_form_header("replace","replace_modify",0,1);
dohtml_table_header("edit",$msg_admin_menu_edit);
echo $replace_title;
//dohtml_form_label($msg_admin_replace,$html);
dohtml_form_label($msg_admin_name,$findword);
dohtml_form_hidden("replace_id",$replace_id);
dohtml_form_textarea($msg_admin_replace,"replaceword",$replaceword,25,80);
dohtml_form_footer($msg_admin_reg_update);
dothml_pagefooter();
exit;
}

// SCREEN = ADD EVENT
if ($action == 'add')
{
dohtml_form_header("replace","replace_add",0,1);
dohtml_table_header("add",$msg_admin_menu_add);
dohtml_form_input($msg_admin_find,"findword","");
dohtml_form_textarea($msg_admin_replace,"replaceword","",25,80);
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// SCREEN = DEFAULT
if (empty($action))
{
	dohtml_table_header("edit",$msg_admin_menu_edit,3);
	$replace_array = $DB_site->query(" SELECT * FROM vcard_replace ORDER BY replace_id ");
	$html .= "<table><tr><td><b>$msg_admin_find</b> &nbsp;</td><td><b>$msg_admin_replace</b> &nbsp;</td><td width='65%'>&nbsp;</td></tr>\n";
	while ($replace_item = $DB_site->fetch_array($replace_array))
	{
		//extract($event);
		$replace_id = $replace_item['replace_id'];
		$findword = stripslashes(htmlspecialchars($replace_item['findword']));
		$replaceword = stripslashes(htmlspecialchars($replace_item['replaceword']));
		// Display list of categories
		//$Month = cexpr($replace_month<10,"$MsgMonth[$replace_month]","$replace_month");
		$html .= "<tr class='".get_row_bg()."'><td valign='top'> $findword </td><td valign='top'> $replaceword </td><td valign='top'> &nbsp; <a href='replace.php?action=replace_edit&replace_id=$replace_id&s=$s'>[$msg_admin_menu_edit]</a>&nbsp; <a href='replace.php?action=replace_delete&replace_id=$replace_id&s=$s'>[$msg_admin_menu_delete]</a></td></tr>\n";
	}
	$DB_site->free_result($replace_array);
	$html .= '</table>';
	dohtml_form_label($msg_admin_replace,$html);
	dohtml_table_footer();
	dothml_pagefooter();
	exit;
}
?>
