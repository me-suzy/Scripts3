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
error_reporting(7);

require("../script_ext.inc");
require('../inc/config.inc' . $script_ext);
require('../inc/lib.inc' . $script_ext);
require('../inc/' . $TS_SCRIPTS['DB_CLASS']);
require('../inc/' . $TS_SCRIPTS['ALIB']);
require('../inc/' . 'ts_user_' . $C_OPTS['USER_MODULE'] . '.inc' . $script_ext);
if(@file_exists("../$TS_SCRIPTS[INSTALL]"))
{
	show_cp_header();
	echo "<br><br><br><center>";
	error_out("Fatal Error", "You have not deleted the install program (<b>$TS_SCRIPTS[INSTALL]</b>)! The install script must be deleted
	in order for this program to function. If the install script is not deleted, any visitor to your site could run the script and
	clear your entire database to the default installation.");
	
} // end if
$date = gmdate("D, d M Y H:i:s") . " GMT";
header("Last-Modified: $date");
header("Expires: $date");
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: pre-check=0, post-check=0, max-age=0");
$db = new db_sql($DB_INFO['HOSTNAME'], $DB_INFO['USERNAME'], $DB_INFO['PASSWORD'], $DB_INFO['DATABASE']);

if(!$db->connect())
{
	error_out("Database Error", "Unable to connect to the database server.");
}
	
$db->select_db();

$db->die_on_error = 1;

$ts_user = new ts_user();
if($ts_username = get_global_input("ts_username"))
{
	sleep(1);
	
	$ts_user->set_username($ts_username);
	$ts_user->set_password(get_global_input("ts_password"));

	if($ts_user->validate() && $ts_user->is_admin())
	{
		$ts_user->set_cookies();

		show_cp_header();		
		echo "<meta http-equiv=\"refresh\" content=\"1;URL=$TS_SCRIPTS[AINDEX]\">";
		echo "<br><br><br><center>";
		show_status_message("<center>Logging you in... wait a second...<br><br><small><a href=\"$TS_SCRIPTS[AINDEX]\" class=header1link>(Click here if your browser does not automatically forward you)</a></small></center>");
		show_cp_footer();
		exit;
	}
}

else
{
	$ts_user->validate();
}
if(!$ts_user->is_admin())
{
	show_cp_header();
	show_admin_login_form();
	show_cp_footer();
	exit;
}
if(get_global_input('logout'))
{
	$ts_user->unset_cookies();
	
	show_cp_header();
	
	echo "<meta http-equiv=\"refresh\" content=\"1;URL=../$TS_SCRIPTS[INDEX]\">";
	echo "<br><br><br><center>";
	show_status_message("<center>Logging you out... wait a second...<br><br><small><a href=\"../$TS_SCRIPTS[INDEX]\" class=header1link>(Click here if your browser does not automatically forward you)</a></small></center>");
	
	show_cp_footer();

	exit;
}
$OPTIONS = get_options();
game_session_clean_up();
		
?>