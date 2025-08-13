<?php
require("../script_ext.inc");
require("admin_global" . $script_ext);

check_high_score_reset();

show_cp_header();
show_section_info("Browse Trivia Games", "View and administrate existing trivia games.");
if( !num_categories(0) )
{
	error_out("Whoops", "There are no question categories in the database. You must first create a question category and add questions to it before you can create or edit trivia games.");
}

$form_options			= get_global_input("form_options");
$form_games_order		= get_global_input("form_games_order");
$form_sections_order		= get_global_input("form_sections_order");
$form_options['section_id']	= get_global_input("section_id");
$fn				= get_global_input("fn");

if( !isset($form_options['mode']) )
{
	$form_options['mode'] = get_global_input("mode");
}
	
if( !isset($form_options['id']) )
{
	$form_options['id'] = get_global_input("id");
}

if( get_global_input("update_orders") )
{
	$fn = "update_orders";
}

if( is_valid_id($form_options['id']) )
{
	if( !game_exists($form_options['id']) )
	{
		error_out("Whoops", "The game you specified does not exist. If you followed a link to this game, it is possible that the game has been deleted since the page that referred you was refreshed");
	}

}

switch( $fn )
{

	case 'delete_game':
	
		if( game_in_use($form_options['id']) )
		{
			$game_name = get_game_name($form_options['id']);
	
			error_out("Whoops", "Unable to delete game <b>$game_name</b>. It appears that this game is currently in use. This means that it is either online
				or it is in maintenance mode with game sessions still in progress. To delete this game, you must either set
				it to offline mode or set it to maintenance mode and wait until all game sessions are finished. You can
				change the mode of this game via the " . hlink($TS_SCRIPTS['GAME_BR'], "game browser", 1) . ".");

		} // end if
	
		delete_game();
	break;


	case 'delete_section':
	
		delete_section();
		browse_games();
	
	break;
	
	case 'set_mode':
		if( $form_options['mode'] )
		{
			set_mode();
		}
		else
		{
			show_status_message("Please select a game mode to change to.");
			browse_games();
		}

	break;

	case 'reset_scores':
	
		reset_scores();

	break;

	case 'reset_logs':
	
		if( game_in_use($form_options['id']) )
		{
			$game_name = get_game_name($form_options['id']);
	
			error_out("Whoops", "Unable to reset game logs for <b>$game_name</b>. It appears that this game is currently in use. This means that it is either online
				or it is in maintenance mode with game sessions still in progress. To reset the game logs for this game, you must either set
				it to offline mode or set it to maintenance mode and wait until all game sessions are finished. You can
				change the mode of this game via the " . hlink($TS_SCRIPTS['GAME_BR'], "game browser", 1) . ".");

		} // end if

		reset_logs();
	break;


	case 'update_orders':
	
		update_orders();
		browse_games();
		
	break;

	default:
		browse_games();
}



show_cp_footer();

function browse_games()
{
  
	global $db, $form_options, $ts_user, $DB_TABLES, $TS_SCRIPTS, $OPTIONS;
	$result = $db->query("SELECT * FROM $DB_TABLES[GAME_SECTIONS] ORDER BY section_order, id ASC");

	start_form();
	start_form_table();
		
	while( $section = $result->fetch_array() )
	{
		if( $section['id'] != 0 )
		{
			$section_id = $section['id'];

			$order_input = do_inputtext_plain("form_sections_order[$section_id]", $section['section_order'], 2, "input", 1);

			$links = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"$TS_SCRIPTS[GAME_SECTION]?id=$section_id\" class=header1link>[Edit]</a>"
				 ."&nbsp; <a href=\"$TS_SCRIPTS[GAME_BR]?fn=delete_section&section_id=$section_id\" class=header1link>[Delete]</a>";

			do_table_header("<b>$section[name]</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <small><b>Order:</b></small> $order_input $links", 4);
	
		} // end if
		$result2 = $db->query("SELECT * FROM $DB_TABLES[GAMES] WHERE game_section_id=$section[id] ORDER BY game_order, id ASC");
		
		while( $game = $result2->fetch_array() )
		{
			$game_id = $game['id'];

			$currbg = switch_bgcolor($currbg);
			$game_url = "$OPTIONS[TRIVIA_SITE_URL]/$TS_SCRIPTS[GAME_INFO]?game_id=$game[id]";

			$links = "<small><a href=\"$TS_SCRIPTS[GAME]?id=$game[id]\">[Edit]</a>&nbsp;&nbsp;"
				."<a href=\"$TS_SCRIPTS[GAME_BR]?fn=delete_game&id=$game[id]\">[Delete]</a>&nbsp;&nbsp;&nbsp;&nbsp;"
				."<a href=\"$TS_SCRIPTS[GAME_BR]?fn=reset_scores&id=$game[id]\">[Reset High Scores]</a>&nbsp;&nbsp;"
				."<a href=\"$TS_SCRIPTS[GAME_BR]?fn=reset_logs&id=$game[id]\">[Reset Game Logs]</a>&nbsp;&nbsp;&nbsp;&nbsp;";

			switch( $game['mode'] )
			{
				case TS_GAME_MODE_ONLINE:
					$mode = "ONLINE";
				break;

				case TS_GAME_MODE_OFFLINE:
					 $mode = "OFFLINE";
				break;

				case TS_GAME_MODE_MAINTENANCE:
					$mode = "MAINTENANCE MODE";
				break;
			}


			start_table_row();
			start_table_cell($currbg);
			echo "<small><b>[$mode]</b></small>";
			end_table_cell();
	
			start_table_cell($currbg);
			echo "<b><u>$game[name]</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <small>Order:</small></b> ";

			echo do_inputtext_plain("form_games_order[$game_id]", $game['game_order'], 2, "input", 1);
	
			echo "<br><small>$game[description]</small>";
			echo "<br><a href=\"$game_url\" target=\"_blank\">$game_url</a>";
			end_table_cell();
	
			start_table_cell($currbg, "", "center");
			echo $links;
			echo "<br><b>Set Mode To:</b> ";
			space(2);
			
			echo "<a href=\"$TS_SCRIPTS[GAME_BR]?fn=set_mode&id=$game[id]&mode=" . TS_GAME_MODE_ONLINE . "\">[Online]</a>";
			space(2);
			echo "<a href=\"$TS_SCRIPTS[GAME_BR]?fn=set_mode&id=$game[id]&mode=" . TS_GAME_MODE_OFFLINE . "\">[Offline]</a>";
			space(2);
			echo "<a href=\"$TS_SCRIPTS[GAME_BR]?fn=set_mode&id=$game[id]&mode=" . TS_GAME_MODE_MAINTENANCE . "\">[Maintenance]</a>";
			
			end_table_cell();


		} // end while $game


	} // end while $section

	end_table(2);

	br(2);

	start_form_table();
	do_table_header("<b>Update Orders:</b> " . do_submitbutton("update_orders", "Go", "button", 1));
	end_table(2);
 
	end_form();
 
} // end function browse_games()
 
function delete_game()
{

	global $db, $form_options, $DB_TABLES;
	$game_name = get_game_name($form_options[id]);
	if( !isset($form_options['go']) )
	{
		$currbg = switch_bgcolor($currbg);
		start_form();
		start_form_table();
		start_table_row();
		start_table_cell($currbg, "40%", "center");
		echo "Are you sure you want to delete the game <b>$game_name</b>? This will also delete all high scores and game logs for this game.";
		br(2);
		do_inputhidden("fn", "delete_game");
		do_inputhidden("id", $form_options['id']);

		do_submitbutton("form_options[go]", "Yes");
		space(2);
		do_submitbutton("form_options[go]", "No");
		end_table_cell();
		end_table_row();
		end_table(2);
		end_form();
		
	} // end if
	elseif( $form_options['go'] == "Yes" )
	{
 
		interrupt_active_game_sessions($form_options['id']);
		delete_game_question_cache($form_options['id']);
		$db->query("DELETE FROM $DB_TABLES[GAMES] WHERE id=$form_options[id]");
		$db->query("DELETE FROM $DB_TABLES[GAME_CATS] WHERE game_id=$form_options[id]");
		$db->query("DELETE FROM $DB_TABLES[GAME_SESSIONS] WHERE game_id=$form_options[id]");

		show_status_message("<b>$game_name</b> was successfully deleted.");

		browse_games();

	} // end elseif
	else
	{
		browse_games();
	}

} // end function delete_game()
function reset_scores()
{
	global $db, $form_options;
	$game_name = get_game_name($form_options['id']);
	if( !isset($form_options['go']) )
	{
		$currbg = switch_bgcolor($currbg);
		start_form();
		start_form_table();
		start_table_row();
		start_table_cell($currbg, "40%", "center");
		echo "Are you sure you want to reset the high score list for <b>$game_name</b>?";
		br(2);
		do_inputhidden("fn", "reset_scores");
		do_inputhidden("form_options[id]", "$form_options[id]");
		do_submitbutton("form_options[go]", "Yes");
		space(2);
		do_submitbutton("form_options[go]", "No");
		end_table_cell();
		end_table_row();	
		end_table(2);
		end_form();

 	} // end if
	elseif($form_options['go'] == "Yes")		
	{
		reset_game_scores($form_options['id']);

		show_status_message("High score list for <b>$game_name</b> was successfully reset.");

		browse_games();

	} // end elseif
	else
 	{
		browse_games();
	}


} // end function reset_scores()
function reset_logs()
{
 
	global $db, $form_options;
	$game_name = get_game_name($form_options['id']);
	if( !isset($form_options['go']) )
	{
		$currbg = switch_bgcolor($currbg);
		start_form();
		start_form_table();
		start_table_row();
		start_table_cell($currbg, "40%", "center");
		echo "Deleting game logs will reset all high scores and all records of games for <b>$game_name</b>. Are you sure you want to delete the game logs for this game?";
		br(2);
		do_inputhidden("fn", "reset_logs");
		do_inputhidden("id", "$form_options[id]");

		do_submitbutton("form_options[go]", "Yes");
		space(2);
		do_submitbutton("form_options[go]", "No");
		end_table_cell();
		end_table_row();
		end_table(2);
		end_form();
	
	} // end if
	elseif( $form_options['go'] == "Yes" )
	{
		reset_game_logs($form_options[id]);
	
		show_status_message("Game logs for <b>$game_name</b> were successfully reset.");

		browse_games();

	} // end elseif
	else
	{
		browse_games();
	}

} // end function reset_game_logs()

function set_mode()
{
 // changes the mode of a category

 global $db, $DB_TABLES, $form_options;

 // get the name of this game and the questions per session
 $result = $db->query("SELECT name,questions_per_session FROM $DB_TABLES[GAMES] WHERE id=$form_options[id]");
 @list($game_name, $questions_per_session) = $result->fetch_array();

 // number of people currently playing this game
 $num_players = get_num_players($form_options['id']);

 switch($form_options[mode])
 {
	case TS_GAME_MODE_ONLINE:
		$mode = "online";
	break;

	case TS_GAME_MODE_OFFLINE:
		$mode = "offline";
	break;

	case TS_GAME_MODE_MAINTENANCE:
		$mode = "maintenance";
	break;

 }
 
 // if no one is playing, or they're setting it to online, just do it
 if($num_players == 0 || $form_options[mode] == TS_GAME_MODE_ONLINE || $form_options[mode] == TS_GAME_MODE_MAINTENANCE)
 {
	if($form_options[mode] == TS_GAME_MODE_ONLINE || $form_options[mode] == TS_GAME_MODE_MAINTENANCE)
	{
		 // 10 questions total in all the categories they chose).
	
		$total_questions = $db->query_one_result("SELECT COUNT(*) FROM $DB_TABLES[GAME_CATS], $DB_TABLES[QUESTIONS]
							WHERE $DB_TABLES[GAME_CATS].game_id = $form_options[id]
							AND $DB_TABLES[GAME_CATS].category_id = $DB_TABLES[QUESTIONS].category_id");
		if($total_questions >= $questions_per_session)
		{
		 
		 
			delete_game_question_cache($form_options[id]);
			create_game_question_cache($form_options[id]);

			set_game_mode($form_options[mode], $form_options[id]);
			show_status_message("Set mode for <b>$game_name</b> to: <b>$mode</b>");
				
		} // end if
		else
		{
			show_status_message("Unable to set <b>$game_name</b> to <b>online</b>. There are not enough questions in the 
					categories you selected for it. You set <b>Questions Per Session</b> to 
					<b>$questions_per_session</b> questions, but there are only <b>$total_questions</b> 
					questions in the categories you selected. There would not be enough questions for a full game 
					session, since a player cannot be given the same question twice in the same game session. You must 
					first either descrease the questions per session or add questions to the categories used by this 
					game.");
		}
	
	}  // end if TS_GAME_MODE_ONLINE OR MAINTENANCE
	else
	{
		set_game_mode($form_options[mode], $form_options[id]);
		show_status_message("Set mode for <b>$game_name</b> to: <b>$mode</b>");
	}

	browse_games();	

 } // end if


 // else, people are currently playing, verify first.
 else
 {
	 // if they're not sure yet, show them the yes/no form
	 if(!isset($form_options[go]))
	 {
		$num_players = get_num_players($form_options[id]);

		$currbg = switch_bgcolor($currbg);
		start_form();
		start_form_table();
		start_table_row();
		start_table_cell($currbg, "40%", "center");

		echo "There are currently <b>$num_players</b> users playing games.
			Setting the game mode to <b>offline</b> will interrupt and terminate their game sessions. 
			Are you sure you want to set this game to <b>offline</b>?";
	
		br(2);

		  do_inputhidden("fn", "set_mode");
		  do_inputhidden("form_options[mode]", $form_options['mode']);
		  do_inputhidden("form_options[id]", "$form_options[id]");

		  do_submitbutton("form_options[go]", "Yes");
		  space(2);
		  do_submitbutton("form_options[go]", "No");
		 end_table_cell();
		end_table_row();
		end_table(2);
		end_form();
	 }


	 elseif($form_options[go] == "Yes")		
	 {
		interrupt_active_game_sessions($form_options[id]);
	
 
	 	set_game_mode($form_options[mode], $form_options[id]);
		
		show_status_message("Set mode for <b>$game_name</b> to: <b>$mode</b>");

		browse_games();

	 } // end elseif

	 // else (they clicked no), just go to browse mode
	 else
	 {
		browse_games();
	 }

 } // end else


} // end function set_mode()

function update_orders()
{
	global $db, $DB_TABLES, $form_sections_order, $form_games_order;
	@reset($form_sections_order);
	while( @list($key, $value) = @each($form_sections_order) )
	{
		$db->query("UPDATE $DB_TABLES[GAME_SECTIONS] SET section_order='$value' WHERE id='$key'");
	}
	@reset($form_games_order);
	while( @list($key, $value) = @each($form_games_order) )
	{
		$db->query("UPDATE $DB_TABLES[GAMES] SET game_order='$value' WHERE id='$key'");
	}
	
	

	show_status_message("Game and section orders were updated successfully");

} // end function update_orders()

function delete_section()
{
	global $form_options, $db, $DB_TABLES;

	$game_section_name = $db->query_one_result("SELECT name FROM $DB_TABLES[GAME_SECTIONS] WHERE id='$form_options[section_id]'");
	$db->query("DELETE FROM $DB_TABLES[GAME_SECTIONS] WHERE id='$form_options[section_id]'");
	$db->query("UPDATE $DB_TABLES[GAMES] SET game_section_id=0 WHERE game_section_id='$form_options[section_id]'");
	
	show_status_message("Deleted game section: <b>$game_section_name</b>");
	
} // end function delete_section()

?>
