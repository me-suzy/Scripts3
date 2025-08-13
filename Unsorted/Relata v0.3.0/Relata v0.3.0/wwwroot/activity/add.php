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
include("activity_lib.php");

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

// get the user id
$user_id = $auth->auth["uid"];

// set the template file
$template->set_file("activity", "activity_detail.htm");

// if 'OK' was pressed
if($action == "  OK  ")
{	
	if(!$starttime)
	{
		$starttime = "00:00:00";
	}
	if(!$endtime)
	{
		$endtime = "00:00:00";
	}

	$event_date = removeleadingzeros($event_date);

	$db->query("SELECT * FROM $activity WHERE date='$event_date'");
	
	if((!$db->num_rows()) || ($is_calendar_item == "N") || ($is_calendar_item == "Y2"))
	{
		if($is_calendar_item == "Y2")
		{
			$is_calendar_item = "Y";
		}
	
		if($is_calendar_item == "N")
		{
			$is_calendar_item = " ";
		}
		
		$db->query("INSERT INTO $activity (user_id,activity_desc,date,starttime,endtime,priority,is_calendar_item) 
				VALUES ('$user_id','$activity_desc','$event_date','$starttime','$endtime','$priority','$is_calendar_item')");
	}
	else
	{
		exit("You cannot have 2 events for the same date");
	}
	
	header("location: main.php?session_id=$session_id&date=$date&reload=true");
}

// set template variables
$template->set_var(array(
	"ACTION2"		=>	"reset",
	"ACTION2_LABEL"	=>	"Cancel",
	"WWW_DIR"		=>	$_PHPLIB['webdir'],
	"PHP_SELF"		=>	$PHP_SELF,
	"SESSION_ID"	=>	$session_id
	));

// set template files
$template->parse("output","activity");
$template->p("output");

// page close functions
page_close();

?>