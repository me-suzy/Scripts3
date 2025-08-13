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

require('script_ext.inc');
require('global' . $script_ext);

$fn = get_global_input('fn');
check_high_score_reset();
game_session_clean_up();
show_header();

switch($fn)
{

	case 'show_faq':
		$faq = new ts_template('FAQ');
		$faq->set('TRIVIA_MAIN_PAGE_LINK', $TS_SCRIPTS['INDEX']);
		$faq->show();

	break;
	
	
	default:
		$main = new ts_template('TRIVIA_MAIN_PAGE');

		$main->set('FAQ_LINK', $TS_SCRIPTS['INDEX'] . "?fn=show_faq");
		$main->set('REGISTER_LINK', get_register_link());
		$main->set('REGISTERED_USERS', get_registered_users());
		$main->set('NEWEST_USER', get_newest_user());
		$main->set('GAMES_PLAYED', (int)$db->query_one_result("SELECT SUM(plays) FROM ts_games"));
		$main->set('USERS_PLAYING', $db->query_one_result("SELECT COUNT(*) FROM ts_game_sessions WHERE end_type=" . TS_GAME_END_TYPE_NOT_ENDED));
		$result = $db->query("SELECT ts_game_sections.name AS game_section_name,
					ts_game_sections.description AS game_section_description, 
					ts_game_sections.id AS game_section_id, ts_games.* 
					FROM ts_games, ts_game_sections 
					WHERE ts_games.mode=" . TS_GAME_MODE_ONLINE . " 
					AND ts_games.game_section_id = ts_game_sections.id 
					ORDER BY ts_game_sections.section_order, ts_game_sections.id, ts_games.game_order, ts_games.id ASC");
		while($row = $db->fetch_array($result))
		{
			if($row['game_section_id'] != $last_section_id)
			{
				if( $row['game_section_id'] )
				{
					$game_section = new ts_template("GAME_SECTION");
		
					$game_section->set("GAME_SECTION_NAME", $row['game_section_name']);
					$game_section->set("GAME_SECTION_DESCRIPTION", $row['game_section_description']);
		
					$game_list .= $game_section->dump();
		
					$last_section_id = $row['game_section_id'];
				}

			} // end if
			$game = new ts_template('GAME_LISTING');
			$game->set('TRIVIA_GAME_NAME', $row['name']);
			$game->set('TRIVIA_GAME_INFO_LINK', $TS_SCRIPTS['GAME_INFO'] . "?quiz_id=$row[id]");
			$game->set('TRIVIA_GAME_ID', $row['id']);
	
			$game->set('TRIVIA_GAME_DESCRIPTION', $row['description']);
			$game->set('TRIVIA_GAME_NUM_QUESTIONS', $row['questions_per_session']);
			$game->set('TRIVIA_GAME_NUM_PLAYS', $row['plays']);
			$result2 = get_high_scores($row['id'], 1);
			if($db->num_rows($result2))
			{
				$row2 = $db->fetch_array($result2);
				$current_high_score = new ts_template('CURRENT_HIGH_SCORE');
				$current_high_score->set('SCORE', $row2['score']);
				$current_high_score->set('PROFILE_LINK', get_profile_link($row2['id']));
				$current_high_score->set('USERNAME', $row2['username']);
				$game->set('CURRENT_HIGH_SCORE', $current_high_score->dump());

			} // end if
			else
			{
				$current_high_score_none = new ts_template('CURRENT_HIGH_SCORE_NONE');
				$game->set("CURRENT_HIGH_SCORE", $current_high_score_none->dump());
			}
	
			$game_list .= $game->dump();
	
		} // end while
		$main->set('GAME_LIST', $game_list);
		$main->show();

	break;
	
} // end switch

show_footer();

?>
