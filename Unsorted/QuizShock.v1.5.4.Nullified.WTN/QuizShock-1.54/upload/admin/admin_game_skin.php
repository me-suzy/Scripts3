<?php
/************************************************************************/
/*  Program Name         : QuizShock                                    */
/*  Program Version      : 1.5.4                                        */
/*  Program Author       : Pineapple Technologies                       */
/*  Supplied by          : CyKuH [WTN]                                  */
/*  Nullified by         : CyKuH [WTN]                                  */
/*  Distribution         : via WebForum and Forums File Dumps           */
/*                  (c) WTN Team `2004                                  */
/*   Copyright (c)2002 Pineapple Technologies. All Rights Reserved.     */
/************************************************************************/

require("../script_ext.inc");
require("admin_global" . $script_ext);;

show_cp_header();

show_section_info("Upload Flash Skin", "Use this form to upload a Flash frontend Skin. Flash skins control the look of the quiz frontend");

$game_skin_dir = "../$TS_DIRS[GAME_SKINS]/";

$form_options = get_global_input("form_options");
if(!isset($HTTP_POST_FILES))
{
	$HTTP_POST_FILES[game_skin_file][name] = get_global_input("game_skin_file_name");
	$HTTP_POST_FILES[game_skin_file][size] = get_global_input("game_skin_file_size");
	$HTTP_POST_FILES[game_skin_file][tmp_name] = get_global_input("game_skin_file");
}
if(isset($form_options[submit]))
{
	if(!$HTTP_POST_FILES[game_skin_file][name])
	$errors[] = "You didn't specify a file to upload!";
	elseif(!is_uploaded_file($HTTP_POST_FILES[game_skin_file][tmp_name]))
		$errors[] = "Invalid file upload. Please make sure to select a file from your local computer to upload.";
	elseif(!$HTTP_POST_FILES[game_skin_file][size])
		$errors[] = "The file you uploaded is empty!";
	if(count($errors))
		show_form($errors);
	else
	{
		$tmp = explode('.', $HTTP_POST_FILES[game_skin_file][name]);
		$ext = $tmp[count($tmp)-1];
		if(!@in_array($ext, $TS_FILE_TYPES[GAME_SKIN]))
		{
			$errors[] = "Invalid file type. The acceptable file type(s) for flash skins are:<br><b>" . implode("</b>, <b>", $game_skin_types) . "</b>.";
		}
		if(count($errors))
		{
			show_form($errors);
		}
		else
		{
			if(!@move_uploaded_file($HTTP_POST_FILES[game_skin_file][tmp_name], $game_skin_dir . $HTTP_POST_FILES[game_skin_file][name]))
			{
				error_out("Whoops", "Unable to write flash skin file. Please ensure that the game_skins directory is set to writable (chmod 755).");
			}
			
			show_status_message("Uploaded flash skin <b>" . $HTTP_POST_FILES[game_skin_file][name] . "</b> successfully.");

		} // end else


	} // end else

} // end if isset($submit)
else
	show_form();

function show_form($errors=array())
{
	global $TS_SCRIPTS;
	
	if(count($errors))
		show_errors($errors);

	echo "<form action=\"$TS_SCRIPTS[GAME_SKIN]\" ENCTYPE=\"multipart/form-data\" method=POST>";

	start_form_table();
	do_table_header("<b>Upload Flash Skin</b>", 2);

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Flash Skin File", "Select the Flash (.swf) skin file from your computer.", $currbg);

	start_table_cell($currbg, "75%");
	echo "<input type=file name=\"game_skin_file\" ENCTYPE=\"multipart/form-data\" size=35>";
	end_table_cell();
	end_table_row();

	start_table_row();
	$currbg = switch_bgcolor($currbg);
	start_table_cell($currbg, "", "center", "", 2);
	do_submitbutton("form_options[submit]", "Upload Flash Skin");
	br();
	echo "<small>Please be patient, this could take a few moments.</small>";
	end_table_cell();
	end_table_row();
	end_table(2);

	end_form();


} // end function show_form

?>
