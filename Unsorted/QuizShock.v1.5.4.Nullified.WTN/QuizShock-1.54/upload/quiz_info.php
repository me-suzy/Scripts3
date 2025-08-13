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

require("script_ext.inc");
require("global" . $script_ext);
game_session_clean_up();
check_high_score_reset();

show_header();
if( !$OPTIONS['TRIVIA_ONLINE'] && !$ts_user->is_admin() )
{
	t_error_out($OPTIONS['OFFLINE_MESSAGE']);
}

// switched to "quiz_id"
$game_id = get_global_input("quiz_id");
$fn = get_global_input("fn");
if( !$game_id || ereg("[^0-9]", $game_id) )
{
	t_error_out("Invalid quiz specified!");
}

$result = $db->query("SELECT ts_games.* FROM ts_games WHERE ts_games.id=$game_id");
if( !$db->num_rows($result) )
{
	t_error_out("The quiz you specified does not exist.");
}

switch( $fn )
{

	case 'show_high_scores':
		$result = $db->query("SELECT name,num_high_scores FROM ts_games WHERE id=$game_id");
		$game_info = $db->fetch_array($result);

		$main = new ts_template('HIGH_SCORE_LIST');

		$main->set("TRIVIA_MAIN_PAGE_LINK", $TS_SCRIPTS['INDEX']);

		$main->set("GAME_INFO_PAGE_LINK", $TS_SCRIPTS['GAME_INFO'] . "?quiz_id=$game_id");

		$main->set("TRIVIA_GAME_NAME", $game_info['name']);
		$main->set("NUM_HIGH_SCORES", $game_info['num_high_scores']);
/*		$result = $db->query("SELECT ts_users.username, ts_users.id, ts_game_sessions.score, ts_game_sessions.end_time
					FROM ts_users, ts_game_sessions 
					WHERE ts_users.id=ts_game_sessions.user_id 
					AND ts_game_sessions.game_id=$game_id 
					AND ts_game_sessions.game_type=" . TS_GAME_TYPE_NORMAL . " 
					AND ts_game_sessions.end_type=" . TS_GAME_END_TYPE_NORMAL . " 
					ORDER BY ts_game_sessions.score DESC LIMIT 0,$game_info[num_high_scores]");
	*/
	
		$result = get_high_scores($game_id, $game_info['num_high_scores']);

		$total_scores = 0;
		$high_scores = "";
		while($row = $db->fetch_array($result))
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
		$row = $db->fetch_array($result);

		$main->set("TRIVIA_MAIN_PAGE_LINK", $TS_SCRIPTS['INDEX']);

		$main->set("TRIVIA_GAME_NAME", $row['name']);
		$main->set("TRIVIA_GAME_DESCRIPTION", $row['description']);

		$main->set("TRIVIA_GAME_QUESTIONS_PER_SESSION", $row['questions_per_session']);
		$main->set("TRIVIA_GAME_QUESTION_TIME_LIMIT", $row['question_time_limit']);

		$main->set("TRIVIA_GAME_CORRECT_POINTS", $row['correct_points']);
		$main->set("TRIVIA_GAME_INCORRECT_PENALTY", $row['incorrect_penalty']);

		$main->set("TRIVIA_GAME_NUM_PLAYS", $row['plays']);

		//$result2 = $db->query("SELECT ts_game_sessions.score, ts_users.username FROM ts_game_sessions,ts_users WHERE ts_game_sessions.user_id=ts_users.id AND game_type=1 AND end_type=1 AND game_id=$game_id ORDER BY score DESC LIMIT 1");

		$result2 = get_high_scores($game_id, 1);

		if($db->num_rows($result2))
		{
			$row2 = $db->fetch_array($result2);
			$main->set("SCORE", $row2['score']);
			$main->set("USERNAME", $row2['username']);
		}
		else
		{
			$main->set("SCORE", '(none)');
			$main->set("USERNAME", '(none)');
		}
/*		$result2 = $db->query("SELECT ts_users.username, ts_users.id 
					FROM ts_users, ts_game_sessions 
					WHERE ts_users.id=ts_game_sessions.user_id 
					AND ts_game_sessions.game_id=$game_id 
					AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
*/
					
		$result2 = get_users_playing($game_id);

		while($row2 = $db->fetch_array($result2))
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
			$main->set("USER_LIST", "<small>-</small>");
		}

		$last_played_by = ( get_last_user_played($game_id) ) ? get_last_user_played($game_id) : '(Never taken)';

		$main->set('TRIVIA_GAME_LAST_PLAYED_BY', $last_played_by['username']);
		$main->set('TRIVIA_GAME_LAST_PLAYED_BY_PROFILE_LINK', get_profile_link($last_played_by['id']));
		$main->set("PLAY_LINK", $TS_SCRIPTS['PLAY'] . "?quiz_id=$row[id]");
		$main->set("HIGH_SCORE_LINK", $TS_SCRIPTS['GAME_INFO'] . "?fn=show_high_scores&quiz_id=$row[id]");

		$main->show();

	break;

} // end switch

show_footer();

?>
