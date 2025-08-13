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

$user_id = $auth->auth["uid"];

// if user clicked 'OK' on activity detail page
if($action == "  OK  " && $source == "activity_detail")
{
	if((!$starttime) || ($is_calendar_item == "Y2") || ($is_calendar_item == "N"))
	{
		$starttime = "00:00:00";
	}
	
	if((!$endtime) || ($is_calendar_item == "Y2") || ($is_calendar_item == "N"))
	{
		$endtime = "00:00:00";
	}
	
	if($is_calendar_item == "Y2")
	{
		$is_calendar_item = "Y";
	}
	
	if($is_calendar_item == "N")
	{
		$is_calendar_item = " ";
	}
	$db->query("SELECT * FROM $activity WHERE date='$event_date'");
	
	$db->query("UPDATE $activity SET activity_desc='$activity_desc', date='$event_date', starttime='$starttime', endtime='$endtime',
	priority='$priority', is_calendar_item='$is_calendar_item', ismodified='true', palm_record_id='0' WHERE user_id='$user_id' AND activity_id='$activity_id'");
	
	$template->set_var("RELOAD","reload=true;");
	
	$template->set_file("activity", "activity_detail.htm");
	
	unset($date);
}
// if user clicked 'OK' on activity week page
else if($action == "  OK  " && $source == "activity_week")
{
	$template->set_var("RELOAD","reload=true;");
	
	$date = addleadingzeros($date);

	// go through each possible time of day and perform a update on the db
	if($edit_notime)
	{
		// check to see if there is an entry for this already
		$db->query("SELECT * FROM $activity WHERE date='$date' AND is_calendar_item!='Y'");
		
		if($db->num_rows())
		{
			$db->query("UPDATE $activity SET activity_desc='$edit_notime', ismodified='true' WHERE date='$date' AND is_calendar_item!='Y'");
		}
		else
		{
			$db->query("INSERT INTO $activity (user_id,activity_desc,date) VALUES ('$user_id','$edit_notime','$date')");
		}
	}
	
	// update the activities
	function update_act($act_time)
	{
		global $db, $date, $user_id;
		global $activity;
		
		$form_field = "edit_" . $act_time;
		global ${$form_field};

		// check to see if there is already an entry for this in the DB
		$db->query("SELECT * FROM $activity WHERE date='$date' AND is_calendar_item='Y' and starttime='$act_time:00:00'");
		
		// if there is a db entry already and a value for this time.. update it
		if($db->num_rows() && ${$form_field})
		{
			$query = "UPDATE $activity SET activity_desc='${$form_field}', ismodified='true' WHERE date='$date' AND starttime='$act_time:00:00' AND is_calendar_item='Y'";
			$db->query($query);
		}

		// no entry already, but there is a value for this time add it
		else if(${$form_field})
		{
			$db->query("INSERT INTO $activity (user_id,activity_desc,date,starttime,is_calendar_item) 
						VALUES ('$user_id','${$form_field}','$date','$act_time:00:00', 'Y')");
		}
		// there is a db entry but the user deleted it from the web page ( clean it out of the db )
		else if($db->num_rows() && !${$form_field})
		{
			$db->query("DELETE FROM $activity WHERE user_id='$user_id' AND date='$date' AND starttime='$act_time:00:00'");
		}
	}
	
	for($i = 8; $i <= 19; $i++)
	{
		update_act($i);
	}
	
	$template->set_file("activity", "activity_week.htm");
}
else if($action == "Delete")
{
	$db->query("DELETE FROM $activity WHERE user_id='$user_id' AND activity_id='$activity_id'");
	
	$date = date("Y-m-d");
	unset($activity_id);
	
	$template->set_var("RELOAD","reload=true;");
}

// view activity detail
if($activity_id)
{
	$template->set_file("activity", "activity_detail.htm");
	
	$db->query("SELECT * FROM $activity WHERE activity_id='$activity_id' AND user_id='$user_id'");
	$db->next_record();
	
	$startyear = date("Y");
	
	// setup the template files
	$month_temp = "DATE_MONTH_" . $month;
	$day_temp = "DATE_DAY_" . $day;
	
	// build a list of the next 10 years and past 5
	for($i = ($startyear - 5); $i < (date("Y") + 10); $i++)
	{
		if($year == $i)
		{
			$selected = "SELECTED";
		}
		else
		{
			unset($selected);
		}
		
		$year_list .= "<option value=\"$i\" $selected>$i</option>\n";
	}

	if(($db->f('is_calendar_item') == "Y") && ($db->f('starttime') != "00:00:00"))
	{
		$is_calendar_item = "checked";
	}
	if(($db->f('is_calendar_item') == "Y") && ($db->f('starttime') == "00:00:00"))
	{
		$is_untimed_calendar_item = "checked";
	}
	if(($db->f('is_calendar_item') == "") && ($db->f('starttime') == "00:00:00"))
	{
		$non_calendar_item = "checked";
	}
	// setup the SELECT boxes
	$priority = "PRIORITY_" . $db->f('priority');
	$starttime = explode(":",$db->f('starttime'));
	$endtime = explode(":",$db->f('endtime'));
	
	$template->set_var(array(
		"ACTION2"						=>	"submit",
		"ACTION2_LABEL"					=>	"Delete",
		"ACTIVITY_ID"					=>	$activity_id,
		"ACTIVITY_DESC"					=>	$db->f('activity_desc'),
		"STARTTIME_" . $starttime[0]	=>	"SELECTED",
		"ENDTIME_" . $endtime[0]		=>	"SELECTED",
		"ENDTIME"						=>	$db->f('endtime'),
		"YEAR_LIST"						=>	$year_list,
		"IS_CALENDAR_ITEM"              =>	$is_calendar_item,
		"IS_UNTIMED_CALENDAR_ITEM"      =>	$is_untimed_calendar_item,
		"NON_CALENDAR_ITEM"             =>	$non_calendar_item,
		"EVENT_DATE"					=>  $db->f('date'),
		$month_temp						=>	"SELECTED",
		$day_temp						=>	"SELECTED",
		$priority						=>	"SELECTED"
	));
}
else
{
	if(!$date)
	{
		$date = date("Y-m-d");
	}
	
	$template->set_file("activity", "activity_week.htm");
}

// show this date
if($date && !$activity_id)
{
	$template->set_file("activity", "activity_week.htm");
	
	if($reload)
	{
		$template->set_var("RELOAD","reload=true;");
	}
	
	$date = removeleadingzeros($date);
	
	// format the date like "January, 1 2001" rather than "1-1-2001"
	$month_list = array("","January","Febuary","March","April","May","June","July","August","September","October","November","December");
	$bdate = explode("-",$date);
	
	$year = $bdate[0];
	$month = $bdate[1];
	$day = $bdate[2];
	
	$fmonth = $month_list[$month];
	$fdate = $fmonth . " " . $day . ", " . $year;
	
	$template->set_var("FDATE", $fdate);
	
	// put it back together
	$date = $year . "-" . $month . "-" . $day;
	
	$template->set_var("DATE",$date);
	
	$date = addleadingzeros($date);
	
	// get all of the activities for this day
	$query = "SELECT * FROM $activity WHERE user_id='$user_id' AND date='$date'";
	$db->query($query);
	
	while($db->next_record())
	{
		// convert 13:30:00 to 1PM ...
		$act_time = explode(":",$db->f('starttime'));
		
		// not a calendar item
		if($db->f('is_calendar_item') != "Y")
		{
			$act_time = "NOTIME";
		}
		else 
		{
			$act_time = $act_time[0];
		}
		
		$template->set_var($act_time,$db->f('activity_desc'));
	}
}

$template->set_var(array(
	"WWW_DIR"	=>	$_PHPLIB['webdir'],
	"PHP_SELF"	=>	$PHP_SELF,
	"SESSION_ID"=>	$session_id
	));

$template->parse("output","activity");
$template->p("output");

page_close();


?>