<?php
require("../script_ext.inc");
require("admin_global" . $script_ext);

show_cp_header();

show_section_info("Create/Edit Trivia Games", "Use this form to create new trivia games or edit existing ones.");

$form_options = get_global_input("form_options");
$form_categories = get_global_input("form_categories");

$form_options['id'] = get_global_input("id");
if( !num_categories(0) )
{
	error_out("Whoops", "There are no question categories in the database. You must first create a question category and add questions to it before you can create trivia games.");
}

if( is_valid_id($form_options['id']) )
{
	$game_name = get_game_name($form_options['id']);
	if( $form_options['id'] != "new" && !game_exists($form_options['id']) )
	{
		error_out("Whoops", "The game you specified does not exist!");
	}
	if( game_in_use($form_options['id']) )
	{
		error_out("Whoops", "Unable to change game <b>$game_name</b>. It appears that this game is currently in use. This means that it is either online
				or it is in maintenance mode with game sessions still in progress. To make changes to this game, you must either set
				it to offline mode or set it to maintenance mode and wait until all game sessions are finished. You can
				change the mode of this game via the " . hlink($TS_SCRIPTS[GAME_BR], "game browser", 1) . ".");
	}
	if( $form_options['submit'] )
	{
		$errors = check_input();
		if( !$errors )
		{
			if( count($form_categories) > 1 )
			{
				$multicategory = 1;
			}
			else
			{
				$multicategory = 0;
			}
			
			$db_stuff = array(
				'name'				=> $form_options['name'],
				'description'			=> $form_options['description'],
				'questions_per_session'		=> $form_options['questions_per_session'],
				'question_time_limit'		=> $form_options['question_time_limit'],
				'incorrect_penalty'		=> $form_options['incorrect_penalty'],
				'correct_points'		=> $form_options['correct_points'],
				'num_high_scores'		=> $form_options['num_high_scores'],
				'high_score_period'		=> $form_options['high_score_period'],
				'show_correct_answer'		=> $form_options['show_correct_answer'],
				'multicategory'			=> $multicategory,
				'game_skin'			=> $form_options['game_skin'],
				'game_section_id'		=> $form_options['game_section_id'],
				'time_between_games'		=> $form_options['time_between_games']
					);
			if( $form_options['id'] == "new" )
			{
				$db_stuff['mode'] = TS_GAME_MODE_OFFLINE;
				$db_stuff['last_score_reset'] = time();
				
				$form_options['id'] = $db->insert_from_array($db_stuff, $DB_TABLES['GAMES']);
				insert_categories();

				show_status_message("Trivia Game <b>$form_options[name]</b> was created successfully.
				<br><br>Please note that your new game is <b>offline</b>. Before it can be played
				and before it will show up on your site, it must be set to <b>online</b> mode.<br><br>

				To change the mode of this game, <a href=\"$TS_SCRIPTS[GAME_BR]\" class=header1link>proceed to the game browser</a>				
				");
			}
			else
			{
				$db->query("DELETE FROM $DB_TABLES[GAME_CATS] WHERE game_id=$form_options[id]");
				insert_categories();

				$db->update_from_array($db_stuff,$DB_TABLES[GAMES], $form_options['id']);
				show_status_message("Your changes to <b>$db_stuff[name]</b> have been saved.<br><br>
				<a href=\"$TS_SCRIPTS[GAME_BR]\" class=header1link>Go to the game browser</a>");

			} // end else

		} // end if !error
		else
		{
			show_form($errors);
		}

	} // end if $submit

	elseif( $form_options['id'] != "new" )
	{
		$form_options = load_data_from_db($DB_TABLES['GAMES'], $form_options['id']);
		$result = $db->query("SELECT category_id FROM $DB_TABLES[GAME_CATS] WHERE game_id=$form_options[id]");

		while( $row = $result->fetch_array() )
		{
			$category_id = $row['category_id'];
			$form_categories[$category_id] = 1;
		}

		show_form($errors);
	}

	elseif($form_options['id'] == "new")
	{
		show_form();
	}

} // end if $id
else
{
	error_out("Whoops", "Invalid game ID");
}


show_cp_footer();

function show_form($errors=array())
{
	global $form_options, $form_options_error, $form_categories, $db, $DB_TABLES, $FILE_TYPES;
 
	if( count($errors) )
	{
		show_errors($errors);
	}

	start_form();
	start_form_table();

	if( $form_options['id'] && $form_options['id'] != "new")
	{
		do_table_header("<b>Editing Game</b> - <small>$form_options[name]</small>", 2);
	}
	else
	{
		do_table_header("<b>Creating New Game</b>", 2);
	}

	$currbg = switch_bgcolor($currbg);
	if( !$form_options['id'] )
	{
	 	$form_options['id'] = "new";
	}
	do_inputhidden("id", $form_options['id']);

	start_table_row();
 
	do_option_info_cell("Game Name", "A name for this game.",$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[name]", $form_options['name'], $form_options_error['name'], 40);

	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("Game Description","A short description for this game.",$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[description]", $form_options['description'], $form_options_error['description'], 80);

	end_table_cell();
	end_table_row();
 
	 ////////// GAME SECTION //////////

	$currbg = switch_bgcolor($currbg);

	start_table_row();

	do_option_info_cell("Game Section","A section to place this game in.",$currbg, "35%");

	start_table_cell($currbg, "65%");

	do_select_from_query("form_options[game_section_id]", $form_options['game_section_id'], "SELECT id,name FROM $DB_TABLES[GAME_SECTIONS] ORDER BY $DB_TABLES[GAME_SECTIONS].section_order, $DB_TABLES[GAME_SECTIONS].id ASC", "name", "id", "input");

	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
 
	do_option_info_cell("Game Skin", "Select the game skin to use for this game. Skins are flash (.swf) files that customize the look of the flash frontend that the game is played through.",$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_select_from_directory("form_options[game_skin]", $form_options['game_skin'], "../swf/skins/", "swf", "input");
 
	end_table_cell();
	end_table_row();
 

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("Question Categories", "Select the question categories to use in this game. When the game is played, questions will be randomly chosen from the categories you select.",$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	$result = $db->query("SELECT id,name,description FROM $DB_TABLES[CATEGORIES] ORDER BY name ASC");
	while($row = $result->fetch_array())
	{
		$id = $row['id'];

		$row['num_questions'] = $db->query_one_result("SELECT COUNT(*) FROM $DB_TABLES[QUESTIONS] WHERE category_id=$row[id]");
	
		echo "<table cellpadding=5 border=0 cellspacing=1>";
		start_table_row();
		start_table_cell("","","","top");
		do_checkbox("form_categories[$id]", "1", $form_categories[$id]);

		end_table_cell();
		start_table_cell("","","","top");
		echo "<b>$row[name]</b> ($row[num_questions] questions)";
		br();
		echo "<small>$row[description]</small>";

		end_table_cell();
		end_table_row();
		end_table();

	} // end while


	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("Questions Per Session", "The number of questions given to the player each game. These questions will be randomly selected each time.", $currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[questions_per_session]", $form_options['questions_per_session'], $form_options_error['questions_per_session'], 5);

	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("Question Time Limit", "The amount of time (in seconds) that the player will have to answer each question.",$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[question_time_limit]", $form_options['question_time_limit'], $form_options_error['question_time_limit'], 5, "input", "seconds");

	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("Correct Points", "Number of points added to a players score when a question is answered correctly.",$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[correct_points]", $form_options['correct_points'], $form_options_error['correct_points'], 5, "input", "points");

	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("Incorrect Question Penalty", "The number of points to subtract from a players score when a question is answered incorrectly. <br><br>Note: if the player runs out of time on a question, it is also counted as incorrect.",$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[incorrect_penalty]", $form_options['incorrect_penalty'], $form_options_error['incorrect_penalty'], 5, "input", "points");

	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("Number of High Scores", "Number of high scores to show on the high score list." ,$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[num_high_scores]", $form_options['num_high_scores'], $form_options_error['num_high_scores'], 5);

	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("High Score Period", "Number of days to keep up with high scores before automatically resetting them. Leave blank or set to 0 to never automatically reset high scores.",$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[high_score_period]", $form_options['high_score_period'], $form_options_error['high_score_period'], 5, "input", "days");

	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("Show Correct Answer?", "Whether or not to display the correct answer if the player answers it incorrectly.<br><br>Note: Setting this to No would keep the game more competitive and discourage cheating but not revealing the correct answer.",$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_yesnoradio("form_options[show_correct_answer]", $form_options['show_correct_answer'], $form_options_error['show_correct_answer'], "input");

	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);

	start_table_row();
	do_option_info_cell("Time Between Games", "The number of hours a player must wait between each game (i.e. if you set this to 24, and a player plays the game at 3:00pm, he/she will not be able to play it again until 3:00pm the following day). Leave blank or set to 0 to disable this option.",$currbg, "35%");
  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[time_between_games]", $form_options['time_between_games'], $form_options_error['time_between_games'], 5, "input", "hours");

	end_table_cell();
	end_table_row();
	if( $form_options[id] == "new" )
	{
		$action = "Create New Game";
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
	br(1);

} // end function show_form()

function check_input()
{
	global $form_options,$form_options_error, $OPTIONS, $db, $DB_TABLES, $form_categories;
	if( is_blank($form_options['name']) )
	{
		$errors[] = "<b>Game Name</b> is blank!";
		$form_options_error['name'] = 1;
	}
 
	if( is_blank($form_options['description']) )
	{
		$errors[] = "<b>Description</b> is blank!";
		$form_options_error['description'] = 1;
	}
	if( strlen($form_options['name']) > 30 )
	{
		$errors[] = "<b>Game Name</b> is too long, it cannot be over 30 characters.";
		$form_options_error['name'] = 1;
	}

	if( strlen($form_options['description']) > 100 )
	{
		$errors[] = "<b>Description</b> is too long, it cannot be over 100 characters.";
		$form_options_error['description'] = 1;
	}

	if( !is_num_between($form_options['questions_per_session'], 2, 99) )
	{
		$errors[] = "<b>Questions Per Session</b> must be a number between 2 and 99";
		$form_options_error['questions_per_session'] = 1;
	}

	if( !is_num_between($form_options['question_time_limit'], 1, 255) )
	{
		$errors[] = "<b>Question Time Limit</b> must be a number between 1 and 255";
		$form_options_error['question_time_limit'] = 1;
	}

	if( !is_num_between($form_options['correct_points'], 1, 999) )
	{
		$errors[] = "<b>Correct Points</b> must be a number between 1 and 999";
		$form_options_error['correct_points'] = 1;
	}

	if( $form_options['incorrect_penalty'] && !is_num_between($form_options['incorrect_penalty'], 1, 999) )
	{
		$errors[] = "<b>Incorrect Penalty</b> must be a number between 1 and 999";
		$form_options_error['incorrect_penalty'] = 1;
	}


	if( !is_num_between($form_options['num_high_scores'], 2, 100) )
	{
		$errors[] = "<b>Number of High Scores</b> must be a number between 2 and 100";
		$form_options_error['num_high_scores'] = 1;
	}

	if( !is_num($form_options['high_score_period']) || $form_options['high_score_period'] > 365 )
	{
		$errors[] = "<li><b>High Score Period</b> must be a number less than 365";
		$form_options_error['high_score_period'] = 1;
	}
	$cats = 0;

	@reset($form_categories);
	while( @list($key,$value) = @each($form_categories) )
	{
		if($value)
		{
			$cats++;
		}
	}

	if(!$cats)
	{
		$errors[] = "You didn't select any questions categories for this game!";
	}

	return $errors;

} // end function check _input()
function insert_categories()
{
	global $form_options, $form_categories, $db, $DB_TABLES;

	@reset($form_categories);
	while( @list($key,$value) = @each($form_categories) )
	{
		$db->query("INSERT INTO $DB_TABLES[GAME_CATS] (category_id, game_id) VALUES ($key, $form_options[id])");
	}

} // end function insert_categories()


?>
