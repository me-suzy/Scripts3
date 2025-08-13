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

// start a new template
$template = start_template();

// open connection to db
$db = new relata_db;

// set template files
$template->set_file("main","opportunity_main.htm");

// get userid
$user_id = $auth->auth["uid"];

// set the default values for 'view' mode
$template->set_var(array(
	"TARGET"		=>	'target="loader"',
	"FORM_URL"		=>	"contact_loader.php",
	"LOADER"		=>	'<iframe src="' . $_PHPLIB['webdir'] . 'opportunity/opp_loader.php?session_id=' . $session_id . '&opp_id='.$opp_id.'" name="loader" width="0" height="0"></iframe>',
	"ACTION2"		=>	"submit",
	"ACTION2_LABEL"	=>	"Delete",
	"FORM_URL"		=>	$_PHPLIB['webdir'] . "opportunity/opp_loader.php?session_id=$session_id",
	));

// if we are returning from the add page
if($mode == "add")
{
	// reload the left frame
	$misc_javascript = "eval('parent.left' + parent.winid + '.location.reload();');";
	// select the just added button in left frame
	$misc_javascript .= "parent.curbtn = $opp_id";
	$template->set_var("RELOAD",$misc_javascript);
}

// build a list of all the sales stages
$db->query("SELECT * FROM $sales_stage ORDER BY ss_id ASC");
while($db->next_record())
{
	$ss_id = $db->f('ss_id');
	$ss_title = $db->f('ss_title');
	$salesstages .= "<option value=\"$ss_id\">$ss_title</option>";
}

// set template variables
$template->set_var(array(
	"CONTACTS"      =>  $avail_contacts,
	"SALES_STAGES"	=>	$salesstages
	));

// print the template
$template->parse("output","main");
$template->p("output");

// page close functions
page_close();

?>