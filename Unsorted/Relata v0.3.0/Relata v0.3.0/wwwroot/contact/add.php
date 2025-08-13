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
require("lib.php");

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

// open a db connection
$db = new relata_db;

// set template files
$template->set_file("form", "contact_main.htm");

// get the userid
$user_id = $auth->auth["uid"];

// print the extra fields onto the template
print_xtra_fields();

// set template variables
$template->set_var(array(
	"TEMPLATE_MODE"=>"Add",
	"MODE"		=>	"add",
	"FORM_URL"	=>	"add.php",
	"NOTES"		=>	'<textarea name="notes" class="form" rows="10" cols="50"></textarea>'
	));

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

// if page has been submitted
if($action == "   OK   ")
{
	$today = today();
	$now = now();
	
	$db->query("INSERT INTO $contact (user_id, fname, mname, lname, prefix, title, company, hm_address, hm_city, hm_state, hm_zip, hm_country, bus_address, bus_city, bus_state, bus_zip, bus_country, alt_address, alt_city, alt_state, alt_zip, alt_country, website1, website2, palm_custfield1, palm_custfield2, palm_custfield3, palm_custfield4, palm_notes, palm_category, user_notes, email_type, is_prospect, entry_date, entry_time, last_mod_date, last_mod_time, contact_lbl1,contact_lbl2,contact_lbl3,contact_lbl4,contact_lbl5, contact_val1,contact_val2,contact_val3,contact_val4,contact_val5,palm_record_id, palm_dphone) VALUES ('$user_id', '$fname', '$mname', '$lname', '$prefix', '$title',	'$company', '$hm_street', '$hm_city', '$hm_state', '$hm_zip', '$hm_country', '$bus_street', '$bus_city', '$bus_state', '$bus_zip', '$bus_country', '$alt_street', '$alt_city', '$alt_state', '$alt_zip', '$alt_country', '$website1', '$website2', '$palm_custfield1', '$palm_custfield2', '$palm_custfield3', '$palm_custfield4', '$palm_notes', '0', '$notes', '$email_type', '$is_prospect', '$today', '$now', '$today', '$now', '$contact_lbl1','$contact_lbl2','$contact_lbl3','$contact_lbl4','$contact_lbl5', '$contact_val1','$contact_val2','$contact_val3','$contact_val4','$contact_val5','0', '$palm_dphone')");
	
	$contact_id = $db->currid("contact_seq");
	
	// GROUPS
	// make a list of all the possible groups
	$db->query("SELECT group_id FROM $group WHERE user_id='$user_id'");
	while($db->next_record())
	{
		$group_ids[] = $db->f('group_id');
	}
	
	$group_id_cnt = count($group_ids);
	
	// look for matches
	for($i = 0; $i < $group_id_cnt; $i++)
	{
		// if the box is checked INSERT a new association record
		if(${$group_ids[$i]})
		{
			$db->query("INSERT INTO $contact_group (contact_id,group_id) VALUES ('$contact_id','$group_ids[$i]')");
		}
	}

	// EXTRA FIELDS
	$j = 0;
	// get a list of all the extra field definitions
	$db->query("SELECT xfield_id, xfield_name FROM $extra_field WHERE user_id='$user_id'");
	while($db->next_record())
	{
		$xfield_form[$j] = $db->f('xfield_id');
		++$j;
	}
	
	// loop through all of extra fields
	for($i = 0; $i < $j; ++$i)
	{
		// if $(xfield_id) is not blank add it to the DB query
		if(${$xfield_form[$i]} != "")
		{
			$query = "INSERT INTO $contact_xfield (contact_id, xfield_id, xfield_value) VALUES ('$contact_id', '$xfield_form[$i]', '${$xfield_form[$i]}')";
			
			$db->query($query);
		}
	}

	// view the just added record in the current frame
	header("location: " . $_PHPLIB['webdir'] . "contact/contact_main.php?&action=add&session_id=" . $session_id . "&contact_id=" . $contact_id);
}

// get the list of groups
$db->query("SELECT group_id, group_name FROM $group WHERE user_id='$user_id'");

while($db->next_record())
{
	$groups .= "<input type=\"checkbox\" name=\"".$db->f('group_id')."\" value=\"true\" $checked />".$db->f('group_name')."<br>";
}

// reload left frame
if($reload)
{
	$template->set_var("RELOAD","eval('parent.left' + parent.winid + '.location.reload();');");
}

// set template variables
$template->set_var(array(
	"ACTION2_LABEL"	=> "Cancel",
	"ACTION2"		=>	"reset",
	"GROUPS"		=>	$groups
	));

// print the template
$template->parse("output","form");
$template->p("output");

// page close functions
page_close();



?>
