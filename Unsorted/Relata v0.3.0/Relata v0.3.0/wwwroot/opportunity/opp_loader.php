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
	"auth" => "relata_challenge_Auth"
	));

$db = new relata_db;

$user_id = $auth->auth["uid"];

if($action == "   OK   ")
{

	$cnt = count($opp_contacts);
	
	//delete all contacts associated with this opportunity
	$db->query("DELETE FROM $contact_opportunity WHERE opp_id='$opp_id'");
	
	//insert contacts chosen for this opportunity
	for($i = 0;$i < $cnt; $i++)
	{
			$db->query("INSERT INTO $contact_opportunity (contact_id,opp_id) VALUES ('$opp_contacts[$i]','$opp_id')");
	}
	
	// if there is no sales stages defined set it to default 0
	if(!$ss_id)
	{
		$ss_id = 0;
	}

	// update the actual opportunity
	$db->query("UPDATE $opportunity SET opp_title='$opp_title', opp_desc='$opp_desc', potential_revenue='$potential_revenue', probability='$probability', close_date='$close_date', ss_id='$ss_id' WHERE opp_id='$opp_id' AND user_id='$user_id'");
}
else if($action == " Delete ")
{
	// delete the account
	$db->query("DELETE FROM $opportunity WHERE opp_id='$opp_id' AND user_id='$user_id'");
	
	// delete the contact associations
	if($contact_id)
		$db->query("DELETE FROM $contact_opportunity WHERE opp_id='$opp_id' AND contact_id='$contact_id'");
	
	unset($opp_id);
}

// view opportunity
if($opp_id)
{
	$db->query("SELECT * FROM $opportunity WHERE opp_id='$opp_id' AND user_id='$user_id' ORDER by opp_title ASC");
}
else
{
	$db->query("SELECT * FROM $opportunity WHERE user_id='$user_id' ORDER by opp_title ASC LIMIT 1");
}
$db->next_record();

$opp_id = $db->f('opp_id');

if($db->num_rows())
{
	// write the JS that loads up the fields
	// has been compressed,w/o comments for speed
	?><html><script type="text/javascript">
	f=parent.document.opportunity_form;
	<?
	// check to see if the loader is being loaded for the add page or not
	if($add_contact != 1)
    {
	?>
	f.opp_id.value="<? echo jsformat($opp_id); ?>"
	f.opp_title.value="<? echo jsformat($db->f('opp_title')); ?>"
	f.opp_desc.value="<? echo textarea_format($db->f('opp_desc')); ?>"
	f.potential_revenue.value="<? echo jsformat($db->f('potential_revenue')); ?>"
	f.close_date.value="<? echo $db->f('close_date'); ?>"
	parent.clear_opp_contacts();<?
	
	if($db->f('probability'))
	{
		echo "f.probability.selectedIndex = ". $db->f('probability') .";";
	}
	
	?>
	f.ss_id.selectedIndex = <? echo $db->f('ss_id')-1; ?>;
	<?
	// get a list of all the contacts associated with this opportunity
	$db->query("SELECT contact_id FROM $contact_opportunity WHERE opp_id='$opp_id'");
	while($db->next_record())
	{
		$opp_contacts_id[] = $db->f('contact_id');
	}

	// get the names associated with the contacts
	$cnt = count($opp_contacts_id);
	for($i = 0; $i < $cnt; $i++)
	{
		$db->query("SELECT fname,lname FROM $contact WHERE contact_id='$opp_contacts_id[$i]'");
		$db->next_record();
	
		$fname = $db->f('fname');
		$lname = $db->f('lname');

			echo "parent.new_opp_contact('".jsformat($lname).", ".jsformat($fname)."','$opp_contacts_id[$i]');";
	}
	// build a list of all contacts
	$i = 0;
	$db->query("SELECT contact_id,fname,lname FROM $contact WHERE user_id='$user_id'");
	while($db->next_record())
	{
		$all_contacts_id[$i] = $db->f('contact_id');
		$all_contacts_name[$i] = $db->f('lname') . ', ' . $db->f('fname');
		$i++;
	}

	// find out which contacts aren't already associated with the opportunity
	$cnt = count($all_contacts_id);
	for($i = 0; $i < $cnt; $i++)
	{
		$db->query("SELECT contact_id FROM $contact_opportunity WHERE contact_id='$all_contacts_id[$i]' && opp_id='$opp_id'");
		if(!$db->num_rows())
		{
			echo "parent.new_avail_contact('".jsformat($all_contacts_name[$i])."','".jsformat($all_contacts_id[$i])."');";
		}
	}
}
	// if the page is being loaded for the add page, do not load all the opportunity variables, build the appropriate contact list
	elseif($add_contact == 1)
	{
	?>
		parent.clear_opp_contacts();
	<?
		// build a list of all contacts
	$i = 0;
	$db->query("SELECT contact_id,fname,lname FROM $contact WHERE user_id='$user_id'");
	while($db->next_record())
	{
		$all_contacts_id[$i] = $db->f('contact_id');
		$all_contacts_name[$i] = $db->f('lname') . ', ' . $db->f('fname');
		$i++;
	}
	// find out which contacts aren't already associated with an account
	$cnt = count($all_contacts_id);
	for($i = 0; $i < $cnt; $i++)
	{
		echo "parent.new_avail_contact('".jsformat($all_contacts_name[$i])."','".jsformat($all_contacts_id[$i])."');";
	}
}

	if($action == " Delete " || $action == "   OK   ")
	{
		// reload the left frame
		echo "eval('parent.parent.left' + parent.parent.winid + '.location.reload();');";
	
		if(!$no_records)
		{
			// select the just added button in left frame
			echo "parent.parent.curbtn = $opp_id";
		}
	}
	?></script></html><?
}
else if ($add_contact != 1)
{
	?>
	<script type="text/javascript">
	parent.location.href="add.php?session_id=<? echo $session_id; ?>&add_contact=1";
	eval('parent.parent.left' + parent.parent.winid + '.location.reload();');
	</script>
	<?
}
else
{
	?>
	<script type="text/javascript">
	parent.clear_opp_contacts();
	<?
	// build a list of all contacts
	$i = 0;
	$db->query("SELECT contact_id,fname,lname FROM $contact WHERE user_id='$user_id'");
	while($db->next_record())
	{
		$all_contacts_id[$i] = $db->f('contact_id');
		$all_contacts_name[$i] = $db->f('lname') . ', ' . $db->f('fname');
		$i++;
	}
	// find out which contacts aren't already associated with the opportunity
	$cnt = count($all_contacts_id);
	for($i = 0; $i < $cnt; $i++)
	{
		echo "parent.new_avail_contact('".jsformat($all_contacts_name[$i])."','".jsformat($all_contacts_id[$i])."');";
	}
	?>
	</script>
	<?
}

page_close();

?>