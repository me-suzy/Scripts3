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

// changed to "quiz_id"
$game_id = get_global_input("quiz_id");

game_session_clean_up();
if(!$OPTIONS['TRIVIA_ONLINE'] && !$ts_user->is_admin())
{
	t_show_message($OPTIONS['OFFLINE_MESSAGE']);
	exit;
}
if($OPTIONS['MAX_GAME_SESSIONS'])
{
	if(get_num_players() >= $OPTIONS['MAX_GAME_SESSIONS'])
	{
		t_show_message("Sorry, there are currently too many people playing trivia games. Please try again later when the server is less busy.");
		exit;
	}
}
if(empty($game_id) || ereg("[^0-9]", $game_id))
{
	t_show_message("Invalid game specified!");
	exit;
}

$result = $db->query("SELECT * FROM ts_games WHERE id=$game_id");
if( !$db->num_rows($result) )
{
	t_show_message("The game you specified does not exist!");
	exit;
}

$game_info = $db->fetch_array($result);
switch($game_info['mode'])
{
	case TS_GAME_MODE_ONLINE:
	break;
	case TS_GAME_MODE_OFFLINE:
		t_show_message("<center>This game is currently offline and cannot be played.<br><br><a href=\"javascript:window.close();\">Close Window</a></center>");
		exit;
	break;
	case TS_GAME_MODE_MAINTENANCE:
		t_show_message("<center>You cannot start new games on <b>$game_info[name]</b> at the moment because it is in maintenance mode. Maintenance mode allows the administrator of the game to make changes to the game without interrupting any game sessions. However, no new games can be started until the game is in online mode.<br><br><a href=\"javascript:window.close();\">Close Window</a></center>");
		exit;
	break;
	default:
		t_show_message("<center>This game is currently offline and cannot be played<br><br><a href=\"javascript:window.close();\">Close Window</a></center>");
		exit;
	break;
}
mt_srand((double)microtime() * 1000000);
$game_key = md5(mt_rand());
if($ts_user->is_validated())
{
	$user_id = $ts_user->user_id;
}
else
{
	t_show_message("<center>You must be logged in to play this game.<br><br><a href=\"javascript:window.close();\">Close Window</a></center>");
	exit;

} // end else
if( $game_info['time_between_games'] && !$ts_user->is_admin() )
{
	$latest_time = time() - ( $game_info['time_between_games'] * 3600 );
	$last_play_time = $ts_user->get_last_play_time($game_info['id']);
	if( $last_play_time > $latest_time )
	{
		$next_play_time = $last_play_time + ($game_info['time_between_games']*3600);
		
		t_show_message("<center>The administrator has specified that you can play this game once every "
				."$game_info[time_between_games] hour(s).<br><br>The next time you can play is "
				. get_date($next_play_time) 
				." at "
				. get_time($next_play_time)
				."<br><br><a href=\"javascript:window.close();\">Close Window</a></center>");
		exit;
	}

} // end if
if( $game_info['quizzes_per_user'] && !$ts_user->is_admin() )
{
	$times_taken = $db->query_one_result("SELECT COUNT(*) FROM ts_game_sessions WHERE user_id=$user_id AND game_id=$game_info[id] AND end_type=" . TS_GAME_END_TYPE_NORMAL);
	if( $times_taken >= $game_info['quizzes_per_user'] )
	{
		t_show_message("<center>The administrator has specified that you can only take this quiz "
				.$game_info['quizzes_per_user']
				." time(s)."
				."<br><br><a href=\"javascript:window.close();\">Close Window</a></center>");
		exit;
	}

} // end if

if($ts_user->is_admin())
{
	$game_type = TS_GAME_TYPE_TEST;
}
else
{
	$game_type = TS_GAME_TYPE_NORMAL;
}
$db->query("INSERT INTO ts_game_sessions (game_id,game_key,user_id,game_type,state,last_response_time) VALUES ($game_id,'$game_key', $user_id, $game_type, " . TS_GAME_STATE_START . "," . time() . ")");
$game_session_id = $db->query_one_result("SELECT id FROM ts_game_sessions WHERE game_id=$game_id AND user_id=$user_id AND state=" . TS_GAME_STATE_START . " AND game_key='$game_key' ORDER BY id DESC LIMIT 1");
$play = new ts_template("PLAY");

$trivia_site = urlencode("$OPTIONS[TRIVIA_SITE_URL]/$TS_SCRIPTS[TRIVIA]");
$game_skin = urlencode("$OPTIONS[TRIVIA_SITE_URL]/swf/skins/$game_info[game_skin]");

$play->set("TRIVIA_GAME_NAME", $game_info['name']);
$play->set("FRONTEND_URL", "$OPTIONS[TRIVIA_SITE_URL]/swf/quizshock.swf?game_session_id=$game_session_id&game_key=$game_key&trivia_site=$trivia_site&game_skin=$game_skin");

$play->show();

?>
