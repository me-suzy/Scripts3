<?php

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
	global $db, $DB_TABLES;
	
	return $db->query_one_result("SELECT COUNT(*) FROM $DB_TABLES[USERS] WHERE level != " . TS_USER_LEVEL_PENDING);
}

function get_newest_user()
{
	global $db, $DB_TABLES;
	
	return $db->query_one_result("SELECT username FROM $DB_TABLES[USERS] WHERE level != " . TS_USER_LEVEL_PENDING . " ORDER BY id DESC LIMIT 1");
}

function get_users_playing($game_id=0)
{
	global $db, $DB_TABLES;
	
	if( $game_id )
	{
		return $db->query("SELECT $DB_TABLES[USERS].username, $DB_TABLES[USERS].id
					FROM $DB_TABLES[USERS], $DB_TABLES[GAME_SESSIONS] 
					WHERE $DB_TABLES[USERS].id = $DB_TABLES[GAME_SESSIONS].user_id 
					AND $DB_TABLES[GAME_SESSIONS].game_id = $game_id 
					AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
	}				
	else
	{
		return $db->query("SELECT $DB_TABLES[GAMES].name AS game_name, $DB_TABLES[USERS].username
					FROM $DB_TABLES[GAMES], $DB_TABLES[GAME_SESSIONS], $DB_TABLES[USERS]
					WHERE $DB_TABLES[GAMES].id=$DB_TABLES[GAME_SESSIONS].game_id 
					AND $DB_TABLES[GAME_SESSIONS].user_id=$DB_TABLES[USERS].id 
					AND $DB_TABLES[GAME_SESSIONS].end_type=" . TS_GAME_END_TYPE_NOT_ENDED . " 
					ORDER BY $DB_TABLES[GAMES].name DESC");
	}
	
}

function get_high_scores($game_id, $num_high_scores)
{
	global $db, $DB_TABLES;

	return $db->query("SELECT $DB_TABLES[USERS].username, $DB_TABLES[USERS].id, $DB_TABLES[GAME_SESSIONS].score, $DB_TABLES[GAME_SESSIONS].end_time
				FROM $DB_TABLES[USERS], $DB_TABLES[GAME_SESSIONS] 
				WHERE $DB_TABLES[USERS].id = $DB_TABLES[GAME_SESSIONS].user_id 
				AND $DB_TABLES[GAME_SESSIONS].game_id=$game_id 
				AND $DB_TABLES[GAME_SESSIONS].game_type=" . TS_GAME_TYPE_NORMAL . " 
				AND $DB_TABLES[GAME_SESSIONS].end_type=" . TS_GAME_END_TYPE_NORMAL . " 
				ORDER BY $DB_TABLES[GAME_SESSIONS].score DESC LIMIT 0,$num_high_scores");
}
function increment_games_played($user_id)
{
	global $db, $DB_TABLES;

	$db->query("UPDATE $DB_TABLES[USERS] SET games_played = games_played + 1 WHERE id=$user_id");
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

		global $db, $DB_TABLES;
		if( isset($this->username) )
		{
			$result = $db->query("SELECT id,username,time_offset,level FROM $DB_TABLES[USERS] WHERE username='$this->username' AND password='$this->password' AND level != " . TS_USER_LEVEL_PENDING);
		}
		elseif( isset($this->user_id) )
		{
			$result = $db->query("SELECT id,username,time_offset,level FROM $DB_TABLES[USERS] WHERE id='$this->user_id' AND password='$this->password' AND level != " . TS_USER_LEVEL_PENDING);
		
		}
		else
		{
			return 0;
		}

		if($result->num_rows())	
		{
			$this->validated = 1;

			$row = $result->fetch_array();
			
			$this->user_id		= $row['id'];
			$this->username		= $row['username'];
			$this->level		= $row['level'];
			$this->time_offset	= $row['time_offset'];
			$db->query("UPDATE $DB_TABLES[USERS] SET last_visit_time=" . time() . " WHERE id=$this->user_id");
			
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
		global $db, $DB_TABLES;

		if( $last_play_time = $db->query_one_result("SELECT start_time FROM $DB_TABLES[GAME_SESSIONS] WHERE user_id='$this->user_id' AND game_id='$game_id' AND state !=" . TS_GAME_STATE_START . " ORDER BY id DESC LIMIT 1") )
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
