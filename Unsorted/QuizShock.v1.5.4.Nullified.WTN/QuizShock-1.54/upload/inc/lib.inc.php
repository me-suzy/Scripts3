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

define("TS_GAME_MODE_ONLINE", 1);
define("TS_GAME_MODE_OFFLINE", 2);
define("TS_GAME_MODE_MAINTENANCE", 3);

define("TS_GAME_TYPE_NORMAL", 1);
define("TS_GAME_TYPE_TEST", 2);
define("TS_GAME_TYPE_OLD", 3);

define("TS_GAME_STATE_START", 1);
define("TS_GAME_STATE_ANSWER_QUESTION", 2);
define("TS_GAME_STATE_ENDED", 3);

define("TS_GAME_END_TYPE_NOT_ENDED", 0);
define("TS_GAME_END_TYPE_NORMAL", 1);
define("TS_GAME_END_TYPE_INTERRUPTED", 2);
define("TS_GAME_END_TYPE_TIMED_OUT", 3);
define("TS_GAME_END_TYPE_OUT_OF_SYNC", 4);
define("TS_GAME_END_TYPE_BAD_STATE", 5);
define("TS_GAME_END_TYPE_INVALID_INPUT", 6);
define("TS_GAME_END_TYPE_BAD_ANSWER", 7);
define("TS_GAME_END_TYPE_BAD_QUESTION", 8);
define("TS_GAME_END_TYPE_BAD_GAME_KEY", 9);
define("TS_GAME_END_TYPE_DB_ERROR", 10);
define("TS_GAME_END_TYPE_UNKNOWN_ERROR", 11);

define("TS_GAME_RESPONSE_TIME_LIMIT", 600);

define("TS_ANSWER_RESULT_CORRECT", 1);
define("TS_ANSWER_RESULT_INCORRECT", 2);
define("TS_ANSWER_RESULT_OUT_OF_TIME", 3);

define("TS_USER_LEVEL_PENDING", 0);
define("TS_USER_LEVEL_NORMAL", 1);
define("TS_USER_LEVEL_ADMIN", 2);

define('TS_VERSION', '1.5.4'); // version of this software

$TS_SCRIPTS = array(

	'INDEX'			=> 'index'			. $script_ext,
	'GAME_INFO'		=> 'quiz_info'			. $script_ext,
	'USER'			=> 'user'			. $script_ext,
	'TRIVIA'		=> 'quiz'			. $script_ext,
	'PLAY'			=> 'take_quiz'			. $script_ext,

	'AUTH'			=> 'auth'			. $script_ext,
	'FORGOT_INFO'		=> 'forgot_info'		. $script_ext,

	'AINDEX'		=> 'index'			. $script_ext,
	'MAIN'			=> 'admin_main'			. $script_ext,
	'NULLIFICATION' 	=> 'admin_nullification'	. $script_ext,
	'QUESTION' 		=> 'admin_question'		. $script_ext,
	'QUESTION_BR' 		=> 'admin_question_br'		. $script_ext,
	'CATEGORY' 		=> 'admin_category'		. $script_ext,
	'CATEGORY_BR' 		=> 'admin_category_br' 		. $script_ext,
	'CATEGORY_EXPORT' 	=> 'admin_category_export'	. $script_ext,
	'CATEGORY_IMPORT' 	=> 'admin_category_import'	. $script_ext,
	'GAME' 			=> 'admin_game'			. $script_ext,
	'GAME_BR' 		=> 'admin_game_br'		. $script_ext,
	'GAME_SECTION'		=> 'admin_game_section'		. $script_ext,

	'GAME_SKIN'		=> 'admin_game_skin'		. $script_ext,
	'GAME_SKIN_BR'		=> 'admin_game_skin_br'		. $script_ext,
	'OPTIONS' 		=> 'admin_options'		. $script_ext,
	'TEMPLATE_BR' 		=> 'admin_template_br'		. $script_ext,
	'TEMPLATE_VARS' 	=> 'admin_template_vars'	. $script_ext,
	'TEMPLATE' 		=> 'admin_template'		. $script_ext,
	'MANUAL'		=> 'admin_manual'		. $script_ext,
	'REPORT'		=> 'admin_report'		. $script_ext,

	'ALIB'			=> 'admin_lib.inc'		. $script_ext,
	'LIB'			=> 'lib.inc'			. $script_ext,
	'DB_CLASS'		=> 'db_class.inc'		. $script_ext,

	'INSTALL'		=> 'install'			. $script_ext

	); // end array()


$TS_FILE_TYPES['GAME_SKIN'] = array('swf');

$TS_DIRS = array(

	'GAME_SKINS'			=> 'swf/skins'
	
	);
@set_time_limit(120);
if( !@get_magic_quotes_gpc() )
{
	my_array_walk($HTTP_POST_VARS, "addslashes");
	my_array_walk($HTTP_GET_VARS, "addslashes");
	my_array_walk($HTTP_COOKIE_VARS, "addslashes");
}
@set_magic_quotes_runtime(0);
@mt_srand((double)microtime()*1000000);
function ts_mail($to, $subject, $body)
{
	global $OPTIONS;
	
	mail($to, $subject, $body, "From: $OPTIONS[EMAIL_FROM]");

} // end function ts_mail
function get_templates()
{
	global $db;

	$result = $db->query("SELECT the_key,data FROM ts_template_cache");

	while($row = $db->fetch_array($result))
	{
		$key = $row['the_key'];
		$TEMPLATES["$key"] = $row['data'];
	}

	return $TEMPLATES;
 
} // end function get_templates()
function cache_templates()
{

	global $db;
	$result = $db->query("SELECT the_key,data FROM ts_templates");
	$result2 = $db->query("SELECT the_key,the_value FROM ts_template_vars ORDER BY id ASC");
	$result3 = $db->query("SELECT the_key,the_value FROM ts_options");

	$db->query("DELETE FROM ts_template_cache");

	while( $row = $db->fetch_array($result) )
	{
		$db->data_seek($result2, 0);
		while( $row2 = $db->fetch_array($result2) )
		{
			$row['data'] = str_replace("{" . $row2['the_key'] . "}", $row2['the_value'], $row['data']);
		}
		$db->data_seek($result3, 0);
		while( $row3 = $db->fetch_array($result3) )
		{
			$row['data'] = str_replace("{" . $row3['the_key'] . "}", $row3['the_value'], $row['data']);
		}

		$row['the_key']	= addslashes($row['the_key']);
		$row['data']	= addslashes($row['data']);
		$db->query("INSERT INTO ts_template_cache (the_key,data) VALUES ('$row[the_key]', '$row[data]')");

 	} // end while


} // end function cache_templates()
function get_options($option="")
{
	global $db;
	if($option)
	{
		return $db->query_one_result("SELECT the_value FROM ts_options WHERE the_key='$option'");
	}

	$result = $db->query("SELECT the_key, the_value FROM ts_options");

	while($row = $db->fetch_array($result))
	{
		$key = $row['the_key'];
		$OPTIONS["$key"] = $row['the_value'];
	}
 
	return $OPTIONS;

} // end function get_options()
function error_out($title, $message)
{

	$currbg = switch_bgcolor($currbg);

	start_form_table();
	do_table_header("<b>$title</b>");
 	start_table_row();
	start_table_cell($currbg);
	echo $message;
	end_table_cell();
	end_table_row();
	
	start_table_row();
	do_col_header("</small><a href=\"javascript:history.go(-1);\">Back</a>", "100%", "center");
	end_table_row();
	end_table(2);

	show_cp_footer();
	exit;

} // end function error _out()

function show_footer()
{
	global $ts_user, $TS_SCRIPTS, $OPTIONS;
	if( $OPTIONS['TRIVIA_ONLINE'] || $ts_user->is_admin() )
	{
		$footer = new ts_template("FOOTER");
		$footer->set("TIME", get_time(time()));
		if(!$ts_user->is_validated())
		{
			$login_form = new ts_template("LOGIN_FORM");
			$login_form->set("SUBMIT_TO", $TS_SCRIPTS['AUTH']);
			$login_form->set("FORWARD_TO", $TS_SCRIPTS['INDEX']);
	
			$login_form->set("FORGOT_INFO_LINK", get_forgot_info_link());
	
			$footer->set("LOGIN_FORM", $login_form->dump());
		}
		else
		{
			$logout_form = new ts_template("LOGOUT_FORM");
			$logout_form->set("USERNAME", $ts_user->username);
			$logout_form->set("LOGOUT_LINK", $TS_SCRIPTS['AUTH'] . "?fn=logout&forward_to=$TS_SCRIPTS[INDEX]&nocache=" . mt_rand());
	
			$footer->set("LOGIN_FORM", $logout_form->dump());
		}
		if($ts_user->is_admin())
		{
			$footer->set("CONTROL_PANEL_LINK", "<br><a href=\"admin/$TS_SCRIPTS[AINDEX]\" class=\"normallink\">[ Go to the Control Panel ]</a><br><br>");
		}
		else
		{
			$footer->drop("CONTROL_PANEL_LINK");
		}
		$footer->set("TS_VERSION", TS_VERSION);
		eval("?>" . $footer->dump());
		
	} // end if

} // end function show_footer()
function get_num_players($game_id=0)
{
	global $db;

	if($game_id)
		return (int)$db->query_one_result("SELECT COUNT(*) FROM ts_game_sessions WHERE game_id=$game_id AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
	 else
		return (int)$db->query_one_result("SELECT COUNT(*) FROM ts_game_sessions WHERE end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
}
function num_games_using_category($category_id)
{
	global $db;

	return $db->query_one_result("SELECT COUNT(*) FROM ts_games,ts_game_cats WHERE ts_games.id = ts_game_cats.game_id AND ts_game_cats.category_id=$category_id AND ts_games.mode !=" . TS_GAME_MODE_OFFLINE);

} // end function num_games_using_category()

function get_games_using_category($category_id)
{

	global $db;
	$result = $db->query("SELECT ts_games.name, ts_games.id FROM ts_games,ts_game_cats WHERE ts_games.id=ts_game_cats.game_id AND ts_game_cats.category_id=$category_id AND ts_games.mode !=" . TS_GAME_MODE_OFFLINE);

	while($row = $db->fetch_array($result))
	{
		if( game_in_use($row['id']) )
		{
		 	$game_list[] = $row['name'];
		}
	}
	if(count($game_list))
		return $game_list;
	else
		return 0;

} // end function get_games_using_category()

function game_in_use($game_id)
{

	global $db, $OPTIONS;

	$mode = $db->query_one_result("SELECT mode FROM ts_games WHERE id=$game_id");

	if( !$OPTIONS['TRIVIA_ONLINE'] && !$db->query_one_result("SELECT COUNT(*) FROM ts_game_sessions WHERE end_type=" . TS_GAME_END_TYPE_NOT_ENDED) )
	{
		return 0;
	}
		
	switch($mode)
	{
		case TS_GAME_MODE_ONLINE:
			return 1;
		break;
		case TS_GAME_MODE_OFFLINE:
			return 0;
		break;
		case TS_GAME_MODE_MAINTENANCE:
			if( $db->query_one_result("SELECT COUNT(*) FROM ts_game_sessions WHERE game_id=$game_id AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED) )
				return 1;
			else
				return 0;
		break;
	
		default:

			return 0;
		
	} // end switch


} // end function game_in_use()

function get_category_name($category_id)
{
	global $db;

	return $db->query_one_result("SELECT name FROM ts_categories WHERE id=$category_id");

} // end function get_category_name()

function get_game_name($game_id)
{
	global $db;

	return $db->query_one_result("SELECT name FROM ts_games WHERE id=$game_id");

} // end function get_game_name()

function get_user_id($username)
{
	global $db;
  
	return $db->query_one_result("SELECT id FROM ts_users WHERE username='$username'");

} // end function get_user_id()

function reset_game_scores($game_id=0)
{
	global $db;

	$time=time();
	if($game_id)
	{
		$db->query("UPDATE ts_game_sessions SET game_type=" . TS_GAME_TYPE_OLD . " WHERE game_id=$game_id AND game_type=" . TS_GAME_TYPE_NORMAL);
		$db->query("UPDATE ts_games SET last_score_reset=$time WHERE id=$game_id");
	}
	else
	{
		$db->query("UPDATE ts_game_sessions SET game_type=" . TS_GAME_TYPE_OLD . " WHERE game_type=" . TS_GAME_TYPE_NORMAL);
		$db->query("UPDATE ts_games SET last_score_reset=$time WHERE id=$game_id");		
	}
	
} // end function reset_game_scores()

function reset_game_logs($game_id=0)
{	
	global $db;
	if($game_id)
	{
		$db->query("DELETE FROM ts_game_sessions WHERE game_id=$game_id");
		$db->query("UPDATE ts_games SET plays=0 WHERE id=$game_id");
	}
	else
	{
		$db->query("DELETE FROM ts_game_sessions");
		$db->query("UPDATE ts_games SET plays=0");
	}

	
} // end function reset_game_logs()

function set_game_mode($mode, $game_id)
{
	global $db;
	if($game_id)
	{
		$db->query("UPDATE ts_games SET mode=$mode WHERE id=$game_id");
	}
	else
	{
		$db->query("UPDATE ts_games SET mode=$mode");
	}
		
} // end function set_game_mode()
	
function interrupt_active_game_sessions($game_id=0)
{	
	global $db;
	if($game_id)
	{
		$result = $db->query("SELECT id FROM ts_game_sessions WHERE game_id=$game_id AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
		$db->query("UPDATE ts_game_sessions SET end_type=" . TS_GAME_END_TYPE_INTERRUPTED . ", end_time='" . time() . "' WHERE end_type=" . TS_GAME_END_TYPE_NOT_ENDED . " AND game_id=$game_id");

	}
	else
	{
		$result = $db->query("SELECT id FROM ts_game_sessions WHERE end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
		$db->query("UPDATE ts_game_sessions SET end_type=" . TS_GAME_END_TYPE_INTERRUPTED . ", end_time='" . time() . "' WHERE end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
	}
	while(@list($id) = $db->fetch_array($result))
	{
		$db->query("DROP TABLE IF EXISTS ts_gsq_$id");
	}

} // end function interrupt_active_game_sessions()

function db_clean_up()
{

	global $db; ;
	if(!$db->query_one_result("SELECT COUNT(*) FROM template_cache"))
	{
		cache_templates();
	}

} // end function db_clean_up()
function get_date($timestamp)
{ 
	global $OPTIONS, $ts_user;
	if($ts_user->is_validated())
	{
		$timestamp = $ts_user->offset_time($timestamp);
	}
	else
	{
		$timestamp = $timestamp + ($OPTIONS['DEFAULT_TIME_OFFSET'] * 3600);
	}

	return @gmdate($OPTIONS['DATE_FORMAT'], $timestamp);
}
function get_time($timestamp)
{
	global $OPTIONS, $ts_user;
	if($ts_user->is_validated())
	{
		$timestamp = $ts_user->offset_time($timestamp);
	}
	else
	{
		$timestamp = $timestamp + ($OPTIONS['DEFAULT_TIME_OFFSET'] * 3600);
	}

	return @gmdate($OPTIONS['TIME_FORMAT'], $timestamp);
}

function get_global_input($varname)
{

	global $HTTP_GET_VARS, $HTTP_POST_VARS, $form_options;
	if(isset($HTTP_GET_VARS["$varname"]))
	{
		return $HTTP_GET_VARS["$varname"];
	}
	elseif(isset($HTTP_POST_VARS["$varname"]))
	{
		return $HTTP_POST_VARS["$varname"];
	}

} // end function get_global_input()

function my_array_walk(&$array, $func)
{

	@reset($array);
	while( @list($key,$value) = @each($array) )
	{
		if(is_array($array[$key]))
		{
			my_array_walk($array[$key], $func);
		}
		else
		{
			$array[$key] = $func($value);
		}

	} // end while

} // end function my_array_walk

function do_inputhidden_array($array_name, $array=array())
{
	
	@reset($array);
	while(@list($key,$value) = @each($array))
	{
		do_inputhidden("$array_name" . "[$key]", $value);
	}


} // end function do_inputhidden_array

function array_to_get_url($array_name, $array)
{

	@reset($array);
	while(@list($key,$value) = @each($array))
	{
		$vars[] = "$array_name" . "[$key]=" . urlencode($value);
	}
	
	return implode("&", $vars);
	

} // end function array_to_get_url

function game_exists($id)
{
	global $db;

	return $db->query_one_result("SELECT COUNT(*) FROM ts_games WHERE id=$id");

} // end function game_exists()

function category_exists($id)
{
	global $db;
 
	return $db->query_one_result("SELECT COUNT(*) FROM ts_categories WHERE id=$id");

} // end function category_exists()

function question_exists($id)
{
	global $db;
 
	return $db->query_one_result("SELECT COUNT(*) FROM ts_questions WHERE id=$id");

} // end function question_exists()

function user_exists($id)
{
	global $db;
 
	return $db->query_one_result("SELECT COUNT(*) FROM ts_users WHERE id=$id");

} // end function user_exists()
function t_error_out($msg)
{
	$message = new ts_template("MESSAGE");
	$message->set("MESSAGE", $msg);
	$message->show();
	
	show_footer();
	
	exit;
	
} // end function t_error_out

function game_session_clean_up()
{
	global $db, $OPTIONS;

	$earliest_response = time() - TS_GAME_RESPONSE_TIME_LIMIT;
	$result = $db->query("SELECT id FROM ts_game_sessions WHERE last_response_time < $earliest_response AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
	$db->query("UPDATE ts_game_sessions SET end_type=" . TS_GAME_END_TYPE_TIMED_OUT . " 
			WHERE last_response_time <$earliest_response
			AND end_type=" . TS_GAME_END_TYPE_NOT_ENDED);
	while($row = $db->fetch_array($result))
	{
		$id = $row['id'];
		$db->query("DROP TABLE IF EXISTS ts_gsq_$id");
	}
	if( !$OPTIONS['TRIVIA_ONLINE'] && $OPTIONS['INTERRUPT_ACTIVE_GAME_SESSIONS'])
	{
		interrupt_active_game_sessions();
	}

} // end function game_session_clean_up

function check_high_score_reset()
{
	global $db;
	
	$time = time();
	$result = $db->query("SELECT id FROM ts_games WHERE last_score_reset < $time - (high_score_period * 86400) AND high_score_period != 0");
	while($row = $db->fetch_array($result))
	{
		reset_game_scores($row['id']);
	}
	
} // end function check_high_score_reset()

function create_game_question_cache($game_id)
{
	global $db;
	$db->query("CREATE TABLE IF NOT EXISTS ts_game_question_cache_$game_id (id int unsigned unique primary key)");
	$db->query("INSERT INTO ts_game_question_cache_$game_id SELECT id FROM ts_questions, ts_game_cats WHERE ts_questions.category_id=ts_game_cats.category_id AND ts_game_cats.game_id=$game_id ORDER BY id ASC");
	

} // end function create_game_question_cache()

function delete_game_question_cache($game_id)
{
	global $db;
	
	$db->query("DROP TABLE IF EXISTS ts_game_question_cache" . "_$game_id");
	
} // end function delete_game_question_cache()

function show_header()
{
	global $TS_SCRIPTS, $ts_user, $OPTIONS;

	$header = new ts_template("HEADER");
	$header->set("TRIVIA_MAIN_PAGE_LINK", $TS_SCRIPTS['INDEX']);
	$header->set("REGISTER_LINK", get_register_link());
	$header->set("PROFILE_LINK", get_profile_link($ts_user->user_id));
	$header->set("FAQ_LINK", $TS_SCRIPTS['INDEX'] . "?fn=show_faq");
	if($ts_user->is_admin())
	{
		if(!$OPTIONS['TRIVIA_ONLINE'])
		{
			$message = new ts_template("MESSAGE");
			$message->set("MESSAGE", "<b>NOTE: Your trivia site is currently offline but you are able to view it now because you are logged in as the administrator. All others visitors are currently being locked out.");
			$header->set("ADMIN_OFFLINE_NOTICE", $message->dump());
		}
		else
		{
			$header->drop("ADMIN_OFFLINE_NOTICE");
		}
		
	} // end if
	else
	{
		$header->drop("ADMIN_OFFLINE_NOTICE");
	}
	
			
	eval("?>" . $header->dump());

} // end function show_header();

function t_show_message($msg)
{
	$message = new ts_template("MESSAGE");
	$message->set("MESSAGE", $msg);
	$message->show();

} // end function t_show_message


if( !@function_exists("in_array") )
{
	function in_array($needle, $haystack)
	{
		@reset($haystack);
		while(@list($key,$value) = @each($haystack))
		{
			if($needle == $value)
				return true;
		}
		
	} // end function in_array()

} // end if function_exists("in_array")

class ts_template
{
	var $template;

	function ts_template($key)
	{
		global $TEMPLATES;

		$this->template = $TEMPLATES[$key];
	}

	function set($key, $value)
	{
		$this->template = str_replace("{" . $key . "}", $value, $this->template);
	}

	function drop($key)
	{
		$this->template = str_replace("{" . $key . "}", "", $this->template);
	}

	function dump()
	{
		return $this->template;
	}

	function show()
	{
		echo $this->template;
	}

}

/*
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
		
		$this->username = $ts_userinfo['ts_username'];
		$this->password = $ts_userinfo['ts_password'];

		$this->user_id = 0;
		$this->level = 0;
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
		$result = $db->query("SELECT id,username,time_offset,level FROM ts_users WHERE username='$this->username' AND password='$this->password' AND level != 0");
		
		if($db->num_rows($result))	
		{
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

		$ts_userinfo = urlencode(serialize(array("ts_username"=>$this->username, "ts_password"=>$this->password)));
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
		

		if($this->user_id)
			return 1;
		else
			return 0;
	}
	function is_admin()
	{
		
		if($this->level == TS_USER_LEVEL_ADMIN)
			return 1;
		else
			return 0;
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

*/

class ts_game_session
{
	
	var $id;
	var $game_key;
	var $user_id;
	var $state;
	var $end_type;
	var $current_question;
	var $question_number;
	var $start_time;
	var $end_time;
	var $last_response_time;
	var $time;
	var $multicategory;

	
	var $num_questions_correct;
	var $num_questions_incorrect;
	var $num_questions_out_of_time;
	var $score;
	
	var $game = array();
	function ts_game_session($game_session_id)
	{
		$this->id = $game_session_id;
	}
	
	function init()
	{
		global $db;
		if( !$result = $db->query("SELECT ts_games.*, ts_games.id AS game_id, ts_game_sessions.* 
					FROM ts_games,ts_game_sessions 
					WHERE ts_games.id=ts_game_sessions.game_id
					AND ts_game_sessions.id=$this->id") )
		{
			return 0;
		}
		if(!$db->num_rows($result))
		{
			return 0;
		}

		$row = $db->fetch_array($result);
		
		$this->user_id				= $row['user_id'];	
		$this->game_key				= $row['game_key'];
		$this->current_question			= $row['current_question'];
		$this->question_number			= $row['question_number'];
		$this->score				= $row['score'];
		$this->state				= $row['state'];
		$this->start_time			= $row['start_time'];
		$this->num_questions_correct		= $row['num_questions_correct'];
		$this->num_questions_incorrect		= $row['num_questions_incorrect'];
		$this->num_questions_out_of_time	= $row['num_questions_out_of_time'];

		$this->end_type				= $row['end_type'];
		$this->last_response_time		= $row['last_response_time'];
		
		$this->game['id']			= $row['game_id'];
		$this->game['name']			= $row['name'];
		$this->game['description']		= $row['description'];
		$this->game['questions_per_session']	= $row['questions_per_session'];
		$this->game['question_time_limit']	= $row['question_time_limit'];
		$this->game['correct_points']		= $row['correct_points'];
		$this->game['incorrect_penalty']	= $row['incorrect_penalty'];
		$this->game['show_correct_answer']	= $row['show_correct_answer'];
		$this->game['multicategory']		= $row['multicategory'];
		$this->time				= time();
		return 1;

	} // end function init()
	
	function is_timed_out()
	{
		
		if( ($this->time - $this->last_response_time) > TS_GAME_RESPONSE_TIME_LIMIT )
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	function start()
	{
		global $db, $frontend_link, $C_OPTS;

		$game_question_cache_table = ts_game_question_cache . '_' . $this->game['id'];
		if( $C_OPTS['USE_HEAP_TABLES'] )
		{
			$db->query("CREATE TABLE ts_gsq_$this->id type=heap SELECT id, RAND() as RAND FROM $game_question_cache_table ORDER BY RAND LIMIT " . $this->game[questions_per_session]);
		}
		else
		{
			$db->query("CREATE TABLE ts_gsq_$this->id SELECT id FROM $game_question_cache_table ORDER BY RAND() LIMIT " . $this->game[questions_per_session]);
		}
		
		
		$this->question_number = 1;
		$this->start_time = time();
		
	} // end function start()
	
	function get_game_info()
	{
		global $TS_SCRIPTS;
		
		$game_info = new ts_game_info;
		
		$game_info->game_name			= $this->game['name'];
		$game_info->game_description		= $this->game['description'];
		$game_info->questions_per_session	= $this->game['questions_per_session'];
		$game_info->question_time_limit		= $this->game['question_time_limit'];
		$game_info->correct_points		= $this->game['correct_points'];
		$game_info->incorrect_penalty		= $this->game['incorrect_penalty'];
		$game_info->multicategory		= $this->game['multicategory'];
		$OPTIONS['TRIVIA_SITE_URL'] = get_options("TRIVIA_SITE_URL");
		$game_info->high_score_link		= "$OPTIONS[TRIVIA_SITE_URL]/$TS_SCRIPTS[GAME_INFO]?fn=show_high_scores&quiz_id=" . $this->game[id];
		
		return $game_info;
			
	} // end function get_game_info()

	function fetch_next_question()
	{

		global $db;
		$question_offset = $this->question_number - 1;
		$result = $db->query("SELECT ts_questions.* FROM ts_questions,ts_gsq_$this->id WHERE ts_questions.id=ts_gsq_" . $this->id . ".id LIMIT $question_offset, 1");
		$row = $db->fetch_array($result);
		$this->current_question = $row['id'];
		if($this->game[multicategory])
			$category = $db->query_one_result("SELECT name FROM ts_categories WHERE id=$row[category_id]");
		$next_question = new ts_question;

		$next_question->question	= $row['question'];
		$next_question->category	= $category;
		$next_question->number		= $this->question_number;
		$result = $db->query("SELECT id,answer,answer_order FROM ts_answers WHERE question_id=$row[id] ORDER BY RAND()");
		while($row = $db->fetch_array($result))
		{
			$next_question->add_answer($row['id'], $row['answer'], $row['answer_order']);
		}
		$next_question->order_answers();
		$this->question_number++;
		return $next_question;
		
	}
	function set_state($state)
	{
		$this->state = $state;
	}
	function get_answer_result($answer_id)
	{
		global $db;

		$answer_result = new ts_answer_result;
		$result = $db->query("SELECT answer_notes FROM ts_questions WHERE id=$this->current_question");
		$question_info = $db->fetch_array($result);
		$result = $db->query("SELECT id,answer FROM ts_answers WHERE question_id=$this->current_question AND correct=1");
	 	$answer_info = $db->fetch_array($result);
		if( $answer_id == 0 )
		{
			$answer_result->answer_result = TS_ANSWER_RESULT_OUT_OF_TIME;
			
			$this->num_questions_out_of_time++;
			$this->score -= $this->game['incorrect_penalty'];
			if( $this->game['show_correct_answer'] )
			{
				$answer_result->correct_answer = $answer_info['answer'];
				$answer_result->answer_notes = $question_info['answer_notes'];
			}
						
		}
		else
		{
			if( $answer_info['id'] == $answer_id )
			{
				$answer_result->answer_result = TS_ANSWER_RESULT_CORRECT;
				$this->num_questions_correct++;
				$this->score += $this->game['correct_points'];
				$answer_result->answer_notes = $question_info['answer_notes'];

				$answer_result->correct_answer = $answer_info['answer'];

			} // end if $answer_info[id] == $answer_id


			else
			{
				$answer_result->answer_result = TS_ANSWER_RESULT_INCORRECT;
				$this->num_questions_incorrect++;
				$this->score -= $this->game['incorrect_penalty'];
				if($this->game['show_correct_answer'])
				{
					$answer_result->correct_answer = $answer_info['answer'];
					$answer_result->answer_notes = $question_info['answer_notes'];
				}

			} // end else

		} // end else				

		return $answer_result;

	} // end function get_answer_result()

	function get_stats()
	{
		$stats = new ts_game_stats($this->num_questions_correct, $this->num_questions_incorrect, $this->num_questions_out_of_time, $this->score);
		return $stats;
	} 

	function update()
	{

		global $db;
		if( $this->end_type == TS_GAME_END_TYPE_NOT_ENDED )
		{
			$db->query("UPDATE ts_game_sessions SET state=$this->state, start_time=$this->start_time, current_question=$this->current_question, question_number=$this->question_number, score=$this->score, num_questions_correct=$this->num_questions_correct,num_questions_incorrect=$this->num_questions_incorrect, num_questions_out_of_time=$this->num_questions_out_of_time, last_response_time=$this->time WHERE id=$this->id");
		}
		else
		{
			$db->query("UPDATE ts_game_sessions SET state=$this->state, start_time=$this->start_time, current_question=$this->current_question, question_number=$this->question_number, score=$this->score, num_questions_correct=$this->num_questions_correct,num_questions_incorrect=$this->num_questions_incorrect, num_questions_out_of_time=$this->num_questions_out_of_time, end_type=$this->end_type, end_time=$this->end_time WHERE id=$this->id");
		}

	} // end function update()
	function end($end_type)
	{
		global $db;
		$this->end_type = $end_type;
		$this->end_time = time();
		$db->query("DROP TABLE IF EXISTS ts_gsq_$this->id");
	}

} // end class ts_game_session

class ts_game_info
{
	var $game_name;
	var $game_description;
	var $questions_per_session;
	var $question_time_limit;
	var $correct_points;
	var $incorrect_penalty;
	var $multicategory;
	var $high_score_link;
		
} // end class ts_game_info

class ts_question
{
	
	var $question;
	var $number;
	var $category;
	var $answers = array();
	function add_answer($id, $answer, $answer_order)
	{
		$this->answers[] = array('id'=>$id, 'answer'=>$answer, 'answer_order'=>$answer_order);
	}
	
	function order_answers()
	{
		$num_answers = count($this->answers);
		for($i=0;$i<$num_answers;$i++)
		{
			if($this->answers[$i][answer_order])
			{
				$move_answer = $this->answers[$i];
				$answer_order = $move_answer[answer_order] - 1;
				$this->answers[$i] = $this->answers[$answer_order];
				$this->answers[$answer_order] = $move_answer;
			}
		
		} // end for
		
	} // end function order_anwers

} // end class ts_question

class ts_answer_result
{	
	var $answer_result;
	var $correct_answer;
	var $answer_notes;

} // end class ts_answer_result

class ts_game_stats
{
	
	var $num_questions_correct;
	var $num_questions_incorrect;
	var $num_questions_out_of_time;
	var $total_questions;
	var $correct_percentage;
	var $score;

	function ts_game_stats($num_questions_correct, $num_questions_incorrect, $num_questions_out_of_time, $score)
	{
		$this->num_questions_correct		= $num_questions_correct;
		$this->num_questions_incorrect		= $num_questions_incorrect;
		$this->num_questions_out_of_time	= $num_questions_out_of_time;
		$this->score				= $score;

		$this->total_questions = $this->num_questions_correct + $this->num_questions_incorrect + $this->num_questions_out_of_time;
		$this->correct_percentage = ceil(($this->num_questions_correct / $this->total_questions) * 100);

	} // end constructor

} // end class ts_game_stat

class ts_flash_link
{
	
	var $response_vars = array();
	var $response;
	function set_game_info($game_info)
	{
		$this->response_vars['game_name']		= $game_info->game_name;
		$this->response_vars['game_description']	= $game_info->game_description;
		$this->response_vars['questions_per_session']	= $game_info->questions_per_session;
		$this->response_vars['question_time_limit']	= $game_info->question_time_limit;
		$this->response_vars['correct_points'] 		= $game_info->correct_points;
		$this->response_vars['incorrect_penalty']	= $game_info->incorrect_penalty;
		$this->response_vars['multicategory']		= $game_info->multicategory;
		$this->response_vars['high_score_link']		= $game_info->high_score_link;

	} // end function set_game_info()
	function set_question($question)
	{
		$this->response_vars['question']	= $question->question;
		$this->response_vars['category']	= $question->category;
		$this->response_vars['question_number']	= $question->number;

		$num_answers = count($question->answers);
		for($i=0;$i<$num_answers;$i++)
		{
			$this->response_vars["a_" . ++$actr . "_id"] = $question->answers[$i][id];

			$this->response_vars["a_" . $actr] = $question->answers[$i]['answer'];
		}
		$this->response_vars['num_answers'] = count($question->answers);

	}

	function set_answer_result($answer_result)
	{
		$this->response_vars['answer_result'] = $answer_result->answer_result;
		if( $answer_result->correct_answer )
		{
			$this->response_vars['correct_answer'] = $answer_result->correct_answer;
		}
		if($answer_result->answer_notes)
		{
			$this->response_vars['answer_notes'] = $answer_result->answer_notes;
		}

	}

	function set_error($error_msg)
	{
		$this->response_vars['error_msg'] = $error_msg;
	}

	function set_state($state)
	{
		$this->response_vars['state'] = $state;
	}
	
	function set_stats($stats)
	{
		$this->response_vars['num_questions_correct']		= $stats->num_questions_correct;
		$this->response_vars['num_questions_incorrect']		= $stats->num_questions_incorrect;
		$this->response_vars['num_questions_out_of_time']	= $stats->num_questions_out_of_time;
		$this->response_vars['correct_percentage']		= $stats->correct_percentage;
		$this->response_vars['score']				= $stats->score;
	}
	function send_response()
	{
		@reset($this->response_vars);
		while(@list($key,$value) = @each($this->response_vars))
		{
			$this->response .= "&$key=" . urlencode($value);
		}

		$this->response .= "&end=1";
		echo $this->response;
	}


} // end class ts_flash_link

class select_query
{
	var $fields	= array();
	var $tables	= array();
	var $where	= array();
	var $order_by	= array();
	var $group_by	= array();
	var $query;
	var $order_type;
	var $have_limit;
	var $limit_offset;
	var $limit_num_records;
	
	function select_query()
	{
	}
	
	
	function add_field($fields)
	{
		$this->fields[] = $fields;
	}
	
	function add_table($table)
	{
		$this->tables[] = $table;
	}
	
	function add_where_clause($clause)
	{
		$this->where[] = $clause;
	}
	
	function add_order_by($field)
	{
		$this->order_by[] = $field;
	}
	
	function add_group_by($field)
	{
		$this->group_by[] = $field;
	}
	
	function set_order_type($type)
	{
		$this->order_type = $type;
	}
	
	function set_limit($offset, $num_records)
	{
		$this->have_limit = true;
		$this->limit_offset = $offset;


		$this->limit_num_records = $num_records;
	}
	
	function make()
	{
		$this->query = "SELECT ";
		$this->query .= implode(",", $this->fields);
		$this->query .= " FROM ";
		$this->query .= implode(",", $this->tables);
		if(count($this->where))
		{
			$this->query .= " WHERE ";
			$this->query .= implode(" AND ", $this->where);
		}
		if(count($this->group_by))
		{
			$this->query .= " GROUP BY ";
			$this->query .= implode(",", $this->group_by);
		}
		if(count($this->order_by))
		{
			$this->query .= " ORDER BY ";
			$this->query .= implode(",", $this->order_by);
			
			$this->query .= " " . $this->order_type;
		}
		if($this->have_limit)
			$this->query .= " LIMIT $this->limit_offset,";
			$this->query .= $this->limit_num_records;
	
		return $this->query;
	}
	

} // end class select_query


?>
