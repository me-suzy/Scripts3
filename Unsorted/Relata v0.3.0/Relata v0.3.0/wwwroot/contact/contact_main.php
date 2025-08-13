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
	"auth" => "relata_challenge_auth"
	));

header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

require("lib.php");

// start a new template
$template = start_template();

// NS6 BUG FIX.. expected to be fixed in 6.02. Bug has to do with old layout engine fixed in Mozilla 0.9
if(ereg("Netscape6",$HTTP_USER_AGENT))
{
	$template->set_var(array(
		"NS6_CONTACT_CSS"			=>	"-ns6",
		"ALL_BROWSERS_VISIBLITY"	=>	"visible"
		));
}
else
{
	$template->set_var("ALL_BROWSERS_VISIBLITY","hidden");
}

$template->set_file("form", "contact_main.htm");

// connect to DB
$db = new relata_db;

$user_id = $auth->auth["uid"];

// if there is a specified contact_id we want to view
if(isset($contact_id))
{
	$db->query("SELECT * FROM $contact WHERE contact_id='$contact_id' AND user_id='$user_id' and isdeleted='false'");
}
else 
// just display the first record
{
	$db->query("SELECT * FROM $contact WHERE user_id='$user_id' and isdeleted='false' order by lname,fname,company,website1,website2,contact_lbl5,contact_lbl4,contact_lbl3,contact_lbl2,contact_lbl1 ASC LIMIT 1");
}

// get the info out of the DB
$db->next_record();

// no records in the db
if($db->num_rows() == 0)
{
	header("location: add.php?session_id=" . $session_id);
}

$contact_id = $db->f('contact_id');

// set the default values for 'view' mode
$template->set_var(array(
	"TARGET"		=>	"loader",
	"NOTES"			=>	'<iframe width="100%" height="450" src="ph_comm.php?session_id=' . $session_id . '&contact_id='. $contact_id .'" class="form" name="ph_comm_win"></iframe>',
	"FORM_URL"		=>	"contact_loader.php",
	"LOADER"		=>	'<iframe src="' . $_PHPLIB['webdir'] . 'contact/contact_loader.php?session_id=' . $session_id . '&contact_id='.$contact_id.'" name="loader" width="0" height="0" border="0"></iframe>',
	"PALM_CATNAME"	=>	$db->f('palm_catname'),
	"ACTION2"		=>	"submit",
	"ACTION2_LABEL"	=>	"Delete",
	));

// if we are returning from the add page
if($action == "add")
{
	// reload the left frame
	$misc_javascript = "eval('parent.left' + parent.winid + '.location.reload();');";
	// select the just added button in left frame
	$misc_javascript .= "parent.curbtn = $contact_id";
	$template->set_var("RELOAD",$misc_javascript);
}

// find out which groups this user has created
$db->query("SELECT * FROM $group WHERE user_id='$user_id' ORDER BY group_id ASC");
while($db->next_record())
{
	$groups .= "<input type=\"checkbox\" name=\"group_". $db->f('group_id') . "\" value=\"true\">".$db->f('group_name')."<br>";
}

$template->set_var("GROUPS",$groups);

// fill in the template w/ all the extra fields associated w/ this user
print_xtra_fields();

$template->parse("output","form");
$template->p("output");

page_close();

?>