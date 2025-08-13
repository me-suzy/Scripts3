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
require("admin_global" . $script_ext);

show_cp_header();
show_section_info("Browse Flash Skins", "View samples of each Flash skin. Flash Skins control the look of the quiz frontend.");

$game_skin_dir = "../" . $TS_DIRS['GAME_SKINS'];

$fn = get_global_input("fn");

$form_options = get_global_input("form_options");

switch($fn)
{	
	case 'view_game_skin':

		show_form();

		show_game_skin($form_options['filename']);
	
	break;
	
	default:
	
		show_form();
		
	break;

}

show_cp_footer();
function show_game_skin($filename)
{
	global $TS_SCRIPTS, $TS_DIRS, $OPTIONS, $CP_HVARS;

	$currbg = switch_bgcolor($currbg);
	
	echo "<b>$filename</b> <small>(Displayed at 50% size)</small><br>";
	?>
	<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
	codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"
	WIDTH=300 HEIGHT=200>
	<PARAM NAME=movie VALUE="admin_game_skin_tester.swf?trivia_site=<?php echo $OPTIONS[TRIVIA_SITE_URL];?>&game_skin=<?php echo "$OPTIONS[TRIVIA_SITE_URL]/$TS_DIRS[GAME_SKINS]/$filename"; ?>"> <PARAM NAME=quality VALUE=high><PARAM NAME=bgcolor VALUE="<?php echo $CP_HVARS['BODY_BGCOLOR'];?>">
	<EMBED src="admin_game_skin_tester.swf?trivia_site=<?php echo $OPTIONS[TRIVIA_SITE_URL];?>&game_skin=<?php echo "$OPTIONS[TRIVIA_SITE_URL]/$TS_DIRS[GAME_SKINS]/$filename"; ?>" 
	quality=high WIDTH=300 HEIGHT=200 TYPE="application/x-shockwave-flash"
	PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></EMBED>
	</OBJECT>
	<?php

} // end function show_game_skin
function show_form()
{
	global $form_options, $game_skin_dir;

	start_form();
	
	do_inputhidden("fn", "view_game_skin");

	start_form_table();
	do_table_header("<b>Select Flash Skin</b>", 2);
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Flash Skin File", "Select the flash skin to display.", $currbg);
	start_table_cell($currbg, "65%");
	do_select_from_directory("form_options[filename]", $form_options['filename'], $game_skin_dir, "swf");
	end_table_cell();
	end_table_row();
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center", "", 2);
	do_submitbutton("form_options[submit]", "View Flash Skin");
	end_table_cell();
	end_table_row();

	end_table(2);
	end_form();
		
} // end function show_form()

?>
