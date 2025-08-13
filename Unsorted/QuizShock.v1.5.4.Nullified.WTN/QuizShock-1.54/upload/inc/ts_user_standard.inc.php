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

function get_profile_link($user_id)
{
	global $TS_SCRIPTS;
	return $TS_SCRIPTS['USER'] . "?fn=show_profile&user_id=$user_id";
}

function get_register_link()
{
	global $TS_SCRIPTS;
	return $TS_SCRIPTS['USER'] . "?fn=register";
}

function get_forgot_info_link()
{
	global $TS_SCRIPTS;
	return $TS_SCRIPTS['FORGOT_INFO'];
}

function get_registered_users()
{
	global $db;
	
	return $db->query_one_result("SELECT COUNT(*) FROM ts_users WHERE level != " . TS_USER_LEVEL_PENDING);
}

function get_newest_user()
{
	global $db;
	
	return $db->query_one_result("SELECT username FROM ts_users WHERE level != " . TS_USER_LEVEL_PENDING . " ORDER BY id DESC LIMIT 1");
}

function get_users_playing($game_id=0)
{
	global $db;
	
	if( $game_id )
	{
		return $db->query("SELECT ts_users.username, ts_users.id
					FROM ts_users, ts_game_sessions 
					WHERE ts_users.id = ts_game_sessions.user_id 
					AND ts_game_sessions.game_id = $game_id 
					AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
	}				
	else
	{
		return $db->query("SELECT ts_games.name AS game_name, ts_users.username
					FROM ts_games, ts_game_sessions, ts_users
					WHERE ts_games.id=ts_game_sessions.game_id 
					AND ts_game_sessions.user_id=ts_users.id 
					AND ts_game_sessions.end_type=" . TS_GAME_END_TYPE_NOT_ENDED . " 
					ORDER BY ts_games.name DESC");
	}
	
}

function get_high_scores($game_id, $num_high_scores)
{
	global $db;

	return $db->query("SELECT ts_users.username, ts_users.id, ts_game_sessions.score, ts_game_sessions.end_time
				FROM ts_users, ts_game_sessions 
				WHERE ts_users.id = ts_game_sessions.user_id 
				AND ts_game_sessions.game_id=$game_id 
				AND ts_game_sessions.game_type=" . TS_GAME_TYPE_NORMAL . " 
				AND ts_game_sessions.end_type=" . TS_GAME_END_TYPE_NORMAL . " 
				ORDER BY ts_game_sessions.score DESC LIMIT 0,$num_high_scores");
}
function increment_games_played($user_id)
{
	global $db;

	$db->query("UPDATE ts_users SET games_played = games_played + 1 WHERE id=$user_id");
}
function get_last_user_played($game_id)
{
	global $db;
	$result = $db->query("SELECT ts_users.username, ts_users.id 
			FROM ts_users,ts_game_sessions
			WHERE ts_users.id = ts_game_sessions.user_id 
			AND ts_game_sessions.game_id=$game_id 
			AND ts_game_sessions.game_type !=" . 99 ."
			AND ts_game_sessions.end_type=" . TS_GAME_END_TYPE_NORMAL ." 
			ORDER BY id DESC LIMIT 1");

	return ( $db->num_rows($result) ) ? $db->fetch_array($result) : FALSE;
}

class ts_user
{
	var $username;
	var $password;
	var $user_id;
	var $level;
	var $validated;
	var $time_offset;
	var $last_visit_time;

	function ts_user()
	{
		global $HTTP_COOKIE_VARS;
		
		$ts_userinfo = @unserialize(@stripslashes($HTTP_COOKIE_VARS['ts_userinfo']));

		if( is_array($ts_userinfo) )
		{
			$this->user_id = $ts_userinfo['ts_user_id'];
			$this->password = $ts_userinfo['ts_password'];
		}

		$this->level = 0;
		$this->validated = 0;
	}
	
	function set_username($username)
	{
		$this->username = $username;
	}

	function set_password($password)
	{
		$this->password = md5($password);
	}
		
	function validate()
	{

		global $db;
		if( isset($this->username) )
		{
			$result = $db->query("SELECT id,username,time_offset,level FROM ts_users WHERE username='$this->username' AND password='$this->password' AND level != " . TS_USER_LEVEL_PENDING);
		}
		elseif( isset($this->user_id) )
		{
			$result = $db->query("SELECT id,username,time_offset,level FROM ts_users WHERE id='$this->user_id' AND password='$this->password' AND level != " . TS_USER_LEVEL_PENDING);
		
		}
		else
		{
			return 0;
		}

		if($db->num_rows($result))	
		{
			$this->validated = 1;

			$row = $db->fetch_array($result);
			
			$this->user_id		= $row['id'];
			$this->username		= $row['username'];
			$this->level		= $row['level'];
			$this->time_offset	= $row['time_offset'];
			$db->query("UPDATE ts_users SET last_visit_time=" . time() . " WHERE id=$this->user_id");
			
			return 1;
		}

		else
		{
			return 0;
		}

	}

	function set_cookies($remember=0)
	{
		global $PHP_SELF;

		$ts_userinfo = urlencode(serialize(array("ts_user_id"=>$this->user_id, "ts_password"=>$this->password)));
		if($remember)
			$seconds = 3600;
		else
			$seconds = 365*86400; // 86400 seconds in a day

		$date = gmdate("l, d-M-y H:i:s", time()+$seconds);
            	header("Set-Cookie: ts_userinfo=$ts_userinfo; expires=$date GMT; path=/");

	}
	function unset_cookies()
	{	
		global $PHP_SELF;
		
		$date = gmdate("l, d-M-y H:i:s", time()-(86400*365));
            	header("Set-Cookie: ts_userinfo=nothing; expires=$date GMT; path=/");
		
	}
	function is_validated()
	{
		if( $this->validated )
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	function is_admin()
	{
		
		if($this->level == TS_USER_LEVEL_ADMIN)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	function get_current_timestamp()
	{
		return time()+(3600*$this->time_offset);
	}
	function get_last_visit_timestamp()
	{
		return $this->last_visit_time+(3600*$this->time_offset);
	}
	function offset_time($time)
	{
		return $time + (3600*$this->time_offset);
	}
	function get_last_play_time($game_id)
	{
		global $db;

		if( $last_play_time = $db->query_one_result("SELECT start_time FROM ts_game_sessions WHERE user_id='$this->user_id' AND game_id='$game_id' AND state !=" . TS_GAME_STATE_START . " ORDER BY id DESC LIMIT 1") )
		{
			return $last_play_time;
		}
		else
		{
			return 0;
		}

	} // end function get_last_play_time()
		
} // end class ts_user

?>
