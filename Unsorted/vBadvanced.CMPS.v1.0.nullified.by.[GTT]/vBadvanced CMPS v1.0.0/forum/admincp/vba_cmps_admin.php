<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++

error_reporting(E_ALL & ~E_NOTICE);
define('NO_REGISTER_GLOBALS', 1);

$specialtemplates = array('adv_portal_opts');

$phrasegroups = array('adv_portal_cp');

require_once('./global.php');

print_cp_header($vbphrase['vbadvanced_cmps']);

if (empty($_REQUEST['do']))
{
  $_REQUEST['do'] = 'options';
}

// ######################### Update Module Cache ###########################
function update_module_cache()
{
	global $DB_site;

	$mods = $DB_site->query("SELECT * FROM " . TABLE_PREFIX . "adv_modules WHERE active = '1' ORDER BY modcol, displayorder");
	while ($mod = $DB_site->fetch_array($mods))
	{
		$module[] = $mod;
	}
	build_datastore('adv_modules', serialize($module));
}

// ######################### Build Settings ###########################
function build_adv_settings()
{
	global $DB_site;

	$settings = $DB_site->query("SELECT varname, value FROM " . TABLE_PREFIX . "adv_setting WHERE varname LIKE 'portal%'");
	while ($setting = $DB_site->fetch_array($settings))
	{
		$vba_options[$setting['varname']] = $setting['value'];
	}
	$DB_site->free_result($settings);
	build_datastore('adv_portal_opts', serialize($vba_options));
}

// ######################### Print Module Columns ###########################
function print_column_row($colarray, $coltitle, $type = 'module')
{
	global $page, $_REQUEST, $vbphrase;

	echo '<td width="33%" valign="top">';
	print_table_start(false, '100%');

	print_description_row($coltitle, '', 2, 'thead');

	if ($type != 'page')
	{
		$cell = array();
		$cell[] = $vbphrase['module_name'];
		$cell[] = $vbphrase['order'];
		print_cells_row($cell, 1);
	}

	
	if (!empty($colarray))
	{
		foreach ($colarray AS $key => $column)
		{
			if ($type == 'page')
			{
				if ($_REQUEST['do'] == 'addpage')
				{
					$checked = 'checked="checked"';
				}
				if ($page['modules'])
				{
					$checked = '';
					if (in_array($column['modid'], explode(',', $page['modules'])))
					{
						$checked = 'checked="checked"';
					}
				}
					
				echo '<tr><td class="alt1"><label for="' . $column['modid'] . '"><input ' . $checked . iif(!$column['active'], 'disabled="disabled"') . 'id="' . $column['modid'] . '" name="enablemod[]" type="checkbox" value="' . $column['modid'] . '" /> <span class="smallfont">' . $column['title'] . '</span></label></td></tr>';
			}
			else
			{
				print_input_row('<span class="smallfont"><strong><a href="vba_cmps_admin.php?do=editmodule&amp;modid=' . $column['modid'] . '">' . $column['title'] . '</a></strong></span>' . iif(!$column['active'], ' <div style="color:red;font-size:9px;font-weight:normal;">(' . $vbphrase['inactive'] . ')</div>') . '<div class="smallfont" style="margin-top:5px">' . $vbphrase['move_to'] . ': ' . 
				iif($column['modcol'] == 0, ' [<a href="vba_cmps_admin.php?do=movemodule&amp;modid=' . $column['modid'] . '&amp;col=2" title="' . $vbphrase['right_column'] . '">-&raquo;</a>] [<a href="vba_cmps_admin.php?do=movemodule&amp;modid=' . $column['modid'] . '&amp;col=1" title="' . $vbphrase['center_column'] . '"">-&rsaquo;</a>]') . 
				iif($column['modcol'] == 1, ' [<a href="vba_cmps_admin.php?do=movemodule&amp;modid=' . $column['modid'] . '&amp;col=0" title="' . $vbphrase['left_column'] . '">&lsaquo;-</a>] [<a href="vba_cmps_admin.php?do=movemodule&amp;modid=' . $column['modid'] . '&amp;col=2" title="' . $vbphrase['right_column'] . '">-&rsaquo;</a>]') . 
				iif($column['modcol'] == 2, ' [<a href="vba_cmps_admin.php?do=movemodule&amp;modid=' . $column['modid'] . '&amp;col=1" title="' . $vbphrase['center_column'] . '">&lsaquo;-</a>] [<a href="vba_cmps_admin.php?do=movemodule&amp;modid=' . $column['modid'] . '&amp;col=0" title="' . $vbphrase['left_column'] . '">&laquo;-</a>]')
		
		, 'modorder[' . $column['modid'] . ']', $column['displayorder'], 1, 1, '', '', iif(!$column['active'], 'bginput" disabled="disabled"', ''));
			}
		}
		unset($column);
	}
	else
	{
		print_label_row($vbphrase['no_modules']);
	}
	print_table_footer(true, '', '', false);
	echo '</td>';
}		

// ######################### Construct Settings ###########################
function construct_adv_page_settings()
{
	global $DB_site, $setting, $usedefault;
	$defsettings = $DB_site->query("
			SELECT *
			FROM " . TABLE_PREFIX . "adv_setting AS adv_setting
			WHERE grouptitle LIKE 'adv_portal%'
	");

	while ($defsetting = $DB_site->fetch_array($defsettings))
	{
		if (!empty($setting))
		{
			foreach ($setting AS $varname => $value)
			{
				if ($varname == $defsetting['varname'] AND $value != $defsetting['value'] AND !$usedefault[$varname])
				{
					$adv_setting[$varname] = $value;
				}
			}
		}
	}
	$DB_site->free_result($defsettings);
	unset($defsetting, $setting);

	if (!empty($adv_setting))
	{
		$adv_setting = serialize($adv_setting);
	}
	else
	{
		$adv_setting = '';
	}
	return $adv_setting;
}

// ######################### Print Settings ###########################
function print_adv_setting_group($grouptitle, $revert = 1)
{
	global $settingscache, $grouptitlecache, $debug, $session, $vbphrase, $settingphrase, $stylevar;

	print_table_header(
		$settingphrase["settinggroup_$grouptitlecache[$grouptitle]"]
		 . iif($debug AND !$revert,
		 	'<span class="normal">' .
			construct_link_code($vbphrase['edit'], "vba_cmps_admin.php?$session[sessionurl]do=editgroup&amp;grouptitle=$grouptitle") .
			construct_link_code($vbphrase['delete'], "vba_cmps_admin.php?$session[sessionurl]do=removegroup&amp;grouptitle=$grouptitle") .
			construct_link_code($vbphrase['add_setting'], "vba_cmps_admin.php?$session[sessionurl]do=addsetting&amp;grouptitle=$grouptitle") .
			'</span>'
		)
	);

	foreach ($settingscache[$grouptitle] AS $settingid => $setting)
	{
		if ($setting['varname'] == 'portal_pagevar' AND $revert OR ($setting['varname'] == 'portal_version'))
		{
			continue;
		}

		print_description_row(
			'<div class="smallfont" style="float:' . $stylevar['right'] . '">' . 
			iif($debug AND !$revert, construct_link_code($vbphrase['edit'], "vba_cmps_admin.php?$session[sessionurl]do=editsetting&varname=$setting[varname]") . construct_link_code($vbphrase['delete'], "vba_cmps_admin.php?$session[sessionurl]do=removesetting&varname=$setting[varname]")) . ' ' . iif($revert, '&nbsp; &nbsp; ' . $vbphrase['use_default'] . ' <input ' . iif($setting['default'], 'checked="checked"') . ' type="checkbox" name="usedefault[' . $setting['varname'] . ']" value="1" />') . '</div>
			<div>' . $settingphrase["setting_$setting[varname]_title"] . '<a name="' . $setting['varname'] . '"></a></div>',
			0, 2, 'optiontitle" title="$vba_options[' . $setting['varname'] . ']"'
		);

		$description = '<div class="smallfont" title="' . $vboptions[$setting['varname']]. '">' . $settingphrase["setting_$setting[varname]_desc"] . '</div>';
		$name = "setting[$setting[varname]]";

		switch ($setting['optioncode'])
		{
			case '':
				print_input_row($description, $name, $setting['value'], 1, 40, '', '', iif($revert, 'bginput" onfocus="js_check_default(\'' . $setting['varname'] . '\');'));
				break;

			case 'textarea':
				print_textarea_row($description, $name, $setting['value'], 8, 40, '', '', '', iif($revert, 'bginput" onfocus="js_check_default(\'' . $setting['varname'] . '\');'));
				break;

			case 'yesno':
				print_yes_no_row($description, $name, $setting['value'], iif($revert, 'js_check_default(\'' . $setting['varname'] . '\');'));
				break;

			default:
				eval("\$right = \"$setting[optioncode]\";");
				print_label_row($description, $right, '', 'top', $name);
		}
	}
}

$vba_options = unserialize($datastore['adv_portal_opts']);

// ######################## Modify Settings ###########################
if ($_REQUEST['do'] == 'modifysettings')
{

	if ($debug == 1)
	{
		echo '<div align="center" class="smallfont">[<a href="vba_cmps_admin.php?do=addgroup">Add Setting Group</a>]</div>';
	}
	$getphrases = $DB_site->query("SELECT varname, text FROM " . TABLE_PREFIX . "phrase ORDER BY languageid ASC");
	while($phrases = $DB_site->fetch_array($getphrases))
	{
		$settingphrase[$phrases['varname']] = $phrases['text'];
	}

	require_once('./includes/adminfunctions_options.php');

	$settings = $DB_site->query("
			SELECT adv_setting.*
			FROM " . TABLE_PREFIX . "adv_setting AS adv_setting
			LEFT JOIN " . TABLE_PREFIX . "adv_settinggroup AS adv_settinggroup USING (grouptitle)
			WHERE adv_settinggroup.grouptitle LIKE 'adv_portal%'
			ORDER BY adv_settinggroup.displayorder, adv_setting.displayorder
	");

	while ($setting = $DB_site->fetch_array($settings))
	{
		$settingscache[$setting['grouptitle']][$setting['varname']] = $setting;
		$grouptitlecache[$setting['grouptitle']] = $setting['grouptitle'];
	}

	$DB_site->free_result($settings);
	print_form_header('vba_cmps_admin', 'saveopts');

	foreach ($grouptitlecache AS $grouptitle)
	{
		print_adv_setting_group($grouptitle, 0);
		print_table_break();
	}
	print_submit_row();
}

// ####################### Save Settings ############################
if ($_POST['do'] == 'saveopts')
{
	globalize($_POST, array('setting'));

  foreach ($setting AS $varname => $value)
  {
		$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_setting SET value = '$value' WHERE varname = '$varname'");
	}



	build_adv_settings();

	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=modifysettings", 0);
}

// ######################## Add / Edit Setting Group ###########################
if (in_array($_REQUEST['do'], array('addgroup', 'editgroup')))
{
	$group = $DB_site->query_first("SELECT * FROM " . TABLE_PREFIX . "adv_settinggroup WHERE grouptitle = '$_REQUEST[grouptitle]'");
	$groupphrase = $DB_site->query_first("SELECT text FROM " . TABLE_PREFIX . "phrase WHERE varname = 'settinggroup_" . $group['grouptitle'] . "'");

	print_form_header('vba_cmps_admin', 'do' . $_REQUEST['do']);
	print_table_header($vbphrase['add'] . ' ' . $vbphrase['setting_group']);
	construct_hidden_code('grouptitle', $group['grouptitle']);
	
	if ($_REQUEST['do'] == 'addgroup')
	{
		print_input_row($vbphrase['varname'] . ':', 'varname', 'adv_portal_');
	}
	else
	{
		print_label_row($vbphrase['varname'] . ':', '<b>' . $group['grouptitle'] . '</b>');
	}
	print_input_row($vbphrase['title'] . ':', 'title', $groupphrase['text']);
	print_input_row($vbphrase['display_order'] . ':', 'displayorder', $group['displayorder']);
	print_submit_row();
}


// ######################## Do Add / Edit Setting Group ###########################
if (in_array($_POST['do'], array('doaddgroup', 'doeditgroup')))
{
	if ($_POST['do'] == 'doeditgroup')
	{
		$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_settinggroup SET displayorder = '$_POST[displayorder]' WHERE grouptitle = '$_POST[grouptitle]'");
		$DB_site->query("UPDATE " . TABLE_PREFIX . "phrase SET text = '$_POST[title]' WHERE varname = 'settinggroup_" . $_POST['grouptitle'] . "'");
	}
	else
	{
		$DB_site->query("INSERT INTO " . TABLE_PREFIX . "adv_settinggroup (grouptitle, displayorder) VALUES ('$_POST[varname]', '$_POST[displayorder]')");
		$DB_site->query("INSERT INTO " . TABLE_PREFIX . "phrase (languageid, varname, text, phrasetypeid) VALUES (0, 'settinggroup_" . $_POST['varname'] . "', '$_POST[title]', 5000)");
	}
	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=modifysettings", 0);
}

// ######################### Remove Setting Group #######################
if ($_REQUEST['do'] == 'removegroup')
{

	print_form_header('vba_cmps_admin', 'doremovegroup');
	construct_hidden_code('grouptitle', $_REQUEST['grouptitle']);
	print_table_header($vbphrase['confirm_deletion']);
	print_description_row(construct_phrase($vbphrase['are_you_sure_you_want_to_delete_the_x_called_y'], $vbphrase['setting_group'], $_REQUEST['grouptitle'], $vbphrase['setting_group'], $_REQUEST['grouptitle'], ''));
	print_submit_row($vbphrase['yes'], '', 2, $vbphrase['no']);

}

// ######################## Do Remove Setting Group #########################
if ($_POST['do'] == 'doremovegroup')
{
	globalize($_POST, array('grouptitle' => STR));

	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "adv_settinggroup WHERE grouptitle = '$grouptitle'");
	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "adv_setting WHERE grouptitle = '$grouptitle'");
	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "phrase WHERE varname = 'settinggroup_" . $grouptitle . "'");

	build_adv_settings();

}
// ######################## Add / Edit Setting ###########################
if (in_array($_REQUEST['do'], array('addsetting', 'editsetting')))
{
	globalize($_REQUEST, array('varname' => STR));

	$getphrases = $DB_site->query("SELECT varname, text FROM " . TABLE_PREFIX . "phrase ORDER BY languageid ASC");
	while($phrases = $DB_site->fetch_array($getphrases))
	{
		$settingphrase[$phrases['varname']] = $phrases['text'];
	}

	$settinggroups = array();
	$groups = $DB_site->query("
			SELECT grouptitle 
			FROM " . TABLE_PREFIX . "adv_settinggroup 
			WHERE grouptitle LIKE 'adv_portal%' 
			ORDER BY displayorder
	");
	while ($group = $DB_site->fetch_array($groups))
	{
		$settinggroups[$group['grouptitle']] = $settingphrase["settinggroup_$group[grouptitle]"];
	}

	print_form_header('vba_cmps_admin', iif($_REQUEST['do'] == 'editsetting', 'doeditsetting', 'doaddsetting'));
	print_table_header(iif($_REQUEST['do'] == 'editsetting', $vbphrase['edit_setting'] . ': ' . $varname, $vbphrase['add_setting']));

	if ($_REQUEST['do'] == 'editsetting')
	{
		$setting = $DB_site->query_first("SELECT * FROM " . TABLE_PREFIX . "adv_setting WHERE varname = '$_REQUEST[varname]'");

		construct_hidden_code('setting[varname]', $setting['varname']);
		print_label_row($vbphrase['varname'], '<b>' . $setting['varname'] . '</b>');

		$phrases = $DB_site->query("SELECT varname, text FROM " . TABLE_PREFIX . "phrase WHERE varname IN ('setting_$setting[varname]_title', 'setting_$setting[varname]_desc') AND phrasetypeid = '5000'");
		while ($phrase = $DB_site->fetch_array($phrases))
		{
			if ($phrase['varname'] == "setting_$setting[varname]_title")
			{
				$setting['title'] = $phrase['text'];
			}
			else
			{
				$setting['description'] = $phrase['text'];
			}
		}

	}
	else
	{
		print_input_row($vbphrase['varname'] . ':', 'setting[varname]', $setting['varname']);
	}
	print_input_row($vbphrase['title'] . ':', 'setting[title]', $setting['title']);
	print_select_row($vbphrase['setting_group'], 'setting[grouptitle]', $settinggroups, iif($_REQUEST['do'] == 'addsetting', $_REQUEST['grouptitle'], $setting['grouptitle']));
	print_textarea_row($vbphrase['description'] . ':', 'setting[description]', $setting['description']);
	print_textarea_row($vbphrase['option_code'] . ':', 'setting[optioncode]', $setting['optioncode'], 4, 50);
	print_textarea_row($vbphrase['default'], 'setting[defaultvalue]', $setting['defaultvalue'], 4, 50);
	print_input_row($vbphrase['display_order'], 'setting[displayorder]', $setting['displayorder']);
	print_submit_row();
}

// ######################## Do Add Setting ###########################
if ($_POST['do'] == 'doaddsetting')
{
	globalize($_POST, array('setting'));

	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "phrase (languageid, varname, text, phrasetypeid) VALUES ('0', 'setting_" . $setting['varname'] . "_title', '" . addslashes(htmlspecialchars($setting['title'])) ."', '5000')");
	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "phrase (languageid, varname, text, phrasetypeid) VALUES ('0',  'setting_" . $setting['varname'] . "_desc', '" . addslashes($setting['description']) . "', '5000')");

	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "adv_setting (varname, grouptitle, value, defaultvalue, optioncode, displayorder) VALUES ('$setting[varname]', '$setting[grouptitle]', '$setting[defaultvalue]', '$setting[defaultvalue]', '" . addslashes($setting['optioncode']) . "', '$setting[displayorder]')");

	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=modifysettings", 0);

	build_adv_settings();

}	

// ######################## Do Edit Setting ###########################
if ($_POST['do'] == 'doeditsetting')
{
	globalize($_POST, array('setting'));

	$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_setting SET grouptitle = '$setting[grouptitle]', defaultvalue = '$setting[defaultvalue]', optioncode = '" . addslashes($setting['optioncode']) . "', displayorder = '$setting[displayorder]' WHERE varname = '$setting[varname]'");

	$DB_site->query("UPDATE " . TABLE_PREFIX . "phrase SET text = '" . addslashes($setting['description']) . "' WHERE varname = 'setting_" . $setting['varname'] . "_desc' AND phrasetypeid = '5000'");

	$DB_site->query("UPDATE " . TABLE_PREFIX . "phrase SET text = '" . addslashes($setting['title']) . "' WHERE varname = 'setting_" . $setting['varname'] . "_title' AND phrasetypeid = '5000'");

	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=modifysettings", 0);

}

// ########################## Remove Setting ###########################
if ($_REQUEST['do'] == 'removesetting')
{
	globalize($_REQUEST, array('varname' => STR));

	print_form_header('vba_cmps_admin', 'doremovesetting');
	construct_hidden_code('varname', $varname);
	print_table_header($vbphrase['confirm_deletion']);
	print_description_row(construct_phrase($vbphrase['are_you_sure_you_want_to_delete_the_x_called_y'], $vbphrase['setting'], $varname, $vbphrase['varname'], $varname, ''));
	print_submit_row($vbphrase['yes'], '', 2, $vbphrase['no']);
}


// ######################### Do Remove Setting ############################
if ($_POST['do'] == 'doremovesetting')
{
	globalize($_POST, array('varname' => STR));

	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "adv_setting WHERE varname = '$varname'");

	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "phrase WHERE varname = 'setting_" . $varname . "_desc'");

	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "phrase WHERE varname = 'setting_" . $varname . "_title'");

	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=modifysettings", 0);

	build_adv_settings();
}	

// ##################### List Modules #########################
if ($_REQUEST['do'] == 'listmodules')
{
	print_form_header('vba_cmps_admin', 'updatemodorder');
	print_table_header($vbphrase['edit_modules'], 3);


	$modules = $DB_site->query("SELECT * FROM " . TABLE_PREFIX . "adv_modules ORDER BY displayorder");
	while ($module = $DB_site->fetch_array($modules))
	{

		switch ($module['modcol'])
		{
			case 0:
				$leftcol[] = $module;
				break;
			case 2:
				$rightcol[] = $module;
				break;
			default:
				$centercol[] = $module;
		}
	}

	echo '<tr class="alt2">';

	print_column_row($leftcol, $vbphrase['left_column']);
	print_column_row($centercol, $vbphrase['center_column']);
	print_column_row($rightcol, $vbphrase['right_column']);

	print_table_break();

	print_submit_row($vbphrase['save_display_order'], $vbphrase['reset'], 3);
	print_table_footer();

}

// ##################### Update Modual Order #####################
if ($_POST['do'] == 'updatemodorder')
{
	globalize($_POST, array('modorder'));

  foreach ($modorder AS $key => $disorder)
  {
		$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_modules SET displayorder = '$disorder' WHERE modid = '$key'");
  }
	update_module_cache();

	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=listmodules", 0);
	
}

// ########################## Move Module ##########################
if ($_REQUEST['do'] == 'movemodule')
{
	globalize($_REQUEST, array('col' => INT, 'modid' => INT));

	$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_modules SET modcol = '$col' WHERE modid = '$modid'");

	update_module_cache();

	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=listmodules", 0);
}

// ######################## Add / Edit Module #########################
if (in_array($_REQUEST['do'], array('addmodule', 'editmodule')))
{
	globalize($_REQUEST, array('modid' => STR));


	if ($_REQUEST['do'] == 'editmodule')
	{
		$mod = $DB_site->query_first("SELECT * FROM " . TABLE_PREFIX . "adv_modules WHERE modid = '$modid'");
		$headertitle = $vbphrase['edit_module'] . ': ' . $mod['title'];
		
		if ($mod['identifier'] != 'custompage')
		{
			print_form_header('vba_cmps_admin', 'removemodule');
			construct_hidden_code('modid', $mod['modid']);
			print_table_header($vbphrase['remove_module']);
			print_description_row($vbphrase['to_remove_this_module']);
			print_submit_row($vbphrase['remove_module'], '');
		}
	}
	else
	{
		$headertitle = $vbphrase['add_module'];
	}

	print_form_header('vba_cmps_admin', 'do' . $_REQUEST['do']);
	construct_hidden_code('modid', $mod['modid']);
	print_table_header($headertitle);
	print_input_row($vbphrase['module_title'] . ':', 'title', $mod['title']);

	if ($mod['identifier'] != 'custompage')
	{
		print_input_row($vbphrase['module_identifier'] . ':<br /><span class="smallfont">' . $vbphrase['this_what_used_identify_module'], 'identifier', $mod['identifier']);
	
		if ($_REQUEST['do'] == 'addmodule')
		{
			$filenames['none'] = $vbphrase['choose_a_file'];
		}
		else
		{
			$filenames['none'] = $vbphrase['none'];
		}
		$filenames['dash'] = '--------------------';
	
		$directory = opendir('./modules'); 
		while ($modfile = readdir($directory))
		{ 
			if(ereg('[^.]+', $modfile))
			{ 
				$filenames[$modfile] = $modfile;
			}
		} 
	
		closedir($directory); 
	
		print_select_row($vbphrase['file_to_include'] . ':', 'filename', $filenames, $mod['filename']);
	
	
		print_input_row($vbphrase['or_template_to_include'] . ':<div class="smallfont">' . $vbphrase['template_note_prefixes'], 'templatename', iif($mod['inctype'] == 1, $mod['filename']), '', '' , '', '', 'bginput" " onclick="this.form[\'templatelist\'].disabled=true;"'
		);
	}
	else
	{
		construct_hidden_code('identifier', $mod['identifier']);
		construct_hidden_code('inctype', 2);
	}

	print_yes_no_row($vbphrase['active'] . ':', 'active', $mod['active']);
	print_select_row($vbphrase['column'] . ':', 'modcol', array(0 => $vbphrase['left_column'], 2 => $vbphrase['right_column'], 1 => $vbphrase['center_column']), $mod['modcol']);
	print_input_row($vbphrase['display_order'] . ':', 'displayorder', $mod['displayorder']);

	if ($mod['identifier'] != 'custompage')
	{
		print_textarea_row($vbphrase['templates_used'] . ':<div class="smallfont">' . $vbphrase['templates_used_note'] . '</div>', 'templatelist', $mod['templatelist'], 4, 40, 1, 0);
	}

	if ($_REQUEST['do'] == 'addmodule')
	{
		print_yes_no_row($vbphrase['update_all_pages'] . '<div class="smallfont">' . $vbphrase['update_all_pages_desc'] . '</div>', 'updateall', 1);
	}

	print_table_break();

	print_table_header($vbphrase['usergroups']);
	print_description_row($vbphrase['here_specify_which_usergroups_view_module'], '', 2, 'thead', 'center');
	
	$usergroups = $DB_site->query("SELECT usergroupid, title FROM " . TABLE_PREFIX . "usergroup ORDER BY usergroupid");
	while ($usergroup = $DB_site->fetch_array($usergroups))
	{
		$checked = 1;
		if (trim($mod['userperms']) AND !in_array($usergroup['usergroupid'], explode(',', $mod['userperms'])))
		{
			$checked = 0;
		}
		print_checkbox_row($usergroup['title'], 'userperms[' . $usergroup['usergroupid'] . ']', $checked);
	}
	print_table_break();
	print_table_break();


	print_submit_row();
}

// ##################### Do Add / Edit Module ##########################
if (in_array($_POST['do'], array('doaddmodule', 'doeditmodule')))
{
	globalize($_POST, array('modid' => INT, 'title' => STR, 'identifier' => STR, 'filename' => STR, 'templatename' => STR, 'displayorder' => INT, 'modcol' => INT, 'templatelist' => STR, 'inctype' => INT, 'active' => INT, 'userperms'));

	if (!$templatename AND (!$filename OR $filename == 'none' OR $filename == 'dashes') AND $identifier != 'custompage')
	{
		print_stop_message('adv_portal_must_choose_include');
	}

	if ($templatename)
	{
		$filename = $templatename;
		$inctype = 1;
	}

	if ($userperms)
	{
		foreach ($userperms AS $key => $value)
		{
			$ugroups[] = $key;
		}
		$userperms = implode(',', $ugroups);
	}

	if ($_POST['do'] == 'doaddmodule')
	{
		$DB_site->query("INSERT INTO " . TABLE_PREFIX . "adv_modules (title, identifier, filename, displayorder, modcol, inctype, templatelist, userperms, active) VALUES ('" . addslashes($title) . "', '$identifier', '$filename', '$displayorder', '$modcol', '$inctype', '$templatelist', '$userperms', '$active')");
		$modid = $DB_site->insert_id();

		if ($_POST['updateall'])
		{
			$pages = $DB_site->query("SELECT pageid, modules FROM " . TABLE_PREFIX . "adv_pages");
			while ($page = $DB_site->fetch_array($pages))
			{
				$modules = explode(',', $page['modules']);
				$modules[] = $modid;
				$modules = implode(',', $modules);
				$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_pages SET modules = '$modules' WHERE pageid = '$page[pageid]'");
			}
		}
	}
	else
	{
		$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_modules SET title = '" . addslashes($title) . "', identifier = '$identifier', filename = '$filename', displayorder = '$displayorder', modcol = '$modcol', inctype = '$inctype', templatelist = '$templatelist', userperms = '$userperms', active = '$active' WHERE modid = '$modid'");
	}

	update_module_cache();

	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=listmodules", 0);
}

// ######################## Remove Module ############################
if ($_REQUEST['do'] == 'removemodule')
{
	$module = $DB_site->query_first("SELECT modid, title FROM " . TABLE_PREFIX . "adv_modules WHERE modid = '$_REQUEST[modid]'");
	print_form_header('vba_cmps_admin', 'doremovemodule');
	construct_hidden_code('modid', $_REQUEST['modid']);
	print_table_header($vbphrase['confirm_deletion']);

	print_description_row(construct_phrase($vbphrase['are_you_sure_delete_module_called_x'], $module['title']));
	print_submit_row($vbphrase['yes'], '', 2, $vbphrase['no']);
}

// ######################## Do Remove Module ############################
if ($_POST['do'] == 'doremovemodule')
{
	
	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "adv_modules WHERE modid = '$_POST[modid]'");

	update_module_cache();

	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=listmodules", 0);
}

// ##################### List Pages #########################
if ($_REQUEST['do'] == 'listpages')
{
	print_form_header('vba_cmps_admin', 'doupdatepages');
	print_table_header($vbphrase['edit_pages'], 3);
	print_cells_row(array($vbphrase['title'], $vbphrase['identifier'], $vbphrase['options']), 1);

	?>
	<script type="text/javascript">
	function js_page_jump(pageid)
	{
		action = eval("document.cpform.p" + pageid + ".options[document.cpform.p" + pageid + ".selectedIndex].value");
		switch (action)
		{
			case 'edit': 
				page = 'vba_cmps_admin.php?do=editpage&pageid=';
				break;
			case 'remove': 
				page = 'vba_cmps_admin.php?do=removepage&pageid=';
				break;
		}
		document.cpform.reset();

		window.location = page + pageid + "&s=<?php echo $session['sessionhash']; ?>";
	}
	</script>

	<?php

	$diroptions = array(
		'edit' => $vbphrase['edit'],
		'remove' => $vbphrase['delete']
	);

	$pages = $DB_site->query("SELECT * FROM " . TABLE_PREFIX . "adv_pages");
	while ($page = $DB_site->fetch_array($pages))
	{
		$cells = array();
		$cells[] = '<a href="vba_cmps_admin.php?do=editpage&amp;pageid=' . $page['pageid'] . '">' . $page['title'] . '</a>';
		$cells[] = $page['name'];
		$cells[] = '<select name="p' . $page['pageid'] . '" onchange="js_page_jump(' . $page['pageid'] . ');" class="bginput">' . construct_select_options($diroptions) . '</select><input type="button" class="button" value="' . $vbphrase['go'] . '" onclick="js_page_jump(' . $page['pageid'] . ');" />';

		print_cells_row($cells);
	}

	print_table_footer();

}

// ########################## Build Modules ######################
if ($_REQUEST['do'] == 'buildmods')
{
	update_module_cache();
}

// ######################## Add / Edit Page #########################
if (in_array($_REQUEST['do'], array('addpage', 'editpage')))
{

	?>
	<script type="text/javascript">
	<!--
	function js_check_default(varname)
	{
		document.cpform[ "usedefault[" + varname + "]" ].checked=false
	}
	// -->
	</script>

	<script language="Javascript">
	
	function checkAll(allbox)
	{
		var	allInputs	=	allbox.form.getElementsByTagName("input")	
		var	allUsedefaults = new Array()
		for	(var i = 0;	i	<	allInputs.length;	i++)
		{	
			if (allInputs[i].type	!= 'checkbox')	
			continue;	
			if (allInputs[i].name.match(/usedefault\[[^\]]+\]/))
			{	
				allUsedefaults.push(allInputs[i])
			}
		}
		for	(var i = 0;	i	<	allUsedefaults.length; i++)
		{	
			allUsedefaults[i].checked	=	allbox.checked
		}
	}

	function checkAllUsergroup(allbox)
	{
		var	allInputs	=	allbox.form.getElementsByTagName("input")	
		var	allUserperms = new Array()
		for	(var i = 0;	i	<	allInputs.length;	i++)
		{	
			if (allInputs[i].type	!= 'checkbox')	
			continue;	
			if (allInputs[i].name.match(/userperms\[[^\]]+\]/))
			{	
				allUserperms.push(allInputs[i])
			}
		}
		for	(var i = 0;	i	<	allUserperms.length; i++)
		{	
			allUserperms[i].checked	=	allbox.checked
		}
	}
	
	
	</script>
	
	<?php

	require_once('./includes/adminfunctions_template.php');
	print_form_header('vba_cmps_admin', iif($_REQUEST['do'] == 'addpage', 'doaddpage', 'doeditpage'));

	if ($_REQUEST['do'] == 'editpage')
	{
		globalize($_REQUEST, array('pageid' => INT));

		$page = $DB_site->query_first("SELECT * FROM " . TABLE_PREFIX . "adv_pages WHERE pageid = '$pageid'");
		construct_hidden_code('page[pageid]', $page['pageid']);
	}

	print_table_header(iif($_REQUEST['do'] == 'addpage', $vbphrase['add_page'], $vbphrase['edit_page'] . ': ' . $page['title']));
	print_input_row($vbphrase['page_title'] . ':', 'page[title]', $page['title']);

	if ($page['name'] == 'home')
	{
		construct_hidden_code('page[name]', 'home');
	}
	else
	{
		print_input_row($vbphrase['page_identifier'] . ':<div class="smallfont">' . construct_phrase($vbphrase['page_identifier_example'], $vboptions['homeurl'], $vba_options['portal_pagevar']), 'page[name]', $page['name']);
	}

	print_input_row($vbphrase['page_template'] . ':<div class="smallfont">' . $vbphrase['page_template_description'], 'page[template]', $page['template']);
	print_style_chooser_row('page[styleid]', $page['styleid'], $vbphrase['use_default_style'], $vbphrase['custom_style_for_this_page'] . ':', 1);

	print_table_break();
	print_table_break();

	print_table_header($vbphrase['modules_enabled'], 3);

	$modules = $DB_site->query("SELECT * FROM " . TABLE_PREFIX . "adv_modules ORDER BY displayorder");
	while ($module = $DB_site->fetch_array($modules))
	{
		switch ($module['modcol'])
		{
			case 0:
				$leftcol[] = $module;
				break;
			case 2:
				$rightcol[] = $module;
				break;
			default:
				$centercol[] = $module;
		}
	}

	echo '<tr class="alt2">';
	print_column_row($leftcol, $vbphrase['left_column'], 'page');
	print_column_row($centercol, $vbphrase['center_column'], 'page');
	print_column_row($rightcol, $vbphrase['right_column'], 'page');

	print_table_break();
	print_table_break();


	print_table_header($vbphrase['usergroups']);
	print_description_row('<span style="float:' . $stylevar['right'] . '"><label for="ugcheckall"><input type="checkbox" name="allbox" onclick="checkAllUsergroup(this)" name="allbox" /> ' . $vbphrase['all_yes'] . '</span>' . $vbphrase['here_specify_which_usergroups_access_page'], '', 2, 'thead', 'center');

	$usergroups = $DB_site->query("SELECT usergroupid, title FROM " . TABLE_PREFIX . "usergroup ORDER BY usergroupid");
	while ($usergroup = $DB_site->fetch_array($usergroups))
	{
		$checked = 1;
		if ($page['userperms'] AND !in_array($usergroup['usergroupid'], explode(',', $page['userperms'])))
		{
			$checked = 0;
		}
		print_checkbox_row($usergroup['title'], 'userperms[' . $usergroup['usergroupid'] . ']', $checked);
	}
	print_table_break();
	print_table_break();


	print_table_header('<span style="float:' . $stylevar['right'] . '"><label for="allbox">' . $vbphrase['all_default'] . ' <input id="allbox" name="allbox" onclick="checkAll(this);" type="checkbox" /></label></span> ' . $vbphrase['advanced_options']);
	print_description_row($vbphrase['advanced_options_description']);
	print_table_break();

	$getphrases = $DB_site->query("SELECT varname, text FROM " . TABLE_PREFIX . "phrase ORDER BY languageid ASC");
	while($phrases = $DB_site->fetch_array($getphrases))
	{
		$settingphrase[$phrases['varname']] = $phrases['text'];
	}

	require_once('./includes/adminfunctions_options.php');

	$adv_opts = unserialize($page['advanced']);

	$settings = $DB_site->query("
			SELECT adv_setting.*
			FROM " . TABLE_PREFIX . "adv_setting AS adv_setting
			LEFT JOIN " . TABLE_PREFIX . "adv_settinggroup AS adv_settinggroup USING (grouptitle)
			WHERE adv_settinggroup.grouptitle LIKE 'adv_portal%'
			ORDER BY adv_settinggroup.displayorder, adv_setting.displayorder
	");

	while ($setting = $DB_site->fetch_array($settings))
	{
		$setting['default'] = true;
		if (!empty($adv_opts))
		{
			foreach ($adv_opts AS $adv => $value)
			{
				if ($adv == $setting['varname'] AND $value != $setting['value'])
				{
					$setting['default'] = false;
					$setting['value'] = $value;
				}
			}
		}
		$settingscache[$setting['grouptitle']][$setting['varname']] = $setting;
		$grouptitlecache[$setting['grouptitle']] = $setting['grouptitle'];
	}

	$DB_site->free_result($settings);

	foreach ($grouptitlecache AS $grouptitle)
	{
		$groups = print_adv_setting_group($grouptitle);
		print_table_break();
	}

	print_submit_row();
}

// ##################### Do Add / Edit Page ##########################
if (in_array($_POST['do'], array('doaddpage', 'doeditpage')))
{
	globalize($_POST, array('page', 'enablemod', 'setting', 'usedefault', 'userperms'));

	$adv_opts = construct_adv_page_settings();

	if (!trim($page['title']))
	{
		print_stop_message('adv_portal_must_enter_x_for_page', $vbphrase['title']);
	}

	if (!trim($page['name']))
	{
		print_stop_message('adv_portal_must_enter_x_for_page', $vbphrase['identifier']);
	}

	$emod = implode(',', $enablemod);

	if ($userperms)
	{
		foreach ($userperms AS $key => $value)
		{
			$ugroups[] = $key;
		}
		$userperms = implode(',', $ugroups);
	}

	if ($_POST['do'] == 'doeditpage')
	{
		$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_pages SET title = '" . addslashes($page['title']) . "', name = '" . addslashes($page['name']) . "', template = '$page[template]', modules = '$emod', advanced = '$adv_opts', userperms = '$userperms', styleid = '$page[styleid]' WHERE pageid = '$page[pageid]'");
	}
	else
	{
		$DB_site->query("INSERT INTO " . TABLE_PREFIX . "adv_pages (title, name, template, modules, advanced, styleid) VALUES ('" . addslashes($page['title']) . "', '" . addslashes($page['name']) . "', '" . trim($page['template']) . "', '$emod', '$adv_opts', '$page[styleid]')");
	}
	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=listpages", 0);

}

// ######################## Remove Page ############################
if ($_REQUEST['do'] == 'removepage')
{
	$page = $DB_site->query_first("SELECT pageid, title, name FROM " . TABLE_PREFIX . "adv_pages WHERE pageid = '$_REQUEST[pageid]'");
	if ($page['name'] == 'home')
	{
		print_stop_message('adv_portal_cant_remove_default');
	}
	print_form_header('vba_cmps_admin', 'doremovepage');
	construct_hidden_code('pageid', $page['pageid']);
	print_table_header($vbphrase['confirm_deletion']);
	print_description_row(construct_phrase($vbphrase['are_you_sure_delete_page_called_x'], $page['title']));
	print_submit_row($vbphrase['yes'], '', 2, $vbphrase['no']);
}

// ######################## Do Remove Page ############################
if ($_POST['do'] == 'doremovepage')
{
	
	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "adv_pages WHERE pageid = '$_POST[pageid]'");
	print_cp_redirect("vba_cmps_admin.php?$session[sessionurl]&do=listpages", 0);
}

print_cp_footer();
?>