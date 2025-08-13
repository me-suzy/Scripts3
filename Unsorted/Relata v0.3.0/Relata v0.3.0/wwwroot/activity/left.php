<?

//******************************************************************************
/*
GPL Copyright Notice

Relata
Copyright (C) 2001-2002 Stratabase, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/
//******************************************************************************

include("../config.inc.php");

//  We use the following features:
//  sess   for session variables
//  auth   for login checks, also required for user variables

// perform all startup functions
page_open(array(
	"sess" => "relata_session", 
	"auth" => "relata_challenge_Auth"
	));

header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

// start a new template
$template = start_template();

// connect to DB
$db = new relata_db;

// set template files
$template->set_file(array(
	"activity_left"	=>	"activity_left.htm"
	));

// get the user id
$user_id = $auth->auth["uid"];

$current_date = date("Y-m-d");

// make a list of all of the activities
$db->query("SELECT activity_id, activity_desc, date, priority FROM $activity WHERE user_id='$user_id' and iscompleted='false' and (date>='$current_date' or date='0000-00-00') order by date");

while($db->next_record())
{
	if($db->f("date") == "0000-00-00")
		{
			$date_stamp = "no date";
		}
		else
		{
			$date_stamp = $db->f("date");
		}
	$activity_list .= "<tr>";
		$activity_list .= '<td class="activitylist"><a href="javascript:changerightframe(\''. $_PHPLIB['webdir'] .'activity/main.php?session_id=' . $session_id . '&activity_id=' . $db->f("activity_id") . '\');">' . $db->f("activity_desc") . '</a></td>';
		$activity_list .= '<td class="activitylist">' . $date_stamp . '</td>';
		$activity_list .= '<td class="activitylist">' . $db->f('priority') . '</td>';
	$activity_list .= '</tr>';
}

// set template variables
$template->set_var(array(
	"ACTIVITY_LIST"	=>	$activity_list,
	"WWW_DIR"		=>	$_PHPLIB['webdir']
	));

// print the template
$template->parse("output","activity_left");
$template->p("output");

// page close functions
page_close();

?>