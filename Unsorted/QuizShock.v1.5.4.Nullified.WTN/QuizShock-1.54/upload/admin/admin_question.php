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

show_section_info("Create/Edit Questions", "Use this form to create questions or edit existing ones.");

$form_options = get_global_input("form_options");
$form_buttons = get_global_input("form_buttons");

$answers = get_global_input("answers");
$answers_order = get_global_input("answers_order");
$answers_delete = get_global_input("answers_delete");

$new_answers = get_global_input("new_answers");
$new_answers_order = get_global_input("new_answers_order");
$new_answers_delete = get_global_input("new_answers_delete");

if( !isset($form_options['id']) )
{
	$form_options['id'] = get_global_input("id");
}

if( !isset($form_options['category_id']) )
{
	$form_options['category_id'] = get_global_input("category_id");
}
global $new_answers_error;
global $new_answers_order_error;
global $answers_error;
global $answers_order_error;
global $question_error;
global $form_options_error;
define("TS_QUESTION_MAX_LENGTH", 150);
define("TS_ANSWER_MAX_LENGTH", 60);
define("TS_ANSWER_NOTES_MAX_LENGTH", 200);
if( !num_categories(0) )
{
	error_out("Whoops", "There are no question categories in the database. You must first create categories before you can add any questions");
}
if( $form_buttons['truefalse'] )
{
	$preset_answers[1] = "True";
	$preset_answers[2] = "False";

	$form_options['num_new_answers'] = 2;
}
else
{
	switch( $form_options['type'] )
	{
		case "All of the Above":

			$num = $form_options[num_new_answers];
			$preset_answers[$num] = "All of the Above";
			
			$new_answers_order[$num] = $num;

		break;

		case "None of the Above":
			$num = $form_options[num_new_answers];
			$preset_answers[$num] = "None of the Above";;
			
			$new_answers_order[$num] = $num;
		break;

	} // end switch

} // end else

if( is_valid_id($form_options['id']) )
{
	if($form_options[id] != "new")
	{
		if(!question_exists($form_options[id]))
			error_out("Whoops", "The question you specified does not exist");		
	}
	if( isset($form_options['category_id']) )
	{
		
		$category_name = get_category_name($form_options[category_id]);
		if(!category_exists($form_options['category_id']))
		{
			error_out("Whoops", "The category you specified does not exist");
		}
		if( $game_list = get_games_using_category($form_options['category_id']) )
		{
	
			error_out("Whoops", "Unable to add/modify questions in category <b>$category_name</b>. This question category is currently being used by the following trivia game(s): <b>" . implode(', ', $game_list) . 
			"</b><br><br>In order to make modifications to this question category (by adding or modifying questions), each game using it must be set to offline mode or to maintenance
			mode (you will have to wait for all game sessions to finish if you set it to maintenance mode). You can change the mode
			of trivia games using the " . hlink($TS_SCRIPTS[GAME_BR], "game browser", 1) . ".");

		} // end if
		
	} // end if
	if( isset($form_buttons['submit']) )
	{
		if(!$errors = check_input())
		{
			if($form_options[id] == "new")
			{
				save_changes();

				show_status_message("Question: \"<b>" . stripslashes($form_options[question]) . "</b>\" has been added to the database");
				new_question_form();

				start_form($TS_SCRIPTS[QUESTION_BR]);
				start_form_table();
				$currbg = switch_bgcolor($currbg);
				start_table_row();
				 start_table_cell($currbg);

				  echo "Browse questions in ";
				  do_select_from_query("category_id", $form_options[category_id], "SELECT id,name FROM ts_categories ORDER BY name ASC", "name", "id");
				  space();
				  do_submitbutton("form_buttons[submit]", "Go");
				 end_table_cell();
				end_table_row();
				end_table(2);

			}
			else
			{
				save_changes();
				show_status_message("Your changes to <b>" . stripslashes($form_options[question]) . "</b> have been saved.");
				new_question_form();

				start_form($TS_SCRIPTS[QUESTION_BR]);
				start_form_table();
				$currbg = switch_bgcolor($currbg);
				start_table_row();
				 start_table_cell($currbg);

				  echo "Browse questions in ";
				  do_select_from_query("category_id", $form_options[category_id], "SELECT id,name FROM ts_categories ORDER BY name ASC", "name", "id");
				  space();
				  do_submitbutton("form_buttons[submit]", "Go");
				 end_table_cell();
				end_table_row();
				end_table(2);
				
			}

		}
		else
		{
			show_form($errors);
		}
	
	} // end if submit
	elseif($form_buttons['del_answer_choices'])
	{
		$message = delete_answers();

		show_status_message($message);
		show_form();
	}
	elseif($form_buttons['add_answer_choices'] || $form_buttons['truefalse'])
	{
		if($form_options[num_new_answers] > 1)
			$message .= "Added <b>$form_options[num_new_answers]</b> answer choices.";
		else
			$message .= "Added <b>1</b> answer choice.";

		show_status_message($message);
		show_form();
			
	}
	elseif($form_options[id] != "new")
	{
		$form_options = load_data_from_db(ts_questions, $form_options[id]);
		$result = $db->query("SELECT * FROM ts_answers WHERE question_id=$form_options[id] ORDER BY id ASC");
		while($row = $db->fetch_array($result))
		{
			$actr++;
			$aid = $row['id'];
		
			$answers[$aid] = $row['answer'];
			$answers_correct[$aid] = $row['correct'];

			if($row['answer_order'])
				$answers_order[$aid] = $row['answer_order'];
			if($row[correct])
				$form_options[correct_answer] = $actr;

		} // end while

		show_form($errors);
	
	} // end if $form_options[id]
	else
		new_question_form();

} // end if is_valid_id
else
	error_out("Whoops", "No valid question ID was specified.");

end_form();


show_cp_footer();

function show_form($errors=array())
{

 global $db;
 global $answers,$answers_order;
 global $new_answers,$new_answers_order;
 global $preset_answers;
 global $question_error;
 global $form_options, $form_buttons, $form_options_error;
 	
 // Some globals used to flag fields with errors in them
 global $answers_error,$answers_order_error,$new_answers_error,$new_answers_order_error;


 // If we got any errors, show them
 if(count($errors))
 	show_errors($errors);

 start_form();
 start_form_table();

 if($form_options[id] && $form_options[id] != "new")
	do_table_header("<b>Editing Question</b> - <small>\"" . stripslashes($form_options[question]) . "\"</small>", 2);
 else
	do_table_header("<b>Creating New Question</b>", 2);

 do_inputhidden("form_options[id]", $form_options[id]);

 ///////////////////////////////////////////////
 // category
 ///////////////////////////////////////////////
 $currbg = switch_bgcolor($currbg);
 start_table_row();
  do_option_info_cell("Category", "The category to put this question under", $currbg);
  start_table_cell($currbg);

   $query = "SELECT id,name FROM ts_categories ORDER BY name ASC";
   
   // Make a list of categories
   do_select_from_query("form_options[category_id]", $form_options['category_id'], $query, "name", "id");
  end_table_cell();
 end_table_row();
 
 //// Question ///////////////////////////////
 $currbg = switch_bgcolor($currbg);
 start_table_row();
  do_option_info_cell("Question", "", $currbg, "25%");
  start_table_cell($currbg);
   do_inputtext("form_options[question]", $form_options['question'], $form_options_error['question'], 65);
  end_table_cell();
 end_table_row();




 //// Answer Choices /////////////////////////
 $currbg = switch_bgcolor($currbg);
 start_table_row();
  do_option_info_cell("Answer Choices", "The answer choices for this question.<br><br>Check the box to the left of the answer choice to mark is as a correct answer choice.<br><br>The order field determines where that answers will appear (i.e. placing a 5 in the order for an answer choice will make it always be the 5th answer choice). Answer choices with their answer order left blank will appear in random order..", $currbg, "25%");

  start_table_cell($currbg); 
 
  echo "<table cellpadding=0 cellspacing=0 border=0\">";

  start_table_cell();
  space();
  end_table_cell();

  start_table_cell();
  echo "<small>Correct</small>";
  end_table_cell();

  start_table_cell();
  space();
  end_table_cell();

  start_table_cell();
  echo "<small>Answer</small>";
  end_table_cell();

  start_table_cell();
  echo "<small>Order</small>";
  end_table_cell();

  start_table_cell();
  space();
  end_table_cell();

  start_table_cell();
  echo "<small>Delete</small>";
  end_table_cell();


 end_table_row();

 ////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////
 // Show answer forms
 ////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////
 

 $actr=1;
 $nactr=1;
 
 // Do a loop for answer that already exists in the database
 @reset($answers);
 while(@list($i, $value) = @each($answers))
 {
	start_table_row();
	start_table_cell($currbg);
	echo "<small>$actr</small>";
	end_table_cell();
	start_table_cell($currbg, "", "center");
	do_radio("form_options[correct_answer]", $actr, $form_options[correct_answer]);
	end_table_cell();

	start_table_cell();
	space();
	end_table_cell();

	start_table_cell($currbg);
	do_inputtext("answers[$i]", $answers[$i], $answers_error[$i], 40);
	end_table_cell();
	start_table_cell($currbg);
	do_inputtext("answers_order[$i]", $answers_order[$i], $answers_order_error[$i], 1);
	end_table_cell();

	start_table_cell();
	space();
	end_table_cell();
	start_table_cell($currbg, "", "center");
	do_checkbox("answers_delete[$i]", 1, $answers_delete[$i]);
	end_table_cell();

	end_table_row();
	$actr++;
			
 } // end while


 // Do a loop for each new answer
 @reset($new_answers);
 while(@list($i, $value) = @each($new_answers))
 {

	start_table_row();
	start_table_cell($currbg);
	 echo "<small>$actr</small>";
	end_table_cell();
	start_table_cell($currbg, "", "center");
	 do_radio("form_options[correct_answer]", $actr, $form_options[correct_answer]);
	end_table_cell();

	start_table_cell();
	 space();
	end_table_cell();

	start_table_cell($currbg);
	 do_inputtext("new_answers[$i]", $new_answers[$i], $new_answers_error[$i], 40);
	end_table_cell();

	start_table_cell($currbg);
	 do_inputtext("new_answers_order[$i]", $new_answers_order[$i], $new_answers_order_error[$i], 1);
	end_table_cell();

	start_table_cell($currbg);
	 space();
	end_table_cell();

	start_table_cell($currbg, "", "center");
	 do_checkbox("new_answers_delete[$i]", 1, $new_answers_delete[$i]);
	end_table_cell();

	end_table_row();
	if(!$new_answers_correct[$i])
		$new_answers_correct[$i] = 0;

	$nactr++;
	$actr++;
		
 } // end while



 // If they submitted the add new answers button, or a premade question
 // button
 if($form_buttons[add_answer_choices] || $form_buttons[truefalse])
 {
	for($i = $nactr; $i <= $form_options[num_new_answers]+$nactr-1; $i++)
	{
		start_table_row();
		start_table_cell($currbg);
		 echo "<small>$actr</small>";
		end_table_cell();
		start_table_cell($currbg, "", "center");
		 do_radio("form_options[correct_answer]", $actr, $form_options[correct_answer]);
		end_table_cell();

		start_table_cell();
		 space();
		end_table_cell();

		start_table_cell($currbg);
		 do_inputtext("new_answers[$i]", $preset_answers[$i], $new_answers_error[$i], 40);
		end_table_cell();
		start_table_cell($currbg);
		 do_inputtext("new_answers_order[$i]", $new_answers_order[$i], $new_answers_order_error[$i], 1);
		end_table_cell();

		start_table_cell("", "", "center");
		 space();
		end_table_cell();
		start_table_cell($currbg, "", "center");
		 do_checkbox("new_answers_delete[$i]", 1, $new_answers_delete[$i]);
		end_table_cell();

		end_table_row();
		
		$actr++;
					
	} // end for

	
} // end if $form_buttons[add_answer_choices]


 // If there are no answers, print that out
 // (since we start $actr at 1, if it's still 1 that means there are no answers)
 if($actr==1)
 {
	start_table_row();
	start_table_cell("", "", "center", "", 7);
	echo "<small>(There are no answer choices for this question)</small>";
	end_table_cell();
	end_table_row();
 }

 // else show the del button
 else
 {
	start_table_row();
	start_table_cell($currbg, "", "", "", 6);
	space();
	end_table_cell();

	start_table_cell($currbg);
	do_submitbutton("form_buttons[del_answer_choices]", "Del");
	end_table_cell();
	end_table_row();
 }
	

 end_table();

 // option to add more answer choiecs
 // only show this if there are less than 9 answer choices existing
 if($actr < 9)
 {
	br(2);

	echo "Add ";
	for($i=1;$i<=9-$actr;$i++)
		$temp[$i] = $i;
 
	 do_select_from_array("form_options[num_new_answers]", "", $temp, "input");
	unset($temp);

	echo " new answer(s) ";

	space();

	do_submitbutton("form_buttons[add_answer_choices]", "Go");

 } // end if $actr < 9

 end_table_cell();
 end_table_row();

 ///////////////////////////////////////////////
 // answer notes
 ///////////////////////////////////////////////
 $currbg = switch_bgcolor($currbg);
 start_table_row();
  do_option_info_cell("Answer Notes", "Notes about the correct answer that will be displayed after the player answers the question. This could be used to give the player more information about the answer or explain why it is the correct answer. Leave this field blank if you do not want to include any notes for this question.", $currbg);
  start_table_cell($currbg);
   do_inputtext("form_options[answer_notes]", $form_options[answer_notes], $form_options_error[answer_notes], 65);
  end_table_cell();
 end_table_row();

 $currbg = switch_bgcolor($currbg);
 start_table_row();
  start_table_cell($currbg, "", "center", "", 2);

  // if it's a new question, the button should say "Add Question";
  if($form_options[id] == "new")
	$action = "Add Question";
  // else it should be save changes
  else
	$action = "Save Changes";

  do_submitbutton("form_buttons[submit]", $action);

  space(2);
  do_resetbutton();

 end_table(2);

} // end function

function save_changes()
{
 global $db;
 global $answers, $answers_correct, $answers_order;
 global $new_answers, $new_answers_correct, $new_answers_order;
 global $form_options;

 // This function takes everything from the input form and inserts it into
 // The database

 // If it's a new question that has never been inserted, we need to do an
 // insert query
 
 if($form_options[id] == "new")
 {
 
 	$form_options[id] = $db->insert_from_array(array(	'question'	=> $form_options[question],
								'category_id'	=> $form_options[category_id],
								'answer_notes'	=> $form_options[answer_notes]), ts_questions);
 } // end if
 
 // else it already exists, we'll just do an update 
 else
 {   
 	$db->update_from_array(array(	'question'	=> $form_options[question],
					'category_id'	=> $form_options[category_id],
					'answer_notes'	=> $form_options[answer_notes]), ts_questions, $form_options[id]);
 }
  
 // This loop is for answers that are already in the database and the index
 // on the array ($i) matches their ID number in the database, so all we have
 // to do is an update

 $is_correct=0;
 $actr=1;
 
 @reset($answers);
 while(@list($i, $value) = @each($answers))
 {
	if(!$answers_correct[$i]) $answers_correct[$i] = 0;
	if($form_options[correct_answer] == $actr)
		$is_correct = 1;
	else
		$is_correct = 0;

	$db->update_from_array(array(	'answer'	=> $answers[$i],
					'correct'	=> $is_correct,
					'answer_order'	=> $answers_order[$i]), ts_answers, $i);
	$actr++;

 } // end while


 // This is a loop for all of the new answers they have added that have not
 // yet been written to the database. They need to be inserted and assigned
 // ID numbers

 @reset($new_answers);
 while(@list($i, $value) = @each($new_answers))
 {
	if(!$new_answers_correct[$i])
		$new_answers_correct[$i] = 0;

	if(!$new_answers_order[$i])
		$new_answers_order[$i] = "0";

        // if this is the answer number they selected to be correct
        if($form_options[correct_answer] == $actr)
                $is_correct = 1;
	else
		$is_correct = 0;

	$answer_id = $db->insert_from_array(array(	'question_id'	=> $form_options[id],
							'answer'	=> $new_answers[$i],
							'correct'	=> $is_correct,
							'answer_order'	=> $new_answers_order[$i]), ts_answers);
	$answers[$answer_id] = $new_answers[$i];
	$answers_correct[$answer_id] = $new_answers_correct[$i];
	$answers_order[$answer_id] = $new_answers_order[$i];
	unset($new_answers[$i]);
	unset($new_answers_correct[$i]);
	unset($new_answers_order[$i]);

	$actr++;
	  
 } // end while

} // end function

function check_input()
{
 global $db;
 global $answers,$answers_correct,$answers_order;
 global $new_answers, $new_answers_correct, $new_answers_order;

 global $answers_error,$answers_order_error,$new_answers_error,$new_answers_order_error;
 global $HTTP_POST_VARS, $form_options, $form_options_error;


 // Check for a question, make sure there's text, not just blank spaces or nothing
 if(is_blank($form_options['question']))
 {
		$errors[] = "The Question is blank!";
		$form_options['question'] = "";
		$form_options_error[question] = 1;
 }

 // check for maximum length on the question
 if(strlen($form_options['question']) > TS_QUESTION_MAX_LENGTH)
 {
	$errors[] = "The Question is too long, it cannot exceed " . TS_QUESTION_MAX_LENGTH . " characters.";
	$form_options_error[question] = 1;
 }
 

 // get total number of answer choices
 $total_answers = count($answers) + count($new_answers);

 // For each existing answer (that is, ones already in the database)
 @reset($answers);
 while(@list($i, $value) = @each($answers))
 {
 	$actr++;
	if(is_blank($answers[$i]))
	{
		$errors[] = "Answer $actr is blank!";
		$answers[$i] = ""; 
         
		$answers_error[$i] = 1;
	}


	if(strlen($answers[$i]) > TS_ANSWER_MAX_LENGTH)
	{
		$errors[] = "Answer $actr is too long, it cannot exceed " . TS_ANSWER_MAX_LENGTH . " characters.";
		$answers_error[$i] = 1;
	}

	if((int)$answers_order[$i] > $total_answers)
	{
		$errors[] = "Order for answer $actr is too high, it must be not be more than the number of answer
			choices you have ($total_answers)";

               
               	$answers_order_error[$i] = 1;
	}

	if($answers_order[$i] != NULL && $answers_order[$i] < 1)
	{
		$errors[] = "Order for answer $actr cannot be less than 1";
		$answers_order_error[$i] = 1;
	}

	if(!is_num($answers_order[$i]))
	{
		$errors[] = "Order for answer $actr must be a number";

		 // Mark this field as having an error
		$answers_order_error[$i] = 1;
	}
	if(@in_array($answers_order[$i], $used_answer_orders))
	{
		$errors[] = "Order for answer $actr ($answers_order[$i]) is already being used by another
		answer choice. Two answer choices cannot have the same order.";

		$answers_order_error[$i] = 1;
	}
	if($answers_correct[$i] == 1)
	{
		$found_correct = 1;
	}
	if($answers_order[$i])
	{
		$used_answer_orders[] = $answers_order[$i];
	}

 } // end while
 

 // For each new answer
 @reset($new_answers);
 while(@list($i,$value) = @each($new_answers))
 {

	$actr++;

 
 	if(is_blank($new_answers[$i]))
	{
		$errors[] = "Answer $actr is blank!";
		$new_answers[$i] = "";
		$new_answers_error[$i] = 1;
	}
	
	if(strlen($new_answers[$i]) > TS_ANSWER_MAX_LENGTH)
	{
		$errors[] = "Answer $actr is too long, it cannot exceed " . TS_ANSWER_MAX_LENGTH . " characters";
		$new_answers_error[$i] = 1;
	}

	if((int)$new_answers_order[$i] > $total_answers)
	{
		$errors[] = "Order for answer $actr is too high, it must be not be more than the number of answer
			choices you have ($total_answers)";
		$new_answers_order_error[$i] = 1;
	}
	if($new_answers_order[$i] != NULL && $new_answers_order[$i] < 1)
	{

		$errors[] = "Order for answer $actr cannot be less than 1";
		$new_answers_order_error[$i] = 1;
	}

	if(!is_num($new_answers_order[$i]))
	{
		$errors[] = "Order for answer $actr must be a number";
		$new_answers_order_error[$i] = 1;
	}
	if(@in_array($new_answers_order[$i], $used_answer_orders))
	{
		$errors[] = "Order for answer $actr ($new_answers_order[$i]) is already being used by another
		answer choice. Two answer choices cannot have the same order.";

		$new_answers_order_error[$i] = 1;
	}
	if($new_answers_order[$i])
	{
		$used_answer_orders[] = $new_answers_order[$i];
	}

 } // end while


 // Make sure there were actually some questions
 // if $actr = 0 there are none since it was never incremented...
 if(!$actr)
 {
	$errors[] = "There are no answer choices for this question!";

 }
 // If they didn't select a correct answer, let them know
 elseif(!$form_options[correct_answer])
 {
	$errors[] = "You didn't select a correct answer choice!";
 }

 return $errors;
  
} // end function check_input

function delete_answers()
{

 // This function deletes any answers they selected to be deleted.
 // It unsets the variables so they won't be reprinted in the form and
 // deletes any existing answers from the database.

 global $db;
 global $answers,$answers_order,$answers_correct,$answers_delete;
 global $new_answers,$new_answers_order,$new_answers_correct,$new_answers_delete;

 // If they selected any existing answers to be deleted
 @reset($answers_delete);
 while(@list($i, $value) = @each($answers_delete))
 {
	$db->query("DELETE FROM ts_answers WHERE id=$i");
	unset($answers[$i]);
	unset($answers_order[$i]);
	unset($answers_correct[$i]);
	unset($answers_delete[$i]);
	$num_deleted++;

 } // end while

 
 // for any new, not saved answers they selected
 @reset($new_answers_delete);
 while(@list($i, $value) = @each($new_answers_delete))
 {

	unset($new_answers[$i]);
	unset($new_answers_order[$i]);
	unset($new_answers_correct[$i]);
	unset($new_answers_delete[$i]);
	$num_deleted++;
	
 } // end while


 // more than one answer deleted
 if($num_deleted > 1)
	$message = "Deleted <b>$num_deleted</b> answer choices.";

 // just one answer deleted
 elseif($num_deleted)
	$message = "Deleted <b>1</b> answer choice.";

 // no answers selected. why did they click the del button? who knows.
 else
	$message = "Couldn't delete any answer choiecs - you didn't select any!";

 return $message;

} // end function delete_answer
 
function new_question_form()
{
	global $db, $form_options, $ts_user;

	$currbg = switch_bgcolor($currbg);

	start_form();
 	do_inputhidden("form_options[id]", "new");
	start_form_table();
	do_table_header("<b>Create Question</b>", 2);

	start_table_row();
	start_table_cell($currbg);

	echo "Category: ";
   
	$query = "SELECT id,name FROM ts_categories ORDER BY name ASC";
   
	do_select_from_query("form_options[category_id]", $form_options['category_id'], $query, "name", "id");
	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg);

	echo "Add a new ";

	$temp = array('Custom'=>'Custom', 'All of the Above'=>'All of the Above', 'None of the Above'=>'None of the Above');
	do_select_from_array("form_options[type]", "Custom", $temp, "input");
	unset($temp);

	echo " question with ";
	for($i=1;$i<=8;$i++)
		$temp[$i] = $i;

	do_select_from_array("form_options[num_new_answers]", 3, $temp, "input");
	unset($temp);

	echo " answer choices ";

	do_submitbutton("form_buttons[add_answer_choices]", "Go");
		
	end_table_cell();
	end_table_row();

	start_table_row();
	start_table_cell($currbg);
	echo "Create a true/false question";
	space();
	do_submitbutton("form_buttons[truefalse]", "Go");
	end_table_cell();
	end_table_row();
	end_table(2);
	end_form();

} // end function new_question_form

?>
