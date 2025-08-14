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

check_lvl_access($canviewstyle);

// ############################# DB ACTION #############################
// ###################### Download Style #######################
if ($action == 'download')
{

	if (function_exists("set_time_limit")==1 && get_cfg_var("safe_mode")==0)
	{
		@set_time_limit(1200);
	}
	$style[title] 	= "vCard Style Set";
	$replacements 	= $DB_site->query("SELECT * FROM vcard_replace");
	$templates 	= $DB_site->query("SELECT * FROM vcard_template WHERE title<>'options'");
	
	$plaintext_template  = escapepipe($templateversion)."|||";
	$plaintext_template .= escapepipe($style[title])."|||";
	$plaintext_template .= $DB_site->num_rows($replacements)."|||".$DB_site->num_rows($templates)."|||";
	
	while ($replacement=$DB_site->fetch_array($replacements))
	{
		$plaintext_template .= escapepipe($replacement[findword])."|||".escapepipe($replacement[replaceword])."|||";
	}
	$DB_site->free_result($replacements);
	while ($template = $DB_site->fetch_array($templates))
	{
		$template[template] = stripslashes($template[template]);
		$plaintext_template .= escapepipe($template[title])."|||".escapepipe($template[template])."|||";
	}
	$DB_site->free_result($templates);
	
	header("Content-disposition: filename=vcard.style");
	header("Content-Length: ".strlen($plaintext_template));
	header("Content-type: unknown/unknown");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $plaintext_template;
	exit;
}


dothml_pageheader();

// ###################### Import Style #######################
if ($action == 'import')
{
	if (function_exists("set_time_limit")==1 && get_cfg_var("safe_mode")==0)
	{
		@set_time_limit(1200);
	}
	/*
	if ($safeupload)
	{
		if (function_exists("is_uploaded_file"))
		{
			$path = "$site_image_path/$HTTP_POST_VARS[stylefile_name]";
			if (is_uploaded_file($stylefile))
			{
				if (move_uploaded_file($stylefile, "$path"))
				{
				$stylefile = $path;
				}
			}
		}
	}
	*/
	$styletext = get_filecontent($HTTP_POST_VARS['stylefile']);
	//@unlink($stylefile);
	
	if (trim($styletext) == "")
	{
		dohtml_errorpage(1,$msg_admin_style_errorfile);
	}
	
	$stylebits = explode("|||",$styletext);
	
	list($devnul,$styleversion) = each($stylebits);
	
	if ($styleversion != $templateversion && $HTTP_POST_VARS['ignorestyle'] == 0)
	{
		$localmessage = "
		<p>$msg_admin_style_errorver</p>
		<p>$msg_admin_style_yourtplver: $templateversion<br>
		$msg_admin_style_yourstylever: $styleversion</p>";
		dohtml_errorpage(1,$localmessage);
	}

	// remove old templates
	$DB_site->query("DELETE FROM vcard_replace ");
	$DB_site->query("DELETE FROM vcard_template WHERE title<>'options' ");
	
	list($devnul,$style[title]) 		= each($stylebits);
	// get number of replacements and templates
	list($devnull,$numreplacements) 	= each($stylebits);
	list($devnull,$numtemplates) 		= each($stylebits);
	
	$counter=0;
	while ($counter++<$numreplacements)
	{
		list($devnull,$findword)	= each($stylebits);
		list($devnull,$replaceword) 	= each($stylebits);
		if (trim($findword) != "")
		{
			$DB_site->query("INSERT INTO vcard_replace (replace_id,findword,replaceword) VALUES (NULL,'".addslashes($findword)."','".addslashes($replaceword)."')");
		}
	}
	
	$counter = 0;
	while ($counter++<$numtemplates)
	{
		list($devnull,$title) 		= each($stylebits);
		list($devnull,$template) 	= each($stylebits);
		if (trim($title) != "")
		{
			$DB_site->query("INSERT INTO vcard_template (template_id,title,template) VALUES (NULL,'".addslashes($title)."','".addslashes($template)."')");
		}
	}
	$local_message = "$msg_admin_style_import";
	dohtml_result(1,"$local_message");
	echo "<blockquote>$style[title] $msg_admin_imported!</p> <P>$msg_admin_style_note1</blockquote>";
	$action = "";
}

if (empty($action))
{
dohtml_form_header("style","download",0,1);
dohtml_table_header("add","$msg_admin_menu_style");
dohtml_form_infobox($msg_admin_style_download);
dohtml_form_footer($msg_admin_menu_download);


dohtml_form_header("style","import",0,1);
dohtml_table_header("add","$msg_admin_menu_import");
dohtml_form_input($msg_admin_stylefile,"stylefile","vcard.style");
dohtml_form_yesno($msg_admin_style_note3,"ignorestyle",0);
dohtml_form_footer($msg_admin_menu_import);
dothml_pagefooter();
exit;

}
?>