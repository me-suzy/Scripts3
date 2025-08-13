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

show_section_info("Browse Question Categories" , "Here you can browse the question categories in the database.");


$form_options = get_global_input("form_options");
$form_options['id'] = get_global_input("id");

$query_options = get_global_input("query_options");

if(!isset($query_options[page]))
	$query_options[page] = get_global_input("page");

if(get_global_input("fn"))
	$form_options[fn] = get_global_input("fn");
if(is_valid_id($form_options[id]))
{
	$category_name = get_category_name($form_options[id]);
	if($form_options[id] != "new")
	{
		if(!category_exists($form_options[id]))
			error_out("Whoops", "The category you specified does not exist!");
	}

} // end if is_valid_id

switch($form_options[fn])
{

	case 'delete':
		delete_category();
	break;
	default:
		browse_categories();
}


show_cp_footer();

function browse_categories()
{
 // lists each category with links to edit them
 global $OPTIONS, $TS_SCRIPTS, $db, $ts_user;
 global $form_options, $query_options;
 	
 //////////////////////////////////////////////////////////////////////////
 // SHOW QUERY OPTIONS
 //////////////////////////////////////////////////////////////////////////
 
 // create a navigation bar that will allow them to specify what they want
 // to see and in what order

 if(!isset($query_options[records_per_page]))
	$query_options[records_per_page] = 10;

 start_form($TS_SCRIPTS[CATEGORY_BR],"GET");

 $currbg = switch_bgcolor($currbg);
 start_form_table();
  start_table_row();
   start_table_cell($currbg, "", "center");

	echo "Show ";

	do_select_from_array("query_options[records_per_page]", $query_options['records_per_page'], array(10=>10, 25=>25, 50=>50, 100=>100), "input");

	echo " categories, sorted by ";

	$temp['Category Name'] = "Category Name";
	do_select_from_array("query_options[sortby]", $query_options['sortby'], $temp, "input");
	unset($temp);

	echo " in ";

	do_select_from_array("query_options[order]", $query_options['order'], array('Ascending'=>'ASC', 'Descending'=>'DESC'), "input");
	unset($temp);

	echo " order ";

	space();
	do_submitbutton("Go", "Go");

   end_table_cell();
  end_table_row();

 end_table(2);
 br(2);

 //////////////////////////////////////////////////////////////////////////
 // CREATE QUERY
 //////////////////////////////////////////////////////////////////////////

 if(!$query_options[page])
 	$query_options[page] = 1;
 
 // create a query based on what options they selected
 
 $query = new select_query();
 $query->add_table(ts_categories);
 $query->add_field("ts_categories.*");

 $count_query = new select_query();
 $count_query->add_table(ts_categories);
 $count_query->add_field("COUNT(*)");

 $query->add_order_by("ts_categories.name");

 if($query_options[order] == "DESC")
	$query->set_order_type("DESC");
 else
	$query->set_order_type("ASC");

 // what record we should start on. should be the current page * records per page
 $page_offset = ($query_options[page]-1) * $query_options[records_per_page];
 
 $query->set_limit($page_offset, $query_options[records_per_page]);
 
 // get the categories
 $result = $db->query($query->make());

 // get total number of categories
 $total_categories = $db->query_one_result($count_query->make());

 $currbg = switch_bgcolor($currbg);
 
 //////////////////////////////////////////////////////////////////////////
 // DISPLAY CATEGORIES
 //////////////////////////////////////////////////////////////////////////

 // make sure there are some categories to display
 if(!$db->num_rows($result))
 {
 	show_status_message("No categories were found.");
 }
 else
 {
  	start_form_table(); 
 
 	while($row = $db->fetch_array($result))
 	{

		$links = "<small><a href=\"$TS_SCRIPTS[CATEGORY]?id=$row[id]\" class=header1link>[Edit]</a>&nbsp;&nbsp;"
			."<a href=\"$TS_SCRIPTS[CATEGORY_BR]?fn=delete&id=$row[id]\" class=header1link>[Delete]</a>&nbsp;&nbsp;&nbsp;&nbsp;"
			."<a href=\"$TS_SCRIPTS[QUESTION]?id=new&category_id=$row[id]\" class=header1link>[Add Questions]</a>&nbsp;&nbsp;"
			."<a href=\"$TS_SCRIPTS[QUESTION_BR]?category_id=$row[id]\" class=header1link>[Browse Questions]</a>&nbsp;&nbsp;&nbsp;&nbsp;"
			."<a href=\"$TS_SCRIPTS[CATEGORY_EXPORT]?category_id=$row[id]\" class=header1link>[Export to File]</a></small>";

		do_table_header("<b><u>$row[name]</u></b> &nbsp;&nbsp;" . $links, 6);
		start_table_row();
		do_col_header("<small>Description</small>");
		do_col_header("<small>Total Questions</small>");
		end_table_row();

		start_table_row();
		start_table_cell($currbg, "50%");
		echo "<small>$row[description]</small>";
		end_table_cell();

		start_table_cell($currbg);
		 // get number of questions in this category
		 $total_questions = $db->query_one_result("SELECT COUNT(*) FROM ts_questions WHERE category_id=$row[id]");
 		 echo $total_questions;
		end_table_cell();
	
		end_table_row();

		$currbg = switch_bgcolor($currbg);

	 } // end while

 	do_record_page_nav($query_options, $total_categories, "categories", $TS_SCRIPTS[CATEGORY_BR]);
 
  } // end else

 end_table(2);

} // end function browse_categories()

function delete_category()
{
 // asks them if they really want to delete the category, if they click yes
 // it deletes it and all of it's questions, if they click no it goes back
 // to browse mode.

 global $db, $form_options, $query_options;

 // get the name of this category
 $category_name = get_category_name($form_options[id]);

 // if there are any games that are online or in maintenance mode with people still playing
 // do not allow deletion
 if($game_list = get_games_using_category($form_options[id]))
 {
	show_status_message("Unable to delete <b>$category_name</b>. It is currently in use by the following games: " . implode(",", $game_list) . ". In order to delete this game you must set these games to offline or maintenance mode and wait until there are 0 active game sessions");

	browse_categories();
	exit;
 }


 // if they're not sure yet, show them the yes/no form
 if(!isset($form_options[go]))
 {
	$currbg = switch_bgcolor($currbg);
	start_form();
	start_form_table();
	start_table_row();
	 start_table_cell($currbg, "40%", "center");
	  echo "Are you sure you want to delete <b>$category_name</b>? Doing this will delete all questions in this category and the category itself.";
	  br(2);

	  // so we know what function we're performing
	  do_inputhidden("fn", "delete");

	  do_inputhidden("id", "$form_options[id]");

	  // save their query options
	  do_inputhidden_array("query_options", $query_options);
	  
	  do_submitbutton("form_options[go]", "Yes");
	  space(2);
	  do_submitbutton("form_options[go]", "No");
	 end_table_cell();
	end_table_row();
	end_table(2);
	end_form();
 }

 // if they chose yes, get rid of the category
 elseif($form_options[go] == "Yes")
 {
	$db->query("DELETE FROM ts_categories WHERE id=$form_options[id]");
	$result = $db->query("SELECT id FROM ts_questions WHERE category_id=$form_options[id]");
	$db->query("DELETE FROM ts_questions WHERE category_id=$form_options[id]");
	while($row = $db->fetch_array($result))
	{
		$db->query("DELETE FROM ts_answers WHERE question_id=$row[id]");
	}
	$db->query("DELETE FROM ts_game_cats WHERE category_id=$form_options[id]");

	show_status_message("Category <b>$category_name</b> was successfully deleted");

	browse_categories();

 } // end elseif

 // else (they clicked no), just go to browse mode
 else
	browse_categories();


} // end function delete_category()


?>
