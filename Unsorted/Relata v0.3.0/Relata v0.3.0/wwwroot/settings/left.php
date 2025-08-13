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

// get user id
$user_id = $auth->auth["uid"];

if($type == "extrafields")
{
	$template->set_file("settings_list","settings_left_xfields.htm");
	
	// make a list of all the buttons
	$query = "SELECT xfield_id, xfield_name FROM $extra_field WHERE user_id='$user_id' ORDER BY xfield_name ASC";
	$db->query($query);
	
	while($db->next_record())
	{
		$btn_label = jsformat($db->f('xfield_name'));
	
		$count_label_chars = strlen(trim($btn_label));
    	if ($count_label_chars > 18)
		{
			$btn_label = substr($btn_label, 0, 15);
			$btn_label .= "...";
		}
		$btn_list .= "btns[".$db->f('xfield_id')."] = addbtn(\"".$btn_label."\",".$db->f('xfield_id').");";
	}
}
else
{
	$template->set_file("settings_list","settings_left_groups.htm");

	// make a list of all the buttons
	$query = "SELECT group_id, group_name FROM $group WHERE user_id='$user_id' ORDER BY group_name ASC";
	$db->query($query);
	
	while($db->next_record())
	{
		$btn_label = jsformat($db->f('group_name'));
	
		$count_label_chars = strlen(trim($btn_label));
    	if ($count_label_chars > 18)
		{
			$btn_label = substr($btn_label, 0, 15);
			$btn_label .= "...";
		}
		$btn_list .= "btns[".$db->f('group_id')."] = addbtn(\"".$btn_label."\",".$db->f('group_id').");";
	}
}

// print the buttons onto the template
$template->set_var(array(
	"ROWS"		=> 	$btn_list,
	"SESSION_ID"	=>	$session_id
	));

$template->parse("output","settings_list");
$template->p("output");

page_close();

?>