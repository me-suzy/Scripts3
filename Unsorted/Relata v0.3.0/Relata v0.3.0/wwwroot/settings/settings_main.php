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

// connect to DB
$db = new relata_db;

$user_id = $auth->auth["uid"];

// if we are returning from the add page
if($mode == "add")
{
	if($xfield_id != "")
	{
		$btnid = $xfield_id;
	}
	else if($group_id != "")
	{
		$btnid = $group_id;
	}
	// reload the left frame
	$misc_javascript = "parent.curbtn = $btnid;";
	$misc_javascript .= "eval('parent.left' + parent.winid + '.location.reload();');";
	// select the just added button in left frame
	$template->set_var("RELOAD",$misc_javascript);
}

// set the default values for 'view' mode
$template->set_var(array(
	"TARGET"		=>	'target="loader"',
	"NOTES"			=>	'<iframe width="100%" height="100%" src="ph_comm.php?session_id=' . $session_id . '&contact_id='. $contact_id .'" class="form" name="ph_comm_win"></iframe>',
	"FORM_URL"		=>	$_PHPLIB['webdir'] . "settings/settings_loader.php",
	"LOADER"		=>	'<iframe src="'.$_PHPLIB['webdir'].'settings/settings_loader.php?session_id='.$session_id.'&type='.$type.'&group_id='.$group_id.'&xfield_id='.$xfield_id.'" name="loader" width="0" height="0"></iframe>',
	"ACTION2"		=>	"submit",
	"ACTION2_LABEL"	=>	"Delete",
	));

// defaulted to groups
if(($type == "extrafields") || ($xfield_id != ""))
{
	$template->set_file("settings","settings_xfields.htm");
	
	// set the xtra fields radio button to checked
	$xtra_checked = 'selected';
}
else
{
	$template->set_file("settings","settings_groups.htm");
	
	// set the radio button to checked
	$groups_checked = 'selected';
}

// add this to every page except when 'adding' a new record
$settings_nav = "
<div class=\"form\">
<select name=\"type\" onchange=\"changetype(this)\">
<option class=\"form\" value=\"groups\" $groups_checked  style=\"background: #ffffff;\"/>Groups</option>
<option class=\"form\" value=\"extrafields\" $xtra_checked  style=\"background: #ffffff;\"/>Extra Fields</option>
</select>
<div>";

$template->set_var(array(
	"ACTION2"		=>	"submit",
	"ACTION2_LABEL"	=>	"Delete",
	"WWW_DIR"		=>	$_PHPLIB['webdir'],
	"PHP_SELF"		=>	$PHP_SELF,
	"SESSION_ID"	=>	$session_id,
	"SETTINGS_NAV"	=>	$settings_nav,
	));

$template->parse("output","settings");
$template->p("output");

page_close();

?>