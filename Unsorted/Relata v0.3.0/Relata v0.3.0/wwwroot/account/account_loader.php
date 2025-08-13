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

// force browser to not cache page
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

$user_id = $auth->auth["uid"];

$db = new relata_db;

echo $action;

if($action == "   OK   ")
{

$is_account_contact = count($account_contacts);
if($is_account_contact == 0)
{
?>
<script type="text/javascript">
alert("You must have at least one contact associated with this account");
</script>
<?
}
else
{
	$cnt = count($account_contacts);
	
	//delete all contacts associated with this account
	$db->query("DELETE FROM $contact_account WHERE account_id='$account_id'");
	
	//insert contacts chosen for this account
	for($i = 0;$i < $cnt; $i++)
	{
			$db->query("INSERT INTO $contact_account (contact_id,account_id) VALUES ('$account_contacts[$i]','$account_id')");
	}
	
	$db->query("UPDATE $account SET
		account_name='$account_name',
		account_address='$account_address',
		account_city='$account_city',
		account_state='$account_state',
		account_zip='$account_zip',
		account_country='$account_country',
		account_fax='$account_fax',
		account_phone='$account_phone',
		account_website='$account_website',
		account_status='$account_status',
		account_desc='$account_desc'
		WHERE account_id='$account_id' AND user_id='$user_id'
	");
	}
}
else if($action == " Delete ")
{
	// delete the account
	$db->query("DELETE FROM $account WHERE account_id='$account_id'");
	
	// delete all the account associations
	$db->query("DELETE FROM $contact_account WHERE account_id='$account_id'");
	
	unset($account_id);
}


if($account_id)
{
	$db->query("SELECT * FROM $account WHERE account_id='$account_id' AND user_id='$user_id'");
}
else
{
	$db->query("SELECT * FROM $account WHERE user_id='$user_id' ORDER by account_name ASC LIMIT 1");
}

// if there is at least one account
if($db->num_rows())
{
	$db->next_record();

	$account_id = $db->f('account_id');

	// the following doesn't use templates, has been compressed and stripped
	// of commments for speed purposes
	?><html>
	<script type="text/javascript">f=parent.document.accounts_form;</script>
	<? /*<script type="text/javascript" src=" echo $_PHPLIB['webdir'] account/account_loader.js"></script>*/ ?>
	<script type="text/javascript">
	<?
	// check to see if the laoder is being loaded for the add page or not
	if($add_contact != 1)
    {
		?>
		f.account_id.value="<? echo $account_id; ?>"
		f.account_name.value="<? echo jsformat($db->f('account_name')); ?>"
		f.account_address.value="<? echo jsformat($db->f('account_address')); ?>"
		f.account_city.value="<? echo jsformat($db->f('account_city')); ?>"
		f.account_state.value="<? echo jsformat($db->f('account_state')); ?>"
		f.account_zip.value="<? echo jsformat($db->f('account_zip')); ?>"
		f.account_country.value="<? echo jsformat($db->f('account_country')); ?>"
		f.account_website.value="<? echo jsformat($db->f('account_website')); ?>"
		f.account_desc.value="<? echo textarea_format($db->f('account_desc')); ?>"
		f.account_status.selectedIndex = <? echo $db->f('account_status');?>;
		parent.clear_account_contacts();
		<?
		// get a list of all the contacts associated with this account
		$db->query("SELECT contact_id FROM $contact_account WHERE account_id='$account_id'");
		while($db->next_record())
		{
			$account_contacts_id[] = $db->f('contact_id');
		}

		// get the names associated with the contacts
		$cnt = count($account_contacts_id);
		for($i = 0; $i < $cnt; $i++)
		{
			$db->query("SELECT fname,lname FROM $contact WHERE contact_id='$account_contacts_id[$i]'");
			$db->next_record();
	
			$fname = $db->f('fname');
			$lname = $db->f('lname');
	
			echo "parent.new_account_contact('".jsformat($lname).", ".jsformat($fname)."','$account_contacts_id[$i]');";
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

		// find out which contacts aren't already associated with an account
		$cnt = count($all_contacts_id);
		for($i = 0; $i < $cnt; $i++)
		{
			$db->query("SELECT contact_id FROM $contact_account WHERE contact_id='$all_contacts_id[$i]'");
			if(!$db->num_rows())
			{
				echo "parent.new_avail_contact('".jsformat($all_contacts_name[$i])."','".jsformat($all_contacts_id[$i])."');";
			}
		}

		echo "</script>";
	}

	// if the page is being loaded for the add page, do not load all the account variables, build the appropriate contact list
	elseif($add_contact == 1)
	{
		?>
		parent.clear_account_contacts();
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
			$db->query("SELECT contact_id FROM $contact_account WHERE contact_id='$all_contacts_id[$i]'");
			if(!$db->num_rows())
			{
				echo "parent.new_avail_contact('".jsformat($all_contacts_name[$i])."','".jsformat($all_contacts_id[$i])."');";
			}
		}
		echo "</script>";
	}
	
	if($action == "   OK   " || $action == " Delete ")
	{
		echo '<script type="text/javascript">';
		
		// reload the left frame
		echo "eval('parent.parent.left' + parent.parent.winid + '.location.reload();');";
		
		// select the just added button in left frame if there is one to select
		if(!$no_records)
		{
			echo "parent.parent.curbtn = $account_id";
		}
		else
		{
			echo "parent.location.reload();";
		}
		
		echo "</script>";
	}
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
	parent.clear_account_contacts();
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
		$db->query("SELECT contact_id FROM $contact_account WHERE contact_id='$all_contacts_id[$i]'");
		if(!$db->num_rows())
		{
			echo "parent.new_avail_contact('".jsformat($all_contacts_name[$i])."','".jsformat($all_contacts_id[$i])."');";
		}
	}
	?>
	</script>
	<?
}

echo "</html>";

page_close();
?>