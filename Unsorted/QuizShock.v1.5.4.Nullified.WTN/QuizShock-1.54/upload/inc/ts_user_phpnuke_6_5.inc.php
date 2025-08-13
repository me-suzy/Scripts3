<?php
// user module
$user_module_name = 'PHP-Nuke';

///////////////////////////////////////////////////////////////////////////////
// CONFIGURATION - Edit the following options for setup
///////////////////////////////////////////////////////////////////////////////

// URL to your PHP-Nuke installation, no trailing slash
define("PATH_TO_NUKE", "/php-nuke");

// Prefix before database table names
define('NUKE_TABLE_PREFIX', 'nuke_');

// PHP-Nuke user level for an administrator
// (can log into the TriviaShock control panel)
define("USER_LEVEL_ADMIN", 1); 

// Should we update the users last activity time for the 
// if they are using TriviaShock?
define("UPDATE_LAST_ACTIVITY", false);


///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

$nuke_user_table	= NUKE_TABLE_PREFIX . 'users';

function get_profile_link( $user_id )
{
	global $db, $nuke_user_table;
	$username = $db->query_one_result("SELECT username FROM $nuke_user_table WHERE user_id=$user_id");
	return PATH_TO_NUKE . "/modules.php?name=Your_Account&op=userinfo&username=$username";
}

function get_register_link()
{
	return PATH_TO_NUKE . "/modules.php?name=Your_Account&op=new_user";
}

function get_forgot_info_link()
{
	return PATH_TO_NUKE . "/modules.php?name=Your_Account&op=pass_lost";
}

function get_registered_users()
{
	global $db, $nuke_user_table;
	return $db->query_one_result("SELECT COUNT(*) FROM $nuke_user_table WHERE user_active=1");
}

function get_newest_user()
{
	global $db, $nuke_user_table;
	return $db->query_one_result("SELECT username FROM $nuke_user_table WHERE user_active=1 ORDER BY user_id DESC LIMIT 1");
}

function get_users_playing($game_id=0)
{
	global $db, $nuke_user_table;
	
	if( $game_id )
	{
		// get list of people playing this game
		return $db->query("SELECT $nuke_user_table.username, $nuke_user_table.user_id AS id
					FROM $nuke_user_table, ts_game_sessions 
					WHERE $nuke_user_table.user_id = ts_game_sessions.user_id 
					AND ts_game_sessions.game_id = $game_id 
					AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
	}
	else
	{
		// show list of games and users playing each
		return $db->query("SELECT ts_games.name AS game_name, $nuke_user_table.username
					FROM ts_games, ts_game_sessions, $nuke_user_table
					WHERE ts_games.id = ts_game_sessions.game_id 
					AND ts_game_sessions.user_id = $nuke_user_table.user_id
					AND ts_game_sessions.end_type = " . TS_GAME_END_TYPE_NOT_ENDED . " 
					ORDER BY ts_games.name DESC");
	}		
}

function get_high_scores($game_id, $num_high_scores)
{
	global $db, $nuke_user_table;
	return $db->query("SELECT $nuke_user_table.username, $nuke_user_table.user_id AS id, ts_game_sessions.score, ts_game_sessions.end_time
				FROM $nuke_user_table, ts_game_sessions 
				WHERE $nuke_user_table.user_id = ts_game_sessions.user_id 
				AND ts_game_sessions.game_id=$game_id 
				AND ts_game_sessions.game_type=" . TS_GAME_TYPE_NORMAL . " 
				AND ts_game_sessions.end_type=" . TS_GAME_END_TYPE_NORMAL . " 
				ORDER BY ts_game_sessions.score DESC LIMIT 0,$num_high_scores");
}				

function get_last_user_played($game_id)
{
	global $db, $nuke_user_table;
	$result = $db->query("SELECT $nuke_user_table.username, $nuke_user_table.user_id AS id
			FROM $nuke_user_table,ts_game_sessions
			WHERE $nuke_user_table.user_id = ts_game_sessions.user_id 
			AND ts_game_sessions.game_id=$game_id 
			AND ts_game_sessions.game_type !=" . 99 ."
			AND ts_game_sessions.end_type=" . TS_GAME_END_TYPE_NORMAL ." 
			ORDER BY id DESC LIMIT 1");

	return ( $db->num_rows($result) ) ? $db->fetch_array($result) : FALSE;
}

function increment_games_played($user_id)
{
	// not used
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
		

		$ts_userinfo	= @unserialize(@stripslashes($_COOKIE['ts_userinfo']));
		$this->user_id	= $ts_userinfo['ts_user_id'];
		$this->password = $ts_userinfo['ts_password'];
		$this->level	= 0;
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
		global $db, $nuke_user_table, $phpbb_session_table;

		// if we have the username (they are logging in)
		if( $this->username )
		{
			$result = $db->query("SELECT user_id,user_level,username,user_timezone 
						FROM $nuke_user_table 
						WHERE username='$this->username' 
						AND user_password='$this->password' 
						AND user_active=1");
		}
		// else validate from user_id (cookie)
		elseif( $this->user_id )
		{		
			$result = $db->query("SELECT user_id,username,user_timezone,user_level  
						FROM $nuke_user_table
						WHERE user_id='$this->user_id' 
						AND user_password='$this->password' 
						AND user_active=1");
		}
		else
		{
			return false;
		}

		if( $db->num_rows($result) )	
		{
			$row = $db->fetch_array($result);

			$this->validated	= 1;
			$this->user_id		= $row['user_id'];
			$this->username		= $row['username'];
			$this->level		= $row['user_level'];
			$this->time_offset	= $row['user_timezone'];

			// update their last visited time to now
			if( UPDATE_LAST_ACTIVITY )
			{
				$db->query("UPDATE $nuke_user_table SET user_session_time=" . time() . " WHERE user_id=$this->user_id");
			}
			return true;
		}

		else
		{
			return false;
		}
	}

	function set_cookies( $remember=0 )
	{
		global $PHP_SELF;

		$ts_userinfo = urlencode(serialize(array("ts_user_id"=>$this->user_id, "ts_password"=>$this->password)));

		// if $remember is true, save cookies for a year, otherwise just an hour
		if($remember)
		{
			$seconds = 3600;
		}
		else
		{
			$seconds = 365*86400;
		}
	
		$date = gmdate("l, d-M-y H:i:s", time()+$seconds);
            	header("Set-Cookie: ts_userinfo=$ts_userinfo; expires=$date GMT; path=/");
	}

	function unset_cookies()
	{	
		global $PHP_SELF;
		
		$date = gmdate("l, d-M-y H:i:s", time()-(86400*365));
            	header("Set-Cookie: ts_userinfo=NULL; expires=$date GMT; path=/");	
	}

	function is_validated()
	{
		return $this->validated;
	}
	
	function is_admin()
	{
		if( $this->level == USER_LEVEL_ADMIN )
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	// returns timestamp relative to their timezone offset
	function get_current_timestamp()
	{
		return time()+(3600*$this->time_offset);
	}

	function get_last_visit_timestamp()
	{
		return $this->last_visit_time+(3600*$this->time_offset);
	}

	// returns timestamp relative to their timezone offset
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
			return false;
		}
	}
						
} // end class ts_user

?>