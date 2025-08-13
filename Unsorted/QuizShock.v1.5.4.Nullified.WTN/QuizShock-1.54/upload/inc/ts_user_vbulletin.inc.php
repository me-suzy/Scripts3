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

///////////////////////////////////////////////////////////////////////////////
// CONFIGURATION
///////////////////////////////////////////////////////////////////////////////
// Edit the following options for setup

// URL to your vBulletin installation, no trailing slash
define("PATH_TO_VB", "/vbforums");

// vBulletin user level for an administrator
// (can log into the TriviaShock control panel)
define("VB_USER_LEVEL_ADMIN", 6); 

// User group that cannot login to TriviaShock
// (i.e. unregistered/unverified group)
$vb_user_level_no_access = array(1, 3);

// Should we update the users last activity time for the forums if they are using
// TriviaShock? 1 = yes, 0 = no
define("UPDATE_LAST_ACTIVITY", 1);

// the name of the vBulletin user id cookie
define("VB_COOKIE_USER_ID", "bbuserid");

// the name of the vBulletin user password cookie
define("VB_COOKIE_PASSWORD", "bbpassword");

///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

function get_profile_link($user_id)
{
	return PATH_TO_VB . "/member.php?action=getinfo&userid=$user_id";
}

function get_register_link()
{
	return PATH_TO_VB . "/register.php?action=signup";
}

function get_forgot_info_link()
{
	return PATH_TO_VB . "/member.php?action=lostpw";
}

function get_registered_users()
{
	global $db;
	
	return $db->query_one_result('SELECT COUNT(*) FROM user');
}

function get_newest_user()
{
	global $db;
	
	return $db->query_one_result("SELECT username FROM user ORDER BY userid DESC LIMIT 1");
}

function get_users_playing($game_id=0)
{
	global $db;
	
	if($game_id)
	{
		// get list of people playing this game
		return $db->query("SELECT user.username, user.userid AS id
					FROM user, ts_game_sessions 
					WHERE user.userid = ts_game_sessions.user_id 
					AND ts_game_sessions.game_id = $game_id 
					AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
	}
	else
	{
		// show list of games and users playing each
		return $db->query("SELECT ts_games.name AS game_name, user.username
					FROM ts_games, ts_game_sessions, user
					WHERE ts_games.id = ts_game_sessions.game_id 
					AND ts_game_sessions.user_id = user.userid
					AND ts_game_sessions.end_type = " . TS_GAME_END_TYPE_NOT_ENDED . " 
					ORDER BY ts_games.name DESC");
	}
				
}

function get_high_scores($game_id, $num_high_scores)
{
	global $db;

	return $db->query("SELECT user.username, user.userid AS id, ts_game_sessions.score, ts_game_sessions.end_time
				FROM user, ts_game_sessions 
				WHERE user.userid = ts_game_sessions.user_id 
				AND ts_game_sessions.game_id=$game_id 
				AND ts_game_sessions.game_type=" . TS_GAME_TYPE_NORMAL . " 
				AND ts_game_sessions.end_type=" . TS_GAME_END_TYPE_NORMAL . " 
				ORDER BY ts_game_sessions.score DESC LIMIT 0,$num_high_scores");
}				
			
// increments number of games the user has played
function increment_games_played($user_id)
{
	global $db;
		
	if( $profile_field_id = $db->query_one_result("SELECT the_value FROM ts_blackboard WHERE the_key='VB_PROFILE_FIELD_ID_GAMES_PLAYED'") )
	{
		$field_name = "field" . $profile_field_id;
		
		$db->query("UPDATE userfield SET $field_name = $field_name + 1 WHERE userid=$user_id");
	}
}
function get_last_user_played($game_id)
{
	global $db;
	$result = $db->query("SELECT user.username, user.userid AS id
			FROM user,ts_game_sessions
			WHERE user.userid = ts_game_sessions.user_id 
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
	var $user_id;		// user id
	var $level;		// level of user.. normal or administrator
	var $validated;		// if the username/password was correct and the user is logged in
	var $time_offset;
	var $last_visit_time;

	var $validated;		// whether user is validated or not

	function ts_user()
	{
		global $HTTP_COOKIE_VARS;
	
		// if vBulletin cookies are present	
		if( $HTTP_COOKIE_VARS[VB_COOKIE_USER_ID] && $HTTP_COOKIE_VARS[VB_COOKIE_PASSWORD] )
		{
			$this->user_id = $HTTP_COOKIE_VARS[VB_COOKIE_USER_ID];
			$this->password = $HTTP_COOKIE_VARS[VB_COOKIE_PASSWORD];
		}
		// else check for ours
		else
		{
			$ts_userinfo = @unserialize(@stripslashes($HTTP_COOKIE_VARS['ts_userinfo']));

			$this->user_id = $ts_userinfo['ts_user_id'];
			$this->password = $ts_userinfo['ts_password'];

			$this->level = 0;
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
		// attempts to log the user in based on username and password input

		global $db, $vb_user_level_no_access;

		// create comma separated list of vBulletin user groups that
		// cannot log in here
		$user_group_id_list = implode(',', $vb_user_level_no_access);

		// if we have the username (they are logging in)
		if( $this->username )
		{
			// attempt to fetch user information. If we get anything, it's a valid login
			$result = $db->query("SELECT userid,usergroupid,username,timezoneoffset
						FROM user
						WHERE username='$this->username' 
						AND password='$this->password'
						AND usergroupid NOT IN ($user_group_id_list)");
		}
		
		// else validate from user_id (cookie)
		elseif( $this->user_id )
		{		
			$result = $db->query("SELECT userid,usergroupid,username,timezoneoffset 
						FROM user 
						WHERE userid='$this->user_id' 
						AND password='$this->password'
						AND usergroupid NOT IN ($user_group_id_list)");
		}
		
		// else nothing, don't validate
		else
		{
			return 0;
		}

		if( $db->num_rows($result) )	
		{	
			$this->validated = 1;

			$row = $db->fetch_array($result);

			$this->user_id		= $row['userid'];
			
			// get their real username so that we get the correct
			// capitalization rather than what they logged in with
			$this->username		= $row['username'];
			$this->level		= $row['usergroupid'];
			$this->time_offset	= $row['timezoneoffset'];


			// update their last visited time to now
			if( UPDATE_LAST_ACTIVITY )
			{
				$db->query("UPDATE user SET lastactivity=" . time() . " WHERE userid=$this->user_id");
			}
		
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

		// if $remember is true, save cookies for a year, otherwise just an hour
		if($remember)
		{
			$seconds = 3600;
		}
		else
		{
			$seconds = 365*86400; // 86400 seconds in a day
		}
	
		$date = gmdate("l, d-M-y H:i:s", time()+$seconds);
            	header("Set-Cookie: ts_userinfo=$ts_userinfo; expires=$date GMT; path=/");

	}


	// deletes their login cookies
	function unset_cookies()
	{	
		global $PHP_SELF;
		
		$date = gmdate("l, d-M-y H:i:s", time()-(86400*365));
            	header("Set-Cookie: ts_userinfo=NULL; expires=$date GMT; path=/");
		
		header("Set-Cookie: " . VB_COOKIE_USER_ID . "=NULL; expires=$date GMT; path=/");
		header("Set-Cookie: " . VB_COOKIE_PASSWORD . "=NULL; expires=$date GMT; path=/");
		
	}

	// returns true if the user has been validated
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

	// tells us if this user is an administrator		
	function is_admin()
	{
		if( $this->level == VB_USER_LEVEL_ADMIN )
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

	// returns timestamp relative to their timezone offset
	function get_last_visit_timestamp()
	{
		return $this->last_visit_time+(3600*$this->time_offset);
	}

	// returns timestamp relative to their timezone offset
	function offset_time($time)
	{
		return $time + (3600*$this->time_offset);
	}
	

	// returns last time they played the specified game, or 0 if never
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
