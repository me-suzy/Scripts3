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

require('../script_ext.inc');
require('admin_global' . $script_ext);

show_cp_header();

show_section_info("Browse Questions", "View questions that are currently in the database");

$form_options = get_global_input('form_options');
$form_questions = get_global_input('form_questions');

$query_options = get_global_input('query_options');

if( !isset($query_options['category_id']) )
{
	$query_options['category_id'] = get_global_input('category_id');
}

if( !isset($form_options['checkall']))
{
	$form_options['checkall'] = get_global_input("checkall");
}
if(!num_categories(0))
{
	error_out("Whoops", "There are no question categories in the database. You must first create a question category before you can add or browse questions.");
}
?>

<script language="javascript"> 
var i;

function togglecheckall()
{ 
	if (document.form.checkall.checked == false)
	{ 
		for (i=0; i < document.form.elements.length; i++)
		{ 
			if(document.form.elements[i].type == "checkbox")
				document.form.elements[i].checked = false; 
		} 
	}	 

	if (document.form.checkall.checked == true)
	{ 
		for (i=0; i < document.form.elements.length; i++)
		{ 
			if(document.form.elements[i].type == "checkbox")
				document.form.elements[i].checked = true; 
		} 
	} 
} 

</script> 

<?php
if( $errors = check_categories() )
{
	show_status_message($errors);
	browse_questions();
	exit;
}
if( $form_options['submit_movecategory'] )
	$message = move_category();
if( $form_options['submit_delquestion'] )
	$message = del_question();
if($message)
{
	show_status_message($message);
}

browse_questions();

show_cp_footer();


function browse_questions()
{
	global $form_questions, $form_options, $query_options, $db, $ts_user, $TS_SCRIPTS;

	start_form();
	start_form_table();
	if( !$query_options['page'] )
	{
	 	$query_options['page'] = 1;
	}
	if( !$query_options['records_per_page'] )
	{
		$query_options['records_per_page'] = 25;
	}

	if( !$query_options['sortby'] )
	{
	 	$query_options['sortby'] = 'Category';
	}

	$currbg = switch_bgcolor($currbg);

	start_table_cell($currbg, "", "center", "", 4);

	echo "Show ";
	do_select_from_array("query_options[records_per_page]", $query_options['records_per_page'], array(25=>25, 50=>50, 100=>100), "input");
	echo " questions, sorted by ";
	do_select_from_array("query_options[sortby]", $query_options['sortby'], array('Date Created'=>'Date Created', 'Category'=>'Category'), "input");
	echo " in ";
	do_select_from_array("query_options[order]", $query_options['order'], array('Ascending'=>'Ascending', 'Descending'=>'Descending'), "input");
	echo " order ";
	do_submitbutton("submit", "Go");

	br(2);

	$catquery= "SELECT id,name FROM ts_categories ORDER BY name ASC";

	echo "Show questions from: ";
	do_select_from_query("query_options[category_id]", $query_options['category_id'], $catquery, "name", "id", "input", "All Categories", 0);

	space();

	do_submitbutton("submit", "Go");

	br(2);

	echo "Search for question: ";
	do_inputtext_plain("query_options[question_search]", $query_options[question_search], 40, "input");
	space();
	do_submitbutton("submit", "Go");

	br();
	echo "<small>You can use <b>*</b> for a wildcard search (i.e. <b>*shock</b> would match <b>TriviaShock</b>)</small>";
	space();

	end_table_cell();
	end_table_row();

	 ////////////////////////////////////////////////////////////////
	 ////////////////////////////////////////////////////////////////
	 // Retrieve and display the questions
	 ////////////////////////////////////////////////////////////////
	 ////////////////////////////////////////////////////////////////
	
	$query = new select_query;
 
	$query->add_table('ts_questions');
	$query->add_table('ts_categories');
	$query->add_field("ts_questions.*");
	$query->add_field("ts_categories.name AS category");
 	$query->add_where_clause("ts_questions.category_id=ts_categories.id");
 
	$count_query = new select_query;
 	$count_query->add_table(ts_questions);
	$count_query->add_field("COUNT(*)");
 
	if( $query_options['category_id'] )
	{
		$query->add_where_clause("ts_categories.id=$query_options[category_id]");
		$count_query->add_where_clause("category_id=$query_options[category_id]");
	}
 
	if( $query_options['question_search'] )
	{
	 
		$query_options['question_search'] = ereg_replace("\*", "%", $query_options['question_search']);
		$query->add_where_clause("ts_questions.question LIKE '%$query_options[question_search]%'");
	
		$count_query->add_where_clause("ts_questions.question LIKE '%$query_options[question_search]%'");
	}
 
	switch( $query_options['sortby'] )
	{
		case "Date Created":
 			$query->add_order_by("id");
		break;
	
		case 'Category':
		default:
			$query->add_order_by("ts_categories.name");
		break;	
	}
	if( $query_options['order'] == 'Descending' )
	{
		$query->set_order_type(" DESC");
	}
	else
	{
		$query->set_order_type("ASC");
	}

	$page_offset = $query_options['records_per_page'] * ($query_options['page']-1);

	$query->set_limit($page_offset, $query_options['records_per_page']);
  
	$result = $db->query($query->make());
	$total_questions = $db->query_one_result($count_query->make());
	$currbg = switch_bgcolor($currbg);
 
	start_table_row();

	$checkbox = "<input type=checkbox name=\"checkall\" value=1 class=input onClick=\"togglecheckall();\"";

	if($form_options['checkall'])
	{
		$checkbox .= ' checked';
	}

	$checkbox .= '>';

	do_col_header($checkbox);
	do_col_header('Question');
	do_col_header('Category');
 
	end_table_row();
 
	if( !$db->num_rows($result) )
	{
		start_table_cell($currbg, "", "center", "", 5);
		echo "No questions were found.<br><br>";
		if( $query_options['category_id'] )
		{
			$category_name = get_category_name($query_options['category_id']);
			hlinkb("$TS_SCRIPTS[QUESTION]?id=new&category_id=$query_options[category_id]", "Add Questions to <b>$category_name</b>");
		}
		else
		{
			hlinkb("$TS_SCRIPTS[QUESTION]?id=new", "Add Questions");
		}

	 } // end if !$db->num_rows($result)
 
	 else
	 {
	 	while( $row = $db->fetch_array($result) )
	 	{		
			$id = $row['id'];

			start_table_cell($currbg, "", "center");
			 do_checkbox("form_questions[$id]", 1, $form_questions[$id]);
			end_table_cell();

			start_table_cell($currbg, "75%");
			echo "<a href=\"$TS_SCRIPTS[QUESTION]?id=$row[id]\">$row[question]</a>";

			end_table_cell();
			start_table_cell($currbg, "", "center");
	 		echo $row['category'];
			end_table_cell();

			$currbg = switch_bgcolor($currbg);
			end_table_row();

		 } // end while

		 // this function will create prev/next buttons if there are more questions
		 // than can be shown on one page.
		 do_record_page_nav($query_options, $total_questions, "questions", $TS_SCRIPTS['QUESTION_BR']);

		end_table(2);
		br(2);
		start_form_table();
 
		$currbg = switch_bgcolor($currbg);

		start_table_row();
		start_table_cell($currbg);
		echo "Delete selected questions ";
		do_submitbutton("form_options[submit_delquestion]", "Go");
		end_table_cell();
		end_table_row();
		$currbg = switch_bgcolor($currbg);

		start_table_row();
		start_table_cell($currbg);
		echo "Move selected questions to category: ";
		do_select_from_query("form_options[movecategory]", $form_options['movecategory'], "SELECT id,name FROM ts_categories ORDER BY name ASC", "name", "id", "input");
		space();
		do_submitbutton("form_options[submit_movecategory]", "Go");
		end_table_cell();
		end_table_row();

		end_table(2);

	} // end else
  
	end_table(2);
 
} // end function browse_questions()

function move_category()
{
 // This function changes the category of selected questions

 global $form_options, $form_questions, $db;

 // For each question
 @reset($form_questions);
 while(@list($key,$value) = @each($form_questions))
 {
	if($value)
		$db->query("UPDATE ts_questions SET category_id=$form_options[movecategory] WHERE id=$key");
	$qctr++;
			
 } // end while
	
 // Get the name of the category
 $category_name = $db->query_one_result("SELECT name FROM ts_categories WHERE id=$form_options[movecategory]");
  
 // If we did any questions
 if($qctr)
 {
	if($qctr>1)
		$message = "Moved <b>$qctr</b> questions to <b>$category_name</b>.";
	else
		$message = "Moved <b>1</b> question to <b>$category_name</b>.";
 }
 
 // else they didn't select any questions to change
 else
 	$message = "Couldn't move any questions to <b>$category_name</b>, you didn't select any!";
  

 return $message;
 
} // end function move_category()

function change_level()
{
 // This function changes the level of the selected answers

 global $form_options, $form_questions, $db;
   
 // For each answer they selected
 @reset($form_questions);
 while(@list($key,$value) = @each($form_questions))
 {
	if($value)
		$db->query("UPDATE ts_questions SET level_id=$form_options[changelevel] WHERE id=$key");
	$qctr++;
			
 } // end while
 
 $level_name = get_levelname($form_options[changelevel]);

 // If we did any questions
 if($qctr)
 {
	if($qctr>1)
		$message = "Changed <b>$qctr</b> questions to <b>$level_name</b>.";
	else
		$message = "Changed <b>$qctr</b> question to <b>$level_name</b>.";
 }
 
 // else they didn't select any questions to change
 else
 	$message = "Couldn't change question levels to <b>$level_name</b>, you didn't select any!";

 // Return out message
 return $message;
 
} // end function change_level()

function del_question()
{

 // This function deletes the selected questions from the database

 global $form_options, $form_questions, $db;

 // For each question they selected...
 @reset($form_questions);
 while(@list($key,$value) = @each($form_questions))
 {
	if($value)
		$db->query("DELETE FROM ts_questions WHERE id=$key");
	$qctr++;

 } // end while

 // If we did any questions
 if($qctr)
 {      
	if($qctr>1)
		$message = "Deleted <b>$qctr</b> questions.";
	else
		$message = "Deleted <b>1</b> question.";
 }
 
 // else they didn't select any questions to change
 else
	$message = "You didn't select any questions to delete!";

 // Return out message
 return $message;

} // end function del_question()

function check_categories()
{

 global $form_questions;

 @reset($form_questions);
 while(@list($i,$value) = @each($form_questions))
 {
	$category_id = get_question_category($i);
	$category_name = get_category_name($category_id);
	
	if($game_list = get_games_using_category($category_id))
	{
		$errors = "Unable to modify questions in category <b>$category_name</b>. This question category is currently being used by the following trivia game(s): <b>" . implode(', ', $game_list) . 
			"</b><br><br>In order to make modifications to this question category (by adding or modifying questions), each game using it must be set to offline mode or to maintenance
			mode (you will have to wait for all game sessions to finish if you set it to maintenance mode). You can change the mode
			of trivia games using the <a href=\"$TS_SCRIPTS[GAME_BR]\" class=header1link>game browser</a>.";
			
		break;
	}
 }

 return $errors;

}

?>
