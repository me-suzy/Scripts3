<?php
require("script_ext.inc");
require("global" . $script_ext);
game_session_clean_up();
check_high_score_reset();

show_header();
if( !$OPTIONS['TRIVIA_ONLINE'] && !$ts_user->is_admin() )
{
	t_error_out($OPTIONS['OFFLINE_MESSAGE']);
}

$game_id = get_global_input("game_id");
$fn = get_global_input("fn");
if( !$game_id || ereg("[^0-9]", $game_id) )
{
	t_error_out("Invalid game specified!");
}

$result = $db->query("SELECT $DB_TABLES[GAMES].* FROM $DB_TABLES[GAMES] WHERE $DB_TABLES[GAMES].id=$game_id");
if( !$result->num_rows() )
{
	t_error_out("The game you specified does not exist.");
}

switch( $fn )
{

	case 'show_high_scores':
		$result = $db->query("SELECT name,num_high_scores FROM $DB_TABLES[GAMES] WHERE id=$game_id");
		$game_info = $result->fetch_array();

		$main = new ts_template('HIGH_SCORE_LIST');

		$main->set("TRIVIA_MAIN_PAGE_LINK", $TS_SCRIPTS['INDEX']);

		$main->set("GAME_INFO_PAGE_LINK", $TS_SCRIPTS['GAME_INFO'] . "?game_id=$game_id");

		$main->set("TRIVIA_GAME_NAME", $game_info['name']);
		$main->set("NUM_HIGH_SCORES", $game_info['num_high_scores']);
/*		$result = $db->query("SELECT $DB_TABLES[USERS].username, $DB_TABLES[USERS].id, $DB_TABLES[GAME_SESSIONS].score, $DB_TABLES[GAME_SESSIONS].end_time
					FROM $DB_TABLES[USERS], $DB_TABLES[GAME_SESSIONS] 
					WHERE $DB_TABLES[USERS].id=$DB_TABLES[GAME_SESSIONS].user_id 
					AND $DB_TABLES[GAME_SESSIONS].game_id=$game_id 
					AND $DB_TABLES[GAME_SESSIONS].game_type=" . TS_GAME_TYPE_NORMAL . " 
					AND $DB_TABLES[GAME_SESSIONS].end_type=" . TS_GAME_END_TYPE_NORMAL . " 
					ORDER BY $DB_TABLES[GAME_SESSIONS].score DESC LIMIT 0,$game_info[num_high_scores]");
	*/
	
		$result = get_high_scores($game_id, $game_info['num_high_scores']);

		$total_scores = 0;
		$high_scores = "";
		while($row = $result->fetch_array())
		{
			$total_scores++;
			$high_score = new ts_template("HIGH_SCORE");
			$high_score->set("RANK", $total_scores);
	
			$high_score->set("USERNAME", $row['username']);
			$high_score->set("PROFILE_LINK", get_profile_link($row['id']));
	
			$high_score->set("SCORE", $row[score]);
			$high_score->set("DATETIME", get_date($row['end_time']) . " at " . get_time($row['end_time']));
			$high_scores .= $high_score->dump();

		} // end while

		$num_blank_scores = $game_info['num_high_scores'] - $total_scores;

		for($i=0;$i<$num_blank_scores;$i++)
		{
			$high_score_none = new ts_template("HIGH_SCORE_NONE");
			$high_score_none->set("RANK", $total_scores + $i + 1);

			$high_scores .= $high_score_none->dump();

		} // end for
		$main->set("HIGH_SCORES", $high_scores);

		$main->show();

	break;
	
	default:
		$main = new ts_template("GAME_INFO_PAGE");
		$row = $result->fetch_array();

		$main->set("TRIVIA_MAIN_PAGE_LINK", $TS_SCRIPTS['INDEX']);

		$main->set("TRIVIA_GAME_NAME", $row['name']);
		$main->set("TRIVIA_GAME_DESCRIPTION", $row['description']);

		$main->set("TRIVIA_GAME_QUESTIONS_PER_SESSION", $row['questions_per_session']);
		$main->set("TRIVIA_GAME_QUESTION_TIME_LIMIT", $row['question_time_limit']);

		$main->set("TRIVIA_GAME_CORRECT_POINTS", $row['correct_points']);
		$main->set("TRIVIA_GAME_INCORRECT_PENALTY", $row['incorrect_penalty']);

		$main->set("TRIVIA_GAME_NUM_PLAYS", $row['plays']);
		$result2 = $db->query("SELECT $DB_TABLES[GAME_SESSIONS].score, $DB_TABLES[USERS].username FROM $DB_TABLES[GAME_SESSIONS],$DB_TABLES[USERS] WHERE $DB_TABLES[GAME_SESSIONS].user_id=$DB_TABLES[USERS].id AND game_type=1 AND end_type=1 AND game_id=$game_id ORDER BY score DESC LIMIT 1");
		if($result2->num_rows())
		{
			$row2 = $result2->fetch_array();
			$main->set("SCORE", $row2['score']);
			$main->set("USERNAME", $row2['username']);
		}
		else
		{
			$main->set("SCORE", '(none)');
			$main->set("USERNAME", '(none)');
		}
/*		$result2 = $db->query("SELECT $DB_TABLES[USERS].username, $DB_TABLES[USERS].id 
					FROM $DB_TABLES[USERS], $DB_TABLES[GAME_SESSIONS] 
					WHERE $DB_TABLES[USERS].id=$DB_TABLES[GAME_SESSIONS].user_id 
					AND $DB_TABLES[GAME_SESSIONS].game_id=$game_id 
					AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);*/
					
		$result2 = get_users_playing($game_id);

		while($row2 = $result2->fetch_array())
		{
			$user_playing = new ts_template('USER_PLAYING');
			$user_playing->set('PROFILE_LINK', get_profile_link($row2['id']));
			$user_playing->set('USERNAME', $row2['username']);
			$user_list[] = $user_playing->dump();
		}

		$main->set("NUM_PLAYERS", count($user_list));
		if(count($user_list))
		{
			$main->set("USER_LIST", @implode(", ", $user_list));
		}
		else
		{
			$main->set("USER_LIST", "<small>(There are no users currently playing this game)</small>");
		}


		$main->set("PLAY_LINK", $TS_SCRIPTS['PLAY'] . "?game_id=$row[id]");
		$main->set("HIGH_SCORE_LINK", $TS_SCRIPTS['GAME_INFO'] . "?fn=show_high_scores&game_id=$row[id]");

		$main->show();

	break;

} // end switch

show_footer();

?>
