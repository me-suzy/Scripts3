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

show_section_info("Edit Options", "Use this form to customize the program options.");

$form_options = get_global_input("form_options");

global $form_options_error;

define("TS_OPTION_TYPE_YESNO", 1);
define("TS_OPTION_TYPE_TEXT", 2);
define("TS_OPTION_TYPE_TEXTAREA", 3);
define("TS_OPTION_TYPE_TIMEZONE", 4);
if($form_options[submit_savechanges])
{
 
	if(!($errors = check_input()))
	{
		$message = save_changes();
		$OPTIONS = get_options();
		if( !$OPTIONS['TRIVIA_ONLINE'] && $OPTIONS['INTERRUPT_ACTIVE_GAME_SESSIONS'] )
		{
			interrupt_active_game_sessions();
		}
		cache_templates();
		
	} // end if
	
 
} // end if
elseif($form_options[submit_restoredefaults])
{
	$message = restore_defaults();
}
if($message)
{
	show_status_message($message);
}

show_form($errors);

show_cp_footer();

function check_input()
{

	global $form_options, $form_options_error, $db;

	$result = $db->query("SELECT * FROM ts_options ORDER BY group_id ASC");
	while($row = $db->fetch_array($result))
	{	 
		$i = $row[the_key];
		if($row[required] && is_blank($form_options[$i]))
		{
			$errors[] = "<b>$row[name]</b> is blank!";
			$form_options_error[$i] = 1;
		}

	switch($row['input'])
	{
		case 'num':

			if(!is_num($form_options[$i]))
			{
				$errors[] = "<b>$row[name]</b> must be a number";
				$form_options_error[$i] = 1;
			}
		break;
		
		case 'text':
		
			if(strlen($form_options[$i]) > 255)
			{
				$errors[] = "<b>$row[name]</b> is too long - it must be shorter than 255 characters";
				$form_options_error[$i] = 1;
			}

		break;
		
	
		case 'color':
			if(!ereg("\#[A-Za-z0-9]{6}", $form_options[$i]) && !ereg("[A-Za-z]+", $form_options[$i]))
			{
				$errors[] = "<b>$row[name]</b> must either be a valid HTML color (i.e. \"#FF00FF\") or a named color (i.e. \"green\").";
				$form_options_error[$i] = 1;
			}
		
		break;
		
		case 'url':
			if(!eregi("http\:\/\/.+\..+", $form_options[$i]))
			{
				$errors[] = "<b>$row[name]</b> must be a valid URL (web site address). Example: http://www.example.com";
				$form_options_error[$i] = 1;
			}
		
		break;
		
	
		case 'email':
			if( !is_email($form_options[$i]) )
			{
				$errors[] = "<b>$row[name]</b> must be a valid email address. Example: foo@example.com";
				$form_options_error[$i] = 1;
			}

		break;
	
	} // end switch
						
 } // end while

 return $errors;

} // end function check_input()

function show_form($errors=array())
{
 
	global $form_options, $form_options_error, $OPTIONS, $db;
	if(count($errors))
	 	show_errors($errors);

	start_form();
	start_form_table();
   
	$result = $db->query("SELECT * FROM ts_option_groups");
	while($row = $db->fetch_array($result))
	{
		do_table_header("<b>$row[name]</b>", 2);
		$result2 = $db->query("SELECT * FROM ts_options WHERE group_id=$row[id]");

		$currbg = switch_bgcolor($currbg);
		while($row2 = $db->fetch_array($result2))
		{
			$i = $row2[the_key];

	 
			if(!$form_options['submit_savechanges'])
			{
				$form_options[$i] = $row2['the_value'];
			}
		
			start_table_row();

			do_option_info_cell($row2[name],$row2[description],$currbg, "35%");
  
			start_table_cell($currbg,"75%");

			switch($row2['type'])
			{
				case TS_OPTION_TYPE_YESNO:			
				
					do_yesnoradio("form_options[$i]",$form_options[$i]);
					
				break;
				
				case TS_OPTION_TYPE_TEXT:
				
					do_inputtext("form_options[$i]",$form_options[$i],$form_options_error[$i],$row2[size]);
				
				break;
				
				
				case TS_OPTION_TYPE_TEXTAREA:
				
					do_textarea("form_options[$i]", $form_options[$i], $form_options_error[$i], 40, 4);
					
				break;
				
				
				case TS_OPTION_TYPE_TIMEZONE:
					$temp = array(
		
					'(GMT -12:00 hours) Eniwetok, Kwajalein'								=> "-12",
					'(GMT -12:00 hours) Eniwetok, Kwajalein'								=> "-11",
					'(GMT -10:00 hours) Hawaii'										=> "-10",
					'(GMT -9:00 hours) Alaska'										=> "-9",
					'(GMT -8:00 hours) Pacific Time (US & Canada)'								=> "-8",
					'(GMT -7:00 hours) Mountain Time (US & Canada)'								=> "-7",
					'(GMT -6:00 hours) Central Time (US & Canada), Mexico City'						=> "-6",
					'(GMT -5:00 hours) Eastern Time (US & Canada), Bogota, Lima, Quito'					=> "-5",
					'(GMT -4:00 hours) Atlantic Time (Canada), Caracas, La Paz'						=> "-4",
					'(GMT -3:30 hours) Newfoundland'									=> "-3.5",
					'(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown'							=> "-3",
					'(GMT -2:00 hours) Mid-Atlantic'									=> "-2",
					'(GMT -1:00 hours) Azores, Cape Verde Islands'								=> "-1",
					'(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia'					=> "0",
					'(GMT +1:00 hours) CET(Central Europe Time), Brussels, Copenhagen, Madrid, Paris'			=> "+1",
					'(GMT +2:00 hours) EET(Eastern Europe Time), Kaliningrad, South Africa'					=> "+2",
					'(GMT +3:00 hours) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg, Volgograd, Nairobi'			=> "+3",
					'(GMT +3:30 hours) Tehran'										=> "+3.5",
					'(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi'							=> "+4",
					'(GMT +4:30 hours) Kabul'										=> "+4.5",
					'(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi, Tashkent'						=> "+5",
					'(GMT +5:30 hours) Bombay, Calcutta, Madras, New Delhi'							=> "+5.5",
					'(GMT +6:00 hours) Almaty, Dhaka, Colombo'								=> "+6",
					'(GMT +7:00 hours) Bangkok, Hanoi, Jakarta'								=> "+7",
					'(GMT +8:00 hours) Beijing, Perth, Singapore, Hong Kong, Chongqing, Urumqi, Taipei'			=> "+8",
					'(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo, Yakutsk'						=> "+9",
					'(GMT +9:30 hours) Adelaide, Darwin'									=> "+9.5",
					'(GMT +10:00 hours) EAST(East Australian Standard), Guam, Papua New Guinea, Vladivostok'		=> "+10",
					'(GMT +11:00 hours) Magadan, Solomon Islands, New Caledonia'						=> "+11",
					'(GMT +12:00 hours) Auckland, Wellington, Fiji, Kamchatka, Marshall Island'				=> "+12"

					);
	
					do_select_from_array("form_options[$i]", $form_options[$i], $temp);
					
					
				break;
				
			} // end switch
			$currbg = switch_bgcolor($currbg);
			end_table_cell();
			end_table_row();

	 	} // end while $row2

	} // end while $row

	start_table_row();
	start_table_cell($currbg, "", "center", "", 2);
 
	do_submitbutton("form_options[submit_savechanges]","Save Changes");
	space(2);
	do_submitbutton("form_options[submit_restoredefaults]", "Restore Default Values");
  
	end_table_cell();
	end_table_row();
	end_table(2);

} // end function show_form()

function save_changes()
{
	global $form_options, $db;
 
	unset($form_options[submit_savechanges]);
	unset($form_options[submit_restoredefaults]);
	@reset($form_options);
	while(@list($i, $value) = @each($form_options))
	{
		$value = str_replace("\r", "", $form_options[$i]);
		$db->query("UPDATE ts_options SET the_value='$value' WHERE the_key='$i'");
	
	} // end while
	return "Your changes have been saved to the database.";
 
} // end function

function restore_defaults()
{
	global $form_options, $db;
 
 
	$db->query("UPDATE ts_options SET the_value=default_value");
	return "All options have been reset to their default values.";
 
} // end function restore_defaults()


?>
