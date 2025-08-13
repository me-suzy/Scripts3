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

// this page does not use templates for speed purposes, all JS has been stripped of all comments and compressed

include("../config.inc.php");

//  We use the following features:
//  sess   for session variables
//  auth   for login checks, also required for user variables

// force browser to not cache page
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

// functions used within this page are stored here
include("lib.php");

// perform all startup functions
page_open(array(
	"sess" => "relata_session", 
	"auth" => "relata_challenge_auth"
	));

$user_id = $auth->auth["uid"];

// open a db connnection
$db   = new relata_db();


if($action == "   OK   ")
{
	// db doesn't like null values for integer fields
	if(!$palm_id)
	{
		$palm_id = "0";
	}	
	if(!$palm_record_id)
	{
		$palm_record_id = "0";
	}

	// check the checkboxes
	$db->query("SELECT group_id FROM $group WHERE user_id='$user_id'");
	while($db->next_record())
	{
		$group_ids[] = $db->f('group_id');
		$group_id_names[] = "group_" . $db->f('group_id');
	}
	
	$group_id_cnt = count($group_ids);
	
	// look for matches
	for($i = 0; $i < $group_id_cnt; $i++)
	{
		// if the box is checked either INSERT or UPDATE the record
		if(${$group_id_names[$i]})
		{
			// check to see if there is already a record for this
			$db->query("SELECT * FROM contact_group WHERE contact_id='$contact_id' AND group_id='$group_ids[$i]'");
			if(!$db->num_rows())
			{
				$db->query("INSERT INTO contact_group (contact_id,group_id) VALUES ('$contact_id','$group_ids[$i]')");
			}
		}
		// if the box is unchecked.. delete the record (if it exists)
		else
		{
			$db->query("SELECT * FROM $contact_group WHERE contact_id='$contact_id' AND group_id='$group_ids[$i]'");
			if($db->num_rows())
			{
				$db->query("DELETE FROM $contact_group WHERE contact_id='$contact_id' AND group_id='$group_ids[$i]'");
			}
		}
	}
	
	$today = today();
	$now = now();

	$db->query("UPDATE $contact SET fname='$fname', mname='$mname', lname='$lname', title='$title', hm_address='$hm_street', hm_city='$hm_city', hm_state='$hm_state',hm_zip='$hm_zip', hm_country='$hm_country', company='$company', bus_address='$bus_street', bus_city='$bus_city', bus_state='$bus_state', bus_zip='$bus_zip', bus_country='$bus_country', alt_address='$alt_street', alt_city='$alt_city', alt_state='$alt_state', alt_zip='$alt_zip', alt_country='$alt_country', website1='$website1', 	website2='$website2', palm_custfield1='$palm_custfield1', 	palm_custfield2='$palm_custfield2', palm_custfield3='$palm_custfield3', palm_custfield4='$palm_custfield4', palm_notes='$palm_notes', palm_id='$palm_id', palm_notes='$palm_notes', email_type='$email_type', is_prospect='$is_prospect', prefix='$prefix', last_mod_date='$today', last_mod_time='$now', contact_lbl1='$contact_lbl1', contact_lbl2='$contact_lbl2', contact_lbl3='$contact_lbl3', contact_lbl4='$contact_lbl4', contact_lbl5='$contact_lbl5', contact_val1='$contact_val1', contact_val2='$contact_val2', contact_val3='$contact_val3', contact_val4='$contact_val4', contact_val5='$contact_val5', palm_record_id='0', palm_category='0', palm_dphone='$palm_dphone', user_notes='$user_notes', ismodified='true' WHERE contact_id='$contact_id'");

	////////////////////////////////////////////////////////////////////////////////////////
	// get a list of all the extra field definitions
	$db->query("SELECT xfield_id FROM $extra_field WHERE user_id='$user_id'");
	
	$j = 0;
	while($db->next_record())
	{
		// name of the xfield variables ( from the form )
		// use ${$xfield_names[$i]} to refer to the value of the form field
		$xfield_names[$j] = "xfield_" . $db->f('xfield_id');
		// id of the xfields
		$xfield_ids[$j] = $db->f('xfield_id');
		++$j;
	}
	
	// # of extra fields associated with this contact
	$num_xfields = $db->num_rows();
	
	// loop through all of extra fields
	for($i = 0; $i < $num_xfields; ++$i)
	{
		// check to see if there is already a entry in the DB for this value
		$db->query("SELECT * FROM $contact_xfield WHERE xfield_id='$xfield_ids[$i]' AND contact_id='$contact_id'");
		
		// if there is not already a record for this entry insert it rather than update
		if($db->num_rows() < 1 && ${$xfield_names[$i]})
		{
			$db->query("INSERT INTO $contact_xfield (contact_id, xfield_id, xfield_value) 
			VALUES ('$contact_id', '$xfield_ids[$i]', '${$xfield_names[$i]}')");
		}
		else
		{
			$db->query("UPDATE $contact_xfield SET xfield_value ='${$xfield_names[$i]}'
						WHERE xfield_id='$xfield_ids[$i]' AND contact_id='$contact_id'");
		}
	}
	
	?>
	<html>
	<script type="text/javascript">
	parent.parent.curbtn = "<? echo $contact_id; ?>";
	eval('parent.parent.left' + parent.parent.winid + '.location.reload();');
	f=parent.document;
	f.contact_id_id.value="<? echo $contact_id; ?>";
	parent.parent.location.reload();
	</script>	
	</html>	
	<?
}
else if($action == " Delete ")
{
	$contact_delete == "";
	//select all accounts associated with the contact
	$db->query("SELECT * FROM $contact_account WHERE contact_id='$contact_id'");
	if($db->num_rows())
	{
		while($db->next_record())
		{
			//select all the contacts associated with the account
			$account_id = $db->f('account_id');
			$db->query("SELECT * FROM $contact_account WHERE account_id='$account_id'");
			$db->next_record();
			//if there is only one record, the account must be deleted as well, ask the user if they are sure they want to delete the account
			if($db->num_rows() == 1)
			{
				$db->query("SELECT * FROM $account WHERE account_id='$account_id'");
				$db->next_record();
				$account_name = $db->f('account_name');
				$error_message = "This is the only contact associated with the $account_name account, deleting this contact will delete the $account_name account as well. Are you sure you want to delete this contact?";
				?>
  				<script language="JavaScript">
  				if (confirm("<?echo $error_message;?>"))
				{
	open("delete_contact.php?account_id=<?echo$account_id;?>&contact_id=<?echo$contact_id;?>&session_id=<?echo$session_id;?>","new_window","toolbar=0,location=0,status=0,menubar=0,scrollbars=0,resizable=0,width=200,height=15,top=0,left=0");
				}
				</script>
				<?
			}
			else
			{
				//if there is more than one record, set contact_delete and break out of the loop
				$contact_delete = "true";
				break;
			}
		}
		if($contact_delete == "true")
		{
			//delete the contact
			delete_contact($contact_id);
		}
	}
	else
	{
		//delete the contact
		delete_contact($contact_id);
	}
	view_record();
	?>
	<html>
	<script type="text/javascript">
	parent.parent.curbtn = null;
	parent.parent.firstbtn = true;
	parent.parent.btnhistory_cnt = 0;
	eval('parent.parent.left' + parent.parent.winid + '.location.reload();');
	</script>	
	</html>
	<?
}
else
{
	// print the js to view the record
	view_record($contact_id);
}

page_close();

?>