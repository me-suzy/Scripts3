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

$fn = get_global_input("fn");
$forward_to = get_global_input("forward_to");
$remember = get_global_input("remember");

$message = new ts_template("MESSAGE");
					
switch($fn)
{
	case 'login':
		if( $ts_user->is_validated() )
		{
			echo "<meta http-equiv=\"refresh\" content=\"5;URL=$forward_to\">";
			
			$message->set("MESSAGE", "<center>You are already logged in as <b>$ts_user->username</b>. Please log out if you want to log in as a different user.<br><br><small><a href=\"$forward_to\">(Click here if your browser does not automatically forward you)</a></small></center>");
			$message->show();
			exit;
		}
		sleep(1);
	
		$ts_user->set_username(get_global_input("ts_username"));
		$ts_user->set_password(get_global_input("ts_password"));
		if($ts_user->validate())
		{
			$ts_user->set_cookies($remember);	
		
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=$forward_to\">";
			
			$message->set("MESSAGE", "<center>Logging you in... wait a second...<br><br><small><a href=\"$forward_to\">(Click here if your browser does not automatically forward you)</a></small></center>");
			$message->show();
			
		} // end if $ts_user->validate()
		else
		{
			$login_form = new ts_template("LOGIN_FORM");
			$login_form->set("SUBMIT_TO", $TS_SCRIPTS['AUTH']);
			$login_form->set("FORWARD_TO", $forward_to);
			$login_form->set("FORGOT_INFO_LINK", get_forgot_info_link());
					
			$message->set("MESSAGE", "<center>Invalid login. The username and/or password you entered are incorrect. Please try again.<br><br>" . $login_form->dump() . "</center>");
			$message->show();
			
		}
		
	break;
	
	case 'logout':
		$ts_user->unset_cookies();
		
		echo "<meta http-equiv=\"refresh\" content=\"1;URL=$forward_to\">";

		$message = new ts_template("MESSAGE");
		$message->set("MESSAGE", "<center>Logging you out.. wait a second<br><br><small><a href=\"$forward_to\">(Click here if your browser does not automatically forward you)</a></small></center>");
		$message->show();
		
	break;
	
} // end switch


?>
