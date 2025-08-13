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

// get userid
$user_id = $auth->auth["uid"];

// if OK was pressed on the 'groups' page
if($action == "   OK   " && $group_name)
{
 $db->query("select group_name from $group WHERE user_id='$user_id'");
 while($db->next_record())
  {
  	if($db->f('group_name') == $group_name)
	{
		$same_name = "true";
	}
  }
if ($same_name == "true")
 {
 ?>
 <script type="text/javascript">
 alert("You can not enter two groups with the same name");
 </script>
 <?
 }
else
 {
	$db->query("INSERT INTO $group (group_name,group_desc,user_id) VALUES('$group_name','$group_desc','$user_id')");
	$group_id = $db->currid('group_seq');
	header("location: ".$_PHPLIB['webdir']."settings/settings_main.php?session_id=$session_id&group_id=$group_id&mode=add&type=groups");
} 
}
// if OK was pressed on xfields page
else if($action == "   OK   " && $xfield_name)
{
 $db->query("select xfield_name from $extra_field WHERE user_id='$user_id'");
 while($db->next_record())
  {
  	if($db->f('xfield_name') == $xfield_name)
	{
		$same_name = "true";
	}
  }
if ($same_name == "true")
 {
 ?>
 <script type="text/javascript">
 alert("You can not enter two extra field names with the same name");
 </script>
 <?
 }
else
 {
	$db->query("INSERT INTO $extra_field (xfield_name,user_id) VALUES ('$xfield_name','$user_id')");
	$xfield_id = $db->currid('xfield_seq');	
	header("location: ".$_PHPLIB['webdir']."settings/settings_main.php?session_id=$session_id&xfield_id=$xfield_id&mode=add&type=extrafields");
}
}

if($type == "group" || $group_name)
{
	$template->set_file("main","settings_groups.htm");
}
else if($type == "extrafields" || $xfield_name)
{
	$template->set_file("main","settings_xfields.htm");
}

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

// set template variables
$template->set_var(array(
	"ACTION2"		=>	"reset",
	"ACTION2_LABEL"	=>	"Cancel",
	"WWW_DIR"		=>	$_PHPLIB['webdir'],
	"PHP_SELF"		=>	$PHP_SELF,
	"SESSION_ID"	=>	$session_id,
	"TEMPLATE_MODE"	=>	"Add",
	"SETTINGS_NAV"	=>	$settings_nav
	));

// print the template
$template->parse("output","main");
$template->p("output");

// page close functions
page_close();

?>