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

// ############################# LOCAL FUNCTION ########################
function dohtmlform_radiooptions($optionname,$fieldname,$value="0") {
	global $msg_admin_yes,$msg_admin_no;
	
	echo "<tr><td><b>$optionname</b> </td><td>: <input type=\"radio\" name=\"$fieldname\" value=\"1\"".cexpr($value==1," checked","").">$msg_admin_yes 
	<input type=\"radio\" name=\"$fieldname\" value=\"0\"".cexpr($value==0," checked","").">$msg_admin_no 
	</td></tr>";
}

// ############################# DB ACTION #############################
if ($action == 'account_delete')
{
	dohtml_form_header("users","account_delete_yes",0,1);
	dohtml_table_header("edit","$msg_admin_op_confirm_question",2);
	dohtml_form_hidden("id",$HTTP_GET_VARS['id']);
	$html .= "<b>$msg_admin_menu_delete</b>";
	dohtml_form_label($msg_admin_op_confirm_question,$html);
	dohtml_table_footer();
	dohtml_form_footer($msg_button_confirm);
	exit;
}

if ($action == 'account_delete_yes')
{
	$checkaccountnumber = $DB_site->count_records("vcard_account");
	if ($checkaccountnumber == 1)
	{
		dohtml_result(0,$msg_admin_user_error01);
		exit;
	}else{
		$result = $DB_site->query(" DELETE FROM vcard_account WHERE account_id='$HTTP_POST_VARS[id]' ");
		dohtml_result($result,$msg_admin_reg_delete);
	}
		$action = 'edit';
}

if ($action == 'account_update')
{
	$check = $DB_site->query_first(" SELECT * FROM vcard_account WHERE account_username='".addslashes($HTTP_POST_VARS['account_username'])."' ");
//		account_username='".addslashes($HTTP_POST_VARS['account_username'])."',
	$result = $DB_site->query("
	UPDATE vcard_account
	SET
		account_password='".addslashes($HTTP_POST_VARS['account_password'])."',
		superuser = '".addslashes($HTTP_POST_VARS['superuser'])."',
		canworkcategory = '".addslashes($HTTP_POST_VARS['canworkcategory'])."',
		canaddcard = '".addslashes($HTTP_POST_VARS['canaddcard'])."',
		caneditcard = '".addslashes($HTTP_POST_VARS['caneditcard'])."',
		candeletecard = '".addslashes($HTTP_POST_VARS['candeletecard'])."',
		canaddmusic = '".addslashes($HTTP_POST_VARS['canaddmusic'])."',
		caneditmusic = '".addslashes($HTTP_POST_VARS['caneditmusic'])."',
		candeletemusic = '".addslashes($HTTP_POST_VARS['candeletemusic'])."',
		canaddpattern = '".addslashes($HTTP_POST_VARS['canaddpattern'])."',
		caneditpattern = '".addslashes($HTTP_POST_VARS['caneditpattern'])."',
		candeletepattern = '".addslashes($HTTP_POST_VARS['candeletepattern'])."',
		canaddpoem = '".addslashes($HTTP_POST_VARS['canaddpoem'])."',
		caneditpoem = '".addslashes($HTTP_POST_VARS['caneditpoem'])."',
		candeletepoem = '".addslashes($HTTP_POST_VARS['candeletepoem'])."',
		canaddevent = '".addslashes($HTTP_POST_VARS['canaddevent'])."',
		caneditevent = '".addslashes($HTTP_POST_VARS['caneditevent'])."',
		candeleteevent = '".addslashes($HTTP_POST_VARS['candeleteevent'])."',
		canaddstamp = '".addslashes($HTTP_POST_VARS['canaddstamp'])."',
		caneditstamp = '".addslashes($HTTP_POST_VARS['caneditstamp'])."',
		candeletestamp = '".addslashes($HTTP_POST_VARS['candeletestamp'])."',
		canaddreplace = '".addslashes($HTTP_POST_VARS['canaddreplace'])."',
		caneditreplace = '".addslashes($HTTP_POST_VARS['caneditreplace'])."',
		canviewcontrol = '".addslashes($HTTP_POST_VARS['canviewcontrol'])."',
		canviewoptions = '".addslashes($HTTP_POST_VARS['canviewoptions'])."',
		canedittemplate = '".addslashes($HTTP_POST_VARS['canedittemplate'])."',
		canviewsearch = '".addslashes($HTTP_POST_VARS['canviewsearch'])."',
		canviewstyle = '".addslashes($HTTP_POST_VARS['canviewstyle'])."',
		canviewstats = '".addslashes($HTTP_POST_VARS['canviewstats'])."',
		canviewemailog = '".addslashes($HTTP_POST_VARS['canviewemailog'])."',
		canviewphpinfo = '".addslashes($HTTP_POST_VARS['canviewphpinfo'])."' 
	WHERE account_id='".addslashes($HTTP_POST_VARS['account_id'])."'
	");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = "edit";
}

if ($action == 'account_add')
{
	checkfieldempty($HTTP_POST_VARS['account_username'],"$msg_admin_title $msg_admin_error_formempty");
	checkfieldempty($HTTP_POST_VARS['account_password'],"$msg_admin_text $msg_admin_error_formempty");
	// Check if exist

	$check = $DB_site->query_first(" SELECT * FROM vcard_account WHERE account_username='".addslashes($HTTP_POST_VARS['account_username'])."' ");
	if( !empty($check['account_username']) )
	{
		dohtml_errorpage(0,$msg_admin_user_alreayusername);
		exit;
	}

	$query = ("
		INSERT INTO vcard_account ( account_id, account_username, account_password, superuser, canworkcategory, canaddcard, caneditcard, candeletecard, canaddmusic, caneditmusic, candeletemusic, canaddpattern, caneditpattern, candeletepattern, canaddpoem, caneditpoem, candeletepoem, canaddevent, caneditevent, candeleteevent, canaddstamp, caneditstamp, candeletestamp, canaddreplace, caneditreplace, canviewcontrol, canviewoptions, canedittemplate, canviewsearch, canviewstyle, canviewstats, canviewemailog, canviewphpinfo)
		VALUES 
		( NULL, 
		'".addslashes($HTTP_POST_VARS['account_username'])."',
		'".addslashes($HTTP_POST_VARS['account_password'])."',
		'$HTTP_POST_VARS[superuser]',
		'$HTTP_POST_VARS[canworkcategory]',
		'$HTTP_POST_VARS[canaddcard]',
		'$HTTP_POST_VARS[caneditcard]',
		'$HTTP_POST_VARS[candeletecard]',
		'$HTTP_POST_VARS[canaddmusic]',
		'$HTTP_POST_VARS[caneditmusic]',
		'$HTTP_POST_VARS[candeletemusic]',
		'$HTTP_POST_VARS[canaddpattern]',
		'$HTTP_POST_VARS[caneditpattern]',
		'$HTTP_POST_VARS[candeletepattern]',
		'$HTTP_POST_VARS[canaddpoem]',
		'$HTTP_POST_VARS[caneditpoem]',
		'$HTTP_POST_VARS[candeletepoem]',
		'$HTTP_POST_VARS[canaddevent]',
		'$HTTP_POST_VARS[caneditevent]',
		'$HTTP_POST_VARS[candeleteevent]',
		'$HTTP_POST_VARS[canaddstamp]',
		'$HTTP_POST_VARS[caneditstamp]',
		'$HTTP_POST_VARS[candeletestamp]',
		'$HTTP_POST_VARS[canaddreplace]',
		'$HTTP_POST_VARS[caneditreplace]',
		'$HTTP_POST_VARS[canviewcontrol]',
		'$HTTP_POST_VARS[canviewoptions]',
		'$HTTP_POST_VARS[canedittemplate]',
		'$HTTP_POST_VARS[canviewsearch]',
		'$HTTP_POST_VARS[canviewstyle]',
		'$HTTP_POST_VARS[canviewstats]',
		'$HTTP_POST_VARS[canviewemailog]',
		'$HTTP_POST_VARS[canviewphpinfo]')
		");
	$result = $DB_site->query($query);
	dohtml_result($result,"$msg_admin_reg_add");
	$action = "edit";
}


// ############################# ACTION SCREENS #############################
// SCREEN = EDIT
if ($action == 'account_edit')
{
	$userinfo = $DB_site->query_first(" SELECT * FROM vcard_account WHERE account_id='$HTTP_GET_VARS[id]' ");
	//$userinfo = $DB_site->fetch_array($result);
	// $temp_catlist = categoryoption($userinfo[canworkcategory],$userinfo[canworkcategory]);

	extract($userinfo);
dohtml_form_header("users","account_update",0,0);
dohtml_table_header("edit","$msg_admin_menu_edit");
dohtml_form_hidden("account_id",$account_id);
//dohtml_form_input($msg_admin_username,"account_username",$account_username);
dohtml_form_fixedfield($msg_admin_username,"account_username",$account_username);
dohtml_form_input($msg_admin_password,"account_password",$account_password);
dohtml_form_yesno($msg_admin_superuser,"superuser",$superuser);
dohtml_form_cardcategory(0,"canworkcategory",$userinfo['canworkcategory'],"<option value=0> $msg_admin_all $msg_admin_category </option>");
dohtml_form_yesno("$msg_admin_postcard $msg_admin_menu_add","canaddcard",$canaddcard);
dohtml_form_yesno("$msg_admin_postcard $msg_admin_menu_edit","caneditcard",$caneditcard);
dohtml_form_yesno("$msg_admin_postcard $msg_admin_menu_delete","candeletecard",$candeletecard);

dohtml_form_yesno("$msg_admin_event $msg_admin_menu_add","canaddevent",$canaddevent);
dohtml_form_yesno("$msg_admin_event $msg_admin_menu_edit","caneditevent",$caneditevent);
dohtml_form_yesno("$msg_admin_event $msg_admin_menu_delete","candeleteevent",$candeleteevent);

dohtml_form_yesno("$msg_admin_poem $msg_admin_menu_add","canaddpoem",$canaddpoem);
dohtml_form_yesno("$msg_admin_poem $msg_admin_menu_edit","caneditpoem",$caneditpoem);
dohtml_form_yesno("$msg_admin_poem $msg_admin_menu_delete","candeletepoem",$candeletepoem);

dohtml_form_yesno("$msg_admin_music $msg_admin_menu_add","canaddmusic",$canaddmusic);
dohtml_form_yesno("$msg_admin_music $msg_admin_menu_edit","caneditmusic",$caneditmusic);
dohtml_form_yesno("$msg_admin_music $msg_admin_menu_delete","candeletemusic",$candeletemusic);

dohtml_form_yesno("$msg_admin_stamp $msg_admin_menu_add","canaddstamp",$canaddstamp);
dohtml_form_yesno("$msg_admin_stamp $msg_admin_menu_edit","caneditstamp",$caneditstamp);
dohtml_form_yesno("$msg_admin_stamp $msg_admin_menu_delete","candeletestamp",$candeletestamp);

dohtml_form_yesno("$msg_admin_pattern $msg_admin_menu_add","canaddpattern",$canaddpattern);
dohtml_form_yesno("$msg_admin_pattern $msg_admin_menu_edit","caneditpattern",$caneditpattern);
dohtml_form_yesno("$msg_admin_pattern $msg_admin_menu_delete","candeletepattern",$candeletepattern);

dohtml_form_footer($msg_admin_reg_edit);
dothml_pagefooter();
exit;
}

// SCREEN = ADD
if ($action == 'add')
{
dohtml_form_header("users","account_add",0,1);
dohtml_table_header("add","$msg_admin_menu_add");
$temp_catlist = categoryoption(0,0,"<option value=\"0\" selected> $msg_admin_all $msg_admin_category</option>\n");
//dohtml_form_input("$msg_admin_username","account_username");
//dohtml_form_input("$msg_admin_password","account_password");
//dohtml_form_yesno($msg_admin_superuser,"superuser");
//dohtml_form_select2($msg_admin_category,"cat_name","canworkcategory","vcard_category","cat_id","cat_name");

dohtml_form_input($msg_admin_username,"account_username","");
dohtml_form_input($msg_admin_password,"account_password","");
dohtml_form_yesno($msg_admin_superuser,"superuser","");
dohtml_form_cardcategory(0,"canworkcategory","","<option value=0> $msg_admin_all $msg_admin_category </option>");
dohtml_form_yesno("$msg_admin_postcard $msg_admin_menu_add","canaddcard","");
dohtml_form_yesno("$msg_admin_postcard $msg_admin_menu_edit","caneditcard","");
dohtml_form_yesno("$msg_admin_postcard $msg_admin_menu_delete","candeletecard","");

dohtml_form_yesno("$msg_admin_event $msg_admin_menu_add","canaddevent","");
dohtml_form_yesno("$msg_admin_event $msg_admin_menu_edit","caneditevent","");
dohtml_form_yesno("$msg_admin_event $msg_admin_menu_delete","candeleteevent","");

dohtml_form_yesno("$msg_admin_poem $msg_admin_menu_add","canaddpoem","");
dohtml_form_yesno("$msg_admin_poem $msg_admin_menu_edit","caneditpoem","");
dohtml_form_yesno("$msg_admin_poem $msg_admin_menu_delete","candeletepoem","");

dohtml_form_yesno("$msg_admin_music $msg_admin_menu_add","canaddmusic","");
dohtml_form_yesno("$msg_admin_music $msg_admin_menu_edit","caneditmusic","");
dohtml_form_yesno("$msg_admin_music $msg_admin_menu_delete","candeletemusic","");

dohtml_form_yesno("$msg_admin_stamp $msg_admin_menu_add","canaddstamp","");
dohtml_form_yesno("$msg_admin_stamp $msg_admin_menu_edit","caneditstamp","");
dohtml_form_yesno("$msg_admin_stamp $msg_admin_menu_delete","candeletestamp","");

dohtml_form_yesno("$msg_admin_pattern $msg_admin_menu_add","canaddpattern","");
dohtml_form_yesno("$msg_admin_pattern $msg_admin_menu_edit","caneditpattern","");
dohtml_form_yesno("$msg_admin_pattern $msg_admin_menu_delete","candeletepattern","");
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// SCREEN = EDIT
if ($action == 'edit')
{
dohtml_table_header("delete","$msg_admin_menu_delete",2);
	$html .= "<table><tr><td>\n";
	$accountlist = $DB_site->query(" SELECT * FROM vcard_account ORDER BY account_username ");
	while ($account = $DB_site->fetch_array($accountlist))
	{
		extract($account);
		$html .= "<li class=\"".get_row_bg()."\">$account_username - 
		<a href=\"users.php?action=account_edit&id=$account_id&s=$s\">[$msg_admin_menu_edit]</a>
		<a href=\"users.php?action=account_delete&id=$account_id&s=$s\">[$msg_admin_menu_delete]</a>
		</li>";
	}
	$DB_site->free_result($accountlist);
	$html .= "</td></tr></table>";
dohtml_form_label($msg_admin_users,$html);
dohtml_table_footer();
dothml_pagefooter();
exit;
}

?>