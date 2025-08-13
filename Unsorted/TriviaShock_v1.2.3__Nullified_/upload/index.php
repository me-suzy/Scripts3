<?php
require("script_ext.inc");
require("global" . $script_ext);

$fn = get_global_input("fn");
check_high_score_reset();
game_session_clean_up();
show_header();

switch($fn)
{

	case 'show_faq':
		$faq = new ts_template("FAQ");
		$faq->set("TRIVIA_MAIN_PAGE_LINK", $TS_SCRIPTS['INDEX']);
		$faq->show();

	break;
	
	
	default:
		$main = new ts_template("TRIVIA_MAIN_PAGE");

		$main->set("FAQ_LINK", $TS_SCRIPTS['INDEX'] . "?fn=show_faq");
		$main->set("REGISTER_LINK", get_register_link());
		$main->set("REGISTERED_USERS", get_registered_users());
		$main->set("NEWEST_USER", get_newest_user());
		$main->set("GAMES_PLAYED", (int)$db->query_one_result("SELECT SUM(plays) FROM $DB_TABLES[GAMES]"));
		$main->set("USERS_PLAYING", $db->query_one_result("SELECT COUNT(*) FROM $DB_TABLES[GAME_SESSIONS] WHERE end_type=" . TS_GAME_END_TYPE_NOT_ENDED));
		$result = $db->query("SELECT $DB_TABLES[GAME_SECTIONS].name AS game_section_name,
					$DB_TABLES[GAME_SECTIONS].description AS game_section_description, 
					$DB_TABLES[GAME_SECTIONS].id AS game_section_id, $DB_TABLES[GAMES].* 
					FROM $DB_TABLES[GAMES], $DB_TABLES[GAME_SECTIONS] 
					WHERE $DB_TABLES[GAMES].mode=" . TS_GAME_MODE_ONLINE . " 
					AND $DB_TABLES[GAMES].game_section_id = $DB_TABLES[GAME_SECTIONS].id 
					ORDER BY $DB_TABLES[GAME_SECTIONS].section_order, $DB_TABLES[GAME_SECTIONS].id, $DB_TABLES[GAMES].game_order, $DB_TABLES[GAMES].id ASC");
		while($row = $result->fetch_array($result))
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
			$game = new ts_template("GAME_LISTING");
			$game->set("TRIVIA_GAME_NAME", $row['name']);
			$game->set("TRIVIA_GAME_INFO_LINK", $TS_SCRIPTS['GAME_INFO'] . "?game_id=$row[id]");
			$game->set("TRIVIA_GAME_ID", $row['id']);
	
			$game->set("TRIVIA_GAME_DESCRIPTION", $row['description']);
			$game->set("TRIVIA_GAME_NUM_QUESTIONS", $row['questions_per_session']);
			$game->set("TRIVIA_GAME_NUM_PLAYS", $row['plays']);
			$result2 = get_high_scores($row['id'], 1);
			if($result2->num_rows())
			{
				$row2 = $result2->fetch_array();
				$current_high_score = new ts_template("CURRENT_HIGH_SCORE");
				$current_high_score->set("SCORE", $row2['score']);
				$current_high_score->set("PROFILE_LINK", get_profile_link($row2['id']));
				$current_high_score->set("USERNAME", $row2['username']);
				$game->set("CURRENT_HIGH_SCORE", $current_high_score->dump());

			} // end if
			else
			{
				$current_high_score_none = new ts_template("CURRENT_HIGH_SCORE_NONE");
				$game->set("CURRENT_HIGH_SCORE", $current_high_score_none->dump());
			}
	
			$game_list .= $game->dump();
	
		} // end while
		$main->set("GAME_LIST", $game_list);
		$main->show();

	break;
	
} // end switch

show_footer();

?>
