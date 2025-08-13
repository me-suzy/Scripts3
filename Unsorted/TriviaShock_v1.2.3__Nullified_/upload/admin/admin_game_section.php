<?php
require("../script_ext.inc");
require("admin_global" . $script_ext);

show_cp_header();

show_section_info("Create / Edit Game Sections", "Create a new game section or edit an existing one.");

$form_options = get_global_input("form_options");

if( !isset($form_options[id]) )
{
	$form_options[id] = get_global_input("id");
}

global $form_options_error;


if( is_valid_id($form_options[id]) )
{		

	if( $form_options[submit] )
	{
		if( !($errors = check_input()) )
		{
			$db_stuff = array(
				'name' => $form_options[name],
				'description' => $form_options[description],
					);
			if( $form_options[id] == "new" )
			{
				if( !$form_options[id] = $db->insert_from_array($db_stuff,$DB_TABLES['GAME_SECTIONS'],"name") )
				{
					$errors[] = "There is already a game section named <b>$db_stuff[name]</b>!";
					$form_options_error['name'] = 1;
					show_form($errors);
				}
				else
				{
					show_status_message("Added game section: <b>$db_stuff[name]</b> to the database");
				}

				unset($db_stuff);

			}
			else
			{
				$db->update_from_array($db_stuff, $DB_TABLES['GAME_SECTIONS'],$form_options[id]);
				unset($db_stuff);

				show_status_message("Your changes to <b>$db_stuff[name]</b> have been saved to the database.");
			}

		} // end if !error
		else
		{
			show_form($errors);
		}
	

	} // end if $submit
	elseif( $form_options[id] != "new" )
	{
		$form_options = load_data_from_db($DB_TABLES['GAME_SECTIONS'], $form_options[id]);
		show_form($errors);
	}
	elseif( $form_options[id] == "new" )
	{
		show_form($errors);
	}

} // end is_valid_id
else
{
	error_out("Whoops", "No valid game section ID was specified!");
}

show_cp_footer();

function show_form($errors)
{

	global $HTTP_POST_VARS,$form_options,$form_options_error, $db;
 
	if( count($errors) )
	{
	 	show_errors($errors);
	}

	start_form();
	start_form_table();

	if( $form_options[id] && $form_options[id] != "new" )
	{
		do_table_header("<b>Editing Game Section</b> - <small>$form_options[name]</small>", 2);
	}
	else
	{
		do_table_header("<b>Creating New Game Section</b>", 2);
	}

	$currbg = switch_bgcolor($currbg);
	do_inputhidden("form_options[id]", $form_options[id]);

	start_table_row();
 
	do_option_info_cell("Game Section Name", "A name for this game section.",$currbg, "50%");
  
	start_table_cell($currbg, "50%");
	do_inputtext("form_options[name]", $form_options['name'], $form_options_error['name'], 40);

	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("Game Section Description", "A short description of this game section.",$currbg, "50%");
  
	start_table_cell($currbg, "50%");
	do_inputtext("form_options[description]", $form_options['description'], $form_options_error['description'], 80);

	end_table_cell();
	end_table_row();
	if( $form_options[id] == "new" )
	{
		$action = "Create Game Section";
	}
	else
	{
		$action = "Save Changes";
	}

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	start_table_cell($currbg, "", "center", "", 2);
	do_submitbutton("form_options[submit]", $action);
	space(2);
	do_resetbutton();
	end_table_cell();
	end_table_row();

	end_table(2);

} // end function show_form();

function check_input()
{
	global $form_options,$form_options_error;
	if( is_blank($form_options['name']) )
	{
		$errors[] = "<b>Game Section Name</b> is blank!";
		$form_options_error['name'] = 1;
	}
	if( strlen($form_options['name']) > 30 )
	{
		$errors[] = "<b>Game Section Name</b> is too long, it cannot exceed 30 characters";
		$form_options_error['name'] = 1;
	}


	if(strlen($form_options['description']) > 255)
	{
		$errors[] = "<b>Game Section Description</b> is too long, it cannot exceed 255 characters";
		$form_options_error['description'] = 1;
	}
	return $errors;

} // end function check_input()


?>
