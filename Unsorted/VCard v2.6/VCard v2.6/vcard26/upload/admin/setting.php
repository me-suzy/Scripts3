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

$fieldsize = optionbynavigator("55","30");

// ############################# DB ACTION #############################
if ($action == 'saveoptions')
{
	while (list($key,$val) = each($HTTP_POST_VARS['setting']))
	{
		$DB_site->query("UPDATE vcard_setting SET value='".addslashes($val)."' WHERE setting_id='$key'");
	}
	$settings = $DB_site->query("SELECT varname,value FROM vcard_setting");
	while ($setting = $DB_site->fetch_array($settings))
	{
		$optionstemplate.="\$$setting[varname] = \"".addslashes(str_replace("\"","\\\"",$setting[value]))."\";\n";
	}
	$result = $DB_site->query("UPDATE vcard_template SET template='$optionstemplate' WHERE title='options'");
	dohtml_result($result,"option template updated");
	$action = '';
}

if ($action == 'set_insert')
{
	$query[1] = "INSERT INTO vcard_setting (setting_id,settinggroup_id,varname,value,optioncode,displayorder) VALUES (NULL,'".addslashes($HTTP_POST_VARS[settinggroup_id])."','".addslashes($HTTP_POST_VARS['varname'])."','".addslashes($HTTP_POST_VARS['value'])."','".addslashes($HTTP_POST_VARS['optioncode'])."','".addslashes($HTTP_POST_VARS['displayorder'])."')";
	$result = $DB_site->query($query[1]);
	dohtml_result($result,"setting inserted");
	$action = '';

}
if ($action == 'set_update')
{
	$query[1] = "UPDATE vcard_setting SET settinggroup_id='".addslashes($HTTP_POST_VARS['settinggroup_id'])."', varname='".addslashes($HTTP_POST_VARS['varname'])."',value='".addslashes($HTTP_POST_VARS['value'])."', optioncode='".addslashes($HTTP_POST_VARS['optioncode'])."',displayorder='".addslashes($HTTP_POST_VARS['displayorder'])."' WHERE setting_id='".addslashes($HTTP_POST_VARS['setting_id'])."' ";
	$result = $DB_site->query($query[1]);
	dohtml_result($result,"setting updated");
	$action = '';
}
if($action == 'set_delete')
{
	$query[1] = "DELETE FROM vcard_setting WHERE setting_id='$HTTP_GET_VARS[setid]' ";
	$result = $DB_site->query($query[1]);
	dohtml_result($result,"setting deleted");
	$action = '';
}


if ($action == 'group_update')
{
	$query[1] = "UPDATE vcard_settinggroup SET varname='".addslashes($HTTP_POST_VARS['varname'])."', displayorder='$HTTP_POST_VARS[displayorder]' WHERE settinggroup_id='$HTTP_POST_VARS[settinggroup_id]' ";
	$result = $DB_site->query($query[1]);
	dohtml_result($result,"Group updated");
	$action = '';
}
if ($action == 'group_insert')
{
	$query[1] = "INSERT INTO vcard_settinggroup (settinggroup_id,varname,displayorder) VALUES (NULL,'".addslashes($HTTP_POST_VARS['varname'])."','$HTTP_POST_VARS[displayorder]')";
	$result = $DB_site->query($query[1]);
	dohtml_result($result,"group added");
	$action = '';
}
if ($action == 'group_delete')
{
	$query[1] = "DELETE FROM vcard_settinggroup WHERE settinggroup_id='$HTTP_GET_VARS[groupid]' ";
	$result = $DB_site->query($query[1]);
	dohtml_result($result,"group deleted");
	$action = '';
}

if ($action == 'todb')
{
	$result = $DB_site->query("SELECT * FROM vcard_setting  LIMIT 0, 80");
	while ($results = $DB_site->fetch_array($result))
	{
		$html .= "$"."result = $"."DB_site->query(\"INSERT INTO $"."table_setting VALUES ( '$results[setting_id]', '$results[settinggroup_id]', '$results[varname]', '" .addslashes($results['value']) . "', '$results[optioncode]', '$results[displayorder]')\");\r";
	}
	echo "<p><pre>";
	echo '<textarea cols="120" rows="30">'.$html.'</textarea>';
	echo '</pre>';
	$action = '';
	
}
// ############################# ACTION SCREENS #############################
// SCREEN = DEFAULT
if (is_array($query))
{
	echo "<blockquote><b>Queries Executed:</b> <font size='1'>(useful for copy/pasting into upgrade scripts)</font><br><textarea rows='10' cols='100' style='color:red'>\n";
 	while (list($queryindex,$querytext)=each($query))
	{
		echo "\$DB_site->query(\"".htmlspecialchars($querytext)."\");\n";
	}
	echo "</textarea></blockquote>\n";
}
if (empty($action))
{
	dohtml_form_header("options","debug_saveoptions",0,0);
	echo "<p>$msg_admin_menu_options:</p><ul>\n";
	$settinggroups = $DB_site->query("SELECT * FROM vcard_settinggroup WHERE displayorder<>0 ORDER BY displayorder");
	while ($settinggroup = $DB_site->fetch_array($settinggroups))
	{
		echo "<a href='#settinggroup$settinggroup[settinggroup_id]'>[ ".$settinggroup_title[$settinggroup['varname']] ." ]</a><br>\n";
	}
	echo "</ul>\n";
	$DB_site->data_seek(0,$settinggroups);
	while ($settinggroup = $DB_site->fetch_array($settinggroups))
	{
		echo "<ul><b>".$settinggroup_title[$settinggroup['varname']] ." <a name=\"settinggroup$settinggroup[settinggroup_id]\"></a></b>  - 
		<a href=\"setting.php?s=$s&action=group_edit&groupid=$settinggroup[settinggroup_id]\">[edit]</a>
		<a href=\"setting.php?s=$s&action=group_delete&groupid=$settinggroup[settinggroup_id]\">[remove]</a>
		<a href=\"setting.php?s=$s&action=set_add&groupid=$settinggroup[settinggroup_id]\">[add setting]</a><ul>";
		$settings = $DB_site->query("SELECT * FROM vcard_setting WHERE settinggroup_id=$settinggroup[settinggroup_id] ORDER BY displayorder");
		while ($setting = $DB_site->fetch_array($settings))
		{
			echo "<li>".$settinggroup_title[$setting['varname']] ." ($setting[varname]) - 
			<a href=\"setting.php?s=$s&action=set_edit&setid=$setting[setting_id]&varname=$setting[varname]\">[edit]</a>
			<a href=\"setting.php?s=$s&action=set_delete&setid=$setting[setting_id]&varname=$setting[varname]\">[remove]</a>
			";
		}
		echo "</ul></ul>";
	}
	echo "<a href='setting.php?action=todb&s=$s'>to database</a>";
	dohtml_form_footer($msg_admin_reg_update);
	dothml_pagefooter();
	exit;
}
if ($action == 'set_edit')
{
	$settinginfo = $DB_site->query_first(" SELECT * FROM vcard_setting WHERE setting_id='$HTTP_GET_VARS[setid]' ");

	dohtml_form_header("setting","set_update",0,2);
	dohtml_form_hidden("setting_id",$setid);
	dohtml_table_header("edit","edit",2);
	dohtml_form_select2("Setting Category","varname","settinggroup_id","vcard_settinggroup","settinggroup_id","varname",$settinginfo['settinggroup_id']);
	dohtml_form_input("varname","varname",$settinginfo['varname']);
	dohtml_form_textarea("value","value",$settinginfo['value'],10,80);
	dohtml_form_textarea("optioncode","optioncode",$settinginfo['optioncode'],10,80);
	dohtml_form_input("displayorder","displayorder",$settinginfo['displayorder']);
	dohtml_form_footer("update");
	dohtml_table_footer();
	dothml_pagefooter();
exit;
}
if ($action == 'set_add')
{
	dohtml_form_header("setting","set_insert",0,2);
	dohtml_table_header("edit","add new setting",2);
	dohtml_form_select2("Setting Category","varname","settinggroup_id","vcard_settinggroup","settinggroup_id","varname",$HTTP_GET_VARS['groupid']);
	dohtml_form_input("varname","varname","");
	dohtml_form_textarea("value","value","",10,80);
	dohtml_form_textarea("optioncode","optioncode","",10,80);
	dohtml_form_input("displayorder","displayorder","");
	dohtml_form_footer("add setting");
	dohtml_table_footer();
	dothml_pagefooter();
exit;
}

if ($action == 'group_edit')
{
	$groupinfo = $DB_site->query_first(" SELECT * FROM vcard_settinggroup WHERE settinggroup_id=$HTTP_GET_VARS[groupid] ");

	dohtml_form_header("setting","group_update",0,2);
	dohtml_form_hidden("settinggroup_id",$groupinfo['settinggroup_id']);
	dohtml_table_header("edit","edit setting",2);
	dohtml_form_input("varname","varname",$groupinfo['varname']);
	dohtml_form_input("displayorder","displayorder",$groupinfo['displayorder']);
	dohtml_form_footer("update");
	dohtml_table_footer();
	dothml_pagefooter();
exit;
}
if ($action == 'group_add')
{
	dohtml_form_header("setting","group_insert",0,2);
	dohtml_form_hidden("settinggroup_id",$groupinfo['settinggroup_id']);
	dohtml_table_header("edit","add new group",2);
	dohtml_form_input("varname","varname","");
	dohtml_form_input("displayorder","displayorder","");
	dohtml_form_footer("add group");
	dohtml_table_footer();
	dothml_pagefooter();
exit;
}

?>
