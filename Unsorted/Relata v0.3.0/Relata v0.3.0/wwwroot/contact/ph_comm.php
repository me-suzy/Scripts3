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

/*
	This is the <iframe> document that handels all of the user comments and phone communications
	Last Modified by : Jeremy Rempel for Relata
*/

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

// get the user id
$user_id = $auth->auth["uid"];

$template->set_file("main","phone_comm.htm");

// get the time & date
$date = date("Y-m-d");
$time = date("g:i a");

// get the current user notes from the DB
$db->query("SELECT user_notes FROM $contact WHERE contact_id='$contact_id' AND user_id='$user_id' and isdeleted='false'");

$db->next_record();

$user_notes = $db->f('user_notes');

// incoming call button was pressed, add header
if($action == "incoming")
{
	// add incoming call title to notes
	$incoming = "[$date] <$time> ~~~~ Incoming Call ~~~~\n";
	$user_notes = $incoming . addslashes($db->f('user_notes')) . "\n";
	$db->query("UPDATE $contact SET user_notes='$user_notes',last_mod_date='today()',last_mod_time='now()', ismodified='true' WHERE contact_id='$contact_id' AND user_id='$user_id'");
	
	// set a flag to tell the script we are in the middle of a phone conversation
	$phone_call = "y";
	
	// focus the form field
	$field_focus = true;
}
// outgoing call button was pressed add header
else if($action == "outgoing")
{
	// add outgoing call title to notes
	$outgoing = "[$date] <$time> ~~~~ Outgoing Call ~~~~\n";
	$user_notes = $outgoing . addslashes($db->f('user_notes')) . "\n";
	$db->query("UPDATE $contact SET user_notes='$user_notes',last_mod_date='today()',last_mod_time='now()', ismodified='true' WHERE contact_id='$contact_id' AND user_id='$user_id' and isdeleted='false'");
	
	// set a flag to tell the script we were in the middle of a phone conversation
	$phone_call = "y";

	// focus the form field
	$field_focus = true;
}
else if($action == "End Call")
{
	$end_call = "[$date] <$time> ~~~~  End of Call  ~~~~\n";
	$user_notes = $end_call . addslashes($db->f('user_notes')) . "\n";
	$db->query("UPDATE $contact SET user_notes='$user_notes',last_mod_date='today()',last_mod_time='now()', ismodified='true' WHERE contact_id='$contact_id' AND user_id='$user_id'");
	
	$phone_call = "n";
}
// user made changes to the textarea field save it to the DB
else if($action == "Save")
{
	$user_notes = $ph_comm_textarea;
	$db->query("UPDATE $contact SET user_notes='$user_notes',last_mod_date='today()',last_mod_time='now()', ismodified='true' WHERE contact_id='$contact_id' AND user_id='$user_id'");
}
// user entered something in... add it to db
else if($ph_comm_notes)
{
	$time = date("g:i a");
	
	$ph_comm_notes = "[$date] <$time> $ph_comm_notes \n" . addslashes($user_notes);
	
	$user_notes = $ph_comm_notes;
	
	$query = "UPDATE $contact SET user_notes='$ph_comm_notes',last_mod_date='today()',last_mod_time='now()', ismodified='true' WHERE contact_id='$contact_id' AND user_id='$user_id'";
	$db->query($query);

	// focus the form field
	$field_focus = true;
}

if($field_focus)
{
	$template->set_var("FIELD_FOCUS","document.ph_comm_form.ph_comm_notes.focus();");
}

$template->set_var("PHONE_CALL", $phone_call);

// if we are in the middle of a phone call show the 'end call' button
if($phone_call == "y")
{
	$template->set_var("EXTRA_ACTION","End Call");
}
// else display the 'save' button
else
{
	$template->set_var("EXTRA_ACTION","Save");
}


$template->set_var(array(
	"PHP_SELF"			=>	$PHP_SELF,
	"SESSION_ID"		=>	$session_id,
	"MESSAGE"			=>	$message,
	"CONTACT_ID"		=>	$contact_id,
	"PH_COMM_TEXTAREA"	=>	stripslashes($user_notes)
));

$template->parse("output","main");
$template->p("output");

page_close();

?>