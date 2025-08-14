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
	$optionstemplate = generateoptions();
	$result = $DB_site->query("UPDATE vcard_template SET template='$optionstemplate' WHERE title='options'");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'todb')
{
	$result = $DB_site->query("SELECT * FROM vcard_setting");
	echo '<pre>';
	while ($results = $DB_site->fetch_array($result))
	{
		//echo htmlspecialchars("$"."setting_title[$results[setting_id]] 		= \"$results[title]\";\r$"."setting_description[$results[setting_id]] 	= \"$results[description]\";\r\r");
		echo "\$result = \$DB_site->query(\"INSERT INTO vcard_setting VALUES ( '$results[setting_id]', '$results[settinggroup_id]', '\".addslashes(\$setting_title[$results[setting_id]]).\"', '$results[varname]', '$results[value]','\".addslashes(\$setting_description[$results[setting_id]]).\"', '$results[optioncode]', '$results[displayorder]')\");\r";
	}
}
// ############################# ACTION SCREENS #############################
// SCREEN = DEFAULT
if (empty($action))
{
	dohtml_form_header("options","saveoptions",0,0);

	$sqlwhere = (!empty($settinggroup_id))? " WHERE settinggroup_id='$settinggroup_id' " : ' WHERE displayorder<>0 ';

	echo "<p>$msg_admin_menu_options:</p><ul>\n";

	$settinggroups = $DB_site->query("SELECT * FROM vcard_settinggroup $sqlwhere ORDER BY displayorder");

	while ($settinggroup=$DB_site->fetch_array($settinggroups))
	{
		echo "<a href='#settinggroup$settinggroup[settinggroup_id]'>[ ". $settinggroup_title[$settinggroup['varname']] . " ]</a><br>\n";
	}
	
	echo '</ul>';
	$DB_site->data_seek(0,$settinggroups);
	while ($settinggroup = $DB_site->fetch_array($settinggroups))
	{
		dohtml_table_header("settinggroup$settinggroup[settinggroup_id]", $settinggroup_title[$settinggroup['varname']] );
		//echo "<hr noshade>";
		//echo "<a name=\"settinggroup$settinggroup[settinggroup_id]\"><p><b><i>$settinggroup[title]:</i></b></p>";
		//echo "<blockquote><table border=0 width=\"100%\">";
		$settings = $DB_site->query("SELECT setting_id,varname,value,optioncode,displayorder FROM vcard_setting WHERE settinggroup_id=$settinggroup[settinggroup_id] ORDER BY displayorder");
		while($setting=$DB_site->fetch_array($settings))
		{
			$setting['value'] = stripslashes($setting['value']);
			echo "<tr>\n<td valign='top' width='60%'><b>".$setting_title[$setting['varname']]."</b><br>".$setting_description[$setting['varname']]."</td>\n";
			echo '<td width="40%"><p>';
			if(empty($setting['optioncode']))
			{
				echo "<input type='text' size='$fieldsize' name='setting[$setting[setting_id]]' value='".htmlspecialchars($setting[value])."'>";
			
			// yes no
			}elseif ($setting['optioncode']=='yesno'){
				echo "$msg_admin_yes <input type='radio' name='setting[$setting[setting_id]]' value='1' ".cexpr($setting['value']==1,"checked","").">  $msg_admin_no <input type='radio' name='setting[$setting[setting_id]]' value='0' ".cexpr($setting['value']==0,"checked","").">";
			// textarea
			}elseif ($setting['optioncode']=='textarea'){
				echo "<textarea cols='$fieldsize' rows='15' name='setting[$setting[setting_id]]'>".htmlspecialchars($setting['value'])."</textarea>";
			// date formate
			}elseif ($setting['optioncode']=='dateformat'){
				echo "
				dd/mm/yyyy<input type='radio' name='setting[$setting[setting_id]]' value='1' ".cexpr($setting[value]==1,"checked","").">
				mm/dd/yyyy<input type='radio' name='setting[$setting[setting_id]]' value='0' ".cexpr($setting[value]==0,"checked","").">";
			// default
			}elseif ($setting['optioncode']=='language'){
				$handle = opendir('./../language');
				echo "<select name='setting[$setting[setting_id]]'>";
				while($dirname = readdir($handle))
				{
					if ($dirname !='.' && $dirname!='..')
					{
						if(filetype('./../language/'.$dirname)=='dir')
						{
							echo "<option value='$dirname' " . cexpr($dirname==$setting['value'],'selected','') .">$dirname</option>";
						}	
					}
				}
				closedir($handle);
				echo '</select>';
			}else{
				eval ("echo \"$setting[optioncode]\";");
			}
			echo '</p></td></tr>';
			//echo "<tr><td colspan=2><p>$setting[description]</p></td></tr>\n";
		}
		dohtml_table_footer();
		//echo "</table></blockquote></a>\n\n";
	}
	$DB_site->free_result($settinggroups);
	dohtml_form_footer($msg_admin_reg_update);
	dothml_pagefooter();
	exit;
}

?>
