<?php
// Invision Power Board user module
$user_module_name = 'Invision Power Board';

///////////////////////////////////////////////////////////////////////////////
// CONFIGURATION - Edit the following options for setup
///////////////////////////////////////////////////////////////////////////////

// URL to your phpBB2 installation, no trailing slash
define('PATH_TO_FORUMS', "/ipb");

// Prefix before database table names
define('INVBOARD_TABLE_PREFIX', 'ipb');

// User level for an administrator
// (can log into the TriviaShock control panel)
define('USER_LEVEL_ADMIN', 4); 

// Should we update the users last activity time for the forums 
// if they are using TriviaShock?
define('UPDATE_LAST_ACTIVITY', false);

// List of user group id's that will NOT be able to log into
// TriviaShock (i.e. guests, unvalidated users, banned users)
$user_group_no_access = array(2, 1, 5);

// the name of the user id cookie
define('COOKIE_USER_ID', 'member_id');

// the name of the user password cookie
define('COOKIE_PASSWORD', 'pass_hash');

define('COOKIE_SESSION_ID', 'session_id');

///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

$invboard_user_table	= INVBOARD_TABLE_PREFIX . 'members';
$invboard_session_table = INVBOARD_TABLE_PREFIX . 'sessions';

function get_profile_link($user_id)
{
	return PATH_TO_FORUMS . "/index.php?act=Profile&CODE=03&MID=$user_id";
}

function get_register_link()
{
	return PATH_TO_FORUMS . "/index.php?act=Reg&CODE=00";
}

function get_forgot_info_link()
{
	return PATH_TO_FORUMS . "/index.php?act=Reg&CODE=10";
}

function get_registered_users()
{
	global $db, $invboard_user_table;
	return $db->query_one_result("SELECT COUNT(*) FROM $invboard_user_table WHERE mgroup != 1 AND id > 0");
}

function get_newest_user()
{
	global $db, $invboard_user_table;
	return $db->query_one_result("SELECT name FROM $invboard_user_table WHERE mgroup != 1 AND id > 0 ORDER BY id DESC LIMIT 1");
}

function get_users_playing($game_id=0)
{
	global $db, $invboard_user_table;
	
	if( $game_id )
	{
		// get list of people playing this game
		return $db->query("SELECT $invboard_user_table.name AS username, $invboard_user_table.id AS id
					FROM $invboard_user_table, ts_game_sessions 
					WHERE $invboard_user_table.id = ts_game_sessions.user_id 
					AND ts_game_sessions.game_id = $game_id 
					AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
	}
	else
	{
		// show list of games and users playing each
		return $db->query("SELECT ts_games.name AS game_name, $invboard_user_table.name AS username
					FROM ts_games, ts_game_sessions, $invboard_user_table
					WHERE ts_games.id = ts_game_sessions.game_id 
					AND ts_game_sessions.user_id = $invboard_user_table.id
					AND ts_game_sessions.end_type = " . TS_GAME_END_TYPE_NOT_ENDED . " 
					ORDER BY ts_games.name DESC");
	}		
}

function get_high_scores($game_id, $num_high_scores)
{
	global $db, $invboard_user_table;
	return $db->query("SELECT $invboard_user_table.name AS username, $invboard_user_table.id AS id, ts_game_sessions.score, ts_game_sessions.end_time
				FROM $invboard_user_table, ts_game_sessions 
				WHERE $invboard_user_table.id = ts_game_sessions.user_id 
				AND ts_game_sessions.game_id=$game_id 
				AND ts_game_sessions.game_type=" . TS_GAME_TYPE_NORMAL . " 
				AND ts_game_sessions.end_type=" . TS_GAME_END_TYPE_NORMAL . " 
				ORDER BY ts_game_sessions.score DESC LIMIT 0,$num_high_scores");
}				

function increment_games_played($user_id)
{
	// not used
}

function get_last_user_played($game_id)
{
	global $db, $invboard_user_table;
	$result = $db->query("SELECT $invboard_user_table.name AS username, $invboard_user_table.id AS id
			FROM $invboard_user_table,ts_game_sessions
			WHERE $invboard_user_table.id = ts_game_sessions.user_id 
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

	var $third_party_session_id;

	function ts_user()
	{
		

		// if Invision Power Board cookies are present	
		if( $_COOKIE[COOKIE_USER_ID] && $_COOKIE[COOKIE_PASSWORD] )
		{
			$this->user_id = $_COOKIE[COOKIE_USER_ID];
			$this->password = $_COOKIE[COOKIE_PASSWORD];
		}
		elseif( $_COOKIE['ts_userinfo'] )
		{
			$ts_userinfo = @unserialize(@stripslashes($_COOKIE['ts_userinfo']));
			$this->user_id = $ts_userinfo['ts_user_id'];
			$this->password = $ts_userinfo['ts_password'];
			$this->level = 0;
		}
		elseif( $_COOKIE[COOKIE_SESSION_ID] )
		{
			$this->third_party_session_id = $_COOKIE[COOKIE_SESSION_ID];
		}
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
		global $db, $invboard_user_table, $invboard_session_table, $user_group_no_access;

		$user_groups = implode(',', $user_group_no_access);

		// if we have the username (they are logging in)
		if( $this->username )
		{
			$result = $db->query("SELECT id,name,mgroup,time_offset 
						FROM $invboard_user_table 
						WHERE name='$this->username' 
						AND password='$this->password'
						AND mgroup NOT IN ($user_groups)");
		}
		// else validate from user_id (cookie)
		elseif( $this->user_id )
		{		
			$result = $db->query("SELECT id,name,time_offset,mgroup 
						FROM $invboard_user_table
						WHERE id='$this->user_id' 
						AND password='$this->password'
						AND mgroup NOT IN ($user_groups)");
		}
		// validate from third party session id
		elseif( $this->third_party_session_id )
		{
			$result = $db->query("SELECT $invboard_user_table.id,$invboard_user_table.name,$invboard_user_table.time_offset,$invboard_user_table.mgroup 
						FROM $invboard_user_table, $invboard_session_table
						WHERE $invboard_session_table.id='$this->third_party_session_id' 
						AND $invboard_user_table.id=$invboard_session_table.member_id 
						AND $invboard_user_table.mgroup NOT IN ($user_groups)");
		}
		else
		{
			return false;
		}

		if( $db->num_rows($result) )	
		{
			$row = $db->fetch_array($result);

			$this->validated	= 1;
			$this->user_id		= $row['id'];
			$this->username		= $row['name'];
			$this->level		= $row['mgroup'];
			$this->time_offset	= $row['time_offset'];

			// update their last visited time to now
			if( UPDATE_LAST_ACTIVITY )
			{
				$db->query("UPDATE $invboard_session_table SET running_time=" . time() . " WHERE member_id=$this->user_id");
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

		// Get rid of Invision Power Board cookies as well
		header("Set-Cookie: " . COOKIE_USER_ID . "=NULL; expires=$date GMT; path=/");
		header("Set-Cookie: " . COOKIE_PASSWORD . "=NULL; expires=$date GMT; path=/");	
		header("Set-Cookie: " . COOKIE_SESSION_ID . "=NULL; expires=$date GMT; path=/");
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