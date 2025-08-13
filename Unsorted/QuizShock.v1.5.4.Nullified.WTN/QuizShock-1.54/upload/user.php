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
require("inc/" . $TS_SCRIPTS['ALIB']);

$form_options = get_global_input("form_options");

if(!isset($form_options['id']))
{
	$form_options['id'] = get_global_input("id");
}

$fn = get_global_input("fn");
$go = get_global_input("go");
show_header();
if(!$OPTIONS['TRIVIA_ONLINE'] && !$ts_user->is_admin())
{
	t_error_out($OPTIONS['OFFLINE_MESSAGE']);
}


global $form_options_error;


switch($fn)
{
	
	case 'show_coppa_form':
	
		$coppa_form = new ts_template("COPPA_FORM");
		$coppa_form->set("PRIVACY_LINK", $TS_SCRIPTS['USER'] . "?fn=show_privacy_policy");
		$coppa_form->show();

	break;
		
	case 'show_privacy_policy':
	
		$privacy_policy = new ts_template("PRIVACY_POLICY");
		$privacy_policy->show();
		
	break;
	
	case 'register':
		if($OPTIONS['USE_COPPA'] && !$go)
		{
			$coppa_notice = new ts_template("COPPA_NOTICE");

			$coppa_notice->set("OVER_13_LINK", $TS_SCRIPTS['USER'] . "?fn=register&go=1");
			$coppa_notice->set("UNDER_13_LINK", $TS_SCRIPTS['USER'] . "?fn=show_coppa_form");

			$coppa_notice->set("PRIVACY_LINK", $TS_SCRIPTS['USER'] . "?fn=show_privacy_policy");
			$coppa_notice->show();			
		}
		else
		{
			if(isset($form_options['submit']))
			{
				if(!$errors = check_input_register())
				{
					if($form_options['homepage'] == 'http://')
					{
						$form_options['homepage'] = '';
					}
					$db_stuff = array(

					'username'		=> $form_options['username'],
					'email'			=> $form_options['email'],
					'password'		=> md5($form_options['password']),
					'homepage'		=> $form_options['homepage'],
					'location'		=> $form_options['location'],
					'biography'		=> $form_options['biography'],
					'icq_number'		=> $form_options['icq_number'],
					'aim_handle'		=> $form_options['aim_handle'],
					'yahoo_handle'		=> $form_options['yahoo_handle'],
					'time_offset'		=> $form_options['time_offset'],
					'date_registered'	=> time()

					);
					if($OPTIONS['USE_EMAIL_ACTIVATION_SYSTEM'])
					{
						$db_stuff['level'] = TS_USER_LEVEL_PENDING;
						mt_srand((double)microtime()*1000000);
						$db_stuff['activation_key'] = mt_rand()%900000+100000;
					
						$db->insert_from_array($db_stuff, ts_users);
						$user_id = $db->query_one_result("SELECT id FROM ts_users WHERE username='$db_stuff[username]' ORDER BY id DESC LIMIT 1");

						t_show_message("Thank you for registering, <b>$form_options[username]</b>. Your account is not yet activated. Details on how to activate your account have been emailed to you at: <b>$form_options[email]</b>. Remember that until you have activated your account, you cannot play trivia games.");
				
						$activation_email = new ts_template("ACTIVATION_EMAIL");
						$activation_email->set("ACTIVATION_LINK", "$OPTIONS[TRIVIA_SITE_URL]/$TS_SCRIPTS[USER]" . "?fn=ac&u_id=$user_id&k=$db_stuff[activation_key]");
						$activation_email->set("USERNAME", $db_stuff['username']);
						$activation_email->set("PASSWORD", $form_options['password']);

						$activation_email_subject = new ts_template("ACTIVATION_EMAIL_SUBJECT");

						ts_mail($form_options['email'], $activation_email_subject->dump(), $activation_email->dump());
				
					} // end if use email activation system
					else
					{
						$db_stuff['level'] = TS_USER_LEVEL_NORMAL;
					
						$db->insert_from_array($db_stuff, ts_users);
					
						t_show_message("Thank you for registering, <b>$form_options[username]</b>! Your account is now active. Please use the form below to log in.");

					} // end else
				
			
				} // end if !$error
				else
				{
						show_form_register($errors);
				}


			} // end if isset($form_options['submit'])
			else
			{
				show_form_register();
			}


		} // end else
	break;

	case 'edit_profile':
		if(!user_exists($form_options['id']))
		{
			t_error_out("Whoops", "The user you specified does not exist");
		}
		if(!($ts_user->is_validated() && $ts_user->user_id == $form_options['id']) && !$ts_user->is_admin())
		{
			t_error_out("Unable to edit your profile - you must be logged in as this user or the administrator");
		}
		if(isset($form_options['submit']))
		{
			if(!$errors = check_input_edit_profile())
			{
				if($form_options['homepage'] == 'http://')
				{
					$form_options['homepage'] = '';
				}
				$db_stuff = array(
				
					'homepage'	=> $form_options['homepage'],
					'location'	=> $form_options['location'],
					'biography'	=> $form_options['biography'],
					'icq_number'	=> $form_options['icq_number'],
					'aim_handle'	=> $form_options['aim_handle'],
					'yahoo_handle'	=> $form_options['yahoo_handle'],
					'time_offset'	=> $form_options['time_offset']

					);
				if($form_options['password'])
				{
					$db_stuff['password'] = md5($form_options['password']);
				}
					
				$db->update_from_array($db_stuff, ts_users, $form_options['id']);
				
				t_show_message("Changes to profile for <b>$form_options[username]</b> have been saved.");
			

			} // end if !$error
			else
			{
				show_form_edit($errors);
			}
			
		} // end if isset($form_options['submit'])
		else
		{
			$form_options = load_data_from_db(ts_users, $form_options['id']);
			unset($form_options['password']);

			show_form_edit();
		}
	
	break;
	
	case 'show_profile':
		
		$user_id = get_global_input("user_id");
		if( !$user_id )
		{
			if($ts_user->is_validated())
			{
				$form_options['id'] = $ts_user->user_id;
			}
			else
			{
				t_error_out("Error: You must be logged in to view your profile");
			}
		}
		$result = $db->query("SELECT * FROM ts_users WHERE id='$user_id'");
		if(!$db->num_rows($result))
		{
			t_error_out("The user you specified does not exist");
		}

		$user_info = $db->fetch_array($result);
		$profile = new ts_template("PROFILE");

		$profile->set("TRIVIA_MAIN_PAGE_LINK", $TS_SCRIPTS['INDEX']);
		$profile->set("USERNAME", $user_info['username']);
		$profile->set("DATE_REGISTERED", get_date($user_info['date_registered']));

		$profile->set("LAST_VISIT_DATE", get_date($user_info['last_visit_time']));
		$profile->set("LAST_VISIT_TIME", get_time($user_info['last_visit_time']));

		$profile->set("GAMES_PLAYED", $user_info['games_played']);
		$result = $db->query("SELECT ts_games.id,ts_games.name,ts_game_sessions.start_time, ts_game_sessions.score
					FROM ts_games, ts_game_sessions 
					WHERE ts_games.id=ts_game_sessions.game_id 
					AND ts_game_sessions.user_id='$user_id' 
					AND ts_game_sessions.end_type=" . TS_GAME_END_TYPE_NORMAL . "
					ORDER BY ts_game_sessions.id DESC LIMIT 1");
		if($db->num_rows($result))
		{
			$last_game_info = $db->fetch_array($result);
			$last_game_played = new ts_template("LAST_GAME_PLAYED");
			$last_game_played->set("LAST_GAME_LINK", $TS_SCRIPTS['GAME_INFO'] . "?quiz_id=$last_game_info[id]");
			$last_game_played->set("LAST_GAME_NAME", $last_game_info['name']);
			$last_game_played->set("LAST_GAME_DATE", get_date($last_game_info['start_time']));
			$last_game_played->set("LAST_GAME_TIME", get_time($last_game_info['start_time']));
			$last_game_played->set("LAST_GAME_SCORE", $last_game_info['score']);
			$profile->set("LAST_GAME_PLAYED", $last_game_played->dump());
		}
		else
		{
			$last_game_played_none = new ts_template("LAST_GAME_PLAYED_NONE");
	
			$profile->set('LAST_GAME_PLAYED', $last_game_played_none->dump());
		}

	
		$profile->set('HOMEPAGE', $user_info['homepage']);
		$profile->set('LOCATION', $user_info['location']);
		$profile->set('BIOGRAPHY', nl2br($user_info['biography']));
		$profile->set('ICQ_NUMBER', $user_info['icq_number']);
		$profile->set('AIM_HANDLE', $user_info['aim_handle']);
		$profile->set('YAHOO_HANDLE', $user_info['yahoo_handle']);
		if($user_info['time_offset'] >= 0)
		{
			$time_offset = '+' . $user_info['time_offset'];
		}
		else
		{
			$time_offset = $user_info['time_offset'];
		}

		$profile->set('TIME_OFFSET', $time_offset);
		if(($ts_user->is_validated() && $ts_user->user_id == $user_id) || $ts_user->is_admin())
		{
			$edit_profile_link = new ts_template("EDIT_PROFILE_LINK");
			$edit_profile_link->set('EDIT_PROFILE_LINK', $TS_SCRIPTS['USER'] . "?fn=edit_profile&id=$user_info[id]");
			$profile->set('EDIT_PROFILE_LINK', $edit_profile_link->dump());
		}
		else
		{
			$profile->drop('EDIT_PROFILE_LINK');
		}		


		$profile->show();

	break;
	
	case 'ac':
	
		$user_id = get_global_input('u_id');
		$key = get_global_input('k');

		if(!is_num($user_id) || $user_id == 0)
		{
			t_error_out("Invalid user ID specified");
		}

		if(!is_num($key) || $key == 0)
		{
			t_error_out("Invalid activation key specified");
		}
		$result = $db->query("SELECT username, level, activation_key FROM ts_users WHERE id=$user_id && activation_key=$key");
		$row = $db->fetch_array($result);
		if(!$db->num_rows($result))
		{
			t_error_out("Invalid user id / activation key combination entered. Please ensure that you have visited the link exactly as it appears out of the email you were sent (make sure the address is on one line!)");
		}
		elseif($row[level] != TS_USER_LEVEL_PENDING)
		{
			t_error_out("The user account you specified is already activated!");
		}
		else
		{
			$db->query("UPDATE ts_users SET level=" . TS_USER_LEVEL_NORMAL . " WHERE id=$user_id");
			$username = $db->query_one_result("SELECT username FROM ts_users WHERE id=$user_id");
			t_show_message("Success! User account for <b>$username</b> is now activated. Please log in below:");

		} // end else

	break;

} // end switch

show_footer();

function show_form_register($errors=array())
{

	global $form_options,$form_options_error, $db, $OPTIONS;
 
	if(count($errors))
	{
 		show_errors($errors);
	}
	if(!isset($form_options['time_offset']))
	{
		$form_options['time_offset'] = $OPTIONS['DEFAULT_TIME_OFFSET'];
	}
		
	start_form();
	do_inputhidden('fn', 'register');
	do_inputhidden('go', 1);

	$user = new ts_template('USER_REGISTER');

	$user->set('USERNAME_FIELD', do_inputtext("form_options[username]", $form_options['username'], $form_options_error['username'], 40, "input", "", 1));
	$user->set('PASSWORD_FIELD', do_inputpassword("form_options[password]", $form_options['password'], $form_options_error['password'], 40, "input", "", 1));
	$user->set('PASSWORD_RETYPE_FIELD', do_inputpassword("form_options[password_retype]", $form_options['password_retype'], $form_options_error['password_retype'], 40, "input", "", 1));
	$user->set('EMAIL_FIELD', do_inputtext("form_options[email]", $form_options['email'], $form_options_error['email'], 40, "input", "", 1));
	$user->set('EMAIL_RETYPE_FIELD', do_inputtext("form_options[email_retype]", $form_options['email_retype'], $form_options_error['email_retype'], 40, "input", "", 1));

	if(!isset($form_options['homepage']))
	{
		$form_options['homepage'] = "http://";
	}
	
	$user->set('HOMEPAGE_FIELD', do_inputtext("form_options[homepage]", $form_options['homepage'], $form_options_error['homepage'], 40, "input", "", 1));
	$user->set('LOCATION_FIELD', do_inputtext("form_options[location]", $form_options['location'], $form_options_error['location'], 40, "input", "", 1));
 	$user->set('BIOGRAPHY_FIELD', do_textarea("form_options[biography]]", $form_options['biography'], $form_options_error['biography'], 40, 5, "", 1));
	$user->set('ICQ_NUMBER_FIELD', do_inputtext("form_options[icq_number]", $form_options['icq_number'], $form_options_error['icq_number'], 40, "input", "", 1));
	$user->set('AIM_HANDLE_FIELD', do_inputtext("form_options[aim_handle]", $form_options['aim_handle'], $form_options_error['aim_handle'], 40, "input", "", 1));
	$user->set('YAHOO_HANDLE_FIELD', do_inputtext("form_options[yahoo_handle]", $form_options['yahoo_handle'], $form_options_error['yahoo_handle'], 40, "input", "", 1));
	$temp = array(
	
		'(GMT -12:00 hours) Eniwetok, Kwajalein'								=> "-12",
		'(GMT -12:00 hours) Eniwetok, Kwajalein'								=> "-11",
		'(GMT -10:00 hours) Hawaii'										=> "-10",
		'(GMT -9:00 hours) Alaska'										=> "-9",
		'(GMT -8:00 hours) Pacific Time (US & Canada)'								=> "-8",
		'(GMT -7:00 hours) Mountain Time (US & Canada)'								=> "-7",
		'(GMT -6:00 hours) Central Time (US & Canada), Mexico City'						=> "-6",
		'(GMT -5:00 hours) Eastern Time (US & Canada), Bogota, Lima, Quito'					=> "-5",
		'(GMT -4:00 hours) Atlantic Time (Canada), Caracas, La Paz'						=> "-4",
		'(GMT -3:30 hours) Newfoundland'									=> "-3.5",
		'(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown'							=> "-3",
		'(GMT -2:00 hours) Mid-Atlantic'									=> "-2",
		'(GMT -1:00 hours) Azores, Cape Verde Islands'								=> "-1",
		'(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia'					=> "0",
		'(GMT +1:00 hours) CET(Central Europe Time), Brussels, Copenhagen, Madrid, Paris'			=> "+1",
		'(GMT +2:00 hours) EET(Eastern Europe Time), Kaliningrad, South Africa'					=> "+2",
		'(GMT +3:00 hours) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg, Volgograd, Nairobi'			=> "+3",
		'(GMT +3:30 hours) Tehran'										=> "+3.5",
		'(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi'							=> "+4",
		'(GMT +4:30 hours) Kabul'										=> "+4.5",
		'(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi, Tashkent'						=> "+5",
		'(GMT +5:30 hours) Bombay, Calcutta, Madras, New Delhi'							=> "+5.5",
		'(GMT +6:00 hours) Almaty, Dhaka, Colombo'								=> "+6",
		'(GMT +7:00 hours) Bangkok, Hanoi, Jakarta'								=> "+7",
		'(GMT +8:00 hours) Beijing, Perth, Singapore, Hong Kong, Chongqing, Urumqi, Taipei'			=> "+8",
		'(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo, Yakutsk'						=> "+9",
		'(GMT +9:30 hours) Adelaide, Darwin'									=> "+9.5",
		'(GMT +10:00 hours) EAST(East Australian Standard), Guam, Papua New Guinea, Vladivostok'		=> "+10",
		'(GMT +11:00 hours) Magadan, Solomon Islands, New Caledonia'						=> "+11",
		'(GMT +12:00 hours) Auckland, Wellington, Fiji, Kamchatka, Marshall Island'				=> "+12"

		);

	$user->set('TIME_OFFSET_FIELD', do_select_from_array("form_options[time_offset]", $form_options['time_offset'], $temp, "input", 1));

	$user->show();

	end_form();

} // end function show_form_register()

function show_form_edit($errors=array())
{

	global $form_options,$form_options_error, $db;
 
	if(count($errors))
	{
		show_errors($errors);
	}

	start_form();
	do_inputhidden("fn", "edit_profile");
	do_inputhidden("form_options[id]", $form_options['id']);
	do_inputhidden("go", 1);

	$user = new ts_template("USER_EDIT_PROFILE");
	$user->set("USERNAME", $form_options['username']);
	do_inputhidden("form_options[username]", $form_options['username']);

	$user->set("PASSWORD_FIELD", do_inputpassword("form_options[password]", $form_options['password'], $form_options_error['password'], 40, "input", "", 1));
	$user->set("PASSWORD_RETYPE_FIELD", do_inputpassword("form_options[password_retype]", $form_options['password_retype'], $form_options_error['password_retype'], 40, "input", "", 1));
	$user->set("EMAIL", $form_options['email']);
	do_inputhidden("form_options[email]", $form_options['email']);
 
	if(!$form_options['homepage'])
	{
		$form_options['homepage'] = "http://";
	}

	$user->set("HOMEPAGE_FIELD",  do_inputtext("form_options[homepage]", $form_options['homepage'], $form_options_error['homepage'], 40, "input", "", 1));
	$user->set("LOCATION_FIELD",   do_inputtext("form_options[location]", $form_options['location'], $form_options_error['location'], 40, "input", "", 1));
	$user->set("BIOGRAPHY_FIELD", do_textarea("form_options[biography]]", $form_options['biography'], $form_options_error['biography'], 40, 5, "", 1));
	$user->set("ICQ_NUMBER_FIELD", do_inputtext("form_options[icq_number]", $form_options['icq_number'], $form_options_error['icq_number'], 40, "input", "", 1));
	$user->set("AIM_HANDLE_FIELD", do_inputtext("form_options[aim_handle]", $form_options['aim_handle'], $form_options_error['aim_handle'], 40, "input", "", 1));
	$user->set("YAHOO_HANDLE_FIELD", do_inputtext("form_options[yahoo_handle]", $form_options['yahoo_handle'], $form_options_error['yahoo_handle'], 40, "input", "", 1));
	$temp = array(
	
		'(GMT -12:00 hours) Eniwetok, Kwajalein'								=> "-12",
		'(GMT -12:00 hours) Eniwetok, Kwajalein'								=> "-11",
		'(GMT -10:00 hours) Hawaii'										=> "-10",
		'(GMT -9:00 hours) Alaska'										=> "-9",
		'(GMT -8:00 hours) Pacific Time (US & Canada)'								=> "-8",
		'(GMT -7:00 hours) Mountain Time (US & Canada)'								=> "-7",
		'(GMT -6:00 hours) Central Time (US & Canada), Mexico City'						=> "-6",
		'(GMT -5:00 hours) Eastern Time (US & Canada), Bogota, Lima, Quito'					=> "-5",
		'(GMT -4:00 hours) Atlantic Time (Canada), Caracas, La Paz'						=> "-4",
		'(GMT -3:30 hours) Newfoundland'									=> "-3.5",
		'(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown'							=> "-3",
		'(GMT -2:00 hours) Mid-Atlantic'									=> "-2",
		'(GMT -1:00 hours) Azores, Cape Verde Islands'								=> "-1",
		'(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia'					=> "0",
		'(GMT +1:00 hours) CET(Central Europe Time), Brussels, Copenhagen, Madrid, Paris'			=> "+1",
		'(GMT +2:00 hours) EET(Eastern Europe Time), Kaliningrad, South Africa'					=> "+2",
		'(GMT +3:00 hours) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg, Volgograd, Nairobi'			=> "+3",
		'(GMT +3:30 hours) Tehran'										=> "+3.5",
		'(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi'							=> "+4",
		'(GMT +4:30 hours) Kabul'										=> "+4.5",
		'(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi, Tashkent'						=> "+5",
		'(GMT +5:30 hours) Bombay, Calcutta, Madras, New Delhi'							=> "+5.5",
		'(GMT +6:00 hours) Almaty, Dhaka, Colombo'								=> "+6",
		'(GMT +7:00 hours) Bangkok, Hanoi, Jakarta'								=> "+7",
		'(GMT +8:00 hours) Beijing, Perth, Singapore, Hong Kong, Chongqing, Urumqi, Taipei'			=> "+8",
		'(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo, Yakutsk'						=> "+9",
		'(GMT +9:30 hours) Adelaide, Darwin'									=> "+9.5",
		'(GMT +10:00 hours) EAST(East Australian Standard), Guam, Papua New Guinea, Vladivostok'		=> "+10",
		'(GMT +11:00 hours) Magadan, Solomon Islands, New Caledonia'						=> "+11",
		'(GMT +12:00 hours) Auckland, Wellington, Fiji, Kamchatka, Marshall Island'				=> "+12"

	);
	

	$user->set("TIME_OFFSET_FIELD", do_select_from_array("form_options[time_offset]", $form_options['time_offset'], $temp, "input", 1));
	$user->show();

	end_form();

} // end function show_form_edit()

function check_input_register()
{
	global $form_options,$form_options_error,$OPTIONS, $db;
	if( $db->query_one_result("SELECT COUNT(*) FROM ts_users WHERE username='$form_options[username]' AND level != " . TS_USER_LEVEL_PENDING) )
	{
		$errors[] = "Another user has already chosen the username <b>$form_options[username]</b>, please choose a different one</li>";
		$form_options_error['username'] = 1;
	}
	if( is_blank($form_options['username']) )
	{
		$errors[] = "Username is blank!";
		$form_options_error['username'] = 1;
	} 
	else
	{
		if( @in_array($form_options['username'], @explode(' ', $OPTIONS['DISALLOWED_USERNAMES'])) )
		{
			$errors[] = "The username you have chosen (<b>$form_options[username]</b>) is not allowed. Please choose another";
			$form_options_error[username] = 1;
		}
	}
 
	if(strlen($form_options['username']) > 20)
	{
		$errors[] = "Username cannot be longer than 20 characters";
		$form_options_error['username'] = 1;
	}
	 
	if(is_blank($form_options['email']))
	{
		$errors[] = "Email is blank!";
		$form_options_error['email'] = 1;
	}
	elseif($form_options['email'] != $form_options['email_retype'])
	{
		$errors[] = "Your email addresses do not match.";
		$form_options_error['email'] = $form_options_error['email_retype'] = 1;
	}
	elseif( !is_email($form_options['email']) )
	{
		$errors[] = "Please enter a valid email address (e.g. <b>you@example.com</b>)";
		$form_options_error['email'] = $form_options_error['email_retype'] = 1;
	}
	
 
	elseif(@in_array($form_options['email'], @explode(' ', $OPTIONS[DISALLOWED_EMAIL_ADDRESSES])))
	{
		$errors[] = "You may not register with the email address <b>$form_options[email]</b>.";
		$form_options_error['email'] = $form_options_error['email_retype'] = 1;
	}
	elseif($db->query_one_result("SELECT COUNT(*) FROM ts_users WHERE email='$form_options[email]' AND level != " . TS_USER_LEVEL_PENDING))
	{
		$errors[] = "Another user has already registered with the email address <b>$form_options[email]</b>. Only one user can be registered to any email address";
		$form_options_error['email'] = $form_options_error['email_retype'] = 1;
	}

 
	if(is_blank($form_options['password']))
	{
		$errors[] = "Password is blank!";
		$form_options_error['password'] = 1;
	}
	if($form_options['password'] != $form_options['password_retype'])
	{
		$errors[] = "Passwords do not match.";
		$form_options_error['password'] = $form_options_error['password_retype'] = 1;
	}


	if(strlen($form_options['biography']) > 1000)
	{
		$errors[] = "<b>Biography</b> is too long. It cannot exceed 1000 characters";
		$form_options_error['biography'] = 1;
	}
	if(count($errors))
	{
		return $errors;
	}
	else
	{
		return 0;
	}
	

} // end function check_input_register()

function check_input_edit_profile()
{
	global $form_options, $form_options_error;
	 
	if( !is_blank($form_options['password']) || !is_blank($form_options['password_retype']) )
	{
		if(is_blank($form_options['password']))
		{
			$errors[] = "Password is blank!";
			$form_options_error['password'] = 1;
		}
		if($form_options['password'] != $form_options['password_retype'])
		{
			$errors[] = "Passwords do not match.";
			$form_options_error['password'] = $form_options_error['password_retype'] = 1;
		}

	} // end if


	if(strlen($form_options['biography']) > 1000)
	{
		$errors[] = "<b>Biography</b> is too long. It cannot exceed 1000 characters";
		$form_options_error['biography'] = 1;
	}
	if(count($errors))
	{
		return $errors;
	}
	else
	{
		return 0;
	}
 
 
} // end function check_input_edit_profile()

?>