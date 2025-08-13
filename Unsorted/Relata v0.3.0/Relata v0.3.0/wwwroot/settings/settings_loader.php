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

// force browser to not cache page
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

// perform all startup functions
page_open(array(
	"sess" => "relata_session", 
	"auth" => "relata_challenge_auth"
	));

$db = new relata_db;

$user_id = $auth->auth["uid"];

echo $action;

// update the db
if($action == "   OK   " && $group_id)
{
 $db->query("select * from $group WHERE user_id='$user_id'");
 while($db->next_record())
  {
  	if($db->f('group_name') == $group_name && $group_id != $db->f('group_id'))
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
	$db->query("UPDATE $group SET group_name='$group_name', group_desc='$group_desc' WHERE group_id='$group_id' AND user_id='$user_id'");
}
}
// delete the group and its links w/ the contact table
else if($action == " Delete " && $group_id)
{
	$db->query("DELETE FROM $contact_group WHERE group_id='$group_id'");
	$db->query("DELETE FROM $group WHERE group_id='$group_id' AND user_id='$user_id'");
	
	unset($group_id);
}

if($action == "   OK   " && $xfield_id)
{
 $db->query("select * from $extra_field WHERE user_id='$user_id'");
 while($db->next_record())
  {
  	if($db->f('xfield_name') == $xfield_name && $xfield_id != $db->f('xfield_id'))
	{
		$same_name = "true";
	}
  }
if ($same_name == "true")
 {
 ?>
 <script type="text/javascript">
 alert("You can not enter two extra fields with the same name");
 </script>
 <?
 }
else
 {
	$db->query("UPDATE $extra_field SET xfield_name='$xfield_name' WHERE xfield_id='$xfield_id' AND user_id='$user_id'");	
}
}
else if($action == " Delete " && $type == "extrafields")
{
	// delete all of the extra field data
	$db->query("DELETE FROM $contact_xfield WHERE xfield_id='$xfield_id'");
	// delete the actual definition
	$db->query("DELETE FROM $extra_field WHERE xfield_id='$xfield_id' AND user_id='$user_id'");	
	unset($xfield_id);
}

// VIEW RECORDS

echo '<html><script type="text/javascript">';

// we are on extra fields page
if($type == "extrafields" || $xfield_id)
{
	if($xfield_id)
	{
		$db->query("SELECT * FROM $extra_field WHERE xfield_id='$xfield_id' AND user_id='$user_id'");
	}
	else
	{
		$db->query("SELECT * FROM $extra_field WHERE user_id='$user_id' ORDER BY xfield_name ASC LIMIT 1");
	}
	$db->next_record();

	// if there is no records
	if($db->num_rows())
	{
	
		$xfield_id = $db->f('xfield_id');
		
		// write the JS that loads up the fields
		// has been compressed w/o comments for speed
		?>
		f=parent.document.settings
		f.xfield_id.value="<? echo $xfield_id; ?>"
		f.xfield_name.value="<? echo jsformat($db->f('xfield_name')); ?>"
		<?
		
		$btnid = $xfield_id;
	}
	else
	{
		$no_records = true;
	}
}
// we are on groups page
else
{
	// if a group id is specified.. else pick the first record in db
	if($group_id)
	{
		$db->query("SELECT * FROM $group WHERE group_id='$group_id' AND user_id='$user_id'");
	}
	else
	{
		$db->query("SELECT * FROM $group WHERE user_id='$user_id' ORDER BY group_name ASC LIMIT 1");
	}
	$db->next_record();

	// if there is no records
	if($db->num_rows())
	{
		$group_id = $db->f('group_id');
		
		// write the JS that loads up the fields
		// has been compressed,w/o comments for speed
		?>f=parent.document.settings
		f.group_id.value="<? echo $group_id; ?>"
		f.group_name.value="<? echo jsformat($db->f('group_name')); ?>"
		f.group_desc.value="<? echo textarea_format($db->f('group_desc')); ?>";<?
		
		$btnid = $group_id;
	}
	else
	{
		$no_records = true;
		$type="group";
	}
}

// select the just added button in left frame
if($no_records)
{
	?>
	parent.location.href="add.php?session_id=<? echo $session_id; ?>&type=<? echo $type; ?>";
	eval('parent.parent.left' + parent.parent.winid + '.location.reload();');
	<?
}

if($action == " Delete " or $action == "   OK   ")
{
	// reload the left frame
	echo "eval('parent.parent.left' + parent.parent.winid + '.location.reload();');";
	
	if(!$no_records)
	{
		echo "parent.parent.curbtn = $btnid";
	}
}

echo '</script></html>';

page_close();

?>