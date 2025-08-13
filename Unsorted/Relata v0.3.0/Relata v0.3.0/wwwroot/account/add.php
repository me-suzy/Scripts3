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
	"auth" => "relata_challenge_Auth"
	));

header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

// start a new template
$template = start_template();

$db = new relata_db;

$template->set_file("main","account_main.htm");

$user_id = $auth->auth["uid"];

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
	// create the account
	$timedate_create = today() . " " . now();
	$db->query("INSERT INTO $account 
	(user_id,account_name,account_address,account_city,account_state,account_zip,account_country,account_fax,account_phone,account_website,
	timedate_create,account_status,account_desc)
	VALUES ('$user_id','$account_name','$account_address','$account_city','$account_state','$account_zip','$account_country','$account_fax',
	'$account_phone','$account_website','$timedate_create','$account_status','$account_desc')");

	// link the users (if any) to the account
	$account_id = $db->currid("account_seq");
	
	$cnt = count($account_contacts);
	for($i = 0;$i < $cnt; $i++)
	{
		$db->query("INSERT INTO $contact_account (contact_id,account_id) VALUES ('$account_contacts[$i]','$account_id')");
	}
	
		echo '<script type="text/javascript">';
		// reload the left frame
		echo "eval('parent.parent.left' + parent.parent.winid + '.location.reload();');";
		
		// select the just added button in left frame if there is one to select
		if(!$no_records)
		{
			echo "parent.parent.curbtn = $account_id;";
		}
		else
		{
			echo "parent.location.reload();";
		}
			echo "parent.location.href=\"account_main.php?session_id=$session_id&account_id=$account_id\";";
		    echo "</script>";

}
}

$template->set_var("FORM_URL", $_PHPLIB['webdir'] . "account/add.php?session_id=$session_id");

$add_contact = 1;

// set the default values for 'add' mode
$template->set_var(array(
	"TARGET"		=>	'target="loader"',
	"LOADER"		=>	'<iframe src="' . $_PHPLIB['webdir'] . 'account/account_loader.php?session_id=' . $session_id . '&account_id='.$account_id.'&add_contact='.$add_contact.'" name="loader" width="0" height="0"></iframe>',
	"ACTION2"		=>	"reset",
	"ACTION2_LABEL"	=>	"Cancel",
	"FORM_URL"		=>	$_PHPLIB['webdir'] . "account/add.php?session_id=$session_id",		
	"TEMPLATE_MODE"	=>	"Add"
	));

$template->parse("output","main");
$template->p("output");

page_close();

?>