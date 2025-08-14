<?php
define('IN_VCARD', true);
require("./lib.inc.php");

dothml_pageheader();

// ############################# DB ACTION #############################
// Order Categories
if ($action == 'order_sound')
{
	while (list($key,$val)=each($HTTP_POST_VARS['order']))
	{
		$DB_site->query("UPDATE vcard_sound SET sound_order='$val' WHERE sound_id='$key'");
	}
	// echo "<p>Order updated!</p>";
	$action = '';
}
if ($action == 'delete')
{
	echo "<b>$msg_admin_menu_delete</b>: $msg_admin_op_confirm_question<p>";
	echo "<a href=\"music.php?action=delete_yes&sound_id=$HTTP_GET_VARS[sound_id]&s=$s\">$msg_admin_op_confirm_yes</a>";
	exit;
}

if ($action == 'delete_yes' && ($superuser==1 || $vcuser[candeletemusic]==1))
{
	$result = $DB_site->query(" DELETE FROM vcard_sound WHERE sound_id='$HTTP_GET_VARS[sound_id]' ");
	dohtml_result($result,"$msg_admin_reg_delete");
	$action = '';
}

if ($action == 'active')
{
	$result = $DB_site->query(" UPDATE vcard_sound SET sound_active='1' WHERE sound_id='$HTTP_GET_VARS[sound_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'deactive')
{
	$result = $DB_site->query(" UPDATE vcard_sound SET sound_active='0' WHERE sound_id='$HTTP_GET_VARS[sound_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if($action == 'update')
{
	$result = $DB_site->query("
				UPDATE vcard_sound
				SET
				 sound_file='$HTTP_POST_VARS[sound_file]',
				 sound_name='".addslashes($HTTP_POST_VARS['sound_name'])."',
				 sound_author='".addslashes($HTTP_POST_VARS['sound_author'])."',
				 sound_genre='".addslashes($HTTP_POST_VARS['sound_genre'])."'
				WHERE sound_id='$HTTP_POST_VARS[sound_id]'
				");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if($action == 'insert')
{
	checkfieldempty($HTTP_POST_VARS['sound_path'],"$msg_admin_path $msg_admin_error_formempty");
	checkfieldempty($HTTP_POST_VARS['sound_name'],"$msg_admin_name $msg_admin_error_formempty");
	if (!eregi("http://",$HTTP_POST_VARS['sound_path']))
	{
		// Check if exist
		$check = file_exists("$site_music_path/$HTTP_POST_VARS[sound_path]");
		checkfile($check,$HTTP_POST_VARS['sound_path']);
	}
	$result = $DB_site->query("
		INSERT
		INTO vcard_sound ( sound_file, sound_name, sound_author, sound_genre)
		VALUES ('$HTTP_POST_VARS[sound_path]','".addslashes($HTTP_POST_VARS[sound_name])."','".addslashes($HTTP_POST_VARS[sound_author])."','".addslashes($HTTP_POST_VARS[sound_genre])."')
		");
	dohtml_result($result,"$msg_admin_reg_add");
	$action = 'add';
}

if ($action == 'do_upload')
{
	$datefilename = date("Y-m-d_His");
	//checkfieldempty($HTTP_POST_VARS['sound_file'],"$msg_admin_file $msg_admin_error_formempty");
	checkfieldempty($HTTP_POST_VARS['sound_name'],"$msg_admin_name $msg_admin_error_formempty");

	// get file extension.
	$extension	= get_file_extension($HTTP_POST_FILES['sound_file']['name']);
	$new_music_file = "music_$datefilename.$extension";
	//echo $new_music_file;
	do_fileupload($HTTP_POST_FILES['sound_file']['tmp_name'],$HTTP_POST_FILES['sound_file']['name'],$new_music_file,2,1);

	$query = ("
		INSERT
		INTO vcard_sound ( sound_file, sound_name, sound_author, sound_genre)
		VALUES ('$new_music_file','".addslashes($HTTP_POST_VARS['sound_name'])."','".addslashes($HTTP_POST_VARS['sound_author'])."','".addslashes($HTTP_POST_VARS['sound_genre'])."')
		");
	$result = $DB_site->query($query);
	dohtml_result($result,"$msg_admin_reg_add");
	$action ="upload";
}

// ############################# ACTION SCREENS #############################
// SCREEN = EDIT
if($action == 'edit')
{
	$music_info = $DB_site->query_first(" SELECT * FROM vcard_sound WHERE sound_id='$HTTP_GET_VARS[sound_id]' ");
	$sound_name = stripslashes(htmlspecialchars($music_info['sound_name']));
	$sound_author = stripslashes(htmlspecialchars($music_info['sound_author']));
	$sound_genre = stripslashes(htmlspecialchars($music_info['sound_genre']));
	$sound_id = $music_info['sound_id'];
	$sound_file = $music_info['sound_file'];
	
dohtml_form_header("music","update",1,1);
dohtml_table_header("edit","$msg_admin_menu_edit");
dohtml_form_hidden("sound_id",$sound_id);
dohtml_form_input($msg_admin_path,"sound_path",$sound_file);
dohtml_form_input($msg_admin_name,"sound_name",$sound_name);
dohtml_form_input($msg_admin_author,"sound_author",$sound_author);
dohtml_form_input($msg_admin_genre,"sound_genre",$sound_genre);
dohtml_form_footer($msg_admin_reg_edit);
dothml_pagefooter();
exit;
}

// SCREEN = ADD
if ($action == 'add')
{
dohtml_form_header("music","insert",0,1);
dohtml_table_header("add","$msg_admin_menu_add");
dohtml_form_input($msg_admin_path,"sound_path","");
dohtml_form_input($msg_admin_name,"sound_name","");
dohtml_form_input($msg_admin_author,"sound_author","");
dohtml_form_input($msg_admin_genre,"sound_genre","");
dohtml_form_infobox($msg_admin_help_musicpath);
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// SCREEN = UPLOAD
if($action == 'upload')
{
dohtml_form_header("music","do_upload",1,1);
dohtml_table_header("upload","$msg_admin_menu_add : $msg_admin_menu_upload");

dohtml_form_file($msg_admin_file,"sound_file",10000000);
dohtml_form_input($msg_admin_name,"sound_name","");
dohtml_form_input($msg_admin_author,"sound_author","");
dohtml_form_input($msg_admin_genre,"sound_genre","");
dohtml_form_infobox($msg_admin_help_safemode);
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// SCREEN = EDIT
if(empty($action))
{
dohtml_form_header("music","order_sound",0,1);
dohtml_table_header("edit","$msg_admin_menu_edit");
$html = "<table><tr><td>";
	$musiclist = $DB_site->query(" SELECT * FROM vcard_sound ORDER BY sound_order ");
	while ($music_info = $DB_site->fetch_array($musiclist))
	{
		$sound_id = $music_info['sound_id'];
		$sound_file = $music_info['sound_file'];
		$sound_order = $music_info['sound_order'];
		$sound_name = stripslashes(htmlspecialchars($music_info['sound_name']));
		$sound_author = stripslashes(htmlspecialchars($music_info['sound_author']));
		$sound_genre = stripslashes(htmlspecialchars($music_info['sound_genre']));
		// Display list of soundfile
		$html .= "<li class=\"".get_row_bg()."\">&nbsp; ($msg_admin_menu_order: <input type='text' name='order[$sound_id]' value='$sound_order' size='3'>) ". 
		cexpr(($superuser==1 || $vcuser[caneditmusic]==1),"<a href=\"music.php?action=edit&sound_id=$sound_id&s=$s\">[$msg_admin_menu_edit]</a>&nbsp;","") .
		cexpr($music_info['sound_active']==1,"<a href=\"music.php?action=deactive&sound_id=$sound_id&s=$s\">[$msg_admin_menu_deactive]</a>&nbsp;","<a href=\"music.php?action=active&sound_id=$sound_id&s=$s\">[$msg_admin_menu_active]</a>&nbsp;") .
		cexpr(($superuser==1 || $vcuser[candeletemusic]==1), "<a href=\"music.php?action=delete&sound_id=$sound_id&s=$s\">[$msg_admin_menu_delete]</a>&nbsp;","").
		"&nbsp;<a href=\"javascript:playmusic('$sound_file')\">[$msg_admin_play]</a>
		- $sound_genre - $sound_author - $sound_name </li>";
	}
	$DB_site->free_result($musiclist);
	dohtml_form_label($msg_admin_music,$html);
	dohtml_form_footer($msg_admin_reg_order);
	dothml_pagefooter();
	exit;
}

?>