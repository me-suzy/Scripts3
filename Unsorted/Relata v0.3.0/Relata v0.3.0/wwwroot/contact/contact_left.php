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

// set template files
$template->set_file(array(
	"contact_list"	=>	"contact_list_left.htm"
	));

// get user id
$user_id = $auth->auth["uid"];

// build all of the buttons
if($order_by == "last_first_company_email")
	{
		$db->query("SELECT contact_id,fname,lname,company,contact_val1,contact_val2,contact_val3,contact_val4,contact_val5,website1,website2 FROM $contact WHERE user_id='$user_id' and isdeleted='false' ORDER by lname,fname,company,website1,website2,contact_lbl5,contact_lbl4,contact_lbl3,contact_lbl2,contact_lbl1 ASC");
	}
	elseif($order_by == "company_last_first_email")
	{
		$db->query("SELECT contact_id,fname,lname,company,contact_val1,contact_val2,contact_val3,contact_val4,contact_val5,website1,website2 FROM $contact WHERE user_id='$user_id' and isdeleted='false' ORDER by company,lname,fname,website1,website2,contact_lbl5,contact_lbl4,contact_lbl3,contact_lbl2,contact_lbl1 ASC");
	}
	elseif($order_by == "email_last_first_company")
	{
		$db->query("SELECT contact_id,fname,lname,company,contact_val1,contact_val2,contact_val3,contact_val4,contact_val5,website1,website2 FROM $contact WHERE user_id='$user_id' and isdeleted='false' ORDER by lname,fname,company,website1,website2,contact_lbl5,contact_lbl4,contact_lbl3,contact_lbl2,contact_lbl1 ASC");
	}
	else
	{
		$db->query("SELECT contact_id,fname,lname,company,contact_val1,contact_val2,contact_val3,contact_val4,contact_val5,website1,website2 FROM $contact WHERE user_id='$user_id' and isdeleted='false' ORDER by lname,fname,company,website1,website2,contact_lbl5,contact_lbl4,contact_lbl3,contact_lbl2,contact_lbl1 ASC");
	}

while($db->next_record())
{
	// format the data for the button label
	if($db->f('fname') && $db->f('lname'))
	{
		$btn_label = jsformat($db->f('lname')) .", ". jsformat($db->f('fname'));
	}
	else if($db->f('lname'))
	{
		$btn_label = jsformat($db->f('lname'));
	}
	else if($db->f('fname'))
	{
		$btn_label = jsformat($db->f('fname'));
	}
	else if($db->f('company'))
	{
		$btn_label = jsformat($db->f('company'));
	}
	else if($db->f('contact_val1'))
	{
		$btn_label = jsformat($db->f('contact_val1'));
	}
	else if($db->f('contact_val2'))
	{
		$btn_label = jsformat($db->f('contact_val2'));
	}
	else if($db->f('contact_val3'))
	{
		$btn_label = jsformat($db->f('contact_val3'));
	}
	else if($db->f('contact_val4'))
	{
		$btn_label = jsformat($db->f('contact_val4'));
	}
	else if($db->f('contact_val5'))
	{
		$btn_label = jsformat($db->f('contact_val5'));
	}
	else if($db->f('website1'))
	{
		$btn_label = jsformat($db->f('website1'));
	}
	else if($db->f('website2'))
	{
		$btn_label = jsformat($db->f('website2'));
	}
	
	$count_label_chars = strlen(trim($btn_label));
    if ($count_label_chars > 18)
		{
			$btn_label = substr($btn_label, 0, 15);
			$btn_label .= "...";
		}
	$contacts_list .= "btns[".$db->f('contact_id')."] = addbtn(\"". $btn_label ."\",".$db->f('contact_id').");";
}

$order_by_drop_list = "<option value=\"last_first_company_email\"";
if($order_by == "last_first_company_email")
	{
		$order_by_drop_list .= "selected";
	}

$order_by_drop_list .= ">Last, First, Company, Email</option>
<option value=\"company_last_first_email\"";

if($order_by == "company_last_first_email")
	{
		$order_by_drop_list .= "selected";
	}

$order_by_drop_list .= ">Company, Last, First, Email</option>";
//<option value=\"email_last_first_company\"";

//if($order_by == "email_last_first_company")
	//{
		//$order_by_drop_list .= "selected";
	//}

//$order_by_drop_list .= ">Email, Last, First, Company</option>";



// set template variables
$template->set_var(array(
	"ROWS"			=> 	$contacts_list,
	"ORDER_BY_DROP_LIST"	=>	$order_by_drop_list,
	"SESSION_ID"	=>	$session_id
	));

// print the template
$template->parse("output","contact_list");
$template->p("output");

// page close functions
page_close();


?>