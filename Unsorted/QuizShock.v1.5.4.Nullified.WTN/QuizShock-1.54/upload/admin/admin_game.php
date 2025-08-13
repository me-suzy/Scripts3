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

show_section_info("Create/Edit Quizzes", "Use this form to create new quizzes or edit existing ones.");

$form_options = get_global_input("form_options");
$form_categories = get_global_input("form_categories");

$form_options['id'] = get_global_input('id');
if( !num_categories(0) )
{
	error_out("Whoops", "There are no question categories in the database. You must first create a question category and add questions to it before you can create quizzes.");
}

if( is_valid_id($form_options['id']) )
{
	$game_name = get_game_name($form_options['id']);
	if( $form_options['id'] != "new" && !game_exists($form_options['id']) )
	{
		error_out("Whoops", "The quiz you specified does not exist!");
	}
	if( game_in_use($form_options['id']) )
	{
		error_out("Whoops", "Unable to change quiz <b>$game_name</b>. This quiz is currently in use. This means that it is either online
				or it is in maintenance mode with quiz sessions still in progress. To make changes to this quiz, you must either set
				it to offline mode or set it to maintenance mode and wait until all quiz sessions are finished. You can
				change the mode of this quiz via the " . hlink($TS_SCRIPTS[GAME_BR], "quiz browser", 1) . ".");
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
				'time_between_games'		=> $form_options['time_between_games'],
				'email_results_to'		=> $form_options['email_results_to'],
				'quizzes_per_user'		=> $form_options['quizzes_per_user']
					);
			if( $form_options['id'] == "new" )
			{
				$db_stuff['mode'] = TS_GAME_MODE_OFFLINE;
				$db_stuff['last_score_reset'] = time();
				
				$form_options['id'] = $db->insert_from_array($db_stuff, ts_games);
				insert_categories();

				show_status_message("Quiz <b>$form_options[name]</b> was created successfully.
				<br><br>Please note that your new quiz is <b>offline</b>. Before it can be taken
				and before it will show up on your site, it must be set to <b>online</b> mode.<br><br>

				To change the mode of this quiz, <a href=\"$TS_SCRIPTS[GAME_BR]\" class=header1link>proceed to the quiz browser</a>				
				");
			}
			else
			{
				$db->query("DELETE FROM ts_game_cats WHERE game_id=$form_options[id]");
				insert_categories();

				$db->update_from_array($db_stuff,ts_games, $form_options['id']);
				show_status_message("Your changes to <b>$db_stuff[name]</b> have been saved.<br><br>
				<a href=\"$TS_SCRIPTS[GAME_BR]\" class=header1link>Go to the quiz browser</a>");

			} // end else

		} // end if !error
		else
		{
			show_form($errors);
		}

	} // end if $submit

	elseif( $form_options['id'] != "new" )
	{
		$form_options = load_data_from_db(ts_games, $form_options['id']);
		$result = $db->query("SELECT category_id FROM ts_game_cats WHERE game_id=$form_options[id]");

		while( $row = $db->fetch_array($result) )
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
	error_out("Whoops", "Invalid quiz ID");
}


show_cp_footer();

function show_form($errors=array())
{
	global $form_options, $form_options_error, $form_categories, $db, $FILE_TYPES;
 
	if( count($errors) )
	{
		show_errors($errors);
	}

	start_form();
	start_form_table();

	if( $form_options['id'] && $form_options['id'] != "new")
	{
		do_table_header("<b>Editing Quiz</b> - <small>$form_options[name]</small>", 2);
	}
	else
	{
		do_table_header("<b>Creating New Quiz</b>", 2);
	}

	$currbg = switch_bgcolor($currbg);
	if( !$form_options['id'] )
	{
	 	$form_options['id'] = "new";
	}
	do_inputhidden("id", $form_options['id']);

	start_table_row(); 
	do_option_info_cell("Quiz Name", "A name for this quiz.",$currbg, "35%");  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[name]", $form_options['name'], $form_options_error['name'], 40);
	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Quiz Description","A short description for this quiz.",$currbg, "35%");
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[description]", $form_options['description'], $form_options_error['description'], 50);
	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Quiz Section","A section to place this quiz in.",$currbg, "35%");
	start_table_cell($currbg, "65%");
	do_select_from_query("form_options[game_section_id]", $form_options['game_section_id'], "SELECT id,name FROM ts_game_sections ORDER BY ts_game_sections.section_order, ts_game_sections.id ASC", "name", "id", "input");
	end_table_cell();
	end_table_row();

	/*
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Email Results To","After each quiz is taken, the results will be emailed to this address. Leave blank to disable.",$currbg, "35%");  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[email_results_to]", $form_options['email_results_to'], $form_options_error['email_results_to'], 50);
	end_table_cell();
	end_table_row();
	*/

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Flash Skin", "Select the flash skin to use for this game. Skins are flash (.swf) files that customize the look of the flash frontend that the quiz is taken through.",$currbg, "35%");
	start_table_cell($currbg, "65%");
	do_select_from_directory("form_options[game_skin]", $form_options['game_skin'], "../swf/skins/", "swf", "input"); 
	end_table_cell();
	end_table_row();
 

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Question Categories", "Select the question categories to use in this quiz. When the quiz is taken, questions will be randomly chosen from the categories you select.",$currbg, "35%");
	start_table_cell($currbg, "65%");
	$result = $db->query("SELECT id,name,description FROM ts_categories ORDER BY name ASC");
	while($row = $db->fetch_array($result))
	{
		$id = $row['id'];

		$row['num_questions'] = $db->query_one_result("SELECT COUNT(*) FROM ts_questions WHERE category_id=$row[id]");
	
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
	do_option_info_cell("Questions Per Session", "The number of questions given to the user each quiz session. These questions will be randomly selected each time.", $currbg, "35%");  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[questions_per_session]", $form_options['questions_per_session'], $form_options_error['questions_per_session'], 5);
	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Question Time Limit", "The amount of time (in seconds) that the user will have to answer each question.",$currbg, "35%");  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[question_time_limit]", $form_options['question_time_limit'], $form_options_error['question_time_limit'], 5, "input", "seconds");
	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Correct Points", "Number of points added to a users score when a question is answered correctly.",$currbg, "35%");
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[correct_points]", $form_options['correct_points'], $form_options_error['correct_points'], 5, "input", "points");
	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Incorrect Question Penalty", "The number of points to subtract from a users score when a question is answered incorrectly. <br><br>Note: if the player runs out of time on a question, it is also counted as incorrect.",$currbg, "35%");  
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
	do_option_info_cell("Show Correct Answer?", "Whether or not to display the correct answer if the user answers it incorrectly.",$currbg, "35%");
	start_table_cell($currbg, "65%");
	do_yesnoradio("form_options[show_correct_answer]", $form_options['show_correct_answer'], $form_options_error['show_correct_answer'], "input");
	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Quizzes Per User", "The number of times each user can take this quiz. Leave blank or set to 0 for unlimited.",$currbg, "35%");  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[quizzes_per_user]", $form_options['quizzes_per_user'], $form_options_error['quizzes_per_user'], 5, "input");
	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Time Between Quizzes", "The number of hours a user must wait between each quiz session (i.e. if you set this to 24, and a user takes the quiz at 3:00pm, he/she will not be able to take it again until 3:00pm the following day). Leave blank or set to 0 to disable this option.",$currbg, "35%");  
	start_table_cell($currbg, "65%");
	do_inputtext("form_options[time_between_games]", $form_options['time_between_games'], $form_options_error['time_between_games'], 5, "input", "hours");
	end_table_cell();
	end_table_row();

	if( $form_options[id] == "new" )
	{
		$action = "Create New Quiz";
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
	global $form_options,$form_options_error, $OPTIONS, $db, $form_categories;
	if( is_blank($form_options['name']) )
	{
		$errors[] = "<b>Quiz Name</b> is blank!";
		$form_options_error['name'] = 1;
	}
 
	if( is_blank($form_options['description']) )
	{
		$errors[] = "<b>Description</b> is blank!";
		$form_options_error['description'] = 1;
	}
	if( strlen($form_options['name']) > 30 )
	{
		$errors[] = "<b>Quiz Name</b> is too long, it cannot be over 30 characters.";
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


	if( !is_num_between($form_options['num_high_scores'], 2, 255) )
	{
		$errors[] = "<b>Number of High Scores</b> must be a number between 2 and 255";
		$form_options_error['num_high_scores'] = 1;
	}

	if( !is_num($form_options['high_score_period']) || $form_options['high_score_period'] > 9999 )
	{
		$errors[] = "<li><b>High Score Period</b> must be a number less than 9999";
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
		$errors[] = "You didn't select any questions categories for this quiz!";
	}

	return $errors;

} // end function check _input()

function insert_categories()
{
	global $form_options, $form_categories, $db;

	@reset($form_categories);
	while( @list($key,$value) = @each($form_categories) )
	{
		$db->query("INSERT INTO ts_game_cats (category_id, game_id) VALUES ($key, $form_options[id])");
	}

} // end function insert_categories()

?>