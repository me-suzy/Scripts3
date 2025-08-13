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
error_reporting(7);

require("script_ext.inc");

require('inc/config.inc' . $script_ext);
require('inc/lib.inc' . $script_ext);
require('inc/' . $TS_SCRIPTS['DB_CLASS']);
require('inc/' . 'ts_user_' . $C_OPTS['USER_MODULE'] . '.inc' . $script_ext);

$frontend_input = get_global_input("f");

if( !@count($frontend_input) )
{
	exit;
}
$frontend_input['selected_answer'] = (int)$frontend_input['selected_answer'];
$frontend_link = new ts_flash_link();
$ts_game_session = new ts_game_session($frontend_input['game_session_id']);
$db = new db_sql($DB_INFO['HOSTNAME'], $DB_INFO['USERNAME'], $DB_INFO['PASSWORD'], $DB_INFO['DATABASE']);
if( !$db->connect() )
{
	$frontend_link->set_error("There was an error attempting to connect to the database.");
	$frontend_link->send_response();
	exit;
}
if( !$db->select_db() )
{
	$frontend_link->set_error("There was an error attempting to select to the database.");
	$frontend_link->send_response();
	exit;
}
$db->die_on_error = 0;
if( !$ts_game_session->init() )
{
	$frontend_link->set_error("Invalid game session ($frontend_input[game_session_id]) specified!");
	$frontend_link->send_response();
	exit;
}
if($ts_game_session->game_key != $frontend_input['game_key'])
{
	$ts_game_session->end(TS_GAME_END_TYPE_BAD_GAME_KEY);
	$frontend_link->set_error("Invalid game key specified.");
	$frontend_link->send_response();
	exit;
}
switch($ts_game_session->end_type)
{
	case TS_GAME_END_TYPE_NOT_ENDED:
	break;
	case TS_GAME_END_TYPE_TIMED_OUT:
		$frontend_link->set_error("This game session has timed out. The server says it waited too long for your response.");
		$frontend_link->send_response();
		exit;
	break;
	case TS_GAME_END_TYPE_OUT_OF_SYNC:
		$frontend_link->set_error("The game went out of synchronization with the server. This could either be caused by attempting to cheat or an internal error.");
		$frontend_link->send_response();
		exit;
	break;
	case TS_GAME_END_TYPE_INTERRUPTED:
		$frontend_link->set_error("This game session has been interrupted. The administrator temporarily turned off this game or the entire system while you were playing.");
		$frontend_link->send_response();
		exit;
	break;
	
} // end switch
if($frontend_input['state'] != $ts_game_session->state)
{
	$ts_game_session->end(TS_GAME_END_TYPE_OUT_OF_SYNC);
	$ts_game_session->update();
	
	$frontend_link->set_error("This game has gone out of synchronization with the server.");
	$frontend_link->send_response();
	exit;
	
} // end if
if($ts_game_session->is_timed_out())
{
	$ts_game_session->end(TS_GAME_END_TYPE_TIMED_OUT);
	$ts_game_session->update();
	
	$frontend_link->set_error("This game session has timed out. The server says it waited too long for your response.");
	$frontend_link->send_response();
	exit;
}

switch($ts_game_session->state)
{
	case TS_GAME_STATE_START:

		$ts_game_session->start();
		$frontend_link->set_game_info($ts_game_session->get_game_info());
		$frontend_link->set_question($ts_game_session->fetch_next_question());

		$ts_game_session->set_state(TS_GAME_STATE_ANSWER_QUESTION);
		$frontend_link->set_state(TS_GAME_STATE_ANSWER_QUESTION);

	break;

	case TS_GAME_STATE_ANSWER_QUESTION:
		$frontend_link->set_answer_result($ts_game_session->get_answer_result($frontend_input['selected_answer']));
		if($ts_game_session->question_number > $ts_game_session->game['questions_per_session'])
		{
			$ts_game_session->set_state(TS_GAME_STATE_ENDED);
			$ts_game_session->end(TS_GAME_END_TYPE_NORMAL);
			$frontend_link->set_state(TS_GAME_STATE_ENDED);
			$frontend_link->set_stats($ts_game_session->get_stats());
			if($ts_game_session->user_id)
			{
				increment_games_played($ts_game_session->user_id);
			}
			$db->query("UPDATE ts_games SET plays=plays+1 WHERE id=" . $ts_game_session->game[id]);
			
			
		} // end if
		else
		{
			$frontend_link->set_question($ts_game_session->fetch_next_question());
			$frontend_link->set_state(TS_GAME_STATE_ANSWER_QUESTION);
			

		} // end else


	break;
	default:

		$ts_game_session->set_state(TS_GAME_STATE_ENDED);
		$ts_game_session->end(TS_GAME_END_TYPE_BAD_STATE);
			
		$frontend_link->set_error("The server could not recognize the recognize the response sent by the game frontend. This game session has been terminated.");
	break;
	
	
} // end switch($frontend_input[st])
$ts_game_session->update();
$frontend_link->send_response();

?>
