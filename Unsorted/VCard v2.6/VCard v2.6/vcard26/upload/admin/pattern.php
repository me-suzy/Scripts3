<?php
define('IN_VCARD', true);
require("./lib.inc.php");

dothml_pageheader();

// ############################# DB ACTION #############################
if ($action == 'delete')
{
	dohtml_form_header("pattern","delete_yes",0,1);
	dohtml_table_header("delete","$msg_admin_op_confirm_question",2);
	dohtml_form_hidden("pattern_id",$HTTP_GET_VARS['pattern_id']);
	$html .= "<b>$msg_admin_menu_delete</b>";
	dohtml_form_label($msg_admin_op_confirm_question,$html);
	dohtml_table_footer();
	dohtml_form_footer($msg_button_confirm);
	exit;

}
if ($action == 'delete_yes' && ($superuser==1 || $vcuser[candeletepattern]==1))
{
	$result = $DB_site->query(" DELETE FROM vcard_pattern WHERE pattern_id='$HTTP_POST_VARS[pattern_id]' ");
	dohtml_result($result,"$msg_admin_reg_delete");
	$action = '';
}


if ($action == 'active')
{
	$result = $DB_site->query(" UPDATE vcard_pattern SET pattern_active='1' WHERE pattern_id='$HTTP_GET_VARS[pattern_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'deactive')
{
	$result = $DB_site->query(" UPDATE vcard_pattern SET pattern_active='0' WHERE pattern_id='$HTTP_GET_VARS[pattern_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'insert')
{
	checkfieldempty($HTTP_POST_VARS[pattern_path],"$msg_admin_path $msg_admin_error_formempty");
	checkfieldempty($HTTP_POST_VARS[pattern_name],"$msg_admin_name $msg_admin_error_formempty");
	// Check if exist
	$check = file_exists("$site_image_path/$HTTP_POST_VARS[pattern_path]");
	checkfile($check,$HTTP_POST_VARS[pattern_path]);
	$result = $DB_site->query(" INSERT INTO vcard_pattern ( pattern_file, pattern_name) VALUES ('$HTTP_POST_VARS[pattern_path]','".addslashes($HTTP_POST_VARS[pattern_name])."') ");
	dohtml_result($result,"$msg_admin_reg_add");
	$action = 'add';
}

if ($action == 'do_upload')
{
	$datefilename = date("Y-m-d_His");
	checkfieldempty($HTTP_POST_FILES['pattern_file'],"$MsgA_pattern_file $msg_admin_error_formempty");
	checkfieldempty($HTTP_POST_VARS['pattern_name'],"$msg_admin_name $msg_admin_error_formempty");

	// get file extension.
	$extension	= get_file_extension($HTTP_POST_FILES['pattern_file']['name']);
	$new_pattern_file = "pat_$datefilename.$extension";
	//echo $HTTP_POST_FILES['pattern_file']['tmp_name']."/".$HTTP_POST_FILES['pattern_file']['name']."/$pattern_name,1,0)";
	do_fileupload($HTTP_POST_FILES['pattern_file']['tmp_name'],$HTTP_POST_FILES['pattern_file']['name'],$new_pattern_file,1,0);
	$result = $DB_site->query(" INSERT INTO vcard_pattern ( pattern_file, pattern_name) VALUES ('$new_pattern_file','".addslashes($HTTP_POST_VARS['pattern_name'])."') ");
	dohtml_result($result,"$msg_admin_reg_add");
	$action = 'upload';
}

// ############################# ACTION SCREENS #############################
// SCREEN = DELETE
if (empty($action))
{
dohtml_form_header("pattern","delete",0,0);
dohtml_table_header("delete","$msg_admin_menu_delete",2);
	$patternlist = $DB_site->query(" SELECT * FROM vcard_pattern ORDER BY pattern_id ");
	$icounter ="0";
	while ($pattern = $DB_site->fetch_array($patternlist))
	{
		extract($pattern);
		// Display list fo pattern
		$html .= "<li class=\"".get_row_bg()."\"><img src=\"$site_image_url/$pattern_file\" width=40 height=40 border=1 align=\"MIDDLE\">&nbsp;" .
		cexpr($pattern_active==1,"<a href=\"pattern.php?action=deactive&pattern_id=$pattern_id&s=$s\">[$msg_admin_menu_deactive]</a>&nbsp;","<a href=\"pattern.php?action=active&pattern_id=$pattern_id&s=$s\">[$msg_admin_menu_active]</a>&nbsp;") .
		cexpr(($superuser==1 || $vcuser[candeletepattern]==1),"<a href=\"pattern.php?action=delete&pattern_id=$pattern_id&s=$s\">[$msg_admin_menu_delete]</a>&nbsp;","") .
		"</li>";
	}
	$DB_site->free_result($patternlist);
dohtml_form_label($msg_admin_pattern,$html);
dohtml_table_footer();
exit;
}

// SCREEN = ADD
if ($action == 'add')
{
dohtml_form_header("pattern","insert",0,1);
dohtml_table_header("add","$msg_admin_menu_add");
dohtml_form_input($msg_admin_path,"pattern_path","");
dohtml_form_input($msg_admin_name,"pattern_name","");
dohtml_form_infobox($msg_admin_help_imagepath);
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// SCREEN = UPLOAD
if ($action == 'upload')
{
dohtml_form_header("pattern","do_upload",1,1);
dohtml_table_header("upload","$msg_admin_menu_add: $msg_admin_menu_upload");
dohtml_form_file($msg_admin_file,"pattern_file",10000000);
dohtml_form_input($msg_admin_name,"pattern_name","");
dohtml_form_infobox($msg_admin_help_safemode);
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}
?>