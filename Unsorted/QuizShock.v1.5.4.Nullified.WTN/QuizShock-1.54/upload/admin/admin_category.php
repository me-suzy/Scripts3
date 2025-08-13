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

show_section_info("Create / Edit Categories", "Create a new question category or edit an existing one");

$form_options = get_global_input("form_options");

if( !isset($form_options['id']) )
	$form_options['id'] = get_global_input("id");

global $form_options_error;


if( is_valid_id($form_options['id']) )
{		

	$category_name = get_category_name($form_options[id]);
	if( $form_options['id'] != "new" )
	{
		if( !category_exists($form_options['id']) )
			error_out("Whoops", "The category you specified does not exist.");
		if($game_list = get_games_using_category($form_options[id]))
			error_out("Whoops", "Unable to edit question category <b>$category_name</b>. This category is currently being used by the following trivia game(s): <b>" . implode(', ', $game_list) . 
			"</b><br><br>In order to make modifications to this question category, each game using it will have to be set to offline mode or to maintenance
			mode (you will have to wait for all game sessions to finish if you set it to maintenance mode). You can change the mode
			of trivia games using the " . hlink($TS_SCRIPTS[GAME_BR], "game browser", 1) . ".");
			
	}


	if($form_options[submit])
	{
		if(!($errors = check_input()))
		{
			$db_stuff = array(
				'name' => $form_options[name],
				'description' => $form_options[description],
					);
			if($form_options[id] == 'new')
			{
				if(!($form_options[id] = $db->insert_from_array($db_stuff,ts_categories,"name")))
				{
					$errors[] = "There is already a category named <b>$db_stuff[name]</b>!";
					$form_options_error[name] = 1;
					show_form($errors);
				}
				else
				{
					show_status_message("Added the category: <b>$db_stuff[name]</b> to the database<br><br><a href=\"$TS_SCRIPTS[QUESTION]?id=new&category_id=$form_options[id]\" class=header1link>Add questions to this category</a>");
				}

				unset($db_stuff);
			}
			else
			{
				$db->update_from_array($db_stuff, ts_categories,$form_options[id]);
				unset($db_stuff);

				show_status_message("Your changes to <b>$db_stuff[name]</b> have been saved to the database.");
			}

		}
		else
		{
			show_form($errors);
		}
	

	} // end if $submit
	elseif($form_options[id] != "new")
	{
		$form_options = load_data_from_db(ts_categories, $form_options[id]);
		show_form($errors);
	}
	elseif($form_options[id] == "new")
	{
		show_form($errors);
	}

} // end is_valid_id
else
{
	error_out("Whoops", "No valid category ID was specified!");
}

show_cp_footer();

function show_form($errors)
{
 global $HTTP_POST_VARS,$form_options,$form_options_error, $db;
 
 if(count($errors))
 	show_errors($errors);

 start_form();
 start_form_table();

 if($form_options[id] && $form_options[id] != "new")
	do_table_header("<b>Editing Category</b> - <small>$form_options[name]</small>", 2);
 else
	do_table_header("<b>Creating New Category</b>", 2);


 $currbg = switch_bgcolor($currbg);

 if(!$form_options[id]) $form_options[id] = "new";

 do_inputhidden("form_options[id]", $form_options[id]);

 ////////// CATEGORY NAME //////////

 start_table_row();
 
 do_option_info_cell("Category Name", "A name for this category",$currbg, "50%");
  
 start_table_cell($currbg, "50%");

 do_inputtext("form_options[name]", $form_options['name'], $form_options_error['name'], 40);

 end_table_cell();
 end_table_row();

 ////////// CATEGORY DESCRIPTION //////////

 $currbg = switch_bgcolor($currbg);

 start_table_row();

 do_option_info_cell("Category Description", "A short description of this category.",$currbg, "50%");
  
 start_table_cell($currbg, "50%");

 do_inputtext("form_options[description]", $form_options['description'], $form_options_error['description'], 60);

 end_table_cell();
 end_table_row();

 /////////////////////////////////////////////////////////

 if($form_options[id] == "new")
	$action = "Create Category";
 else
	$action = "Save Changes";

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
 global $form_options,$form_options_error,$OPTIONS, $db;

 if(is_blank($form_options['name']))
 {
	$errors[] = "<b>Category Name</b> is blank!";
	$form_options_error[name] = 1;
 }

 if(strlen($form_options['name']) > 30)
 {
	$errors[] = "<b>Category Name</b> is too long, it cannot exceed 30 characters";
	$form_options_error['name'] = 1;
 }

 if(is_blank($form_options['description']))
 {
	$errors[] = "<b>Category Description</b> is blank!";
	$form_options_error[description] = 1;
 }


 if(strlen($form_options['description']) > 100)
 {
	$errors[] = "<b>Category Description</b> is too long, it cannot exceed 100 characters";
	$form_options_error['description'] = 1;
 }

 return $errors;

} // end function check_input()


?>