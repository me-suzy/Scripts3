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

require("../script_ext.inc");
require("admin_global" . $script_ext);

$fn = get_global_input('fn');


switch($fn)
{
	case 'top':
	
		top();
		
	break;
	
	case 'bottom':
	
		bottom();

	break;

	case 'index':
	
		index();
		
	break;
	
	case 'nav':
	
		nav();
		
	break;

} // end select

function index()
{
	global $TS_SCRIPTS, $CP_HVARS, $db, $OPTIONS, $C_OPTS;

	show_cp_header(0, "#FFFFFF");

	?>
	</center>
	<font size=2 face="verdana,arial,helvetica">
	
	<small>

	<blockquote>
	
	<center><img src="../images/quizshock.gif"></center>
	<h2 align=center>Welcome to QuizShock <?php echo TS_VERSION;?></h2>

	Thank you for choosing <b>QuizShock</b> as your quiz management system. This is the
	control panel, where you can configure and set up every part of the software.

	<br><br>

	</small><b><a href="<?php echo $TS_SCRIPTS[MANUAL];?>">Users Manual</a></b><small><br>
	The manual provides complete details and instructions for using this software.

	<br><br>

	</blockquote>

	<center>
	<?php

	start_form_table("90%");

	do_table_header("<b>$OPTIONS[TRIVIA_SITE_NAME]</b> - System Status", 2);
	
	start_table_row();
	$currbg = switch_bgcolor($currbg);
	start_table_cell($currbg, "", "", "top");
	echo "<b>QuizShock System</b><br>";
	echo "<small>The status of the QuizShock system, online or offline. You can change this setting by 
	<a href=\"$TS_SCRIPTS[OPTIONS]\">editing your options</a></small>";
	end_table_cell();

	start_table_cell($currbg, "75%", "center", "center");
	
	if( $OPTIONS['TRIVIA_ONLINE'] )
	{
		$text = "<font color=$CP_HVARS[TABLE_HEADER_COLOR]>Online</font>";
	}
	else
	{
		$text = "<font color=red>Offline</font>";
	}
	
	echo "<font size=6><b>$text</b></big></font>";
	
	end_table_cell();
	end_table_row();

	start_table_row();
	$currbg = switch_bgcolor($currbg);
	start_table_cell($currbg, "", "", "top");
	echo "<b>Users Taking Quizzes:</b><br>";
	echo "<small>Number of users currently in active quiz sessions.</small>";
	end_table_cell();

	start_table_cell($currbg, "75%", "center", "center");
	echo "<b>Total: " . get_num_players() . "</b><br><br>";
	$result = $db->query("SELECT ts_games.name, COUNT(*) AS num_players 
				FROM ts_games, ts_game_sessions 
				WHERE ts_games.id=ts_game_sessions.game_id 
				AND ts_game_sessions.end_type=" . TS_GAME_END_TYPE_NOT_ENDED . " 
				GROUP BY ts_games.id 
				ORDER BY num_players DESC");



	echo "<table width=\"100%\">";

	start_table_row();
	start_table_cell("", "50%", "center", "top");
	if($db->num_rows($result))
	{
		echo "<b>Users per quiz:</b><br>";
		echo "<select name=null multiple size=5 class=inputlist>";

		while($row = $db->fetch_array($result))
		{
			$word = ($row['num_players'] >= 2) ? 'users' : 'user';

			echo "<option>$row[name]	- $row[num_players] $word</option>";

		}

		echo "</select>";	

	} // end if


	$result = get_users_playing();

	end_table_cell();
	start_table_cell("", "50%", "center", "top");
	if($db->num_rows($result))
	{
		echo "<b>User List:</b><br>";
		echo "<select name=null multiple size=5 class=\"inputlist\">";

		while($row = $db->fetch_array($result))
		{
			echo "<option>$row[username] - ($row[game_name])</option>";
		}

		echo "</select>";	

	} // end if

	end_table_cell();
	end_table_row();
	end_table();

	end_table_cell();
	end_table_row();
	if( $C_OPTS['USER_MODULE'] == "standard" )
	{
		start_table_row();
		$currbg = switch_bgcolor($currbg);
		start_table_cell($currbg, "", "", "top");
		echo "<b>Users Logged In:</b><br>";
		echo "<small>Number of users who have visited the site within the last 5 minutes.</small>";
		end_table_cell();
		$earliest_time = time() - 300;

		$result = $db->query("SELECT username,last_visit_time FROM ts_users WHERE last_visit_time > $earliest_time ORDER BY username ASC");

		start_table_cell($currbg, "75%", "center", "center");

		echo "<b>Total: " . $db->num_rows($result) . "</b><br><br>";
		if($db->num_rows($result))
		{
			echo "<b>User / Last Action:</b><br>";
			echo "<select name=null2 multiple size=3 class=inputlist>";
	
			while($row = $db->fetch_array($result))
			{
				$last_action = ($row[last_visit_time] - time()) * -1;
		
				echo "<option>$row[username] - $last_action seconds ago</option>";
			}
	
			echo "</select>";

		} // end if

		end_table_cell();
		end_table_row();

	} // end if
		
		end_table(2);



	show_cp_footer();

} // end function index()

function nav()
{
	global $TS_SCRIPTS, $CP_HVARS, $OPTIONS;

	$currbg = switch_bgcolor($currbg);

	show_cp_header(0, "#FFFFFF");

	echo "";
	
	echo "<table width=\"95%\"><tr><td align=center>";

	start_form_table();
	
	do_table_header("<b>Control Panel</b>", "", "center");

	start_table_row();
	do_col_header("<b>General</b>", "", "center");
	end_table_row();
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[MAIN]?fn=index\" target=\"main\">Control Panel Index</a>");
	end_table_cell();
	end_table_row();

       	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[NULLIFICATION]?fn=nullification\" target=\"main\">Nullification Info</a>");
	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"../$TS_SCRIPTS[INDEX]\" target=\"_top\">Quiz Site Index</a>");
	end_table_cell();
	end_table_row();
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[MANUAL]\" target=\"main\">Users Manual</a>");
	end_table_cell();
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[OPTIONS]\" target=\"main\">Edit Options</a>");
	end_table_cell();
	end_table_row();

	start_table_row();
	do_col_header("<b>Questions</b>", "", "center");
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[QUESTION]?id=new\" target=\"main\">Create Question</a></small>");
	end_table_cell();
	end_table_row();
		
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[QUESTION_BR]\" target=\"main\">Browse Questions</a></small>");
	end_table_cell();
	end_table_row();
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[CATEGORY]?id=new\" target=\"main\">Create Category</a>");
	end_table_cell();
	end_table_row();
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[CATEGORY_BR]\" target=\"main\">Browse Categories</a>");
	end_table_cell();
	end_table_row();
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[CATEGORY_IMPORT]\" target=\"main\">Import Category</a>");
	end_table_cell();
	end_table_row();


	start_table_row();
	do_col_header("<b>Quizzes</b>", "", "center");
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[GAME_SECTION]?id=new\" target=\"main\">Create Quiz Section</a>");
	end_table_cell();
	end_table_row();
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[GAME]?id=new\" target=\"main\">Create Quiz</a>");
	end_table_cell();
	end_table_row();
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[GAME_BR]\" target=\"main\">Browse Quizzes</a>");
	end_table_cell();
	end_table_row();
	
	start_table_row();
	do_col_header("<b>Flash Skins</b>", "", "center");
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[GAME_SKIN]\" target=\"main\">Upload Flash Skin</a>");
	end_table_cell();
	end_table_row();
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[GAME_SKIN_BR]\" target=\"main\">Browse Flash Skins</a>");
	end_table_cell();
	end_table_row();
	
	start_table_row();
	do_col_header("<b>Templates</b>", "", "center");
	end_table_row();

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[TEMPLATE_BR]\" target=\"main\">Browse Templates</a>");
	end_table_cell();
	end_table_row();
	
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	start_table_cell($currbg, "", "center");
	echo("<small><a href=\"$TS_SCRIPTS[TEMPLATE_VARS]\" target=\"main\">Edit Template Variables</a>");
	end_table_cell();
	end_table_row();
	
	end_table(2);

	echo "</td></tr></table>";
	echo "<!--CyKuH [WTN]-->";
	
	show_cp_footer();

} // end function nav()

function top()
{
	global $CP_HVARS, $TS_SCRIPTS, $OPTIONS;

	show_cp_header(1, $CP_HVARS['TABLE_HEADER_COLOR']);

	echo "</center><font color=\"$CP_HVARS[TABLE_HEADER_TEXT_COLOR]\" size=1 face=\"verdana,arial,tahoma,helvetica\">";
	
	echo "&nbsp; <b>QuizShock " . TS_VERSION . " Control Panel</b> &nbsp;&nbsp; :: &nbsp;&nbsp;";

	echo "<a href=\"$TS_SCRIPTS[AINDEX]?logout=1\" class=header1link target=\"_top\">Logout</a> &nbsp;&nbsp; :: &nbsp;&nbsp;";
		
	?>
	<?php


} // end function top()

function bottom()
{
	global $CP_HVARS, $db, $OPTIONS, $TS_SCRIPTS;
	
	show_cp_header(1, $CP_HVARS['TABLE_HEADER_COLOR']);
	
	echo "</center><font color=\"$CP_HVARS[TABLE_HEADER_TEXT_COLOR]\" size=1 face=\"verdana,arial,tahoma,helvetica\">";

	echo "&nbsp;&nbsp;<b><a href=\"../$TS_SCRIPTS[INDEX]\" class=header1link target=\"_top\">Go to your quiz home page</a></b>";
	
} // end function bottom()
	
?>
