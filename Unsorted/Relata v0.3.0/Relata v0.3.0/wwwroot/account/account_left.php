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

// force browser to not cache page
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

// start a new template
$template = start_template();

// connect to DB
$db = new relata_db;

// get the userid
$user_id = $auth->auth["uid"];

// build the buttons for the accounts
$query = "SELECT account_id, account_name FROM $account WHERE user_id='$user_id' ORDER by account_name ASC";
$db->query($query);
while($db->next_record())
{
	$btn_label = jsformat($db->f('account_name'));
	
	$count_label_chars = strlen(trim($btn_label));
    if ($count_label_chars > 18)
		{
			$btn_label = substr($btn_label, 0, 15);
			$btn_label .= "...";
		}
	$accounts_list .= "btns[".$db->f('account_id')."] = addbtn(\"". $btn_label ."\",".$db->f('account_id').");";
}

// set template variables
$template->set_var(array(
	"ROWS"		=> 	$accounts_list,
	"SESSION_ID"	=>	$session_id
	));

// set template files
$template->set_file(array(
	"account_left"	=>	"account_list_left.htm"
	));

// print the templates
$template->parse("output","account_left");
$template->p("output");

// page close functions
page_close();

?>