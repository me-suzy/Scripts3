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
show_header();
if( !$OPTIONS['TRIVIA_ONLINE'] && !$ts_user->is_admin() )
{
	t_error_out($OPTIONS['OFFLINE_MESSAGE']);
}

$message = new ts_template("MESSAGE");

if( $email = get_global_input("email") )
{
	if( !eregi("^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}| [0-9]{1,3})(\]?)\$", $email) )
	{
		$forgot_info_form = new ts_template("FORGOT_INFO_FORM");
		$forgot_info_form->set("SUBMIT_TO", $TS_SCRIPTS[FORGOT_INFO]);
		
		$message->set("MESSAGE", "<center><b>Error:</b> Invalid email address. Please enter a valid email address (example: <b>you@example.com)</b><br><br>" . $forgot_info_form->dump() . "</center>");
	}
	elseif( !$real_email = $db->query_one_result("SELECT email FROM ts_users WHERE email='$email' AND level !=" . TS_USER_LEVEL_PENDING) )
	{
		$forgot_info_form = new ts_template("FORGOT_INFO_FORM");
		$forgot_info_form->set("SUBMIT_TO", $TS_SCRIPTS['FORGOT_INFO']);
		
		$message->set("MESSAGE", "<center><b>Error:</b> There is no user registered under the email address <b>$email</b><br><br>" . $forgot_info_form->dump() . "</center>");
	}
	else
	{
		$temp_password = strtoupper( substr(md5((double)microtime()*1000000), 0, 8) );
		$db->query("UPDATE ts_users SET password='" . md5($temp_password) . "' WHERE email='$real_email' AND level !=" . TS_USER_LEVEL_PENDING);
		
		$message->set("MESSAGE", "<center>Thank you. You should receive an email containing your username and a temporary password shortly at: <b>$real_email</b>.</center>");
		$result = $db->query("SELECT username FROM ts_users WHERE email='$real_email' AND level !=" . TS_USER_LEVEL_PENDING);
		$row = $db->fetch_array($result);
		
		$forgot_info_email = new ts_template("FORGOT_INFO_EMAIL");
		$forgot_info_email->set("USERNAME", $row['username']);
		$forgot_info_email->set("PASSWORD", $temp_password);

		$forgot_info_email_subject = new ts_template("FORGOT_INFO_EMAIL_SUBJECT");
			
		ts_mail($real_email, $forgot_info_email_subject->dump(), $forgot_info_email->dump());
				
	}		

	$message->show();

} // end if isset($submit)
else
{

	$forgot_info_form = new ts_template("FORGOT_INFO_FORM");
	$forgot_info_form->set("SUBMIT_TO", $TS_SCRIPTS['FORGOT_INFO']);
	
	$forgot_info_form->show();
	
} // end else


show_footer();

?>
