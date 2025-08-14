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
require("./lib.inc.php");

dothml_pageheader();

// ############################# DB ACTION #############################
if ($action == 'delete')
{
	dohtml_form_header("stamp","delete_yes",0,1);
	dohtml_table_header("edit","$msg_admin_op_confirm_question",2);
	dohtml_form_hidden("stamp_id",$HTTP_GET_VARS[stamp_id]);
	$html .= "<b>$msg_admin_menu_delete</b>";
	dohtml_form_label($msg_admin_op_confirm_question,$html);
	dohtml_table_footer();
	dohtml_form_footer($msg_button_confirm);
	exit;

}
if ($action == 'delete_yes' && ($superuser==1 || $vcuser[candeletestamp]==1))
{
	$result = $DB_site->query(" DELETE FROM vcard_stamp WHERE stamp_id='$HTTP_POST_VARS[stamp_id]' ");
	dohtml_result($result,"$msg_admin_reg_delete");
	$action = '';
}

if ($action == 'active')
{
	$result = $DB_site->query(" UPDATE vcard_stamp SET stamp_active='1' WHERE stamp_id='$HTTP_GET_VARS[stamp_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'deactive')
{
	$result = $DB_site->query(" UPDATE vcard_stamp SET stamp_active='0' WHERE stamp_id='$HTTP_GET_VARS[stamp_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}


if ($action == 'insert')
{
	checkfieldempty($HTTP_POST_VARS[stamp_path],"$msg_admin_path $msg_admin_error_formempty");
	checkfieldempty($HTTP_POST_VARS[stamp_name],"$msg_admin_name $msg_admin_error_formempty");
	// Check if exist
	$check = file_exists("$site_image_path/$HTTP_POST_VARS[stamp_path]");
	checkfile($check,$HTTP_POST_VARS['stamp_path']);
	$result = $DB_site->query(" INSERT INTO vcard_stamp ( stamp_file, stamp_name) VALUES ('$HTTP_POST_VARS[stamp_path]','".addslashes($HTTP_POST_VARS['stamp_name'])."') ");
	dohtml_result($result,"$msg_admin_reg_add");
	$action = 'add';
}

if ($action == 'do_upload')
{
	$datefilename = date("Y-m-d_His");
	checkfieldempty($HTTP_POST_FILES['stamp_file'],"$msg_admin_file $msg_admin_error_formempty");
	checkfieldempty($HTTP_POST_VARS['stamp_name'],"$msg_admin_name $msg_admin_error_formempty");

	// get file extension.
	$extension	= get_file_extension($HTTP_POST_FILES['stamp_file']['name']);
	//echo $HTTP_POST_FILES['stamp_file']['name'];
	$new_stamp_file = "stamp_$datefilename.$extension";
	// echo $HTTP_POST_FILES['stamp_file']['tmp_name']."/".$HTTP_POST_FILES['stamp_file'][name]."/$stamp_file,1,0) ";
	do_fileupload($HTTP_POST_FILES['stamp_file']['tmp_name'],$HTTP_POST_FILES['stamp_file']['name'],$new_stamp_file,1,0);

	$result = $DB_site->query(" INSERT INTO vcard_stamp ( stamp_file, stamp_name) VALUES ('$new_stamp_file','".addslashes($HTTP_POST_VARS['stamp_name'])."') ");
	dohtml_result($result,"$msg_admin_reg_add");
	$action = 'upload';
}

// ############################# ACTION SCREENS #############################
// SCREEN = DELETE
if (empty($action))
{

dohtml_form_header("stamp","delete",0,0);
dohtml_table_header("delete","$msg_admin_menu_edit",2);
	$stamplist = $DB_site->query(" SELECT * FROM vcard_stamp ORDER BY stamp_id ");
	$icounter = 0;
	while ($stamp = $DB_site->fetch_array($stamplist))
	{
		extract($stamp);
		// Display list fo stamp
		$html .= "<li class=\"".get_row_bg()."\"><img src=\"$site_image_url/$stamp_file\" border=0 align=\"MIDDLE\">&nbsp;" .
		cexpr($stamp_active==1,"<a href=\"stamp.php?action=deactive&stamp_id=$stamp_id&s=$s\">[$msg_admin_menu_deactive]</a>&nbsp;","<a href=\"stamp.php?action=active&stamp_id=$stamp_id&s=$s\">[$msg_admin_menu_active]</a>&nbsp;") .
		cexpr(($superuser==1 || $vcuser[candeletestamp]==1),"<a href=\"stamp.php?action=delete&stamp_id=$stamp_id&s=$s\">[$msg_admin_menu_delete]</a>&nbsp;","").
		"</li>";
	}
	$DB_site->free_result($stamplist);
dohtml_form_label($msg_admin_stamp,$html);
dohtml_table_footer();
exit;
}

// SCREEN = ADD
if ($action == 'add')
{
dohtml_form_header("stamp","insert",0,1);
dohtml_table_header("add","$msg_admin_menu_add");
dohtml_form_input($msg_admin_path,"stamp_path","");
dohtml_form_input($msg_admin_name,"stamp_name","");
dohtml_form_infobox($msg_admin_help_imagepath);
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// SCREEN = UPLOAD
if ($action == 'upload')
{
dohtml_form_header("stamp","do_upload",1,1);
dohtml_table_header("upload","$msg_admin_menu_add: $msg_admin_menu_upload");
dohtml_form_file($msg_admin_file,"stamp_file",10000000);
dohtml_form_input($msg_admin_name,"stamp_name","");
dohtml_form_infobox($msg_admin_help_safemode);
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
}
?>