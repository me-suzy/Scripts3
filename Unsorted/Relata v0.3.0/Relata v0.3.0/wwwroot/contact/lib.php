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
This file contains functions that are used throughout the contact manager pages
*/

// get all the extra fields from the DB associated with $user_id and print them to the template
// if mode = "add" ... just print the blank extra fields to the template
function print_xtra_fields()
{
	global $db, $template;
	global $user_id, $contact_id;
	global $contact_xfield;
	
	// get a list of all the extra field definitions
	$j = 0;
	$db->query("SELECT xfield_id, xfield_name FROM extra_field WHERE user_id='$user_id'");
	while($db->next_record())
	{
		$xfield_id[$j] 		= $db->f('xfield_id');
		$xfield_name[$j] 	= $db->f('xfield_name');
		++$j;
	}

	// if there is at least one extra field
	if($db->num_rows())
	{

		// build the input / textarea boxes and fill them w/ info
		for($i = 0; $i < $j; ++$i)
		{
			// print the heading
			$extra_fields .= "<tr><td align=right class=\"form\"><span class=\"field-titles\">$xfield_name[$i]:</span></td><td>&nbsp;</td><td><input type=\"text\" name=\"xfield_$xfield_id[$i]\" size=\"30\"></td></tr>";
		}
	}
	
	$template->set_var("EXTRA_FIELDS", $extra_fields);
}

// deletes a account and all dependencies
function delete_account($account_id)
{
	global $db, $user_id, $contact_account, $account;
	
	// delete any dependencies in contact_account
	$db->query("DELETE FROM contact_account WHERE account_id='$account_id'");
	// delete the actual account
	$db->query("DELETE FROM account where account_id='$account_id' AND user_id='$user_id'");
}

// deletes a contact and all dependencies
function delete_contact($contact_id)
{
	global $db, $user_id, $contact_xfield, $contact_account, $contact_group, $contact;
	
	// delete any dependencies in contact_xfield table first
	$db->query("DELETE FROM contact_xfield WHERE contact_id='$contact_id'");
	// delete any dependencies in contact_account
	$db->query("DELETE FROM contact_account WHERE contact_id='$contact_id'");
	// delete any dependencies in contact_group
	$db->query("DELETE FROM contact_group WHERE contact_id='$contact_id'");
	// delete the actual contact
	$db->query("DELETE FROM contact where contact_id='$contact_id' AND user_id='$user_id'");
}

// view a record... Doesn't use templates. All JS has been "stripped" for speed purposes
// this loads up in a hidden frame on the contact manager and uses JS to change all of the fields
function view_record($contact_id = "default")
{
	global $db, $user_id, $contact, $contact_group, $contact_xfield, $session_id, $_PHPLIB;
	
	// if there is a specified $contact_id else just use the first record in the db
	if($contact_id == "default" || $contact_id == "")
	{
		$db->query("SELECT * FROM contact WHERE user_id='$user_id' and isdeleted='false' ORDER BY lname ASC LIMIT 1");
	}
	else
	{
		$db->query("SELECT * FROM contact WHERE contact_id='$contact_id' AND user_id='$user_id' and isdeleted='false' LIMIT 1");
	}
	$db->next_record();
	
	$contact_id = $db->f('contact_id');
	
	// if there is at least one record
	if($db->num_rows())
	{
		// the JS that fills in all of the fields
		// it has been "compressed" and stripped of
		// comments, so new records will load faster
		?><html>
		<meta http-equiv="Expires" content="Fri, Jun 12 1981 08:20:00 GMT">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache">
		<script type="text/javascript"><?
		
		
		// fill in all of the fields in a hidden frame using JS
		if($db->f('prefix'))
		{
			$prefix = $db->f('prefix');
		}
		else
		{
			$prefix = 0;
		}
		
		if($db->f('email_type'))
		{
			$email_type = $db->f('email_type');
		}
		else
		{
			$email_type = 0;
		}
		
		if($db->f('is_prospect'))
		{
			$is_prospect = $db->f('is_prospect');
		}
		else
		{
			$is_prospect = 0;
		}
		
		?>
		f=parent.document.contacts_form
		f.contact_id.value="<? echo $db->f('contact_id'); ?>"
		f.fname.value="<? echo jsformat($db->f('fname')); ?>"
		f.mname.value="<? echo jsformat($db->f('mname')); ?>"
		f.lname.value="<? echo jsformat($db->f('lname')); ?>"
		f.title.value="<? echo jsformat($db->f('title')); ?>"
		f.company.value="<? echo jsformat($db->f('company')); ?>"
		f.hm_street.value="<? echo jsformat($db->f('hm_address')); ?>"
		f.hm_city.value="<? echo jsformat($db->f('hm_city')); ?>"
		f.hm_state.value="<? echo jsformat($db->f('hm_state')); ?>"
		f.hm_zip.value="<? echo jsformat($db->f('hm_zip')); ?>"
		f.hm_country.value="<? echo jsformat($db->f('hm_country')); ?>"
		f.bus_street.value="<? echo jsformat($db->f('bus_address')); ?>"
		f.bus_city.value="<? echo jsformat($db->f('bus_city')); ?>"
		f.bus_state.value="<? echo jsformat($db->f('bus_state')); ?>"
		f.bus_zip.value="<? echo jsformat($db->f('bus_zip')); ?>"
		f.bus_country.value="<? echo jsformat($db->f('bus_country')); ?>"
		f.alt_street.value="<? echo jsformat($db->f('alt_address')); ?>"
		f.alt_city.value="<? echo jsformat($db->f('alt_city')); ?>"
		f.alt_state.value="<? echo jsformat($db->f('alt_state')); ?>"
		f.alt_zip.value="<? echo jsformat($db->f('alt_zip')); ?>"
		f.alt_country.value="<? echo jsformat($db->f('alt_country')); ?>"
		f.website1.value="<? echo jsformat($db->f('website1')); ?>"
		f.website2.value="<? echo jsformat($db->f('website2')); ?>"
		f.palm_custfield1.value="<? echo jsformat($db->f('palm_custfield1')); ?>"
		f.palm_custfield2.value="<? echo jsformat($db->f('palm_custfield2')); ?>"
		f.palm_custfield3.value="<? echo jsformat($db->f('palm_custfield3')); ?>"
		f.palm_custfield4.value="<? echo jsformat($db->f('palm_custfield4')); ?>"
		f.palm_notes.value="<? echo textarea_format($db->f('palm_notes')); ?>"
		f.palm_dphone.value="<? echo textarea_format($db->f('palm_dphone')); ?>"
		f.contact_val1.value="<? echo jsformat($db->f('contact_val1')); ?>"
		f.contact_val2.value="<? echo jsformat($db->f('contact_val2')); ?>"
		f.contact_val3.value="<? echo jsformat($db->f('contact_val3')); ?>"
		f.contact_val4.value="<? echo jsformat($db->f('contact_val4')); ?>"
		f.contact_val5.value="<? echo jsformat($db->f('contact_val5')); ?>"	
		if(parent.ph_comm_win.loaded){
		p=parent.ph_comm_win.document.ph_comm_form
		p.ph_comm_textarea.value="<? echo  textarea_format($db->f('user_notes'));?>";
		p.contact_id.value="<? $db->p('contact_id'); ?>"}
		prefix="<? echo $prefix; ?>";
		email_type="<? echo $email_type; ?>";
		is_prospect="<? echo $is_prospect; ?>";
		contact_lbl1=<? echo $db->f('contact_lbl1'); ?>;
		contact_lbl2=<? echo $db->f('contact_lbl2'); ?>;
		contact_lbl3=<? echo $db->f('contact_lbl3'); ?>;
		contact_lbl4=<? echo $db->f('contact_lbl4'); ?>;
		contact_lbl5=<? echo $db->f('contact_lbl5'); ?>;
	<?
		
		// reload left frame
		if($reload)
		{
			echo "eval('parent.parent.left' + parent.parent.winid + '.location.reload();');";
		}
		
		// uncheck all groups
		$db->query("SELECT group_id FROM groups WHERE user_id='$user_id'");
		while($db->next_record())
		{
			// build a list of all the group id's
			$group_list[] = $db->f('group_id');
		}
		
		// loop through all possible groups
		for($i = 0; $i < count($group_list); $i++)
		{
			// find out which groups this user belongs to
			$db->query("SELECT group_id FROM contact_group WHERE contact_id='$contact_id' AND group_id='$group_list[$i]'");
			$db->next_record();
			
			// if the contact is associated with this group
			if($db->num_rows())
			{
				// make a JS array of all the groups the user belongs to
				?> f.group_<? echo $group_list[$i]; ?>.checked=true;<?
			}
			else
			{
				?>f.group_<? echo $group_list[$i]; ?>.checked=false;<?
			}
		}
	
		// get a list of all the extra field definitions
		$db->query("SELECT xfield_id FROM extra_field WHERE user_id='$user_id'");
		while($db->next_record())
		{
			$xfield_id[] = $db->f('xfield_id');
		}
	
		// get the values of the extra fields for this specific contact
		$cnt = count($xfield_id);
		
		for($i = 0; $i < $cnt; ++$i)
		{
			$db->query("SELECT xfield_value FROM contact_xfield WHERE xfield_id='$xfield_id[$i]' AND contact_id='$contact_id'");
			$db->next_record();
			
			?>f.xfield_<? echo $xfield_id[$i]; ?>.value="<? echo $db->f('xfield_value'); ?>";<?
		}
		
		?>
		parent.changeaddr()
		</script><script type="text/javascript" src="<? echo $_PHPLIB['webdir']; ?>contact/contact_loader.js"></script>
	<?
	}
	else
	{
		// there are no records
		?><script type="text/javascript">parent.location.href="add.php?reload=true&session_id=<? echo $session_id; ?>";</script><?
	}
	?>	
	</html><?
}

?>